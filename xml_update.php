<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$arIds = $arGUIDS = $arGUIDSCA = [];
$xml = simplexml_load_file('1.xml');
/*
foreach ($xml as $data) {

    foreach($data[0]->КонтактныеЛицаПартнера->КонтактноеЛицо as $k => $val) { //
        if (intval(trim($val->ID))>0) {
            $arIds[trim(str_replace(" ", "", $val->ID))] = trim(str_replace(" ", "", $val->ID));
            $arGUIDS[trim(str_replace(" ", "", $val->ID))] = trim($val->GUID_Контакт);
        }
    }

    foreach($data[0]->КонтрагентыПартнера->Контрагент as $k => $val) { //
        if (intval(trim($val->ID))>0) {
            $arGUIDSCA[trim(str_replace(" ", "", $val->ID))] = trim($val->GUID_Контрагент);
        }
    }
}*/
/*
echo "<pre>";
print_r($arIds);
echo "</pre>";
die();
*/
die();

$xml = '<?xml version="1.0" encoding="UTF-8"?><ДанныеВыгрузки>';
$filter = Array("ID"=>implode("|",$arIds)); //"ID" => "1",2965|5010|1
$filter = array();
$rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, ["SELECT" => array("UF_*")]);
while ($arUser = $rsUsers->GetNext()) {
        $arFIO = [$arUser["LAST_NAME"], $arUser["NAME"], $arUser["SECOND_NAME"]];
        $contact_fio = $contact_email = $contact_phone = $contact_id = $contr_id = $prop_GUID = '';
        $company_title = $company_inn = $company_fio = $company_email = $company_kpp = $company_phone = $company_cont_email = $company_cont_phone = '';
        $contact_id = $arUser["ID"];
        $contact_fio = implode(" ", $arFIO);
        $contact_email = $arUser["EMAIL"];
        $contact_phone = $arUser["PERSONAL_PHONE"];
        $GUID = $arUser["UF_GUID"];

        $xml .= '<Партнер GUID_Партнера="' . $GUID . '" Наименование="' . str_replace(array("'", '"', '/'), '', $contact_fio) . '" Конкурент="Ложь">';

        /*
            echo "<pre>";
            print_r($arUser);
            echo "</pre>";
        */

        $xml .= '<КонтактныеЛицаПартнера>
                    <КонтактноеЛицо>
                        <GUID_Контакт>' . $GUID . '</GUID_Контакт>
                        <ID>' . $contact_id . '</ID>
                        <ФИО>' . str_replace(array("'", '"', '/'), '', $contact_fio) . '</ФИО>
                        <Телефон>' . $contact_phone . '</Телефон>
                        <Почта>' . $contact_email . '</Почта>
                    </КонтактноеЛицо>
                </КонтактныеЛицаПартнера>';
        $xml .= '<КонтрагентыПартнера>';

        $db_sales = CSaleOrderUserProps::GetList(array("ID" => "ASC"), array("USER_ID" => $arUser["ID"]));
        while ($ar_sales = $db_sales->Fetch()) {
            /*echo "<pre>";
            print_r($ar_sales);
            echo "</pre>";*/
            $contr_id = $ar_sales["ID"];


            $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), array("USER_PROPS_ID" => $ar_sales["ID"]));
            while ($arPropVals = $db_propVals->Fetch()) {

                if ($ar_sales["PERSON_TYPE_ID"] == 1 or $ar_sales["PERSON_TYPE_ID"] == 6) {
                    if ($arPropVals["PROP_CODE"] == "FIO") $contact_fio = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "EMAIL") $contact_email = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "PHONE") $contact_phone = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "1C_GUID") $prop_GUID = $arPropVals["VALUE"];
                } elseif ($ar_sales["PERSON_TYPE_ID"] == 2 or $ar_sales["PERSON_TYPE_ID"] == 5) {
                    if ($arPropVals["PROP_CODE"] == "COMPANY_TITLE") $company_title = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "INN") $company_inn = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "FIO") $company_fio = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "CONTACT_EMAIL") $company_cont_email = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "CONTACT_PHONE") $company_cont_phone = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "COMPANY_PHONE") $company_phone = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "COMPANY_EMAIL") $company_email = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "KPP") $company_kpp = $arPropVals["VALUE"];
                    if ($arPropVals["PROP_CODE"] == "1C_GUID") $prop_GUID = $arPropVals["VALUE"];
                }
                /*
                            echo $ar_sales["PERSON_TYPE_ID"]." ".$ar_sales["ID"]."<pre>";
                            print_r($arPropVals);
                            echo "</pre>"*/
            }

            if ($ar_sales["PERSON_TYPE_ID"] == 1 or $ar_sales["PERSON_TYPE_ID"] == 6) {
                $xml .= '<Контрагент>
                    <GUID_Контрагент>'.$prop_GUID.'</GUID_Контрагент>
                    <ID>' . $ar_sales["ID"] . '</ID>
                    <Наименование>' . str_replace(array("'", '"', '/'), '', $contact_fio) . '</Наименование>
                    <ИНН/>
                    <КПП/>
                    <Телефон>' . $contact_phone . '</Телефон>
                    <Почта>' . $contact_email . '</Почта>
                </Контрагент>';
            } elseif ($ar_sales["PERSON_TYPE_ID"] == 2 or $ar_sales["PERSON_TYPE_ID"] == 5) {
                $xml .= '<Контрагент>
                    <GUID_Контрагент>'.$prop_GUID.'</GUID_Контрагент>
                    <ID>' . $ar_sales["ID"] . '</ID>
                    <Наименование>' . str_replace(array("'", '"', '/'), '', $company_title) . '</Наименование>
                    <ИНН>' . $company_inn . '</ИНН>
                    <КПП>' . $company_kpp . '</КПП>
                    <Телефон>' . $company_cont_phone . '</Телефон>
                    <Почта>' . $company_cont_email . '</Почта>
                </Контрагент>';
            }


        }
        $xml .= '</КонтрагентыПартнера>';

        $xml .= '</Партнер>';


        $db_sales = CSaleOrderUserProps::DoLoadProfiles($arUser["ID"]);
        /*echo "<pre>";
        print_r($db_sales);
        echo "</pre>";*/


}
$xml .= '</ДанныеВыгрузки>';

header("Content-Type: text/xml");
header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
echo $xml;

?>
