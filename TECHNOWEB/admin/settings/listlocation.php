<?php
		check_message(); 
		?> 
		 
		<div class="row">
       	 <div class="col-lg-12">
            <h2 class="page-header">List of Locations  <a href="index.php?view=add" class="btn btn-primary btn-xs  ">  <i class="fa fa-plus-circle fw-fa"></i> New</a>  </h2>
       		</div>
        	<!-- /.col-lg-12 -->
   		 </div>
			    <form action="controller.php?action=delete" Method="POST">  	
			    <div class="table-responsive">				
				<table id="dash-table"  class="table table-striped table-bordered table-hover "  style="font-size:12px" cellspacing="0" >
					
				  <thead>
				  	<tr>  
				  		<th>Place</th> 
				  		<th>Delivery Fee</th>  
				  		<th style="text-align: center;">Action</th>
				  	</tr>	
				  </thead> 	

			  <tbody>
				  	<?php 
				  		$query = "SELECT * FROM `tblsetting` ";
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList();

						foreach ($cur as $result) { 
				  		echo '<tr>'; 
				    		
				  		echo '<td>'.$result->BRGY.' '.$result->PLACE.'  </a></td>';  ; 
				  		echo '<td> &#8369 '.  number_format($result->DELPRICE,2).'</td>'; 
				  		echo '<td align="center"><a title="Edit" href="index.php?view=edit&id='.$result->SETTINGID.'" class="btn btn-primary btn-xs  ">  <span class="fa fa-edit fw-fa"></a>
							<a title="Delete" href="controller.php?action=delete&id='.$result->SETTINGID.'" class="btn btn-danger btn-xs  ">  <span class="fa  fa-trash-o fw-fa "></a>
							</td>';
				  	} 
				  	?>
				  </tbody>
					
				 	
				</table>

				<!-- <div class="btn-group">
				  <a href="index.php?view=add" class="btn btn-default">New</a>
				  <button type="submit" class="btn btn-default" name="delete"><i class="fa fa-trash fw-fa"></i> Delete Selected</button>
				</div> -->
				</div>
				</form>
 
 