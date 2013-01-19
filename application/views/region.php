<?php require_once 'seourlgen.php'; 
 	$urlGen = new SeoUrlGen();
 	$session = new Zend_Session_Namespace('userSession');
 	?>
     <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 

<script type="text/JavaScript">

jQuery(document).ready(function() {

    //setup tabs - featured players and teams
    jQuery('#menu1content,#menu2content').hide();
    jQuery('#menu1content').show();
    jQuery('#menu1').addClass('active');
    jQuery('#featTab ul li').click(function(){
       // var thisClass = this.className;
        jQuery('#menu1content,#menu2content').hide();
        tab_id = jQuery(this).attr('id');
        //show div content
        jQuery('#' + tab_id + 'content').show();
        //show see more header link
        jQuery('#menu1_more,#menu2_more').hide();
        jQuery('#' + tab_id + '_more').show();
        jQuery('#menu1,#menu2').removeClass('active');
        jQuery(this).addClass('active');
   });

    var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboardbyregion';
	//load the first list by default in scores	
 	jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
 	//url = urlBase + 'today'; 
 	var timezone = calculate_time_zone();
 	jQuery('#scoreboardResult').load(urlBase ,{name :'<?php echo $this->regionName; ?>' , date:'today',timezone : timezone, initDateTime : initDateTime, endDateTime : endDateTime } );
	

	
 	jQuery('#scoresTab').click(function() {
		loadScoresTab('scoresTabLi', urlBase, 'today', 'scoreboardResult' , 'ScoresDateFilter' ,'today' , 'tab_2' , '<?php echo $this->regionName; ?>');
	 	jQuery('#tab_3').hide();
	});
	jQuery('#schedulesTab').click(function() {
		loadScoresTab('schedulesTabLi', urlBase, 'tomorrow', 'scoreboardResult2' , 'SchedulesDateFilter' ,'tomorrow' , 'tab_3' , '<?php echo $this->regionName; ?>');
	 	jQuery('#tab_2').hide();
	});

	//scores
	jQuery('#today').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'today','scoreboardResult' ,'ScoresDateFilter','<?php echo $this->regionName; ?>');	
	 });
	jQuery('#l3').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , '-3' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->regionName; ?>');			
     });
	jQuery('#l7').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'last' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->regionName; ?>');			
     });
     //schedules
	jQuery('#tomorrow').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'tomorrow' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->regionName; ?>');			
     });
	jQuery('#n3').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , '3' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->regionName; ?>');			
     });
	jQuery('#n7').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'week' ,'scoreboardResult2' ,'SchedulesDateFilter' ,'<?php echo $this->regionName; ?>');			
     });
 	

	showHideDivBox('regionScoresId','regionScoresBodyId');
});

function loadScoresTab(tabid, url, date, container , filter ,defaultsearchid , tabtoshow , regionName){
	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	jQuery('li.Selected').removeClass('Selected');
    jQuery('#'+tabid).addClass('Selected');
    jQuery('#'+ filter+' a').removeClass('filterSelected');
 	jQuery('#'+defaultsearchid).addClass('filterSelected');
    jQuery('#'+container).html('');
    jQuery('#'+container).html("<div class='ajaxload widget'></div>");
	jQuery('#'+tabtoshow).show();
	jQuery('#'+container).load(url ,{name :regionName , date:date , initDateTime : initDateTime, endDateTime : endDateTime} );
	
}

function loadScoreBoardByTimeFrame(id, url, date, container , filter ,regionName)
{
	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');	
	jQuery('#'+ filter+' a').removeClass('filterSelected');
    jQuery('#' + id).addClass('filterSelected');
	jQuery('#' + container).html("<div class='ajaxload widget'></div>"); 			
	jQuery('#' + container).load(url , {date : date , name :regionName ,initDateTime : initDateTime, endDateTime : endDateTime});
}

function addGoalShout(){

    var commentText = jQuery('#commentGoalShoutId').val();
    if(jQuery.trim(commentText) == ''){
        jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
        return;
    }
    var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout';

    var commentType = 6;
    var idtocomment = '<?php echo $this->regionId; ?>';
    var screennametocomment = '<?php echo $session->screenName;?>';
    var regiongroup = '<?php echo $this->regiongroupid;?>';
    
    jQuery('#goalshoutId').load(url ,{regiongroup : regiongroup , commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment , comment : commentText});
    jQuery('#commentGoalShoutId').val('');

}
 	

	


