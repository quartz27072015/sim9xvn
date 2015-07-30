<?php
class user extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');

	}
	public function index()
	{
		//In time, just click Search | Add | Delete all
		//So Split if then condition other
		if (isset($_POST['delselect'])){//echo 'fdsf';die;
			if (isset($_POST['checkbox'])){
				$checkbox=$_POST['checkbox'];
				for($i=0;$i<sizeof($checkbox);$i++ ){
					$this->user_model->del('user','id',$checkbox[$i]);
				}
				redirect(base_url().'admin/user', 'location');
			} else redirect(base_url().'admin/user', 'location');
		} else{
			$data['users'] = $this->user_model->get('*');
			$this->load->view('admin/user/manage_users.phtml',$data);
		}
		
		//Search
		if (isset($_POST['text'])){
			$text = $_POST['text'];
			$arrSearch = array(
					'username' => $text,
					'rules' => $text,

			);
			$data['agency'] = $this->agency_model->get('*','agency_profile','',$arrSearch);
			$this->load->view('admin/agency/manage-agency.phtml',$data);
		}//End of if (isset($_POST['text']))
	}
	public function add()
	{
		$original = array('donhang','congno','checksim','news','page','member','cod','sim','tools','system');
		$description = array('Đơn hàng','Công nợ','Check sim','Tin tức','Trang','Thành viên','Quản lý cod','SIM','Tiện ích','Hệ thống');


		if (isset($_POST['submit'])){ 
			if((isset($_POST['username']) && $_POST['username'] != null) && 
					(isset($_POST['password']) && $_POST['password']!=null)&& 
					(isset($_POST['confirm_password']) && $_POST['confirm_password'] != null)){
				// Dùng hàm addslashes() để tránh SQL injection, dùng hàm md5() để mã hóa password;
				$username = addslashes( $_POST['username'] );
				$password = md5( addslashes( $_POST['password'] ) );
				$verify_password = md5( addslashes( $_POST['confirm_password'] ) );
				if ( $password != $verify_password ){
					print "<p align='center' style='color: red'>Mật khẩu không giống nhau, bạn hãy nhập lại mật khẩu. <a href='add'>Nhấp vào đây để quay trở lại</p>";
				} else {
					$rules ='';
					$rules_description = '';
					if (isset($_POST['check'])){
						for ($i=0;$i<sizeof($_POST['check']);$i++){
							for ($j=0;$j<sizeof($original);$j++){
								if ($original[$j] == $_POST['check'][$i]){
									$rules .= $original[$j].',';
									$rules_description .= $description[$j].' | ';
								}
							}
							
						}
					}//ENd of if (isset($_POST['check']))
						//var_dump($rules);var_dump($rules_description);die;
						$dataarray = array(
								'username' => $username,
								'password' => $password,
								'rules' => $rules,
								'rules_description' => $rules_description
						);
						$this->user_model->insert('user',array($dataarray));
					
						//After Success redirect admin import sim
						redirect(base_url().'admin/user', 'location');
						print "Tài khoản {$username} đã được tạo. <a href='login.php'>Nhấp vào đây để đăng nhập</a>";
					
				}///End of if ( $password != $verify_password )
			} else{//echo 'else';die;
				print "<p align='center' style='color: red'>Xin vui lòng nhập đầy đủ các thông tin. <a href='add'>Nhấp vào đây để quay trở lại</a></p>";				
			}//End of if(!isset($_POST['username']) && !isset($_POST['password']) && !isset($_POST['confirm_password']))
		} else {//End of if (isset($_POST['submit']))
			$this->load->view('admin/user/add_user.phtml');
				
		}
	}
	public function edit($num)
	{
		$original = array('donhang','congno','checksim','news','page','member','cod','sim','tools','system');
		$description = array('Đơn hàng','Công nợ','Check sim','Tin tức','Trang','Thành viên','Quản lý cod','SIM','Tiện ích','Hệ thống');
		
		if (isset($_POST['submit'])){
			$username = '';
			$password = '';
			$verify_password = '';
			// Dùng hàm addslashes() để tránh SQL injection, dùng hàm md5() để mã hóa password;
			if (isset($_POST['username']) && $_POST['username'] != null){
				$username = addslashes( $_POST['username'] );
			}
			if (isset($_POST['password']) && $_POST['password']!=null){
				$password = md5( addslashes( $_POST['password'] ) );
			}				
			if (isset($_POST['confirm_password']) && $_POST['confirm_password'] != null){
				$verify_password = md5( addslashes( $_POST['confirm_password'] ) );
			}
			$rules ='';
			$rules_description = '';
			if (isset($_POST['check'])){
				for ($i=0;$i<sizeof($_POST['check']);$i++){
					for ($j=0;$j<sizeof($original);$j++){
						if ($original[$j] == $_POST['check'][$i]){
							$rules .= $original[$j].',';
							$rules_description .= $description[$j].' | ';
						}
					}
						
				}
			}//ENd of if (isset($_POST['check']))
			if ($username == ''){
				if ($password != ''){
					$dataEdit = array(
							'password' => $password
					);
					$this->user_model->update('user',$dataEdit,'id',$num);
					//After Success redirect admin import sim
					redirect(base_url().'admin/user', 'location');
				} else {
					print "<p align='center' style='color: red'>Chưa nhập mật khẩu. <a href=''>Nhấp vào đây để quay trở lại</p>";
				}
			} else {
				//Ko can kt co mat khau hay ko					
				if ( $password != $verify_password ){
					print "<p align='center' style='color: red'>Mật khẩu không giống nhau, bạn hãy nhập lại mật khẩu. <a href=''>Nhấp vào đây để quay trở lại</p>";
				} else {
				
						if ($password != ''){
							$dataEdit = array(
									'username' => $username,
									'password' => $password,
									'rules' => $rules,
									'rules_description' => $rules_description
							);
							$this->user_model->update('user',$dataEdit,'id',$num);
							//After Success redirect admin import sim
							redirect(base_url().'admin/user', 'location');
						} else {
							$dataEdit = array(
									'username' => $username,
									'rules' => $rules,
									'rules_description' => $rules_description
							);
							$this->user_model->update('user',$dataEdit,'id',$num);
							//After Success redirect admin import sim
							redirect(base_url().'admin/user', 'location');					}
				}//End of if ( $password != $verify_password )
			}
				
		} else {
			$data['user'] = $this->user_model->get('*','user','id',$num);//var_dump($data);die;
			$this->load->view('admin/user/edit_user.phtml',$data);
		}//End of 
	}
	public function del($num = '')
	{
		
		if ($num != ''){
			$this->user_model->del('user','id',$num);
			redirect(base_url().'admin/user', 'location');
		}
	}
}
	?>