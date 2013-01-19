<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
	$common = new Common();
?>
<div id="comments_list">
<?php
	    $i = 1;
       foreach($this->paginator as $activity)
       {
       		if($i % 2 == 1) {$style = "odd_row";}else{$style = "even_row";}
         ?>
         <div id="comment_<?php echo $i;?>" class="friends_area <?php echo $style;?>">
             <div class="leftcomment">
                <a href = "<?php echo $urlGen->getUserProfilePage($activity["screen_name"],True);?>">
                
	                 <?php  if ($activity["main_photo"]!=null || $activity["main_photo"]!='') { ?>   
	                                 
	                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $activity["main_photo"]; ?>" alt=""/>
	
	                <?php } else {  ?>
	
	                    <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="">
	
	                <?php }   ?>
	            
                </a>
             </div>
             
             <div class="rightcomment">
             	<?php
//             	/echo "<p>Activity Type: " . $activity['activitytype_icon'] .", comment_id: " . $activity['commentid']. " Deleted: " . $activity['commentdeleted'] . "</p>";
             	//var_dump($activity);
             	//Check whether to display activity text or removal text
             	$activityText = $activity['activity_text'];
             	$activityCommentType = "Comment";
             	$commentDeleted = false;
       			//Let's get the comment id
            	$activeCommentId = $activity['commentid'];
            	$isActivityComment = "false";
            	if(empty($activeCommentId))
            	{
            		//We are commenting on an activity
            		$activeCommentId = $activity['activity_id'];
            		$isActivityComment = "true";
            	}
             	if($activity['activitytype_icon'] == 'y')
             	{
             		$activityCommentType = "Goooal Shout";
             	}
             	
             	if ($activity['commentdeleted']=='1')
             	{
             		$activityText = "<i>{$activityCommentType} was removed by owner</i>";
             		$commentDeleted = true;
             	}
             	else if ($activity['commentdeleted']=='2')
             	{
             		$activityText = "<i>{$activityCommentType} was removed by the profile owner</i>";
             		$commentDeleted = true;
             		
             	}
             	
             	//If this is goal shout 
             	if($activity['activitytype_icon'] == 'y')
             	{
             		
             		?>
             	   <span class="author">
	             	<a href="<?php echo $urlGen->getUserProfilePage($activity["screen_name"],True);?>">
	             		<?php echo $activity['screen_name'];?>
	             	</a> 
	               </span>
	             <font style="color:#000;"><?php if(!$commentDeleted){?>broadcast:<?php }?></font>
		         <span id="<?php echo 'goalshout' .$activeCommentId?>"> <?php echo $activityText;?> </span>
	            <?php 
             	}
             	else 
             	{
             		echo $activityText;
	            }
	            ?>	
	             
	             <span class="date"><?php  echo $common->convertDates($activity["activity_date"]);?></span>
	             <?php if($activity['activitytype_icon'] == 'y')
	             {
	            	if($session->screenName == $activity['screen_name'] && !$commentDeleted)
	            	{
	            		
				?>		                        	           
						<a class="edit" id="edit<?php echo $activeCommentId;?>" href="javascript:editGoalShout('<?php echo $activeCommentId;?>')">Edit</a> |
				<?php 
					} 
					if($this->isMyProfile == 'y' or $session->screenName == $activity['screen_name'] && !$commentDeleted )
					{
				?>    
						<a class="edit" href="javascript:deleteGoalShout('<?php echo $activeCommentId;?>');">Delete</a>
				<?php 
					}
	             }
	             ?>
             </div> 
             
             
             <br clear="all">
             
             <?php 
             if($activity['activitytype_icon'] == 'y')
             { ?>
            <!--  <div class="sendmessage">
             	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/compose/<?php echo $activity['screen_name'];?>">Send Message</a>
             </div> -->
             <?php
             }
             //If comment is not deleted, show the Comment and Ratings Controls
             if (!in_array($activity['commentdeleted'], array("1", "2")))
             {
             ?>
             <div class="commentsAndRatings">
             	
                <span><a href="javascript:void(0)" onclick="toggleReplyContainerDisplay('#replyControlContainer<?php echo $activeCommentId;?>')">Comment</a>&nbsp;&nbsp;&nbsp;&nbsp;|</span>
                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', 1)"><img width="19" height="25" alt="Rate This Activity Up" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_up.png"></a> <span id="activity<?php echo $activeCommentId;?>PlusCount">0</span>
                <a href="javascript:void(0)" onclick="rateActivity('<?php echo $activeCommentId;?>', -1)"><img width="19" height="25" alt="Rate This Activity Down" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hand_down.png"></a> <span id="activity<?php echo $activeCommentId;?>MinusCount">0</span>                
                <input type="hidden" id="activity<?php echo $activeCommentId;?>HasBeenRated">
               <?php 
                if($activity['activitytype_icon'] == 'y')
             	{ 
               ?>
               <span>
               		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/compose/<?php echo $activity['screen_name'];?>">|&nbsp;&nbsp;&nbsp;&nbsp;Send Private Message</a>
            	</span>	
             
               <span >
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
             	if($isActivityComment === 'true')
             	{
             		$replies = $activityObj->findActivityComments($activeCommentId,null );
             	}
             	else
             	{
             		$replies = $activityObj->findRepliesByBroadcast($activeCommentId,null );
             	}
             	//var_dump($replies);
           		foreach($replies as $reply)
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
            <?php } 
            ?>
             </div>
          	
            <?php 
            if (!in_array($activity['commentdeleted'], array("1", "2")))
            {
            	
            ?>
          
             <div  id="replyControlContainer<?php echo $activeCommentId;?>" class="comment_form" style="display:none">
             	<div id="" class="leftside">
             		<a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>">
             		<?php  if ($activity["main_photo"]!=null || $activity["main_photo"]!='') { ?>   
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $session->mainPhoto;?>" alt=""/>
					<?php } else {  ?>
					<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="">
					<?php } ?>
					</a>
             	</div>
             	<div class="rightside">
             		<textarea id="text_comment<?php echo $activeCommentId;?>" rows="" cols="" style="width:417px;height:30px;" onfocus="javascript:if(this.value=='Write a comment...')this.value='';" onblur="javascript:if(this.value=='')this.value='Write a comment...';" onclick="javascript:jQuery('#replyControlId<?php echo $activeCommentId;?>').show()">Write a comment...</textarea>
             		<div id="replyControlId<?php echo $activeCommentId;?>" class="replyControlsContainer">
	             		<input type="button" onclick="javascript:sendReply( '<?php echo $activeCommentId;?>', <?php echo $isActivityComment;?>);" value="Add Comment" /> 
	             		&nbsp;&nbsp;or&nbsp;&nbsp; <a href="javascript:void(0)" onclick="javascript:jQuery('#replyControlId<?php echo $activeCommentId;?>').hide()">Cancel</a>
             		</div>
             	</div> 
             	<div style="clear:both"></div>
             </div>
             <?php 
            }?>     
         </div>
	<?php 
	$i++; 
	} 
 	?>

	     <?php if($this->limit == 10){?>

           
           <div class="SeeMoreNews">
              <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreactivities" class="OrangeLink">See More Activty &amp; Broadcasts &raquo;</a>
           </div>
           
           
           
           <?php }else {?>
           <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div_simple.phtml',array('ajaxdata'=>'data_activities'));  ?>
          <?php }?>
</div>

