<?php

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    private $helper;

    public function setUp(): void
    {
        parent::setUp();
        $this->helper = new \Core\Helper();
    }

    public function testIsJsonValid()
    {
        $string = '[{"_id":{"$oid":"5d4734198f5c8200077355ce"},"name":"Container name","products":[{"id":12321,"name":"Product 12321"},{"id":12322,"name":"Product 12322"}]}]';
        $this->assertEquals($this->helper->isJson($string), 1);
    }

    public function testIsJsonFail()
    {
        $string = 'some text';
        $this->assertNotEquals($this->helper->isJson($string), 1);
    }

}
