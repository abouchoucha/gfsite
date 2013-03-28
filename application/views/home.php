<?php 
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
?>
<?php  $session = new Zend_Session_Namespace('userSession'); ?>
<script type="text/JavaScript">
 jQuery(document).ready(function() {
	 //showinterstitial();
	 
	 callRandonProfiles();
	 callCommunityActivities();
	 callFeaturedNews();
	 //callHomeGallery();
	 //callScoreboard();
	 
	 
 //setup tabs - featured players and teams
        jQuery('#menu6content,#menu7content').hide();
        jQuery('#menu6content').show();
        jQuery('#menu6').addClass('active');
        jQuery('#featTab ul li').click(function(){
           // var thisClass = this.className;
            jQuery('#menu6content,#menu7content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            //show see more header link
            jQuery('#menu6_more,#menu7_more').hide();
            jQuery('#' + tab_id + '_more').show();
            jQuery('#menu6,#menu7').removeClass('active');
            jQuery(this).addClass('active');
       });

        //Setup Tabs - Featured Matches
       jQuery('#menu4content,#menu5content').hide();
       <?php if($this->nextMatch != null) {?>
            jQuery('#menu4content').show();
            jQuery('#menu4').addClass('active');
       <?php } else { ?>
            jQuery('#menu5content').show();
            jQuery('#menu5').addClass('active');
       <?php }  ?>
       jQuery('#matchTab ul li').click(function(){
            jQuery('#menu4content,#menu5content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            //show see more header link
            jQuery('#menu4_more,#menu5_more').hide();
            jQuery('#' + tab_id + '_more').show();
            jQuery('#menu4,#menu5').removeClass('active');
            jQuery(this).addClass('active');
        });
       
       
       
       
       
   <?php if ($session->email == null){ ?>
	  	jQuery('#customScoreBoard').click(function(){ //
	  		loginModal();
		 });
	 <?php } else { ?>
	//toggle on/off for edit scoreboard
	  jQuery("#editscore").hide();
	  jQuery('#customScoreBoard').click(function() {
		  jQuery("#editscore").toggle('slow');
		  return false;
	  });	
	  <?php }  ?>	  
	  //submit login
	  jQuery('#submitButton').click(function() {
		  	login();
		  });
		
	  jQuery('#saveEditFavorites').click(function() {
		    var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/editscoreboard';
		    var query_string = '';
		   
		    jQuery("input[name='competition']").each(
		    function(){
			    if(this.checked){
			    	query_string += "&competition[]=" + this.value;
			    }
		    });
		    
		    jQuery.ajax({
                type: 'POST',
                url : url,
                data : "id=1" + query_string ,
                success: function (text) {
      					jQuery('#ScorecardContainer').html(text);
      					jQuery("#editscore").hide();
      			}
             });
		  
	  	  });	
	  
	        //scoreboards - added 03/01/11
     
	  		var timezone = calculate_time_zone(); 
		 	jQuery('#today').addClass('filterSelected');
		 	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboard/date/';
		 	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
			var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
		 	<?php if($session->email != null) { ?>
			 	//load the first list by default in myscores
		 		jQuery('li.Selected').removeClass('Selected');
				jQuery('#myScoresTabLi').addClass('Selected');	
				jQuery('#tab_1').show();
			 	jQuery('#scoreboardResult0').html("<div class='ajaxload widget'></div>");
			 	jQuery('#tab_2').hide();
			 	url = urlBase ; 
				//jQuery('#scoreboardResult0').load(url , {date : 'today' , type :'myscoreboard', initDateTime : initDateTime, endDateTime : endDateTime});
			 	//added 03/01/11
			 	jQuery('#scoreboardResult0').load(url , {date : 'today' ,timezone : timezone, type :'myscoreboard', initDateTime : initDateTime, endDateTime : endDateTime});
		 	<?php }else { ?>
			 	//load the first list by default in scores	
			 	jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
			 	url = urlBase ; 
				//jQuery('#scoreboardResult').load(url , {date : 'today', initDateTime : initDateTime, endDateTime : endDateTime});
			 	//added 03/01/11
			 	jQuery('#scoreboardResult').load(url , {date : 'today', timezone : timezone , initDateTime : initDateTime, endDateTime : endDateTime}); 
		 	<?php } ?>
			
			jQuery('#myScoresTab').click(function() {
				jQuery('li.Selected').removeClass('Selected');
				jQuery('#myScoresTabLi').addClass('Selected');
				jQuery('#MyScoresDateFilter a').removeClass('filterSelected');
		     	jQuery('#todayms').addClass('filterSelected');
				jQuery('#scoreboardResult0').html('');
				jQuery('#scoreboardResult0').html("<div class='ajaxload widget'></div>");
				jQuery('#tab_1').show();
				//added 03/01/11
				jQuery('#scoreboardResult0').load(urlBase , {date : 'today' , type :'myscoreboard',timezone : timezone, initDateTime : initDateTime, endDateTime : endDateTime});
				//jQuery('#scoreboardResult0').load(urlBase , {date : 'today' , type :'myscoreboard', initDateTime : initDateTime, endDateTime : endDateTime});
				jQuery('div#tab_2>div>div#scoreboardResult').html('Loading...');
				jQuery('div#tab_3>div>div#scoreboardResult2').html('Loading...');
			 	jQuery('#tab_2').hide();
			 	jQuery('#tab_3').hide();
			});
			jQuery('#scoresTab').click(function() {
				jQuery('li.Selected').removeClass('Selected');
			    jQuery('#scoresTabLi').addClass('Selected');
			    jQuery('#ScoresDateFilter a').removeClass('filterSelected');
		     	jQuery('#today').addClass('filterSelected');
			    jQuery('#scoreboardResult').html('');
			    jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
				jQuery('#tab_2').show();
				jQuery('#scoreboardResult').load(urlBase , {date : 'today',timezone : timezone, initDateTime : initDateTime, endDateTime : endDateTime});
				//added 03/01/11
				//jQuery('#scoreboardResult').load(urlBase , {date : 'today', initDateTime : initDateTime, endDateTime : endDateTime});
			 	jQuery('div#tab_1>div>div#scoreboardResult0').html('Loading...');
			 	jQuery('div#tab_3>div>div#scoreboardResult2').html('Loading...');
			 	jQuery('#tab_1').hide();
			 	jQuery('#tab_3').hide();
			});
			jQuery('#schedulesTab').click(function() {
				jQuery('li.Selected').removeClass('Selected');
				jQuery('#schedulesTabLi').addClass('Selected');
				jQuery('#SchedulesDateFilter a').removeClass('filterSelected');
		     	jQuery('#tomorrow').addClass('filterSelected');
				jQuery('#scoreboardResult2').html('');
			    jQuery('#scoreboardResult2').html("<div class='ajaxload widget'></div>");
				jQuery('#tab_3').show();
				jQuery('#scoreboardResult2').load(urlBase , {date : 'tomorrow', timezone : timezone,initDateTime : initDateTime, endDateTime : endDateTime});
				//added 03/01/11
				//jQuery('#scoreboardResult2').load(urlBase , {date : 'tomorrow', initDateTime : initDateTime, endDateTime : endDateTime});
				jQuery('div#tab_1>div>div#scoreboardResult0').html('Loading...');
			 	jQuery('div#tab_2>div>div#scoreboardResult').html('Loading...');
			 	jQuery('#tab_1').hide();
			 	jQuery('#tab_2').hide();
			});
					
			//myscoreboard
		 	<?php if($session->email != null) { ?>
		 	jQuery('#todayms').click(function(){			
				loadScoreBoard(this.id, urlBase , 'today','scoreboardResult0' ,'MyScoresDateFilter','myscoreboard');			
		     });
		 	jQuery('#l3ms').click(function(){			
				loadScoreBoard(this.id, urlBase , '-3' ,'scoreboardResult0' ,'MyScoresDateFilter','myscoreboard');			
		     });
			jQuery('#l7ms').click(function(){			
				loadScoreBoard(this.id, urlBase , 'last' ,'scoreboardResult0' ,'MyScoresDateFilter','myscoreboard');			
		     });
	 		<?php } ?>	
			//scores
			jQuery('#today').click(function(){			
				loadScoreBoard(this.id, urlBase , 'today','scoreboardResult' ,'ScoresDateFilter','commom');			
		     });
			jQuery('#l3').click(function(){			
				loadScoreBoard(this.id, urlBase , '-3' ,'scoreboardResult' ,'ScoresDateFilter','commom');			
		     });
			jQuery('#l7').click(function(){			
				loadScoreBoard(this.id, urlBase , 'last' ,'scoreboardResult' ,'ScoresDateFilter','commom');			
		     });
		     //schedules
			jQuery('#tomorrow').click(function(){			
				loadScoreBoard(this.id, urlBase , 'tomorrow' ,'scoreboardResult2' ,'SchedulesDateFilter','commom');			
		     });
			jQuery('#n3').click(function(){			
				loadScoreBoard(this.id, urlBase , '3' ,'scoreboardResult2' ,'SchedulesDateFilter','commom');			
		     });
			jQuery('#n7').click(function(){			
				loadScoreBoard(this.id, urlBase , 'week' ,'scoreboardResult2' ,'SchedulesDateFilter' ,'commom');			
		     });
			
			 
		//Profile go to Main Profile page
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profiles/';
			jQuery('#buttonProfile').click(function(){ //se ejecuta con el evento onCLick
				document.profilesHomeForm.action = url;
				document.profilesHomeForm.submit();
			 });
			jQuery('#buttonActivity').click(function(){ //
				showActivitiesByCategory();
			 }); 			
			jQuery('#buttonNews').click(function(){ //
				showFeaturesNewsByByHeadLines();
			 });
			jQuery('#buttonPhotos').click(function(){ //
				showHomeGalleryByPhotos();
			 });	
			//news close open div
			showHideDivBox('TopNewsArrowId','TopNewsBodyId');
			//photos close open div
			showHideDivBox('photoGalleryArrowId','photoGalleryBodyId');
			//scoreboard close open div
			showHideDivBox('scoreboardArrowId','scoretabBodyId');
	});
 function loadScoreBoard(id, url, date, container , filter ,type)
 {
	 	//added 03/01/11
	 	var timezone = calculate_time_zone(); 
	 	var initDateTime = formatDate(getCurrentInitTime(+1.0),'yyyy-MM-dd HH:mm:ss');
		var endDateTime = formatDate(getCurrentEndTime(+1.0),'yyyy-MM-dd HH:mm:ss');
		jQuery('#'+ filter+' a').removeClass('filterSelected');
     	jQuery('#' + id).addClass('filterSelected');
		jQuery('#' + container).html("<div class='ajaxload widget'></div>"); 	
		jQuery('#' + container).load(url , {date : date , type :type, timezone : timezone, initDateTime : initDateTime, endDateTime : endDateTime});
		//added 03/01/11		
		//jQuery('#' + container).load(url , {date : date , type :type, initDateTime : initDateTime, endDateTime : endDateTime});
 }
 
 
//Load Random Profiles
 function callRandonProfiles()
 {
       jQuery('#randomprofiles').html("<div class='ajaxload widget'></div>");
       jQuery.ajax({
                     method: 'get',
                     url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom',
                     dataType : 'text',
                     success: function (text) {
                         jQuery('#randomprofiles').html(text);
                     }
                  });
 }
 //setInterval(callRandonProfiles, 60000);
//Load Community activities
function callCommunityActivities(){
	var timezone = calculate_time_zone();
    jQuery('#community').html("<div class='ajaxload widgetlong'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/community/showallactivities/timezone/'+timezone,
        dataType : 'text',
        success: function (text) {
            jQuery('#community').html(text);
         }
     });
}
setInterval(callCommunityActivities,300000);
function showActivitiesByCategory(){
	var timezone = calculate_time_zone();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/community/showallactivities/timezone/'+timezone;
	var activityId = jQuery("#activityId").val();
	jQuery('#community').html("<div class='ajaxload widgetlong'></div>");
	jQuery('#community').load(url ,{ activityId : activityId });
}
//AutoUpdate for Featured News
function callFeaturedNews(){
	var numOfFeeds = jQuery('#headLineId').val();
	jQuery('#FeaturedNewsList').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/news/homefeaturednews/numfeeds/'+numOfFeeds,
        dataType : 'text',
        success: function (text) {
                jQuery('#FeaturedNewsList').html(text);
		}
     });
}
//setInterval(callFeaturedNews, 60000);
function showFeaturesNewsByByHeadLines(){
	var numFeeds = jQuery("#headLineId").val();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/news/homefeaturednews/numfeeds/'+numFeeds;
	jQuery('#FeaturedNewsList').html("<div class='ajaxload widget'></div>");
	jQuery('#FeaturedNewsList').load(url);
}
//AutoUpdate for Home Gallery
function callHomeGallery(){
    jQuery('#PhotoGalleryList').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomephotos/numphotos/4',
        dataType : 'text',
        success: function (text) {
			jQuery('#PhotoGalleryList').html(text);
		}
     });
}
//setInterval(callHomeGallery, 60000);
function showHomeGalleryByPhotos(){
	var numPhotos = jQuery("#slcgallery").val();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomephotos/numphotos/'+numPhotos;
	jQuery('#PhotoGalleryList').html('Loading...');
	jQuery('#PhotoGalleryList').load(url);
}
//AutoUpdate for ScoreBoard
function callScoreboard(){
	jQuery.ajax({
        method: 'post',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboard/date/<?php echo $this->selected; ?>',
        dataType : 'text',
        success: function (text) {
				jQuery('#tab1').html(text);
		}
     });
}
//setInterval(callScoreboard, 60000);
function closeAll(){
	jQuery('div.Scores').removeClass('Scores').addClass('ScoresClosed');
	jQuery('div.DownArrow').removeClass('DownArrow').addClass('RightArrow');
}
function openAll(){
	jQuery('div.ScoresClosed').removeClass('ScoresClosed').addClass('Scores');
	jQuery('div.RightArrow').removeClass('RightArrow').addClass('DownArrow');
}
</script>
<!--Page Starts here-->
<div id="ContentWrapper">
  <div class="FirstColumn">        
    <?php //echo $this->render('include/topleftbanner.php')?>                
