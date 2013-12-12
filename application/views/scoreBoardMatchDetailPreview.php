  <?php $session = new Zend_Session_Namespace('userSession');?>
  <?php require_once 'seourlgen.php'; ?>
 <?php $urlGen = new SeoUrlGen(); ?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $offset = $config->time->offset->daylight;
 ?> 

<script type="text/javascript">
  		
        jQuery(function() {

        	var date = null;
            var date_time_os = null;
            jQuery("span[id^='feature_matchDate_page']").each(function() {
                var date_time = jQuery(this).text();
              date = new Date(date_time);
              date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
                jQuery(this).text(formatDate(date_time_os, 'MMM dd,yyyy'));
              });
            
             jQuery("span[id^='feature_matchHour_page']").each(function() {
                var date_time = jQuery(this).text();
              date = new Date(date_time);
              date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
                jQuery(this).text(formatDate(date_time_os, 'HH:mm'));
              
              });
            
             // var oldtitle = jQuery(this).attr('title');
              //jQuery(this).attr('title',oldtitle + formatDate(date_time_os, 'MMM dd,yyyy') ); 

       

        	callRandonProfiles();

        	jQuery('#addtofavoritematchtrigger').click(function(){
   			 addfavoritematch();
   		  	});
	   		jQuery('#removefromfavoritematchtrigger').click(function(){
	   			 removefavoritematch();
	   	 	});	 

	   		var timezone = calculate_time_zone(); 
			 //var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showrelatedmatchesbycompetition/timezone/'+timezone+'/countryId/<?php echo $this->match[0]["country_id"]; ?>/competitionId/<?php echo $this->match[0]["competition_id"]; ?>/matchId/<?php echo $this->matchId;?>/date/';
	   		var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showrelatedmatchesbycompetition/timezone/'+timezone+'/countryId/<?php echo $this->match[0]["country_id"]; ?>/competitionId/<?php echo $this->match[0]["competition_id"]; ?>/matchId/<?php echo $this->matchId;?>/date/';
			//load the first list by default in scores	
		 	jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
		 	var initDateTime = formatDate(new Date('<?php echo $this->currentDate;?>'),'yyyy-MM-dd HH:mm:ss');
			var endDateTime = formatDate(new Date('<?php echo $this->nextDay;?>'),'yyyy-MM-dd HH:mm:ss');
			<?php if($this->othermatches ==null){ ?>
				url = escape(urlBase + 'today/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime);  
				loadScoresTab('scoresTabLi', url, 'scoreboardResult' , 'ScoresDateFilter'  , 'tab_2' );
	    	 	jQuery('#tab_3').hide();
		 	<? } /* else { ?>
			 	url = escape(urlBase + 'week/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime); 
	    		loadScoresTab('schedulesTabLi', url, 'scoreboardResult2' , 'SchedulesDateFilter' , 'tab_3' );
	    	 	jQuery('#tab_2').hide();
		 	<? } */?>

			jQuery('#scoresTab').click(function() {
				url = escape(urlBase + 'today/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime);  
	    		loadScoresTab('scoresTabLi', url, 'scoreboardResult' , 'ScoresDateFilter'  , 'tab_2' );
	    	 	jQuery('#tab_3').hide();
	    	});
	    	jQuery('#schedulesTab').click(function() {
	    		url = escape(urlBase + 'week/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime); 
	    		loadScoresTab('schedulesTabLi', url, 'scoreboardResult2' , 'SchedulesDateFilter' , 'tab_3' );
	    	 	jQuery('#tab_2').hide();
	    	});
			
	      });
	
  		
        function loadScoresTab(tabid, url, container , filter  , tabtoshow ){

        	jQuery('li.Selected').removeClass('Selected');
            jQuery('#'+tabid).addClass('Selected');
            jQuery('#'+ filter+' a').removeClass('filterSelected');
         	jQuery('#'+container).html('');
            jQuery('#'+container).html("<div class='ajaxload widget'></div>");
        	jQuery('#'+tabtoshow).show();
        	jQuery('#'+container).load(url );
        	
        }
  		
    	//Load Random Profiles
  	  function callRandonProfiles()
  	  {
  	        jQuery.ajax({
  	                      method: 'get',
  	                      url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom/teamId/<?php echo $this->match[0]["team_a"]; ?>/teamId2/<?php echo $this->match[0]["team_b"]; ?>',
  	                      dataType : 'text',
  	                      success: function (text) {
  	            									 jQuery('#randomprofiles').html(text);
  	            									 }
  	                   });

  	  }
  

  	function addfavoritematch(){

		 jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalFavoriteTitleId').html('Add favorite game?');
		 jQuery('#dataText1').html('<?php echo $this->match[0]["t1"];?> <?php echo $this->match[0]["fs_team_a"];?> - <?php echo $this->match[0]["fs_team_b"];?>  <?php echo $this->match[0]["t2"];?>');
		 jQuery('#title1Id').html('Competition:');
		 var competitionUrl = null;
		 <?php if($this->competitionType == 'club') { ?>
			competitionUrl = '<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>';
 		<?php } else {?>
 			competitionUrl = '<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>';
 		<?php } ?>
		 jQuery('#dataText2').html("<?php echo $this->competitionName; ?>");
		 jQuery('#dataText2').attr('href',competitionUrl);
	
		 jQuery('#title2Id').html('Date:');
		 jQuery('#dataText3').html("<?php echo date ('F j , Y' , strtotime($this->match[0]['match_date']));?>");
		 //jQuery('#title3Id').html('Stadium:');
		 //jQuery('#dataText4').html('<?php echo $this->match[0]["team_stadium"]!='NULL'?$this->match[0]["team_stadium"]:"N/A";?>');
		
		 
		 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoritematchtrigger', onHide: closeModal });
		 jQuery('#addFavoriteModal').jqmShow();
		 
		 var favoriteImage = null;

		 jQuery('#favoriteImageSrcId').attr('src','');

		 var matchId = '<?php echo $this->match[0]["match_id"];?>';	
		 
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/addfavorite',
				data : ({matchId: matchId}),
				success: function(data){
					jQuery('#modalBodyResponseId').html('Game <?php echo $this->teamname;?> has been added to your favorite games.');
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
			})
			
		 });	
	}	

	function removefavoritematch(){

		 jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalFavoriteTitleId').html('Remove favorite game?');
		 jQuery('#dataText1').html('<?php echo $this->match[0]["t1"];?> <?php echo $this->match[0]["fs_team_a"];?> - <?php echo $this->match[0]["fs_team_b"];?>  <?php echo $this->match[0]["t2"];?>');
		 jQuery('#title1Id').html('Competition:');
		 var competitionUrl = null;
		 <?php if($this->competitionType == 'club') { ?>
			competitionUrl = '<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>';
		<?php } else {?>
			competitionUrl = '<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>';
		<?php } ?>
		 jQuery('#dataText2').html("<?php echo $this->competitionName; ?>");
		 jQuery('#dataText2').attr('href',competitionUrl);
	
		 jQuery('#title2Id').html('Date:');
		 jQuery('#dataText3').html("<?php echo date ('F j , Y' , strtotime($this->match[0]['match_date']));?>");
		 //jQuery('#title3Id').html('Stadium:');
		 //jQuery('#dataText4').html('<?php echo $this->match[0]["team_stadium"]!='NULL'?$this->match[0]["team_stadium"]:"N/A";?>');
		
		 
		 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoritematchtrigger', onHide: closeModal });
		 jQuery('#addFavoriteModal').jqmShow();
		 
		 var favoriteImage = null;

		 jQuery('#favoriteImageSrcId').attr('src','');

		 var matchId = '<?php echo $this->match[0]["match_id"];?>';	
		 
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/removefavorite',
				data : ({id: matchId}),
				success: function(data){
					jQuery('#modalBodyResponseId').html('Game <?php echo $this->teamname;?> has been removed from your favorite games.');
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#remove').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#favorite').removeClass('ScoresClosed').addClass('Display');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
			})
			
		 });	
	}	
		
				
		
