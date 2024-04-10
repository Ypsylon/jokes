<?php declare(strict_types = 1);

namespace App\Components\Meme;

interface IMemeComponentFactory
{
    public function create(): MemeComponent;
}