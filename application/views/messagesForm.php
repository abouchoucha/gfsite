<?php $session = new Zend_Session_Namespace('userSession');?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
<script>

jQuery(document).ready(function() {
	
	<?php if($this->name != null){ ?>
		jQuery('#to').val('(<?php echo $this->screenname; ?>) <?php echo $this->name; ?>');
		jQuery('#idarray').val('<?php echo $this->userid; ?>');
		jQuery('#arregloUsers').append(
				jQuery('<option></option>').val('<?php echo $this->userid; ?>').html('(<?php echo $this->screenname; ?>) <?php echo $this->name; ?>')
		    );
	<?php } ?>
	
	jQuery('#addFriendsModaltrigger').click(function(){
		jQuery('#addFriendsModalId').jqm({onHide: closeModal });
		jQuery('#addFriendsModalId').jqmShow();
	 });


	jQuery('#acceptFindFriendsModalButtonId').click(function(){
		appendFriends();
	});
	jQuery('#cancelFindFriendsModalButtonId').click(function(){
		jQuery('#addFriendsModalId').jqmHide();
		jQuery('#arregloUsers').find('option').remove().end();
		jQuery('#to').val('');
	});
	
	
	jQuery('#submitButton').click(function(){
		sendMessage();
	});
	jQuery('#submitButtonReplyId').click(function(){
		replyMessage();
	});
	
	
	jQuery('#cancelButtonmc').click(function(){
		cancelMessage();
	});	

	
	

});		


function sendMessage(){

	valid = validaNewForm('messageForm');
	if(!valid){
  		jQuery('#ErrorMessagesmc').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    jQuery('html, body').animate({scrollTop:0}, 'slow');
	    return;
  	}
  	jQuery('#ErrorMessagesmc').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
	jQuery.ajax({
		type: 'POST',
		data: jQuery("#messageForm").serialize(),
		url: '<?php echo Zend_Registry::get("contextPath"); ?>/message/addmessage',
		dataType : 'script',
		success: function(text){
				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/inbox/1/all/ok';
       	}
	  	
	})
} 

function replyMessage(){

	valid = validaNewForm('messageForm');
	if(!valid){
  		jQuery('#ErrorMessagesmc').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    jQuery('html, body').animate({scrollTop:0}, 'slow');
	    return;
  	}
	jQuery('#ErrorMessagesmc').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
	jQuery.ajax({
		type: 'POST',
		data: jQuery("#messageForm").serialize(),
		url: '<?php echo Zend_Registry::get("contextPath"); ?>/message/replymessage',
		dataType : 'script',
		success: function(text){
			top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/inbox/1/all/ok';
       		    
		}
	  	
	})
} 


function cancelMessage(){

	var to = jQuery('#to').val();
	var subject = jQuery('#subjectmc').val();
	var content = jQuery('#content').val();
	
	var allcontent = to + subject + content; 
	
	if(jQuery.trim(allcontent) != ''){
		jQuery('#addFavoriteModal').jqm({onHide: closeModal });
		jQuery('#addFavoriteModal').jqmShow();
		jQuery('#modalFavoriteTitleId').html('Compose Message');
		jQuery('#modalBodyResponseId').html('Are you sure you want to cancel this message?  All message contents will be lost.');
		jQuery('#modalBodyId').hide();
		jQuery('#modalBodyResponseId').show();
		jQuery('#acceptFavoriteModalButtonId').attr('value','Yes');
		jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
		jQuery("#acceptFavoriteModalButtonId").unbind();
		jQuery('#acceptFavoriteModalButtonId').click(function(){
			jQuery('#addFavoriteModal').jqmHide();
			top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/inbox/1/all/ko';
		});
		jQuery("#cancelFavoriteModalButtonId").unbind();
		jQuery('#cancelFavoriteModalButtonId').click(function(){
			jQuery('#to').val(to);
			jQuery('#subjectmc').val(subject);
			jQuery('#content').val(content);
			jQuery('#addFavoriteModal').jqmHide();
		});		
	}else {
		top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/';
	}
}

	
function appendFriends(){

	var destArray = "";
	var namesArray = "";
	var items = jQuery("#arregloUsers option");
	jQuery(items).each(function(i, selected){
		destArray += jQuery(selected).val();
		if(i < (items.length - 1)){
			destArray += ",";
		}
		namesArray += jQuery(selected).text();
		if(i < (items.length - 1)){
			namesArray += ";";
		}
		
	});
	if(destArray != ''){
		jQuery('#to').val(namesArray);
		jQuery('#idarray').val(destArray);
		jQuery('#addFriendsModalId').jqmHide();
	}
}

function addUsers(){
	
	var items = jQuery("#arregloUsers option");
	var value = jQuery('#users').val();
	var text = jQuery('#users option:selected').text();
	var duplicated = true;
	jQuery(items).each(function(i, selected){
		if(value == jQuery(selected).val()){
			duplicated = false;
		}
	});
	if(items.length < 5){
		 if(duplicated){
			jQuery('#arregloUsers').append(
					jQuery('<option></option>').val(value).html(text)
			    );
		 }
	}else{
		alert("You have already 5 users.");
	}
}


