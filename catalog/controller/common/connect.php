<?php

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
		//if(empty($customer)){
			$url = $this->url->link('shopify/install', 'shop='. $shop);
		/*}else{
			$this->customer->login($email, $shop);
			$this->session->data['oauth_token'] = $this->customer->getToken();
			$this->session->data['shop'] = $shop;
			$this->session->data['store'] = 'shopify';
			$url = $this->url->link('commonipl/dashboard', '');
		}		*/
		$this->response->redirect($url);
	}
	
}