<?php declare(strict_types=1);
namespace App\Presentation\Front\Home;

use App\Data\DataModel;
use App\Data\User\User;
use Nette\Application\UI\Presenter;
use Nette\Database\Connection;
use Nette\DI\Attributes\Inject;

final class HomePresenter extends Presenter
{
    #[Inject]
    public Connection $database;

    #[Inject]
    public DataModel $orm;

    public function actionDefault(): void
    {
        // @TODO: remove this test after db connection start working
        bdump($this->database->query('select current_timestamp'));
        bdump($this->database->query('select * from users')->fetchAll());

        /** @var User $u */
        $u = $this->orm->userRepository->getById(1);
        bdump($u->articles->toCollection()->fetchAll());
        bdump($u->tokens->toCollection()->fetchAll());
        bdump($u);
        bdump($u->role);
    }

    public function renderDefault(): void
    {

    }
}
