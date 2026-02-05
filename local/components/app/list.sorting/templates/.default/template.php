<?php if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)exit();

use Test\Components\Listsorting\AbstractSortingType;
use Test\Components\Listsorting\ListSorting;

/** @var array $arResult */
/** @var ListSorting $component */
/** @var CBitrixComponentTemplate $this */

if (!$arResult['SELECTED']) {
    return;
}
?>
<form name="sortingTypes" method="get">
    <label for="list_de62">Сортировать:</label>
    <select class="" name="<?= ListSorting::GET_PARAM ?>" id="list_de62"><?php
        /** @var AbstractSortingType $type */
        foreach ($arResult['TYPES'] as $type) {
            foreach ($type->getVariants() as $variant) {
                $selected = $variant['GET_PARAM']===$arResult['SELECTED'] ? 'selected' : '';?>
                <option value="<?= $variant['GET_PARAM'] ?>"<?= $selected ?>><?= $variant['TITLE'] ?></option><?php
            }
        }?>
    </select>
</form>

<script>
    BX && BX.message({
        SORTING_COMPONENT:  '<?= $component->getName() ?>',
        SORTING_PARAMETERS: '<?= $component->getSignedParameters() ?>',
    });
</script>
