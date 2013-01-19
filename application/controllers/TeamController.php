<?php

require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';
require_once 'util/Common.php';


class TeamController extends GoalFaceController {
	
	private $country = null;
	private static $urlGen = null;
	private static $regionGroupNames = null;
	private static $title = null;
	private static $keywords = null;
	private static $description = null;
	
	function init() {
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'TeamSeason' );
		Zend_Loader::loadClass ( 'TeamSeasonStats' );
		Zend_Loader::loadClass ( 'LeagueCompetition' ) ;
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Pagination' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'MetaKeywordGen' );
		Zend_Loader::loadClass ( 'MetaDescriptionGen' );
		Zend_Loader::loadClass ( 'PageType' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'PlayerSeason');
		Zend_Loader::loadClass ( 'Zend_Gdata_YouTube');
		Zend_Loader::loadClass ( 'Photo' );
		Zend_Loader::loadClass ( 'Feed' );
		Zend_Loader::loadClass ( 'Season' );
		
		parent::init ();
		$this->breadcrumbs->addStep ( 'Teams', $this->getUrl ( null, 'teams' ) );
		$this->country = new Country ();
		self::$urlGen = new SeoUrlGen ();
		self::$regionGroupNames = array ("europe" => array ("european", "Europe", "European Leagues & Tournaments" ), "asia" => array ("asian", "Asia and Pacific Islands", "Asian Leagues & Tournaments" ), "africa" => array ("african", "Africa", "African Leagues & Tournaments" ), "americas" => array ("americas", "Americas", "American Leagues & Tournaments" ), "international" => array ("international", "FIFA/International", "International Leagues & Tournaments" ) );
		
		self::$title = new PageTitleGen ( );
		self::$keywords = new MetaKeywordGen ( );
		self::$description = new MetaDescriptionGen ( );
		
