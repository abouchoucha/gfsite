  <?php 
    require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
  ?>
<!-- Footer -->
<div id="footer">
  <?php
    $session = new Zend_Session_Namespace('userSession');
    if($session->email != null){
   ?>
   <!-- // Autenthicated View-->
			<div class="foot">
				<div class="ftr">
					<p class="profile">My GoalFace</p>
					<ul>
						<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/myhome" title="My Profile">Dashboard</a></li>
						<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreupdates" title="My Profile">Updates</a></li>
						<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreactivities" title="My Profile">Activities</a></li>
						<li><a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>" title="My Profile">Profile</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends" title="Friends">Friends</a></li>
                    	<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities" title="Friends Favorites">Favorites</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter" title="Message Center">Messages  <?php echo ($session->newMessages > 0? '<span class="redmessage">New!</span>':''); ?></a></li>
					</ul>
				</div>
				<div class="ftr1">
					<p class="profile">Scores</p>
					<ul>
                        <li><a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="Football Live Scores & Match Schedules">Scores &amp; Schedules</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/europe" title="Europe Football Live Scores & Match Schedules">Europe</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/americas" title="Americas Football Live Scores & Match Schedules">America</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/africa" title="Africa Football Live Scores & Match Schedules">Africa</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/asia" title="Asia Football Live Scores & Match Schedules">Asia &amp; Pacific Islands</a></li>
					</ul>
				</div>
				<div class="ftr">
					<p class="profile">Teams</p>
					<ul>
						<?php require_once 'Team.php';
                            $team = new Team();
                            $topTeams= $team->findUserTeams();
                            foreach($topTeams as $data)
                            {
           					echo "<li><a href=". $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_name"], true).">".$data["team_name"]."</a></li>";
                        }?>
					</ul>
				</div>
				<div class="ftr">
					<p class="profile">Players</p>
					<ul>
                        <?php require_once 'Player.php';
                           $player = new Player();
                           $topPlayers = $player->findUserPlayers();
                            foreach($topPlayers as $data)
                            {
                                echo "<li><a href=". $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]).">".$data["player_name_short"]."</a></li>";
                            }?>
					</ul>
				</div>
				<div class="ftr2">
					<p class="profile">Leagues &amp; Tournaments</p>
					<ul>
						 <?php
                            require_once 'UserLeague.php';
                            $usercomp = new UserLeague();
                            $userCompRows= $usercomp->findAllUserCompetitions($session->userId,null,5);
