<?php
class ControllerCheckoutCart extends Controller {
	public function index() {
		$this->load->language('checkout/cart');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
		);

		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$data['error_warning'] = $this->language->get('error_stock');
			} elseif (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$data['attention'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['action'] = $this->url->link('checkout/cart/edit', '', true);

			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}

			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				if(isset($product['tshirtecommerce']['options']['images'])){
					//print_r($product['tshirtecommerce']['options']['images']);
					//
					$images = str_replace("quot;","", explode(':', $product['tshirtecommerce']['options']['images']));
					
					$image = 'tshirtecommerce/'.substr($images[1],1,strlen($images[1])-3);
				}else if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
				} else {
					$image = '';
				}
				if(isset($data['products'][$product['product_id']])){
					$data['products'][$product['product_id']]['images'][] =$image;
					continue;
				}
				$product_total = 0;
				//print_r($product['option']);
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}
				
				

				$option_data = array();

				foreach ($product['option'] as $option) {
					print_r($option);
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
					
					$price = $this->currency->format($unit_price, $this->session->data['currency']);
					$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
				} else {
					$price = false;
					$total = false;
				}

				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year')
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				$data['products'][$product['product_id']] = array(
					'product_id'=> $product['product_id'],
					'cart_id'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'variants' => $this->getVariants($product['product_id'],$image,$unit_price),
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'description' => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
				$data['products'][$product['product_id']]['images'][] =$image;
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;
			
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);
						
						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}

			$data['totals'] = array();

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}

			$data['continue'] = $this->url->link('common/home');

			$data['checkout'] = $this->url->link('checkout/checkout', '', true);

			$this->load->model('setting/extension');

			$data['modules'] = array();
			
			$files = glob(DIR_APPLICATION . '/controller/extension/total/*.php');

			if ($files) {
				foreach ($files as $file) {
					$result = $this->load->controller('extension/total/' . basename($file, '.php'));
					
					if ($result) {
						$data['modules'][] = $result;
					}
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('checkout/cart', $data));
		} else {
			$data['text_error'] = $this->language->get('text_empty');
			
			$data['continue'] = $this->url->link('common/home');

			unset($this->session->data['success']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function add() {
		$this->load->language('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		//echo $product_id;
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}
			//echo $option;
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				// Unset all shipping and payment methods
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('setting/extension');

				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
		
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_setting_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get('total_' . $result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}

				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function save() {
		$this->load->language('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		//print_r('product_id='.$product_id);
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
//print_r('quantity='.$quantity);
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}
			//print_r($option);
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

				/* vqmod/xml/tshirtecommerce_product.xml */
				$this->load->model('tshirtecommerce/sproduct');
				if (isset($this->request->post['tshirtecommerce'])) {
					$tshirtecommerces = $this->request->post['tshirtecommerce'];

					if (isset($tshirtecommerces['colors'])) {
						$check_tshirtecommerce_colors = explode(';', $tshirtecommerces['colors']);
						if (count($check_tshirtecommerce_colors) > 0) {
							$tmp_colors = array();
							foreach ($check_tshirtecommerce_colors as $tcolor_hex) {
								if (!empty($tcolor_hex))
									$tmp_colors[] = $tcolor_hex;
							}
							$tshirtecommerces['colors'] = $tmp_colors;
						}
					} else {
						$tshirtecommerces['colors'] = false;
					}

					$tshirtecommerce = $this->model_tshirtecommerce_sproduct->getQtyAndPrice($tshirtecommerces, $this->request->post['product_id'], $quantity, true);
					$quantity = ($tshirtecommerce['quantity'] > 1) ? $tshirtecommerce['quantity'] : 1;
					if ($tshirtecommerce['type'] != 'cart') {
						$option['tshirtecommerce']['price_of_print'] = $tshirtecommerce['price_of_print'];
					}
					$option['tshirtecommerce']['options'] = $tshirtecommerces;
				} elseif (isset($this->request->post['design'])) {
					$tshirtecommerces = $this->request->post['design'];
					if (isset($tshirtecommerces) && count($tshirtecommerces) > 0 && isset($tshirtecommerces['refer']) && $tshirtecommerces['refer'] == 'designer') {
						if (isset($tshirtecommerces['color_hex'])) {
							$check_tshirtecommerce_colors = explode(';', $tshirtecommerces['color_hex']);
							if (count($check_tshirtecommerce_colors) > 0) {
								$tmp_colors = array();
								foreach ($check_tshirtecommerce_colors as $tcolor_hex) {
									if (!empty($tcolor_hex))
										$tmp_colors[] = $tcolor_hex;
								}
								$tshirtecommerces['colors'] = $tmp_colors;
							}
						} else {
							$tshirtecommerces['colors'] = false;
						}
						$tshirtecommerces['type'] = 'cart';
						$tshirtecommerce = $this->model_tshirtecommerce_sproduct->getQtyAndPrice($tshirtecommerces, $this->request->post['product_id'], $quantity, true);
						$quantity = ($tshirtecommerce['quantity'] > 1) ? $tshirtecommerce['quantity'] : 1;
						$option['tshirtecommerce']['price_of_print'] = $tshirtecommerce['price_of_print'];
						$option['tshirtecommerce']['options'] = $tshirtecommerces;

						// update opencart option
						if (isset($tshirtecommerces['option_oc']) && !empty($tshirtecommerces['option_oc'])) {
							$oc_option = array();
							$str_option_oc = str_replace('&quot;', '"', $tshirtecommerces['option_oc']);
							$array_option_oc = explode(';;', $str_option_oc);
							if (count($array_option_oc) > 0) {
								foreach ($array_option_oc as $row) {
									if (!empty($row) && $row != '') {
										$str_row_array = explode('::', $row);
										if (count($str_row_array) > 1) {
											$str_row_child = explode(',', $str_row_array[1]);
											if (count($str_row_child) > 0) {
												foreach ($str_row_child as $r) {
													if (!empty($r) && $r != '') $oc_option[$str_row_array[0]][] = $r;
												}
											}
										}
									}
								}
							}
							if (count($oc_option) > 0) {
								foreach ($oc_option as $key => $value) {
									if (isset($product_options) && count($product_options) > 0) {
										foreach ($product_options as $po) {
											if ($po['product_option_id'] == $key) {
												if ($po['type'] == 'text' || $po['type'] == 'textarea' || $po['type'] == 'file' || $po['type'] == 'date' || $po['type'] == 'datetime' || $po['type'] == 'time' || $po['type'] == 'select' || $po['type'] == 'radio') {
													$option[$key] = isset($value[0]) ? $value[0] : '';
												} else {
													$option[$key] = $value;
												}
												break;
											}
										}
									} else {
										$option[$key] = $value;
									}
								}
							}
						}
					}
				}
			

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				// Unset all shipping and payment methods
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('setting/extension');

				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
		
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_setting_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get('total_' . $result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}

				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));


	}

	public function edit() {
		$this->load->language('checkout/cart');

		$json = array();

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}

			$this->session->data['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('checkout/cart');

		$json = array();

		// Remove
		if (isset($this->request->post['key'])) {
			$this->cart->remove($this->request->post['key']);

			unset($this->session->data['vouchers'][$this->request->post['key']]);

			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);

						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}

			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	private function getVariants($product_id,$thumb,$pprice){
		
			$product_options = $this->model_catalog_product->getProductOptions($product_id);
			$option_data = array();
			$index=0;
			$optionName ='';
			foreach ($product_options as $product_option) {
				if($product_option['type']=='select'){
					$create = false;
					foreach ($product_option['product_option_value'] as $option_value) {
						$optionName =  $option_value['name'];
						
							if(!$create){
								$option_data[$index] = array();
								$create = true;
							}
							$option_data[$index][] = $option_value;
						
					}
					if($create){
						$index++;
					}
				}
				
			}
			//print_r($images);
			
			//print_r($option_data);
			$options = $this->getArrSet($option_data);
			$variants = array();
			foreach($options as $key=> $option){
				//print_r($option);
				$variants[$key]['thumb'] = $thumb;
				$price = $pprice;
				foreach($option as $i=> $v){
					$variants[$key]['option'.($i+1)] = $v['name'];
					$price+=$v['price'];
					$variants[$key]['product_option_id'] = $v['product_option_id'];
					$variants[$key]['option_value_id'] = $v['option_value_id'];
				}
				$variants[$key]['price'] = $price;
				$variants[$key]['sale_price'] = $price*2;
				$variants[$key]['msrp'] = $price*4;
				
			}
			return $variants;
	}
	
	  public function getArrSet($arrs,$_current_index=-1)
	  {
		  //总数组
		  static $_total_arr;
		  //总数组下标计数
		  static $_total_arr_index;
		  //输入的数组长度
		  static $_total_count;
		  //临时拼凑数组
		  static $_temp_arr;
		  
		  //进入输入数组的第一层，清空静态数组，并初始化输入数组长度
		  if($_current_index<0)
		  {
			  $_total_arr=array();
			  $_total_arr_index=0;
			  $_temp_arr=array();
			  $_total_count=count($arrs)-1;
			  $this->getArrSet($arrs,0);
		  }
		  else
		  {
			  //循环第$_current_index层数组
			  foreach($arrs[$_current_index] as $v)
			  {
				  //如果当前的循环的数组少于输入数组长度
				  if($_current_index<$_total_count)
				  {
					  //将当前数组循环出的值放入临时数组
					  $_temp_arr[$_current_index]=$v;
					  //继续循环下一个数组
					  $this->getArrSet($arrs,$_current_index+1);
					  
				  }
				  //如果当前的循环的数组等于输入数组长度(这个数组就是最后的数组)
				  else if($_current_index==$_total_count)
				  {
					  //将当前数组循环出的值放入临时数组
					  $_temp_arr[$_current_index]=$v;
					  //将临时数组加入总数组
					  $_total_arr[$_total_arr_index]=$_temp_arr;
					  //总数组下标计数+1
					  $_total_arr_index++;
				  }
	  
			  }
		  }
		  
		  return $_total_arr;
	  }

}
