
<?php
/**
* Content for the 404 page
*
* @author Jorge Vasquez <jorge@goalface.com>
* @version 1.0
* @copyright GoalFace
*
*/
?>
<?php
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
    $session = new Zend_Session_Namespace('userSession');
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
?>

<script language="javascript">

var RecaptchaOptions = {
        theme : 'custom'
 };

   jQuery(document).ready(function() {

	jQuery("#bugtextareaId").charCounter(200);
	   
        jQuery('#bugsendbuttonId').click(function(){

            var bugText = jQuery('#bugtextareaId').val();
            if(jQuery.trim(bugText) == 'Enter a URL or text describing what you were looking for or the problem you encountered.'){
                jQuery('#alertErrorMessageId').removeClass('closeDiv');
                return;
            }

            jQuery(".inlineMessageWide .closemessage").click(function(){
        		jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
            });

          	var requestedpage =  '<?php echo $this->requestURL; ?>';

            jQuery.ajax({
    			type: 'POST',
    			//data: ({bugtext: bugText , type : 'error404' , requestedpage : requestedpage}),
    			data: jQuery("#reportbugid").serialize(),
    			url: '<?php echo Zend_Registry::get("contextPath"); ?>/options/reportbug',
    			dataType : 'script',
    			success: function(text){
	            	if(text!= ''){
	       		    	jQuery('#ErrorMessagesFeedBack').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	       			    jQuery('#ErrorMessagesFeedBack').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	       		    }else {
		       		     jQuery('#alertErrorMessageId').hide();
			       		 jQuery("#alertSuccessMessageId").show();
			       		 jQuery('#bugtextareaId').val('Enter a URL or text describing what you were looking for or the problem you encountered');
			       		 jQuery('#alertSuccessMessageId').removeClass('closeDiv');
	                 }
            	}	
    		})
        })	
   });

</script>



<!-- middle -->
<div id="middle">
<!-- 	/left -->
    <div class="page">
        <p class="pageerror"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/404page.jpg" alt=""/></p>
        <p class="page1">The page <strong><?php echo $this->requestURL; ?></strong>  you requested is no longer available or cannot be found. Help us correct the mistake by reporting
        it as a bug or double-check the URL (address) and try again it you misspelled something.</p>
        <div class="some">
                <p class="here">Here are some other useful pages</p>
                <div class="play">
                    <div class="players">
                        <ul>
                                <li><a href="/" title="Home Page">Home Page</a></li>
                                <li><a href="<?php echo $urlGen->getPlayersMainUrl(true); ?>" title="Players">Players</a></li>
                                <li><a href="<?php echo $urlGen->getClubsMainUrl(true); ?>" title="Teams">Teams</a></li>
                                <li><a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true);?>" title="Scores">Scores</a></li>
                                <li><a href="<?php echo $urlGen->getMainNewsPage(true); ?>" title="News">News</a></li>
                                <li><a href="<?php echo $urlGen->getMainLeaguesAndCompetitionsUrl(true); ?>"title="Leagues">Leagues &amp; Tournaments</a></li>
                        </ul>
                    </div>
                    
                    <div class="players1">
                        <ul>
                            <?php if($session->email == null){?>
                                <li><a href="#" onclick="loginModal();" title="My Account">My Account</a></li>
                                <li><a href="#" onclick="loginModal();" title="My Inbox">My Inbox</a></li>
                                <li><a href="#" onclick="loginModal();" title="My Profile">My Profile</a></li>
                            <?php }else { ?>
                                <li><a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"settings");?>" title="My Account">My Account</a></li>
                                <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter" title="My Inbox">My Inbox</a></li>
                                <li><a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>" title="My Profile">My Profile</a></li>
                            <?php } ?>
                                <li><a href="<?php echo $urlGen->getAboutPageUrl(true); ?>" title="About GoalFace">About GoalFace</a></li>
                                <li><a href="<?php echo $urlGen->getHelpFaqPageUrl(true); ?>" title="Frequently Asked Question">Frequently Asked Questions</a></li>
                                                      
                        </ul>
                    </div>
                </div>
        </div>
    </div>

        <!-- 	<div class="go">
                <p class="go1"><a href="">Go to the GoalFace Home Page</a></p>
        </div> -->

        <div class="need" style="margin-top: 30px;">
            <form method="get" action="<?php echo $urlGen->getSearchMainUrl(true); ?>">
                    
                    <p class="need1">Search for what you need:</p>
                    <span class="pro2">
                        <input id="search-query" type="text" value="Search GoalFace..." name="q" onfocus="javascript:if(this.value=='Search GoalFace...')this.value='';" onblur="javascript:if(this.value=='')this.value='Search GoalFace...';"/>
                    </span>
                    <span class="pro3">
                        <input id="searchButtonId" type="submit" value="Search" name=""/>
                    </span>
             </form>
                  
                  <p class="need1">Bug?</p> 
                   
                  <div id="alertSuccessMessageId" class="inlineMessageWide alertSucess closeDiv" style="width:346px;margin-left:10px;padding-left:28px;">
                      <p id="successMessageId">Your message was successfully sent.</p>
                      <span class="closemessage"></span>
                   </div>
                  
                  <div id="alertErrorMessageId" class="inlineMessageWide alertError closeDiv" style="width:346px;margin-left:10px;padding-left:28px;">
                      <p id="successMessageId">Please fill out the report a bug form.</p>
                      <span class="closemessage"></span>
                   </div>
                   <!-- <p id="ErrorMessagesBug" class="errorMessage">
                        Please enter repor bug message in form.
	                </p> -->
                               
                  <form id="reportbugid" method="post"  action="">
                      <input type="hidden" name="type" value="error404">
                      <div class="bug">
                        <p class="look">
                          <textarea id="bugtextareaId" cols="" rows="" class="tarea1" name="bugtextarea" onfocus="javascript:if(this.value=='Enter a URL or text describing what you were looking for or the problem you encountered.')this.value='';" onblur="javascript:if(this.value=='')this.value='Enter a URL or text describing what you were looking for or the problem you encountered.';">Enter a URL or text describing what you were looking for or the problem you encountered.</textarea>
                        </p>
                      </div>
                      <?php if($session->email == null){?>
                      
								<div id="recaptcha_container" class="error404" style="margin-left:12px;">

                                 <div id="recaptcha_response_fielderror" class="ErrorMessageIndividual">Enter the text that appears in the image below</div>
								  <label for="WordVerification">
									<em>* </em>
                                        Word Verification:
								  </label>
									Type the characters you see in the picture below.
									<p/>
								    
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
                      <span class="report1">
                          <input id="bugsendbuttonId" type="button" name="" value="Report">
                      </span>
                      
                    </form>
                   


            </div>
	</div>
 <!-- /middle -->
 <script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
