<?php

namespace Webfly\Upload;

use \Bitrix\Main\Application,
    \Bitrix\Main\Web\Uri,
    \Bitrix\Main\Web\HttpClient;

class RequestTo1C
{

    public $options = [];
    public $httpClient = [];
    public $url = '';
    const URL_USER = 'https://1c.legend-tea.ru/legenda_b24/hs/bitrix24/';
    public $writeLog = true;

    public function __construct()
    {
        $this->options = array(
            "redirect" => true, // true, если нужно выполнять редиректы
            "redirectMax" => 5, // Максимальное количество редиректов
            "waitResponse" => true, // true - ждать ответа, false - отключаться после запроса
            "socketTimeout" => 30, // Таймаут соединения, сек
            "streamTimeout" => 60, // Таймаут чтения ответа, сек, 0 - без таймаута
            "version" => HttpClient::HTTP_1_0, // версия HTTP (HttpClient::HTTP_1_0 или HttpClient::HTTP_1_1)
            "proxyHost" => "", // адрес
            "proxyPort" => "", // порт
            "proxyUser" => "", // имя
            "proxyPassword" => "", // пароль
            "compress" => false, // true - принимать gzip (Accept-Encoding: gzip)
            "charset" => "", // Кодировка тела для POST и PUT
            "disableSslVerification" => true, // true - отключить проверку ssl (с 15.5.9)
        );
        $this->httpClient = new HttpClient($this->options);
    }

    public function setUrl($type)
    {
        $this->url = self::URL_USER . $type . '/';
    }


    public function executePostRequest($arrayData, $typeUrl = '')
    {
        self::addLog($arrayData);
        $res = ['result' => false];
        $error = false;

        if (!empty($typeUrl)) $this->setUrl($typeUrl);
        $arrayData['token'] = '4wj086kgykggh19foa841qezp59egzol';
        $postData = json_encode($arrayData);

        self::addLog($postData);

        $responseJson = $this->httpClient->post($this->url, $postData); // Возвращает тело ответа
        $errServer = $this->httpClient->getError();
        $status = $this->httpClient->getStatus();

        self::addLog($responseJson);

        if (empty($errServer) && $status == 200) {

            if (!empty($responseJson)) {
                $response = json_decode($responseJson, true);

                if ($response["result"]) {
                    if ($response["result"] == 'ERROR') $error = true;

                } else  $error = true;
            } else $error = true;

        } else $error = true;


        if ($error) {
            $this->writeLog = true;
            self::addLog('$response ' . $response);
            self::addLog('$responseJson ' . $responseJson);
            self::addLog('getStatus ' . $status);
            self::addLog('getError ' . $errServer);
        }


        $res = ['result' => $response];
        self::addLog($res);
        return $res;
    }


    public function addLog($array = [])
    {
        if (!$this->writeLog) return;
        \Webfly\Helper\Log::Add(print_r($array, true), false, false, 'logsRequestTo1C');
    }
}