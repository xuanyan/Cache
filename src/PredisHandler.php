<?php
	
/**
*  quqiang
*/


class PredisHandler extends cacheHandler
{
    private $client = NULL;

    function __construct($config = NULL)
    {
        $this->client = new Predis\Client($config);
    }

    public function delete($key)
    {
        if (!$this->ns) {
            return $this->client->del($key);
        }

        if (!$key) {
            return $this->client->del($this->ns);
        }

        return $this->client->hdel($this->ns, $key);
    }

    public function get($key)
    {
        if (!$this->ns) {
            $result = $this->client->get($key);

            return $result === false ? false : json_decode($result, true);
        }

        $data = $this->client->hget($this->ns, $key);

        $data = json_decode($data, true);

        if (!isset($data['data'])) {
            return false;
        }

        if (intval($data['expire']) < time()) {
            $this->client->hdel($this->ns, $key);
            return false;
        }


        return $data['data'];
    }

    public function set($key, $value, $expire = 3600)
    {
        if (!$this->ns) {
            $value = json_encode($value);
            $this->client->set($key, $value);
            if (!empty($expire)) {
                $this->client->expire($key, $expire);
            }
            return true;
        }

        $value = array('data'=>$value, 'expire'=>time()+intval($expire));
        $value = json_encode($value);

        return $this->client->hset($this->ns, $key, $value);
    }
}

?>