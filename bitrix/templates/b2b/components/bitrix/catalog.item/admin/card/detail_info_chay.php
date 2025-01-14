
<?
$propScale = [
    'SKOROST_ZAVARIVANIYA' => ['Медленно', 'Средне', 'Быстро', 'Очень быстро'],
    'KREPOST' => ['Легкий', 'Умеренный', 'Крепкий', 'Очень крепкий'],
    'AROMAT' => ['Почти отсутствует', 'Слабый', 'Умеренный', 'Яркий'],
    'VKUS_1' => ['Очень слабый', 'Слабый', 'Средний', 'Насыщенный'],
];
$scaleCount = 4;
?>
<? if (!empty($item['PROPERTIES']["VREMYA_ZAVARIVANIYA"]['VALUE']) || !empty($item['PROPERTIES']["TEMPERATURA_ZAVARIVANIYA"]['VALUE']) || !empty($item['PROPERTIES']["VES_CHAYA"]['VALUE'])): ?>
    <div class="stats">
        <? if (!empty($item['PROPERTIES']["VREMYA_ZAVARIVANIYA"]['VALUE'])): ?>
            <div class="stat-item">
                <p class="small-text">Время заваривания</p>
                <i class="icon-time"></i>
                <p class="stat-item-value"> <?= $item['PROPERTIES']["VREMYA_ZAVARIVANIYA"]['VALUE'] ?> </p>
            </div>
        <? endif; ?>
        <? if (!empty($item['PROPERTIES']["TEMPERATURA_ZAVARIVANIYA"]['VALUE'])): ?>
            <div class="stat-item">
                <p class="small-text">Температура</p>
                <i class="icon-temperature"></i>
                <p class="stat-item-value"> <?= $item['PROPERTIES']["TEMPERATURA_ZAVARIVANIYA"]['VALUE'] ?>  </p>
            </div>
        <? endif; ?>
        <? if (!empty($item['PROPERTIES']["VES_CHAYA"]['VALUE'])): ?>
            <div class="stat-item">
                <p class="small-text">Вес чая</p>
                <i class="icon-weight"></i>
                <p class="stat-item-value"><?= $item['PROPERTIES']["VES_CHAYA"]['VALUE'] ?></p>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>
<div class="stats-grid">
    <ul class="stats-text">
        <li>Регион: <span>Ассам, Индия</span></li>
        <li>Базовая единица: <span>шт</span></li>
        <li>Производитель: <span>Легенда чая</span></li>

        <? if (!empty($item['PROPERTIES']["VID_CHAYA"]['VALUE'])): ?>
            <li>Вид чая: <span><?= $item['PROPERTIES']["VID_CHAYA"]['VALUE'] ?></span></li>
        <? endif; ?>

        <? if (!empty($item['PROPERTIES']["OSNOVA"]['VALUE'])): ?>
            <li>Страна происхождения: <span><?= $item['PROPERTIES']["OSNOVA"]['VALUE'] ?></span>
            </li>
        <? endif; ?>

        <? if (!empty($item['PROPERTIES']["KATEGORIYA_CHAYA"]['VALUE'])): ?>
            <li>Вид чая: <span><?= $item['PROPERTIES']["KATEGORIYA_CHAYA"]['VALUE'] ?></span></li>
        <? endif; ?>
    </ul>
    <div class="stats-colors">
        <? foreach ($propScale as $type => $propValues) : ?>
            <? if (!empty($item['PROPERTIES'][$type]['VALUE'])):
                $value = array_search($item['PROPERTIES'][$type]['VALUE'], $propValues);
                ?>
                <? if (isset($value)): ?>
                <div class="stat-color-item">
                    <p class="text"><?= $item['PROPERTIES'][$type]['NAME'] ?>:</p>
                    <ul>
                        <? for ($i = 0; $i < $scaleCount; $i++) : ?>
                            <li class="<?= $i <= $value ? "filled" : "" ?>"></li>
                        <? endfor; ?>
                    </ul>
                    <p class="small-text"><?= $item['PROPERTIES'][$type]['VALUE'] ?></p>
                </div>
            <? endif; ?>
            <? endif; ?>
        <? endforeach; ?>
    </div>
</div>