<?php
require_once 'util/Common.php';
require_once 'scripts/seourlgen.php';
require_once 'GoalFaceController.php';
require_once 'Zrad/cFacebook.php';
class PlayerController extends GoalFaceController
{
    function init ()
    {
        Zend_Loader::loadClass('Player');
        Zend_Loader::loadClass('TeamSeason');
        Zend_Loader::loadClass('UserPlayer');
        Zend_Loader::loadClass('LeagueCompetition');
        Zend_Loader::loadClass('Country');
        Zend_Loader::loadClass('Comment');
        Zend_Loader::loadClass('Zend_Debug');
        Zend_Loader::loadClass('Pagination');
        Zend_Loader::loadClass('PageTitleGen');
        Zend_Loader::loadClass('MetaKeywordGen');
        Zend_Loader::loadClass('MetaDescriptionGen');
        Zend_Loader::loadClass('PageType');
        Zend_Loader::loadClass('Zend_Filter_StripTags');
        Zend_Loader::loadClass('PlayerActivity');
        Zend_Loader::loadClass('Season');
        Zend_Loader::loadClass('User');
        Zend_Loader::loadClass('Comment');
        //Zend_Loader::loadClass ( 'Tournament' );
        //Zend_Loader::loadClass ( 'Participant' );
        parent::init();
        $this->breadcrumbs->addStep('Players',
        $this->getUrl(null, 'players'));
        $this->updateLastActivityUserLoggedIn();
    }
    function indexAction ()
    {}
    private function buildplayerbadge ($playerId, $countryId, $view, $rowset)
    {
        $player = new Player();
        //from findUniquePlayer
        $view->playerid = $rowset->player_id;
        $view->playertype = $rowset->player_type;
        $view->playername = $rowset->player_name_short;
        $view->playerfname = $rowset->player_firstname;
        $view->playerlname = $rowset->player_lastname;
        $view->playernickname = $rowset->player_nickname;
        $view->playercommonname = $rowset->player_common_name;
        $view->playerpos = $rowset->player_position;
        $view->playerdob = $rowset->player_dob;
        $view->playerdobcity = $rowset->player_dob_city;
        $view->playerheight = $rowset->player_height;
        $view->playerweight = $rowset->player_weight;
        $view->playershortbio = $rowset->player_short_bio;
        $view->playercountryid = $rowset->player_country;
        //get the countryname based on country_id = $view->playercountry
        $country = new Country();
        $countryBirth = $country->fetchRow('country_id=' . $countryId);
        $view->playercountry = $countryBirth->country_name;
        //get Current Club Team
        $currentclubseason = null;
        $currentclubseason['team_id'] = null;
        $currentclubseason = $player->getActualClubTeamSeason($playerId);


        $currentclubseason1 = $player->getActualClubTeamSeason(312423);
        Zend_Debug::dump ($currentclubseason1);

        if ($currentclubseason != null) {
            $view->playeractualteam = $currentclubseason['actual_team'];
            $view->playerteamleagueid = $currentclubseason['competition_id'];
            $view->playerteamleague = $currentclubseason['competition_name'];
            $view->playerteamid = $currentclubseason['team_id'];
            $view->playerteamgsid = $currentclubseason['team_gs_id'];
            $view->playerteamclub = $currentclubseason['team_name'];
            $view->playerteamseoclub = $currentclubseason['team_seoname'];
            $view->playerteamcountryid = $currentclubseason['country_id'];
            $view->playerteamcountry = $currentclubseason['country_name'];
            $teamcurrentseason = $currentclubseason['season_id'];
            $view->seasontitle = $currentclubseason['title'];
        }

        $config = Zend_Registry::get('config');
        $path_player_photos = $config->path->images->players . $playerId . ".jpg";
        if (file_exists($path_player_photos)) {
            $view->imagefacebook = "http://www.goalface.com/public/images/players/" .$playerId . ".jpg";
        } else {
            $view->imagefacebook = "http://www.goalface.com/public/images/Player1Text.gif";
        }

        return $currentclubseason;
    }

    private function getfeedstats($playerId,$stat_type='league') {
        //get stats from feed for domestic cup and regional competitions
        $feedpath = 'soccerstats/player/' . $playerId;
        $xml = parent::getGoalserveFeed($feedpath);
        $stats_array = array();

        switch($stat_type) {
            case 'league':
                $league_stats = $xml->xpath("/players/player/statistic/club");
                $league_stats_total = $this->get_stats_total($league_stats);
                $stats_array = array('stats' => $league_stats, 'stats_total' => $league_stats_total);
                break;
            case 'cup':
                $cup_stats = $xml->xpath("/players/player/statistic_cups/club");
                $cup_stats_total = $this->get_stats_total($cup_stats);
                $stats_array = array('stats' => $cup_stats, 'stats_total' => $cup_stats_total);
                break;
            case 'cup_int':
                $cup_int_stats = $xml->xpath("/players/player/statistic_cups_intl/club");
                $cup_int_stats_total = $this->get_stats_total($cup_int_stats);
                $stats_array = array('stats' => $cup_int_stats, 'stats_total' => $cup_int_stats_total);
                break;
            case 'nat_team':
                 $nat_team_stats = $xml->xpath("/players/player/statistic_intl/club");
                 $nat_team_stats_total = $this->get_stats_total($nat_team_stats);
                 $stats_array = array('stats' => $nat_team_stats, 'stats_total' => $nat_team_stats_total);
                break;
        }
        return $stats_array;
        
    }

    private function get_stats_total($stats) {
        $total_stats = array();

        if ($stats != null) {
            $total_appearences=0;
            $total_goals=0;
            $total_minutes=0;
            $total_yellowcards=0;
            $total_redcards=0;
            $total_stats_int = array();
             foreach ($stats as $totalstats) {
               $total_appearences += $totalstats['appearences'];
               $total_goals += $totalstats['goals'];
               $total_minutes += $totalstats['minutes'];
               $total_yellowcards += $totalstats['yellowcards'];
               $total_redcards += $totalstats['redcards'];
            }
            $total_stats['total_gp'] = $total_appearences;
            $total_stats['total_min'] = $total_minutes;
            $total_stats['total_gl'] = $total_goals;
            $total_stats['total_yc'] = $total_yellowcards;
            $total_stats['total_rc'] = $total_redcards;
        }
        return $total_stats;

    }


