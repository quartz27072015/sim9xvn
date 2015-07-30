<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
               $this->load->model('order_model');
               $this->load->model('sim_model');
               $this->load->model('pagecontent_model');
        }
        public function Index()
        {
        
        /*	}else {
        		$idsim = $_POST['id_sim'];
        		foreach ($idsim as $key=>$value){
        			if ($value == $_POST['delcart']){
        				unset($idsim[$key]);
        			}
        		}
        		var_dump($idsim);die;
        	}*/
        }
        public function mycart()
        {
        	$idsim = '';
        	//Get id_sim from mycart and search in database; return result
        	if(isset($_POST['id_sim']) ){
	        	$idsim = $_POST['id_sim'];
	        	
				$data['order_sim'] = $this->sim_model->get('id_sim,sim_split,sim_original,price_sell','','','id_sim',$idsim);
			//	var_dump($data['order_sim']);die;
				$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
	        	$this->load->view('frontend/order/mycart.phtml',$data);
        	} elseif (isset($_GET['sim'])){
        		$idsim = $_GET['sim'];
        		$data['order_sim'] = $this->sim_model->get('id_sim,sim_split,sim_original,price_sell','','','id_sim',$idsim);
        		//	var_dump($data['order_sim']);die;
        		$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
        		$this->load->view('frontend/order/mycart.phtml',$data);
        	}
        }
        public function done()
        {
        	//Xoa cac phan tu null
        	$orderidsim = $_POST['order_idsim'];
        	foreach ($orderidsim as $key=>$value){
        		if ($value == '') unset($orderidsim[$key]);
        	}
        	//Thong tin chinh xac don hang
        	if (isset($_POST['fullname'])){
        		$data['fullname'] = $_POST['fullname']; 
        	} else $data['fullname'] = '';
        	
        	if (isset($_POST['phone'])){
        		$data['phone'] = $_POST['phone'];
        	}else $data['phone'] = '';
        	
        	if (isset($_POST['address'])){
        		$data['address'] = $_POST['address'];
        	}else $data['address'] = '';
        	
        	if (isset($_POST['email'])){
        		$data['email'] = $_POST['email'];
        	}else $data['email'] = '';
        	
        	if (isset($_POST['notes'])){
        		$data['notes'] = $_POST['notes'];
        	}else $data['notes'] = '';
        	//Get array sim
        	$data['order_sim'] = $this->sim_model->get('id_sim,sim_split,price_sell,agency_id','','','id_sim',$orderidsim);
        	//Build data insert order
        	$order = array(
        			'fullname' => $data['fullname'],
        			'phone' => $data['phone'],
        			'address' => $data['address'],
        			'email' => $data['email'],
        			'notes' => $data['notes'],
        			'status' => 'processing'
        	);//var_dump($data['order_sim'][0]->sim_split);die;
        	//Code an toan, lay du lieu trong CSDL
        	$simArray = array();
        	$pricesellArray = array();
        	$agencyArray = array();
        	foreach ($data['order_sim'] as $os){
	        	$simArray[] = $os->sim_split; 
	        	$pricesellArray[] = $os->price_sell;
	        	$agencyArray[] = $os->agency_id;
	        	$idsimArray[] = $os->id_sim;
        	}
        	
        
        	//var_dump($order_detail);die;
        	$this->order_model->insert_multi_tables($order,$simArray,$pricesellArray,$agencyArray,$idsimArray);
        	$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
        	$this->load->view('frontend/order/done.phtml',$data);
        }
}