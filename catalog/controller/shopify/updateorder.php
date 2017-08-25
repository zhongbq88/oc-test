<?php 

require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';

class ControllerShopifyUpdateorder extends Controller {

public function index(){

	$shopify = shopify\client($this->session->data['shop'], SHOPIFY_APP_API_KEY, $this->session->data['oauth_token']);
	$json = array();
	try
	{
		//$order_id = $this->request->post['order_id'];
		$fulfillments = array();
		$fulfillments[] = array(
			"created_at"=> "2017-08-25T13:09:54-04:00",
			"order_id"=>5238292616,
    		"status"=> "failure",
    		"tracking_company"=> 'China Post',
    		"tracking_number"=> "1Z2345",
    		"updated_at"=> "2017-08-25T13:09:54-04:00"
		);
		$update = array('order'=>array("id"=>5238292616,'fulfillments' =>$fulfillments));
		print_r($update);
		$product = $shopify('PUT /admin/orders/5238292616.json', array(),$update);
		print_r($product);
	}
	catch (shopify\ApiException $e)
	{
		# HTTP status code was >= 400 or response contained the key 'errors'
		echo $e;
		print_r($e->getRequest());
		print_r($e->getResponse());
	}
	catch (shopify\CurlException $e)
	{
		# cURL error
		echo $e;
		print_r($e->getRequest());
		print_r($e->getResponse());
	}
}
}
?>