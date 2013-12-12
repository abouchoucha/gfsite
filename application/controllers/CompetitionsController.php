<?php
require_once 'application/controllers/util/Constants.php';
require_once 'application/controllers/util/Common.php';
require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';

class CompetitionsController extends GoalFaceController {
	
	public $region_name = null;
	public $regional_Heading = null;
	public $country = null;
	private static $regionGroupNames = null;
	private static $regionGroupNameHeader = null;
	private static $urlGen = null;
	
	function init() {
		Zend_Loader::loadClass ( 'LeagueCompetition' );
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'Player' );
		Zend_Loader::loadClass ( 'Season' );
		Zend_Loader::loadClass ( 'Round' );
		Zend_Loader::loadClass ( 'Groupp' );
		Zend_Loader::loadClass ( 'Ranking' );
		Zend_Loader::loadClass ( 'UserLeague' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'MetaKeywordGen' );
		Zend_Loader::loadClass ( 'MetaDescriptionGen' );
		Zend_Loader::loadClass ( 'FeedNews' );
		Zend_Loader::loadClass ( 'Region' );
		Zend_Loader::loadClass ( 'WorldCupPlayerStats' );
		Zend_Loader::loadClass ( 'PlayerCurrentStats');
		Zend_Loader::loadClass ( 'TeamPlayerStats');
		Zend_Loader::loadClass ( 'Tournament' );
		Zend_Loader::loadClass ( 'Standing' );
		//Zend_Loader::loadClass ( 'Zend_Service_Twitter');
		Zend_Loader::loadClass ( 'GoalserveStanding');
		Zend_Loader::loadClass ( 'Zend_Feed_Rss' );
		Zend_Loader::loadClass ( 'Feed' );
		
		
		self::$urlGen = new SeoUrlGen ();
		
		parent::init ();
		//$this->breadcrumbs->addStep ( 'Football Leagues & Tournaments', self::$urlGen->getMainLeaguesAndCompetitionsUrl ( true ) );
		

		$this->region_name = array ('', 'Europe', 'Americas', 'Americas', 'Americas', 'Africa', 'Asia', '', 'World' );
		$this->regional_Heading = array ('', 'European', 'American', 'American', 'American', 'African', 'Asian', '', 'International' );
		$this->country = new Country ();
		
		self::$regionGroupNames = array ("europe" => array ("european", "Europe", "European Leagues & Tournaments" ), "asia" => array ("asian", "Asia and Pacific Islands", "Asian Leagues & Tournaments" ), "africa" => array ("african", "Africa", "African Leagues & Tournaments" ), "americas" => array ("americas", "Americas", "American Leagues & Tournaments" ), "international" => array ("international", "FIFA/International", "International Leagues & Tournaments" ) );
		
