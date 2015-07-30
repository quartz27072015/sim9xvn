<?php
class pagecontent extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pagecontent_model');
	}
	public function index()
	{
		if (isset($_POST['delselect'])){//echo 'fdsf';die;
			$checkbox=$_POST['checkbox'];
				
			for($i=0;$i<sizeof($checkbox);$i++ ){
				$this->pagecontent_model->del('pagecontentid',$checkbox[$i]);
			}
			redirect(base_url().'admin/pagecontent', 'location');
		} else{
			$data['pagecontent']=$this->pagecontent_model->get('*','pagecontent');
			$this->load->view('admin/pagecontent/manage-pagecontent.phtml',$data);
		}
		
		
	}
	public function add()
	{
		if(!isset($_POST['title'])){
			$this->load->view('admin/pagecontent/add-pagecontent.phtml');
		} else{
			$titlefriendly = $this->create_slug($_POST['title']);
			$dataAdd = array(
					'title' => $_POST['title'],
					'content' => $_POST['content'],
					'location' => $_POST['location'],
					'urlfriendly' => $titlefriendly
			);
			if ($this->pagecontent_model->insert($dataAdd))
			{
				$this->session->set_flashdata('sessionSuccessAdd', 1);
			}
			//After Success redirect admin import sim
			redirect(base_url().'admin/pagecontent', 'location');
		}
	}
	public function create_slug($string) {
		$search = array (
				'#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
				'#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
				'#(ì|í|ị|ỉ|ĩ)#',
				'#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
				'#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
				'#(ỳ|ý|ỵ|ỷ|ỹ)#',
				'#(đ)#',
				'#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
				'#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
				'#(Ì|Í|Ị|Ỉ|Ĩ)#',
				'#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
				'#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
				'#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
				'#(Đ)#',
				"/[^a-zA-Z0-9\-\_]/",
		) ;
		$replace = array (
				'a',
				'e',
				'i',
				'o',
				'u',
				'y',
				'd',
				'A',
				'E',
				'I',
				'O',
				'U',
				'Y',
				'D',
				'-',
		) ;
		$string = preg_replace($search, $replace, $string);
		$string = preg_replace('/(-)+/', '-', $string);
		$string = strtolower($string);
		return $string;
	}
	public function edit($num)
	{
	
		$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent','','pagecontentid',$num);
		$this->load->view('admin/pagecontent/add-pagecontent.phtml',$data);
		if (isset($_POST['title'])){
			$titlefriendly = $this->create_slug($_POST['title']);
			$dataEdit =array(
					'title' => $_POST['title'],
					'content' => $_POST['content'],
					'location' => $_POST['location'],
					'urlfriendly' => $titlefriendly
			);
			if ($this->pagecontent_model->update($dataEdit,'pagecontentid',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/pagecontent', 'location');
		} else{
			//$this->load->view('admin/pagecontent/add-pagecontent.phtml',$data);
		}
	}
	public function del($num)
	{
		if (isset($num)){
			if ($this->pagecontent_model->del('pagecontentid',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/pagecontent', 'location');
		} else{
				
			redirect(base_url().'admin/pagecontent', 'location');
		}
	}
}