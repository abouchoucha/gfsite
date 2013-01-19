    <?php require_once 'seourlgen.php';
	  require_once 'urlGenHelper.php';  
	  $urlGenHelper = new UrlGenHelper();
	  $urlGen = new SeoUrlGen();
?> 	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalfacefb.css' rel="stylesheet" type="text/css" media="screen"/> 	
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.3.2.js" type="text/javascript"></script>
</head>
<body>

   <div class="fbcontainer">
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

	</div>

 </body>
</html>