<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
               $this->load->model('index_model');
               $this->load->model('sim_model');
               $this->load->model('news_model');
               $this->load->model('order_model');
               $this->load->model('pagecontent_model');
        }
		
        public function Index()
        {
        	
        	$this->load->view('frontend/index.phtml',$data);
        		
        }
     
        public function head($provider = '',$head = '',$kind = '',$page = '')
        {
        	//var_dump(intval($kind));die;
        	//Only head
              if((is_int(intval($kind)) && intval($kind) != 0) || $kind == ''){
              	$page = $kind;
              	$kind = '';
              	$config['base_url'] = base_url().$provider.'/dau-so-'.$head.'/';
              	
              	$whereArr = array($provider,$head);
              	$config['total_rows'] = $this->index_model->countproviderheadkind($whereArr);
              	
              } else {
              	$kind = 'sim-'.$kind;
              	$data['flag_kind'] = $this->index_model->get('fullname','kinds','','name',$kind);
              	$whereArr = array($provider,$head,$kind);
              	$config['total_rows'] = $this->index_model->countproviderheadkind($whereArr);
              	$config['base_url'] = base_url().$provider.'/dau-so-'.$head.'/'.$kind.'/';
              }
              $data['flag_provider'] = $provider;
              $data['flag_head'] = $head;
             
             // var_dump($page);die;
              $config['enable_query_strings'] = true;
              $config['per_page'] = 80;
              $config['uri_segment'] = 3;
              $config['cur_page'] = $page;
              $config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.base_url().'">Đầu tiên</a>';
              $config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].'/'.$config['total_rows'].'">Cuối cùng</a>';
              $prev = $page-$config['per_page'];
              $config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$prev.'">Trước</a>';
              $next = $page+$config['per_page'];
              $config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$next.'">Sau</a>';
              $config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
              $config['cur_tag_close']    = '</label></span>';
              $config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
              $config['num_tag_close']     = '</span>';
              $config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
              $config['full_tag_close']     = '</p>'; ;
              $config['num_links']=3;
              $this->pagination->initialize($config);
              $data['sim'] = $this->index_model->list_all($config['per_page'],$page,'',$provider,$kind,$head);//var_dump(sizeof($data['sim']));die;
              $data['pagination'] = $this->pagination->create_links();
               
              $data['providers'] = $this->index_model->get('*','providers');
              $data['kinds'] = $this->index_model->get('*','kinds');
              $data['head'] = $this->index_model->get('*','providers_head');
              $selected = null;
              if (isset($_GET['provider'])){
              	$selected = $_GET['provider'];
              	 
              }
              $data['selected'] = $selected; //var_dump($data['selected']);die;
              //Load data of news
              $data['news'] = $this->news_model->get('*');
              //Load data of order
              $data['order'] = $this->order_model->get('fullname,phone,datetime','','','','','',15);
              $data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
              $data['flag'] = 'head';
              //var_dump($data);die;
              $this->load->view('frontend/index.phtml',$data);
        }
        
        public function filter($provider = '',$kind = '',$page = '')
        {//echo $provider.'-'.$kind.'-'.$page;die;
		
        	//Homepage
	        //Config base_url in 	
	        if ($provider == '' && $kind == '' && $page == '')
	        	$config['base_url'] = base_url().'page/';
	        	$config['total_rows'] = $this->sim_model->countresults();
	        //Config base_url if begin pagination in , set number page follow by first variable
	        if ($provider != '' && $kind == '' && $page == ''){
	        	$page = $provider;
	        	$provider = '';
	        	$config['base_url'] = base_url().'page/';
	        	$config['total_rows'] = $this->sim_model->countresults();
	        }
	        
	        //Main menu: 
	        //Config base_url
	        if ($provider != '' && $kind != ''){
	        	$data['flag_kind'] = $this->index_model->get('fullname','kinds','','name',$kind);
	        	$config['base_url'] = base_url().$provider.'/'.$kind.'.html/page/';
	        	$whereArr = array( $provider,$kind );
	        	$config['total_rows'] = $this->index_model->countproviderkind($whereArr);//var_dump($config['total_rows']);die;var_dump(sizeof($config['total_rows']));die;
	        }
	        $data['flag_provider'] = $provider;
				
	        
        	$config['enable_query_strings'] = true;        	
        	$config['per_page'] = 80;
        	$config['uri_segment'] = 3;
        	$config['cur_page'] = $page;
        	$config['first_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.base_url().'">Đầu tiên</a>';
        	$config['last_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].'/'.$config['total_rows'].'">Cuối cùng</a>';
        	$prev = $page-$config['per_page'];
        	$config['prev_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;" href="'.$config['base_url'].$prev.'">Trước</a>';        	
        	$next = $page+$config['per_page'];
        	$config['next_link'] = '<a style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin:0 -1px 0 -4px;" href="'.$config['base_url'].$next.'">Sau</a>';        	 
        	$config['cur_tag_open']         = '<span id="current_page" style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;"><label>';
        	$config['cur_tag_close']    = '</label></span>';
        	$config['num_tag_open']     = '<span style="border: 1px solid #ddd;padding: 5px 10px 5px 10px;margin-right: -1px;">';
        	$config['num_tag_close']     = '</span>';
        	$config['full_tag_open']     = '<p id="pager_links" style="float: right;width: 100%;">';
        	$config['full_tag_close']     = '</p>'; ;
        	$config['num_links']=3;
        	$this->pagination->initialize($config);
          	$data['sim'] = $this->index_model->list_all($config['per_page'],$page,'',$provider,$kind);//var_dump(sizeof($data['sim']));die;
        	$data['pagination'] = $this->pagination->create_links();
        	
        	$data['providers'] = $this->index_model->get('*','providers');
        	$data['kinds'] = $this->index_model->get('*','kinds');
        	$data['head'] = $this->index_model->get('*','providers_head');
        	$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
        	$selected = null;
        	if (isset($_GET['provider'])){
        		$selected = $_GET['provider'];
        	
        	}
        	$data['selected'] = $selected; //var_dump($data['selected']);die;
        	//Load data of news
        	$data['news'] = $this->news_model->get('*');
        	//Load data of order
        	$data['order'] = $this->order_model->get('fullname,phone,datetime','','','','','',15);
        	$data['flag'] = 'filter';
        	//var_dump($data);die;
        	$this->load->view('frontend/index.phtml',$data);
        }
        
}