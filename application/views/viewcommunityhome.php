<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php'; 
    require_once 'Matchh.php';
    $urlGen = new SeoUrlGen();
	$config = Zend_Registry::get ( 'config' );
    $common = new Common();
    $match = new Matchh();
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
                if (file_exists($path_player_photos)) {  
                	//$stImage = 'http://' . $config->path->index->server->name . "/public/images/players/". $activity['activity_player_id'] . ".jpg";
                	?>

             		<li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=50&h=50&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $activity['activity_player_id']; ?>.jpg');">
             		
            	<?php  } else { 
            		//$stImage = 'http://' . $config->path->index->server->name . "/public/images/" . $activity['activity_icon'];
            		?>	
            		<li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $activity['activity_icon']; ?>');">
            	<?php  } ?>
				
			 <?php  } else { ?>
			 <?php if($activity['activity_icon'] == 'y'){?>
			      	 <li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=50&h=50&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?>
			      	 		<?php echo $this->root_crop;?>/photos/<?php echo $activity["activitytype_name"]; ?>');">
					<?php 
							//$stImage = 'http://' . $config->path->index->server->name . "/public/images/" . $activity["activitytype_name"];
						} else { ?>
					 <li style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $activity['activity_icon']; ?>');">
					<?php  
						//$stImage = 'http://' . $config->path->index->server->name . "/public/images/" . $activity['activity_icon'];
					}?>
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
              <?php 
              	$stTitle = strip_tags($activity['activity_text']);
              	$stURL = 'http://' . $config->path->index->server->name . '/community/showuniqueactivity/activityId/' . $activity["activity_id"];
                $social_icons_images = 'http://' . $config->path->index->server->name ."/public/images/icons/social/";
                
            	//If activity type is not 3 (players) so it is leagues / teams 
                if ($activity['activitytype_category_id'] != 3) { 
                	$matchrow = $match->findMatchById($activity["activity_match_id"]);
                   	$stImage = 'http://' . $config->path->index->server->name . "/public/images/competitionlogos/" .$matchrow[0]['competition_id']. ".gif";
                } else {
                	if (file_exists($path_player_photos)) { 
                   		$stImage = 'http://' . $config->path->index->server->name . "/public/images/players/". $activity['activity_player_id'] . ".jpg";
                	} else {
                		$stImage = 'http://' . $config->path->index->server->name . "/public/images/" . $activity['activity_icon']; 
                	}
                }

                 $args = array(
                  'image_size' => 16
                 ,'image_path' => $social_icons_images
                 ,'separator'  => ' '
                 ,'url' => $stURL
                 ,'title' => $stTitle 
                 ,'media_url' => $stImage
                 ,'twitter_username' => 'goalface'
                 ,'platforms' => array('facebook','twitter')
                );

                echo '<p>'.$common->social_links($args) .'</p>';
                //echo '<p>' . $activity['activitytype_category_id'] . '</p>';
                //echo '<p>' . $stURL . '</p>';
	          ?>                          
	       </li>                         
		<?php
			$tempDate =  date('m-d-Y' , strtotime($activity["activity_date"])); 
		} 
	}else {
		echo 'There are no activities at this time';
		
	}	
		?>
  </ul>   
