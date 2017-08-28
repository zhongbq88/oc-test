<?php
require __DIR__ . '/vendor/autoload.php';
use Automattic\WooCommerce\Client;

class Woocommerce{
	
    private static $instances = array();
    private $client;
	private $consumerKey = 'ck_194319d29796bd5461a73bddd21db0ff980655d8';
    private $consumerSecret = 'cs_3eb9b1a8950d474f1fe410e606faf0133e14b0bc';
	private $api = array(
       			 'wp_api' => true,
       			'version' => 'wc/v1'
			 );

    private function __construct($store){ 
       	 $this->client = new Client($store, $this->consumerKey, $this->consumerSecret, $this->api);
    }
	
    public static function getInstance($store){
        $key = "{$store}";
        if (empty(self::$instances[$key])){
            $class = __CLASS__;
            self::$instances[$key] = new $class($store);
        }
        return self::$instances[$key];
    }

    public function __clone(){
        throw new Exception("Singleton Class Can Not Be Cloned");
    }

    public function get($parameters){
        return $this->client->get($parameters);
    }
	
	public function post($data){
        return $this->client->post($data);
    }
	
	public function put($data){
        return $this->client->put($data);
    }
	
	public function delete($parameters = array()){
        return $this->client->delete($parameters);
    }
	
	public function options(){
        return $this->client->options();
    }

    public function __destruct(){
        $this->Client=null;
    }
}

?>