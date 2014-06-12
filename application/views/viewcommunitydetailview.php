
<?php require_once 'Common.php'; 
      $common = new Common();
      require_once 'Matchh.php';
      $match = new Matchh();
?>

<?php require_once 'seourlgen.php';
       $urlGen = new SeoUrlGen(); 
       $config = Zend_Registry::get ( 'config' );
       $session = new Zend_Session_Namespace('userSession'); ?>


<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>


<input type="hidden" id="activityId" name="type" value="<?php echo $this->activityId; ?>">
<ul id="ActivityDetailList">
 
<?php
      $tempDate = '1234';
      $today = date ( "m-d-Y" ) ;
      $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
      if (count($this->paginator) >= 1){
	      foreach ($this->paginator as $activity) { 
	      	 if ( $activity['activity_player_id'] > 1) { 
                $path_player_photos = $config->path->images->players . $activity['activity_player_id'] .".jpg" ;
                if (file_exists($path_player_photos)) { 
                    $alert_image = "/utility/imagecrop?w=50&h=50&zc=1&src=". $this->root_crop . "/players/".$activity['activity_player_id'].".jpg";
                } else {
                    //modify path to get resized images for activities
                    $alert_image = "/public/images/". $activity['activity_icon'];
                }
	       } else { 
      		      if($activity['activity_icon'] == 'y'){ 
                  $alert_image = "/utility/imagecrop?w=40&h=40&zc=1&src=". $this->root_crop . "/photos/". $activity['activitytype_name'];
              } else { 
                  $alert_image = "/public/images/". $activity['activity_icon'];
               }
		    } 


        ?>

          <li style="background-image:url(<?php echo $alert_image; ?>)">

    		    <strong>
    	          <?php if($activity['activity_icon'] == 'y'){?>
    	          	Broadcast
    	          <?php }else { ?>
                  <?php echo $activity['activitytype_name']; ?>
                <?php }?> 
            </strong>
            <br />
    	         <?php echo $activity['activity_text']; ?>
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

				$matchrow = null;
                //$stTitle = $activity["activitytype_name"];
                $stTitle = strip_tags($activity['activity_text']);
                $stURL = 'http://' . $config->path->index->server->name . '/community/showuniqueactivity/activityId/' . $activity["activity_id"];

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
                 
                $social_icons_images = 'http://' . $config->path->index->server->name ."/public/images/icons/social/";

                $args = array(
                  'image_size' => 16
                 ,'image_path' => $social_icons_images
                 ,'separator'  => ' '
                 ,'url' => $stURL
                 ,'title' => $stTitle 
                 ,'media_url' => $stImage
                 ,'twitter_username' => 'goalface'
                 ,'platforms' => array('facebook','twitter','pinterest','google-plus')
                );

                echo '<p>'.$common->social_links($args) .'</p>';

                //echo '<p>'.$stURL .'</p>';

                //echo '<p>' . $activity['activitytype_category_id'] . '</p>';
                //echo '<p>' . $stImage . '</p>';

            ?>
  
           </li>

		<?php
			$tempDate =  date('m-d-Y' , strtotime($activity["activity_date"]));


		}
	}else {
		echo 'There are no activities at this time';

	} ?>
</ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>

