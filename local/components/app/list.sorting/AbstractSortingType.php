<?php
declare(strict_types=1);

namespace Test\Components\Listsorting;

abstract class AbstractSortingType implements SortingTypeInterface
{
    use SortingTypeTrait;

    protected array $orders;
    protected ?string $selectedOrder;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
        $this->selectedOrder = array_key_first($orders);
    }

    /**
     * Should return two values: sorting type and order for this type, parsed from query string
     * @return array{type: string, order: string}
     */
    protected function parseQuery(string $query): array
    {
        list($type, $order) = explode('_', $query);

        return ['type'=>$type, 'order'=>(string)$order]; // $order can have a null value
    }
}