    function showuniqueplayerAction ()
    {
        //http://staging.goalface.com/players/lionel-messi_119/
        $session = new Zend_Session_Namespace('userSession');
        $config = Zend_Registry::get('config');
        //echo '--->>>'.strlen(cFacebook::getAccessToken());
        if ($session->duser == null && $session->userId == null) {
            $appId = $config->facebook->appid;
            $secret = $config->facebook->secret;
            $servername = $config->path->index->server->name;
            $optionURL = "http://" . $_SERVER["SERVER_NAME"] .
             $_SERVER["REQUEST_URI"];
            $valTemp = cFacebook::loginFacebook($optionURL, $session, $appId,
            $secret, $servername);
            if ($valTemp != null) {
                $this->_redirect("/login/fbdologin");
            }
        }
        $playerId = $this->_request->getParam('id', 0);
        $view = Zend_Registry::get('view');
        $player = new Player();
        $league = new LeagueCompetition();
        $standing = new GoalserveStanding();
        $season = new Season();
        $rowset = $player->findUniquePlayer($playerId);
        $rowsetdetail = $player->findUniquePlayerDetailed($playerId);
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle($rowset,PageType::$_PLAYER_MASTER_PAGE);
        $view->description = $description->getMetaDescription('',PageType::$_PLAYER_MASTER_PAGE);
        $view->keywords = $keywords->getMetaKeywords($rowsetdetail,PageType::$_PLAYER_MASTER_PAGE);
        //build left Player Badge
        $currentClubSeason = $this->buildplayerbadge($playerId,$rowset->player_country, $view, $rowset);



       //get player stats from feed for domestic cup and regional competitions
        $feedpath = 'soccerstats/player/' . $playerId;
        $xml = parent::getGoalserveFeed($feedpath);

    
        //all league stats
        $leaguestats = $this->getfeedstats($playerId,'league');
        $league_stats = $leaguestats['stats'];
        $league_stats_total = $leaguestats['stats_total'];
        //all cup stats
        $cupstats = $this->getfeedstats($playerId,'cup');
        $cup_stats = $cupstats['stats'];
        $cup_stats_total = $cupstats['stats_total'];
        //all club international stats
        $cupintstats = $this->getfeedstats($playerId,'cup_int');
        $cup_int_stats = $cupintstats['stats'];
        $cup_int_stats_total = $cupintstats['stats_total'];
        //all national team stats
        $natteamstats = $this->getfeedstats($playerId,'nat_team');
        $nat_team_stats = $natteamstats['stats'];
        $nat_team_stats_total = $natteamstats['stats_total'];
        //pass to the view
        $view->stats_league = $league_stats;
        $view->total_stats_league = $league_stats_total;
        $view->stats_cup = $cup_stats;
        $view->total_stats_cup = $cup_stats_total;
        $view->stats_club_int = $cup_int_stats;
        $view->total_stats_int = $cup_int_stats_total;
        $view->stats_national = $nat_team_stats;
        $view->total_stats_nat = $nat_team_stats_total;
    
        //old
        $teamsplayedagainst = $player->getPlayerMatchEventsTeamsSelect($playerId);
        $view->teamselect = $teamsplayedagainst;
        $currentmatchstats = $player->getPlayerMatchEventsTeams($playerId);
        $view->matchstats = $currentmatchstats;

        //get competition(s) where player is participating (played) this season
        $playerseasoncompetition = $season->getActiveCompetitionPerTeamSeason($currentClubSeason['team_id'],$currentClubSeason['title']);

        //get current player stats for all competitions the player is participating
        $stats = array();
        foreach ($playerseasoncompetition as $comp) {
            $activeseasonstats = $player->getPlayerMatchEventsTotals($playerId,$comp['competition_id'],$currentClubSeason['title']);
            if ($activeseasonstats[0]['league_id'] != null) {
                $stats[] = $activeseasonstats;
            }
        }
        $view->stats = $stats;
        //Zend_Debug::dump ($playerseasoncompetition);
        //Zend_Debug::dump ($stats);

        $team = new Team();
        $timezone = '+00:00';
        $matchesresultnext = null;
        $matchesresultprevious = null;
        if ($currentClubSeason['team_id'] != null) {
            $matchesresultnext = $team->selectMatchesPerTeam(
            $currentClubSeason['team_id'], 'Fixture', 'one');
        }
        if ($currentClubSeason['team_id'] != null) {
            $matchesresultprevious = $team->selectMatchesPerTeam(
            $currentClubSeason['team_id'], 'Played', 'one');
        }
        $view->nextMatch = $matchesresultnext;
        $view->previousMatch = $matchesresultprevious;

        //get position of team in league Standings
        $teamtable[0]['position'] = null;
        if ($currentClubSeason['competition_id'] != null) {
            $standing = new GoalserveStanding();
            $rowstanding = $standing->fetchRow(
            'competition_id = ' . $currentClubSeason['competition_id']);
            if ($rowstanding['description'] != null) {
                //get team position in domestic league table based on standings goalserve feed
                $feedpath = 'standings/'. $rowstanding['description']; 
					      $leagueTable = parent::getGoalserveFeed($feedpath);
                $teamtable = $leagueTable->xpath("//standings/tournament[@stage_id='" . $rowstanding['round_id'] ."']/team[@id='" . $currentClubSeason['team_gs_id'] . "']");
                $this->view->teamposition = $teamtable[0]['position'];
            }
        }
        //get Player Photos
        $photo = new Photo();
        $numphotos = 4;
        $type_id = 2;
        //$photosTagList = $photo->selectPhotosPerTag ( $playerId, $type_id, $numphotos );
        $view->homeProfilePhotos = null;

        //get Player RSS feed
        $feed = new Feed();
        $feed_url = 'http://api.bing.com/rss.aspx?Source=News&Market=en-US&Version=2.0&Query=' .urlencode($rowset->player_common_name)."%20Football";;
        //$teamfeed = Zend_Feed::import($feed_url);

        $view->playerfeed = null;

        //Zend_Debug::dump ( $feed_url );
        //Added player info for Head 2 Head Set up
        $view->playera = $rowset;
        $league = new Competitionfile();
        $rowCountry = $league->selectLeaguesByCountry();
        $view->countries = $rowCountry;
        //get PLayer Teammates
        $rowsetmates = null;
        if ($currentClubSeason['team_id'] != null) {
            $rowsetmates = $player->findTeammatesByPlayer(
            $currentClubSeason['team_id'], $playerId);
        }
        $view->playermates = $rowsetmates;
        //Zend_Debug::dump ($rowsetmates);
        //get Player Distinctions
        $throphies = $player->selectThrophyByPlayer($playerId);
        $view->throphy = $throphies;
        //get Player Stats
        $playerposition = $rowset->player_position;
        if ($playerposition == 'Goalkeeper') {
            $rowsetclub = $player->getPlayerKeeperTeamStatsDetails($playerId, 1);
            $rowsetregional = $player->getPlayerKeeperTeamStatsDetails($playerId, 2);
            $rowsetnational = $player->getPlayerKeeperTeamStatsDetails($playerId, 3);
            $rowsetclubtotal = $player->getPlayerKeeperTeamTotalStatsDetails($playerId, 1);
            $rowsetregionaltotal = $player->getPlayerKeeperTeamTotalStatsDetails($playerId, 2);
            $rowsetnationaltotal = $player->getPlayerKeeperTeamTotalStatsDetails( $playerId, 3);
        } else {
            $rowsetclub = $player->getPlayerTeamStatsDetails($playerId, 1);
            $rowsetregional = $player->getPlayerTeamStatsDetails($playerId, 2);
            $rowsetnational = $player->getPlayerTeamStatsDetails($playerId, 3);
            $rowsetclubtotal = $player->getPlayerTeamTotalStatsDetails($playerId, 1);
            $rowsetregionaltotal = $player->getPlayerTeamTotalStatsDetails($playerId, 2);
            $rowsetnationaltotal = $player->getPlayerTeamTotalStatsDetails($playerId, 3);
        }
        $view->player_club_stats = $rowsetclub;
        $view->player_regional_stats = $rowsetregional;
        $view->player_national_stats = $rowsetnational;
        $view->player_club_stats_totals = $rowsetclubtotal;
        $view->player_regional_stats_totals = $rowsetregionaltotal;
        $view->player_national_stats_total = $rowsetnationaltotal;
        $view->total_club_stats = count($rowsetclub);
        $view->total_regional_stats = count($rowsetregional);
        $view->total_national_stats = count($rowsetnational);

        $isFavorite = "false";
        if ($session->email != null) {
            $userPlayer = new UserPlayer();
            $row = $userPlayer->findUserPlayer($session->userId, $playerId);
            if ($row != null) {
                $isFavorite = "true";
            }
        }
        $view->isFavorite = $isFavorite;
        //get the goalshouts of a given player
        $uc = new Comment();
        $playercomments = $uc->findCommentsPerPlayer($playerId, 5);
        $pa = new Activity();
        $playerActivities = $pa->selectActivitiesPerPlayer($playerId, null);
        $totalPlayerComments = $uc->findCommentsPerPlayer($playerId);
        $view->totalPlayerComments = count($totalPlayerComments);
        $view->playercomments = $playercomments;
        $view->playeractivities = $playerActivities;
        $view->playerMenuSelected = 'profile';
        $common = new Common();
        $view->playerage = $common->GetAge($rowset->player_dob);
        $this->breadcrumbs->addStep(
        $rowset->player_firstname . " " . $rowset->player_lastname);
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewplayer2.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    
    function getuniqueplayerAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $userId = $session->userId;
        $playerId = $this->_request->getParam('id', 0);
        $view = Zend_Registry::get('view');
        if (! $userId && $this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->viewRenderer->setNoRender(true);
            echo Zend_Json::encode(
            array("status" => 0,
            "msg" => "You need to login to suscribe to get this player's information!"));
            return;
        }
        $playerData = array("status" => 1);
        $player = new Player();
        $league = new LeagueCompetition();
        $playerProfileImage = $player->getPlayerProfileImage($playerId);
        $playerData['profile_image'] = (isset($playerProfileImage[0])) ? $playerProfileImage[0]['imagefilename'] : "";
        $rowsetdetail = $player->findUniquePlayerDetailed($playerId);
        $playerData['player_details'] = $rowsetdetail[0];
        $isFavorite = "false";
        if ($session->email != null) {
            $userPlayer = new UserPlayer();
            $row = $userPlayer->findUserPlayer($session->userId, $playerId);
            if ($row != null) {
                $isFavorite = "true";
            }
        }
        $playerData['isFavorite'] = $isFavorite;
        $common = new Common();
        $playerData['playerage'] = $common->GetAge($rowsetdetail[0]['player_dob']);
        $this->_helper->viewRenderer->setNoRender(true);
        echo Zend_Json::encode($playerData);
    }

