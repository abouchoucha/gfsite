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
                    <h1>Trademark</h1>

                    <div id="SecondColumnHighlightBoxContent">


                        <p>GoalFace is a registered trademark ® of GoalFace, Inc.  GoalFace.com, its respective logos, and
                        taglines Football Everywhere!, For Fans of the Beautiful Game and Celebrating the Magic and Passion of the Beautiful Game
                        are trademarks of GoalFace, Inc. in the United States of America and/or other countries.  GoalFace's
                        trademarks and other marks may not be used, including as part of trademarks and/or as part of domain
                        names, in connection with any product or service in any manner that is likely to cause confusion and
                        may not be copied, imitated, or used, in whole or in part, without the prior written permission of
                        the GoalFace, Inc.</p>


                        <p>All other trademarks or other marks, logos, names, and titles mentioned herein belong to, and are
                        'the property of their respective owners.  The use of the word partner does not imply a partnership
                        relationship between GoalFace or GoalFace, Inc and any other party. GoalFace is not affiliated with
                        or endorsed by any professional player, team, league, association, federation, consumer brand or service
                        mentioned or available through the Web site in any way</p>

                        <p><em>Last updated Sep 06, 2009.</em></p>
                  </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->
