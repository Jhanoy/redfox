<?php 

class Model_customers extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function getCustomerData($userId = null){
		if($userId) {
			$sql = "SELECT * FROM customers WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM customers WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data){
		if($data) {
			$create = $this->db->insert('customers', $data);
			$user_id = $this->db->insert_id();
			return ($create == true) ? true : false;
		}
	}

	public function edit($data = array(), $id = null){
		$this->db->where('id', $id);
		$update = $this->db->update('customers', $data);
		return ($update == true) ? true : false;	
	}

	public function delete($id){
		$this->db->where('id', $id);
		$delete = $this->db->delete('customers');
		return ($delete == true) ? true : false;
	}

	public function countTotalCustomers(){
		$sql = "SELECT * FROM customers";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
}