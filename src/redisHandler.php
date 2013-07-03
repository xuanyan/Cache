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
			$this->client->hdel($this->ns, $key);
		} else {
			$this->client->del($key);
		}
		return true;
    }

    public function get($key)
    {
        if ($this->ns) {
			$data = $this->client->hget($this->ns, $key);
		} else {
			$data = $this->client->get($key);
		}
		$data = json_decode($data);
		if (empty($data['data'])) {
			return '';
		} else {
			if (intval($data['expire']) < time()) {
				return '';
			}
			return $data['data'];
		}
    }

    public function set($key, $value, $expire = 3600)
    {
		$value = array('data'=>$value, 'expire'=>time()+intval($expire));
		$value = json_encode($value);
        if ($this->ns) {
			$this->client->hset($this->ns, $key, $value);
		} else {
			$this->client->set($key, $value);
			if (!empty($expire)) {
				$this->client->expire($key, $expire);
			}
		}
		return true;
    }
}

?>