<?php declare(strict_types=1);

namespace App\Components\Table;

interface ITableComponentFactory
{
    public function create(\Closure $dataLoader): TableComponent;
}