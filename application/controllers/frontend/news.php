<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
               $this->load->model('news_model');
               $this->load->model('order_model');
               $this->load->model('pagecontent_model');
        }
        public function Index($idtitle)
        {
        	//Load data of order
        	$data['order'] = $this->order_model->get('fullname,phone,datetime','','','','','',15);
        	$data['news'] = $this->news_model->get('*');
                $data['news'] = $this->order_model->get('id', 'desc');
			/**
			@author: chieuvd
			@date: 27-07-2015
			Change to get by urlfriendly
			*/
        	$data['newsonly'] = $this->news_model->get('*','','','urlfriendly',$idtitle);
        	$data['pagecontent'] = $this->pagecontent_model->get('*','pagecontent');
        	$this->load->view('frontend/news/index.phtml',$data);
        }
}