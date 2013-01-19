<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/validate.js" type="text/javascript"></script>
<?php $sessionRegister = new Zend_Session_Namespace ( "registerSession" );?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script language="javascript">

var RecaptchaOptions = {
        theme : 'custom'
 };

jQuery(document).ready(function() {

	

	jQuery('#cancelbuttonid').click(function(){
		top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/';
	});		
	
	<?php if($this->sent == 'ok'){?>
		jQuery('#ErrorMessagesFeedBack').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay').addClass('ErrorMessagesDisplayBlue');
	   	jQuery('#ErrorMessagesFeedBack').html('Your Contact Us Message was sent successfully');
	<?php } ?>
	jQuery("#Message").charCounter(500);
	
	jQuery('#sendfeedbackbuttonId').click(function(){

		valid = validaNewForm('join');
		if(!valid){
	    	jQuery('#ErrorMessagesFeedBack').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
		    jQuery('#ErrorMessagesFeedBack').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
	    }
		jQuery.ajax({
			type: 'POST',
			data: jQuery("#join").serialize(),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/options/feedback',
			dataType : 'script',
            success: function(text){
				if(text!= ''){
	   		    	jQuery('#ErrorMessagesFeedBack').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	   			    jQuery('#ErrorMessagesFeedBack').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	   		    }else {
					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/contact-us/ok';
	   		    }
	    		 
			}	
		})
		
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
 <div id="ContentWrapper" class="TwoColumnLayout">
             <div class="FirstColumn">
                <?php
                   $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>



                    <?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

          

                      <!--Goalface Join Now-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                        </a>
                        </div>
                    </div>
                    <?php } ?>
              </div><!--end FirstColumnOfThree-->

                <div class="SecondColumn" id="SecondColumnHighlightBox">
                       <div id="FormWrapper">
                            <h3>Contact Us </h3>
                            <div id="FormWrapperForBottomBackground">
                                <form id="join" method="post"  action="<?php echo Zend_Registry::get("contextPath"); ?>/options/feedback">
                                <input type="hidden" name="type" id="type" value="contact">
                                    <div id="FieldsetWrapper">
                                        <div id="ErrorMessagesFeedBack" class="ErrorMessages">
                                            <div id="MainErrorMessageFeedBack">All fields are required.  Please enter the fields highlighted below.</div>
                                        </div>
                                        <h5>Have a question, comment or something to say? If you’re a football fan, potential partner or someone
                                        who loves the game as much as we do, we’d love to hear from you!
                                        </h5>
                                             <p style="font-style:italic">
                                               <?php if($session->email != null){ ?>
                                                    To report abuse such as inappropriate content please visit the violators profile and click on
                                                the 'Report Abuse' link underneath their main photo. A team member will personally
                                                review the reported violation.
                                                <?php } ?>

                                            </p>
                                        <?php if(isset($this->messages)) { ?>
                                                <div id="ErrorMessages" class="ErrorMessagesDisplay">
                                                <?php foreach($this->messages as $message) { ?>
                                                <?php echo $message; ?><br />
                                                <?php } ?>
                                               </div>
                                        <?php } ?>
                                        <div id="ErrorMessages" class="ErrorMessages">
                                            <div id="MainErrorMessage">All fields marked with(*) are required.  Please enter the fields highlighted below.</div>
                                        </div>
                                        <fieldset class="FirstColumnOfTwo" id="">
                                             <div id="nameerror" class="ErrorMessageIndividual">You must enter your name</div>
                                             <label for="name">
                                                <?php if($session->email != null){ ?>
                                                    User Name :
                                                <?php } else {?>
                                                    Your Name:
                                                <?php }?>
                                              </label>
                                                <?php if($session->email != null){ ?>
                                                    <?php echo $session->screenName; ?>
                                                <?php } else {?>
                                                    <input class="text" type="text" id="name" name="name"  autocomplete="off"/>
                                                <?php }?>

                                              <p></p>
                                             <?php if($session->email == null){ ?>
                                             <div id="emailaddresserror" class="ErrorMessageIndividual">You must enter an Email Address</div>
                                             <label for="emailaddress">
                                                <em>*</em>Email Address:
                                              </label>
                                              <input class="text" type="text" id="emailaddress" name="emailaddress" required="email" autocomplete="off" />
                                              <p></p>
                                              <?php } ?>
                                              <div id="subjectfeedbackerror" class="ErrorMessageIndividual">You must select a Subject from the list</div>
                                              <label for="subject">
                                                <em>*</em>Subject:
                                              </label>
                                              <select name="subjectfeedback" id="subjectcontacus" required="nn">
                                                    <option value="">--Select--</option>
                                                    <option value="Advertising/Sponsorships">Advertising/Sponsorships</option>
                                                    <option value="Copyright Claim">Copyright Claim</option>
                                                    <option value="Link Request">Link Request</option>
                                                    <option value="Partnership Inquiry">Partnership Inquiry</option>
                                                    <option value="Press/Media Inquiry">Press/Media Inquiry</option>
                                                    <option value="Site Feedback-Report a Problem">Site Feedback-Report a Problem</option>
                                                    <option value="General Inquiry">General Inquiry</option>
                                                </select>
                                              <p></p>
                                               <div id="messagefeedbackerror" class="ErrorMessageIndividual">You must enter a message</div>
                                               <label for="Message">
                                                <em>*</em>Message:
                                               </label>
                                                (Please be as detailed as possible)
                                                <textarea id="Message" name="messagefeedback" required="nn"></textarea>
                                                <p></p>
                                                <?php if($session->email == null){ ?>
                                                <div id="recaptcha_response_fielderror" class="ErrorMessageIndividual">Enter the text that appears in the image below</div>
                                                <label for="WordVerification">
                                                <em>* </em>
                                                Word Verification:
                                              </label>
                                                Type the characters you see in the picture below.
                                                <p/>
                                                <div id="recaptcha_container">
								    
												    <input id="recaptcha_response_field" type="text" name="recaptcha_response_field" class="text" required="nn"/>
				                                    <br>
												    <div id="recaptcha_image">
				
				                                    </div>
				
												    <span>Choose captcha format: <a href="javascript:Recaptcha.switch_type('image');">Image</a> or <a href="javascript:Recaptcha.switch_type('audio');">Audio</a> </span>
												
												    <input class="recaptchabtn" type="button" id="recaptcha_reload_btn" value="Get new words" onclick="Recaptcha.reload();" />
												</div>
												
												<script type="text/javascript" src="http://api.recaptcha.net/challenge?k=<?php echo $this->captchapublickey;?>"></script>
												
												<noscript>
												    <iframe src="http://api.recaptcha.net/noscript?k=<?php echo $this->captchapublickey;?>" height="300" width="500" frameborder="0"></iframe>
												
												    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
												    <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
												
												</noscript>
                                                <?php } ?>
                                                <p></p>
                                               <fieldset class="AddToPhotoButtonWrapper"> 
                                                <input type="button" name="sendfeedbackbuttonId" id="sendfeedbackbuttonId" class="submit GreenGradient" value="Send Message" />
                                                <input type="button" class="submit GreenGradient" name="Register" id="cancelbuttonid" value="Cancel">
                                               </fieldset> 
                                        </fieldset>

                                        <br class="clearleft"/>
                                    </div><!-- end of FieldSetWrapper -->
                                </form>
                            </div> <!--end FormWrapperForBottomBackground -->
                        </div><!--end FormWrapper -->
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->