?>
<?php
        				foreach($userCompRows as $data)
           				{
           					if($data["regional"] == "1")
           					{
           						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}
           					elseif($data["regional"] == "2")
           					{
           						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}
           					elseif($data["regional"] == "0")
           					{
           						$url = $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}?>
           					<li>
            					<a href="<?php echo $url;?>"><?php echo $competitionName;?></a>
            				</li>
           				<?php }?>
                       
                       
					</ul>
				</div>
				<div class="ftr3">
					<p class="profile">GoalFace Community</p>
					<ul>
					   <li class="first"><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=popular%23mp">Most Popular Fans</a></li>
                       <li><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=active%23lm">Like Me</a></li>
                       <li><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=active%23ma">Most Active</a></li>
                       <li><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=recently%23ru">Recently Updated</a></li>
                        <li><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=newest%23nw">Newest Fans</a></li>
                         <li><a href="<?php echo $urlGen->getMainProfilesPage(true); ?>/?search=online%23onl">Online Now</a></li>
					</ul>
				</div>
			</div>

  <?php
		} else {
	?>
     <!-- // Non Autenthicated View-->
			<div class="foot">

				<div class="ftr1">
					<p class="profile">Scores</p>
					<ul>
                        <li><a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="Football Live Scores & Match Schedules">Scores &amp; Schedules</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/europe" title="Europe Football Live Scores & Match Schedules">Europe</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/americas" title="Americas Football Live Scores & Match Schedules">America</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/africa" title="Africa Football Live Scores & Match Schedules">Africa</a></li>
                        <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/asia" title="Asia Football Live Scores & Match Schedules">Asia &amp; Pacific Islands</a></li>
					</ul>
				</div>
				<div class="ftr">
					<p class="profile">Teams</p>
					<ul>
						<?php require_once 'Team.php';
                            $team = new Team();
                            $topTeams= $team->findTopTeams();
                            foreach($topTeams as $data)
                            {
           					echo "<li><a href=". $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_seoname"], true).">".$data["team_name"]."</a></li>";
                        }?>
					</ul>
				</div>
				<div class="ftr">
					<p class="profile">Players</p>
					<ul>
                        <?php require_once 'Player.php';
                           $player = new Player();
                           $topPlayers = $player->findTopPlayers();
                            foreach($topPlayers as $data)
                            {
                                echo "<li><a href=". $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]).">".$data["player_name_short"]."</a></li>";
                            }?>
					</ul>
				</div>
				<div class="ftr2">
					<p class="profile">Leagues &amp; Tournaments</p>
					<ul>
						<?php require_once 'LeagueCompetition.php';
          				$comp = new LeagueCompetition(); 
                  $topComps= $comp->findTopCompetitions();
           				foreach($topComps as $data)
           				{
           					if($data["regional"] == "1")
           					{
           						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}
           					elseif($data["regional"] == "2")
           					{
           						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}
           					elseif($data["regional"] == "0")
           					{
           						$url = $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True);
           						$competitionName = $data["competition_name"];
           					}?>
           					<li>
            					<a href="<?php echo $url;?>"><?php echo $competitionName;?></a>
            				</li>
           				<?php }?>
					</ul>
				</div>
				<div class="ftr2">
					<p class="profile">Things You Can Do</p>
					<ul>
	              <li><a href="http://www.goalface.org/tour.php" title="GoalFace Quick Tour" target="_blank">Take the Quick Tour</a></li>
                <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Football Teams">Follow Your Favorite Team</a></li>
                <li><a href="<?php echo $urlGen->getMainScoresAndMatchesPageUrl(true); ?>" title="Football Scores & Schedules">Check the Latest Scores and Schedules</a></li>
                <li><a href="<?php echo $urlGen->getMainLeaguesAndCompetitionsUrl(true); ?>" title="League and Tournaments">View League and Tournament Standings and Stats</a></li>
                <li>A Lot More...<strong><a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register"  title="Register to GoalFace">Join Now!</a></strong></li>
					</ul>
				</div>
			</div>




<?php } ?>

    <div class="flist">
        <div class="flist1">
            <ul>
                <li><a href="<?php echo $urlGen->getAboutPageUrl(true); ?>">About</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getHelpFaqPageUrl(true); ?>">Help/FAQ</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getTermsPageUrl(true); ?>">Terms</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getGuidelinesPageUrl(true); ?>">Guidelines</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getPrivacyPageUrl(true); ?>">Privacy</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getDisclaimerPageUrl(true); ?>">Disclaimer</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getTrademarkPageUrl(true); ?>">Trademark</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getAdvertisePageUrl(true); ?>">Advertise</a></li>
                <li class="lin"> | </li>
                <li><a href="<?php echo $urlGen->getContactUsPageUrl(true); ?>">Contact</a></li>
            </ul>
        </div>

        <form method="get" action="<?php echo $urlGen->getSearchMainUrl(true); ?>">
        <p class="fsearch">
            <span class="fboxx">
                <input id="search-query" type="text" value="Search GoalFace..." name="q" class="fbox"  onfocus="javascript:if(this.value=='Search GoalFace...')this.value='';" onblur="javascript:if(this.value=='')this.value='Search GoalFace...';"/>
            </span>
            <span class="ser">
                <input id="searchButtonId" type="submit" value="Search"  class="ser1"/>
            </span>
        </p>
        </form>
   
       	<div class="followus">
          <a target="_blank" href="http://www.twitter.com/goalface">
            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/logo_twitter.png">
          </a>
          <span>Follow us on Twitter</span>
          |
          <a target="_blank" href="http://www.facebook.com/goalface">
            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/logo_facebook.png" class="lasticon">
          </a>
          <span>Join us on Facebook</span>
        </div>
        
        <p class="copy">Copyright &#169; 2013 <a href="http://www.goalface.org" 
target="_blank">GoalFace, Inc</a>. All Rights Reserved.</p>

    </div>
</div>
<!--/ Footer -->


