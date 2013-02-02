<?php require_once 'Common.php';
 require_once 'seourlgen.php';
 //require_once 'Zrad/Zrad_Facebook.php';
 require_once 'Zrad/cFacebook.php';

 	$urlGen = new SeoUrlGen();
 	$session = new Zend_Session_Namespace('userSession');
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
    include 'include/functions.php';
    
    //to be used to check active menu
    $name_array = explode('/',$this->actualURL);
    
?>
<script type="text/JavaScript">
var closeModal = function(h) { 
    //t.html('Please Wait...');  // Clear Content HTML on Hide.
    h.o.remove(); // remove overlay
    h.w.fadeOut(700); // hide window
	
};
//Global contextPath variable for JS ajax calls;
contextPath= "<?php echo Zend_Registry::get("contextPath");?>";
root_crop = "<?php echo $config->path->crop;?>";
ajaxLoaderImage = "<?php echo Zend_Registry::get("contextPath");?>/public/images/ajax-loader3.gif";
isLoggedIn = <?php echo (empty($session->email)) ? 'false' : 'true';?>;
pageurl = "<?php echo $this->actualURL;?>";

jQuery(document).ready(function() {

	//alert(menuitems[1].getAttribute("rel"));
	
	/* Default when load menu home*/ 
	//jQuery('#nav_scores').addClass('menuactive');
	
	/* Active and Unactive */
	 jQuery('.chromestyle ul li a').click(function(){
	  	//jQuery(this).parent().find('li a.selected').removeClass('selected');
		//jQuery(this).children().addClass('selected');
		//jQuery('.chromestyle ul li a').not(this).removeClass("selected");
		//jQuery(this).addClass('selected');
		//var nav_id = jQuery(this).attr('id');
		//jQuery(this).parent().find('li a.active').removeClass('active');
		//jQuery('#' + nav_id + ' li a').addClass('selected');
		var url = jQuery(this).attr('href');
		var menuitem = url.split("/")[2] ;
		//alert(menuitem);	

		if (menuitem == 'live-scores-match-schedules') {
			//alert("scores and schedules")
			jQuery('#nav_scores').addClass('menuactive');
		}	
		//news
		//players
		//teams
		//tournaments
		//profiles
		
	 });

	 jQuery('.dropmenudiv a').click(function(){

			var url = jQuery(this).attr('href');
			var menuitem = url.split("/")[2] ;
			//alert(menuitem);		
	  });
	

	 jQuery('#search-query').focus(function() {
		 	if(this.value=='Search GoalFace...'){
			 	this.value='';
		 	}	
		 		jQuery('#searchButtonId').removeAttr('disabled');
		 	 
		});

	 jQuery('#search-query').blur(function() {
		 if(this.value=='') {
			 this.value='Search GoalFace...';
			 jQuery('#searchButtonId').attr('disabled');
		 }		 
		  
	 });	 
	 

	  jQuery('#homepageFirefox').jqm({trigger: '#makeHomePage', onHide: closeModal ,modal:true });

	  jQuery('a#invitefriendsTrigger').click(function(){
			<?php if ($session->email != null) { ?>
				inviteFriends();
			<?php } else { ?>
				loginModal();
			<?php }  ?>
	  });
	 
	//multipurpose modal
	 
	  jQuery('a#emailThisPageTrigger').click(function(){	
	  		sendPageByEmail();
	   });

<?php if ($session->origin == 'inviteFriends') {?>
	 jQuery('#inviteFriendsModal').jqmShow();
	 <?php 
	 	$session->origin = null;
	 } ?>
	 <?php if ($session->origin == 'emailPage') {?>
	 	jQuery('#emailPageModal').jqmShow();
	 <?php 
	 	$session->origin = null;
	 } ?>

	 jQuery('#searchButtonId').click(function(){	
		 	var searchText = escape(jQuery('#search-query').val());
	  		window.location = "<?php echo Zend_Registry::get('contextPath'); ?>/search/?q="+searchText;
	   });
	 	
  
});

function showHideDivBox(arrowId , contentId){
	 jQuery('#'+arrowId).click(function(){ 
			if(jQuery('#'+contentId).is(":hidden")){	
				jQuery('#'+contentId).show();
				jQuery('#'+arrowId).removeClass('RightArrow').addClass('DownArrow');
			}else{
				jQuery('#'+contentId).hide();
				jQuery('#'+arrowId).removeClass('DownArrow').addClass('RightArrow');
			}
	});

}

function toggleCompetition(competion , same){
	   if(jQuery('#'+competion).is(":hidden")){
		   jQuery('#'+competion).show();
		   jQuery(same).removeClass('RightArrow').addClass('DownArrow');
	   }else {
		   jQuery('#'+competion).hide();
		   jQuery(same).removeClass('DownArrow').addClass('RightArrow');
		}		   
}


function loginModal(){
	var url = "<?php echo Zend_Registry::get('contextPath'); ?>/user/register";
	jQuery('#loginModal').jqm({ onHide: closeModal ,modal:true});
	jQuery('#loginModal').jqmShow();
	//jQuery('#error').html('You must be a GoalFace member to do that.Please sign in below or <a title="Join GoalFace Here" href=".url.">Click here to register</a> for GoalFace.');

	var checked = '<?php echo (isset($_COOKIE ["checked"])?$_COOKIE ["checked"]:"");?>';
	jQuery('#rememberIdModal').attr('checked',checked);
	jQuery("#acceptLoginModalButtonId").unbind();
	jQuery('#acceptLoginModalButtonId').click(function(){
		dologinModal();
	});
		
}


