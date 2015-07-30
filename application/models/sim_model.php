<?php
class Sim_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function get($select = '*', $from = '',$join = '', $field = '', $value = '',$start = '', $limit = '' )
	{
		if ($select && $select != ''){
			$this->db->select($select);
		}
		if ($join == ''){
			
			if(is_array($value)){// echo 'array';die;
				$this->db->where_in($field,$value);
			} elseif($value){//echo 'noarray';die;
				$this->db->where($field, $value);
			}
		}
		else if($join != '' ){
			//$this->db->join($join,$field,$value); //var_dump($join);die;
			$this->db->join($join,$field,$value);
		} 
		if ($limit != '' && $start >= 0){ 
			//$this->order_by('id_sim','asc');
			$this->db->limit($limit,$start);
		}
		if($from && $from != ''){
			$query = $this->db->get($from);
		} else{
			$query = $this->db->get('sim');
		}
				
		$result = $query->result();
		$query->free_result();		
		return $result;
	}
	public function getonly($select = '*',$where = '')
	{
		if($where && $where != '')
		{
			$this->db->where($where);
		}
		$query = $this->db->get('agency_percent');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function checksim($field,$value){
		$this->db->join('agency_profile','agency_profile.profileid=sim.agency_id');
		$this->db->where($field,$value);
		$query = $this->db->get('sim');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function insert($table,$dataarray)
	{
	    $this->db->insert_batch($table,$dataarray);
	//	return ;
	}

	public function update($from = '',$dataEdit,$field = "", $value="")
	{
		if($field != '' && $value != "")
		{
			$this->db->where($field,$value);
		}
		if (is_array($dataEdit)){ 
			return $this->db->update($from,$dataEdit);
		} else { 
			return $this->db->update($from,$dataEdit);
		}
	}
	public function del($from = '',$field = "",$value = "")
	{//var_dump($value);die;
		if(is_array($value)){
			$this->db->where_in($field, $value);
		}elseif($value != ''){ 
			$this->db->where($field,$value);
		} 
		if($from && $from != ''){
			return $this->db->delete($from);
		}
	}
	public function countresults()
	{

		//$this->db->where('');
		return $this->db->count_all_results('sim');
	}
	public function countsold()
	{
		$this->db->where('status','sold');
		return $this->db->count_all_results('sim');
	}
	public function delduplicate($type)
	{
		//Distribute array to compare
		$this->db->select('sim_original');
		$this->db->group_by('sim_original');
		$this->db->having('count(sim_original) > 1');
		$dataCompare = $this->db->get('sim')->result_array(); var_dump($dataCompare);die;
		if ($dataCompare != null){
			$dataToDelTemp = array();
			//Filter by highetst price sell to delete
			$this->db->select('*');
			if ($type != null){
				if ($type = 'max'){
					$this->db->select_max('price_sell');
				} elseif ($type == 'min'){
					$this->db->select_min('price_sell');
				}
			}
					
			$this->db->where_in('sim_original',$dataCompare[0]);
			$this->db->order_by('price_sell','desc');
			$dataToDel = $this->db->get('sim')->result();//var_dump($dataToDel);die;
			$dataToDelTemp[] = $dataToDel;
			//Ready to delete
			$this->db->where('id_sim',$dataToDel[0]->id_sim);
			$this->db->delete('sim');//var_dump($dataToDel[0]);die;
			return $dataToDelTemp;//[0]->id_sim;
		} //return false;
	}

	public function list_all($number, $offset,$join = '',$field = '',$value= '')
	{
		if (is_array($field)){
			$this->db->join($join,'sim.'.$field[0].' = agency_profile.'.$field[1]);
			$this->db->where($field[2],$field[3]);
		}else {
			if($join != '' && $field != '' && $value !=''){
			 
				$this->db->join($join,$field,$value);
			}
		}
		$this->db->limit($number,$offset);
		$query = $this->db->get('sim');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
}
?>