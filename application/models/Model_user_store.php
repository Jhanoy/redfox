<?php 

class Model_user_store extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getUserStoreData($user_id = null) 
	{
		if ($user_id) {
			$sql = "SELECT * FROM user_store WHERE user_id = ?";
			$query = $this->db->query($sql, array($user_id));
			return $query->row_array();	
		}
		
	}
}

?>