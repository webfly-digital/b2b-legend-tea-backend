<?php

namespace Webfly\Handlers;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserPhoneAuthTable;
use Bitrix\Main\UserTable;
use Bitrix\Crm\Service;

Loader::includeModule('sale');

class Main
{

    public static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields, $message_id)
    {
        if ($lid == 's1' && $event == 'SALE_STATUS_CHANGED_F' && !empty($arFields['ORDER_REAL_ID'])) {
            if ($arFields['ORDER_REAL_ID']) {
                \Bitrix\Main\Loader::includeModule("catalog");
                $basket = \Bitrix\Sale\Order::load($arFields['ORDER_REAL_ID'])->getBasket();
                $strProduct = '';
                foreach ($basket as $basketItem) {
                    $strProduct .= '<a href="' . $basketItem->getField('DETAIL_PAGE_URL') . '">' . $basketItem->getField('NAME') . '</a><br/>';
                }
                $arFields['LIST_PRODUCTS'] = $strProduct;
            }
        }

    }

    public static function OnCommentAddHandler($id, &$arParams)
    { //https://webfly.bitrix24.ru/company/personal/user/3389/tasks/task/view/29591/

        \Bitrix\Main\Loader::includeModule('blog');

        if (!empty($arParams['PARENT_ID'])) return;

        $arPost = \CBlogPost::GetByID($arParams['POST_ID']);

        $new_text = preg_replace('|<virtues>(.*)(</virtues>)|Uis', 'Достоинства $1', $arParams['POST_TEXT']);
        $new_text = preg_replace('|<limitations>(.*)(</limitations>)|Uis', 'Недостатки $1', $new_text);
        $new_text = preg_replace('|<comment>(.*)(</comment>)|Uis', 'Комментарий $1', $new_text);


        $resContact = \Webfly\Helper\Helper::searchContactByUserPhone($arParams['AUTHOR_ID']);

        $oLead = new \CCrmLead(false);
        $arFields = array(
            "TITLE" => 'Новый отзыв на ' . $arPost['TITLE'],
            "STATUS_ID" => 'NEW',
            "COMMENTS" => $new_text . '<br>' . 'Оценка ' . $arParams['UF_ASPRO_COM_RATING'] . '<br>' . $arParams['PATH'],
            "SOURCE_ID" => "UC_YMM8DC",
            "ASSIGNED_BY_ID" => 118,
            'CONTACT_IDS' => $resContact
        );
        $LEAD_ID = $oLead->Add($arFields, true, []);
    }

    /***Событие вызывается в методе CUser::Add до вставки нового пользователя,
     * и может быть использовано для отмены вставки или переопределения некоторых полей.
     */
    public static function OnBeforeUserAddHandler(&$arFields)
    {
        \Webfly\Helper\User::identicalPhonesInNewUser($arFields);
    }


    /**
     * OnBeforeUserRegister выхывается на главном ините
     * Валидация полей регистрации, которые в базе - необязательные, но нам надо, чтоб они были обязательными)
     * @param $arFields
     * @return bool|void
     */

    public
    static function OnBeforeUserRegisterHandler(&$arFields)
    {
        self::addLog($arFields);
        $skip = self::checkAdminSkip($arFields);
        if ($skip) return;//ничего не делать, если админка || юзер - админ

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        self::addLog($request);

        $errors = \Webfly\Helper\User::validFieldsRegister($arFields, $request);

        if (!empty($errors)) {
            global $APPLICATION;
            $APPLICATION->throwException(implode('<br>', $errors));
            return false;
        }

        \Webfly\Helper\User::changeFieldsNewUser($arFields);
        self::addLog($arFields);
    }

    /**
     * Вспомагательный метод,
     * исключающий обработчики для админов и в админке
     * @param $arFields
     * @return bool
     */
    protected
    static function checkAdminSkip($arFields)
    {
        $skip = $isAdminGroup = false;
        $isAdminSection = (defined('ADMIN_SECTION') && ADMIN_SECTION === true);
        if ($arFields['GROUP_ID']) $isAdminGroup = in_array(1, $arFields['GROUP_ID']);
        if ($arFields['USER_ID'] || $arFields['ID']) {
            $userId = $arFields['USER_ID'] ?: $arFields['ID'];
            if ($userId > 0) {
                $userGroups = UserGroupTable::getList(['filter' => ['USER_ID' => $userId, 'USER.ACTIVE' => 'Y'], 'select' => ['GROUP_ID']])->fetchAll();
                if (in_array(1, array_column($userGroups, 'GROUP_ID'))) $isAdminGroup = true;
            }
        }
        $skip = $isAdminSection || $isAdminGroup;
        return $skip;
    }

    /**
     * Событие "OnAfterUserRegister" вызывается после попытки регистрации нового пользователя
     * методом CUser::Register. Все параметры данного обработчика являются ссылками на исходные переменные.
     * Поэтому изменить эти параметры невозможно: изменения не сохраняются.
     */
    public
    static function OnAfterUserRegisterHandler(&$arFields)
    {
        if ($arFields['RESULT_MESSAGE']['TYPE'] != 'OK' && empty($arFields['USER_ID'])) return;

        $skip = self::checkAdminSkip($arFields);
        if ($skip) return;//ничего не делать, если админка || юзер - админ

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

      //  \Webfly\Helper\Buyer::addProfile($arFields, $request);
        \Webfly\Helper\Buyer::addProfileRegister($arFields, $request);

        //ранее получая ответ из 1с получили гуид передаём его в $arFields['UF_CONTACT_GUID']
        if ($arFields['UF_1C'] != 'да') \Webfly\Helper\Contact::checkExistContact($arFields);
    }


    static public function OnAfterUserAuthorizeHandler($arUser)
    {
        $skip = self::checkAdminSkip($arUser['user_fields']);
        if ($skip) return;//ничего не делать, если админка || юзер - админ

        \Webfly\Helper\Contact::checkExistContact($arUser['user_fields']);
    }

    static public function OnEpilogHandler()
    {
        global $APPLICATION;
        $NavPageNomer = intval($APPLICATION->GetPageProperty("NavPageNomer"));
        if ($NavPageNomer > 1) {
            $title = $APPLICATION->GetPageProperty("title") . ' - page ' . $NavPageNomer;
            $APPLICATION->SetPageProperty('title', $title);
            $APPLICATION->SetPageProperty('description', '');
            $APPLICATION->SetPageProperty("robots", "noindex,follow");
        }
    }


    static public function OnPrologHandler()
    {
        if (SITE_ID == 's1' || SITE_ID == 's3') self::deleteBasket();
    }

    static public function deleteBasket()
    {
        $needSave = false;
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
        if ($basket) {
            foreach ($basket as $basketItem) {
                $arProductIds [$basketItem->getId()] = $basketItem->getProductId();
            }
        }
        if (!empty($arProductIds)) {
            $arDelete = \Webfly\Helper\Functions::checkListElementsSite($arProductIds);
            if (!empty($arDelete)) {
                foreach ($basket as $basketItem) {
                    if ($arDelete[$basketItem->getId()]) {
                        $basketItem->delete();
                        $needSave = true;
                    }
                }
            }
        }
        if ($needSave) $basket->save();
    }

    static public function OnPageStartHandler()
    {
        if (array_key_exists('REQUEST_1C', $_REQUEST) && !empty($_REQUEST["REQUEST_1C"])) { //пропускаем проверку капчи, если запрос из 1С на регистарцию пользователя
            define("CAPTCHA_COMPATIBILITY", true);
            $_SESSION['CAPTCHA_CODE'][$_REQUEST['captcha_sid']] = $_REQUEST['captcha_word'];
        }

       
    }



    public static function addLog($array = [])
    {
        \Webfly\Helper\Log::Add(print_r($array, true), false, false, 'logsRegister');
    }

    static public function OnAfterUserUpdateHandler($arFields)
    {

        if ($arFields['RESULT'] && !self::userToOpenLine($arFields)) $res = \Webfly\Helper\Contact::updateContactWhenChangeUser($arFields);

    }

    static public function OnBeforeUserUpdateHandler(&$arFields)
    {
        $res = \Webfly\Helper\User::checkUniqPhone($arFields);

        if (!empty($res)) {
            global $APPLICATION;
            $APPLICATION->ThrowException($res);
            return false;
        }

    }

    static public function userToOpenLine($arFields)
    {
        $openLine = false;
        if (key_exists('GROUP_ID', $arFields) && empty($arFields['GROUP_ID'])) $openLine = true;
        if (key_exists('UF_CONNECTOR_MD5', $arFields)) $openLine = true;
        return $openLine;
    }


}