<?php
                    
                    if($session->email != null){
                    ?>                                        
    <div class="img-shadow">
      <div class="WrapperForDropShadow">                              
        <?php include 'include/loginbox.php';?>                         
      </div>
    </div>
    <!--Feedback-->                  
    <?php //echo $this->render('include/feedbackbadge.php')?>                                                          
    <?php }else { ?> 					 	                    
    <!--Me box Non-authenticated--> 
    <div class="img-shadow">
      <div class="WrapperForDropShadow">	                            
        <?php include 'include/loginNonAuthBox.php';?>	                        
      </div>
    </div>
    <!--Feedback--> 
    <!--Goalface Join Now-->
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <a 	href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" 	title="GoalFace Registration"> 
          <img border="0" 	src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" 	style="margin-bottom: -3px;" /> </a>
      </div>
    </div>                    
    <?php } ?>                                   		
    <!--Facebook Like Module-->                    
    <?php echo $this->render('include/navigationfacebook.php')?>                                       
  </div>
  
  <div class="SecondColumn">
  
     <!--Brand New  Featured  Teams and Players -->
   <div class="prof">
    <p class="mblack">
      <span class="black">Featured Teams &amp; Players
      </span>
      <span id="menu6_more" class="sm"> 
        <a 	href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">More &raquo;</a>
      </span> 
      <span id="menu7_more" class="sm" style="display: none;"> 
        <a 	href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">More &raquo;</a>
      </span>
    </p>
    <div id="featTab" class="nxt">
        <ul>
            <li id="menu6"><a href="javascript:void(0);">Teams</a></li>
            <li id="menu7"><a href="javascript:void(0);">Players</a></li>
        </ul>
    </div>
    
   <!--Teams Tab Content-->
    <div class="nmatch" id="menu6content">
          <?php
              $config = Zend_Registry::get ( 'config' );
              $teamCounter = 0;
              if(sizeOf($this->featuredTeams) == 0){
              	echo "<br><center><strong>No Data Available.</strong></center>";
              }else {
              
              
              foreach ($this->featuredTeams  as $data) {
               $teamCounter++;
          ?>
          <?php if($teamCounter==1){  ?>
            <div class="imgs">
          <?php }  ?>

              <p class="<?php if($teamCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">

                  <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                     <?php 
                        $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;   
                        if (file_exists($path_team_logos)) { ?> 
                          <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif" alt="<?php echo $data['team_name'];?>"/>
                      <?php } else { ?>
                          <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif" alt="<?php echo $data['team_name'];?>"/>
                      <?php } ?>
                  </a>
                  <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                    <?php echo $data['team_name'];?>
                  </a>
                  
              <?php if ($session->email != null) { ?>
    					<?php if($session->userId == $data['user_id']) { ?>
    					     <a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    					<?php } else { ?>
    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    						<a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    					<?php } ?>
					
    			   <?php } else { ?>
    				     <a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    					</a>
    			   <?php } ?>	

              </p>

           <?php if($teamCounter==3){ $teamCounter = 0; ?>
              </div>
            <?php } ?>

              <?php } 
              }?>

                <p class="smatch1">
                    <a class="OrangeLink" href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">See More Featured Teams</a>
                </p>
        </div>
            
        <!--Players Tab Content-->
        <div class="nmatch" id="menu7content">
          <?php
              $config = Zend_Registry::get ( 'config' );
              $playerCounter = 0;
              if(sizeOf($this->featuredPlayers) == 0){
              	echo "<br><center><strong>No Data Available.</strong></center>";
              }else {
              foreach ($this->featuredPlayers  as $pp) {
               $playerCounter++;
          ?>
          
              <?php if($playerCounter==1){  ?>
                  <div class="imgs">
               <?php }  ?>

               <p class="<?php if($playerCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">
                             
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_name_short"] ?>">
                           <?php
						            $path_player_photos = $config->path->images->players . $pp["player_id"] .".jpg" ;
						             if (file_exists($path_player_photos)) { 
						          ?>
                                        <img id="player<?php echo $pp["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/players/<?php echo $pp["player_id"]; ?>.jpg" alt="<?php echo $pp["player_common_name"];?>"/>
                                        
                                   <?php } else  { ?>
                                        <img id="player<?php echo $pp["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/Player1Text80.gif" alt="<?php echo $pp["player_common_name"];?>"/>
                                  <?php } ?>
                      </a>
                      <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_name_short"] ?>">
                                <?php echo $pp["player_name_short"];?>
                      </a>
                      <?php if ($session->email != null) { ?>
          					<?php if($session->userId == $pp['user_id']) { ?>
                                  <a id="btn_player_off_<?php echo $pp["player_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $pp["player_id"];?>);">
                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $pp["player_name_short"];?>'s updates">
                            			</a>
                            			<a id="btn_player_on_<?php echo $pp["player_id"];?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $pp["player_id"];?>);">
                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $pp["player_name_short"];?>'s updates">
                            			</a>
                              <?php } else { ?>
                              	<a id="btn_player_on_<?php echo $pp["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $pp["player_id"];?>);">
                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $pp["player_name_short"];?>'s updates">
                            			</a>
                            			<a id="btn_player_off_<?php echo $pp["player_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $pp["player_id"];?>);">
                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $pp["player_name_short"];?>'s updates">
                            		</a>
                            <?php }  ?>
					<?php } else { ?>
					    <a id="btn_playerid_<?php echo $pp["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $pp["player_id"];?>);">
							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $pp["player_name_short"];?>'s updates">
						</a>
					<?php }  ?>	
					
                </p>

           <?php if($playerCounter==3){ $playerCounter = 0; ?>
              </div>
            <?php } ?>

              <?php }
              } ?>

                <p class="smatch1">
                    <a class="OrangeLink" href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">See More Featured Players</a>
                </p>
            </div>
      
   </div>
  <!--ENND - Brand New  Featured  Teams and Players -->

    <!--GoalFace Pulse-->
        <div class="img-shadow">
          <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              <h4 class="NoArrowLeft">GoalFace Pulse</h4>
              <span> 
                <a 	href="<?php echo Zend_Registry::get("contextPath"); ?>/community/showallactivititiesdetail" 	title="see more">More &raquo;</a> 
              </span>
            </div>
            <div class="BlueShaded DisplayDropdown">
              <div class="secondaryDropDown">Show: 
                <select id="activityId" 	class="slct" name="activityId">	
                  <option value="all" selected="selected">All Activity
                  </option>	
                  <option value="1">Friends
                  </option>	
                  <option value="2">Teams
                  </option>	
                  <option value="3">Players
                  </option>	
                  <option value="4">Leagues & Tournaments
                  </option>	
                  <option value="5">News
                  </option>
                </select> 
                <input style="display: inline;" type="submit" 	id="buttonActivity" value="Ok" class="submit">
              </div>
              <div class="JoinedDate">
                <a 	href="<?php echo Zend_Registry::get("contextPath"); ?>/community/showrssfeed" 	class="OrangeLink">Subscribe</a>
              </div>
            </div>
            <!--<div id="MapContainer">
                                            <img src="/public/images/mapanimation.gif" />
                                        </div>-->
            <div id="community">Loading...
            </div>
            <!--/<div class="HomeSecondColumnBottomOfMap">
                                            Profiles, Games, Teams, and Events near you and all over the world.
                                        </div>-->
            <div id="SeeMoreNews" class="SeeMoreNews">
              <a class="OrangeLink" 	href="<?php echo Zend_Registry::get("contextPath"); ?>/community/showallactivititiesdetail" 	title="See More Activities">See more pulse updates</a>
            </div>
          </div>
        </div>
        
        <div class="prof">
          <p class="mblack">
            <span class="black">Fan Profiles
            </span> 
            <span 	class="sm" id="menu6_more">                            	
              <?php if($session->email == null){ ?>                  				 
              <a href="javascript:loginModal();" 	title="GoalFace Fan Profiles">More »</a>                  			
              <?php }else{?>	                              	
              <a 	href="<?php echo $urlGen->getMainProfilesPage(true); ?>" 	title="GoalFace Fan Profiles">More »</a>                            
              <?php }?>	                            
            </span>
          </p>
          <form id="profilesHomeForm" style="display: inline;" 	name="profilesHomeForm" action="#" method="get">
            <p class="show">
              <label>Show:
              </label> 
              <select id="profilesSelect" 	name="search" class="most">	
                <option value="popular#mp">Most Popular
                </option>                                        
                <?php if($session->email != null) {?>                                        
                <option value="likeme#lm">Like 	Me
                </option>                                        
                <?php } ?>                                        
                <option value="active#ma">Most 	Active
                </option>	
                <option value="recently#ru">Recently Updated
                </option>	
                <option value="newest#nw">Newest
                </option>	
                <option value="online#onl">Online
                </option>
              </select> 
              <input style="display: inline;" type="submit" 	id="buttonProfile" value="Ok" class="submit">
            </p>
          </form>
          <div id="randomprofiles" class="nmatch">
          </div>
          <p class="modfooter">                        	
            <?php if($session->email == null){ ?>                        	
            <a class="orangelink" 	href="javascript:loginModal();">Browse</a> | 
            <a class="orangelink" 	href="javascript:loginModal();">Most Popular</a>                        	
            <?php }else{?>	                         	
            <a class="orangelink" 	href="<?php echo $urlGen->getMainProfilesPage(true); ?>">Browse</a> | 
            <a 	class="orangelink" 	href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles">Most Popular</a>                        	
            <?php }?>                        
          </p>
        </div>
      </div>
      <!--/SecondColumn-->
      <div class="ThirdColumn">
        <div id="topnews" class="img-shadow">
          <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              <h4 class="NoArrowLeft">Top News</h4>
              <!--<span>
                                                 <a href="<?php //echo $urlGen->getMainNewsPage(true); ?>" title="Football news from around the world">More &raquo;</a>
                                            </span>-->
            </div>
            <div id="TopNewsBodyId">
              <div class="BlueShaded DisplayDropdown">Show: 
                <select id="headLineId" 	class="slct" name="headLineId">	
                  <option value="5" selected="selected">5 Headlines
                  </option>	
                  <option value="10">10 Headlines
                  </option>	
                  <option value="15">15 Headlines
                  </option>
                </select> 
                <input style="display: inline;" type="submit" id="buttonNews" 	value="Ok" class="submit">
              </div>
              <div id="FeaturedNewsList">
                <div id="topnewsWorking">Updating...
                </div>
              </div>
              <!-- <div id="SeeMoreNews" class="SeeMoreNews">
                    	                            <a class="OrangeLink" href="<?php //echo $urlGen->getMainNewsPage(true); ?>" title="Football news from around the world">See more news from around the world</a>
                                              </div>-->
            </div>
          </div>
        </div>
        <!-- Competition Gallery --> 
        <!-- 			        <div class="featured1">
        					 	<p class="mblack">
        							<span class="black"><?php echo $this->compName; ?> Pictures</span>
        							 <span class="sm">
        			                   <a href="<?php echo $urlGen->getPhotosPageUrl(true); ?>" title="Football Photo Gallery">More &raquo;</a>
        			               </span>
        						</p> 
        				       	<p class="show">                           
        			                 <label>Show:</label>                          
        			                 <select id="slcgallery" name="slcgallery" class="most">
        			 						<option value="4" selected="selected">Display 4 Photos</option>
        			                        <option value="8">Display 8 Photos</option>
        			                  </select>
        			                  <input style="display:inline;" type="submit" id="buttonPhotos" value="Ok" class="submit">                 
        			            </p>
        			            
        			            <div id="PhotoGalleryList" class="imgscontent">
        			
        			            
        			            </div>
        			
        						<p class="modfooter"><a class="orangelink" href="<?php echo $urlGen->getPhotosPageUrl(true); ?>">See More Photos &raquo;</a></p> 
        				    </div> --> 
        <!-- Scoreboard -->
        <div id="scoreboard" class="img-shadow">
          <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              <h4 class="NoArrowLeft">Scoreboard</h4>
              <span> 
                <a 	href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" 	title="Football Score Board">More &raquo;</a> 
              </span>
            </div>
            <div class="WrapperForScoreTab" id="scoretabBodyId">
              <ul id="main_tabs" class="TabbedHomeNav">                            
                <?php if ($session->email != null) { ?>                                
                <li id="myScoresTabLi" 		style="font-size: 12px;">
                  <a id="myScoresTab" href="javascript:void(0)">My 	Scoreboard</a>
                </li>                            
                <?php }?>                                
                <li id="scoresTabLi" class="Selected" 		style="font-size: 12px;">
                  <a id="scoresTab" class="Selected" 		href="javascript:void(0)">Scores</a>
                </li>	
                <li id="schedulesTabLi" style="font-size: 12px;">
                  <a id="schedulesTab" 		href="javascript:void(0)">Schedules</a>
                </li>
              </ul><br class="ClearBoth" />
              <div id="ScoresSchedulesWrapperBox">
                <div id="tab_1" class="tabContent" style="display: none">
                  <div class="FeaturedNewsSort" id="MyScoresDateFilter">
                    <a 	href="javascript:void(0);" id="todayms" class="filterSelected">Today</a>| 
                    <a href="javascript:void(0);" id="l3ms">Last 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="l7ms">Last 7 Days</a> |
                  </div>
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult0">Loading..
                    </div>
                  </div>
                </div>
                <div id="tab_2" class="tabContent" style="">
                  <div class="FeaturedNewsSort" id="ScoresDateFilter">
                    <a 	href="javascript:void(0);" id="today" class="filterSelected">Today</a>| 
                    <a href="javascript:void(0);" id="l3">Last 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="l7">Last 7 Days</a> |
                  </div>
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult">Loading...
                    </div>
                  </div>
                </div>
                <div id="tab_3" class="tabContent" style="display: none;">
                  <div class="FeaturedNewsSort" id="SchedulesDateFilter">
                    <a 	href="javascript:void(0);" id="tomorrow" class="filterSelected">Tomorrow</a>| 
                    <a href="javascript:void(0);" id="n3">Next 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="n7">Next 7 Days</a> |
                  </div>
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult2">Loading..
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="SeeMoreNews">
              <a class="OrangeLink" 	href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" 	title="Live football scores from around the world">See all scores from around the world</a>
            </div>
          </div>
        </div>
        <!--/My Scoreboard-->
        <div id="myscoreboard" class="img-shadow closeDiv">
          <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              <div class="DownArrow" id="scoreboardArrowId">
              </div>
              <h4 class="WithArrowToLeft">Scoreboard</h4>
              <span> 
                <a 	href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" 	title="Football Score Board">See More &raquo;</a> 
              </span>
            </div>
            <div class="WrapperForScoreTab" id="scoretabBodyId">
              <div id="ScoresTabs">
                <ul style="height: 31px;">                                    
                  <?php if ($session->email != null) { ?>                                      
                  <li>
                  <a href="#tab1">
                    <span>My 	Scoreboard
                    </span></a>
                  </li>                                    
                  <?php }?>                                         
                  <li>
                  <a href="#tab2">
                    <span>Top 	Scores
                    </span></a>
                  </li>	
                  <li>
                  <a href="#tab3">
                    <span>Schedule
                    </span></a>
                  </li>
                </ul>
                <a class="trigger" id="customScoreBoard" href="#">Customize</a>                                    
                <?php if ($session->email != null) { ?>                                                            
                <div id="editscore" 	class="ScoresEdit scoresClosed">
                  <p>Select up to 20 regions and countries you'd like to see in your scoreboard. You can see all scores from around the world here.
                  </p>                                      
<?php foreach($this->countryList as $country) {
                                                foreach($this->userCompetitions as $countryCompetition){
                                                    $checked = "";
                                                    if($country["country_id"] == $countryCompetition["country_id"]){
                                                        $checked = 'checked';
                                                        break;
                                                    }
                                                }
                                                                  ?>                                              
                  <input type="checkbox" 	<?php echo $checked; ?> value="<?php echo $country["country_id"]; ?>" 	name="competition" id="country_
                  <?php echo $country["country_id"]; ?> " />
                  <label 	for="<?php echo $country["country_name"]; ?>">
                    <?php echo $country["country_name"]; ?>
                  </label>
                  <br>                                          
<?php
                                            $checked = '';
                                                            } ?>                                          
                  <input id="saveEditFavorites" 	class="submit" type="submit" value="Save" /> 
                  <input class="submit" 	type="button" value="Cancel" />
                </div>                                
                <?php }?>                               
                <?php if ($session->email != null) { ?>                                  
                <div id="tab1">
                  <div class="FeaturedNewsSort" id="MyScoresDateFilter">
                    <a href="#" 	id="todayms" class="filterSelected">Today</a> | 
                    <a 	href="javascript:void(0);" id="l3ms">Last 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="l7ms">Last 7 Days</a> |
                  </div>
                  <!--<div class="BlueShaded DisplayDropdown">hello</div>-->
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult0">Loading..
                    </div>
                  </div>
                </div>                              	
                <?php }?>                                                                
                <div id="tab2">
                  <div class="FeaturedNewsSort" id="ScoresDateFilter">
                    <a href="#" 	id="today" class="filterSelected">Today</a> | 
                    <a 	href="javascript:void(0);" id="l3">Last 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="l7">Last 7 Days</a> |
                  </div>
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult">Loading...
                    </div>
                  </div>
                </div>
                <div id="tab3">
                  <div class="FeaturedNewsSort" id="SchedulesDateFilter">
                    <a href="#" 	id="tomorrow" class="filterSelected">Tomorrow</a> | 
                    <a 	href="javascript:void(0);" id="n3">Next 3 Days</a> | 
                    <a 	href="javascript:void(0);" id="n7">Next 7 Days</a> |
                  </div>
                  <div id="ScorecardContainer">
                    <div id="scoreboardResult2">Loading..
                    </div>
                  </div>
                </div>
              </div>
              <div id="SeeMoreNews" class="SeeMoreNews">
                <a class="OrangeLink" 	href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" 	title="Live football scores from around the world">See all scores from around the world</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ThirdColumn-->
    </div>
    <!--was not there initially, added-->
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>