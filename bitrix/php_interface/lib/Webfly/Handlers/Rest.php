<?php


namespace Webfly\Handlers;


use Webfly\Helper\Profile;


\Bitrix\Main\Loader::includeModule('main');
\Bitrix\Main\Loader::includeModule('sale');


class Rest
{


    static public function OnRestServiceBuildDescriptionHandler()
    {
        return array(
            'rest_webfly' => array(
                'rest.add.profile' => array(
                    'callback' => array(__CLASS__, 'addProfile'),
                    'options' => array(),
                ),
                'rest.reconnecting.profile' => array(
                    'callback' => array(__CLASS__, 'reconnectingProfile'),
                    'options' => array(),
                ),
            )
        );
    }

    public static function reconnectingProfile($dataRequest)
    {
        if (!$dataRequest['USER_ID'])
            throw new \Bitrix\Rest\RestException('USER_ID empty', 'ERROR_CODE',);

        if (!$dataRequest['PROFILE_ID'])
            throw new \Bitrix\Rest\RestException('PROFILE_ID empty', 'ERROR_CODE',);


        $filter = ['PROFILE_CODE' => 'PROFILE_ID', 'PROFILE_VALUE' => $dataRequest['PROFILE_ID'], '!USER_ID' => $dataRequest['USER_ID']];
        $arOrders = \Bitrix\Sale\Internals\OrderTable::getList(array(
            'order' => array('ID' => 'ASC'),
            'filter' => $filter,
            'select' => ['PROFILE_' => 'PROFILE', 'ID'],
            'runtime' => [new  \Bitrix\Main\Entity\ReferenceField('PROFILE', \Bitrix\Sale\Internals\OrderPropsValueTable::getEntity(), ['=this.ID' => 'ref.ORDER_ID']),]
        ));
        while ($ob = $arOrders->fetch()) {
            $arUpdateOrder[] = $ob['ID'];
        }

        $arMessage = [];
        if (!empty($arUpdateOrder)) {
            foreach ($arUpdateOrder as $item) {
                $arFields = array("USER_ID" => $dataRequest["USER_ID"],);
                $res = \CSaleOrder::Update($item, $arFields);
                if ($res == false)
                    throw new \Bitrix\Rest\RestException('update order ' . $item . ' error', 'ERROR_CODE',);
            }
            $arMessage[] = 'update orders';
        }

        $arFields = array("USER_ID" => $dataRequest['USER_ID']);
        $res = \CSaleOrderUserProps::Update($dataRequest['PROFILE_ID'], $arFields);
        if ($res == false) {
            throw new \Bitrix\Rest\RestException('update profile error', 'ERROR_CODE',);
        } else {
            $arMessage[] = 'update profile';
        }

        return ['STATUS' => 'OK', 'MESSAGE' => implode(' ', $arMessage)];
    }

    public static function addProfile($dataRequest)
    {
        if (!$dataRequest['USER_ID'])
            throw new \Bitrix\Rest\RestException('USER_ID empty', 'ERROR_CODE',);

        if (!$dataRequest['PROFILE_TYPE_ID'])
            throw new \Bitrix\Rest\RestException('PROFILE_TYPE_ID empty', 'ERROR_CODE',);


        if ($dataRequest['PROFILE_TYPE_ID'] == B2B_FIZ_PERSON_TYPE_ID) {
            $arFieldsName['LAST_NAME'] = $dataRequest[Profile::PROP_ORDER_BUYER_LAST_NAME['CODE']];
            $arFieldsName['NAME'] = $dataRequest[Profile::PROP_ORDER_BUYER_NAME['CODE']];
        } else {
            $companyTitle = $dataRequest['COMPANY'];

            $arEmail = \Webfly\Helper\Helper::getExistUsersInn($dataRequest[Profile::PROP_ORDER_COMPANY_INN['CODE']], $dataRequest['USER_ID']);
            if (!empty($arEmail)) {
                throw new \Bitrix\Rest\RestException(
                    'Company INN exist',
                    'ERROR_CODE',
                );
            }
        }

        $objProfile = new Profile(false, $dataRequest['PROFILE_TYPE_ID']);
        $objProfile->setNameProfile($arFieldsName, $companyTitle);
        $objProfile->addProfileBuyer($dataRequest['USER_ID']);

        $objProfile->setCompanyTitleValue();
        if ($dataRequest[Profile::PROP_ORDER_CODE_GUID['CODE']]) $objProfile->setCodeGuidValue($dataRequest[Profile::PROP_ORDER_CODE_GUID['CODE']]);
        if ($dataRequest[Profile::PROP_ORDER_COMPANY_ADR['CODE']]) $objProfile->setCompanyAdrValue($dataRequest[Profile::PROP_ORDER_COMPANY_ADR['CODE']]);
        if ($dataRequest[Profile::PROP_ORDER_COMPANY_INN['CODE']]) $objProfile->setCompanyInnValue($dataRequest[Profile::PROP_ORDER_COMPANY_INN['CODE']]);

        if ($dataRequest[Profile::PROP_ORDER_ADDRESS['CODE']]) $objProfile->setAddressValue($dataRequest[Profile::PROP_ORDER_ADDRESS['CODE']]);
        if ($dataRequest[Profile::PROP_ORDER_LOCATION['CODE']]) $objProfile->setLocationValue($dataRequest[Profile::PROP_ORDER_LOCATION['CODE']]);


        if ($dataRequest[Profile::PROP_ORDER_BUYER_LAST_NAME['CODE']]) $objProfile->setBuyerLastNameValue($dataRequest[Profile::PROP_ORDER_BUYER_LAST_NAME['CODE']]);
        if ($dataRequest[Profile::PROP_ORDER_BUYER_NAME['CODE']]) $objProfile->setBuyerNameValue($dataRequest[Profile::PROP_ORDER_BUYER_NAME['CODE']]);

        if ($dataRequest[Profile::PROP_ORDER_PHONE['CODE']]) $objProfile->setEmailValue($dataRequest[Profile::PROP_ORDER_PHONE['CODE']]);
        if ($dataRequest[Profile::PROP_ORDER_EMAIL['CODE']]) $objProfile->setPhoneValue($dataRequest[Profile::PROP_ORDER_EMAIL['CODE']]);

        $objProfile->addPropsProfileBuyer();

        return ['STATUS' => 'OK', 'PROFILE_ID' => $objProfile->idProfile];
    }


}