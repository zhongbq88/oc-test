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
		$fulfillments = array(
    		"tracking_company"=> 'China Post',
    		"tracking_number"=> "1Z2345",
    		"tracking_url"=> "https://www.aftership.com/",
			'line_items'=>array(
				0=>array('id'=>9963689096)
			)
		);
		//$update = array('order'=>array("id"=>5238292616,'fulfillments' =>$fulfillments));
		print_r($fulfillments);
		$product = $shopify('POST /admin/orders/5238292616/fulfillments.json', array(),array('fulfillment'=>$fulfillments));
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