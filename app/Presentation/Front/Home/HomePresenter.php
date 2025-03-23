<?php declare(strict_types=1);
namespace App\Presentation\Front\Home;

use App\Business\Controller\TokenController;
use App\Business\Controller\UserController;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;

final class HomePresenter extends Presenter
{
    #[Inject]
    public UserController $userController;

    #[Inject]
    public TokenController $tokenController;

    public function actionDefault(): void
    {
        //@TODO: remove this debug playground before deadline
//        $user = $this->userController->registerUser('domm98cz@gmail.com', 'ahoj123', 'Dominik Procházka');
        $user = $this->userController->loginUser('domm98cz@gmail.com', 'ahoj123');
        bdump($user);

//        $token = $this->tokenController->createToken($user);
//        bdump($token);
//
//        $xxx = $this->userController->authorizeUser('ZG9tbTk4Y3pAZ21haWwuY29tOmFob2oxMjM');
//        bdump($xxx);
    }

    public function renderDefault(): void
    {

    }
}
