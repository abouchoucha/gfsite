<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script type="text/javascript">
	
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,preview,|,forecolor,backcolor",
		theme_advanced_buttons2 : "link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,emotions",
		theme_advanced_buttons3 : "",
		
		// Theme options
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		theme_advanced_resizing : true,

	});
	
function createNewPost(){
	document.action = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost';
	document.createblogpostform.submit();
}	
function createNewPost1(){
	var pars = Form.serialize("createblogpostform");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost';
	var myAjax = new Ajax.Updater(
			'SecondColumnHighlightBoxContent', 
					url,
		     {
		      method: 'post',
		      contentType:'multipart/form-data',
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
                    <h1>New Post</h1>                   
					
                    <div id="SecondColumnHighlightBoxContent">
                        
                        <form id="createblogpostform" name="createblogpostform" enctype="multipart/form-data" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost">
                          <input type="hidden" name="blogid" value="<?php echo $this->blog->blog_id;?>">
                          <input type="hidden" name="actionToDo" value="<?php echo $this->actionToDo;?>">
                         <input type="hidden" name="blogpostid" value="<?php echo $this->blogpost->blogpost_id;?>">
                          <fieldset>
                              <label for="title">
									                 Post Title:
								              </label>
                              <input class="text" type="text" id="title" name="title" value="<? echo $this->blogpost!=null?$this->blogpost->postcaption:""; ?>" size="50"">
                              <BR />
                              <label for="tags">
									                 Tags:
								              </label>
                              <input class="text" type="text" id="tags" name="tags" value="<? echo $this->blogpost!=null?$this->blogpost->tags:""; ?>" required="tags">
                              <BR />
                              <label for="content">
									                 Post Content:
								              </label>
								              <textarea id="content" name="content" cols="60" rows="20"><? echo $this->blogpost!=null?$this->blogpost->posttext:""; ?></textarea>
							<div class="margined_left">post comment permissions
			</div>
			<div class="margined_left">
				<div class="edit_error" style="display: none;">
					
				</div>
				<input name="commentPerm" value="public" checked="checked" type="radio">
				public<br>
				<input name="commentPerm" value="friends" type="radio">
				friends only
			</div>
			<div class="margined_left">read permissions
			</div>
			<div class="margined_left">
				<div class="edit_error" style="display: none;">
					
				</div>
				<input checked="checked" name="readPerm" value="public" type="radio">
				public<br>
				<input name="readPerm" value="friends" type="radio">
				friends only
			</div>
			<div class="clear_both"></div>	 
								 
								 
							<input type="button" class="submit GreenGradient" name="createpost" id="createpost" value="<? echo $this->actionTitle; ?>" onclick="createNewPost()">
                          </fieldset>
              </form>
                        
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
				
				
           </div> <!--end ContentWrapper two column layout-->
          


