<?php

/*
 * This file is part of the Geek-Zoo Projects.
 *
 * @copyright (c) 2013 Geek-Zoo Projects More info http://www.geek-zoo.com
 * @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
 * @author xuanyan <xuanyan@geek-zoo.com>
 *
 */

class Cache
{
    private $cacheHandler = null;
    private $ns = '';
    private $lastKey = '';

    function __construct($type = null, $config = null)
    {
        if ($type) {
            $class = "{$type}Handler";
            $file = require_once dirname(__FILE__)."/{$class}.php";
            $this->cacheHandler = new $class($config);
        }
    }

    public function setHandler($obj)
    {
        $this->cacheHandler = $obj;
    }

    public function ns($key)
    {
        $this->cacheHandler->ns = $key;

        return $this;
    }

    public function delete($key = null)
    {
        $this->cacheHandler->delete($key);
        $this->cacheHandler->ns = null;

        return true;
    }

    public function get($key)
    {
        $result = $this->cacheHandler->get($key);

        $this->cacheHandler->ns = null;

        return $result;
    }

    public function set($key, $value = array(), $expire = 3600)
    {
        $this->cacheHandler->set($key, $value, $expire);
        $this->cacheHandler->ns = null;
    }
}


abstract class cacheHandler
{
    public $ns = null;

    public function delete($key)
    {
        throw new Exception("u must rewrite the 'delete' method");
    }

    public function get($key)
    {
        throw new Exception("u must rewrite the 'get' method");
    }

    public function set($key, $value, $expire)
    {
        throw new Exception("u must rewrite the 'set' method");
    }
}