function inviteFriends(){

	jQuery('#tomailerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	jQuery('#subjecterror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	jQuery('#messageerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	jQuery('#ErrorMessages').removeClass('ErrorMessageIndividualDisplay');
	
	jQuery('#recipient_list').val('');
	jQuery('#Subject').val('Hey, check out GoalFace');
	jQuery('#FriendMessage').val("Hi,\nI just joined a new website for football fans called GoalFace. It has football match results, player and team profiles, pictures and tons of news and information from leagues and tournaments all over the world. GoalFace is 100% football and a fun and easy way to follow the game.");
	
	jQuery('#inviteFriendsModal').jqm({trigger: 'a#invitefriendsTrigger', onHide: closeModal,modal:true });
	jQuery('#inviteFriendsModal').jqmShow();

	jQuery('#mailButtonSend').unbind();
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
	     
		 //var result = jQuery('#MainErrorMessage').load(url , {to : to , message : message , subject : subject});

		 jQuery.ajax({
				type: 'POST',
				data: ({to : to , message : message , subject : subject}),
				url : url,
				success: function(data){
				    if(data == ''){ 
				 		jQuery('#inviteFriendsModal').jqmHide();
				 		jQuery('#messageConfirmationId').jqm({trigger: '#invitefriendstrigger', onHide: closeModal ,modal:true});
						jQuery('#messageConfirmationId').jqmShow();
						jQuery('#modalTitleConfirmationId').html('Invite Friends');
						jQuery('#messageConfirmationTextId').html('An invitation to goalface.com has been sent to all the email addreses.');
						jQuery('#acceptModalButtonId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#cancelModalButtonId').attr('value','Close');
						jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 3500).jqmHide();
				    }else {
				    	jQuery('#ErrorMessages').html(data);
					}
				}	
			})
	});  
}

function sendPageByEmail(){

		var k = jQuery('div.ErrorMessageIndividualDisplay');
	    for(var i=0;i < k.length;i++ ){ 
	       k[i].className ='ErrorMessageIndividual';
	    }
		jQuery('#sendEmailModalForm')[0].reset();             // this resets form variables to their initial values (must be set in form fields)...
		jQuery('#labeltoId').hide();
		jQuery('#sendEmailto').show();
		
    	<?php if($session->email == null){ ?>
    		jQuery('#emailFromId').show();
    		jQuery('#sendEmailfrom').val("");
    		jQuery('#sendEmailfrom').attr('required','email');
		 <?php }?>
    	jQuery('#sendEmailBodyId').show();
		jQuery('#sendEmailBodyResponseId').hide();
		jQuery('#acceptSendEmailButtonId').show(); 	
		jQuery('#sendEmailTitleId').text('EMAIL THIS PAGE');
		jQuery('#sendEmailto').val("");
		jQuery('#sendEmailtextForwardFriend').val("");
		
		jQuery('#sendEmailto').attr('required','email');
		jQuery('#sendEmailtextForwardFriend').val("Hi,I just joined a new website for football fans called GoalFace and wanted to share this page");
		jQuery('#sendEmailsubject').val("<?php echo $this->escape($this->title); ?>");
		jQuery('#sendEmailsubject').attr('readonly','readonly');
		
		jQuery('#sendEmailModal').jqm({trigger: '#forwardToFriendTrigger', onHide: closeModal,modal:true });
		jQuery('#sendEmailModal').jqmShow();

		jQuery('#acceptSendEmailButtonId').unbind();
		jQuery('#acceptSendEmailButtonId').click(function() {

			valid = validaNewForm('sendEmailModalForm');
			if(valid){
		 		var from = jQuery('#sendEmailfrom').val();
				var to = jQuery('#sendEmailto').val();
				var subject = jQuery('#sendEmailsubject').val();
				var pageurl = "<?php echo $this->actualURL; ?>";
				var message = jQuery('#sendEmailtextForwardFriend').val();
				
				jQuery.ajax({
					type: 'POST',
					data: ({from : from , to :to , subject : subject , pageurl : pageurl , message : message }),
					url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/sendthispagebyemail',
					success: function(data){
						jQuery('#sendEmailBodyResponseId').html('The page was emailed successfully');
						jQuery('#sendEmailBodyId').hide();
						jQuery('#sendEmailBodyResponseId').show();
						jQuery('#acceptSendEmailButtonId').hide();
						jQuery('#cancelSendEmailButtonId').attr('value','Close');
						jQuery('#sendEmailModal').animate({opacity: '+=0'}, 2500).jqmHide();
					}	
				})
			}
		});

    }


function openbrowserlogin(){
	open("http://<?php echo $_SERVER['SERVER_NAME']; ?>/login/","GoalFace Login");
}


function openbrowserregister(){
	open("http://<?php echo $_SERVER['SERVER_NAME']; ?>/register/","GoalFace Registration");
}





<?php if($this->actualURL != '/sign-in/') {?>

function dologinModal(){

	var emailaddress =  jQuery('#emailaddressModal').val();
	var password     =  jQuery('#passwordModal').val();
	var referrer     =  jQuery('#referrerModal').val();
	var persistent   =  jQuery('#rememberIdModal:checked').val();
	jQuery('div#systemWorkingLoginForm').show();
	
	valid = validaNewForm('loginModalForm');
	
  	if(!valid){
  	    jQuery('#systemWorkingLoginForm').hide();
	    jQuery('html, body').animate({scrollTop:0}, 'slow');
	    jQuery('div#systemWorkingLoginForm').hide();
	    return;
  	}
		
	jQuery.ajax({
			type: 'POST',
			dataType : 'script',
			data: ({username: emailaddress , password : password , referrer : referrer , persistent : persistent}),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/dologin',
			success: function(data){
				 
	    	}	
	  })

		//return false;
}
<?php }?>


