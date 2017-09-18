<?php

class ControllerCommonConnect extends Controller {
	
	public function index(){
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('common/connect', $data));
	}
	
	public function install(){
		$shop = $this->request->post['Store'];
		if(!isset($shop)){
			return;
		}
		$shops = explode(".", $store_url);
		$email = $shops[0]."@shopify.com";
		$customer = $this->model_account_customer->getCustomerByEmail($email);
		if(empty($customer)){
			$url = $this->url->link('shopify/install', 'shop='. $shop);
		}else{
			$this->customer->login($email, $shop);
			$url = $this->url->link('commonipl/dashboard', '');
		}		
		$this->response->redirect($url);
	}
	
}