		$this->updateLastActivityUserLoggedIn();
	}
	
	function indexAction() {
		
		//$this->checkifUserIsRemembered();
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace('userSession');
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$view->title = $title->getPageTitle ( '', PageType::$_CLUBS_MAIN_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_CLUBS_MAIN_PAGE );
		$view->description = $description->getMetaDescription ( '', PageType::$_CLUBS_MAIN_PAGE );
		
		$country = new Country ( );
		$europe = $country->findCountryByRegion (2);
		$americas = $country->findCountryByRegion (1);
		$asiapacific = $country->findCountryByRegion (4);
		$africa = $country->findCountryByRegion ( 3 );

        $view->europe = $europe;
		$view->africa = $africa;
		$view->americas = $americas;
		$view->asiapacific = $asiapacific;

        $view->totalEurope = count($europe);
        $view->totalAmericas = count($americas);
        $view->totalAsia = count($asiapacific);
        $view->totalAfrica = count($africa);

		$team = new Team ( );
		if($session->userId != null) {
		    $this->view->featuredTeamsFour = $team->findFeaturedTeams ( 4,$session->userId );
		    $this->view->popularTeamsTwelve = $team->findPopularTeams ( 12,$session->userId );
		} else {
		     $this->view->featuredTeamsFour = $team->findFeaturedTeams ( 4 );
		     $this->view->popularTeamsTwelve = $team->findPopularTeams ( 12 );
		}
		
		$this->view->popularTeamsRandom = $team->getPopularTeamsRandom ( 3 );

		//Zend_Debug::dump($this->view->featuredTeamsFour);
		//test them out
		$teamseason = new TeamSeason ( );
		//$teamcomp = $teamseason->selectActiveCompetitionByTeam ( $teamId );
		//$view->competitionactive = $teamcomp;

    $view->teamsMenuSelected = 'home';
        
    $league = new Competitionfile ( );
		$rowCountry = $league->selectLeaguesByCountry ();		
		$view->countries = $rowCountry;
		
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'clubdirectory.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	function showteamsAction() {
		$view = Zend_Registry::get ( 'view' );
		$countryId = $this->_request->getParam ( 'id', 0 );
		$team = new Team ( );
		$row = $team->selectTeamsByCountry ( $countryId );
		$view->teams = $row;
		$view->actionTemplate = 'viewcountryteams.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	function autocompleteteamAction()
	{
		$team = new Team();
		$teamName = $this->_request->getParam ( 'term', null );
		$callback = $this->_request->getParam ( 'callback', null );
		$results = $team->findTeamsAutoComplete($teamName);
		$this->_helper->viewRenderer->setNoRender(true);
		echo $callback . "(" . Zend_Json::encode($results) . ")";
	}
	
	
	//Show teams with logo in the profile type view
	function showfeaturedteamsAction() {
		$view = Zend_Registry::get ( 'view' );
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$countryId = $this->_request->getParam ( 'id', 0 );
		//getAllDomesticCompetitionByCountry
		$dLeagues = new LeagueCompetition ();
		$comprow = $dLeagues->findDomesticCompetitionsByCountry($countryId);
	  	$topDivisionComp = $comprow[0]["competition_id"];
		$view->dCompetitions = $comprow;
        $view->dCompetitionsTotal = count($comprow);
  

		$view->defaultComp = $topDivisionComp;

		$view->teamsMenuSelected = 'home';
        
		//fetch country name
		$countryRow = $this->country->findCountryById ( $countryId );
		$view->countryName = $countryRow->country_name;
		$this->breadcrumbs->addStep ( $countryRow->country_name );
		
		$view->title = $title->getPageTitle ( $countryRow->country_name, PageType::$_CLUBS_IN_COUNTRY );
		$view->keywords = $keywords->getMetaKeywords ( $countryRow->country_name, PageType::$_CLUBS_IN_COUNTRY );
		$view->description = $description->getMetaDescription ( $countryRow->country_name, PageType::$_CLUBS_IN_COUNTRY );

		$league = new Competitionfile ( );
		$rowCountry = $league->selectLeaguesByCountry ();
		$view->countries = $rowCountry;
		
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'featuredteams.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showteamsbycompetitionAction(){
		
		$view = Zend_Registry::get ( 'view' );
		$compId = $this->_request->getParam ( 'c', 0 );
		$type = $this->_request->getParam ( 't', null );
		$seasonId = $this->_request->getParam ('s',null);
			
		$session = new Zend_Session_Namespace("userSession");
		
		$team = new Team ( ); // fetch all records from the table
		// getting request variables
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		$teamsResult = $team->getTeamsInCompetitionByLimit ( $compId, null , ($session->userId!= null ? $session->userId : null));

    	$paginator = Zend_Paginator::factory ( $teamsResult );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$numberOfItems = 20;
		if($type == 'modal'){
			$numberOfItems = 10;
		}
		$paginator->setItemCountPerPage($numberOfItems);
		
		$renderPage = 'featuredteamsbycompetition.php';
		if($type == 'modal'){
			$renderPage = 'teamsearchresult.php';
		}
			
		$view->paginator = $paginator;
		$this->_response->setBody ( $view->render ( $renderPage ) );
	}
	
	
	public function showteamsbyseasonAction(){
	    $view = Zend_Registry::get ( 'view' );
	    $session = new Zend_Session_Namespace('userSession');
		$seasonId = $this->_request->getParam ('s',null);
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$team = new Team ( );
		//$teamsResult = $team->selectTeamsBySeason($seasonId);
	    if($session->userId != null) {
		     $teamsResult = $team->selectTeamsBySeason ( $seasonId, null,$session->userId );
		 } else {
		     $teamsResult = $team->selectTeamsBySeason ( $seasonId);
		 }
    	$paginator = Zend_Paginator::factory ($teamsResult );
		$paginator->setCurrentPageNumber ($pageNumber);
		$numberOfItems = 20;
		$paginator->setItemCountPerPage($numberOfItems);		
		$renderPage = 'featuredteamsbyseason.php';		
		$view->paginator = $paginator;
		$this->_response->setBody ( $view->render ( $renderPage ) );
	}
	
	public function showteamsbyseasonfbAction(){
	  	$view = Zend_Registry::get ( 'view' );
		$seasonId = $this->_request->getParam ('s',null);
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$team = new Team ( );
		$teamsResult = $team->selectTeamsBySeason($seasonId);
		$view->seasonId = $seasonId;
		//Zend_Debug::dump($teamsesult);
		
    	$paginator = Zend_Paginator::factory ($teamsResult );
		$paginator->setCurrentPageNumber ($pageNumber);
		$numberOfItems = 32;
		$paginator->setItemCountPerPage($numberOfItems);
		
		$renderPage = 'featuredteamsbyseasonfb.php';
		
		$view->paginator = $paginator;
		$this->_response->setBody ( $view->render ( $renderPage ) );
	}
	
	
	
  private function buildteambadge ($teamId,$view,$rowset) {
  	
        
        $view->teamid = $rowset[0]['team_id'];
        $view->teamgsid = (!empty($rowset[0]['team_gs_id']))?$rowset[0]['team_gs_id']:'';
        $view->teamname = $rowset[0]['team_name'];
        $view->teamseoname = $rowset[0]['team_seoname'];
        $view->countryname = $rowset[0]['country_name'];
        $view->countryid = $rowset[0]['country_id'];
        $view->regionname = $rowset[0]['region_group_name'];
        $view->regionname = $rowset[0]['region_name'];
        $view->teamurl = $rowset[0]['team_url'];
        $view->teamtype = $rowset[0]['team_type'];
        $view->teamfederation = $rowset[0]['team_federation'];
        $view->teamadditionalinfo = $rowset[0]['team_additional_info'];
        $view->teamnickname = $rowset[0]['team_nickname'];        
        $view->teamstadium = $rowset[0]['team_stadium'];
        
        //get team manager
        
        $view->teammanager = null;

        $config = Zend_Registry::get ( 'config' );
        $path_team_logos = $config->path->images->teamlogos . $teamId .".gif" ;
        if (file_exists($path_team_logos)) {
        	$view->imagefacebook = "http://www.goalface.com/public/images/teamlogos/".$teamId.".gif";
        } else {
        	$view->imagefacebook = "http://www.goalface.com/public/images/TeamText.gif";
        }
        
	    //get All competitions where team is active
	    $team = new Team();
      $teamseason = new TeamSeason ( );
      if ($rowset[0]['team_type'] == 'national') {
        $natteam = $team->findUniqueTeam( $teamId );
      }
        
		$teamcomp = $teamseason->getActiveSeasonByTeamLeague( $teamId );
		$view->competitionactive = $teamcomp;

		//return array result set
    return $teamcomp;

    }

    
	function showolduniqueteamAction() {
		$id = $this->_request->getParam ( 'id', null );
		$team = new Team ( );
		if($id != null){
			$rowset = $team->findUniqueTeam ($id);
			$this->_redirect("/teams/".strtolower($rowset[0]['team_seoname'])."/");
		}
	
	}

	function setupHead2HeadViewData($team, $view)
	{
		 $league = new Competitionfile ();
		 $rowCountry = $league->selectLeaguesByCountry ();	
		 $view->countries = $rowCountry;
		 $view->team_name = $team[0]['team_name'];
	}
	
	
	function showuniqueteamAction() {
	
        $teamSeoName = $this->_request->getParam ( 'name', 0 );
	    $view = Zend_Registry::get ( 'view' );
	    $session = new Zend_Session_Namespace("userSession");
	
	    $team = new Team ( );
	    $league = new LeagueCompetition();
	    $rowset = $team->findUniqueTeamBySeoName ($teamSeoName);
	    
	    $team_name = $rowset[0]["team_name"];
	    $teamId = $rowset[0]['team_id'];
	    $view->team = $rowset;
	    $this->setupHead2HeadViewData($rowset, $view);
	    //build left Team Badge
	    $badgeteam = $this->buildteambadge($teamId,$view,$rowset);

	    //Zend_Debug::dump($badgeteam);
	    
	    //get Matches
            $timezone = '+00:00';
            $matchesresultnext = $team->selectMatchesPerTeam ( $teamId,'Fixture','one');
            $matchesresultprevious = $team->selectMatchesPerTeam ( $teamId,'Played','one');
            $view->nextMatch = $matchesresultnext;
            $view->previousMatch = $matchesresultprevious;

            //get rss news search by keyword team_name
            $feed = new Feed();
            $team_name_rss = $team_name . "soccer";
            $feed_url = 'http://api.bing.com/rss.aspx?Source=News&Market=en-US&Version=2.0&Query='.urlencode($team_name_rss);
            //$teamfeed = Zend_Feed::import($feed_url);
            $view->teamfeed = null;
            $teamfeed = null;

            //get Photos tagged for team
            $photo = new Photo ( );
            $numphotos = 4;
            $type_id = 1;
            //$photosTagList = $photo->selectPhotosPerTag ( $teamId, $type_id, $numphotos );
            $photosTagList = null;
            $view->homeProfilePhotos = $photosTagList;

            
            // National Teams or Club Teams
            if ($rowset[0]['team_type'] == 'national') { //National Team
             
            	//get players by national team
            	$playerseason = new PlayerSeason();
            	$seasonlist = $playerseason->selectSeasonsPerNational($teamId);
	    		//Zend_Debug::dump ($seasonlist);
				if ( $seasonlist != null ) {
		    		$view->natseasons = $seasonlist;
					$view->multiNatSeason = 'yes';
				} else {
					$players = $team->findPlayersbyTeam ( $teamId );
		    		$view->players = $players;
		    		$view->multiNatSeason = 'no';
				}
				
	    	} else { //Club Team
	    	 
	    		$view->domesticleagueid = $badgeteam['competition_id'];
	    		$view->domesticleaguename = $badgeteam['competition_name'];
	    		$view->seasonleague = $badgeteam['season_title'];
	    		$players = $team->findPlayersbyTeam ( $teamId );
	    		$view->players = $players;
	    	
		    	//get Stats By Team Per Domestic Season
				$leaguetopscorers = $league->getTopScorersPerSeason($badgeteam['season_id'],$teamId);
				//$leagueyellowcards = $league->getYellowCardsPerSeason($badgeteam['domestic_season_id'],$teamId);
				//$leagueredcards = $league->getRedCardsPerSeason($badgeteam['domestic_season_id'],$teamId);
				$leaguecards = $league->getDisciplinaryPerSeason($badgeteam['season_id'],$teamId);
				$leaguelineups = $league->getLineupsPerSeason($badgeteam['season_id'],$teamId);
				$view->topscorerca  = $leaguetopscorers;
				$view->allcards = $leaguecards;
				$view->lineups = $leaguelineups;

				if ($rowset[0]['team_gs_id'] != null) {
					
				   //get minutes played from feed
					$teamurl = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/'.$rowset[0]['team_gs_id'];
					$request_url = file_get_contents($teamurl);
					$xml = new SimpleXMLElement ($request_url);
					
					//Competions the team is participating
					foreach ($xml->team->leagues->league_id as $leaguexml) {
						//$leagueTeam[]=array("competition_id"=>$leaguexml);
						$leagueTeam[] = $leaguexml;
					}	
					
					//ge Player Stats
					foreach ( $xml->team->squad->player as $playerxml ) {
							$playerArray[] = array("player_id"=>$playerxml['id'],
	                                               "player_appearences"=>$playerxml ['appearences'],
	                                               "player_minutes"=>$playerxml ['minutes'],);
					}
						
						
					$common = new Common ( );
					$playerStatSorted = $common->array_sort($playerArray, 'player_minutes', SORT_DESC);
				}

			//get position of team in league Standings
				$standing = new GoalserveStanding();
				$rowstanding = $standing->fetchRow ( 'competition_id = ' . $badgeteam['competition_id'] );
				if ($rowstanding != null) {
					//get tables display goalserve
					 $xmlsource = "http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/standings/".$rowstanding['description'];
					 //Zend_Debug::dump ($xmlsource);
					 $leagueTable = new SimpleXMLElement($xmlsource,null,true); 
					 $teamtable = $leagueTable->xpath("//standings/tournament[@stage_id='".$rowstanding['round_id']."']/team[@id='".$rowset[0]['team_gs_id']."']"); 
					 //$this->view->teamTable = $teamtable;
					 $this->view->teamposition = $teamtable[0]['position'];
					 $this->view->teamstatus = $teamtable[0]['status'];
					 $this->view->gp = $teamtable[0]->overall['gp'];
					 $this->view->w = $teamtable[0]->overall['w'];
					 $this->view->d = $teamtable[0]->overall['d'];
					 $this->view->l = $teamtable[0]->overall['l'];
					 $this->view->pts = $teamtable[0]->total['p'];
				} 			
	    }

	    //find team trophies
	    $rowtrophy = $team->getTeamTrophy($teamId);
	    $view->trophydetails = $rowtrophy;
	    $view->totalTrophies = count ( $rowtrophy );
	

	    $title = new PageTitleGen ( );
	    $description = new MetaDescriptionGen ( );
	    $keywords = new MetaKeywordGen ( );
	
	    $view->title = $title->getPageTitle ( $team_name, PageType::$_CLUB_MASTER_PAGE );
	    $view->description = $description->getMetaDescription($team_name, PageType::$_CLUB_MASTER_PAGE);
	    $view->keywords = $keywords->getMetaKeywords($rowset, PageType::$_CLUB_MASTER_PAGE);
	
	    $isFavorite = "false";
	    if ($session->email != null) {
	        $userTeam = new UserTeam ( );
	        $row = $userTeam->findUserTeam ($session->userId, $teamId );
	        if ($row != null) {
	           $isFavorite = "true";
	        }
	    }
	    $view->isFavorite = $isFavorite;

		
		//find team activities
		$activity = new Activity();
		$teamActivities = $activity->selectActivitiesPerTeamPerType($teamId , null ,'n');
		$view->teamActivities = $teamActivities;
		
		//find team goalshouts
		$comment = new Comment();
		$teamComments = $comment->findCommentsPerTeam($teamId , 5);
		$totalTeamComments = $comment->findCommentsPerTeam($teamId);
		$view->totalTeamShouts = count ( $totalTeamComments );
		$view->teamcomments = $teamComments;
	
	    $view->teamMenuSelected = 'profile';
	
		$view->teamId = $teamId;
	    $view->team_name = $team_name;
	    //Breadcrumbs
	    //$this->breadcrumbs->addStep ( $rowset [0] ['country_name'], $this->urlGen->getClubsInACountryWithRegion ( $rowset [0] ['country_name'], '0', 'europe', $rowset [0] ['country_id'], True ) );
		$this->breadcrumbs->addStep ( $rowset [0] ['country_name'], self::$urlGen->getShowDomesticCompetitionsByCountryUrl($rowset [0] ['country_name'],$rowset [0] ['country_id'],true) );
	    $this->breadcrumbs->addStep ( $rowset [0] ['team_name'] );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'viewteam.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	
function getuniqueteamAction() {
	
		$teamData = array("status"=>1);
        $teamSeoName = $this->_request->getParam ( 'id', 0 );
	    $session = new Zend_Session_Namespace("userSession");
		
	    $team = new Team ( );
	    $league = new LeagueCompetition();
	    $rowset = $team->findUniqueTeam($teamSeoName);
	    $teamId = $rowset[0]['team_id'];
	    $teamData['team_details'] = $rowset[0];

	    $isFavorite = "false";
	    if ($session->email != null) {
	        $userTeam = new UserTeam ( );
	        $row = $userTeam->findUserTeam ($session->userId, $teamId );
	        if ($row != null) {
	           $isFavorite = "true";
	        }
	    }
	    //Get image for team
 		$config = Zend_Registry::get ( 'config' );
        $path_team_logos = $config->path->images->teamlogos . $teamId .".gif" ;
        if (file_exists($path_team_logos)) 
        {
        	$teamData['team_image_path'] = Zend_Registry::get("contextPath") . "/utility/imageCrop?w=80&h=80&zc=1&src=" . Zend_Registry::get("contextPath") . "/public/images/teamlogos/".$teamId.".gif";
        } 
        else 
        {
        	$teamData['team_image_path'] = Zend_Registry::get("contextPath") . "/utility/imageCrop?w=80&h=80&zc=1&src=" . Zend_Registry::get("contextPath") . "/public/images/TeamText.gif";
        }
	   $teamData['isFavorite'] = $isFavorite;

		$this->_helper->viewRenderer->setNoRender(true);
		echo  Zend_Json::encode($teamData);
	
	}
	
	function showmatchesAction() {
		$teamId = $this->_request->getParam ( 'id', 0 );
		$status = $this->_request->getParam ( 'status', 0 );		
		$type = $this->_request->getParam ( 'type', null );
		$timezone = $this->_request->getParam ( 'timezone', null );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "Matches Played";
		$team = new Team ( );

		if($type != null and $type == 'profile'){
        	$matchesresult = $team->selectMatchesPerTeam ( $teamId, $status ,'y',$timezone);
			$view->matchesresult = $matchesresult;
			$this->_response->setBody ( $view->render ( 'viewteammatches.php' ) );
        }else {
        	//pagination - getting request variables
        	$matchesresult = $team->selectMatchesPerTeam ( $teamId, $status ,'n',$timezone);
	        $pageNumber = $this->_request->getParam('page');
	        if (empty($pageNumber)){
	            $pageNumber = 1;
	        }
	        $paginator = Zend_Paginator::factory($matchesresult);
	        $paginator->setItemCountPerPage(20);
	        $paginator->setCurrentPageNumber($pageNumber);
	        $this->view->paginator = $paginator;			
			$this->_response->setBody ( $view->render ( 'teammatchesscoresschedules.php'));
        }
        //Zend_Debug::dump ( $timezone );
	}

	public function addfavoriteAction() {
		
		$session = new Zend_Session_Namespace("userSession");
		$fromPage = $this->_request->getPost ( 'fromPage' , '' );
		$teamId = $this->_request->getPost ( 'teamId' );
		$jsonAction = $this->_request->getPost ( 'jsonAction' );
		$userId = $session->userId;
		$user_team = new UserTeam ( );
		
			
		//add country favority
		$data = array ('user_id' => $userId, 'team_id' => $teamId );
		//If user is not logged in, return this message
		if(!$userId && $this->getRequest()->isXmlHttpRequest() )
		{
			$this->_helper->viewRenderer->setNoRender(true);
			echo  Zend_Json::encode(array("status"=>0, "msg"=>"You need to login to subscribe to this team's updates!"));
			return;
		}
		$exist = $user_team->findUserTeam($userId ,$teamId);
		if($exist == null){
			$user_team->insert ( $data );
		}
		else 
		{
			//userteam already exists show Error Message
			//Return a JSON action
			if($this->getRequest()->isXmlHttpRequest() )
			{
				$this->_helper->viewRenderer->setNoRender(true);
				echo  Zend_Json::encode(array("status"=>0, "msg"=>"You've already subscribed to this team's udpates!"));
				return;
			}
			echo "ko";
			return;
		}	
		
		
		//insert favorite activity
		$team = new Team();
		$rowset = $team->findUniqueTeam($teamId);
		$screenName = $session->screenName;
		$team_name_seo = self::$urlGen->getClubMasterProfileUrl($rowset[0]['team_id'], $rowset[0]['team_seoname'], true);
		$config = Zend_Registry::get ( 'config' );
		$imageName  = $config->path->images->fanphotos . $session->mainPhoto;
		$variablesToReplace = array ('username' => $screenName ,
									 'team_name_seo' => $team_name_seo ,
									 'team_name' => $rowset[0]['team_name'],
									 'gender' => ($session->user->gender =='m'?'his':'her')			
																		);
		$activityType = Constants::$_ADD_FAVORITE_TEAM_ACTIVITY;
		$activityAddFavoriteTeam = new Activity ( );
		$activityAddFavoriteTeam->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId , 1 ,null , null , null ,$imageName );
		//add an activity to the team itself
		$activityAddFavoriteTeam->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,'n' ,null , $teamId , null ,$imageName );
		
		//Return a JSON action
		if($this->getRequest()->isXmlHttpRequest() )
		{
			$this->_helper->viewRenderer->setNoRender(true);
			echo  Zend_Json::encode(array("status"=>1, "msg"=>"The team was successfully added to your favorites", "debug"=>$rowset));
			return;
		}
		
		if($fromPage == 'edit'){
			$this->_redirect ( "/profile/editfavorities/editAction/teams/page/" ) ;
		}
		
	}
	
	
	public function removefavoriteAction() {
		$session = new Zend_Session_Namespace('userSession');
		$teamId = $this->_request->getPost ( 'teamId' );
		$userId = $session->userId;
		$user_team = new UserTeam ( );
		
		$user_team->deleteUserTeam ( $userId, $teamId );
		
	}
	
	
	public function removefavoriteteamsAction(){
		
		$session = new Zend_Session_Namespace('userSession');
		$arrayFavorites = $this->_request->getPost( 'arrayFavorities' );
		//echo($session->userId);
		$user_team = new UserTeam();
		foreach ( $arrayFavorites as $item ) {
			$user_team->deleteUserTeam ( $session->userId, $item );
		}
		$this->_redirect ( "/profile/editfavorities/editAction/teams/page/" ) ;
		
	}
	
	
	public function addteamAction() {
		$view = Zend_Registry::get ( 'view' );
		$this->_response->setBody ( $view->render ( 'addTeamModal.php' ) );
	}
	
	public function searchteamAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace('userSession');
		$criteria = $this->_request->getParam ( "q" );
		$type = $this->_request->getParam ( "type" );
		$team = new Team ( );
		$result = null;
		$userId = $session->userId;
		if ($criteria != "") {
			$result = $team->findTeamByName ( $criteria, $type, $userId );
		}
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		$paginator = Zend_Paginator::factory ( $result );
		$paginator->setCurrentPageNumber ( $pageNumber );
		
		$view->paginator = $paginator;
		
		$this->_response->setBody ( $view->render ( 'teamsearchresult.php' ) );
	
	}
	
	
	public function searchteambynameAction() {
		
		$criteria = trim($this->_request->getParam ( "q" ));
		$type = trim($this->_request->getParam ( "t" ));
		$team = new Team ( );
		$result = null;
		
		if ($criteria != "") {
			$result = $team->findTeamByName ( $criteria ,$type );
		}
		foreach($result as $record) {
			echo $record['team_name_official'] . ($record ['team_soccer_type'] == "women"?" (Women) ":"") . '|' . $record ['team_id'] . "\n";
		}	
		
	
	}
	
	
	
	public function insertfavoriteTeamAction() {
		
		$teamid = $this->_request->getParam ( "id" );
		$user_team = new UserTeam ( );
		$session = new Zend_Session_Namespace('userSession');
		$data = array ('user_id' => $session->userId, 'team_id' => $teamid );
		$user_team->insert ( $data );
		
		$this->_redirect ( "/user/editFavorities/editAction/teams" );
	
	}
	
	public function deletefavoriteTeamAction() {
		
		$teamid = $this->_request->getParam ( "id" );
		$user_team = new UserTeam ( );
		$session = new Zend_Session_Namespace('userSession');
		$user_team->deleteUserTeam ( $session->userId, $teamid );
		
		$this->_redirect ( "/user/editFavorities/editAction/teams" );
	
	}
	
	public function showfeaturedteamsmoreAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace('userSession');
		$team = new Team ( );
		$type = $this->_request->getParam('type');
		
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        if($type == 'club'){
        	if($session->userId != null) {
				$featuredTeamsClub = $team->selectFeaturedTeams ('club',null,null,$session->userId);
			} else {
				$featuredTeamsClub = $team->selectFeaturedTeams ('club');
			}	
        	$paginator = Zend_Paginator::factory($featuredTeamsClub);
	        $paginator->setCurrentPageNumber($pageNumber);
	        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
	        $this->view->paginatorClub = $paginator;
        }else if($type == 'national'){
            if($session->userId != null) {
                $featuredTeamsNational = $team->selectFeaturedTeams ('national',null,null,$session->userId); 
            } else {
        	    $featuredTeamsNational = $team->selectFeaturedTeams ('national'); 
            }
	        $paginator = Zend_Paginator::factory($featuredTeamsNational);
	        $paginator->setCurrentPageNumber($pageNumber);
	        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
	        $this->view->paginatorNational = $paginator;
        }

		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$view->title = $title->getPageTitle ( '', PageType::$_CLUB_FEATURED_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_CLUB_FEATURED_PAGE );
		$view->description = $description->getMetaDescription ( '', PageType::$_CLUB_FEATURED_PAGE );

    $view->teamsMenuSelected = 'featured';

		$this->breadcrumbs->addStep ( 'Featured Teams' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$league = new Competitionfile ( );
		$rowCountry = $league->selectLeaguesByCountry ();		
		$view->countries = $rowCountry;
		
		$this->view->type = $type;
		
		$view->actionTemplate = 'featuredteamsmore.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	
	public function showmostpopularteamsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace('userSession');
		$team = new Team ( );
		
		$type = $this->_request->getParam('type');
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        if($type == 'club'){
            if($session->userId != null) {
                $popularTeamsClub = $team->findPopularTeams (null,$session->userId,'club');
            } else {
                $popularTeamsClub = $team->findPopularTeams (null,null,'club');
            }
	        $paginatorClub = Zend_Paginator::factory($popularTeamsClub);
	        $paginatorClub->setCurrentPageNumber($pageNumber);
	        $paginatorClub->setItemCountPerPage($this->getNumberOfItemsPerPage());
	        $this->view->paginatorClub = $paginatorClub;
        }else if($type == 'national'){
            if($session->userId != null) {
               $popularTeamsNational = $team->findPopularTeams (null,$session->userId,'national');
            } else {
               $popularTeamsNational = $team->findPopularTeams (null,null,'national'); 
            }
	        $paginatorNational = Zend_Paginator::factory($popularTeamsNational);
	        $paginatorNational->setCurrentPageNumber($pageNumber);
	        $paginatorNational->setItemCountPerPage($this->getNumberOfItemsPerPage());
	        $this->view->paginatorNational = $paginatorNational;
        }
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$view->title = $title->getPageTitle ( '', PageType::$_CLUB_POPULAR_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_CLUB_POPULAR_PAGE );
		$view->description = $description->getMetaDescription ( '', PageType::$_CLUB_POPULAR_PAGE );
		
		$league = new Competitionfile ( );
		$rowCountry = $league->selectLeaguesByCountry ();
		$view->countries = $rowCountry;
		
        $view->teamsMenuSelected = 'popular';
		$this->view->type = $type;
		$view->actionTemplate = 'popularteamsmore.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	public function findfavoriteteamsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$teamType = $this->_request->getParam ( "t" );
		$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
		$userTeam = new UserTeam ( );
		$currentUserId = $this->_request->getParam ( "u" , $session->userId );
		$favTeams = $userTeam->findUserTeamsByType ( $currentUserId, $teamType , 0 );
		
		$view->teamType = $teamType;
		$view->favTeams = $favTeams; 
		
		$this->_response->setBody ( $view->render ( 'favoriteteamsresult.php' ) );
	}
	
		
	    public function showteamactivityAction(){
	        $teamId = $this->_request->getParam ( 'id', 0 );
			$view = Zend_Registry::get ( 'view' );
	        $view->teamId = $teamId;
	
	        $team = new Team ( );
			$rowset = $team->findUniqueTeam ( $teamId );
			$view->team = $rowset;
			$this->setupHead2HeadViewData($rowset, $view);
	        //build left Team Badge
	        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);

	        
	        if ($rowset[0]['team_type'] == 'club') {
        		$view->domesticleagueid = $badgeteam['competition_id'];
	    		$view->domesticleaguename = $badgeteam['competition_name'];
        	}
        
        
	        //get Team activity
			$ta = new Activity();
	        $type = $this->_request->getParam ( 'type', 0 );
	        $teamActivities = $ta->selectActivitiesPerTeamPerType($teamId ,$type , 'n');
	        
			$view->teamactivities = $teamActivities;
			
			//pagination - getting request variables
	        $pageNumber = $this->_request->getParam('page');
	        if (empty($pageNumber)){
	            $pageNumber = 1;
	        }
	        $paginator = Zend_Paginator::factory($teamActivities);
	        $paginator->setCurrentPageNumber($pageNumber);
	        $this->view->paginator = $paginator;
				
			$view->teamid = $teamId;
	        $view->teamMenuSelected = 'activity';
			$view->submitvalue = $type;
	        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
	        //$view->teamurl = $teamurl;
	
	        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
	        $this->breadcrumbs->addStep ( 'Activity Feed' );
	        $this->view->breadcrumbs = $this->breadcrumbs;
			$view->actionTemplate = 'viewallteamactivity.php';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    public function showteamthrophycaseAction() {
        $teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $session = new Zend_Session_Namespace("userSession");

        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;

       //build left Team Badge
        $team_name = $this->buildteambadge($teamId,$view,$rowset);

        //get Throphy Case - Team Stats
        $rowTeamStats = $team->getTeamTrophy($teamId);
        $view->teamStatsFull = $rowTeamStats;

        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        $view->teamurl = $teamurl;

        $view->teamMenuSelected = 'stats';
        
        $view->title = self::$title->getPageTitle ( $team_name, PageType::$_CLUB_STATISTICS_PAGE );
		//$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_CLUB_FEATURED_PAGE );
		//$view->description = $description->getMetaDescription ( '', PageType::$_CLUB_FEATURED_PAGE );
        

        $this->breadcrumbs->addStep ($team_name,$teamurl );
        $this->breadcrumbs->addStep ( 'League Performance' );
        $this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'viewallteamthrophycase.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }
	
	public function showteamscoresschedulesAction() {
        $teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $session = new Zend_Session_Namespace("userSession");

        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;

        //build left Team Badge
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);

        if ($rowset[0]['team_type'] == 'club') {
        		$view->domesticleagueid = $badgeteam['competition_id'];
	    		$view->domesticleaguename = $badgeteam['competition_name'];
        }
        
        $scoreOrSchedule = $this->_request->getParam ( 'sm','scores' );
        $view->teamMenuSelected = $scoreOrSchedule;
        //Zend_Debug::dump($scoreOrSchedule);

        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        //$view->teamurl = $teamurl;
        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
        $this->breadcrumbs->addStep ( 'Scores & Schedules' );
        $this->view->breadcrumbs = $this->breadcrumbs;
		//$view->actionTemplate = 'viewallteamscores.php';
		$view->actionTemplate = 'viewallteamscores2.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    public function showteamfansAction() {
        $teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $session = new Zend_Session_Namespace("userSession");

        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;

         //build left Team Badge
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);

        if ($rowset[0]['team_type'] == 'club') {
        		$view->domesticleagueid = $badgeteam['competition_id'];
	    		$view->domesticleaguename = $badgeteam['competition_name'];
        }
        
        //get team fan profiles
        $user = new User ();
        $teamuserprofiles = $user->findUserProfilesByTeam($teamId , null,($session->userId!=null?$session->userId :null));
        $view->teamfanprofiles = $teamuserprofiles;
        $view->totalteamfans = count ($teamuserprofiles);
        
        //pagination - getting request variables
       
        $view->teamMenuSelected = 'fans';

        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        //$view->teamurl = $teamurl;       
        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
        $this->breadcrumbs->addStep ( 'Fans' );
        $this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'viewallteamfans.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
 
    }
    
	function showteamfansresultAction(){
   		
		$view = Zend_Registry::get ( 'view' );
    	$teamId = $this->_request->getParam ( 'id', 0 );
    	$view->type = $this->_request->getParam ( 'type', 'list' );
		$session = new Zend_Session_Namespace("userSession");
		//team info
        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;

         //build left Team Badge
        $team_name = $this->buildteambadge($teamId,$view,$rowset);

        //get team fan profiles
        $user = new User ();
        $teamuserprofiles = $user->findUserProfilesByTeam($teamId , null,($session->userId!=null?$session->userId :null));
        $view->teamfanprofiles = $teamuserprofiles;
        $view->totalteamfans = count ($teamuserprofiles);
   		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($teamuserprofiles);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
        $this->_response->setBody ( $view->render ( 'playerfansresult.php' ) );
    }

    public function showteamsquadAction() {
        $teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $session = new Zend_Session_Namespace("userSession");

        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;

        //build left Team Badge
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);
        
        if ($rowset[0]['team_type'] == 'club') {
        	$view->domesticleagueid = $badgeteam['competition_id'];
	    	$view->domesticleaguename = $badgeteam['competition_name'];
        }

