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
                    <h1>Disclaimer</h1>
 
                    <div id="SecondColumnHighlightBoxContent">
                       

	
                        <p>The materials on this Web site  are provided for informational purposes only. If you are unwilling to abide by the
                        <a href="<?php echo $urlGen->getTermsPageUrl(true); ?>">terms and conditions</a>, please discontinue the use of this web site immediately. By using the information on GoalFace.com
                        you agree to abide by these terms and conditions.  </p>

                        <h3>Disclaimer of Accuracy and Currency </h3>

                        <p>GoalFace Inc. ("GoalFace"), nor its owners, agents, employees or any other affiliated persons or entities,
                        make no guarantee of accuracy of any of the items contained herein. In providing this Web site, GoalFace 
                        incurs no obligation to provide support, maintenance or updates. While every effort will be made to keep 
                        the materials accurate and up-to-date, information changes rapidly and it is not possible to guarantee 
                        that all items will be accurate at all times. If there is any doubt as to the accuracy of any claim or 
                        information on this site, the reader is responsible for verifying same against an alternative source.</p>

                        <p><a href="<?php echo $urlGen->getFeedbackPageUrl(true); ?>">Click here</a> to make a suggestion or report a bug.</p>

                        <h3>Disclaimer of Warranty</h3>
                        

                        <p>All of the information, instructions, and recommendations on this Web site are offered on a strictly "as is"
                        basis. This material is offered as a free public resource, without any warranty, expressed or implied. In particular,
                        any and all warranties of fitness for use or merchantability are disclaimed. Neither GoalFace, nor its owners,
                        agents, employees or any other affiliated persons or entities, shall be held responsible for any direct, indirect,
                        incidental or consequential damages, that may result from anything that is viewed on this web site, or anything
                        you do as a result thereof. It is up to the reader to determine the suitability of any directions or information
                        viewed here</p>

                        <h3>Disclaimer of Relationship</h3>
                        

                        <p>The use of the word partner does not imply a partnership relationship between GoalFace or GoalFace, Inc and
                        any other party. GoalFace is not affiliated with or endorsed by any professional player, team, league,
                        association, federation, consumer brand or service mentioned or available through the Web site in any way.
                        All third-party trademarks or other marks, logos, names, and titles mentioned herein belong to, and are the
                        property of their respective owners.  </p>

                        <p><em>Last updated Sep 06, 2009.</em></p>
                  </div>
               <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
           </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
      </div> <!--end ContentWrapper-->
