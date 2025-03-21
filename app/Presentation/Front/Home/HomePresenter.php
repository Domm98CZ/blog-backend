<?php declare(strict_types=1);
namespace App\Presentation\Front\Home;

use Nette\Application\UI\Presenter;
use Nette\Database\Connection;
use Nette\DI\Attributes\Inject;

final class HomePresenter extends Presenter
{
    #[Inject]
    public Connection $database;

    public function actionDefault(): void
    {
        // @TODO: remove this test after db connection start working
        bdump($this->database->query('select current_timestamp'));
    }

    public function renderDefault(): void
    {

    }
}
