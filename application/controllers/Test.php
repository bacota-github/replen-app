<?php

class Test extends CI_Controller
{

    public function __construct(){
        parent::__construct();
    
    }	
 
    public function index(){
      
        for($i=2;$i<=20;$i++){
            $k=1; $j=1;
            while($j<$i){
                echo $k.' ';
                $k =$k+2; $j++;
            }
            echo "<br>";
        }
        
    }
    
  
 
}