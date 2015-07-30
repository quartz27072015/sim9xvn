<?php
class agency extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('agency_model');

	}
	public function index()
	{
		//In time, just click Search | Add | Delete all
		//So Split if then condition other
		if (isset($_POST['delselect'])){//echo 'fdsf';die;
			if (isset($_POST['checkbox'])){
				$checkbox=$_POST['checkbox'];
				for($i=0;$i<sizeof($checkbox);$i++ ){
					$this->agency_model->del(array('agency_profile','agency_percent'),'profileid',$checkbox[$i]);
				}
				redirect(base_url().'admin/agency', 'location');
			} else redirect(base_url().'admin/agency', 'location');
		} else{
			$data['agency'] = $this->agency_model->get('*','agency_profile');//var_dump($data['agency']);die;
			if ($data['agency'] == null){
				$this->agency_model->insert('agency_profile',array(array('agencyname' => 'Kho')));
				redirect(base_url().'admin/agency', 'location');
			}
			$data['agencypercent'] = $this->agency_model->get('*','agency_percent');//var_dump($dataget);die;
			//var_dump($data['agency']);die;
			//If haven't exist any agency then insert record default "kho nha" 

			$this->load->view('admin/agency/manage-agency.phtml',$data);
		}
		
		//Search
		if (isset($_POST['text'])){
			$text = $_POST['text'];
			$arrSearch = array(
					'agencyname' => $text,
					'agencyemail' => $text,
					'agencyaddress' => $text,
					'agencymobile' => $text,
					'agencywebsite' => $text,
					'agencypassport' => $text
			);
			$data['agency'] = $this->agency_model->get('*','agency_profile','',$arrSearch);
			$this->load->view('admin/agency/manage-agency.phtml',$data);
		}//End of if (isset($_POST['text']))
	}
	public function add()
	{
		$name = '';
		$email = '';
		$address = '';
		$mobile = '';
		$passport = '';
		$website = '';
		if(!isset($_POST['name'])){
			$this->load->view('admin/agency/add-agency.phtml');
		} else{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$mobile =  $_POST['mobile'];
			$passport = $_POST['passport'];
			$website = $_POST['website'];
			$dataarray = array(
					'agencyname' => $name,
					'agencyemail' => $email,
					'agencyaddress' => $address,
					'agencypassport' => intval($passport),
					'agencymobile' => $mobile,
					'agencywebsite' => $website
			);//print_r(array_keys(current($dataarray)));die;//$curr = current($dataarray);var_dump(array_keys($curr));die;
			$this->agency_model->insert('agency_profile',array($dataarray));

			//After Success redirect admin import sim
			redirect(base_url().'admin/agency', 'location');
		}
	}
	public function percentagency()
	{
		//Any Exist checkbox will del
		if (isset($_POST['checkbox'])){
			$checkbox=$_POST['checkbox'];
			//var_dump($checkbox);die;
			for($i=0;$i<sizeof($checkbox);$i++ ){
				$this->agency_model->del('agency_percent','percentid',$checkbox[$i]);				
			}
			redirect(base_url().'admin/agency/percent-agency', 'location');
		}else {
			//View page will load data	
			//If have no agency select, default load with agency first 	
			$data['agency'] = $this->agency_model->get('profileid,agencyname','agency_profile');//echo $data['agency'][0]->profileid;
			if(!isset($_POST['agency'])){
				$data['agencypercent'] = $this->agency_model->get('*','agency_percent','','profileid',$data['agency'][0]->profileid);//echo sizeof($data['agencypercent']);
				$data['selected'] = null;
				 	$this->load->view('admin/agency/percent-agency.phtml',$data);
			}else{
				$data['agencypercent'] = $this->agency_model->get('*','agency_percent','','profileid',$_POST['agency']);//var_dump($data);die;
				//var_dump($data['agencypercent']);die;
				$data['selected'] = $_POST['agency'];
				$this->load->view('admin/agency/percent-agency.phtml',$data);
				
				
			}
				/*Have post will insert or update
				 Must have three number: lower, upper and percent, default is enabled
				If have percentid, that's mean have all of row record,
				Incase, has exception with line has no percentid,
				so must to be update with that's percentid and insert new data
				if not insert into database with rows what have no percentid
				*/
				if(isset($_POST['lower']) || isset($_POST['upper']) || isset($_POST['percent'])){
					if($_POST['percentid'] != null){
						//Update record hava exist
						for ($i=0;$i<sizeof($_POST['percentid']);$i++){//echo $_POST['percentid'][$i];
							if($_POST['percentid'][$i] != null){//echo 'vao'.$_POST['lower'][$i].$_POST['upper'][$i].$_POST['percent'][$i];
								//echo $_POST['percentid'][$i];
								//$dataarray = null;
								$dataarray[] = array(
										'lower' => $_POST['lower'][$i]*1000,
										'upper' => $_POST['upper'][$i]*1000,
										'percent' => $_POST['percent'][$i],
								);
								$this->agency_model->update('agency_percent',$dataarray[$i],'percentid',$_POST['percentid'][$i]);
							} else{ //echo 'insert';
								$dataarray = null;//var_dump($dataarray);
								//Insert new data
								$dataarray[] = array(
										'lower' => $_POST['lower'][$i]*1000,
										'upper' => $_POST['upper'][$i]*1000,
										'percent' => $_POST['percent'][$i],
										'profileid' => $_POST['profileid']
								);
								$this->agency_model->insert('agency_percent',$dataarray);
							}
						}//End of for
					}else{
						for ($i=0;$i<sizeof($_POST['lower']);$i++){
							$dataarray[] = array(
									'lower' => $_POST['lower'][$i]*1000,
									'upper' => $_POST['upper'][$i]*1000,
									'percent' => $_POST['percent'][$i],
									'profileid' => $_POST['agency']
							);
							$this->agency_model->insert('agency_percent',$dataarray);
						}
					}
					//After Success redirect admin import sim
					redirect(base_url().'admin/agency/percent-agency', 'location');
				}
		}		
	}
	public function delpercentagency($num)
	{
		if (isset($num)){
			if ($this->agency_model->del('agency_percent','percentid',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/agency/percent-agency', 'location');
		} else{
			echo 'Báº¡n cÃ³ cháº¯c Ä‘Ã£ chá»�n Ä‘á»¯ liá»‡u xÃ³a??';
		}
	}
	public function editprofile($num)
	{

			$data['agencyprofile'] = $this->agency_model->get('*','agency_profile','','profileid',$num);
			$this->load->view('admin/agency/add-agency.phtml',$data);
			if (isset($_POST['name'])){
					$name = $_POST['name'];
					$email = $_POST['email'];
					$address = $_POST['address'];
					$mobile =  $_POST['mobile'];
					$passport = $_POST['passport'];
					$website = $_POST['website'];
					
					$dataEdit = array(
						'agencyname' => $name,
						'agencyemail' => $email,
						'agencyaddress' => $address,
						'agencypassport' => $passport,
						'agencymobile' => $mobile,
						'agencywebsite' => $website
			); //var_dump($dataarray);die;
				if ($this->agency_model->update('agency_profile',$dataEdit,'profileid',$num))
				{
					$this->session->set_flashdata('sessionSuccessEdit', 1);
				}
				redirect(base_url().'admin/agency', 'location');
			} else{
				//$this->load->view('admin/news/add-news.phtml',$data);				
			}		
	}
	public function del($num)
	{
		if (isset($num)){
			$this->agency_model->del(array('agency_profile','agency_percent'),'profileid',$num);
			redirect(base_url().'admin/agency', 'location');
		} 
	}
}