<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
	$common = new Common();
?>

<script type="text/JavaScript">
    jQuery(document).ready(function(){

    	jQuery("#message_wall").charCounter(140);
    	callAllActivites();
    	 
    	jQuery('#buttonActivity2').click(function(){ //
    		callAllActivites();
		 });

       	 jQuery("#postbroadcastId").click(function() {

        	var broadcast_message = jQuery('#message_wall').attr('value');
			jQuery.ajax({
        	type: "POST",
        	url: "<?php echo Zend_Registry::get("contextPath"); ?>/index/addbroadcast",
        	data:"message_wall="+ broadcast_message,
        	success: function(){
				//jQuery("ul#wall").prepend("<li style='display: none;'>"+broadcast_message+"</li>");
        		//jQuery("ul#wall li:first").fadeIn();
				callAllActivites();
        		jQuery('#message_wall').val('What do you have to broadcast?');
        	}
        	});
        	return false;
         });

        jQuery("span.commentsControl a.edit").click(function() {

            var broadcast_message = jQuery('#message_wall').attr('value');
    		jQuery.ajax(
    	    {
	            type: "POST",
	            url: "<?php echo Zend_Registry::get("contextPath"); ?>/common/editgoalshout",
	            data:"message_wall="+ broadcast_message,
	            success: function(){
	    			callAllActivites();
	            }
            });
            return false;
             });
	});


	 function callAllActivites(){
	        jQuery('#data_activities').html("<div class='ajaxload widgetlong'></div>");
	    	var activityId = jQuery("#activityId2").val();
	    	jQuery.ajax({
	            method: 'post',
	            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showactivities',
	            dataType : 'text',
	            data : { activityId : activityId },
	            success: function (text) {
	                jQuery('#data_activities').html(text);
	                if(text !=''){
				  		 jQuery('#data_activities').html(text);
		              }else{
						top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
			          } 
	             }
	         });
		}
		//setInterval(callAllActivites,300000);
	



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
					var url = '<?php echo Zend_Registry::get("contextPath"); ?>/index/editgoalshout';
					var dataEditted = jQuery('#textgoalshoutEdit').val();
					var redirectAction = 'myhome';
					jQuery.ajax({
						type: 'POST',
						data: ({redirect:'myhome', id : id , dataEditted : dataEditted}),
						url: '<?php echo Zend_Registry::get("contextPath"); ?>/index/editgoalshout',
						success: function(data){
							jQuery('#editGoalShoutModal').jqmHide();
							top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/myhome';
				    		 
						}	
					})
				});
		}



	function deleteGoalShout(id, isComment)
	{

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
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/removeplayergoalshout/playerid/<?php echo $this->playerid;?>/id/'+id;
			 jQuery.ajax({
					type: 'GET',
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/player/removeplayergoalshout/playerid/<?php echo $this->playerid;?>/id/'+id,
					success: function(data){
					 	 jQuery('#messageConfirmationTextId').text(deleteMessage);
						 jQuery('#acceptModalButtonId').hide();
						 jQuery('#cancelModalButtonId').attr('value','Close');
						 jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
						 top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamgoalshouts/id/<?php echo $this->teamId;?>';
			    		 
					}	
				})
			 
		 });	
	}	


    
    
</script>

<div id="ContentWrapper" class="TwoColumnLayout">

     <div class="FirstColumn">	
               <?php 
                    $session = new Zend_Session_Namespace ( 'userSession' );
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
                
               <?php } ?>

				
				<div class="img-shadow" id="leftnav">
				    <?php include 'include/myauthleftnavigation.php';?>
                </div>
				
				
				
				
				
				
     </div><!--end FirstColumnOfThree-->

     <div id="SecondColumnFullHome" class="SecondColumnOfTwo">
        <div class="ammid">
            <!-- <p class="ad">Ad image here</p> -->
            	<div class="aleft">
                    <!-- Friends Activity & Broadcasts -->
                    <h2>Activity &amp; Broadcasts <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/showrssfeedactivity/email/<?php echo $session->email;?>/key/<?php echo $session->user->salt;?>"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/rss1.jpg" alt=""/></a></h2>
                    <div class="regional">
                    <table border="0" class="table1" cellpadding="5" cellspacing="0">
                            <tr bgcolor="#ffffff">
                                    <td align="center">
                                    <?php if ($session->fbuser == null) { ?>
	                                    <?php if ($session->mainPhoto != null) { ?>
									       <a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"photo");?>">
									        <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=62&h=63&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $session->mainPhoto;?>" />
									       </a>
									    <?php } else {  ?>
									 
								      		<a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"photo");?>">
									        	<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=62&h=63&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale30.gif" />
									       	</a>
										<?php }   ?>
									    <a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"photo");?>">Manage<br/> Photo</a>
									<?php }  else { ?>
                         				<a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">     		
											<?php $result = $session->fbuser; ?>
											<img src="https://graph.facebook.com/<?php echo $result['id']; ?>/picture">
										</a>
                         			<?php }  ?>
									</td>
                                    <td>
                                            <form id="submit_broadcast">
                                            <div class="post">
                                                    <textarea id="message_wall" cols="" rows="" onfocus="javascript:if(this.value=='What do you have to broadcast?')this.value='';" onblur="javascript:if(this.value=='')this.value='What do you have to broadcast?';">What do you have to broadcast?</textarea>
                                                    <div class="share">Share thoughts, links, and more...</div>
                                                    <!-- div class="t1"><p class="cbox"><input type="checkbox" value=""/></p> <p class="t"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/t.jpg" alt=""/></a></p></div> -->
                                                    <div class="include">Include:
                                                                            <select class="sell">
                                                                            <option>Everyone</option>
                                                                            </select>
                                                    </div>
                                            </div>
                                            <p class="po"><a href="#" id="postbroadcastId">Post</a></p>
                                            </form>
                                    </td>
                            </tr>

                    </table>
                    <table border="0" class="table" cellpadding="5" cellspacing="0">
                            <tr bgcolor="#ffffff">
                                    <td>
                                            Show:
                                            <select id="activityId2" class="sell">
                                                    <option value="0">All Activity &amp; Broadcasts</option>
                                                    <option value="1">Friends Activity Only</option>
                                                    <option value="2">Friends Broadcasts Only</option>
                                                    <option value="3">My Activity Only</option>
                                                    <option value="4">My Broadcasts Only</option>
                                            </select>
                                            <input style="display:inline;" type="submit" id="buttonActivity2" value="Ok" class="submit">
                                    </td>
                                    <!--  td align="right"><a href="#">Manage Activity &amp; Broadcast Updates</a></td>-->
                            </tr>
                    </table>
                    <div id="data_activities">
                    </div>
                    </div>
                    <!-- /Friends Activity & Broadcasts -->

                </div>
        </div>
    </div><!--end SecondColumnOfTwo -->

</div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
