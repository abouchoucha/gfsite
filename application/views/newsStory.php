<?php require_once 'seourlgen.php'; 
	 	$urlGen = new SeoUrlGen();
	 	$session = new Zend_Session_Namespace('userSession'); 	
?>

<div id="share">
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

<script language="javascript">

    jQuery(document).ready(function() {

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

        jQuery('#normal').addClass('filterSelected');
        jQuery('#small').click(function(){
   			jQuery('#texsize a').removeClass('filterSelected');
   	     	jQuery('#small').addClass('filterSelected');
   	     	jQuery('div.NewsStoryColumnOne').css('font-size' ,'11px');
   		 });
   		 
        jQuery('#normal').click(function(){
   			jQuery('#texsize a').removeClass('filterSelected');
   	     	jQuery('#normal').addClass('filterSelected');
   	     	jQuery('div.NewsStoryColumnOne').css('font-size','12px');
   		 });
        jQuery('#large').click(function(){
   			jQuery('#texsize a').removeClass('filterSelected');
   	     	jQuery('#large').addClass('filterSelected');
   	     	jQuery('div.NewsStoryColumnOne').css('font-size','13px');
   		 });

   		jQuery('li.EmailArticle').click(function(){
			sendNewsByEmail();
    	});	
	     
		/**
    	* Star Rating 
    	*/	 
   		<?php
	 		if($session->email != null){
         ?>
	     		jQuery('#newsRater2').rater({ rateResult:'#newsRater2' , postHref: '<?php echo Zend_Registry::get("contextPath"); ?>/news/ratenews/idToRate/<?php echo $this->newsArticleId?>' });
			<?php }else{ ?>
			jQuery('#rateDivId').click(function(){
				loginModal();
			});	
		<?php }?>
	     		
	   /*** End Star Rating */	
      
        /**
    	* Share bookmarking
    	*/
    	 jQuery('#sharelink2').click(function() {
            jQuery('#share').show('slow');
            return false;
        });
    	
    	
      jQuery("a[sharewith]").click(function(){
         var url = "<?php echo Zend_Registry::get('contextPath'); ?>/news/sharenews/id/<?php echo $this->newsArticleId?>"; 
    	 jQuery('#newboxresult').load(url); 
    	 pausecomp(2500);
         var rel = jQuery(this).attr("sharewith");
         var url = escape(self.location.href);
         var title = escape(jQuery("title:first").html());
         rel = rel.replace("{url}",url);
         rel = rel.replace("{title}",title);
         self.location.href = rel;
         return false;
      });

    });

    function sendNewsByEmail(){

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
  		jQuery('#sendEmailTitleId').text('EMAIL NEWS ARTICLE');
  		jQuery('#sendEmailto').val("");
  		jQuery('#sendEmailto').attr('required','email');
  		jQuery('#sendEmailtextForwardFriend').val("");
  		jQuery('#sendEmailto').attr('readonly','');
  		jQuery('#sendEmailtextForwardFriend').html("");
  		var headline = "<?php echo $this->article['news_headline'];?>";
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
					data: ({from : from , to :to , subject : subject , message : message }),
					url : '<?php echo Zend_Registry::get("contextPath"); ?>/news/sendnewsbyemail/id/<?php echo $this->newsArticleId?>',
					success: function(data){
						jQuery('#sendEmailBodyResponseId').html('The story was emailed successfully');
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
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/news/editnewscomment';
				var dataEditted = jQuery('#textgoalshoutEdit').val();
				jQuery.ajax({
					type: 'GET',
					data: ({id : id , dataEditted : dataEditted}),
					url: url,
					success: function(data){
						jQuery('#editGoalShoutModal').jqmHide();
						top.location.href = "<?php echo $urlGen->getNewsArticlePageUrl ( $this->article['news_headline'], $this->article['news_id'], false )?>";
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
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/news/removenewscomment/';
			 jQuery.ajax({
					type: 'GET',
					data: ({id : id }),
					url: '<?php echo Zend_Registry::get("contextPath"); ?>/news/removenewscomment/',
					success: function(data){
					 	 jQuery('#messageConfirmationTextId').html('Your comment has been deleted.');
						 jQuery('#acceptModalButtonId').hide();
						 jQuery('#cancelModalButtonId').attr('value','Close');
						 jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
						 top.location.href = "<?php echo $urlGen->getNewsArticlePageUrl ( $this->article['news_headline'], $this->article['news_id'], false )?>";
			    		 
					}	
				})
			 
		 });	
	}

    function reportAbuse(id){

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

			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/news/reportabuse';
			var playerid = '<?php echo $this->playerid;?>';
			var dataReport = jQuery('#textReportAbuseId').val();
			var reportType = jQuery('#reportTypeId').val();

			jQuery.ajax({
				type: 'POST',
				data:({id : id , dataReport : dataReport ,reportType:reportType}),
				url: url,
				success: function(data){
					jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
					jQuery('#reportAbuseBodyId').hide();
					jQuery('#reportAbuseBodyResponseId').show();
					jQuery('#acceptReportAbuseButtonId').hide();
					jQuery('#cancelReportAbuseButtonId').attr('value','Close');
					jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
					top.location.href = "<?php echo $urlGen->getNewsArticlePageUrl ( $this->article['news_headline'], $this->article['news_id'], false )?>";
		    		 
				}	
			})

			
		});	
	 }

	function pausecomp(millis)
	    {
		    var date = new Date();
		    var curDate = null;
		
		    do { curDate = new Date(); }
		    while(curDate-date < millis);
	    } 
    
    function addNewsComment(){
		
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
			 	top.location.href = "<?php echo $urlGen->getNewsArticlePageUrl ( $this->article['news_headline'], $this->article['news_id'], false )?>";
			 		jQuery('#commentsbox').val('');
				}	
			})
			
	}	
    
    </script>
    <script language="javascript" type="text/javascript">
    <!--
    function popitup(url) {
    	newwindow=window.open(url,'name','height=600,width=650,scrollbars=1');
    	if (window.focus) {newwindow.focus()}
    	return false;
    }
    
    // -->
  </script>




    <?php require_once 'Common.php';
    	$common = new Common();
    ?>
   

        <div id="ContentWrapper" class="TwoColumnLayout">
             <div class="FirstColumn">
               <?php
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

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
                              <img class="JoinNowHome" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/join_now_green.jpg" />
                             </a>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <!--News left Menu-->
                    <div class="img-shadow">
                         <?php echo $this->render('include/navigationnews.php');?>
                    </div>
                    
                  
     </div><!--end FirstColumnOfThree-->
	
           		<div id="SecondColumnPlayerProfile" class="SecondColumn">
           			<h1>Featured News</h1>
					<div class="img-shadow">
						<div class="WrapperForDropShadow">
							<div class="SecondColumnProfile">
  							   	<ul class="FriendSearch NewsSearch">
								  <li class="Search">
									<form id="searchplayersform" method="post">
										<label>Search</label>
									  <input id="search-players" type="text" class="text"  name="searchtext" onclick="submitsearch()"/>
									  <input type="submit" class="Submit" value="GO"/>
									</form>
								  </li>
								  <li class="PopularSearches">
			                        Popular Searches:
			                        <a title="R.Van Nistelrooy" href="<?php echo Zend_Registry::get("contextPath"); ?>/players/ruud-nistelrooy_46/">R.Van Nistelrooy</a>
			
			                        ,
			                        <a title="Serie A" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/serie%20a_13/">Italy Serie A</a>
			                        ,
			                        <a title="Ligue 1" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/ligue%201_16/">France Ligue1</a>
			                        </li>
									<!--<li class="AdvancedSearch"><a href="#">Advanced Search</a></li>-->
                                </ul>

                  	<div id="FriendsWrapper" class="NewsStory">
                  		<div class="RSSFeed">
										<a href="<?php echo Zend_Registry::get("contextPath"); ?>/news/rss" class="OrangeLink">RSS News Feed</a>
                		</div>
                		<ul class="PreviousNextStory">
                			<?php if( $this->previousArticle != null) {?>
                			<li class="PreviousStory"><a href="<?php echo $urlGen->getNewsArticlePageUrl($this->previousArticle["news_headline"],$this->previousArticle["news_id"], True);?>" title="<?php echo $this->previousArticle["news_headline"]; ?>">&lt; Previous Story </a></li>
                			<?php } ?>
                			<?php if( $this->nextArticle != null) {?>
                			<li class="NextStory"><a href="<?php echo $urlGen->getNewsArticlePageUrl($this->nextArticle["news_headline"],$this->nextArticle["news_id"], True);?>" title="d">Next Story &gt;</a></li>
                			<?php } ?>
                		</ul>
                		<div class="NewsStoryColumnOne">
										<h2><?php echo $this->article["news_headline"]; ?></h2>

										<p class="Stats Posted">
											<?php echo $this->article["news_provider"]; ?> - <?php  echo date ('l - F j , Y H:i' , strtotime($this->article["news_this_created"]));?>
										</p>
										<p class="Stats">
											<?php echo $this->article["news_byline"]; ?>
										</p>
										<p class="StatsProvider">
											<?php echo $this->article["news_provider"]; ?>
										</p>
                    
                    <p class="Photoright<?php if ($this->PhotoWidth > 200 ) {echo '245';} else {echo '200';}  ?>">
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotoitem/id/<?php echo $this->PhotoId?>">
                      <img style="border:1px solid #CCCCCC" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/feed/afp/<?php echo $this->firstNewsQuickPhoto ?>" />
                    </a>
                    <br>
                    <?php echo $this->firstNewsQuickPhotoCaption ?>
                    </p>

										<p>
										  <?php echo $this->article["news_body_content"]; ?>
										</p>
				        
										<div class="NewsStoryToolbar">
										  
											<ul class="NewsShare">
												<li class="PrintVersion"><a href="/" onclick="return popitup('<?php echo Zend_Registry::get("contextPath"); ?>/news/shownewsstorypf/id/<?php echo $this->newsArticleId; ?>')">Print Version</a></li>
												<li class="EmailArticle"><a id="email2" href="#">Email Article</a></li>
												<li class="RSSFeed"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/news/rss">RSS Feed</a></li>
												<li class="ShareArticle"><a id="sharelink1" href="#">Share Article</a></li>
											</ul>

                                            <ul class="NewsShareLower">
                                               <li><div class="Comments"><?php echo $this->totalComments;?> Comments - <a href="#comments">Add Yours</a></div></li>
                                               <li>
                                                  <div class="statVal" style="width:300px;float:left;text-align:right;">
                                                    <span style="margin: 2px">Avg. Rating: </span>
                                                    <span style="width: 90px; cursor: default;;" class="ui-rater-starsOff"></span>
                                                    <span style="width: <?php echo (18*$this->rating);?>px; cursor: default;" class="ui-rater-starsOn"/></span>
                                                  </div>
                                               </li>
                                           </ul>

										</div>
										<br>
										<a name="rateId"></a><div class="RateArticleTitle">
					            			<h3>RATE THIS ARTICLE</h3>
					            		</div>
					            		<br>
					            		
											<div class="RateArticleArea">
												<div id="newsRater2" class="stat">
							            			<div class="statVal RateArticleCenter">
						                    		  	<span style="font-weight: bold">Your rating: </span>
						                    			<span class="ui-rater" id="rateDivId">
						                    				<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:0px"></span></span>
						                    				<span class="ui-rater-rating"></span>&#160;
						                    			</span>
						                    			<span style="color:gray">(click to rate this article)</span>
							                    	</div>
							            		</div>
						            		</div>
					            		<br/>
					            		<div class="RateArticleTitle">
					            			
					            			<a name="comments"></a><h3 style="width:190px;margin-left:3px;float:left">COMMENTS</h3>
					            			<div style="width:300px;float:left;text-align:right;margin-top:5px"><?php echo $this->totalComments;?> Comments - <a href="#comments">Add Yours</a></div>
					            		</div>
		
					 
					<div id="boxComments">
                      
                      <?php
                        $today = date ( "m-d-Y" ) ;
                        $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                        if ($this->totalComments > 0){
                        	 foreach ($this->comments as $uc){  ?>
                       
                      <dl class="comment">	
                        <dt>
                                        
                          <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>"> 
                              <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'ProfileMale50.gif');?>" />
                           </a>   
                              
                        </dt>	
                        <dd>
                          <span class="name"> 
                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>">
                                	<?php echo $uc["screen_name"];?>
                                </a>
                          </span>&nbsp; 
                          <span class="date"><?php
    		                                if($today == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Today';
    		                                }else if($yesterday == date('m-d-Y' , strtotime($uc["comment_date"]))){
    		                                	echo 'Yesterday';
    		                                }else {
    		                                	echo date(' F j , y' , strtotime($uc["comment_date"])) ;
    		                                }?>
                                    		&nbsp;at <?php echo date(' g:i a' , strtotime($uc["comment_date"])) ;?>
                            </span>	
                          <?php if($session->screenName == $uc['screen_name']){
								if($uc['comment_deleted']=='0'){?>		                        	           
				               <a class="edit" id="edit<?php echo $uc ["comment_id"];?>" href="javascript:editGoalShout('<?php echo $uc ["comment_id"];?>')">Edit</a>
				              <?php }
								} ?>    
				           <?php if($this->isMyProfile == 'y' or $session->screenName == $uc['screen_name']){
				           	if($uc['comment_deleted']=='0'){?>    
				              <a class="edit" href="javascript:deleteGoalShout('<?php echo $uc['comment_id'];?>');">Delete</a>
				              <?php }
								} ?>    
				                               <p id="<?php echo 'goalshout' .$uc['comment_id']?>"><?php if ($uc['comment_deleted']=='0'){ ?><?php echo trim($uc["comment"]); ?>
				                               <?php }else if ($uc['comment_deleted']=='1'){  ?>
				                               		<i>Comment was removed by owner</i>
				                               <?php }else if ($uc['comment_deleted']=='2'){  ?>
				                               		<i>Comment was removed by profile owner</i>
				                               <?php } ?>
				                               </p>
		              <?php if($uc['comment_deleted']=='0'){?>
		             	<span class="abuse"><a class="warning" href="javascript:reportAbuse('<?php echo $uc['comment_id'];?>');">Report This</a></span>
		             <?php }?>
                        </dd>
                      </dl>
                       <?php }
								}else { ?>
									No comments yet.
							 <?php }
						?>
                    </div>
                    <br>
                    <div class="RateArticleTitle">
					            			
            			<h3 style="width:190px;margin-left:3px;float:left">POST A COMMENT</h3>
            			<div style="width:300px;float:left;text-align:right;margin-top:5px"><a href="#comments">Discussion Policy</a></div>
            		</div>
					            		
              		<div id="comment_formerror" class="ErrorMessageIndividual">Error: You must enter a comment.</div> 	
					         <div class="HighFivePost Comments">
                      <a name="boxcomments"></a> 
                        <form id="comment_form" method="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addgoalShout">
                            <textarea <?php if($session->email == null){ ?> 
                            	disabled="disabled" <?php } ?> id="commentsbox" onfocus="this.value='';" class="comments" id="comment" name="comment" rows=5 cols=10>
                              <?php 
	                    		       if($session->email == null){
                            		  echo "Sign in to post comment";
                            	 }else{
                            		  echo "Enter your comment here..."; 
                            	 }
                            ?>
                            </textarea>
                            <input type="hidden" name="idtocomment" value="<?php echo $this->article["news_afp_id"];?>">
                            <input type="hidden" name="commentType" value="4">
                            <input type="hidden" name="screennametocomment" value="<?php echo $session->screenName;?>">

                            <?php if($session->email == null){?><input type="button" id="signInButtonId" value="Leave Comment" class="submit" onclick="loginModal();">
						                <?php	}else{ ?><input type="button" id="addGoalShoutId" value="Leave Comment" class="submit" onclick="addNewsComment();"><?php	} ?>
                        </form>            
                   </div>

									</div>
									<div class="NewsStoryColumnTwo">
										<div class="NewsStoryToolbar Vertical">
											<p id="texsize" class="TextSize">
												Text size:  <a id="small" href="#" class="SmallFont">A</a> |
												 			<a id="normal" href="#" >A</a> | 
												 			<a id="large" href="#" class="LargeFont">A</a>
											<p>
											<ul class="NewsShare">
												<li class="PrintVersion"><a href="/" onclick="return popitup('<?php echo Zend_Registry::get("contextPath"); ?>/news/shownewsstorypf/id/<?php echo $this->newsArticleId; ?>')">Print Version</a></li>
												<li class="EmailArticle"><a id="email1" href="#">Email Article</a></li>
												<li class="RSSFeed"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/news/rss">RSS Feed</a></li>
												<li class="ShareArticle"><a id="sharelink2" href="#">Share Article</a></li>
											</ul>
											

		
											<div class="Comments">
												<a href="#comments"><?php echo $this->totalComments;?> comments</a>
											</div>
								
                          <div id="newsRater" class="stat">
                              <div class="statVal">
                                  <span class="ui-rater">
                                      <a href="#rateId"><span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:<?php echo (18*$this->rating);?>px"></span></a></span>
                                      <span class="ui-rater-rating"><?php echo $this->rating;?></span>&#160;(<span class="ui-rater-rateCount"><?php echo $this->totalVotes;?></span>)
								</span>
                              </div>
                          </div>

										</div>
										<div class="TopNews"><!--- delete this style after putting back tag--->
											<div class="DropShadowHeader BlueGradientForDropShadowHeader">
                      <h4>Top News</h4>
												<span>
												   <a href="<?php echo $urlGen->getShowNewsWorldPageUrl(true); ?>">More &gt;</a>
												</span>
                        </div>
                      			<?php foreach($this->selectedFeeds as $item) { ?>
											<h5><a href="<?php echo Zend_Registry::get("contextPath"); ?>/news/showexternalnews/id/<?php echo $item['feed_news_id']; ?>"><?php echo $item["feed_news_title"]; ?></a></h5>
											<p>
												<?php echo $item["feed_news_description"]; ?>
											</p>
											<!-- <span>##<em>|</em>Rating #.#</span> -->
							         <?php } ?>
											

											<a href="<?php echo $urlGen->getShowNewsWorldPageUrl(true); ?>" class="OrangeLink">See More &gt;</a>
										</div>
									</div>
                </div>
							</div>
						</div>
					</div>
				</div>

             </div> <!--end ContentWrapper-->

