<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

        public function user_login($data=array()){

            $query = $this->db->select('username,email,phone,user_id')
                              ->from('user_detail')
                              ->where($data)
                              ->get();
            return $query->row();

               
        }

        public function user_register($data=array())
        {   
           
            $query = $this->db->select_max('user_id')
                     ->from('user_detail') 
                     ->get();
            $result = $query->row();      
            $user_id = $result->user_id;
            $user_id = $user_id+1;
            $data['user_id'] = $user_id;
            $query= $this->db->insert('user_detail',$data);
            if($query){
                $this->session->set_userdata('user_id',$user_id);
            }
            return $query;
                
        }
        public function getAmazonData($data=array())
        {   
           $results_new = array(); 
           $user_id = $this->session->userdata('user_id');
            
           $move = $data['move'];  
           $sku = $data['sku'];
           if($sku!=''){
              $where = "move_data = '$move' AND sku = '$sku' AND user_id = '$user_id'";  
           }else{
              $where = "move_data = '$move' AND user_id = '$user_id'";
           }
           //print_r($where);die;
           //print_r($where);die;
           $query = $this->db->query("SELECT DISTINCT sku ,asin,item_name, image_url, upc,
           lead_time, supplier_name, supplier_number, supplier_email,supplier_website,
           updated_at,created_at,unit_last_30_days AS total_order_30,unit_last_7_days AS total_order_7,inbound_inventory AS inbound ,
           available_inventory AS inventory 
           FROM amazon_mws WHERE $where LIMIT $data[offset],$data[limit]");
                            
            $results = $query->result_array();

            foreach($results as $result){
                if($result['image_url']==''){
                    $result['image_url']="http://g-ecx.images-amazon.com/images/G/01/x-site/icons/no-img-sm._CB192198896_.gif";
                }

                if($result['inventory']==''){
                    $result['inventory'] = 0;
                }
                // $query=$this->db->query("SELECT sum(quantity) as total_order_30 
                // FROM 30_days_order_list WHERE sku = '$result[seller_sku]' AND user_id ='$user_id'");
                // $result5 = $query->row();
                // $result['total_order_30'] = $result5->total_order_30;
               
                // $query=$this->db->query("SELECT available_inventory as inventory
                // FROM amzaon_inbound WHERE sku = '$result[seller_sku]' AND user_id ='$user_id'");
                
                
                // $query=$this->db->query("SELECT sum(inbound_inventory) as inbound
                // FROM amzaon_inbound WHERE sku = '$result[seller_sku]' AND user_id ='$user_id'");
                // $result6 = $query->row();
                // $result['inbound'] = $result6->inbound;
               
                // $created_at_minus_7day = date('Y-m-d',strtotime($result['updated_at'] . "-6 days"));
                // //die("SELECT sum(quantity) as total_order_7 FROM 30_days_order_list WHERE asin = '$result[asin1]' AND date(created_at)<='$result[created_at]'
                // //AND date(purchase_date) >= '$created_at_minus_7day' AND user_id = '$user_id'");
                // $query=$this->db->query("SELECT sum(quantity) as total_order_7 FROM 30_days_order_list WHERE sku = '$result[seller_sku]' AND updated_at<='$result[updated_at]'
                //  AND date(purchase_date) >= '$created_at_minus_7day' AND user_id = '$user_id'");
                // $result4 = $query->row();
                // $result['total_order_7'] = $result4->total_order_7;

               
                if ($result['total_order_30']!=0) {
                    $estimated_days_left = ( $result['inventory'] + $result['inbound'] - (($result['total_order_30'] / 30 ) * $result['lead_time'])) / ($result['total_order_30'] / 30 );
                }else{
                    $estimated_days_left = 0;  
                }

                if($estimated_days_left>20){
                    $background_color = 'green'; 
                }
                if(($estimated_days_left>5)&&($estimated_days_left<20)){
                    $background_color = 'yellow';
                }
                if($estimated_days_left<5){
                    $background_color = 'red';
                }
                
                if($result['lead_time']==''){
                    $estimated_days_left ='-';
                    $background_color = '#fff';
                }else{
                   $estimated_days_left = round($estimated_days_left);  
                }
                if($estimated_days_left>=0&&$estimated_days_left!='-'){
                    $replenish_date = date('d-m-Y',strtotime($result['updated_at'] . "+$estimated_days_left days"));
                    
                }else if($result['lead_time']==''){
                    $replenish_date = '-';
                }else{
                    $replenish_date = 'Overdue';
                }

                $result['replenish_date'] = $replenish_date;
                
                $result['estimated_days_left'] = $estimated_days_left;
                
                if($move == 1){
                    $result['background_color'] = $background_color;
                }else if($move ==0){
                    $result['move'] = 0;
                }
                if($result['lead_time']=='')
                    $results_new['without_lead_time'][]= $result;
                else{
                    $results_new['with_lead_time'][]= $result;
                }    
            }
                if(isset($results_new['with_lead_time'])){
                    $results_new['with_lead_time']= $this->msort($results_new['with_lead_time'],'estimated_days_left');
                }
                return $results_new;
     
                
         }


        public function msort($array, $key, $sort_flags = SORT_NUMERIC) {
            if (is_array($array) && count($array) > 0) {
                if (!empty($key)) {
                    $mapping = array();
                    foreach ($array as $k => $v) {
                        $sort_key = '';
                        if (!is_array($key)) {
                            $sort_key = $v[$key];
                        } else {
                            // @TODO This should be fixed, now it will be sorted as string
                            foreach ($key as $key_key) {
                                $sort_key .= $v[$key_key];
                            }
                            $sort_flags = SORT_NUMERIC;
                        }
                        $mapping[$k] = $sort_key;
                    }
                    asort($mapping, $sort_flags);
                    $sorted = array();
                    foreach ($mapping as $k => $v) {
                        $sorted[] = $array[$k];
                    }
                    return $sorted;
                }
            }
            return $array;
        }


        public function save_crediantial($data=array())
        {   

            $query0 = $this->db->select("*")
                          ->from("developer_detail")
                          ->get();
            $result0  = $query0->result_array();
            $data['developer_id'] = $result0[0]['developer_id'];
            $data['accesskeyid'] = $result0[0]['accesskeyid'];
            $data['merchant_id'] = $result0[0]['merchant_id'];
            $data['secret_key'] = $result0[0]['secret_key'];
            
            $query = $this->db->select("*")
                          ->from("user_crediantial")
                          ->where(['user_id' => $data['user_id']])
                          ->get();
            $result  = $query->row();
            if($result){
            $query = $this->db->where(['user_id'=>$data['user_id']])
                              ->update('user_crediantial',$data);
            return $query;
            }else{
            $query = $this->db->insert('user_crediantial',$data);
            return $query;
            }
                
        }

        public function getCrediantial($data=array())
        {       
            $query = $this->db->select("*")
                          ->from("user_crediantial")
                          ->where(['user_id' => $data['user_id']])
                          ->get();
            $result  = $query->row();

            return $result;    
                
        }

        public function num_rows($move,$sku){
            $user_id=$this->session->userdata('user_id');
            if($sku !=''){
                $where = array('move_data'=> $move,'sku'=>$sku,'user_id'=>$user_id);
            }else{
                $where = array('move_data'=> $move,'user_id'=>$user_id);
            }
            $user_id=$this->session->userdata('user_id');
            $query=$this->db->select('*')
                        ->from('amazon_mws')
                        ->where($where)
                        ->get();
            return $query->num_rows();
        }

        // public function deleteOldAmazonOrder($user_id){
        //     //$user_id=$this->session->userdata('user_id');
        //     $query=$this->db->delete('30_days_order_list',['user_id'=>$user_id]);
        //     return $query;
        // }

        // public function deleteOldAmazonData($user_id){
        //    // $user_id=$this->session->userdata('user_id');
        //     $query=$this->db->delete('amzaon_report',['user_id'=>$user_id]);
        //     return $query;
        // }

        public function save_data_from_user($sku,$data=array())
        {   

            return $this->db->where('sku',$sku)
                           ->update('amazon_mws',$data);
                
        }
        public function moveData($sku,$data=array())
        {   

            return $this->db->where('sku',$sku)
                           ->update('amazon_mws',$data);
                
        }

    
        public function moveDataToinventory($sku,$data=array())
        {   
            return $this->db->where('sku',$sku)
                            ->update('amazon_mws',$data);
        }

        // public function deleteAmazonInbound($user_id){
        //     //$user_id=$this->session->userdata('user_id');
        //     $query=$this->db->delete('amzaon_inbound',['user_id'=>$user_id]);
        //     return $query;
        // }
 


}


