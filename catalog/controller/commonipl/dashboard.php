<?php

//include('woocommerce.php');

class ControllerCommoniplDashboard extends Controller{

	public function index(){
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link($this->session->data['store'].'/login', '', true));
		}
		/*$status = $this->request->get['success'];
		if($status!=1){
			unset($this->session->data['shop']);
		}
		$user_id =  $this->request->get['user_id'];*/

		$this->load->language('commonipl/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data = array();

		$this->load->model('commonipl/order');

		$results = $this->model_commonipl_order->getStatusTotalOrders();

		$pending =0;
		$in_production =0;
		$shipped =0;
		$on_hold =0;
		$cancelled =0;
		$total =0;
		$charges =0;
		$orderIds ='';
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
				$orderIds .=",'".$result['order_id']."'";
				//$total+=(float)$result['total'];
				//$charges+=(float)$result['total'];
			}
		}
		echo $orderIds;
		
		if(!empty($orderIds)){
			echo '----'.substr($orderIds,1);
			$results = $this->model_commonipl_order->getOrderProductSales(substr($orderIds,1));
			foreach ($results as $result) {
				$total+=(float)$result['shopify_price']*(float)$result['quantity'];
				$charges+=(float)$result['price']*(float)$result['quantity'];
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
		
		$this->load->language('commonipl/product');
		$this->load->model('commonipl/product');
		$this->load->model('tool/image');
		$products = $this->model_commonipl_product->getPublishProduct();
		$productList = array();
		foreach($products as $product){
			$p = json_decode($product['shopify_product_json'],true);
				
			if(isset($p['title'])){
				if ($p['image']) {
					$image =$p['image']['src'] ;
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				$sales = $this->model_commonipl_product->getPublishProductSales($p['id']);
				$productList[] = array(
					'name'=>$p['title'],
					'image'=>$image,
					'status'=>'published',
					'published_at'=>date($this->language->get('date_format_short'), strtotime($product['date_added'])),
					'sales'=>0,
					'href'  => 'https://vivajean.myshopify.com/admin/products/'.$p['id']
				);
			}else if(isset($p['name'])){
				
				if ($p['images']) {
					$image =$p['images'][0]['src'] ;
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				$sales = $this->model_commonipl_product->getPublishProductSales($p['id']);
				$productList[] = array(
					'name'=>$p['name'],
					'image'=>$image,
					'status'=>$p['status'],
					'published_at'=>date($this->language->get('date_format_short'), strtotime($product['date_added'])),
					'sales'=>0,
					'href'  => 'https://vivajean.myshopify.com/admin/products/'.$p['id']
				);
			}
		}
		//print_r($productList);
		$data['products'] = $productList;
		
		$data['footer'] = $this->load->controller($this->session->data['store'].'/footer');
		$data['header'] = $this->load->controller($this->session->data['store'].'/header');
		if(isset($this->session->data['sussecc'])){
			$data['success'] = $this->session->data['sussecc'];
			unset($this->session->data['sussecc']);
		}
		$this->response->setOutput($this->load->view('commonipl/dashboard', $data));
	}
	
}

?>