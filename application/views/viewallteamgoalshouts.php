<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>
  <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    
 ?>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

	jQuery(document).ready(function() {

		jQuery("#commentGoalShoutId").charCounter(400); 
		jQuery('#addGoalShoutId').click(function() {
			 addGoalShout();
			 
		 });
		 
		 jQuery('#reportTypeId').change(function(){
			 var selectValue = jQuery('#reportTypeId').val();
			 if(selectValue == 0){
				 jQuery('#textReportAbuseId').attr('disabled','disabled'); 
				 jQuery('#acceptReportAbuseButtonId').attr('disabled','disabled'); 
			 }else {
			 	jQuery('#textReportAbuseId').removeAttr('disabled');
			 	jQuery('#acceptReportAbuseButtonId').removeAttr('disabled');
			 }	 
	   }); 	
		 
	});


	var closeModal = function(h) { 
	    //t.html('Please Wait...');  // Clear Content HTML on Hide.
	    h.o.remove(); // remove overlay
	    h.w.fadeOut(888); // hide window
	  };
     
     function addGoalShout(){
		 var commentText = jQuery('#commentGoalShoutId').val();
		 var ok = 'true'; 	
		 if(jQuery.trim(commentText) == '' || commentText =='Enter your goalshout here...'){
			jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			ok = 'false';
 		 }else {
		      jQuery.ajax({
	 	  	    type: "POST",
		      	data: jQuery("#comment_form").serialize(),
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalshout',
				success: function(data) {
		    	  top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamgoalshouts/id/<?php echo $this->teamId;?>';
				}	
			}) 	
 		 }
 		 return ok;
	}


     function editGoalShout(id, isReply){
			
			jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShoutTrigger', onHide: closeModal });
			jQuery('#editGoalShoutModal').jqmShow();
			var replyId = '#goalshout'+id;
			if(isReply)
			{
				replyId = '#reply'+id;
			}
			var dataEdit = jQuery(replyId).text();
			jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));
			
				jQuery('#acceptEditGoalShoutButtonId').click(function() {
					var commentText = jQuery('#textgoalshoutEdit').val();
					if(jQuery.trim(commentText) == ''){
						jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			 			return;
			 		 }
					var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/editplayergoalshout';
					var playerId = '<?php echo $this->playerid;?>';	
					var dataEditted = jQuery('#textgoalshoutEdit').val();
					jQuery.ajax({
						type: 'POST',
						data: ({playerId :playerId , id : id , dataEditted : dataEditted}),
						url: '<?php echo Zend_Registry::get("contextPath"); ?>/player/editplayergoalshout',
						success: function(data){
							jQuery('#editGoalShoutModal').jqmHide();
				    		top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayergoalshouts/id/<?php echo $this->playerid;?>';
				    		 
						}	
					})
				});
	}

function sendReply(commentid, isActivityComment){

	var broadcast_reply = jQuery('#text_comment'+commentid).attr('value');
	if(broadcast_reply == '' || broadcast_reply == 'Write a comment...'){
			return;
  }
	
		jQuery.ajax({
	type: "POST",
	url: "<?php echo Zend_Registry::get("contextPath"); ?>/index/addbroadcast",
	data:"message_wall="+ broadcast_reply+"&commentid="+commentid+"&isActivityComment="+isActivityComment,
	success: function(){
		callAllReplies(commentid, isActivityComment);
		jQuery('#text_comment'+commentid).val('Write a comment...');
	}
	});
	return false;

}

function callAllReplies(commentid, isActivityComment){
	        jQuery('#viewreplies'+commentid).html("<div class='ajaxload widgetlong'></div>");
	    	jQuery.ajax({
	            method: 'get',
	            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmessagereplies/commentid/'+commentid+ '/isActivityComment/' + isActivityComment,
	            dataType : 'text',
	            success: function (text) {
	                jQuery('#viewreplies'+commentid).html(text);
	             }
	         });
		}

function toggleReplyContainerDisplay(replyContainerId)
	{
		if(jQuery(replyContainerId).css('display') == "none")
		{
			jQuery(replyContainerId).show('fast');
		}
		else
		{
			jQuery(replyContainerId).hide('fast');
		}
	}
	//Records a user rating on an activity
	function rateActivity(activityId, rateValue)
	{
		//console.log("activityId: " + activityId + ' rateValue: ' + rateValue);
		if( jQuery('#activity' + activityId + 'HasBeenRated').val())
		{
			alert("You have already rated this activity!");
			return;
		}
		if(rateValue > 0 )
		{
			//Increase plus rating by one
			jQuery('#activity' + activityId + 'PlusCount').text(parseInt(jQuery('#activity' + activityId + 'PlusCount').text()) + 1);
			
		}
		else
		{
			//Increase plus rating by one
			jQuery('#activity' + activityId + 'MinusCount').text(parseInt(jQuery('#activity' + activityId + 'MinusCount').text()) + 1);
		
		}
		jQuery('#activity' + activityId + 'HasBeenRated').val('1');
	}