</script>

		
<div id="ContentWrapper">

    <p class="flags">
        <span style="background-image: url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/regions/<?php echo $this->regionGroupId; ?>.png);" class="flagtitle">
             <?php echo $this->regionalHeading; ?>
        </span>
    </p>
  
  

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
     
                    <?php } ?>

					

        </div><!--/FirstColumn-->
                
        <div class="SecondColumn">
  
             <!--New  Featured  Teams and Players -->
		     <div class="prof">
		          <p class="mblack">
		              <span class="black">Featured Teams &amp; Players</span>
		              <span id="menu1_more" class="sm">
		              	<a href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">More &raquo;</a>
		              </span>
		              <span id="menu2_more" class="sm">
		              	<a href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">More &raquo;</a>
		              </span>
		          </p>
		          <div id="featTab" class="nxt">
		              <ul>
		                  <li id="menu1"><a href="javascript:void(0);">Teams</a></li>
		                  <li id="menu2"><a href="javascript:void(0);">Players</a></li>
		              </ul>
		          </div>
		          
                  <!--Featured  Teams Content -->
                  <div class="nmatch" id="menu1content">
			          <?php
			              $config = Zend_Registry::get ( 'config' );
			              $teamCounter = 0;
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
					              </p>
					
					           <?php if($teamCounter==3){ $teamCounter = 0; ?>
					           
					              </div>
					            <?php } ?>
			
			              <?php } ?>
			
			                <p class="smatch1">
			                    <a class="OrangeLink" href="">See More Featured Teams</a>
			                </p>
			            </div>
			            
			           <div class="nmatch" id="menu2content">
                
		                   		<?php
					              $config = Zend_Registry::get ( 'config' );
					              $playerCounter = 0;
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
				                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $pp["player_id"]; ?>.jpg"alt="<?php echo $pp["player_common_name"];?>"/>
				                                   <?php } else  { ?>
				                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/Player1Text80.gif" alt="<?php echo $pp["player_common_name"];?>"/>
				                                  <?php } ?>
					                      </a>
					                      <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_name_short"] ?>">
					                                <?php echo $pp["player_name_short"];?>
					                      </a>
					                </p>
					
					           <?php if($playerCounter==3){ $playerCounter = 0; ?>
					              </div>
					            <?php } ?>
					
					              <?php } ?>

                              <p class="smatch1">
                              	<a class="OrangeLink" href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">See More Featured Players</a>
                             </p>
                             
                        </div>

              </div>
 
                    <!-- Competitions --> 
                    <div id="regioncompetition" class="img-shadow">
                       	 <div class="WrapperForDropShadow">
						    <div class="DropShadowHeader BlueGradientForDropShadowHeader">
						        <h4 class="NoArrowLeft"><?php echo $this->regionName; ?> - Leagues and Tournaments</h4>
						    </div>
    						<div id="compList">
    							<?php echo $this->render('competitionsByRegion.php');?>
    						</div>
  						</div>
                    </div>
                                     
        </div><!--/SecondColumn-->
					
					 
        <div class="ThirdColumn">
          <!-- Top Scorers -->

          <!-- End Top Scorers --> 
                    
          <!-- Scoreboard --> 
                 <div id="scoreboard" class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                          
                            <h4 class="NoArrowLeft"><?php echo $this->regionName;?> Scores & Schedules</h4>
                             <span>
                                 <a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="Football Score Board">See More &raquo;</a>
                            </span>
                        </div>
                        <div class="WrapperForScoreTab" id="scoretabBodyId">
                            <ul id="main_tabs" class="TabbedHomeNav">
                                <li id="scoresTabLi"  class="Selected" style="font-size:12px;">
                                    <a id="scoresTab" class="Selected" href="javascript:void(0)">Top Scores</a>
                                </li>

                                <li id="schedulesTabLi" style="font-size:12px;">
                                    <a id="schedulesTab" href="javascript:void(0)">Schedules</a>
                                </li>
                            </ul>
                            <br class="ClearBoth"/>
                            <div id="ScoresSchedulesWrapperBox">
                                <div id="tab_1" class="tabContent" style="display:none">
                                    
                                    <div class="FeaturedNewsSort" id="MyScoresDateFilter">
                                       <a href="#" id="todayms" class="filterSelected">Today</a> |
                                       <a href="javascript:void(0);" id="l3ms">Last 3 Days</a> |
                                       <a href="javascript:void(0);" id="l7ms">Last 7 Days</a> |
								  </div>  
								                  <!--<div class="BlueShaded DisplayDropdown">hello</div>-->
                             	  <div id="ScorecardContainer">

                                        <div id="scoreboardResult0">

                                            Loading..

                                        </div>
                                    </div>
                                </div>
                                  <div id="tab_2" class="tabContent" style="">
                                    
	                                <div class="FeaturedNewsSort" id="ScoresDateFilter">
	                                   <a href="javascript:void(0);" id="today" class="filterSelected">Today</a> |
	                                   <a href="javascript:void(0);" id="l3">Last 3 Days</a> |
	                                   <a href="javascript:void(0);" id="l7">Last 7 Days</a> |
	                                </div>
	
	                             	<div id="ScorecardContainer">
	                            		<div id="scoreboardResult">
	                            		
	                            			Loading...
	                            		
	                            		</div>    
	                                </div>
                                </div>
                                   <div id="tab_3" class="tabContent" style="display:none;">
                                    <div class="FeaturedNewsSort" id="SchedulesDateFilter">
                                   <a href="javascript:void(0);" id="tomorrow" class="filterSelected">Tomorrow</a> |
                                   <a href="javascript:void(0);" id="n3">Next 3 Days</a> |
                                   <a href="javascript:void(0);" id="n7">Next 7 Days</a> |
								</div>  				 
                           		<div id="ScorecardContainer">
                                      <div id="scoreboardResult2">

                                          Loading..

                                      </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="SeeMoreNews">
                                    <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/<?php echo strtolower($this->regionName);?>/<?php echo $this->regionId;?>">See More Scores and Schedules</a>
                         </div>    
                     </div>
                </div>
                
		       <!-- <div id="goalshoutId" class="img-shadow">
		            <?php //echo $this->partial('scripts/goalshouttemplate.phtml',array('totalGoalShouts'=>$this->totalGoalShouts, 'comments'=>$this->comments ,'elementid'=>$this->regiongroupid ,'typeofcomment'=>Constants::$_COMMENT_REGION ));?>
		        </div>--> 
        
     </div><!--/ThirdColumn--> 
</div>
