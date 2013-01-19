            <?php require_once 'Common.php'; 
            	$common = new Common();
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
                    <h1>Latest Football News</h1>                   
                    <div class="ShowNewsOption">Show: <select><option>English Only</option></select></div>
                    <ul id="SecondColumnHighlightBoxContentNav">
                        <li class="selected">Top News</li>
                        <li><a href="#">Teams</a></li>
                        <li><a href="#">Players</a></li>

                        <li><a href="#">Leagues</a></li>
                      <li class="last">
                        <a href="#">Customize Feeds</a><img src="/public/images/green_gradient_down_arrow.gif"/>                      
                      </li>

                    </ul>
                    <div id="SecondColumnHighlightBoxContent">
                        <?php 
                        	
                        	foreach ($this->newsFeeds as $item) {?>
                        <p>
                            <a class="NewsHeadline" target='_blank' href="<?php echo "news/articleid/".$item['news_id']; ?>"><?php echo $item['news_headline']; ?></a>
                            <br/><?php echo $common->convertDates($item['news_this_created'])?> - (<?php echo $item['news_provider']; ?>)

                            <br />
                            <?php echo $item['news_body_content']; ?></p>
                        <? }?>
                         
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->

