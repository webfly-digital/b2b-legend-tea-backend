<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');





$arIds = $arContrIds = $arGUIDS = $arGUIDSCA = [];
$xml = simplexml_load_file('3.xml');

foreach ($xml as $k0=>$data) {

    $partner_name = $data->attributes()->Наименование;
    $GUID_partner = $data->attributes()->GUID_Партнера;

    $arUserIds = $arProfileIds = [];
    $arUsers = $arPprofiles = [];

    foreach($data->КонтактныеЛицаПартнера as $contact) {
        foreach($contact->КонтактноеЛицо as $val) {
            if (intval($val->ID)>0) {
                $arUserIds[intval($val->ID)] = intval($val->ID);
                $arUsers[intval($val->ID)] = [
                    "GUID_contact" => $val->GUID_Контакт,
                    "ID" => intval($val->ID),
                    "fio" => (string)$val->ФИО,
                    "phone" => (string)$val->Телефон,
                    "mail" => (string)$val->Почта,
                    "profiles" => []
                ];

                foreach($data->КонтрагентыПартнера as $contragent) {
                    foreach($contragent->Контрагент as $valC) {

                        if (intval($valC->ID)>0) {
                            $arUsers[intval($val->ID)]["profiles"][intval($valC->ID)] = [
                                "GUID_contr" => $valC->GUID_Контрагент,
                                "ID" => intval($valC->ID),
                                "person_type" => (string)$valC->ВидКонтрагента,
                                "name" => (string)$valC->Наименование,
                                "phone" => (string)$valC->Телефон,
                                "mail" => (string)$valC->Почта,
                                "inn" => (string)$valC->ИНН,
                                "kpp" => (string)$valC->КПП,
                            ];
                        }
                    }
                }


            }
        }
    }



    if (!empty($arUsers)) {
        foreach ($arUsers as $usr) {

            $usr_id = '';
            $filter = Array("ID"=>$usr["ID"]);
            $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, ["SELECT" => array("UF_*")]);
            while ($arUser = $rsUsers->GetNext()) {
                $usr_id = $arUser["ID"];
            }

            $filter = Array("PERSONAL_PHONE"=>$usr["phone"]);
            $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, ["SELECT" => array("UF_*")]);
            while ($arUser = $rsUsers->GetNext()) {
                $usr_id = $arUser["ID"];
            }
            $filter = Array("EMAIL"=>$usr["mail"]);
            $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, ["SELECT" => array("UF_*")]);
            while ($arUser = $rsUsers->GetNext()) {
                $usr_id = $arUser["ID"];
            }
            $filter = Array("NAME"=>$usr["fio"]);
            $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter, ["SELECT" => array("UF_*")]);
            while ($arUser = $rsUsers->GetNext()) {
                $usr_id = $arUser["ID"];
            }



            if ($usr_id) {
                $filter = array("ID" => $usr_id);
                $rsUsers = CUser::GetList(($by = "ID"), ($order = "asc"), $filter, ["SELECT" => array("UF_*")]);
                while ($arUser = $rsUsers->GetNext()) {
                    $flag = 1;
                    $user = new CUser;
                    $fields = array("UF_PARTNER_GUID" => $GUID_partner, "UF_CONTACT_GUID" => $usr["GUID_contact"]);
                    $user->Update($arUser["ID"], $fields);

                    $arProfileIds = $arProfileINNs = $arProfilePhones = $arProfileEmails = $arProfileNames = [];
                    if (!empty($usr["profiles"])) {
                        foreach ($usr["profiles"] as $profile) {
                            $arProfileIds[$profile["ID"]] = $profile["ID"];
                            $arProfileINNs[$profile["ID"]] = $profile["inn"];
                            $arProfilePhones[$profile["ID"]] = $profile["phone"];
                            $arProfileEmails[$profile["ID"]] = $profile["mail"];
                            $arProfileNames[$profile["ID"]] = $profile["name"];
                        }
                    }

                    $arUserSales = [];
                    $db_sales = CSaleOrderUserProps::GetList(array("ID" => "ASC"), array("USER_ID" => $usr["ID"]));
                    while ($ar_sales = $db_sales->Fetch()) {
                        $arUserSales[] = $ar_sales["ID"];
                    }
                    if (!empty($arUserSales)) {
                        foreach ($arUserSales as $sale_id) {
                            if (in_array($sale_id, $arProfileIds)) {
                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"]
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 112;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 114;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 113;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 115;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                            } elseif (in_array($ar_sales["ID"], $arProfileINNs)) {
                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"]
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 112;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 114;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 113;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 115;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                            } elseif (in_array($ar_sales["ID"], $arProfilePhones)) {
                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"]
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 112;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 114;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 113;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 115;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                            } elseif (in_array($ar_sales["ID"], $arProfileEmails)) {
                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"]
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 112;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 114;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 113;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 115;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                            } elseif (in_array($ar_sales["ID"], $arProfileNames)) {
                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"]
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 112;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 114;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $ar_sales["ID"],
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner
                                );
                                if ($ar_sales["PERSON_TYPE_ID"] == 5) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 113;
                                }
                                if ($ar_sales["PERSON_TYPE_ID"] == 6) {
                                    $arFieldsProps["ORDER_PROPS_ID"] = 115;
                                }
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                            } else {

                                $arFields = array(
                                    "NAME" => $usr["profiles"][$ar_sales["ID"]]["name"],
                                    "USER_ID" => $usr["ID"],
                                );
                                if ($usr["profiles"][$ar_sales["ID"]]["person_type"] == "Физическое лицо") $arFields["PERSON_TYPE_ID"] = 6; else $arFields["PERSON_TYPE_ID"] = 5;
                                $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);

                                if ($usr["profiles"][$ar_sales["ID"]]["person_type"] == "Физическое лицо") {

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID контрагента",
                                        "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"],
                                        "ORDER_PROPS_ID" => 114,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID партнера",
                                        "VALUE" => $GUID_partner,
                                        "ORDER_PROPS_ID" => 115,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    if ($usr["profiles"][$ar_sales["ID"]]["name"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Контактное лицоа",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["name"],
                                            "ORDER_PROPS_ID" => 54,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($usr["profiles"][$ar_sales["ID"]]["mail"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "E-mail",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["mail"],
                                            "ORDER_PROPS_ID" => 55,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($usr["profiles"][$ar_sales["ID"]]["phone"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["phone"],
                                            "ORDER_PROPS_ID" => 56,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                } else {

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID контрагента",
                                        "VALUE" => $usr["profiles"][$ar_sales["ID"]]["GUID_contr"],
                                        "ORDER_PROPS_ID" => 112,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID партнера",
                                        "VALUE" => $GUID_partner,
                                        "ORDER_PROPS_ID" => 113,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    if ($usr["profiles"][$ar_sales["ID"]]["name"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Контактное лицоа",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["name"],
                                            "ORDER_PROPS_ID" => 43,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($usr["profiles"][$ar_sales["ID"]]["mail"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "E-mail",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["mail"],
                                            "ORDER_PROPS_ID" => 51,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($usr["profiles"][$ar_sales["ID"]]["phone"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["phone"],
                                            "ORDER_PROPS_ID" => 52,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($usr["profiles"][$ar_sales["ID"]]["inn"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $usr["profiles"][$ar_sales["ID"]]["inn"],
                                            "ORDER_PROPS_ID" => 45,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                }


                            }
                        }
                    } else {


                        if (!empty($usr["profiles"])) {
                            foreach ($usr["profiles"] as $profile) {

                                $arFields = array(
                                    "NAME" => $profile["name"],
                                    "USER_ID" => $ID,
                                );
                                if ($profile["person_type"] == "Физическое лицо") $arFields["PERSON_TYPE_ID"] = 6; else $arFields["PERSON_TYPE_ID"] = 5;
                                $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);

                                if ($profile["person_type"] == "Физическое лицо") {

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID контрагента",
                                        "VALUE" => $profile["GUID_contr"],
                                        "ORDER_PROPS_ID" => 114,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID партнера",
                                        "VALUE" => $GUID_partner,
                                        "ORDER_PROPS_ID" => 115,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    if ($profile["name"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Контактное лицоа",
                                            "VALUE" => $profile["name"],
                                            "ORDER_PROPS_ID" => 54,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($profile["mail"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "E-mail",
                                            "VALUE" => $profile["mail"],
                                            "ORDER_PROPS_ID" => 55,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($profile["phone"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $profile["phone"],
                                            "ORDER_PROPS_ID" => 56,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                } else {

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID контрагента",
                                        "VALUE" => $profile["GUID_contr"],
                                        "ORDER_PROPS_ID" => 112,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "GUID партнера",
                                        "VALUE" => $GUID_partner,
                                        "ORDER_PROPS_ID" => 113,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);

                                    if ($profile["name"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Контактное лицоа",
                                            "VALUE" => $profile["name"],
                                            "ORDER_PROPS_ID" => 43,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($profile["mail"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "E-mail",
                                            "VALUE" => $profile["mail"],
                                            "ORDER_PROPS_ID" => 51,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($profile["phone"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $profile["phone"],
                                            "ORDER_PROPS_ID" => 52,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                    if ($profile["inn"]) {
                                        $arFieldsProps = array(
                                            "USER_PROPS_ID" => $USER_PROPS_ID,
                                            "NAME" => "Телефон",
                                            "VALUE" => $profile["inn"],
                                            "ORDER_PROPS_ID" => 45,
                                        );
                                        CSaleOrderUserPropsValue::Add($arFieldsProps);
                                    }

                                }
                            }
                        }


                    }


                }
            } else {

                $login = 'user_'.time();
                if ($usr["mail"]) $login = $usr["mail"];
                elseif ($usr["fio"]) $login = $usr["fio"];

                $pass = substr(md5(microtime()), 0, 10);
                $user = new CUser;
                $arFields = Array(
                    "NAME"              => $usr["fio"],
                    "EMAIL"             => $usr["mail"],
                    "LOGIN"             => $login,
                    "ACTIVE"            => "Y",
                    "GROUP_ID"          => array(3,5,6),
                    "PASSWORD"          => $pass,
                    "CONFIRM_PASSWORD"  => $pass,
                    "PERSONAL_PHONE"    => $usr["phone"]
                );
                $ID = $user->Add($arFields);

                if ($ID) {

                    if (!empty($usr["profiles"])) {
                        foreach ($usr["profiles"] as $profile) {

                            $arFields = array(
                                "NAME" => $profile["name"],
                                "USER_ID" => $ID,
                            );
                            if ($profile["person_type"] == "Физическое лицо") $arFields["PERSON_TYPE_ID"] = 6; else $arFields["PERSON_TYPE_ID"] = 5;
                            $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);

                            if ($profile["person_type"] == "Физическое лицо") {

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $USER_PROPS_ID,
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $profile["GUID_contr"],
                                    "ORDER_PROPS_ID" => 114,
                                );
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $USER_PROPS_ID,
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner,
                                    "ORDER_PROPS_ID" => 115,
                                );
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                if ($profile["name"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "Контактное лицоа",
                                        "VALUE" => $profile["name"],
                                        "ORDER_PROPS_ID" => 54,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                                if ($profile["mail"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "E-mail",
                                        "VALUE" => $profile["mail"],
                                        "ORDER_PROPS_ID" => 55,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                                if ($profile["phone"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "Телефон",
                                        "VALUE" => $profile["phone"],
                                        "ORDER_PROPS_ID" => 56,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                            } else {

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $USER_PROPS_ID,
                                    "NAME" => "GUID контрагента",
                                    "VALUE" => $profile["GUID_contr"],
                                    "ORDER_PROPS_ID" => 112,
                                );
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                $arFieldsProps = array(
                                    "USER_PROPS_ID" => $USER_PROPS_ID,
                                    "NAME" => "GUID партнера",
                                    "VALUE" => $GUID_partner,
                                    "ORDER_PROPS_ID" => 113,
                                );
                                CSaleOrderUserPropsValue::Add($arFieldsProps);

                                if ($profile["name"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "Контактное лицоа",
                                        "VALUE" => $profile["name"],
                                        "ORDER_PROPS_ID" => 43,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                                if ($profile["mail"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "E-mail",
                                        "VALUE" => $profile["mail"],
                                        "ORDER_PROPS_ID" => 51,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                                if ($profile["phone"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "Телефон",
                                        "VALUE" => $profile["phone"],
                                        "ORDER_PROPS_ID" => 52,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                                if ($profile["inn"]) {
                                    $arFieldsProps = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "NAME" => "Телефон",
                                        "VALUE" => $profile["inn"],
                                        "ORDER_PROPS_ID" => 45,
                                    );
                                    CSaleOrderUserPropsValue::Add($arFieldsProps);
                                }

                            }
                        }
                    }


                }

            }

        }
    }


}
?>