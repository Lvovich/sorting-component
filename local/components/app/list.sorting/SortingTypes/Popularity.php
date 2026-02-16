<?php
declare(strict_types=1);

namespace Test\Components\Listsorting\SortingTypes;

use Bitrix\Main\Localization\Loc;
use Test\Components\Listsorting\AbstractSortingType;

/* @noinspection PhpUnused */
class Popularity extends AbstractSortingType
{
    public function __construct()
    {
        parent::__construct([
            'up'   => 'Сначала популярные',
            'down' => 'Сначала непопулярные',
        ]);
    }

    public function getCode(): string
    {
        return 'popularity';
    }

    public function getTitle(): string
    {
        return Loc::getMessage('APP_SORTING_POPULARITY_TYPE_TITLE') ?? '';
    }

    public function getSortFields(): array
    {
        return [
            'FIELD' => 'show_counter',
            'ORDER' => $this->selectedOrder==='up' ? 'desc,nulls' : 'nulls,asc',
        ];
    }
}
