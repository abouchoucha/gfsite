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
                    <h1>Advertise with Us</h1>
                   
                    <div id="SecondColumnHighlightBoxContentFooter">

                     <h3>The Global Football Marketplace. It’s a Whole New Game.</h3>
					 <p>GoalFace is looking for a limited number of marquee sponsors interested in engaging and conversing with a global football (soccer) fan base in a vibrant, fun and engaging online experience. <a href="contact.html">Contact us</a> for information about available sponsorship opportunities you can use to get you in the game and win.</p>


                   <h3>Extend Your Reach in the Global Football Marketplace</h3>

                    <p class="midtitle">GoalFace is a vertical social media platform <b>delivering best-of-breed community and football content experiences</b> via its Web site, social media
                    applications and other channels to fans around the world—<b>anytime, everywhere, on demand.</b></p>

                    <p class="list">
                    <!--<img src="images/homepageSmall.jpg" alt="GoalFace" style="float:right;" />It’s every football marketer’s dream to find a vehicle
                        like GoalFace to reach the global football marketplace. GoalFace makes it possible for you to—</p>-->

						<ul>
							<li>Take advantage of the Internet’s worldwide reach, limitless capacity, flexibility, and one-to-one targeting power.</li>

							<li>Target fans anywhere, anytime who are already <em>engaged</em> with players and teams—from the international to the neighbourhood level. </li>
							<li>Bond your brand to consumer groups in a resilient, meaningful way.</li>
							<li>Cut through the limitations imposed by traditional broadcast and media markets.</li>
						</ul>
						<p></p>

						<h3>Make GoalFace a Key Driver in Your Marketing Plans</h3>

						<p class="list">With GoalFace, you can—</p>
						<ul>
							<li>Drive sales with increased product/brand awareness.</li>
							<li>Create strong loyalty and increase perceived value through an association with professional football at any marketplace level you choose.</li>
							<li>Utilize the affordable power of a seamless marketing and distribution platform that launches your campaign across multiple media, channels, and geographies.</li>
							<li>Track and measure response to your messaging and refine it in real-time as you need.</li>

						</ul>
						<p></p>
						<h3><b>To grab your share of the global football marketplace contact the GoalFace sales team at<br>
                        <a id="sales" href="#goalface.com" onclick="MakeLink()">sales at GoalFace dot com</a>.</b></h3>


                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->
