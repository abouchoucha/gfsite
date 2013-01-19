<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php require_once 'Common.php'; $common = new Common();?>
<?php $session = new Zend_Session_Namespace('userSession');   ?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

<div id="share" style="top:255px;left:782px">
  <span class="close clearfix">
    <img onclick="javascript:getElementById('share').style.display='none'" alt="Close" src="http://media.nbcwashington.com/designimages/ico_close.png"/>
  </span>
  <h3>Share</h3>
  <div class="colwrap"> 
    <div class="lftcol"> 
    <ul class="sharegroup">
      <li class="sharelist">
        <a class="shareIcon delicious" target="_blank" href="http://del.icio.us/post" sharewith="http://del.icio.us/post?v=4&partner=[partner]&noui&url={url}&title={title}">Delicious
        </a>
      </li>
      <li class="sharelist">
        <a class="shareIcon facebook" target="_blank" href="http://www.facebook.com/sharer.php" sharewith="http://www.facebook.com/sharer.php?u={url}&t={title}">Facebook
        </a>
      </li>
      <li class="sharelist">
        <a class="shareIcon stumbleupon" target="_blank" href="http://www.stumbleupon.com/submit" sharewith="http://www.stumbleupon.com/submit?url={url}&title={title}">StumbleUpon
        </a>
      </li>
        <li class="sharelist">
        <a class="shareIcon google" target="_blank" href="http://www.google.com/bookmarks/mark" sharewith="http://www.google.com/bookmarks/mark?op=add&bkmk={url}&title={title}">Google
        </a>
      </li>
        <li class="sharelist">
        <a class="shareIcon newsvine" target="_blank" href="http://www.newsvine.com/_tools/seed&save" sharewith="http://www.newsvine.com/_tools/seed&save?u={url}&h={title}">newsvine
        </a>
      </li>
        <li class="sharelist">
        <a class="shareIcon reddit" target="_blank" href="http://reddit.com/submit" sharewith="http://reddit.com/submit?url={url}&title={title}">Reddit
        </a>
      </li>
      <li class="sharelist">
        <a class="shareIcon blinklist" target="_blank" href="http://www.blinklist.com/index.php" sharewith="http://www.blinklist.com/index.php?Action=Blink/addblink.php&Url={url}&Title={title}">Blinklist
        </a>
      </li>
      </ul>
    </div>

    <div class="rgtcol">
      <ul class="sharegroup">
        <li class="sharelist">
        <a class="shareIcon digg" target="_blank" href="http://digg.com/submit" sharewith="http://digg.com/submit?phase=2&partner=[partner]&url={url}&title={title}">Digg
        </a>
        </li>
        <li class="sharelist">
        <a class="shareIcon mixx" target="_blank" href="http://www.mixx.com/submit" sharewith="http://www.mixx.com/submit?page_url={url}">Mixx
        </a>
      </li>
          <li class="sharelist">
        <a class="shareIcon myspace" target="_blank" href="http://www.myspace.com/Modules/PostTo/Pages/" sharewith="http://www.myspace.com/Modules/PostTo/Pages/?u={url}&t={title}&c=%20">MySpace
        </a>
      </li>
          <li class="sharelist">
        <a class="shareIcon twitter" target="_blank" href="http://twitter.com/home" sharewith="http://twitter.com/home?status={url}">Twitter
        </a>
      </li>
          <li class="sharelist">
        <a class="shareIcon yahoo" target="_blank" href="http://bookmarks.yahoo.com/toolbar/savebm" sharewith="http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u={url}&t={title}">Yahoo
        </a>
      </li>
          <li class="sharelist">
        <a class="shareIcon friendfeed" target="_blank" href="http://friendfeed.com/share" sharewith="http://friendfeed.com/share?url={url}&title={title}">Friendfeed
        </a>
      </li>
          <li class="sharelist">
        <a class="shareIcon bebo" target="_blank" href="http://bebo.com/c/share" sharewith="http://bebo.com/c/share?Url={url}&Title={title}">Bebo
        </a>
      </li>
      
      </ul>
    </div>
  </div>
</div>

<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/rating.css' rel="stylesheet" type="text/css" media="screen"/>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.rating.js" type="text/javascript"></script> 
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

