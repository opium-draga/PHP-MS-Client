<?php

use SphereMall\MS\Client;
use SphereMall\MS\Services\BaseService;
use SphereMall\MS\Services\Products\Entities\Product;
use SphereMall\MS\Services\Products\ProductService;

/**
 * Created by PHPStorm.
 * User: Serhii Kondratovec
 * Email: sergey@spheremall.com
 * Date: 10/8/2017
 * Time: 4:52 PM
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException Exception
     */
    public function testClientObjectCreatedNotConfigured()
    {
        $client = new Client();
    }

    public function testClientObjectCreatedWithConfiguration()
    {
        $client = new Client([
            'gatewayUrl' => 'API_URL',
            'clientId'   => 'API_CLIENT_ID',
            'secretKey'  => 'API_SECRET_KEY',
            'version'    => 'API_VERSION',
        ]);

        $this->assertEquals('API_URL', $client->getGatewayUrl());
        $this->assertEquals('API_CLIENT_ID', $client->getClientId());
        $this->assertEquals('API_SECRET_KEY', $client->getSecretKey());
        $this->assertEquals('API_VERSION', $client->getVersion());
    }

    public function testClientCallService()
    {
        $client = new Client([
            'gatewayUrl' => 'API_URL',
            'clientId'   => 'API_CLIENT_ID',
            'secretKey'  => 'API_SECRET_KEY',
            'version'    => 'API_VERSION',
        ]);

        $productService = $client->call(Product::class);

        $this->assertInstanceOf(BaseService::class, $productService);
        $this->assertInstanceOf(ProductService::class, $productService);
    }
}
