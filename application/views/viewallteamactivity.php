<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>

<script language="javascript">


    jQuery(document).ready(function() {   
     
     jQuery('#buttonActivity').click(function(){

            var teamactivityId = jQuery('#teamActivityId').val();
            var url  = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamactivity/id/<?php echo $this->teamid; ?>/type/'+teamactivityId;
            
            document.teamactivityIdForm.action = url;
            document.teamactivityIdForm.submit();

        });
    });

    </script>    

 
<div id="ContentWrapper" class="TwoColumnLayout">

    <div class="FirstColumn">

                <?php
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>
                    
                    <?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                    <!--Goalface Register Ad-->

                    <?php } ?>
				

                    <!--Team Profile Badge-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/badgeTeamNew.php');?>
                    </div>

                    <!--Team Profile left Menu-->
                    <div class="img-shadow">
                       <?php echo $this->render('include/navigationTeam.php');?>
                    </div>
                    


                </div><!--end FirstColumnOfTwo-->

    <div class="SecondColumn" id="SecondColumnPlayerProfile">
        <h1><?php echo $this->team[0]['team_name']; ?> Activity</h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">
              <div id="FriendsWrapper" style="padding-top:0;">
                  <ul class="Friendtoolbar" style="margin-top:0;">
                    <li class="Buttons">
                    <span class="Label">Show:
                       <form id="teamactivityIdForm" action="#" method="post" name="teamactivityIdForm">
                              <select id="teamActivityId" class="slct" name="teamctivityIdForm">
						    <option value="0" <?php if(0 == $this->submitvalue) echo 'selected="selected"';?>>All Activity</option>
						    <option value="1" <?php if(1 == $this->submitvalue) echo 'selected="selected"';?>>Match Results</option>
						    <!--<option value="2">News</option>
						    <option value="3">Pictures</option>-->
						    <option value="4" <?php if(4 == $this->submitvalue) echo 'selected="selected"';?>>Community</option>
						</select>
						<input style="display:inline;" type="submit" id="buttonActivity" value="Ok" class="submit">
                              </form>
                       </span>
                    </li>
                  </ul>
              <?php
                $tempDate = '1234';
                $today = date ( "m-d-Y" ) ;
                $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                if (sizeOf($this->teamactivities) > 0) {
              ?>
                   
              <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
                  
              <?php
                    foreach($this->paginator as $apu) {
                  ?>
                       <div id="boxComments">
                          <dl class="comment">
                            <dt class="shout">
                              <img id="iconActivity" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $apu["activitytype_icon"];?>">
                            </dt>
                            <dd>
                              <?php if($tempDate != date('m-d-Y' , strtotime($apu["activity_date"])) ){ ?>
                                <span class="date">
                                  <?php
                                    if($today == date('m-d-Y' , strtotime($apu["activity_date"]))){
                                        echo 'Today';
                                    }else if($yesterday == date('m-d-Y' , strtotime($apu["activity_date"]))){
                                        echo 'Yesterday';
                                    }else {
                                        echo date(' F j' , strtotime($apu["activity_date"]))." - " .date(' g:i a' , strtotime($apu["activity_date"]))   ;
                                  }?>
                                </span>
                              <?php } ?>
                                <p class="shoutp"><?php echo $apu["activity_text"];?></p>
                            </dd>
                          </dl>
                        </div>

                        <?php
                            //$tempDate =  date('m-d-Y' , strtotime($apu["activity_date"]));
                          }
                        ?>
                  <?php }else { ?>
                    No Activity for this team
                  <?php   } ?>
					<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
                 </div>
              </div>
            </div>
        </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

  </div><!--end SecondColumnOfTwo-->
             
</div> <!--end ContentWrapper-->
