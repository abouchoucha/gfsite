<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
       $urlGen = new SeoUrlGen(); 
       $config = Zend_Registry::get ( 'config' );
       $session = new Zend_Session_Namespace('userSession'); ?>

<script type="text/JavaScript">
  jQuery.getScript("http://w.sharethis.com/button/buttons.js", function() {
      var switchTo5x = false;
      stLight.options({publisher: "bf8f5586-8640-4cce-9bca-c5c558b3c0a1"});
  });
</script>

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
          
            <div class="sharing">
              <span id="button_<?php echo $activity["activity_id"]?>" class='st_sharethis_hcount' 
                    displayText='ShareThis' 
                    st_url='http://<?php echo $config->path->index->server->name; ?>/community/showuniqueactivity/activityId/<?php echo $activity["activity_id"]; ?>' 
                    st_title='<?php echo $activity["activitytype_name"]; ?> Event' 
                    st_image='http://<?php echo $config->path->index->server->name; ?>/<?php echo $alert_image; ?>'>
              </span>
              XX1
            </div>         
  	       </li>

		<?php
			$tempDate =  date('m-d-Y' , strtotime($activity["activity_date"]));
		}
	}else {
		echo 'There are no activities at this time';

	} ?>
</ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>

