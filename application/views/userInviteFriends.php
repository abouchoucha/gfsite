<!--  <script type="text/javascript" src="http://www.plaxo.com/css/m/js/util.js"></script>
<script type="text/javascript" src="http://www.plaxo.com/css/m/js/basic.js"></script>
<script type="text/javascript" src="http://www.plaxo.com/css/m/js/abc_launcher.js"></script>-->

<?php $session = new Zend_Session_Namespace('userSession');?>

<script type="text/javascript">
/*function onABCommComplete(data) {
  // OPTIONAL: do something here after the new data has been populated in your text area
  var newText ='';
  if($('recipient_list').value !=''){
    newText = $('recipient_list').value;
    newText+=',';
  }
  for(var i=0 ; i < data.length; i++){
    var element = data[i];
    newText += element[1];
    if(i < (data.length - 1)){
     newText += ',';
    }
   }
   //alert(newText);
   $('recipient_list').value = newText;
}*/
</script>

<script type="text/javascript">


function inviteFriends(type){
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/invite-friends';
     var to = jQuery('#recipient_list').val();
     var subject = jQuery('#Subject').val();
     var message = jQuery('#FriendMessage').val();
     
     //jQuery('#frm-error-text1').load(url , { type :type , to : to , subject : subject , message : message});

     jQuery.ajax({
			type: 'POST',
			dataType : 'script',
			data: ({type :type , to : to , subject : subject , message : message}),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/invite-friends',
			success: function(data){
				
	    	}	
	  })
}

function skipInvite(){

    window.location = "<?php echo Zend_Registry::get("contextPath"); ?>/user/skipInvite"

}


</script>





<div id="FormWrapper">
                <h3>Invite Friends</h3>
                <div id="FormWrapperForBottomBackground">

                    <form name="invite_form" id="InviteFriends" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/user/invite">
                    	<input type="hidden" id= "type" name= "type" value="">
                    	<div id="FieldsetWrapper">
                    		<h5>Invite Friends to Join Goalface</a><div align="center" id="systemWorking" style="display:none"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'></div>
                    		<div align="center" id="frm-error-text1" class="frm-error-text1" ></div>
                    </h5>
                    		<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">Your invitations have been sent.</div>
	             			</div>

							<fieldset class="LabelTop FirstColumnOfTwo" id="inviteFriendsFieldset">

							    <p>Now start <strong>adding your friends</strong> by inviting them to join too.  The more fans <br/>
you invite, the better GoalFace is for everyone!</p>

								<div id="tomailerror" class="ErrorMessageIndividual">Please enter a valid email in the To: field before clicking the send message</div>
								<label for="To">
									To: (enter email adddresses separated by commas)
								  </label>
                                <textarea id="recipient_list" name="to" wrap="on" cols="40"></textarea>
                                <div id="AddressBooks">
                                    <ul>
                                      <!--/goalFaceAppTest/user/importAddress folder changed-->
                                    <!-- li><a href="#" onclick="showPlaxoABChooser('recipient_list', '<?php echo Zend_Registry::get("contextPath"); ?>/user/importAddress'); return false">Add Email (Yahoo, Hotmail, Gmail,other)</a></li>
                                    <li><a href="#">Hotmail Addresses</a></li>-->
                                    </ul>
                                </div>
                                <p></p>
                                 <label for="Subject">
                                    Subject:
                                </label>
                                <input type="text" id="Subject" name="subject" value="Hey, check out Goalface" />
                                <p></p>
								<label for="FriendMessage">
									Message: (max 300 characters, no HTML)
								</label>
                  <textarea id="FriendMessage" name="message">Hi,I just joined a new website for football fans called Goalface. It has Football results, schedules, player profiles and tons of news and information from all over the world. It is a fun way to stay current on football and meet fans. Check it out\n\n http://www.goalface.com/profiles/<?php echo $session->screenName;?></textarea>
								<p></p>
								<input type="hidden" name="invite" value="do_invite" class="hidden">   <!-- look for a class for this element -->
								<input type="button" class="submit blue" name="Send Invitations" value="Send Message" onclick="inviteFriends(1)"/>
								

                </fieldset>

							<div class="SecondColumnOfTwo" id="RightInfoOnForm">
								<h4>Why Invite Friends ?</h4>
									<strong>When someone you invite joins GoalFace by clicking on your invite link:</strong>
									<ul>
									   <li>You earn points </li>
									   <li>They’ll be automatically connected to you</li>
									   <li>You’ll be automatically connected to their friends</li>
									    <li>Your popularity ranking goes up so more  people can find you.</li>
									    <li>The more the merrier!</li>
									</ul>

							</div>
							<br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
          </form>
        </div> <!--end FormWrapperForBottomBackground -->
      </div><!--end FormWrapper -->