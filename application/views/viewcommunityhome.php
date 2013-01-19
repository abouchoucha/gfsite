<?php 
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
	$config = Zend_Registry::get ( 'config' );
?>
<ul id="TalkingPoints">

      <?php 
      $tempDate = '1234'; 
      $today = date ( "m-d-Y" ) ;  
      $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;               	
      if ($this->activities != null ) {
	      foreach ($this->activities as $activity) {
	      	 if ( $activity['activity_player_id'] > 1) {  ?>
	      	 	<?php
                $path_player_photos = $config->path->images->players . $activity['activity_player_id'] .".jpg" ;
                if (file_exists($path_player_photos)) { ?>
             		<li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=50&h=50&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $activity['activity_player_id']; ?>.jpg');">
             		
            	<?php  } else { ?>	
            		<li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $activity['activity_icon']; ?>');">
            	<?php  } ?>
				
			 <?php  } else { ?>
			 <?php if($activity['activity_icon'] == 'y'){?>
			      	 <li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=50&h=50&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?>
			      	 		<?php echo $this->root_crop;?>/photos/<?php echo $activity["activitytype_name"]; ?>');">
					<?php }else { ?>
					 <li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $activity['activity_icon']; ?>');">
					<?php }?>
			 <?php  } ?>
	          <strong>
	          <?php if($activity['activity_icon'] == 'y'){?>
	          	Broadcast
	          <?php }else { ?>
                <?php echo $activity['activitytype_name']; ?>
               <?php }?> 
               </strong>
	             <br />
	              <a href="<?php echo $urlGen->getUserProfilePage($activity["screen_name"],True);?>">
	             		<?php echo $activity['screen_name'];?>
	             	</a> <?php echo $activity['activity_text']; ?>
	             <br />
	           
	          <div class="TimeSetting">
                <?php 
                  if($today == date('m-d-Y' , strtotime($activity["activity_date"]))){
                  	echo 'Today at ' . date("H:i" , strtotime($activity["activity_date"])) ;
                  }else if($yesterday == date('m-d-Y' , strtotime($activity["activity_date"]))){ 
                  	echo 'Yesterday at ' . date("H:i" , strtotime($activity["activity_date"])) ;
                  }else {
                  	echo date(' F j' , strtotime($activity["activity_date"])) ;	
                }?>			
            </div>                          
	                                    
	       </li>                          
		<?php
			$tempDate =  date('m-d-Y' , strtotime($activity["activity_date"])); 
		} 
	}else {
		echo 'There are no activities at this time';
		
	}	
		?>
  </ul>   
