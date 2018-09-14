<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tablescript extends CI_Model {

  
        // public function insertAmazonData($user_id,$data=array())
        // {        
            
        //     //$data['user_id'] = $this->session->userdata('user_id');
           

        //     $data0 = addslashes($data[0]);
        //     $data1 = addslashes($data[1]);
        //     $asin1 = $data[16];
        //     $listing_id = $data[2];

        //     $query=$this->db->select('*')
        //     ->from('amzaon_report')
        //     ->where(['user_id'=> $user_id,'asin1'=>$asin1,'listing_id'=> $listing_id])
        //     ->get();
        //     $check = $query->num_rows();

        //     if($check > 0){
        //         $query= $this->db->query("UPDATE amzaon_report SET item_name = '$data0',item_description = '$data1',
        //         price = '$data[4]',open_date = '$data[6]',product_id = '$data[22]',fulfillment_channel = '$data[26]',
        //         merchant_shipping_group = '$data[27]',status = '$data[28]',seller_sku = '$data[3]',updated_at = NOW()
        //          WHERE user_id = '$user_id' AND asin1 = '$asin1' AND listing_id = '$data[2]'");
                
        //     }else{
        //         $query= $this->db->query("INSERT INTO amzaon_report(user_id,item_name,item_description,price,
        //         open_date,asin1,product_id,fulfillment_channel,merchant_shipping_group,status,
        //         seller_sku,listing_id,updated_at) VALUES ('$user_id','$data0','$data1',
        //         '$data[4]','$data[6]','$data[16]','$data[22]','$data[26]',
        //         '$data[27]','$data[28]','$data[3]','$data[2]',NOW())");
               
        //     } 
            
               
        // }

        public function insertAmazonInbound($user_id,$data=array())
        {        
            
           // $data['user_id'] = $this->session->userdata('user_id');
           
            $data0 = addslashes($data[0]);
            $asin = $data[2];
            $sku = $data[1];
            $query=$this->db->select('*')
            ->from('amazon_mws')
            ->where(['user_id'=> $user_id,'asin'=>$asin,'sku'=>$sku])
            ->get();
            $check = $query->num_rows();

            if($check > 0){
                $query= $this->db->query("UPDATE amazon_mws SET item_name = '$data0'
                ,p_condition = '$data[3]',supplier = '$data[4]',price = '$data[5]',
                sales_last_30_days = '$data[6]',unit_last_30_days = '$data[7]',
                available_inventory = '$data[10]',total_inventory = '$data[8]',
                inbound_inventory = '$data[9]',updated_at = NOW() WHERE user_id = '$user_id' AND asin = '$asin' AND sku = '$sku'");
                
            }else{
                $query= $this->db->query("INSERT INTO amazon_mws(user_id,item_name, sku, asin,p_condition
                ,supplier, price, sales_last_30_days,unit_last_30_days, total_inventory,
                inbound_inventory,fulfilled_by,available_inventory,updated_at) VALUES ('$user_id','$data0','$data[1]','$data[2]','$data[3]',
                '$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[15]','$data[10]',NOW())");
                
            } 
            
            
           
               
        }

        public function updateAmazonInbound($user_id,$sku,$order_7,$order_30)
        {        
            
           
            $query= $this->db->query("UPDATE amazon_mws SET unit_last_7_days = '$order_7'
             WHERE user_id = '$user_id' AND sku = '$sku'");
     
               
        }
        

        // public function insertAmazonOrder($user_id,$data=array())
        // {        
            
        //     //$data['user_id'] = $this->session->userdata('user_id');
                
        //         $data10 = addslashes($data[10]);

        //         $asin = $data[12];
        //         $amazon_order_id = $data[1];
        //         $query=$this->db->select('*')
        //         ->from('30_days_order_list')
        //         ->where(['user_id'=> $user_id,'asin'=>$asin,'amazon_order_id' => $amazon_order_id])
        //         ->get();
        //         $check = $query->num_rows();

        //         if($check > 0){
        //             $query= $this->db->query("UPDATE 30_days_order_list SET quantity = '$data[14]',
        //             sku = '$data[11]',updated_at = NOW()
        //             WHERE user_id = '$user_id' AND asin = '$asin' AND amazon_order_id = '$amazon_order_id'");
                    
        //         }else{
        //             $query= $this->db->query("INSERT INTO `30_days_order_list`(`user_id`,`amazon_order_id`, `merchant_order_id`,
        //          `purchase_date`, `last_updated_date`, `order_status`, `fulfillment_channel`, `sales_channel`, 
        //          `order-channel`, `url`, `ship_service_level`, `product_name`, `sku`, `asin`, `item_status`, 
        //          `quantity`, `currency`, `item_price`, `item_tax`, `shipping_price`, `shipping_tax`, 
        //          `gift_wrap_price`, `gift_wrap_tax`, `item_promotion_discount`, `ship_promotion_discount`, 
        //          `ship_city`, `ship_state`, `ship_postal_code`, `ship_country`, `promotion_ids`, 
        //          `is_business_order`, `purchase_order_number`, `price_designation`, `customized_url`, 
        //          `customized_page`,`updated_at`) VALUES ('$user_id','$data[0]','$data[1]','$data[2]','$data[3]','$data[4]',
        //          '$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data10','$data[11]','$data[12]',
        //          '$data[13]','$data[14]','$data[15]','$data[16]','$data[17]','$data[18]','$data[19]','$data[20]',
        //          '$data[21]','$data[22]','$data[23]','$data[24]','$data[25]','$data[26]','$data[27]','$data[28]',
        //          '$data[29]','$data[30]','$data[31]','$data[32]','$data[33]',NOW())");
                    
        //         }
                
           
               
        // }

        public function getCrediantial()
        {       
            $query = $this->db->query("SELECT * FROM user_crediantial");
            $result  = $query->result_array();
            return $result;    
                
        }

        
        // public function deleteOldAmazonOrder(){
        //     //$user_id=$this->session->userdata('user_id');
        //     // $query=$this->db->where('user_id', $user_id) 
        //     //                 ->delete('30_days_order_list');
        //     // return $query;
        //     $query=$this->db->truncate('30_days_order_list');
        //     return $query;
        // }

        // public function deleteOldAmazonData(){
    
        //     $query=$this->db->truncate('amzaon_report');
        //     return $query;
        // }

    

        // public function deleteAmazonInbound(){
        //     //$user_id=$this->session->userdata('user_id');
        //     // $query=$this->db->where('user_id', $user_id) 
        //     //                 ->delete('amzaon_inbound');
        //     // return $query;
        //     $query=$this->db->truncate('amzaon_inbound');
        //     return $query;
        // }

        public function num_rows($user_id){

            $query=$this->db->select('*')
                        ->from('amazon_mws')
                        ->where(['user_id'=> $user_id])
                        ->get();
            return $query->num_rows();
        }

        public function saveImage($user_id,$asin,$imageUrl){

            $query=$this->db->query("UPDATE amazon_mws SET image_url = '$imageUrl' WHERE asin = '$asin' AND user_id = '$user_id'");
            return $query;
        }
        

       



}	