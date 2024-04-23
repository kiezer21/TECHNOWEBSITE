	
<?php
require_once ("../include/initialize.php");
  if (!isset($_SESSION['CUSID'])){
      redirect("index.php");
     }



	
// if (isset($_POST['id'])){

// if ($_POST['actions']=='confirm') {
// 							# code...
// 	$status	= 'Confirmed';	
// 	// $remarks ='Your order has been confirmed. The ordered products will be yours anytime.';
	 
// }elseif ($_POST['actions']=='cancel'){
// 	// $order = New Order();
// 	$status	= 'Cancelled';
// 	// $remarks ='Your order has been cancelled due to lack of communication and incomplete information.';
// }

// $summary = New Summary();
// $summary->ORDEREDSTATS     = $status;
// $summary->update($_POST['id']);


// }

if(isset($_POST['close'])){
	unset($_SESSION['ordernumber']);
	redirect(web_root.'index.php'); 
}

if (isset($_POST['ordernumber'])){
	$_SESSION['ordernumber'] = $_POST['ordernumber'];
}

// unsetting notify msg
$summary = New Summary();
$summary->HVIEW = 1;
$summary->update($_SESSION['ordernumber']);  

// end


$query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
		WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM='".$_SESSION['ordernumber']."'";
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

// $query = "SELECT * FROM tblusers
// 				WHERE   `USERID`='".$_SESSION['cus_id']."'";
// 		$mydb->setQuery($query);
// 		$row = $mydb->loadSingleResult();
?>
 

<div class="modal-dialog" style="width:60%">
  <div class="modal-content">
	<div class="modal-header">
		<button class="close" id="btnclose" data-dismiss="modal" type= "button">×</button>
		 <span id="printout">
 
		<table>
			<tr>
				<td align="center"> 
				<img src="<?php echo web_root; ?>images/home/homelogo.jpg"   alt="Image">
        		</td> 
			</tr>
		</table>
		<!-- <h2 class="modal-title" id="myModalLabel">Billing Details </h2> -->
		
		 
 	 <div class="modal-body"> 
