<?php
class index_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function get($select = "*",$from = "",$join = "",$field = "",$value="")
	{//var_dump($value);die;
		if($value){
			$this->db->where($field, $value);
		}elseif(is_array($value)){ 
			$this->db->where_in($field,$value);
		} 
		if ($join && $join != ''){
			$this->db->join($join,$field,$value);
		}
 
		$query = $this->db->get($from);
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function insert($dataAdd)
	{
	    $this->db->insert("news",$dataAdd);
	//	return ;
	}
	public function update($dataEdit,$where = "")
	{
		if($where && $where != "")
		{
			$this->db->where($where);
		}
		return $this->db->update("news", $dataEdit);
	}
	public function del($field="",$value="")
	{
	
			$this->db->where($field, $value);

		return $this->db->delete("news");
	}
	public function countproviderkind($where = '')
	{
		//var_dump($where[0]);die;
		if ($where != '' && is_array($where)){
				$this->db->where('provider',$where[0]);
				$this->db->like('kind',$where[1]);
		}
		return $this->db->count_all_results('sim');
	}
	public function countproviderheadkind($where = '')
	{
		//var_dump($where[0]);die;
		if ($where != '' && is_array($where)){
			$this->db->where('provider',$where[0]);
			$this->db->like('sim_original',$where[1]);
			if (isset($where[2])){
				$this->db->like('kind',$where[2]);
			}
		}
		return $this->db->count_all_results('sim');
	}
	public function list_all($number, $offset,$join = '',$provider = '', $kind = '',$head = '')
	{
		//echo $provider.'-'.$kind.'-'.$head;die;
		if($provider != ''){
			$this->db->where('provider',$provider);			
		}
		if ($kind != ''){
			$this->db->like('kind',$kind);
		}
		if ($head != ''){
			$this->db->like('sim_original',$head);
		}
		//echo $number.'-'.$offset;die;
		$this->db->limit($number,$offset);
		$query = $this->db->get('sim');
		return $query->result_array();
	}
	public function search($number,$offset,$provider,$price_lower,$price_upper,$sim,$n,$totalpoint,$nut,$number11,$number10)
	{
		if($provider && $provider != ''){
			$this->db->where('provider',$provider);//
		}
		if ($price_lower && $price_lower != ''){
			$this->db->where('price_sell >= ',$price_lower);
		}
		if ($price_upper && $price_upper != ''){
			$this->db->where('price_sell <= ',$price_upper);
		}
		if ($sim && $sim != ''){
			$this->db->where('sim_original',$sim);
		}
		//var_dump($n);die;
		if ($n && $n != null && $n[0][0] != null){//echo count($n[0]);die;
			$i=0;
			//var_dump($n[0]);die;
			for ($i=0;$i<count($n[0]);$i++){
			//var_dump($n[0][$i]);
			//echo $i;
				$this->db->where('instr(sim_original,'.$n[0][$i].') = 0');
				
			}
		}
		//die;
		/*Test toc do
		if ($n && $n != ''){
		}
		*/
		if ($totalpoint && $totalpoint != ''){
			$this->db->where('substring(sim_original,1,1)+substring(sim_original,2,1)+substring(sim_original,3,1)+
					substring(sim_original,4,1)+substring(sim_original,5,1)+substring(sim_original,6,1)+
					substring(sim_original,7,1)+substring(sim_original,8,1)+substring(sim_original,9,1)+
					substring(sim_original,10,1)+ +substring(sim_original,11,1) <',$totalpoint);
		}
		if ($nut && $nut != ''){
			$this->db->where('(substring(sim_original,1,1)+substring(sim_original,11,1)+
					substring(sim_original,10,1)+substring(sim_original,2,1)+substring(sim_original,3,1)+
					substring(sim_original,4,1)+substring(sim_original,5,1)+substring(sim_original,6,1)+
					substring(sim_original,7,1)+substring(sim_original,8,1)+substring(sim_original,9,1))/10 =',$nut);		}
		if ($number11 && $number11 != ''){
			$this->db->where('char_length(sim_original) = 11');
		}
		if ($number10 && $number10 != ''){
			$this->db->where('char_length(sim_original) = 10');
		}
		if ($number != ''){
			$this->db->limit($number,$offset);
		}
		$query = $this->db->get('sim');
		return $query->result_array();
	}
	public function filterspecial($number = '',$offset = '',$sim)
	{//echo strlen($sim);die;
		//If sign '*' at fisrt
		if (strpos($sim,'*') == 0){
			$stringtosearch = substr($sim,1,strlen($sim)-1);
			$length = strlen($stringtosearch);//echo $stringtosearch;//die;
			$this->db->where('substring(sim_original,-'.$length.','.$length.')=',$stringtosearch);
		}
		//If sign '*' at lastt
		if (strpos($sim,'*') == strlen($sim)-1){
			$stringtosearch = substr($sim,0,strlen($sim)-1);//echo $stringtosearch;die;
			$length = strlen($stringtosearch);
			$this->db->where('substring(sim_original,1,'.$length.')=',$stringtosearch);
		}
		//If sign '*' at center
		if ((strpos($sim,'*') >0) && (strpos($sim,'*') <strlen($sim))){
			$previous = substr($sim,0,strpos($sim,'*')); //echo $previous;
			$next = substr($sim,strpos($sim,'*')+1,strlen($sim)-strpos($sim,'*'));//echo $next;die;
			$this->db->where('substring(sim_original,1,'.strlen($previous).')=',$previous);
			$this->db->where('substring(sim_original,-'.strlen($next).','.strlen($next).')=',$next);
		}
		if ($number != ''){
			$this->db->limit($number,$offset);
		}
		$query = $this->db->get('sim');
		return $query->result_array();
	}
}
?>