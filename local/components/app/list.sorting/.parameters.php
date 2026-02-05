<?php if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)exit();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Test\Components\Listsorting\SortingTypesCollection;

/** @var string $componentPath */
/** @var array $arCurrentValues */

$fullPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . $componentPath;
Loader::registerNamespace('Test\Components\Listsorting', $fullPath);

$allTypesCollection = new SortingTypesCollection([]);

$availableTypes = array_map(function($type){return $type->getTitle();}, $allTypesCollection->getList());

if ($arCurrentValues['AVAILABLE_TYPES']) {
    $allTypesCollection->filter(array_flip($arCurrentValues['AVAILABLE_TYPES']));
}

$sortGroups = ['BASE'=>[]];
$sortVariants = [];
$sortParameters = [];

foreach ($allTypesCollection->getList() as $code => $type) {
    $sortGroups[$code] = [
        'NAME' => Loc::getMessage('APP_SORTING_GROUP_TITLE', ['#TITLE#'=>$type->getTitle()]),
    ];

    foreach ($type->getVariants() as $variant) {
        $sortVariants[$variant['GET_PARAM']] = $variant['TITLE'];
    }

    foreach ($type->getParameters() as $key => $description) {
        $sortParameters[$key] = $description;
        $sortParameters[$key]['PARENT'] = $code;
    }
}

$arComponentParameters = [
    'GROUPS' => $sortGroups,
    'PARAMETERS' => [
        'CACHE_TIME' => [
            'DEFAULT' => 36000000,
        ],
        'AVAILABLE_TYPES' => [
            'PARENT'   => 'BASE',
            'NAME'     => Loc::getMessage('APP_SORTING_AVAILABLE_TYPES'),
            'TYPE'     => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES'   => $availableTypes,
            'REFRESH'  => 'Y',
        ],
        'DEFAULT' => [
            'PARENT'  => 'BASE',
            'NAME'    => Loc::getMessage('APP_SORTING_DEFAULT_SORTING'),
            'TYPE'    => 'LIST',
            'VALUES'  => $sortVariants,
        ],
    ],
];

$arComponentParameters['PARAMETERS'] = array_merge($arComponentParameters['PARAMETERS'], $sortParameters);
