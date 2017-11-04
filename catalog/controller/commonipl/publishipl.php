<?php

include(DIR_APPLICATION.'/controller/shopify/oauthclient.php');

class ControllerCommoniplPublishipl extends Controller {
	
	public function index(){
		
		$this->load->language('checkout/cart');
		$this->load->language('shopify/product');

		$json = array();

		$this->load->model('commonipl/order');
		$this->load->model('commonipl/product');
		
		$title = $this->request->post['product_title'];
		$pdsc = $this->request->post['product_description'];
		$ptag = $this->request->post['product_tags'];
		$pcol = $this->request->post['product_pcol'];
		
		//print_r($pdsc);
		$pimgs =array();
		$variants = array();
		$images = array();
		//$order_option = $result_one['order_option'];
		if (isset($this->request->post['variant'])) {
			$variant = array_filter($this->request->post['variant']);
		} else {
			$variant = array();
		}
		if (isset($this->request->post['product_image'])) {
			$pimgs = array_filter($this->request->post['product_image']);
		} else {
			$pimgs = array();
		}
		if (isset($this->request->post['variant_option'])) {
			$variant_option = array_filter($this->request->post['variant_option']);
		} else {
			$variant_option = array();
		}
		
		if (isset($this->request->post['variant_title'])) {
			$variant_title = array_filter($this->request->post['variant_title']);
		} else {
			$variant_title = array();
		}
		
		
		if (isset($this->request->post['product_price'])) {
			$product_price = array_filter($this->request->post['product_price']);
		} else {
			$product_price = array();
		}
		if (isset($this->request->post['compare_price'])) {
			$compare_price = array_filter($this->request->post['compare_price']);
		} else {
			$compare_price = array();
		}
		if (isset($this->request->post['each_price'])) {
			$each_price = array_filter($this->request->post['each_price']);
		} else {
			$each_price = array();
		}
		if (isset($this->request->post['product_option_value_id'])) {
			$option_value_id = array_filter($this->request->post['product_option_value_id']);
		} else {
			$option_value_id = array();
		}
		if (isset($this->request->post['option_type'])) {
			$option_type = array_filter($this->request->post['option_type']);
		} else {
			$option_type = array();
		}
		if (isset($this->request->post['product_id'])) {
			$product_ids = array_filter($this->request->post['product_id']);
		} else {
			$product_ids = array();
		}
		$option1 = array();
		$optionSize = array();
		$srcImages = $this->session->data['srcImages'];
		//print_r($srcImages);
		$typeName='';
		$optionindex = 2;
		$optionsnew = array();
		$variant_count = array();
		foreach ($pimgs as $key => $value) {
				$i=0;
				$option1[] =  $variant_title[$key];
				$product_info = $this->model_commonipl_product->getProduct($product_ids[$key]);
				foreach ($variant_option[$key]  as $v) {
					if($typeName !=  $option_type[$key]){
						  if(!empty($typeName)){
							  if($optionSize){
									$optionsnew[] = array(
											'name'=>'Size',
											'values'=>array_unique($optionSize)
									);
									$optionSize = array();
									$optionindex++;
							  }
							  //print_r($optionsnew);
						  }
						$typeName =  $option_type[$key];
					}
					$optionSize[] = $v;
					$sku =  array(
						'product_id'  		=> $product_ids[$key],
						'price'       		=> $each_price[$key][$i],
						'sku'        		=> $product_info['sku'],
						'model'        		=> $product_info['model'],
						'product_option_id' => $key,
						'option_value_id'   => $option_value_id[$key],//$option_value_id[$key] ,
						'product_options'   => $v,//json_encode($v),
						'option_file'       => $srcImages[$key],//$imgs[$key],
						'design_file'       => HTTP_SERVER.$value
					);
					//print_r($sku);
					$sku_id = $this->model_commonipl_order->addProductSku($sku);
					$variants[] = array(
						'option1' => $variant_title[$key],
						'option'.$optionindex => $v,
						'price' => $product_price[$key][$i],
						'compare_at_price' => $compare_price[$key][$i],
						"sku"=> $product_info['sku'].".".$sku_id
					);		
					
					$i++;		
			}
			$variant_count[] = array(
				"variant_count"=>$i
			);
			$images[] = array(
			//"src"=>'https://www.customdr.com/image/catalog/designs/23_3_1504177279.jpg'
				"src"=>HTTPS_SERVER.$pimgs[$key]
			);
			
		}
		
	  if($optionSize){
			$optionsnew[] = array(
				  'name'=>'Size',
				  'values'=>array_unique($optionSize)
			);
		}
		//print_r($optionsnew);
	   $options = array();
	   $options[] = array(
					'name'=>'Style',
					'values'=>array_unique($option1)
			);
		foreach($optionsnew as $optionsn)
		{
			$options[] = $optionsn;
		}	
		
		$paoduct = array(
							"title"=>$title,
							"body_html"=> html_entity_decode($pdsc),
							"tags"=> $ptag ,
							"vendor"=>  $this->customer->getFirstName(),
							"product_type"=>  $product_info['model'],
							"options"=>$options,
							"variants"=>$variants,
							"images"=>$images
		);
		//print_r($paoduct);
		$this->save($paoduct,$product_ids,$images,$variants,$variant_count);
	}
	
