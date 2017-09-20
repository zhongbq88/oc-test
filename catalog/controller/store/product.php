<?php
class ControllerStoreProduct extends Controller {


	public function index() {
		$this->load->language('store/product');
		$this->load->model('commonipl/product');
		$this->load->model('tool/image');
		if(isset($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
		}else{
			$product_id = 0;
		}
		$product = $this->model_commonipl_product->getPublishProductById($product_id);
		if(isset($product)){
			$p = json_decode($product['shopify_product_json'],true);
			
			//print_r($p);
			//print_r($images);
			$data['product'] = array(
				'product_id'=>$product_id,
				'name'=>$p['title'],
				'des'=>$p['body_html'],
				'style'=>$p['options'][0],
				'tags' =>$p['tags'],
				'variants'=> $p['variants']
			);
			$options = array();
			$images = $p['images'];
			$data['product']['images'] = array();
		
			foreach($images as $key=> $image){
					//echo str_replace(HTTP_SERVER.'image/', '', $image['src']);
				$data['product']['images'][] = array(
					'thumb_image'=>$this->model_tool_image->resize(str_replace(HTTP_SERVER.'image/', '', $image['src']), 142, 142),
					'pop_image'=>$image['src'],
					'image_pos'=>$key
				);
			}
			$option1 ='';
			$optionIndex =0;
			//print_r($p['options']);
			$optionCount = count($p['options']);
			$option = array();
			$prices = array();
			$p_index =-1;
			foreach($p['variants'] as $key=> $variant){
				//print_r($variant);
				if($option1!=$variant['option1']){
					$option[$variant['option1']] = array();
					$p_index++;
				}
				$prices[$p_index][] = array(
						'price'=>$variant['price'],
						'compare_at_price'=>$variant['compare_at_price']
					);
				$index =0;
				for($i=1;$i<$optionCount;$i++){
					if(isset($variant['option'.($i+1)])){
						$option[$variant['option1']][$p['options'][$i]['name']][] = array(
							'price'=>$variant['price'],
							'compare_at_price'=>$variant['compare_at_price'],
							'name'=>$variant['option'.($i+1)]
						);
						$index++;
					}
				}				
				//print_r($variant);
				$option1 = $variant['option1'];
			}
		}
		print_r($option);
		//$data['opts'] = $option;
		$data['product']['prices'] = $prices;
		$data['product']['options'] = $option;
		$data['footer'] = $this->load->controller('store/footer');
		$data['header'] = $this->load->controller('store/header');
		
		$this->response->setOutput($this->load->view('store/product', $data));
	}
}
