<div id="FormWrapper">
                <h3>Reset Password</h3>
                <div id="FormWrapperForBottomBackground">

					<form id="loginForm" name="loginForm" method="post" action="">
                    	<div id="FieldsetWrapper">

                       		<div id="ErrorMessagesReset" class="ErrorMessages">
                                    <div id="MainErrorMessageReset">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
                            </div>
                            <!--to close success div add class = closeDiv-->
                            <div id="alertSuccessMessageId" class="inlineMessageWide alertSucess closeDiv" style="width:864px;margin-left:0px;">
                                   <p id="successMessageId">Your message was successfully sent.</p>
                                 <span class="closemessage"></span>
                             </div>

                    		<h5>Forget Password</h5>
                    		
      					<div class="FirstColumnOfTwo" id="SignIntoGoalface">
							<h4 style="margin-top:0;">Trouble Accessing Your Account?</h4>

                            <script language="javascript">
                            var error = '<?php echo $this->error; ?>';
                            if(error =='1'){
                                showErrorDiv('The link that you followed was invalid or incomplete. Try copying and pasting the full link into the address bar');
                            }
                          </script>
                          <p style="padding-bottom:20px;">Forgot your password? Enter your login email below. We will send you an email with a link to reset your password.</p>
                                        <div id="emailaddressreseterror" class="ErrorMessageIndividual">Please enter your Email Address</div>
                                         <label for="emailaddressreset">
                                            <em>* </em>Email Address:
                                        </label>
                                        <input style="width:356px;" type="text" class="text" id="emailaddressreset" name="emailaddressreset" size="35" tabindex="1"  required="email"/>
                                             <br/>
                                        <input style="margin-top:20px;" type="button" class="submit GreenGradient" value="Reset" onclick="retrievepassword()" tabindex="2"/>
                                        </div>



                                           <br class="clearleft"/>
                                        </div><!-- end of FieldSetWrapper -->
                          </form>
             </div> <!--end FormWrapperForBottomBackground -->
</div><!--end FormWrapper -->




<script type="text/javascript">

function retrievepassword(){
      
      /*var nm = document.getElementById('ErrorMessages');
      if(nm.className == 'ErrorMessagesDisplay'){
        nm.className = 'ErrorMessages';
      }*/
    
      valid = validaNewForm('loginForm'); 
      if(!valid){
        return;
      }
      var url = '<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword';

      var emailaddress =  jQuery('#emailaddressreset').val();
      
      jQuery.ajax({
			type: 'POST',
			dataType : 'script',
			data: ({emailaddressreset: emailaddress}),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword',
			success: function(text){
	    	  	if(text!= ''){
	    	  		jQuery('#alertSuccessMessageId').hide();
	    	  		jQuery('#ErrorMessagesReset').show();
			    	jQuery('#ErrorMessagesReset').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
				    jQuery('#ErrorMessagesReset').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
			    }else {
			    	jQuery('#ErrorMessagesReset').hide();
			    	jQuery('#alertSuccessMessageId').show();
				    jQuery('#alertSuccessMessageId').html('An Email has been sent to you with instructions to reset your password.');
				    jQuery('#emailaddressreset').val("");
			    }
	    	}	
	  })	
	
}
</script>


