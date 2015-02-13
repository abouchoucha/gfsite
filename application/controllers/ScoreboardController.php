<?php

require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';

class ScoreboardController extends GoalFaceController {


    public $region_name = null;
    public $regional_Heading = null;
    public $country = null;
    private static $regionGroupNames = null;
    private static $regionGroupNameHeader = null;
    private static $urlGen = null;

    public $loggedin = "";
    public function _checkLogin() {
        $session = new Zend_Session_Namespace ( 'userSession' );
        /*foreach ($session as $name => $value) {
		 echo $value ."<br>";
		 }*/
        //Zend::dump($session);
        //echo $session->userId;
        if (isset ( $session->email )) {
            $this->loggedin = $session->userId;
        }
        //echo "LoggedIn" . $this->loggedin ;
    }

    function init() {        
        Zend_Loader::loadClass ( 'Matchh' );
        Zend_Loader::loadClass ( 'MatchEvent' );
        Zend_Loader::loadClass ( 'Competitionfile' );
        Zend_Loader::loadClass ( 'Zend_Debug' );
        Zend_Loader::loadClass ( 'PageTitleGen' );
        Zend_Loader::loadClass ( 'Comment' );
        Zend_Loader::loadClass ( 'Country' );
        Zend_Loader::loadClass ( 'PageType' );
        Zend_Loader::loadClass ( 'Team' );
        Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
        Zend_Loader::loadClass ( 'UserMatch' );
        Zend_Loader::loadClass ( 'Pagination' );
        //Zend_Loader::loadClass ( 'Event' );
        Zend_Loader::loadClass ( 'Season' );
        Zend_Loader::loadClass ( 'Round' );
        Zend_Loader::loadClass ( 'Groupp' );
        //Zend_Loader::loadClass ( 'Standing' );

        parent::init();
        self::$urlGen = new SeoUrlGen ( );

        parent::init ();
        $this->breadcrumbs->addStep ( 'Scores & Schedules', self::$urlGen->getMainScoresAndMatchesPageUrl(true) );

        $this->region_name = array ('', 'Europe', 'Americas', 'Americas', 'Americas', 'Africa', 'Asia', '', 'World' );
        $this->regional_Heading = array ('', 'European', 'American', 'American', 'American', 'African', 'Asian', '', 'International' );
        $this->country = new Country ( );

        self::$regionGroupNames = array ("europe" => array ("european", "Europe", "European Leagues & Tournaments" ),
                "asia" => array ("asian", "Asia and Pacific Islands", "Asian Leagues & Tournaments" ),
                "africa" => array ("african", "Africa", "African Leagues & Tournaments" ),
                "americas" => array ("americas", "Americas", "American Leagues & Tournaments" ),
                "international" => array ("international", "FIFA/International", "International Leagues & Tournaments" ) );

        $this->updateLastActivityUserLoggedIn();
    }

    public function calculateDatesNew () {
        //getDate Parameter
        //difference time

        $date = $this->_request->getParam ( 'date', 'today' );
        //Zend_Debug::dump($date);
        $diff = (int)$this->_request->getParam ( 'diff', 0 );

        $todayDate = new Zend_Date();
        $todayDate->set('00:00:00',Zend_Date::TIMES);

        if($diff < 0) {
            $todayDate->add( abs($diff), Zend_Date::HOUR );
        }else if($diff > 0) {
            $todayDate->sub( abs($diff), Zend_Date::HOUR );
        }

        $startDate = new Zend_Date();
        $endDate = new Zend_Date();

        if ($date == 'today') {
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '1', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == 'tomorrow') {
            $todayDate->add( '1', Zend_Date::DAY );
            ;
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '2', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == '3') {
            $todayDate->add( '1', Zend_Date::DAY );
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '4', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == 'week') {
            $todayDate->add( '1', Zend_Date::DAY );
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '8', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == 'last') {
            $todayDate->sub( '7', Zend_Date::DAY );
            ;
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '1', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == '-3') {
            $todayDate->sub( '3', Zend_Date::DAY );
            ;
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $todayDate->add( '1', Zend_Date::DAY );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        } else if ($date == 'yesterday') {
            $todayDate->sub( '1', Zend_Date::DAY );
            ;
            $startDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
            $endDate = $todayDate->toString ( 'YYYY-MM-dd hh:mm:ss' );
        }

        //Zend_Debug::dump($startDate);
        //Zend_Debug::dump($endDate);

        $result [0] = $startDate;
        $result [1] = $endDate;
        $result [2] = $date;
        return $result;

    }

