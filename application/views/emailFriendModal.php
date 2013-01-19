<script language="javascript" type="text/javascript">

jQuery(document).ready(function() {
	jQuery('#emailFriendButtonSend').click(function(){
		     var url = "<?php echo Zend_Registry::get('contextPath'); ?>/index/sendthispagebyemail"; 
		     var emailTo   = jQuery('#emailto').attr('value');
		     var message   = jQuery('#message').attr('value');
		     var emailfrom = jQuery('#emailfrom').attr('value');
			 var valid = true;
		     if(emailTo == ''){	
		     	jQuery('#emailtoerror').addClass('ErrorMessageIndividualDisplay');	
		     	valid = false;	
		     }
		     if(emailTo == ''){	
		    	jQuery('#emailfromerror').addClass('ErrorMessageIndividualDisplay');
			    valid = false;	
			 }
			 if(valid == false){
				return false;	
			 }	
		     
			 var result = jQuery('#MainErrorMessage').load(url , {emailTo : emailTo , message : message , emailfrom : emailfrom});

			 if(result == ''){
	    	 	
		    	 jQuery('#emailto').val('');
		    	 jQuery('#message').val('');
		    	 jQuery('#emailfrom').val('');
		    	 jQuery('#emailtoerror').addClass('ErrorMessageIndividual');
		    	 jQuery('#emailfromerror').addClass('ErrorMessageIndividual');
		    	 if(jQuery('#MessageOkEmailThisPage').is(":hidden")){
					   jQuery('#"MessageOkEmailThisPage"').show("slow");
					   jQuery('#"MessageOkEmailThisPage"').animate({opacity: '+=0'}, 2000).slideUp('slow');
					   jQuery('#emailPageModal').animate({opacity: '+=0'}, 2000).slideUp('slow');
					   pausecomp(2500); 
				 }
			 }else {
				 jQuery('#ErrorMessages').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
			 }	 	
	    	 
	    	 	
	   });  
	
});

function pausecomp(millis)
{
    var date = new Date();
    var curDate = null;

    do { curDate = new Date(); }
    while(curDate-date < millis);
} 

</script>

<!--Login Page jqmodal-->
       <div id="FormWrapper">
        <h3>Email This Page</h3>
        <div id="FormWrapperForBottomBackground">
        	 <form name="invite_form" id="InviteFriends" method="post" action="#">
                    	<input type="hidden" id= "type" name= "type" value="">
                    	<div id="FieldsetWrapper">
                    		<h5>Email This Page<div align="center" id="systemWorking" style="display:none"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'></div>
                    		<div align="center" id="frm-error-text1" class="frm-error-text1" ></div>
                    </h5>
                    		<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">Ooops, there was a problem!</div>
	             			</div>
	             			<div id="MessageOkEmailThisPage" class="ErrorMessages closeDiv">
                                    	This page has been emailed successfully.
                            </div>
	             			<fieldset id="SignInFieldset" class="SecondColumnOfTwo">
                <div align="center" style="display: none;" id="systemWorking">
                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif"/>
                </div>
                <div id="error" class="error">
                </div>
                <div id="emailtoerror" class="ErrorMessageIndividual">You must enter an Email Address</div>
                   	  <label for="emailaddress">
                        To (separated by semicolon):
                      </label>
                      <input type="text" tabindex="1" size="35" id="emailto" value="" name="emailto" class="text"/>
                      <br/>
                       <div id="emailfromerror" class="ErrorMessageIndividual">You must enter something in From</div>
                      <label for="from">
                        From:
                      </label>
                      <input type="text" tabindex="2" size="35" id="emailfrom" value="" name="emailfrom" class="text"/>
                        <br/>
                        <label for="persistent">
                         Message:
                      </label>
                      <textarea style="width:194px;"  rows="3" cols="15" tabindex="3"  name="message" id="message"></textarea>
                       <br/>
                       <p>
                       <input type="button" id="emailFriendButtonSend" class="submit GreenGradient" value="Email" tabindex="4"/>
                       <p>
                       <input type="button" id="cancel" class="submit GreenGradient" value="Cancel" tabindex="4"/>
        				<p>
                       <br/><br/><br/>
                </fieldset>

							

							
							<br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
          </form>
        
        
     </div> <!--end FormWrapperForBottomBackground -->
   </div><!--end FormWrapper -->    
        