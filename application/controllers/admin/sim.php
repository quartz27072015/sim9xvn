<?php
class sim extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sim_model');
        $this->load->model('agency_model');
        
    }
	public function index($page = '')
	{
		$config['base_url'] = base_url().'admin/sim/page/';//.$queryStrings;
		 
		$config['per_page'] = 20;
		$config['total_rows'] = $this->sim_model->countresults();
		$config['uri_segment'] = 3;
		 
		$config['enable_query_strings'] = TRUE;

		$config['page_query_string'] = false;
		//$config['use_page_numbers'] = TRUE;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}//var_dump($page);die;
		$config['cur_page'] = $page;
		$config['first_link'] = '<a href="'.$config['base_url'].'">First</a>';
		$last = round($config['total_rows']/$config['per_page']);
		$config['last_link'] = '<a href="'.$config['base_url'].$last.'">Last</a>';
		$prev = $page-$config['per_page'];
		$config['prev_link'] = '<a href="'.$config['base_url'].$prev.'">Prev</a>';
		
		$next = $page+$config['per_page'];
		$search ='search';
		//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
		$config['next_link'] = '<a  href="'.$config['base_url'].$next.'">Next</a>';
		 
		$config['cur_tag_open']         = '<span id="current_page"><label>';
		$config['cur_tag_close']    = '</label></span>';
		$config['num_tag_open']     = '<span>';
		$config['num_tag_close']     = '</span>';
		$config['full_tag_open']     = '<p id="pager_links" style="float: right;">';
		$config['full_tag_close']     = '</p>'; ;
		$config['num_links']=6;
		$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
		// var_dump($data['sim']);die;
		//var_dump($config['cur_page']);die;
		$data['pagination'] = $this->pagination->create_links();
		if(isset($_POST['delselect'])){
			$data['sim'] = $this->sim_model->del('sim',$_POST['simcheck']);
			$data['totalsim'] = sizeof($data['sim']);
			$data['simcheck'] = $_POST['simcheck'];
			$this->load->view('admin/sim/manage-sim.phtml',$data);
				
		} else {
			$data['sim'] = $this->sim_model->list_all($config['per_page'],$page,'agency_profile','agency_id','profileid');
			//var_dump(sizeof($data['sim']));die;	
			$data['totalsim'] = sizeof($data['sim']);
			$this->load->view('admin/sim/manage-sim.phtml',$data);
		}
	}

	public function import()
	{
		#+ Cau hinh provider
		$providers['VinaPhone'] = array('091', '094', '0123','0124', '0125', '0127', '0129');
		$providers['MobiFone'] = array('090', '093', '0120', '0121', '0122', '0126', '0128');
		$providers['Viettel'] = array('096','097', '098','0161','0162','0163', '0164','0165','0166','0167','0168','0169');
		$providers['VietNammobile'] = array('092','0188');
		$providers['Sfone'] = array('095');
		$providers['Gmobile'] = array('0199','099');
		$providers['Codinh'] = array('041','042','043','044','045','046','047','048','049');


		$data = '';
		$data['sellpercent'] = $this->sim_model->get('*',"sell_percent",'',"status",'1');
		
		if(!isset($_POST['datasim']) || $_POST['datasim'] == null){
			$data['agency'] = $this->agency_model->get('profileid,agencyname','agency_profile');//echo $data['agency'][0]->profileid;
					
			if(!isset($_POST['agency'])){
				if($data['agency'] != null)
					$data['agencypercent'] = $this->agency_model->get('*','agency_percent','','profileid',$data['agency'][0]->profileid);//echo sizeof($data['agencypercent']);
				$this->load->view('admin/sim/import-sim.phtml',$data);
			}else{
				if ($_POST['agency'] != 0)
					$data['agencypercent'] = $this->agency_model->get('*','agency_percent','','profileid',$_POST['agency']);//var_dump($data);die;
				//else $data['agency'] = null;
				$this->load->view('admin/sim/import-sim.phtml',$data);
			}

		} else{//var_dump($_POST['datasim']);die;
			$data['agencypercent'] = $this->sim_model->get('lower,upper,percent','agency_percent','',array('status'=>'1','profileid' => $_POST['agency']));

			//var_dump($provider);die;
			//300s for insert 300 000 sim
	    	ini_set('max_execution_time', 300);	    
			$datasim = preg_split('/[\s]+/', $this->filterdata($_POST['datasim'])); //var_dump($datasim);die;
			$i=0;
			//Split to be process later...
			//Assign kind sim process in frontend
			//Create data to insert
			do {
				$sim_original_i = str_replace(".", "", $datasim[$i]);

				//Identify providers
				foreach ($providers as $prv=>$value){
					foreach ($value as $vl){
						if($vl==substr($sim_original_i, 0,3) || $vl==substr($sim_original_i, 0, 4)){
							$provide = $prv;
						}
					}
				} ;
				// thuáº­t toÃ¡n sá»­ lÃ½ chuá»—i sá»‘ Ä‘á»ƒ lá»�c káº¿t quáº£ trong cÆ¡ sá»Ÿ dá»¯ liá»‡u
	//			 $sub="SUBSTRING(".$sim_original_i;
//$kind['sim-tu-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", $sub.",-3,1) =".$sub.",-4,1)", $sub.",-2,1) = ".$sub.",-3,1)", $sub.",-4,1) != ".$sub.",-5,1)");
//$kind['sim-ngu-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", $sub.",-3,1) =".$sub.",-4,1)", $sub.",-2,1) = ".$sub.",-3,1)", $sub.",-4,1) = ".$sub.",-5,1)", $sub.",-5,1) != ".$sub.",-6,1)");
//$kind['sim-luc-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", $sub.",-3,1) =".$sub.",-4,1)", $sub.",-2,1) = ".$sub.",-3,1)", $sub.",-4,1) = ".$sub.",-5,1)", $sub.",-5,1) = ".$sub.",-6,1)");
//4$kind['sim-taxi']=array($sub.",-2,2) = ".$sub.",-4,2)", $sub.",-4,2) = ".$sub.",-6,2)", $sub.",-1, 1)!= ".$sub.",-2,1) || (".$sub.",-6,3) = ".$sub.",-3,3) AND ".$sub.",-6,2)", $sub.",-1, 1)!= ".$sub.",-2,1))");
//5$kind['sim-nam-sinh']=array($sub.",-4,4) > ".(date('Y')-50),$sub.",-4,4) < ".date('Y'));
//6$kind['sim-loc-phat']=array($sub.",-2,2) = 68 OR ".$sub.",-2,2 = 86)");
//7$kind['sim-than-tai']=array($sub.",-2,2) = 39 OR ".$sub.",-2,2) = 79 OR ".$sub.",-2,2) = 38 OR ".$sub.",-2,2) = 78");
//8$kind['sim-kep']=array($sub.",-1,1) = ".$sub.",-2,1)",$sub.",-3,1) = ".$sub.",-4,1)",$sub.",-2,1) != ".$sub.",-3,1)");
//9$kind['sim-tien-kep']=array($sub.",-2,2) = 11+".$sub.",-4,2)", $sub.",-4,2) = 11+".$sub.",-6,2) || ".$sub.",-2,2) = 10+".$sub.",-4,2)", $sub.",-4,2) = 10+".$sub.",-6,2) || ".$sub.",-1,1) = 1+".$sub.",-2,1)",$sub.",-2,1) = 1+".$sub.",-3,1) || ".$sub.",-1,1) = 2+".$sub.",-2,1)",$sub.",-2,1) = 2+".$sub.",-3,1)");
//13$kind['sim-lap']=array($sub.",-1,1) = ".$sub.",-3,1)",$sub.",-2,1) = ".$sub.",-4,1)",$sub.",-1,1)!=".$sub.",-2,1)",$sub.",-4,2) != ".$sub.",-6,2)");
//12$kind['sim-ganh-dao']=array($sub.",-1,1) = ".$sub.",-4,1)",$sub.",-2,1) = ".$sub.",-3,1)",$sub.",-2,2) !=".$sub.",-4,2)");
//10$kind['sim-tam-hoa-don&kep']=array($sub.",-1,1) = ".$sub.",-2,1)",$sub.",-2,1) = ".$sub.",-3,1)",$sub.",-3,1) !=".$sub.",-4,1)");				
//14$kind['dau-so-co']=array($sub.",1,3) = '091'|| ".$sub.",1,3) = '098' || ".$sub.",1,3) ='097' || ".$sub.",1,3) ='090'");
//11$kind['sim-dep']=array("giaban >= 0.2", "giaban <= 10");
				
						//var_dump($kind);die;				
				//Identify kinds sim
				$simarray = array();
				for ($j=1;$j<12;$j++){
					$simarray[] = substr($sim_original_i,-$j,1);
				}
				$kind = array();
//1$kind['sim-tu-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", 
//$sub.",-3,1) =".$sub.",-4,1)", 
//$sub.",-2,1) = ".$sub.",-3,1)", 
//$sub.",-4,1) != ".$sub.",-5,1)");				
				if(($simarray[0] == $simarray[1]) && 
						($simarray[0] == $simarray[2]) && 
						($simarray[0] == $simarray[3])){
						$kind[] = 'sim-tu-quy';
				}
//2$kind['sim-ngu-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", $sub.",-3,1) =".$sub.",-4,1)", $sub.",-2,1) = ".$sub.",-3,1)", $sub.",-4,1) = ".$sub.",-5,1)", $sub.",-5,1) != ".$sub.",-6,1)");
				if(($simarray[0] == $simarray[1]) && 
						($simarray[0] == $simarray[2]) && 
						($simarray[0] == $simarray[3]) && 
						($simarray[0] == $simarray[4])){
					$kind[] = 'sim-ngu-quy';
				}
//3$kind['sim-luc-quy']=array($sub.",-1,1) = ".$sub.",-2,1)", $sub.",-3,1) =".$sub.",-4,1)", $sub.",-2,1) = ".$sub.",-3,1)", $sub.",-4,1) = ".$sub.",-5,1)", $sub.",-5,1) = ".$sub.",-6,1)");
				if(($simarray[0] == $simarray[1]) && 
						($simarray[0] == $simarray[2]) && 
						($simarray[0] == $simarray[3]) && 
						($simarray[0] == $simarray[4]) && 
						($simarray[0] == $simarray[5])){
						$kind[] = 'sim-luc-quy';
				}
//4$kind['sim-taxi']=array($sub.",-2,2) = ".$sub.",-4,2)", 
//$sub.",-4,2) = ".$sub.",-6,2)",
// $sub.",-1, 1)!= ".$sub.",-2,1) || (".$sub.",-6,3) = ".$sub.",-3,3) AND ".$sub.",-6,2)", 
//$sub.",-1, 1)!= ".$sub.",-2,1))");
				if (substr($sim_original_i,-2,2) == substr($sim_original_i,-4,2) && 
						substr($sim_original_i,-4,2) == substr($sim_original_i,-6,2) &&
						((substr($sim_original_i,-1,1) != substr($sim_original_i,-2,1) || (substr($sim_original_i,-6,3) == substr($sim_original_i,-3,3) && substr($sim_original_i,-6,3) == substr($sim_original_i,-6,2)))) &&
						substr($sim_original_i,-1,1) != substr($sim_original_i,-2,1)
						){
						$kind[] = 'sim-taxi';
				} 
//5$kind['sim-nam-sinh']=array($sub.",-4,4) > ".(date('Y')-50),$sub.",-4,4) < ".date('Y'));
				if ((substr($sim_original_i,-4,4) > (date('Y')-50)) && (substr($sim_original_i,-4,4) < date('Y'))){
						$kind[] = 'sim-nam-sinh';
				}
//6$kind['sim-loc-phat']=array($sub.",-2,2) = 68 OR ".$sub.",-2,2 = 86)");
				if (substr($sim_original_i,-2,2) == 68 || substr($sim_original_i,-2,2) == 86){
						$kind[] = 'sim-loc-phat';
				}				
//7$kind['sim-than-tai']=array($sub.",-2,2) = 39 OR ".$sub.",-2,2) = 79 OR ".$sub.",-2,2) = 38 OR ".$sub.",-2,2) = 78");
				if(substr($sim_original_i,-2,2) == 39 || substr($sim_original_i,-2,2) == 79 || substr($sim_original_i,-2,2) == 38 || substr($sim_original_i,-2,2) ==78){
						$kind[] = 'sim-than-tai';
				}
//8$kind['sim-kep']=array($sub.",-1,1) = ".$sub.",-2,1)",
//$sub.",-3,1) = ".$sub.",-4,1)",
//$sub.",-2,1) != ".$sub.",-3,1)");
				if (substr($sim_original_i,-1,1) == substr($sim_original_i,-2,1) && 
						substr($sim_original_i,-3,1) == substr($sim_original_i,-4,1) && 
						substr($sim_original_i,-2,1) != substr($sim_original_i,-3,1)){
						$kind[] = 'sim-kep';
				}
//9$kind['sim-tien']=array($sub.",-2,2) = 11+".$sub.",-4,2)", 
//$sub.",-4,2) = 11+".$sub.",-6,2) || ".$sub.",-2,2) = 10+".$sub.",-4,2)", 
//$sub.",-4,2) = 10+".$sub.",-6,2) || ".$sub.",-1,1) = 1+".$sub.",-2,1)",
//$sub.",-2,1) = 1+".$sub.",-3,1) || ".$sub.",-1,1) = 2+".$sub.",-2,1)",
//$sub.",-2,1) = 2+".$sub.",-3,1)");
				if ((substr($sim_original_i,-2,2) == 11+substr($sim_original_i,-4,2)) && 
						(substr($sim_original_i,-4,2) == 11+substr($sim_original_i,-6,2) || substr($sim_original_i,-2,2) ==10+substr($sim_original_i,-4,2)) && 
						(substr($sim_original_i,-4,2) == 10+substr($sim_original_i,-2,2) || substr($sim_original_i,-1,1) == 1+substr($sim_original_i,-2,1)) && 
						(substr($sim_original_i,-2,1) == 1+substr($sim_original_i,-3,1) || substr($sim_original_i,-1,1) == 2+substr($sim_original_i,-2,1)) &&
						substr($sim_original_i,-2,1) == 2+substr($sim_original_i,-3,1)){
						$kind[] = 'sim-tien-kep';
				}
//10$kind['sim-tam-hoa-don&kep']=array($sub.",-1,1) = ".$sub.",-2,1)",
//$sub.",-2,1) = ".$sub.",-3,1)",
//$sub.",-3,1) !=".$sub.",-4,1)");
				if (substr($sim_original_i,-1,1) == substr($sim_original_i,-2,1) &&
					substr($sim_original_i,-2,1) == substr($sim_original_i,-3,1) &&
					substr($sim_original_i,-3,1) != substr($sim_original_i,-4,1)){
						$kind[] = 'sim-tam-hoa_don&kep';
				}			
//11$kind['sim-dep']=array("giaban >= 0.2", "giaban <= 10");

//12$kind['sim-ganh-dao']=array($sub.",-1,1) = ".$sub.",-4,1)",
//$sub.",-2,1) = ".$sub.",-3,1)",
//$sub.",-2,2) !=".$sub.",-4,2)");		0995.86.86.68		
				if (substr($sim_original_i,-1,1) == substr($sim_original_i,-4,1) &&
					substr($sim_original_i,-2,1) == substr($sim_original_i,-3,1) &&
							substr($sim_original_i,-2,2) != substr($sim_original_i,-4,2)){
						$kind[] = 'sim-ganh-dao';
				}
//13$kind['sim-lap']=array($sub.",-1,1) = ".$sub.",-3,1)",
//$sub.",-2,1) = ".$sub.",-4,1)",
//$sub.",-1,1)!=".$sub.",-2,1)",
//$sub.",-4,2) != ".$sub.",-6,2)");0939999696
				if (substr($sim_original_i,-1,1) == substr($sim_original_i,-3,1) &&
					substr($sim_original_i,-2,1) == substr($sim_original_i,-4,1) &&
					substr($sim_original_i,-1,1) == substr($sim_original_i,-2,1) &&
					substr($sim_original_i,-4,2) == substr($sim_original_i,-6,2)){			
						$kind[] = 'sim-lap';
				}
							
				//var_dump(implode(',', $kind));die;	
				$kind_implode = implode(',', $kind);
				//Calculate sell price
				//1. If price buy >lower and <upper then priceagency = (100 + this percent) *pricebuy and BREAK else $priceagency = $pricebuy;;
				//2. If don't have % price sell then price sell = prcie agency 
				//    else if $pricebuy >= $sp->lower && $pricebuy <= $sp->upper then and BREAK else 
				//    price sell = price buy * %
				$pricebuy = intval(str_replace(",", "",$datasim[$i+1]));
				if($pricebuy == null){
					echo 'Thiáº¿u giÃ¡ ban. Nháº­p láº¡i...'; break;
				}
				$priceagency = '';
				foreach ($data['agencypercent'] as $ap){
					if ($pricebuy >= $ap->lower && $pricebuy <= $ap->upper){
						$priceagency = $pricebuy*(100-$ap->percent)/100;break;
					} else {
						$priceagency = $pricebuy;
					}
				}
				
				$pricesell = '';//var_dump($data['sellpercent']);die;
				if(!isset($data['sellpercent'])){ 
					$pricesell = $priceagency;	
				}else {
					foreach ($data['sellpercent'] as $sp){
						if ($pricebuy >= $sp->lower && $pricebuy <= $sp->upper){
							$pricesell = $pricebuy*(100+$sp->percent)/100;break;//echo $pricesell;die;
						}else {
							$pricesell = $pricebuy;
						}
					}
					
				}
				
				//echo $kind_implode;die;
				//Prepare data array to import
			    $dataarray[] = array(
			      	'sim_split' => $datasim[$i],
			    	'provider' => $provide,	
			    	'sim_original' => $sim_original_i,
			        'price_buy' => $datasim[$i+1],
			    	'price_agency' => $priceagency,
			    	'price_sell' => $pricesell,
			    	'agency_id' => $_POST['agency'],
			    	'status' => 'sim-moi-ve',
			    	'kind' => $kind_implode
			    );
			    $i=$i+2;
			} while($i<(sizeof($datasim, 0)));//die;
			if ($this->sim_model->insert('sim',$dataarray))
			{
				$this->session->set_flashdata('sessionSuccessAdd', 1);
			}
			//After Success redirect admin import sim
			redirect(base_url().'admin/sim', 'location');
		}
	}
	public function filterdata($data)
	{
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}
	public function percentsell()
	{
		//View page will load data
		$data['sellpercent'] = $this->sim_model->get("*",'sell_percent');
		$this->load->view('admin/sim/percent-sell.phtml',$data);
		
		//Any Exist checkbox will del
		if (isset($_POST['checkbox']) && isset($_POST['delselect'])){
			$this->sim_model->del('sell_percent','percentid',$_POST['checkbox']);
			redirect(base_url().'admin/sim/percent-sell', 'location');
		}
				
		/*Have post will insert or update
		Must have three number: lower, upper and percent, default is enabled
		If have percentid, that's mean have all of row record, 
			Incase, has exception with line has no percentid, 
			so must to be update with that's percentid and insert new data
		if not insert into database with rows what have no percentid	
		*/
		if (isset($_POST['lower']) && isset($_POST['upper']) && isset($_POST['percent'])){			
			if($_POST['percentid'] != null){		
				//Update record hava exist
				for ($i=0;$i<sizeof($_POST['percentid']);$i++){//echo $_POST['percentid'][$i];
					if($_POST['percentid'][$i] != null){
						//$dataarray = null;
						$dataarray[] = array(
								'lower' => $_POST['lower'][$i]*1000,
								'upper' => $_POST['upper'][$i]*1000,
								'percent' => $_POST['percent'][$i],
								'status' => $_POST['status'][$i],
						);
						$this->sim_model->update('sell_percent',$dataarray[$i],'percentid',$_POST['percentid'][$i]);
					} else{
						$dataarray = null;//var_dump($dataarray);
						//Insert new data
						$dataarray[] = array(
								'lower' => $_POST['lower'][$i]*1000,
								'upper' => $_POST['upper'][$i]*1000,
								'percent' => $_POST['percent'][$i],
								'status' => $_POST['status'][$i],
						);
						$this->sim_model->insert('sell_percent',$dataarray);
					}
				}//End of for
			}else{
				for ($i=0;$i<sizeof($_POST['lower']);$i++){
					$dataarray[] = array(
							'lower' => $_POST['lower'][$i]*1000,
							'upper' => $_POST['upper'][$i]*1000,
							'percent' => $_POST['percent'][$i],
							'status' => $_POST['status'][$i],
					);
					$this->sim_model->insert('sell_percent',$dataarray);
				}
			}
			//After Success redirect admin import sim
			redirect(base_url().'admin/sim/percent-sell', 'location');
		}
	}
	public function edit($num)
	{

			$data['simcheck'] = $this->sim_model->get('*','','','id_sim',$num);
			$this->load->view('admin/sim/edit.phtml',$data);
			if (isset($_POST['id_sim'])){
				$dataEdit =array(
					'sim_split' => $_POST['sim_split'],
						'price_sell' => $_POST['price_sell'],
						'price_buy' =>  $_POST['price_buy'],
						'price_agency' =>  $_POST['price_agency']
				);
				if ($this->sim_model->update('sim',$dataEdit,'id_sim',$num))
				{
					$this->session->set_flashdata('sessionSuccessEdit', 1);
				}
				redirect(base_url().'admin/sim', 'location');
			} 	
	}
	public function delpercentsell($num)
	{
		if (isset($num)){
			if ($this->sim_model->del('sell_percent','percentid',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/sim/percent-sell', 'location');
		} else{
			$this->load->view('admin/sim/import-sim.phtml',$data);
		}
	}
	public function del($num)
	{
		if (isset($num)){
			if ($this->sim_model->del('sim','id_sim',$num))
			{
				$this->session->set_flashdata('sessionSuccessEdit', 1);
			}
			redirect(base_url().'admin/sim', 'location');
		} else{
			$this->load->view('admin/sim',$data);
		}
	}
	public function delduplicate()
	{
		if (isset($_POST['start'])){
			if (isset($_POST['c'])){
				$type = 'min';
			}
			$data['del'] = $this->sim_model->delduplicate($type); //var_dump($data['del']);die;
				$this->load->view('admin/sim/del-duplicate.phtml',$data);
				


		} else {
			$this->load->view('admin/sim/del-duplicate.phtml');			
		}
		
		//var_dump($del);
	}
	public function delsold($page = '')
	{
		$config['base_url'] = base_url().'admin/sim/sold/page/';//.$queryStrings;
			
		$config['per_page'] = 20;
		$config['total_rows'] = $this->sim_model->countsold();
		$config['uri_segment'] = 3;
			
		$config['enable_query_strings'] = TRUE;
		
		$config['page_query_string'] = false;
		//$config['use_page_numbers'] = TRUE;
		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}//var_dump($page);die;
		$config['cur_page'] = $page;
		$config['first_link'] = '<a href="'.$config['base_url'].'">First</a>';
		$last = round($config['total_rows']/$config['per_page']);
		$config['last_link'] = '<a href="'.$config['base_url'].$last.'">Last</a>';
		$prev = $page-$config['per_page'];
		$config['prev_link'] = '<a href="'.$config['base_url'].$prev.'">Prev</a>';
		
		$next = $page+$config['per_page'];
		$search ='search';
		//$config['next_link'] = "<a onclick=\"document.forms['search'].submit()\" href=\"".$config['base_url'].$next.'">Next</a>';
		$config['next_link'] = '<a  href="'.$config['base_url'].$next.'">Next</a>';
			
		$config['cur_tag_open']         = '<span id="current_page"><label>';
		$config['cur_tag_close']    = '</label></span>';
		$config['num_tag_open']     = '<span>';
		$config['num_tag_close']     = '</span>';
		$config['full_tag_open']     = '<p id="pager_links" style="float: right;">';
		$config['full_tag_close']     = '</p>'; ;
		$config['num_links']=6;
		$this->pagination->initialize($config); //echo $config['per_page'].$page.$provider;die;
		// var_dump($data['sim']);die;
		//var_dump($config['cur_page']);die;
		$data['pagination'] = $this->pagination->create_links();
		if(isset($_POST['delselect'])){
			$data['sim'] = $this->sim_model->del('sim',$_POST['simcheck']);
			$data['totalsim'] = sizeof($data['sim']);
			$data['simcheck'] = $_POST['simcheck'];
			$this->load->view('admin/sim/simsold.phtml',$data);
		
		}elseif(isset($_POST['delsold'])){
			$this->sim_model->del('sim','status','sold');
			$this->load->view('admin/sim/simsold.phtml',$data);
		} else {
			$data['sim'] = $this->sim_model->list_all($config['per_page'],$page,'agency_profile',array('agency_id','profileid','status','sold'));
			//var_dump(sizeof($data['sim']));die;
			$data['totalsim'] = sizeof($data['sim']);
			$this->load->view('admin/sim/simsold.phtml',$data);
		}
		$data['listsold'] = $this->sim_model->get('*','','','status','sold');
		
		if (isset($delsold)){
			
		}
	}
}
?>