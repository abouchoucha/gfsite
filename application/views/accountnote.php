<script type="text/JavaScript">

jQuery(document).ready(function() {

	jQuery('#totalMatchCommentsId').html('GoalTalk (<?php echo $this->totalAcctComments;?>)');

});


</script>


<?php $session = new Zend_Session_Namespace('userSession');?>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_box.phtml'); ?>
        
        <div id="boxComments">
           <div id="comment_formerror" class="ErrorMessageIndividual">You must enter a Goooal Shout.</div>
              <div class="comment_input">
                <form id="comment_form" action="post" name="comment_form" action="">
                <?php
                        $message = "What do you have to say...";
                  ?>
                  <textarea class="comment_text" id="commentGoalShoutId" name="comment" onfocus="this.value='';"><?php echo $message; 	?>

                   </textarea>
                  <input type="hidden" name="idtocomment" id="idtocommentId"	value="<?php echo $this->user_id;?>">
                  <input type="hidden" name="screennametocomment"  id="screennametocommentId"	value="<?php echo $session->screenName; 	?>">
                  <input class="submit" type="button" value="Add Note" onclick="addNote()" />


                </form>
            </div>

            <?php
                $today = date ( "m-d-Y" );
                $yesterday = date ( "m-d-Y", (strtotime ( date ( "Y-m-d" ) ) - 1 * 24 * 60 * 60) );
             foreach ( $this->paginator as $uc ) {
		?>
          <dl class="comment">
            <dt>
            </dt>
            <dd>
              <span class="name">
                <a href="<?php echo Zend_Registry::get("contextPath");?>/profiles/<?php echo $uc ["admin_id"];?>">
                <?php echo $uc ["admin_id"];?></a>
              </span>&nbsp;
              <span class="date">
                <?php
                		if ($today == date ( 'm-d-Y', strtotime ( $uc ["date_added"] ) )) {
                			echo 'Today';
                		} else if ($yesterday == date ( 'm-d-Y', strtotime ( $uc ["date_added"] ) )) {
                			echo 'Yesterday';
                		} else {
                			echo date ( ' F j , y', strtotime ( $uc ["date_added"] ) );
                		}
                ?>
                &nbsp;at
                <?php
                		echo date ( ' g:i a', strtotime ( $uc ["date_added"] ) );
                ?>
              </span>
              <?php if($session->screenName == $uc['admin_id']){
              			if($uc['note_state']=='0'){?>
			               <a class="edit" id="edit<?php echo $uc ["note_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["note_id"];?>')">Edit</a>
			               <a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['note_id'];?>');">Delete</a>
	               		<?php }?>
              <?php } ?>
              <p id="<?php echo 'goalshout' .$uc['note_id']?>"><?php echo ($uc['note_state']=='0'?$uc["note"]:'<i>GoalTalk was removed by owner</i>');?></p>
              </dd>
            </dl>
            <?php
            	}
            
            ?>
            
  
        </div>
		
