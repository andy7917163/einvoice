<?php


namespace Andy7917163\Einvoice\Tests;

use Andy7917163\Einvoice\Einvoice;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Testing\Fluent\AssertableJson;

final class EinvoiceTest extends TestCase
{
    public function testCanInvoiceDetails()
    {
        $appID = 'EINV1202001234567';
        $client = Einvoice::fromAppId($appID);
        $client->invNum = 'FB89333038';
        $client->invTerm = '10910';
        $client->randomNumber = '1235';

        $json = '{"msg":"執行成功","code":"200","invNum":"FB89333038","invoiceTime":"15:13:00","invStatus":"已確認","sellerName":"全家便利商店股份有限公司基隆市第五門市部","invPeriod":"10910","sellerAddress":"基隆市中正區北寧路２號","sellerBan":"42092179","buyerBan":"","currency":"","details":[{"unitPrice":"35","amount":"70","quantity":"2","rowNum":"1","description":"舒味思萊姆口味氣泡水"},{"unitPrice":"0","amount":"-35","quantity":"1","rowNum":"2","description":"會員促"}],"invDate":"20201028"}';

        $this->assertJsonStringEqualsJsonString($json, json_encode($client->getInvoice()));
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