<?php




class ControllerShopifyCategory extends Controller {
	
	public function index() {
	
		$this->load->language('shopify/category');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setTabIndex(1);
		$data['footer'] = $this->load->controller('shopify/footer');
		$data['header'] = $this->load->controller('shopify/header');
		$data['products'] = $this->load->controller('shopify/productlist');
		$this->response->setOutput($this->load->view('shopify/category', $data));	
	}
	
}
