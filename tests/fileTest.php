<?php


class fileTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->c = new Cache('file', __DIR__ . '/tmp');
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

    function testCallBack()
    {
        $result = $this->c->get('key3', array($this,'getArray1'));

        $this->assertEquals(6, array_sum($result));

        // if get cache, the data will not be set
        $result = $this->c->get('key3', array($this,'getArray2'));

        $this->assertEquals(6, array_sum($result));

        $result = $this->c->ns('namespace1')->get('key4', array($this,'getArray3'));

        $result = $this->c->ns('namespace1')->get('key4');
        $this->assertEquals(3, array_sum($result));
    }

    function getArray1()
    {
        return array(2,2,2);
    }

    function getArray2()
    {
        return array(2,2,2,2);
    }

    function getArray3()
    {
        return array(1,1,1);
    }

    public function tearDown()
    {
        $this->c = null;
        MyDelete(__DIR__ . '/tmp');
    }
}