    public function showmatchplayerstatsAction () {
         $view = Zend_Registry::get('view');
        //get Full stats by player
        $playerId = (int) $this->_request->getParam('id', 0);
        $teamId = (int) $this->_request->getParam('team', 0);
        $layout = $this->_request->getParam ( 'detail', null );
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
           //when select is ALL
        if ($teamId == 0){
            $teamId = null;
        }
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $view->playerposition = $rowset->player_position;
        $teamsplayedagainst = $player->getPlayerMatchEventsTeamsSelect($playerId);
        $view->teamselect = $teamsplayedagainst;
        $currentmatchstats = $player->getPlayerMatchEventsTeams($playerId,$teamId);
        //Zend_Debug::dump ($rowset->player_position);
        $paginator = Zend_Paginator::factory($currentmatchstats);
        $paginator->setCurrentPageNumber($pageNumber);
        if($layout == null){
            $paginator->setItemCountPerPage(10);
            $this->view->paginator = $paginator;
            $this->_response->setBody($this->view->render('playermatchstatsview.php'));
        } else {
            $paginator->setItemCountPerPage(20);
            $this->view->paginator = $paginator;
            $this->_response->setBody($this->view->render('playermatchstatsdetailview.php'));
        }
    }

    public function showplayerstatsdetailAction() {
        $view = Zend_Registry::get('view');
        $session = new Zend_Session_Namespace('userSession');
        $playerId = (int) $this->_request->getParam('id', 0);
        $view->playerid = $playerId;
        $player = new Player();
        $isFavorite = "false";
        if ($session->email != null) {
            $userPlayer = new UserPlayer();
            $row = $userPlayer->findUserPlayer($session->userId, $playerId);
            if ($row != null) {
                $isFavorite = "true";
            }
        }
        $view->isFavorite = $isFavorite;
        $rowset = $player->findUniquePlayer($playerId);
        $view->playername = $rowset->player_name_short;
        $view->title = $rowset->player_name_short . " Stats";
        //build player badge
        $currentTeamId = $this->buildplayerbadge($playerId, $rowset->player_country, $view, $rowset);
        $teamsplayedagainst = $player->getPlayerMatchEventsTeamsSelect($playerId);
        $view->teamselect = $teamsplayedagainst;

        $view->playerMenuSelected = 'stats';

        /*//get stats from feed for domestic cup and regional competitions
        $feedpath = 'soccerstats/player/' . $playerId;
        $xml = parent::getGoalserveFeed($feedpath);
        //all league stats
        $league_stats = $xml->xpath("/players/player/statistic/club");
        $club_stats[] = $league_stats[0];*/

        //Get Current stats goals, lineups, subins, minutes, minutes subin, yc and card per LEAGUE season
        //$activeseasonstats = $player->getPlayerMatchEventsTotals($playerId,$currentTeamId['competition_id'],$currentTeamId['title']);
        //Zend_Debug::dump ($activeseasonstats);

        // $view->season_appearences = $activeseasonstats[0]['total_appear'];
        // $view->season_minutes = $activeseasonstats[0]['total_full_minutes'];
        // $view->season_goals = $activeseasonstats[0]['total_goals'];
        // $view->season_goals_allowed = $activeseasonstats[0]['total_goals_allowed'];
        // $view->season_clean_sheets = $activeseasonstats[0]['total_clean_sheets'];
        // $view->season_yellowcards = $activeseasonstats[0]['total_yellow_cards'];
        // $view->season_redcards = $activeseasonstats[0]['total_red_cards'];

        //current league stats

        $leaguestats = $this->getfeedstats($playerId,'league');
        $active_league_stats = $leaguestats['stats'][0];
        $view->activeleaguestats = $active_league_stats;



        $view->actionTemplate = 'playerstatsdetail.php';
        $this->_response->setBody($view->render('site.tpl.php'));

    }

    public function showplayerministatsAction ()
    {
        $view = Zend_Registry::get('view');
        //get Full stats by player
        $playerId = (int) $this->_request->getParam('playerId', 0);
        $format = $this->_request->getParam('format', '');
        //$playerposition = (string) $this->_request->getParam('playerpos');

        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);

