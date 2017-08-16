<?php

session_start();
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;

require __DIR__.'/conf.php';

class ControllerShopifyGetorders extends Controller {
	public function index(){
		//echo $_SESSION['shop']."--".$_SESSION['oauth_token'];
		$shopify = shopify\client($_SESSION['shop'], SHOPIFY_APP_API_KEY, $_SESSION['oauth_token']);

	try
	{
		
		//echo REDIRECTION_URL;
		//print_r();
		//echo print_r('product='.$_SESSION['product']);
		# Making an API request can throw an exception
		
		$this->load->model('account/customer');
		$customer_info = array();
		if ($this->customer->isLogged()) {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
				
		}
		$this->load->model('shopify/order');
		$adddate = $this->model_shopify_order->getOrderLastAddDate($customer_info['customer_group_id']);
		$adddate = str_replace(' ',"T",$adddate)."+00:00";
		//print_r(date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000)));
		$orders = $shopify('GET /admin/orders.json?status=any'/*&processed_at_min='.$adddate.'&created_at_min='.$adddate*/);
		$this->load->model('localisation/order_status');
		//print_r($orders);
		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

		foreach($orders as $order){
			$od = $this->initOrder($order,$order_statuses,$customer_info);
			
			//print_r($od);
			$order_id = $this->model_shopify_order->addOrder($od);
			//echo $order_id;
		}
		
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
		//$this->response->redirect($this->url->link('shopify/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	
	public function initOrder($order,$order_statuses,$customer_info){
	
	$order_data =  array(
				'order_id'                => $order['id'],
				'email'                   => $order['email'],
				'telephone'               => isset($order['shipping_address'])?$order['shipping_address']['phone']:'',
				'custom_field'            => json_encode($order['line_items']),
				'payment_firstname'       => $this->getValue($order,'payment_firstname'),
				'payment_lastname'        => $this->getValue($order,'payment_lastname'),
				'payment_company'         => $this->getValue($order,'payment_company'),
				'payment_address_1'       => $this->getValue($order,'payment_address_1'),
				'payment_address_2'       => $this->getValue($order,'payment_address_2'),
				'payment_postcode'        => $this->getValue($order,'payment_postcode'),
				'payment_city'            => $this->getValue($order,'payment_city'),
				'payment_zone_id'         => 3528,//$this->getValue($order,'payment_zone_id'),
				'payment_zone'            => $this->getValue($order,'payment_zone'),
				'payment_zone_code'       => '',
				'payment_country_id'      => 222,//$this->getValue($order,'payment_country_id'),
				'payment_country'         => $this->getValue($order,'payment_country'),
				'payment_iso_code_2'      => '',
				'payment_iso_code_3'      => '',
				'payment_address_format'  => $this->getValue($order,'payment_address_format'),
				'payment_custom_field'    => '',
				'payment_method'          => $this->getValue($order,'payment_method'),
				'payment_code'            => $this->getValue($order,'payment_code'),
				'shipping_firstname'      => isset($order['shipping_address'])?$order['shipping_address']['first_name']:'',
				'shipping_lastname'       => isset($order['shipping_address'])?$order['shipping_address']['last_name']:'',
				'shipping_company'        => isset($order['shipping_address'])?$order['shipping_address']['company']:'',
				'shipping_address_1'      => isset($order['shipping_address'])?$order['shipping_address']['address1']:'',
				'shipping_address_2'      => isset($order['shipping_address'])?$order['shipping_address']['address2']:'',
				'shipping_postcode'       => '',
				'shipping_city'           => isset($order['shipping_address'])?$order['shipping_address']['city']:'',
				'shipping_zone_id'        => 0,
				'shipping_zone'           => isset($order['shipping_address'])?$order['shipping_address']['province_code']:'',
				'shipping_zone_code'      => '',
				'shipping_country_id'     => 0,
				'shipping_country'        => isset($order['shipping_address'])?$order['shipping_address']['country']:'',
				'shipping_iso_code_2'     => '',
				'shipping_iso_code_3'     => '',
				'shipping_address_format' => '',
				'shipping_custom_field'   => isset($order['shipping_address'])?json_encode($order['shipping_address'], true):'',
				'shipping_method'         => 'Flat Shipping Rate',
				'shipping_code'           => isset($order['shipping_address'])?$order['shipping_address']['country_code']:'',
				'comment'                 => $order['note'],
				'total'                   => $order['total_price'],
				'reward'                  => '',
				'order_status_id'         => $this->getOrderStatus(!empty($order['fulfillment_status'])?$order['fulfillment_status']:$order['financial_status']),
				'order_status'            => !empty($order['fulfillment_status'])?$order['fulfillment_status']:$order['financial_status'],
				'affiliate_id'            => '',
				'affiliate_firstname'     => '',
				'affiliate_lastname'      => '',
				'commission'              => '',
				'language_id'             => 0,
				'language_code'           =>'',
				'currency_id'             => 0,
				'currency_code'           => $order['currency'],
				'currency_value'          => '',
				'ip'                      => $order['browser_ip'],
				'forwarded_ip'            => $order['id'],
				'user_agent'              =>  $this->getValue(isset($order['client_details'])?$order['client_details']:'','user_agent'),
				'accept_language'         =>  $this->getValue(isset($order['client_details'])?$order['client_details']:'','accept_language'),
				'date_added'              => $order['created_at'],
				'date_modified'           => $order['updated_at']
			);
			
			if (!empty($customer_info)) {
				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				//$order_data['email'] = $customer_info['email'];
				//$order_data['telephone'] = $customer_info['telephone'];
				$order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				//$order_data['email'] = $this->session->data['guest']['email'];
				//$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
			}
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$od = array();
			if($order['line_items']){
				/*foreach($order['line_items'] as $items){
					print_r($items['sku']);
				}*/
				$order_data['invoice_prefix'] = $order['line_items'][0]['sku'];
				$sku='';
				$quantity=0;
				foreach($order['line_items'] as $items){
					/*if($items['sku']){
						if($items['sku']==$sku){
							$quantity+=$items['quantity'];
						}else{
							if($quantity>0){
								$skus = explode('.', $items['sku']);
								$sk = $skus[count($skus)-1];
								if(empty($sk) || $sk==$items['sku']){
						  			$sk = preg_replace('/\D/s', '',$items['sku']);
					  			}
								//echo $sk.",";
								$orderProducts = $this->model_shopify_order->getOrderProductsBySku($sk);
								if(count($orderProducts)>0){
									$orderProducts[0]['name'] = $items['name'];
									$orderProducts[0]['shopify_price'] = $items['price'];
									$orderProducts[0]['order_id'] = $order_data['order_id'];
									$orderProducts[0]['quantity'] = count($order['line_items']);
									$orderProducts[0]['total'] = $orderProducts[0]['price']*$orderProducts[0]['quantity'];
									$orderProducts[0]['shopify_sku'] = $sk;
									$od[] = $orderProducts[0];
								}else{
									$od[] = array(
									'name'=> $items['name'],
									'order_id'=> $order['id'],
									'product_id'=> $items['product_id'],
									'name'=> $items['name'],
									'model'=> $items['title'],
									'quantity'=> $items['quantity'],
									'price'=> 0,
									'total'=> 0,
									'shopify_price'=> $items['price'],
									'shopify_sku'=> $items['sku'],
									'tax'=> 0,
									'reward'=> ''
									);
								}
							}
							echo $quantity.",";
							$quantity=$items['quantity'];
							$sku = $items['sku'];
							}
						}
					
				}*/
				//if($quantity>0){
					 $sku = $items['sku'];
					 $skus = explode('.', $items['sku']);
					  $sk = $skus[count($skus)-1];
					  if(empty($sk) || $sk==$items['sku'] ){
						  $sk = preg_replace('/\D/s', '',$items['sku']);
					  }
					  //echo $sk.",";
					  $orderProducts = $this->model_shopify_order->getOrderProductsBySku($sk);
					  if(count($orderProducts)>0){
						  $orderProducts[0]['name'] = $items['name'];
						  $orderProducts[0]['shopify_price'] = $items['price'];
						  $orderProducts[0]['order_id'] = $order_data['order_id'];
						  $orderProducts[0]['quantity'] = $items['quantity'];
						  $orderProducts[0]['total'] = $orderProducts[0]['price']*$items['quantity'];
						  $orderProducts[0]['shopify_sku'] = $sk;
						  $od[] = $orderProducts[0];
					  }else{
						  $od[] = array(
						  'name'=> $items['name'],
						  'order_id'=> $order['id'],
						  'product_id'=> $items['product_id'],
						  'name'=> $items['name'],
						  'model'=> $items['title'],
						  'quantity'=> $items['quantity'],
						  'price'=> 0,
						  'total'=> 0,
						  'shopify_price'=> $items['price'],
						  'shopify_sku'=> $items['sku'],
						  'tax'=> 0,
						  'reward'=> ''
						  );
					 }
				//}
					if(!empty($od)){
						$order_data['products'] = $od;
						//print_r($order_data['products']);
					}
				}
			}
			/*echo preg_replace('/\D/s', '',$order_data['invoice_prefix']);
			$orderProducts = $this->model_shopify_order->getOrderProductsByOrderProductId(preg_replace('/\D/s', '',$order_data['invoice_prefix']));
			print_r($orderProducts);
			if(count($orderProducts)>0){
				$order_data['products'] = $orderProducts[0];
				$order_data['products']['order_id'] = $order_data['order_id'];
				$order_data['products']['quantity'] = count($order['line_items']);
				$order_data['products']['total'] = $orderProducts[0]['price']*$orderProducts[0]['quantity'];
			}*/
			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}
			
			return $order_data;	
	}
	
	public function getValue($order,$key){
		if(isset($order[$key])){
			return $order[$key];
		}
		return '';
	}
	
	public function getOrderStatus($status){
		switch($status) {
			case 'canceled':
				return $this->config->get('payment_pp_express_canceled_reversal_status_id');
				break;
			case 'refunded':
				return  $this->config->get('payment_pp_express_refunded_status_id');
			case 'reversed':
				return  $this->config->get('payment_pp_express_reversed_status_id');
			case 'voided':
				return  $this->config->get('payment_pp_express_voided_status_id');
		}
		return 1;
	}
}

?>