/*-----------------------------Dirty form------------------------------*/
var isConfirmationSaveAlerts=false;
var href_='';
function popUpConfirmationSaveAlerts(elemento){
	 $('#messageConfirmationFrmDirtyId').show();	 	
	 jQuery('#modalTitleConfirmationFrmDirtyId').html('Confirmation');
	 jQuery('#messageConfirmationTextFrmDirtyId').html('Do you want to save the changes you made to your Updates & Alerts Settings?');
	 jQuery('#messageConfirmationFrmDirtyId').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
	
	 jQuery('#messageConfirmationFrmDirtyId').jqmShow();
	 
	 jQuery("#saveModalButtonFrmDirtyId").unbind();
	 jQuery("#dontsaveModalButtonFrmDirtyId").unbind();
	 jQuery("#cancelModalButtonFrmDirtyId").unbind();
	 jQuery('#saveModalButtonFrmDirtyId').click(function(){
		 isConfirmationSaveAlerts=true;
		 if($(elemento).attr('id')=="searchButtonId" ||  $(elemento).attr('id')=="search-query"){
			href_="";
		 }else{
			href_=$(elemento).attr("href");
		}
		 jQuery('#saveAlertsButtonId').click();
		 //Borrar la línea de abajos
		 //window.location = href_;
	 });
	 jQuery('#dontsaveModalButtonFrmDirtyId').click(function(){
		 jQuery('#closeModalFrmDirtyId').click();
		 
		 if($(elemento).attr('id')=="searchButtonId" ||  $(elemento).attr('id')=="search-query"){
			var searchText = escape(jQuery('#search-query').val());
			window.location = "<?php echo Zend_Registry::get('contextPath'); ?>/search/?q="+searchText;
		 }else{
			 href_=$(elemento).attr("href");
			 if(href_!=""){
				window.location = href_;
			 }
		 }
	 });
	 jQuery('#cancelModalButtonFrmDirtyId').click(function(){
		 jQuery('#closeModalFrmDirtyId').click();
	 });
}

function popUpMessageInformation(mensaje){
	 $('#messageInformationId').show();	 	
	 jQuery('#modalTitleInformationId').html('Information');
	 jQuery('#messageInformationTextId').html(mensaje);
	 jQuery('#messageInformationId').jqm({trigger: '#acceptModalButtonInformationId', onHide: closeModal });
	
	 jQuery('#messageInformationId').jqmShow();
	 
	 jQuery("#acceptModalButtonInformationId").unbind();

	 jQuery('#acceptModalButtonInformationId').click(function(){
		 jQuery('#closeModalInformationId').click();
	 });
}

function popUpLoading(mensaje){
	 $('#messageLoadingId').show();
	 jQuery('#modalTitleLoadingId').html('Information');
	 jQuery('#messageLoadingTextId').html(mensaje);
	 jQuery('#messageLoadingId').jqm({trigger: '#acceptModalButtonLoadingId', onHide: closeModal });
	 jQuery('#loadingId').css('display','block');
	 jQuery('#imageLoadingId').css('display','block');
	 
	 jQuery('#messageLoadingId').jqmShow();
	 
	 //jQuery("#acceptModalButtonLoadingId").unbind();

	 //jQuery('#acceptModalButtonLoadingId').click(function(){
		 //jQuery('#closeModalLoadingId').click();
	 //});
}

var isMessageConfirmation=false;
function popUpMessageConfirmation(elemento,titulo,mensaje,botonOk,botonCancel){
	 isMessageConfirmation=true;
	 $('#acceptModalButtonConfirmationId').attr('value',botonOk);
	 $('#cancelModalButtonConfirmationId').attr('value',botonCancel);
	 
	 $('#messageConfirmationId').show();
	 jQuery('#modalTitleConfirmationId').html(titulo);
	 jQuery('#messageConfirmationTextId').html(mensaje);
	 jQuery('#messageConfirmationId').jqm({trigger: '#acceptModalButtonId', onHide: closeModal });
	
	 jQuery('#messageConfirmationId').jqmShow();
	 
	 jQuery("#acceptModalButtonConfirmationId").unbind();
	 jQuery("#cancelModalButtonConfirmationId").unbind();

	 jQuery('#cancelModalButtonConfirmationId').click(function(){
		isMessageConfirmation=false;
		 jQuery('#closeModalConfirmationId').click();
	 });
	 jQuery('#acceptModalButtonConfirmationId').click(function(){
		 jQuery(elemento).click();
	 });
}

function frmIsDirty(elemento){
	var cantDirty=$(".FrmValidarDirty input[type='text'].isDirty,input[type='checkbox'].isDirty,input[type='radio'].isDirty,select.isDirty")
	//alert("Cant Dirty:"+cantDirty.length)
	if(cantDirty.length>0){
		/*Show Pop-up*/
		popUpConfirmationSaveAlerts(elemento);
		return false;
		/*-----------*/
	}else{
		if($(elemento).attr('id')=="searchButtonId" ||  $(elemento).attr('id')=="search-query"){
			var searchText = escape(jQuery('#search-query').val());
			window.location = "<?php echo Zend_Registry::get('contextPath'); ?>/search/?q="+searchText;
		}
		return true;
	}
}	   

function searchEnter(elemento,event) {
	if (event.which == 13 || event.keyCode == 13) {
		frmIsDirty(elemento)
	}
}

