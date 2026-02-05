<?php
declare(strict_types=1);

namespace Test\Components\Listsorting\SortingTypes;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Test\Components\Listsorting\AbstractSortingType;
use Bitrix\Catalog\GroupTable;
use Exception;

/* @noinspection PhpUnused */
class Price extends AbstractSortingType
{
    private int $priceType;
    private array $catalogGroups;

    public function __construct(array $params)
    {
        parent::__construct([
            'up'   => 'Сначала дешевле',
            'down' => 'Сначала дороже',
        ]);

        $this->catalogGroups = $this->getCatalogGroups();

        $this->priceType = intval($params['PRICE_TYPE'] ?: array_key_first($this->catalogGroups));
    }

    public function getCode(): string
    {
        return 'price';
    }

    public function getTitle(): string
    {
        return Loc::getMessage('APP_SORTING_PRICE_TYPE_TITLE') ?? '';
    }

    public function getSortFields(): array
    {
        return $this->priceType ? [
            'FIELD' => "CATALOG_PRICE_$this->priceType",
            'ORDER' => $this->selectedOrder==='up' ? 'asc' : 'desc',
        ] : [];
    }

    public function getParameters(): array
    {
        $priceTypes = [];

        foreach ($this->catalogGroups as $id => $name) {
            $priceTypes[$id] = "$name [$id]";
        }

        return [
            'PRICE_TYPE' => [
                'NAME'   => Loc::getMessage('APP_SORTING_PRICE_PRICE_TYPE'),
                'TYPE'   => 'LIST',
                'VALUES' => $priceTypes,
            ],
        ];
    }

    private function getCatalogGroups(): array
    {
        try {
            Loader::requireModule('catalog');
            $dbRes = GroupTable::getList(['select'=>['ID', 'NAME'], 'cache'=>['ttl'=>3600]])->fetchAll();
        }
        catch (Exception) {
            $dbRes = [];
        }

        return array_combine(array_column($dbRes, 'ID'), array_column($dbRes, 'NAME'));
    }
}
