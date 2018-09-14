<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userdetail extends CI_Controller {

    public function __construct(){

    	    parent::__construct();
                $this->load->model('User_model'); 	
            $param = array();
			$this->load->library('pagination');
			$this->load->model('Tablescript'); 	
            $this->load->library('XML2Array');

    } 

	public function index()
	{   
        
		$this->load->view('login_form');
		
	}
	

	public function replens()
	{   

        if (!$this->session->userdata('user_id')){
    		return redirect('Userdetail');
        }else{
            $sku = ''; 
            
            $config=[
                 'base_url'=> base_url().index_page()."/userdetail/replens",
                 'per_page'=>10,
                 'total_rows'=>$this->User_model->num_rows(1,$sku),
                 'full_tag_open'=>"<ul class='pagination'>",
                 'full_tag_close'=>"</ul>",
                 'next_tag_open' =>'<li>',
                 'next_tag_close' =>'</li>',
                 'prev_tag_open' =>'<li>',
                 'prev_tag_close' =>'</li>',
                 'last_tag_open' =>'<li>',
                 'last_tag_close' =>'</li>',
                 'num_tag_open' =>'<li>',
                 'num_tag_close' =>'</li>',
                 'cur_tag_open' =>"<li class='active'><a>",
                 'cur_tag_close' =>'</a></li>',
                 'first_tag_open' =>'<li>',
                 'first_tag_close' =>'</li>',
                ];



            $offset=$this->uri->segment(3); 
            if($offset!=''){
            	$item_no = $offset +1;
            }else{
				$item_no=1;
				$offset = 0;
            }
           
            $this->pagination->initialize($config);
           

            $AmazonDatareplens =	$this->User_model->getAmazonData(['user_id' => $this->session->userdata('user_id'),'limit'=> $config['per_page'],'offset'=>$offset,'move' =>1,'sku'=>'']);

           // $AmazonDatainventory =	$this->User_model->getAmazonData(['user_id' => $this->session->userdata('user_id'),'limit'=> $config['per_page'],'offset'=>$offset,'move' =>0]);
             
            $fetchCrediantial =	$this->User_model->getCrediantial(['user_id' => $this->session->userdata('user_id')]);
            
		    $this->load->view('home_page',['username' => $this->session->userdata('user_username'),'email' => $this->session->userdata('user_email'),'phone' => $this->session->userdata('user_phone'), 'fetchCrediantial' => $fetchCrediantial,'AmazonDatareplens' => $AmazonDatareplens,'move'=>1,'item_no'=>$item_no]);
        }
		
	}

	public function inventory()
	{   

        if (!$this->session->userdata('user_id')){
    		return redirect('Userdetail');
        }else{
                $method = $this->input->server('REQUEST_METHOD');
        	if($method =='POST'){
	            $sku = $this->input->post('sku');
	        }else{
	            $sku = '';
	        }
	           
            $config=[
                 'base_url'=> base_url().index_page()."/userdetail/inventory",
                 'per_page'=>10,
                 'total_rows'=>$this->User_model->num_rows(0,$sku),
                 'full_tag_open'=>"<ul class='pagination'>",
                 'full_tag_close'=>"</ul>",
                 'next_tag_open' =>'<li>',
                 'next_tag_close' =>'</li>',
                 'prev_tag_open' =>'<li>',
                 'prev_tag_close' =>'</li>',
                 'last_tag_open' =>'<li>',
                 'last_tag_close' =>'</li>',
                 'num_tag_open' =>'<li>',
                 'num_tag_close' =>'</li>',
                 'cur_tag_open' =>"<li class='active'><a>",
                 'cur_tag_close' =>'</a></li>',
                 'first_tag_open' =>'<li>',
                 'first_tag_close' =>'</li>',
                ];

           //echo "<pre>";print_r(expression)

            $offset=$this->uri->segment(3); 
            if($offset!=''){
            	$item_no = $offset +1;
            }else{
				$item_no = 1;
				$offset = 0;
            }
            
            $this->pagination->initialize($config);
            
            //$AmazonDatareplens =	$this->User_model->getAmazonData(['user_id' => $this->session->userdata('user_id'),'limit'=> $config['per_page'],'offset'=>$offset,'move' =>1]);

            $AmazonDatainventory =	$this->User_model->getAmazonData(['user_id' => $this->session->userdata('user_id'),'limit'=> $config['per_page'],'offset'=>$offset,'move' =>0,'sku'=>$sku]);
             
            $fetchCrediantial =	$this->User_model->getCrediantial(['user_id' => $this->session->userdata('user_id')]);
            
		    $this->load->view('home_page',['username' => $this->session->userdata('user_username'),'email' => $this->session->userdata('user_email'),'AmazonDatainventory' => $AmazonDatainventory,'phone' => $this->session->userdata('user_phone'), 'fetchCrediantial' => $fetchCrediantial,'move'=>0,'item_no'=>$item_no,'sku'=>$sku]);
        }
		
	}

	public function save_crediantial()
	{   
	    if (!$this->session->userdata('user_id')){
    		return redirect('Userdetail');
        }else{

			$seller_id = trim($this->input->post('seller_id'));
			$marketplace_id = trim($this->input->post('marketplace_id'));
			// $secret_key = trim($this->input->post('secret_key'));
			// $accesskeyid = trim($this->input->post('accesskeyid'));
		    if(!empty($seller_id) || !empty($auth_token)){
	        $data =array(
	          'seller_id' => $seller_id,
	          'marketplace_id' => $marketplace_id,
	          'email'       => $this->session->userdata('user_email'),
	          'phone'       => $this->session->userdata('user_phone'),
	          'user_id'     => $this->session->userdata('user_id'),
	         
	        );

	        $check = $this->User_model->save_crediantial($data);
			if ($check==true) {
			    $this->session->set_flashdata('feedback',"Crediantials saved successfully.");
				$this->session->set_flashdata('feedback_class','alert-success');
				sleep(10);
				$this->fetchAmazon_data();
			}else{
			    $this->session->set_flashdata('feedback',"Some error occurs in processing.");
			    $this->session->set_flashdata('feedback_class','alert-danger');
			 }
			}
			
		}	
		
	}

	public function move_data()
	{   
	    if (!$this->session->userdata('user_id')){
    		return redirect('Userdetail');
        }else{
			$sku = $this->input->post('sku');
	        $data =array(
	          'move_data' => 1,
	        
	        );

	        $check = $this->User_model->moveData($sku,$data);
			if ($check==true) {
			    $this->session->set_flashdata('feedback',"Data Moved successfully.");
			    $this->session->set_flashdata('feedback_class','alert-success');
			}else{
			    $this->session->set_flashdata('feedback',"Some error occurs in processing.");
			    $this->session->set_flashdata('feedback_class','alert-danger');
			}
		    

			return redirect('Userdetail/replens');
	    }		
		
	}

	public function loginUser()
	{   
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

			$data = array('email' => $email,
	                      'password' => md5($password)
		    );
			
			if ($this->form_validation->run()){
			    $check = $this->User_model->user_login($data);

			    if ($check) {
			     $this->session->set_userdata('user_id',$check->user_id);
			     $this->session->set_userdata('user_username',$check->username);
			     $this->session->set_userdata('user_email',$check->email);
			     $this->session->set_userdata('user_phone',$check->phone);
                
			     $this->session->set_flashdata('feedback',"User Login successfully.");
			     $this->session->set_flashdata('feedback_class','alert-success');
			     return redirect('Userdetail/replens');

			    }else{
			     $this->session->set_flashdata('feedback',"Ivalid User detail.");
			     $this->session->set_flashdata('feedback_class','alert-danger');
			     $this->load->view('login_form',$data);
			    }

		    }else{
		    	 $this->load->view('login_form');
		    }
	    

	}

	public function registerUser()
	{  
			
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$phone = $this->input->post('phone');
			//$website = $this->input->post('website');
	        $this->form_validation->set_rules('username', 'Username','required|min_length[5]|max_length[20]');
	        $this->form_validation->set_rules('phone', 'Phone', 'is_natural|max_length[10]');
	        $this->form_validation->set_rules('password', 'Password', 'required');
	        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user_detail.email]'); 
	        $data =array(
	          'username' => $username,
	          'email' => $email,
	          'password' => md5($password),
	          'phone' => $phone,
	         

	        );
	        if ($this->form_validation->run()){

				$check = $this->User_model->user_register($data);
				if ($check==true) {

			     $this->session->set_userdata('user_username',$username);
			     $this->session->set_userdata('user_email',$email);
			     $this->session->set_userdata('user_phone',$phone);
			     		
			     $this->session->set_flashdata('feedback',"User Register successfully.");
			     $this->session->set_flashdata('feedback_class','alert-success');
			     return redirect('Userdetail/replens');
			    }else{
			     $this->session->set_flashdata('feedback',"Ivalid Register detail.");
			     $this->session->set_flashdata('feedback_class','alert-danger');
			     $this->load->view('login_form');
			    }

		    }else{
	             $this->load->view('login_form');
		    }
			
		
	}
	
    public function logOut()
	{   
        if ($this->session->has_userdata('user_id')){

        	$this->session->unset_userdata('user_id');
        	$this->session->unset_userdata('user_username');
        	$this->session->unset_userdata('user_email');
        }
            return redirect('Userdetail');
		
	}

	public function move_dataToinventory()
	{   
		if (!$this->session->userdata('user_id')){
    		return redirect('Userdetail');
        }else{
	        $sku = $this->input->get('sku');
	        $data =array(
		          'move_data' => 0,
		        
		        );
	        $check2 = $this->User_model->moveDataToinventory($sku,$data);
	        if($check2==true){
		        $this->session->set_flashdata('feedback',"Data moved to inventory successfully.");
			    $this->session->set_flashdata('feedback_class','alert-success');
			    return redirect('Userdetail/inventory');
	        }else{
		        $this->session->set_flashdata('feedback',"Sorry! Data have not moved. Please try again.");
				$this->session->set_flashdata('feedback_class','alert-alert');
				return redirect('Userdetail/inventory'); 	

	        }
	    }    
		
	}

    
    public function save_data_byUser(){
    	if ($this->session->has_userdata('user_id')){

	    	$sku = $this->input->post('sku');
	    	$upc = $this->input->post('upc');
	    	$lead_time = $this->input->post('lead_time');
	    	$supplier_name = $this->input->post('supplier_name');
	    	$supplier_number = $this->input->post('supplier_number');
	    	$supplier_email = $this->input->post('supplier_email');
	    	$supplier_website = $this->input->post('supplier_website');
		    
		    $this->form_validation->set_rules('supplier_name', 'Supplier Name','max_length[20]');
	        $this->form_validation->set_rules('supplier_email', 'Supplier Email', 'valid_email'); 

            $data = array(
            	'upc' => $upc,
            	'lead_time' => $lead_time,    
            	'supplier_name' => $supplier_name, 
            	'supplier_number' => $supplier_number, 
            	'supplier_email' => $supplier_email, 
            	'supplier_website' => $supplier_website,        
		    );
            
	     
	        if ($this->form_validation->run()){
		   
				$check2 = $this->User_model->save_data_from_user($sku,$data);
				
				if ($check2==true) {

				    $this->session->set_flashdata('feedback',"Data save successfully.");
				    $this->session->set_flashdata('feedback_class','alert-success');
				    return redirect('Userdetail/replens');
				}else{
				    $this->session->set_flashdata('feedback',"Data did not save.");
				    $this->session->set_flashdata('feedback_class','alert-danger');
				    return redirect('Userdetail/replens');
				}
		    }else{
				return redirect('Userdetail/replens');
		    }		

        }else{
        	return redirect('Userdetail');
        }
 
	}
	

	public function fetchAmazon_data(){
        // echo "I AM fetchAmazon_inbound <br><br>";
        $user_id = $this->session->userdata('user_id');
		$crediantial = $this->User_model->getCrediantial(['user_id' => $user_id]);
		//foreach ($crediantials as $crediantial) {
			$param['AWSAccessKeyId']   = $crediantial->accesskeyid; 
			$param['SellerId']         = $crediantial->seller_id;
			$param['SignatureMethod']  = 'HmacSHA256';  
			$param['SignatureVersion'] = '2';  
			$param['Version']          = '2009-01-01'; 
			$param['MarketplaceId']    = $crediantial->marketplace_id;
			$secret = $crediantial->secret_key;
			$param['AWSSecretAccessKey'] = $crediantial->secret_key;
			//$param['MWSAuthToken'] = $crediantial->auth_token;
			
			//$ReportType      = '_GET_FBA_MYI_ALL_INVENTORY_DATA_';
			$ReportType      = '_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_'; 
			$ReportRequestId = $this->getReportRequestId($ReportType,$param);
            //echo "I AM fetchAmazon_inbound".$ReportId."<br><br>";
			if($ReportRequestId){
				$ReportId        = $this->getReportId($ReportRequestId,$param);
				//echo "I AM fetchAmazon_inbound".$ReportId."<br><br>";
			
				if($ReportId){ 
					
					$param['Timestamp']= gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
					$param['ReportId'] = $ReportId;
					$param['Action']   = 'GetReport'; 

					$url = array();
					foreach ($param as $key => $val) {

						$key = str_replace("%7E", "~", rawurlencode($key));
						$val = str_replace("%7E", "~", rawurlencode($val));
						$url[] = "{$key}={$val}";
					}

					sort($url);

					$arr1   = implode('&', $url);

					$sign1  = 'GET' . "\n";
					$sign1 .= 'mws.amazonservices.com' . "\n";
					$sign1 .= '/Reports/2009-01-01' . "\n";
					$sign1 .= $arr1;

					$signature1 = hash_hmac("sha256", $sign1, $secret, true);
					$signature1 = urlencode(base64_encode($signature1));

					$link1  = "https://mws.amazonservices.com/Reports/2009-01-01?";
					$link1 .= $arr1 . "&Signature=" . $signature1;
					//echo($link1)."<br><br><br><br><br>"; //for debugging - you can paste this into a browser and see if it loads.
					//sleep(10);
					$ch1 = curl_init($link1);
					curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE); 
					$res = curl_exec($ch1);
					curl_close($ch1);	
					//print_r('$res');
					$res_new = array();
					$res_new1 = array();
					$res_new = explode("\n", $res);
				 
					// $check_new0 = $this->User_model->deleteAmazonInbound($crediantial->user_id);
					// if($check_new0){           
					for ($i=0; $i < count($res_new)-1; $i++) { 
						if($i>0){
							$res_new2 = explode("\t", $res_new[$i]);
							$check_new = $this->Tablescript->insertAmazonInbound($crediantial->user_id,$res_new2);
					    }		
					}
					//echo "<pre>";print_r($res_new2);die;
				
				//	}
					 
				}	
			}
			sleep(3);
		    $this->fetchorders();
	
	}

	private function fetchorders(){
        // echo "I AM fetchAmazon_inbound <br><br>";
        $user_id = $this->session->userdata('user_id');
		$crediantial = $this->User_model->getCrediantial(['user_id' => $user_id]);
		//foreach ($crediantials as $crediantial) {
			$param['AWSAccessKeyId']   = $crediantial->accesskeyid; 
			$param['SellerId']         = $crediantial->seller_id;
			$param['SignatureMethod']  = 'HmacSHA256';  
			$param['SignatureVersion'] = '2';  
			$param['Version']          = '2009-01-01'; 
			$param['MarketplaceId']    = $crediantial->marketplace_id;
			$secret = $crediantial->secret_key;
			$param['AWSSecretAccessKey'] = $crediantial->secret_key;
			//$param['MWSAuthToken'] = $crediantial->auth_token;
			
			//$ReportType      = '_GET_FBA_MYI_ALL_INVENTORY_DATA_';
			$ReportType      = '_GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_'; 
			$ReportRequestId = $this->getReportRequestId($ReportType,$param);
           // echo "I AM fetchAmazon_inbound".$ReportRequestId."<br><br>";
			if($ReportRequestId){
				$ReportId        = $this->getReportId($ReportRequestId,$param);
				//echo "I AM fetchAmazon_inbound".$ReportId."<br><br>";
			    //$ReportId = 10003324379017703;
				if($ReportId){ 
					
					$param['Timestamp']= gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
					$param['ReportId'] = $ReportId;
					$param['Action']   = 'GetReport'; 

					$url = array();
					foreach ($param as $key => $val) {

						$key = str_replace("%7E", "~", rawurlencode($key));
						$val = str_replace("%7E", "~", rawurlencode($val));
						$url[] = "{$key}={$val}";
					}

					sort($url);

					$arr1   = implode('&', $url);

					$sign1  = 'GET' . "\n";
					$sign1 .= 'mws.amazonservices.com' . "\n";
					$sign1 .= '/Reports/2009-01-01' . "\n";
					$sign1 .= $arr1;

					$signature1 = hash_hmac("sha256", $sign1, $secret, true);
					$signature1 = urlencode(base64_encode($signature1));

					$link1  = "https://mws.amazonservices.com/Reports/2009-01-01?";
					$link1 .= $arr1 . "&Signature=" . $signature1;
					//echo($link1)."<br><br><br><br><br>"; //for debugging - you can paste this into a browser and see if it loads.
					//sleep(10);
					$ch1 = curl_init($link1);
					curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE); 
					$res = curl_exec($ch1);
					curl_close($ch1);	
					//print_r($res);
					$res_new = array();
					$res_new1 = array();
					$res_new = explode("\n", $res);
				 
					// $check_new0 = $this->User_model->deleteAmazonInbound($crediantial->user_id);
					// if($check_new0){           
					for ($i=0; $i < count($res_new)-1; $i++) { 
						if($i>0){
							$res_new2 = explode("\t", $res_new[$i]);
							$check_new = $this->Tablescript->updateAmazonInbound($crediantial->user_id,$res_new2[1],$res_new2[17],$res_new2[18]);
					    }		
					}
					echo "<pre>";print_r($res_new2);die;
				
				//	}
					 
				}	
			}
			sleep(3);
		   $this->fetchImage();
	
	}
	
	private function getReportRequestId($ReportType,$param)
    {   
	
		$param['Action']           = 'RequestReport';   
		$param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
		$param['ReportType']       = $ReportType; 
		$param['Version']          = '2009-01-01'; 
		$secret = $param['AWSSecretAccessKey'];
		//echo "<pre>";print_r($param);die;
		$url = array();
		foreach ($param as $key => $val) {

			$key = str_replace("%7E", "~", rawurlencode($key));
			$val = str_replace("%7E", "~", rawurlencode($val));
			$url[] = "{$key}={$val}";
		}

		sort($url);

		$arr   = implode('&', $url);

		$sign  = 'GET' . "\n";
		$sign .= 'mws.amazonservices.com' . "\n";
		$sign .= '/Reports/2009-01-01' . "\n";
		$sign .= $arr;

		$signature = hash_hmac("sha256", $sign, $secret, true);
		$signature = urlencode(base64_encode($signature));

		$link  = "https://mws.amazonservices.com/Reports/2009-01-01?";
		$link .= $arr . "&Signature=" . $signature;
		//echo($link); //for debugging - you can paste this into a browser and see if it loads.

		$ch = curl_init($link);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$response = curl_exec($ch);
		curl_close($ch);
		$xml = new SimpleXMLElement($response);
		$ReportRequestId = $xml->RequestReportResult->ReportRequestInfo->ReportRequestId;
		sleep(10);

		return $ReportRequestId;

	}

	private function getReportId($ReportRequestId,$param)
	{           
			$param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
			$param['ReportRequestIdList.Id.1'] = $ReportRequestId;
			$param['Action']                   = 'GetReportList'; 
			$secret = $param['AWSSecretAccessKey'];

			$url = array();
			foreach ($param as $key => $val) {

				$key = str_replace("%7E", "~", rawurlencode($key));
				$val = str_replace("%7E", "~", rawurlencode($val));
				$url[] = "{$key}={$val}";
			}

			sort($url);

			$arr1   = implode('&', $url);

			$sign1  = 'GET' . "\n";
			$sign1 .= 'mws.amazonservices.com' . "\n";
			$sign1 .= '/Reports/2009-01-01' . "\n";
			$sign1 .= $arr1;

			$signature1 = hash_hmac("sha256", $sign1, $secret, true);
			$signature1 = urlencode(base64_encode($signature1));

			$link1  = "https://mws.amazonservices.com/Reports/2009-01-01?";
			$link1 .= $arr1 . "&Signature=" . $signature1;
			//echo($link1)."<br><br><br><br><br>"; //for debugging - you can paste this into a browser and see if it loads.
		
			$ch1 = curl_init($link1);
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE); 
			$res = curl_exec($ch1);
			curl_close($ch1); 
			$xml1 = new SimpleXMLElement($res);
			$ReportId = $xml1->GetReportListResult->ReportInfo->ReportId;

			return $ReportId;      
    }


	private function fetchImage(){
		//echo "I AM fetchImage <br><br>";
	    $user_id = $this->session->userdata('user_id');
		$crediantial = $this->User_model->getCrediantial(['user_id' => $user_id]);
	  
		//foreach ($crediantials as $crediantial) {
				
			$total_row = $this->Tablescript->num_rows($crediantial->user_id);

			$offset = 0; 
			while($offset <= $total_row){

				$query=$this->db->query("SELECT DISTINCT asin FROM amazon_mws
				WHERE user_id = $crediantial->user_id LIMIT $offset,8");
	
				$result = $query->result_array();

				$i=1;
				foreach ($result as $result) {
					$param['ASINList.ASIN.'.$i] = $result['asin'];
					$i++;
				}
				$param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
				$param['AWSAccessKeyId']   = $crediantial->accesskeyid; 
				$param['SellerId']         = $crediantial->seller_id;
				$param['SignatureMethod']  = 'HmacSHA256';  
				$param['SignatureVersion'] = '2';  
				$param['Version']          = '2011-10-01'; 
				$param['MarketplaceId']    = $crediantial->marketplace_id;
				$secret = $crediantial->secret_key;
				$param['AWSSecretAccessKey'] = $crediantial->secret_key;
				$param['Action'] = 'GetMatchingProduct';
			//	$param['MWSAuthToken'] = $crediantial->auth_token;
				//echo "<pre>";print_r($param);die;
				
			
						$url = array();
						foreach ($param as $key => $val) {

							$key = str_replace("%7E", "~", rawurlencode($key));
							$val = str_replace("%7E", "~", rawurlencode($val));
							$url[] = "{$key}={$val}";
						}

						sort($url);

						$arr1   = implode('&', $url);

						$sign1  = 'GET' . "\n";
						$sign1 .= 'mws.amazonservices.com' . "\n";
						$sign1 .= '/Products/2011-10-01' . "\n";
						$sign1 .= $arr1;

						$signature1 = hash_hmac("sha256", $sign1, $secret, true);
						$signature1 = urlencode(base64_encode($signature1));

						$link1  = "https://mws.amazonservices.com/Products/2011-10-01?";
						$link1 .= $arr1 . "&Signature=" . $signature1;
						//echo($link1)."<br><br><br><br><br>"; //for debugging - you can paste this into a browser and see if it loads.
						//sleep(10);
						$ch1 = curl_init($link1);
						curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
						curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE); 
						$res = curl_exec($ch1);
						curl_close($ch1);
                       // print_r($res);
						$responses = XML2Array::createArray($res);
						if(isset($responses['GetMatchingProductResponse'])){
						//echo "I AM fetchImage Response <br><br>";	
						$responses_new = $responses['GetMatchingProductResponse']['GetMatchingProductResult'];
							
							foreach ($responses_new as $key => $response) {
								if(isset($response['Product'])){
									$imageUrl = $response['Product']['AttributeSets']['ns2:ItemAttributes']['ns2:SmallImage']['ns2:URL'];
									$Asin =  $response['Product']['Identifiers']['MarketplaceASIN']['ASIN'];
									$saveImage = $this->Tablescript->saveImage($crediantial->user_id,$Asin,$imageUrl);
								}		
							}
						}	
						$offset = $offset+8;
			            
			}
			//die('dfgfdgdf');
		  return redirect('Userdetail/replens');
		
		}				
  


}