function popUpSuspendAccount(title,message){
	console.log('suspsned')
	
	jQuery.ajax({
		type: 'POST',
		url: '<?php echo Zend_Registry::get("contextPath"); ?>/suspendaccount/getmotives',
		success: function(data){
			jQuery('#motiveSAId').html(data);
		}	
	})
		
	console.log('suspsned ->')
	 $('#messageSuspendAccountId').show();
	 jQuery('#modalTitleSAId').html(title);
	 jQuery('#messageSATextId').html(message);
	 jQuery('#messageSuspendAccountId').jqm({trigger: '#acceptModalButtonSAId', onHide: closeModal });
	 jQuery('#messageSuspendAccountId').jqmShow();
	 
	 jQuery("#acceptModalButtonSAId").unbind();

	 jQuery('#acceptModalButtonSAId').click(function(){
		/*jQuery.ajax({
			type: 'POST',
			dataType : 'script',
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/suspendaccount/usersuspendaccount',
			data: ({motiveId: jQuery('#motiveSAId').val() }),
			success: function(data){
				//alert(data);
			}	
		})*/
		window.location = "<?php echo Zend_Registry::get('contextPath'); ?>/suspendaccount/usersuspendaccount/?motiveId="+jQuery('#motiveSAId').val();
	 });
	 
	 jQuery('#cancelModalButtonSAId').click(function(){
		 jQuery('#closeModalSAId').click();
	 });
	 
}

/************************************************************************/


</script>


<!------------------------------DIRTY FORM - BEGIN---------------------------------------->
<div id="messageConfirmationFrmDirtyId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleConfirmationFrmDirtyId"></h4>
                <div id="closeModalFrmDirtyId" class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li id="messageConfirmationTextFrmDirtyId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li>
                	<input type="button" id="saveModalButtonFrmDirtyId" class="submit" value="Save"/>
                	<input type="button" id="dontsaveModalButtonFrmDirtyId" class="submit" value="Don't Save"/>
                	<input type="button" id="cancelModalButtonFrmDirtyId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
</div> <!--end wrapper-->
<!------------------------------DIRTY FORM -END ---------------------------------------->

<!------------------------------DIALOG MESSAGE INFORMATION - BEGIN---------------------------------------->
<div id="messageInformationId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleInformationId"></h4>
                <div id="closeModalInformationId" class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li id="messageInformationTextId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li>
                	<input type="button" id="acceptModalButtonInformationId" class="submit" value="Accept"/>
                </li>
            </ul>
        </div>
    </div>
</div> <!--end wrapper-->
<!------------------------------DIALOG MESSAGE INFORMATION -END ---------------------------------------->

<!------------------------------DIALOG MESSAGE LOADING - BEGIN---------------------------------------->
<div id="messageLoadingId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleLoadingId"></h4>
                <div id="closeModalLoadingId" class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
			<div id="loadingId" style="display: block;float:left; padding-right:7px">
				<img id="imageLoadingId" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/loading.gif" width="20px" height="20px"  />
			</div>
                <ul>
                    <li id="messageLoadingTextId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li>
                	<input type="button" id="acceptModalButtonLoadingId" class="submit" value="Accept"/>
                </li>
            </ul>
        </div>
    </div>
</div> <!--end wrapper-->

<!------------------------------DIALOG MESSAGE INFORMATION -END ---------------------------------------->
<div id="messageConfirmationId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleConfirmationId"></h4>
                <div id="closeModalConfirmationId" class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li id="messageConfirmationTextId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li><input type="button" id="acceptModalButtonConfirmationId" class="submit" value="Ok"/>
                <input type="button" id="cancelModalButtonConfirmationId"  class="submit jqmClose" value="Cancel"/></li>
            </ul>
        </div>
    </div>
 </div>

 <!------------------------------DIALOG SUSPEND ACCOUNT - BEGIN---------------------------------------->
<div id="messageSuspendAccountId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleSAId"></h4>
                <div id="closeModalSAId" class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here
			<div id="SAId" style="display: block;float:left; padding-right:7px">
				<img id="imageLoadingId" src="<?php //echo Zend_Registry::get("contextPath"); ?>/public/images/loading.gif" width="20px" height="20px"  />
			</div>-->
                <ul>
                    <li id="messageSATextId"></li>
					<select class="sell" id="motiveSAId" style="font:11px Helvetica,Arial,sans-serif" >
						<option value="0">This is temporary. I'll be back.</option>
					</select>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li>
                	<input type="button" id="acceptModalButtonSAId" class="submit" value="Accept"/>
					<input type="button" id="cancelModalButtonSAId" class="submit" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
</div> <!--end wrapper-->



