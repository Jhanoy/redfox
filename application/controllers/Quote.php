<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quote extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Quotation';

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

		$this->data['page_title'] = 'Manage Quotations';
		$this->render_template('quote/index', $this->data);		
	}
	
	public function fetchQuotesData()
	{
		$result = array('data' => array());

		$data = $this->model_orders->getQuotesData();

		foreach ($data as $key => $value) {

			$count_total_item = $this->model_orders->countQuoteItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			$time = date('h:i a', $value['date_time']);

			$date_time = $date . ' ' . $time;

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="'.base_url('quote/printDiv/'.$value['id']).'" class="btn btn-default"><i class="fa fa-print"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('quote/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			if($value['status'] == 1) {
				$status = '<span class="label label-success">Approved</span>';	
			}
			else {
				$status = '<span class="label label-warning">Denied</span>';
			}

			$result['data'][$key] = array(
				$value['quot_no'],
				$value['customer_name'],
				$value['customer_phone'],
				$date_time,
				$count_total_item,
				$value['net_amount'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}	
	
    public function create()
    {
    	if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
    
    	$this->data['page_title'] = 'Create Quotation';
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
            	
        	$quote_id = $this->model_orders->create_quote();
        	
        	if($quote_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('quote/update/'.$quote_id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('quote/create/', 'refresh');
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
            $this->render_template('quote/create_quote', $this->data);
        }	
    }
    
	public function update($id)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}	

		$this->data['USERNAME'] = $this->username;	
		$this->data['page_title'] = 'Update Quote';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update_quote = $this->model_orders->update_quote($id);
        	
        	if($update_quote == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('quote/update/'.$id, 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('quote/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$quotes_data = $this->model_orders->getQuotesData($id);
    		$result['order'] = $quotes_data;
    		$quote_item = $this->model_orders->getQuotesItemData($quotes_data['id']);
    		$USERNAME = $this->model_users->getUserData($quotes_data['user_id']);
    		$result['USERNAME'] = $USERNAME;

    		foreach($quote_item as $k => $v) {
    			$result['quote_item'][] = $v;
    		}

    		$this->data['quote_data'] = $result;
        	$this->data['products'] = $this->model_products->getActiveProductData();      	
            $this->render_template('quote/edit_quote', $this->data);
        }
	}

public function printDiv($id)
  {
    if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
	    if($id) {
	      $quote_data = $this->model_orders->getQuotesData($id);
	      $quote_items = $this->model_orders->getQuotesItemData($id);
	      $attributes = $this->model_attributes->getAllAttributeValueData();
	      $company_info = $this->model_company->getCompanyData(1);
	      $USERNAME = array();
	      $USERNAME = $this->model_users->getUserData($quote_data['user_id']);

	      $quote_ref_date = date('y', $quote_data['date_time']);
	      $quote_date = date('d/m/Y', $quote_data['date_time']);
	      $status = ($quote_data['status'] == 1) ? "Approved" : "Declined";
	      $html = '
	      <!DOCTYPE html PUBLIC -//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	      <html xmlns="https://www.w3.org/1999/xhtml">
	      <head>
	      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	      <body style="font-family:Tahoma;font-size:12px;color: #333333;background-color:#FFFFFF;">
	      <table background="'.base_url('assets/images/water_mark_p.jpg').'" align="center" border="0" cellpadding="0" cellspacing="0" style="-webkit-print-color-adjust: exact; height:842px; width:631px;font-size:12px;">
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
	                  <strong>Customer Name: </strong>['.$quote_data['customer_name'].']
	                  <br/><strong>Customer Address: </strong>['.$quote_data['customer_address'].']
	                  <br/><strong>Customer Phone: </strong>['.$quote_data['customer_phone'].']<br/>
	                </td>
	                <td valign="top" width="35%"></td>
	                <td valign="top" width="30%" style="font-size:12px;">
	                  Issued By: '.$USERNAME['firstname'].' '.$USERNAME['lastname'].'<br/>
	      		        Date: '.$quote_date.'<br/>
	      		        Ref. No.QT<span id="refNo"> 0'.$quote_data['id'].'/'.$quote_ref_date.'</span>
	                </td>
	              </tr>
	            </table>
	            <table width="100%" height="100" cellspacing="0" cellpadding="0">
	              <tr>
	                <td><!-- THIS IS THE PROFORMA TITLE PLACE --></td>
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
	                  $count = 1;
	                  foreach ($quote_items as $k => $v) {
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
	                <td style="font-size:12px;width:50%;">
	                  <strong>---</strong>
	                </td>
	                <td>
	                  <table width="100%" cellspacing="0" cellpadding="2" border="0">
	                    <tr>
	                      <td align="right" style="font-size:12px;" >Subtotal</td>
	                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$quote_data['gross_amount'].'</td>
	                    </tr>';
	                    if($quote_data['service_charge'] > 0) {
	                      $html .= '<tr>
	                        <td  align="right" style="font-size:12px;">Service Charge ('.$quote_data['service_charge_rate'].'%)</td>
	                        <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$quote_data['service_charge'].'</td>
	                      </tr>';
	                     }
	                    if($quote_data['vat_charge'] > 0) { 
	                      $html .= '<tr>
	                        <td  align="right" style="font-size:12px;">VAT (15%)</td>
	                        <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc">'.$quote_data['vat_charge'].'</td>
	                      </tr>';
	                    }
	                    if($quote_data['discount'] > 0) {
		                    $html .= '<tr>
		                      <td  align="right" style="font-size:12px;"><b>Discount</b></td>
		                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc"><b>'.$quote_data['discount'].'</b></td>
		                    </tr>';
		                }
	                    $html .= '<tr>
	                      <td  align="right" style="font-size:12px;"><b>Total</b></td>
	                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc"><b>'.$quote_data['net_amount'].'</b></td>
	                    </tr>
	                    <!--<tr>
	                      <td  align="right" style="font-size:12px;"><b>Paid Status</b></td>
	                      <td  align="right" style="font-size:12px; border-bottom: 2px solid #cccccc"><b>'.$status.'</b></td>
	                    </tr> -->                            
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
	      <style rel="application/javascript">
	      	var refNo = document.getElementById("refNo").textContent;
	      	refNo.textContent = refNo.padStart(4, "0");
	      </script>
	      </body>
	      </html>';
	      echo $html;
      }
    }  	
	
}	