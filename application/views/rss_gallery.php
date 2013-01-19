<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>

<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();?>

<?php require_once 'Common.php'; 
$common = new Common();
?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
    $session = new Zend_Session_Namespace('userSession');
 ?> 

<script>
jQuery.ajaxSetup({
	  beforeSend: function() {
	     $('#systemWorking').show()
	  },
	  complete: function(){
	     $('#systemWorking').hide()
	  },
	  success: function() {}
	});



</script>


<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumn">
     <?php echo $this->render('include/topleftbanner.php')?>       
 
 <?php
        $session = new Zend_Session_Namespace('userSession');
        if($session->email != null) {
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

           
            <!--Goalface Join Now-->
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                       <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                    </a>
                    </div>
                </div>


            <?php } ?>
            
        <!--Facebook Like Module-->
                   <?php echo $this->render('include/navigationfacebook.php')?>

    </div><!--end FirstColumnOfThree-->

    <div id="SecondColumnPlayerProfile" class="SecondColumn">
        <h1 >Updates &amp; Alerts</h1>
        <p class="rssHeaderText">
    Updates and alerts from GoalFace make following your favorite football players, teams and leagues easier than ever! Subscribe 
    using the forms below and receive updates to your email inbox, mobile phone and your favorite places online.
        </p>
        <!--<div><a class="trigger" id="customNews" href="">Customize News</a></div>-->

        <div class="ammid">		
			<div class="emlist">
				<ul>
					<li><a href="#playerSection">Players</a></li>
					<li><a href="#teamSection">Team</a></li>
					<li><a href="#leagueSection">Leagues &amp; Tournaments</a></li>
				</ul>
			</div>
			<div class="rsleft">
				<div class="plhead"><span class="plhead1">Players</span></div>
				<div class="plfeature">
					<h2><a name="playerSection">Featured Players</a> &nbsp;<a href="javascript:" class="refreshPlayer">Refresh</a> </h2>
					<div id="popularPlayersContainer" class="refreshContainer">
					<?php
						$count = 0; 
						foreach($this->popularPlayers as $player)
						{
							//print_r($player);
							if($count == 0 || $count==6)
							{	
						?>
								<div class="mailrow">
						<?php 
							}
							$itemClass = "mail1";
							if($count > 5)
							{
								$itemClass = "mail2";
							}
					?>
					<p class="<?php echo $itemClass?>">
						<span class="mailimg">
						 <a id="player<?php echo $player["player_id"];?>profileLink" href="<?php echo Zend_Registry::get("contextPath"). $urlGen->getPlayerMasterProfileUrl("", "", "", $player["player_id"], true, $player["player_common_name"]); ?>" title="<?php echo $player["player_common_name"] ?>">
                                                                
                                <?php
						                $path_player_photos = $config->path->images->players . $player["player_id"] .".jpg" ;
						                if (file_exists($path_player_photos)) { 
						         ?>                   
			
                                	<img id="player<?php echo $player["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $player["player_id"]?>.jpg" alt="<?php echo $player["player_common_name"]; ?> Profile Image"/>
                          
                                <?php 
								} 
								else 
								{  
								?>
                                	<img id="player<?php echo $player["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>
                                <?php 
								} 
								?>
								
								
								
							  </a>
						
						</span>
						<span class="mailname"> <?php echo $player["player_name_short"];?></span>
						<span class="mailsub">
			
							<a href="javascript:" onclick="subscribeToPlayer(<?php echo $player["player_id"];?>);">
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $player["player_name_short"];?>'s updates">
							</a>

						
							
						</span>
						<span class="mailsub"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $player["player_id"]; ?>"><img alt="Rss updates" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a></span>
					</p>
					<?php 
							$count++;
							if($count==6 || $count == 12)
							{
					?>
							</div>
					<?php 
							}
						}
					?>
					</div>
			
				
				<div id="playerACSearchContainer" class="fdplayer">
				
				
					<h3>Find Player</h3>
					<p class="rssBodyText">Use the form below to subscribe to updates from your favorite football players.</p>
					<span class="fpser">
						<label>Search:</label>
						<input class="mailtb playerautocomp" id="playerSearchInput" type="text" value="Enter player name">
						<input type="hidden" id="searchPlayerId" value=""/>
						<input type="hidden" id="searchPlayerName" value=""/>
						<span id="subscribePlayerSearchButtons" class="vamoose">
							<span id="subscribePlayerLinkSpan"> <a href='javascript:' class='subscribeToSearchPlayerRSS'>Subscribe</a>&nbsp; </span>
							<a class="playerRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a> 
						</span>
					</span>
					<span class="fpser orLabel">OR</span>
					<div class="sbox">
						<div class="sbox_labels">
							<span class="sel"><label>Browse By:</label></span>
							<span class="nation"><input value="0" name="searchWidget_playerSelectionType" checked  type="radio"> Nationality</span>
							<span class="club"><input value="1" name="searchWidget_playerSelectionType" type="radio">Club Team</span>
						</div>	
						<div class="sbox_controls">
							<span class="selcon"><select id="searchWidget_playerCountry" class="selcon1"><option selected="selected" value="">Select Country</option></select></span>
							<span class="selcon"><select id="searchWidget_playerClub" class="selcon1"><option selected="selected" value="">Select A Club Team</option></select></span>
							<span class="selcon"><select id="searchWidget_playerSelected" class="selcon1"><option selected="selected" value="">Select A Player</option></select></span>
							<span id="searchWidgetPlayer_browserSubscribe" class="mailsub vamoose ">
								
							</span>
							<span id="subscribePlayerBrowseButtons" class="selcon">
								<a href=javascript" class="subscribeToSearchPlayerRSS"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt=""></a>
								<a class="playerRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a>
							</span>
						</div>
					</div>
				</div>
				<div class="plhead">
					<span class="plhead1">Teams</span>
					<span class="plhead2"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/btt.jpg" alt=""></a></span>
				</div>
				<div class="plfeature">
					<h2><a name="teamSection">Featured Teams</a>&nbsp;<a href="javascript:" class="refreshTeam">Refresh</a></h2>
					<div id="popularTeamsContainer" class="refreshContainer">
					<?php 
						$count=0;
						
					
						foreach($this->popularTeams as $team)
						{
							//print_r($team);
							if($count == 0 || $count==6)
							{	
					?>
							<div class="mailrow">
					<?php 
							}
							$config = Zend_Registry::get('config' );
						    $path_team_logos = $config->path->images->teamlogos . $team['team_id'].".gif" ;
							$itemClass = "mail1";
							if($count > 5)
							{
								$itemClass = "mail2";
							}
							
					?>
					<p class="<?php echo $itemClass?>">
						<span class="mailimg">
						 <a id="team<?php echo $team["team_id"];?>profileLink" href="<?php echo $urlGen->getClubMasterProfileUrl($team['team_id'],$team['team_seoname'], true); ?>" title="<?php echo $team["team_name"] ?>">
								<?php 
								if ( file_exists($path_team_logos) ) 
								{ 
								?>
                                	<img id="team<?php echo $team["team_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $team['team_id']?>.gif"/>
                                <?php 
								} 
								else 
								{  
								?>
                                	<img id="team<?php echo $team["team_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
                                <?php 
								} 
								?>
						</a>
						
						</span>
						<span class="mailname"> 
							<a href="<?php echo $urlGen->getClubMasterProfileUrl($team['team_id'],$team['team_seoname'], true); ?>"><?php echo $team["team_name"];?></a>
						</span>
						<?php $teamName = $team["team_name"]; ?>
						<span class="mailsub">
							<a href="javascript:" onclick="subscribeToTeam(<?php echo $team["team_id"];?>, '<?php echo $teamName;?>');">
							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $team["team_name"];?>'s updates">
							</a>
						</span>
						<span class="mailsub">
							<a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/rss/id/<?php echo $team["team_id"]; ?>">
								<img alt="Rss updates" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />
									&nbsp;RSS Updates
							</a>
						</span>
						
					</p>
					<?php 
							$count++;
							if($count==6 || $count == 12)
							{
					?>
							</div>
					<?php 
							}
						}
					
					
						
						?>
					</div>	
						</div>
				
				</div>
				<div id="teamACSearchContainer" class="fdplayer">
					<h3>Find Team</h3>
					<p class="rssBodyText">Use the form below to subscribe to updates from your favorite national and club teams.</p>
					<span class="fpser">
						<label>Search:</label>
						<input class="mailtb teamautocomp" id="teamSearchInput" type="text" value="Enter team name">
						<input type="hidden" id="searchTeamId" value=""/>
						<input type="hidden" id="searchTeamName" value=""/>
						<span id="subscribeTeamSearchButtons" class="vamoose">
							<span id="subscribeTeamLinkSpan"><a href='javascript:' class='subscribeToSearchTeamRSS'>Subscribe</a>&nbsp;</span>
							<a class="teamRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a> 
						</span>
					</span>
					<span class="fpser orLabel">OR</span>
					<div class="sbox">
						<div class="sbox_labels">
							<span class="sel"><label>Browse By:</label></span>
							<span class="nation"><input value="0" name="searchWidget_teamSelectionType" checked type="radio">National Team</span>
							<span class="club"><input value="1" name="searchWidget_teamSelectionType"   type="radio">Club Team</span>
						
							
						</div>	
						<div class="sbox_controls">
							<span class="selcon"><select id="searchWidget_teamCountry" class="selcon1"><option selected="selected" value="">Select Country</option></select></span>
							<span class="selcon"><select id="searchWidget_teamClub" class="selcon1"><option selected="selected" value="">Select A National Team</option></select></span>
							<span id="subscribeTeamBrowseButtons" class="selcon">
								<a href=javascript" class="subscribeToSearchTeamRSS"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt=""></a>
								&nbsp; 
								<a class="teamRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a>
							</span>
						</div>
					</div>
				</div>

				<div class="plhead">
					<span class="plhead1">Leagues &amp; Tournaments</span>
					<span class="plhead2"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/btt.jpg" alt=""></a></span>
				</div>
				<div class="plfeature">
					<h2><a name="leagueSection">Featured Leagues &amp; Tournaments </a> &nbsp; <a href="javascript:" class="refreshLeague">Refresh</a></h2>
					<div id="popularLeaguesContainer" class="refreshContainer">
					<?php 
						$count=0;
						foreach($this->popularLeagues as $league)
						{
							//print_r($league);
						if($count == 0 || $count==6)
							{	
						?>
								<div class="mailrow">
						<?php 
							}
						    $config = Zend_Registry::get ( 'config' );
                    		$path_comp_logos = $config->path->images->complogos . $league['competition_id'].".gif" ;
							$itemClass = "mail1";
							if($count > 5)
							{
								$itemClass = "mail2";
							}
					?>
					<p class="<?php echo $itemClass?>">
						<span class="mailimg">
						 <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($league["competition_name"], $league["competition_id"], True); ?>" title="<?php echo $league["competition_name"] ?>">
								<?php 
								if ( file_exists($path_comp_logos) ) 
								{ 
								?>
                                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/competitionlogos/<?php echo $league['competition_id']?>.gif"/>
                                <?php 
								} 
								else 
								{  
								?>
                                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif"/>
                                <?php 
								} 
								?>
						</a>
						
						</span>
						<span class="mailname"> <?php echo $league["competition_name"];?></span>
						<?php $leagueName = $league["competition_name"]; ?>
						<span class="mailsub">
							<a href="javascript:" onclick="subscribeToLeague(<?php echo $league["competition_id"];?>, '<?php echo $leagueName;?>');">
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $league["competition_name"];?>'s updates">
							</a>
						</span>
					</p>
					<?php 
							$count++;
							if($count==6 || $count == 12)
							{
					?>
							</div>
					<?php 
							}
						}
					?>	
					</div>					
				</div>
				<div id="leagueACSearchContainer" class="fdplayer">
					<h3>Find League &amp; Tournament</h3>
					<p class="rssBodyText">Use the form below to subscribe to updates from your favorite league and tournament around the world.</p>
					<span class="fpser"><label>Search:</label>
							<input class="mailtb teamautocomp" id="leagueSearchInput" type="text" value="Enter league or tournament name">
							<input type="hidden" id="searchLeagueId" value=""/>
							<input type="hidden" id="searchLeagueName" value=""/>
							<span id="subscribeLeagueSearchButtons" class="vamoose">
							<span id="subscribeLeagueLinkSpan"><a href='javascript:' class='subscribeToSearchLeagueRSS'>Subscribe</a>&nbsp;</span>
							<a class="leagueRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a> 
						</span>
					
					</span>
					<span class="fpser orLabel">OR</span>
					<div class="sbox">
							<div class="sbox_labels_short">
								<span class="sel"><label>Select:</label></span>
							</div>
							<div class="sbox_controls">
								<span class="selcon"><select id="searchWidget_leagueCountry" class="selcon1"><option selected="selected" value="">Select Region/Country</option></select></span>
								<span class="selcon"><select id="searchWidget_leagueSelected" class="selcon1"><option selected="selected" value="">Select League/Tournament</option></select></span>
								<p style="clear:both;">
								<span class="selcon" id="subscribeLeagueBrowseButtons">
									<a href="javascript:" class="subscribeToSearchLeagueRSS"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt=""></a>
									&nbsp; 
									<a class="leagueRssFeedSubscribe" href="#"><img alt="Rss Update Icon" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a>
								</span>
							</div>	
					</div>
					
				</div>

				
				
			

			</div>
			
	<!-- /mmid -->	
	</div> 

    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

</div> <!--end ContentWrapper-->
 <div align="center" id="systemWorking" style="display:none">
                                    <img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>
                                </div>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/findplayer.js" type="text/javascript"></script>

<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/findteam.js" type="text/javascript"></script>

<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/findleague.js" type="text/javascript"></script>


