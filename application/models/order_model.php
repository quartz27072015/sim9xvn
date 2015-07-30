<?php
class Order_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function get($select = "",$from = "",$join = "",$field = "",$value="",$start='',$limit='')
	{
		//var_dump($join);die;
		if ($limit != ''){
			//$this->order_by('id_sim','asc');
			$this->db->limit($limit);
			$this->db->order_by('orderid','desc');
		}
		//if (is_array($field)){
		//	$this->db->where_in($field);
		//}else
		if ($field != '' && $value != ''){
			$this->db->where($field,$value);
		}
		if ($join != ''){
			$this->db->join($join[0],$from.'.'.$join[1].' = '.$join[0].'.'.$join[2]);
		}
		
		if ($from && $from !=''){
			$query = $this->db->get($from);
		} else{
			$query = $this->db->get('order');
		}
		
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function getonly($detailid = '')
	{
		$this->db->where('detailid',$detailid);
		
		$this->db->join('order','order_detail.order_id = order.orderid');
		$this->db->where('order.status !=','delete'); 
		$this->db->join('sim','sim.id_sim = order_detail.simid');
		$this->db->join('agency_profile','agency_profile.profileid = order_detail.agencyid');
		$query = $this->db->get('order_detail');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function insert($from,$dataAdd)
	{
		//var_dump($dataAdd);die;
		if ($from && $from != ''){
			$this->db->insert($from,$dataAdd);
		} else{
	    	$this->db->insert("order",$dataAdd);
		}
	//	return ;
	}
	public function insert_multi_tables($order,$simArray,$pricesellArray,$agencyArray,$idsimArray)
	{
		if ($order != '' && $simArray != '' &&  $pricesellArray != ''){
			$this->db->insert('order',$order);
			$order_id = $this->db->insert_id();//var_dump($orderdetail);die;
			//if (is_array($orderdetail)){
				//Generate order_id for element of Array $orderdetail
				for ($i=0;$i<sizeof($simArray);$i++){
					$orderdetail[] = array(
							'order_id'=>$order_id,
							'sim_split' => $simArray[$i],
							'price_sell' => $pricesellArray[$i],
							'agencyid' => $agencyArray[$i],
							'simid' => $idsimArray[$i],
							'od_status' => 'pending'
					);												
				}
				//var_dump($orderdetail);die;
				$this->db->insert_batch('order_detail',$orderdetail);
			//} else	$this->db->insert('order_detail',$orderdetail);
			
			return;
		} else {
			echo 'Đơn hàng lỗi';
			return false;
		}
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
	public function del($field="",$value="")
	{
		//$this->db->join('order_detail','order_detail.order_id=order.orderid');
		if (is_array($value)){
			$this->db->where_in($field,$value);
			$data = array('status' => 'delete');
			$this->db->update("order",$data);
			return;
		}elseif ($field != ''){
		$this->db->where($field,$value);
		 $data = array('status' => 'delete');
		 $this->db->update("order",$data);
		 return;
		} else  return;
	}
	public function list_all($number, $offset, $field = '',$value = '')
	{
		//var_dump($field);die;
		if (is_array($field)){
			$this->db->where('order.status','processing');
			//$this->db->join('order_detail','order_detail.order_id = order.orderid');
			//$this->db->join('sim','sim.id_sim = order_detail.simid');
			if ($field[0] != '') $this->db->like('fullname',$field[0]);
			if ($field[1] != '') {
				$this->db->like('phone',$field[1]);
				$this->db->like('sim_original',$field[1]);
			}
			if ($field[2] != '') $this->db->like('datetime',$field[2]);
			if ($field[3] != '') $this->db->like('address',$field[3]);
			
		} else {
			$this->db->where($field,$value);
		}
		$this->db->limit($number,$offset);
		
		$query = $this->db->get('order');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
	public function search($dataSearch)
	{

		$query = $this->db->get('order');
		$result = $query->result();
		$query->free_result();
		return $result;
	}
}
?>