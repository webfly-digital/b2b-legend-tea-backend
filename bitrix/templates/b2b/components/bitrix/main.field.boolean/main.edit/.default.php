<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UserField\Types\BooleanType;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\HtmlFilter;

/**
 * @var BooleanUfComponent $component
 * @var array $arResult
 */
$component = $this->getComponent();
$value = $arResult['additionalParameters']['VALUE'][0];
?>
<input
        class="fields boolean"
        type="hidden"
        value="0"
        name="<?= $arResult['fieldName'] ?>"
>
<label class="checkbox-group <?=$arResult["userField"]['class']?>">
    <input type="checkbox" value="1" name="<?= $arResult['fieldName'] ?>" class="custom-checkbox" <?if ($arResult["userField"]['group']):?>data-control-group="<?=$arResult["userField"]['group']?>" data-control-mode="<?=$arResult["userField"]['mode']?>"<?endif?> <?= $value ? ' checked' : '' ?>>
    <span> <?=$arResult["userField"]["EDIT_FORM_LABEL"]?> </span>
</label>

<?php
array (
    'additionalParameters' =>
        array (
            'bVarsFromForm' => true,
            'arUserField' =>
                array (
                    'ID' => '305',
                    'ENTITY_ID' => 'USER',
                    'FIELD_NAME' => 'UF_EDO_USE',
                    'USER_TYPE_ID' => 'boolean',
                    'XML_ID' => 'UF_EDO_USE',
                    'SORT' => '40',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'IS_SEARCHABLE' => 'N',
                    'SETTINGS' =>
                        array (
                            'DEFAULT_VALUE' => 0,
                            'DISPLAY' => 'CHECKBOX',
                            'LABEL' =>
                                array (
                                    0 => '',
                                    1 => '',
                                ),
                            'LABEL_CHECKBOX' => '',
                        ),
                    'EDIT_FORM_LABEL' => 'Работаю по ЭДО',
                    'LIST_COLUMN_LABEL' => 'Работаю по ЭДО',
                    'LIST_FILTER_LABEL' => 'Работаю по ЭДО',
                    'ERROR_MESSAGE' => '',
                    'HELP_MESSAGE' => '',
                    'USER_TYPE' =>
                        array (
                            'USER_TYPE_ID' => 'boolean',
                            'CLASS_NAME' => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                            'EDIT_CALLBACK' =>
                                array (
                                    0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                                    1 => 'renderEdit',
                                ),
                            'VIEW_CALLBACK' =>
                                array (
                                    0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                                    1 => 'renderView',
                                ),
                            'USE_FIELD_COMPONENT' => true,
                            'DESCRIPTION' => 'Да/Нет',
                            'BASE_TYPE' => 'int',
                        ),
                    'VALUE' => false,
                    '~EDIT_FORM_LABEL' => 'Работаю по ЭДО',
                    'group' => 'g2',
                    'mode' => 'master',
                    '~FIELD_NAME' => 'UF_EDO_USE',
                ),
            'form_name' => 'bform',
            'CACHE_TYPE' => 'A',
            '~bVarsFromForm' => true,
            '~arUserField' =>
                array (
                    'ID' => '305',
                    'ENTITY_ID' => 'USER',
                    'FIELD_NAME' => 'UF_EDO_USE',
                    'USER_TYPE_ID' => 'boolean',
                    'XML_ID' => 'UF_EDO_USE',
                    'SORT' => '40',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'IS_SEARCHABLE' => 'N',
                    'SETTINGS' =>
                        array (
                            'DEFAULT_VALUE' => 0,
                            'DISPLAY' => 'CHECKBOX',
                            'LABEL' =>
                                array (
                                    0 => '',
                                    1 => '',
                                ),
                            'LABEL_CHECKBOX' => '',
                        ),
                    'EDIT_FORM_LABEL' => 'Работаю по ЭДО',
                    'LIST_COLUMN_LABEL' => 'Работаю по ЭДО',
                    'LIST_FILTER_LABEL' => 'Работаю по ЭДО',
                    'ERROR_MESSAGE' => '',
                    'HELP_MESSAGE' => '',
                    'USER_TYPE' =>
                        array (
                            'USER_TYPE_ID' => 'boolean',
                            'CLASS_NAME' => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                            'EDIT_CALLBACK' =>
                                array (
                                    0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                                    1 => 'renderEdit',
                                ),
                            'VIEW_CALLBACK' =>
                                array (
                                    0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                                    1 => 'renderView',
                                ),
                            'USE_FIELD_COMPONENT' => true,
                            'DESCRIPTION' => 'Да/Нет',
                            'BASE_TYPE' => 'int',
                        ),
                    'VALUE' => false,
                    '~EDIT_FORM_LABEL' => 'Работаю по ЭДО',
                    'group' => 'g2',
                    'mode' => 'master',
                ),
            '~form_name' => 'bform',
            '~CACHE_TYPE' => 'A',
            'skip_manager' => true,
            'mode' => 'main.edit',
            'VALUE' =>
                array (
                    0 => 1,
                ),
            'parentComponent' => NULL,
        ),
    'userField' =>
        array (
            'ID' => '305',
            'ENTITY_ID' => 'USER',
            'FIELD_NAME' => 'UF_EDO_USE',
            'USER_TYPE_ID' => 'boolean',
            'XML_ID' => 'UF_EDO_USE',
            'SORT' => '40',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'I',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array (
                    'DEFAULT_VALUE' => 0,
                    'DISPLAY' => 'CHECKBOX',
                    'LABEL' =>
                        array (
                            0 => '',
                            1 => '',
                        ),
                    'LABEL_CHECKBOX' => '',
                ),
            'EDIT_FORM_LABEL' => 'Работаю по ЭДО',
            'LIST_COLUMN_LABEL' => 'Работаю по ЭДО',
            'LIST_FILTER_LABEL' => 'Работаю по ЭДО',
            'ERROR_MESSAGE' => '',
            'HELP_MESSAGE' => '',
            'USER_TYPE' =>
                array (
                    'USER_TYPE_ID' => 'boolean',
                    'CLASS_NAME' => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                    'EDIT_CALLBACK' =>
                        array (
                            0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                            1 => 'renderEdit',
                        ),
                    'VIEW_CALLBACK' =>
                        array (
                            0 => 'Bitrix\\Main\\UserField\\Types\\BooleanType',
                            1 => 'renderView',
                        ),
                    'USE_FIELD_COMPONENT' => true,
                    'DESCRIPTION' => 'Да/Нет',
                    'BASE_TYPE' => 'int',
                ),
            'VALUE' => false,
            '~EDIT_FORM_LABEL' => 'Работаю по ЭДО',
            'group' => 'g2',
            'mode' => 'master',
            '~FIELD_NAME' => 'UF_EDO_USE',
        ),
    'fieldName' => 'UF_EDO_USE',
    'value' => 0,
    'valueList' =>
        array (
            0 => 'нет',
            1 => 'да',
        ),
);