<?php if($this->actualURL != '/sign-in/') {?>

<div id="loginModal" class="jqmGeneralWindow">
     
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId">GoalFace Member Sign In</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            
            <form id="loginModalForm" name="loginModalForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/login/doLogin">
             <input type="hidden" id="origin" value="<?php echo $this->modalOrigin;?>" name="origin"/>
             <input type ="hidden" id="referrerModal" name="referrer" value="<?php echo $this->actualURL; ?>"/>
             <div id="ErrorMessagesLogin" class="ErrorMessages">
                   <div id="MainErrorMessageLogin">Error</div>
	          </div>
             <fieldset id="SignInFieldset" class="SecondColumnOfTwo">
             
               
               
            	 <div class="error">You must be a GoalFace member to do that.  
						Please sign in below or <a title="Join GoalFace Here" href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">click here to create an account</a> if you do not have one.
            	 </div>
				 <p>
				 <p>
				 	<div id="emailaddressModalerror" class="ErrorMessageIndividual">You must enter your Email Address</div> 
        			 	<label for="emailaddress">
        					Email Address:
        				  </label>
        				  <input type="text" tabindex="1" size="35" id="emailaddressModal" value="<?php echo (isset($_COOKIE ["emailGoalface"])?$_COOKIE ["emailGoalface"]:"");?>" name="emailaddressModal" class="text" required="email"/>
        				  <br/>
						 <div id="passwordModalerror" class="ErrorMessageIndividual">You must enter your password</div>
        				  <label for="password">
        					Password:
        				  </label>
        				  
        				  <input type="password" tabindex="2" size="15" id="passwordModal" value="<?php echo (isset($_COOKIE ["passwordGoalface"])?$_COOKIE ["passwordGoalface"]:"");?>" name="passwordModal" class="text" required="nn"/>
        					<br/><br/>
        					<!--
        				
        				Already have a facebook account?<br>
        					<div id="fb-root"></div>						
							Sign In using <fb:login-button size="small" scope="<?php //echo cFacebook::getPermission();?>">Facebook</fb:login-button>
						<br/><br/>
        					-->
        					
        					<label for="persistent">
        					   <input type="checkbox" tabindex="3" checked="" id="rememberIdModal" value="1" name="persistent" class="checkbox"/>
                     			Remember me
        				  	</label>

        					</p>
        					<div>
        					    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword">Forgot your password?</a> 
        					</div>
        					<p/>
        					
                </fieldset>
                <br class="clearleft"/>
              </form>
            <!--set the background image here-->
                <ul>
                    <li id="messageTextId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li><input type="button" id="acceptLoginModalButtonId" class="submit" value="Login"/>
                <input type="button" id="cancelLoginModalButtonId"  class="submit jqmClose" value="Cancel"/>
                <div align="center" class="closeDiv" id="systemWorkingLoginForm">
                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"/>
                </div>
                </li>
            </ul>
        </div>
    </div>
 </div>

<?php }?>


<div id="messageConfirmationId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleConfirmationId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li id="messageConfirmationTextId"></li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li><input type="button" id="acceptModalButtonId" class="submit" value="Ok"/>
                <input type="button" id="cancelModalButtonId"  class="submit jqmClose" value="Cancel"/></li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->
 
 <div id="addFavoriteModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalFavoriteTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div id="modalBodyId" class="MessageModal">
            <!--set the background image here-->
               <ul class="MyFavorites">
                    <li class="Picture">
                        <a id="urlFavoriteImage1" href="">
                            <img border="0" id="favoriteImageSrcId" src=""/>
                        </a>
                    </li>
                    <li class="Title">
                        <strong>
                        <a id="dataText1" href="#"></a>
                        </strong>
                        <strong id="dataText1"></strong>
                    </li>
                    <li class="Title">
                        <strong id="title1Id"></strong>
                         <a id="dataText2" href="#"></a>
                        <br/>
                        <strong id="title2Id"></strong>
                        <a id="dataText3" href="#"></a>
                        <br/>
                        <strong id="title3Id"></strong>
                        <a id="dataText4" href="#"></a>
                        
                        <br/>
                    </li>
                    <li id="checkBoxUpdates"><label><input id="updatesCheck" value="1" type="checkbox" checked />I would like to receive e-mail updates for<strong id="title5Id"></strong></label></li>
                 </ul>
            </div>
            <div id="modalBodyResponseId" class="MessageModal closeDiv">
            </div>
            <ul class="ButtonWrapper">
                <li><input id="acceptFavoriteModalButtonId" type="button" class="submit" value="Accept"/>
                <input id="cancelFavoriteModalButtonId" type="button" class="submit jqmClose" value="Cancel"/></li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->

 <div id="messageFormModalId" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalFormTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div id="modalFormBodyId" class="MessageModal">
                set the background image here
                <div id="messageerrorId" class="ErrorMessageIndividual">You must enter something.</div>
                <ul>
                    <li>
                        <strong id="formModalTextId"></strong>
                    </li>
                    <li>
                        <textarea rows="2" cols="30" id="textGoalShoutId"></textarea>
                    </li>
				</ul>
            </div>
            <div id="modalFormBodyResponseId" class="MessageModal closeDiv"></div>
            <ul class="ButtonWrapper">
                <li>
                  <input id="acceptFormModalButtonId" type="button" class="submit" value="Accept"/>
                  <input id="cancelFormModalButtonId" type="button" class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->
 
<div id="reportAbuseModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="reportAbuseTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div id="reportAbuseBodyId" class="MessageModal">
                <!--set the background image here-->
                <ul>
                    <li>
                        <strong id="reportAbuseTextId"></strong>
                    </li>
                    <li>
                        Reason:<select id="reportTypeId" class="slct" name="Favorites1select">
		                            <option value="0">-Select Reason-</option>
		                            <option value="Obscenity/vulgarity">Obscenity/vulgarity</option>
		                            <option value="Hate speech">Hate speech</option>
		                            <option value="Personal attack">Personal attack</option>
		                            <option selected="selected" value="Advertising/Spam">Advertising/Spam</option>
		                            <option value="Copyright/Plagiarism">Copyright/Plagiarism</option>
		                            <option value="Other">Other</option>
                        	 </select>
                    </li>
                    <li>
                        <textarea rows=2 cols=30 disabled="disabled" id="textReportAbuseId"></textarea>
                    </li>

                </ul>
            </div>
            <div id="reportAbuseBodyResponseId" class="MessageModal closeDiv"></div>
            <ul class="ButtonWrapper">
                <li>
                  <input id="acceptReportAbuseButtonId" disabled="disabled" type="button" class="submit" value="Report"/>
                  <input id="cancelReportAbuseButtonId" type="button" class="submit jqmClose"/>
                </li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->


