<?php
class News_model extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}
	public function get($select = "*",$from = "",$join = "",$field = "",$value="")
	{
		//var_dump($select);echo '-';var_dump($from);echo '-';var_dump($join);echo '-';var_dump($field);echo '-';var_dump($value);//die;
                
            $this->db->order_by('id', 'desc');            
            if ($field && $value){ //var_dump($value);die;
			$this->db->where($field,$value);
		}
		$query = $this->db->get('news');
		$result = $query->result();//var_dump($result);die;
		$query->free_result();
                
		return $result;
	}
	public function insert($dataAdd)
	{
	    $this->db->insert("news",$dataAdd);
	//	return ;
	}
	public function update($dataEdit,$field = "",$value='') 							
	{
		//?? cap nhat mang $dataedit tất cả các trường $field và các giá trị $value = ???
		//cap nhat mang $dateEdit tai ban ghi co truong $field = gia tri $value.
		//E viet luon trong phpmyadmin de a de hinh dung nhe:D
		if($field && $field != "" && $value && $value != "") //??
		{
			$this->db->where($field,$value);
		}
		return $this->db->update("news", $dataEdit);
	}
	public function del($field="",$value="")
	{
		if($value){
			$this->db->where($field, $value);
		}elseif(is_array($value)){
			
			$this->db->where_in($field,$value);
		}
		return $this->db->delete("news");
	}
        public function sort ()
        {
            
            
        }
}
?>