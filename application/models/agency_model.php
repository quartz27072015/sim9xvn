<?php

class Agency_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function get($select = "*",$from = "",$join = "",$field = "",$value = "")
	{
		if ($select && $select != ''){
			$this->db->select($select);
		}
		if($join != ''){
			$this->db->join($join,$field,$value);
		}
		if(is_array($value)){
			$this->db->where_in($field,$value);
		} elseif($value != '' && $field != ''){
			$this->db->where($field, $value);
		}  
		$query = $this->db->get($from);		
		$result = $query->result();
		$query->free_result();		
		return $result;
	}
	public function insert($table,$dataarray) 
	{//$this->db->insert($table,$dataarray);
		
		if(is_array($dataarray)){
			$this->db->insert_batch($table,$dataarray);
			$profile_id = $this->db->insert_id();
			$this->db->insert('agency_percent',array('profileid' => $profile_id));
		} else{//var_dump($dataarray);die;
			$this->db->insert($table,array($dataarray));
			$profile_id = $this->db->insert_id();
			$this->db->insert('agency_percent',array('profileid' => $profile_id));
			
		}
			return ;
	}
	public function update($from = '',$dataEdit,$field = "", $value = "")
	{
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
		
		//Delete sim of agency
		if(is_array($value)){
			$this->db->where_in('agency_id',$value);
		} elseif($value != ''){
			$this->db->where('agency_id',$value);
		}
		
		if( $from != ''){
			$this->db->delete('sim');
		}
		
		return;
	}
}
?>