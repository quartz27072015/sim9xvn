<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_detail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('order_model');
		$this->load->model('sim_model');
	}
	function getMessage(){
		$msg = parent::getMessage();
		return "ERROR - ".$msg;
	}
	public function index($num)
	{
		$data['order'] = $this->order_model->get('*','','','orderid',$num);
		$joinAgencyprofile = array('agency_profile','agencyid','profileid');
		$data['order_detail'] =  $this->order_model->get('*','order_detail',$joinAgencyprofile,'order_id',$num);//var_dump($data['order_detail']);die;
		$joinNotes= array('order_detail_notes','detailid','detailid');
		$data['order_detail_notes'] = $this->order_model->get('*','order_detail',$joinNotes,'order_id',$num);
		//var_dump(sizeof($data['order_detail']));die;
		$this->load->view('admin/order/detail.phtml',$data);
	}
	public function forward($detailid)
	{
		if (!isset($_POST['smsagency'])){
			//Cap nhat trang thai vao o status
			if (isset($_POST['smsagency_deliver']) || isset($_POST['deliver'])){
				
				$simid = $this->order_model->get('simid','order_detail','','detailid',$detailid);//var_dump($simid[0]->simid);die;
				try {
					$this->sim_model->update('sim',array('simstatus'=>'deliver'),'id_sim',$simid[0]->simid);
					$this->order_model->update('order_detail',array('od_status'=>'deliver'),'detailid',$detailid);
				}catch (Exception $e){
					$this->sim_model->update('sim',array('simstatus'=>'failure'),'id_sim',$simid[0]->simid);
					$this->order_model->update('order_detail',array('od_status'=>'failure'),'detailid',$detailid);
					$e->getMessage();
				}
			}
				
			//Mac dinh coi nhu send sms thanh cong di
			//Cap nhat trang thai vao cot smsagency
			if (isset($_POST['smsagency_sendsms'])){
				$simid = $this->order_model->get('simid','order_detail','','detailid',$detailid);//var_dump($simid[0]->simid);die;
				$dataEdit = array('smsagency'=>'enable');
				$this->sim_model->update('sim',$dataEdit,'id_sim',$simid[0]->simid);
				$this->order_model->update('order_detail',$dataEdit,'detailid',$detailid);
			}
			$data['forward'] = $this->order_model->getonly($detailid);//var_dump($data);die;
			$this->load->view('admin/order/detail_forward.phtml',$data);
		} else{
			//echo 'test';die;
			
			
			$data['last_price_agency'] = $_POST['last_price_agency'];
			$data['sendsms'] = $this->order_model->getonly($detailid);//var_dump($data);die;
			$this->load->view('admin/order/smsagency.phtml',$data);
		}
	}

	public function noteadd($orderid,$detailid)
	{
		
		if (isset($_POST['submit']) && isset($_POST['contentnote']) ){
			//var_dump(time());die;
			$dataArray = array(
					'user' => 'process later...',
					'detailid' => $detailid,
				'content' => $_POST['contentnote'],
			);//var_dump($dataArray);die;
			$this->order_model->insert('order_detail_notes',$dataArray);
			redirect(base_url().'admin/order/detail/'.$orderid, 'location');
		} 
			$data['orderid'] = $orderid;
			$data['detailid'] = $detailid;//var_dump($data);
			$this->load->view('admin/order/noteadd.phtml',$data);		
	}
	/*
	 * After deliver, but meet problem. Get out status deliver
	 * */
	public function notdeliver($orderid,$detailid) 
	{
		$dataEdit = array(
				'od_status'=>'pending'				
		);
		$this->order_model->update('order_detail',$dataEdit,'detailid',$detailid);
		redirect(base_url().'admin/order/detail/'.$orderid, 'location');
	}
	public function smsagency($detailid)
	{
		if (isset($_POST['smsagency_deliver']) || isset($_POST['deliver'])){
			$simid = $this->order_model->get('simid','order_detail','','detailid',$detailid);//var_dump($simid[0]->simid);die;
			$this->sim_model->update('sim',array('simstatus'=>'deliver'),'id_sim',$simid[0]->simid);
			$this->order_model->update('order_detail',array('od_status'=>'deliver'),'detailid',$detailid);
		}
		
		//Mac dinh coi nhu send sms thanh cong di
		//Cap nhat trang thai vao cot smsagency
		if (isset($_POST['smsagency_sendsms'])){
			$simid = $this->order_model->get('simid','order_detail','','detailid',$detailid);//var_dump($simid[0]->simid);die;
			$dataEdit = array('smsagency'=>'enable');
			$this->sim_model->update('sim',$dataEdit,'id_sim',$simid[0]->simid);
			$this->order_model->update('order_detail',$dataEdit,'detailid',$detailid);
		}
		$data['sendsms'] = $this->order_model->getonly($detailid);//var_dump($data);die;
		$this->load->view('admin/order/smsagency.phtml',$data);
	}
	public function smscustomer($orderid,$detailid)
	{
		if (isset($_POST['smscustomer_sendsms'])){
			redirect(base_url().'admin/order/detail/'.$orderid, 'location');
		} else {
		$data['check'] = $this->order_model->getonly($detailid);//var_dump($data);die;
		$this->load->view('admin/order/smscustomer.phtml',$data);
		}
	}
}