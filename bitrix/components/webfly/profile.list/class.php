<?

use Webfly\Helper\Buyer;
use Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Main\Localization,
    Bitrix\Sale\Cashbox\CheckManager;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();


class WebflySearchProfileComponent extends CBitrixComponent implements Controllerable
{


    public function configureActions()
    {
        return [
            'setProfile' => [
                'prefilters' => [
                    new HttpMethod(
                        array(HttpMethod::METHOD_POST)
                    ),
                    new Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    public function setProfileAction()
    {
        $result = [];

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $profileId = $request->get('profileId');


        if (!empty($profileId)) {
            $res = Buyer::setDefault($profileId);
        }
        $this->getDefaultProfile();

        $resp['result'] = $this->arResult;
        return json_encode(['response' => $resp]);
    }

    protected function getItems()
    {
        $this->arResult['ITEMS'] = Buyer::getByUser();
    }

    protected function getDefaultProfile()
    {
        $this->arResult['DEFAULT_PROFILE']['NAME'] = 'Не выбрано';

        $res = Buyer::getDefault();
        if ($res) $this->arResult['DEFAULT_PROFILE'] = $res;
    }

    public function executeComponent()
    {
        $this->getItems();
        $this->getDefaultProfile();
        $this->includeComponentTemplate();
    }
}