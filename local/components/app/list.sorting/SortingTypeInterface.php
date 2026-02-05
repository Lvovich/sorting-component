<?php
declare(strict_types=1);

namespace Test\Components\Listsorting;

interface SortingTypeInterface
{
    /** Must return an unique code of this type. Preferably, it should be a lowercase class name */
    function getCode(): string;

    /** Must return a title of this type (for use in the parameter list, for example) */
    function getTitle(): string;

    /**
     * $query can be either a separate order, or a full query containing the order
     * @see SortingTypeTrait.php
     */
    function setActualOrder(string $query): static;

    /**
     * Must return an array of two values, which will be set as values for standard parameters of bitrix lists
     * @return array{FIELD: string, ORDER: string}
     */
    function getSortFields(): array;

    /** Check that the type matches the specified $query */
    function isMatchTo(string $query): bool;

    /**
     * Must return an array of two values, which will be used to build a list of available sorting types in HTML
     * @return array{GET_PARAM: string, TITLE: string}
     */
    function getVariants(): array;

    /** Must return value for the query parameter of sorting. E.g. value "name_z-a" for paramerter "?sort=name_z-a" */
    function getQueryParam(): string;

    /**
     * Can return an array describing the necessary parameters for this type, or an empty array otherwise
     * @see https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2132
     * @return array
     */
    function getParameters(): array;
}
