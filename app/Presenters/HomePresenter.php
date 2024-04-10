<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\Meme\IMemeComponentFactory;
use App\Components\Meme\MemeComponent;
use App\Components\Table\ITableComponentFactory;
use App\Components\Table\TableComponent;
use App\Helpers\DataHelper;
use Nette\Application\UI\Presenter;

final class HomePresenter extends Presenter
{
    public function __construct(
        protected DataHelper $dataHelper,
        protected IMemeComponentFactory $memeComponentFactory,
        protected ITableComponentFactory $tableComponentFactory,
    )
    {
        parent::__construct();
    }

    public function renderDefault(): void
    {
    }

    protected function createComponentMeme(): MemeComponent
    {
        return $this->memeComponentFactory->create();
    }

    protected function createComponentInitialsTable(): TableComponent
    {
        return $this->tableComponentFactory->create(fn() => $this->dataHelper->getSameInitialsData());
    }

}
