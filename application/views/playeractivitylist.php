<table>
            <?php
              $tempDate = '1234';
              $today = date ( "m-d-Y" ) ;
              $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
              if (sizeOf($this->playeractivities) > 0){
                foreach($this->playeractivities as $apu) {
                  if($tempDate != date('m-d-Y' , strtotime($apu["activity_date"])) ){
            ?>
            <tr class="header">
              <td colspan="2">
                <strong>
                <?php
                      if($today == date('m-d-Y' , strtotime($apu["activity_date"]))){
                      	echo 'Today';
                      }else if($yesterday == date('m-d-Y' , strtotime($apu["activity_date"]))){
                      	echo 'Yesterday';
                      }else {
                      	echo date(' F j' , strtotime($apu["activity_date"])) ;
                }?>
                </strong>
              </td>
            </tr>
            <?php } ?>
            <tr class="feedFriendItem">
              <td class="feedFriendIcon">
                <img  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/<?php echo $apu["activitytype_icon"];?>"></td>
              <td class="feedFriendcontent">
                <?php echo $apu["activity_text"];?><span style="color:#999999;font-size:9px;"><?php echo date(' g:i a' , strtotime($apu["activity_date"])) ;?></span></td>
            </tr>
            <?php
              $tempDate =  date('m-d-Y' , strtotime($apu["activity_date"]));
               }
            }else { ?>
              <span style=\"text-align:center\">No recent activity for this player.</span>
          <?php } ?>
 </table>