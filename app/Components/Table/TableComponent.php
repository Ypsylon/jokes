<?php declare(strict_types=1);

namespace App\Components\Table;

use App\Components\BaseComponent;
use App\Helpers\DataHelper;
use Nette\Application\Attributes\Persistent;

class TableComponent extends BaseComponent
{
    #[Persistent]
    public bool $isShowingData = false;

    public function __construct(
        protected DataHelper $dataHelper,
        protected \Closure $dataLoader,
    )
    {
    }

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/templates/default.latte');
        $this->template->isShowingData = $this->isShowingData;
        $this->template->data = null;

        if ($this->isShowingData) {
            $func = $this->dataLoader;
            $this->template->data = $func();
        }

        $this->template->render();
    }

    public function handleShow(): void
    {
        $this->isShowingData = true;

        $this->baseRedirect();
    }

    public function handleHide(): void
    {
        $this->isShowingData = false;

        $this->baseRedirect();
    }
}