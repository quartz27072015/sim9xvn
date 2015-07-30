<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function Index($page='')
	{
		if($page=='ho-tro') $this->load->view('frontend/p/ho-tro.phtml');
		if($page=='hinh-thuc-thanh-toan') $this->load->view('frontend/p/hinh-thuc-thanh-toan.phtml');
		if($page=='lien-he') $this->load->view('frontend/p/lien-he.phtml');
		if($page=='bao-mat-thong-tin') $this->load->view('frontend/p/bao-mat-thong-tin.phtml');
		if($page=='cam-ket-ban-hang') $this->load->view('frontend/p/cam-ket-ban-hang.phtml');
		if($page=='dieu-khoan-va-dieu-kien') $this->load->view('frontend/p/dieu-khoan-va-dieu-kien.phtml');
		if($page=='hinh-thuc-thanh-toan') $this->load->view('frontend/p/hinh-thuc-thanh-toan.phtml');
		if($page=='huong-dan-mua-sim') $this->load->view('frontend/p/huong-dan-mua-sim.phtml');
		if($page=='dang-ky-thong-tin') $this->load->view('frontend/p/dang-ky-thong-tin.phtml');
		if($page=='dia-chi-van-phong') $this->load->view('frontend/p/dia-chi-van-phong.phtml');
		if($page=='luan-phong-thuy-sim') $this->load->view('frontend/p/luan-phong-thuy-sim.phtml');
		if($page=='y-nghia-cac-con-so-trong-sim-dien-thoai') $this->load->view('frontend/p/y-nghia-cac-con-so-trong-sim-dien-thoai.phtml');
		
	}
}
?>