<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php require_once 'Zrad/cFacebook.php'; ?>
<?php 
	$urlGen = new SeoUrlGen(); 
	include 'include/functions.php';
?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
 ?>

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
                    url: '<?php echo Zend_Registry::get("contextPath"); ?>/login/dologin',
                    success: function(data){

                    }	
                })

            }

</script>
<?php  $view = Zend_Registry::get ( 'view' );?>
<div id="middle">
	<div class="snleft">
		<h1>-Sign in to GoalFace-</h1>
		<span class="account">Don't have a GoalFace account yet?  What are you waiting for?! <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register"> It's Free. Sign Up Now</a></span>
		<div class="team">
			<span class="team1"><a href="http://www.goalface.com/players">Teams</a></span>
			<ul>
				<li><a href="http://www.goalface.com/teams/barcelona/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/2017.gif"></a></li>
				<li><a href="http://www.goalface.com/teams/guadalajara/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/1462.gif"></a></li>
				<li><a href="http://www.goalface.com/teams/boca-juniors/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/95.gif"></a></li>
				<li><a href="http://www.goalface.com/teams/al-ahly/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/3447.gif"></a></li>
				<li><a href="http://www.goalface.com/teams/cr-flamengo-rj/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/318.gif"></a></li>
				<li><a href="http://www.goalface.com/teams/ac-milan/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/teamlogos/1240.gif"></a></li>
			</ul>
		</div>
		<div class="team">
			<span class="team1"><a href="http://www.goalface.com/players">Players</a></span>
			<ul>
				<li><a href="http://www.goalface.com/players/lionel-messi_119/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/119.jpg"></a></li>
				<li><a href="http://www.goalface.com/players/cristiano-ronaldo_382/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/49.jpg"></a></li>
				<li><a href="http://www.goalface.com/players/wayne-rooney_193/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/34.jpg"></a></li>
				<li><a href="http://www.goalface.com/players/wesley-sneijder_34/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/1595.jpg"></a></li>
				<li><a href="http://www.goalface.com/players/zlatan-ibrahimovic_168/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/186.jpg"></a></li>
				<li><a href="http://www.goalface.com/players/carlos-t%C3%A9vez_122/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop;?>/players/382.jpg"></a></li>
			</ul>
		</div>
		
		
		
		<div class="team">
				<span class="team1"><a href="http://www.goalface.com/tournaments">Leagues &amp; Tournaments</a></span>
				<ul>
					<li><a href="http://www.goalface.com/tournaments/uefa-champions-league_10/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/10.gif"></a></li>
					<li><a href="http://www.goalface.com/tournaments/primera-division_7/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/7.gif"></a></li>
					<li><a href="http://www.goalface.com/tournaments/bundesliga_9/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/9.gif"></a></li>
					<li><a href="http://www.goalface.com/tournaments/serie-a_13/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/13.gif"></a></li>
					<li><a href="http://www.goalface.com/tournaments/copa-libertadores_241/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/241.gif"></a></li>
					<li><a href="http://www.goalface.com/tournaments/ligue-1_16/"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&amp;h=80&amp;zc=1&amp;src=<?php echo Zend_Registry::get("contextPath"); ?>/goalfaceapp/public/images/competitionlogos/16.gif"></a></li>
				</ul>
			</div>
		<div class="team">
			<span class="team1"><a href="">Fans</a></span>
			<ul>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img24.jpg' alt=""></a></li>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img23.jpg' alt=""></a></li>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img22.jpg' alt=""></a></li>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img21.jpg' alt=""></a></li>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img20.jpg' alt=""></a></li>
				<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/img19.jpg' alt=""></a></li>
			</ul>
		</div>
		<div class="once">
				<h2><strong>Once you are signed in you can:</strong></h2>
				<ul>
						<li><a href="#">Access exclusive, member-only content in a place that is 100% football</a></li>
						<li><a href="#">Customize the site to focus on leagues and tournaments you care about</a></li>
						<li><a href="#">Receive updates on your favorite teams and players</a></li>
						<li><a href="#">Create a profile, invite friends and connect with fans like you</a></li>
						<li><a href="#">And a whole lot more!</a></li>
				</ul>
				<h2 style="width:550px"><strong>Don't have an account? What are you waiting for? </strong><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">Sign up and join the fun today</a>!</h2>
		</div>

	</div>
	<div class="snright">
	    	<!-- Or Sign In with Facebook Connect --><!--  
			<div class="sign1 sign1Border">
				<div id="fb-root"></div>							
				<fb:login-button size="large" scope="<?php //echo cFacebook::getPermission()?>">Sign in using Facebook</fb:login-button>
			 </div>

	    --><div id="ErrorMessagesLogin" class="ErrorMessages" style="width:228px;margin-bottom:10px;margin-left:0;">
            <div id="MainErrorMessageLogin">Error</div>
         </div>	  
	
	 	<form id="loginForm" name="loginForm" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/login/dologin">
	 	
            <input type="hidden" name="referrerForm" id="referrerForm" value="<?php echo $this->referrer != '' ? $this->referrer :Zend_Registry::get("contextPath")?>">
				<div class="into">
						<div class="intop"></div>
						<div class="inmid">
							<h2>Sign In to GoalFace</h2>
							<span class="name">
								<label>Email:</label>
									<span class="textbox1">									
										<input type="text" class="textbox" id="emailaddressForm" name="emailaddressForm" value="<?php echo $this->email;?>" size="35" tabindex="1" required="email"/>										
									</span>
							</span>
							<span id="emailaddressFormerror" class="ErrorMessageIndividual" style="padding-left: 85px;">You must enter your Email Address</span>
							<span class="name">
								<label>Password:</label>
									<span class="textbox1">										
										<input type="password" class="textbox" id="passwordForm" name="passwordForm" value="<?php echo $this->password;?>"  size="15" tabindex="2" required="nn"/>									
									</span>
							</span>
							<span id="passwordFormerror" class="ErrorMessageIndividual" style="padding-left: 85px;">You must enter your password</span>
							
							<span class="name">
								<label>
                        			<input type="checkbox" class="checkbox" checked="" name="persistent" value="1" id="rememberIdForm" tabindex="3"/>
                        		</label>
                        		<span>
                        			&nbsp;Remember me
                    			</span>	
                    		</span>							
							<span class="butn"><input type="button" id="submitButtonLoginFormId" class="submit" value="Sign In" tabindex="4"/></span>														
							
							 
						</div>
						<div class="inbtm"></div>
				</div>	
		</form> 	
				<div class="forgot">
						<span class=""><a href="<?php echo Zend_Registry::get("contextPath"); ?>/login/retrievepassword">Forgot your password?</a><!-- &nbsp;| &nbsp;<a href="#">Password Help</a>--></span>
				</div>
				<div class="sign1">
					Don't have an account? <br> <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Click here to register</a>&nbsp;for GoalFace.
			    </div>
				

				
		</div>
	
</div>	

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
		    e.src
		    = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		    document.getElementById('fb-root').appendChild(e);
	} ()); 
</script>
