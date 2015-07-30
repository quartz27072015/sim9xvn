<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
               $this->load->model('index_model');
               $this->load->model('sim_model');
               $this->load->model('news_model');
               $this->load->model('order_model');
               $this->load->model('pagecontent_model');
        }
        public function index()
        {//var_dump($page);die;
        	//echo $provider.'-'.$price_lower.'-'.$price_upper.'-'.$sim.'-'.$n.'-'.$totalpoint.'-'.$nut.'-'.
        	//$number11.'-'.$number10;
        	
        	//Init variables
        	$page = '';
        	$provider = '';
        	$price_lower = '';
        	$price_upper = '';
        	$sim = '';
        	$n = '';
        	$totalpoint = '';
        	$nut = '';
        	$number10 ='';
        	$number11 = '';
        	if (isset($_GET['sim'])){
        		$sim = $_GET['sim'];
        	}
        	//Load data general
        	//var_dump(sizeof($data['sim']));die;
        	$data['providers'] = $this->index_model->get('*','providers');
        	$data['kinds'] = $this->index_model->get('*','kinds');
        	$data['head'] = $this->index_model->get('*','providers_head');
        	$data['news'] = $this->news_model->get('*');        	         
        	$data['order'] = $this->order_model->get('fullname,phone,datetime','','','','','',15);
        	$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
        	
        	//Config general
        	$config['per_page'] = 80;
        	$config['base_url'] = base_url().'search?';//.$queryStrings;
        	$config['uri_segment'] = 3;
        	$config['enable_query_strings'] = TRUE;//Append "&" to begin
        	$config['page_query_string'] = TRUE;
        	//$config['use_page_numbers'] = TRUE;
        	if (isset($_GET['page'])){
        		$page = $_GET['page'];
        		$config['cur_page'] = $page;
        		 
        	}//var_dump($page);die;
        	$prev = $page-$config['per_page'];
        	$next = $page+$config['per_page'];
        	
        	//Filter by input sim and by select
        	if (strpos($sim,'*') > -1 || strpos($sim,'x') > -1){
        		//Caculator before generate links
        		$data['total'] = $this->index_model->filterspecial('','',$sim);//var_dump($data['sim']);die;        		
        		$config['total_rows'] = sizeof($data['total']);
        		$config['query_string_segment'] = 'sim='.$sim.'&page';
        		//Generator links
        		$config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'">Đầu tiên</a>';
        		$config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$config['total_rows'].'">Cuối cùng</a>';
        		
        		$config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$prev.'">Trước</a>';
        		 
        		
        		//$search ='search';
        		//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
        		$config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$next.'">Sau</a>';
        		
        		$config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
        		$config['cur_tag_close']    = '</label></span>';
        		$config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
        		$config['num_tag_close']     = '</span>';
        		$config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
        		$config['full_tag_close']     = '</p>'; ;
        		$config['num_links']=3;
        		$data['sim'] = $this->index_model->filterspecial($config['per_page'],$page,$sim);
        		$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
        		
        		$data['pagination'] = $this->pagination->create_links();
        		//Load data of order
        		$this->load->view('frontend/index.phtml',$data);
        	} else {
	        	if (isset($_GET['provider'])){
	        		$provider = $_GET['provider'];
	        		$data['selected'] = $provider;
	        	} else $data['selected'] = ''; 
	        	if (isset($_GET['price_lower'])){
	        		$price_lower = intval(str_replace(',','',$_GET['price_lower']));//var_dump($price_lower);die;
	        	}
	        	if (isset($_GET['price_upper'])){
	        		$price_upper = intval(str_replace(',','',$_GET['price_upper']));
	        	}
	        	
	        	if (isset($_GET['n'])){
	        		$n[] = $_GET['n'];
	        	}
	        	//var_dump($n);die;
	        	//var_dump($n);die;
	        	if (isset($_GET['totalpoint'])){
	        	$totalpoint = $_GET['totalpoint'];
	        	}
	        	if (isset($_GET['nut'])){
	        	$nut = $_GET['nut'];
	        	}
	        	if (isset($_GET['number11'])){
	        	$number11 = $_GET['number11'];
	        	}
	        	if (isset($_GET['number10'])){
	        	$number10 = $_GET['number10'];
	        	}
	       
	        	//After return result
	        	if($n == null){
	        		//Set variable page at this point to get result of page variable
	        		$config['query_string_segment'] = 'provider='.$provider.'&price_lower='.$price_lower.'&price_upper='.$price_upper.'&sim='.$sim.
	        	'&n[]=&totalpoint='.$totalpoint.'&nut='.$nut.'&number11='.$number11.'&number10='.$number10.'&page';
	        	}else {
	        		$points = '';
	        		$before = 'provider='.$provider.'&price_lower='.$price_lower.'&price_upper='.$price_upper.'&sim='.$sim;
	        		$after = "&totalpoint=".$totalpoint.'&nut='.$nut.'&number11='.$number11.'&number10='.$number10.'&page';
	        	
	        		for ($i=0;$i<count($n[0]);$i++){
						$points .= '&n[]='.$n[0][$i];        	
	        		}
	        		//echo $points; die;
	        		$config['query_string_segment'] = $before.$points.$after;
	        	}

	        	$prev = $page-$config['per_page'];
	        	$next = $page+$config['per_page'];
	        	//Caculator before generate links
	        	$data['total'] = $this->index_model->search('','',$provider,$price_lower,$price_upper,$sim,$n,$totalpoint,$nut,$number11,$number10);
	        	//var_dump(sizeof($data['total']));die;
	        	$config['total_rows'] = sizeof($data['total']);
	        	//Generator links
	        	$config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'">Đầu tiên</a>';
	        	$config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$config['total_rows'].'">Cuối cùng</a>';
	        	
	        	$config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$prev.'">Trước</a>';
	        	 
	        	
	        	//$search ='search';
	        	//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
	        	$config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$config['query_string_segment'].'='.$next.'">Sau</a>';
	        	
	        	$config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
	        	$config['cur_tag_close']    = '</label></span>';
	        	$config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
	        	$config['num_tag_close']     = '</span>';
	        	$config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
	        	$config['full_tag_close']     = '</p>'; ;
	        	$config['num_links']=3;
	        	$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
	        	// var_dump($data['sim']);die;
	        	$data['sim'] = $this->index_model->search($config['per_page'],$page,$provider,$price_lower,$price_upper,$sim,$n,$totalpoint,$nut,$number11,$number10);
	        	//var_dump($config['per_page']);die;
	        	$data['pagination'] = $this->pagination->create_links();
				$data['flag_search'] = 'search';
				$data['f_s_arr'] = array($provider,$price_lower,$price_upper,$sim,$n,$totalpoint,$nut,$number11,$number10);
	        	$this->load->view('frontend/index.phtml',$data);
        	}//End of if (strpos($sim,'*') > -1 || strpos($sim,'x') > -1)
        }
        public function filterdata($data)
        {
        	$data = trim($data);
        	$data = stripslashes($data);
        	$data = htmlspecialchars($data);
        	return $data;
        }
}
        ?>