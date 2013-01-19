 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 


<script language="javascript">

var closeModalMessages = function(h) { 
    //t.html('Please Wait...');  // Clear Content HTML on Hide.
    h.o.remove(); // remove overlay
    h.w.fadeOut(888); // hide window
    top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/<?php echo $this->messageType; ?>';
};

jQuery(document).ready(function() {
	
	jQuery(".inlineMessageWide .closemessage").click(function(){
		jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
	 }); 
	
	<?php if($this->sentok == 'ok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message was successfully sent.');
	<?php }?>
	<?php if($this->sentok == 'deleteok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message(s) were successfully deleted.');
	<?php }?>
	<?php if($this->sentok == 'readok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message(s) were successfully mark as read.');
	<?php }?>
	<?php if($this->sentok == 'unreadok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message(s) were successfully mark as unread.');
	<?php }?>
	<?php if($this->sentok == 'movetoinboxok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message(s) were successfully move to your inbox');
	<?php }?>
	
	<?php if($this->sentok == 'pdeleteok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your message(s) were successfully permantly deleted.');
	<?php }?>
	<?php if($this->sentok == 'acceptok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your request(s) were successfully accepted.');
	<?php }?>
	<?php if($this->sentok == 'rejectok') {?>
		jQuery('#alertSuccessMessageId').show();
		jQuery('#successMessageId').html('Your request(s) were successfully rejected.');
	<?php }?>
		jQuery('#alertSuccessMessageId').animate({opacity: 'hide'}, 5000);
	<?php if($this->sentok == 'ko') {?>
		jQuery('#alertErrorMessageId').show();
		jQuery('#errorMessageId').html('Your message was cancelled.');
	<?php }?>
	
	jQuery('#messageFilterSelectId').change(function(){ 
		filterMessages();
	 }); 
	
	jQuery('input:button#composebuttonid').click(function(){
		top.location.href = '<?php echo Zend_Registry::get("contextPath");?>/message/gocompose';
	});	
	jQuery('input:checkbox#messegeActionId').click(function(){
		clickUnClickCheckBoxes(this.checked);
	 });

	jQuery('#readButtonId,#readButtonId2').click(function(){
		updateMessage('3');
	 });
	jQuery('#unreadButtonId,#unreadButtonId2').click(function(){
		updateMessage('4');
	 });
	jQuery('#deleteButtonId,#deleteButtonId2,').click(function(){
		updateMessage('5');
	 });
	 jQuery('#movetoInboxButtonId,#movetoInboxButtonId2').click(function(){
		updateMessage('3' , 'movetoInbox');
	 });
	 jQuery('#acceptButtonId,#acceptButtonId2').click(function(){
		updateMessage('10');
	  });
	 jQuery('input:button#rejectButtonId').click(function(){
		updateMessage('11');
	  });
	 buttonDeleteAction('message','permanently delete' ,'deletePermanentButtonId');

});		

function buttonDeleteAction(type , action ){

		jQuery('#deletePermanentButtonId,#deletePermanentButtonId2').click(function(){

		var cont = 0;
		var query_string = '';

		jQuery("input[name='arrayMessages']").each(
			function() 
			 { 
				if(jQuery(this).is(':checked')){ 
					query_string += "&arrayMessages[]=" + jQuery(this).val();
					cont++; 
				} 
			}
		);
		jQuery('#acceptModalButtonId').unbind();
		if(cont == 0){
			jQuery('#modalTitleConfirmationId').html(action + ' '+type);
			jQuery('#messageConfirmationTextId').html('You do not have any message(s) selected.  Select the message(s) you want to '+action+ '.' );
			jQuery('#acceptModalButtonId').addClass('jqmClose');
			jQuery('#messageConfirmationId').jqm({onHide: closeModal });
			jQuery('#messageConfirmationId').jqmShow();
			return false;
		}
		jQuery('#modalTitleConfirmationId').html(action + ' '+type);
		jQuery('#messageConfirmationTextId').html('Are you sure you want to '+action+ ' the selected message(s)?');
		jQuery('#messageConfirmationId').jqm({onHide: closeModal });
		jQuery('#messageConfirmationId').jqmShow();
		jQuery('#acceptModalButtonId').click(function(){

			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/messagedelete';
	
			jQuery.ajax({
		        type: 'POST',
		        url : url,
		        data : "id=1"+ query_string ,
		 		success: function (text) {
		 			top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/<?php echo $this->messageType; ?>/1/pdeleteok';
		 		}	
			});	
		});	
		
		return false;
		
	});

}


