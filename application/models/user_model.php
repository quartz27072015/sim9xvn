<?php
class User_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function insert($table,$dataarray)
	{//$this->db->insert($table,$dataarray);
	//var_dump($dataarray);die;
		if(is_array($dataarray)){ //var_dump($dataarray);die;
			$this->db->insert_batch($table,$dataarray);
		}
		return ;
	}
	public function get($select = '',$from  = '',$field = '',$value = '')
	{
		if (isset($select) && $select != ''){
			$this->db->select($select);
		}
		if (isset($field) && $field != '' && isset($value) && $value != ''){
			$this->db->where($field,$value);
		}
		if (isset($from) && $from != ''){
			$query = $this->db->get($from);
		} else {
			$query = $this->db->get('user');
		}
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function update($from = '',$dataEdit,$field = "", $value = "")
	{//var_dump($dataEdit);die;
		if($field != ''&& $value != "")
		{
			$this->db->where($field, $value);
		}
		
		return $this->db->update($from,$dataEdit);
	}
	public function del($from = '',$field = "",$value = "")
	{//var_dump($value);die;
		if(is_array($value)){
			$this->db->where_in($field,$value);
		} elseif($value != ''){
			$this->db->where($field, $value);
		}
		
		if( $from != ''){
			$this->db->delete($from);
		}
		return;
	}
	public function checklogin($username, $password)
	{
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$query = $this->db->get('user');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
}
?>