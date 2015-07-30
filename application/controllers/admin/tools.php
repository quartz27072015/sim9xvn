<?php
class tools extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	public function checksites()
	{
		if (isset($_POST['start'])){
			if (isset($_POST['datasim'])){
				$datasim = preg_split('/[\s]+/', $this->filterdata($_POST['datasim'])); //var_dump($datasim);die;
				$sites = array();
				$status = array();
				$i=0;
				$url = 'sovip.com.vn';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_NOBODY, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_exec($ch);
				$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//Split to be process later...
				//Assign kind sim process in frontend
				//Create data to insert
				do {
					$sites[] = $datasim[$i];
					
					$url = rawurldecode($datasim[$i]);
					$url = rawurlencode($datasim[$i]);
					$url = str_replace ( "%3A%2F", ":/", $url);
					$url = str_replace ( "%2F", "/", $url);
					//$http = split( ":/", $url);
					if (!$this->url_exists($url)) {	
						$status[$i] = '<p style="color: red; margin-bottom: 0px">Chết</p>';
					} else {
						$status[$i] = '<p style="color: green; margin-bottom: 0px">Sống</p>';
					}		
					$i++;	
				} while($i<(sizeof($datasim, 0)));
				$data['sites'] = $sites;
				$data['status'] = $status;//var_dump($data);die;
				$this->load->view('admin/tools/checksites.phtml',$data);
			}
		} else {
			$this->load->view('admin/tools/checksites.phtml');
		}
	}
	public function filterdata($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	public function url_exists($url){//var_dump($url);die;
		$url = str_replace("http://", "", $url);
		if (strstr($url, "/")) {
			$url = explode("/", $url, 2);
			$url[1] = "/".$url[1];
		}else {
			$url = array($url, "/");
		}
		$url_port = explode(':', $url[0]);
		if (isset($url_port[1])) {
			$port = $url_port[1];
			$url[0] = str_replace(':'.$port, '', $url[0]);
		}
		else $port = 80;//var_dump($url[0]);die;
		$ping = shell_exec("ping -n 1 ".$url[0]);//echo $ping;//die;
		
		if (strstr($ping, 'Ping request could not find host')) {return FALSE;exit;}
		else {
			$fh = fsockopen($url[0], $port);//var_dump(fread($fh, 22));die;
			if ($fh) {
				fputs($fh,"GET ".$url[1]." HTTP/1.1\nHost:".$url[0]."\n\n");//var_dump(fread($fh, 22));die;
				if (fread($fh, 22) == "HTTP/1.1 404 Not Found") { return FALSE; }
				else { return TRUE; }		
			}else { return FALSE;}
		}
	}
}