<?php




class ControllerCatalogCustomerproduct extends Controller {
	
	public function index() {

		$this->load->language('catalog/product');
		$this->load->model('catalog/product');
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$filter_data = array(
			'filter_customer'	 	 => $filter_customer,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		$order_total = $this->model_catalog_product->geFiltertTotalPublishProduct($filter_data);
		$products = $this->model_catalog_product->getPublishProduct($filter_data);
		//print_r($products);
		$productList = array();
		$this->load->model('tool/image');
		foreach($products as $product){
			$p = json_decode($product['shopify_product_json'],true);
			//print_r($p);
			if(isset($p['title'])){
				if (isset($p['image'])) {
					$image =$p['image']['src'] ;
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				
				if($p['variants']){
					$price = $p['variants'][0]['price'] ;
				}else{
					$price = 0;
				}
				$sales = $this->model_catalog_product->getPublishProductSales($p['id']);
				$productList[] = array(
					'name'=>$p['title'],
					'image'=>$image,
					'status'=>'published',
					'published_at'=>date($this->language->get('date_format_short'), strtotime($product['date_added'])),
					'sales'=>$sales,
					'price'=>$price,
					'customer' =>$p['vendor'],
					'href'  => 'https://'.$p['vendor'].'.myshopify.com/products/'.$p['handle']
				);
			}else if(isset($p['name'])){
				
				if ($p['images']) {
					$image =$p['images'][0]['src'] ;
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				$sales = $this->model_catalog_product->getPublishProductSales($p['id']);
				$productList[] = array(
					'name'=>$p['name'],
					'image'=>$image,
					'status'=>$p['status'],
					'published_at'=>date($this->language->get('date_format_short'), strtotime($product['date_added'])),
					'sales'=>$sales,
					'price'=>$p['price'],
					'customer' =>$p['vendor'],
					'href'  => 'https://'.$p['vendor'].'.myshopify.com/products/'.$p['name']
				);
			}
		}
		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/customerproduct', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['filter_customer'] = $filter_customer;
		
		//print_r($productList);
		$data['products'] = $productList;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('catalog/customerproduct', $data));
	}
	

}
