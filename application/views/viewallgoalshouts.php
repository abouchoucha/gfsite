   <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
  
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

jQuery(document).ready(function() {
	    jQuery("#commentGoalShoutId").charCounter(400); 
	
	    jQuery('#addGoalShoutId').click(function() {
			 addGoalShoutAll();
		});
	    
		  jQuery('input:checkbox#checkall').click(function(){
				clickUnClickCheckBoxes(this.checked);
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
	
			  deleteProfileGoalShouts();
			 		
  });

  function clickUnClickCheckBoxes(checked){

	  jQuery("input[name='chkbox']").each(		
				function() 
				{ 
					jQuery(this).attr('checked',checked); 
			   	}
		);
	}


  function addGoalShoutAll(){
		 var commentText = jQuery('#commentGoalShoutId').val();
		 var ok = 'true'; 	
		 if(jQuery.trim(commentText) == '' || commentText =='Type your Goooal Shout here...'){
			jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			ok = 'false';
		 }else {
		      jQuery.ajax({
	 	  	    type: "POST",
		      	data: jQuery("#comment_form").serialize(),
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalshout',
				success: function(data) {
		    	  top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts/username/<?php echo $this->currentUser->screen_name;?>';
				}	
			}) 	
		 }
		 return ok;
 }


  function editGoalShoutProfile(id){

		jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShout', onHide: closeModal ,modal:true});
		jQuery('#editGoalShoutModal').jqmShow();
		var dataEdit = jQuery('#goalshout'+id).html();
		jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));
		
			jQuery('#acceptEditGoalShoutButtonId').click(function() {
				var commentText = jQuery('#textgoalshoutEdit').val();
				if(jQuery.trim(commentText) == ''){
					jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		 			return;
		 		 }
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editprofilegoalshout';
				var userid = '<?php echo $this->currentUser->user_id;?>';
				var dataEditted = jQuery('#textgoalshoutEdit').val();
				jQuery('#editGoalShoutModal').jqmHide();
				/*jQuery('#goalshoutId').html('Loading...'); 
				jQuery('#goalshoutId').load(url , {userid :userid , id : id , dataEditted : dataEditted});
				*/
				jQuery.ajax({
		 	  	    type: "POST",
			      	data: {userid :userid , id : id , dataEditted : dataEditted},
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editprofilegoalshout',
					success: function(data) {
			    	  top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts/username/<?php echo $this->currentUser->screen_name;?>';
					}	
				}) 	
				
			});
}
  	

  function deleteProfileGoalShouts(){
        
		jQuery('input:button#removeButtonId').click(function(){

			var cont = 0;
			var query_string = '';
			jQuery("input[name='chkbox']").each(			
					function() 
					    { 
					        if(jQuery(this).is(':checked')){
					        	cont++;
							}	 
					         
					}
				);
			
			jQuery('#acceptModalButtonId').unbind();
			if(cont == 0){
				jQuery('#modalTitleConfirmationId').html('DELETE GOALSHOUT');
				jQuery('#messageConfirmationTextId').html('You do not have any goooalshout(s) selected.  Select the goooalshout(s) you want to delete');
				jQuery('#acceptModalButtonId').addClass('jqmClose');
				jQuery('#messageConfirmationId').jqm({trigger: '#removePlayersIdButton', onHide: closeModal });
				jQuery('#messageConfirmationId').jqmShow();
				return false;
			}
			jQuery('#modalTitleConfirmationId').html('DELETE GOALSHOUT');
			jQuery('#messageConfirmationTextId').html('Are you sure you want to delete the selected goooalshout(s)');
			jQuery('#messageConfirmationId').jqm({trigger: '#removePlayersIdButton', onHide: closeModal });
			jQuery('#messageConfirmationId').jqmShow();
			jQuery('#acceptModalButtonId').click(function(){
				var query_string = '';
				jQuery("input[name='chkbox']").each( 
						    function() 
						    { 
						        if (this.checked) 
						        { 
									query_string += "&value[]=" + this.value;
								} 
					});
						
				 jQuery.ajax({
		           type: 'POST',
		           url : '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/goalshoutsactions',
		           data : "id=1&option=7" + query_string, 
		           success: function (text) {
		 					
		 					jQuery('#messageConfirmationId').jqmHide();	
		 					jQuery('#addFavoriteModal').jqm({trigger: '#deletegoalshoutstrigger', onHide: closeModal });
							jQuery('#addFavoriteModal').jqmShow();
							jQuery('#modalBodyResponseId').html('The selected goooalshout(s) have been deleted.');
							jQuery('#modalFavoriteTitleId').html('Delete Goooalshout');
							jQuery('#modalBodyId').hide();
							jQuery('#modalBodyResponseId').show();
							jQuery('#acceptFavoriteModalButtonId').hide();
							jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
							jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
							top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts';
					}
		 		});	
			});	
			
		});

	}	
  
  function reportProfileAbuse(id ,reportTo){

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

			var dataReport = jQuery('#textReportAbuseId').val();
			var reportType = jQuery('#reportTypeId').val();

			jQuery.ajax({
				type: 'POST',
				data:({id : id , dataReport : dataReport ,reportTo : reportTo ,reportType:reportType}),
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/reportuserabuse',
				success: function(data){
					jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
					jQuery('#reportAbuseBodyId').hide();
					jQuery('#reportAbuseBodyResponseId').show();
					jQuery('#acceptReportAbuseButtonId').hide();
					jQuery('#cancelReportAbuseButtonId').attr('value','Close');
					jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
		    	}	
			})

			
		});	
	 }	
  
  </script>
    

    
  <?php
		require_once 'Common.php';
		//$this->headScript()->appendFile(Zend_Registry::get("contextPath") . "/public/scripts/jqModal.js");
	 ?>

      <div id="ContentWrapper" class="TwoColumnLayout">
          <div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
           ?>   
            	
             <!-- START Profile Box Include -->
                	<?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
              <!-- END Profile Box Include -->	

           

            </div><!--end FirstColumnOfThree-->
			

           
              
              <div class="SecondColumn" id="SecondColumnPlayerProfile">
                <h1><?php echo $this->userName; ?> Gooal Shouts</h1>
          					
          					
          					<div class="img-shadow">
          						<div class="WrapperForDropShadow">
          							<div class="SecondColumnProfile">
      					          <div id="FriendsWrapper">
          					          	
          					          <?php if($session->isMyProfile == 'y') { ?>
			                            <ul class="Friendtoolbar">
			                                <li class="Buttons">
			                                     <input type="checkbox" id="checkall" class="checkbox"><!--input type="submit" id="refresh" value="Refresh" class="submit blue">-->
			                                     <input type="button" id="removeButtonId" value="Delete" class="submit blue">
			                                </li>
			                            </ul>
			                          <?php }?>	
          					          <div id="comment_formerror" class="ErrorMessageIndividual">Error: You must enter a comment.</div> 	
										
										<div style="border-bottom:1px solid #CCCCCC;">
					                      <a name="boxcomments"></a> 
					                        <form id="comment_form" style="padding-bottom:30px;" method="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalShout">
					                           <textarea <?php if($session->email == null){ ?> 
					                            	disabled="disabled" <?php } ?>  onfocus="javascript:if(this.value=='Type your Goooal Shout here...')this.value='';" onblur="javascript:if(this.value=='')this.value='Type your Goooal Shout here...';" class="comments" id="commentGoalShoutId" style="width:673px;margin-bottom:10px;" name="comment" rows=5 cols=10>Type your Goooal Shout here...</textarea>
					                            
					                            <input type="hidden" name="commentType" id="commentTypeId"	value="1">
						                        <input type="hidden" name="idtocomment" id="idtocommentId"	value="<?php echo $this->currentUser->user_id;?>">
						                        <input type="hidden" name="screennametocomment"  id="screennametocommentId"	value="<?php echo $this->currentUser->screen_name;?>">
						                        
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
                                if (count($this->paginator)  > 0) {
                                ?> 
                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
                                <?php
                                	foreach ( $this->paginator as $uc ) {
                                ?>     
          					      <div id="boxComments">
                                    <dl class="comment<?php echo(count($this->paginator)  == 1? ' commentNoBorder' : '')?>">
                                      <dt class="shout">
                                        <?php if($session->isMyProfile == 'y') { ?>
		                                    <input id="chkbox" name="chkbox" class="chkbx" style="float:left;" type="checkbox" value="<?php echo $uc['comment_id']; ?>" name="chkbox"/>
		                                  <?php } ?>  
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
                                        <?php if($session->screenName == $uc['screen_name']){
											if($uc['comment_deleted']=='0'){?>		                        	           
							               <a class="edit" id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShoutProfile('<?php echo $uc ["comment_id"];?>')">Edit</a>
							              <?php }
											} ?>    
							           <?php /*if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name']){
							           	if($uc['comment_deleted']=='0'){?>    
							              <a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
							              <?php }
											} */?>    
							                               <p id="<?php echo 'goalshout' .$uc['comment_id']?>"><?php if ($uc['comment_deleted']=='0'){ ?><?php echo trim($uc["comment"]); ?>
							                               <?php }else if ($uc['comment_deleted']=='1'){  ?>
							                               		<i>GoalShout was removed by owner</i>
							                               <?php }else if ($uc['comment_deleted']=='2'){  ?>
							                               		<i>GoalShout was removed by profile owner</i>
							                               <?php } ?>
							                               </p>
							              <?php if($uc['comment_deleted']=='0'){?>
							             	<span class="abuse"><a class="warning" href="javascript:reportProfileAbuse('<?php echo $uc['comment_id'];?>','<?php echo $uc['friend_id'];?>');">Report This</a></span>
							             <?php }?>
                                      </dd>
                                    </dl>
                                  </div>
          					          		<?php }  ?>
          					          	<?php } else { ?>  
                                    		No Gooal Shouts for this <?php echo $this->userName; ?> 							 
          					          	<?php }  ?>  
  
    							         </div>
                          </div>
                        </div>
                    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

					

              </div><!--end Second Column-->
              

    </div> <!--end ContentWrapper-->
	<?php 
		//echo $this->modal1;
	?>