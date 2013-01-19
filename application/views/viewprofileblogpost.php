<?php $session = new Zend_Session_Namespace('userSession'); ?>
<script>



function addBlogPostComment(){
	<?php if($session->email != null){ ?>
	var pars = Form.serialize("comment_form");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/addBlogPostComment';
	var myAjax = new Ajax.Updater(
			'boxComments', 
					url,
		     {
		      method: 'post',
		      parameters: pars,
		      insertion: Insertion.Top,
		     });
	<?php }else { ?>
		window.location = '<?php echo Zend_Registry::get("contextPath"); ?>/login';
	<?php } ?>	     
}


</script>


        
  <div id="ContentWrapper" class="TwoColumnLayout">
         <div class="FirstColumn">
             <?php 
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
                            <h4>user's name blog</h4>
                            <a href="Add">Add to Favorites</a>
                            <a class="Subscribe" href="#">Subscribe</a>
                        </div>
                        <div class="SecondColumnBlueBackground">
                           
                           <div>
                                <div class="BlogNav BlogHeaderLinks">                                
                                    <ul>
                                        <li><?php echo date(' F j , Y' , strtotime($this->blogpost->postdate)) ;?>&nbsp;at <?php echo date(' g:i a' , strtotime($this->blogpost->postdate)) ;?></li>
                                        <li class="last">
                                        
                                            <a href="">del.icio.us</a>
                                            |
                                            <a href="">Read It</a>
                                            |
                                            <a href="">Digg it</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <h3><?php echo $this->blogpost->postcaption;?></h3>
                                <?php echo $this->blogpost->posttext;?>
                                <h3>Tags</h3>
                                <a href=""><?php echo $this->blogpost->tags;?></a>
                                <ul class="TabbedNav">
                                    <li class="Selected">Comments&nbsp;(<?php echo $this->blogpost->num_comments;?>) </li>
                                    <li><a href="#">Share</a></li>
                                    <li class="Rating">Rating</li>
                                </ul>
                                <br class="ClearBoth" />
                                <div id="boxComments">
                                
                                    <!--set the background image in the php-->
                                    <?php foreach($this->blogpostcomments as $bpc) { ?>
                                    <div style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/thumbscomm/<?php echo substr($bpc["main_photo"],0,strpos($bpc["main_photo"] , ".")). "48x48.jpg" ?>');background-repeat: no-repeat;background-position: 0px 10px;" class="BlogComment">
                                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/username/<?php echo $bpc["screen_name"];?>"><?php echo $bpc["screen_name"];?></a> <span>|</span> <a href="">See All Comments</a>
                                        <br />
                                       <?php echo date(' F j , Y' , strtotime($bpc["date"])) ;?>&nbsp;<?php echo date(' g:i a' , strtotime($bpc["date"])) ;?>
                                       <p>
                                        <?php echo $bpc["comment"];?>
                                        </p>
                                    </div>
                                    <?php } ?>
                                    
                                </div>
                                <form id="comment_form" action="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/blog/addBlogPostComment">
                                      
                                 <input type="hidden" name="idtocomment" value="<?php echo $this->blogpost->blogpost_id;?>">
                                <fieldset>
                                    <strong>Add Comment</strong>
                                    <br />
                                    <textarea class="comment_text" id="comment" name="comment"> </textarea>
                                    <input class="submit" type="button" value="Post" onclick="addBlogPostComment()"/>
                                    
                                </fieldset>
                                 </form> 
                            </div>
                            
                            
                        </div>
                    </div>
                </div>    
             </div><!--//SecondColumn-->
     </div><!--//ContentWrapper-->
           
