<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';

		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_customers');
		$this->load->model('model_users');
		$this->load->model('model_user_store');
		$this->load->model('model_attributes');
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage Orders';
		$this->render_template('orders/index', $this->data);		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$result = array('data' => array());

		$data = $this->model_orders->getOrdersData();

		foreach ($data as $key => $value) {

			$count_total_item = $this->model_orders->countOrderItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			$time = date('h:i a', $value['date_time']);

			$date_time = $date . ' ' . $time;

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('orders/printDiv/'.$value['id']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('orders/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			if($value['paid_status'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';	
			}
			else {
				$paid_status = '<span class="label label-warning">Not Paid</span>';
			}

			$result['data'][$key] = array(
				$value['bill_no'],
				$value['customer_name'],
				$value['customer_phone'],
				$date_time,
				$count_total_item,
				$value['net_amount'],
				$paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Add Sales';
/////////////////////////////////// NEW EDIT CUSTOMER /////////////////////////////////////////////////////

		$customer_data = $this->model_customers->getCustomerData();
		$result = array();
		foreach ($customer_data as $k => $v) {
			$result[$k]['customer_info'] = $v;
		}
		$this->data['customer_data'] = $result;


///////////////////////////////////// NEW EDIT USER ///////////////////////////////////////////////////////		

		$this->data['USERNAME'] = $this->username;
		$this->data['user_id'] = $this->user_id;
			

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
        if ($this->form_validation->run() == TRUE) { 

        	$is_stock_available = false;
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {

	    			$form_qty = $this->input->post('qty')[$x];
	    			$store_id = $this->model_products->getProductData($this->input->post('product')[$x])['store_id'];
	    			$product_name = $this->model_products->getProductData($this->input->post('product')[$x])['name'];
	    			$product_qty = $this->model_products->getProductData($this->input->post('product')[$x])['qty'];
	    		
	    		if($form_qty > $product_qty){
	    			$this->session->set_flashdata('stock_error','Only '.'<b>'.$product_qty.' '.$product_name.'</b>'.'(s) left in the store!');
	    			redirect('orders/create/', 'refresh');	
	    		}
/* added this one */  	$is_stock_available = true;
	    	}            
	        if($is_stock_available) {		        	
	        	$order_id = $this->model_orders->create();
	        	
	        	if($order_id) {
	        		$this->session->set_flashdata('success', 'Successfully created');
	        		redirect('orders/update/'.$order_id, 'refresh');
	        	}
	        	else {
	        		$this->session->set_flashdata('errors', 'Error occurred!!');
	        		redirect('orders/create/', 'refresh');
	        	}
	        }
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;
			
			$user_data = $this->model_users->getUserData();
        	$user_store = $this->model_user_store->getUserStoreData($this->user_id);
        	$products = $this->model_products->getActiveProductData($this->user_id, $user_store['store_id']);
        	
        	$this->data['products'] = $products;
            $this->render_template('orders/create', $this->data);
        }	
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

///////////////////////////////////// NEW EDIT USER ///////////////////////////////////////////////////////		

		$this->data['USERNAME'] = $this->username;

///////////////////////////////////// NEW EDIT USER ///////////////////////////////////////////////////////	
		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_orders->update($id);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_orders->getOrdersData($id);

    		$result['order'] = $orders_data;
    		$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);
    		$USERNAME = $this->model_users->getUserData($orders_data['user_id']);
    		$result['USERNAME'] = $USERNAME;

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$this->data['order_data'] = $result;

        	$this->data['products'] = $this->model_products->getActiveProductData();      	

            $this->render_template('orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$order_id = $this->input->post('order_id');

        $response = array();
        if($order_id) {
            $delete = $this->model_orders->remove($order_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response); 
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/

public function printDiv($id)
  {
    if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
	    if($id) {
	      $order_data = $this->model_orders->getOrdersData($id);
	      $orders_items = $this->model_orders->getOrdersItemData($id);
	   	  $attributes = $this->model_attributes->getAllAttributeValueData();
	      $company_info = $this->model_company->getCompanyData(1);
	      $USERNAME = array();
	      $USERNAME = $this->model_users->getUserData($order_data['user_id']);

	      $order_date = date('d/m/Y', $order_data['date_time']);
	      $quote_ref_date = date('y', $order_data['date_time']);	      
	      $paid_status = ($order_data['paid_status'] == 1) ? "Cash" : "Credit";
                         

	      $html = '
	      <!DOCTYPE html PUBLIC -//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	      <html xmlns="https://www.w3.org/1999/xhtml">
	      <head>
	      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	      <body style="font-family:Tahoma;font-size:12px;color: #333333;background-color:#FFFFFF;">
	      <table background="'.base_url('assets/images/water_mark_p.jpg').'" align="center" border="0" cellpadding="0" cellspacing="0" style="-webkit-print-color-adjust: exact; height:888px; width:631px;font-size:12px;">
	        <tr>
	          <td valign="top">
	            <table width="100%" cellspacing="0" cellpadding="0">
	              <tr>
	                <td valign="bottom" width="50%" height="50">
	                  <div align="left">
	                    <img src="'.base_url('assets/images/redfox.png').'" /></div><br />
	                </td>
	                <td width="50%">&nbsp;</td>
	              </tr>
	            </table>Bill To: <br/><br/>
	            <table width="100%" cellspacing="0" cellpadding="0">
	              <tr>
	                <td valign="top" width="35%" style="font-size:12px;">
	                  <strong>Customer Name: </strong>['.$order_data['customer_name'].']
	                  <br/><strong>Customer Address: </strong>['.$order_data['customer_address'].']
	                  <br/><strong>Customer Phone: </strong>['.$order_data['customer_phone'].']<br/>
	                </td>
	                <td valign="top" width="35%"></td>
	                <td valign="top" width="30%" style="font-size:12px;">
	                  Issued By: '.$USERNAME['firstname'].' '.$USERNAME['lastname'].'<br/>
	      		        Date: '.$order_date.'<br/>
	      		        Ref. No.DO<span id="refNo"> 0'.$order_data['id'].'/'.$quote_ref_date.'</span>	      		        
	                </td>
	              </tr>
	            </table>
	            <table width="100%" height="100" cellspacing="0" cellpadding="0">
	              <tr>
	                <!-- <td><div align="center" style="font-size: 14px;font-weight: bold;">Proforma # '.$order_data['bill_no'].'</div></td> -->
	              </tr>
	            </table>
	            <table width="100%" cellspacing="0" cellpadding="2" border="1" bordercolor="#CCCCCC">
	              <tr>
	                <td width="5%" bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px;"><strong>No.</strong></td>	              
	                <td width="35%" bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px;"><strong>Product</strong></td>
	                <td bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px;"><strong>Qty</strong></td>
	                <td bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px;"><strong>Unit Price</strong></td>
	                <td bordercolor="#ccc" bgcolor="#f2f2f2" style="font-size:12px;"><strong>Subtotal</strong></td>
	              </tr>
	              <tr style="display:none;">
	                <td colspan="*">';
	                  foreach ($orders_items as $k => $v) {
	                  	$count = 1;
	                    $product_data = $this->model_products->getProductData($v['product_id']);
		                $att = json_decode($product_data['attribute_value_id']);
	                    $html .= '<tr>
						  <td>'.$count++.'</td>
	                      <td valign="top" style="font-size:12px;">'.$product_data['name'].' (';
	                      
	                          if(is_array($att)){
	                            // echo 'TRUE';
	                            foreach ($att as $key => $val){
	                                // echo $val;
	                                // print_r($attributes);
	                              foreach ($attributes as $_k => $_v){
	                                if($_v['id'] == $val) 
	                                	$html .='<span>'.$_v['value'].',</span>';
	                            	}
	                            }
	                          }
	                     $html .= ')</td>
	                      <td valign="top" style="font-size:12px;">'.$v['qty'].'</td>
	                      <td valign="top" style="font-size:12px;">'.$v['rate'].'</td>
	                      <td valign="top" style="font-size:12px;">'.$v['amount'].'</td>
	                    </tr>';
	                  }
	                $html .= '</td>
	              </tr>
	            </table>
	            <table width="100%" cellspacing="0" cellpadding="2" border="0">
	              <tr>
	                <!--
	                <td style="font-size:12px;width:50%;">
	                  <strong>ONE THOUSAND  ONE HUNDRED SIXTY THREE USD AND 44 CENTS</strong>
	                </td>
	                -->
	                <td>
	                  <table width="100%" cellspacing="0" cellpadding="2" border="0">
	                    <tr>
	                      <td align="right" style="font-size:12px;" >Subtotal</td>
	                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$order_data['gross_amount'].'</td>
	                    </tr>';
	                    if($order_data['service_charge'] > 0) {
	                      $html .= '<tr>
	                        <td  align="right" style="font-size:12px;">Service Charge ('.$order_data['service_charge_rate'].'%)</td>
	                        <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$order_data['service_charge'].'</td>
	                      </tr>';
	                     }
	                    if($order_data['vat_charge'] > 0) { 
	                      $html .= '<tr>
	                        <td  align="right" style="font-size:12px;">VAT ('.$order_data['vat_charge_rate'].'%)</td>
	                        <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$order_data['vat_charge'].'</td>
	                      </tr>';
	                    }
	                    if($order_data['discount'] > 0) {
		                    $html .= '<tr>
		                      <td  align="right" style="font-size:12px;"><b>Discount</b></td>
		                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc"><b>'.$order_data['discount'].'</b></td>
		                    </tr>';
		                }
	                    $html .= '<tr>
	                      <td  align="right" style="font-size:12px;"><b>Total</b></td>
	                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc"><b>'.$order_data['net_amount'].'</b></td>
	                    </tr>                            
	                  </table>
	                </td>
	              </tr>
	            </table> 
	            <table width="100%" height="50">
	              <tr>
	                <td style="font-size:12px;text-align:justify;"><center>Your Engineering Solution!</center></td>
	              </tr>
	            </table>
	            <table  width="100%" cellspacing="0" cellpadding="2">
	              <tr>
	                <td width="33%" style="border-top:double medium #CCCCCC; font-size:12px;" align="center" valign="top">
	                  <center>Tel: +251 90 399 944 | +251 90 399 955 | E-mail: info@redfoxeme.com | Web:www.redfoxeme.com</center>
	                  </b> Airport road - Next to Aberus Building. Mobile Plaze Office no. B8B | Addis Ababa, Ethiopia
	                </td>
	              </tr>
	            </table>
	          </td>
	        </tr>
	      </table>
	      </body>
	      </html>';
	      echo $html;
      }
    }  	

// 	public function printDiv($id)
// 	{
// 		if(!in_array('viewOrder', $this->permission)) {
//             redirect('dashboard', 'refresh');
//         }
        
// 		if($id) {
// 			$order_data = $this->model_orders->getOrdersData($id);
// 			$orders_items = $this->model_orders->getOrdersItemData($id);
// 			$company_info = $this->model_company->getCompanyData(1);
// 			$USERNAME = array();
// 			$USERNAME = $this->model_users->getUserData($order_data['user_id']);
// ///////////////////////////////////// NEW EDIT USER ///////////////////////////////////////////////////////		

// 			// $USERNAME = $this->username;

// ///////////////////////////////////// NEW EDIT USER ///////////////////////////////////////////////////////	
// 			$order_date = date('d/m/Y', $order_data['date_time']);
// 			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

// 			$html = '<!-- Main content -->
// 			<!DOCTYPE html>
// 			<html>
// 			<head>
// 			  <meta charset="utf-8">
// 			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
// 			  <title>AdminLTE 2 | Invoice</title>
// 			  <!-- Tell the browser to be responsive to screen width -->
// 			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
// 			  <!-- Bootstrap 3.3.7 -->
// 			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
// 			  <!-- Font Awesome -->
// 			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
// 			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
// 			</head>
// 			<body onload="window.print();">
			
// 			<div class="wrapper">
// 			  <section class="invoice">
// 			    <!-- title row -->
// 			    <div class="row">
// 			      <div class="col-xs-12">
// 			        <h2 class="page-header">
// 			          '.$company_info['company_name'].'
// 			          <small class="pull-right">Date: '.$order_date.'</small></br>
// 			          <small class="pull-right">Issued By: '.$USERNAME['firstname'].' '.$USERNAME['lastname'].'</small>
// 			        </h2>
// 			      </div>
// 			      <!-- /.col -->
// 			    </div>
// 			    <!-- info row -->
// 			    <div class="row invoice-info">
			      
// 			      <div class="col-sm-4 invoice-col">
			        
// 			        <b>Bill ID:</b> '.$order_data['bill_no'].'<br>
// 			        <b>Name:</b> '.$order_data['customer_name'].'<br>
// 			        <b>Address:</b> '.$order_data['customer_address'].' <br />
// 			        <b>Phone:</b> '.$order_data['customer_phone'].'
// 			      </div>
// 			      <!-- /.col -->
// 			    </div>
// 			    <!-- /.row -->

// 			    <!-- Table row -->
// 			    <div class="row">
// 			      <div class="col-xs-12 table-responsive">
// 			        <table class="table table-striped">
// 			          <thead>
// 			          <tr>
// 			            <th>Product name</th>
// 			            <th>Price</th>
// 			            <th>Qty</th>
// 			            <th>Amount</th>
// 			          </tr>
// 			          </thead>
// 			          <tbody>'; 

// 			          foreach ($orders_items as $k => $v) {

// 			          	$product_data = $this->model_products->getProductData($v['product_id']); 
			          	
// 			          	$html .= '<tr>
// 				            <td>'.$product_data['name'].'</td>
// 				            <td>'.$v['rate'].'</td>
// 				            <td>'.$v['qty'].'</td>
// 				            <td>'.$v['amount'].'</td>
// 			          	</tr>';
// 			          }
			          
// 			          $html .= '</tbody>
// 			        </table>
// 			      </div>
// 			      <!-- /.col -->
// 			    </div>
// 			    <!-- /.row -->

// 			    <div class="row">
			      
// 			      <div class="col-xs-6 pull pull-right">

// 			        <div class="table-responsive">
// 			          <table class="table">
// 			            <tr>
// 			              <th style="width:50%">Gross Amount:</th>
// 			              <td>'.$order_data['gross_amount'].'</td>
// 			            </tr>';

// 			            if($order_data['service_charge'] > 0) {
// 			            	$html .= '<tr>
// 				              <th>Service Charge ('.$order_data['service_charge_rate'].'%)</th>
// 				              <td>'.$order_data['service_charge'].'</td>
// 				            </tr>';
// 			            }

// 			            if($order_data['vat_charge'] > 0) {
// 			            	$html .= '<tr>
// 				              <th>Vat Charge ('.$order_data['vat_charge_rate'].'%)</th>
// 				              <td>'.$order_data['vat_charge'].'</td>
// 				            </tr>';
// 			            }
			            
			            
// 			            $html .=' <tr>
// 			              <th>Discount:</th>
// 			              <td>'.$order_data['discount'].'</td>
// 			            </tr>
// 			            <tr>
// 			              <th>Net Amount:</th>
// 			              <td>'.$order_data['net_amount'].'</td>
// 			            </tr>
// 			            <tr>
// 			              <th>Paid Status:</th>
// 			              <td>'.$paid_status.'</td>
// 			            </tr>
// 			          </table>
// 			        </div>
// 			      </div>
// 			      <!-- /.col -->
// 			    </div>
// 			    <!-- /.row -->
// 			  </section>
// 			  <!-- /.content -->
// 			</div>
// 		</body>
// 	</html>';

// 			  echo $html;
// 		}
// 	}
	function printPf(){
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		$order_date = date('d/m/Y');

		$html = '<!-- Main content -->
		<!DOCTYPE html>
		<html>
		<head>
		  <meta charset="utf-8">
		  <meta http-equiv="X-UA-Compatible" content="IE=edge">
		  <title>AdminLTE 2 | Invoice</title>
		  <!-- Tell the browser to be responsive to screen width -->
		  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		  <!-- Bootstrap 3.3.7 -->
		  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
		  <!-- Font Awesome -->
		  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
		  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
		</head>
		<body onload="window.print();">
		
		<div class="wrapper">
		  <section class="invoice">
		    <!-- title row -->
		    <div class="row">
		      <div class="col-xs-12">
		        <h2 class="page-header">
		          '.$this->company_name.'
		          <small class="pull-right">Date: '.$order_date.'</small></br>
		          <small class="pull-right">Issued By: '.$this->username.'</small>
		        </h2>
		      </div>
		      <!-- /.col -->
		    </div>
		    <!-- info row -->
		    <div class="row invoice-info">
		      
		      <div class="col-sm-4 invoice-col">
		        
		        <b>Name:</b> '.$this->input->post('customer_name').'<br>
		        <b>Address:</b> '.$this->input->post('customer_address').' <br />
		        <b>Phone:</b> '.$this->input->post('customer_phone').'
		      </div>
		      <!-- /.col -->
		    </div>
		    <!-- /.row -->

		    <!-- Table row -->
		    <div class="row">
		      <div class="col-xs-12 table-responsive">
		        <table class="table table-striped">
		          <thead>
		          <tr>
		            <th>Product name</th>
		            <th>Price</th>
		            <th>Qty</th>
		            <th>Amount</th>
		          </tr>
		          </thead>
		          <tbody>'; 

		        $count_product = count($this->input->post('product'));
    			
    			for($x = 0; $x < $count_product; $x++) {
		          	
		          	$html .= '<tr>
			            <td>'.$this->model_products->getProductData($this->input->post('product')[$x])['name'].'</td>
			            <td>'.$this->input->post('rate_value')[$x].'</td>
			            <td>'.$this->input->post('qty')[$x].'</td>
			            <td>'.$this->input->post('amount_value')[$x].'</td>
		          	</tr>';
		         }
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-6 pull pull-right">

			        <div class="table-responsive">
			          <table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>'.$this->input->post('gross_amount_value').'</td>
			            </tr>';

			            if($this->input->post('service_charge_value') > 0) {
			            	$html .= '<tr>
				              <th>Service Charge ('.$this->input->post('service_charge_rate').'%)</th>
				              <td>'.$this->input->post('service_charge_value').'</td>
				            </tr>';
			            }

			            if($this->input->post('vat_charge_value') > 0) {
			            	$html .= '<tr>
				              <th>Vat Charge ('.$this->input->post('vat_charge_rate').'%)</th>
				              <td>'.$this->input->post('vat_charge_value').'</td>
				            </tr>';
			            }
			            
			            
			            $html .=' <tr>
			              <th>Discount:</th>
			              <td>'.$this->input->post('discount').'</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>'.$this->input->post('net_amount_value').'</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			  echo $html;			         

	}
}