<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FetchAlldata extends CI_Controller {

    public function __construct(){

    	    parent::__construct();
            $this->load->model('Tablescript'); 	
            $this->load->library('XML2Array');
            $param = array();

    } 
	
	public function fetchAmazon_data(){
		    
		$crediantials = $this->Tablescript->getCrediantial();
		//$check_new0 = $this->Tablescript->deleteAmazonInbound();
		//if($check_new0){
		foreach ($crediantials as $crediantial) {

			$param['AWSAccessKeyId']   = $crediantial['accesskeyid']; 
			$param['SellerId']         = $crediantial['seller_id'];
			$param['SignatureMethod']  = 'HmacSHA256';  
			$param['SignatureVersion'] = '2';  
			$param['Version']          = '2009-01-01'; 
			$param['MarketplaceId']    = $crediantial['marketplace_id'];
			$secret = $crediantial['secret_key'];
			$param['AWSSecretAccessKey'] = $crediantial['secret_key'];
			//$param['MWSAuthToken'] = $crediantial['auth_token'];

			$ReportType      = '_GET_RESTOCK_INVENTORY_RECOMMENDATIONS_REPORT_'; 
			$ReportRequestId = $this->getReportRequestId($ReportType,$param);

			if($ReportRequestId){
				$ReportId        = $this->getReportId($ReportRequestId,$param);
			//print_r($ReportId);die;
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
					$res_new = array();
					$res_new1 = array();
					$res_new = explode("\n", $res);
							
					for ($i=0; $i < count($res_new)-1; $i++) { 
						if($i>0){
						$res_new2 = explode("\t", $res_new[$i]);
						$check_new = $this->Tablescript->insertAmazonInbound($crediantial['user_id'],$res_new2);
						}
					}
					
				}	
			}
			sleep(60);
		}
	//}   
	   
		$this->fetchorders(); 
			
        
	}

	private function fetchorders(){
       
        $crediantials = $this->Tablescript->getCrediantial();
		//$check_new0 = $this->Tablescript->deleteAmazonInbound();
		//if($check_new0){
		foreach ($crediantials as $crediantial) {

			$param['AWSAccessKeyId']   = $crediantial['accesskeyid']; 
			$param['SellerId']         = $crediantial['seller_id'];
			$param['SignatureMethod']  = 'HmacSHA256';  
			$param['SignatureVersion'] = '2';  
			$param['Version']          = '2009-01-01'; 
			$param['MarketplaceId']    = $crediantial['marketplace_id'];
			$secret = $crediantial['secret_key'];
			$param['AWSSecretAccessKey'] = $crediantial['secret_key'];
			//$param['MWSAuthToken'] = $crediantial->auth_token;
			
			
			$ReportType      = '_GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_'; 
			$ReportRequestId = $this->getReportRequestId($ReportType,$param);
           
			if($ReportRequestId){
				$ReportId        = $this->getReportId($ReportRequestId,$param);
				
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
					//echo "<pre>";print_r($res_new2);die;
				
					}
					 
				}
			sleep(60);		
			}
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
	        sleep(20);

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
          
		    $crediantials = $this->Tablescript->getCrediantial();
          
		    foreach ($crediantials as $crediantial) {
                    
                $total_row = $this->Tablescript->num_rows($crediantial['user_id']);

                $k=0; 
                while($k<$total_row){

			    	$query=$this->db->query("SELECT DISTINCT asin FROM amazon_mws 
					WHERE user_id = $crediantial->user_id LIMIT $offset,8");
	
				    $result = $query->result_array();
		            $i=1;
		            foreach ($result as $result) {
		            	$param['ASINList.ASIN.'.$i] = $result['asin'];
		            	$i++;
		            }
			        $param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
				    $param['AWSAccessKeyId']   = $crediantial['accesskeyid']; 
					$param['SellerId']         = $crediantial['seller_id'];
				    $param['SignatureMethod']  = 'HmacSHA256';  
				    $param['SignatureVersion'] = '2';  
					$param['Version']          = '2011-10-01'; 
					$param['MarketplaceId']    = $crediantial['marketplace_id'];
					$secret = $crediantial['secret_key'];
					$param['AWSSecretAccessKey'] = $crediantial['secret_key'];
					$param['Action'] = 'GetMatchingProduct';
					//$param['MWSAuthToken'] = $crediantial['auth_token'];
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
	                        //print_r($res);
							$responses = XML2Array::createArray($res);
							if(isset($responses['GetMatchingProductResponse'])){
                            $responses_new = $responses['GetMatchingProductResponse']['GetMatchingProductResult'];
                            //echo "<pre>";print_r($responses_new);die;
								foreach ($responses_new as $key => $response) {
									if(isset($response['Product'])){
										$imageUrl = $response['Product']['AttributeSets']['ns2:ItemAttributes']['ns2:SmallImage']['ns2:URL'];
										$Asin =  $response['Product']['Identifiers']['MarketplaceASIN']['ASIN'];
										$saveImage = $this->Tablescript->saveImage($crediantial['user_id'],$Asin,$imageUrl);
									}		
								}
							}	
			               

							$k = $k+8;
						//	sleep(5);
			    
                } 
			}				
		}

    
    


}