    public function calculateDates($temp = null) {

        $temp2 = $this->_request->getParam ( 'date', '0' );

        if (trim ( $temp2 ) == "") {
            $temp2 = 'today';
        }
        if (trim ( $temp2 ) == "0") {
            $temp2 = $temp;
        }
        $date = '';
        $todays_date = date ( "Y-m-d" );
        $todays_init_date = $this->_request->getParam ( 'initDateTime', '0' );
        $todays_end_date = $this->_request->getParam ( 'endDateTime', '0' );
        //echo $todays_init_date . ' - ' . $todays_end_date;
        //$todays_date = date("D,j M Y G:i:s T");
        //$todays_date = '2007-02-10';
        $ts = strtotime ( $todays_date );
        $tsid = strtotime ( $todays_init_date );
        $tsed = strtotime ( $todays_end_date );
        # figure out what 7 days is in seconds
        $one_week = 24 * 60 * 60;
        $one_week_after = 0;
        $one_week_after_init = 0;
        $one_week_after_end = 0;
        Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
        $filter = new Zend_Filter_StripTags ( );
        if ($this->_request->isPost ()) {
            $date = trim ( $filter->filter ( $this->_request->getParam ( 'date' ) ) );

            //$post = Zend_Registry::get('post');
            //$date = trim($post->getEscaped('date'));
            if ($date == "") {
                $date = "today";
            }
            if ($date == 'today') {
                $one_week_after = $todays_date;
                $one_week_after_init = $todays_init_date;
                $one_week_after_end = $todays_end_date;
            } else if ($date == 'tomorrow') {
                $todays_date = date ( "Y-m-d H:i:s", ($ts + (1 * $one_week)) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid + (1 * $one_week)) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed + (1 * $one_week)) );
                $one_week_after = date ( "Y-m-d H:i:s", ($ts + (1 * $one_week)) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + (1 * $one_week)) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + (1 * $one_week)) );
            } else if ($date == 'week') {
                $todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
                $one_week_after = date ( "Y-m-d H:i:s", ($ts + 7 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 7 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 7 * $one_week) );
            } else if ($date == 'last') {
                $one_week_after = $todays_date;
                $one_week_after_init = $todays_init_date;
                $one_week_after_end = $todays_end_date;
                $todays_date = date ( "Y-m-d H:i:s", ($ts - 7 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 7 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 7 * $one_week) );
            } else if ($date == '-3') {
                $one_week_after = $todays_date;
                $one_week_after_init = $todays_init_date;
                $one_week_after_end = $todays_end_date;
                $todays_date = date ( "Y-m-d H:i:s", ($ts - 3 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 3 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 3 * $one_week) );
            } else if ($date == '3') {
                $todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
                $one_week_after = date ( "Y-m-d H:i:s", ($ts + 3 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 3 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 3 * $one_week) );
            } else if ($date == 'yesterday') {
                $one_week_after = date ( "Y-m-d H:i:s", ($ts - 1 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid - 1 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed - 1 * $one_week) );
                $todays_date = $one_week_after;
                $todays_init_date = $one_week_after_init;
                $todays_end_date = $one_week_after_end;
            }

        } else {
            $date = trim ( $filter->filter ( $this->_request->getParam ( 'date' ) ) );
            if ($temp2 == 'today') {
                $one_week_after = $todays_date;
                $one_week_after_init = $todays_init_date;
                $one_week_after_end = $todays_end_date;
            } else if ($date == 'tomorrow') {
                $todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
                $one_week_after = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
            } else if ($temp2 == 'week') {
                $todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
                $one_week_after = date ( "Y-m-d H:i:s", ($ts + 7 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 7 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 7 * $one_week) );
            } else if ($temp2 == 'last') {
                $one_week_after = $todays_date;
                $one_week_after_init = $todays_init_date;
                $one_week_after_end = $todays_end_date;
                $todays_date = date ( "Y-m-d H:i:s", ($ts - 7 *$one_week) );
                $todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 7 * $one_week) );
                $todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 7 * $one_week) );
            } else if ($date == 'yesterday') {
                $one_week_after = date ( "Y-m-d H:i:s", ($ts - 1 * $one_week) );
                $one_week_after_init = date ( "Y-m-d H:i:s", ($tsid - 1 * $one_week) );
                $one_week_after_end = date ( "Y-m-d H:i:s", ($tsed - 1 * $one_week) );
                $todays_date = $one_week_after;
                $todays_init_date = $one_week_after_init;
                $todays_end_date = $one_week_after_end;
            }
            $date = $temp2;
        }
        $fechas [0] = $todays_date;
        $fechas [1] = $todays_init_date;
        $fechas [2] = $todays_end_date;
        $fechas [3] = $one_week_after;
        $fechas [4] = $one_week_after_init;
        $fechas [5] = $one_week_after_end;
        $fechas [6] = $date;
        $fechas [7] = $this->_request->getParam ( 'timezone', '+00:00' ); //default is GMT
        return $fechas;

    }

    function indexAction() {

        $view = Zend_Registry::get ( 'view' );
        Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
        $filter = new Zend_Filter_StripTags ( );

        $date = trim ( $filter->filter ( $this->_request->getPost ( 'date' ) ) );

        $title = new PageTitleGen ( );
        $keywords = new MetaKeywordGen ( );
        $description = new MetaDescriptionGen ( );

        $view->title = $title->getPageTitle ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );
        $view->keywords = $keywords->getMetaKeywords ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );
        $view->description = $description->getMetaDescription ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );
        $view->title = 'test title';

        $view->date = $date;
        $view->errorLogin = "";
        $view->password = "";
        $view->email = "";
        $view->checked = "";
        $this->_response->setBody ( $view->render ( 'mainajax.php' ) );
    }

    function showmatchesbycountryAction() {
        $view = Zend_Registry::get ( 'view' );
        $view->title = "ScoreBoard";
        
        //calculate today's date + 7 days from now to fetch the next matches
        //$todays_date = '2006-09-23';
        $todays_date = date ( "Y-m-d" );
        //echo $todays_date;


        //$ts = time();
        $ts = strtotime ( $todays_date );
        # figure out what 7 days is in seconds
        //$one_week = 7 * 24 * 60 * 60;
        //$one_week_after ='';
        $date = '';
        $countryid = '';
        Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
        $filter = new Zend_Filter_StripTags ( );
        $date = trim ( $filter->filter ( $this->_request->getPost ( 'date' ) ) );
		
		    

        if ($this->_request->isPost ()) {
            //$countryid = trim($post->noTags('countryid'));
            $countryid = trim ( $filter->filter ( $this->_request->getPost ( 'countryid' ) ) );
        } else { //GET
            $countryid = $this->_request->getParam ( 'countryid', 0 );
        }
     
        $pos = strpos ( $countryid, "@" );
        $temp1 = substr ( $countryid, 0, $pos );
        $temp2 = substr ( $countryid, $pos + 1, strlen ( $countryid ) - 1 );
        $date = $temp2;

        $match = new Matchh ( );
        //echo $todays_date;
        $leagueid = $this->_request->getParam ( 'leagueid', null );
        $date = $temp2;

        $match = new Matchh ( );
        //echo $todays_date;
        $leagueid = $this->_request->getParam ( 'leagueid', null );

        $fechas = $this->calculateDates ( $temp2 );
        //$fechas = $this->calculateDates ();

        $result = $match->selectAllMatchesByCountryLeague ( $fechas , $temp1, $leagueid );
        $nrmatches = $match->countMatchesByLeague ( $fechas , $temp1, $leagueid );

        //Zend_Debug::dump($result);
        $view->matches = $result;
        //Zend_Debug::dump($view->matches);
        $view->nrmatches = $nrmatches;
        //Zend:: dump ($nrmatches);
        $view->selected = $fechas [2];
		    $view->selectedRestriction =$fechas [6];

        $view->countryid = $countryid;
        $view->leagueId = $leagueid;
        //$view->actionTemplate = 'scoreBoardByCountry.tpl.php';
        //$this->_response->setBody($view->render('scoreBoardByCountry.tpl.php'));
        //$view->actionTemplate = 'scoreboardviewcountry.php';
        $this->_response->setBody ( $view->render ( 'scoreboardviewcountry.php' ) );
    }

    public function showmatchgoalshoutsAction() {
        //get the goalshouts of a given match
        $uc = new Comment ( );
        $id = $this->_request->getParam ( 'matchid', 0 );
        $matchcomments = $uc->findCommentsPerMatch ( $id );
        $totalMatchComments = $uc->fetchAll ( "comment_party_id='" . $id ."'");
        $this->view->totalMatchComments = count ( $totalMatchComments );
        //$view->matchcomments = $matchcomments;
        $match = new Matchh();
        $row = $match->findMatchById ( $id );
        $this->view->match = $row;

        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($matchcomments);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage(10);
        $this->view->paginator = $paginator;

        $this->_response->setBody ( $this->view->render ( 'goalshoutmatchdetail.php' ) );

    }

