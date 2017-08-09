<?php

session_start();

require 'vendor/autoload.php';
use phpish\shopify;

require 'conf.php';
	
	class ControllerShopifyDashboard extends Controller {

		public function index(){
			if(empty($_SESSION['shop'])){
				$this->getToken();
			}
			if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('v'));
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, true)
		);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], true),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		$this->response->setOutput($this->load->view('shopify/dashboard', $data));
		
		
		}
		
			public function getToken(){
		# Step 3: http://docs.shopify.com/api/authentication/oauth#confirming-installation
	try
	{
		//echo 'shop='.$_GET['shop'];
		//echo 'code='.$_GET['code'];
		# shopify\access_token can throw an exception
		$oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
		
		//echo $oauth_token;
		$this->load->model('account/customer');
		$shop = $_GET['shop'];
		$shops = explode(".", $shop);
		$email = $shops[0]."@shopify.com";
		$customer = $this->model_account_customer->getCustomerByEmail($email);
		if(empty($customer)){
			$customer_id = $this->model_account_customer->addShopifyUser($shop,$oauth_token);
		}
		$this->customer->login($email, $shop);
		
$this->session->data['oauth_token'] = $oauth_token;
$this->session->data['shop'] = $_GET['shop'];
		$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop'];
		return $_SESSION['shop'];
		//echo 'App Successfully Installed!';
	}
	catch (shopify\ApiException $e)
	{
		# HTTP status code was >= 400 or response contained the key 'errors'
		//echo $e;
		print_R($e->getRequest());
		print_R($e->getResponse());
		
	}
	catch (shopify\CurlException $e)
	{
		# cURL error
		//echo $e;
		print_R($e->getRequest());
		print_R($e->getResponse());
		
	}
	}
	
	}

?>