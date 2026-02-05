<?php
declare(strict_types=1);

namespace Test\Components\Listsorting;

class SortingTypesCollection
{
    private const TYPES_SUBDIR = 'SortingTypes';

    /** @var AbstractSortingType[] */
    private array $collection = [];

    private ?AbstractSortingType $defaultType;

    public function __construct(array $params)
    {
        $types = glob(dirname(__FILE__) . '/' . self::TYPES_SUBDIR . '/*.php');

        foreach ($types as $type) {
            if (is_file($type)) {
                /** @var AbstractSortingType|string $typeClassName */
                $typeClassName = __NAMESPACE__ . '\\' . self::TYPES_SUBDIR . '\\' . basename($type, '.php');
                $interfaces = class_implements($typeClassName); // <-class autoloading is performed

                if (class_exists($typeClassName) && $interfaces[SortingTypeInterface::class]) {
                    $typeObj = new $typeClassName($params);
                    $this->collection[$typeObj->getCode()] = $typeObj;
                }
            }
        }

        $this->defaultType = reset($this->collection) ?: null;
    }

    public function filter(array $availableTypes): void
    {
        $this->collection = array_intersect_key($this->collection, $availableTypes);
    }

    public function getList(): array
    {
        return $this->collection;
    }

    public function find(string $query): ?AbstractSortingType
    {
        $filtered = $query ?
            array_filter($this->collection, function($type) use ($query) {return $type->isMatchTo($query);}) :
            [];

        return reset($filtered) ?: null;
    }

    public function setDefault(string $query): void
    {
        if ($type = $this->find($query)) {
            $this->defaultType = $type->setActualOrder($query);
        }
    }

    public function getDefault(): ?AbstractSortingType
    {
        return $this->defaultType;
    }
}
