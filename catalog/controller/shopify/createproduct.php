<?php

require __DIR__.'/vendor/autoload.php';
use phpish\shopify;

require __DIR__.'/conf.php';

class ControllerShopifyCreateproduct extends Controller {
	public function index(){
		
		$shopify = shopify\client($this->session->data['shop'], SHOPIFY_APP_API_KEY, $this->session->data['oauth_token']);
		try
		{
			//print_r();
			echo print_r($this->session->data['product']);
			# Making an API request can throw an exception
			$product = $shopify('POST /admin/products.json', array(), array('product' => $this->session->data['product']));
			print_r($product);
			unset($this->session->data['product']);
			//return true;
		}
		catch (shopify\ApiException $e)
		{
			# HTTP status code was >= 400 or response contained the key 'errors'
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		catch (shopify\CurlException $e)
		{
			# cURL error
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		//return false;
			$this->response->redirect($this->url->link('shopify/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
}

?>