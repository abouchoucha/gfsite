 <script type="text/javascript">

//     jQuery('#emailaddress').focus();
//     $('loginForm').observe('submit' , login);

     jQuery(document).ready(function() {
    	 jQuery('#submitButton').click(function() {
 		  	login();
 		 });
   		
 	});
      

	
function login(){

	 jQuery('#error').html('Loading...'); 
	 //jQuery('#error').load(url , {username : username , password:password} );

     jQuery.ajax({
			type: 'POST',
			data: jQuery("#loginForm").serialize(),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/dologin',
			success: function(data){
				 jQuery('#error').html(data);
	    	}	
	  })

		//return false;
} 
</script>
 <!--Login Page jqmodal-->
       <div id="FormWrapper">
        <h3>Login</h3>
        <div id="FormWrapperForBottomBackground">
        	 <form id="loginForm" name="loginForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/login/doLogin">
             <input type="hidden" id="origin" value="<?php echo $this->modalOrigin;?>" name="origin"/>
             <fieldset id="SignInFieldset" class="SecondColumnOfTwo">
               <div align="center" style="display: none;" id="systemWorking">
                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif"/>
               </div>
            	 <div id="error" class="error"/>

        			 <label for="emailaddress">
        					Email Address:
        				  </label>
        				  <input type="text" tabindex="1" size="35" id="emailaddress" value="" name="username" class="text"/>
        				  <br/>

        				  <label for="password">
        					Password:
        				  </label>
        				  <input type="password" tabindex="2" size="15" id="password" value="" name="password" class="text"/>
        					<br/>

        					 <label for="persistent">
        					   <input type="checkbox" tabindex="3" id="side-persistent" value="1" name="persistent" class="checkbox"/>
                     	Remember me
        				  </label>

        					<p/>
        					<!-- <input type="button" id="submitButton" class="submit GreenGradient" value="Login" /> -->
        					<input type="button" tabindex="4" value="Login" class="submit GreenGradient" id="submitButton"/>
        					<p>
        					</p>
        					<div>
        					    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword">Forgot your password?</a> | <a href="#">Help</a>
        					</div>
        					<p/>
        					<div>
        					    Signing Up for a Goalface Account is Easy.
        					    <br/>
        					    <a title="Join GoalFace Here" href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">Join GoalFace Here</a>
        					</div>
        					<p/>
                </fieldset>
                <br class="clearleft"/>
              </form>
        
        
     </div> <!--end FormWrapperForBottomBackground -->
   </div><!--end FormWrapper -->    
 