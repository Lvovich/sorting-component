<?php if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)exit();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
	'NAME' => Loc::getMessage('APP_SORTING_COMPONENT_NAME'),
	'DESCRIPTION' => Loc::getMessage('APP_SORTING_COMPONENT_DESCRIPTION'),
	'ICON' => '/images/cat_list.gif',
	'CACHE_PATH' => 'Y',
	'SORT' => 30,
	'PATH' => [
		'ID' => 'content',
    ],
];
