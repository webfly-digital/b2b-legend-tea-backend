<?php

namespace Webfly\Upload;

use Bitrix\Highloadblock;
use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;


Loader::includeModule("highloadblock");

class RequestQueueIn1c
{
    protected $HL = 16;
    protected $entity_data_class = false;

    public function __construct()
    {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->HL)->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $this->entity_data_class = $entity->getDataClass();
    }

    public function createRecord($id, $type)
    {
        if ($type == "ADD_USER") $typeValue = 380;
        if ($type == "UPDATE_USER") $typeValue = 381;

        $dataDefault = [
            'UF_ID' => $id,
            'UF_TYPE' => $typeValue
        ];

        $res = $this->entity_data_class::add($dataDefault);
        if ($res->isSuccess()) {
            return $res->getId();
        }
        return $res;
    }

    public function updateRecord($id, $data)
    {
        $res = $this->entity_data_class::update($id, $data);
        return $res;
    }

    public function getById($id)
    {
        $rsData = $this->entity_data_class::getList([
            "select" => ["*"],
            "filter" => ["ID" => $id]
        ]);

        while ($arData = $rsData->Fetch()) {
            return $arData;
        }

        return [];
    }


}