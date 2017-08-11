<?php

session_start();
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;

require __DIR__.'/conf.php';

class ControllerShopifyGetorders extends Controller {
	public function index(){
		$shopify = shopify\client($_SESSION['shop'], SHOPIFY_APP_API_KEY, $_SESSION['oauth_token']);

	try
	{
		//print_r();
		//echo print_r('product='.$_SESSION['product']);
		# Making an API request can throw an exception
		//$orders = $shopify('GET  /admin/orders.json?financial_status=authorized');
		$this->load->model('localisation/order_status');

		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();
		$json = '{
"orders": [
    {
      "id": 1073459963,
      "email": "",
      "closed_at": null,
      "created_at": "2017-07-22T11:53:33-04:00",
      "updated_at": "2017-07-22T11:53:33-04:00",
      "number": 2,
      "note": null,
      "token": "cc5f5fb4fa68e08cd25a15c0ab9e44e7",
      "gateway": "",
      "test": false,
      "total_price": "199.00",
      "subtotal_price": "199.00",
      "total_weight": null,
      "total_tax": "0.00",
      "taxes_included": false,
      "currency": "USD",
      "financial_status": "paid",
      "confirmed": true,
      "total_discounts": "0.00",
      "total_line_items_price": "199.00",
      "cart_token": null,
      "buyer_accepts_marketing": false,
      "name": "#1002",
      "referring_site": null,
      "landing_site": null,
      "cancelled_at": null,
      "cancel_reason": null,
      "total_price_usd": "199.00",
      "checkout_token": null,
      "reference": null,
      "user_id": null,
      "location_id": null,
      "source_identifier": null,
      "source_url": null,
      "processed_at": "2017-07-22T11:53:33-04:00",
      "device_id": null,
      "phone": null,
      "customer_locale": null,
      "browser_ip": null,
      "landing_site_ref": null,
      "order_number": 1002,
      "discount_codes": [
      ],
      "note_attributes": [
      ],
      "payment_gateway_names": [
      ],
      "processing_method": "",
      "checkout_id": null,
      "source_name": "755357713",
      "fulfillment_status": null,
      "tax_lines": [
      ],
      "tags": "",
      "contact_email": null,
      "order_status_url": null,
      "line_items": [
        {
          "id": 1071823173,
          "variant_id": 447654529,
          "title": "IPod Touch 8GB",
          "quantity": 1,
          "price": "199.00",
          "grams": 567,
          "sku": "IPOD2009BLACK",
          "variant_title": "Black",
          "vendor": "Apple",
          "fulfillment_service": "manual",
          "product_id": 921728736,
          "requires_shipping": true,
          "taxable": true,
          "gift_card": false,
          "name": "IPod Touch 8GB - Black",
          "variant_inventory_management": "shopify",
          "properties": [
          ],
          "product_exists": true,
          "fulfillable_quantity": 1,
          "total_discount": "0.00",
          "fulfillment_status": null,
          "tax_lines": [
          ]
        }
      ],
      "shipping_lines": [
        {
          "id": 369256396,
          "title": "Free Shipping",
          "price": "0.00",
          "code": "Free Shipping",
          "source": "shopify",
          "phone": null,
          "requested_fulfillment_service_id": null,
          "delivery_category": null,
          "carrier_identifier": null,
          "tax_lines": [
          ]
        }
      ],
      "billing_address": {
        "first_name": "Bob",
        "address1": "Chestnut Street 92",
        "phone": "555-625-1199",
        "city": "Louisville",
        "zip": "40202",
        "province": "Kentucky",
        "country": "United States",
        "last_name": "Norman",
        "address2": "",
        "company": null,
        "latitude": 45.41634,
        "longitude": -75.6868,
        "name": "Bob Norman",
        "country_code": "US",
        "province_code": "KY"
      },
      "shipping_address": {
        "first_name": "Bob",
        "address1": "Chestnut Street 92",
        "phone": "555-625-1199",
        "city": "Louisville",
        "zip": "40202",
        "province": "Kentucky",
        "country": "United States",
        "last_name": "Norman",
        "address2": "",
        "company": null,
        "latitude": 45.41634,
        "longitude": -75.6868,
        "name": "Bob Norman",
        "country_code": "US",
        "province_code": "KY"
      },
      "fulfillments": [
        {
          "id": 255858046,
          "order_id": 450789469,
          "status": "failure",
          "created_at": "2017-07-22T11:53:09-04:00",
          "service": "manual",
          "updated_at": "2017-07-22T11:53:09-04:00",
          "tracking_company": null,
          "shipment_status": null,
          "tracking_number": "1Z2345",
          "tracking_numbers": [
            "1Z2345"
          ],
          "tracking_url": "http:\/\/wwwapps.ups.com\/etracking\/tracking.cgi?InquiryNumber1=1Z2345&TypeOfInquiryNumber=T&AcceptUPSLicenseAgreement=yes&submit=Track",
          "tracking_urls": [
            "http:\/\/wwwapps.ups.com\/etracking\/tracking.cgi?InquiryNumber1=1Z2345&TypeOfInquiryNumber=T&AcceptUPSLicenseAgreement=yes&submit=Track"
          ],
          "receipt": {
            "testcase": true,
            "authorization": "123456"
          },
          "line_items": [
            {
              "id": 466157049,
              "variant_id": 39072856,
              "title": "IPod Nano - 8gb",
              "quantity": 1,
              "price": "199.00",
              "grams": 200,
              "sku": "IPOD2008GREEN",
              "variant_title": "green",
              "vendor": null,
              "fulfillment_service": "manual",
              "product_id": 632910392,
              "requires_shipping": true,
              "taxable": true,
              "gift_card": false,
              "name": "IPod Nano - 8gb - green",
              "variant_inventory_management": "shopify",
              "properties": [
                {
                  "name": "Custom Engraving Front",
                  "value": "Happy Birthday"
                },
                {
                  "name": "Custom Engraving Back",
                  "value": "Merry Christmas"
                }
              ],
              "product_exists": true,
              "fulfillable_quantity": 1,
              "total_discount": "0.00",
              "fulfillment_status": null,
              "tax_lines": [
                {
                  "title": "State Tax",
                  "price": "3.98",
                  "rate": 0.06
                }
              ]
            }
          ]
        }
      ],
      "client_details": {
        "browser_ip": "0.0.0.0",
        "accept_language": null,
        "user_agent": null,
        "session_hash": null,
        "browser_width": null,
        "browser_height": null
      },
      "refunds": [
        {
          "id": 509562969,
          "order_id": 450789469,
          "created_at": "2017-07-22T11:53:09-04:00",
          "note": "it broke during shipping",
          "restock": true,
          "user_id": 799407056,
          "processed_at": "2017-07-22T11:53:09-04:00",
          "refund_line_items": [
            {
              "id": 104689539,
              "quantity": 1,
              "line_item_id": 703073504,
              "subtotal": 195.67,
              "total_tax": 3.98,
              "line_item": {
                "id": 703073504,
                "variant_id": 457924702,
                "title": "IPod Nano - 8gb",
                "quantity": 1,
                "price": "199.00",
                "grams": 200,
                "sku": "IPOD2008BLACK",
                "variant_title": "black",
                "vendor": null,
                "fulfillment_service": "manual",
                "product_id": 632910392,
                "requires_shipping": true,
                "taxable": true,
                "gift_card": false,
                "name": "IPod Nano - 8gb - black",
                "variant_inventory_management": "shopify",
                "properties": [
                ],
                "product_exists": true,
                "fulfillable_quantity": 1,
                "total_discount": "0.00",
                "fulfillment_status": null,
                "tax_lines": [
                  {
                    "title": "State Tax",
                    "price": "3.98",
                    "rate": 0.06
                  }
                ]
              }
            },
            {
              "id": 709875399,
              "quantity": 1,
              "line_item_id": 466157049,
              "subtotal": 195.66,
              "total_tax": 3.98,
              "line_item": {
                "id": 466157049,
                "variant_id": 39072856,
                "title": "IPod Nano - 8gb",
                "quantity": 1,
                "price": "199.00",
                "grams": 200,
                "sku": "IPOD2008GREEN",
                "variant_title": "green",
                "vendor": null,
                "fulfillment_service": "manual",
                "product_id": 632910392,
                "requires_shipping": true,
                "taxable": true,
                "gift_card": false,
                "name": "IPod Nano - 8gb - green",
                "variant_inventory_management": "shopify",
                "properties": [
                  {
                    "name": "Custom Engraving Front",
                    "value": "Happy Birthday"
                  },
                  {
                    "name": "Custom Engraving Back",
                    "value": "Merry Christmas"
                  }
                ],
                "product_exists": true,
                "fulfillable_quantity": 1,
                "total_discount": "0.00",
                "fulfillment_status": null,
                "tax_lines": [
                  {
                    "title": "State Tax",
                    "price": "3.98",
                    "rate": 0.06
                  }
                ]
              }
            }
          ],
          "transactions": [
            {
              "id": 179259969,
              "order_id": 450789469,
              "amount": "209.00",
              "kind": "refund",
              "gateway": "bogus",
              "status": "success",
              "message": null,
              "created_at": "2005-08-05T12:59:12-04:00",
              "test": false,
              "authorization": "authorization-key",
              "currency": "USD",
              "location_id": null,
              "user_id": null,
              "parent_id": 801038806,
              "device_id": null,
              "receipt": {},
              "error_code": null,
              "source_name": "web"
            }
          ],
          "order_adjustments": [
          ]
        }
      ]
    }
  ]
}';
		$orders = json_decode($json, true);
		$this->load->model('account/customer');
		$customer_info = array();
		if ($this->customer->isLogged()) {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
				
		}
		$this->load->model('shopify/order');
		foreach($orders['orders'] as $order){
			$od = $this->initOrder($order,$order_statuses,$customer_info);
				

			$this->session->data['order_id'] = $this->model_shopify_order->addOrder($od);
		}
		
		//return true;
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
	//return false;
		//$this->response->redirect($this->url->link('shopify/dashboard', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	
	public function initOrder($order,$order_statuses,$customer_info){
		
	
	$order_data =  array(
				'order_id'                => $order['id'],
				/*'invoice_no'              => $this->getValue($order,'invoice_no'),
				'invoice_prefix'          => $order['invoice_prefix'],
				'store_id'                => $order['store_id'],
				'store_name'              => $order['store_name'],
				'store_url'               => $order['store_url'],*/
				/*'customer_id'             => $order['customer_id'],
				'customer'                => $order['customer'],
				'customer_group_id'       => $order['customer_group_id'],
				'firstname'               => $order['firstname'],
				'lastname'                => $order['lastname'],*/
				'email'                   => $order['email'],
				'telephone'               => $order['shipping_address']['phone'],
				'custom_field'            => json_encode($order, true),
				'payment_firstname'       => $this->getValue($order,'payment_firstname'),
				'payment_lastname'        => $this->getValue($order,'payment_lastname'),
				'payment_company'         => $this->getValue($order,'payment_company'),
				'payment_address_1'       => $this->getValue($order,'payment_address_1'),
				'payment_address_2'       => $this->getValue($order,'payment_address_2'),
				'payment_postcode'        => $this->getValue($order,'payment_postcode'),
				'payment_city'            => $this->getValue($order,'payment_city'),
				'payment_zone_id'         => $this->getValue($order,'payment_zone_id'),
				'payment_zone'            => $this->getValue($order,'payment_zone'),
				'payment_zone_code'       => '',
				'payment_country_id'      => $this->getValue($order,'payment_country_id'),
				'payment_country'         => $this->getValue($order,'payment_country'),
				'payment_iso_code_2'      => '',
				'payment_iso_code_3'      => '',
				'payment_address_format'  => $this->getValue($order,'payment_address_format'),
				'payment_custom_field'    => '',
				'payment_method'          => $this->getValue($order,'payment_method'),
				'payment_code'            => $this->getValue($order,'payment_code'),
				'shipping_firstname'      => $order['shipping_address']['first_name'],
				'shipping_lastname'       => $order['shipping_address']['last_name'],
				'shipping_company'        => $order['shipping_address']['company'],
				'shipping_address_1'      => $order['shipping_address']['address1'],
				'shipping_address_2'      => $order['shipping_address']['address2'],
				'shipping_postcode'       => '',
				'shipping_city'           => $order['shipping_address']['city'],
				'shipping_zone_id'        => 0,
				'shipping_zone'           => $order['shipping_address']['province_code'],
				'shipping_zone_code'      => '',
				'shipping_country_id'     => 0,
				'shipping_country'        => $order['shipping_address']['country'],
				'shipping_iso_code_2'     => '',
				'shipping_iso_code_3'     => '',
				'shipping_address_format' => '',
				'shipping_custom_field'   => json_encode($order['shipping_address'], true),
				'shipping_method'         => 'Flat Shipping Rate',
				'shipping_code'           => $order['shipping_address']['country_code'],
				'comment'                 => $order['note'],
				'total'                   => $order['total_price'],
				'reward'                  => '',
				'order_status_id'         =>1,
				'order_status'            => !empty($order['fulfillment_status'])?$order['fulfillment_status']:$order['financial_status'],
				'affiliate_id'            => '',
				'affiliate_firstname'     => '',
				'affiliate_lastname'      => '',
				'commission'              => '',
				'language_id'             => 0,
				'language_code'           =>'',
				'currency_id'             => 0,
				'currency_code'           => $order['currency'],
				'currency_value'          => '',
				'ip'                      => $order['browser_ip'],
				'forwarded_ip'            => '',
				'user_agent'              =>  $this->getValue($order['client_details'],'user_agent'),
				'accept_language'         =>  $this->getValue($order['client_details'],'accept_language'),
				'date_added'              => $order['created_at'],
				'date_modified'           => $order['updated_at']
			);
			
			/*foreach($order_statuses as $status){
				echo $order['financial_status']."--".$status['name']." ==".($order['financial_status']==$status['name']);
				if($order['financial_status']==$status['name']){
					echo '\nid='.$status['order_status_id'];
					$order_data['order_status_id']=$status['order_status_id'];
					break;
				}
			}*/
			
			if (!empty($customer_info)) {
				$order_data['customer_id'] = $this->customer->getId();
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				//$order_data['email'] = $customer_info['email'];
				//$order_data['telephone'] = $customer_info['telephone'];
				$order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
			} elseif (isset($this->session->data['guest'])) {
				$order_data['customer_id'] = 0;
				$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$order_data['firstname'] = $this->session->data['guest']['firstname'];
				$order_data['lastname'] = $this->session->data['guest']['lastname'];
				//$order_data['email'] = $this->session->data['guest']['email'];
				//$order_data['telephone'] = $this->session->data['guest']['telephone'];
				$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
			}
			$order_data['invoice_prefix'] = $order['line_items'][0]['sku'];
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}
			return $order_data;	
	}
	
	public function getValue($order,$key){
		if(isset($order[$key])){
			return $order[$key];
		}
		return '';
	}
}

?>