<?php
declare(strict_types=1);

namespace Test\Components\Listsorting\SortingTypes;

use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Basket;
use CCatalogSku;
use Exception;
use Test\Components\Listsorting\AbstractSortingType;

/**
 * Task statement: Популярность товаров определяется по кол-ву заказов товара, чем больше покупок, тем товар популярнее.
 *
 * Ограничение: проект должен иметь торговые предложения (которые кладутся в корзину).
 * Замечание: такая реализация только для проектов с небольшим количеством товаров, т.к. рассчитывает сортировку на хите
 *     В реальных проектах лучше добавить числовое свойство для хранения индекса сортировки, и рассчитывать его агентом
 *     на кроне (заполнять количеством его ТП в заказах). Далее здесь только отдавать это свойство в качестве поля
 *     сортировки.
 *
 * @noinspection PhpUnused
 */
class Orders extends AbstractSortingType
{
    public function __construct()
    {
        parent::__construct([
            'frequently' => 'Часто заказываемые вначале',
            'rarely'     => 'Редко заказываемые вначале',
        ]);
    }

    public function getCode(): string
    {
        return 'orders';
    }

    public function getTitle(): string
    {
        return Loc::getMessage('APP_SORTING_ORDERS_TYPE_TITLE') ?? '';
    }

    public function getSortFields(): array
    {
        return [
            'FIELD' => 'ID',
            'ORDER' => $this->getSortedIDs(),
        ];
    }

    private function getSortedIDs(): array
    {
        $res = [];

        if ($offers = $this->getOffersFromOrders()) {
            $products = CCatalogSKU::getProductList(array_keys($offers)) ?: [];

            foreach ($offers as $offerId => $count) {
                if ($p = $products[$offerId]) {
                    $res[$p['ID']] += $count;
                }
            }

            $this->selectedOrder==='frequently' ? arsort($res) : asort($res);
        }

        return array_keys($res);
    }

    private function getOffersFromOrders(): array
    {
        try {
            $d = Basket::getList([
                'filter'  => ['!ORDER_ID'=>false],
                'select'  => ['PRODUCT_ID', 'P_COUNT'],
                'group'   => ['PRODUCT_ID'],
                'runtime' => [new ExpressionField('P_COUNT', 'SUM(QUANTITY)')],
                'cache'   => ['ttl'=>3600],
            ])->fetchAll();
        }
        catch (Exception) {
            $d = [];
        }

        return array_combine(array_column($d, 'PRODUCT_ID'), array_column($d, 'P_COUNT'));
    }
}
