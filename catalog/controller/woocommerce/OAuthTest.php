<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

class OAuthTest 
{

    protected $oAuth;

    public function setUp()
    {
        $url = 'http://34.201.28.35';
		
		$store_url = 'http://34.201.28.35';
$endpoint = '/wc-auth/v1/authorize';
$params = [
    'app_name' => 'My App Name',
    'scope' => 'read_write',
    'user_id' => 123,
    'return_url' => 'https://www.customdr.com',
    'callback_url' => 'https://www.customdr.com'
];
$query_string = http_build_query( $params );

$url = $store_url . $endpoint . '?' . $query_string;
		echo $url;
        $consumerKey = 'ck_194319d29796bd5461a73bddd21db0ff980655d8';
        $consumerSecret = 'cs_3eb9b1a8950d474f1fe410e606faf0133e14b0bc';
        $this->oAuth = new \Automattic\WooCommerce\HttpClient\OAuth($url, $consumerKey, $consumerSecret, 'v3', 'POST');
    }

    public function testGetParameters()
    {
        $parameters = $this->oAuth->getParameters();
        $keys = [
            'oauth_consumer_key',
            'oauth_nonce',
            'oauth_signature',
            'oauth_signature_method',
            'oauth_timestamp',
        ];
		print_r($parameters);

        //$this->assertEquals($keys, array_keys($parameters));
       // $this->assertEquals('ck_xxx', $parameters['oauth_consumer_key']);
       // $this->assertEquals('HMAC-SHA256', $parameters['oauth_signature_method']);
    }
}
$test = new OAuthTest();
$test->setUp();
//$test->testGetParameters();