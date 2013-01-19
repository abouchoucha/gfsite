<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

jQuery(document).ready(function() {
	jQuery("#commentGoalShoutId").charCounter(400); 
	jQuery('#totalMatchCommentsId').html('GoalTalk (<?php echo $this->totalMatchComments;?>)');

});


</script>


<?php $session = new Zend_Session_Namespace('userSession');?>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_box.phtml'); ?>
        
        <div id="boxComments">
           <div id="comment_formerror" class="ErrorMessageIndividual">You must enter a Goooal Shout.</div>
              <div class="comment_input">
                <form id="comment_form" action="post" name="comment_form" action="">
                <?php
                    if($session->email == null){
                        $message = "Sign in in order to leave a Goooal Shout";
                    }else{
                        $message = "What do you have to say...";
                    }
                  ?>
                  <textarea <?php if($session->email == null){ ?>
                            disabled="disabled" <?php } ?> class="comment_text" id="commentGoalShoutId" name="comment" onfocus="this.value='';"><?php echo $message; 	?>

                   </textarea>
                  <input type="hidden" name="commentType" id="commentTypeId"	value="2">
                  <input type="hidden" name="idtocomment" id="idtocommentId"	value="<?php echo $this->match[0]["match_id"];?>">
                  <input type="hidden" name="screennametocomment"  id="screennametocommentId"	value="<?php echo $session->screenName; 	?>">
                  <input type="hidden" name="matchname" id="matchnameId" value="<?php echo $this->match[0]["t1"];?> vs. <?php echo $this->match[0]["t2"];?>">
                  <input type="hidden" name="matchid" id="matchid" value="<?php echo $this->match[0]["match_id"];?>">
                        <?php if($session->email == null){?>
                                <input class="submit" type="button" value="Leave Goooal Shout"  id="signInButtonId" onclick="loginModal()"/>
                        <?php	}else{ ?>
                                <input class="submit" type="button" value="Leave Goooal Shout" onclick="addGoalShout()" />
                        <?php	} ?>


                </form>
            </div>

            <?php
                $today = date ( "m-d-Y" );
                $yesterday = date ( "m-d-Y", (strtotime ( date ( "Y-m-d" ) ) - 1 * 24 * 60 * 60) );
             foreach ( $this->paginator as $uc ) {
            ?>
          <dl class="comment">
            <dt>
              <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>">
                <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>" />
              </a>
            </dt>
            <dd>
              <span class="name">
                <a href="<?php echo Zend_Registry::get("contextPath");?>/profiles/<?php echo $uc ["screen_name"];?>">
                <?php echo $uc ["screen_name"];?></a>
              </span>&nbsp;
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
              <?php if($session->screenName == $uc['screen_name']){
              			if($uc['comment_deleted']=='0'){?>
			               <a class="edit" id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["comment_id"];?>')">Edit</a>
			               <a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
	               		<?php }?>
              <?php } ?>
              <p id="<?php echo 'goalshout' .$uc['comment_id']?>"><?php echo ($uc['comment_deleted']=='0'?$uc["comment"]:'<i>GoalTalk was removed by owner</i>');?></p>
              <?php if($uc['comment_deleted']=='0'){?>
              	<span class="abuse"><a class="warning" href="javascript:reportAbuse('<?php echo $uc['comment_id'];?>','<?php echo $uc['friend_id'];?>');">Report This</a></span>
              <?php }?>
              </dd>
            </dl>
            <?php
            	}
            
            ?>
            
  
        </div>
		