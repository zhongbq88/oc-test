<?php



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
						'compare_price' => $compare_price[$key][$i],
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
					'name'=>'Type',
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
							"vendor"=>  "vivajean",
							"product_type"=>  $product_info['model'],
							"options"=>$options,
							"variants"=>$variants,
							"images"=>$images
		);
		//print_r($paoduct);
		$this->save($paoduct,$product_ids,$images,$variants,$variant_count);
	}
	
	public function save($paoduct,$product_ids,$images,$variants,$variant_count){
		//$shopify = shopify\client($this->session->data['shop'], SHOPIFY_APP_API_KEY, $this->session->data['oauth_token']);
		$json = array();
		try
		{
			include(str_replace('commonipl','',__DIR__).$this->session->data['store'].'/oauthclient.php');
			$product = Oauthclient::getInstance($this->customer->getStore(),$this->customer->getConsumerkey()
			,$this->customer->getConsumerSecret(),$this->customer->getToken())->post(array('product' =>$paoduct),$variant_count);
			$this->load->model('commonipl/product');
			$this->model_commonipl_product->saveShopifyAddProduct($product,1,$product_ids);
			if(isset($this->session->data['shop'])){
				$this->session->data['sussecc'] = sprintf($this->language->get('publish_sucessfully'), 'https://'.$this->session->data['shop'].'/admin/products/'.$product['id']);
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
		if(isset($this->session->data['srcImages'])){
			unset($this->session->data['srcImages']);
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