<div id="editGoalShoutModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4>Edit Goalshout?</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
                <!--set the background image here-->
                <div id="commentediterrorId" class="ErrorMessageIndividual">You must enter a goooaltalk.</div>
                <ul>
                    <li>
                        <strong>Do you want to edit your Goalshout?</strong>
                    </li>
                    <li>
                        <textarea rows=2 cols=30 id="textgoalshoutEdit"></textarea>
                    </li>

                </ul>
            </div>
            <ul class="ButtonWrapper">
                <li>
                  <input id="acceptEditGoalShoutButtonId" type="button" class="submit" value="Save"/>
                  <input id="cancelEditGoalShoutButtonId" type="button" class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->

<div id="sendEmailModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="sendEmailTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <form id="sendEmailModalForm">
            <div id="sendEmailBodyId" class="MessageModal">
                <!--set the background image here-->
                
                <ul>
                    <li id="emailFromId" class="closeDiv">
                        <div id="sendEmailfromerror" class="ErrorMessageIndividual">You must enter an Email Address</div>
                        <strong>From: </strong>
                        <input style="font-size:11px" type="text" id="sendEmailfrom" autocomplete="off"  name="sendEmailfrom" value="" size="50">
                    </li>
                    <li>
                        <div id="sendEmailtoerror" class="ErrorMessageIndividual">You must enter an Email Address</div>
                        <strong>To: </strong>
                        <span id="labeltoId" class="closeDiv"></span>
                        <input style="font-size:11px" type="text" id="sendEmailto"  autocomplete="off"  name="sendEmailto" value="" size="50">
                    </li>
                    <li>
                    	<div id="sendEmailsubjecterror" class="ErrorMessageIndividual">You must enter a Subject</div>
                        <strong>Subject:</strong> 
                        <input style="font-size:11px" type="text" id="sendEmailsubject" required="nn" name="sendEmailsubject" value="Please take a look at my friend's profile" size="42">
                    </li>
                    <li> 
                    	<div id="sendEmailtextForwardFrienderror" class="ErrorMessageIndividual">You must enter a Body</div>
                    	<strong>Body:</strong> 
                        <textarea rows=2 cols=35 id="sendEmailtextForwardFriend" name="sendEmailtextForwardFriend" required="nn">
                        </textarea>
                    </li>

                </ul>
            </div>
            <div id="sendEmailBodyResponseId" class="MessageModal closeDiv"></div>
            <ul class="ButtonWrapper">
                <li>
                  <input id="acceptSendEmailButtonId" type="button" class="submit" value="Send"/>
                  <input id="cancelSendEmailButtonId" type="button" class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
            </form>
        </div>
    </div>
 </div> <!--end wrapper-->


 <!--end wrapper-->

<div id="homepageFirefox" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4>Make this my homepage</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">
            <!--set the background image here-->
                <ul>
                    <li>How to make GoalFace your homepage</li>
                </ul>
                <ul>
                    <li>1.- <b>Select</b> Options in the <b>Tool Menu</b></li>
                </ul>
                <ul>
                    <li>2.-Click on the <b>Use Current Pages</b> button</li>
                </ul>
                <ul>
                    <li>3.-Click on the <b>OK</b> button. GoalFace.com is now your Home Page.</li>
                </ul>
            </div>
            <ul class="ButtonWrapper">
               <li><input type="button" id="cancelModalButtonId"  class="submit jqmClose" value="Cancel"/></li>
            </ul>
        </div>
    </div>
 </div>		

 <div id="inviteFriendsModal" class="jqmGeneralWindowLong">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4>Invite Friends</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal">

                <form name="invite_form" id="InviteFriends" method="post" action="#">
                    	<input type="hidden" id= "type" name= "type" value="">
                    	<div id="FieldsetWrapper">
                    		<h5 style="margin-top: 5px; margin-bottom: 5px;">Invite Friends to Join Goalface</h5>
                                <div align="center" id="systemWorking" style="display:none">
                                    <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>
                                </div>

                    		<div align="center" id="frm-error-text1" class="frm-error-text1" ></div>

                    		<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">Ooops, there was a problem!</div>
	             			</div>
							
							<fieldset class="LabelTop" id="inviteFriendsFieldset">

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
                                    <!-- <li><a href="#" onclick="showPlaxoABChooser('recipient_list', '<?php echo Zend_Registry::get("contextPath"); ?>/user/importaddress'); return false">Add Email (Yahoo, Hotmail, Gmail,other)</a></li> -->
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
                                <br>
                                <div id="messageerror" class="ErrorMessageIndividual">Please enter something in the Message: text field before clicking the send message</div>
								<label for="FriendMessage">
									Message: (max 300 characters, no HTML)
								</label>
				                   <textarea id="FriendMessage" name="message">I just joined a new website for football fans called GoalFace. It has football match results, player and team profiles, pictures and tons of news and information from leagues and tournaments all over the world. GoalFace is 100% football and a fun and easy way to follow the game. </textarea>
								<p></p>
								<p></p>
								<input type="button" id="mailButtonSend" class="submit GreenGradient" name="Register" value="Send Message"/>
                           </fieldset>

							<!-- div class="SecondColumnOfTwo" id="RightInfoOnForm">
								<h4>Why Invite Friends?</h4>
									<strong>When someone you invite joins GoalFace by clicking on your invite link:</strong>
									<ul>
									   <li>You earn points </li>
									   <li>They'll be automatically connected to you</li>
									   <li>You'll be automatically connected to their friends</li>
									    <li>Your popularity ranking goes up so more  people can find you.</li>
									    <li>The more the merrier!</li>
									</ul>

							</div> -->
							<br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
          </form>
            </div>
            <ul class="ButtonWrapper">
              <li><input type="button" id="cancelModalButtonId"  class="submit jqmClose" value="Cancel" /></li>
            </ul>
        </div>
    </div>
 </div>

