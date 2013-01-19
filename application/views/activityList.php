 <?php
  $tempDate = '1234';
  $today = date ( "m-d-Y" ) ;
  $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
  if ($this->totalFriendActivities != null) {
    foreach($this->activitiesPerUser as $apu) {
      if($tempDate != date('m-d-Y' , strtotime($apu["activity_date"])) ){
        if($tempDate != '1234'){ ?>
          </ul>
            <?php  } ?>
              <ul class="OneFriend">
                <li class="Date">
              <?php
                if($today == date('m-d-Y' , strtotime($apu["activity_date"]))){
                    echo 'Today';
                }else if($yesterday == date('m-d-Y' , strtotime($apu["activity_date"]))){
                    echo 'Yesterday';
                }else {
                    echo date(' F j' , strtotime($apu["activity_date"])) ;
                }?>
                </li>

              <? } ?>

                <li class="<?php echo $apu["activitytype_icon"];?>">
                    <img  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/<?php echo $apu["activitytype_icon"];?>">
                    <?php echo $apu["activity_text"];?><?php echo date(' g:i a' , strtotime($apu["activity_date"])) ;?>
                </li>

          <?php
          $tempDate =  date('m-d-Y' , strtotime($apu["activity_date"]));

   }
    }else { ?>
        <ul class="boxDefaultMsg">
        <li>No friend activity.  Click <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends">here</a> to add friends to your Fan Feed..</li>
        </ul>
 <? }

 ?>
