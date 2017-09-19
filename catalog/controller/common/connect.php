<?php
use phpish\shopify;

class ControllerCommonConnect extends Controller {
	
	public function index(){
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('common/connect', $data));
	}
	
	public function install(){
		$shop = $this->request->post['store'];
		if(!isset($shop)){
			return;
		}
		//print_r( $shop);
		$shops = explode(".", $shop);
		$email = $shops[0]."@shopify.com";
		$this->load->model('account/customer');
		$customer = $this->model_account_customer->getCustomerByEmail($email);
		
		try
		{
			require(str_replace('common','',__DIR__).'shopify/vendor/autoload.php');
			$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, '708e8ea78a7f32594f661ed40e9755e4');
			$products = $shopify('GET /admin/products.json', array('published_status'=>'Unpublished'));
		print_r($products);
			//return $shop;
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
		
		//if(empty($customer)){
			//$url = $this->url->link('shopify/install', 'shop='. $shop);
		/*}else{
			$this->customer->login($email, $shop);
			$this->session->data['oauth_token'] = $this->customer->getToken();
			$this->session->data['shop'] = $shop;
			$this->session->data['store'] = 'shopify';
			$url = $this->url->link('commonipl/dashboard', '');
		}		*/
		//$this->response->redirect($url);
	}
	
}