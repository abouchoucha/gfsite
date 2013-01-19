<?php require_once 'Common.php'; $common = new Common();?>
<?php require_once 'seourlgen.php'; 
	$urlGen = new SeoUrlGen();
  require_once 'urlGenHelper.php';  
  $urlGenHelper = new UrlGenHelper();
  $session = new Zend_Session_Namespace ( 'userSession' );
  ?>
  <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 <script type="text/JavaScript">
 
 jQuery(document).ready(function() {

     initleague = '<?php echo $this->leagueid;?>';
    // Load first time page loads withe defaul table for top 1 league
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitiontablefb/roundid/<?php echo $this->roundId;?>/fb/no';
	 jQuery('#standingscontent').html("<div class='ajaxloadtable'></div>"); 
	 jQuery('#standingscontent').load(url);

	//Media Tabs 
     jQuery('#menu14content,#menu16content').hide();
     jQuery('#menu16content').show();
     jQuery('#menu16').addClass('active');
     jQuery('#mediaTabs ul li').click(function(){
         jQuery('#menu14content,#menu16content').hide();
         tab_id = jQuery(this).attr('id');
         //show div content
         jQuery('#' + tab_id + 'content').show();
         jQuery('#menu14,#menu16').removeClass('active');
         jQuery(this).addClass('active');
     });

	 
	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboard/date/';
 		
	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showmatchesbycountry';

	//load the first list by default in scores	
 	jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
 	//url = urlBase + '/countryid/<?php echo $this->countryId;?>@today/leagueId/<?php echo $this->compId;?>'; 
 	url = urlBase;
	//jQuery('#scoreboardResult').load(url);
 	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var timezone = calculate_time_zone();
 	jQuery('#scoreboardResult').load(url , { countryid : '<?php echo $this->countryId;?>@today', leagueId : '<?php echo $this->compId;?>',timezone:timezone , initDateTime : initDateTime, endDateTime : endDateTime});

	jQuery('#scoresTab').click(function() {
		loadScoresTab('scoresTabLi', urlBase, 'today', 'scoreboardResult' , 'ScoresDateFilter' ,'today' , 'tab_2' , '<?php echo $this->countryId;?>@today');
	 	jQuery('#tab_3').hide();
	});
	jQuery('#schedulesTab').click(function() {
		loadScoresTab('schedulesTabLi', urlBase, 'tomorrow', 'scoreboardResult2' , 'SchedulesDateFilter' ,'tomorrow' , 'tab_3' , '<?php echo $this->countryId;?>@today');
	 	jQuery('#tab_2').hide();
	});
	

	//scores
	jQuery('#today').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'today','scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@today');	
	 });
	jQuery('#l3').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , '-3' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@today');			
     });
	jQuery('#l7').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'last' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@today');			
     });
     //schedules
	jQuery('#tomorrow').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'tomorrow' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->countryId;?>@today');			
     });
	jQuery('#n3').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , '3' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->countryId;?>@today');			
     });
	jQuery('#n7').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'week' ,'scoreboardResult2' ,'SchedulesDateFilter' ,'<?php echo $this->countryId;?>@today');			
     });
	
	//load top scorers default view
 	showTopScores();

    //assign top scorers function to event click "ok" button in dropdown
	jQuery('#topscorersid').click(function(){ //
		showTopScores();
	 });

	showHideDivBox('countryScoresId','countryScoresBodyId');
	showHideDivBox('countryNewsId','countryNewsBodyId');
	
	callRandonProfiles();
		 	
});


 function showLeagueStanding(roundId){
 		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitiontablefb/fb/no/roundid/'+roundId;
		jQuery('#standingscontent').html("<div class='ajaxloadtable'></div>"); 
		jQuery('#standingscontent').load(url);
    }

 function loadScoresTab(tabid, url, date, container , filter ,defaultsearchid , tabtoshow , countryid){
	 	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
		var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
		var timezone = calculate_time_zone();
		jQuery('li.Selected').removeClass('Selected');
	    jQuery('#'+tabid).addClass('Selected');
	    jQuery('#'+ filter+' a').removeClass('filterSelected');
	 	jQuery('#'+defaultsearchid).addClass('filterSelected');
	    jQuery('#'+container).html('');
	    jQuery('#'+container).html("<div class='ajaxload widget'></div>");
		jQuery('#'+tabtoshow).show();
		jQuery('#'+container).load(url ,{countryid :countryid , date:date, timezone : timezone ,initDateTime : initDateTime, endDateTime : endDateTime } );
}
 function loadScoreBoardByTimeFrame(id, url, date, container , filter ,countryid)
 {
	 var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	 var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	 var timezone = calculate_time_zone();
 	 jQuery('#'+ filter+' a').removeClass('filterSelected');
     jQuery('#' + id).addClass('filterSelected');
 	 jQuery('#' + container).html("<div class='ajaxload widget'></div>"); 			
  	jQuery('#' + container).load(url , {date : date , countryid :countryid,timezone:timezone, initDateTime : initDateTime, endDateTime : endDateTime});
 } 
 	
 function callRandonProfiles()
 {
     jQuery.ajax({
         method: 'get',
         url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom/teamId/<?php echo $this->team[0]["team_id"]; ?>',
         dataType : 'text',
         success: function (text) {
             jQuery('#randomprofiles').html(text);
         }
     });

 }	

