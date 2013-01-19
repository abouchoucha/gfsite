
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>



<script>
function rate( value ) {
	new Ajax.Updater( 'rateprofile', '<?php echo Zend_Registry::get("contextPath"); ?>/profile/rateProfile/v/'+value );
}

function createBlog(){
	var pars = Form.serialize("createblogform");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/blog/createMyBlog';
	var myAjax = new Ajax.Updater(
			'SecondColumnHighlightBoxContent', 
					url,
		     {
		      method: 'post',
		      parameters: pars
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
                
					      <div class="img-shadow-gray">
                        <div class="WrapperForDropShadow-gray">
                            <div id="Feedback">
                                <strong>Views</strong>
                                <br />

                                High Fives<strong class="Quantity">25</strong>Gooal Shouts<strong class="Quantity">12</strong>
                            
                                <div class="OnlineNow">
                                    Online Now
                                </div>
                                <img width="180" height="180" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TalkingPoint1.jpg" />
                                <br />
                                
                                <ul class="ulGradientBoxes GradientBlue">

                                    <li>Rate Profile:
                                   <div id="rateprofile"> 
                                    <img class="first" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_off.gif" onclick="rate(1)" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_off.gif" onclick="rate(2)" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_off.gif" onclick="rate(3)"/>
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_off.gif" onclick="rate(4)"/>
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_off.gif" onclick="rate(5)"/>
                                    </div>
                                    </li>
                                   
                                    <li>Give a High Five</li>
                                    <li>Send a Gooal Shout</li>
                                    <li class="last">Send Private Message</li>                                    
                                </ul>
                                 <ul class="ulGradientBoxes GradientBlue">
                                  <li>Rate Profile:<img class="first" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_matte.gif" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_matte.gif" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_matte.gif" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_matte.gif" />
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/star_blue_matte.gif" />
                                  </li>
                                  <li>Add to Friends</li>
                                  <li>Add to Favorities</li>
								                  <li>Invite to Group</li>
								                    <li class="last">Forward to Friend</li>
                                </ul>
                                <ul class="ulGradientBoxes GradientGray">
                                    <li>Block this User</li>
                                    <li class="last">Report this user</li>
                                </ul>
                            </div>    
                        </div>
                </div>

                </div><!--end FirstColumn-->

 	      		<div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1>Create/Edit My Blog</h1>                   
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
					
					
                    <div id="SecondColumnHighlightBoxContent">
                        <table class="viewphototables" cellpadding="0" cellspacing="0">
							<tbody><tr>
							    <th>&nbsp;</th>
							    	<th>Content</th>
							    <th>Status</th>
							    <th>Author</th>
							    <th>Date</th>
							    <th>Sticky?</th>
							    <th>&nbsp;</th>
							</tr>
							
							<?php foreach($this->postsPerUser as $ppu) { ?>
							<tr>
							    <td>
							        <input name="posts_selected[]" id="posts_selected_8159" value="8159" type="checkbox">        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost/id/<?php echo $ppu["blogpost_id"];?>">Edit</a>
							        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/blog/createNewPost/id/<?php echo $ppu["blogpost_id"];?>">View</a>
							    </td>
							    	<td><?php echo $ppu["postcaption"];?></td>
							    <td style="font-weight: bold; color: rgb(255, 153, 51);"><?php echo $ppu["poststatus"];?></td>    <td><a href="/profile/chocheraz">chocheraz</a></td>
							    <td><?php echo date(' F j , Y' , strtotime($ppu["postdate"])) ;?></td>
								<td><a onclick="javascript:return confirm('Are you sure you want to change the &quot;stickiness&quot; of this blog post? Sticky blog posts will always remain at the top of your blog even when you make newer blog posts, until you make it &quot;unsticky&quot; again.');" href="/blog/makesticky/id/8159"><img src="/main/web/images/staff/icons/disabled.png" alt="Disabled"></a>    </td>
							    <td><a href="/blog/postdelete/id/8159" onclick="javascript:return confirm('Delete. Are you sure?')">Delete</a></td>
							</tr>
							<? } ?>
							</tbody></table>
							<center><div class="paginate"></div></center>
							<input onclick="this.form.submit();" value="Delete Selected" class="submitter" type="button">
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
				
				
           </div> <!--end ContentWrapper two column layout-->
