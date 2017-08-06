<?php
	session_start();

	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;

	require __DIR__.'/conf.php';

	function addProducts($products,$shop,$oauth_token){
		echo "shop=".$shop;
		echo "oauth_token=".$oauth_token;
		//print_r($products);
		
		$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $oauth_token);

	try
	{
		# Making an API request can throw an exception
		$product = $shopify('POST /admin/products.json', array(), $products);

		print_r($product);
		return true;
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
	return false;
	}
?>