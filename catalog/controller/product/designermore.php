<?php
class ControllerProductDesignermore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getDesignProducts();

		if ($products) {
			//print_r(count($products));
			$data['products'] = array();
			$this->load->model('tshirtecommerce/sproduct');
			foreach($products as $product){
				if(!empty($product['design_product_id'])){
					$design =  $this->model_tshirtecommerce_sproduct->getInfo($product['product_id'],$product['design_product_id']);
					if(isset($design)&&isset($design['design'])){
						$front = json_decode(str_replace("'","\"",str_replace("px","",$design['design']['front'][0])),true);
						$product['front'] = $front[1];
						$product['area'] = json_decode(str_replace("'","\"",str_replace("px","",$design['design']['area']['front'])),true);
						$product['design_info'] = $design;
						$data['products'][] = $product;
					}
				}
				
			}
			
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			$data['baseurl'] = $server.'/tshirtecommerce/';
			$this->response->setOutput($this->load->view('product/designmore', $data));
		}
	}
}
