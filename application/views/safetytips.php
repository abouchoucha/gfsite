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
                    <h1>Safety Tips</h1>
                   
                    <div id="SecondColumnHighlightBoxContent">
                    <p>
                          Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Ut hendrerit dolor eu est. Aenean lacinia nibh vitae augue. Pellentesque diam. Aenean commodo nibh eget neque. Nullam imperdiet leo sit amet turpis. Quisque velit nulla, sodales eu, iaculis at, condimentum ac, nulla. Duis adipiscing aliquam velit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque eget arcu eget ipsum venenatis iaculis. Quisque adipiscing aliquam lectus. In aliquam neque vel ipsum.
                          </p>
                          <p>
                          Nullam mattis luctus nibh. Cras ut enim eget nulla pretium rutrum. Proin est nisi, mattis a, congue rutrum, hendrerit sed, neque. Sed eleifend mauris quis massa. Pellentesque hendrerit lacinia arcu. Maecenas faucibus venenatis lorem. Donec luctus. Ut adipiscing est a eros. Nulla dolor. Suspendisse eget nisl ac arcu bibendum sodales. Phasellus bibendum commodo enim. Pellentesque vulputate justo ut magna. Fusce accumsan. Aliquam erat volutpat.
                          </p>
                          <p>
                          Nam non lorem eu nulla viverra convallis. Etiam nec lectus sed urna pretium tristique. Cras sapien. Nullam non quam. Donec ut eros. Curabitur porta. Suspendisse eros lacus, lacinia at, consectetuer sed, tincidunt nec, augue. Nullam egestas aliquet tortor. Mauris viverra, lacus quis bibendum gravida, orci eros lacinia purus, eu porta dui libero vitae magna. Pellentesque fermentum. Nulla et ligula sed felis blandit laoreet. Fusce dui. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ligula nulla, fringilla et, placerat hendrerit, ullamcorper vitae, nisi. Aliquam sed ante. Sed imperdiet condimentum augue. In at erat vitae enim congue porta. Morbi ultricies ligula mattis sem.
                          </p>
                          <p>
                          Aenean dolor. Ut bibendum libero volutpat nisl. Sed elementum felis a urna. Vestibulum venenatis turpis non nunc. Nam rutrum vestibulum eros. Donec nec orci. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum in sem. Suspendisse ac enim. Curabitur ac sapien eu eros pretium eleifend. In velit arcu, placerat sit amet, cursus in, faucibus ut, neque. Proin tempus. Pellentesque pharetra.
                          </p>
                         
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->