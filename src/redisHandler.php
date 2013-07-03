<?php
	
/**
*  quqiang
*/


class redisHandler extends cacheHandler
{
	private $config = NULL;
	private $client = NULL;
	function __construct($config = NULL)
	{
		$this->config = array(
		    'host'     => 'geek-zoo.com',
		    'port'     => 6379,
		    'database' => 15
		);
		$this->client = new Predis\Client($this->config);
	}
	
    public function delete($key)
    {
        if ($this->ns) {
			$this->client->del($this->ns);
		} else {
			$this->client->del($key);
		}
		return true;
    }

    public function get($key)
    {
        if ($this->ns) {
			$this->client->hget($this->ns, $key);
		} else {
			$this->client->get($key);
		}
    }

    public function set($key, $value, $expire)
    {
        if ($this->ns) {
			$this->client->hset($this->ns, $key, $value);
		} else {
			$this->client->set($key, $value);
			if (!empty($expire)) {
				$this->client->expire($key, $expire);
			}
		}
    }
}

?>