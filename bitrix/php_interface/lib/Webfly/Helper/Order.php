<?

namespace Webfly\Helper;

use Bitrix\Main\Event;
use Bitrix\Sale\BuyerProfile;

\Bitrix\Main\Loader::includeModule('main');
\Bitrix\Main\Loader::includeModule('sale');

class Order
{


    public static function FillOrderPropsFromProfileId($profileId, $order)
    {
        $propertyCollection = $order->getPropertyCollection();

        // Получаем профиль пользователя
        $arProfile = \CSaleOrderUserProps::GetByID($profileId);
        if (!$arProfile) {
            return;
        }

        // Получаем свойства профиля и значения
        $dbProps = \CSaleOrderUserPropsValue::GetList(
            [],
            ["USER_PROPS_ID" => $profileId]
        );

        while ($arProp = $dbProps->Fetch()) {
            $code = $arProp["PROP_CODE"];
            $value = $arProp["VALUE"];


            if ($code == 'PROFILE_ID' && empty($value)) $value = $profileId;

            if (!$value || !$code) {
                continue;
            }

            $orderProp = $propertyCollection->getItemByOrderPropertyCode($code);
            if ($orderProp && !$orderProp->getValue()) {

                if ($code == 'PROFILE_ID') $value = $profileId;
                $orderProp->setValue($value);
            }
        }
    }
}