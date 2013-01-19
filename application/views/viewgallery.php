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
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div id="Feedback">
                                <strong>Feedback</strong>
                                <br />

                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/feedback/">Tell us what you think</a> about Goalspace.  We'd
                                love to hear what you have to say.
                            </div>    
                        </div>
                    </div>
                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
                    
                    
                    <!--Goalface Register Ad-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">
                              <img class="JoinNowHome" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" /> 
                             </a>  
                        </div>
                    </div>
                    <?php } ?>
     

              </div><!--end FirstColumnOfThree-->

                <div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1>Photo Gallery</h1>                   
                   
                    <div id="SecondColumnHighlightBoxContent">
                          
                          <ul>
                            <li>
                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/feed/afp/photo_1226245665846-1-0.jpg" />
                            </li>
                          </ul>
                         
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->