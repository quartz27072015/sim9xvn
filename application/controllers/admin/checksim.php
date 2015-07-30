<?php
class checksim extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sim_model');
		$this->load->model('agency_model');

	}
	public function Index()
	{
		if (isset($_POST['simcheck'])){
			//Filter get only number
			$sim_standard = preg_replace('/[^0-9]/','',$_POST['simcheck']);
			$data['simcheck'] = $this->sim_model->checksim('sim_original',$sim_standard);
			//var_dump($data['simcheck']);die;
			$this->load->view('admin/checksim.phtml',$data);
		} else {
			$this->load->view('admin/checksim.phtml');
		}
		
	}
}
?>