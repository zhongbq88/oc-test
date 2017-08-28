<?php

include('woocommerce.php');

class ControllerWoocommerceDashboard extends Controller{

	public function index(){
		$status = $this->request->get['success'];
		if($status!=1){
			unset($this->session->data['shop']);
		}
		$user_id =  $this->request->get['user_id'];

		$this->load->language('shopify/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data = array();

		$this->load->model('shopify/order');

		$results = $this->model_shopify_order->getStatusTotalOrders();

		$pending =0;
		$in_production =0;
		$shipped =0;
		$on_hold =0;
		$cancelled =0;
		$total =0;
		$charges =0;
		foreach ($results as $result) {
			
			if($result['order_status_id']==1){
				$pending+=1;
			}else if($result['order_status_id']==17){
				$in_production+=1;
			}else if($result['order_status_id']==3){
				$shipped+=1;
			}else if($result['order_status_id']==18){
				$on_hold+=1;
			}else if($result['order_status_id']==7 || $result['order_status_id']==11||$result['order_status_id']==10||$result['order_status_id']==14){
				$cancelled+=1;
			}
			if($result['order_status_id']==17 || $result['order_status_id']==13 || $result['order_status_id']==17|| $result['order_status_id']==5|| $result['order_status_id']==3){
				$total+=(float)$result['total'];
				$charges+=(float)$result['total'];
			}
		}
		$data['pending'] = $pending; 
		$data['in_production'] = $in_production; 
		$data['shipped'] = $shipped; 
		$data['on_hold'] = $on_hold; 
		$data['cancelled'] = $cancelled; 
		$data['aug_total'] = $total; 
		$data['aug_charges'] = $charges; 
		$data['tabtype'] = 0;
		$data['footer'] = $this->load->controller('shopify/footer');
		$data['header'] = $this->load->controller('shopify/header');
		if(isset($this->session->data['sussecc'])){
			$data['success'] = $this->session->data['sussecc'];
			unset($this->session->data['sussecc']);
		}
		$this->response->setOutput($this->load->view('shopify/dashboard', $data));
	}
	
}

?>