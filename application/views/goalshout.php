<?php $session = new Zend_Session_Namespace('userSession');?>
<div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                              <h4 class="NoArrowLeft">GOOOAL Shouts (<?php echo $this->totalGoalShouts;?>)</h4>
                               <span>
                                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts/username/<?php echo $this->currentUser->screen_name;?>">View All</a>
                                </span>
                            </div>
                            <div id="boxComments">
                              <?php
                              $today = date ( "m-d-Y" ) ;
                              $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                              if ($this->totalGoalShouts > 0) {
                                foreach($this->comments as $uc) { ?>
                              
                                <dl class="comment">
                        	         <dt>
                                      <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"> 
                                        <img alt="<?php echo $this->currentUser->screen_name;?>" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>"/>
                                      </a>
                                   </dt>

                                   
                        	         <dd>
                                      <span class="name">
                                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"><?php echo $uc["screen_name"];?></a> 
                                      </span>
                                      &nbsp; <span class="date">
                                        	<?php
    		                                if($today == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Today';
    		                                }else if($yesterday == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Yesterday';
    		                                }else {
    		                                	echo date(' F j , y' , strtotime($uc["comment_date"])) ;
    		                                }?>
                                    		&nbsp;at <?php echo date(' g:i a' , strtotime($uc["comment_date"])) ;?>
                                        </span>
                        	            <p>                                    	
                                      <?php echo $uc["comment"];?>                                
                                      </p>

                        	         </dd>
                        	          <?php if ($this->isMyProfile == 'y'){ ?>
                        	         <dd class="Delete">
                                    	 
                                    	 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:deleteGoalShout('<?php echo $uc["comment_id"];?>')">Delete</a>
                                    	   
                                    </dd>
                                    <?php } ?> 
                                  </dl>   
                              
                              <?php }
								}else { ?>
									You don't have gooaalshouts.
							 <? }
							 ?>
                            <?php if ($this->isMyProfile == 'n'){ 
                              		if($session->email != null){ ?>
                              <div id="comment_formerror" class="ErrorMessageIndividual">You must enter a goalshout.</div>
                              <div class="comment_input">
                              
                                  <form id="comment_form" action="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addGoulShout">
                                      <textarea class="comment_text" id="comment" name="comment"> </textarea>
                                      <input type="hidden" name="idtocomment" value="<?php echo $this->currentUser->user_id;?>">
                                      <input type="hidden" name="commentType" value="1">
                                      <input type="hidden" name="screennametocomment" value="<?php echo $this->currentUser->screen_name;?>">
                                      <input class="submit" type="button" value="Leave Comment" onclick="addGoalShout()"/>
                                  </form>
                              </div>
                              <? } else {
                            	 if ($this->totalGoalShouts > 0) { ?>
                            	 	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/login">Leave a Goal Shout to <? echo $this->currentUser->screen_name; ?></a>
                            <? }
								}
                            }	 ?>
                          </div>
                             <?php if ($this->totalGoalShouts > 0) { ?>
                            <div class="SeeAllShouts">
                                <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showshoutout/id/<?php echo $this->currentUser->user_id;?>">See All Gooal Shouts</a>
                            </div>
                            <?  }?>


                        </div>