<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;

define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

if (!check_bitrix_sessid() || !$request->isPost())
    return;

$resp = false;
$action = $request->get('action');
$pos = false;
$pos2 = false;
$newStrUser = '';
if ($action == 'searchUser') {
    $strUser = $request->get('str');
    if ($strUser) {

        $arIdProp = [67, 68, 133, 134];
        $arStrUser = [];
        $pos = mb_strpos($strUser, 'ё');
        $pos2 = mb_strpos($strUser, 'е');
        if ($pos !== false || $pos2 !== false) {
            if ($pos) $newStrUser = preg_replace('/ё/', "е", $strUser);
            if ($pos2) $newStrUser = preg_replace('/е/', "ё", $strUser);
            $arStrUser = [$strUser, $newStrUser];
            $arFilter =
                ["LOGIC" => "OR",
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%USER__EMAIL' => $arStrUser,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%USER__PERSONAL_PHONE' => $arStrUser,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%NAME' => $arStrUser, 'GROUP_GROUP_ID' => B2B_GROUP],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%NAME' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__NAME' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__EMAIL' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__PERSONAL_PHONE' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__LAST_NAME' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER_PROPS__VALUE' => $arStrUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                ];
        } else {
            $arFilter =
                ["LOGIC" => "OR",
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%USER__EMAIL' => $strUser,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%USER__PERSONAL_PHONE' => $strUser,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 5, '%NAME' => $strUser,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%NAME' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__NAME' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__EMAIL' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__PERSONAL_PHONE' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER__LAST_NAME' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                    ['USER__ACTIVE' => 'Y', 'PERSON_TYPE_ID' => 6, '%USER_PROPS__VALUE' => $strUser, 'USER_PROPS__ORDER_PROPS_ID' => $arIdProp,],
                ];
        }

        $rsUsers = \Bitrix\Sale\OrderUserProperties::getList(array(
            'filter' => $arFilter,
            'select' => ['*', 'USER__' => 'USER', 'GROUP_' => 'USER_GROUP', 'USER_PROPS__' => 'USER_PROPS'],
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField('USER', '\Bitrix\Main\UserTable', array('=this.USER_ID' => 'ref.ID'), array('join_type' => 'LEFT')),
                new \Bitrix\Main\Entity\ReferenceField('USER_GROUP', '\Bitrix\Main\UserGroupTable', array('=this.USER_ID' => 'ref.USER_ID'), array('join_type' => 'LEFT')),
                new \Bitrix\Main\Entity\ReferenceField('USER_PROPS', '\Bitrix\Sale\Internals\UserPropsValueTable', array('=this.ID' => 'ref.USER_PROPS_ID'), array('join_type' => 'LEFT'))
            ]));

        $newArr = [];
        $newArrName = [];
        $newArrEmail = $newArrPhone = [];
        $newArrFIO = [];
        $newArrProfileFIO = [];
        while ($arUser = $rsUsers->fetch()) {
            if ($arUser['GROUP_GROUP_ID'] != 1) {
                $arNewUser['PROFILE_ID'] = $arUser['ID'];
                $arNewUser['USER_ID'] = $arUser['USER_ID'];
                $arNewUser['NAME'] = $arUser['USER__NAME'];
                $arNewUser['LAST_NAME'] = $arUser['USER__LAST_NAME'];
                $arNewUser['EMAIL'] = $arUser['USER__EMAIL'];
                $arNewUser['PHONE'] = $arUser['USER__PERSONAL_PHONE'];
                if ($arUser['PERSON_TYPE_ID'] == 5) $arNewUser['TITLE_PROFILE'] = $arUser['NAME'];
                // if ($arUser['PERSON_TYPE_ID'] == 6) $arNewUser['TITLE_PROFILE'] = $arUser['NAME'];
                if ($arUser['USER_PROPS__ORDER_PROPS_ID'] == 68) $arNewUser['PROFILE_LAST_NAME'] = $arUser['USER_PROPS__VALUE'];
                if ($arUser['USER_PROPS__ORDER_PROPS_ID'] == 67) $arNewUser['PROFILE_NAME'] = $arUser['USER_PROPS__VALUE'];
                if ($arUser['USER_PROPS__ORDER_PROPS_ID'] == 134) $arNewUser['PROFILE_LAST_NAME'] = $arUser['USER_PROPS__VALUE'];
                if ($arUser['USER_PROPS__ORDER_PROPS_ID'] == 133) $arNewUser['PROFILE_NAME'] = $arUser['USER_PROPS__VALUE'];

                if ($newArr[$arNewUser['USER_ID']]["EMAIL"] != $arNewUser["EMAIL"]) $newArr[$arNewUser['USER_ID']][] = $arNewUser;
                elseif ($newArr[$arNewUser['USER_ID']]["PHONE"] != $arNewUser["PHONE"]) $newArr[$arNewUser['USER_ID']][] = $arNewUser;
            }
        }

        foreach ($newArr as $keyU => $user) {
            foreach ($user as $keyP => $profile) {
                $profileData = $profile['EMAIL'] ?: $profile['PHONE'];

                if ($profile['TITLE_PROFILE']) {
                    $posName = mb_strripos($profile['TITLE_PROFILE'], $strUser);
                    if ($posName !== false) $newArrName[$profile['TITLE_PROFILE']] = ['NAME' => '"' . $profile['TITLE_PROFILE'] . '" ' . $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profileData . ')/tc', 'ID' => $profile['USER_ID']];

                    if ($pos !== false || $pos2 !== false) {
                        $posName = mb_strripos($profile['TITLE_PROFILE'], $newStrUser);
                        if ($posName !== false) $newArrName[$profile['TITLE_PROFILE']] = ['NAME' => '"' . $profile['TITLE_PROFILE'] . '" ' . $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profileData . ')/tc', 'ID' => $profile['USER_ID']];
                    }
                }

                $posEmail = mb_strripos($profile['EMAIL'], $strUser);
                if ($posEmail !== false) $newArrEmail[$profile['USER_ID']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['EMAIL'] . ')/е', 'ID' => $profile['USER_ID']];

                if ($pos !== false || $pos2 !== false) {
                    $posEmail = mb_strripos($profile['EMAIL'], $newStrUser);
                    if ($posEmail !== false) $newArrEmail[$profile['USER_ID']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['EMAIL'] . ')/е', 'ID' => $profile['USER_ID']];
                }

                $posPhone = mb_strripos($profile['PHONE'], $strUser);
                if ($posPhone !== false) $newArrPhone[$profile['USER_ID']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['PHONE'] . ')/p', 'ID' => $profile['USER_ID']];
                if ($pos !== false || $pos2 !== false) {
                    $posPhone = mb_strripos($profile['PHONE'], $newStrUser);
                    if ($posPhone !== false) $newArrPhone[$profile['USER_ID']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['PHONE'] . ')/p', 'ID' => $profile['USER_ID']];
                }

                $posFIO = mb_strripos($profile['NAME'] . $profile['LAST_NAME'], $strUser);
                if ($posFIO !== false) $newArrFIO[$profile['NAME'] . $profile['LAST_NAME']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['EMAIL'] . ')/us', 'ID' => $profile['USER_ID']];

                if ($pos !== false || $pos2 !== false) {
                    $posFIO = mb_strripos($profile['NAME'] . $profile['LAST_NAME'], $newStrUser);
                    if ($posFIO !== false) $newArrFIO[$profile['NAME'] . $profile['LAST_NAME']] = ['NAME' => $profile['NAME'] . ' ' . $profile['LAST_NAME'] . ' (' . $profile['EMAIL'] . ')/us', 'ID' => $profile['USER_ID']];
                }

                $posProfileFIO = mb_strripos($profile['PROFILE_NAME'] . $profile['PROFILE_LAST_NAME'], $strUser);
                if ($posProfileFIO !== false) $newArrProfileFIO[$profile['NAME'] . $profile['LAST_NAME']] = ['NAME' => $profile['PROFILE_NAME'] . ' ' . $profile['PROFILE_LAST_NAME'] . ' (' . $profileData . ')/pf', 'ID' => $profile['USER_ID']];

                if ($pos !== false || $pos2 !== false) {
                    $posProfileFIO = mb_strripos($profile['PROFILE_NAME'] . $profile['PROFILE_LAST_NAME'], $newStrUser);
                    if ($posProfileFIO !== false) $newArrProfileFIO[$profile['NAME'] . $profile['LAST_NAME']] = ['NAME' => $profile['PROFILE_NAME'] . ' ' . $profile['PROFILE_LAST_NAME'] . ' (' . $profileData . ')/pf', 'ID' => $profile['USER_ID']];
                }
            }
        }

        $respAr = array_merge($newArrName, $newArrEmail, $newArrPhone, $newArrFIO, $newArrProfileFIO);
        if (!empty($respAr)) {
            $index = 0;
            foreach ($respAr as $str) {
                $resp[$index] = $str;
                $index++;
            }
        }
    }
    if (!$resp) $resp = 'Ничего не найдено';
} else
    if ($action == 'authUser') {
        $id = $request->get('id');
        if (!$id) return;

        global $USER;
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        if (!$session->has('mode')) {
            $key = md5(WORD_CODING);
            $session->set('mode', $key);
            $USER->Authorize($id);
            $resp = 'redirect';
        } elseif ($session->has('mode') && $session->has('mode') && $session['mode'] == '21232f297a57a5a743894a0e4a801fc3') {
            $USER->Authorize($id);
            $resp = 'redirect';
        }
    }
echo json_encode(['response' => $resp]);
