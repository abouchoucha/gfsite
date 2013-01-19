
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

<script language="javascript">

jQuery(document).ready(function() {
            jQuery("#SecondColumnHighlightBoxContent > div").slideDown('medium');

            jQuery("a[name^='faq-']").each(function() {
                jQuery(this).click(function() {
                    if( jQuery("#" + this.name).is(':hidden') ) {
                        jQuery("#" + this.name).slideDown('medium');
                    } else {
                        jQuery("#" + this.name).slideUp('medium');
                    }
                    return false;
                });
            });

            jQuery("#openAllFaq").click(function() {
                 jQuery("#SecondColumnHighlightBoxContent > div").slideDown('medium');
            });

            jQuery("#closeAllFaq").click(function() {
                jQuery("#SecondColumnHighlightBoxContent > div").slideUp('medium');
            });

        });

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
                    <h1>Help/FAQ</h1>                   
                   
                    <div id="SecondColumnHighlightBoxContent">
                      <h2>Top Questions</h2>

                       <p class="expandlinks">
                        <a id="openAllFaq">open all</a>
                        |
                        <a id="closeAllFaq">close all</a>
                       </p>


                       
                       <h3><a href="#" name="faq-1">What is GoalFace?</a></h3>

                       <div class="faq-answer" id="faq-1">GoalFace is the premier online community experience for fans of the Beautiful Game. The service provides limitless opportunities 
                        for fans to keep up with and learn about the players, teams and fans that make football the best game in the world.
                       <br /><br /></div>

                       <h3><a href="#" name="faq-2">How do I invite others to join GoalFace?</a></h3>
                       <div class="faq-answer" id="faq-2">That’s easy. Just click on the "Invite Friends" link at the top of any page of the site. From there you can enter your friends 
                       contact info. They’ll be added to your Friend list once they create an account.
                       <br /><br /></div>

                       <h3><a href="#" name="faq-3">Are there any rules I should be aware of?</a></h3>
                       <div class="faq-answer" id="faq-3">Just like the game of football, we have rules to keep hooligans at bay (pharmaceutical and mortgage spammers included!)
                        You are welcome to read the Terms of Service, but for an abridged version, which is easily understood, refer to the Community Guidelines. It’s basically a 
                        cheat sheet on how you should conduct yourself while on the site.
                       <br /><br /></div>

                       <h3><a href="#" name="faq-4">What are Goooal Shouts?</a></h3>
                       <div class="faq-answer" id="faq-4">Comments you want to leave on another Fan's page.
                       <br /><br /></div>

                       <h3><a href="#" name="faq-5">Can I control who sees my Profile?</a></h3>
                       <div class="faq-answer" id="faq-5">Yes. After logging into the site, you can click on the "Edit" link located just below your profile photo on the left side of any page.
                        Once on the Profile Information page, you’ll notice a dropdown box which permits you to select who can view bits of your personal information. 
                       <br /><br /></div>
                       
                       <h3><a href="#" name="faq-6">How do I share something I like with a friend who isn't a member of GoalFace?</a></h3>
                       <div class="faq-answer" id="faq-6">Use the "Share This" link. You can find the link at the top of any page on the site. We’ve tried to make sharing as simple as
                       possible, so we’ve included all of the links to share content with the most popular social media tools (e-mail too!).
                       <br /><br /></div>

                      <h3><a href="#" name="faq-7">How do I add Friends to my Profile?</a></h3>
                      <div class="faq-answer" id="faq-7">When you are on a profile page of a potential friend, click on the link below their profile photo, which says, "Add to Friends."  You will be prompted
                      to confirm your wish by the site (just click "OK").  A friend request will then be sent to your potential friend. If they approve the request, you'll be added to their Friend list as well.
                      <br /><br /></div>

                    </div>
                  <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
             </div> <!--end ContentWrapper-->
             


