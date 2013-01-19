<?php $session = new Zend_Session_Namespace("userSession"); ?>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#passwordForm').focus();
	
	jQuery('#submitButtonVerify').click(function(){
		login();
	});
	
	jQuery(document).keydown(function(event) {
		if (event.keyCode == 13) {
			login();
		}
	});  
});
function login(){

	var emailaddress =  jQuery('#emailaddressForm').val();
	var password =  jQuery('#passwordForm').val();
	jQuery('div#systemWorkingLoginForm').show();

	valid = validaNewForm('loginFormVerify');
	
  	if(!valid){
  		//jQuery('#LoginErrorMessagesId').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
  		jQuery('form>div#LoginErrorMessagesId').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
  		jQuery('form>div>#MainErrorMessageLogin').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    jQuery('div#systemWorkingLoginForm').hide();
	    jQuery('html, body').animate({scrollTop:0}, 'slow');
	    return;
  	}
	
	jQuery.ajax({
			type: 'POST',
			dataType : 'script',
			data: ({username: emailaddress , password : password }),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/verifypassword',
			success: function(data){
				
	    	}	
	  })

}

</script>

    <div id="FormWrapper">
        <h1>Why am I being asked for my password?</h1>
        <div id="FormWrapperForBottomBackground">
				    <form id="loginFormVerify" name="loginForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/login/verifypassword">
				 <div id="LoginErrorMessagesId" class="ErrorMessages">
                    <div id="MainErrorMessageLogin">Error</div>
	             </div>
              	<div id="FieldsetWrapper">
              	
                    		
      						  <div class="FirstColumnOfTwo" id="SignIntoGoalface">
                          <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/soccerball300100.jpg' height="100" width="300" alt="photo collage" />
          
          								<p />
          									To protect your account, you need to confirm your password periodically.:          								
          								<ul>
          								</ul>
          						
          								Only one account can be signed in at a time.
										If you're not <strong><?php echo $session->screenName;?></strong> sign in with your own <a href="<?php echo Zend_Registry::get("contextPath"); ?>/login/dologout"> ID</a>.
          								
          					</div>             		
                    		
			               <fieldset class="SecondColumnOfTwo" id="SignInFieldset">
                       <div id="systemWorkingLoginForm" class="closeDiv">
                        <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>
                       </div>
                    	 <div id="errorForm" class="error"></div>
                                                     
        								 <label for="emailaddress">
        									Email Address:
        								  </label>
        								  <input type="text" class="text" id="emailaddressForm" name="emailaddressForm" readonly value="<?php echo $session->email;?>" size="35" tabindex="1" />
        								  <br/>
        								  <div id="passwordFormerror" class="ErrorMessageIndividual">You must enter your password</div>
        								  <label for="password">
        									Password:
        								  </label>
        								  <input type="password" class="text" id="passwordForm" name="passwordForm" value="<?php echo $this->password;?>" required="nn" size="15" tabindex="2" />
        									<br/>
        									<p></p>
        									<input type="button" id="submitButtonVerify" class="submit GreenGradient" value="Login" tabindex="4"/>
        									<p>
        									</p>
        									<div>
        									    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword">Forgot your password?</a> | <a href="<?php echo Zend_Registry::get("contextPath"); ?>/options/help">Help</a>
        									</div>
        									<p></p>
        									<div>
        									    Signing Up for a Goalface Account is Easy.
        									    <br />
        									    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="Join GoalFace Here">Join GoalFace Here</a>
        									</div>
        									<p></p>
							   </fieldset>



							   <br class="clearleft"/>
						    </div><!-- end of FieldSetWrapper -->
              </form>
 </div> <!--end FormWrapperForBottomBackground -->
</div><!--end FormWrapper -->