<?php 
	 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if($cur->ORDEREDSTATS=='Confirmed'){

		
		if ($cur->PAYMENTMETHOD=="Cash on Pickup") {
			 
		
?>
 	 <h4>Your order has been confirmed and ready for Pick Up</h4><br/>
		<h5>Dear Ma'am/Sir ,</h5>
		<h5>As you have ordered cash on pick up, please prepare the exact amount of cash to pay.</h5>
		 <hr/>
		 <h4><strong>Pick up Information</strong></h4>
		 <div class="row">
		 	<!-- <div class="col-md-6">
		 		<p> ORDER NUMBER : <?php echo $_SESSION['ordernumber']; ?></p>
		 		<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Items to be pickup : <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 
		 	</div> -->
		 	<div class="col-md-6">
		 	<p>Name : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<p>Address : <?php echo $cur->CUSHOMENUM . ' ' . $cur->STREETADD . ' ' .$cur->BRGYADD . ' ' . $cur->CITYADD . ' ' .$cur->PROVINCE . ' ' .$cur->COUNTRY; ?></p>
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}elseif ($cur->PAYMENTMETHOD=="Cash on Delivery"){
		 
?>
 	 <h4>Your order has been confirmed and on the way to deliver</h4><br/>
		 <hr/>
		 <h4><strong>Delivery Information</strong></h4>
		 <div class="row">
		 	<div class="col-md-6">
		 		<p> ORDER NUMBER : <?php echo $_SESSION['ordernumber']; ?></p>

		 			<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Items to be delivered : <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 

		 	</div>
		 	<div class="col-md-6">
		 	<p>Name : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<!-- <p>Address : <?php echo $cur->ADDRESS;?></p> -->
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}
}elseif($cur->ORDEREDSTATS=='Cancelled'){

	 echo "Your order has been cancelled due to many reasons. Please contact us";

}else{
	echo "<h5>Your order is on process. Please check time by time for confirmation.</h5>";
} 
?>
<hr/>
 <h4><strong>Order Information</strong></h4>
		<table id="table" class="table">
			<thead>
				<tr>
					<th>PRODUCT IMG</th>
					<th>PRODUCT</th>
					<!-- <th>DATE ORDER</th>  -->
					<th>PRICE</th>
					<th>QUANTITY</th>
					<th></th> 
				</tr>
				</thead>
				<tbody>
 
				<?php
				 $subtot=0;
				  $query = "SELECT * 
							FROM  `tblproduct` p, tblcategory ct,  `tblcustomer` c,  `tblorder` o,  `tblsummary` s, `tblpromopro` pr
							WHERE p.`CATEGID` = ct.`CATEGID` 
							AND pr.`PROID`=p.`PROID`
							AND p.`PROID` = o.`PROID` 
							AND o.`ORDEREDNUM` = s.`ORDEREDNUM` 
							AND s.`CUSTOMERID` = c.`CUSTOMERID` 
							AND o.`ORDEREDNUM`=".$_SESSION['ordernumber'];
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList(); 
						foreach ($cur as $result) {
						echo '<tr>';  
						echo '<td><img src="' . web_root . '/admin/products/' . $result->IMAGES . '" width="100px" height="100px" title="' . $result->IMAGES . '"/></td>';
						// echo '<td>' . $result->PRODUCTNAME.'</td>';
				  		// echo '<td>'. $result->FIRSTNAME.' '. $result->LASTNAME.'</td>';
				  		echo '<td>'. $result->PRODESC.'</td>';
				  		// echo '<td>'.date_format(date_create($result->ORDEREDDATE),"M/d/Y h:i:s").'</td>';
				  		echo '<td> &#8369 '. number_format($result->PROPRICE,2).' </td>';
				  		echo '<td align="center" >'. $result->ORDEREDQTY.'</td>';
				  		?> 
				  		<?php
				  		// echo '<td id="status" >'. $result->STATS.'</td>';
				  		echo '<td><a  href="#"  data-id="'.$result->ORDERID.'"  class="cancel btn btn-danger btn-xs">Cancel</a>
				  			</td>';
				  		echo '</tr>';

				  		$subtot +=$result->ORDEREDPRICE;
				 
				}
				?> 
			</tbody>
		<tfoot >
		<?php 
				 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if ($cur->PAYMENTMETHOD=="Cash on Delivery") {
			# code...
			$price = $cur->DELFEE;
		}else{
			$price = 0.00;
		}


		// $tot =   $cur->PAYMENT  + $price;
		?>

	 </tfoot>
       </table> <hr/>
 		<div class="row">
		  	<div class="col-md-6 pull-left">
		  	 <div>Ordered Date : <?php echo date_format(date_create($cur->ORDEREDDATE),"M/d/Y h:i:s"); ?></div> 
		  		<div>Payment Method : <?php echo $cur->PAYMENTMETHOD; ?></div>

		  	</div>
		  	<div class="col-md-6 pull-right">
			  <p align="right">Total Price : <b>&#8369 <?php echo number_format($subtot,2); ?></b></p>
		  	</div>
		  </div>
		 
		  <?php
		  if($cur->ORDEREDSTATS=="Confirmed"){
		  ?>
		   <hr/> 
		  <div class="row">
		  	  <p>We hope you enjoy your purchased products or services. Have a nice day!</p>
		  	  <p>Sincerely,</p>
		  	  <h4>KGB Shop</h4>
		  </div>
		  <?php }?>
  </div> 

</span>

		<div class="modal-footer">
		 <div id="divButtons" name="divButtons">
		<?php if($cur->ORDEREDSTATS!='Pending' || $cur->ORDEREDSTATS!='Cancelled' ){ ?> 
     
                <button  onclick="tablePrint();" class="btn btn_fixnmix pull-right "><span class="glyphicon glyphicon-print" ></span> Print</button>     
             
        <?php } ?>
			<button class="btn btn-pup" id="btnclose" data-dismiss="modal" type=
			"button">Close</button> 
		 </div> 
		<!-- <button class="btn btn-primary"
			name="savephoto" type="submit">Upload Photo</button> -->
		</div>
	<!-- </form> -->
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
 </div>
  <script>
function tablePrint(){ 
 // document.all.divButtons.style.visibility = 'hidden';  
    var display_setting="toolbar=no,location=no,directories=no,menubar=no,";  
    display_setting+="scrollbars=no,width=500, height=500, left=100, top=25";  
    var content_innerhtml = document.getElementById("printout").innerHTML;  
    var document_print=window.open("","",display_setting);  
    document_print.document.open();  
    document_print.document.write('<body style="font-family:verdana; font-size:12px;" onLoad="self.print();self.close();" >');  
    document_print.document.write(content_innerhtml);  
    document_print.document.write('</body></html>');  
    document_print.print();  
    document_print.document.close(); 
     // document.all.divButtons.style.visibility = 'Show';  
   
    return false; 

    } 
 
</script>