<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Stores';
		$this->load->model('model_stores');
		$this->load->model('model_reports');
		$this->load->model('model_products');
		$this->load->model('model_attributes');
		$this->load->model('model_category');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}

		$parking_data = $this->model_reports->getOrderData($today_year);
		$this->data['report_years'] = $this->model_reports->getOrderYear();
		

		$final_parking_data = array();
		foreach ($parking_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_amount_earned[] = $v2['gross_amount'];						
					}
				}
				$final_parking_data[$k] = array_sum($total_amount_earned);	
			}
			else {
				$final_parking_data[$k] = 0;	
			}
			
		}
		
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_parking_data;

		$this->render_template('reports/index', $this->data);
	}

	public function products(){

		$products = $this->model_reports->getProductData();
		$categories = $this->model_category->getCategoryData();
		$attributes = $this->model_attributes->getAllAttributeValueData();		
		$this->data['products'] = $products;
		$this->data['category'] = $categories;
		$this->data['attributes'] = $attributes;
		$this->render_template('reports/products', $this->data);
	}

	public function sales(){
		$categories = $this->model_category->getCategoryData();
		$store = $this->model_stores->getStoresData();
		$sales = $this->model_reports->getSalesData();
		$this->data['store'] = $store;
		$this->data['category'] = $categories;
		$this->data['sales'] = $sales;
		$this->render_template('reports/sales', $this->data);
	}

	public function user_sales(){
		$user_sales = $this->model_reports->getUserSalesData();
		$this->data['user_sales'] = $user_sales;
		$this->render_template('reports/user_vat_history', $this->data);
	}

	public function customers_report(){
		$customers_report = $this->model_reports->getCustomersReportData();
		$this->data['customers_report'] = $customers_report;
		$this->render_template('reports/customers_purchase_report', $this->data);
	}	
}	