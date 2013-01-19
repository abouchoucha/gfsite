<script>
function createBlog(){
	var pars = Form.serialize("createblogform");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/createMyBlog';
	var myAjax = new Ajax.Updater(
			'SecondColumnHighlightBoxContent', 
					url,
		     {
		      method: 'post',
		      parameters: pars,
		      //insertion: Insertion.Top,
		     });
		   
}
</script>
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

 	      		<div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1>Create My Blog</h1>                   
					<div align="right"><a href = "<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost/id/<? echo $this->blog->blog_id; ?>">New Post</a></div>
                    <div id="SecondColumnHighlightBoxContent">
                        
                        <form id="createblogform" name="createblogform" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/blog/createMyBlog">
                          <input type="hidden" name="actionToDo" value="<?php echo $this->actionToDo;?>">
                          <fieldset>
                              <label for="title">
								Blog Title:
							</label>
                              <input class="text" type="text" value="<? echo $this->blog!=null?$this->blog->title:""; ?>" id="title" name="title" value="" required="title">
                               <BR />
                              <label for="description">
									                 Write a description for your blog: 
							  </label>
								              <textarea id="description" name="description"><? echo $this->blog!=null?$this->blog->description:""; ?></textarea>
								              
								              <input type="button" class="submit GreenGradient" name="CreatBlog" id="createblog" value="<? echo $this->actionTitle; ?>" onclick="createBlog()">
                          </fieldset>
                        </form>
                        
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
				
				
           </div> <!--end ContentWrapper two column layout-->
          