function clickUnClickCheckBoxes(checked){
	jQuery("input[name='arrayMessages']").each(		
			function() 
			{ 
				jQuery(this).attr('checked',checked); 
		   	});

}

function noSelectedValuesMessage(action){

	jQuery('#modalTitleConfirmationId').html(action + ' ' + 'messages');
	jQuery('#messageConfirmationTextId').html('You do not have any message(s) selected.  Select the message(s) you want to '+action+ '.' );
	jQuery('#acceptModalButtonId').addClass('jqmClose');
	jQuery('#messageConfirmationId').jqm({onHide: closeModal });
	jQuery('#messageConfirmationId').jqmShow();
	return ;
	
}
function getCheckedValues(){

	var query_string = '';
	jQuery("input[name='arrayMessages']").each(		 
	    function() 
	    { 
	    	if(jQuery(this).is(':checked'))
	        { 
				query_string += "&arrayMessages[]=" + jQuery(this).val();
			}
	    }	 
	);
	return query_string;	
}

function updateMessage(type , optional){

	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/messageupdate';

	var query_string = getCheckedValues();
	var action = null;
	if(query_string == ''){
		if(type == '3' && optional != 'movetoInbox'){
			return noSelectedValuesMessage('mark as read');
		}else if(type == '4'){
			return noSelectedValuesMessage('mark as unread');
		}else if(type == '5'){
			return noSelectedValuesMessage('delete');
		}else if(type == '10'){
			return noSelectedValuesMessage('accept');
		}else if(type == '11'){
			return noSelectedValuesMessage('reject');
		}else if(type == '3' && optional == 'movetoInbox'){
			return noSelectedValuesMessage('move to your inbox');
		}	
	}	
	if(type == '3'){
		action = 'readok';
		if(optional == 'movetoInbox'){
			action = 'movetoinboxok';
		}
	}else if(type == '4'){
		action = 'unreadok';
	}else if(type == '5'){
		action = 'deleteok';
	}else if(type == '10'){
		action = 'acceptok';
	}else if(type == '11'){
		action = 'rejectok';
	}
		
	jQuery.ajax({
        type: 'POST',
        url : url,
        data : "status="+type + query_string ,
 		success: function (text) {
					top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/<?php echo $this->messageType; ?>/1/all/'+action;
 				}	
	});	
}

function filterMessages(){

	var status = jQuery("#messageFilterSelectId").val();
	top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/<?php echo $this->messageType; ?>/1/'+status;
}

function acceptFriendRequest(messageRequestId , newFriendId , newUserFriendName){

	var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/message/acceptfriendrequest';
	
	jQuery.ajax({
        type: 'POST',
        url : url,
        data : {newFriendId: newFriendId ,messageRequestId: messageRequestId ,newUserFriendName:newUserFriendName} ,
        
 		success: function (text) {
				jQuery('#messageDetail').jqmHide();
				top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/inbox/1/all/acceptok';
 		}	
	});	
}

function rejectFriendRequest(id){

	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/messageupdate';
	
	jQuery.ajax({
        type: 'POST',
        url : url,
        data : "status=11&arrayMessages[]="+ id ,
        
 		success: function (text) {
			jQuery('#messageDetail').jqmHide();
			top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/inbox/1/all/rejectok';
 		}	
	});
} 
function showMessageDetail(id){
	
	 jQuery('#messageDetail').jqm({
      ajax: '<?php echo Zend_Registry::get("contextPath"); ?>/message/showmessagedetail/type/<?php echo $this->messageType;?>/message/'+id ,
      onHide: closeModalMessages});
	 jQuery('#messageDetail').jqmShow();
           
}