		$this->updateLastActivityUserLoggedIn ();
	}
	
	private function loadRssFeed ($url) {
  		try {
			$feed = new Zend_Feed_Rss($url);
  		} catch (Zend_Feed_Exception $e) {
       		// feed import failed
        	return null;
 		}
   		return $feed;    
 	}
	
	/**
	 * This method is called when framework did not find 
	 * controller that will handle the request.
	 */
	
	private function isFavoriteCompetition($view , $leagueid ){
		
		$isFavorite = "false";
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->email != null) {
			$userLeague = new UserLeague ();
			$row = $userLeague->findUserCompetition ( $session->userId, $leagueid );
			//Zend_debug::dump($row);
			if ($row != null) {
				$isFavorite = "true";
			}
		}
		$view->isFavorite = $isFavorite;
		
	}

 						
	public function indexAction() {
		
		//$this->checkifUserIsRemembered();

		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace('userSession');
		// Declare title, keywords, and description objects
		$title = new PageTitleGen ();
		$keywords = new MetaKeywordGen ();
		$description = new MetaDescriptionGen ();
		
		// set title, metakeywords, and MetaDescription values on VIEW
		$view->title = $title->getPageTitle ( '', PageType::$_LEAGUES_MAIN_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_LEAGUES_MAIN_PAGE );
		$view->description = $description->getMetaDescription ( '', PageType::$_LEAGUES_MAIN_PAGE );
		
		//execute queries from model "league competition". 5 queries each one for each region. regional and domestic
		
		//Regional Competitions
		$lea_comp = new LeagueCompetition ();
		$rafrica = $lea_comp->findRegionalCompetitionsByContinent ( 5, '1,2' ); //Africa
		$ramericas = $lea_comp->findRegionalCompetitionsByContinent ( '2,3,4', '1,2' ); //Americas
		$rasiapacific = $lea_comp->findRegionalCompetitionsByContinent ( '6,7', '1,2' ); //Asia & Pacific Islands
		$reurope = $lea_comp->findRegionalCompetitionsByContinent ( 1, '1,2' ); //Europe
		$rfifa = $lea_comp->findRegionalCompetitionsByContinent ( 8, '1,2' ); //FiFA
		
		//Domestic Competitions
		$dafrica = $lea_comp->findDomesticCompetitionsByContinent ( 5, '0' ); //Africa
		$damericas = $lea_comp->findDomesticCompetitionsByContinent ( '2,3,4', '0' ); //Americas
		$dasiapacific = $lea_comp->findDomesticCompetitionsByContinent ( '6,7', '0' ); //Asia & Pacific Islands
		$deurope = $lea_comp->findDomesticCompetitionsByContinent ( 1, '0' ); //Europe
		$view->totalEurope = count ( $deurope );
		$view->totalAmericas = count ( $damericas );
		$view->totalAfrica = count ( $dafrica );
		$view->totalAsia = count ( $dasiapacific );
		$dfifa = $lea_comp->findDomesticCompetitionsByContinent ( 8, '0' ); //FiFA
		

		//assign results to view variabless for its use 
		$view->rafrica = $rafrica;
		$view->ramericas = $ramericas;
		$view->rasiapacific = $rasiapacific;
		$view->reurope = $reurope;
		$view->rfifa = $rfifa;
		$view->dafrica = $dafrica;
		$view->damericas = $damericas;
		$view->dasiapacific = $dasiapacific;
		$view->deurope = $deurope;
		$view->dfifa = $dfifa;
		
		$view->regionGroupNames = self::$regionGroupNames;
		
		//featured leagues and tournaments
		if($session->userId != null) { 
		    
		} else {
		    
		}
		
		$this->view->featuredLeaguesFour = $lea_comp->findFeaturedCompetitions ( 4 );
		
		$this->breadcrumbs->addStep ( 'Football Leagues & Tournaments', self::$urlGen->getMainLeaguesAndCompetitionsUrl ( true ) );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'leaguedirectory.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showregionAction() {
		
		//$this->checkifUserIsRemembered();
		

		$view = Zend_Registry::get ( 'view' );
		//$regionId = $this->_request->getParam ( 'id', 0 ) ;
		$regionName = $this->_request->getParam ( 'name', null );
		$regionName = strtolower ( $regionName );
		//Zend_Debug::dump($regionName);
		$regionId = '0';
		$regionGroupId = '0';
		if ($regionName == 'european') {
			$regionId = '1';
			$regionGroupId = '2';
		} else if ($regionName == 'asian') {
			$regionId = '6';
			$regionGroupId = '4';
		} elseif ($regionName == 'americas') {
			$regionId = '2';
			$regionGroupId = '1';
		} elseif ($regionName == 'african') {
			$regionId = '5';
			$regionGroupId = '3';
		} elseif ($regionName == 'international') {
			$regionId = '8';
			$regionGroupId = '5';
		}
		
		
		//find Region Group
		$region = new Region ();
		$regionRow = $region->find ( $regionId );
		$regionRow = $regionRow->current ();

		//$region_group_id = $region->getRegionGroupByName($regionName);
		
		
		// Make sure we have a right Match for the URL
		// Other wise throw a 404
		if ($regionId != '0') {
			// Declare title, keywords, and description objects
			$title = new PageTitleGen ();
			$keywords = new MetaKeywordGen ();
			$description = new MetaDescriptionGen ();
			
			// set title, metakeywords, and MetaDescription values on VIEW
			$view->title = $title->getPageTitle ( $this->regional_Heading [$regionId], PageType::$_LEAGUES_BY_REGION );
			$view->keywords = $keywords->getMetaKeywords ( $this->regional_Heading [$regionId], PageType::$_LEAGUES_BY_REGION );
			$view->description = $description->getMetaDescription ( $this->regional_Heading [$regionId], PageType::$_LEAGUES_BY_REGION );
			
			//added to get region name - JV
			//$region_name = array ( '' , 'EUROPE' , 'AMERICAS' , '' , '' , 'AFRICA' , 'ASIA' , '' , 'WORLD' ) ;
			$view->regionName = $this->region_name [$regionId];
			$view->regionalHeading = $this->regional_Heading [$regionId] . ' Leagues & Tournaments';
			$view->regionId = $regionId;
			$view->regionGroupId = $regionGroupId;
			//Zend_Debug::dump($regionGroupId);
		
			//Zend_Debug::dump($regionRow);		

			//$view->scorers = $scorers_row;
			//
			$player = new Player ();
			$lea_comp = new LeagueCompetition ();
			//Regional competitions
			$regionalNatTeam = $lea_comp->findCompetitionsRegionalByRegion ( $regionGroupId, 'international' );
			$regionalClubTeam = $lea_comp->findCompetitionsRegionalByRegion ( $regionGroupId, 'club' );
			//Domestic competitions
			$domesticTeams = $lea_comp->findDomesticCompetitionsByCountryInRegion ( $regionGroupId );
			$view->regionalNatTeam = $regionalNatTeam;
			$view->regionalClubTeam = $regionalClubTeam;
			$view->domesticTeams = $domesticTeams;
			
			$this->breadcrumbs->addStep ( $view->regionName );
			$this->view->breadcrumbs = $this->breadcrumbs;
			$view->selected = 'today';
			
			$comment = new Comment ();
			$regionComments = $comment->findCommentsPerRegion ( $regionRow->region_group_id, 10 );
			$totalComments = $comment->findCommentsPerRegion ( $regionRow->region_group_id );
			$view->totalGoalShouts = count ( $totalComments );
			$view->comments = $regionComments;
			$view->regiongroupid = $regionRow->region_group_id;
			
			//featured teams and players for the region
			$team = new Team ();
			$featuredteams = $team->selectFeaturedTeams ( null, 6, $regionRow->region_group_id );
			$view->featuredTeams = $featuredteams;
			$view->featuredTeamsTotal = count ( $featuredteams );
			$featuredplayers = $player->findFeaturedPlayers ( 6, $regionRow->region_group_id );
			$view->featuredPlayersTotal = count ( $featuredplayers );
			$view->featuredPlayers = $featuredplayers;
			
			$view->actionTemplate = 'region.php';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
		} else {
			// Should not have been here
			$this->noRouteAction ();
		}
	
	}
	
	//new showcountry for new feed 
	public function showcountry1Action() {
		$view = Zend_Registry::get ( 'view' );
		$countryId = $this->_request->getParam ( 'id', 0 );
		$country = new Country ();
		$region = new Region ();
		$countryRow = $country->findCountryById ( $countryId );
		$view->countryName = $countryRow->country_name;
		$view->longitude = $countryRow->longitude;
		$view->latitude = $countryRow->latitude;
		$view->countryCodeIso = $countryRow->country_code_iso2;
		$regionId = $countryRow->region_id;
		$regiongroupname = $region->getRegionGroupName ( $regionId );
		$region_name_display = $regiongroupname ["region_group_name"];
		
		// Declare title, keywords, and description objects
		$title = new PageTitleGen ();
		$keywords = new MetaKeywordGen ();
		$description = new MetaDescriptionGen ();
		
		// set title, metakeywords, and MetaDescription values on VIEW
		$view->title = $title->getPageTitle ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
		$view->keywords = $keywords->getMetaKeywords ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
		$view->description = $description->getMetaDescription ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
		
		//filtered news feed by country
		$feednews = new FeedNews ();
		$query = $countryRow->country_name;
		$feeds = $feednews->selectFeedNewsFiltered ( $query );
		//Zend_Debug::dump($regiongroupname);
		$this->view->newsFeeds = $feeds;
		$this->view->numberFeeds = count ( $feeds );
		
		$isFavorite = "false";
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->email != null) {
			$userTeam = new UserTeam ();
			$row = $userTeam->findUserTeam ( $session->userId, $countryId );
			if ($row != null) {
				$isFavorite = "true";
			}
		}
		$view->isFavorite = $isFavorite;
		
		//find National Teams
		$teams = new Team ();
		$natTeams = $teams->selectNationalTeams2 ( $countryId );
		$view->natTeamsList = $natTeams;
		//pass only mens national team
		$nationalTeamId = $natTeams [0] ["team_id"];
		$nationalTeamName = $natTeams [0] ["team_name_official"];
		$view->nteamid = $nationalTeamId;
		$view->nteamname = $nationalTeamName;
		
		//find all Domestic Leagues per Country
		$tournament = new Tournament ();
		$domesticLeagues = $tournament->getAllTournamentStagesPerCountry ( $countryId );
		$view->stageslist = $domesticLeagues;
		
		$view->selected = 'today';
		
		//$compIdDisplay = $seasonActive;
		if ($this->_request->isPost ()) {
			
			//get variable post from dropdown
			$currentLeagueSeasonId = $this->_request->getParam ( 'leagueId', 0 );
			//$roundRow = $round->getSeasonRounds($seasonIdDisplay);
			$view->currentSeasonId = $currentLeagueSeasonId;
		
		} else {
			
			// set up first default season "active" order by (first one is the most recent)
			$currentLeagueSeasonId = $domesticLeagues [0] ["stage_id"];
		
		}
		//assign view 
		$view->currentSeasonId = $currentLeagueSeasonId;
		
		$this->breadcrumbs->addStep ( $region_name_display, self::$urlGen->getShowRegionUrl ( strval ( $region_name_display ), True ) );
		$this->breadcrumbs->addStep ( $view->countryName );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'country.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	public function showcountryAction() {
		//$this->checkifUserIsRemembered();
		$view = Zend_Registry::get ( 'view' );
		$countryId = $this->_request->getParam ( 'id', 0 );
		//get the countrycode based on country_id = $view->playercountry
		$country = new Country ();
		$countryRow = $country->fetchRow ( 'country_id=' . $countryId );
		$view->countryName = $countryRow->country_name;
		$view->longitude = $countryRow->longitude;
		$view->latitude = $countryRow->latitude;
		$view->countryCodeIso = $countryRow->country_code_iso2;
		$cCode = $countryRow->country_code;		
		$regionId = $countryRow->region_id;
        
		//get news from search bing api
		//$feed = new Feed();
        //$feed_url = 'http://api.bing.com/rss.aspx?Source=News&Market=en-US&Version=2.0&Query=' .urlencode($countryRow->country_name)."%20Football";
        //$countryfeed = Zend_Feed::import($feed_url);
        //$view->countryFeed = $countryfeed;
		
		// Declare title, keywords, and description objects
		$title = new PageTitleGen ();
		$keywords = new MetaKeywordGen ();
		$description = new MetaDescriptionGen ();
		
		// set title, metakeywords, and MetaDescription values on VIEW
		$view->title = $title->getPageTitle ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
		$view->keywords = $keywords->getMetaKeywords ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
		$view->description = $description->getMetaDescription ( $countryRow->country_name, PageType::$_LEAGUES_BY_COUNTRY );
        $view->imagefacebook = "http://www.goalface.com/public/images/flags/32x32/".$countryRow->country_code_iso2.".png";

		$originalRegionId = $regionId;
		if ($regionId == '2') {
			$regionId = '2,3,4';
		}
		if ($regionId == '6') {
			$regionId = '6,7';
		}

		//Get the National Teams for a Country
		$teams = new Team ();
		$nationalTeams = $teams->selectNationalTeamByCountryType ( $countryId );
		$view->nationalTeams = $nationalTeams;
		
		$view->nationalTeamId = $nationalTeams [0] ["team_id"];
		$view->nationalTeamName = $nationalTeams [0] ["team_name"];
		$view->nationalTeamSeoName = $nationalTeams [0] ["team_seoname"];
		
		//Load Box of country competitions
		$lea_comp = new LeagueCompetition ();
		$round = new Round ();
		$dcountry = $lea_comp->findDomesticCompetitionsByCountry ( $countryId ); //By Country
		$dcountryleagues = $lea_comp->findDomesticCompetitionsByCountryLeagues ( $countryId ); //By Country Leagues Only
		$rcountry = $lea_comp->findRegionalCompetitionsByContinent ( $regionId, '1,2' ); //Continent
		$domesticLeagues = $lea_comp->findDomesticCompetitionsByCountryDomesticLeagues ( $countryId );

		$view->originalRegionId = $originalRegionId;
		$view->dcountry = $dcountry;
		$view->dcountryleagues = $dcountryleagues;
		$view->domesticleagues = $domesticLeagues;
		$view->rcountry = $rcountry;
		$view->countryId = $countryId;
		$view->regionId = $regionId;
		$view->roundId = $domesticLeagues[0]['round_id'];
        
		$leagueid = $domesticLeagues [0] ["competition_id"];
		$view->leagueid = $leagueid;
		$view->selected = 'today';

		$this->isFavoriteCompetition($view , $leagueid);
		
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $countryId );
		$continentData = self::$regionGroupNames [$regionGroup];
		
		if ($this->_request->isPost ()) {
			
			//get variable post from dropdown
			$currentRoundId = $this->_request->getParam ( 'roundId', 0 );
			$view->currentRoundId = $currentRoundId;			
			$leagueid = $this->_request->getParam ( 'leagueId', 0 );
			$view->leagueid = $leagueid;

		} else {
			
			// set up first default season "active" order by (first one is the most recent)
			$initRoundId = $domesticLeagues [0] ["round_id"];
			$initSeasonId = $domesticLeagues [0] ["season_id"];
			$view->currentRoundId = $initRoundId;
		}

		//Get the National Teams for a Country
		$teams = new Team ();
		$nationalTeams = $teams->selectNationalTeamByCountryType ( $countryId );
		$view->nationalTeams = $nationalTeams;
		
		$view->nationalTeamId = $nationalTeams [0] ["team_id"];
		$view->nationalTeamName = $nationalTeams [0] ["team_name"];
		
		$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		$this->breadcrumbs->addStep ( $view->countryName );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'country.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showtopscorersAction() {
		
		$view = Zend_Registry::get ( 'view' );
		//get Top Scorers by Country
		//$leagueround = ( int ) $this->_request->getParam ( 'roundid', 0 );
		$leagueid = ( int ) $this->_request->getParam ( 'leagueid', 0 );
		$league = new LeagueCompetition ();
		$season = new Season();
		//$gsstanding = new GoalserveStanding();
		//$rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $leagueid );
		
		$seasonActive = $season->getLeagueSeasonsActive($leagueid);
		//Zend_Debug::dump($seasonActive);
		$leaguetopscorers = $league->getTopScorersPerSeason($seasonActive[0]['season_id'],null,10,null,null);

		$view->topscorercomp = $leaguetopscorers;	
		$this->_response->setBody ( $this->view->render ( 'topscorersview.php' ) );
	
	}
	
    public function showcompetitionAction() {

        $view = Zend_Registry::get ( 'view' );
        $config = Zend_Registry::get ( 'config' );
        $session = new Zend_Session_Namespace('userSession');
        $leagueid = $this->_request->getParam ( 'compid', 0 );
		$timezone =  $this->_request->getParam( 'timezone', '+00:00' ); //default is GMT
		
        //Competition/League Information
        $league = new LeagueCompetition ();
        $competitionRow = $league->findCompetitionById ( $leagueid );
        $view->compName = $competitionRow ['competition_name'];
        $view->comp_gs_id = $competitionRow['competition_gs_id'];
        $view->leagueId = $leagueid;
        $view->compType = $competitionRow ['type'];
        $view->compFormat = $competitionRow ['format'];
        $view->regionId = $competitionRow ['region_id'];
        $view->regionName = $competitionRow ['region_name'];
        $view->regionGroupId = $competitionRow ['region_group_id'];
        $view->regionGroupName = $competitionRow ['region_group_name'];
        $view->compRegional = $competitionRow ['regional'];
        $view->countryId = $competitionRow ['country_id'];
        $view->countryCodeIso = $competitionRow ['country_code_iso2'];
        $view->countryName = $competitionRow ['country_name'];
        $countryId = $competitionRow ['country_id'];
        $countryname = $competitionRow ['country_name'];
        $path_comp_logos = $config->path->images->complogos . $leagueid.".gif" ;
        if (file_exists($path_comp_logos)) {
        	$view->imagefacebook = "http://www.goalface.com/public/images/competitionlogos/".$leagueid.".gif";
        } else {
        	$view->imagefacebook = "http://www.goalface.com/public/images/flags/".$countryId.".png";
        }

		//left menu passing variables
		$menuSelected = 'competition';
		$roundmenuSelected = 'none';
		$submenuSelected = 'none';
		$view->menuSelected = $menuSelected;
		$view->roundmenuSelected = $roundmenuSelected;
		$view->submenuSelected = $submenuSelected;
		
		//Season Information and current active season
		$season = new Season ();
		$round = new Round ();
		$group = new Groupp ();
		
		$seasonRow = $season->getLeagueSeasons ( $leagueid );		
		$season_active = $season->getLeagueSeasonsActive ( $leagueid );
		$seasonActive = $season_active [0] ["season_id"];
		$view->seasonTitle = $season_active [0] ["title"];
		$view->seasonList = $seasonRow;
		
		
		if ($this->_request->isPost ()) {
			//User coming from dropdown season selection
			$seasonId = $this->_request->getParam ( 'seasonId', 0 );

			$this->view->seasonId = $seasonId;
			
			//rounds per archived season
			$roundRow = $round->getSeasonRounds ( $seasonId );
			$roundListTable = $round->getRoundsPerSeasonTable ( $seasonId );
			$roundRowCup = $round->selectRoundsByType($seasonId,'cup');
			
			$roundId = $roundRow [0] ["round_id"]; //active
			$view->roundId = $roundId;
			$view->roundName = $roundRow [0] ["round_title"];
			$view->roundList = $roundRow;
			$view->totalrounds = count ( $roundRow );
			$view->roundmenuSelected = $roundId;
		
		} else {
			//Default only when page loads for the first time
			$this->view->seasonId = $seasonActive;
			$this->view->seasonActive = $seasonActive;
			$seasonId = $seasonActive;
			
			//get all rounds per current season active
			$roundRow = $round->getSeasonRounds ( $seasonId );
			$roundRowCup = $round->selectRoundsByType($seasonId,'cup');

			$roundName = null;
			if (sizeof ( $roundRow ) == 1) {				
				$roundId = $roundRow [0] ["round_id"];
				$roundName = $roundRow [0] ["round_title"];
				$roundType = $roundRow [0] ["type"];			
			} else {
				//add HERE else here for season with multiple rounds already in the DB
				$roundId = $roundRow [0] ["round_id"];
				$roundName = $roundRow [0] ["round_title"];
				$roundType = $roundRow [0] ["type"];
				$view->roundmenuSelected = $roundId;	
			}
			
			$view->roundId = $roundId; //Active Round 
			$view->roundName = $roundName;
			$view->roundType = $roundType;
			$view->roundList = $roundRow;
			$view->totalrounds = count($roundRow);

		}

		$team = new Team ();
		$player = new Player ();
		$match = new Matchh ();	

		//get All Domestic and Regional competitions by country and region where the country is located
		$dcountry = $league->findDomesticCompetitionsByCountry ( $competitionRow ['country_id'] ); //By Country
		$rcountry = $league->findRegionalCompetitionsByRegion ( $competitionRow ['region_group_id'] );
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;


        $gsstanding = new GoalserveStanding();
        $rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $leagueid );

        $view->feed_standings = $rowstanding['description'];
        $view->feed_topscorers = $rowstanding['description_topscorers']; 
        
        $view->gs_table = null;
		
        //Prepare seasonid, roundi, knockout stages id, for total matches)
   		$has_group_stage = 0;
		$cont = 0;
		$contcup =0;
        $number_rounds = count($roundRow);
        $number_rounds_cup = count($roundRowCup);
  
        //List of ALL rounds
		$round_list = ''; 
		$round_list_knockout = '';
		
		//All Rounds 
		foreach ($roundRow as $round) {
			if ($cont < $number_rounds) {
				if($round_list) {
					$round_list .= ",";
				}
				$round_list .= $round['round_id'];
			}
		$cont++;
		}

		if ($competitionRow ['format'] == 'Domestic league') {
			
		    //$allMatches = $match->selectTotalPlayedMatchesBySeason ( null, null, $roundId );
			$allMatches = $match->selectTotalPlayedMatchesBySeason2($seasonId,$round_list,null,$timezone,count($roundRow));
		    $nextMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Playing' );

                    if ($nextMatch == null) {
                       $nextMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Fixture' );
                    }

                    $previousMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Played' );
                    $view->allMatchesCompetition = $allMatches;
                    $view->nextMatch = $nextMatch;
                    $view->previousMatch = $previousMatch;

                    if ($rowstanding != null) {  
                        $feedpath = 'standings/'. $rowstanding['description']; 
						            $leagueTable = parent::getGoalserveFeed($feedpath);
                        //$rounds = $leagueTable->xpath("//standings/tournament");
                        $table= $leagueTable->xpath("//standings/tournament");
                        $view->hasgroups = count($table);  
                        //$table = $leagueTable->xpath("//standings/tournament[@stage_id='".$rowstanding['round_id']."']/team");
                        $this->view->leagueTable = $table;
                        $gs_standing_view = 1;
                        $view->gs_table = $gs_standing_view;
                    } else {
                        $gs_standing_view = NULL;
                    }
  			
 
				//get Top Scorers
				$leaguetopscorers = $league->getTopScorersPerSeason($seasonActive,null,10,null,null);
				$leagueyellowcards = $league->getYellowCardsPerSeason($seasonActive,null,10,null);
				$leagueredcards = $league->getRedCardsPerSeason($seasonActive,null,10,null);					
				$view->topscorercomp = $leaguetopscorers;
				$view->leagueyc = $leagueyellowcards;
				$view->leaguerc = $leagueredcards;				
		}

		if ($competitionRow ['format'] == 'Domestic cup') {

			$cont = 0;
            $number_rounds = count($roundRow);
            //List of ALL rounds
			$round_list = ''; 
			foreach ($roundRow as $round) {
				if ($cont < $number_rounds) {
					if($round_list) {
						$round_list .= ",";
					}
					$round_list .= $round['round_id'];
				}
			$cont++;
			}
			
			
			//Next and Previous Match 
            $nextMatch = $match->selectNextPreviousMatchByRound ( $roundId,'Playing',$competitionRow ['format']);
            if ($nextMatch == null) {
                 $nextMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Fixture',$competitionRow ['format']);
            }
            $previousMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Played',$competitionRow ['format']);
            $view->nextMatch = $nextMatch;
            $view->previousMatch = $previousMatch;
            
            $allMatches = $match->selectTotalPlayedMatchesBySeason2($seasonId,$round_list,null,$timezone,$number_rounds);		  
		  	$view->allMatchesCompetition = $allMatches;
		  	
		  	
		  	$leaguetopscorers = $league->getTopScorersPerSeason($seasonActive,null,10,null);
			$leagueyellowcards = $league->getYellowCardsPerSeason($seasonActive,null,10,null);
			$leagueredcards = $league->getRedCardsPerSeason($seasonActive,null,10,null);
					
			$view->topscorercomp = $leaguetopscorers;
			$view->leagueyc = $leagueyellowcards;
			$view->leaguerc = $leagueredcards;
			
		}
		
		if ($competitionRow ['format'] == 'International cup') {
			
        $todaydate = new Zend_Date ();
        $view->todaysdate = $todaydate;

			  //competition type = international  type = clubs - (libertadores, champions league etc)
        if ($competitionRow ['type'] == 'club') {      	            	   	
            $knockoutstage = array();
    		$has_group_stage = 0;
            $cont = 0;
            $number_rounds = count($roundRow);
            //List of ALL rounds
			$round_list = ''; 
			$round_list_final_stages = '';
			foreach ($roundRow as $round) {
				if ($round['type'] == 'table') {
					//get table standings
					if ($rowstanding['description'] !=null) {
						$feedpath = 'standings/'. $rowstanding['description'];
						$xmlstandings = parent::getGoalserveFeed($feedpath);
						$view->leagueTable = $xmlstandings;
					} 
					$has_group_stage = 1;
					$view->submenuSelected = 'tables';
				}

				///get group stage and knockout round list
				if ($round['type'] != 'cup1') {
					
						if($round_list_final_stages) {
							$round_list_final_stages .= ",";
						}	
						$round_list_final_stages .= $round['round_id'];
						$cont++;
				} 

				if($round_list) {
						$round_list .= ",";
				}					
				$round_list .= $round['round_id'];				
			}
			
			// number of rounds on group stage
			$view->knockoutstage = $cont;	
			$view->hasgroups = $has_group_stage;
    			
			// Knockout stage rounds exist
			if ($has_group_stage == 1) {
				$roundList = $round_list_final_stages;
				$number_rounds = $cont;
			} else {
				$roundList = $round_list;
				$number_rounds = count($roundRow);
			}

			//get all matches for the actual roundlist
			$allMatches = $match->selectTotalPlayedMatchesBySeason2($seasonId,$roundList,null,$timezone,$number_rounds);
    		$view->allMatchesCompetition = $allMatches;	


    		//Next and Previous Match 
            $nextMatch = $match->selectNextPreviousMatchByRound ( $roundList,'Playing',$competitionRow ['format']);
            if ($nextMatch == null) {
                 $nextMatch = $match->selectNextPreviousMatchByRound ( $roundList, 'Fixture',$competitionRow ['format']);
            }
            $previousMatch = $match->selectNextPreviousMatchByRound ( $roundList, 'Played',$competitionRow ['format']);
            $view->nextMatch = $nextMatch;
            $view->previousMatch = $previousMatch;
            //get Top Scorers
            $leaguetopscorers = null;
            $leaguetopscorers = $league->getTopScorersPerSeason($seasonActive,null,10,$roundList);

		  	$leagueyellowcards = $league->getYellowCardsPerSeason($seasonActive,null,10,$roundList);
	      	$leagueredcards = $league->getRedCardsPerSeason($seasonActive,null,10,$roundList);
		  	$view->topscorercomp = $leaguetopscorers;
		  	$view->leagueyc = $leagueyellowcards;
		 	$view->leaguerc = $leagueredcards;

  
           } else { 
                
            	// competition type = international  type = national team - (world cup, africa cup of nations, euro  etc)
              $knockoutstage = array();
            	$has_group_stage = 0;
            	$cont = 0;
            	$number_rounds = count($roundRow);
            	//List of ALL rounds
        				$round_list = ''; 
        				//List of ALL rounds final stages ( group stage, quarter, semi , final)
        				$round_list_final_stages = '';
        				foreach ($roundRow as $round) {
        					if ($cont < $number_rounds) {
        						if($round_list) {
        							$round_list .= ",";
        						}
        						$round_list .= $round['round_id'];
        						
        						if ($round['type'] == 'table' || $round['type'] == 'cup') {
        						    if($round_list_final_stages) {
        						        $round_list_final_stages .= ",";
        						    }
        						    $round_list_final_stages .= $round['round_id'];
        					    }
        
        						if ($round['type'] == 'table') {
        							//get table standings
        							if ($rowstanding['description'] !=null) {
        								$feedpath = 'standings/'. $rowstanding['description'];
        								$xmlstandings = parent::getGoalserveFeed($feedpath);
        								$view->leagueTable = $xmlstandings;
        							} 
        							$has_group_stage = 1;
        
        						} elseif ($round['type'] == 'cup') {
        						    //List Array of all rounds knockout
        							$knockoutstage [] = $round['round_id'];
        						}
        						
        					}
        					$cont++;
        				}

        				$view->hasgroups = $has_group_stage;
        				$roundList = $round_list;				
        				$view->knockoutstage = count($knockoutstage);
        				
            	  $allMatches = $match->selectTotalPlayedMatchesBySeason ( null, null, $roundId );
            	  $nextMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Playing' );

                if ($nextMatch == null) {
                		$nextMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Fixture' );
                }
            	  
        	  	$previousMatch = $match->selectNextPreviousMatchByRound ( $roundId, 'Played' );
        	  	$view->allMatchesCompetition = $allMatches;
        	  	$view->nextMatch = $nextMatch;
        	  	$view->previousMatch = $previousMatch;
                    	
				//get table standings
				$xmlstandings = null;
				if ($rowstanding['description'] !=null) {
					$feedpath = 'standings/'. $rowstanding['description'];
					$xmlstandings = parent::getGoalserveFeed($feedpath);
					$view->leagueTable = $xmlstandings;
				} 
				$league = new LeagueCompetition();
				if ($has_group_stage == 1) {
				    $round = $round_list_final_stages;
				} else {
				    $round = null;
				}
                
             
				$leaguetopscorers = $league->getTopScorersPerSeason($seasonActive,null,10,$round);
				$leagueyellowcards = $league->getYellowCardsPerSeason($seasonActive,null,10,$round);
				$leagueredcards = $league->getRedCardsPerSeason($seasonActive,null,10,$round);
				
				$view->topscorercomp = $leaguetopscorers;
				$view->leagueyc = $leagueyellowcards;
				$view->leaguerc = $leagueredcards;
				$view->feed_topscores = null;

            }
            
		}

		// Get teams from season/competition active to show on featured teams per competition
		 if($session->userId != null) {
		     $view->featuredTeams = $team->selectTeamsBySeason ( $seasonId, 6,$session->userId );
		     $view->featuredPlayers = $player->findPlayersBySeason ( $seasonId, 6,$session->userId );
		 } else {
		     $view->featuredTeams = $team->selectTeamsBySeason ( $seasonId, 6, null);
		     $view->featuredPlayers = $player->findPlayersBySeason ( $seasonId, 6, null );
		 }
        
		
		// Declare title, keywords, and description objects
		$title = new PageTitleGen ();
		$keywords = new MetaKeywordGen ();
		$description = new MetaDescriptionGen ();
		
		// Set title, metakeywords, and MetaDescription values on VIEW
		$view->title = $title->getPageTitle ( $competitionRow, PageType::$_REGIONAL_LEAGUE );
		$view->keywords = $keywords->getMetaKeywords ( $competitionRow ['competition_name'], PageType::$_REGIONAL_LEAGUE );
		$view->description = $description->getMetaDescription ( $competitionRow ['competition_name'], PageType::$_REGIONAL_LEAGUE );
		
		// Check if user has competition as one of his/her favorite
		$this->isFavoriteCompetition($view , $leagueid);
		
		// Get all comments for competition
		$comment = new Comment ();
		$leagueComments = $comment->findComments ( $leagueid, Constants::$_COMMENT_COMPETITION, 10 );
		$totalComments = $comment->findComments ( $leagueid, Constants::$_COMMENT_COMPETITION );
		$view->totalGoalShouts = count ( $totalComments );
		$view->comments = $leagueComments;

		//teams world cup
		$teamseasonstats = new TeamSeasonStats ();
		$view->teamsworldcup = $teamseasonstats->selectTeamSeasonStatsByCompetition ( 72 );
		$teamappearances = $teamseasonstats->getAllSeasonsPlayed ( 10, $leagueid );
		$view->teamappear = $teamappearances;
		

		//breadcrumbs
		$regionGroup = $league->findRegionGroupPerCountry ( $competitionRow ["country_id"] );
		$continentData = self::$regionGroupNames [$regionGroup];
		if ($competitionRow ['country_id'] != 1) {
			$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		}
		$view->regionNameTitle = $continentData [1];
		if ($competitionRow ['format'] != 'International cup') {
			$this->breadcrumbs->addStep ( $countryname, self::$urlGen->getShowDomesticCompetitionsByCountryUrl ( $countryname, $countryId, true ) );
		}
		$this->breadcrumbs->addStep ( $view->compName );
		$this->view->breadcrumbs = $this->breadcrumbs;	
		
		if ($leagueid == 25) {
			$feed = new Feed ( );
	    $feed_parsed = $this->loadRssFeed('http://www.uefa.com/rssfeed/uefaeuro/rss.xml');
	    $this->view->newsFeeds = $feed_parsed;
			$this->view->numberFeeds = 10;	
			
		    //$twitter_feed_parsed = $this->loadRssFeed('https://api.twitter.com/1/statuses/user_timeline.rss?screen_name=GoalFace');
		    //$this->view->twitterFeeds = $twitter_feed_parsed;
            //for dropdowns
			$teamleague = $team->selectTeamsBySeason(5103);
			//Zend_debug::dump($twitter_feed_parsed);
			$this->view->teamlist = $teamleague;
			
			$view->actionTemplate = 'competitioneuro.php';
		} elseif ($leagueid == 72) {
		
      $feed = new Feed ( );
	    $feed_parsed = $this->loadRssFeed('http://www.fifa.com/worldcup/news/rss.xml');
	    $this->view->newsFeeds = $feed_parsed;
			$this->view->numberFeeds = 10;	
			$teamleague = $team->selectTeamsBySeason(4770);
		  //Zend_debug::dump($twitter_feed_parsed);
	 	  $this->view->teamlist = $teamleague;
			$view->actionTemplate = 'competitionworldcup.php';
  			
    } else {
			$view->actionTemplate = 'competition.php';
		}
		
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	
	
	
	public function getleagueAction()
	{
		$leagueData = array("status"=>1);
		$view = Zend_Registry::get ( 'view' );
        $leagueid = $this->_request->getParam ( 'id', 0 );
        //Competition/League Information
        $league = new LeagueCompetition ();
        $competitionRow = $league->findCompetitionById ( $leagueid );
        $leagueData['league_details'] = $competitionRow;
		$isFavorite = "false";
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->email != null) {
			$userLeague = new UserLeague ();
			$row = $userLeague->findUserCompetition ( $session->userId, $leagueid );
			//Zend_debug::dump($row);
			if ($row != null) {
				$isFavorite = "true";
			}
		}
		 $leagueData['isFavorite'] = $isFavorite;
	 	//competition logos
        $config = Zend_Registry::get ( 'config' );
        $path_comp_logos = $config->path->images->complogos . $leagueid.".gif" ;
        if (file_exists($path_comp_logos)) {
        	$leagueData['league_image_path'] = Zend_Registry::get("contextPath") . "/utility/imageCrop?w=80&h=80&zc=1&src=" . Zend_Registry::get("contextPath") ."/public/images/competitionlogos/".$leagueid.".gif";
        } else {
        	$leagueData['league_image_path'] =  Zend_Registry::get("contextPath") ."/public/images/flags/".$competitionRow ['country_id'].".png";
        }
        
        	$this->_helper->viewRenderer->setNoRender(true);
		echo  Zend_Json::encode($leagueData);
        
	}
	
	public function showstatsAction() {
		$view = Zend_Registry::get ( 'view' );
		$leagueid = $this->_request->getParam ( 'compid', 0 );
		$seasonid = $this->_request->getParam ( 'seasonid', 'all' );
		//Competition/League Information
		$lea_comp = new LeagueCompetition ();
		$competitionRow = $lea_comp->findCompetitionById ( $leagueid );
		$view->compName = $competitionRow ['competition_name'];
		$view->leagueId = $leagueid;
		$view->seasonId = $seasonid;
		$view->compType = $competitionRow ['type'];
		$view->compFormat = $competitionRow ['format'];
		$view->regionId = $competitionRow ['region_id'];
		$view->regionName = $competitionRow ['region_name'];
		$view->regionGroupId = $competitionRow ['region_group_id'];
		$view->regionGroupName = $competitionRow ['region_group_name'];
		$view->compRegional = $competitionRow ['regional'];
		$view->countryId = $competitionRow ['country_id'];
		$view->countryCodeIso = $competitionRow ['country_code_iso2'];
		$view->countryName = $competitionRow ['country_name'];
		$countryId = $competitionRow ['country_id'];
		
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $countryId );
		$continentData = self::$regionGroupNames [$regionGroup];
		$view->regionNameTitle = $continentData [1];
		
		$season = new Season ();
		$round = new Round();
		//World Cup Data
		//$wcseasons = $season->getLeagueSeasons ( 72 );
		//$view->allworldcupseasons = $wcseasons;
		
		$roundRow = $round->getSeasonRounds ( $seasonid );
		$view->roundList = $roundRow;
			
		
        $gsstanding = new GoalserveStanding();
        $rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $leagueid );
        
	  	$leaguetopscorers = null;
            if ($rowstanding['description_topscorers'] != null) {
            	$feedpath = $rowstanding['description_topscorers'];
				$xmlfeedtopscore = parent::getGoalserveFeed($feedpath);
				$leaguetopscorers = $xmlfeedtopscore->xpath("/topscorers/tournament[@stage_id='".$rowstanding['round_id']."']/player");
				$view->topscorercomp = $leaguetopscorers;
            } else {
            	$league = new LeagueCompetition();
				$leaguetopscorers = $league->getTopScorersPerSeason($seasonid);
				$leagueyellowcards = $league->getYellowCardsPerSeason($seasonid);
				$leagueredcards = $league->getRedCardsPerSeason($seasonid);
				
				$view->topscorercomp = $leaguetopscorers;
				$view->leagueyc = $leagueyellowcards;
				$view->leaguerc = $leagueredcards;
            }
		
		$menuSelected = 'stats';
		$view->menuSelected = $menuSelected;
		
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $competitionRow ["country_id"] );
		$continentData = self::$regionGroupNames [$regionGroup];
		$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		$this->breadcrumbs->addStep ( $competitionRow ['competition_name'], self::$urlGen->getShowRegionalCompetitionsByRegionUrl (  $competitionRow ['competition_name'],$leagueid, true ) );
		$this->breadcrumbs->addStep ( 'Statistics' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$view->actionTemplate = 'viewstats.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	public function showstatsresultsAction() {
		$view = Zend_Registry::get ( 'view' );
		$seasonid = $this->_request->getParam ( 'seasonid', 'all' );
		$typestat = $this->_request->getParam ( 'typestat', 'tab1' );
		//Worldcup stats esto tiene q ir en el action
		$wordcupplayerstats = new WorldCupPlayerStats ();
		$teamseasonstats = new TeamSeasonStats ();
		$viewstatsresult = 'viewstatsresults.php';
		if ($typestat == 'tab1') {
			$result = $wordcupplayerstats->getAllGoalsScored ( null, $seasonid );
		} else if ($typestat == 'tab11') {
			$result = $wordcupplayerstats->getAllAssists ( null, $seasonid );
		} else if ($typestat == 'tab12') {
			$result = $wordcupplayerstats->getAllMinutesPlayed ( null, $seasonid );
		} else if ($typestat == 'tab2') {
			$result = $teamseasonstats->getAllGoalsScored ( null, 72, $seasonid );
			$viewstatsresult = "viewstatsresults2.php";
		} else if ($typestat == 'tab21') {
			$result = $teamseasonstats->getAllCleanSheets ( null, 72, $seasonid );
			$viewstatsresult = "viewstatsresults2.php";
		}
		$view->typestat = $typestat;
		//pagination - getting request variables
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$paginator = Zend_Paginator::factory ( $result );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( $this->getNumberOfItemsPerPage () );
		$this->view->paginator = $paginator;
		//esto tiene q ir en el action
		$this->_response->setBody ( $view->render ( $viewstatsresult ) );
	}
	
	public function showfeaturedcompetitionsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		
		// Declare title, keywords, and description objects
		$title = new PageTitleGen ();
		$keywords = new MetaKeywordGen ();
		$description = new MetaDescriptionGen ();
		
		// set title, metakeywords, and MetaDescription values on VIEW
		$view->title = $title->getPageTitle ( null, PageType::$_LEAGUES_FEATURED );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_LEAGUES_FEATURED );
		$view->description = $description->getMetaDescription ( null, PageType::$_LEAGUES_FEATURED );
		
		//featured leagues and tournaments
		$lea_comp = new LeagueCompetition ();
		$this->view->featuredLeagues = $lea_comp->findFeaturedCompetitions ( 20 );
		$view->regionGroupNames = self::$regionGroupNames;
		
		$this->breadcrumbs->addStep ( 'Featured' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$view->actionTemplate = 'featuredCompetitionsMore.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showcompetitionfulltableAction() {
		$view = Zend_Registry::get ( 'view' );
		/*$leagueid = $this->_request->getParam ( 'leagueid', 0 );
        $seasonid = $this->_request->getParam ( 'seasonid', 0 );
         */
		$roundId = $this->_request->getParam ( 'roundid', 0 );
		
		$round = new Round ();
		$roundRow = $round->fetchRow ( "round_id = " . $roundId );
		$seasonid = $roundRow->season_id;
		$roundType = $roundRow->type;
		$season = new Season ();
		$seasonRow = $season->fetchRow ( "season_id = " . $seasonid );
		$leagueid = $seasonRow->competition_id;

		$menuSelected = 'competition';
		$view->menuSelected = $menuSelected;
		$submenuSelected = 'tables';
		$view->submenuSelected = $submenuSelected;

		$lea_comp = new LeagueCompetition ();
		$compRow = $lea_comp->findCompetitionById ( $leagueid );
		
		$view->compName = $compRow ['competition_name'];
		$view->comp_gs_id = $compRow['competition_gs_id'];
		$view->compType = $compRow ['type'];
		$view->countryId = $compRow ['country_id'];
		$view->countryCodeIso = $compRow ['country_code_iso2'];
		$view->countryName = $compRow ['country_name'];
		$view->compType = $compRow ['type'];
		$view->compFormat = $compRow ['format'];
		$view->leagueId = $leagueid;
		$view->regionId = $compRow ['region_id'];
		$view->regionName = $compRow ['region_name'];
		$view->regionGroupId = $compRow ['region_group_id'];
		$view->regionGroupName = $compRow ['region_group_name'];
		$view->compRegional = $compRow ['regional'];
		
		$countryId = $compRow ['country_id'];
		$countryname = $compRow ['country_name'];
		
		$todaydate = new Zend_Date ();
		$view->todaysdate = $todaydate;
		
		//get All Domestic and Retional competitions by country and region where the country is located
		$dcountry = $lea_comp->findDomesticCompetitionsByCountry ( $compRow ['country_id'] ); //By Country
		$rcountry = $lea_comp->findRegionalCompetitionsByRegion ( $compRow ['region_group_id'] );
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;
		
		//Season Informattion and current active season
		$season = new Season ();
		$round = new Round ();
		$seasonRow = $season->getLeagueSeasons ( $leagueid );
		$seasonActive = $seasonRow [0] ["season_id"];
		$view->seasonList = $seasonRow;
		
		$roundRow = $round->getSeasonRounds ( $seasonActive );
		
		$seasonRow = $season->fetchRow ( 'season_id = ' . $seasonid );
		$this->view->seasonTitle = $seasonRow->title;
		$view->seasonId = $seasonid;
		$view->roundId = $roundId;
		//$view->seasonList = $seasonRow;
		

		//get rounds to display
		$roundlist = $round->getSeasonRounds ( $seasonid );
		$view->roundList = $roundlist;
		$view->totalrounds = count ( $roundlist );
		
		$view->currentActiveRound = $roundId;
		
		//get tables display
		
			
		//Build Tables based on type of competitions
		$gsstanding = new GoalserveStanding();
		$rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $leagueid );

		if ($compRow ['format'] == 'Domestic league') {
			
			if ($compRow['competition_gs_id'] != null) {
				$leagueTable = null;
				//get table standings
				if ($rowstanding['description'] !=null) {
					$feedpath = 'standings/'. $rowstanding['description'];
					$leagueTableFeed = parent::getGoalserveFeed($feedpath);
					$leagueTable = $leagueTableFeed->xpath("//standings/tournament[@stage_id='".$rowstanding['round_id']."']/team");
				} 
				$view->hasgroups = 0; 
			}
		}
		

		if ($compRow ['format'] == 'International cup') {
			
			$group = new Groupp ();
			$grouplist = $group->getGroupsPerRound ( $roundId );

			if ($grouplist !=null) {
				//get table standings
				if ($rowstanding['description'] !=null) {
					$feedpath = 'standings/'. $rowstanding['description'];
					$leagueTable = parent::getGoalserveFeed($feedpath);
					//Zend_Debug::dump($xmlstandings);
				} 
				$view->hasgroups = 1; 
			} else {
				
				if ($rowstanding['description'] !=null) {
					$feedpath = 'standings/'. $rowstanding['description'];
					$leagueTable = parent::getGoalserveFeed($feedpath);

				}
				
				
			}

		}

		$this->view->leagueTable = $leagueTable;
		//Zend_debug::dump($leagueTable);	

		//check if user has competition as one of his/her favorite
		$this->isFavoriteCompetition($view , $leagueid);
		
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $compRow ['country_id'] );
		$continentData = self::$regionGroupNames [$regionGroup];
		if ($compRow ['country_id'] != 1) {
			$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		}
		$view->regionNameTitle = $continentData [1];
		if ($compRow ['format'] != 'International cup') {
			$this->breadcrumbs->addStep ( $countryname, self::$urlGen->getShowDomesticCompetitionsByCountryUrl ( $countryname, $countryId, true ) );
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ['competition_id'], True ) );
		} else {
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ["competition_id"], True ) );
		}
		$this->breadcrumbs->addStep ( 'Full Table' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'competitiontable.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function addfavoriteAction() {
		$urlGen = new SeoUrlGen ();
		$session = new Zend_Session_Namespace ( "userSession" );
		$leagueId = $this->_request->getPost ( 'leagueId' );
		$fromPage = $this->_request->getPost ( 'fromPage', '' );
		$userId = $session->userId;
		$arrayExploded = explode ( '*', $leagueId );
		$leagueId = $arrayExploded [0];
		$countryId = $arrayExploded [1];
		if(empty($countryId))
		{
			//Get the country id from the db instead
			$league = new LeagueCompetition ();
        	$competitionRow = $league->findCompetitionById ( $leagueId );
        	$countryId= $competitionRow['country_id'];
		}
		$user_league = new UserLeague ();
		
		$checkEmail = $this->_request->getPost ( 'updatesCheck' );
		$frequency = '0';
		if($checkEmail == '1'){
			$frequency = Constants::$_48HOURSINADVANCE; 
		}
		
		//If user is not logged in, return this message
		if(!$userId && $this->getRequest()->isXmlHttpRequest() )
		{
			$this->_helper->viewRenderer->setNoRender(true);
			echo  Zend_Json::encode(array("status"=>0, "msg"=>"You need to login to subscribe to this competition's updates!"));
			return;
		}
		
		$data = array ('user_id' => $userId, 'competition_id' => $leagueId, 'country_id' => $countryId,'alert_email' =>$checkEmail , 'alert_frecuency_type' =>$frequency );
		$exist = $user_league->findUserCompetition ( $userId, $leagueId );
		if ($exist == null) 
		{
			$user_league->insert ( $data );
		} 
		else 
		{
		//Return a JSON action
			if($this->getRequest()->isXmlHttpRequest() )
			{
				$this->_helper->viewRenderer->setNoRender(true);
				echo  Zend_Json::encode(array("status"=>0, "msg"=>"You've already subscribed to this team's udpates!"));
				return;
			}
			else
			{
			
				//userleague already exists show Error Message
				echo "ko";
				return;
			}
		}
		
		//insert favorite activity
		$competition = new LeagueCompetition ();
		$country = new Country ();
		$data = $competition->findCompetitionById ( $leagueId );
		$dataCountry = $country->findCountryById ( $data ["country_id"] );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$screenName = $session->screenName;
		
		$competition_name_seo = $urlGen->getShowTournamentUrl ( $data ["competition_name"], $data ["competition_id"], true );
		
		if ($data ["regional"] == "1") {
			$countryRegionNameSeo = $data ['region_group_name'];
			$regionName = self::$regionGroupNames [mb_strtolower ( $data ['region_group_name'] )];
			$url = $urlGen->getShowRegionUrl ( strval ( $regionName [0] ), True );
		} elseif ($data ["regional"] == "2") {
			$countryRegionNameSeo = $data ['region_group_name'];
			$regionName = self::$regionGroupNames [mb_strtolower ( $data ['region_group_name'] )];
			$url = $urlGen->getShowRegionUrl ( strval ( $regionName [0] ), True );
		
		} elseif ($data ["regional"] == "0") {
			$countryRegionNameSeo = $data ["country_name"];
			$url = $urlGen->getShowDomesticCompetitionsByCountryUrl ( $dataCountry ['country_name'], $dataCountry ['country_id'], true );
		}
		$config = Zend_Registry::get ( 'config' );
		$imageName = $config->path->images->fanphotos . $session->mainPhoto;
		$variablesToReplace = array ('username' => $screenName, 'competition_name_seo' => $competition_name_seo, 'competition_name' => $data ['competition_name'], 'country_name_seo' => $url, 'country_name' => $countryRegionNameSeo, 'gender' => ($session->user->gender == 'm' ? 'his' : 'her') );
		$activityType = Constants::$_ADD_FAVORITE_COMPETITION_ACTIVITY;
		$activityAddFavoriteCompetition = new Activity ();
		$activityAddFavoriteCompetition->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId, '1', 0, null, null, $imageName );
		//Return a JSON action
		if($this->getRequest()->isXmlHttpRequest() )
		{
			$this->_helper->viewRenderer->setNoRender(true);
			echo  Zend_Json::encode(array("status"=>1, "msg"=>"The competition was successfully added to your favorites", "debug"=>$rowset));
			return;
		}
		if ($fromPage == 'edit') {
			$this->_redirect ( "/profile/editfavorities/editAction/comp/page/" );
		}
	}
	
	public function removefavoriteAction() {
		$session = new Zend_Session_Namespace ( "userSession" );
		$leagueId = $this->_request->getPost ( 'leagueId' );
		$arrayExploded = explode ( '*', $leagueId );
		$leagueId = $arrayExploded [0];
		$countryId = $arrayExploded [1];
		$user_league = new UserLeague ();
		$user_league->deleteUserLeague ( $session->userId, $leagueId );
	
	}
	
	public function removefavoriteleaguesAction() {
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		$arrayFavorites = $this->_request->getPost ( 'arrayFavorities' );
		//echo($session->userId);
		$user_league = new UserLeague ();
		foreach ( $arrayFavorites as $item ) {
			$arrayExploded = explode ( '*', $item );
			$user_league->deleteUserLeague ( $session->userId, $arrayExploded [0], $arrayExploded [1] );
		}
		$this->_redirect ( "/profile/editfavorities/editAction/comp/page/" );
	
	}
	
	public function findfavoriteuserleaguesAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$userLeague = new UserLeague ();
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
		$currentUserId = $this->_request->getParam ( "u", $session->userId );
		$favLeagues = $userLeague->findAllUserCompetitions ( $currentUserId, 0 );
		
		$view->favLeagues = $favLeagues;
		
		$this->_response->setBody ( $view->render ( 'favoritecompresult.php' ) );
	}
	
	public function addcompetitionAction() {
		$view = Zend_Registry::get ( 'view' );
		$this->_response->setBody ( $view->render ( 'addCompetitionModal.php' ) );
	}
	
	function autocompleteleagueAction()
	{
		$leagueCompetition = new LeagueCompetition();
		$leagueName = $this->_request->getParam ( 'term', null );
		$callback = $this->_request->getParam ( 'callback', null );
		$results = $leagueCompetition->findLeagueAutoComplete($leagueName);
		$this->_helper->viewRenderer->setNoRender(true);
		echo $callback . "(" . Zend_Json::encode($results) . ")";
	}
	
	public function searchcompetitionsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$countryId = $this->_request->getParam ( "countryid", null );
		$criteria = $this->_request->getParam ( "criteria", null );
		$leagueComp = new LeagueCompetition ();
		$result = $leagueComp->findDomesticCompetitionsByCountry ( $countryId, 'y', $criteria );
		
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		$paginator = Zend_Paginator::factory ( $result );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$view->paginator = $paginator;
		$this->_response->setBody ( $view->render ( 'leaguessearchresult.php' ) );
	}
	
	public function searchcompetitionsselectAction() {
		
		$countryId = ( int ) $this->_request->getParam ( "id", null );
		
		$leagueComp = new LeagueCompetition ();
		$result = null;
		if ($countryId >= 1 && $countryId <= 5) {
			$result = $leagueComp->findRegionalCompetitionsByContinent2 ( $countryId );
		} else {
			$result = $leagueComp->findDomesticCompetitionsByCountry ( $countryId );
		}
		echo '<option value="0" selected>Select League or Tournament</option>';
		foreach ( $result as $data ) {
			echo '<option value=' . trim ( $data ['competition_id'] ) . '>' . trim ( $data ['competition_name'] );
		
		}
		if ($countryId == 5) {
			echo '<option value=\'284\'>World Club Championship';
		}
	}
	
	public function showfullscoreboardAction() {
		$view = Zend_Registry::get ( 'view' );
		$seasonId = $this->_request->getParam ( 'seasonid', null );
		$roundId = $this->_request->getParam ( 'roundid', null );
		$default = $this->_request->getParam ( 'sm', 'scores' );	

		$submenuSelected = $this->_request->getParam ( 'sm', 'scores' );
		$menuSelected = 'competition';
		
		$view->menuSelected = $menuSelected;
		$view->submenuSelected = $submenuSelected;
		
		if ($submenuSelected == 'scores') {
			$view->scoreTypeTitle = "Scores";
		} else {
			$view->scoreTypeTitle = "Schedule";
		}

		$season = new Season ();
		$round = new Round ();
		
		
		if($seasonId != null){
			$seasonRow = $season->fetchRow ( "season_id = " . $seasonId );
			$leagueid = $seasonRow->competition_id;
			$roundList = $round->getSeasonRounds ($seasonId);
			$roundActive = $roundList [0] ["round_id"];
			$view->roundActive = $roundActive;
		}
		
		if($roundId != null){
			$roundRow = $round->fetchRow ( "round_id = " . $roundId );
			$seasonId = $roundRow->season_id;
			$roundType = $roundRow->type;
			$roundList = $round->getSeasonRounds ($seasonId);			
			$seasonRow = $season->fetchRow ( "season_id = " . $seasonId );		
			$leagueid = $seasonRow->competition_id;
			$currentRoundId = $roundList [0] ["round_id"];
			$currentRoundType = $roundList [0] ["type"];
			$view->roundActive = $currentRoundId;
		}
			
		$knockoutstage = null;	
		foreach ($roundList as $round) {
			if ($round['type'] == 'cup') {
				$knockoutstage [] = $round['round_id'];	
			}
		}
		
		$view->roundList = $roundList;
		$view->totalrounds = count ( $roundList );
		$this->view->seasonTitle = $seasonRow->title;
		$view->knockoutstage = count($knockoutstage);
		//Zend_Debug::dump ( count($knockoutstage) );
		$view->default = $default;
		
		$todaydate = new Zend_Date ();
		$view->todaysdate = $todaydate;
	    
	    //Competition/League Information
		$lea_comp = new LeagueCompetition ();	
		$compRow = $lea_comp->findCompetitionById ( $leagueid );
		$view->compName = $compRow ['competition_name'];
		$view->leagueId = $leagueid;
		$view->compType = $compRow ['type'];
		$view->compFormat = $compRow ['format'];
		$view->regionId = $compRow ['region_id'];
		$view->regionName = $compRow ['region_name'];
		$view->regionGroupId = $compRow ['region_group_id'];
		$view->regionGroupName = $compRow ['region_group_name'];
		$view->compRegional = $compRow ['regional'];
		$view->countryId = $compRow ['country_id'];
		$view->countryCodeIso = $compRow ['country_code_iso2'];
		$view->countryName = $compRow ['country_name'];
		$countryId = $compRow ['country_id'];
		$countryname = $compRow ['country_name'];
	    

		//Get list of all seasons and current active season
		$seasonRow = $season->getLeagueSeasons ( $leagueid );
		$seasonActive = $seasonRow [0] ["season_id"];
		$view->seasonList = $seasonRow;
		

		if ($this->_request->isPost ()) {
			//User coming from dropdown season selection 
			$seasonId = $this->_request->getParam ( 'seasonId', 0 );
			
			//$seasonDisplay = $seasonId;
			$this->view->seasonId = $seasonId;
			
			//rounds per archived season
			$roundRow = $round->getSeasonRounds ( $seasonId );
			$roundListTable = $round->getRoundsPerSeasonTable ( $seasonId );
			$roundRow = $round->getSeasonRounds ( $seasonId );
			//Zend_Debug::dump ( $roundRow );
			$roundId = $roundRow [0] ["round_id"]; //active
			$view->roundId = $roundId;
			$view->roundName = $roundRow [0] ["round_title"];
			$view->roundList = $roundRow;
			$view->totalrounds = count ( $roundRow );
			$view->roundmenuSelected = $roundId;
		
		} else {
			//Default only when page loads for the first time
			$this->view->seasonId = $seasonId;
			$this->view->seasonActive = $seasonId;
			//$seasonId = $seasonActive;
			$view->roundId = $roundId;
			//Zend_Debug::dump($roundId);
			// $view->roundName = $roundRow[0]["round_title"]; 
		}
		
		//check if user has competition as one of his/her favorite
		$this->isFavoriteCompetition($view , $leagueid);
		
		//breadcrumbs
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $compRow ["country_id"] );
		$continentData = self::$regionGroupNames [$regionGroup];
		if ($compRow ['country_id'] != 1) {
			$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		}
		$view->regionNameTitle = $continentData [1];
		if ($compRow ['format'] != 'International cup') {
			$this->breadcrumbs->addStep ( $countryname, self::$urlGen->getShowDomesticCompetitionsByCountryUrl ( $countryname, $countryId, true ) );
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ['competition_id'], True ) );
		} else {
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ["competition_id"], True ) );
		}
		$this->breadcrumbs->addStep ( 'Scoreboard' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$view->actionTemplate = 'fullScoreboard.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	private function buildLeftMenu($leagueId, $roundId, $view, $seasonId) {
		
		//get all seasons per competition in an array
		$season = new Season ();
		$seasonRow = $season->getLeagueSeasons ( $leagueId );
		$seasonActive = $seasonRow [0] ["season_id"];
		$view->seasonActive = $seasonId;
		$view->seasonList = $seasonRow;
		
		$round = new Round ();
		$lea_comp = new LeagueCompetition ();
		
		$competitionRow = $lea_comp->findCompetitionById ( $leagueId );
		$country = new Country ();
		$countryRow = $country->findCountryById ( $competitionRow ['country_id'] );
		$regionId = $countryRow->region_id;
		
		$dcountry = $lea_comp->findDomesticCompetitionsByCountry ( $competitionRow ['country_id'] ); //By Country
		$rcountry = $lea_comp->findRegionalCompetitionsByContinent ( $regionId, '1,2' ); //Continent
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;
		$view->compRegional = $competitionRow ['regional'];
		$view->leagueId = $leagueId;
		
		$roundRow = $round->getSeasonRounds ( $seasonId );
		
		if (sizeof ( $roundRow ) == 1) {
			$roundId = $roundRow [0] ["round_id"];
		} else {
			//add HERE else here for season with multiple rounds already in the DB
			

			$todaydate = new Zend_Date ();
			foreach ( $roundRow as $round ) {
				$startDate = strtotime ( $round ['start_date'] );
				$endDate = strtotime ( $round ['end_date'] );
				if ($todaydate->isLater ( $endDate ) and $todaydate->isEarlier ( $startDate )) {
					$roundId = $round ['round_id'];
				}
			}
		}
		
		$roundRow = $round->fetchRow ( 'round_id = ' . $roundId );
		$view->roundTitle = $roundRow->round_title;
		$view->roundId = $roundId;
		
		//$view->roundActive = $roundId;
		//$view->currentActiveRound = $roundId;
		$view->roundList = $roundRow;
		$view->compType = $competitionRow ['type'];
	
	}
	
	// Show featured player per league/tournament
	function showcompetitionplayersAction() {
		$view = Zend_Registry::get ( 'view' );
		$leagueid = $this->_request->getParam ( 'leagueid', 0 );
		$seasonId = $this->_request->getParam ( 'seasonid', 5103 );
		
		$lea_comp = new LeagueCompetition ();
		$compRow = $lea_comp->findCompetitionById ( $leagueid );
		$view->compName = $compRow ['competition_name'];
		$view->leagueid = $leagueid;
		$view->leagueId = $leagueid;
		$view->compType = $compRow ['type'];
		$view->compFormat = $compRow ['format'];
		$view->regionId = $compRow ['region_id'];
		$view->regionName = $compRow ['region_name'];
		$view->regionGroupId = $compRow ['region_group_id'];
		$view->regionGroupName = $compRow ['region_group_name'];
		$view->compRegional = $compRow ['regional'];
		$view->countryId = $compRow ['country_id'];
		$view->countryCodeIso = $compRow ['country_code_iso2'];
		$view->countryName = $compRow ['country_name'];
		$countryId = $compRow ['country_id'];
		$countryname = $compRow ['country_name'];
		
		$round = new Round ();
		$roundRow = $round->getSeasonRounds ( $seasonId );
				$roundName = null;
		if (sizeof ( $roundRow ) == 1) {				
			$roundId = $roundRow [0] ["round_id"];
			$roundName = $roundRow [0] ["round_title"];
			$roundType = $roundRow [0] ["type"];			
		} else {
			//add HERE else here for season with multiple rounds already in the DB
			$roundId = $roundRow [0] ["round_id"];
			$roundName = $roundRow [0] ["round_title"];
			$roundType = $roundRow [0] ["type"];
			$view->roundmenuSelected = $roundId;			
		}
		$view->roundId = $roundId; //Active Round 
		$view->roundName = $roundName;
		$view->roundType = $roundType;
		$view->roundList = $roundRow;
		$view->totalrounds = count($roundRow);
	
		
		$menuSelected = 'competition';
		$view->menuSelected = $menuSelected;
		$submenuSelected = 'players';
		$view->submenuSelected = $submenuSelected;
		
		
		$view->seasonId = $seasonId;
		$view->actionTemplate = 'competitionplayers.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	
	function showcompetitionsteamsAction() {
		$view = Zend_Registry::get ( 'view' );
		$roundId = $this->_request->getParam ( 'roundid', null );
		$season = new Season ();
		$round = new Round ();
		$roundRow = $round->fetchRow ( "round_id = " . $roundId );
		$seasonId = $roundRow->season_id;
		$roundType = $roundRow->type;
		$view->roundType = $roundType;
		$season = new Season ();
		$seasonRow = $season->fetchRow ( "season_id = " . $seasonId );
		$leagueid = $seasonRow->competition_id;
		
		$menuSelected = 'competition';
		$view->menuSelected = $menuSelected;
		$submenuSelected = 'teams';
		$view->submenuSelected = $submenuSelected;
		
		//Competition/League Information
		$lea_comp = new LeagueCompetition ();
		$compRow = $lea_comp->findCompetitionById ( $leagueid );
		$view->compName = $compRow ['competition_name'];
		$view->leagueid = $leagueid;
		$view->leagueId = $leagueid;
		$view->compType = $compRow ['type'];
		$view->compFormat = $compRow ['format'];
		$view->regionId = $compRow ['region_id'];
		$view->regionName = $compRow ['region_name'];
		$view->regionGroupId = $compRow ['region_group_id'];
		$view->regionGroupName = $compRow ['region_group_name'];
		$view->compRegional = $compRow ['regional'];
		$view->countryId = $compRow ['country_id'];
		$view->countryCodeIso = $compRow ['country_code_iso2'];
		$view->countryName = $compRow ['country_name'];
		$countryId = $compRow ['country_id'];
		$countryname = $compRow ['country_name'];
		
		$todaydate = new Zend_Date ();
			$view->todaysdate = $todaydate;
		
		//get All Domestic and Retional competitions by country and region where the country is located
		$dcountry = $lea_comp->findDomesticCompetitionsByCountry ( $compRow ['country_id'] ); //By Country
		$rcountry = $lea_comp->findRegionalCompetitionsByRegion ( $compRow ['region_group_id'] );
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;
		
		//Get list of all seasons and current active season
		$seasonRow = $season->getLeagueSeasons ( $leagueid );
		$seasonActive = $seasonRow [0] ["season_id"];
		$view->seasonList = $seasonRow;
		
		if ($this->_request->isPost ()) {
			//User coming from dropdown season selection
			$seasonId = $this->_request->getParam ( 'seasonId', 0 );
			$this->view->seasonId = $seasonId;
			$this->view->seasonActive = $seasonActive;
			//get Active Round for Season
			$roundRow = $round->getSeasonRounds ( $seasonId );
			//Zend_Debug::dump($roundRow);
			$activeRoundSeason = $roundRow [0] ["round_id"];
			$this->view->activeRoundSeason = $activeRoundSeason;
		} else {
			//Default only when page loads for the first time
			$this->view->seasonId = $seasonActive;
			$this->view->seasonActive = $seasonActive;
			$roundRow = $round->fetchRow ( 'round_id = ' . $roundId );
			$view->roundTitle = $roundRow->round_title;
			$view->roundId = $roundRow->round_id;
		}
		
		$seasonRow = $season->fetchRow ( 'season_id = ' . $seasonId );
		$this->view->seasonTitle = $seasonRow->title;
		$view->seasonId = $seasonId; // use this season anytime to get teams by season. 
		

		//get rounds to display
		$roundlist = $round->getSeasonRounds ( $seasonId );
		$view->roundList = $roundlist;
	
		$view->totalrounds = count($roundlist);
		
		
		$regionGroup = $lea_comp->findRegionGroupPerCountry ( $compRow ["country_id"] );
		$continentData = self::$regionGroupNames [$regionGroup];
		if ($compRow ['country_id'] != 1) {
			$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		}
		$view->regionNameTitle = $continentData [1];
		
		if ($compRow ['format'] != 'International cup') {
			$this->breadcrumbs->addStep ( $countryname, self::$urlGen->getShowDomesticCompetitionsByCountryUrl ( $countryname, $countryId, true ) );
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ['competition_id'], True ) );
		} else {
			$this->breadcrumbs->addStep ( $compRow ['competition_name'], self::$urlGen->getShowDomesticCompetitionUrl ( $compRow ['competition_name'], $compRow ["competition_id"], True ) );
		}
		$this->breadcrumbs->addStep ( 'Teams' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$this->isFavoriteCompetition($view , $leagueid);
		
		$view->actionTemplate = 'competitionsteams.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	/*public function showleagueroundtableAction() {
		$view = Zend_Registry::get ( 'view' );
		//get Full stats by player
		$seasonId = ( int ) $this->_request->getParam ( 'leagueSeasonId', 0 );
		$roundId = ( int ) $this->_request->getParam ( 'leagueRoundId', 0 );
		
		//get tables to display
		$standing = new Standing ();
		$leagueTable = $standing->getTeamLeagueStanding ( $roundId );
		$view->leagueTable = $leagueTable;
		
		//Zend_Debug::dump($leagueTable);
		$this->view->roundDisplayId = $roundId;
		$this->_response->setBody ( $this->view->render ( 'leagueroundtableview.php' ) );
	
	}*/
	
	
	public function showcompetitiontablefbAction () {
		$view = Zend_Registry::get ( 'view' );
		$leagueid = $this->_request->getParam ( 'compid', 0 );
		$roundid = $this->_request->getParam ( 'roundid', 0 );
		$fb = $this->_request->getParam( 'fb' ,'yes');
	    $lea_comp = new LeagueCompetition ();
		$season = new Season ();
		$round = new Round ();
		$roundRow = $round->findLeagueSeasonRound($roundid);
		$leagueid = $roundRow['competition_id'];
		
		//Competition/League Information
		$competitionRow = $lea_comp->findCompetitionById ($leagueid);
		$view->compName = $competitionRow ['competition_name'];
		$view->leagueId = $leagueid;		
		$view->compType = $competitionRow ['type'];
		$view->compFormat = $competitionRow ['format'];
		$view->regionId = $competitionRow ['region_id'];
		$view->regionName = $competitionRow ['region_name'];
		$view->regionGroupId = $competitionRow ['region_group_id'];
		$view->regionGroupName = $competitionRow ['region_group_name'];
		$view->compRegional = $competitionRow ['regional'];
		$view->countryId = $competitionRow ['country_id'];
		$view->countryCodeIso = $competitionRow ['country_code_iso2'];
		$view->countryName = $competitionRow ['country_name'];
		$countryId = $competitionRow ['country_id'];
		$countryname = $competitionRow ['country_name'];
		$view->roundId = $roundid;
		//Season Information and current active season

		$gsstanding = new GoalserveStanding();
        $rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $leagueid );
        
        $view->feed_standings = $rowstanding['description'];
        $view->feed_topscorers = $rowstanding['description_topscorers']; 
        
        $view->gs_table = null;

		if ($competitionRow ['format'] == 'Domestic league') {

			if ($rowstanding != null) {  
				$feedpath = 'standings/'. $rowstanding['description']; 
				$leagueTable = parent::getGoalserveFeed($feedpath);
				$table= $leagueTable->xpath("//standings/tournament");
				$number_rounds = count($table);
				$this->view->leagueTable = $table;
				$gs_standing_view = 1;
				$view->gs_table = $gs_standing_view;
				$view->number_table_rounds  = $number_rounds;
			} else {
			  $this->view->leagueTable = null;
			}			
		}
		
		if ($competitionRow ['format'] == 'International cup') {
		       $todaydate = new Zend_Date ();
            	$view->todaysdate = $todaydate;
            
			// competition type = international  type = clubs - (libertadores, champions league etc)
            if ($competitionRow ['type'] == 'club') {      	
             
            	    if ($rowstanding != null) {  
                        $feedpath = 'standings/'. $rowstanding['description']; 
						$leagueTable = parent::getGoalserveFeed($feedpath);
						$table= $leagueTable->xpath("//standings/tournament");
                        //$number_rounds = count($table);
                        $this->view->leagueTable = $table;
                        $gs_standing_view = 1;
                        $view->gs_table = $gs_standing_view;
                        //$view->number_table_rounds  = $number_rounds;
                    } else {
                      $this->view->leagueTable = null;
                    }	
     
            } else { 
				
            	    if ($rowstanding != null) {  
                        $feedpath = 'standings/'. $rowstanding['description']; 
						$leagueTable = parent::getGoalserveFeed($feedpath);
						$table= $leagueTable->xpath("//standings/tournament");
                        //$number_rounds = count($table);
                        $this->view->leagueTable = $table;
                        $gs_standing_view = 1;
                        $view->gs_table = $gs_standing_view;
                        //$view->number_table_rounds  = $number_rounds;
                    } else {
                      $this->view->leagueTable = null;
                    }
					

            }
		}
		if ($fb == 'yes') {
		    $this->_response->setBody ( $this->view->render ( 'leagueroundtableviewfb.php' ) );	
		} else {
		    $this->_response->setBody ( $this->view->render ( 'leagueroundtableview.php' ) );	
		}
	}

	public function findcompetitionsAction() {
		
		$filter = new Zend_Filter_StripTags ();
		$league = new LeagueCompetition ();
		if ($this->_request->isPost ()) {
			
			$country = trim ( $filter->filter ( $this->_request->getPost ( 'countryId' ) ) );
			
			$rowArray = $league->findDomesticCompetitionsByCountryLeagues ( $country );
			//$rowArray = $teams->toArray () ;
			

			echo '<option value="0" selected>Select Team</option>';
			foreach ( $rowArray as $data ) {
				echo '<option value=' . $data ['competition_id'] . '>' . $data ['competition_name'];
			
			}
		
		}
	}
	
	public function findhead2headmatchesAction() 
	{
		$session = new Zend_Session_Namespace('userSession');
		$view = Zend_Registry::get ( 'view' );
		$match = new Matchh ();
		$matchId = $this->_request->getParam ( 'matchid', '0' );
		
		//$moreHead2Head = 'n';
		if ($matchId == '0') 
		{
			$teama = $this->_request->getParam ( 'teama', 0 );
			$teamb = $this->_request->getParam ( 'teamb', 0 );
			$competitionId = $this->_request->getParam ( 'competitionid', 0 );

			$result = $match->compareTeamsHead2Head ( $teama, $teamb, $competitionId );		
			//Zend_Debug::dump($result);
			$resultYearFirstHead2Head = $match->getYearFirstHead2HeadGame ( $teama, $teamb, $competitionId );
			$view->yearFirstHead2Head = $resultYearFirstHead2Head [0] ['year'];
			$view->competitionwcid = $competitionId;
			if ($result != null) 
			{
				$row = $match->findMatchById ( $result [0] ['matchid'], $competitionId );
				$team = new Team ();
				$nameA = $team->findUniqueTeam ( $teama );
				$nameB = $team->findUniqueTeam ( $teamb );
				$row = array (0 => array ('team_a' => $teama, 
										  'team_b' => $teamb, 
										  't1' => (!empty($nameA))?$nameA [0] ['team_name']:'',
										  't1seoname' => (!empty($nameA))?$nameA [0] ['team_seoname']:'',
										  't2' =>(!empty($nameB))? $nameB [0] ['team_name']:'',
										  't2seoname' => (!empty($nameB))?$nameB [0] ['team_seoname']:'',) 
						);
			} 
			else
			{
				$row = $match->findMatchById ( 0, $competitionId );
				$team = new Team ();
				$nameA = $team->findUniqueTeam ( $teama );
				$nameB = $team->findUniqueTeam ( $teamb );
				$row = array (0 => array ('team_a' => $teama, 
										  'team_b' => $teamb, 
										  't1' => (!empty($nameA))?$nameA [0] ['team_name']:'',
										  't1seoname' => (!empty($nameA))?$nameA [0] ['team_seoname']:'',
										  't2' =>(!empty($nameB))? $nameB [0] ['team_name']:'',
										  't2seoname' => (!empty($nameB))?$nameB [0] ['team_seoname']:'',) 
						);
			}
			//$moreHead2Head = 'y';
			//Zend_Debug::dump($row);
			$view->teama = $teama;
			$view->teamb = $teamb;
			$view->teamnamea = $nameA [0] ['team_name'];
			$view->teamnameb = $nameB [0] ['team_name'];
		} 
		else 
		{
			$competitionId = $this->_request->getParam ( 'competitionid', 0 );
			$row = $match->findMatchById ( $matchId, $competitionId );
			$result = $match->compareTeamsHead2Head ( $row [0] ['team_a'], $row [0] ['team_b'], $competitionId );
			$resultYearFirstHead2Head = $match->getYearFirstHead2HeadGame ( $row [0] ['team_a'], $row [0] ['team_b'], $competitionId );
			$view->yearFirstHead2Head = $resultYearFirstHead2Head [0] ['year'];
			$view->competitionwcid = $competitionId;
		}
		$teamAwins = 0;
		$teamBwins = 0;
		$teamAlosses = 0;
		$teamBlosses = 0;
		$teamties = 0;
		$teamAclean = 0;
		$teamBclean = 0;
		if ($result != null) 
		{
			$teamAinit = $row [0] ['team_a'];
			$teamBinit = $row [0] ['team_b'];
		}
		foreach ( $result as $m ) 
		{
			$teamA = $m ["cteama"];
			$teamB = $m ["cteamb"];
			//if 'chosen team A' is the same that 'result team A' 
			if ($teamAinit == $teamA && $teamBinit == $teamB) 
			{
				if ($m ["fs_team_a"] > $m ["fs_team_b"]) 
				{
					$teamAwins ++;
					$teamBlosses ++;
					if ($m ["fs_team_b"] == 0) 
					{
						$teamAclean ++;
					}
				} 
				elseif ($m ["fs_team_a"] < $m ["fs_team_b"]) 
				{
					$teamBwins ++;
					$teamAlosses ++;
					if ($m ["fs_team_a"] == 0) 
					{
						$teamBclean ++;
					}
				} 
				else 
				{
					$teamties ++;
				}
			} 
			else 
			{
				if ($m ["fs_team_a"] > $m ["fs_team_b"]) 
				{
					$teamAlosses ++;
					$teamBwins ++;
					if ($m ["fs_team_b"] == 0) 
					{
						$teamBclean ++;
					}
				} 
				elseif ($m ["fs_team_a"] < $m ["fs_team_b"]) 
				{
					$teamBlosses ++;
					$teamAwins ++;
					if ($m ["fs_team_a"] == 0) 
					{
						$teamAclean ++;
					}
				}
				else 
				{
					$teamties ++;
				}
			}
		}
		
		$view->teamAwins = $teamAwins;
		$view->teamBwins = $teamBwins;
		$view->teamAlosses = $teamAlosses;
		$view->teamBlosses = $teamBlosses;
		$view->teamties = $teamties;
		$view->teamAclean = $teamAclean;
		$view->teamBclean = $teamBclean;
		
		if ($result != null) {
			$view->competitionName = $result [0] ['competition_name'];
			$view->competitionCountry = $result [0] ['cname'];
			$view->countryId = $result [0] ['country'];
			$view->competitionType = $result [0] ['type'];
			$view->competitionId = $result [0] ['league'];
		}
		
		$league = new Competitionfile ();
		$rowCountry = $league->selectLeaguesByCountry ();
		
		$view->countries = $rowCountry;
		
		//pagination - getting request variables
		$pageNumber = $this->_request->getParam ( 'page' );
		$isFirstPage = 'n';
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
			$isFirstPage = 'y';
		}
		$paginator = Zend_Paginator::factory ( $result );
		$paginator->setCurrentPageNumber ( $pageNumber );
		//$paginator->setItemCountPerPage(10);
		$paginator->setPageRange ( 5 );
		$this->view->paginator = $paginator;
		$this->breadcrumbs->addStep ( 'Teams', self::$urlGen->getClubsMainUrl ( true ) );
		$this->breadcrumbs->addStep ( 'Teams Head-to-Head' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->teamsMenuSelected = "headtohead";
		$view->match = $row;

		if ($isFirstPage == 'y') {
			$view->actionTemplate = 'head2headmatches.php';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
		} else {
			$this->_response->setBody ( $view->render ( 'head2headscoreboard.php' ) );
		}
	}
	
	public function showcompetitionfansAction() {
		$leagueId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
		$view->leagueId = $leagueId;
		
		$session = new Zend_Session_Namespace ( "userSession" );
		
		//team info
		$competition = new LeagueCompetition ();
		$rowset = $competition->findCompetitionById ( $leagueId );
		$view->competition = $rowset;
		
		//build left Team Badge
		

		//get team fan profiles
		$user = new User ();
		$leagueuserprofiles = $user->findUserProfilesByTournament ( $leagueId, null, ($session->userId != null ? $session->userId : null) );
		$view->teamfanprofiles = $leagueuserprofiles;
		$view->totalteamfans = count ( $leagueuserprofiles );
		
		//get All Domestic and Regional competitions by country and region where the country is located
		$dcountry = $competition->findDomesticCompetitionsByCountry ( $rowset ['country_id'] ); //By Country
		$rcountry = $competition->findRegionalCompetitionsByRegion ( $rowset ['region_group_id'] );
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;
		
		$view->countryId = $rowset ['country_id'];
		$view->countryName = $rowset ['country_name'];
		$view->compName = $rowset ['competition_name'];
		
		$view->teamMenuSelected = 'fans';
		
		$compurl = self::$urlGen->getShowTournamentUrl ( $rowset ['competition_name'], $rowset ['competition_id'], true );
		//$view->teamurl = $teamurl;
		$this->breadcrumbs->addStep ( $rowset ['competition_name'], $compurl );
		$this->breadcrumbs->addStep ( 'Fans' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'viewallleaguefans.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	public function showcompetitionfansresultAction() {
		$view = Zend_Registry::get ( 'view' );
		$leagueId = $this->_request->getParam ( 'id', 0 );
		$view->type = $this->_request->getParam ( 'type', 'list' );
		$view->leagueId = $leagueId;
		$session = new Zend_Session_Namespace ( "userSession" );
		
		//team info
		$competition = new LeagueCompetition ();
		$rowset = $competition->findCompetitionById ( $leagueId );
		$view->competition = $rowset;
		
		//get team fan profiles
		$user = new User ();
		$leagueuserprofiles = $user->findUserProfilesByTournament ( $leagueId, null, ($session->userId != null ? $session->userId : null) );
		$view->teamfanprofiles = $leagueuserprofiles;
		$view->totalteamfans = count ( $leagueuserprofiles );
		
		//pagination - getting request variables
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$paginator = Zend_Paginator::factory ( $leagueuserprofiles );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 5 ); //$this->getNumberOfItemsPerPage());
		$this->view->paginator = $paginator;
		
		//get All Domestic and Regional competitions by country and region where the country is located
		$dcountry = $competition->findDomesticCompetitionsByCountry ( $rowset ['country_id'] ); //By Country
		$rcountry = $competition->findRegionalCompetitionsByRegion ( $rowset ['region_group_id'] );
		$view->dcountry = $dcountry;
		$view->rcountry = $rcountry;
		
		$view->teamMenuSelected = 'fans';
		
		$compurl = self::$urlGen->getShowTournamentUrl ( $rowset ['competition_name'], $rowset ['competition_id'], true );
		//$view->teamurl = $teamurl;
		$this->breadcrumbs->addStep ( $rowset ['competition_name'], $compurl );
		$this->breadcrumbs->addStep ( 'Fans' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$this->_response->setBody ( $view->render ( 'playerfansresult.php' ) ); //the same php template is being user,team,player,competitions
	

	}
	
	public function findhead2headplayersAction() {
		$view = Zend_Registry::get ( 'view' );
		$teama = $this->_request->getParam ( 'teama', 0 );
		$teamb = $this->_request->getParam ( 'teamb', 0 );
		$playera = $this->_request->getParam ( 'playera', 0 );
		$playerb = $this->_request->getParam ( 'playerb', 0 );
		
		$playerModel = new Player();
		//get current club stats 
		$playera_currentclubseason = $playerModel->getActualClubTeamSeason ($playera);       
		$playerb_currentclubseason = $playerModel->getActualClubTeamSeason ($playerb);
		
		//Zend_Debug::dump($playera_currentclubseason);
		

		//player a
		if(empty($teama))
		{
			$teama = $playera_currentclubseason['team_id'];
		}
		$view->playera_teamid = $playera_currentclubseason['team_id'];
        $view->playera_teamclub = $playera_currentclubseason['team_name'];
        $view->playera_teamseoclub = $playera_currentclubseason['team_seoname'];
		$playera_teamcurrentclubseason = $playera_currentclubseason['season_id'];
		
		//player b
		if(empty($teamb))
		{
			$teamb = $playerb_currentclubseason['team_id'];
		}
		$view->playerb_teamid = $playerb_currentclubseason['team_id'];
        $view->playerb_teamclub = $playerb_currentclubseason['team_name'];
        $view->playerb_teamseoclub = $playerb_currentclubseason['team_seoname'];
		$playerb_teamcurrentclubseason = $playerb_currentclubseason['season_id'];
		
		$playera_totalGoals = 0 ;
	    $playera_totalYC= 0 ;
	    $playera_totalRC = 0;
	    $playera_totalGames = 0;
	    
	    $playerb_totalGoals = 0 ;
	    $playerb_totalYC= 0 ;
	    $playerb_totalRC = 0;
	    $playerb_totalGames = 0;
	    
	    
	    
	    // Player a
	    if($playera_currentclubseason != null){

	    	$playera_totalGames = $playerModel->getGamesTotalSeason($playera,$playera_teamcurrentclubseason);
            $playera_totalGoals = $playerModel->getGoalsCurrentSeason ($playera,$playera_teamcurrentclubseason);
	        $playera_totalYC = $playerModel->getYellowCardsCurrentSeason ($playera,$playera_teamcurrentclubseason);
	        $playera_totalRC = $playerModel->getRedCardsCurrentSeason ($playera,$playera_teamcurrentclubseason);	        
		    $view->playera_gamesplayed = $playera_totalGames['gamesTotal'] ;
			$view->playera_glscored = $playera_totalGoals['goalsSeason'];
	        $view->playera_yc =  $playera_totalYC['ycSeason'];
	        $view->playera_rc = $playera_totalRC['rcSeason'];

		} else {
		    
		   $view->playera_gamesplayed = 'n/a' ;
		   $view->playera_glscored = 'n/a';
		   $view->playera_yc =  'n/a';
		   $view->playera_rc = 'n/a';
	    
		}
		
		//Player b
		if($playerb_currentclubseason != null){
	
			$playerb_totalGames = $playerModel->getGamesTotalSeason($playerb,$playerb_teamcurrentclubseason);
		    $playerb_totalGoals = $playerModel->getGoalsCurrentSeason ($playerb,$playerb_teamcurrentclubseason);
		    $playerb_totalYC = $playerModel->getYellowCardsCurrentSeason ($playerb,$playerb_teamcurrentclubseason);
		    $playerb_totalRC = $playerModel->getRedCardsCurrentSeason ($playerb,$playerb_teamcurrentclubseason);	        
		    $view->playerb_gamesplayed = $playerb_totalGames['gamesTotal'] ;
			$view->playerb_glscored = $playerb_totalGoals['goalsSeason'];
		    $view->playerb_yc =  $playerb_totalYC['ycSeason'];
		    $view->playerb_rc = $playerb_totalRC['rcSeason'];
		
		} else {
		    
		   $view->playerb_gamesplayed = 'n/a' ;
		   $view->playerb_glscored = 'n/a';
		   $view->playerb_yc =  'n/a';
		   $view->playerb_rc = 'n/a';
		
		}
		
		//get player history stats
		$teamplayer = new TeamPlayerStats();
		$datapa = $teamplayer->getPlayerById ( null, $playera );
		$datapb = $teamplayer->getPlayerById ( null, $playerb );
		
	
		
		$view->playera = (!empty($datapa))?$datapa: $this->buildUnavailablePlayer($playera, $teama);
		$view->playerb = (!empty($datapb))?$datapb:$this->buildUnavailablePlayer($playerb, $teamb);
		
		$competitionName = (!empty($datapa[0]['competition_name']))?$datapa[0]['competition_name']:$datapb[0]['competition_name'];
		$competitionId = (!empty($datapa[0]['competition_id']))?$datapa[0]['competition_id']:$datapb[0]['competition_id'];

		
		if(!$competitionName)
		{
			$competitionName = 'n/a';
		}
		$view->competitionName = $competitionName;
		$view->competitionId =$competitionId;
	
		$seasonstatsa = $teamplayer->getPlayerStatsDomestic($playera);
		//Have to make sure that the two players compared play in the same competition
		$seasonstatsb = $teamplayer->getPlayerStatsDomestic($playerb);
		$countera = count($seasonstatsa);
		$counterb = count($seasonstatsb);
		
		
		//Always display the same number of rows for both sides so we'll use the least of the two
		if(($countera - $counterb) >= 0)
		{
			$view->seasonrowcounter = $countera;
		}
		else
		{
			$view->seasonrowcounter = $counterb;
		}
		
		$view->seasonstatsa = $seasonstatsa;
		$view->seasonstatsb = $seasonstatsb;
		
		
		//Get list of countries to change players, and team names
		$league = new Competitionfile ();
		$rowCountry = $league->selectLeaguesByCountry ();	
		$view->countries = $rowCountry;
		
		$view->playerMenuSelected = "headtohead";
		$view->actionTemplate = 'head2headplayers.php';
		//$continentData = self::$regionGroupNames ['international'];
		//$this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
		//$this->breadcrumbs->addStep ( 'World Cup', self::$urlGen->getShowRegionalCompetitionsByRegionUrl ( 'World Cup', '72', true ) );
		$this->breadcrumbs->addStep ( 'Players', $this->getUrl ( null, 'players' ) );
		$this->breadcrumbs->addStep ( 'Players Head-to-Head' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	
	/**
	 * In case no data is available for the selected player
	 * return data in a way that won't break the page and be meaningful to the user
	 */
	private function buildUnavailablePlayer($playerId, $teamid)
	{
		$player = new Player ( );
        $rowset = $player->findUniquePlayer ( $playerId );
        $currentclubseason = $player->getActualClubTeamSeason ($playerId);
         //get the countryname based on country_id = $view->playercountry
        $country = new Country ( );
        $countryBirth = $country->fetchRow ( 'country_id=' . $rowset->player_country );
        $viewPlayer =  array();
        $viewPlayer[0]['player_id']=$playerId;
        $viewPlayer[0]['completename']=$rowset->player_common_name;
        $viewPlayer[0]['first_name']=$rowset->player_firstname;
        $viewPlayer[0]['last_name']=$rowset->player_lastname;
        $viewPlayer[0]['nickname']=$rowset->player_nickname;
        $viewPlayer[0]['imagefilename']='';
        $viewPlayer[0]['country_name']=$countryBirth->country_name;
        $viewPlayer[0]['player_country']=$rowset->player_country;
        $viewPlayer[0]['player_position']= $rowset->player_position;
        $viewPlayer[0]['team_name']=(!empty($currentclubseason['team_name']))?$currentclubseason['team_name']:'';
       	$viewPlayer[0]['team_id']=$teamid;
        $viewPlayer[0]['gp']=''; 
        $viewPlayer[0]['gl']='';
        $viewPlayer[0]['rc']='';
        $viewPlayer[0]['yc']='';
        
        return $viewPlayer; 
    
	}
	
private function buildPlayerh2hbadge($playerId)
	{
		$player = new Player ( );
        $rowset = $player->findUniquePlayer ( $playerId );
         
        //get Profile Details
        $view->playerid = $rowset->player_id;
	    $view->playername = $rowset->player_name_short;
	    $view->playerfname = $rowset->player_firstname;
	    $view->playerlname = $rowset->player_lastname;
        $view->playernickname = $rowset->player_nickname;
        $view->playercommonname = $rowset->player_common_name;
        $view->playername = $rowset->player_common_name;
        $view->playerpos = $rowset->player_position;
        $view->playerdob = $rowset->player_dob;
        $view->playerdobcity = $rowset->player_dob_city;
        $view->playerheight = $rowset->player_height;
        $view->playerweight = $rowset->player_weight;
        $view->playershortbio = $rowset->player_short_bio;
        $view->playercountryid = $rowset->player_country;
        
        //get Current Club Team
        $currentclubseason = $player->getActualClubTeamSeason ($playerId);
        $view->playerteamid = $currentclubseason['team_id'];
        $view->playerteamclub = $currentclubseason['team_name'];
        $view->playerteamseoclub = $currentclubseason['team_seoname'];
        $teamcurrentseason = $currentclubseason['season_id'];
        $view->seasontitle = $currentclubseason['title'];

         //get the countryname based on country_id = $view->playercountry
        $country = new Country ( );
        $countryBirth = $country->fetchRow ( 'country_id=' . $rowset->player_country );
        
        //get player profile Image
        $rowsetprofileimage = $player->getPlayerProfileImage ( $playerId );
        $imageFileName = null;
        $imageLocationName = null;
        if($rowsetprofileimage != null){
           $imageFileName = $rowsetprofileimage [0] ["imagefilename"];
           $imageLocationName = $rowsetprofileimage [0] ["imagelocation"];
        } else {
        	$imageFileName = '';
        	$imageLocationName = '';
        }


        $viewPlayer =  array();
        $viewPlayer[0]['player_id']=$playerId;
        $viewPlayer[0]['completename']=$rowset->player_shiot_name;
        $viewPlayer[0]['imagefilename']='';
        $viewPlayer[0]['country_name']=$countryBirth->country_name;
        $viewPlayer[0]['player_country']=$rowset->player_country;
        $viewPlayer[0]['player_position']= $rowset->player_position;
        $viewPlayer[0]['team_name']=(!empty($currentclubseason['team_name']))?$currentclubseason['team_name']:'';
       	$viewPlayer[0]['team_id']= $currentclubseason['team_id'];
        $viewPlayer[0]['gp']=''; 
        $viewPlayer[0]['gl']='';
        $viewPlayer[0]['rc']='';
        $viewPlayer[0]['yc']='';
        
        return $viewPlayer; 
    
	}
	
	public function rssAction(){

		$view = new Zend_View();
		$view->setEscape('htmlentities');
		$teamId = $this->_request->getParam ( 'id', 0 );
		$activity = new Activity();
		$teamActivities = $activity->selectActivitiesPerTeamPerType($teamId , null ,'n');
	}

}
?>
