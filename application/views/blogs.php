
<script type="text/JavaScript">
  
     function createMyBlog(){
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/createMyBlog';
     var target = 'CreateBlogFormDiv';
     var pars = Form.serialize("CreateBlogForm");
     
     
     var myAjax = new Ajax.Updater(target, url, {
                                                method: 'post', 
                                                parameters: pars,
                                                evalScripts:true
                                                }
                                            );
  } 
     
</script>
          <?php require_once 'Common.php'; ?>
           
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
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/mls_gear_com_ad.jpg" />
                    <div id="AdsByGoogle">
                        <u>Ads by Goooooogle</u>
                        
                        <h6><strong>Soccer</strong> News</h6>
                        Always on the go? Get your news fix via the mobile web - Learn how.
                        <br />
                        <a href="#">mobile.google.com/news</a>

                        <h6><strong>Soccer</strong> In The Streets</h6>
                        Changing lives....
                        <br />
                        <a href="#">www.soccerstreets.org</a>     
                    </div>                    
                </div><!--end FirstColumn-->

                <div class="SecondColumnOfTwo" id="SecondColumnHighlightBox">
                    <h1>Blogs</h1>                   
                    
                    <ul id="SecondColumnHighlightBoxContentNav">
                        <li class="selected">All Blogs</li>
                        <li><a href="#">Top Blogs</a></li>
                        <li><a href="#">Top Posts</a></li>
                       <?php  if($session->email != null){ ?>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/showmyblog">My Blog</a></li> 
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost">Add Post</a></li>                              
                    	<? } ?>                              
                    </ul>
                    
                    <div id="SecondColumnHighlightBoxContent">
        
                        <div id="boxBlogs">
                           <?php if($this->allblogs != null){ ?>   
                    		<?php foreach($this->allblogs as $blog) { ?>
                              <dl class="blogitem">
                                <dt>
                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/index/id/<?php echo $blog["user_id"];?>">
                                      <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/thumbscomm/<?php echo substr($blog["main_photo"],0,strpos($blog["main_photo"] , ".")). "48x48.jpg"?>"/>
                                    </a>
                                </dt>
                                <dd>
                                    <span class="title"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/showUserBlog/id/<?php echo $blog["blog_id"];?>/username/<?php echo $blog["screen_name"];?>"><?php echo $blog["title"];?></a></span>
                                  
                                    <p><?php echo $blog["description"];?>
                                    </p>
                                    
                                    <span class="name">Owned by <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/index/id/<?php echo $blog["user_id"];?>"><?php echo $blog["screen_name"];?></a></span>&nbsp;
                                    <span class="date">Last Post - <?php echo date(' F j , Y' , strtotime($blog["postdate"])) ;?>&nbsp; at <?php echo date(' g:i a' , strtotime($blog["postdate"])) ;?></span>
                                </dd>
                              </dl>
                             <? } ?> 
                            <? }else { ?>
                            	No blogs created.
                            <? } ?> 
                        </div>

                      </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
   
      </div> <!--end ContentWrapper-->



