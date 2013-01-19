<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#changePasswordButtonId').click(function(){
		changePassword();
    });
});

function changePassword(){
      
      var pass1 =  jQuery('#newpassword').val();
      var pass2 =  jQuery('#newpassword2').val();
	  var email =  jQuery('#emailId').val();
      valid = validaNewForm('loginForm');

      if(!valid){
    	  jQuery('form>div#LoginErrorMessagesId').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
          jQuery('form>div>div>#MainErrorMessageReset').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
          jQuery('html, body').animate({scrollTop:0}, 'slow');
          return;
      }
      
      if(pass1!= pass2){
	    	jQuery('#newpassworderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#newpassworderror').html('The passwords you entered do not match. Please try again..');
	    	return;
	  	}
      
     jQuery.ajax({
          type: 'POST',
          dataType : 'script',
          data: ({newpassword: pass1 , newpassword2 : pass2 , email : email }),
          url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/resetpassword',
          success: function(text){
         		
          }	
      });
      
      
}

</script>



           <div id="FormWrapper">
                <h3>Password Reset</h3>
                <div id="FormWrapperForBottomBackground">

					<form id="loginForm" name="loginForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>">
                <div id="FieldsetWrapper">
                    		
                    		
      					<div class="FirstColumnOfTwo" id="SignIntoGoalface">
						    <h4>Please enter a new password.</h4>  
							<div id="ErrorMessages" class="ErrorMessages">                   		
                    		    <div id="MainErrorMessageReset">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
	            </div>
	            <input type="hidden" name="email" id="emailId" value="<?php echo $this->email;?>">
              <fieldset class="SecondColumnOfTwo" id="SignInFieldset">
                  <div align="center" id="systemWorking" style="display:none"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>Logging...</div>
                  				<div id="newpassworderror" class="ErrorMessageIndividual" style="float:left;">You must enter your New Password</div>
                    			<br/>	
                  				<label for="emailaddress">
									<em>* </em>New Password:
								  </label>
								  <input type="password" class="text" id="newpassword" name="newpassword" value="<?php echo $this->newpassword;?>" size="15" tabindex="1" required="min:6"/>
								  <br/>	
								  <div id="newpassword2error" class="ErrorMessageIndividual" style="float:left;">You must confirm your New Password</div>
								  <br/>
								 <label for="password">
									<em>* </em>Confirm Password:
								  </label>
								  <input type="password" class="text" id="newpassword2" name="newpassword2" value="<?php echo $this->newpassword2;?>"  size="15" tabindex="2" required="min:6" />
									</label>
								  
                  
									<p></p>
									<input type="button" class="submit GreenGradient" value="Change Password" id="changePasswordButtonId" tabindex="4"/>
									<p>
									</p>
									<p></p>
									<p></p>
							   </fieldset>
                  </div>
							   <br class="clearleft"/>
						    </div><!-- end of FieldSetWrapper -->
              </form>
 </div> <!--end FormWrapperForBottomBackground -->
</div><!--end FormWrapper -->