jQuery(document).ready(function() {

    //code for comments box
	 jQuery("p.post").hide();
	 jQuery("#commentsbox").charCounter(400);
	 jQuery(".charcounter").hide();
	 
	 jQuery("#commentsbox").blur(function() {
	        if (jQuery(this).val() == "") {
	        	jQuery(this).val("Enter your comment here...")
	                   .removeClass("comment_filled")
	                   .addClass("comment_empty");
	        	jQuery("p.post").hide();
	        	jQuery(".charcounter").hide();
	        }
	    });

	 jQuery("#commentsbox").focus(function() {
	        if (jQuery(this).val() == "Enter your comment here...") {
	        	jQuery(this).val("")
	                   .removeClass("comment_empty")
	                   .addClass("comment_filled");
	        			jQuery("p.post").show();
	        			jQuery(".charcounter").show();
	        }
	    });
      //end of code - comments box


    

	  jQuery('#reportTypeId').change(function(){
			 var selectValue = jQuery('#reportTypeId').val();
			 if(selectValue == 0){
				 jQuery('#textReportAbuseId').attr('disabled','disabled'); 
				 jQuery('#acceptReportAbuseButtonId').attr('disabled','disabled'); 
			 }else {
			 	jQuery('#textReportAbuseId').removeAttr('disabled');
			 	jQuery('#acceptReportAbuseButtonId').removeAttr('disabled');
			 }	 
	   }); 	
	   
      jQuery('#emailbutton').click(function(){
			   sendPhotoByEmail();
    	});	
      /**
    	* Share bookmarking
    	*/
     jQuery('#sharelink2').click(function() {
            jQuery('#share').show('slow');
            return false;
      });
     jQuery("a[sharewith]").click(function(){
         var rel = jQuery(this).attr("sharewith");
         var url = escape(self.location.href);
         var title = escape(jQuery("title:first").html());
         rel = rel.replace("{url}",url);
         rel = rel.replace("{title}",title);
         self.location.href = rel;
         return false;
      });

     jQuery('#email1').click(function() {
    	 sendPhotoByEmail();
  	 });

     <?php
  	 		if($session->email != null){
           ?>
  	     		jQuery('#newsRater2').rater({ rateResult:'#newsRater2' , postHref: '<?php echo Zend_Registry::get("contextPath"); ?>/photo/ratephoto/idToRate/<?php echo $this->photoId?>' });
  			<?php }else{ ?>
  			jQuery('#rateDivId').click(function(){
  				loginModal();
  			});	
  		<?php }?>
     
      
  });
  
  
 
	function sendPhotoByEmail(){

	    	var k = jQuery('div.ErrorMessageIndividualDisplay');
		    for(var i=0;i < k.length;i++ ){ 
		       k[i].className ='ErrorMessageIndividual';
		    }
	    	
	    	jQuery('#sendEmailModalForm')[0].reset();// this resets form variables to their initial values (must be set in form fields)...
			  jQuery('#labeltoId').hide();
			  jQuery('#sendEmailto').show();
			
	    	<?php if($session->email == null){ ?>
	    		jQuery('#emailFromId').show();
	    		jQuery('#sendEmailfrom').val("");
	    		jQuery('#sendEmailfrom').attr('required','email');
			 <?php }?>
			 
	    	jQuery('#sendEmailBodyId').show();
	  		jQuery('#sendEmailBodyResponseId').hide();
	  		jQuery('#acceptSendEmailButtonId').show(); 	
	  		jQuery('#sendEmailTitleId').text('EMAIL PHOTO');
	  		jQuery('#sendEmailto').val("");
	  		jQuery('#sendEmailto').attr('required','email');
	  		jQuery('#sendEmailtextForwardFriend').val("");
	  		jQuery('#sendEmailto').attr('readonly','');
	  		jQuery('#sendEmailtextForwardFriend').html("");
	  		var headline = "<?php echo $this->photoCaption ; ?>";
	  		jQuery('#sendEmailsubject').val(headline);
	  		jQuery('#sendEmailsubject').attr('readonly','readonly');
	  		
	  		jQuery('#sendEmailModal').jqm({trigger: '#forwardToFriendTrigger', onHide: closeModal });
	  		jQuery('#sendEmailModal').jqmShow();
	  
	  		jQuery('#acceptSendEmailButtonId').unbind();
	  		
	  		jQuery('#acceptSendEmailButtonId').click(function() {

				valid = validaNewForm('sendEmailModalForm');
				if(valid){
			 		var from = jQuery('#sendEmailfrom').val();
					var to = jQuery('#sendEmailto').val();
					var subject = jQuery('#sendEmailsubject').val();
					var message = jQuery('#sendEmailtextForwardFriend').val();
					
					jQuery.ajax({
						type: 'POST',
						data: ({from : from , to :to , subject : subject , message : message , id : <?php echo $this->photoId;?>  }),
						url : '<?php echo Zend_Registry::get("contextPath"); ?>/photo/sendphotobyemail/id/<?php echo $this->photoId;?>',
						success: function(data){
							jQuery('#sendEmailBodyResponseId').html('The photo was emailed successfully');
							jQuery('#sendEmailBodyId').hide();
							jQuery('#sendEmailBodyResponseId').show();
							jQuery('#acceptSendEmailButtonId').hide();
							jQuery('#cancelSendEmailButtonId').attr('value','Close');
							jQuery('#sendEmailModal').animate({opacity: '+=0'}, 2500).jqmHide();
							
						}	
					})
				}
			});

	    }

  function addPhotoComment(){
	  	jQuery('#comment_formerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			  var commentText = jQuery.trim(jQuery('#commentsbox').val());
			  var ok = 'true'; 	
			  if(commentText == '' || commentText == 'Enter your comment here...'){
				jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
				return;
			 }	
			 
			 jQuery.ajax({
					type: 'POST',
					data: jQuery("#comment_form").serialize(),
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalshout',
					success: function(data){
				 	top.location.href = "";
				 		jQuery('#commentsbox').val('');
					}	
				})
				
	}

  function editGoalShout(id){
		
		jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShoutTrigger', onHide: closeModal });
		jQuery('#editGoalShoutModal').jqmShow();
		var dataEdit = jQuery('#goalshout'+id).html();
		jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));
		
			jQuery('#acceptEditGoalShoutButtonId').click(function() {
				var commentText = jQuery('#textgoalshoutEdit').val();
				if(jQuery.trim(commentText) == ''){
					jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		 			return;
		 		 }
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/editphotocomment';
				var dataEditted = jQuery('#textgoalshoutEdit').val();
				jQuery.ajax({
					type: 'GET',
					data: ({id : id , dataEditted : dataEditted}),
					url: url,
					success: function(data){
						jQuery('#editGoalShoutModal').jqmHide();
						top.location.href = "<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/itemid/<?php echo $this->photoId;?>";
					}	
				})
			});
	}
	function deleteGoalShout(id){

		 jQuery('#acceptModalButtonId').show();
		 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalTitleConfirmationId').html('DELETE COMMENT?');
		 jQuery('#messageConfirmationTextId').html('Are you sure you want to delete a comment');	
		 
				    
		 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal });
		 jQuery('#messageConfirmationId').jqmShow();
		 
		 jQuery("#acceptModalButtonId").unbind();
			
		 jQuery('#acceptModalButtonId').click(function(){
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/removephotocomment/';
			 jQuery.ajax({
					type: 'GET',
					data: ({id : id }),
					url: url,
					success: function(data){
					 	 jQuery('#messageConfirmationTextId').html('Your comment has been deleted.');
						 jQuery('#acceptModalButtonId').hide();
						 jQuery('#cancelModalButtonId').attr('value','Close');
						 jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
						 top.location.href = "<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/itemid/<?php echo $this->photoId;?>";
			    		 
					}	
				})
			 
		 });	
	}

	function reportAbuse(id , reportTo){

		jQuery('#reportTypeId').val('0');
		jQuery('#textReportAbuseId').val(''); 
		jQuery('#textReportAbuseId').attr('disabled','disabled');
		jQuery('#reportAbuseBodyId').show();
		jQuery('#reportAbuseBodyResponseId').hide();
		jQuery('#acceptReportAbuseButtonId').show(); 
		jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	
		jQuery('#reportAbuseTitleId').html('REPORT ABUSE?');
		jQuery('#reportAbuseTextId').html('Are you sure you want to report abuse in this comment?');	

		jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal });
		jQuery('#reportAbuseModal').jqmShow();
		
		jQuery("#acceptReportAbuseButtonId").unbind();
		jQuery('#acceptReportAbuseButtonId').click(function() {

			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/reportabuse';
			var playerid = '<?php echo $this->playerid;?>';
			var dataReport = jQuery('#textReportAbuseId').val();
			var reportType = jQuery('#reportTypeId').val();

			jQuery.ajax({
				type: 'POST',
				data:({id : id , dataReport : dataReport ,reportTo : reportTo ,reportType:reportType}),
				url: url,
				success: function(data){
					jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
					jQuery('#reportAbuseBodyId').hide();
					jQuery('#reportAbuseBodyResponseId').show();
					jQuery('#acceptReportAbuseButtonId').hide();
					jQuery('#cancelReportAbuseButtonId').attr('value','Close');
					jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
					top.location.href = "<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/itemid/<?php echo $this->photoId;?>";
		    		 
				}	
			})

			
		});	
	 }	

	function setbg(color)
	{
		jQuery('#commentsbox').css("background-color", color);
	}
  

