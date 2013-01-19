<script language="javascript">

jQuery(document).ready(function() {
	
	jQuery('#replyButtonMessageId').click(function(){ 
		jQuery('#messageDetail').jqmHide();
		top.location.href = '<?php echo Zend_Registry::get("contextPath");?>/compose/<?php echo $this->message["screen_name"];?>/<?php echo $this->message["id"];?>';
	 }); 

});	

</script>

 <?php require_once 'seourlgen.php'; ?> 
	<?php $urlGen = new SeoUrlGen ( ); ?>
 <div id="wrapper">

            <div id="ContentWrapper" class="TwoColumnLayout">
                
                <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">
                    <div class="FriendRequest">
                        <div class="img-shadow">
                            <div class="WrapperForDropShadow">
                                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                    <h4><?php if($this->messagetype == 'request') { ?>
                                    	FRIEND REQUEST
                                   		<?php }else if($this->messagetype == 'inbox' or $this->messagetype == 'sent'){?>
                                   		MESSAGE DETAIL
                                   		<?php }?>
                                   </h4>
                                    <div class="CloseButton jqmClose"></div>
                                </div>
                                <div id="friendRequest" class="FriendRequestWrapper">
                                <!--set the background image here-->
                                    <ul style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=70&h=70&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo($this->message["main_photo"]!=null?$this->message["main_photo"] :'ProfileMale.gif');?>')">
                                        <li>From:<strong><a href="<?php echo $urlGen->getUserProfilePage($this->message["screen_name"],True);?>"><?php echo($this->message["screen_name"]);?></a></strong></li>
                                        <li>Date:<strong><?php echo $this->message["date"];?></strong></li>
                                        <li>To:<strong><a href="<?php echo $urlGen->getUserProfilePage($this->message["owner"],True);?>"><?php echo($this->message["owner"]);?></a></strong></li>
                                        <li>Status<strong><?php echo $this->message["status"];?></strong></li>                                        
                                    </ul>
                                    Message
                                    <br />
                                    <textarea readonly="true"><?php echo $this->message["content"];?></textarea>
                                </div>
                                <ul class="ButtonWrapper">
                                    <?php if($this->message['typedescription'] == 'friend request') { ?>
                                    <li>
                                    	<?php if($this->message["status"] == 'Pending') { ?>
                                    	<input type="button" class="submit" value="Accept" id="acceptrequestid" onclick="acceptFriendRequest('<?php echo ($this->message["id"]) ;?>','<?php echo ($this->message["user_from_id"]) ;?>','<?php echo ($this->message["screen_name"]) ;?>')"/>
                                    	<input type="button" class="submit red" value="Reject" onclick="rejectFriendRequest('<?php echo ($this->message["id"]);?>')"/>
                                    	<?php }else {?>
                                    		<input type="button" class="submit jqmClose" value="Close" id="acceptrequestid"/>
                                    	<?php }?>	
                                    	<li class="Last"><input type="button" class="submit blue" value="Send Reply" id="replyButtonMessageId" /></li>
                                    </li>
                                    <?php }?>
                                    <?php if($this->message['typedescription'] == 'private message') { ?>
                                    <input type="button" class="submit jqmClose" value="Accept" id="acceptrequestid"/>
                                    <li class="Last"><input type="button" class="submit blue" value="Send Reply" id="replyButtonMessageId" /></li>
                                    <?php }?>
                                    <?php if($this->messagetype == 'sent' or $this->messagetype == 'deleted') { ?>
                                    <input type="button" class="submit jqmClose" value="Close" id="acceptrequestid"/>
                                     <?php }?>
                                     
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!--end SecondColumnOfTwo and #SecondColumnPlayerProfile-->

                  
             </div> <!--end ContentWrapper-->
        </div> <!--end wrapper-->
  