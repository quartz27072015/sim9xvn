<?php
class Admin extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
			$this->load->view('admin/index.phtml');
        }

        public function view($slug = NULL)
        {
        }
}