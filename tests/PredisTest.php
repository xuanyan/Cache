<?php
/**
 * @requires PHP 5.3
 */
class PredisTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->c = new Cache('Predis', array(
            'host'     => 'geek-zoo.com',
            'port'     => 6379,
            'database' => 12
        ));
    }

    function testSetNoneNsValue()
    {
        $result = $this->c->set('key1', '123456');
        $this->assertEquals(true, $result);

        $result = $this->c->get('key1');
        $this->assertEquals('123456', $result);

        $this->c->delete('key1');

        $result = $this->c->get('key1');
        $this->assertEquals(false, $result);

        $result = $this->c->set('key1', array(1,2,3), 2);
        $result = $this->c->get('key1');
        $this->assertEquals(6, array_sum($result));
        
        sleep(3);
        $result = $this->c->get('key1');
        $this->assertEquals(false, $result);
    }

    function testSetANsValue()
    {
        $result = $this->c->ns('namespace')->set('key1', '654321');
        $this->assertEquals(true, $result);

        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals('654321', $result);

        $this->c->ns('namespace')->delete('key1');

        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals(false, $result);


        $result = $this->c->ns('namespace')->set('key1', array(1,2,3), 2);
        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals(6, array_sum($result));
        
        sleep(3);
        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals(false, $result);
    }

    function testNamespaceDelete()
    {
        $result = $this->c->ns('namespace')->set('key1', '654321');
        $result = $this->c->ns('namespace')->set('key2', array(1,2,3));
        
        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals('654321', $result);

        $result = $this->c->ns('namespace')->get('key2');
        $this->assertEquals(6, array_sum($result));

        $this->c->ns('namespace')->delete();

        $result = $this->c->ns('namespace')->get('key1');
        $this->assertEquals(false, $result);

        $result = $this->c->ns('namespace')->get('key2');
        $this->assertEquals(false, $result);
    }

    public function tearDown()
    {
        $this->c = null;
    }
}