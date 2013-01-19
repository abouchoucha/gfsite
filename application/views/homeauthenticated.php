<!--Add Favorite modal--> 

<div id="addFavoriteHome" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li id="messageConfirmationTextId">To follow a specific player, team or league, simply add
                     them to your <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities">favorites</a> and their updates will appear below</li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li><input type="button" id="acceptModalButtonId" class="submit" value="Ok"/>
                <input type="button" id="cancelModalButtonId"  class="submit jqmClose" value="Cancel"/></li>
            </ul>
        </div>
    </div>
 </div> 
 
 
 
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

    	<?php if($session->firsttimeviewprofile =='true') { ?>
			jQuery('#inlineProfileWelcomeMessage').show();
    	<?php } ?>

		jQuery(".inlineMessageWide .closemessage").click(function(){
		jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
 		});

        

		jQuery("#message_wall").charCounter(140);
    	callMyResults('played','menu1content');
    	callStuffUpdates('data_updates');
    	callAllActivites('data_activities');
    	jQuery('#buttonActivity').click(function(){ //
			callStuffUpdates('data_updates');
		 }); 
    	jQuery('#buttonActivity2').click(function(){ //
    		callAllActivites('data_activities');
		 });

		//setup tabs - scores and schedules
        jQuery('#menu1content,#menu2content').hide();
        jQuery('#menu1content').show();
        jQuery('#menu1').addClass('active');
        jQuery('#tab_scores ul li').click(function(){
            jQuery('#menu1content,#menu2content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu1,#menu2').removeClass('active');
            jQuery(this).addClass('active');
        });

        //setup tabs - updates
        jQuery('#menu3content,#menu4content').hide();
        jQuery('#menu3content').show();
        jQuery('#menu3').addClass('active');
        jQuery('#tab_updates ul li').click(function(){
            jQuery('#menu3content,#menu4content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu3,#menu4').removeClass('active');
            jQuery(this).addClass('active');
        });

        
       	 jQuery("#postbroadcastId").click(function() {
        	var broadcast_message = jQuery('#message_wall').attr('value');
        	var type = jQuery('#typeBroadcastId').val();

        	if(broadcast_message == '' || broadcast_message == 'What do you have to broadcast?'){
				return;
            }
            
			jQuery.ajax({
        	type: "POST",
        	url: "<?php echo Zend_Registry::get("contextPath"); ?>/index/addbroadcast",
        	data:"message_wall="+ broadcast_message +"&type="+type,
        	success: function(){
	        	callAllActivites();
        		jQuery('#message_wall').val('Write a comment');
        	}
        	});
        	return false;
         });



        	jQuery('#mySchedulesTab').click(function() {
        		callMyResults('fixture','menu2content');
        		jQuery('#menu1content').hide();
    		});

        	jQuery('#myScoresTab').click(function() {
        		callMyResults('played','menu1content');
        		jQuery('#menu2content').hide();
    		});


    		
        

    });

    
    function callMyResults(date,div){
    	 var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
    	 var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	   	 jQuery('#'+div).addClass('ajaxloadtabs');
	  	 jQuery.ajax({
	          method: 'get',
	          url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showscoreschedule/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime+'/limit/5/date/'+date,
	          dataType : 'text',
	          success: function (text) {
				  if(jQuery.trim(text) !=''){
			  		  jQuery('#'+div).removeClass('ajaxloadtabs');
		              jQuery('#'+div).html(text);
	              }else{
					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
		          }    
	           }
	       });

     }

    
	//load add favorites alert modal 
    function addFavoritesHomeModal() {
        jQuery('#addFavoriteHome').jqm({ onHide: closeModal ,modal:true});
        jQuery('#addFavoriteHome').jqmShow();
 	 }
  

    //Loads User Community Updates
    function callStuffUpdates(div){
    	jQuery('#'+div).addClass('ajaxloadtabs');
    	var activityId = jQuery("#activityId").val();
    	jQuery.ajax({
            method: 'post',
            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmyupdates/limit/10',
            dataType : 'text',
            data : { activityId : activityId },
            success: function (text) {
    			  if(jQuery.trim(text) !=''){
			  		  jQuery('#'+div).removeClass('ajaxloadtabs');
				  		jQuery('#data_updates').html(text);
	              }else{
					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
		          }    
             }
         });
	}
	setInterval(callStuffUpdates,300000);

    //Loads User Community Activity and Broadcasts
	function callAllActivites(div){
			jQuery('#'+div).addClass('ajaxloadtabs');
			var activityId = jQuery("#activityId2").val();
	    	jQuery.ajax({
	            method: 'post',
	            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showactivities/haslimit/y',
				data : { activityId : activityId },
	            dataType : 'text',
	            success: function (text) {
	    			  if(jQuery.trim(text) !=''){
				  		    jQuery('#'+div).removeClass('ajaxloadtabs');
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


	function deleteGoalShout(id)
	{

		 jQuery('#acceptModalButtonId').show();
		 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalTitleConfirmationId').html('DELETE COMMENT?');
		 jQuery('#messageConfirmationTextId').html('Are you sure you want to delete this comment?');	
		 
				    
		 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal });
		 jQuery('#messageConfirmationId').jqmShow();
		 
		 jQuery("#acceptModalButtonId").unbind();
			
		 jQuery('#acceptModalButtonId').click(function(){
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/index/removegoalshout/id/'+id;
			 jQuery.ajax({
					type: 'GET',
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/index/removegoalshout/id/'+id + '/redirect/myhome',
					success: function(data){
						
					 	 jQuery('#messageConfirmationTextId').html('Your comment has been deleted.');
						 jQuery('#acceptModalButtonId').hide();
						 jQuery('#cancelModalButtonId').attr('value','Close');
						 jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
						 top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/myhome';
						 //alert(data);
					}	
				})
			 
		 });	
	}
    
    
</script>


<div style="width:917px; margin-left: 0px;" class="inlineMessageWide alertSucess closeDiv" id="inlineProfileWelcomeMessage">
    <p id="successMessageId" style="width:850px;">Welcome to GoalFace!&nbsp;Add <a href="<?php echo $urlGen->getClubsMainUrl(true); ?>" title="Football Teams">your favorite teams</a> and <a href="<?php echo $urlGen->getPlayersMainUrl(true); ?>" title="Football Players">your favorite players</a> so you follow them on your dashboard.</p>
 <span class="closemessage"></span>
</div>

<div id="ContentWrapper" class="TwoColumnLayout">

     <div class="FirstColumn">	
     		<?php echo $this->render('include/topleftbanner.php')?>
     
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
            <!--<p class="ad">< img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/adspot.jpg" alt="" width="720px"/></p> -->
            	<div class="aleft">
            	
            	
                    <!-- Scores & Schedules -->
                    <h1>My Scores &amp; Schedules </h1>
                    <div id="tab_scores" class="tabs">
                            <ul>
                               <li id="menu1"><a id="myScoresTab" href="javascript:void(0);">Scores</a></li>
                               <li id="menu2"><a id="mySchedulesTab" href="javascript:void(0);">Schedules</a></li>
                            </ul>
                    </div>
                    
                    	<div class="regional" id="menu1content">
                        	
                    	</div>
                    
                    	<div class="regional" id="menu2content" style='display:none;'>
                            
                    	</div>
                    	
                    <!-- /Scores & Schedules -->
                    
                    
                    <!-- Updates & Alerts -->
                    <h2>Updates<a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/showrssfeedupdates/email/<?php echo $session->email;?>/key/<?php echo $session->user->salt;?>">
                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/rss1.jpg" alt=""/>
                    		</a>
                   </h2>
                    <div id="tab_updates" class="tabs">
                         <ul>
                             <li id="menu3"><a href="javascript:void(0);">Updates</a></li>
                             <!-- li id="menu4"><a href="javascript:void(0);">Alerts</a></li> -->
                         </ul>
                    </div>
                    
                    <div class="regional" id="menu3content">
                    	
                    	 <table border="0" class="table" cellpadding="5" cellspacing="0">
                    	   <tr bgcolor="#ffffff">
                               <td align="right">
                             <?php  if($session->screenName == 'chocheraz' or $session->screenName == 'FCkokus' or $session->screenName == 'JohnGakau' or $session->screenName == 'irsymeon' or $session->screenName == 'BarcelonaFan' or $session->screenName == 'Kelly Matthews'){?>  
                               <a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"settings");?>/#updates">Manage Updates</a> | 
                             <?php } ?>  
                               <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities">Find Favorites</a>
                               </td>
                           </tr>
                    	 </table>
                    
                         <table border="0" class="table" cellpadding="5" cellspacing="0">
                        		
                                <tr bgcolor="#ffffff">
                                        <td>
                                                Show:
                                                <select id="activityId" class="sell">
                                                        <option value="0">All Updates</option>
                                                        <!-- option value="1">Leagues &amp; Tournaments Only</option> -->
                                                        <option value="2">Player Only</option>
                                                        <option value="3">Team Only</option>
                                                </select>                                              
                                                <input type="submit" class="submit" value="Ok" id="buttonActivity" style="display: inline;">
                                        </td>
                                        <!-- td align="right"><a href="#">Manage Updates</a></td> -->
                                </tr>
                         </table>
                         <div id="data_updates">
                        
                         </div>
                     </div>
                    
                    
                    
                    <!-- /Updates & Alerts -->

                    <!-- Activity & Broadcasts -->
                    <h2>Activity &amp; Broadcasts <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/showrssfeedactivity/email/<?php echo $session->email;?>/key/<?php echo $session->user->salt;?>"><img src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/rss1.jpg" alt=""/></a></h2>
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
	                         			<?php }  else{ ?>
	                         					<a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">     		
													<?php $result = $session->fbuser; ?>
														<img src="https://graph.facebook.com/<?php echo $result['id']; ?>/picture">
												</a>
	                         			<?php }   ?>
                         			</td>
                        			<td>
		                                <form id="submit_broadcast">
			                                <div class="post">
			                                        <textarea id="message_wall" cols="" rows="" onfocus="javascript:if(this.value=='What do you have to broadcast?')this.value='';" onblur="javascript:if(this.value=='')this.value='What do you have to broadcast?';">What do you have to broadcast?</textarea>
			                                        <div class="share">Share thoughts, links, and more...</div>
			                                        <!-- div class="t1"><p class="cbox"><input type="checkbox" value=""/></p> <p class="t"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/t.jpg" alt=""/></a></p></div> -->
			                                        <div class="include">Include:
			                                             <select id="typeBroadcastId" name="typeBroadcastId" class="sell">
			                                                 <option value="0">Friends</option>
			                                                 <option value="1">Everyone</option>
			                                                </select>
			                                        </div>
			                                </div>
			                      
			                                <span class="po">
					                            <input type="button" name="Post" value="Post" id="postbroadcastId">
					                        </span>   
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
                    <ul id="wall">
					</ul>
                    <div id="data_activities">
                    </div>
                    </div>
                    <!-- /Friends Activity & Broadcasts -->

                </div>
        </div>
    </div><!--end SecondColumnOfTwo -->

</div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
           