</script>

         <div class="addFavoriteWindow" id="messageDetail">                
			      <a href="#" class="jqmClose">Close</a>                Please wait... 
			      <img src="inc/busy.gif" alt="loading" />
	    </div>
                

         <div id="ContentWrapper" class="TwoColumnLayout">
            <div class="FirstColumnOfThree">
                <?php 
                    $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?> 
                <!--Me box Non-authenticated and Left menu -->
      				 <div class="img-shadow">
                              <div class="WrapperForDropShadow">
                                  <?php include 'include/loginbox.php';?>
                              </div>
                   </div>
                  
      					   
      					
                      <?php }else { ?>
                      
                       <!--Me box Non-authenticated and left menu-->
      				 
                          <div class="img-shadow">
                              <div class="WrapperForDropShadow">
                                  <?php include 'include/loginNonAuthBox.php';?>
                              </div>
                          </div>
      				
      				 <!-- Non-authenticated Profile Left Menu-->
      					
      					
                <?php } ?> <!-- End - Non-authenticated Profile Left Menu-->
                                
           
              </div><!--end FirstColumn of TwoColumnLayout-->
              
           <div id="SecondColumnPlayerProfile" class="SecondColumnOfTwo" > 
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                <h4>Messages</h4>
                            </div>
                           
                          	<div class="PlayerInfoWrapper">
                            
                            <div class="PlayerInfoWrapper">
                             <div id="alertSuccessMessageId" class="inlineMessageWide alertSucess closeDiv">
                                <p id="successMessageId">Your message was successfully sent.</p>
                                <span class="closemessage"></span>
                             </div>
						   	
						  	<div class="PlayerInfoWrapper CloseDiv">
	                             <div id="alertErrorMessageId" class="inlineMessageWide alertError closeDiv">
	                                <p id="errorMessageId">Your message was cancelled.</p>
	                                <span class="closemessage"></span>
	                             </div>
                             </div>


                              <form id="messageForm" name="messageForm" method="post" >
                                <div class="PlayerInfo MessageWrapper">
                                    
                                    <div class="ModuleTabs Messages">
                                        <ul style="overflow:hidden;">
                                        	<li id="inbox" class="<?php echo ($this->messageType =="inbox"?"selected":"")?>"><a class="<?php echo ($this->messageType =="inbox"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter">INBOX (<?php echo($this->cants["cantMess"]); ?>)</a></li>
                                            <li id="sent" class="<?php echo ($this->messageType =="sent"?"selected":"")?>"><a class="<?php echo ($this->messageType =="sent"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/sent">SENT (<?php echo($this->cants["cantSent"]); ?>)</a></li>
                                            <?php /*?><li id="request" class="<?php echo ($this->messageType =="request"?"selected":"")?> reqInvite"><a class="<?php echo ($this->messageType =="request"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/request">REQUESTS & INVITES (<?php echo($this->cants["cantReq"]); ?>)</a></li><?php */?>
                                            <li id="deleted" class="<?php echo ($this->messageType =="deleted"?"selected":"")?>"><a class="<?php echo ($this->messageType =="deleted"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/deleted">DELETED (<?php echo($this->cants["cantDel"]); ?>)</a></li>
                                            
                                            <li id="composeButton"><input type="button" class="submit" value="Compose New Message" id="composebuttonid"/></li>
                                        </ul>
                                        
                                    </div><!-- /ModuleTabs-->
                                    
                                    
                                    <ul class="MessagePager">
                                    <?php if($this->messageType =="inbox"){?>
                                        <li>View: <select style=" width:100px;" id="messageFilterSelectId" >
                                        <option value="0">All</option>
		                                <?php foreach($this->states as $state){?>
		                                 <option value="<?php echo($state["id_description"]); ?>" <?php echo ($state["id_description"] == $this->status?'selected':'')?>><?php echo($state["description"]);?></option>
		                                <?php }?>
                                		</select></li>
                                	<?php }?>	
                                	<?php if (count($this->paginator) > 0){?>
                                    <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_box_message.phtml'); ?> 
                                		                                     
                                    </ul>
                                    
                                    <ul class="MessageToolbar">
                                        <li>
                                        	<?php if($this->messageType =="inbox"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deleteButtonId"/>
                                        	<input type="button" class="submit blue" value="Mark As Unread" id="unreadButtonId"/>
                                            <input type="button" class="submit blue" value="Mark as Read" id="readButtonId"/>
                                            <?php }?>
                                            <?php if($this->messageType =="sent"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deleteButtonId"/>
                                            <?php }?>
                                            <?php /*if($this->messageType =="request"){?>
                                            <input type="button" name="accept" id="acceptButtonId" value="Accept" class="submit"/>
                                            <input type="button" name="reject" id="rejectButtonId" value="Reject" class="submit red"/>
                                            <?php }*/?>
                                            <?php if($this->messageType =="deleted"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deletePermanentButtonId"/>
                                        	<input type="button" class="submit blue" value="Move to Inbox" id="movetoInboxButtonId"/>
                                        	<?php }?>	   
                                        </li>
									</ul>
                                    
                                    <table class="Messages">
                                        
                                        <tr>
                                            <th><input type="checkbox" class="checkbox" value="all" id="messegeActionId"/></th>
                                            <?php if($this->messageType !='sent'){?>
                                            <th class="NoLeftBorder">From</th>
                                            <?php }else if($this->messageType == 'sent'){?>
                                            	<th class="NoLeftBorder">To</th>
                                            <?php }?>
                                            <th class="NoLeftBorder">Subject</th>
                                             <th class="NoLeftBorder">Type</th>
                                            <th class="NoLeftBorder">Status</th>
                                            <th class="NoLeftBorder">Date</th>
                                        </tr>
                                        
                						      <?php  
                                       	$colcont = true;
                                			  $color = "";	
                                       	foreach ($this->paginator as $res){
                                    				if($colcont == true){
                                    					$color = "Odd";
                                    				}else{
                                    					$color = "Even";
                                    				}
                                       
									                    ?>
                                		    <tr class="<?= $color?><?php if($res["statusdescription"] == 'Pending'){?>& notRead<?php }?>">
                                            <td><input type="checkbox" value="<?php echo($res["id"]);?>" name="arrayMessages"" /></td>
                                            <?php if($this->messageType !='sent'){?>
                                            <td class="NoLeftBorder"><?php echo($res["screen_name"]);?></td>
                                             <?php }else if($this->messageType == 'sent'){?>
                                            <td class="NoLeftBorder"><?php echo($res["todest"]);?></td> 
                                            <?php }?>
                                            <td class="NoLeftBorder"><a href="javascript:showMessageDetail('<?php echo($res["id"]);?>')"><?php echo($res["subject"]);?></a></td>
                                            <td class="NoLeftBorder"><?php echo($res["typedescription"]);?></td>
                                            <td class="NoLeftBorder"><?php echo($res["statusdescription"]);?></td>
                                            <td class="NoLeftBorder"><?php echo($res["date"]);?></td>
                                        </tr>
                                        <?php 
                                      	if($colcont == true){$colcont = false;}else{$colcont = true;}
                                      		}
                                      	?>
                                       
                                    </table>
                                    
                                     <ul class="MessageToolbar">
                                        <li>
                                        	<?php if($this->messageType =="inbox"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deleteButtonId2"/>
                                        	<input type="button" class="submit blue" value="Mark As Unread" id="unreadButtonId2"/>
                                            <input type="button" class="submit blue" value="Mark as Read" id="readButtonId2"/>
                                            <?php }?>
                                            <?php if($this->messageType =="sent"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deleteButtonId2"/>
                                            <?php }?>
                                            <?php /*if($this->messageType =="request"){?>
                                            <input type="button" name="accept" id="acceptButtonId" value="Accept" class="submit"/>
                                            <input type="button" name="reject" id="rejectButtonId" value="Reject" class="submit red"/>
                                            <?php }*/?>
                                            <?php if($this->messageType =="deleted"){?>
                                            <input type="button" class="submit blue" value="Delete" id="deletePermanentButtonId2"/>
                                        	<input type="button" class="submit blue" value="Move to Inbox" id="movetoInboxButtonId2"/>
                                        	<?php }?>	   
                                        </li>
                                        
                                    </ul>
									<?php }else{ ?> 
                                          <h4><br></>There are no messages in your <?php echo $this->messageType; ?>.</h4>
                                    <?php }?> 

                                </div><!-- /PlayerInfo-->  
                                </form>
                            </div><!-- /Message Wrapper-->
                            
                        </div>
                    </div>
                  
                </div><!--end SecondColumnOfTwo and #SecondColumnPlayerProfile-->

         </div> <!--end ContentWrapper-->


  



