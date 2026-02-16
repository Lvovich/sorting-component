<?php
declare(strict_types=1);

namespace Test\Components\Listsorting\SortingTypes;

use Bitrix\Main\Localization\Loc;
use Test\Components\Listsorting\AbstractSortingType;

/* @noinspection PhpUnused */
class Name extends AbstractSortingType
{
    public function __construct()
    {
        parent::__construct([
            'a-z' => 'Название по возрастанию алфавита',
            'z-a' => 'Название по убыванию алфавита',
        ]);
    }

    public function getCode(): string
    {
        return 'name';
    }

    public function getTitle(): string
    {
        return Loc::getMessage('APP_SORTING_NAME_TYPE_TITLE') ?? '';
    }

    public function getSortFields(): array
    {
        return [
            'FIELD' => 'name',
            'ORDER' => $this->selectedOrder==='a-z' ? 'asc' : 'desc',
        ];
    }
}
