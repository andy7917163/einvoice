<?php

namespace Andy7917163\Einvoice;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Einvoice
{
    public $version;

    public $type;

    public $action;

    public $appID;

    public $generation;

    public $UUID;

    public $invTerm;

    public $invNum;

    public $randomNumber;

    public $base_uri;

    public $uri;

    public $cardType;

    public $cardNo;

    public $timeStamp;

    public $startDate;

    public $endDate;

    public $cardEncrypt;

    public $invDate;

    private $expTimeStamp;

    private $timeStampDelay = 180;

    private $timeStampExp = 1800;

    private $invoice_info;

    private $error;

    private $connect_timeout = 30;

    public function __construct($appID)
    {
        $this->base_uri = 'https://api.einvoice.nat.gov.tw';
        $this->version = '0.5';
        $this->appID = $appID;
        $this->UUID = (string) Str::uuid();
        $this->cardType = '3J0002';
        $this->invoice_info = null;
    }

    /**
     * @throws Exception
     */
    public static function fromAppId($appID): Einvoice
    {
        if (!$appID) {
            throw new Exception('AppID invalid.');
        }
        return new self($appID);
    }

    public function getInvoice()
    {
        $this->getInvoiceInfo();
        return $this->invoice_info;
    }

    public function getCard()
    {
        $this->getCardInfo();
        return $this->invoice_info;
    }

    public function getHead()
    {
        $this->getInvoiceHead();
        return $this->invoice_info;
    }

    public function getCarddetails()
    {
        $this->getCarrierInvDetail();
        return $this->invoice_info;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setTimeOut(int $second)
    {
        $this->connect_timeout = $second;
    }

    private function getInvoiceInfo()
    {
        $this->uri = '/PB2CAPIVAN/invapp/InvApp';
        $this->type = 'Barcode';
        $this->action = 'qryInvDetail';
        $this->generation = 'V2';

        $queryData = [
            'version' =>      $this->version,
            'type' =>         $this->type,
            'action' =>       $this->action,
            'appID' =>        $this->appID,
            'generation' =>   $this->generation,
            'UUID' =>         $this->UUID,
            'invTerm' =>      $this->invTerm,
            'invNum' =>       $this->invNum,
            'randomNumber' => $this->randomNumber,
        ];
        $this->doRequest($queryData);
    }

    private function getInvoiceHead()
    {
        $this->uri = '/PB2CAPIVAN/invapp/InvApp';
        $this->type = 'Barcode';
        $this->action = 'qryInvHeader';
        $this->generation = 'V2';

        $queryData = [
            'version' =>      $this->version,
            'type' =>         $this->type,
            'action' =>       $this->action,
            'appID' =>        $this->appID,
            'generation' =>   $this->generation,
            'UUID' =>         $this->UUID,
            'invDate' =>      $this->invTerm,
            'invNum' =>       $this->invNum,
        ];

        $this->doRequest($queryData);
    }

    private function getCardInfo()
    {
        $this->uri = '/PB2CAPIVAN/invServ/InvServ';
        $this->action = 'carrierInvChk';
        $this->timeStamp = strval(time() + $this->timeStampDelay);
        $this->expTimeStamp = strval(time() + $this->timeStampExp);

        $queryData = [
            'version' =>        $this->version,
            'cardType' =>       $this->cardType,
            'cardNo' =>         $this->cardNo,
            'expTimeStamp' =>   $this->expTimeStamp,
            'action' =>         $this->action,
            'timeStamp' =>      $this->timeStamp,
            'startDate' =>      $this->startDate,
            'endDate' =>        $this->endDate,
            'onlyWinningInv' => 'N',
            'uuid' =>           $this->UUID,
            'appID' =>          $this->appID,
            'cardEncrypt' =>    $this->cardEncrypt,
        ];

        $this->doRequest($queryData);
    }

    private function getCarrierInvDetail()
    {
        $this->uri = '/PB2CAPIVAN/invServ/InvServ';
        $this->action = 'carrierInvDetail';
        $this->timeStamp = strval(time() + $this->timeStampDelay);
        $this->expTimeStamp = strval(time() + $this->timeStampExp);

        $queryData = [
            'version' =>        $this->version,
            'cardType' =>       $this->cardType,
            'cardNo' =>         $this->cardNo,
            'expTimeStamp' =>   $this->expTimeStamp,
            'action' =>         $this->action,
            'timeStamp' =>      $this->timeStamp,
            'invNum' =>         $this->invNum,
            'invDate' =>        $this->invDate,
            'uuid' =>           $this->UUID,
            'appID' =>          $this->appID,
            'cardEncrypt' =>    $this->cardEncrypt,
        ];

        $this->doRequest($queryData);
    }

    private function doRequest($queryData)
    {
        try {
            $client = new Client(['base_uri' => $this->base_uri]);
            $response = $client->request('POST', $this->uri, [
                'query' => $queryData,
                'connect_timeout' => $this->connect_timeout,
                'verify' => true
            ]);

            $this->invoice_info = json_decode($response->getBody(), true);

        } catch (\Throwable $e) {
            $this->error = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            $this->invoice_info = false;
        }
    }
}
