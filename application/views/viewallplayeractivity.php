<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>  
     <?php require_once 'Common.php'; ?>
     
  <script language="javascript">


    jQuery(document).ready(function() {   
     
     jQuery('#buttonActivity').click(function(){

            var playeractivityId = jQuery('#playerActivityId').val();
            var url  = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayeractivity/id/<?php echo $this->playerid; ?>/type/'+playeractivityId;
            
            document.playeractivityIdForm.action = url;
            document.playeractivityIdForm.submit();

        });
    });

    </script>    

      <div id="ContentWrapper" class="TwoColumnLayout">
          <div class="FirstColumn">
                            <?php 
                    $session = new Zend_Session_Namespace('userSession');
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
		  <?php //echo $this->render('include/feedbackbadge.php')?>
          
          <!--Player Profile Badge-->
          <?php echo $this->render('include/badgePlayerNew.php');?>

          <!--Player Profile left Menu-->
          <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
          	<?php echo $this->render('include/navigationPlayerNew.php');?>
        </div>

                                  
                                   
          </div><!--end FirstColumnOfThree-->
					

            <div class="SecondColumn" id="SecondColumnPlayerProfile">
                <h1><?php echo	$this->playername;?> Activity</h1>
          					<div class="img-shadow">
          						<div class="WrapperForDropShadow">
          							<div class="SecondColumnProfile">
      					          <div id="FriendsWrapper" class="FriendsWrapperTopAlign">
          					          <ul class="Friendtoolbar FriendtoolbarTopAlign">
          					          	<li class="Buttons">
          					          			<span class="Label">Show:
                                    	<form id="playeractivityIdForm" action="#" method="post" name="playeractivityIdForm">
                                        <select id="playerActivityId" class="slct" name="playerActivityId">
                                            <option value="0" <?php if(0 == $this->submitvalue) echo 'selected="selected"';?>>All Activity</option>
                                            <option value="1" <?php if(1 == $this->submitvalue) echo 'selected="selected"';?>>Goals</option>
                                            <option value="2" <?php if(2 == $this->submitvalue) echo 'selected="selected"';?>>Appearances</option>
                                            <option value="3" <?php if(3 == $this->submitvalue) echo 'selected="selected"';?>>Cards</option>
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
                                if (sizeOf($this->playeractivities) > 0) 
                                {
                           			 echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml');
                           			 $i = 1;
                               		 foreach($this->paginator as $apu) {
                               		     if($i % 2 == 1) {$style = "odd_row";}else{$style = "even_row";}
                                  ?>         				          	
																			<div id="comment_<?php echo $i;?>" class="friends_area <?php echo $style;?>">
                                      	<div class="leftcomment">
                                      		<img id="iconActivity" title="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $apu["activitytype_icon"];?>">
                                      	</div>
                                      	<div class="rightcomment">
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
                                      	</div>
                                    	</div>
                                  	<?php 
                                    	$i++; 
                                    	} 
                                    ?>
                              <?php 
                                echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml');
                               }
                               else 
                               { 
                               ?>
                                <a 	href="<?php 	echo Zend_Registry::get ( "contextPath" ); 	?>/login">No Activity for this player</a>	
                              <?php   } ?>
  
    							         </div>
                          </div>
                        </div>
                    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

              </div><!--end Second Column-->

    </div> <!--end ContentWrapper-->
