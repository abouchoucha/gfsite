<script>
function rate( value ) {
	new Ajax.Updater( 'rateprofile', '<?php echo Zend_Registry::get("contextPath"); ?>/profile/rateProfile/v/'+value );
}
</script>


    <?php require_once 'Common.php'; ?> 
         <div id="ContentWrapper" class="TwoColumnLayout">
         <div class="FirstColumn">
             <?php 
              $session = new Zend_Session_Namespace('userSession');
              if($session->email != null){
          ?> 
          <!--Me box Non-authenticated and Left menu -->
				     <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>
                        </div>
             </div>
					   
          <?php }else { ?>
                 <!--Me box Non-authenticated -->
				 
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                    </div>
                </div>
                
           <?php } ?> <!-- End Me box- Non-authenticated Box--> 
             <div class="img-shadow">
                 <div class="WrapperForDropShadow">
                     <a href="/user/register">
                         <img class="JoinNowHome" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" />
                     </a>
                 </div>
             </div>
             <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/mls_gear_com_ad.jpg" />
             <div id="AdsByGoogle">
                 <u>Ads by Goooooogle</u>
                 <h6>
                     <strong>Soccer</strong> News</h6>
                 Always on the go? Get your news fix via the mobile web - Learn how.
                 <br />
                 <a href="#">mobile.google.com/news</a>
                 <h6>
                     <strong>Soccer</strong> In The Streets</h6>
                 Changing lives....
                 <br />
                 <a href="#">www.soccerstreets.org</a>
             </div>
         </div>
         <!--end FirstColumn-->
             <div class="SecondColumn BlogMain">             
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                            <h4><?php echo $this->screenName;?> blog</h4>
                            <a href="Add">Add to Favorites</a>
                            <a class="Subscribe" href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/generateFeed/id/<?php echo $this->blogId;?>/username/<?php echo $this->screenName;?>">Subscribe</a>
                        </div>
                        <div class="SecondColumnBlueBackground">
                            <p>
                            <strong>Description:</strong><br />
                            <?php echo $this->blog->description;?>
                            </p>
                            <p>
                             <strong>Tags:</strong><br />
                           <a href=""><?php echo $this->blog->tags;?></a>
                            </p>
                            <div class="BlogNav">
                                <ul>
                                    <li>1 - 10 of 22</li>
                                    <li class="last">
                                    
                                        <a class="MorePadding" href="">First</a>
                                        <a href="">&lt; Previous</a>
                                        |
                                        <a class="MorePadding" href="">Next &gt;</a>
                                        <a href="">Last</a>
                                    
                                    </li>
                                </ul>
                            </div>
                            <?php foreach($this->blogPosts as $bp) { ?>                          
                            <div>
                                
                                <div class="BlogNav BlogHeaderLinks">                                
                                    <ul>
                                        <li>Posted on   <?php echo date(' F j , Y' , strtotime($bp["postdate"])) ;?>&nbsp;at <?php echo date(' g:i a' , strtotime($bp["postdate"])) ;?>&nbsp;|&nbsp;Views:<?php echo $bp["num_views"];?></li>
                                        <li class="last">
                                        
                                            <a href="">del.icio.us</a>
                                            |
                                            <a href="">Read It</a>
                                            |
                                            <a href="">Digg it</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                
                                <h3><a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/showUserBlogPost/id/<?php echo $bp["blogpost_id"];?>"><?php echo $bp["postcaption"];?></a></h3>
                                <?php 
                                  	$common = new Common();
                                  	
                                  	echo $common->adv_count_words($bp["posttext"],100);?>&nbsp; <a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/showuserblogpost/id/<?php echo $bp["blogpost_id"];?>">More...</a>
                                <ul class="TabbedNav">
                                    <li class="Selected"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/showUserBlogPost/id/<?php echo $bp["blogpost_id"];?>#comments">Comments&nbsp;(<?php echo $bp["num_comments"];?>)</a> </li>
                                    <li><a href="#">Share</a></li>
                                    <li class="Rating">Rating</li>
                                </ul>
                                <br class="ClearBoth" />
                                
                            
                            
                        </div>
                         <? } ?>
                        
                    </div>
                </div>    
             </div><!--//SecondColumn-->
     </div><!--//ContentWrapper-->
           
