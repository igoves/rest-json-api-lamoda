<?php

use PHPUnit\Framework\TestCase;

class CoreTest extends TestCase
{

    private $core;
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->core = new \Core\Core();
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://web'
        ]);
    }

    /**
     * @depends testGenerator
     */
    public function testContainersList()
    {
        $response = $this->client->get('/containers.json');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayHasKey('products', $data[0]);
    }

    /**
     * @depends testContainersList
     */
    public function testContainersProducts()
    {
        $response = $this->client->get('/containers/products.json');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertTrue(is_array($data));
    }

    public function testGenerator()
    {
        $_POST['qty_containers'] = 1000;
        $_POST['capacity_container'] = 10;
        $_POST['unique_products'] = 100;

        $this->assertEquals($this->core->generator(), 'Data successfully generated!');

    }

    public function testInit()
    {
        $this->assertArrayHasKey('total_containers', $this->core->init());
    }

}
