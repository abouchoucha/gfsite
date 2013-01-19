<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen ( );
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $common = new Common();
?>

<script type="text/JavaScript">

jQuery(document).ready(function() {

	jQuery("#commentGoalShoutId").charCounter(400); 
	
	jQuery('#removeButton').click(function() {
		 top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/login'; 
	 });
	jQuery('#commentGoalShoutId').focus(function() {
		 jQuery('#commentGoalShoutId').html('');
		 jQuery('#leaveGSButtonId').removeAttr('disabled'); 
	 });
});

</script>


	<div  class="WrapperForDropShadow">
        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
            <h4 class="WithArrowToLeft"><a href="<?php echo Zend_Registry::get ( "contextPath" ); 	?>/team/showteamgoalshouts/id/<?php echo $this->teamid;?>"><?php echo $this->team[0]['team_name'];?> GOOOAL Shouts (<?php echo $this->totalTeamShouts;?> )</a></h4>
          <?php if ($this->totalTeamShouts  > 0) { ?>
          <span>
            <a href="<?php echo Zend_Registry::get ( "contextPath" ); 	?>/team/showteamgoalshouts/id/<?php echo $this->teamid;?>">View All</a>
          </span>
          <?php } ?>
        </div>
        <div id="boxComments" class="boxComments narrow">
        	<div id="comment_formerror" class="ErrorMessageIndividual">Please enter a message in the GoooalShout form.</div>
         	<div class="comment_input">
          		<form id="comment_form" action="post" name="comment_form" action="">
                    <?php
                        if($session->email == null){
                            $message = "Sign in to leave a Goooal Shout";
                        }else{
                            $message = "Type your Goooal Shout here...";
                        }
                      ?>
                      <textarea <?php if($session->email == null){ ?> 
                            disabled="disabled" <?php } ?> class="comment_text" id="commentGoalShoutId" name="comment" onfocus="this.value='';"><?php echo $message; ?>
                       </textarea>
                      <input type="hidden" name="commentType" id="commentTypeId" value="5">
                      <input type="hidden" name="idtocomment" id="idtocommentId" value="<?php echo $this->teamid;?>">
                      <input type="hidden" name="screennametocomment"  id="screennametocommentId" value="<?php echo $session->screenName;?>">
                      <input type="hidden" name="teamId" id="teamId" value="<?php echo $this->teamid;?>">
                       <input type="hidden" name="countryid" id="countryid" value="<?php echo $this->countryid;?>">
                      <?php if($session->email == null){?>		
                            <input class="submit" type="button" value="Leave Goooal Shout"  id="signInButtonId" onclick="loginModal()"/>
                            <?php	}else{ ?>
                            <input class="submit" type="button" disabled value="Leave Goooal Shout" id="leaveGSButtonId" onclick="addGoalShout()" /> 
                            <?php	} ?>
                           
                      
           		</form>
           	</div>
			<?php
				$today = date ( "m-d-Y" );
				$yesterday = date ( "m-d-Y", (strtotime ( date ( "Y-m-d" ) ) - 1 * 24 * 60 * 60) );
				if ($this->totalTeamShouts > 0) 
				{
					 $i = 1;
					foreach ( $this->teamcomments as $uc ) 
					{
						$activeCommentId = $uc['comment_id'];
			?>
				          <dl class="comment">
				            <dt>
				              <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>">
				                <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>" />
				              </a>
				            </dt>
				            <dd>
				              <span class="name">
				                <a href="<?php echo Zend_Registry::get ( "contextPath" );?>/profiles/<?php	echo $uc ["screen_name"];?>"><?php echo $uc ["screen_name"];?></a>
				              </span>&nbsp;
				              <span class="date">
								<?php
									if ($today == date ( 'm-d-Y', strtotime ( $uc ["comment_date"] ) )) 
									{
										echo 'Today';
									} 
									else if ($yesterday == date ( 'm-d-Y', strtotime ( $uc ["comment_date"] ) )) 
									{
										echo 'Yesterday';
									} 
									else 
									{
										echo date ( ' F j , y', strtotime ( $uc ["comment_date"] ) );
									}
					             ?>			                                 		
					             &nbsp;at 
								<?php
									echo date ( ' g:i a', strtotime ( $uc ["comment_date"] ) );
						        ?>
				              </span>
						   	<?php if($session->screenName == $uc['screen_name'])
						   	{
								if($uc['comment_deleted']=='0')
								{
							?>		                        	           
				               		<a class="edit" id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["comment_id"];?>')">Edit</a>
				            <?php 
								}
							} ?>
								&nbsp;|&nbsp;    
				           <?php 
				           		if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name'])
				           		{
				           			if($uc['comment_deleted']=='0')
				           			{
				           	?>    
				              		<a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
				           <?php 
				           			}
								} 
							?>    
                               	<p id="<?php echo 'goalshout' .$uc['comment_id']?>">
                               	<?php 
                               		if ($uc['comment_deleted']=='0')
                               		{ 
                               			 echo trim($uc["comment"]); 	
                              		}
                              		else if ($uc['comment_deleted']=='1')
                              		{  
                              	?>
                               		<i>Goooal Shout was removed by owner</i>
                               <?php 
                              		}
                              		else if ($uc['comment_deleted']=='2')
                              		{  
                              	?>
                               		<i>Goooal Shout was removed by profile owner</i>
                               <?php 
                              		} 
                              	?>
                               </p>
				             <?php
				             //If comment is not deleted, show the Comment and Ratings Controls
				             if (!in_array($uc['comment_deleted'], array("1", "2")))
				             {
				            ?>
					             <div class="commentsAndRatingsNarrow">
					             	
					                <span><a href="javascript:void(0)" onclick="toggleReplyContainerDisplay('#replyControlContainer<?php echo $activeCommentId;?>')">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;|</span>
					                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', 1)"><img width="19" height="25" alt="Rate This Activity Up" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_up.png"></a> <span id="activity<?php echo $activeCommentId;?>PlusCount">0</span>
					                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', -1)"><img width="19" height="25" alt="Rate This Activity Down" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_down.png"></a> <span id="activity<?php echo $activeCommentId;?>MinusCount">0</span>                
					                <input type="hidden" id="activity<?php echo $activeCommentId;?>HasBeenRated">
					       
						               <span class="reportThis">
						                	<a class="report" href="javascript:void(0)">Report This</a>
						               </span>
					       
					             </div>
				             <?php 
				             }
				             ?>
				             
				              <div id="viewreplies<?php echo $activeCommentId;?>" class="viewallreplies">	
							             <?php 
							             	require_once 'Activity.php';
							          		$activityObj = new Activity(); 
							             	$replies = array();
							             	$replies = $activityObj->findRepliesByBroadcast($activeCommentId,null );
							             	//var_dump($replies);
							           		foreach($replies as $reply)
							           		{
							           			#var_dump($reply);
							           			if(!in_array($uc['comment_deleted'], array("1", "2")))
							           			{
							          	?>
									             <div id="comment_reply_wrapper_<?php echo $i;?>" class="nestedwrapper">
									                <div class="leftcomment_nested">
									                  <a href = "<?php echo $urlGen->getUserProfilePage($reply["screen_name"],True);?>">
									                 	<?php  if ($reply["main_photo"]!=null || $reply["main_photo"]!='') { ?>   
									                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $reply["main_photo"]; ?>" alt=""/>
														<?php } else {  ?>
														<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="">
														<?php } ?>
									                </a>
									                </div>
									                <div class="rightcomment_nested">
								                       <span class="author">
											             	<a href="<?php echo $urlGen->getUserProfilePage($reply["screen_name"],True);?>">
											             		<?php echo $reply['screen_name'];?>
											             	</a> 
											            </span>
													
														<span class="date"><?php echo $common->convertDates($reply["activity_date"]);?>
														<?php 
														if($session->screenName == $reply['screen_name'])
											            	{
											            		
														?>		                        	           
																<a class="edit" id="edit<?php echo $reply['commentid'];?>" href="javascript:editGoalShout('<?php echo $reply['commentid'];?>', true)">Edit</a> |
														<?php 
															} 
															if($this->isMyProfile == 'y' or $session->screenName == $reply['screen_name']  )
															{
														?>    
																<a class="edit" href="javascript:deleteGoalShout('<?php echo $reply['commentid'];?>', true);">Delete</a>
														<?php 
															}
														?>
														</span>
														<span class="replyTextContainer" id="reply<?php echo $reply['commentid'];?>"><?php echo $reply['activity_text']?>&nbsp;</span>
														
									                </div>
									                <div style="clear:both"></div>
									             </div>
							            <?php
							           			} 
							           		} 
							            ?>
							      </div>
						        <?php 
					            if (!in_array($uc['comment_deleted'], array("1", "2")))
					            {	
					          	?>
					          
					             <div  id="replyControlContainer<?php echo $activeCommentId;?>" class="comment_form" style="display:none">
					             	<div id="" class="leftside">
					             		<a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>">
					             		<?php  if ($uc["main_photo"]!=null || $uc["main_photo"]!='') { ?>   
					                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $session->mainPhoto;?>" alt=""/>
										<?php } else {  ?>
										<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="">
										<?php } ?>
										</a>
					             	</div>
					             	<div class="rightside">
					             		<textarea id="text_comment<?php echo $activeCommentId;?>" rows="" cols="" style="width:200px;height:30px;" onfocus="javascript:if(this.value=='Add a comment...')this.value='';" onblur="javascript:if(this.value=='')this.value='Add a comment...';" onclick="javascript:jQuery('#replyControlId<?php echo $activeCommentId;?>').show()">Add a comment...</textarea>
					             		<div id="replyControlId<?php echo $activeCommentId;?>" class="replyControlsContainer">
						             		<input type="button" onclick="javascript:sendReply( '<?php echo $activeCommentId;?>', false);" value="Add Comment" /> 
						             		&nbsp;&nbsp;or&nbsp;&nbsp; <a href="javascript:void(0)" onclick="javascript:jQuery('#replyControlContainer<?php echo $activeCommentId;?>').hide('fast')">Cancel</a>
					             		</div>
					             	</div> 
					             	<div style="clear:both"></div>
					             </div>
					           <?php 
					            }
					            ?>     
				              	
				              </dd>
				            </dl>
				            <?php
				            	}
				            	$i++;
				            } 
				            ?>
	
	          
	          
	        </div>
			<?php
			if ($this->totalTeamShouts > 0) {
			        	?>
			        <div class="SeeMoreNews">
			          <a class="OrangeLink" href="<?php echo Zend_Registry::get ( "contextPath" ); 	?>/team/showteamgoalshouts/id/<?php echo $this->teamid;?>">See more Goooal Shouts</a>
			        </div>
			<?php
			}
        ?>
      </div>