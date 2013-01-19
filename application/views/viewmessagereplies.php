		<?php 
             require_once 'Activity.php';
             require_once 'seourlgen.php';
    		 $urlGen = new SeoUrlGen();
             $common = new Common();
             $session = new Zend_Session_Namespace('userSession');
             $activityObj = new Activity(); 
             $replies = array();
             if($this->isActivityComment === 'true')
             {
             	$replies = $activityObj->findActivityComments($this->commentid,null );
             }
             else
             {
             	$replies = $activityObj->findRepliesByBroadcast($this->commentid,null );
             }
             foreach($replies as $reply){
          ?>
            <div id="comment_reply" class="nestedwrapper">
            	<div class="leftcomment_nested">
                	<a href = "">
		                 <?php  
		                 if ($reply["main_photo"]!=null || $reply["main_photo"]!='') 
		                 { 
		                 ?>   
		                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $reply["main_photo"]; ?>" alt=""/>
						<?php 
		                 } 
		                 else 
		                 {  
		                 ?>
							<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="">
						<?php 
		                 } 
		                 ?>
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
             <?php } ?>