/*        //get team squad
        //if ($rowset[0]['team_type'] == 'club') {
        	if ($badgeteam['team_gs_id'] != null) {
			   //get minutes played from feed
				$team_url = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/'.$badgeteam['team_gs_id'];				
				$request_url = file_get_contents($team_url);
				$xml = new SimpleXMLElement ($request_url);
				
					foreach ( $xml->team->squad->player as $playerxml ) {
						$playerArray[] = array("player_id"=>$playerxml['id'],
										"player_appearences"=>$playerxml ['appearences'],
										"player_position"=>$playerxml ['position'],
										"player_appearences"=>$playerxml ['appearences'],
										"player_appearences"=>$playerxml ['minutes'],
										"player_appearences"=>$playerxml ['goals'],
										"player_appearences"=>$playerxml ['yellowcards'],
										"player_minutes"=>$playerxml ['redcards'],);
					}
				
				$view->squadplayers = $playerArray;
			}
        	
       // } else {
        	$squadplayers = $team->findPlayersbyTeam ( $teamId ,$session->userId!=null?$session->userId :null);
			//$view->squadplayers = count ($squadplayers);
       // }

       Zend_Debug::dump ($rowset)*/;
        	
		$view->teamMenuSelected = 'squad';

		$teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        //$view->teamurl = $teamurl;

        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
        $this->breadcrumbs->addStep ( 'Squad' );
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewallteamsquad.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }
    
    public function showteamsquadajaxAction(){
    	$view = Zend_Registry::get ( 'view' );
    	$teamId = $this->_request->getParam ( 'id', 0 );
    	$seasonId = $this->_request->getParam ( 'season', 0 );
    	$playerseason = new PlayerSeason();
    	$nationalroaster = $playerseason->selectPlayersNationalBySeason($teamId,$seasonId);
    	$view->nationalroaster = $nationalroaster;
    	//Zend_Debug::dump ( $nationalroaster );
    	$this->_response->setBody ( $this->view->render ( 'viewallteamsquadajax.php' ) );
    }
    
	function showteammatesresultAction(){
   		
		$view = Zend_Registry::get ( 'view' );
    	$teamId = $this->_request->getParam ( 'id', 0 );
    	$view->type = $this->_request->getParam ( 'type', 'list' );
		$session = new Zend_Session_Namespace("userSession");
		//team info
        $team = new Team ();
        $player = new Player ();
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;
		
        //build left Team Badge
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);
		
        //get team squad

	     	if ($rowset[0]['team_gs_id'] != null) {
			   //get minutes played from feed
				$team_url = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/'.$rowset[0]['team_gs_id'] ;	
				//Zend_Debug::dump($team_url);  		
				$request_url = file_get_contents($team_url);
				$xml = new SimpleXMLElement ($request_url);
				
					$rowsetmates = array();
					foreach ( $xml->team->squad->player as $playerxml ) {
						//$rowPlayer = $player->fetchRow ( 'player_id = ' . $playerxml['id'] );
						
						$rowPlayer = $player->findPlayerProfileDetails( $playerxml['id'],$session->userId!=null?$session->userId :null);
						
						if ($rowPlayer != null) {
							$rowsetmates[] = array("player_id"=>$playerxml['id'],
										"player_firstname"=>$rowPlayer[0]['player_firstname'],
										"player_lastname"=>$rowPlayer[0]['player_lastname'],
										"player_nickname"=>$rowPlayer[0]['player_nickname'],
										"player_common_name"=>$rowPlayer[0]['player_common_name'],
										"player_name_short"=>$rowPlayer[0]['player_name_short'],
										"player_position"=>$rowPlayer[0]['player_position'],
										"player_dob"=>$rowPlayer[0]['player_dob'],
										"player_country"=>$rowPlayer[0]['player_country'],
										"country_name"=>$rowPlayer[0]['country_name'],
										"ismyplayer"=>$rowPlayer[0]['ismyplayer'],
										"player_appearences"=>$playerxml['appearences'],
										"player_minutes"=>$playerxml['minutes'],
										"player_goals"=>$playerxml['goals'],
										"player_yellowcards"=>$playerxml['yellowcards'],
										"player_redcards"=>$playerxml['redcards'],);
						  }
					}
			} else {
				$rowsetmates = $team->findPlayersbyTeam ( $teamId ,$session->userId!=null?$session->userId :null);
			}

   		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($rowsetmates);       
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->getNumberOfItemsPerPage();
        $this->view->paginator = $paginator;
        $this->_response->setBody ( $view->render ( 'teamsquadresult.php' ) );
    }

   public function showteamgoalshoutsAction() {
        $teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $session = new Zend_Session_Namespace("userSession");

        //team info
        $team = new Team ( );
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;
		//var_dump($rowset);
        //build left Team Badge
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset);

      	if ($rowset[0]['team_type'] == 'club') {
        	$view->domesticleagueid = $badgeteam['competition_id'];
	    	$view->domesticleaguename = $badgeteam['competition_name'];
        }
        
        //find team goalshouts
		$comment = new Comment();
		$teamComments = $comment->findCommentsPerTeam($teamId);
		$view->totalTeamGoalShouts = count ( $teamComments );
		$view->comments = $teamComments;
		
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($teamComments);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;

        $view->playerMenuSelected = 'shouts';
		$view->teamMenuSelected = 'shouts';

        //Zend_Debug::dump ( $teamComments );

        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        //$view->teamurl = $teamurl;
        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
        $this->breadcrumbs->addStep ( 'Gooal Shouts' );
        $this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'viewallteamgoalshouts.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
   }
   
   public function showteamleagueperformanceAction() {
   		$teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
        $view->teamId = $teamId;
        $team = new Team ( );
        $teamseasonstats = new TeamSeasonStats();
		$rowset = $team->findUniqueTeam ( $teamId );
		$view->team = $rowset;
        $badgeteam = $this->buildteambadge($teamId,$view,$rowset); 
        //Zend_Debug::dump ( $team_name );
        
        $performance = $teamseasonstats->getTeamLeaguePerformance( $teamId );
        $view->performance = $performance;
        
   		if ($rowset[0]['team_type'] == 'club') {
        	$view->domesticleagueid = $badgeteam['competition_id'];
	    	$view->domesticleaguename = $badgeteam['competition_name'];
        }
        
        $view->teamMenuSelected = 'performance';
        $teamurl = self::$urlGen->getClubMasterProfileUrl($teamId, $rowset[0]['team_seoname'], true);
        $this->breadcrumbs->addStep ($rowset[0]['team_name'],$teamurl );
        $this->breadcrumbs->addStep ( 'League Performance' );
        $this->view->breadcrumbs = $this->breadcrumbs;
    	$view->actionTemplate = 'viewteamleagueperformance.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
   }
   
   
   public function showteamstatsAction() {
   		$teamId = $this->_request->getParam ( 'id', 0 );
		$view = Zend_Registry::get ( 'view' );
		$view->teamId = $teamId;
   		$team = new Team ( );
   		$league = new LeagueCompetition();
		$rowset = $team->findUniqueTeam ( $teamId );
		$badgeteam = $this->buildteambadge($teamId,$view,$rowset); 
		

           if ($rowset[0]['team_type'] == 'club') {
        	$view->domesticleagueid = $badgeteam['competition_id'];
	    	$view->domesticleaguename = $badgeteam['competition_name'];
        }
	 	 //get Stats By Team Per Domestic Season
		$leaguetopscorers = $league->getTopScorersPerSeason($badgeteam['season_id'],$teamId);
		//$leagueyellowcards = $league->getYellowCardsPerSeason($badgeteam['domestic_season_id'],$teamId);
		//$leagueredcards = $league->getRedCardsPerSeason($badgeteam['domestic_season_id'],$teamId);
		$leaguecards = $league->getDisciplinaryPerSeason($badgeteam['season_id'],$teamId);
		$leaguelineups = $league->getLineupsPerSeason($badgeteam['season_id'],$teamId);
		$view->topscorerca  = $leaguetopscorers;
		$view->allcards = $leaguecards;
		$view->lineups = $leaguelineups;
		//Zend_Debug::dump($leaguelineups);

		$view->teamMenuSelected = 'playerstats';
   		$view->actionTemplate = 'viewteamstats.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
   }

	public function rssAction(){

		$view = new Zend_View();
		$view->setEscape('htmlentities');
		$teamId = $this->_request->getParam ( 'id', 0 );
		$activity = new Activity();
		$teamActivities = $activity->selectActivitiesPerTeamPerType($teamId , null ,'n');
		
		$team = new Team();
		$rowset = $team->findUniqueTeam($teamId );
		//Zend_Debug::dump($rowset);
		$teamseoname = self::$urlGen->getClubMasterProfileUrl($rowset[0]['team_id'], $rowset[0]['team_seoname'], true);
		$domain = 'http://' . $this->getRequest()->getServer('HTTP_HOST');
			
			$feedData = array(
                'title'   => sprintf("GoalFace.com - %s's Feed", $rowset[0]['team_name']),
                'link'    => $domain ,
                'charset' => 'UTF-8',
                'entries' => array()
            );

            // build feed entries based on returned posts
            
            foreach ($teamActivities as $post) {

                $entry = array(
                    'title'       => $view->escape($post['activitytype_name']),
                    'link'        => $domain . $teamseoname,
                    'description' => $post['activity_text'],
                    'lastUpdate'  => strtotime($post['activity_date']),

                );
                $feedData['entries'][] = $entry;
            }

            // create feed based on created data
            $feed = Zend_Feed::importArray($feedData, 'rss');

            // disable auto-rendering since we're outputting an image
            $this->_helper->viewRenderer->setNoRender();

            // output the feed to the browser
            $feed->send();
		
	}
	
	public function findactivitiesAction() {
		
		$view = Zend_Registry::get ( 'view' );
		
		$teamId = $this->_request->getParam ( 'id', 0 );
		$type = $this->_request->getParam ( 'type', 0 );
		
		$teamname = $this->_request->getParam ( 'teamname', 'Team' );
		
		$pa = new Activity ( );
		$teamActivities = $pa->selectActivitiesPerTeamPerType ( $teamId ,$type);
		$view->teamname = $teamname;
		$view->teamActivities = $teamActivities;
		$this->_response->setBody ( $view->render ( 'teamactivitylist.php' ) );
	}
	
	public function removeteamgoalshoutAction() {
		
		$mc = new Comment ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$teamId = $this->_request->getParam ( 'teamid', 0 );
		
		//find message id in order to find the owner of the message
		$comment = $mc->fetchRow ( "comment_id = " . $commentId );
		
		$userWhoDeletesComment = 2; //if 1 = message owner , 2 = profile owner
		
		if($session->userId == $comment->friend_id){
			$userWhoDeletesComment = 1;
		}
		
		$mc->updateDeleteComment($commentId , $userWhoDeletesComment );
		
		$this->_redirect("/team/showteamprofilegoalshouts/id/" .$teamId);
	
	}
	
	public function editteamgoalshoutAction(){
		
		$mc = new Comment ( );
		
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$teamId = $this->_request->getParam ( 'teamId', 0 );
		$dataEditted = $this->_request->getParam ( 'dataEditted', null );
			
		$mc->updateComment($commentId , $dataEditted );
		
		$this->_redirect("/team/showteamprofilegoalshouts/id/" .$teamId);
		
	}
	
	public function reportabuseAction(){
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$teamId = $this->_request->getParam ( 'teamId', 0 );
		$dataReport = $this->_request->getParam ( 'dataReport', null );
		$reportType = $this->_request->getParam ( 'reportType', null );
		$to = $this->_request->getParam ( 'reportTo', null );
		$report = new Report();
		$data = array ('report_comment_id' => $commentId, 
					   'report_text' 	   => $dataReport,
					   'report_type'       => $reportType,
					   'report_reported_to' => $to,
					   'report_comment_type'       => Constants::$_REPORT_COMMENT_TEAM
			  		   );
		
		$report->insert ( $data );
		
		$this->_redirect("/team/showteamprofilegoalshouts/id/" .$teamId);
		
	}
	
	public function showteamprofilegoalshoutsAction(){
		
		$view = Zend_Registry::get ( 'view' );
		$uc = new Comment();
		$teamid = ( int ) $this->_request->getParam ( 'id', 0 );
		
		$teamcomments = $uc->findCommentsPerTeam($teamid );
		$totalTeamComments = $uc->fetchAll ( "comment_party_id=" . $teamid );
		$view->totalTeamShouts = count ( $totalTeamComments );
		$view->teamcomments = $teamcomments;
		$view->teamid = $teamid;
		$this->_response->setBody ( $view->render ( 'goalshoutteam.php' ) );
			
	}

	public function findteamsbycountryAction(){
		
		$filter = new Zend_Filter_StripTags ( );
		$team = new Team ( );
		
		$country = trim ( $filter->filter ( $this->_request->getPost ( 'id' ) ) );
		$type = trim ( $filter->filter ( $this->_request->getPost ( 't' ) ) );
		
		$rowArray = $team->selectTeamsByCountry ( $country ,$type );
		//$rowArray = $teams->toArray () ;
		
		echo '<option value="0" selected>Select Team</option>';
		foreach ( $rowArray as $data ) {
			if ($data ['team_soccer_type'] != 'women') {
				echo '<option value=' . $data ['team_id'] . '>' . $data ['team_name'];
			} else {
				echo '<option value=' . $data ['team_id'] . '>' . $data ['team_name'] . ' - Women';
			}
		}
	}
	
}
?>
