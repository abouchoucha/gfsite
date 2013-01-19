<!-- Competition Table -->
    <?php if ($this->compFormat == 'Domestic league') { ?>
		<div id="leaguetables">
           <?php if ($this->leagueTable != null) { ?>   

				<?php if ($this->gs_table == null){ ?> 
				 <center><strong>No Data available.</strong></center>
            	<?php } else { ?>
            	<!-- Goalserve Competition Table -->
            	<?php include 'include/leaguetableview.php';?>
                    
              <?php } ?>
                    
             	 <?php } else { ?>
             	   <center><strong>No Data available.</strong></center>
             	 <?php } ?>
            </div>  

       <?php } else { ?>  
       <div class="mmid"> 	
       		<div class="march">
           		<?php if($this->hasgroups == 1) { ?>
             			<!-- Goalserve International Competition Table with groups -->
		                 <?php include 'include/leaguegrouptableview.php';?>
		                    
             		
             		<?php } else { ?>
             			<!-- Goalserve International Competition Table NO groups -->
		                <?php include 'include/leaguegrouptableview.php';?>
             
             		<?php }  ?>
            </div>
       </div>
  <?php }  ?> 