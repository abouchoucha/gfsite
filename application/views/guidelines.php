<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
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
                    <h1>Rules of the Game (A.K.A the Community Guidelines)</h1>

                    <div id="SecondColumnHighlightBoxContent">
                        <p>Are you a lawyer? None of us here at GoalFace are; that's why we had this handy guide to interpret the ever 
                        long <a href="<?php echo Zend_Registry::get("contextPath"); ?>/terms">Terms of Service</a>. Enjoy.</p>

                        <h3>Be nice.</h3>

                        <p>Among the many purposes of GoalFace, one of them is to create a pleasant environment for football fans all 
                        around the world to discuss, rave and celebrate their favorite teams and players. This is going to be really hard 
                        to do if everyone is a blockhead. There might not be an "i" in "team", but there is in "nice."</p>

                        <h3>Don't get booked.</h3>

                        <p>Don't you hate it when the opposition knowingly cheats to draw a foul? It bums us out too. So play fair on the 
                        site. This means no spamming, cloning a username, smear campaigns or any other type of tomfoolery. Understood? It 
                        would be a shame to get a red card so early on in your GoalFace career...</p>
                        

                        <h3>Clean sheets are best.</h3>

                        <p>Inappropriate images or video, links to non-related Web sites (yes, this means YOU Viagra marketers), advertising 
                        for a business, hate speech, personal attacks and
                        harassment are all unacceptable.</p>

                        <p>When posting your own content, we'd prefer you keep things "safe for primetime," meaning, if you wouldn't see 
                        or hear it on network television between the hours
                        of 8:00 PM to 9:00 PM, you may not want to post it here. Albeit, we don't want to squash your creative side, so 
                        if you are going to use strong language, just
                        don't direct it at another member, cool?</p>

                        <h3>Keep it legal.</h3>

                        <p>If it's against United States law or the laws of your own country, don't do it here. Likewise, if it's against 
                        the Terms of Service ... you guessed it.</p>

                        <h3>No "flopping" is allowed.</h3>

                        <p>Making fun of others for their views on an issue or instigating bad behavior are all ways to have your computer 
                        confiscated, your body tarred/feathered and
                        your account noted (OK, we won't confiscate your computer or tar and feather you, but we might write you a 
                        nasty-gram telling you to act your age and not
                        your shoe size).</p>

                        <h3>No hooligans.</h3>
                        <p>Stalking others on the service, threatening their safety or impersonating them or someone else are all sure 
                        ways to get your account booked and law 
                        enforcement involved.</p>

                        <p>Don't be that guy.</p>

                        <h3>Moderate your content</h3>

                        <p>It is each Fan's responsibility to moderate the comments, photos, video, etc., which are through out the site. We have created tools so that you can report 
                        bad content. If you need help (or just a high five), fire off an e-mail to <a href="mailto:community@goalface.com">community@goalface.com</a>.</p>


                        <p>Also you may want to view the useful tips in our <a href="<?php echo Zend_Registry::get("contextPath"); ?>/help">FAQ section</a>, which explains how to do some of the most common things on the service.</p>

                        <h3>Enjoy it.</h3>

                        <p>If you aren't having a blast while on the site, something is seriously wrong. We'd love to hear your thoughts about the site and what we can improve. 
                        Send your ideas, gripes, concerns, etc. to <a href="mailto:community@goalface.com">community@goalface.com</a>. </p>


                        <p><em>Last updated June 21, 2009.</em></p>


                         
            </div>
            <div id="SecondColumnHighlightBoxContentBottomImage"></div>
        </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->



     </div> <!--end ContentWrapper-->
