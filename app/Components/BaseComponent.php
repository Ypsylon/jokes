<?php declare(strict_types=1);

namespace App\Components;

use Nette\Application\UI\Control;

abstract class BaseComponent extends Control
{
    public abstract function render(): void;

    protected function baseRedirect(): void
    {
        if ($this->getPresenterIfExists()?->isAjax() ?? false) {
            $this->redrawControl('this');
        } else {
            $this->getPresenterIfExists()?->redirect('this');
        }
    }
}