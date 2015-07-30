<?php
class login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}
	public function index()
	{
		if (isset($_POST['submit']) && $_POST['submit'] != null){
			$username = '';
			$password = '';
			if ($_POST['username'] != null && $_POST['password'] != null){
				// Dùng hàm addslashes() để tránh SQL injection, dùng hàm md5() để mã hóa password
				$username = addslashes( $_POST['username'] );
				$password = md5( addslashes( $_POST['password'] ) );
				$result = $this->user_model->checklogin($username, $password);//var_dump($result);
				if ($result != null){
					//Khởi động phiên làm việc (session)
					@session_start();
					$_SESSION['user_id'] = $result[0]->id;
					// Thông báo đăng nhập thành công
					print "Login successfull!!!. User: {$result[0]->username}. <a href='admin/order'>Click here to go into admin page</a>";
				} else {
					print "Password wrong!. <a href='javascript:history.go(-1)'>Click here to come back</a>";						
				}
			} else {
				print "Miss value <a href='javascript:history.go(-1)'>Click here to come back</a>";				
			}						
		} else{
			$this->load->view('admin/login.phtml');
		}
	} 
}