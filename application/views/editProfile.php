<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/validate.js" type="text/javascript"></script>
 <?php $session = new Zend_Session_Namespace("userSession"); ?>

 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 <script type="text/JavaScript">  
 
 	jQuery(document).ready(function() {
 		showEditAccountTab('<?php echo $this->type; ?>');

 		jQuery(".inlineMessageWide .closemessage").click(function(){
 			jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
 		 }); 
 		
 		<?php if($this->sentok == 'profileok') {?>
 			jQuery('#alertSuccessMessageId').show();
 			jQuery('#successMessageId').html('Your profile information was successfully updated.');
 		<?php }?>
 		<?php if($this->sentok == 'photook') {?>
			jQuery('#alertSuccessMessageId').show();
			jQuery('#successMessageId').html('Your profile photo was successfully updated.');
		<?php }?>
		<?php if($this->sentok == 'removephotook') {?>
			jQuery('#alertSuccessMessageId').show();
			jQuery('#successMessageId').html('Your profile photo was successfully removed.');
		<?php }?>
		jQuery('#alertSuccessMessageId').animate({opacity: 'hide'}, 5000);
		
 		<?php if($this->sentok == 'imagesizeerror') {?>
 			jQuery('#alertErrorMessageId').show();
 			jQuery('#errorMessageId').html('Error. Image size cannot exceed 200 Kb.');
 		<?php }?>
 		<?php if($this->sentok == 'systemerror') {?>
			jQuery('#alertErrorMessageId').show();
			jQuery('#errorMessageId').html('Ooops, there was a problem!.Try uploading your photo again.');
		<?php }?>
		<?php if($this->sentok == 'imagefiletypeerror') {?>
			jQuery('#alertErrorMessageId').show();
			jQuery('#errorMessageId').html('Error. You need to select a valid image file and then click Upload Photo');
		<?php }?>
	
	});

 
   function showEditAccountTab(tab){
    	
         var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editinfo/editaction/'+tab; 
       
		 jQuery('#data').html('Loading...'); 
 		 jQuery('#data').load(url);

         jQuery('.Selected').removeClass('Selected');
		 jQuery('#' + tab).addClass('Selected');
    
   }
   
   
 </script>
 <script>
     
jQuery(document).ready(function() {
    	 jQuery('#Languages1').attr("disabled","disabled");
    	 jQuery('#remove1').hide();
 });

 


 function uploadimg (){
		if(jQuery('#myfile').val() == '' ){
			jQuery('#modalTitleConfirmationId').html('UPLOAD PHOTO');
			jQuery('#messageConfirmationTextId').html('You need to select a valid file and then click Upload Photo.');
			jQuery('#acceptModalButtonId').addClass('jqmClose');
			jQuery('#messageConfirmationId').jqm({trigger: '#uploadphotobutton', onHide: closeModal });
			jQuery('#messageConfirmationId').jqmShow();
			
	    	return;
		 }
		
		var hasPhoto = '<?php echo $session->filename;?>'
		if(hasPhoto != ''){
			
			jQuery('#modalTitleConfirmationId').html('UPLOAD PHOTO');
			jQuery('#messageConfirmationTextId').html('Do you want to replace your profile photo?');
			
			jQuery('#messageConfirmationId').jqm({trigger: '#uploadphotobutton', onHide: closeModal });
			jQuery('#messageConfirmationId').jqmShow();
			jQuery('#acceptModalButtonId').click(function(){
				 jQuery('#uploadform').submit();
				
			});	
			 return;
		}	
		
		jQuery('#uploadform').submit();
		
	
	} 

 function saveImage(){
	 if (!jQuery('#photorights').is(':checked')){	 
		jQuery('#modalTitleConfirmationId').html('UPLOAD PHOTO');
		jQuery('#messageConfirmationTextId').html('Please check the box to certify you have the right to use the selected photo and that it does not violate the terms of use.');
		jQuery('#acceptModalButtonId').addClass('jqmClose');
		jQuery('#messageConfirmationId').jqm({trigger: '#uploadphotobutton', onHide: closeModal });
		jQuery('#messageConfirmationId').jqmShow();
    	return;
	 }

	 jQuery.ajax({
		type: 'GET',
		url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/edituploadphoto',
		success: function(data) {
		 	top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName;?>/photo/photook';
		}	
	})
 } 

 function removeimg(image) {

     //var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/deletephoto/img/'+image;

     jQuery('#modalTitleConfirmationId').html('UPLOAD PHOTO');
		jQuery('#messageConfirmationTextId').html('Do you want to remove your profile photo?');
		jQuery('#acceptModalButtonId').addClass('jqmClose');
		jQuery('#messageConfirmationId').jqm({trigger: '#uploadphotobutton', onHide: closeModal });
		jQuery('#messageConfirmationId').jqmShow();

		jQuery('#acceptModalButtonId').click(function(){
			jQuery.ajax({
				type: 'GET',
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/deletephoto/img/'+image,
				success: function(text) {
					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName;?>/photo/removephotook';
				}	
			})
		});		
}

 </script> 
 
 
 
 
  
  
         <div id="ContentWrapper" class="TwoColumnLayout">

          <div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
              
          ?> 
          	<!-- START Profile Box Include -->
                	<?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
              <!-- END Profile Box Include -->	

                 

                </div><!--/FirstColumn-->
           
           
             <!--end FirstColumn-->
             <div class="SecondColumn">             
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                            <h4>Edit Profile</h4>
                        </div>
                        <div class="PlayerInfoWrapper">
                             <div id="alertSuccessMessageId" class="inlineMessageWide alertSucess closeDiv">
                                <p id="successMessageId"></p>
                                <span class="closemessage"></span>
                         </div>
                         <div class="PlayerInfoWrapper CloseDiv">
	                             <div id="alertErrorMessageId" class="inlineMessageWide alertError closeDiv">
	                                <p id="errorMessageId">Your message was cancelled.</p>
	                                <span class="closemessage"></span>
	                             </div>
                          </div>
                        <div class="SecondColumnBlueBackground">
                                <ul class="TabbedNav" id="main_tabs">
                                    <li id="profileinfo" class="Selected"><a href="javascript:showEditAccountTab('profileinfo')">Information</a></li>
                                    <?php if ($session->fbuser == null) { ?>
                                    <li id="photo"><a href="javascript:showEditAccountTab('photo')">Photo</a></li>
                                    <?php } ?>
                                </ul>
                                
                                <br class="ClearBoth" />
                                <div id="data" class="tabbedData">
                                   	
                               </div>
                        </div>
                    </div>                   
                </div>
               
             </div><!--//SecondColumn-->
         </div><!--//ContentWrapper-->
         
  
         