function showTopScores(){

	var leagueRound = jQuery('#leagueIdTopScorers').val();
	jQuery('#topscorerscontent').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showtopscorers/leagueid/'+leagueRound,
        dataType : 'text',
        success: function (text) {
                jQuery('#topscorerscontent').html(text);
		}
     });
}

</script>

		
<div id="ContentWrapper">


    <p class="flags">
        <span style="background-image: url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/flags/32x32/<?php echo $this->countryCodeIso; ?>.png);" class="flagtitle">
             <?php echo $this->countryName; ?>
        </span>
    </p>

   <div class="FirstColumn">

            <!--Me box Authenticated-->
              <?php 
                    if($session->email != null){
              ?> 
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>
                        </div>
                    </div>

             <?php } else { ?> 

                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
        
               <?php } ?>
                    
                    <!--List of Country competitions-->
                    <div class="img-shadow">
                         <?php echo $this->render('include/navigationCountry.php');?>
                    </div>
                    
                  <?php  if($session->email == null){  ?>
				<!--Goalface Join Now-->
				    <div class="img-shadow">
				        <div class="WrapperForDropShadow">
				        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
				           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
				        </a>
				        </div>
				    </div>
				<?php  } ?>

    </div><!--/FirstColumn-->
                
    <div class="SecondColumn">

    	<!-- Competition Table --> 
         <div class="prof">
			    <p class="mblack">
			        <span class="black"><?php echo $this->countryName; ?>'s Leagues Standings</span>
			    </p>
			    
			    <div class="BlueShaded DisplayDropdown">
                  Show:
                      <select id="LeagueTableSelect" class="slct" name="leagueId" onchange="javascript:showLeagueStanding(this.value)">
		                 <?php foreach($this->domesticleagues as $domestic) { ?>  
		                 	<option value="<?php echo $domestic["round_id"];//$domestic["competition_id"]; ?>">
                                <?php echo $domestic["competition_name"]; ?>
                             </option>
		                  <?php } ?>
                       </select>
                 </div>
                 
				 <div class="cont" id="standingscontent" style="display: block;">
				 
				 </div>		    

       			<p class="worldcup">
	      			<a class="OrangeLink" href="<?php //echo $urlGen->getTablesUrl("tables",$this->roundId ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>" title="<?php echo $this->compName;?> Full Table">See Full Table</a>
	      		</p>
       		</div>

            <div class="prof">
                        <p class="mblack">
                            <span class="black">Fan Profiles</span>
                            <span class="sm" id="menu6_more">
                            <?php if($session->email == null){ ?>
                  				 <a href="javascript:loginModal();" title="GoalFace Fan Profiles">More »</a>
                  			<?php }else{?>	 
                            	<a href="<?php echo $urlGen->getMainProfilesPage(true); ?>" title="GoalFace Fan Profiles">More »</a>
                            <?php }?>	 	
                           </span>
                         </p>
                         
	                     <div id="randomprofiles" class="nmatch">
	                     
						</div>

                        <p class="modfooter">
                        	<?php if($session->email == null){ ?>
                        	<a class="orangelink" href="javascript:loginModal();">Browse</a> | <a class="orangelink" href="javascript:loginModal();">Most Popular</a>
                        	<?php }else{?>	
                        	<a class="orangelink" href="<?php echo $urlGen->getMainProfilesPage(true); ?>">Browse</a> | <a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles">Most Popular</a>
                        	<?php }?>
                        </p>
                        
                    </div>

        </div><!--/SecondColumn-->
        
        <div class="ThirdColumn">


         <div class="featured1">
	  			<p class="mblack">
                        <span class="black"><?php echo $this->countryName; ?> Top Scorers</span>
                       <!--  <span class="sm"><a href="/competitions/showstats/compid/228">More »</a></span>-->
             	</p>
             	<p class="show">                           
	                 <label>Show:</label>                          
	                 <select id="leagueIdTopScorers" class="slct" name="leagueIdTopScorers">
 								<?php foreach($this->domesticleagues as $domestic) { ?>
                           			<?php  if ($domestic["competition_id"] == $this->leagueid) {
                                				$showselected = "selected";
                                  			} else {
                                				$showselected = "";
                            				} ?>
			                       <option value="<?php echo $domestic["competition_id"]; ?>" <?php echo $showselected; ?>>
                                <?php echo $domestic["competition_name"]; ?>
                              </option>
                           <?php } ?>
	                  </select>
	                  <input type="button" value="OK" class="submit" style="display:inline;" id="topscorersid">                
			     </p>
			     
			     <div class="cont" id="topscorerscontent" style="display: block;">
			     
			     </div>
			     
             </div>
          

          <!-- Scoreboard --> 
          <div id="scoreboard" class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                           
                            <h4 class="NoArrowLeft"><?php echo $this->countryName; ?> Scores &amp; Schedules </h4>
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
                                    <a class="OrangeLink" href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="">See More &raquo;</a>
                            </div>    
                     </div>
                </div>
          
          
          <!-- End Scoreboard -->
          
        </div><!--/ThirdColumn-->
  </div><!--end wrapper-->
