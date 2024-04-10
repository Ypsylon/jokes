<?php declare(strict_types = 1);

namespace App\Components\Meme;

use App\Helpers\DataHelper;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Control;

class MemeComponent extends Control
{
    #[Persistent]
    public bool $isShowingImage = false;

    public function __construct(
        protected DataHelper $dataHelper,
    )
    {
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/templates/default.latte');

        $joke = $this->isShowingImage ? $this->dataHelper->getRandomJoke() : null;

        $this->template->joke = $joke;
        $this->template->render();
    }

    protected function baseRedirect(): void
    {
        if ($this->getPresenterIfExists()?->isAjax() ?? false) {
            $this->redrawControl('this');
        } else {
            $this->getPresenterIfExists()?->redirect('this');
        }
    }

    public function handleGenerate(): void
    {
        $this->isShowingImage = true;

        $this->baseRedirect();
    }

    public function handleHide(): void
    {
        $this->isShowingImage = false;

        $this->baseRedirect();
    }
}