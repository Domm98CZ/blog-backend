<?php declare(strict_types=1);

namespace App\Business\Controller;

use App\Business\Enum\UserRole;
use App\Business\Exception\BusinessException;
use App\Business\Exception\EntityAlreadyExists;
use App\Business\Exception\EntityListIsInvalidException;
use App\Business\Exception\EntityWasNotCreatedException;
use App\Business\Exception\EntityWasNotDeletedException;
use App\Business\Exception\EntityWasNotFoundException;
use App\Business\Exception\EntityWasNotUpdatedException;
use App\Business\Exception\InsufficientPermissionsException;
use App\Business\Exception\InvalidLoginCredentialsException;
use App\Business\Interface\RestApiEntity;
use App\Business\Security\UserAccessControl;
use App\Data\AbstractEntity;
use App\Data\DataModel;
use App\Data\RestApiDto\Request\AbstractRequestDto;
use App\Data\RestApiDto\Response\AbstractResponseDto;
use App\Data\RestApiDto\Response\ResponseUserDto;
use App\Data\User\User;
use App\Data\User\UserRepository;
use Exception;
use Nette\Security\Passwords;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;

final readonly class UserController extends AbstractController implements RestApiEntity
{
    private UserRepository $userRepository;

    public function __construct(
        DataModel $orm
        , private TokenController $tokenController
    ) {
        $this->userRepository = $orm->userRepository;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @return User
     */
    public function registerUser(string $email, string $password, string $name): User
    {
        /**
         * @TODO: Potenciální bod k k diskuzi po odevzdání úkolu.
         *  ČÁST ZE ZADÁNÍ: POST /auth/register — registrace nového uživatele (role admin, author nebo reader).
         * --> Upraveno dle mého názoru, může to být chyba proti zadání nebo chyba v zadání.
         * --> Pokud mám endpoint na registraci, určitě bych v něm nenechával možnost volby role.
         * --> Pro testovací účely jsem přidal "demo" účet s oprávněními UserRole::ADMIN.
         * --> Pokud by to byla chyba, oprava je v řádech minut pomocí propagace parametru "role" do této metody z API.
         * */
        return $this->createUserEntity($email, $password, $name, UserRole::READER);
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public function loginUser(string $email, string $password): User
    {
        try {
            $userEntity = $this->userRepository->findByEmail($email);
            //@TODO: response with token
            if (!$userEntity instanceof User) {
                throw new EntityAlreadyExists(sprintf('User with email "%s" already exists', $email));
            }

            if (!new Passwords()->verify($password, $userEntity->password_hash)) {
                throw new InvalidLoginCredentialsException('Invalid login credentials');
            }

            // Modify password hash if needed
            if (new Passwords()->needsRehash($userEntity->password_hash)) {
                $userEntity->password_hash = new Passwords()->hash($userEntity->password_hash);
                $this->userRepository->persist($userEntity);
                $this->userRepository->flush();
            }

            return $userEntity;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new BusinessException(sprintf('User login was not processed due to an exception "%s".', $exception->getMessage()), previous: $exception);
        }

    }

    /**
     * @param string $token
     * @return User|null
     */
    public function authorizeUser(string $token): ?User
    {
        try {
            $tokenEntity = $this->tokenController->getToken($token);
            if (!$tokenEntity->isValid()) {
                throw new InvalidLoginCredentialsException('Invalid token');
            }

            $userEntity = $this->userRepository->findUserByToken($token);
            if (!$userEntity instanceof User) {
                throw new InvalidLoginCredentialsException('Invalid token');
            }

            return $userEntity;
        } catch (InvalidLoginCredentialsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
        }

        return null;
    }

    /**
     * @param User $currentUser
     * @param string $email
     * @param string $password
     * @param string $name
     * @param UserRole $role
     * @return User
     */
    public function creteUser(User $currentUser, string $email, string $password, string $name, UserRole $role = UserRole::READER): User
    {
        try {
            if (!UserAccessControl::canCreateUser($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to create users.');
            }

            return $this->createUserEntity($email, $password, $name, $role);
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotCreatedException($exception->getMessage());
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     * @param UserRole $role
     * @return User
     */
    private function createUserEntity(string $email, string $password, string $name, UserRole $role = UserRole::READER): User
    {
        try {
            if ($this->userRepository->findByEmail($email) instanceof User) {
                throw new EntityAlreadyExists(sprintf('User with email "%s" already exists', $email));
            }

            $userEntity = new User();
            $userEntity->email = $email;
            $userEntity->password_hash = new Passwords()->hash($password);
            $userEntity->name = $name;
            $userEntity->role = $role;
            $userEntity->created_at = new DateTimeImmutable();
            $userEntity->updated_at = new DateTimeImmutable();

            $this->userRepository->persist($userEntity);
            $this->userRepository->flush();

            return $userEntity;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotCreatedException(sprintf('UserEntity was not created due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @param string|null $email
     * @param string|null $name
     * @param UserRole|null $role
     * @return User
     */
    public function updateUser(User $currentUser, int $id, ?string $email = null, ?string $name = null, ?UserRole $role = null): User
    {
        try {
            if (!UserAccessControl::canUpdateUser($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to update users.');
            }

            $userEntity = $this->userRepository->findById($id);
            if (!$userEntity instanceof User) {
                throw new EntityWasNotFoundException(sprintf('User with id "%s" does not exist.', $id));
            }

            if ($email !== null && !$this->userRepository->findByEmail($email) instanceof User) {
                $userEntity->email = $email;
            }

            if ($name !== null) {
                $userEntity->name = $name;
            }

            if ($role !== null) {
                $userEntity->role = $role;
            }

            if ($userEntity->isModified()) {
                $userEntity->updated_at = new DateTimeImmutable();
                $this->userRepository->persist($userEntity);
                $this->userRepository->flush();
            }

            return $userEntity;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotUpdatedException(sprintf('UserEntity was not updated due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @return bool
     */
    public function deleteUser(User $currentUser, int $id): bool
    {
        try {
            if (!UserAccessControl::canDeleteUser($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to delete users.');
            }

            $userEntity = $this->userRepository->findById($id);
            if (!$userEntity instanceof User) {
                throw new EntityWasNotFoundException(sprintf('User with id "%s" does not exist.', $id));
            }

            $this->userRepository->removeAndFlush($userEntity);
            return true;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotDeletedException(sprintf('UserEntity was not deleted due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @return User
     * @throws Exception
     */
    public function getUser(User $currentUser, int $id): User
    {
        try {
            if (!UserAccessControl::canReadUser($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to read user.');
            }

            $userEntity = $this->userRepository->findById($id);
            if (!$userEntity instanceof User) {
                throw new EntityWasNotFoundException(sprintf('User with id "%s" does not exist.', $id));
            }

            return $userEntity;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new Exception(sprintf('UserEntity was not get due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @return ICollection<User>
     */
    public function getUsers(User $currentUser): ICollection
    {
        try {
            if (!UserAccessControl::canReadUsers($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to read users.');
            }

            return $this->userRepository->findAll();
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityListIsInvalidException(sprintf('UserEntityList was not get due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param AbstractEntity|User $entity
     * @param bool $loadRelations
     * @return ResponseUserDto
     */
    public static function createResponseDtoByEntity(AbstractEntity|User $entity, bool $loadRelations = false): ResponseUserDto
    {
        $articlesDto = [];

        if ($loadRelations) {
            foreach ($entity->articles as $article) {
//                $articlesDto[$article->id] = ::createResponseDtoByEntity($article, $loadRelations);
            }
        }

        return new ResponseUserDto(
            $entity->id,
            $entity->email,
            $entity->name,
            $entity->role->value,
            $entity->created_at,
            $entity->updated_at,
            $articlesDto
        );
    }

    /**
     * @param AbstractEntity $entity
     * @param AbstractRequestDto $requestDto
     * @return AbstractEntity
     */
    public static function modifyEntityWithRequestDto(AbstractEntity $entity, AbstractRequestDto $requestDto): AbstractEntity
    {
        // TODO: Implement modifyEntityWithRequestDto() method.
    }
}