        if ($rowset->player_position == 'Goalkeeper') {
            //$rowsetdetailsfull = $player->getPlayerKeeperTeamStatsDetails($playerId, $format);
            $rowsetdetailsfull = $this->getfeedstats($playerId,$format);
        } else {
            //$rowsetdetailsfull = $player->getPlayerTeamStatsDetails($playerId,$format);
            $rowsetdetailsfull = $this->getfeedstats($playerId,$format);
        }
        //Zend_Debug::dump ( $rowsetdetailsfull );
        $view->playerposition = $rowset->player_position;
        $view->playerSeasonFull = $rowsetdetailsfull['stats'];
        //$this->_response->setBody($this->view->render('playerministatsview.php'));
        $this->_response->setBody($this->view->render('playerseasonstatsview.php'));
    }



    public function findactivitiesAction ()
    {
        $view = Zend_Registry::get('view');
        $playerId = $this->_request->getParam('id', 0);
        $type = $this->_request->getParam('type', 0);
        $pa = new Activity();
        $playerActivities = $pa->selectActivitiesPerPlayer($playerId, $type);
        $view->playeractivities = $playerActivities;
        $this->_response->setBody($view->render('playeractivitylist.php'));
    }
    public function showplayersbyteamAction ()
    {
        $view = Zend_Registry::get('view');
        $team = new Team();
        $teamId = $this->_request->getParam('t', null);
        $players = null;
        if ($teamId != null) {
            $players = $team->findPlayersbyTeam($teamId);
        }
        $view->players = $players;
        $this->_response->setBody($view->render('teamplayersview.php'));
    }
    public function showplayerbycompetitionAction ()
    {
        $view = Zend_Registry::get('view');
        $session = new Zend_Session_Namespace('userSession');
        $seasonId = $this->_request->getParam('s', null);
        $player = new Player();
        $playerseason = new PlayerSeason(); // fetch all records from the table
        // getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }

        if($session->userId != null) {
        	$playerfeatured = $player->findFeaturedPlayersUser($session->userId);
        } else {
        	$playerfeatured = $player->findFeaturedPlayers();
        }
        $paginator = Zend_Paginator::factory($playerfeatured);
        $paginator->setCurrentPageNumber($pageNumber);
        $numberOfItems = 20;
        //if($type == 'modal'){
        //	$numberOfItems = 10;
        //}
        $paginator->setItemCountPerPage($numberOfItems);
        $renderPage = 'playersbycompetition.php';
        //if($type == 'modal'){
        //	$renderPage = 'teamsearchresult.php';
        //}
        $view->paginator = $paginator;
        $this->_response->setBody($view->render($renderPage));
    }

    public function showplayerdirectoryAction ()
    {
        $view = Zend_Registry::get('view');
        $alphabet_array = null;
        $fromTo = $this->_request->getParam('ft', "AF");
        $position = $this->_request->getParam('p', null);
        $date = $this->_request->getParam('d', null);
        $countryId = $this->_request->getParam('c', null);
        $dates = null;
        if ($date != null) {
            $todays_date = date("Y-m-d");
            $ts = strtotime($todays_date);
            $one_day = 24 * 60 * 60;
            if ($date == "today") {
                $dates[0] = $todays_date . " 00:00:00";
                $dates[1] = $todays_date . " 23:59:59";
            } else
                if ($date == "last3") {
                    //substract 3 days
                    $dates[0] = date("Y-m-d",
                    ($ts - 3 * $one_day)) . " 00:00:00";
                    $dates[1] = $todays_date . " 23:59:59";
                } else
                    if ($date == "last7") {
                        //substract 7 days
                        $dates[0] = date("Y-m-d",
                        ($ts - 7 * $one_day)) . " 00:00:00";
                        $dates[1] = $todays_date . " 23:59:59";
                    }
        }
        $view->position = $position;
        if ($position != null) {
            if ($position == "GK") {
                $position = "Goalkeeper";
            } else
                if ($position == "AT") {
                    $position = "Attacker";
                } else
                    if ($position == "MD") {
                        $position = "Midfielder";
                    } else
                        if ($position == "DF") {
                            $position = "Defender";
                        }
        }
        if ($fromTo == 'AF') {
            $alphabet_array = array('A', 'B', 'C', 'D', 'E', 'F');
        } else
            if ($fromTo == 'GL') {
                $alphabet_array = array('G', 'H', 'I', 'J', 'K', 'L');
            } else
                if ($fromTo == 'MR') {
                    $alphabet_array = array('M', 'N', 'O', 'P', 'Q', 'R');
                } else
                    if ($fromTo == 'SZ') {
                        $alphabet_array = array('S', 'T', 'U', 'V', 'W', 'X ',
                        'Y', 'Z');
                    }
        $player = new Player();
        $playerRow = null;
        $playerTotalRow = null;
        if ($date == null) {
            for ($i = 0; $i < count($alphabet_array); $i ++) {
                $playerRow[$i] = $player->selectPlayersByAlphabet(
                $alphabet_array[$i], $position, $dates, $countryId);
                $playerTotalRow[$i] = $player->countPlayersByLetter(
                $alphabet_array[$i], $position, $dates, $countryId);
            }
            $view->playersByLetter = $playerRow;
            $view->alphabetArray = $alphabet_array;
        } else {
            $playersResult = $player->selectPlayers(null, null, null, null,
            $dates);
            //			$totalRows = $player->countPlayersByLetter2 ( null, null, $dates );
            $view->players = $playersResult;
             //			$view->totalPlayers = $totalRows;
        }
        $view->playerByLetterTotal = $playerTotalRow;
        $view->countryId = $countryId;
        if ($date == null) {
            $this->_response->setBody(
            $view->render('playerdirectoryresult.php'));
        } else {
            $this->_response->setBody(
            $view->render('playersearchresult2.php'));
        }
    }
    function showfeaturedplayersAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        //$this->checkifUserIsRemembered();
        $view = Zend_Registry::get('view');
        $alphabet_array = null; //array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X ', 'Y', 'Z' );
        $fromTo = $this->_request->getParam('ft', "AF");
        $position = $this->_request->getParam('p', null);
        if ($position == "GK") {
            $position = "Goalkeeper";
        } else
            if ($position == "AT") {
                $position = "Attacker";
            } else
                if ($position == "MD") {
                    $position = "Midfielder";
                } else
                    if ($position == "DF") {
                        $position = "Defender";
                    }
        if ($fromTo == 'AF') {
            $alphabet_array = array('A', 'B', 'C', 'D', 'E', 'F');
        } else
            if ($fromTo == 'GL') {
                $alphabet_array = array('G', 'H', 'I', 'J', 'K', 'L');
            } else
                if ($fromTo == 'MR') {
                    $alphabet_array = array('M', 'N', 'O', 'P', 'Q', 'R');
                } else
                    if ($fromTo == 'SZ') {
                        $alphabet_array = array('S', 'T', 'U', 'V', 'W', 'X ',
                        'Y', 'Z');
                    }
        $player = new Player();
        for ($i = 0; $i < count($alphabet_array); $i ++) {
            $playerRow[$i] = $player->selectPlayersByAlphabet(
            $alphabet_array[$i], $position);
            $playerTotalRow[$i] = $player->countPlayersByLetter(
            $alphabet_array[$i], $position);
        }
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle('', PageType::$_PLAYERS_MAIN_PAGE);
        $view->keywords = $keywords->getMetaKeywords('',
        PageType::$_PLAYERS_MAIN_PAGE);
        $view->description = $description->getMetaDescription('',
        PageType::$_PLAYERS_MAIN_PAGE);
        $view->playersByLetter = $playerRow;
        $view->playerByLetterTotal = $playerTotalRow;
        $view->alphabetArray = $alphabet_array;
        //$playerArow = $player->selectPlayersByAlphabet('A');
        //Zend_Debug::dump($playerArow);
        //$view->listA = $playerArow;
        $player = new Player();
        //Non International
        if($session->userId != null) {
              $this->view->featuredPlayers = $player->findFeaturedPlayers(4,null,$session->userId);
              $this->view->popularPlayers = $player->findPopularPlayers(12,$session->userId);
        } else {
             $this->view->featuredPlayers = $player->findFeaturedPlayers(4);
             $this->view->popularPlayers = $player->findPopularPlayers(12);
        }

        //added for the worldcup featured players by season
        //$seasonId = 4770;
        //$this->view->featuredPlayers = $player->findPlayersBySeason($seasonId,4);

        //$feedpath = 'soccerstats/player/119';
        //$xmlfeed = parent::getGoalserveFeed($feedpath);
        //Zend_Debug::dump($xmlfeed);
        $this->view->breadcrumbs = $this->breadcrumbs;
        //country List
        $country = new Country();
        $countries = $country->selectCountries();
        $view->countries = $countries;
        $view->playersMenuSelected = 'home';
        $view->actionTemplate = 'featuredplayers.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    function autocompleteplayerAction ()
    {
        $player = new Player();
        $playerName = $this->_request->getParam('term', null);
        $callback = $this->_request->getParam('callback', null);
        $results = $player->findPlayersAutoComplete($playerName);
        $this->_helper->viewRenderer->setNoRender(true);
        echo $callback . "(" . Zend_Json::encode($results) . ")";
    }
    function showmorefeaturedplayersAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle('',
        PageType::$_PLAYERS_FEATURED_PAGE);
        $view->keywords = $keywords->getMetaKeywords('',
        PageType::$_PLAYERS_FEATURED_PAGE);
        $view->description = $description->getMetaDescription('',
        PageType::$_PLAYERS_FEATURED_PAGE);
        $this->breadcrumbs->addStep('Featured Players');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $player = new Player();
        if($session->userId != null) {
          //$this->view->featuredPlayers = $player->findFeaturedPlayers(16,null,$session->userId);
          $popularPlayers = $player->findFeaturedPlayers(null,null,$session->userId);
        } else {
          //$this->view->featuredPlayers = $player->findFeaturedPlayers(16);
          $popularPlayers = $player->findFeaturedPlayers();
        }
        //$seasonId = 4770;
        //$popularPlayers = $player->findPlayersBySeason($seasonId, 600);
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($popularPlayers);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
        $view->playersMenuSelected = 'featured';
        $view->actionTemplate = 'featuredplayersmore.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    function showmorepopularplayersAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle('',
        PageType::$_PLAYERS_POPULAR_PAGE);
        $view->keywords = $keywords->getMetaKeywords('',
        PageType::$_PLAYERS_POPULAR_PAGE);
        $view->description = $description->getMetaDescription('',
        PageType::$_PLAYERS_POPULAR_PAGE);
        $this->breadcrumbs->addStep('Popular Players');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $player = new Player();
        //$this->view->featuredPlayers =$player->findFeaturedPlayers(16);
        if($session->userId != null) {
            $popularPlayers = $player->findPopularPlayers(null,$session->userId);
        } else {
           $popularPlayers = $player->findPopularPlayers(null);
        }

        //Zend_Debug::dump($popularPlayers);
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($popularPlayers);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
        $view->playersMenuSelected = 'popular';
        $view->actionTemplate = 'popularplayersmore.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    function showplayersbyalphabetAction ()
    {
        $view = Zend_Registry::get('view');
        $session = new Zend_Session_Namespace('userSession');
        $alphabet_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
        'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
        'X', 'Y', 'Z');
        $view->alphabetArray = $alphabet_array;
        $letter = $this->_request->getParam('letter', 0);
        $position = $this->_request->getParam('position', null);
        $countryId = $this->_request->getParam('c', null);
        $view->position = $position;
        $view->countryId = $countryId;
        if ($position == "GK") {
            $position = "Goalkeeper";
        } else
            if ($position == "AT") {
                $position = "Attacker";
            } else
                if ($position == "MD") {
                    $position = "Midfielder";
                } else
                    if ($position == "DF") {
                        $position = "Defender";
                    }
        if ($letter != null) {
            $session->letter = $letter;
        } else {
            $letter = $session->letter;
        }
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle($letter,
        PageType::$_PLAYERS_BY_ALPHABET);
        $view->keywords = $keywords->getMetaKeywords($letter,
        PageType::$_PLAYERS_BY_ALPHABET);
        $view->description = $description->getMetaDescription($letter,
        PageType::$_PLAYERS_BY_ALPHABET);
        $player = new Player();
        //$this->view->featuredPlayers =$player->findFeaturedPlayers(3);
        $this->view->popularPlayers = $player->findPopularPlayers(3);
        $rowAllPlayers = $player->selectPlayers($letter);
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        $itemNumber = null;
        if (empty($pageNumber)) {
            $pageNumber = 1;
            $itemNumber = 1;
        }
        $paginator = Zend_Paginator::factory($rowAllPlayers);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        if ($pageNumber > 1) {
            $itemNumber = ($pageNumber * $this->getNumberOfItemsPerPage() + 1) -
             20;
        } else {
            $itemNumber = 1;
        }
        $this->view->itemNumber = $itemNumber;
        $this->view->paginator = $paginator;
        $view->playersMenuSelected = 'directory';
        $directory = Zend_Registry::get("contextPath") .
         "/player/showplayersbyalphabet/letter/A";
        $this->breadcrumbs->addStep("Player Directory", $directory);
        $view->playersMenuSelected = 'directory';
        $this->breadcrumbs->addStep("Names Starting with '" . $letter . "'");
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->letter = $letter;
        $view->actionTemplate = 'playersbyalphabet.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    public function showplayeractivityAction ()
    {
        $playerId = $this->_request->getParam('id', 0);
        $type = $this->_request->getParam('type', 0);
        $view = Zend_Registry::get('view');
        //from findUniquePlayer
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $view->title = $rowset->player_name_short . " Activities";
        $this->buildplayerbadge($playerId, $rowset->player_country, $view,
        $rowset);
        $pa = new Activity();
        $playerActivities = $pa->selectActivitiesPerPlayer($playerId, $type,
        'n');
        $view->playeractivities = $playerActivities;
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($playerActivities);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;
        $view->playerMenuSelected = 'activity';
        $view->submitvalue = $type;
        $urlGen = new SeoUrlGen();
        $playerUrlGen = $urlGen->getPlayerMasterProfileUrl(
        $rowset->player_nickname, $rowset->player_firstname,
        $rowset->player_lastname, $rowset->player_id, true,
        $rowset->player_common_name);
        $view->playerUrlGen = $playerUrlGen;
        $this->breadcrumbs->addStep($rowset->player_name_short, $playerUrlGen);
        $this->breadcrumbs->addStep('Activity');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewallplayeractivity.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    public function showplayergoalshoutsAction ()
    {
        $playerId = $this->_request->getParam('id', 0);
        $view = Zend_Registry::get('view');
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $view->title = $rowset->player_name_short . " Goalshouts";
        //build left Player Badge
        $this->buildplayerbadge($playerId, $rowset->player_country,
        $view, $rowset);
        //get the goalshouts of a given player
        $uc = new Comment();
        $playercomments = $uc->findCommentsPerPlayer($playerId);
        $totalPlayerComments = $uc->fetchAll("comment_party_id=" . $playerId);
        $view->totalPlayerComments = count($totalPlayerComments);
        $view->playercomments = $playercomments;
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($playercomments);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;
        $view->playerMenuSelected = 'shouts';
        $urlGen = new SeoUrlGen();
        $this->breadcrumbs->addStep($rowset->player_name_short,
        $urlGen->getPlayerMasterProfileUrl($rowset->player_nickname,
        $rowset->player_firstname, $rowset->player_lastname, $rowset->player_id,
        true, $rowset->player_common_name));
        $this->breadcrumbs->addStep('Gooal Shouts');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewallplayergoalshouts.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    public function showplayerfansAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $user = new User();
        $player = new Player();
        $playerId = $this->_request->getParam('id', '');
        $rowset = $player->findUniquePlayer($playerId);
        $view->title = $rowset->player_name_short . " Fans";
        //build left Player Badge
        $this->buildplayerbadge($playerId, $rowset->player_country,
        $view, $rowset);
        //get Players Fan Profile
        $fanPlayerProfiles = $user->findUserProfilesByPlayer($playerId,
        null, $session->userId != null ? $session->userId : null);
        $view->playerfanprofiles = $fanPlayerProfiles;
        $view->totalplayerfans = count($fanPlayerProfiles);
        //pagination - getting request variables
        /*$pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($fanPlayerProfiles);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;*/
        $view->playerMenuSelected = 'fans';
        $urlGen = new SeoUrlGen();
        $this->breadcrumbs->addStep($rowset->player_name_short,
        $urlGen->getPlayerMasterProfileUrl($rowset->player_nickname,
        $rowset->player_firstname, $rowset->player_lastname, $rowset->player_id,
        true, $rowset->player_common_name));
        $this->breadcrumbs->addStep('Fans');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $typeOfDisplay = $this->_request->getParam('typeofdisplay', 'grid');
        $view->typeOfDisplay = $typeOfDisplay;
        $view->actionTemplate = 'viewallplayerfans.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    function showplayerfansresultAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $player = new Player();
        $user = new User();
        $playerId = $this->_request->getParam('id', '');
        $view->type = $this->_request->getParam('type', 'list');
        $rowset = $player->findUniquePlayer($playerId);
        //$currentTeamId = $this->buildplayerbadge($playerId,$rowset->player_country,$view,$rowset);
        //$format = 'Domestic league'; //show domestic league stats in default view - teammates detail page
        //$rowsetmates = $player->findTeammatesByPlayer ( $currentTeamId, $playerId ,$session->userId!=null?$session->userId :null,$format);
        //get Players Fan Profile
        $fanPlayerProfiles = $user->findUserProfilesByPlayer(
        $playerId, null, $session->userId != null ? $session->userId : null);
        $view->playerfanprofiles = $fanPlayerProfiles;
        $view->totalplayerfans = count($fanPlayerProfiles);
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($fanPlayerProfiles);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage(
        ($this->getNumberOfItemsPerPage() < count($fanPlayerProfiles)) ? $this->getNumberOfItemsPerPage() : count(
        $fanPlayerProfiles));
        $this->view->paginator = $paginator;
        $this->_response->setBody($view->render('playerfansresult.php'));
    }
    //showplayersteammates
    public function showplayerteammatesAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $player = new Player();
        $playerId = $this->_request->getParam('id', '');
        $rowset = $player->findUniquePlayer($playerId);
        $view->title = $rowset->player_name_short . " Teammates";
        //build player badge
        $currentTeamId = $this->buildplayerbadge($playerId, $rowset->player_country, $view, $rowset);
        $format = 'Domestic league'; //show domestic league stats in default view - teammates detail page
        $rowsetmates = $player->findTeammatesByPlayer(
        $currentTeamId['team_id'], $playerId, $session->userId != null ? $session->userId : null, $format);
        $view->playerteammates = $rowsetmates;
        $view->totalteammates = count($rowsetmates);
        $view->playerMenuSelected = 'mates';
        $urlGen = new SeoUrlGen();
        $this->breadcrumbs->addStep($rowset->player_name_short, $urlGen->getPlayerMasterProfileUrl($rowset->player_nickname,$rowset->player_firstname, $rowset->player_lastname, $rowset->player_id,true, $rowset->player_common_name));
        $this->breadcrumbs->addStep('Teammates');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewallplayerteammates.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    function showplayerteammatesresultAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $view = Zend_Registry::get('view');
        $player = new Player();
        $playerId = $this->_request->getParam('id', '');
        $view->type = $this->_request->getParam('type', 'list');
        $rowset = $player->findUniquePlayer($playerId);
        $currentTeamId = $this->buildplayerbadge($playerId,
        $rowset->player_country, $view, $rowset);
        $format = 'Domestic league'; //show domestic league stats in default view - teammates detail page
        $rowsetmates = $player->findTeammatesByPlayer($currentTeamId['team_id'], $playerId,$session->userId != null ? $session->userId : null, $format);
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($rowsetmates);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
        $this->_response->setBody($view->render('playerteamateresult.php'));
    }
     function showplayerstatsAction ()
    {
        $playerId = $this->_request->getParam('id', 0);
        $view = Zend_Registry::get('view');
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $title = new PageTitleGen();
        $keywords = new MetaKeywordGen();
        $description = new MetaDescriptionGen();
        $view->title = $title->getPageTitle($rowset,
        PageType::$_PLAYER_MASTER_PAGE_STATS);
        $view->keywords = $keywords->getMetaKeywords($rowset,
        PageType::$_PLAYER_MASTER_PAGE_STATS);
        $view->description = $description->getMetaDescription($rowset,
        PageType::$_PLAYER_MASTER_PAGE_STATS);
        //build left Player Badge
        $currentTeamId = $this->buildplayerbadge($playerId,
        $rowset->player_country, $view, $rowset);
        $format = 1; //$format == 1 , Domestic League
        //get Player stats
        $rowsetstats = $player->getPlayerTeamStatsDetails(
        $playerId, $format);
        $view->playerstats = $rowsetstats;
        $view->playerMenuSelected = 'stats';
        $feedpath = 'soccerstats/player/' . $playerId;
        $xmlfeedplayer = parent::getGoalserveFeed($feedpath);
        $league_stats = $xmlfeedplayer->xpath(
        "//players/player/statistic/club[@season='" . $currentTeamId['title'] .
         "']");
        $view->leaguestats = $league_stats;
        $urlGen = new SeoUrlGen();
        $this->breadcrumbs->addStep($rowset->player_name_short,
        $urlGen->getPlayerMasterProfileUrl($rowset->player_nickname,
        $rowset->player_firstname, $rowset->player_lastname, $rowset->player_id,
        true, $rowset->player_common_name));
        $this->breadcrumbs->addStep('Career Statistics');
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewplayerstats.php';
        $this->_response->setBody($view->render('site.tpl.php'));
    }
    public function addfavoriteAction ()
    {
        $urlGen = new SeoUrlGen();
        $session = new Zend_Session_Namespace("userSession");
        $fromPage = $this->_request->getPost('fromPage', '');
        $playerId = $this->_request->getPost('playerId');
        $checkEmail = $this->_request->getPost('updatesCheck');
        $jsonAction = $this->_request->getPost('jsonAction');
        $frequency = '0';
        if ($checkEmail == '1') {
            $frequency = '6,7,8,9';
        }
        $userId = $session->userId;
        $user_player = new UserPlayer();
        //add country favority
        $data = array('user_id' => $userId, 'player_id' => $playerId,
        'alert_email' => $checkEmail, 'alert_frecuency_type' => $frequency);
        //If user is not logged in, return this message
        if (! $userId && ! empty($jsonAction)) {
            $this->_helper->viewRenderer->setNoRender(true);
            echo Zend_Json::encode(
            array("status" => 0,
            "msg" => "You need to login to suscribe to this player's updates!"));
            return;
        }
        $exist = $user_player->findUserPlayer($userId, $playerId);
        if ($exist == null) {
        //Insert Player on UserPlayer table
            $user_player->insert($data);
        } else {
            //userplayer already exists show Error Message
            //Return a JSON action
            if (! empty($jsonAction)) {
                $this->_helper->viewRenderer->setNoRender(true);
                echo Zend_Json::encode(
                array("status" => 0,
                "msg" => "You've already subscribed to this player's udpates!"));
                return;
            } else {
                echo "ko";
                return;
            }
        }
        //Create a new Activity Event for your feed
        $screenName = $session->screenName;
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $player_name_seo = $urlGen->getPlayerMasterProfileUrl(
	        $rowset->player_nickname, $rowset->player_firstname,
	        $rowset->player_lastname, $rowset->player_id, true,
	        $rowset->player_common_name);

        $config = Zend_Registry::get('config');
        $imageName = $config->path->images->fanphotos . $session->mainPhoto;
        $variablesToReplace = array('username' => $screenName,
	        'player_name_seo' => $player_name_seo,
	        'player_name' => $rowset->player_name_short,
	        'gender' => ($session->user->gender == 'm' ? 'his' : 'her'));
        $activityType = Constants::$_ADD_FAVORITE_PLAYER_ACTIVITY;
        $activityAddFavoritePlayer = new Activity();
        $activityAddFavoritePlayer->insertUserActivityByActivityType(
        $activityType, $variablesToReplace, $userId, 1, null, null, null,
        $imageName);
        //a new activity for the playeriteself
        $activityAddFavoritePlayer->insertUserActivityByActivityType(
        $activityType, $variablesToReplace, null, 'n', $playerId, null, null,
        $imageName);
        //Return a JSON action
        if (! empty($jsonAction)) {
            $this->_helper->viewRenderer->setNoRender(true);
            echo Zend_Json::encode(
            array("status" => 1,
            	  "msg" => "The player was successfully added to your favorites"));
            return;
        }
        if ($fromPage == 'edit') {
            $this->_redirect("/profile/editfavorities/editAction/players/page/");
        }
    }


    public function removefavoriteAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $playerId = $this->_request->getPost('id');
        $userId = $session->userId;
        $user_player = new UserPlayer();
        $user_player->deleteUserPlayer($userId, $playerId);
    }
    public function removefavoriteplayersAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $arrayFavPlayersToRemove = $this->_request->getPost('arrayFavorities');
        //echo($session->userId);
        $userPlayer = new UserPlayer();
        foreach ($arrayFavPlayersToRemove as $playerId) {
            $userPlayer->deleteUserPlayer($session->userId, $playerId);
        }
        $this->_redirect("/profile/editfavorities/editAction/players/page/");
    }
    public function findplayersAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $param1 = null;
        //Zend_Debug::dump ( $this->_request );
        $param1 = $this->_request->getParam("q");
        if ($this->_request->isPost()) {
            $param1 = trim(
            $filter->filter($this->_request->getPost('value')));
        } else {
            $param1 = trim(
            $filter->filter($this->getRequest()
                ->getParam('q')));
        }
        $player = new Player();
         //$results = $player->findPlayers ( $param1 ) ;
    //		$results = $player->findPlayerByName ( $param1 );
    //		echo '<ul>';
    //		foreach ( $results as $result ) {
    //			echo '<li id="' . $result ['player_id'] . '">' . trim($result ['player_name_short']) . '</li>';
    //		}
    //		echo '</ul>';
    }
    public function findplayersregisterAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $param1 = null;
        if ($this->_request->isPost()) {
            $param1 = trim(
            $filter->filter($this->_request->getPost('value')));
        } else {
            $param1 = trim(
            $filter->filter($this->getRequest()->getParam('q')));
        }
        $player = new Player();
        $results = $player->findPlayers($param1);
        //Zend_Debug::dump($param1);
        //$results = $player->findPlayerByName ( $param1 );
        //echo '<ul>';
        /*$items = array(
			"0001"=>"Ronaldo",
			"0002"=>"Pizarro",
			"0003"=>"Adriano",
			"0004"=>"Henry",
			"0005"=>"Pinto",
			"0006"=>"Pirco",
			);
		*/
        foreach ($results as $result) {
            //echo '<li id="' . $result ['player_id'] . '">' . trim($result ['player_name_short']) . '</li>';
            echo trim($result['player_name_short']) . '|' .
             $result['player_id'] . "\n";
        }
        /*foreach ($items as $key=>$value) {
			echo $value ."|" .$key ."	\n";
		}*/
    }
    public function findplayersbycountryAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $countryid = trim(
        $filter->filter($this->getRequest()->getParam('id')));
        $player = new Player();
        $results = $player->findPlayersByCountryId($countryid);
        echo '<option value="0" selected>Select Player</option>';
        foreach ($results as $data) {
            echo '<option value=' . trim($data['player_id']) . '>' .
             trim($data['player_firstname']) . " " .
             trim($data['player_lastname']);
        }
    }
    public function findplayersbyteamAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $teamid = trim($filter->filter($this->getRequest()->getParam('id')));
        $seasonid = trim($filter->filter($this->getRequest()->getParam('season',null)));
        $team = new Team();
        $playerseason = new PlayerSeason();
        if ($seasonid == null) {
        	$results = $team->findPlayersbyTeam($teamid);
        	  //Zend_Debug::dump($results);
        } else {
        	//national teams squad per tournaments
        	$results = $playerseason->selectPlayersNationalBySeason($teamid,$seasonid);
        }
        echo '<option value="0" selected>Select Player</option>';
        foreach ($results as $data) {
            echo '<option value=' . trim($data['player_id']) . '>' .
             //trim($data['player_firstname']) . " " .
             trim($data['player_name_short']);
        }
    }

    public function findteamsbycountryAction ()
    {
        $view = Zend_Registry::get('view');
        $view->title = "User Favorites";
        $filter = new Zend_Filter_StripTags();
        $team = new Team();
        $country = $this->_request->getParam("c", null);
        if ($country != null) {
            $rowArray = $team->selectTeamsByCountry($country);
            echo '<option value="0" selected>Select Team</option>';
            foreach ($rowArray as $data) {
                if ($data['team_soccer_type'] != 'women') {
                    echo '<option value=' . $data['team_id'] . '>' .
                     $data['team_name'];
                } else {
                    echo '<option value=' . $data['team_id'] . '>' .
                     $data['team_name'] . ' - Women';
                }
            }
        }
    }
    public function findfavoriteuserplayerAction ()
    {
        $view = Zend_Registry::get('view');
        $session = new Zend_Session_Namespace('userSession');
        if ($session->screenName == null) {
            $view->sessionTimeout = true;
            return;
        }
        $userPlayer = new UserPlayer();
        $currentUserId = $this->_request->getParam("u", $session->userId);
        $favPlayers = $userPlayer->findUserPlayers($currentUserId, null, 0);
        $view->favPlayers = $favPlayers;
        $this->_response->setBody($view->render('favoriteplayersresult.php'));
    }
    public function addplayerAction ()
    {
        $view = Zend_Registry::get('view');
        $filter = new Zend_Filter_StripTags();
        $modal = trim($filter->filter($this->_request->getParam('modal')));
        $view->modal = $modal;
        $this->_response->setBody($view->render('addPlayerModal.php'));
    }
    public function findcountriesbycontinentAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $regionId = trim($filter->filter($this->_request->getParam('r')));
        //Zend_Debug::dump($regionId);
        $compFile = new Competitionfile();
        $row = $compFile->selectCountriesByContinent($regionId);
        echo '<option value="Select Country" selected>Select Country</option>';
        foreach ($row as $data) {
            echo '<option value=' . $data['country_id'] . '>' .
             $data['country_name'];
        }
    }
    public function searchplayerAction ()
    {
        $view = Zend_Registry::get('view');
        $type = $this->_request->getParam("t");
        $criteria = $this->_request->getParam("q");
        $player = new Player();
        $team = new Team();
        $result = null;
        $session = new Zend_Session_Namespace('userSession');
        if ($type == 'n') {
            if ($criteria != "") {
                $result = $player->findPlayerByName($criteria, null,
                $session->userId);
            }
        } else
            if ($type == 't') {
                if ($criteria != "") {
                    $result = $team->findPlayersbyTeamSearch($session->userId,
                    $criteria);
                }
            }
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)) {
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($result);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;
        $this->_response->setBody($view->render('playersearchresult.php'));
    }
    public function insertfavoriteplayerAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $playerid = $this->_request->getParam("id");
        $user_player = new UserPlayer();
        $data = array('user_id' => $session->userId, 'player_id' => $playerid);
        $user_player->insert($data);
        $this->_redirect("/user/editFavorities/editAction/players");
    }
    public function deletefavoriteplayerAction ()
    {
        $session = new Zend_Session_Namespace('userSession');
        $playerid = $this->_request->getParam("id");
        $user_player = new UserPlayer();
        $user_player->deleteUserPlayer($session->userId, $playerid);
        $this->_redirect("/user/editFavorities/editAction/players");
    }
    public function updatefavoriteplayersAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $arrayPlayers = array();
        $arrayAllTime = array();
        $arrayStar11 = array();
        $arrayMySquad = array();
        $numPlayers = (int) trim(
        $filter->filter($this->_request->getPost('numPlayers')));
        $alltime = trim(
        $filter->filter($this->_request->getPost('favalltime')));
        for ($i = 1; $i <= $numPlayers; $i ++) {
            if (trim(
            $filter->filter($this->_request->getPost('playerId' . $i) != ''))) {
                $arrayPlayers[$i - 1] = trim(
                $filter->filter($this->_request->getPost('playerId' . $i)));
                if ($alltime == $arrayPlayers[$i - 1]) {
                    $arrayAllTime[$i - 1] = '1';
                } else {
                    $arrayAllTime[$i - 1] = '0';
                }
                $arrayStar11[$i - 1] = trim(
                $filter->filter($this->_request->getPost('favstart' . $i))) != '' ? trim(
                $filter->filter($this->_request->getPost('favstart' . $i))) : '0';
                $arrayMySquad[$i - 1] = trim(
                $filter->filter($this->_request->getPost('favsquad' . $i))) != '' ? trim(
                $filter->filter($this->_request->getPost('favsquad' . $i))) : '0';
            }
        }
        //delete all previous favorite players
        $user_player = new UserPlayer();
        $session = new Zend_Session_Namespace('userSession');
        $user_id = $session->userId;
        $user_player->deleteAllUserPlayers($user_id);
        //add the new players
        for ($z = 0; $z < count($arrayPlayers); $z ++) {
            $data2 = array('user_id' => $user_id,
            'player_id' => $arrayPlayers[$z], 'greatest' => $arrayAllTime[$z],
            'startingeleven' => $arrayStar11[$z],
            'mysquad' => $arrayMySquad[$z]);
            $user_player->insert($data2);
        }
        //		Zend_Debug::dump($arrayPlayers);
        //		Zend_Debug::dump($arrayAllTime);
        //		Zend_Debug::dump($arrayStar11);
        //		Zend_Debug::dump($arrayMySquad);
        //
        echo "<span>Update successfull</span>";
    }
    public function rssAction ()
    {
        $view = new Zend_View();
        $view->setEscape('htmlentities');
        $playerId = $this->_request->getParam('id', 0);
        $player = new Player();
        $rowset = $player->findUniquePlayer($playerId);
        $urlGen = new SeoUrlGen();
        $playerseoname = $urlGen->getPlayerMasterProfileUrl(
        $rowset->player_nickname, $rowset->player_firstname,
        $rowset->player_lastname, $rowset->player_id, true,
        $rowset->player_common_name);
        $pa = new Activity();
        $playerActivities = $pa->selectActivitiesPerPlayer($playerId, 0);
        $domain = 'http://' . $this->getRequest()->getServer('HTTP_HOST');
        $feedData = array(
        'title' => sprintf("GoalFace.com - %s's Feed",
        $rowset->player_name_short), 'link' => $domain, 'charset' => 'UTF-8',
        'entries' => array());
        // build feed entries based on returned posts
        foreach ($playerActivities as $post) {
            $entry = array(
            'title' => $view->escape($post['activitytype_name']),
            'link' => $domain . $playerseoname,
            'description' => $post['activity_text'],
            'lastUpdate' => strtotime($post['activity_date']));
            $feedData['entries'][] = $entry;
        }
        // create feed based on created data
        $feed = Zend_Feed::importArray($feedData, 'rss');
        // disable auto-rendering since we're outputting an image
        $this->_helper->viewRenderer->setNoRender();
        // output the feed to the browser
        $feed->send();
    }
    public function removeplayergoalshoutAction ()
    {
        $mc = new Comment();
        $session = new Zend_Session_Namespace('userSession');
        //first delete goalshout
        $commentId = $this->_request->getParam('id', 0);
        $playerid = $this->_request->getParam('playerid', 0);
        //find message id in order to find the owner of the message
        $comment = $mc->fetchRow("comment_id = " . $commentId);
        $userWhoDeletesComment = 2; //if 1 = message owner , 2 = profile owner
        if ($session->userId == $comment->friend_id) {
            $userWhoDeletesComment = 1;
        }
        $mc->updateDeleteComment($commentId, $userWhoDeletesComment);
        $this->_redirect("/player/showplayerprofilegoalshouts/id/" . $playerid);
    }
    public function editplayergoalshoutAction ()
    {
        $mc = new Comment();
        //first delete goalshout
        $commentId = $this->_request->getParam('id', 0);
        $playerid = $this->_request->getParam('playerId', 0);
        $dataEditted = $this->_request->getParam('dataEditted', null);
        $mc->updateComment($commentId, $dataEditted);
        $this->_redirect("/player/showplayerprofilegoalshouts/id/" . $playerid);
    }
    public function reportabuseAction ()
    {
        $commentId = $this->_request->getParam('id', 0);
        $playerid = $this->_request->getParam('playerid', 0);
        $dataReport = $this->_request->getParam('dataReport', null);
        $reportType = $this->_request->getParam('reportType', null);
        $to = $this->_request->getParam('reportTo', null);
        $report = new Report();
        $data = array('report_comment_id' => $commentId,
        'report_text' => $dataReport, 'report_type' => $reportType,
        'report_reported_to' => $to,
        'report_comment_type' => Constants::$_REPORT_COMMENT_PLAYER);
        $report->insert($data);
        $this->_redirect("/player/showplayerprofilegoalshouts/id/" . $playerid);
    }
    public function showplayerprofilegoalshoutsAction ()
    {
        $view = Zend_Registry::get('view');
        $uc = new Comment();
        $playerid = (int) $this->_request->getParam('id', 0);
        $playercomments = $uc->findCommentsPerPlayer($playerid);
        $totalPlayerComments = $uc->fetchAll("comment_party_id=" . $playerid);
        $view->totalPlayerComments = count($totalPlayerComments);
        $view->playercomments = $playercomments;
        $view->playerid = $playerid;
        $this->_response->setBody($view->render('goalshoutplayer.php'));
    }
    public function showworldcupplayerbyteamAction ()
    {
        $filter = new Zend_Filter_StripTags();
        $teamid = $this->_request->getParam('id', 0);
        $wcplayer = new WorldCupPlayerStats();
        $results = $wcplayer->getPlayersByTeam(72, $teamid);
        echo '<option value="0" selected>Select Player</option>';
        foreach ($results as $data) {
            echo '<option value=' . trim($data['player_id']) . '>' .
             trim($data['First_Name']) . " " . trim($data['Last_Name']);
        }
    }

}
?>
