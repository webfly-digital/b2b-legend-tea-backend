<div class="product-table-wrapper spoiler <?= $needOpen ? 'open-spoiler' : 'no-init' ?>"
     data-spoiler-default-state="<?= $needOpen ? 'opened' : '' ?>" data-sid="<?= $sectionId ?>"
     data-code="<?= $sectionData['CODE'] ?>" id="spoiler-<?= $sectionId ?>"
     data-page="<?= $needOpen ? 2 : 1 ?>">
    <div class="product-table-title toggler">
        <h3>В архиве</h3>
        <div class="icon-link">
            <span class="initial">Раскрыть</span>
            <span class="expanded">Скрыть</span>
            <div class="icon"></div>
        </div>
    </div>
    <div class="product-table type4 content">
        <div class="product-table-header">
            <div class="cell">Наименование</div>
            <div class="cell">Доп.информация</div>
            <div class="cell">Информация о поступлении</div>
        </div>
        <div class="product-table-body" data-entity="items-row">
            <!-- items-container -->
            <?

            if ($arResult['ITEMS'][$sectionId]): ?>
                <?
                $arResult['JS_ITEMS'] = [];
                foreach ($arResult['ITEMS'][$sectionId] as $item) {
                    $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                    $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                    $arResult['JS_ITEMS'][$item['ID']] = $areaIds[$item['ID']];
                    ?>
                    <?
                    $APPLICATION->IncludeComponent(
                        'bitrix:catalog.item',
                        '',
                        array(
                            'RESULT' => array(
                                'ITEM' => $item,
                                'AREA_ID' => $areaIds[$item['ID']],
                                'TYPE' => 'CARD',
                                'BIG_LABEL' => 'N',
                                'BIG_DISCOUNT_PERCENT' => 'N',
                                'BIG_BUTTONS' => 'Y',
                                'SCALABLE' => 'N'
                            ),
                            'PARAMS' => $generalParams
                                + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );
                    ?>
                    <?
                }
                $arResult['JS_OBJECT'] = $obName;
                $component->SetResultCacheKeys(array("JS_ITEMS", 'JS_OBJECT'));
                ?>
            <? endif ?>
            <!-- items-container -->
        </div>
    </div>
</div>