function removeUsers(){
	var value = jQuery('#users').val();
	jQuery('#arregloUsers :selected').each(function(i, selected){
		jQuery(selected).remove();
	});
}



</script>

<div class="addFavoriteWindow" id="addFriendsModalId2">                
      <a href="#" class="jqmClose">Close</a>                Please wait... 
      <img src="inc/busy.gif" alt="loading" />
</div>

<div id="addFriendsModalId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId">Find Friends</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal AddFavoritesModal">
                <table id="addfavtbl_player" class="addfavtbl" cellspacing="0" border="0">
                    <tr>
                        <td class="addfavtbl_middle">

                            
                            <label for="nationality">Friends</label>
                            <br/><br/>
                            
                            <label for="club_team">Friends List</label>
                            <br/>
			</td>
                        <br>
                        <td><table><tr><td style='vertical-align:top;'><input type='button' value='Add' onclick='addUsers()' class='buttonCustom'/></td><td><input type='button' value='Remove' onclick='removeUsers()' class='buttonCustom'/></td></tr></table></td>
                        <td class="addfavtbl_ajax">
                            <br><br>
                                <img id="ajaxloaderTeamPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"><br><br>
                        	<img id="ajaxloaderPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif">
                        </td>
                        <td class="addfavtbl_right">
                         <select id='users'>
                           <?php foreach ($this->userList as $friend) { ?>
                            	<option value="<?php echo $friend["userId"];?>"><?php echo $friend["nickname"];?>(<?php echo $friend["nombre"];?>)</option>
                           <?php } ?>
                         </select>
                         
                         <select name='arregloUsers' multiple size='5'  id='arregloUsers' style='height:100px; width:190px;' >
			 </select>
			</td>
                     </tr>
                </table>
            </div>
            <ul class="ButtonWrapper">

                <li>
                    <input type="button" id="acceptFindFriendsModalButtonId" class="submit" value="Ok"/>
                    <input type="button" id="cancelFindFriendsModalButtonId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
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
                                
         

              </div><!--end FirstColumnOfThree-->
                
                <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                <h4>Compose message</h4>
                            </div>
                            
                            <div class="PlayerInfoWrapper">
                                <div id="ErrorMessagesmc" class="ErrorMessages" style="width:672px;left:10px;margin-bottom:3px;margin-top:10px;">
                    		    <div id="MainErrorMessage">All fields are required.  Please enter the fields highlighted below.</div>
	                     	</div>
                                <div class="PlayerInfo">
                                    <div class="PlayerHighLevel">
                                        <form id="messageForm" name="messageForm" method="post" >
                                        <div align="center" id="systemWorking" style="display:none">
                                            <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>Logging...</div>
                                        <input type="hidden" id="idarray" name="idarray" class="text"/>
                                        <input type="hidden" id="messageId" name="messageId" value="<?php echo $this->messageId;?>"  class="text"/>
                                        <fieldset class="ComposeMessage">
                                            <div align="left" class="error" id="error" style=" color:#FF0000;"></div>
                                            <p>
                                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/message">Back to Messages</a>
                                            </p>
                                        
                                            <label>From:</label><?php echo $session->screenName;?>
                                            <br />
                                            <div>
                                                Note: You can select up to 5 friends to send a message to.  Click here to <a href="javascript:void(0);" id="addFriendsModaltrigger"">find friends</a> to add to your list.
                                            </div>
                                            <div id="toerror" class="ErrorMessageIndividual">Please select at least one friend</div>
                                            <label>To:</label><input type="text" readonly="readonly" id="to" name="to" required="nn"/>
                                            
                                            <br />
                                            <div id="subjectmcerror" class="ErrorMessageIndividual">Please enter a subject.</div>
                                            <label>Subject:</label><input type="text" id="subjectmc" name="subjectmc" required="nn" value="<?php echo $this->subject;?>"/>
                                            <br />
                                            <div id="contenterror" class="ErrorMessageIndividual">Please enter a message.</div>
                                            <label>Message:</label><textarea id="content" name="content" required="nn" value=""><?php echo $this->message;?></textarea>
                                            <br />
                                            <?php if($this->messageId != ''){?> 
                                                <input type="button" value="Send" class="submit" id="submitButtonReplyId"/>
                                            <?php }else {?>
                                            	<input type="button" value="Send" class="submit" id="submitButton"/>
                                            <?php }?>    
                                                <input type="reset" value="Cancel" class="submit blue" id="cancelButtonmc"/>
                                            
                                        </fieldset>
                                        </form>  
                                    </div>
                                </div><!-- /PlayerInfo-->
                            </div><!-- /PlayerInfoWrapper-->
                            
                        </div>
                    </div>
                    
  			</div><!--end SecondColumnOfTwo and #SecondColumnPlayerProfile-->
  
             </div> <!--end ContentWrapper-->



