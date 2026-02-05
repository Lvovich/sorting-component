<?php
declare(strict_types=1);

namespace Test\Components\Listsorting;

use Bitrix\Main\Component\ParameterSigner;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Loader;
use CBitrixComponent;
use Exception;

/* @noinspection PhpUnused */
class ListSorting extends CBitrixComponent implements Controllerable
{
    public const GET_PARAM = 'sort';

    private string $sessionKey;
    private SortingTypesCollection $types;

    public function onPrepareComponentParams($arParams): array
    {
        try {
            $this->signedParameters = ParameterSigner::signParameters($this->getName(), $arParams);
        }
        catch (Exception) {
            $this->signedParameters = '';
        }

        !is_array($arParams['AVAILABLE_TYPES']) && ($arParams['AVAILABLE_TYPES'] = []);

        $this->sessionKey = $this->getName();

        $fullPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . $this->getPath();
        Loader::registerNamespace('Test\Components\Listsorting', $fullPath);

        return $arParams;
    }

    public function configureActions(): array
    {
        return [
            'changeSorting' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                ]
            ],
        ];
    }

    public function executeComponent(): array
    {
        $this->types = $this->prepareTypesCollection();

        $selectedType = $this->getSelectedType((string)$_GET[self::GET_PARAM]);

        $_SESSION[$this->sessionKey] = $selectedType?->getQueryParam() ?? '';

        if ($this->startResultCache(false, $_SESSION[$this->sessionKey])) {
            $this->arResult['TYPES'] = $this->types->getList();
            $this->arResult['SELECTED'] = $_SESSION[$this->sessionKey];

            $this->includeComponentTemplate();
        }

        return $selectedType?->getSortFields() ?? [];
    }

    /**
     * @throws Exception
     *
     * @noinspection PhpUnused
     */
    public function changeSortingAction(string $query): void
    {
        $this->types = $this->prepareTypesCollection();

        if ($type = $this->types->find($query)) {
            $_SESSION[$this->sessionKey] = $type->setActualOrder($query)->getQueryParam();
            return;
        }

        throw new Exception('Invalid sorting type');
    }

    private function prepareTypesCollection(): SortingTypesCollection
    {
        $res = new SortingTypesCollection($this->arParams);

        if ($this->arParams['AVAILABLE_TYPES']) {
            $res->filter(array_flip($this->arParams['AVAILABLE_TYPES']));
        }

        if ($this->arParams['DEFAULT']) {
            $res->setDefault($this->arParams['DEFAULT']);
        }

        return $res;
    }

    private function getSelectedType(string $query): ?AbstractSortingType
    {
        if (!($res = $this->types->find($query))) {
            $query = $_SESSION[$this->sessionKey] ?? '';
            $res = $this->types->find($query);
        }

        return $res?->setActualOrder($query) ?? $this->types->getDefault();
    }
}