<!-- /header -->
    <div id="header">
        <div class="hleft"></div>
        <!-- hmid -->
        <div class="hmid">            
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/">
              <p class="logo"></p>
            </a>
            
            <div class="right">
                <div class="list">
                    <ul>
                        
                        <?php if($session->email != null){ ?>                          
                            <li style="margin-left: 0px;"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter" title="GoalFace Message Center">Messages</a></li>
                            <li style="margin-left: 0px;">&nbsp;<?php echo ($session->newMessages > 0? '<span class="redmessage">New!</span>':''); ?></li>
                            <li>
                              
                               <a href="<?php echo Zend_Registry::get("contextPath"); ?>/sign-out">Sign Out</a>
                            
                            	<!-- FB connect button BUG 07-30-2012 -->
                            	<!--<a href="<?php 
								//$result = $session->fbuser['email'];
								//$logout = $session->logoutUrl;
								//if($result==null){
								//	echo Zend_Registry::get("contextPath") . "/sign-out";
								//}else{
								//	echo $logout;
								//}
								?>">Sign Out</a>-->
								
							</li>
                            <li>|</li>
                            <li><a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"settings");?>" title="GoalFace Account & Settings">Account &amp; Settings</a></li>
                            <li>|</li>
                        <?php } else { ?>
                           <div class="homecallout" style="font-weight:bold;color:#296183;position:absolute;top:10px;right:220px;">Fun, free and 100% football. Follow your favorite teams and players. <a style="color:#F95200;" href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">Sign up now.</a></div>
                            
                            <!-- FB connect button BUG  07-30-2012-->
                            <!--<li>
                            	<div id="fb-root"></div>						
								<fb:login-button size="small" scope="<?php //echo cFacebook::getPermission();?>">Connect</fb:login-button>
                            </li>-->
                            
                            
                            
                            <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/sign-in" title="GoalFace Sign In">Sign In</a></li>
                        <?php } ?>

                            <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/options/help" title="GoalFace Help">Help/FAQ</a></li>
                     
                            <!-- <li><a href="<?php //echo $urlGen->getFeedbackPageUrl(true); ?>" title="GoalFace Feedback">Feedback</a></li> -->
                    </ul>
                    
                    
                    
                    <form method="get" action="<?php echo $urlGen->getSearchMainUrl(true); ?>">
                        <span class="pro">
                            <input id="search-query" type="text" value="Search GoalFace..." name="q" />
                        </span>
                        <span class="pro1">
                            <input id="searchButtonId" type="button" disabled value="Search" name=""/>
                        </span>
                     </form>
                </div>

                
            </div>
        </div>
        <!-- /hmid -->
        <div class="hright"></div>
    </div>
    		
