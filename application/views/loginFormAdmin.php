<script type="text/javascript">

    jQuery(document).ready(function() {
<?php if($this->profileCreated =='true') { ?>
                jQuery('#LoginMessages').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay').addClass('ErrorMessagesDisplayBlue');
                jQuery('#LoginMessages').html('Your profile has been created. Please Login');
    <?php } ?>
                jQuery('#emailaddressForm').focus();

                jQuery('#submitButtonLoginFormId').click(function(){
                    login();
                });
                //alert('<?php echo($this->checked); ?>');
				jQuery('#rememberIdForm').attr('checked','<?php echo($this->checked); ?>');

                jQuery(document).keydown(function(event) {
                    if (event.keyCode == 13) {
                        login();
                    }
                });  
            });
            function login(){

                var emailaddress =  jQuery('#emailaddressForm').val();
                var password =  jQuery('#passwordForm').val();
                var referrer = jQuery('#referrerForm').val();
                var persistent = jQuery('#rememberIdForm:checked').val();
                jQuery('div#systemWorkingLoginForm').show();

                valid = validaNewForm('loginForm');
				if(!valid){
                    //jQuery('#LoginErrorMessagesId').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
                    //jQuery('form>div#LoginErrorMessagesId').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
                    //jQuery('form>div>div>#MainErrorMessageLogin').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
                     jQuery('#ErrorMessagesLogin').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
                     jQuery('#MainErrorMessageLogin').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
                    jQuery('div#systemWorkingLoginForm').hide();
                    jQuery('html, body').animate({scrollTop:0}, 'slow');
                    return;
                }

                jQuery.ajax({
                    type: 'POST',
                    dataType : 'script',
                    data: ({username: emailaddress , password : password , referrer : referrer , persistent : persistent}),
                    url: '<?php echo Zend_Registry::get("contextPath"); ?>/admin/dologin',
                    success: function(data){

                    }	
                })

            }

</script>

<div id="FormWrapper">
    <h1 style="padding-top:80px;font-size:160%;">Sign in to GoalFace Administrator</h1>
    <div id="FormWrapperForBottomBackground">
        <form id="loginForm" name="loginForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/login/dologin">
            <input type="hidden" name="referrerForm" id="referrerForm" value="<?php echo $this->referrer != '' ? $this->referrer :Zend_Registry::get("contextPath")?>">

            <div id="FieldsetWrapper">
                <div id="ErrorMessagesLogin" class="ErrorMessages" style="width:864px;margin-bottom:10px;margin-left:0;">
                    <div id="MainErrorMessageLogin">Error</div>
                </div>	    

                <div class="FirstColumnOfTwo" id="SignIntoGoalface">
                    <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/soccerball300100.jpg' height="100" width="300" alt="photo collage" />

                    

                    <!--	Dont have a GoalFace account yet? What are you waiting for? <a href="/register" title="Join GoalFace Here">Sign up Here</a>-->

                </div>             		

                <fieldset class="SecondColumnOfTwo" id="SignInFieldset">
                    <div id="systemWorkingLoginForm" class="closeDiv">
                        <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif' alt="">
                    </div>
                     
                    <label for="emailaddress">
        		UserName:
                    </label>
                    
                    <input type="text" class="text" id="emailaddressForm" name="emailaddressForm" value="<?php echo $this->email;?>" size="35" tabindex="1" required="nn"/>
                    <div id="emailaddressFormerror" class="ErrorMessageIndividual" style="float:left;">You must enter your Email Address</div>
                    <br/>
                    
                    <label for="password">
        		Password:
                    </label>
                    
                    <input type="password" class="text" id="passwordForm" name="passwordForm" value="<?php echo $this->password;?>"  size="15" tabindex="2" required="nn"/>
                    <div id="passwordFormerror" class="ErrorMessageIndividual" style="float:left;">You must enter your password</div>
                    <br/><br/>

                    <label for="persistent">
                        <input type="checkbox" class="checkbox" checked="" name="persistent" value="1" id="rememberIdForm" tabindex="3"/>
                        &nbsp;Remember me
                    </label>


                     <br><br><br>
                    <input type="button" id="submitButtonLoginFormId" class="submit GreenGradient" value="Login" tabindex="4"/>
                	
                   
                    
                </fieldset>

                <br class="clearleft"/>
            </div><!-- end of FieldSetWrapper -->
        </form>
    </div> <!--end FormWrapperForBottomBackground -->
</div><!--end FormWrapper -->







