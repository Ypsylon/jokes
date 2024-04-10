<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\Meme\IMemeComponentFactory;
use App\Components\Meme\MemeComponent;
use Nette\Application\UI\Presenter;

final class HomePresenter extends Presenter
{
    public function __construct(
        protected IMemeComponentFactory $memeComponentFactory,
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

}
