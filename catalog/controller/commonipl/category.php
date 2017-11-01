<?php
class ControllerCommoniplCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		/*$category_info = $this->model_catalog_category->getCategories();
		//print_r($category_info);
		$data['categories'] = array();
		if ($category_info) {

			foreach ($category_info as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'thumb' =>$image,
					'href' => $this->url->link('commonipl/productlist', 'category_id='. $result['category_id'])
				);
			}
		}*/
		$data['products'] = $this->getList();
		//print_r($data['categories']);
		$data['footer'] = $this->load->controller($this->session->data['store'].'/footer');
		$data['header'] = $this->load->controller($this->session->data['store'].'/header');
		$this->response->setOutput($this->load->view('commonipl/category', $data));
	}
	
	
	public function getList(){
		$this->load->language('commonipl/category');
		$this->load->model('commonipl/product');
		$products = array();
		if(isset($this->request->get['parent_id'])){
			$category_info = $this->model_catalog_category->getCategories($this->request->get['parent_id']);
		}else{
			$category_info = $this->model_catalog_category->getCategories();
		}
		if ($category_info) {

			foreach ($category_info as $category) {
				
				$results = $this->model_commonipl_product->getProductsByCategoryId($category['category_id']);
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
					}
		
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}
					$products[] = array(
						'category_id'=> $category['category_id'],
						'product_id'  => $result['product_id'],
						'thumb'       => $image,
						'price'       => $price,
						'name'        => $result['name'],
						'href' => $this->url->link('commonipl/productlist', 'category_id='. $category['category_id'].'&product_id='.$result['product_id'])
					);
				}
			}
		}
		//print_r($products);
		return $products;
	}
}
