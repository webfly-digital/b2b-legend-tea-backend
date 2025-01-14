<?php
/** @global \CMain $APPLICATION */

use Bitrix\Main\Loader;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);
$value = $request->get('value');
$type = $request->get('type');

$result = ['result' => 'success', 'data' => []];

if (!check_bitrix_sessid() || !$request->isPost() || !$type) {
    echo json_encode($result);
    die();
}

global $USER;
if ($type == 'inn') {
    if (!empty($value)) $arData = \Webfly\Helper\Helper::getExistUsersInn($value, $USER->GetID());
} else {
    if (!empty($value)) $arData = \Webfly\Helper\Helper::getExistProfile($value, $type, $USER->GetID());

    $value2 = $request->get('value2');
    $type2 = $request->get('type2');
    if ($value2 != 'false' && $type2 != 'false') {
        $arData2 = \Webfly\Helper\Helper::getExistProfile($value2, $type2, $USER->GetID());
        if ($arData2) $arData['MESSAGE'] = $arData['MESSAGE'] . ' ' . $arData2['MESSAGE'];
    }
}

if (!empty($arData)) $data['EXIST_DATA'] = $arData['MESSAGE'];

if (!empty($data)) {
    $result = ['result' => 'success', 'data' => $data];
    echo json_encode($result);
    die();
} else {
    echo json_encode($result);
    die();
}