function deleteGoalShout(id, isComment){

		 jQuery('#acceptModalButtonId').show();
		 jQuery('#cancelModalButtonId').attr('value','Cancel'); 
		 var modalTitle = (isComment)?'DELETE COMMENT?':'DELETE GOOOALSHOUT?';
		 jQuery('#modalTitleConfirmationId').html(modalTitle);
		 var confirmMessage = (isComment)?'Are you sure you want to delete this comment?':'Are you sure you want to delete a goalshout';
		 var deleteMessage = (isComment)?'Your comment has been deleted.':'Your goalshout has been deleted.';
		 jQuery('#messageConfirmationTextId').text(confirmMessage);	
		 
				    
		 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal });
		 jQuery('#messageConfirmationId').jqmShow();
		 
		 jQuery("#acceptModalButtonId").unbind();
			
		 jQuery('#acceptModalButtonId').click(function(){
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/removeteamgoalshout/teamid/<?php echo $this->teamid;?>/id/'+id;
			 jQuery.ajax({
					type: 'GET',
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/team/removeteamgoalshout/teamid/<?php echo $this->teamid;?>/id/'+id,
					success: function(data){
						
					 	 jQuery('#messageConfirmationTextId').text(deleteMessage);
						 jQuery('#acceptModalButtonId').hide();
						 jQuery('#cancelModalButtonId').attr('value','Close');
						 jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
			    		 top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamgoalshouts/id/<?php echo $this->teamid;?>';
			    		 
					}	
				})
			 
		 });	
	}		

	 function reportAbuse(id){

			jQuery('#reportTypeId').val('0');
			jQuery('#textReportAbuseId').val(''); 
			jQuery('#textReportAbuseId').attr('disabled','disabled');
			jQuery('#reportAbuseBodyId').show();
			jQuery('#reportAbuseBodyResponseId').hide();
			jQuery('#acceptReportAbuseButtonId').show(); 
			jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	
			jQuery('#reportAbuseTitleId').html('REPORT GOALSHOUT ABUSE?');
			jQuery('#reportAbuseTextId').html('Are you sure you want to report abuse in this goalshout?');	

			jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal });
			jQuery('#reportAbuseModal').jqmShow();
			
			jQuery("#acceptReportAbuseButtonId").unbind();
			jQuery('#acceptReportAbuseButtonId').click(function() {

				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/reportabuse';
				var teamid = '<?php echo $this->teamid;?>';
				var dataReport = jQuery('#textReportAbuseId').val();
				var reportType = jQuery('#reportTypeId').val();

				jQuery.ajax({
					type: 'POST',
					data:({teamid :teamid , id : id , dataReport : dataReport ,reportType:reportType}),
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/team/reportabuse',
					success: function(data){
						jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
						jQuery('#reportAbuseBodyId').hide();
						jQuery('#reportAbuseBodyResponseId').show();
						jQuery('#acceptReportAbuseButtonId').hide();
						jQuery('#cancelReportAbuseButtonId').attr('value','Close');
						jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
						top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamgoalshouts/id/<?php echo $this->teamId;?>';
			    		 
					}	
				})

				
			});	
		 }	
    	

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
            <h1><?php echo $this->team[0]['team_name']; ?> Gooal Shouts</h1>
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="SecondColumnProfile">
                          <div id="FriendsWrapper">
                          
                          		<div id="comment_formerror" class="ErrorMessageIndividual">Error: You must enter a comment.</div> 
                      			<div style="border-bottom:1px solid #CCCCCC;">

			                      <a name="boxcomments"></a> 

			                        <form id="comment_form" style="padding-bottom:30px;" method="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalShout">

			                            <textarea <?php if($session->email == null){ ?> 

			                            	disabled="disabled" <?php } ?>  onfocus="this.value=''; " onblur="" class="comments" id="commentGoalShoutId" style="width:673px;margin-bottom:10px;" name="comment" rows=5 cols=10>

			                            </textarea>

			                            <input type="hidden" name="commentType" id="commentTypeId" value="5">

				                        <input type="hidden" name="idtocomment" id="idtocommentId" value="<?php echo $this->team[0]['team_id'];?>">

				                        <input type="hidden" name="screennametocomment"  id="screennametocommentId" value="<?php echo $session->screenName;?>">

				                        <input type="hidden" name="teamId" id="teamId" value="<?php 	echo $this->team[0]['team_id'] 	?>">
                         				 <?php if($session->email == null){?>		

						                            		<input type="button" id="signInButtonId" value="Add Gooal Shout" class="submit blue" onclick="loginModal();">

				                            <?php	}else{ ?>

				                            		<input type="button" id="addGoalShoutId" value="Add Gooal Shout" class="submit blue">

				                            <?php	} ?> 

									 </form> 

			                   </div>	
                     

                                <?php
		                        $today = date ( "m-d-Y" );
		                        $yesterday = date ( "m-d-Y", (strtotime ( date ( "Y-m-d" ) ) - 1 * 24 * 60 * 60) );
		                        if ($this->totalTeamGoalShouts > 0) 
		                        {
		                        ?>
		
		                         <?php 
		                         	echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml');
		                         	$i = 1;
		                            foreach ( $this->comments as $uc ) 
		                            {
		                            		$activeCommentId = $uc['comment_id'];
		                        ?>
			                              <div id="comment_<?php echo $i;?>" class="boxComments">
			                            	<dl class="comment">
				                             	 <dt class="shout">
				                                	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>">
				                                  		<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>" />
				                                	</a>
				                              	</dt>
			                              	<dd>
			                                <span class="nameshout">
			                                  <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"><?php echo $uc["screen_name"];?></a>
			                                </span>
			                                <span class="date">
			                                  <?php
			                                        if ($today == date ( 'm-d-Y', strtotime ( $uc ["comment_date"] ) )) {
			                                            echo 'Today';
			                                        } else if ($yesterday == date ( 'm-d-Y', strtotime ( $uc ["comment_date"] ) )) {
			                                            echo 'Yesterday';
			                                        } else {
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
														
			                                 } 
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
			                               			<i>GoalShout was removed by owner</i>
			                                <?php 
						                        }
						                        else if ($uc['comment_deleted']=='2')
						                        {  
						                    ?>
			                               			<i>GoalShout was removed by profile owner</i>
			                                <?php 
						                        } 
						                    ?>
			                                </p>
							             	 <?php
							             	//If comment is not deleted, show the Comment and Ratings Controls
								             if (!in_array($uc['comment_deleted'], array("1", "2")))
								             {
								            ?>
									             <div class="commentsAndRatingsDetailed">
									             	
									                <span><a href="javascript:void(0)" onclick="toggleReplyContainerDisplay('#replyControlContainer<?php echo $activeCommentId;?>')">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;|</span>
									                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', 1)"><img width="19" height="25" alt="Rate This Activity Up" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_up.png"></a> <span id="activity<?php echo $activeCommentId;?>PlusCount">0</span>
									                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', -1)"><img width="19" height="25" alt="Rate This Activity Down" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_down.png"></a> <span id="activity<?php echo $activeCommentId;?>MinusCount">0</span>                
									                <input type="hidden" id="activity<?php echo $activeCommentId;?>HasBeenRated">
									        <?php  
									        		if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name'])
													{
											?>
										               <span>
										               		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/compose/<?php echo $uc['screen_name'];?>">|&nbsp;&nbsp;&nbsp;&nbsp;Send Private Message</a>
										            	</span>	
										            
										               <span class="reportThis">
										                	<a class="report" href="javascript:void(0)">Report This</a>
										               </span>
									        <?php
	
								             		}
								             ?>     
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
														<span id="reply<?php echo $reply['commentid'];?>"><?php echo $reply['activity_text']?>&nbsp;</span>
														
									                </div>
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
								             		<textarea id="text_comment<?php echo $activeCommentId;?>" rows="" cols="" style="width:417px;height:30px;" onfocus="javascript:if(this.value=='Add a comment...')this.value='';" onblur="javascript:if(this.value=='')this.value='Add a comment...';" onclick="javascript:jQuery('#replyControlId<?php echo $activeCommentId;?>').show()">Add a comment...</textarea>
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
			                          </div>
                                    <?php 
		                             $i++;
		                            	}  
                                    } 
                                    else 
                                    { 
                                ?>
                                      Be the first to leave a Goooal Shout for this team
                                <?php }  ?>
										
							<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
										
                                 </div>
                  </div>
                </div>
            </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

      </div><!--end Second Column-->

             
</div> <!--end ContentWrapper-->
