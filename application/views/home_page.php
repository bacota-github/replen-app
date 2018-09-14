<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>Codeignator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
	      <?php if($error=$this->session->flashdata('feedback')){?>
	      <h4 class="<?php echo $this->session->flashdata('feedback_class');?>"><?php echo $error;?></h4>
	      <?php } 

    if(validation_errors()){?>
        <h4 class="alert-danger"><?php echo validation_errors(); ?> </h4>
        <?php } ?>
        <a class="navbar-brand" href="#" style="text-align: left">Welcome! Mr. <?php echo $this->session->userdata('user_username'); ?></a>
     
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-left">
	        <li><a href="<?php echo base_url(index_page().'/userdetail/logOut');?>">LOGOUT</a></li>
	        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Setting</button>

         
        </ul>
    </div>
  </div>
</nav>

<!-- First Container -->
<div class="container-fluid bg-1 text-center">
	<?php  if(isset($AmazonDatainventory)){?> 
	   <form action="<?php echo base_url(index_page().'/userdetail/inventory');?>" method="post" role="form">
            <div class="row" style="padding-bottom: 25px;">
                <div class="col-sm-2">         
		            <input type="text" name="sku" id="sku" class="form-control" value="<?php echo $sku; ?>" required placeholder="Enter FNSKU">
		        </div>
		        <div class="col-sm-1">           
		            <input type="submit" name="submit" value="Search" class="btn btn-info btn-lg" value="Move" required>
		        </div>
		        
		    </div>        
                     
        </form>
    <?php } ?>    

    <?php if (isset($fetchCrediantial)) {
   
      $seller_id = $fetchCrediantial->seller_id;
      $marketplace_id = $fetchCrediantial->marketplace_id;
  
    ?>
    <ul class="nav nav-tabs">
      <?php if($move == 1){?>
      <li class="active"><a href="<?php echo base_url(index_page().'/userdetail/replens');?>">Replens</a></li>
      <li><a href="<?php echo base_url(index_page().'/userdetail/inventory');?>">Inventory</a></li>
      <?}else{?>
      <li><a href="<?php echo base_url(index_page().'/userdetail/replens');?>">Replens</a></li>
      <li class="active"><a href="<?php echo base_url(index_page().'/userdetail/inventory');?>">Inventory</a></li>
      <?php } ?>
    
    </ul>
    <div class="tab-content">
      <?php if($move == 1){?>
      <div id="replens" class="tab-pane fade in active">
      <?}else{?>
      <div id="replens" class="tab-pane fade in">
      <?php } 
    if(!empty($AmazonDatareplens)){   ?> 
        <table class="table table-bordered">
    <thead>
      <tr>
        <th class="table_th">Item No.</th>
        <th class="table_th">Name</th>
        <th class="table_th">Picture</th>
        <th class="table_th">FNSKU</th>
        <th class="table_th">ASIN</th>
        <th class="table_th">UPC</th>
        <th class="table_th">Status</th>
        <th class="table_th">Estimated days left</th>
        <th class="table_th">Orders last 30 days</th>
        <th class="table_th">Orders last 7 days</th>
        <th class="table_th">Inventory</th>
        <th class="table_th">Inbound</th>
        <th class="table_th">Lead Time</th>
        <th class="table_th">Replenish by date</th>
        <th class="table_th">Supplier Name</th>
        <th class="table_th">Supplier Phone</th>
        <th class="table_th">Supplier Website</th>
        <th class="table_th">Supplier Email</th>
        <th class="table_th">Action</th>
      </tr>
    </thead>
     <tbody>
       <?php 
           $i = $item_no; 
        if(isset($AmazonDatareplens['with_lead_time'])){   
	       foreach ($AmazonDatareplens['with_lead_time'] as $AmazonDatareplens_with_lead_time) { 
	           
	        ?>
	      <tr>
	        <th><?php echo $i;?></th>
	        <td><?php echo $AmazonDatareplens_with_lead_time['item_name'];?></td>   
	        <td><img src="<?php echo $AmazonDatareplens_with_lead_time['image_url'];?>" style="width: 60px;height: 60px;" alt="Image" /></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['sku'];?></td>  
	        <td><?php echo $AmazonDatareplens_with_lead_time['asin'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['upc'];?></td> 
	        <?php if($AmazonDatareplens_with_lead_time['background_color']=='#fff'){?>
	        <td>-</td>
	        <?php }else{?> 
	        <td><div class="replen-status" style="background-color: <?php echo $AmazonDatareplens_with_lead_time['background_color'];?>;color: <?php echo $AmazonDatareplens_with_lead_time['background_color'];?>">dhfghfg</div ></td>
	        <?php } ?> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['estimated_days_left'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['total_order_30'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['total_order_7'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['inventory'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['inbound'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['lead_time'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['replenish_date'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['supplier_name'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['supplier_number'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['supplier_website'];?></td> 
	        <td><?php echo $AmazonDatareplens_with_lead_time['supplier_email'];?></td> 
	        <td><button type="button" class="btn btn-info btn-lg openmodal" data-toggle="modal" data-target="#myModalEdit" style="padding: 10px" data-seller-sku = "<?php echo $AmazonDatareplens_with_lead_time['sku'];?>" data-upc = "<?php echo $AmazonDatareplens_with_lead_time['upc'];?>" data-lead-time = "<?php echo $AmazonDatareplens_with_lead_time['lead_time'];?>" data-supplier-name = "<?php echo $AmazonDatareplens_with_lead_time['supplier_name'];?>" data-supplier-number = "<?php echo $AmazonDatareplens_with_lead_time['supplier_number'];?>" data-supplier-website = "<?php echo $AmazonDatareplens_with_lead_time['supplier_website'];?>" data-supplier-email = "<?php echo $AmazonDatareplens_with_lead_time['supplier_email'];?>">Edit
	        </button>
	        <a href='<?php echo base_url(index_page()."/userdetail/move_dataToinventory?sku=$AmazonDatareplens_with_lead_time[sku]");?>' class="btn btn-info btn-lg">Remove</a></td>
	        
	      </tr>
	      <?php 
	      $i++;  }
	    }  
        if(isset($AmazonDatareplens['without_lead_time'])){
	       foreach ($AmazonDatareplens['without_lead_time'] as $AmazonDatareplens_without_lead_time) { 
	          
	        ?>
	      <tr>
	        <th><?php echo $i;?></th>
	        <td><?php echo $AmazonDatareplens_without_lead_time['item_name'];?></td>   
	        <td><img src="<?php echo $AmazonDatareplens_without_lead_time['image_url'];?>" style="width: 60px;height: 60px;" alt="Image" /></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['sku'];?></td>  
	        <td><?php echo $AmazonDatareplens_without_lead_time['asin'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['upc'];?></td> 
	        <?php if($AmazonDatareplens_without_lead_time['background_color']=='#fff'){?>
	        <td>-</td>
	        <?php }else{?> 
	        <td><div class="replen-status" style="background-color: <?php echo $AmazonDatareplens_without_lead_time['background_color'];?>;color: <?php echo $AmazonDatareplens_without_lead_time['background_color'];?>">dhfghfg</div ></td>
	        <?php } ?> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['estimated_days_left'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['total_order_30'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['total_order_7'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['inventory'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['inbound'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['lead_time'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['replenish_date'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['supplier_name'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['supplier_number'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['supplier_website'];?></td> 
	        <td><?php echo $AmazonDatareplens_without_lead_time['supplier_email'];?></td> 
	        <td><button type="button" class="btn btn-info btn-lg openmodal" data-toggle="modal" data-target="#myModalEdit" style="padding: 10px" data-seller-sku = "<?php echo $AmazonDatareplens_without_lead_time['sku'];?>" data-upc = "<?php echo $AmazonDatareplens_without_lead_time['upc'];?>" data-lead-time = "<?php echo $AmazonDatareplens_without_lead_time['lead_time'];?>" data-supplier-name = "<?php echo $AmazonDatareplens_without_lead_time['supplier_name'];?>" data-supplier-number = "<?php echo $AmazonDatareplens_without_lead_time['supplier_number'];?>" data-supplier-website = "<?php echo $AmazonDatareplens_without_lead_time['supplier_website'];?>" data-supplier-email = "<?php echo $AmazonDatareplens_without_lead_time['supplier_email'];?>">Edit
	        </button>
	        <a href='<?php echo base_url(index_page()."/userdetail/move_dataToinventory?sku=$AmazonDatareplens_without_lead_time[sku]");?>' class="btn btn-info btn-lg">Remove</a></td>
	        
	      </tr>
	      <?php 
	      $i++; 
	        }
	    }    
      
        ?>  


    </tbody>
    </table>
     <?php echo $this->pagination->create_links(); 
    }else{?>
    	<h4 style="font-weight: 600">There is no data to display.Need to move data from inventory!</h4> 	
    <?php
    }
     ?>
      </div>

      <?php if($move == 0){?>
      <div id="inventory" class="tab-pane fade in active">
      <?}else{?>
      <div id="inventory" class="tab-pane fade in">
      <?php } 
      if(!empty($AmazonDatainventory)){?> 
     
    <table class="table table-bordered">
    <thead>
      <tr>
        <th class="table_th">Item No.</th>
        <th class="table_th">Name</th>
        <th class="table_th">Picture</th>
        <th class="table_th">FNSKU</th>
        <th class="table_th">ASIN</th>
        <th class="table_th">UPC</th>
        <th class="table_th">Move To Replens</th>
        <th class="table_th">Estimated days left</th>
        <th class="table_th">Orders last 30 days</th>
        <th class="table_th">Orders last 7 days</th>
        <th class="table_th">Inventory</th>
        <th class="table_th">Inbound</th>
        <th class="table_th">Lead Time</th>
        <th class="table_th">Replenish by date</th>
        <th class="table_th">Supplier Name</th>
        <th class="table_th">Supplier Phone</th>
        <th class="table_th">Supplier Website</th>
        <th class="table_th">Supplier Email</th>
      </tr>
    </thead>
    <tbody>
       <?php 
           $i = $item_no; 
        if(isset($AmazonDatainventory['with_lead_time'])){
	       foreach ($AmazonDatainventory['with_lead_time'] as $AmazonDatainventory_with_lead) {
	        ?>
	      <tr>
	        <th><?php echo $i;?></th>
	        <td><?php echo $AmazonDatainventory_with_lead['item_name'];?></td>   
	        <td><img src="<?php echo $AmazonDatainventory_with_lead['image_url'];?>" style="width: 60px;height: 60px;" alt="Image" /></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['sku'];?></td>  
	        <td><?php echo $AmazonDatainventory_with_lead['asin'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['upc'];?></td> 
	        <td>
	       <form action="<?php echo base_url(index_page().'/userdetail/move_data');?>" method="post" role="form">
	                  
	                <input type="hidden" name="sku" id="sku" tabindex="1" class="form-control" value="<?php echo $AmazonDatainventory_with_lead['sku'];?>" required>
	                  
	                <input type="submit" name="submit"  tabindex="4" class="btn btn-info btn-lg" value="Move" required>
	                     
	        </form>
	       </td>
	        <td><?php echo $AmazonDatainventory_with_lead['estimated_days_left'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['total_order_30'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['total_order_7'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['inventory'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['inbound'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['lead_time'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['replenish_date'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['supplier_name'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['supplier_number'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['supplier_website'];?></td> 
	        <td><?php echo $AmazonDatainventory_with_lead['supplier_email'];?></td> 
	        
	      </tr>
	      <?php 
	      $i++; 
	       }

	    } 

	    if(isset($AmazonDatainventory['without_lead_time'])){  
       
	       foreach ($AmazonDatainventory['without_lead_time'] as $AmazonDatainventory_without_lead) {
	        ?>
	      <tr>
	        <th><?php echo $i;?></th>
	        <td><?php echo $AmazonDatainventory_without_lead['item_name'];?></td>   
	        <td><img src="<?php echo $AmazonDatainventory_without_lead['image_url'];?>" style="width: 60px;height: 60px;" alt="Image" /></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['sku'];?></td>  
	        <td><?php echo $AmazonDatainventory_without_lead['asin'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['upc'];?></td> 
	        <td>
	       <form action="<?php echo base_url(index_page().'/userdetail/move_data');?>" method="post" role="form">
	                  
	                <input type="hidden" name="sku" id="sku" tabindex="1" class="form-control" value="<?php echo $AmazonDatainventory_without_lead['sku'];?>" required>
	                  
	                <input type="submit" name="submit"  tabindex="4" class="btn btn-info btn-lg" value="Move" required>
	                     
	        </form>
	       </td>
	        <td><?php echo $AmazonDatainventory_without_lead['estimated_days_left'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['total_order_30'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['total_order_7'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['inventory'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['inbound'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['lead_time'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['replenish_date'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['supplier_name'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['supplier_number'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['supplier_website'];?></td> 
	        <td><?php echo $AmazonDatainventory_without_lead['supplier_email'];?></td> 
	        
	      </tr>
	      <?php 
	      $i++; 
	        }
	    }    

        ?>
    </tbody>
  </table>
  <?php echo $this->pagination->create_links(); 
    }else{?>
    	<h4 style="font-weight: 600">There is no data to display!</h4> 
    <?php  	
    } 		
   
    ?>
  <?php }else{
      $seller_id = '';
      $marketplace_id = '';
      
    
    ?>
    <h4>Please Submit Crediantial</h4>
    <?php } ?>

  </div>
 </div>

</div>

<!-- Second Container 
<div class="container-fluid bg-2 text-center">
  
</div>
-->

<!-- Footer -->
<footer class="container-fluid bg-4 text-center">
  <p><a href="<?php echo base_url(index_page().'/Userdetail/home');?>" class="replen-footor">Replen</a></p> 
</footer>


<div class="modal fade" id="myModal" role="dialog" style="color:#000">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align:center">Send Credentials To Developer</h4>
         </div>
         <div class="modal-body">
            <form id="register-form" action="<?php echo base_url(index_page().'/userdetail/save_crediantial');?>" method="post" role="form">
               <div class="form-group">
                  <label for="seller_id" class="form-group">Seller Id</label>
                  <input type="text" name="seller_id" id="seller_id" tabindex="1" class="form-control" placeholder="Seller ID" value="<?php echo $seller_id;?>" required>
               </div>
               <!-- <div class="form-group">
                  <label for="auth_token" class="form-group">Auth Token</label>
                  <input type="text" name="auth_token" id="auth_token" tabindex="1" class="form-control" placeholder="Auth Token" value="<?php echo $auth_token;?>" required>
               </div> -->
               <div class="form-group">
                  <label for="auth_token" class="form-group">Marketplace Id</label>
                  <input type="text" name="marketplace_id" id="marketplace_id" tabindex="1" class="form-control" placeholder="Marketplace ID" value="<?php echo $marketplace_id;?>" required>
               </div>
               <!-- <div class="form-group">
                  <input type="text" name="secret_key" id="secret_key" tabindex="1" class="form-control" placeholder="Secret Key" value="<?php echo $secret_key;?>" required>
               </div>
               <div class="form-group">
                  <input type="text" name="accesskeyid" id="accesskeyid" tabindex="1" class="form-control" placeholder="Accesskey Id" value="<?php echo $accesskeyid;?>" required>
               </div> -->
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6 col-sm-offset-3">
                        <input type="submit" name="submit" id="submit" tabindex="4" class="btn btn-info btn-lg" value="Submit" required>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!-- edit detail modal-->
<div class="modal fade" id="myModalEdit" role="dialog" style="color:#000">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align:center">Fill All Detail</h4>
         </div>
         <div class="modal-body">
            <form method="post" action="<?php echo base_url(index_page().'/userdetail/save_data_byUser');?>">
               <input type="hidden" name="sku" id="sku" />
               <div class="form-group">
                  <label for="upc" class="form-group">UPC</label>
                  <input type="text" name="upc" id="upc" tabindex="1" class="form-control" placeholder="UPC" required />
               </div>
               <div class="form-group">
                  <label for="lead_time" class="form-group">Lead Time</label>
                  <input type="number" name="lead_time" id="lead_time" tabindex="1" class="form-control" placeholder="Lead Time" required />
               </div>
               <div class="form-group">
                 <label for="supplier_name" class="form-group">Supplier Name</label>
                  <input type="text" name="supplier_name" id="supplier_name" tabindex="1" class="form-control" placeholder="Supplier Name" />
               </div>
               <div class="form-group">
                  <label for="supplier_number" class="form-group">Supplier Number</label>
                  <input type="text" name="supplier_number" id="supplier_number" tabindex="1" class="form-control" placeholder="Supplier Number" />
               </div>
               <div class="form-group">
                  <label for="supplier_website" class="form-group">Supplier Website</label>
                  <input type="text" name="supplier_website" id="supplier_website" tabindex="1" class="form-control" placeholder="Supplier Website" />
               </div>
               <div class="form-group">
                  <label for="supplier_email" class="form-group">Supplier Email</label>
                  <input type="text" name="supplier_email" id="supplier_email" tabindex="1" class="form-control" placeholder="Supplier Email" />
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-sm-6 col-sm-offset-3">
                        <input type="submit" name="submit" id="submit" tabindex="4" class="btn btn-info btn-lg" value="Submit" required>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

</body>

 <script type="text/javascript">
        $(document).ready(function(){

          $('.openmodal').click(function() {
            var sku = $(this).attr('data-seller-sku');    
            $('#sku').val(sku);
            $('#upc').val($(this).attr('data-upc'));
            $('#lead_time').val($(this).attr('data-lead-time'));
            $('#supplier_email').val($(this).attr('data-supplier-email'));
            $('#supplier_website').val($(this).attr('data-supplier-website'));
            $('#supplier_number').val($(this).attr('data-supplier-number'));
            $('#supplier_name').val($(this).attr('data-supplier-name'));
           // console.log($(this).attr('data-upc'));
          });
            
        });


        // $(document).ready(function(){
        //    $(".nav-tabs a").click(function(){
        //      var table_type = $(this).attr('id');
            
        //      $(this).tab('show');
        //    });
        // });  

           
</script>

</html>
