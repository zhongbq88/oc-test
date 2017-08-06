<?php

	session_start();

	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;

	require __DIR__.'/conf.php';

	# Guard: http://docs.shopify.com/api/authentication/oauth#verification
	//shopify\is_valid_request($_GET, SHOPIFY_APP_SHARED_SECRET) or die('Invalid Request! Request or redirect did not come from Shopify');


	# Step 2: http://docs.shopify.com/api/authentication/oauth#asking-for-permission
		class ControllerShopifyOauth extends Controller {
			public function index(){
					if (!isset($_GET['code']))
	{
		$permission_url = shopify\authorization_url($_GET['shop'], SHOPIFY_APP_API_KEY, array('read_products', 'read_customers', 'write_products','read_orders', 'write_orders', 'read_fulfillments', 'write_fulfillments'),REDIRECTION_URL);
		die("<script> top.location.href='$permission_url'</script>");
	}


	}
		}

?>