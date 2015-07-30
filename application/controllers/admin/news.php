<?php
class news extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('news_model');
	}
	public function index()
	{//var_dump($_POST['checkbox']);die;
		if (isset($_POST['delselect'])){//echo 'fdsf';die;
			$checkbox=$_POST['checkbox'];
			
			for($i=0;$i<sizeof($checkbox);$i++ ){
				$this->news_model->del('id',$checkbox[$i]);
			}
			redirect(base_url().'admin/news', 'location');
		} else{
			$data['news']=$this->news_model->get('*','news');
			$this->load->view('admin/news/manage-news.phtml',$data);
		}
	}
	public function add()
	{
		if(!isset($_POST['title'])){
		
			$this->load->view('admin/news/add-news.phtml');
		} else{
			$dataAdd = array(
				'title' => $_POST['title'],
					'content' => $_POST['content'], 
					'urlfriendly' =>$this->chuyenChuoi($this->createSlug($_POST['title'])),
					
				
			);
			
			
			if ($this->news_model->insert($dataAdd))
			{
				$this->session->set_flashdata('sessionSuccessAdd', 1);
			}
			//After Success redirect admin import sim
			redirect(base_url().'admin/news', 'location');
		}
	}
	public function edit($num)
	{

			$data['news'] = $this->news_model->get('*','news','','id',$num);
			//$this->load->view('admin/news/add-news.phtml',$data);
			//var_dump($num); die();
			if (isset($_POST['title'])){
				$dataEdit =array(
					'title' => $_POST['title'],
						'content' => $_POST['content'],
						//'id' => $_POST['num']
						'urlfriendly' =>$this->chuyenChuoi($this->createSlug($_POST['title'])),
						
				);
				//var_dump ($dataEdit); die;
				if ($this->news_model->update($dataEdit,'id',$num))
				{
					$this->session->set_flashdata('sessionSuccessEdit', 1);
				//	var_dump($num); die;
				}
				redirect(base_url().'admin/news', 'location');
			} else{
				$this->load->view('admin/news/add-news.phtml',$data);				
			}	
			
	}
	public function del($num)
	{
		if (isset($num)){
			if ($this->news_model->del('id',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/news', 'location');
		} else{ 
			
			redirect(base_url().'admin/news', 'location');
		}
	}
	public function createSlug($slug) 
	{
	$letterNumbersSpaceHypens = '/[^\-\s\pN\pL]+/u';
	$spaceDuplicateHypens = '/[\-\s]+/';
	$slug = preg_replace($letterNumbersSpaceHypens, '', mb_strtolower($slug, 'UTF-8') );    
	$slug = preg_replace($spaceDuplicateHypens, '-', $slug);  
	$slug = trim($slug, '-');
	return $slug;
	}
	public function chuyenChuoi($str) {
// In thường
     $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
     $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
     $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
     $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
     $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
     $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
     $str = preg_replace("/(đ)/", 'd', $str);    
     $str = html_entity_decode ($str);
     $str = str_replace(array(' ','_'), '-', $str); 
        $str = html_entity_decode ($str);
        $str = str_replace("ç","c",$str);
        $str = str_replace("Ç","C",$str);
        $str = str_replace(" / ","-",$str);
        $str = str_replace("/","-",$str);
        $str = str_replace(" - ","-",$str);
        $str = str_replace("_","-",$str);
        $str = str_replace(" ","-",$str);
        $str = str_replace( "ß", "ss", $str);
        $str = str_replace( "&", "", $str);
        $str = str_replace( "%", "percent", $str);
        $str = str_replace("----","-",$str);
        $str = str_replace("---","-",$str);
        $str = str_replace("--","-",$str);
        $str = str_replace(".","-",$str);
        $str = str_replace(",","",$str);
// In đậm
     $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
     $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
     $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
     $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
     $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
     $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
     $str = preg_replace("/(Đ)/", 'D', $str);
     return $str; // Trả về chuỗi đã chuyển
     }

}