	public function push(){
		$this->load->language('checkout/cart');
		$this->load->language('shopify/product');

		$json = array();

		$this->load->model('commonipl/order');
		$this->load->model('commonipl/product');
		$this->load->model('account/cart');
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = '';
		}
		
		if (isset($this->request->post['product'])) {
			$products = array_filter($this->request->post['product']);
		} else {
			$products = array();
		}
		//print_r($products);
		$results = array();
		if(!empty($product_id)){
			$results[$product_id] = $this->pushProduct($products[$product_id],$product_id);
		}else{
			foreach($products['selected'] as $id){
				//if($product['checked']=='on'){
				$results[$id] = $this->pushProduct($products[$id],$id);
				//}
			}	
		}
		if(isset($results)){
			$json['success'] = $results;
			foreach($results as  $value){
				if(!empty($value)){
					$json['text'] = sprintf($this->language->get('publish_sucessfully'), 'https://'.$this->session->data['shop'].'/admin/products/'.$value);
				}
			}
			
		}else{
			$json['error'] = 'Push Error';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	
	public function pushProduct($product,$product_id){
		if(!isset($product['variant']['selected'])){
			return;
		}
		$variants = array();
		$images = array();
		$options = array();
		$position = 1;
		$variantImages = array();
		$option1Value = array();
		$option2Value = array();
		$product_info = $this->model_commonipl_product->getProduct($product_id);		
		//print_r($product['variant']['selected']);
		foreach($product['variant']['selected'] as $key){
				$key = $key-1;
				$variant = array(
					'position' => $position,
					'price' => $product['variant']['price'][$key],
					'compare_at_price' => $product['variant']['compare_price'][$key],
					"inventory_policy"=> "continue",
    				"inventory_management"=>"shopify",
					"inventory_quantity"=> $product_info['quantity'],
   					"weight"=> $product_info['weight'],
   					"weight_unit"=>"g"
				);	
				if(isset($product['variant']['option1'])){
					$variant['option1'] = $product['variant']['option1'][$key];
					$option1Value[] = $variant['option1'];
				}
				if(isset($product['variant']['option2'])){
					if(isset($variant['option1'])){
						$variant['option2'] = $product['variant']['option2'][$key];
						$option2Value[] = $variant['option2'];
					}else{
						$variant['option1'] = $product['variant']['option2'][$key];
						$option1Value[] = $variant['option1'];
					}
				}
				$sku =  array(
					'product_id'  		=> $product_id,
					'price'       		=> $product['variant']['cost'][$key],
					'sku'        		=> $product_info['sku'],
					'model'        		=> $product_info['model'],
					'product_option_id' => $product['variant']['product_option_id'][$key],
					'option_value_id'   => $product['variant']['option_value_id'][$key],
					'product_options'   => (isset($variant['option2'])?$variant['option2']:$variant['option1']),
					'option_file'       => $product['variant']['images'][$key],//$imgs[$key],
					'design_file'       => $product['variant']['images'][$key]
				);
				//print_r($sku);
				$sku_id = $this->model_commonipl_order->addProductSku($sku);
				if(isset($sku_id)){
					$variant['sku']  =  $product_info['sku'].".".$sku_id;
				}
				$variants[] = $variant;
				$image = $product['variant']['images'][$key];
				if(!in_array($image, $images)){
					$images[] = $image;
				}
				$variantImages[HTTPS_SERVER.'image/'.$product['variant']['images'][$key]][] = $position;
				$position++;
			
		}
		if(!empty($option1Value)){
			$options[] = array(
				'name'=>isset($product['option1'])?$product['option1']:$product['option2'],
				'values'=>array_unique($option1Value)
			);
		}
		if(!empty($option2Value)){
			$options[] = array(
				'name'=>$product['option2'],
				'values'=>array_unique($option2Value)
			);
		}
		$imageArray = array_merge($images,$product['images']);
		$images = array();
		foreach($imageArray as $position => $image){
			$images[] = array(
				'position'=>$position+1,
				"src"=>HTTPS_SERVER.'image/'.$image
			);
		}
		
		$data =  array(
			"title"=>$product['title'],
			"body_html"=> html_entity_decode($product['description']),
			"tags"=> $product['tag'],
			"vendor"=>  $this->customer->getFirstName(),
			"product_type"=>  $product['model'],
			"options"=>$options,
			"variants"=>$variants,
			"images"=>$images
		);
		//print_r($images);
		//print_r($variantImages);
		return $this->pushToShop($data,$variantImages,$product_id);
	}
	
	public function pushToShop($paoducts,$variantImages,$product_id){
		//$shopify = shopify\client($this->session->data['shop'], SHOPIFY_APP_API_KEY, $this->session->data['oauth_token']);
		$json = array();
		try
		{
			
			$product = Oauthclient::getInstance($this->customer->getStore(),$this->customer->getConsumerkey()
			,$this->customer->getConsumerSecret(),$this->customer->getToken())->push(array('product' =>$paoducts),$variantImages);
			$product_Add_id = $this->model_commonipl_product->saveShopifyAddProduct($product,$product_id,array($product_id));
			if(isset($product['id'])){
				$this->model_account_cart->deleteCart($product_id);
				return $product['id'];
			}
			//$this->response->redirect($this->url->link('shopify/dashboard'));
			
		}
		catch (shopify\ApiException $e)
		{
			# HTTP status code was >= 400 or response contained the key 'errors'
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		catch (shopify\CurlException $e)
		{
			# cURL error
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		return '';
		
	}
	
	public function save($paoduct,$product_ids,$images,$variants,$variant_count){
		//$shopify = shopify\client($this->session->data['shop'], SHOPIFY_APP_API_KEY, $this->session->data['oauth_token']);
		$json = array();
		try
		{
			$product = Oauthclient::getInstance($this->customer->getStore(),$this->customer->getConsumerkey()
			,$this->customer->getConsumerSecret(),$this->customer->getToken())->post(array('product' =>$paoduct),$variant_count);
			$this->load->model('commonipl/product');
			$product_Add_id = $this->model_commonipl_product->saveShopifyAddProduct($product,1,$product_ids);
			if(isset($this->session->data['shop'])){
				if(!isset($product['id'])||$product['id']==0){
					//$product['id'] =  ($this->model_commonipl_product->getLastOAddProductId();
					$this->session->data['sussecc'] = sprintf($this->language->get('publish_store_sucessfully'), 'index.php?route=store/product&product_id='.$product_Add_id);
				}else{
					$this->session->data['sussecc'] = sprintf($this->language->get('publish_sucessfully'), 'https://'.$this->session->data['shop'].'/admin/products/'.$product['id']);
				}
				
			}
			
			//$this->response->redirect($this->url->link('shopify/dashboard'));
			
		}
		catch (shopify\ApiException $e)
		{
			# HTTP status code was >= 400 or response contained the key 'errors'
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		catch (shopify\CurlException $e)
		{
			# cURL error
			//echo $e;
			print_r($e->getRequest());
			print_r($e->getResponse());
		}
		
		$json['success'] = 'index.php?route=commonipl/dashboard';
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getImageCode($path){
		 $type=getimagesize($path);//取得图片的大小，类型等
     $content=file_get_contents($path);
     $file_content=chunk_split(base64_encode($content));//base64编码
/*     switch($type[2]){//判读图片类型
         case 1:$img_type="gif";break;
         case 2:$img_type="jpg";break;
         case 3:$img_type="png";break;
     }
     $img='data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码*/
     return $file_content.'\n';
	}
		
		public function calculateCombination($inputList, $beginIndex, $arr,$arr2) {
	    global $arr2;
        if($beginIndex == count($inputList)){
			//print_r($beginIndex);
			$arr2[] = $arr;
			//print_r($arr);
			//print_r($arr2);
            return ;
        }
        foreach($inputList[$beginIndex] as $c){
            $arr[$beginIndex] = $c;
            $this->calculateCombination($inputList, $beginIndex + 1,$arr,$arr2);
        }
		return $arr2;
   }
	
		
}

?>