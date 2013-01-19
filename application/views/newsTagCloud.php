 <link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/wordcloud.css' rel="stylesheet" type="text/css" media="screen"/>
 
<?php require_once 'application/controllers/util/wordcloud.class.php';
	$session = new Zend_Session_Namespace('userSession');
?>

<script language="javascript">


jQuery(document).ready(function() {
	 	jQuery('#all').addClass('filterSelected');
	 	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/news/searchFeaturedNews/search/';

	 	//load the first list by default	
	 	jQuery('#newboxresult').html('Loading...');
	 	url = urlBase + 'default/page/'; 
		jQuery('#newboxresult').load(url);

		jQuery('#mr').click(function(){
			jQuery('#FeaturedNewsFilter a').removeClass('filterSelected');
	     	jQuery('#mr').addClass('filterSelected');
			jQuery('#newboxresult').html('Loading...'); 
			url = urlBase + 'mostread/page/'; 
			jQuery('#newboxresult').load(url);
			
	     });
		
		jQuery('#mc').click(function(){
			jQuery('#FeaturedNewsFilter a').removeClass('filterSelected');
	     	jQuery('#mc').addClass('filterSelected');
			jQuery('#newboxresult').html('Loading...'); 
			url = urlBase + 'mostcommented/page/'; 
			jQuery('#newboxresult').load(url);
			
	     });
		
	
	});

</script>
				

<?php require_once 'Common.php';
  	$common = new Common();
?>
<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>
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
     </div><!--end FirstColumnOfThree-->

           		<div id="SecondColumnPlayerProfile" class="SecondColumn">
           			<h1>Featured News Tag Cloud</h1>
					<div class="img-shadow">
						<div class="WrapperForDropShadow">
							<div class="SecondColumnProfile">
  							   <?php

								$cloud = new wordCloud();
								
								foreach ($this->tags as $tag){
									$cloud->addWord(array('word' => $tag['tag'], 'size' => $tag['count'], 'url' => 'tag/'.urlencode($tag['tag']))); // Advanced user method
								}
								
								$myCloud = $cloud->showCloud('array');
								if (is_array($myCloud)){
									foreach ($myCloud as $cloudArray) {
									  echo ' &nbsp; <a href="'.$cloudArray['url'].'" class="word size'.$cloudArray['range'].'">'.$cloudArray['word'].'</a> &nbsp;';
									}
								}
							?>
							</div>
						</div>
					</div>
				</div>

             </div> <!--end ContentWrapper-->