</script>

  <div id="ContentWrapper" class="ScoreboardMatchDetail">

     <div class="flags">
        <span class="flagtitle Noflag" style="background-image:url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/flags/32x32/<?php echo $this->countryCodeIso; ?>.png)">
            <a href="#">
                <?php echo $this->match[0]["competition_name"];?>&nbsp;-&nbsp;<?php echo $this->match[0]["t1"];?> vs. <?php echo $this->match[0]["t2"];?>&nbsp; <?php //echo date ('F j, Y' , strtotime($this->match[0]["match_date"]));?>
            </a>
        </span>

         <?php if($this->isFavorite == 'false') { ?>
               <?php if($session->email != null){ ?>
                <span id="favorite" class="add">
                    <a id="addtofavoritematchtrigger" href="#">Add to Favorites</a>
                </span>
                 <span id="remove" class="remove" style="display:none">
                    <a id="removefromfavoritematchtrigger" href="#">Remove from Favorites</a>
                </span>
               <?php } else {?>
                 <span id="favorite" class="add">
                    <a id="addtofavoriteplayerNonLoggedtrigger" onclick="loginModal()" href="#">Add to Favorites</a>
                </span>
               <?php } ?>
           <?php }else {?>
               <span id="favorite" class="add" style="display:none">
                    <a id="addtofavoritematchtrigger" href="#">Add to Favorites</a>
                </span>
                 <span id="remove" class="remove">
                    <a id="removefromfavoritematchtrigger" href="#">Remove from Favorites</a>
                </span>
           <?php }?>
           
          <span class="tweeterbutton">
              <a href="http://twitter.com/share" class="twitter-share-button" style="padding-bottom:5px;width:100px;" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
          </span>
          
          <span class="tweeterbutton">
          <!-- Place this tag in your head or just before your close body tag -->
			     <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			
    			<!-- Place this tag where you want the +1 button to render -->
    			<g:plusone size="medium"></g:plusone>
    		</span>
		
    		<div class="fbbutton">
    
        			<div class="fb-like" data-href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" data-send="false" data-layout="button_count" data-width="45" data-show-faces="false"></div>
        			<div id="fb-root"></div>
        			<script>(function(d, s, id) {
        			var js, fjs = d.getElementsByTagName(s)[0];
        			if (d.getElementById(id)) return;
        			js = d.createElement(s); js.id = id;
        			js.src = "//connect.facebook.net/eu_EU/all.js#xfbml=1";
        			fjs.parentNode.insertBefore(js, fjs);
        			}(document, 'script', 'facebook-jssdk'));</script>
        </div>
        
        <span class="twitter">
            <!--START PIN BUTTON-->
				    <a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ); ?>&media=<?php echo urlencode($this->imagefacebook ); ?>&description=<?php echo $this->title;?>" class="pin-it-button" count-layout="none">
              <img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" />
            </a>
            <!--END PIN BUTTON-->
		      </span>	

    </div>
  
     <div class="FirstColumn">
                <?php echo $this->render('include/topleftbanner.php')?>
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

                    <?php } ?>
                    

                    <!--navigation left nav-->
                    <div id="leftnav" class="img-shadow">
                         <?php //echo $this->render('include/navigationCompetitionMatchDetail.php');?>
                         <?php echo $this->render('include/navigationCompetitionNew2.php');?>
                    </div>

     </div><!--/FirstColumn-->
     
     <div class="SecondColumn">
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                  <h4 class="NoArrowLeft">match details</h4>
                            </div> 
                            <div id="MatchDetailsTop">
                                <div><strong>Competition:</strong>
                                
                                <?php if($this->competitionType == 'club') { ?>
		                                       <a class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>"><?php echo $this->competitionName; ?></a>
                                <?php } else {?>
                                       <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>"><?php echo $this->competitionName; ?></a>
                                <?php } ?>
                               
                                
                                </div>
                             
                                <div>
			                       <strong>Match Date:</strong>&nbsp;
			                        <span id="feature_matchDate_page">	
			                        	 <?php echo date('Y/m/d H:i:s',strtotime($this->match[0]["match_date"]." ".$this->match[0]['match_time'])) ?>
			                        </span>
			                    </div>
			
			                     <div>
			                        <strong>Match Time:</strong>&nbsp;
			                        <span id="feature_matchHour_page">	
			                              <?php echo date('Y/m/d H:i:s',strtotime($this->match[0]["match_date"]." ".$this->match[0]['match_time'])) ?>
			                        </span>
			                    </div>
                                
                              <div>
                              <strong>Stadium:</strong>
                                <?php if($this->match[0]["venue_id"] != null AND $this->match[0]["venue_id"] != 1000 ) { ?>
                                    <?php echo $this->match[0]["venue_name"] . " (" . $this->match[0]["venue_city"] . ")"; ?>
                                <?php } else {?>
                                   <?php echo $this->match[0]["team_stadium"]!='NULL'?$this->match[0]["team_stadium"]:"N/A";?>
                                <?php } ?>
                              </div>
                            </div>
                            <div id="MatchDetailsLogosScores">
                                
                                <div>
                                    <a border="0" href="<?php echo $urlGen->getClubMasterProfileUrl($this->match[0]["team_a"], $this->match[0]["t1seoname"], True); ?>">
                                        <?php
                                            $config = Zend_Registry::get ( 'config' );
                                            $path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_a"].".gif" ;

                                            if (file_exists($path_team_logos))
                                            {  ?>
                                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_a"]?>.gif"/>

                                            <?php } else {  ?>

                                             <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>

                                        <?php }   ?>
                                    </a>
					               <span> 
                                      <h3>
                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->match[0]["team_a"], $this->match[0]["t1seoname"], True); ?>">
                                            <?php echo $this->match[0]["t1"];?>
                                        </a>
                                     </h3>
                                   </span>
                                   <span> 
		                                <?php if($this->competitionType == 'club') { ?>
		                                       <a class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                    <?php echo $this->competitionName; ?>
                                               </a>
		                                <?php } else {?>
		                                       <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                    <?php echo $this->competitionName; ?>
                                               </a>
		                                <?php } ?>                    
                                   </span><BR>
                                   <span>
                                     <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->match[0]["country_team_a"],$this->match[0]["country_id_team_a"],true);?>">
                                        <?php echo $this->match[0]["country_team_a"];?>
                                     </a>
                                   </span>
                                  </div>
                                

                                <h2>VS</h2>
                                
                                <div>
                                    <a border="0" href="<?php echo $urlGen->getClubMasterProfileUrl($this->match[0]["team_b"], $this->match[0]["t2seoname"], True); ?>">
                                        <?php
                                            $config = Zend_Registry::get ( 'config' );
                                            $path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_b"].".gif" ;

                                            if (file_exists($path_team_logos))
                                            {  ?>
                                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_b"]?>.gif"/>

                                            <?php } else {  ?>

                                             <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>

                                        <?php }   ?>
                                    </a>
                                    
                                      <span>
                                        <h3><a href="<?php echo $urlGen->getClubMasterProfileUrl($this->match[0]["team_b"], $this->match[0]["t2seoname"], True); ?>"><?php echo $this->match[0]["t2"];?></a></h3>
                                      </span>
                                      <span> 
                                       <?php if($this->competitionType == 'club') { ?>
		                                       <a class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>"><?php echo $this->competitionName; ?></a>
		                                <?php } else {?>
		                                       <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>"><?php echo $this->competitionName; ?></a>
		                                <?php } ?>
		                                
                                      </span><BR>  
                                      <span> 
                                      <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->match[0]["country_team_b"],$this->match[0]["country_id_team_b"],true);?>"><?php echo $this->match[0]["country_team_b"];?></a>
                                      </span>

                                </div>
                          
                            </div>

                            <div class="ButtonWrapper">
                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/matchid/<?php echo $this->match[0]['match_id'];?>">Comparisons</a>
                            </div>




                            <!-- Current Form -->
                           <?php if( $this->competitionFormat == 'Domestic league') { ?>
                                <div class="DropShadowHeader BrownGradientForDropShadowHeader">

                                  <h4 class="CurrentFormMatchDetail NoArrowLeft">Current Form </h4>

                                </div>

                                <div id="MatchDetailsStats">

                                    <div class="MatchDetailsStatsWrapper">
                                    <?php if ($this->competitionFormat == 'Domestic League'){?>
                                        <div><?php echo $this->positionA; ?></div>
                                        <div class="StatsLabel">League Standing</div>
                                        <div><?php echo $this->positionB; ?></div>
                                     <?php }  ?> 
                                    </div>
                                     <div class="MatchDetailsStatsWrapper">
                                        <div>
                                          <?php foreach ( $this->last5teamA as $winnerA ) {
                                                  if ($this->match[0]["team_a"] == $winnerA['match_winner']) {
                                                      echo "<span class='win'>W</span>";
                                                  } elseif (999 == $winnerA['match_winner']) {
                                                      echo "<span class='draw'>D</span>";
                                                  } else {
                                                      echo "<span class='lose'>L</span>";
                                                  }
                                                }  ?> 

                                       </div>
                                       <div class="StatsLabel">Last 5 Matches</div>
                                        <div>
                                              <?php foreach ( $this->last5teamB as $winnerB ) {
                                            if ($this->match[0]["team_b"] == $winnerB['match_winner']) {
                                                echo "<span class='win'>W</span>";
                                            } elseif (999 == $winnerB['match_winner']) {
                                                echo "<span class='draw'>D</span>";
                                            } else {
                                                echo "<span class='lose'>L</span>";
                                            }
                                          }  ?> 
                                         
                                         
                                        </div>

                                    </div>
                                    

                                   <div class="MatchDetailsStatsWrapper"></div>
                                </div>
                        <?php } ?>

                            
                          <!-- Head to Head matchups -->
                          <div class="DropShadowHeader BrownGradientForDropShadowHeader">
                              <h4 class="HeadToHeadMatchDetail">Head-to-Head Match Ups</h4>
                               <span>
                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/matchid/<?php echo $this->match[0]['match_id'];?>">See More &raquo;</a>                                    
                                </span>
                            </div>
							<div id="leaguetables">
								 <table id="headtohead" width="355px;" cellspacing="0" cellpadding="0" border="0">
                                    <tbody> 
                                    	<tr class="oddmatch">
                                    		<td class="wins_left"><?php echo $this->teamAwins;?></td>
                                    		<td class="wins_middle_even">Wins</td>
                                    		<td class="wins_right"><?php echo $this->teamBwins;?></td>
                                    	</tr>
                                    	<tr class="even">
                                    		<td class="wins_left"><?php echo $this->teamAlosses;?></td>
                                    		<td class="wins_middle_odd">Losses</td>
                                    		<td class="wins_right"><?php echo $this->teamBlosses;?></td>
                                    	</tr>
                                    	<tr class="oddmatch">
                                    		<td class="wins_left"><?php echo $this->teamties;?></td>
                                    		<td class="wins_middle_even">Draws</td>
                                    		<td class="wins_right"><?php echo $this->teamties;?></td>
                                    	</tr>
                                    	<tr class="even">
                                    		<td class="wins_left"><?php echo $this->teamAclean;?></td>
                                    		<td class="wins_middle_odd">Clean Sheets</td>
                                    		<td class="wins_right"><?php echo $this->teamBclean;?></td>
                                    	</tr>
                                    </tbody>
                                 </table>
							</div>
							
                            <div id="head2headmatches">
                            <?php //echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
                                <table id="headtohead" width="100%" cellspacing="0" cellpadding="1" border="0">
                                    <tbody>                                  
                                     <?php
                                        $counter = 1;
                                        foreach ($this->head2headList as $head){
                                            if ($counter == 10) {
                                                break;
                                            }

                                             if($counter % 2 == 1) {
                                                $style = 'oddmatch';
                                                //$hoverstyle = $hovercolor1;
                                             }else{
                                                $style = 'even';
                                            //$hoverstyle = $hovercolor2;
                                             }
                                     ?>
                                    
                                    <tr class="<?php echo $style; ?>">
                                        <td class="small"><?php echo date ('M d, Y' , strtotime($head['mdate'])); ?></td>
                                        
                                        <td class="small">
                                            <?php echo $head["competition_name"] ?>
                                        </td>

                                        <td class="small">
                                            <a href="<?php echo $urlGen->getMatchPageUrl($head["competition_name"], $head["teama"], $head["teamb"], $head["matchid"], true);?>">
                                            <?php echo $this->escape($head["teama"]);?>&nbsp;<?php echo $this->escape($head["fs_team_a"]);?>&nbsp;-&nbsp;<?php echo $this->escape($head["fs_team_b"]);?>&nbsp;<?php echo $this->escape($head["teamb"]);?>
                                            </a>
                                           </td>
                                    </tr>

                                    <?php $counter = $counter + 1; } ?>

                                </table>
                             </div>
                        	 <div class="SeeMoreNews">
	                       		<a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/matchid/<?php echo $this->match[0]['match_id'];?>">See More Head-to-Head Results </a>
                            </div>
                        </div>
                 </div>
     </div><!--/SecondColumn-->
     
     <div class="ThirdColumn">
     
		     
           <!-- Scoreboard --> 
                  
                <?php if ($this->competitionId == 25) { ?>     
                 <div id="scoreboard" class="img-shadow" style="margin-top: -10px;">          
          			<div class="" style="float: left; padding:0px;border:none;">
                         <a href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/european-championships_25/" title="Subscriptions and Alers">                          
                             <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/BannersEuro2.png" style="margin-top:10px;"/>
                      	</a>
                    </div>
                  </div>
                 <?php } ?>
                  
                  <div id="scoreboard" class="img-shadow">
	                    <div class="WrapperForDropShadow" style="width: 358px;">
	                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
	                           
	                             <h4 class="NoArrowLeft">Related Matches</h4>
	                             <span>
	                                 <a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="Football Score Board">See More &raquo;</a>
	                            </span>
	                        </div>
	                        <div class="WrapperForScoreTab" id="competitionsScoresBodyId">
	                            <ul id="main_tabs" class="TabbedHomeNav">
	                                <li id="scoresTabLi"  class="Selected" style="font-size:12px;">
	                                    <a id="scoresTab" class="Selected" href="javascript:void(0)">Scores</a>
	                                </li>
	
	                                <li id="schedulesTabLi" style="font-size:12px;">
	                                    <a id="schedulesTab" href="javascript:void(0)">Schedules</a>
	                                </li>
	                            </ul>
	                            <br class="ClearBoth"/>
	                            <div id="ScoresSchedulesWrapper" style="padding-top:15px;width:325px;margin-left:2px;border-left:0px;border-right:0px;margin-right:2px;border-bottom:0px;">
	                                <div id="tab_1" class="tabContent" style="display:none">
	                                    
	                                    <div class="FeaturedNewsSort" id="MyScoresDateFilter">
	                                       <a href="#" id="todayms" class="filterSelected">Today</a> |
	                                       <a href="javascript:void(0);" id="l3ms">Last 3 Days</a> |
	                                       <a href="javascript:void(0);" id="l7ms">Last 7 Days</a> |
									                    </div>  
	
	                                        <div id="scoreboardResult0" class="Scores">
	
	                                            Loading..
	
	                                        </div>
	                               
	                                </div>
	                                  <div id="tab_2" class="tabContent" style="">
	
		                            		<div id="scoreboardResult" class="Scores">
		                            		
		                            			Loading...
		                            		
		                            		</div>    
		                         
	                                </div>
	                                   <div id="tab_3" class="tabContent" style="display:none;">
	                                    		 
	                           		
	                                      <div id="scoreboardResult2" class="Scores">
	
	                                          Loading..
	
	                                      </div>
	                              
	                                </div>
	                            </div>
	                        </div>
 
                </div>
                </div><!-- End Scoreboard -->
                
                  <!-- Profiles --> 
		        <div class="prof">
		              <p class="mblack">
		               <span class="black">Fan Profiles</span>
		               <span class="sm" id="menu6_more">
		                   <?php if($session->email == null){ ?>
							<a href="javascript:loginModal();" title="GoalFace Fan Profiles">More »</a>
							<?php } else { ?>
				           		<a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles" title="GoalFace Fan Profiles">More »</a>
					  		<?php } ?>
		               </span>
		              </p>
		                         
		              <div id="randomprofiles" class="nmatch">
		                     
					</div>
					 <?php if($session->email == null){ ?>
						<p class="modfooter"><a class="orangelink" href="javascript:loginModal();">See More Fans</a></p>
					<?php } else { ?>
		           		<p class="modfooter"><a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles">See more fans</a></p>
		            <?php }  ?>         
		       </div>
     

                    

                
     </div><!--/ThirdColumn-->

  </div>
  <!--/ContentWrapper-->
