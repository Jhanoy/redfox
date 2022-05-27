<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', $v['date_time']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	


			return $final_data;
			
		}
	}

	public function getProductData(){
		
		if(true) {
			$sql = "SELECT products.category_id, 
					products.name, 
					products.attribute_value_id, 
					products.price, 
					products.qty, 
					stores.name as store_name
					FROM products 
					INNER JOIN stores 
						ON products.store_id = stores.id";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}
	public function getSalesData(){

		if(true){
			$sql = "SELECT products.name,
					products.category_id,
					orders_item.qty,
					products.price,
					orders.vat_charge,
					orders.service_charge,
					orders.discount,
					products.price*orders_item.qty as total_price,
					orders.paid_status,
					orders.date_time,
					orders_item.store_id,
					users.username
				FROM orders
				JOIN orders_item
					ON orders.id = orders_item.order_id
				JOIN products
					ON orders_item.product_id = products.id
				JOIN stores
					ON orders_item.store_id = stores.id
				JOIN users
					ON orders.user_id = users.id";
		$query = $this->db->query($sql);
		return $query->result_array();
		}
	}

	public function getUserSalesData(){

		if(true){
			$sql = "SELECT
				orders.user_id, 
				users.username,
				SUM(
					CASE WHEN orders.with_vat = 1 THEN 1 ELSE 0 END
				) WITHVAT,
				SUM(
					CASE WHEN orders.with_vat = 0 THEN 1 ELSE 0 END
				) WITHOUTVAT
				FROM orders 
				JOIN users 
				ON orders.user_id = users.id 
				GROUP BY orders.user_id,
				users.username";
		$query = $this->db->query($sql);
		return $query->result_array();	
		}
	}

	public function getCustomersReportData(){
		if(true){
			$sql = "SELECT
				customers.id as customer_id,
				customers.fullname as customer_name, 
				SUM(
					case when orders.with_vat = 1 THEN 1 else 0 end
				) as with_vat,
				SUM(
					case when orders.with_vat = 0 THEN 1 else 0 end
				) as with_out_vat,
				SUM(
					case when orders.paid_status = 2 THEN 1 else 0 end
				) as cash_purchase,
				SUM(
					case when orders.paid_status = 1 THEN 1 else 0 end
				) as credit_purchase,
				count(orders.customer_id) as total_purchase
				from customers
				inner join orders on customers.id = orders.customer_id
				group by customers.id";
		$query = $this->db->query($sql);
		return $query->result_array();	
		}		
	}
	public function getTotalCreditSales(){
		$sql = "SELECT COUNT(paid_status) as tot_credit FROM orders WHERE paid_status = 2";
		$query = $this->db->query($sql);
		return $query->row_array();
	}	
}

