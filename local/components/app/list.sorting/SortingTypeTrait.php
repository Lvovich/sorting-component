<?php
declare(strict_types=1);

namespace Test\Components\Listsorting;

trait SortingTypeTrait
{
    public function setActualOrder(string $query): static
    {
        $order = isset($this->orders[$query]) ? $query : $this->parseQuery($query)['order'];

        if (isset($this->orders[$order])) {
            $this->selectedOrder = $order;
        }

        return $this;
    }

    public function getVariants(): array
    {
        $res = [];

        foreach ($this->orders as $key => $title) {
            $res[] = [
                'GET_PARAM' => $this->getCode() . "_$key",
                'TITLE'     => $title,
            ];
        }

        return $res;
    }

    public function isMatchTo(string $query): bool
    {
        $q = $this->parseQuery($query);

        return $q['type']===$this->getCode() && isset($this->orders[$q['order']]);
    }

    public function getQueryParam(): string
    {
        return $this->selectedOrder ? $this->getCode() . "_$this->selectedOrder" : '';
    }

    public function getParameters(): array
    {
        return [];
    }
}