</script>
   
   
<div id="ContentWrapper" class="TwoColumnLayout">
     <div class="FirstColumn" style="margin-right:5px;">
               <?php
                    $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>

                    
                    
                    <?php if ($this->typeid == 1) { ?>
			        <!--Team badge-->
			        <div class="img-shadow">
			         	<?php echo $this->render('include/badgeTeam.php');?>
			        </div>
			        <!--Team Profile left Menu-->
			        <div class="img-shadow">
			                <?php echo $this->render('include/navigationTeam.php');?>
			        </div>
			            <?php } else if ($this->typeid == 2) { ?>
			        <!--Player badge-->
			        <div class="img-shadow">
			                <?php echo $this->render('include/badgePlayer.php');?>
			        </div>
			
			        <!--Player Profile left Menu-->
			        <div class="img-shadow">
			              <?php echo $this->render('include/navigationPlayer.php');?>
			        </div>
			         <?php }  ?>
                    
                    
                    
        
                    <?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                    <?php if ($this->typeid == 1) { ?>
			        <!--Team badge-->
			        <div class="img-shadow">
			         	<?php echo $this->render('include/badgeTeam.php');?>
			        </div>
			        <!--Team Profile left Menu-->
			        <div class="img-shadow">
			                <?php echo $this->render('include/navigationTeam.php');?>
			        </div>
			            <?php } else if ($this->typeid == 2) { ?>
			        <!--Player badge-->
			        <div class="img-shadow">
			                <?php echo $this->render('include/badgePlayer.php');?>
			        </div>

			        <!--Player Profile left Menu-->
			        <div class="img-shadow">
			              <?php echo $this->render('include/navigationPlayer.php');?>
			        </div>
			         <?php }  ?>

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

         <div class="SecondColumn" id="pictures">
               <p class="burg">Pictures</p>
				<div class="sports">
						<p class="sprev">
								
							<?php if( $this->previousPhoto != null) {?>
								<?php if( $this->typeid == '1' or $this->typeid == '2') {?>
                					<span class="sprev1"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/<?php echo $this->typeid;?>/id/<?php echo $this->previousPhoto["tag_id"]; ?>/itemid/<?php echo $this->previousPhoto["image_id"]; ?>">&lt; Previous</a></span>
                				<?php }else { ?>
                					<span class="sprev1"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/0/id/0/itemid/<?php echo $this->previousPhoto["image_id"]; ?>">&lt; Previous</a></span>
								<?php } 
                				}?>
                			<?php if( $this->nextPhoto != null) {?>
                				<?php if( $this->typeid == '1' or $this->typeid == '2') {?>
                					<span class="sprev2"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/<?php echo $this->typeid;?>/id/<?php echo $this->nextPhoto["tag_id"]; ?>/itemid/<?php echo $this->nextPhoto["image_id"]; ?>">Next &gt;</a></span>
                				<?php }else { ?>
                					<span class="sprev2"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/0/id/0/itemid/<?php echo $this->nextPhoto["image_id"]; ?>">Next &gt;</a></span>
                				<?php } ?>
                				<?php } ?>							
						</p>
						
				
						
						<p class="comment">
								<span class="rate">
									<span class="ratt">Rate:</span>
									<div class="RateArticleArea">
												<div id="newsRater2" class="stat">
							            			<div class="statVal RateArticleCenter" style="width:160px;">
						                    			<span class="ui-rater" id="rateDivId">
						                    				<span class="ui-rater-starsOff" style="width:90px;margin-left:3px;
