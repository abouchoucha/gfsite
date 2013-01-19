   <?php
    require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
  ?>
  <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

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

                    <!--feedback-->
               

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
                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/registration" title="GoalFace Registration">
                           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                        </a>
                        </div>
                    </div>
                    <?php } ?>
              </div><!--end FirstColumnOfThree-->

                <div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1>About GoalFace</h1>
                   
                    <div id="SecondColumnHighlightBoxContentFooter">
                        <h3><strong>GoalFace is Football Everywhere!</strong></h3>
                        
                        <p>GoalFace brings professional football from around the world to fans in an engaging and
	easy-to-use format—anytime, everywhere, on demand. Using the latest web 2.0, social
	media and syndication technologies, GoalFace delivers team schedule notifications, live
	scores, real-time match and player alerts, and much more from professional leagues and
	tournaments across Europe, the Americas, Africa and Asia to football fans everywhere.</p>
						
						<p>GoalFace.com is the premier online experience for the global football community to
convene, connect and celebrate their passion for the game in a place that is fun and 100%
football.</p>
						

                        
                        <p><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration"><strong>Join now! Get your best GoalFace on!</strong></a></p>

                        <p>GoalFace members can personalize the site experience, updates and alerts to focus
						coverage on the players, teams, leagues and tournaments they care about most, with the
						rest of the world of football just a few clicks away.</p>

           <div class="goal_face_features_wrapper">
                       	
				
														
							<div class="goal_face_features updates_box">							
							<span class="red_text">Updates &amp; Alerts:</span> With GoalFace you can subscribe to receive player, team and
							game updates via email, mobile, social media and other Internet channels when, where
							and how you want them. <a href="http://www.goalface.com/subscribe">Find your favorites and subscribe today!</a>
							</div>
							
							<div class="goal_face_features player_box">
							<span class="red_text">Players:</span> Match updates, goals, statistic, news and more for 100,000+ professional
							football players. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/players">Find your favorites in our players directory</a>.
							</div>

							<div class="goal_face_features leagues_box">
							<span class="red_text">Leagues:</span> Live scores, schedules and standings from the professional football leagues and
							tournaments you care about most. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments">Browse our leagues and tournaments directory to find your favorites.</a>
							</div>

							<div class="goal_face_features teams_box">
							<span class="red_text">Teams:</span> News, current squad information, league and tournament updates, historical
							performance and more for 10,000+ professional teams around the world. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/teams">Find your favorites in our teams directory</a>.
							</div>
							
							<div class="goal_face_features dashboard_box">
							<span class="red_text">Dashboard:</span> GoalFace members receive a personalized dashboard to track their favorite football players, teams and leagues all in a
							single place. <a href="http://www.goalface.org/quicktour.php#2slideShow" target="_blank">Find out more and sign up and create your very own dashboard.</a>
							</div>

							<div class="goal_face_features fans_box">
							<span class="red_text">Fans:</span> Profiles, favorites, opinions and more from fans around the world who share the
								same passion for the game as you in a football-centric community 
								experience. <a href="http://www.goalface.org/quicktour.php#9slideShow" target="_blank">Learn more about the GoalFace community and fan profiles.</a>
							</div>

						</div>
                       	
                       	<h4><b>GoalFace is fun, easy-to-use and 100% football. What are you waiting for? <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Register and join the fun today</a>!</b></h4>
                       	
                       	<span><strong>GoalFace Company Web Site</strong></span><br>
						For the latest news about the company, the GoalFace team, career opportunities or to
						contact us, visit the company web site at <a href="http://www.goalface.org" target="_blank">www.goalface.org</a>.<br><br>
						
						<span><strong>GoalFace Social</strong></span><br>
						Follow us on all the social media channels below.<br>
						
						Facebook: <a href="http://www.facebook.com/goalface" target="_blank">www.facebook.com/goalface</a><br>
						Twitter: <a href="http://www.twitter.com/goalface" target="_blank">www.twitter.com/goalface</a>
						<br><br>
						<span><strong>GoalFace Blog</strong></span><br>
						For our musings on football and the latest updates about GoalFace, visit our blog at <a href="http://blog.goalface.com" target="_blank">blog.goalface.com</a>.<br>
                       	
                  </div>
               <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
           </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
      </div> <!--end ContentWrapper-->