<!-- /header -->
			
		<!-- menu -->		
		<div id="menu">
			<!-- menuu -->
			<div class="menuu">
				<div class="mleft"></div>
				<div class="mmid">
					<div id="chromemenu" class="chromestyle">
					   <ul>
							<li id="nav_home" class="<?php echo ($name_array[1]=='')?'menuactive':''; ?>">
								<a href="<?php echo Zend_Registry::get("contextPath"); ?>/">Home</a>
							</li>
							<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>
							<li id="nav_scores" class="<?php echo ($name_array[1]=='live-scores-match-schedules')?'menuactive':''; ?>">
								<a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true);?>" rel="dropmenu2">Scores &amp; Schedules</a>
							</li>
							<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>
							<li id="nav_rss" class="<?php echo ($name_array[1] == 'subscribe') ? 'menuactive' : '';?>">
								<a href="<?php echo Zend_Registry::get("contextPath");?>/subscribe">Alerts &amp; Updates</a>
							</li>
							<li><img src="<?php echo Zend_Registry::get("contextPath");?>/public/images/menuline.jpg" alt="" /></li>				
							<li id="nav_teams" class="<?php echo ($name_array[1]=='teams')?'menuactive':''; ?>">
								<a href="<?php echo $urlGen->getClubsMainUrl(true); ?>" rel="dropmenu4">Teams</a>
							</li>
							<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>
							<li id="nav_players" class="<?php echo ($name_array[1]=='players')?'menuactive':''; ?>">
								<a href="<?php echo $urlGen->getPlayersMainUrl(true); ?>" rel="dropmenu5">Players</a></li>
							<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>
							<li id="nav_leagues" class="<?php echo ($name_array[1]=='tournaments')?'menuactive':''; ?>">
								<a href="<?php echo $urlGen->getMainLeaguesAndCompetitionsUrl(true); ?>" rel="dropmenu6">Leagues &amp; Tournments</a>
							</li>
								<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>
							<li id="nav_fans" class="<?php echo ($name_array[1]=='profiles')?'menuactive':''; ?>">
								<a href="<?php echo $urlGen->getMainProfilesPage(true); ?>" rel="dropmenu7">Fan Profiles</a>
							</li>
							<li><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/menuline.jpg" alt=""/></li>

						</ul>
					</div>
				</div>
				<div class="mright"></div>
			</div>  
			<!-- /menuu -->

			<!-- make -->
				
			<?php if ($this->is404Page != 'true') { ?>	
			<div class="make">
				<ul>
                    <?php
                       require_once 'browserdetection.php';
                       $a_browser_data = browser_detection('full');
                       if ( $a_browser_data[0] !== 'ie' ) {                      
                     ?>
                        <li><a class="trigger" id="makeHomePage" href="#">Make This My Homepage</a></li>

                    <?php } else { // if it is msie, that is
                            if ( $a_browser_data[1] >= 5 ) { ?>
                        <li>
                            <a href="" onClick="this.style.behavior='url(#default#homepage)';
                                this.setHomePage('http://<?php echo $config->path->index->server->name; ?>');">
                                Make This My Homepage
                            </a>
                        </li>
                    <?php }
                        }
                    ?>

                     <!-- Added after john created account community@goalface.com on Sharethis 
                          var switchTo5x=true;
                    
                      <!-- Added after john created account community@goalface.com on Sharethis -->
                      <script type="text/javascript">
                          var __st_loadLate=true; //if __st_loadLate is defined then the widget will not load on domcontent ready
                      </script>
                      <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                      <script type="text/javascript">stLight.options({publisher: "9194a39a-fa1d-47d6-90c6-aa1e43d4013e", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

                    <!--<li>
         				        <span class='st_sharethis' displayText='ShareThis'></span>
                    </li> -->
                    <li><a id="invitefriendsTrigger" href="#" title="Invite Friends">Invite Friends</a></li>
                    <li><a id="emailThisPageTrigger" href="#" title="Email This Page">Email This Page</a></li>


		</ul>
                    <p class="lan" id="chromemenu1">
                    <a href="#" rel="dropmenu8">Language Options</a>
                   
                    </p>
                     <!-- <p id="chromemenu2"><a href="#" rel="dropdownshare">Share</a></p>-->
			</div>
			<?php } ?>
            <!-- /breadcrumbs-->
            <?php require_once 'Common.php';
             $common = new Common();
             if ($this->breadcrumbs != null){
             ?>
             <div class="full">
				        <ul>
                
                <?php echo $common->getBreadCrumbs($this->breadcrumbs->getTrail());?>
                            
             	</ul>
			</div>
            <?php }?>                   
			<!-- /make -->	
		</div>


<!-- /menu dropdowns -->

<!-- /menu dropdowns - Scores and Schedules-->
<div id="dropmenu2" class="dropmenudiv">
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores-match-schedules/">Top Matches</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/africa" title="Africa Football Live Scores & Match Schedules">Africa</a>
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/americas" title="Americas Football Live Scores & Match Schedules">Americas</a>
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/asia" title="Asia Football Live Scores & Match Schedules">Asia &amp; Pacific Islands</a>
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/europe" title="Europe Football Live Scores & Match Schedules">Europe</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/international" title="International Football Live Scores & Match Schedules">FIFA</a>         
</div>

<!-- /menu dropdowns - Pictures-->
<!--<div id="dropmenu2" class="dropmenudiv">
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos/?search=newest%23nw">Newest</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos/?search=mostrated%23hr">Highest Rated</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos/?search=mostviewed%23mv">Most Viewed</a> 
</div>-->

<!-- /menu dropdowns - Teams-->
<div id="dropmenu4" class="dropmenudiv">
		<a href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">Featured</a>
		<a href="<?php echo $urlGen->getPopularTeamsUrl(TRUE);?>">Popular</a>
		<a href="<?php echo $urlGen->getClubsMainUrl(TRUE);?>">Team Directory</a>
		<!--<a href="#" id="select" >Head-to-Head</a> -->	
</div>

<!-- /menu dropdowns - Players-->
<div id="dropmenu5" class="dropmenudiv">
		<a href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">Featured</a>
		<a href="<?php echo $urlGen->getPopularPlayersUrl(TRUE);?>">Popular</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayersbyalphabet/letter/A">Player Directory</a>
    	<!-- <a href="#" id="select1">Head-to-Head</a>	-->
</div>

<!-- /menu dropdowns - Leagues and Tournaments-->
<div id="dropmenu6" class="dropmenudiv">
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/african/">Africa</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/americas/">Americas</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/asian/">Asia &amp; Pacific Islands</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/european/">Europe</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/international/">FIFA</a>
</div>

<!-- /menu dropdowns - Fan Profiles-->
<div id="dropmenu7" class="dropmenudiv">
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/?search=popular%23mp">Most Popular</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/?search=active%23ma">Most Active</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/?search=recently%23ru">Recently Updated</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/?search=newest%23nw">Newest</a>
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/?search=online%23onl">Online Now</a>
</div>

<!-- /menu dropdowns - Languages-->
 <div id="dropmenu8" class="dropmenudiv1">
		<a href="#">English</a>
		
</div>

<!-- /menu dropdowns - Share-->
<div id="dropdownshare" class="dropmenudiv1">
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos">Most Popular</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos">Newest</a>
		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos">Favorites</a>
</div>

<script type="text/javascript">
	cssdropdown.startchrome("chromemenu");
	cssdropdown.startchrome("chromemenu1");
</script>

<script> 
	window.fbAsyncInit = function() {

	    FB.init({
	        appId: '<?php echo cFacebook::getAppId();  ?>',
	        cookie: true,
	        xfbml: true,
	        oauth: true
	    });
	    FB.Event.subscribe('auth.login',
	    function(response) {
	        window.location.reload();
	    });
	    FB.Event.subscribe('auth.logout',
	    function(response) {
	        window.location.reload();
	    });
	}; 
		(function() {
		    var e = document.createElement('script');
		    e.async = true;
		    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		    document.getElementById('fb-root').appendChild(e);
	} ()); 
</script>
