<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagecontent extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('news_model');
		$this->load->model('order_model');
		$this->load->model('pagecontent_model');
	}
	public function Index($page='')
	{
		$data['news'] = $this->news_model->get('*');
		//Load data of order
		$data['order'] = $this->order_model->get('fullname,phone,datetime','','','','','',15);
		$data['url'] = $page;
		$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
		$this->load->view('frontend/pagecontent/general.phtml',$data);
	}
}
?>