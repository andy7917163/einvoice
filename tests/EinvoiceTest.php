<?php


namespace Andy7917163\Einvoice\Tests;

use Andy7917163\Einvoice\Einvoice;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Testing\Fluent\AssertableJson;

final class EinvoiceTest extends TestCase
{
    public function testCanInvoiceDetails()
    {
        $appID = 'EINV1202007279345';
        $client = Einvoice::fromAppId($appID);
        $client->invNum = 'GL53778311';
//        $client->invTerm = '11112';
        $client->invTerm = '2022/12/02';
        $client->randomNumber = '5923';

        $json = '{"msg":"執行成功","code":"200","invNum":"GL53778311","invoiceTime":"10:51:06","invStatus":"已確認","sellerName":"全聯實業股份有限公司屏東大連分公司","invPeriod":"11112","sellerAddress":"屏東縣屏東市大連路21號","sellerBan":"28414044","buyerBan":"","v":"0.5","currency":"","invDate":"20221202"}';
        $head = json_encode($client->getHead());
        $this->assertJsonStringEqualsJsonString($json, $head);

        if (isset($head->invPeriod)) {
            $client->invTerm = $head->invPeriod;
            $json = '{"msg":"執行成功","code":"200","invNum":"GL53778311","invoiceTime":"10:51:06","invStatus":"已確認","sellerName":"全聯實業股份有限公司屏東大連分公司","invPeriod":"11112","sellerAddress":"屏東縣屏東市大連路21號","sellerBan":"28414044","buyerBan":"","currency":"","details":[{"unitPrice":"699","amount":"699","quantity":"1","rowNum":"001","description":"克寧奶粉"}],"invDate":"20221202"}';
            $this->assertJsonStringEqualsJsonString($json, json_encode($client->getInvoice()));
        }
    }

    public function testCanBeCreatedFromAppId()
    {
        $this->assertInstanceOf(
            Einvoice::class,
            Einvoice::fromAppId('EINV1202007279345')
        );
    }

    public function testCanNotBeCreatedFromNullAppId()
    {
        $this->expectException(InvalidArgumentException::class);
        Einvoice::fromAppId(null);
    }
}