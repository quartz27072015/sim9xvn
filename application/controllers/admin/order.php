<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('order_model');
		$this->load->model('sim_model');
	}
	public function Index($page='')
	{
		//echo 'fdsgsdg';die;
		$config['base_url'] = base_url().'admin/order/page/';//.$queryStrings;
			
		$config['per_page'] = 10;
		$config['total_rows'] = 100;
		$config['uri_segment'] = 3;
			
		$config['enable_query_strings'] = TRUE;
		
		$config['page_query_string'] = false;
		//$config['use_page_numbers'] = TRUE;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}//var_dump($page);die;
		$config['cur_page'] = $page;
		$config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].'">First</a>';
		$last = round($config['total_rows']/$config['per_page']);
		$config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$last.'">Last</a>';
		$prev = $page-$config['per_page'];
		$config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$prev.'">Prev</a>';
		
		$next = $page+$config['per_page'];
		$search ='search';
		//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
		$config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$next.'">Next</a>';
			
		$config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
		$config['cur_tag_close']    = '</label></span>';
		$config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
		$config['num_tag_close']     = '</span>';
		$config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
		$config['full_tag_close']     = '</p>'; ;
		$config['num_links']=$last;
		$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
		// var_dump($data['sim']);die;
		//var_dump($config['cur_page']);die;
		$data['pagination'] = $this->pagination->create_links();
		$data['order_detail'] = $this->order_model->get('*','order_detail');
		$date = '';
		if (isset($_POST['search'])){
			if (isset($_POST['day']) && $_POST['day'] != ''){
				$date = '-'.$_POST['day'];
				if (isset($_POST['month']) && $_POST['month'] != ''){
					$date = '-'.$_POST['month'].'-'.$_POST['day'];
					if(isset($_POST['year']) && $_POST['year'] != ''){
						$date = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
					}
				}
			} else {
				if (isset($_POST['month']) && $_POST['month'] != ''){
					$date = '-'.$_POST['month'];
					if(isset($_POST['year']) && $_POST['year'] != ''){
						$date = $_POST['year'].'-'.$_POST['month'];
					}
				} else {
					if(isset($_POST['year']) && $_POST['year'] != ''){
						$date = $_POST['year'];
					}
				}
			}
			
			$dataSearch = array($_POST['fullname'],$_POST['number'],$date,$_POST['address']);
			$data['order'] = $this->order_model->list_all($config['per_page'],$page,$dataSearch);//var_dump($data['order']);die;
			$this->load->view('admin/order/index.phtml',$data);
		} elseif(isset($_POST['delcheck'])){ 
			$this->order_model->del('orderid',$_POST['ordercheck']);
			//$data['totalorder'] = sizeof($data['order']);
			//$data['simcheck'] = $_POST['simcheck'];
			$data['order_detail'] = $this->order_model->get('*','order_detail');
			$data['order'] = $this->order_model->list_all($config['per_page'],$page,'status','processing');
			$this->load->view('admin/order/index.phtml',$data);		
		} else {

			//var_dump($data['order']);die;
			//var_dump(sizeof($data['order']));die;
			//$data['totalsim'] = sizeof($data['sim']);
			$data['order_detail'] = $this->order_model->get('*','order_detail');
			$data['order'] = $this->order_model->list_all($config['per_page'],$page,'status','processing');
			$this->load->view('admin/order/index.phtml',$data);
		}
	}
	public function del($num)
	{
		if (isset($num)){
			if ($this->order_model->del('orderid',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin', 'location');
		}
	}
	public function listdel($page = '')
	{
			$config['base_url'] = base_url().'admin/order/listdel/page/';//.$queryStrings;
			
		$config['per_page'] = 10;
		$config['total_rows'] = 100;
		$config['uri_segment'] = 3;
			
		$config['enable_query_strings'] = TRUE;
		
		$config['page_query_string'] = false;
		//$config['use_page_numbers'] = TRUE;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}//var_dump($page);die;
		$config['cur_page'] = $page;
		$config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].'">First</a>';
		$last = round($config['total_rows']/$config['per_page']);
		$config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$last.'">Last</a>';
		$prev = $page-$config['per_page'];
		$config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$prev.'">Prev</a>';
		
		$next = $page+$config['per_page'];
		$search ='search';
		//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
		$config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$next.'">Next</a>';
			
		$config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
		$config['cur_tag_close']    = '</label></span>';
		$config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
		$config['num_tag_close']     = '</span>';
		$config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
		$config['full_tag_close']     = '</p>'; 
		$config['num_links']=$last;
		$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
		// var_dump($data['sim']);die;
		//var_dump($config['cur_page']);die;
		$data['pagination'] = $this->pagination->create_links();
		$data['order_detail'] = $this->order_model->get('*','order_detail');
		$data['order'] = $this->order_model->list_all($config['per_page'],$page,'status','delete');


			//var_dump($data['order']);die;
			//var_dump(sizeof($data['order']));die;
			//$data['totalsim'] = sizeof($data['sim']);

			$this->load->view('admin/order/listdel.phtml',$data);
	}

}
?>