margin-top:-12px;"><span class="ui-rater-starsOn" style="width:0px"></span></span>
						                    				<span class="ui-rater-rating"></span>&#160;
						                    			</span>
							                    	</div>
							            		</div>
						      </div>
								</span>
								<span class="avgg">Avg: (<font color="#3590ed"><?php echo $this->rating;?></font>)</span>
								<span class="addyour"><?php echo $this->totalComments;?> Comments: # - <a href="#comments">Add Yours</a></span>
						</p>
						<p class="share">
							<span id="sharelink2" class="share1" id="chromemenu2"><a href="#" rel="dropdownshare">Share</a></span>							
							<span id="email1" class="share1"><a href="#">E-mail</a></span>
						</p>

						<p class="imggg" style="width:100%;text-align:center;">
							<a href="#">
<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/<?php echo $this->photoImageName; ?>.jpg" <?php echo $common->imageResize($this->image_width, $this->image_height, 512)?>  alt="" />
							</a>
						</p>
						
						
						<p class="bolton"><?php echo $this->photoCaption ; ?>.</p>
						<p class="comm"><a name="comments"></a><span class="comm1">Comments</span>
								<span class="comm2"><?php echo $this->totalComments;?> Comments - <a href="#comments">Add Yours</a></span>
						</p>
					<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/new_pagination.phtml'); ?>
					<?php
                        $today = date ( "m-d-Y" ) ;
                        $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                        $cont = 1;
                        if ($this->totalComments > 0){
                        	 foreach ($this->paginator as $uc){  
                        	 if($cont % 2 == 1) {
                               $className = "crush";
                            }else{
                               $className = "crushs";
                            }
                        	 ?>
                        	 
					<div class="<?php echo $className;?>">
							
							<p class="cru1"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"> 
                              <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>" />
                           </a> 
                           
                           </p>
                           
							<p class="crushh">
									<span class="crushh1">
										<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"><?php echo $uc["screen_name"];?></a>  
										<font style="color:#808080;font:11px arial;">
									(
										<?php
    		                                if($today == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Today';
    		                                }else if($yesterday == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Yesterday';
    		                                }else {
    		                                	echo date(' F j , y' , strtotime($uc["comment_date"])) ;
    		                                }?>
                                    		&nbsp;at <?php echo date(' g:i a' , strtotime($uc["comment_date"])) ;?>
									) </font>
									</span>
									<span> 
									<?php if($session->screenName == $uc['screen_name']){
										if($uc['comment_deleted']=='0'){?>		                        	           
						               		<a id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["comment_id"];?>')">Edit</a>
						              		<?php }
										} ?>   
									</span> 
									<?php if($session->screenName == $uc['screen_name']){ ?>
									   |
									<?php } ?>
									 <span> <?php if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name']){
							           	if($uc['comment_deleted']=='0'){?>    
							              <a  href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
							              <?php }
											} ?>  
										</span>
										<?php if($uc['comment_deleted']=='0'){
													if($session->screenName == null){?>
														<span class="reportthis"><a href="javascript:loginModal();">Report This</a></span>
													<?php }else {?>
													<span class="reportthis"><a href="javascript:reportAbuse('<?php echo $uc['comment_id'];?>','<?php echo $uc['friend_id'];?>');">Report This</a></span>
												<?php }
														} ?>
									<span class="amet"><p id="<?php echo 'goalshout' .$uc['comment_id']?>"><?php if ($uc['comment_deleted']=='0'){ ?><?php echo trim($uc["comment"]); ?>
				                               <?php }else if ($uc['comment_deleted']=='1'){  ?>
				                               		<i>Comment was removed by owner</i>
				                               <?php }else if ($uc['comment_deleted']=='2'){  ?>
				                               		<i>Comment was removed by profile owner</i>
				                               <?php } ?>
				                     </p></span>
							</p>
					</div>

				<?php $cont++;}
				}//else { ?><!--
					<div class="crush">
						No comments yet.
					</div>
			 --><?php //}
		?>
			
			<form id="comment_form" method="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalShout">

	          <div class="commentbox">
				<textarea <?php if($session->email == null){ ?>disabled="disabled" <?php } ?> id="commentsbox" class="comment_empty" name="comment"><?php if($session->email == null){ ?>Sign in to post comment.<?php } else { ?>Enter your comment here...<?php } ?></textarea>
			 	<p class="post">
		            <?php if($session->email == null){?>
		              <a href="javascript:void(0)" onclick="loginModal();">Post</a>
		            <?php	}else{ ?>
		              <a href="javascript:void(0)" onclick="addPhotoComment();">Post</a>
		            <?php	} ?>
          	 	</p>
          	 	
          	 	<input type="hidden" name="photoid" value="<?php echo $this->photoId;?>">
            	<input type="hidden" name="commentType" value="8">
            	<input type="hidden" name="screennametocomment" value="<?php echo $session->screenName;?>">
			  </div>  

          
          	</form>
					
			<p class="sprev">
							<span class="sprev1">
                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photos">See More Pictures &gt;</a>
              </span>
           </p>   

				</div>

              </div><!--end Second Column-->
      
              <div class="right1">
              	<ul>
        			<li class="gall">&nbsp;<!--<a href="#">Gallery</a>--></li>
        			<li class="line"><!-- |--></li>
        			<li class="gall">&nbsp;<!--<a href="#">Slideshow</a>--></li>
        		</ul>
        		<p class="iab"><!--<a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/iab.jpg" alt="" /></a>--></p>
              </div><!--end Second Column right1-->
        

</div> <!--end ContentWrapper-->

