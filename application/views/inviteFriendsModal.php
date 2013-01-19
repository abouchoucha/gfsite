<!--<script type="text/javascript" src="http://www.plaxo.com/css/m/js/util.js"></script>
<script type="text/javascript" src="http://www.plaxo.com/css/m/js/basic.js"></script>
<script type="text/javascript" src="http://www.plaxo.com/css/m/js/abc_launcher.js"></script>-->

<script language="javascript">

jQuery(document).ready(function() {
	jQuery('#mailButtonSend').click(function(){
		     var url = "<?php echo Zend_Registry::get('contextPath'); ?>/index/invite"; 
		     var to   = jQuery('#recipient_list').attr('value');
		     var subject = jQuery('#Subject').attr('value');
		     var message   = jQuery('#FriendMessage').attr('value');
		     var valid = true;
		     if(to == ''){	
		     	jQuery('#tomailerror').addClass('ErrorMessageIndividualDisplay');	
		     	valid = false;	
		     }
		     if(subject == ''){	
		    	jQuery('#subjecterror').addClass('ErrorMessageIndividualDisplay');
			    valid = false;	
			 }
		     if(message == ''){	
		    	jQuery('#messageerror').addClass('ErrorMessageIndividualDisplay');
			    valid = false;	
			 }
			 if(valid == false){
				return false;	
			 }	
		     
			 var result = jQuery('#MainErrorMessage').load(url , {to : to , message : message , subject : subject});

			 if(result == ''){
				 if(jQuery('#MessageOkInviteFriends').is(":hidden")){
					jQuery('#MessageOkInviteFriends').show("slow");
					jQuery('#MessageOkInviteFriends').animate({opacity: '+=0'}, 2000).slideUp('slow');
					jQuery('#inviteFriendsModal').animate({opacity: '+=0'}, 2000).slideUp('slow');
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


<div id="FormWrapper">
                <h3>Invite Friends</h3>
                <div id="FormWrapperForBottomBackground">

                    <form name="invite_form" id="InviteFriends" method="post" action="#">
                    	<input type="hidden" id= "type" name= "type" value="">
                    	<div id="FieldsetWrapper">
                    		<h5>Invite Friends to Join Goalface</a><div align="center" id="systemWorking" style="display:none"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'></div>
                    		<div align="center" id="frm-error-text1" class="frm-error-text1" ></div>
                    </h5>
                    		<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">Ooops, there was a problem!</div>
	             			</div>
							<div id="MessageOkInviteFriends" class="ErrorMessages closeDiv">
                                    	This page has been emailed successfully.
                            </div>
							<fieldset class="LabelTop FirstColumnOfTwo" id="inviteFriendsFieldset">

							    <p>Now start <strong>adding your friends</strong> by inviting them to join too.  The more fans <br/>
								you invite, the better GoalFace is for everyone!</p>

								<div id="tomailerror" class="ErrorMessageIndividual">Please enter a valid email in the To: field before clicking the send message button</div>
								<label for="To">
									To: (enter email adddresses separated by commas)
								  </label>
                                <textarea id="recipient_list" name="to" wrap="on" cols="40"></textarea>
                                <div id="AddressBooks">
                                    <ul>
                                      <!--/goalFaceAppTest/user/importAddress folder changed-->
                                    <li><a href="#" onclick="showPlaxoABChooser('recipient_list', '<?php echo Zend_Registry::get("contextPath"); ?>/user/importAddress'); return false">Add Email (Yahoo, Hotmail, Gmail,other)</a></li>
                                    <!--<li><a href="#">Hotmail Addresses</a></li>-->
                                    </ul>
                                </div>
                                <p></p>
                                <div id="subjecterror" class="ErrorMessageIndividual">Please enter something in the Subject: text field before clicking the send message</div>
                                 <label for="Subject">
                                    Subject:
                                </label>
                                <input type="text" id="Subject" name="subject" value="Hey, check out Goalface" />
                                <p></p>
                                <div id="messageerror" class="ErrorMessageIndividual">Please enter something in the Message: text field before clicking the send message</div>
								<label for="FriendMessage">
									Message: (max 300 characters, no HTML)
								</label>
				                   <textarea id="FriendMessage" name="message">Hi,I just joined a new website for football fans called Goalface. It has Football results, schedules, player profiles and tons of news and information from all over the world. It is a fun way to stay current on football and meet fans. Check it out\n\n http://www.goalface.com</textarea>
								<p></p>
								</p>
								<input type="button" id="mailButtonSend" class="submit GreenGradient" name="Register" value="Send Message"/>
								

                </fieldset>

							<div class="SecondColumnOfTwo" id="RightInfoOnForm">
								<h4>Why Invite Friends?</h4>
									<strong>When someone you invite joins GoalFace by clicking on your invite link:</strong>
									<ul>
									   <li>You earn points </li>
									   <li>They'll be automatically connected to you</li>
									   <li>You'll be automatically connected to their friends</li>
									    <li>Your popularity ranking goes up so more  people can find you.</li>
									    <li>The more the merrier!</li>
									</ul>

							</div>
							<br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
          </form>
        </div> <!--end FormWrapperForBottomBackground -->
      </div><!--end FormWrapper -->
      
      
      