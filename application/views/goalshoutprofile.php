<script type="text/JavaScript">

jQuery(document).ready(function() {
	jQuery("#commentGoalShoutId").charCounter(400); 

	jQuery('#commentGoalShoutId').focus(function() {
		 jQuery('#commentGoalShoutId').html('');
		 jQuery('#leaveGSButtonId').removeAttr('disabled'); 
	 });
	 
});



</script>


<?php $session = new Zend_Session_Namespace('userSession');?>
 <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                              <h4 class="NoArrowLeft">GOOOAL Shouts (<?php echo $this->totalGoalShouts;?>)</h4>
                               <span>
                                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts/username/<?php echo $this->currentUser->screen_name;?>">View All</a>
                                </span>
                            </div>
                            <div id="commentMessageId" class="ErrorMessages closeDiv">
                                Your report abuse message has been saved.
                             </div>
                            <div id="boxComments">
                              <?php
                              $today = date ( "m-d-Y" ) ;
                              $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                              if ($this->totalGoalShouts > 0) {
                                foreach($this->comments as $uc) {?>
                                <?php if($session->screenName != $uc['screen_name'] and $uc['comment_deleted']=='1'){ 
                               		
                               }else{ ?>
                                 
                                <dl class="comment">
                        	         <dt>
                                      <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"> 
                                        <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'silueta.gif');?>" />
                                      </a>
                                   </dt>

                                   
                        	         <dd>
                                      <span class="name"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"><?php echo $uc["screen_name"];?></a> </span>
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
										<?php if($session->screenName == $uc['screen_name']){
											if($uc['comment_deleted']=='0'){?>		                        	           
							               <a class="edit" id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["comment_id"];?>')">Edit</a>
							           		&nbsp;|&nbsp;
											<?php }
											} ?>
											
										<?php if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name']){
							           	if($uc['comment_deleted']=='0'){?>    
							              <a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
						               <?php }
											} ?>    
                                      <p id="<?php echo 'goalshout' .$uc['comment_id']?>"><?php if ($uc['comment_deleted']=='0'){ ?><?php echo trim($uc["comment"]); ?>
                                      <?php }else if ($uc['comment_deleted']=='1'){  ?>
                                      		<i>Goooal Shout was removed by owner</i>
                                      <?php }else if ($uc['comment_deleted']=='2'){  ?>
                                      		<i>Goooal Shout was removed by profile owner</i>
                                      <?php } ?>
                                      </p>
						               <?php if($uc['comment_deleted']=='0'){?>
						               	<?php if($session->email != null){ ?>
						              	<span class="abuse"><a class="warning" href="javascript:reportAbuse('<?php echo $uc['comment_id'];?>','<?php echo $uc['friend_id'];?>');">Report This</a></span>
						              	<?php }else {?>
						              	<span class="abuse"><a class="warning" href="javascript:loginModal();">Report This</a></span>
						              	<?php }?>
						              <?php }?>

                        	         </dd>
                        	        
                                  </dl>   
                              	<?php } ?>
                            <?php }
                                }else { ?>

                                <ul class="boxDefaultMsg">
                                    <?php if ($this->isMyProfile == 'n'){

                                    echo "<li>". $this->currentUser->screen_name .' does not have any Goooal Shouts. Be the first to write one.</li>';

                                    } else {

									echo '<li>You do not have any Goooal Shouts.</li>';

                                    } ?>

							 <?php } ?>
								
							
							<?php if ($this->isMyProfile == 'n' && $this->isMyFriend == 'false'){
								echo '<li><a href="javascript:void(0);" id="addToFriendTriggerGoalShout">Add '.$this->currentUser->screen_name .'</a> to your friend\'s list and leave a goalshout.</li>';
							} ?>	
                            </ul>
                            <?php  if ($this->isMyProfile == 'n' && $this->isMyFriend == 'true'){ 
                              		if($session->email != null){ ?>
                                      <div id="comment_formerror" class="ErrorMessageIndividual">Please enter a message in the Goooal Shout form</div>
                                      <div class="comment_input">

                                          <form id="comment_form" action="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalshout">
                                              <?php
                                                if($session->email == null){
                                                    $message = "Sign in to leave a Goooal Shout";
                                                }else{
                                                    $message = "Type your Goooal Shout here...";
                                                }
                                              ?>
                                              <textarea <?php if($session->email == null){?>disabled="disabled" <?php } ?> class="comment_text" id="commentGoalShoutId" name="comment"><?php echo trim($message);?>
                                                </textarea>

                                              <input type="hidden" name="commentType" id="commentTypeId" value="1">
                                              <input type="hidden" name="idtocomment" id="idtocommentId" value="<?php echo $this->currentUser->user_id;?>">
                                              <input type="hidden" name="screennametocomment"  id="screennametocommentId"	value="<?php echo $this->currentUser->screen_name;?>">
                                                <?php if($session->email == null){?>
                                                        <input class="submit" type="button" value="Leave Goooal Shout" id="signInButtonId"/>
                                                <?php	}else{ ?>
                                                        <input class="submit" type="button" id="leaveGSButtonId" disabled value="Leave Goooal Shout" onclick="addGoalShout()" />
                                                <?php	} ?>

                                          </form>
                                      </div>
                              <? } 
                            }?>
                          </div>
                             <?php if ($this->totalGoalShouts > 0) { ?>
                            <div class="SeeAllShouts">
                                <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts/username/<?php echo $this->currentUser->screen_name;?>">See More ></a>
                            </div>
                            <?  }?>


                        </div>