function showmatchdetailAction() {
        $view = Zend_Registry::get ( 'view' );
        $config = Zend_Registry::get ( 'config' );
        $id = $this->_request->getParam ( 'matchid', 0 );
        $event = new MatchEvent ( );
        $match = new Matchh ( );
        $country = new Country ( );
        $lea_comp = new LeagueCompetition ( );


        //get info about country and competition
        $row = $match->findMatchById ( $id );
        $view->countryId = $row [0] ['country_id'];
        $view->leagueId = $row [0] ["competition_id"];
        $view->competitionId = $row [0] ["competition_id"];
        $view->competitionName = $row [0] ["competition_name"];
        $view->compName = $row [0] ["competition_name"];      
        $view->seasonId = $row [0] ["season_id"];
        $view->roundId  = $row [0] ["round_id"];
        $view->teama = $row[0] ["t1"];
        $view->teamb = $row[0] ["t2"];

		$todaydate = new Zend_Date ();

		$view->todaysdate = $todaydate;
			
        //menu
        $menuSelected = 'competition';
        $roundmenuSelected = 'none';
		$submenuSelected = 'none';
        $view->menuSelected = $menuSelected;
        $view->submenuSelected = $submenuSelected;
        $view->roundmenuSelected = $roundmenuSelected;


        $countryRow = $country->findCountryById ( $row [0] ['country_id'] );
        $view->countryName = $countryRow->country_name;
        $view->countryCodeIso = $countryRow->country_code_iso2;
       
         $compRow = $lea_comp->findCompetitionById ($row [0] ["competition_id"]);
        $view->competitionType = $compRow['type'];
        $view->competitionFormat = $compRow['format'];
        $view->compFormat = $compRow['format'];

        //Season Informattion and current active season
        $season = new Season ( );
        //$round = new Round();
        $seasonRow = $season->getLeagueSeasons ( $row [0] ["competition_id"] );
        $seasonActive = $seasonRow [0] ["season_id"];
        $view->seasonTitle = $seasonRow [0] ["title"];
        $view->seasonActive  = $seasonActive;
        
        $round = new Round ();
        $roundRow = $round->getSeasonRounds ( $seasonActive );
        $view->roundList = $roundRow;
        $view->totalrounds = count ($roundRow);

        if ($compRow['format'] != 'International cup') {
    			//Domestic League and Domestic Cup
    			$todaydate = new Zend_Date ();
    			$view->todaysdate = $todaydate;
          $peerleagues = $lea_comp->findDomesticCompetitionsByCountryLeagues ( $row [0] ['country_id'] ); //By Country
          $view->peerLeagues = $peerleagues;
  
        } else {
        	//International cup
          $peerleagues = $lea_comp->fetchAll ( 'country_id = '. $row [0] ['country_id'] , 'competition_id');
          $view->peerLeagues = $peerleagues;
          $todaydate = new Zend_Date ();
          $view->todaysdate = $todaydate;
     		  $group = new Groupp (); 
     		 		
			  //Build an array with every round and all groups(rounds) per competition
			   $knockoutstage = null;	
     		 $has_group_stage = 0;
     		 $grouplist = null;
        	
     		foreach ($roundRow as $round) {
     			$myroundslist [] = $round['round_id'];
				if ($round['type'] == 'cup' and count($roundRow) > 1) {
						$knockoutstage [] = $round['round_id'];	
				}
     		}
     			
				// If group stage round exists
/*				if ($round['type'] == 'table') {
					
					$grouplist = $group->getGroupsPerRound ( $round['round_id'] );
  					if($grouplist !=null){
  						$listofgroups = array();
  						foreach ($grouplist as $group) {
							$listofgroups[$group['group_title']] = $standing->getTeamLeagueStanding($group['group_id']);
  						}
  						$view->leagueTable = $listofgroups; 
 				    }

					if($grouplist != null){
						foreach ($grouplist as $data) {
							$myroundslist [] = $data['group_id'];	
						}
					}
					// assign variable true the competition has a group stage
					$has_group_stage = 1;
					
				} else {
					$myroundslist [] = $round['round_id'];
						if ($round['type'] == 'cup' and count($roundRow) > 1) {
							$knockoutstage [] = $round['round_id'];	
						}
				}*/				

			
		    //assign var view to use on the left menu
			$view->hasgroups = $has_group_stage; 
			$view->knockoutstage = count($knockoutstage);
			

        }

		//get match information
        $matchType = trim ( $event->findMatchType ( $id ) );
        $matchEvents = $event->findEventsByMatch ( $id, $matchType );

        $counter1 = 0;
        $counter1a = 0;
        $counter2 = 0;
        $counter3 = 0;
        $counter4 = 0;
        $counter5 = 0;
        $counter6 = 0;
        $eventByGoalTeamA = null;
        $eventByGoalTeamB = null;
        $eventByYC = null;
        $eventByRC = null;
        $eventBySI = null;
        $eventBySO = null;
        $eventByLine = null;
        $flagIsMapped = true;
        
        
        if($matchEvents != null && sizeof($matchEvents) > 0){ //do the actual stuff
       
           foreach ( $matchEvents as $temp ) {
	
	            if ($row [0] ["team_a"] == $temp ['team_id']) {
	                if ($temp ["event_type_id"] == 'G' or $temp ["event_type_id"] == 'PG') {
	                    $eventByGoalTeamA [$counter1] = $temp;
	                    $counter1 ++;
	                } else if ($temp ["event_type_id"] == 'OG') {
	                    $eventByGoalTeamB [$counter1a] = $temp;
	                    $counter1a ++;
	                }
	            } else if ($row [0] ["team_b"] == $temp ['team_id']) {
	                if ($temp ["event_type_id"] == 'G' or $temp ["event_type_id"] == 'PG') {
	                    $eventByGoalTeamB [$counter1a] = $temp;
	                    $counter1a ++;
	                } else if ($temp ["event_type_id"] == 'OG') {
	                    $eventByGoalTeamA [$counter1] = $temp;
	                    $counter1 ++;
	                }
	            }
	
	            if ($temp ["event_type_id"] == 'YC') {
	                $eventByYC [$counter2] = $temp;
	                $counter2 ++;
	            }
	            if ($temp ["event_type_id"] == 'RC' || $temp ["event_type_id"] == 'Y2C') {
	                $eventByRC [$counter3] = $temp;
	                $counter3 ++;
	            }
	            if ($temp ["event_type_id"] == 'SI') {
	                $eventBySI [$counter4] = $temp;
	                $counter4 ++;
	            }
	            if ($temp ["event_type_id"] == 'SO') {
	                $eventBySO [$counter5] = $temp;
	                $counter5 ++;
	            }
	            if ($temp ["event_type_id"] == 'L') {
	                $eventByLine [$counter6] = $temp;
	                //echo $counter6 ." - ".$temp ["player_id"]."<BR>";
	                $counter6 ++;
	            }
        	}
        }

        //get the goalshouts of a given match
        $uc = new Comment ( );
        $totalMatchComments = $uc->fetchAll ( "comment_party_id='" . $id ."'");
        $view->totalMatchComments = count ( $totalMatchComments );

        $view->match = $row;
        //Zend_Debug::dump($row);
        $common = new Common();
        $view->goalsteamA = $common->array_sort($eventByGoalTeamA, 'event_minute', SORT_DESC);
        $view->goalsteamB = $common->array_sort($eventByGoalTeamB, 'event_minute', SORT_DESC);
        $view->yellowCards = $common->array_sort($eventByYC, 'event_minute', SORT_DESC);
        $view->redCards = $common->array_sort($eventByRC, 'event_minute', SORT_DESC);
        $view->subsIn =  $eventBySI;
        $view->subsOut = $eventBySO;
        $view->lineUp = $eventByLine;
        $result = null;
        
		
		if(is_null($eventByLine) or $row [0]['match_status'] == 'Fixture'){
            $result = $match->compareTeamsHead2Head ( $row[0]['team_a'], $row[0]['team_b'] , $row[0]['competition_id']);
            $view->head2headList = $result;
            $view->totalheadtohead = count($result);
        }

        //get current round(stage)
        $round = new Round();
        $activeRound = $round->selectActiveRoundPerCompetition($row[0]['competition_id']);
        $view->activeRound = $activeRound;

        //get last 5 games results per each team
        $last5teamA = $match->getLastFiveMatches($row[0]['team_a']);
        $last5teamB = $match->getLastFiveMatches($row[0]['team_b']);
        $view->last5teamA = $last5teamA;
        $view->last5teamB = $last5teamB;
       
        //current standing        
        $standing = new GoalserveStanding();		
        if($row[0]['team_a_gs']!= null && $row[0]['team_b_gs']!= null ){
            if ($compRow['format'] == 'Domestic League') {
        		$rowstanding = $standing->fetchRow ( 'competition_id = ' . $badgeteam['competition_id'] );
        		//get stats from feed for domestic and regional competitions
      			$feedpath = 'soccerstats/player/'. $playerId;
				$xmlfeedteam = parent::getGoalserveFeed($feedpath);
		
		    	$teamstandingA = $standing->getTeamIndividualStanding($activeRound [0] ["round_id"],$row[0]['team_a_spocosy']);
		    	$view->positionA = $teamstandingA!= null ? $teamstandingA[0]['rank'] : 'N/A';
		    	$teamstandingB = $standing->getTeamIndividualStanding($activeRound [0] ["round_id"],$row[0]['team_b_spocosy']);
		    	$view->positionB = $teamstandingB!= null ? $teamstandingB[0]['rank'] : 'N/A';
		    	
	        }
        }
        
    	$teamAwins = 0;
		$teamBwins = 0;
        $teamAlosses = 0;
        $teamBlosses = 0;
        $teamties = 0;
        $teamAclean = 0;
        $teamBclean = 0;
        if($result != null) {
	        $teamAinit = $row[0]['team_a'];
	        $teamBinit = $row[0]['team_b'];
        
			foreach ($result as $m) {
				$teamA = $m["cteama"];
				$teamB = $m["cteamb"];
				//if 'chosen team A' is the same that 'result team A' 
				if($teamAinit == $teamA && $teamBinit == $teamB){
					if($m["fs_team_a"] > $m["fs_team_b"] ){
						$teamAwins++;
		                $teamBlosses++;
		                if ($m["fs_team_b"] == 0){
		                    $teamAclean++;
		                }
					}elseif($m["fs_team_a"] < $m["fs_team_b"] ) {
						$teamBwins++;
		                $teamAlosses++;
		                if ($m["fs_team_a"] == 0){
		                    $teamBclean++;
		                }
					} else {
		                $teamties++;
		            }	
				} else { 
					if($m["fs_team_a"] > $m["fs_team_b"] ){
						$teamAlosses++;
		                $teamBwins++;
		                if ($m["fs_team_b"] == 0){
		                    $teamBclean++;
		                }
					}elseif($m["fs_team_a"] < $m["fs_team_b"] ) {
						$teamBlosses++;
		                $teamAwins++;
		                if ($m["fs_team_a"] == 0){
		                    $teamAclean++;
		                }
					} else {
		                $teamties++;
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
        }
    	
		//$title = 'GoalFace - Match Details - ' . $row [0] ["competition_name"] . " - " . $row [0] ["t1"] . " vs " . $row [0] ["t2"] . " - " . date ( 'F j, Y', strtotime ( $row [0] ["match_date"] ) );
		$title = $row [0] ["competition_name"] . " - " . $row [0] ["t1"] . " vs " . $row [0] ["t2"] . " - GoalFace.com " ;

		$view->currentDate = date ( 'F j, Y', strtotime ( $row [0] ["match_date"] ) );
		$view->nextDay = date ( 'F j, Y', strtotime ( $row [0] ["match_date"] ) + 86400);
		
        $regionGroup = $lea_comp->findRegionGroupPerCountry ( $row [0] ['country_id'] );
        $continentData = self::$regionGroupNames [$regionGroup];
        $view->continentRegion = $continentData [1];
        //Zend_Debug::dump($continentData);

        //load countries
        $compFile = new Competitionfile ( );
        $countries = $compFile->selectLeaguesByCountry ();
        $view->countries = $countries;

        $isFavorite = "false";
        $session = new Zend_Session_Namespace ( 'userSession' );
        if ($session->email != null) {
            $userMatch = new UserMatch ( );
            $rowUserMatch = $userMatch->findUserMatch ( $session->userId, $id );
            if ($rowUserMatch != null) {
                $isFavorite = "true";
            }
        }
        $view->isFavorite = $isFavorite;
        $view->matchId = $id;
        
        //page title
        $keywords = new MetaKeywordGen ( );
        $description = new MetaDescriptionGen ( );
        
        $this->view->title = $title;
        $view->description = $description->getMetaDescription ( '', PageType::$_SCORES_AND_SCHEDULES_MATCH_DETAILS_PAGE );
        $view->keywords = $keywords->getMetaKeywords ( $row, PageType::$_SCORES_AND_SCHEDULES_MATCH_DETAILS_PAGE );

        //ARE THERE RELATED MATCHES
        //$scoresMatches = $match->selectAllMatchesByCountryLeague (  $fechas, $row [0] ["country_id"], $row [0] ["competition_id"], $id );
        //$view->scoresMatches = $scoresMatches;

        //breadcrumbs
        //Zend_Debug::dump($row [0]);
        $this->breadcrumbs->addStep ( $continentData [1], self::$urlGen->getShowRegionUrl ( strval ( $continentData [0] ), True ) );
        $view->regionNameTitle =  $continentData [1];
        if($compRow['format'] != 'International cup') {
            $this->breadcrumbs->addStep ( $countryRow->country_name, self::$urlGen->getShowDomesticCompetitionsByCountryUrl($countryRow->country_name,$row [0] ['country_id'],true) );
        }
        if($compRow['type'] == 'club') {
            $this->breadcrumbs->addStep ( $row [0] ["competition_name"],self::$urlGen->getShowDomesticCompetitionUrl($row [0] ["competition_name"], $row [0] ["competition_id"], True)  );
        } elseif ($compRow['type'] == 'international') {
            $this->breadcrumbs->addStep ( $row [0] ["competition_name"],self::$urlGen->getShowRegionalCompetitionsByRegionUrl($row [0] ["competition_name"], $row [0] ["competition_id"], True)  );
        }
        $this->breadcrumbs->addStep ( $row [0] ["t1"] . " vs " . $row [0] ["t2"] );
        $this->view->breadcrumbs = $this->breadcrumbs;
        
        
        
		if($row [0]['match_status'] == 'Fixture'){
        	$view->actionTemplate = 'scoreBoardMatchDetailPreview.php';
        	$view->imagefacebook = "http://www.goalface.com/public/images/competitionlogos/".$row [0] ["competition_id"].".gif";
        }else {
        	if($matchEvents == null or !$flagIsMapped){
        		$view->actionTemplate = 'scoreBoardMatchDetailTemp.php';
        		$view->imagefacebook = "http://www.goalface.com/public/images/competitionlogos/".$row [0] ["competition_id"].".gif";
        	}else {
	        	if(((int)$row[0]["fs_team_a"])==((int)$row[0]["fs_team_b"])){
		        	$view->imagefacebook = "http://www.goalface.com/public/images/competitionlogos/".$row [0] ["competition_id"].".gif";
		        }else if(((int)$row[0]["fs_team_a"])>((int)$row[0]["fs_team_b"])){
		        	$view->imagefacebook = "http://www.goalface.com/public/images/teamlogos/".$row [0] ["team_idA"].".gif";
		        }else{
		        	$view->imagefacebook = "http://www.goalface.com/public/images/teamlogos/".$row [0] ["team_idB"].".gif";
		        }
				$view->actionTemplate = 'scoreBoardMatchDetail.php';
        	}
		}
        
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

    public function showrelatedmatchesbycompetitionAction() {
		
        $match = new Matchh();
        $fechas = $this->calculateDates ();
        $countryId = $this->_request->getParam ( 'countryId', 0 );
        $competitionId = $this->_request->getParam ( 'competitionId', 0 );
        $matchId = $this->_request->getParam ( 'matchId', 0 );
        //get the other matches for a day of the same league of current match
        $othermatches = $match->selectAllMatchesByCountryLeague (  $fechas, $countryId, $competitionId, $matchId );
        //Zend_Debug::dump($fechas);
        $this->view->selectedRestriction  = $fechas [6];
        $this->view->othermatches = $othermatches;
        $this->_response->setBody ( $this->view->render ( 'viewrelatedmatches.php' ) );
    }


    public function findteamsbycountryAction() {

        $filter = new Zend_Filter_StripTags ( );
        $team = new Team ( );
        $view->title = "showMatchDetailAction";
        $country = trim ( $filter->filter ( $this->_request->getPost ( 'countryId' ) ) );
        $rowArray = $team->selectTeamsByCountry ( $country );

        foreach ( $rowArray as $data ) {
            echo '<option value=' . $data ['team_id'] . '>' . $data ['team_name'];
        }

    }

    public function compareteamsAction() {

        $view = Zend_Registry::get ( 'view' );
        $view->title = "compareTeamAction";
        $filter = new Zend_Filter_StripTags ( );
        $matchAId = trim ( $filter->filter ( $this->_request->getPost ( 'team1' ) ) );
        $matchBId = trim ( $filter->filter ( $this->_request->getPost ( 'team2' ) ) );
        $match = new Matchh ( );
        $result = $match->compareTeamsHead2Head ( $matchAId, $matchBId );
        $view->head2headList = $result;
        $this->_response->setBody ( $view->render ( 'head2head.php' ) );

    }

    //new function to use with new feed
    
    /*function showscoreboardAction1() {
        $this->_checkLogin ();
        $userId = trim ( $this->loggedin );
        $view = Zend_Registry::get ( 'view' );
        $view->title = "ScoreBoard Home Page";
        $match = new Event ( );
        
        $fechas = $this->calculateDates ();
        $countMatches = $match->getMatchesCountScoreboard($fechas);
        $matches = $match->getMatchesScoreboard($fechas);
        //Zend_Debug::dump($matches);
        $view->matchcount = $countMatches;
        $view->matchlist = $matches;
        $this->_response->setBody ( $view->render ( 'scoreboardviewresult2.php' ) );
        
    }*/

    function showscoreboardAction() {

        $this->_checkLogin ();
        $userId = trim ( $this->loggedin );
        $view = Zend_Registry::get ( 'view' );
        $view->title = "ScoreBoard Home Page";
        $match = new Matchh ( );
        $date = "";

        $typeOfScoreBoard = $this->_request->getParam ( 'type', 'commom' );

        $fechas = $this->calculateDates ();
        
        //$datesXX = $this->calculateDatesNew();
        //Zend_Debug::dump("userid:" . $userId);
        //Zend_Debug::dump("type:" . $typeOfScoreBoard);
		$result = null;
		$nrmatches = null;
        if ($this->_request->isPost ()) {
            //$fechas = $this->calculateDates ();
            if ($userId != "" && $typeOfScoreBoard == "myscoreboard") {
                // show games under your my matches
                //Zend_Debug::dump('post logeado y myscoreboard');
                $result = $match->selectCurrentMatchesByCountryLeagueLoggedIn ( $fechas, $userId );
                $nrmatches = $match->countMatchesByCountryLoggedIn ( $fechas , $userId );
            } else if (($userId != "" or $userId =="")  && $typeOfScoreBoard == "commom") {
            	//Zend_Debug::dump('post logeado y commun');
                $result = $match->selectCurrentMatchesByRegion (  $fechas, 0 , 0, 0 ,null ,'top' );
                $nrmatches = $match->countMatchesBycountryRegion ( $fechas, 0 , 'top' , null , 26);
            }
            $date = $fechas [6];
        } else {
            //$fechas = $this->calculateDates ();
            if ($userId != "" && $typeOfScoreBoard == "myscoreboard") {
                // show games under your my matches
                //Zend_Debug::dump('get logeado y myscoreboard');
                $result = $match->selectCurrentMatchesByCountryLeagueLoggedIn ( $fechas, $userId );
                $nrmatches = $match->countMatchesByCountryLoggedIn ( $fechas, $userId );
             } else if (($userId != "" or $userId =="") && $typeOfScoreBoard == "commom") {
                //Zend_Debug::dump('post logeado y commun'); 
                $result = $match->selectCurrentMatchesByRegion (  $fechas, 0 , 0, 0 ,null ,'top' );
                $nrmatches = $match->countMatchesBycountryRegion ( $fechas, 0 , 'top' , null ,26);
            }
            $date = $fechas [6];
        }
        ($date != null ? $date : "today");
        $view->matches = $result;
        
        $view->includeMatchDetailsLinks = 'n';

        $view->nrmatches = $nrmatches;
        //Zend_Debug::dump($nrmatches); 
        $view->selected = $date;
        $this->_response->setBody ( $view->render ( 'scoreboardviewresult.php' ) );

    }

    function showscoreboardbyregionAction() {

        $this->_checkLogin ();
        $match = new Matchh ( );
        $view = Zend_Registry::get ( 'view' );
        $view->title = "show Score Board by region action";

        //the region id
        $regionId = $this->_request->getParam ( 'regionId', 0 );
        
        //Zend_Debug::dump($regionId);
        $countryId = $this->_request->getParam ( 'countryId', 0 );
        //$pageName = $this->_request->getParam ( 'page', 'home' );
        $status = $this->_request->getParam ( 'status', 0 );
        $type = $this->_request->getParam ( 'type', null );
        $show = $this->_request->getParam ( 'show', 'top' );
        $default = $this->_request->getParam ( 'defaultView', null );
        $continent = $this->_request->getParam ( 'continent', null );
        $continentid = $this->_request->getParam ( 'continentid', null );

        $fechas = $this->calculateDates ();

        //echo $fechas [0] . " - " . $fechas [1] . " - " . $fechas [2] . " - " . $fechas [3] . " - " . $fechas [4] . " - " . $fechas [5] . " - " . $fechas [6];

        $arrayTopCountries = null;
        if ($status != '0' and $status == 'Top') {
            $topCountries = $match->selectTopCountriesByPriority ( '5', $regionId, $fechas );
            
            
            $numCountries = count ( $topCountries );
            $cont = 1;
            foreach ( $topCountries as $top ) {
                $arrayTopCountries .= $top ['country_id'];
                if ($cont < $numCountries) {
                    $arrayTopCountries .= ',';
                }
                $cont ++;
            }
            $status = '0';
        }
        $view->regionid = $regionId;
        $view->regionName = $this->region_name [$regionId];

        $regionName = $this->_request->getParam ( 'name', null );
        $regionName = strtolower ( $regionName );

        $regionId = '0';
        if ($regionName == 'europe' || $regionName == 'european') {
            $regionId = '1';
        } else if ($regionName == 'asia' || $regionName == 'asian') {
            $regionId = '6,7';
        } elseif ($regionName == 'americas') {
            $regionId = '2,3,4';
        } elseif ($regionName == 'africa' || $regionName == 'african') {
            $regionId = '5';
        } elseif ($regionName == 'international') {
            $regionId = '8';
        }


        $view->title = "ScoreBoard Home Page";

        $date = "";

        if ($status == '0') {
            if ($type == 'schedules') {
                $status = 'Fixture';
            }
        }
        
        $result = $match->selectCurrentMatchesByRegion ( $fechas, $regionId, $countryId, $status, $arrayTopCountries ,$show);
        $nrmatches = $match->countMatchesByCountryRegion ( $fechas, $regionId , $show ,$arrayTopCountries);
		
        //Zend_Debug::dump($nrmatches);
        

        $date = $fechas [6];

        ($date != null ? $date : "today");
        $view->matches = $result;
        $view->nrmatches = $nrmatches;
        $view->selected = $date;
        $view->continent = $continent;
        $view->continentid = $continentid;

        //Zend_Loader::loadClass ( 'Zend_Debug' ) ;
        
        $view->includeMatchDetailsLinks = 'y';
        if ($type == 'scores') {
            $view->actionTab = 'scoresTab';
            if($default == 'y') {
                $this->_response->setBody ( $view->render ( 'scores.php' ) );
            }else {
                $this->_response->setBody ( $view->render ( 'scoreboardviewscores.php' ) );
            }
        } else {
            $view->actionTab = 'schedulesTab';
            if($default == 'y') {
                $this->_response->setBody ( $view->render ( 'schedules.php' ) );
            }else {
                $view->includeMatchDetailsLinks = 'n';
                $this->_response->setBody ( $view->render ( 'scoreboardviewresult.php' ) );
                // was done exactly as scoreboardviewscores
                //$this->_response->setBody ( $view->render ( 'scoreboardviewschedules.php' ) );
                
            }
        }
    }

    function showscorescheduleAction() {

        /*$session = new Zend_Session_Namespace('userSession');
		$referrer =  $_SERVER["REQUEST_URI"];
		if($session->email == null){
			$this->checkifUserIsRemembered($referrer);
		}	*/

        //$this->checkifUserIsRemembered();

        $title = new PageTitleGen ( );
        $keywords = new MetaKeywordGen ( );
        $description = new MetaDescriptionGen ( );

        //$view->title = $title->getPageTitle ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );
        //$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );
        //$view->description = $description->getMetaDescription ( '', PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE );

        $this->view->continent = $this->_request->getParam ( "continent" , null);
        $this->view->continentid = $this->_request->getParam ( "continentid", null);
        $this->view->type = $this->_request->getParam ( "type");

        $this->view->selected = 'today';
        $this->view->breadcrumbs = $this->breadcrumbs;
        $this->view->actionTemplate = 'scoreSchedule.php';
        $this->_response->setBody ( $this->view->render ( 'site.tpl.php' ) );

    }

    function showscorescheduletabAction() {

        $view = Zend_Registry::get ( 'view' );
        $action = $this->_request->getParam ( "tabaction" );
        $fechas = $this->calculateDates ();
        $match = new Matchh ( );

        $formToShow = null;
        $result = null;
        $nrmatches = null;

        if ($action == 'scoresTab') {
            $this->view->selected = 'today';
            $result = $match->selectCurrentMatchesByRegion ( $fechas, 0, 0, 0 );
            $nrmatches = $match->countMatchesByCountryRegion ( $fechas, 0, 0, 0 );
            $view->actionTab = $action;
            $formToShow = 'scores.php';

        } else if ($action == 'schedulesTab') {
            $this->view->selected = 'tomorrow';
            //$ts = strtotime ( $fechas [0] );
            //$one_week = 24 * 60 * 60;
            //$tomorrow = date ( "Y-m-d", ($ts + 1 * $one_week) );
            $result = $match->selectCurrentMatchesByRegion ( $fechas, 0, 0, 'Fixture' );
            $nrmatches = $match->countMatchesByCountryRegion ( $fechas, 0, 0, 'Fixture' );
            $view->actionTab = $action;
            $formToShow = 'schedules.php';
        }
        $view->matches = $result;
        $view->nrmatches = $nrmatches;
        $this->_response->setBody ( $view->render ( $formToShow ) );

    }

    function showtableleagueformAction() {

        $view = Zend_Registry::get ( 'view' );
        $view->title = "Table Results Form";
        $league = new Competitionfile ( );
        $row = $league->selectLeaguesByCountry ();

        $view->leagues = $row;
        //Zend:: dump ($row);
        $view->rankings = null;
        $view->actionTemplate = 'scoreBoardTable.php';

        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    function showtableaction() {

        $view = Zend_Registry::get ( 'view' );
        $view->title = "Table Results";
        $league = new Competitionfile ( );
        $row = $league->selectLeaguesByCountry ();
        $view->leagues = $row;
        $league = $this->_request->getParam ( 'league', 0 );

        $pos = strpos ( $league, "&" );
        $temp1 = substr ( $league, 0, $pos );
        $temp2 = substr ( $league, $pos + 1, strlen ( $league ) - 1 );

        $league = new Competitionfile ( );
        $row = $league->selectXMLFileByCountryLeague ( $temp2, $temp1 );
        $view->competition_name = $row [0] ['competition_name'];
        $view->season_name = $row [0] ['season_name'];

        $rankings = simplexml_load_file ( "data/" . $row [0] ['filename'] );


        foreach ( $rankings->xpath ( "//resultstable" ) as $result ) {
            //echo $result['type'];
            if ($result ['type'] == 'total') {
                $cont = 0;
                foreach ( $result->ranking as $ranking ) {
                    $data [$cont] ["team_id"] = $ranking ["team_id"];
                    $data [$cont] ["rank"] = $ranking ["rank"];
                    $data [$cont] ["club_name"] = $ranking ["club_name"];
                    $data [$cont] ["points"] = $ranking ["points"];
                    $data [$cont] ["matches_total"] = $ranking ["matches_total"];
                    $data [$cont] ["matches_won"] = $ranking ["matches_won"];
                    $data [$cont] ["matches_draw"] = $ranking ["matches_draw"];
                    $data [$cont] ["matches_lost"] = $ranking ["matches_lost"];
                    $data [$cont] ["goals_pro"] = $ranking ["goals_pro"];
                    $data [$cont] ["goals_against"] = $ranking ["goals_against"];
                    $data [$cont] ["difference"] = $ranking ["goals_pro"] - $ranking ["goals_against"];
                    $cont ++;
                }
            }
            /*if($result['type'] == 'home'){
			 foreach ($result->ranking as $ranking) {
			 $cont = 0;
			 while($cont <=19){
			 if(trim($data[$cont]["team_id"]) == trim($ranking["team_id"])){
			 $data[$cont]["matches_wonH"] = $ranking["matches_won"];
			 $data[$cont]["matches_drawH"] = $ranking["matches_draw"];
			 $data[$cont]["matches_lostH"] = $ranking["matches_lost"];
			 $data[$cont]["goals_proH"] = $ranking["goals_pro"];
			 $data[$cont]["goals_againstH"] = $ranking["goals_against"];
			 //$data[$cont]["differenceH"] = $ranking["goals_pro"] - $ranking["goals_against"];
			 }
			 }
			 $cont++;
			 }
			 }
			 if($result['type'] == 'away'){
			 foreach ($result->ranking as $ranking) {
			 $cont = 0;
			 while($cont <=19){
			 if(trim($data[$cont]["team_id"]) == trim($ranking["team_id"])){
			 $data[$cont]["matches_wonA"] = $ranking["matches_won"];
			 $data[$cont]["matches_drawA"] = $ranking["matches_draw"];
			 $data[$cont]["matches_lostA"] = $ranking["matches_lost"];
			 $data[$cont]["goals_proA"] = $ranking["goals_pro"];
			 $data[$cont]["goals_againstA"] = $ranking["goals_against"];
			 //$data[$cont]["differenceA"] = $ranking["goals_pro"] - $ranking["goals_against"];
			 }
			 }
			 $cont++;
			 }
			 }
            */
        } //end foreach mayor


        /*foreach ($rankings->xpath("//resultstable[@type='home']") as $result) {
		 foreach ($result->ranking as $ranking) {
		 //echo $ranking["rank"] ."<br>";
		 $cont = 0;
		 while($cont <=19){
		 //echo "<br>" .$data[$cont]["team_id"] . ">>" .$ranking["team_id"];
		 if(trim($data[$cont]["team_id"]) == trim($ranking["team_id"])){
		 //echo "Entro" .$data[$cont]["team_id"] ;
		 $data[$cont]["matches_wonH"] = $ranking["matches_won"];
		 $data[$cont]["matches_drawH"] = $ranking["matches_draw"];
		 $data[$cont]["matches_lostH"] = $ranking["matches_lost"];
		 $data[$cont]["goals_proH"] = $ranking["goals_pro"];
		 $data[$cont]["goals_againstH"] = $ranking["goals_against"];
		 $data[$cont]["differenceH"] = $ranking["goals_pro"] - $ranking["goals_against"];
		 }
		 $cont++;
		 }
		 }
		 }
		 /*foreach ($rankings->xpath("//resultstable[@type='away']") as $result) {
		 foreach ($result->ranking as $ranking) {
		 $cont = 0;
		 while($cont <=19){
		 //echo "<br>" .$data[$cont]["team_id"] . ">>" .$ranking["team_id"];
		 if(trim($data[$cont]["team_id"]) == trim($ranking["team_id"])){
		 $data[$cont]["matches_wonA"] = $ranking["matches_won"];
		 $data[$cont]["matches_drawA"] = $ranking["matches_draw"];
		 $data[$cont]["matches_lostA"] = $ranking["matches_lost"];
		 $data[$cont]["goals_proA"] = $ranking["goals_pro"];
		 $data[$cont]["goals_againstA"] = $ranking["goals_against"];
		 $data[$cont]["differenceA"] = $ranking["goals_pro"] - $ranking["goals_against"];
		 $cont++;
		 }
		 }
		 }
		 } */
        //Zend:: dump ($data);


        $view->rankings = $data;
        $view->actionTemplate = 'scoreBoardTable.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    public function showrssscoreformAction() {

        $view = Zend_Registry::get ( 'view' );
        $view->title = "Table Results";
        $view->actionTemplate = 'rssScoresForm.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }


    public function addfavoriteAction() {

        $session = new Zend_Session_Namespace ( 'userSession' );
        $matchId = $this->_request->getPost ( 'matchId' );
        $userId = $session->userId;
        $user_match = new UserMatch ( );
        $urlGen = new SeoUrlGen();
        $data = array ('user_id' => $userId, 'match_id' => $matchId );
        $user_match->insert ( $data );
        $match = new Matchh();
        $result = $match->findMatchById($matchId);
        //Insert the activity for the match inserted
        $screenName = $session->screenName;
        $matchName = $result[0]["t1"] .' vs. ' . $result[0]["t2"];
        $matchNameSeo = $urlGen->getMatchPageUrl($result[0]["competition_name"], $result[0]["t1"], $result[0]["t2"], $result[0]["match_id"], true);
        $config = Zend_Registry::get ( 'config' );
		$imageName  = $config->path->images->fanphotos . $session->mainPhoto;
        $variablesToReplace = array ('username' => $screenName ,
                'match_name_seo' => $matchNameSeo ,
                'match_name' => $matchName	,
                'gender' => ($session->user->gender =='m'?'his':'her')
        );
        $activityType = Constants::$_ADD_FAVORITE_GAME_ACTIVITY;
        $activityAddFavoriteGame = new Activity ( );
        $activityAddFavoriteGame->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId , 1 ,null , null , null ,$imageName );
    }


    public function removefavoriteAction() {
        $session = new Zend_Session_Namespace ( 'userSession' );
        $matchId = $this->_request->getPost ( 'id' );
        $userId = $session->userId;
        $user_match = new UserMatch ( );
        $user_match->deleteUserMatch ( $userId, $matchId );
    }

    public function removefavoritegamesAction() {

        $session = new Zend_Session_Namespace('userSession');
        $arrayFavorites = $this->_request->getPost( 'arrayFavorities' );
        //echo($session->userId);
        $user_match = new UserMatch();
        foreach ( $arrayFavorites as $item ) {
            $arrayExploded = explode ('*', $item );
            $user_match->deleteUserMatch ( $session->userId, $arrayExploded[0], $arrayExploded[1]);
        }
        $this->_redirect ( "/profile/editfavorities/editAction/games/page/" ) ;

    }

    public function showmatchesheadtoheadAction() {
        $client = new SoapClient ( "http://webservice.globalsportsmedia.com/index.php?wsdl" );
        $sessionId = $client->getSession ( "soccer", "vgmedia", "vgmedia!2421" );
        $clientSoccer = new SoapClient ( "http://webservice.globalsportsmedia.com/soccer/index.php?wsdl" );
        $view = Zend_Registry::get ( 'view' );
        $view->title = "Head to Head";
        $teamA = ( int ) $this->_request->getParam ( 'teamA', 0 );
        $teamB = ( int ) $this->_request->getParam ( 'teamB', 0 );
        $result = $clientSoccer->__soapCall ( "getMatchesHead2Head", array ($sessionId, $teamA, $teamB ) );

        $xmlHeadGames = new SimpleXMLElement ( $result );
        //Zend_Debug::dump($result);
        $view->xmlheadmatches = $xmlHeadGames;
        $view->actionTemplate = 'headtohead.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    public function editscoreboardAction() {

        //parent::validateLoginExpired();

        $session = new Zend_Session_Namespace ( 'userSession' );
        $user_id = $session->userId;
        //Delete the current userfavorites
        $userLeague = new UserLeague();
        //get the ids of the new user favorites
        $newFavLeagues = $this->_request->getPost ( 'competition' );

        $commaSeparatedCountries = implode(",", $newFavLeagues);
        //Zend_Debug::dump($commaSeparatedCountries);
        $userLeague->deleteUserLeagueByUserId($user_id);

        //find all league competitions for the selected countries

        $leaguesByCountry = $userLeague->findUserCompetitionByCountry($commaSeparatedCountries);
        foreach ( $leaguesByCountry as $comp ) {
            $data1 = array ('user_id' => $user_id, 'competition_id' => $comp['competition_id'], 'country_id' => $comp['country_id'] );
            $userLeague->insert ( $data1 );
        }

        //insert the new competitions in userleagues

        //$this->_forward("showscoreboard");

    }

    //simple way to show scores and schedules for FB views
	public function showmatchesAction () {
		$view = Zend_Registry::get ( 'view' );
        //$seasonId = $this->_request->getParam( 'id' ,null );
        $pageType = $this->_request->getParam( 'type' );
        $roundId = $this->_request->getParam( 'roundid' ,null );
        //$pagecoming = $this->_request->getParam( 'page' ,null );
        $timezone =  $this->_request->getParam( 'timezone', '+00:00' ); //default is GMT
        $scoresOrSchedules = null;
        if($pageType == 'scores') {
        	$scoresOrSchedules = 'Played';
        }else if($pageType == 'schedules') {
            $scoresOrSchedules = 'Fixture';
        }
        
        
	}
    
    
    public function showfullmatchesbyseasonAction() {
    	//type can be 'scores' or 'schedules'
      $view = Zend_Registry::get ( 'view' );
      $seasonId = $this->_request->getParam( 'id' ,null );
      $pageType = $this->_request->getParam( 'type' );
			$roundId = $this->_request->getParam( 'roundid' ,null );
      $pagecoming = $this->_request->getParam( 'page' ,null );
      $timezone =  $this->_request->getParam( 'timezone', '+00:00' ); //default is GMT
      $fb = $this->_request->getParam( 'fb' ,'no');
      $scoresOrSchedules = null;
      if($pageType == 'scores') {
        $scoresOrSchedules = 'Played';
      }else if($pageType == 'schedules') {
        $scoresOrSchedules = 'Fixture';
      }
      $lea_comp = new LeagueCompetition();
      $season = new season();
      $round = new Round();
      /* $leagueseasonround = null;
      if($roundId != null){
          $leagueseasonround = $round->findLeagueSeasonRound($roundId);
      } else {
        	$leagueseasonround = $season->findLeagueBySeason($seasonId);
      } */
      $roundRow = $round->getSeasonRounds ($seasonId);
			$roundRowCup = $round->selectRoundsByType($seasonId,'cup');
			$has_group_stage = 0;
			$cont = 0;
			$contcup = 0;
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
	

		//KnockOut Rounds
   $knockoutstage = array();
		foreach ($roundRowCup as $roundcup) {
			if ($contcup < $number_rounds_cup) {
				if($round_list_knockout) {
					$round_list_knockout .= ",";
				}
				$round_list_knockout .= $roundcup['round_id'];
			}
			$knockoutstage [] = $roundcup['round_id'];
		$contcup++;
		}
		
		if(count($knockoutstage) > 1 && $roundId == null) {
			$roundListQuery = $round_list_knockout;
			$roundcount = count($roundRowCup);
		} else {
	     	$roundListQuery = $knockoutstage[0];
	     	$roundcount = 1;
		}
	

     $match = new Matchh();
     $allMatches = $match->selectTotalPlayedMatchesBySeason2($seasonId,$roundListQuery,$scoresOrSchedules,$timezone,$roundcount);
     $view->countAllMatches = count($allMatches); 
     //pagination - getting request variables
	   $pageNumber = $this->_request->getParam('page');
	    if (empty($pageNumber)) {
	        $pageNumber = 1;
	   }
	   
	   $paginator = Zend_Paginator::factory($allMatches);
	   $paginator->setCurrentPageNumber($pageNumber);
	 if ($fb == 'no') {
	 	$paginator->setItemCountPerPage(25);
	   } else {
	  	$paginator->setItemCountPerPage(20);
	 }
	$view->paginator = $paginator;
	
	
	$view->selectedRestriction = $scoresOrSchedules;

        if ($fb == 'no') {
	        if ($pagecoming == 'comppage') {
	            $this->_response->setBody ( $view->render ( 'fullmatchesscoreboardcompetition.php' ) );
	        } else {
	            $this->_response->setBody ( $view->render ( 'fullmatchesscoreboard.php' ) );
	        }
        } else {
        	$this->_response->setBody ( $view->render ( 'fullmatchesscoreboardfb.php' ) );
        }
    }
    

    public function removematchgoalshoutAction() {

        $mc = new Comment ( );

        //first delete goalshout
        $commentId = $this->_request->getParam ( 'id', 0 );
        $matchid = $this->_request->getParam ( 'matchid', 0 );

        $mc->updateDeleteComment($commentId , 1 );

        $this->_redirect("/scoreboard/showmatchgoalshouts/matchid/" .$matchid);

    }

    public function editmatchgoalshoutAction() {

        $mc = new Comment ( );

        //first delete goalshout
        $commentId = $this->_request->getParam ( 'id', 0 );
        $matchid = $this->_request->getParam ( 'matchid', 0 );
        $dataEditted = $this->_request->getParam ( 'dataEditted', null );

        $mc->updateComment($commentId , $dataEditted );

        $this->_redirect("/scoreboard/showmatchgoalshouts/matchid/" .$matchid);

    }

    public function findfavoriteusergamesAction() {

        $view = Zend_Registry::get ( 'view' );

   		$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}

        $userLeague = new UserLeague ( );
        $currentUserId = $this->_request->getParam ( "u", $session->userId );
        $favGames = $userLeague->findAllUserGames ( $currentUserId, null );

        $view->favGames = $favGames;

        $this->_response->setBody ( $view->render ( 'favoritegamesresult.php' ) );
    }

    public function reportabuseAction() {

        $commentId = $this->_request->getParam ( 'id', 0 );
        $matchid = $this->_request->getParam ( 'matchid', 0 );
        $dataReport = $this->_request->getParam ( 'dataReport', null );
        $reportType = $this->_request->getParam ( 'reportType', null );
        $to = $this->_request->getParam ( 'reportTo', null );
        $report = new Report();
        $data = array ('report_comment_id' => $commentId,
                'report_text' 	   => $dataReport,
        		'report_reported_to' => $to,'report_reported_to' => $to,
                'report_type'       => $reportType
        		
        );

        $report->insert ( $data );

        $this->_redirect("/scoreboard/showmatchgoalshouts/matchid/" .$matchid);

    }

}
