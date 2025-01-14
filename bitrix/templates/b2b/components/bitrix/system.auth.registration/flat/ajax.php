<?php
/** @global \CMain $APPLICATION */

use Bitrix\Main\Loader;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);
$inn = $request->get('inn');

$result = ['result' => 'error', 'data' => []];

if (!check_bitrix_sessid() || !$request->isPost() || !$inn) {
    echo json_encode($result);
    die();
}

$data = [];

if($inn) {
    $dadata = new \Webfly\Helper\DaData();
    $res = $dadata->suggest(['query' => $inn, 'count' => 1], 'findById/party');
    if ($res) {
        foreach ($res->suggestions as $item) {
            if ($item->value)
                $data = ['COMPANY' => $item->value, 'COMPANY_ADR' => $item->data->address->unrestricted_value];
        }
    }

    $arEmail = \Webfly\Helper\Helper::getExistUsersInn($inn);
    if (!empty($arEmail)) $data['EXIST_INN'] = $arEmail['MESSAGE'];
}


if ($data) {
    $result = ['result' => 'success', 'data' => $data];
    echo json_encode($result);
    die();
} else {
    echo json_encode($result);
    die();
}
