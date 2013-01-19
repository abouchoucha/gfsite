<?php
require_once 'util/Template.php';
require_once 'util/Constants.php';
require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';
require_once 'util/config.php';
require_once 'util/functions.php';

class ProfileController extends GoalFaceController {
	
	private $_currentUser;
	private static $logger;
	private static $logger2;
	private static $urlGen = null;
    private static $regionGroupNames = null;
	private $image_folder;
	
	function init() {
		
		self::$logger = Zend_Registry::get("logger");
		self::$logger2 = Zend_Registry::get("logger2");
		Zend_Loader::loadClass ( 'User' );
		Zend_Loader::loadClass ( 'Report' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'Comment' );
		Zend_Loader::loadClass ( 'UserLanguage' );
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'UserLeague' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'UserPlayer' );	
		Zend_Loader::loadClass ( 'Player' );
		Zend_Loader::loadClass ( 'Pagination' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'BlogPost' );
		Zend_Loader::loadClass ( 'Message' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'ActivityType' );
		Zend_Loader::loadClass ( 'Activity' );
		Zend_Loader::loadClass ( 'PageType' );

		
		self::$urlGen = new SeoUrlGen ();
        
		parent::init ();
		$this->image_folder =  $this->view->root_crop ."/photos";
        self::$regionGroupNames = array ("europe" => array ("european", "Europe", "European Leagues & Tournaments" ), "asia" => array ("asian", "Asia and Pacific Islands", "Asian Leagues & Tournaments" ), "africa" => array ("african", "Africa", "African Leagues & Tournaments" ), "americas" => array ("americas", "Americas", "American Leagues & Tournaments" ), "international" => array ("international", "FIFA/International", "International Leagues & Tournaments" ) );
		$this->updateLastActivityUserLoggedIn();
		$this->breadcrumbs->addStep ( 'Fan Profiles', self::$urlGen->getMainProfilesPage(true));
	}
	
	function indexAction() {
		
		//$this->checkifUserIsRemembered();
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		
		$userName = $this->_request->getUserParam ( 'username' );
		$view = Zend_Registry::get ( 'view' );
		//fetch user friends
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		//Zend_Debug::dump($currentUser);
		$userId = $currentUser->user_id;
		$userFriends = $user->findUserFriends ( $userId, 0, 6 );
		$view->totalUserFriends = $user->countUserFriends ( $userId );
    
    //total Rating per User
		$avgrating = 0;
		if ($currentUser->total_votes > 0) {
			$avgrating = round($currentUser->total_value / $currentUser->total_votes , 1 );
		}else {
			$avgrating = "0.0";
		}
		  
		$country = new Country ( );
		$countryLive = $country->fetchRow ( 'country_id=' . $currentUser->country_live );
		$countryBirth = $country->fetchRow ( 'country_id=' . $currentUser->country_birth );
		//user teams
		$ut = new UserTeam ( );
		$userTeams = $ut->findUserTeams ( $userId,null );
		//Zend_Debug::dump($userTeams);
		
		//user players
		$up = new UserPlayer ( );
		$userPlayers = $up->findUserPlayers ( $userId );
		//starting 11
		$userPlayersStartingEleven = $up->findUserPlayers ( $userId, 'Y' );
		
		//user comments
		$uc = new Comment ( );
		$comments = $uc->findCommentsByUser ( $userId, 10 );
		//load the last broadcast message
		$lastBroadcastId = $currentUser->last_broadcast;
		if($lastBroadcastId!= null){
			$lastBroadcastComment = $uc->find($lastBroadcastId);
			$view->lastBroadcast = $lastBroadcastComment[0]['comment_data'];
			$view->lastBroadcastTime = $lastBroadcastComment[0]['comment_date'];
		}
		
		
		
		//Add user_comment_type here when profile supposed to be 1
		$totalGoalShoutsPerUser = $uc->findCommentsByUser ( $userId, null );
		//$totalGoalShoutsPerUser = $uc->fetchAll ( "comment_party_id=" . $userId );
		//Zend_Debug::dump ( $totalGoalShoutsPerUser );
		$view->totalGoalShouts = count ( $totalGoalShoutsPerUser );
		
		//userblogs
		//$blogPost = new BlogPost ( );
		//$postPerUser = $blogPost->findBlogPostPerBlogOrUser ( null, $userId );
		//$view->totalPost = count ( $postPerUser );
		//$view->postPerUser = $postPerUser;
		
		$mess = new Message ( );
		$cantReq = $mess->doCountRequestMessagesbyUser ( $userId );
		$reqs = $cantReq [0] ["total"];
		
		
		
		//UserLanguage
		$userLanguage = new UserLanguage ( );
		$spokenLanguages = $userLanguage->findLanguagesSpokenPerUser ( $userId );
		
		$view->spokenLanguages = $spokenLanguages;
		
		//is UserOnline
		
		$rowOnline = $user->isUserOnline ( $currentUser->email );
		
		$isOnline = "false";
		if ($rowOnline != null) {
			$isOnline = "true";
		}
		//Zend_Debug::dump($isOnline);
		$view->isOnline = $isOnline ;
		
		
		//$view->comt = $comt;
		$view->comments = $comments;
		$view->UserFriendProfiles = $userFriends;
		$view->rating = $avgrating;
		$view->totalVotes = $currentUser->total_votes;
		//echo $avgrating;
		$view->cantReq = $reqs;
		
		$view->userTeams = $userTeams;
		$view->userPlayers = $userPlayers;
		$view->userPlayersStartingEleven = $userPlayersStartingEleven;
		
		$title = new PageTitleGen ( );
		$view->title = $title->getPageTitle ( $currentUser, 'userprofile' );
		$view->currentUser = $currentUser;
		$view->countryLive = $countryLive->country_name;
		$view->countryFrom = $countryBirth!= null ? $countryBirth->country_name : '';
		
		$view->profileMenuSelected = 'profile';
		$view->firsttimeviewprofile = 'false';
		if($session->firsttimeviewprofile != null){
			$view->firsttimeviewprofile = $session->firsttimeviewprofile;
			$session->firsttimeviewprofile = null;
		}
		//verify if the user logged in seeing his(her) own profile
		self::setCommonProfileDataIntoSession($userName , $session , $currentUser , $view);
		
		
		//User Feed - Activity
		$activity = new Activity ( );
		/*$activitiesPerUser = $activity->findActivitiesPerUser ( $userId );
		$view->activitiesPerUser = $activitiesPerUser;
		$view->totalFriendActivities = count ( $activitiesPerUser );
		*/
		if ($session->isMyProfile == 'y') {
			$activitiesPerUser = $activity->findActivitiesPerUser ( $userId , 0 ,15 , 'n');
			
		} else  {
			$activitiesPerUser = $activity->findMyActivities ( $userId , 0 , 15 , 'n');
		}
		$view->totalFriendActivities = count ( $activitiesPerUser );
        $this->breadcrumbs->addStep ( $currentUser->screen_name );
        $this->view->breadcrumbs = $this->breadcrumbs;
        $view->actionTemplate = 'viewmyprofile.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	
	function setCommonProfileDataIntoSession($userName , $session ,$currentUser , Zend_View $view){
		$userFriend = new UserFriend();
		$user = new User();
		
		if($userName == null){
			$userName = $session->screenName;
		}
		if($currentUser == null){
			$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		}
		
		if (trim ( strtolower($userName) ) == strtolower ($session->screenName)) {
			//my profile
			//$view->actionTemplate = 'viewmyprofile.php' ;
			$view->isMyProfile = "y";
			$view->isLastNameVisible = "true";
			$view->isCityLiveVisible = "true";
			$view->isCityBirthVisible = "true";
			//Current Profile Settinh
			$view->privateFirstName = $currentUser->firstname_priv;
			$view->privateLastName = $currentUser->lastname_priv;
			$view->privateCountryLives = $currentUser->country_live_priv;
			$view->privateCountryFrom = $currentUser->country_birth_priv;
			$arrayPrivate = array('Private','Friends Only','Public');
			$view->privateText = $arrayPrivate;
			
		} else {
			//his profile
			//$view->actionTemplate = 'viewprofile.php' ;
			$view->isMyProfile = "n";
			//check if it is your friend or not
			$isMyFriend = "false";
			if ($session->email != null) {
				$userFriend = new UserFriend ( );
				$row = $userFriend->findUserFriend ( $session->userId, $currentUser->user_id );
				//Zend_Debug::dump ( sizeof($row) );
				if (sizeof ( $row ) == '1') {
					$isMyFriend = "true";
				}
			}
			$view->isMyFriend = $isMyFriend;
			$view->isLastNameVisible = self::isAttributeVisible ( $isMyFriend, $currentUser->lastname_priv );
			$view->isCityLiveVisible = self::isAttributeVisible ( $isMyFriend, $currentUser->city_live_priv );
			$view->isCityBirthVisible = self::isAttributeVisible ( $isMyFriend, $currentUser->city_birth_priv );
			
			//add view page counter
			$data = array ('views' => $currentUser->views + 1 );
			$user->updateUser( $currentUser->email, $data );
		}
		//set is My profile in user session
		$session->isMyProfile = $view->isMyProfile;
		$session->isMyFriend = $view->isMyFriend;
		$session->rating = $view->rating;
		$session->totalVotes = $view->totalVotes;
		$session->currentUser = $currentUser;
		self::$logger->info("Is My Profile: " . $session->isMyProfile);
		self::$logger->info("Screen Name: " . $session->screenName);
		self::$logger->info("Email: " . $session->email);
		
	}
	
	function isAttributeVisible($isMyFriend, $attribute) {
		//Private 0
		//Only my friends 1
		//Goalface users 2
		$isVisible = false;
		if ($isMyFriend == 'true') {
			if ($attribute == '1') {
				$isVisible = 'true';
			} else if ($attribute == '2') {
				$isVisible = 'true';
			} else if ($attribute == '0') {
				$isVisible = 'false';
			}
		} else {
			if ($attribute == '1') {
				$isVisible = 'false';
			} else if ($attribute == '2') {
				$isVisible = 'true';
			} else if ($attribute == '0') {
				$isVisible = 'false';
			}
		}
		return $isVisible;
	
	}
	
	public function showprofilesrandomAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "Random Profiles";
		$user = new User ( );
		
		$teamId = $this->_request->getParam ( 'teamId', '' );
		$playerId = $this->_request->getParam ( 'playerId', '' );
		$competitionId = $this->_request->getParam ( 'competitionId', '' );
		$fansOf = null;
		$cache = $this->getCache();
		
		$userprofiles = null;
		if ($teamId != '') {
			$teamId2 = $this->_request->getParam('teamId2' ,'');
			if($teamId2 != ''){
				$teamId = $teamId . ',' . $teamId2;
				$fansOf = 'match';
			}else {
				$fansOf = 'team';
			}
			$userprofiles = $user->findUserProfilesByTeam ( $teamId , 6 );
			
		} else if ($playerId != null) {
			$userprofiles = $user->findUserProfilesByPlayer ( $playerId, 6 );
			$fansOf = 'player';
		}else if($competitionId != null){ 
			$userprofiles = $user->findUserProfilesByTournament ( $competitionId, 6 );
			$fansOf = 'competition';
		} else {
			if (!$userprofiles = $cache->load('userProfilesRandom')) {
				$userprofiles = $user->findUserProfilesRandom ();
				$cache->save($userprofiles,'userProfilesRandom');
		    	//Zend_Debug::dump("Using Non cached data");
			} else {
				//Zend_Debug::dump("Using cached data");
				
			}
		}
		$view->fansOf = $fansOf;
		$view->randomprofiles = $userprofiles;
		$view->totalrandomprofiles = count($userprofiles);
		$this->_response->setBody ( $view->render ( 'viewprofilesrandom.php' ) );
	
	}
	
	public function showallprofilesAction() {
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		
		$view = Zend_Registry::get ( 'view' );
		$typeOfSearch = $this->_request->getParam ( 'search', 'all#all' );
		$arrayExploded = explode ( '#', $typeOfSearch );
		$view->typeOfSearch = $arrayExploded [0];
		$view->idToSelect = $arrayExploded [1];
		$user = new User();
		$searchTerms = $user->findUserProfilesRandom(3);
		$this->view->searchTerms = $searchTerms;
		 
        $this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'featuredprofiles.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	function showfeaturedprofilesAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$view->title = $title->getPageTitle ( '', PageType::$_PROFILES_MAIN_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_PROFILES_MAIN_PAGE );
		$view->description = $description->getMetaDescription ( '', PageType::$_PROFILES_MAIN_PAGE );
		
		$typeOfSearch = $this->_request->getParam ( 'search', '' );
		
		$user = new User ( ); // fetch all records from the table user
		

		$rowUsers = 0;
		
		if ($typeOfSearch == 'online') {
			$rowUsers = $user->findUsersOnline (null ,$session->userId);
		} elseif ($typeOfSearch == 'popular') {
			$rowUsers = $user->findMostPopularUsers (null ,$session->userId);
		} elseif ($typeOfSearch == 'active') {
			$rowUsers = $user->findMostActiveUsers ( null , $session->userId);
		} elseif ($typeOfSearch == 'likeme') {
			$rowUsers = $user->findUsersLikeMe ( null, $session->userId );
		} else {
			$rowUsers = $user->findUserProfiles ( null, $typeOfSearch ,$session->userId);
		}
		
		
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($rowUsers);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
		
		$view->typeOfSearch = $typeOfSearch;
		$this->_response->setBody ( $view->render ( 'featuredprofilesresult.php' ) );
	
	}
	
	public function showprofiletipAction() {
		$urlGen = new SeoUrlGen ( );
		$userName = $this->_request->getUserParam ( 'username' );
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		//Zend_Debug::dump($currentUser);
		$userId = $currentUser->user_id;
		
		$country = new Country ( );
		$countryLive = $country->fetchRow ( 'country_id=' . $currentUser->country_live );
		
		$url = $urlGen->getUserProfilePage ( $currentUser->screen_name, True );
		$userTeam = new UserTeam ( );
		$randomClubTeam = $userTeam->findUserTeamsRandom ( $userId, 'club' );
		//Zend_Debug::dump($randomClubTeam);
		$randomNationalTeam = $userTeam->findUserTeamsRandom ( $userId, 'national' );
		//Find Random First Club Team and National Team
		

		echo "<strong>Joined :</strong>" . date ( 'M Y', strtotime ( $currentUser->registration_date ) ) . "<br/>";
		echo $countryLive->country_name . "<br/>";
		echo $currentUser->city_live . "<br/><br/>";
		echo "<strong>Favorite Teams:</strong><br/>";
		echo "Club Team:</strong><br/>	";
		echo $randomClubTeam ['team_name'] . "<br/><br/>";
		echo "National Team:</strong><br/>";
		echo $randomNationalTeam ['team_name'] . "<br/><br/>";
		echo "<a href= '" . $url . "'> View Profile&raquo; </a>";
	}
	
	public function showallmyfriendsAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		//$view->screenName = $session->screenName;
		//Zend_Debug::dump ( $session->screenName );
		$view = Zend_Registry::get ( 'view' );
		$view->profileMenuSelected = 'friends';
		$this->breadcrumbs->addStep ( $session->screenName ,self::$urlGen->getUserProfilePage($session->screenName , true) );
       	$this->breadcrumbs->addStep ( 'Friends' );
       	$this->view->breadcrumbs = $this->breadcrumbs;
       	$user = new User();
		$searchTerms = $user->findUserProfilesRandom(3);
		$this->view->searchTerms = $searchTerms;
		
		self::setCommonProfileDataIntoSession(null , $session , null , $view);
		
		$view->actionTemplate = 'viewallfriends.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showallhisfriendsAction() {
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userId = $this->_request->getParam ( 'id', 0 );
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'user_id=' . $userId );
		$view->screenName = $currentUser->screen_name;
		$view->currentUser = $currentUser; 
		$session->currentUser = $currentUser;
		$view->userId = $userId;
		$searchTerms = $user->findUserProfilesRandom(3);
		$this->view->searchTerms = $searchTerms;
		$view->profileMenuSelected = 'friends';
		
		$view->actionTemplate = 'viewallfriends.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function showallfriendsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$user = new User ( );
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userId = $this->_request->getParam ( 'id', $session->userId );
		
		$currentUser = $user->fetchRow ( 'user_id=' . $userId );
		//Zend_Debug::dump($currentUser);
		
		$typeOfSearch = $this->_request->getParam ( 'search', '' );
		
		if ($typeOfSearch == 'online') {
			$rowUsers = $user->findUsersFriendsOnline ( $userId, null );
		}elseif ($typeOfSearch == 'popular') {
			$rowUsers = $user->findUserFriendsMostPopular ( $userId );
		}elseif ($typeOfSearch == 'active') {
			$rowUsers = $user->findUserFriendsMostActive ( $userId );
		}elseif($typeOfSearch == 'infriendfeed'){
			$rowUsers = $user->findUserFriendsInFriendFeed ( $userId );
		}elseif($typeOfSearch == 'all' or $typeOfSearch == 'recently'){
			$rowUsers = $user->findUserFriends ( $userId, null, null, $typeOfSearch );
		}else{
			$rowUsers = $user->findUserFriendsSearchText ( $userId,null,$typeOfSearch );
		}
		
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($rowUsers);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
		
		$view->currentUser = $currentUser;
		$session->currentUser = $currentUser;
		$view->userName = $currentUser->screen_name;
		$view->typeOfSearch = $typeOfSearch;
		
		$this->_response->setBody ( $view->render ( 'viewallfriendsresult.php' ) );
	
	}
	
	public function showalluseractivityAction () {
	$view = Zend_Registry::get ( 'view' );
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		
		// getting user information
		$user = new User ( );
		$userName = $this->_request->getUserParam ( 'username' , $session->screenName );
		
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		
		$userId =  $currentUser->user_id;

        //User Feed - Activity
		$activity = new Activity ( );
		$activitiesPerUser = null;
		$type = $this->_request->getParam ( 'type', 1 );
		$activityid = $this->_request->getParam ( 'activityid', 0 );
		$tabSelected = null;
		if ($type == "0") {
			$activitiesPerUser = $activity->findActivitiesPerUser ( $userId ,$activityid,10, 'n');
			$tabSelected = 'mfactivity';
		} else if ($type == "1") {
			$activitiesPerUser = $activity->findMyActivities ( $userId ,$activityid ,10,'n');
			$tabSelected = 'myactivity';
		}
		$view->activitiesPerUser = $activitiesPerUser;
		$view->totalFriendActivities = count ( $activitiesPerUser );
		$view->activityid = $activityid;
		$view->currentusername = $userName;
		$view->currentUser = $currentUser;
        //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        
        $paginator = Zend_Paginator::factory($activitiesPerUser);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;
       	$this->breadcrumbs->addStep ( $userName ,self::$urlGen->getUserProfilePage($userName , true) );
       	$this->breadcrumbs->addStep ( 'Activity' );
       	$this->view->breadcrumbs = $this->breadcrumbs;
        $view->profileMenuSelected = 'activity';
        $view->tabSelected = $tabSelected;
        $view->actionTemplate = 'viewalluseractivity.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }
    
	public function rssAction () {
        $view = Zend_Registry::get ( 'view' );
		
		// getting user information
		$user = new User ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userName = $this->_request->getUserParam ( 'username' , $session->screenName );
		
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		$userId =  $currentUser->user_id;

        //User Feed - Activity
		$activity = new Activity ( );
		$activitiesPerUser = $activity->findMyActivities ( $userId );
		
        $domain = 'http://' . $this->getRequest()->getServer('HTTP_HOST');
			
			$feedData = array(
                'title'   => sprintf("GoalFace.com - %s's Feed", $userName),
                'link'    => $domain ,
                'charset' => 'UTF-8',
                'entries' => array()
            );

            // build feed entries based on returned posts
            
            foreach ($activitiesPerUser as $post) {

                $entry = array(
                    'title'       => $view->escape($post['activitytype_name'] ),
                    'link'        => 'no se q pondre aca',
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

    public function showallgoalshoutsAction() {
		
    	//$this->checkifUserIsRemembered();
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
    	
		$view = Zend_Registry::get ( 'view' );
		
		// getting user information
		$user = new User ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userName = $this->_request->getUserParam ( 'username' , $session->screenName );
		
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		$userId =  $currentUser->user_id;
		
		// getting request variables
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		//getting comments
		$comment = new Comment ( );
		//$comments = $comment->findCommentsSendedToUser ( $userId );
		$comments = $comment->findCommentsByUser($userId);
		$paginator = Zend_Paginator::factory ( $comments );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
		
		
		/*
		$view->jQuery ()->addJavascript ( "
		function reply(){
			jQuery(\"input[id^='ReplyBtn']\").click(function(){
			jQuery('#modal').dialog('option', 'buttons', { 'Post' : function(){ 
				var commentSupId = jQuery('#PostCommentSup').val();
				var postComment = jQuery('#PostComment').val();
				ajaxPostMessage(5,commentSupId,postComment,'Your reply has been posted');
				},
				'Cancel': function() { jQuery(this).dialog('close'); } });
			jQuery('#modal').dialog('option', 'height', 200);
			jQuery('#modal').dialog('option', 'title', 'Post Reply');
			jQuery('#modal').load('" . Zend_Registry::get ( "contextPath" ) . "/profile/showmodal/option/1/value/'+this.id.replace(/ReplyBtn_/,'')).dialog('open');
		})
		}
		function editReply(){
			jQuery(\"input[id^='EditBtn']\").click(function(){
			jQuery('#modal').dialog('option', 'buttons', { 'Post' : function(){ 
				var commentSupId = jQuery('#PostCommentSup').val();
				var postComment = jQuery('#PostComment').val();
				ajaxPostMessage(6,commentSupId,postComment,'Your reply has been posted');
				},
				'Cancel': function() { jQuery(this).dialog('close'); } });
			jQuery('#modal').dialog('option', 'height', 200);
			jQuery('#modal').dialog('option', 'title', 'Post Reply');
			jQuery('#modal').load('" . Zend_Registry::get ( "contextPath" ) . "/profile/showmodal/option/1/value/'+this.id.replace(/EditBtn_/,'')).dialog('open');
		})
		}
		function removeElements(){
				jQuery('#removeButtonId').click(function(){
				var checkedElements = jQuery(\"input[name='chkbox']:checked\").length;
				var selectedValues = '';
				var cont = 0;
				jQuery(\"input[name='chkbox']:checked\").each(function(){
					if (cont == 0){
						selectedValues = selectedValues + this.value;
					}else{
						selectedValues = selectedValues + ',' + this.value;
					}
					cont++;
				});
				if (checkedElements > 0){
					jQuery('#modal').dialog('option', 'buttons', { 'Yes' : function(){ deleteAction(7,selectedValues,'Comments has been deleted') } ,'Cancel': function() { jQuery(this).dialog('close'); } });
					jQuery('#modal').html('Do you want delete all Goal Shouts selected').dialog('open');
				}else{
					jQuery('#modal').dialog('option', 'buttons', { 'Accept': function() { jQuery(this).dialog('close'); } });
					jQuery('#modal').html('You have not selected any Goal Shout to delete').dialog('open');
				}
			});
		}
		function ajaxReload(resultMessage){
			jQuery('#modal').dialog('close');
			jQuery('#SecondColumnProfile').load('" . Zend_Registry::get ( "contextPath" ) . "/profile/showallgoalshouts #FriendsWrapper',function(){
				jQuery('#resultMessages').html(resultMessage).show('blind');
				jQuery('select').change(function(){
					selectOnChange(this);
				})
				jQuery('input#checkall').click( function() {	
					var checked = this.checked;
					jQuery('input:checkbox').each( function(){
						this.checked = checked;
					});
				});
				reply();
				removeElements();
			});
		}
		function selectOnChange(select){
			var widget = select;
			var selectedIndex = select.selectedIndex;
			var question = '';
			var action = '';
			switch (selectedIndex){
				case 0: selectedIndex = 0; break;
				case 1: question = 'Do you want to Allow All Goal Shouts from this user?'; break;
				case 2: question = 'Do you want to Allow this Goal Shout?'; break ;
				case 3: question = 'Do you want to block All Goal Shouts from this user?'; break;
				case 4: question = 'Do you want to block this Goal Shout?, you can\'t undo this action'; break ;
			}
			switch (selectedIndex){
				case 0: selectedIndex = 0; break;
				case 1: jQuery('#modal').dialog('option', 'buttons', { 'Yes' : function(){ ajaxLoad(widget,'All Goal shouts has been approved') } ,'Cancel': function() { jQuery(this).dialog('close'); } });
						jQuery('#modal').html(question).dialog('open');
						break;
				case 2: jQuery('#modal').dialog('option', 'buttons', { 'Yes' : function(){ ajaxLoad(widget,'Goal shout has been approved') } ,'Cancel': function() { jQuery(this).dialog('close'); } });
						jQuery('#modal').html(question).dialog('open');
						break;
				case 3: jQuery('#modal').dialog('option', 'buttons', { 'Yes' : function(){ ajaxLoad(widget,'Goal shouts has been blocked') } ,'Cancel': function() { jQuery(this).dialog('close'); } });
						jQuery('#modal').html(question).dialog('open');
						break;
				case 4: jQuery('#modal').dialog('option', 'buttons', { 'Yes' : function(){ ajaxLoad(widget,'Goal shout has been blocked') } ,'Cancel': function() { jQuery(this).dialog('close'); } });
						jQuery('#modal').html(question).dialog('open');
						break;
			}
		}
		function ajaxLoad(select,resultMessage){
			jQuery.ajax({
				type: 'GET',
				url: '" . Zend_Registry::get ( "contextPath" ) . "/profile/goalshoutsactions/option/' + select.selectedIndex + '/value/' + select.name.replace(/AllowShout_/,''),
				success: function(data,text) {
					ajaxReload(resultMessage);
				}	
			})
		};
		
		function deleteAction(option,value,resultMessage){
			jQuery.ajax({
				type: 'GET',
				url: '" . Zend_Registry::get ( "contextPath" ) . "/profile/goalshoutsactions/option/' + option + '/value/' + value,
				success: function(data,text) {
					ajaxReload(resultMessage);
				}	
			})
		};
		
		function ajaxPostMessage(actionIndex,id,message,resultMessage){
			jQuery.ajax({
				type: 'GET',
				url: '" . Zend_Registry::get ( "contextPath" ) . "/profile/goalshoutsactions/option/' + actionIndex + '/value/' + id + '/message/' + message,
				success: function(data,text) {
					ajaxReload(resultMessage);
				}
			});
		}
		" );
		$view->jQuery ()->addOnLoad ( "
			jQuery('input#checkall').click( function() {	
				var checked = this.checked;
				jQuery('input:checkbox').each( function(){
					this.checked = checked;
				});
			});
			jQuery('select').change(function(){
				selectOnChange(this);
			})
			reply();
			removeElements();
		" );
		
		$view->modal1 = $view->dialogContainer ( "modal", '', array ('modal' => true, 'title' => 'Confirmation', 'show' => 'explode', 'hide' => 'explode', 'buttons' => new Zend_Json_Expr ( '{"Cancel": function() { $(this).dialog("close"); },' . '"Yes": function(){ } }' ), 'draggable' => false, 'autoOpen' => false, 'resizable' => false, 'overlay' => new Zend_Json_Expr ( '{"backgroundColor": "#000","opacity": 0.5}' ) ) );
		*/
		$view->currentUser = $currentUser;
		$view->userName = $currentUser->screen_name;
		$view->comments = $comments;
		$view->paginator = $paginator;
		
		$this->breadcrumbs->addStep ( $userName ,self::$urlGen->getUserProfilePage($userName , true) );
       	$this->breadcrumbs->addStep ( 'GooolShouts' );
       	$this->view->breadcrumbs = $this->breadcrumbs;
		$view->profileMenuSelected = 'shouts';
		
		$view->actionTemplate = 'viewallgoalshouts.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	function showmodalAction() {
		$view = Zend_Registry::get ( 'view' );
		$filter = new Zend_Filter_StripTags ( );
		$option = $this->_request->getParam ( 'option' );
		$value = $this->_request->getParam ( 'value' );
		$comment = new Comment ( );
		if (empty ( $option ) or empty ( $value )) {
			$this->_response->setBody ( $view->render ( 'mod_borrame' ) );
		}
		
		$redirectPage = 'mod_error.php';
		
		switch ($option) {
			case 1 :
				$redirectPage = 'mod_reply';
				break;
		}
		
		$view->commentId;
		$view->commentSup = $value;
		
		/*
		Zend_Debug::dump($action);
		Zend_Debug::dump($value);
		Zend_Debug::dump($view->commentId);
		*/
		//$modal = trim ( $filter->filter ( $this->_request->getParam ( 'modal' ) ) );
		//$view->value = $value;
		$this->_response->setBody ( $view->render ( '/scripts/_partial/' . $redirectPage . '.php' ) );
	}
	
	function goalshoutsactionsAction() {
		$view = Zend_Registry::get ( 'view' );
		$filter = new Zend_Filter_StripTags ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userId = $session->userId;
		$option = $this->_request->getPost ( 'option' );
		$message = $this->_request->getPost ( 'message' );
		$value = $this->_request->getPost ( 'value' );
		
		Zend_Debug::dump($option);
		
		if (empty ( $action ) or empty ( $value )) {
			$this->_response->setBody ( $view->render ( 'mod_borrame.php' ) );
		}
		
		$redirectPage = 'mod_error.php';
		//Zend_debug::dump($option);
		switch ($option) {
			//allow all
			case 1 :
				$userFriendModel = new UserFriend ( );
				$commentModel = new Comment ( );
				$currentComment = $commentModel->fetchRow ( 'comment_id=' . $value );
				$userFriendModel->updateStateOfBlockedComment ( $userId, $currentComment->friend_id, '1' );
				break;
			//allow once
			case 2 :
				$comment = new Comment ( );
				$comment->updateStateOfComment ( $value, 1 );
				break;
			//block all
			case 3 :
				$userFriendModel = new UserFriend ( );
				$commentModel = new Comment ( );
				$currentComment = $commentModel->fetchRow ( 'comment_id=' . $value );
				$userFriendModel->updateStateOfBlockedComment ( $userId, $currentComment->friend_id, '2' );
				break;
			//block once
			case 4 :
				$comment = new Comment ( );
				$comment->updateStateOfComment ( $value, 2 );
				break;
			//reply
			case 5 :
				$comment = new Comment ( );
				$currentComment = $comment->fetchRow ( 'comment_id=' . $value );
				//Zend_Debug::dump($currentComment);
				$arrayReply = array ('comment_party_id' => $currentComment->friend_id, 'friend_id' => $currentComment->comment_party_id, 'comment_data' => $message, 'comment_date' => trim ( date ( "Y-m-d H:i:s" ) ), 'comment_type' => 1, 'comment_super_id' => $currentComment->comment_id );
				$comment->insert ( $arrayReply );
				break;
			//edit reply
			case 6 :
				$comment = new Comment ( );
				//$currentComment = $comment->fetchRow ( 'comment_id=' . $value );
				//Zend_Debug::dump($currentComment);
				$arrayReply = array ('comment_data' => $message, 'comment_date' => trim ( date ( "Y-m-d H:i:s" ) ), 'comment_id' => $value );
				$comment->update ( $arrayReply );
				break;
			//delete selected
			case 7 :
				//$values = explode ( ",", $value );
				$comment = new Comment ( );
				foreach ( $value as $item ) {
					$comment->updateStateOfComment ( $item, 2 );
				}
				break;
		}
		
		//$this->_response->setBody ( $view->render ( 'mod_borrame.php' ) );
	}
	
	
	function rateprofileAction() {
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		$value = $this->_request->getParam ( 'rating', 0 );
		$friend = $this->_request->getParam ( 'friendId', 0 );
		$_currentUser = new User ( );
		$ratedProfile = $_currentUser->find ( $friend );
		$row = $ratedProfile->current ();
		
		$temp = $row->total_votes + 1;
		$temp2 = $row->total_value + $value;
		
		$data = array ('total_votes' => $temp, 'total_value' => $temp2 );
		$_currentUser->update ( $data, 'user_id=' . $friend );
		$variablesToReplace = array ('user_name1' => $session->screenName, 'user_name2' => $row->screen_name );
		
		$activityUser = new Activity ( );
		$activityUser->insertUserActivityByActivityType ( Constants::$_RATE_PROFILE_ACTIVITY, $variablesToReplace, $session->userId );
		
		echo $temp2 / $temp;
	
	}
	
	function showpopupteampageAction() {
		$view = Zend_Registry::get ( 'view' );
		$view->title = "searchTeam";
		$this->_response->setBody ( $view->render ( 'searchTeam.php' ) );
	}
	
	public function showshoutoutAction() {
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userId = $this->_request->getParam ( 'id', $session->userId );
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'user_id=' . $userId );
		$uc = new Comment ( );
		$comments = $uc->findCommentsByUser ( $userId );
		$view->comments = $comments;
		$view->currentUser = $currentUser;
		//Zend_Debug::dump($comments);
		//$view->title = "Goal Shout Outs for " . $currentUser->screen_name ;
		$title = new PageTitleGen ( );
		$view->title = $title->getPageTitle ( $currentUser, 'showshoutout' );
		
		$view->currentUser = $currentUser;
		$view->actionTemplate = 'shoutout.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function addgoalshoutAction() {
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		
		$commentText = $this->_request->getPost ( "comment" );
		$userTocomment = $this->_request->getPost ( "idtocomment" ,null );
		$commentType = $this->_request->getPost ( "commentType" );
		$userNameTocomment = $this->_request->getPost ( "screennametocomment" );
		$countryid = $this->_request->getParam ( "countryid" , null);
		$leagueid = $this->_request->getParam ( "leagueid" , null);
		$photoid =  $this->_request->getParam ( "photoid" , null);
		$country = new Country();
		$countryRegionRow = null;
		$regiongroupid = null;
		if($countryid != null){
			$countryRegionRow = $country->findRegionIdByCountry($countryid);
			$regiongroupid = $countryRegionRow[0]['region_group_id'];
		}else {
			$regiongroupid = $this->_request->getParam ( "regiongroup" , null);;
		}	
		
		
		$comment_create = trim ( date ( "Y-m-d H:i:s" ) );
		$data = array ('comment_party_id' => $userTocomment, 'friend_id' => $session->userId, 'comment_data' => $commentText, 
						'comment_date' => $comment_create, 
						'comment_type' => $commentType, 
						'region_group_id' => $regiongroupid,
						'league_id' => $leagueid, 
						'photo_id' => $photoid, 
						);
		
		$comment = new Comment ( );
		$comment->insert ( $data );
		//add activity event
		$variablesToReplace = array ('user_name1' => $session->screenName, 'user_name2' => $userNameTocomment );
		$urlGen = new SeoUrlGen ( );
		$activityType = null;
		$article = null;
		$playerId = null;
		$match_id = null;
		$teamId = null;
		$objectIdCommented = $session->userId;
		if ($commentType == Constants::$_COMMENT_GOALSHOUT) {
			$variablesToReplace = array ('user_name1' => $session->screenName, 'user_name2' => $userNameTocomment );
			$activityType = Constants::$_ADD_GOALSHOUT_ACTIVITY;
		} else if ($commentType == Constants::$_COMMENT_MATCH) {
			$match_name = $this->_request->getPost ( "matchname" );
			$match_id = $this->_request->getPost ( "matchid" );
			$match = new Matchh ( );
			$matchRow = $match->findMatchById ( $match_id );
			//$matchUrl = Zend_Registry::get ( "contextPath" ) . "/scoreBoard/showMatchDetail/matchid/" . $match_id;
			$matchUrl = $urlGen->getMatchPageUrl ( $matchRow [0] ["competition_name"], $matchRow [0] ["t1"], $matchRow [0] ["t2"], $matchRow [0] ["match_id"], true );
			$variablesToReplace = array ('user_name1' => $session->screenName, 'user_name2' => $userNameTocomment, 'match_name' => $match_name, 'match_url' => $matchUrl );
			$activityType = Constants::$_ADD_GOALSHOUT_MATCH_ACTIVITY;
		} else if ($commentType == Constants::$_COMMENT_PLAYER) {
			$playerId = $this->_request->getPost ( "playerId" );
			$player = new Player ( );
			$existplayer = $player->fetchRow ( 'player_id = ' . $playerId );
			$player_name_seo = $urlGen->getPlayerMasterProfileUrl2 ( $existplayer->player_nickname, $existplayer->player_firstname, $existplayer->player_lastname, $existplayer->player_id, true );
			$variablesToReplace = array ('user_name1' => $session->screenName, 'user_name2' => $userNameTocomment, 'player_name_seo' => $player_name_seo, 'player_name' => $existplayer->player_name_short, 'player_id' => $playerId );
			$activityType = Constants::$_ADD_GOALSHOUT_PLAYER_ACTIVITY;
			
		} else if ($commentType == Constants::$_COMMENT_NEWS) {
			$newsFeed = new NewsFeed ( );
			$article = $newsFeed->selectMaxNewsFeedRevisionByNewsAfpId ( $userTocomment );
			//update newsfeed table with one more comment
			$news = new NewsFeed ( );
			$data = array ('news_num_comments' => $article [0] ['news_num_comments'] + 1 );
			//Zend_Debug::dump($article);
			$news->updateNewsFeed ( $data, $article [0] ['news_id'] );
			$articleSeo = $urlGen->getNewsArticlePageUrl ( $article [0] ['news_headline'], $article [0] ['news_id'], true );
			$variablesToReplace = array ('username' => $session->screenName, 'article_seo' => $articleSeo, 'article_headline' => $article [0] ['news_headline'] );
			$activityType = Constants::$_NEW_NEWS_COMMENT_ACTIVITY;
		} else if ($commentType == Constants::$_COMMENT_TEAM) {
			$teamId = $this->_request->getPost ( "teamId" );
			$team = new Team ( );
			$existteam = $team->fetchRow ( 'team_id = ' . $teamId );
			$team_name_seo = $urlGen->getClubMasterProfileUrl ( $teamId, $existteam->team_seoname, true );
			$variablesToReplace = array ('user_name1' => $session->screenName, 'team_name_seo' => $team_name_seo, 'team_name' => $existteam->team_name );
			$activityType = Constants::$_ADD_GOALSHOUT_TEAM_ACTIVITY;
		} else if ($commentType == Constants::$_COMMENT_PHOTO) {
			$photoId = $photoid;
			$urlPhoto = "/photo/showphotogalleryitem/itemid/" . $photoId;
			$variablesToReplace = array ('user_name1' => $session->screenName, 'url_photo' => $urlPhoto );
			$activityType = Constants::$_ADD_GOALSHOUT_PHOTO_ACTIVITY;
			//$objectIdCommented = $photoId; 
		}
		$config = Zend_Registry::get ( 'config' );
		$imageName  = $config->path->images->fanphotos . $session->mainPhoto;
		
		if($activityType != null){
			$activityUser = new Activity ( );
			$activityUser->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $objectIdCommented ,1 , 0 , null , null , $imageName);
			//insert a new activity when commenting on a team /player
			if($commentType == Constants::$_COMMENT_PLAYER or $commentType == Constants::$_COMMENT_TEAM){
				$activityUser->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,0 ,$playerId ,$teamId ,null ,$imageName );
			}
		}
		$view = Zend_Registry::get ( 'view' );
		$uc = new Comment ( );
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userNameTocomment . '"' );
		$view->currentUser = $currentUser;
		if ($commentType == Constants::$_COMMENT_GOALSHOUT) {
			
			$this->_redirect("/profile/showprofilegoalshouts/id/" .$userTocomment);
			
		} else if ($commentType == Constants::$_COMMENT_NEWS) {
			$newsSeoUrl = $urlGen->getNewsArticlePageUrl ( $article [0] ["news_headline"], $article [0] ["news_id"], false );
			//$this->_redirect ( $newsSeoUrl );
		
		} else if ($commentType == Constants::$_COMMENT_PLAYER) {
			$playercomments = $uc->findCommentsPerPlayer ( $userTocomment, 5 );
			$totalPlayerComments = $uc->fetchAll ( "comment_party_id=" . $userTocomment );
			$view->totalPlayerComments = count ( $totalPlayerComments );
			$view->playercomments = $playercomments;
			$view->playerid = $playerId;
			$this->_response->setBody ( $view->render ( 'goalshoutplayer.php' ) );
		} else if ($commentType == Constants::$_COMMENT_TEAM) {
			$teamcomments = $uc->findCommentsPerTeam ( $userTocomment, 5 );
			$totalTeamShouts = $uc->fetchAll ( "comment_party_id=" . $userTocomment );
			$view->totalTeamShouts = count ( $totalTeamShouts );
			$view->teamcomments = $teamcomments;
			$view->teamid = $teamId;
			$this->_response->setBody ( $view->render ( 'goalshoutteam.php' ) );
		} else if ($commentType == Constants::$_COMMENT_MATCH) {
			$this->_redirect("/scoreboard/showmatchgoalshouts/matchid/" .$userTocomment);
		} else if ($commentType == Constants::$_COMMENT_PHOTO) {
			
		}  
		else {
			$comment = new Comment();
			$elementType = null;
			if($commentType == Constants::$_COMMENT_REGION){
				$elementType = $regiongroupid;
			}else if($commentType == Constants::$_COMMENT_COMPETITION){
				$elementType = $leagueid;
			}
			
			$comments = $comment->findComments($elementType ,$commentType, 10);
			//$totalComments = $comment->findCommentsPerRegion($regiongroupid);
			$totalComments = $comment->findComments($elementType,$commentType); 
			$view->totalGoalShouts = count($totalComments);
			$view->comments = $comments;
			$view->elementid = $regiongroupid;
			$view->typeofcomment = $commentType;
			$this->_response->setBody ( $view->render ( 'scripts/goalshouttemplate.phtml' ) );
		}
			
	}
	
	public function showprofilegoalshoutsAction(){
		
		$view = Zend_Registry::get ( 'view' );
		$uc = new Comment();
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userTocomment = ( int ) $this->_request->getParam ( 'id', 0 );
		$comments = $uc->findCommentsByUser ( $userTocomment);
		$user = new User();
		$currentUser = $user->fetchRow ( "user_id = " . $userTocomment );
		$view->currentUser = $currentUser;
		//Add user_comment_type here when profile supposed to be 1
		$totalGoalShoutsPerUser = $uc->findCommentsByUser ( $userTocomment, null );
		if($currentUser->user_id == $session->userId){
			$view->isMyProfile = "y";
		}else {
			$view->isMyProfile = "n";
		}	
		$isMyFriend = "false";
		if ($session->email != null) {
			$userFriend = new UserFriend ( );
			$row = $userFriend->findUserFriend ( $session->userId, $currentUser->user_id );
			if (sizeof ( $row ) == '1') {
				$isMyFriend = "true";
			}
		}
		$view->isMyFriend = $isMyFriend;
		$view->totalGoalShouts = count ( $totalGoalShoutsPerUser );
		$view->comments = $comments;
		$view->userId = $userTocomment;
		$this->_response->setBody ( $view->render ( 'goalshoutprofile.php' ) );
			
	} 		
	
	
	public function removegoalshoutAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		$view = Zend_Registry::get ( 'view' );
		$uc = new Comment ( );
		$userId = $session->userId;
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$uc->deleteComment ( $userId, $commentId );
		
		//user comments
		$view->isMyProfile = "y";
		$comments = $uc->findCommentsByUser ( $userId, 5 );
		//Add user_comment_type here when profile supposed to be 1
		$totalGoalShoutsPerUser = $uc->fetchAll ( "user_id=" . $userId );
		
		$view->totalGoalShouts = count ( $totalGoalShoutsPerUser );
		$view->comments = $comments;
		$view->userId = $userId;
		
		$this->_response->setBody ( $view->render ( 'goalshout.php' ) );
	
	}
	
	public function showedittabAction() {
		$view = Zend_Registry::get ( 'view' );
		$param = $this->_request->getParam ( 'tab', 0 );
		//		echo $param ;
		//		echo "</br> " ;
		//		echo "</br> " ;
		$view->tab = $param;
		$this->_response->setBody ( $view->render ( 'editfavtabs.php' ) );
	}
	
	public function forwardtofriendAction() {
		
		$to = $this->_request->getParam ( 'to', null );
		$subject = $this->_request->getParam ( 'subject', null );
		$message = $this->_request->getParam ( 'message', null );
		
		$mail = new SendEmail();
		$config = Zend_Registry::get ( 'config' );
		$mail->set_from($config->email->confirmation->from);
		$mail->set_to($to);
		$mail->set_subject($subject);
		$mail->set_message($message);
		$mail->sendSimpleEMail();
	
	}
	
	public function editAction() {
		
		parent::isUserLoggedIn();
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userName = $this->_request->getUserParam ( 'username' );
		$sent = $this->_request->getUserParam ( 'sent' );
		//fetch user friends
		$user = new User ( );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		
		//get All user leagues.User Players and User Teams to fill out update alerts form 
		$userleague = new UserLeague();
		$userteam = new UserTeam();
		$userplayer = new UserPlayer();
		$user_leagues = $userleague->findAllUserCompetitions($currentUser['user_id']);
		$view->userleague = $user_leagues;
		$user_teams = $userteam->findUserTeams($currentUser['user_id']);
		$view->userteam = $user_teams;
		$user_players = $userplayer->findUserPlayers($currentUser['user_id']);
		$view->userplayer = $user_players;
		//Alerts Module
		$activityF = new ActivityFrecuency();
		$view->frequencyLeagues = $activityF->selectActivityFrecuencyByCategory(4); //leagues
		$view->frequencyTeams = $activityF->selectActivityFrecuencyByCategory(2); //teams
		$view->frequencyPlayers = $activityF->selectActivityFrecuencyByCategory(3); //players
		
		$view->checkEmailPrivateMessagesAlert = $currentUser['private_message_email'];
		$view->checkEmailFriendInvitesAlert = $currentUser['friend_invites_email'];
		$view->checkEmailGoalShoutsAlert = $currentUser['goalshouts_email'];
		$view->checkPostCommentsAlert = $currentUser['commentpost_email'];
		$view->checkEmailFriendActivitiesAlert = $currentUser['friendactivity_email'];
		//UserFrecuencies
		$view->emailPrivateMessagesFrecuency = $currentUser['private_message_frecuency'];
		$view->emailFriendInvitesFrecuency = $currentUser['friend_invites_frecuency'];
		$view->emailGoalShoutsFrecuency = $currentUser['goalshouts_frecuency'];
		$view->emailPostCommentsFrecuency = $currentUser['commenpost_frecuency'];
		$view->emailFriendActivitiesFrecuency = $currentUser['friendactivity_frecuency'];
		//Alerts Module
		
		if($session->isMyProfile == null){
			self::setCommonProfileDataIntoSession($userName , $session , $currentUser , $view);
		}	
		$view->type = $this->_request->getUserParam ( 't' ,'profileinfo'); 
		
		

		if($view->type =='settings'){
			if($session->fbuser==null){
				if($session->passwordVerify == null){
					$view->actionTemplate = 'loginFormVerify.php';
				}else if($session->passwordVerify == "true"){
					$view->actionTemplate = 'accountEditHome.php';
				}
			}else{
				$view->actionTemplate = 'accountEditHome.php';
			}
		}else {
			$view->actionTemplate = 'editProfile.php';	
		}	
		$view->sentok = $sent;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	
	}
	
	public function editfavoritiesAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->email == null) {
			$this->_redirect ( "/login" );
		}
		$view = Zend_Registry::get ( 'view' );
		$user = new User();
		$editAction = $this->_request->getParam ( "editAction" );
		$letter = $this->_request->getParam ( 'letter', null );
		$userName = $this->_request->getUserParam ( 'username' , $session->screenName );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		self::setCommonProfileDataIntoSession(null , $session , null , $view);
		$view->currentUser = $currentUser;
		$userId =  $currentUser->user_id;
		self::$logger->info('User Id : ' . $userId);
		$user = new User();
		$currentUser = $user->find($userId);
		$view->screenname = $currentUser[0]['screen_name'];
		
		$this->breadcrumbs->addStep ( $session->screenName ,self::$urlGen->getUserProfilePage($session->screenName , true) );
       	$this->breadcrumbs->addStep ( 'Favorites' );
       	$this->view->breadcrumbs = $this->breadcrumbs;
		$team = new Team ( );
		$league = new Competitionfile ( );
		$userTeam = new UserTeam ( );
		$userPlayer = new UserPlayer ( );
		
		$formToShow = null;
		
		if ($editAction == 'teams') {
			$result = $userTeam->findUserTeamsByType ( $userId, null, null );
			$formToShow = 'editTeams.php';
		
		} else if ($editAction == 'players') {
			// Uncomment after the html page for edit players has been completed. This was done to be able to see the page directly
            $format = 'Domestic league'; //show domestic league stats in default view - teammates detail page
            $result = $userPlayer->findUserPlayers ( $userId, null, null, $letter,$format );
			$formToShow = 'editPlayers.php';
		} else if ($editAction == 'comp') {
			$userLeague = new UserLeague ( );
			$result = $userLeague->findAllUserCompetitions ( $userId, null );
			$formToShow = 'editUserCompetitions.php';
		} else if ($editAction == 'games') {
			$userLeague = new UserLeague ( );
			$result = $userLeague->findAllUserGames ( $userId, null );
			$formToShow = 'editUserGames.php';
		} else {
			
			$view->actionTemplate = 'editfavorities.php';
			$favType = $this->_request->getParam ( 't', 'players' );
			$view->favType = $favType;
			$view->username = $userName;
			$view->profileMenuSelected = 'favorities';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
			return;
		}
		
		$pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($result);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
		
		$row = $league->selectLeaguesByCountry ();
		$natioteams = $team->selectNationalTeams1 ();
		$session->countries = $row;
		$session->natteams = $natioteams;
		
		//put on the session the Regions 
		$regiongroup = new RegionGroup();
		$regionRows = $regiongroup->getAllRegions();
		$session->regions = $regionRows;
		
		$view->leagues = $row;
        $view->regionGroupNames = self::$regionGroupNames;
		
		$alphabet_array = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
		$view->alphabetArray = $alphabet_array;
		$view->letter = $letter;
		$view->userId = $userId;
		
		self::setCommonProfileDataIntoSession($currentUser[0]['screen_name'] , $session , null , $view);
		
		$this->_response->setBody ( $view->render ( $formToShow ) );
	
	}
	
	public function findactivitiesAction() {
		
		$view = Zend_Registry::get ( 'view' );
		//User Feed - Activity
		$user = new User ( );
		$userName = $this->_request->getParam ( 'username', null );
		$type = $this->_request->getParam ( 'type', 0 );
		$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
		$userId = $currentUser->user_id;
		$activity = new Activity ( );
		if ($type == "0") {
			$activitiesPerUser = $activity->findActivitiesPerUser ( $userId );
		} else if ($type == "1") {
			$activitiesPerUser = $activity->findMyActivities ( $userId );
		}
		
		$view->activitiesPerUser = $activitiesPerUser;
		$view->totalFriendActivities = count ( $activitiesPerUser );
		$this->_response->setBody ( $view->render ( 'activityList.php' ) );
	}
	
	public function removefriendsAction() {
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		$arrayFansToRemove = $this->_request->getPost ( 'fanprofilesId' );
		//echo($session->userId);
		$userFriend = new UserFriend ( );
		foreach ( $arrayFansToRemove as $friendId ) {
			$userFriend->deleteUserFriend ( $session->userId, $friendId );
		}
		$this->_redirect ( "/profile/showallfriends/search/all/page/" );
	
	}
	
	public function addfriendsfeedAction() {
		$session = new Zend_Session_Namespace ( "userSession" );
		$arrayFansToAddToFriendFeed = $this->_request->getPost ( 'fanprofilesId' );
		//echo($session->userId);
		$userFriend = new UserFriend ( );
		$data = array ('infriendfeed' => 'y');
		foreach ( $arrayFansToAddToFriendFeed as $friendId ) {
			$userFriend->updateUserFriendById($session->userId ,$friendId ,$data);
		}
		$this->_redirect ( "/profile/showallfriends/search/all/page/" );
	
	}
	
	public function removefromfriendsfeedAction() {
		$session = new Zend_Session_Namespace ( "userSession" );
		$arrayFansToAddToFriendFeed = $this->_request->getPost ( 'fanprofilesId' );
		//echo($session->userId);
		$userFriend = new UserFriend ( );
		$data = array ('infriendfeed' => 'n');
		foreach ( $arrayFansToAddToFriendFeed as $friendId ) {
			$userFriend->updateUserFriendById($session->userId ,$friendId ,$data);
		}
		$this->_redirect ( "/profile/showallfriends/search/all/page/" );
	
	}
	
	public function edituploadphotoAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( "userSession" );
//		if ($session->email == null) {
//			$this->_redirect ( "/login" );
//		}
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		
		$route = $_SERVER ['DOCUMENT_ROOT'] . Zend_Registry::get ( "contextPath" );
		$view->actionTemplate = 'editProfile.php';
		$imageUploadError = null;
		
		if (strtolower ( $_SERVER ['REQUEST_METHOD'] ) == 'post') {
			
			$_FILES ['myfile'] ['name'] = strtolower ( $_FILES ['myfile'] ['name'] );
			//If we have a valid file.
			if (isset ( $_FILES ['myfile'] )) {
				echo $_FILES ['myfile'] ['error'];
				if ($_FILES ['myfile'] ['error'] == 0) { //no error
					//Then we need to confirm it is of a file type we want.
					if (in_array ( $_FILES ['myfile'] ['type'], $GLOBALS ['allowedmimetypes'] )) {
						//verify the size of the file
						if ($_FILES ['myfile'] ['size'] / 1000 > 200.0) {
							//echo 'Image size cannot exceed 200 Kb';
							$this->_redirect("/editprofile/" .$session->screenName ."/photo/imagesizeerror");
						}
						//Then we can perform the copy.
						
						$extension = substr($_FILES ['myfile'] ['name'], strripos($_FILES ['myfile'] ['name'], '.') + 1 );
						$filename = "profile_pic_" .$session->userId . "." . $extension;
						//Zend_Debug::dump($extension);
						if (! move_uploaded_file ( $_FILES ['myfile'] ['tmp_name'], $route . $this->image_folder . "/" .$filename)) { 
							$imageUploadError = "Ooops, there was a problem!  Try uploading your photo again.";
							$this->_redirect("/editprofile/" .$session->screenName ."/photo/systemerror");
							//echo "There was an error uploading the file.";
						}
					}else{
						$this->_redirect("/editprofile/" .$session->screenName ."/photo/imagefiletypeerror");
					}
				} else {
					$imageUploadError = "You need to select a valid image file and then click Upload Photo";
					$this->_redirect("/editprofile/" .$session->screenName ."/photo/systemerror");
				}
				$view->filename = $filename;
				$session->filename = $filename;
				
				$view->type = "photo";
				if ($imageUploadError != null) {
					$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
				}
				$this->_redirect("/editprofile/" .$session->screenName ."/photo");
			}
			
			
		}else {
			//Saving the new photo
			$image = $session->filename;
			self::$logger->info("Image: " . $image);
			self::$logger->info("ScreenName: " . $session->screenName);
			self::$logger->info("Email: " . $session->email);
			//update the user tambien with bigPhoto image name
			$data = array ('main_photo' => $image,
							'date_update' => trim ( date ( "Y-m-d H:i:s" ) )
							);
			
			$user = new User ( );
			$email = $session->email;
			$user->updateUser ( $email, $data );
			$session->mainPhoto = $image;
			//$this->_redirect("/profiles/" .$session->screenName );
			
			//Insert Activity profile pic has been changed
			
			$variablesToReplace = array ('username' => $session->screenName,
										 'gender' => ($session->user->gender =='m'?'his':'her')	);
		
			$activityUser = new Activity ( );
			$activityUser->insertUserActivityByActivityType ( Constants::$_PROFILE_PICTURE_CHANGED, $variablesToReplace, $session->userId );
		
			
			
		} 
	}
	
	function deletephotoAction() {
		$session = new Zend_Session_Namespace ( "userSession" );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Upload Photo";
		$view->actionTemplate = 'userUploadPhoto.php';
		$route = $_SERVER ['DOCUMENT_ROOT'] . Zend_Registry::get ( "contextPath" );
		$temp = $this->_request->getParam ( 'img', 0 );
		
		$image = $route . $this->image_folder  . "/" . $temp;
		$deleteMessage = null;
		//Check for a valid image.
		if (is_file ( $image )) {
			//Attempt to remove the image.
			if (unlink ( $image )) {
				//remove the thumbnail
				echo '<img src="' . Zend_Registry::get ( "contextPath" ) . '/public/images/ProfileMale.gif" width="100" height="100">';
				//$session->filename = '';
				$data = array ('main_photo' => null );
				$user = new User ( );
				$email = $session->email;
				$user->updateUser ( $email, $data );
				$session->filename = null;
				$session->mainPhoto = null;
				
				//$this->_redirect("/editprofile/" .$session->screenName ."/photo");
				
			
			} else {
				$this->_redirect("/editprofile/" .$session->screenName ."/photo/systemerror");
			}
			
			//print the js messages
			//$jserror .= '$(\'MainErrorMessage\').innerHTML = "' . $deleteMessage . '";';
			//$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
			//echo '<script>' . $jserror . ' </script>';
			$view->type = "photo";
			//$session->filename = null;
			//$session->mainPhoto = null;
			//$view->actionTemplate = 'editProfile.php';
			//$this->_response->setBody ( $view->render ( 'editProfile.php' ) );
			
		}
	
	}
	
	
	
	public function editinfoAction() {
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );
		$userId = $session->userId;
		$language = new Language ( );
		$country = new Country ( );
		$view->countries = $country->selectCountries ();
		$view->languages = $language->selectLanguagesOrdered ();
		$formToShow = null;
		$editAction = $this->_request->getParam ( "editaction" );
		if ($editAction == 'profileinfo') {
			$formToShow = 'editUserBasicInfo.php';
		} else if ($editAction == 'photo') {
			$formToShow = 'editUserUploadPhoto.php';
			
		}
		$user = new User ( );
		$rowset = $user->find ( $userId );
		$view->user = $rowset->current ();
		
		if($session->filename == NULL){
			$session->filename = $view->user->main_photo;
		}	 
		//UserLanguage
		$userLanguage = new UserLanguage ( );
		$spokenLanguages = $userLanguage->findLanguagesSpokenPerUser ( $userId );
		$view->spokenLanguages = $spokenLanguages;
		$dob = explode ( '-', $rowset->current()->dob );
		$view->year = $dob [0];
		$view->month = $dob [1];
		$view->day = $dob [2];
		//Zend_Debug::dump($rowset->current());
		//Zend_Debug::dump($editAction);
		$this->_response->setBody ( $view->render ( $formToShow ) );
	
	}
	
	public function updateuserbasicinfoAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		$filter = new Zend_Filter_StripTags ( );
		
		if ($this->_request->isPost ()) { //to update basic info
			$firstname = trim ( $filter->filter ( $this->_request->getPost ( 'firstname' ) ) );
			$lastname = trim ( $filter->filter ( $this->_request->getPost ( 'lastname' ) ) );
			$fnprivate = trim ( $filter->filter ( $this->_request->getPost ( 'fnprivate' ) ) ); //new
			$lnprivate = trim ( $filter->filter ( $this->_request->getPost ( 'lnprivate' ) ) ); //new
			$gender = trim ( $filter->filter ( $this->_request->getPost ( 'gender' ) ) );
			$gprivate = trim ( $filter->filter ( $this->_request->getPost ( 'gprivate' ) ) );
			$dobprivate = trim ( $filter->filter ( $this->_request->getPost ( 'dobprivate' ) ) );
			$dobfull = trim ( $filter->filter ( $this->_request->getPost ( 'birth_year' ) ) ) . "-" . trim ( $filter->filter ( $this->_request->getPost ( 'birth_month' ) ) ) . "-" . trim ( $filter->filter ( $this->_request->getPost ( 'birth_day' ) ) );
			self::$logger->info($dobfull);
			$countrylive = trim ( $filter->filter ( $this->_request->getPost ( 'countrylive' ) ) );
			$citylive = trim ( $filter->filter ( $this->_request->getPost ( 'citylive' ) ) );
			$clprivate = trim ( $filter->filter ( $this->_request->getPost ( 'clprivate' ) ) );
			$countryfrom = trim ( $filter->filter ( $this->_request->getPost ( 'countryfrom' ) ) );
			$cfprivate = trim ( $filter->filter ( $this->_request->getPost ( 'cfprivate' ) ) );
			$cityfrom = trim ( $filter->filter ( $this->_request->getPost ( 'cityfrom' ) ) );
			$cityprivate = trim ( $filter->filter ( $this->_request->getPost ( 'cityprivate' ) ) );
			//$langspeak = trim ( $filter->filter ( $this->_request->getPost ( 'langspeak' ) ) ) ;
			$aboutme = trim ( $filter->filter ( $this->_request->getPost ( 'aboutme' ) ) );
			$numSpokenLanguages = trim ( $filter->filter ( $this->_request->getPost ( 'language_count' ) ) );
			
			
			$data = array ('first_name' => $firstname, 'firstname_priv' => $fnprivate, //1
							'last_name' => $lastname, 'lastname_priv' => $lnprivate, //2
							'gender' => $gender, 'gender_priv' => $gprivate, //3
							'dob_check' => $dobprivate, 'country_live' => $countrylive, 'city_live' => $citylive, 'city_live_priv' => $clprivate, //5
							'country_birth' => $countryfrom, 'country_birth_priv' => $cfprivate, //6
							'city_birth' => $cityfrom, 'city_birth_priv' => $cityprivate, //7
							'aboutme_text' => $aboutme ,
							'dob' => $dobfull,
							'dob_check' => $dobprivate,
							'date_update' => trim ( date ( "Y-m-d H:i:s" ) )
							);
			//Zend_Debug::dump($data);
			$user = new User ( );
			$where = $user->getAdapter ()->quoteInto ( 'user_id = ?', $session->userId );
			self::$logger->info($data);
			self::$logger->info($where);
			try {
				$user->update ( $data, $where );
				
				//update user languages
				$arrayLanguages = array ();
				$user_id = $session->userId;
				$cont = 1;
				for($i = 1; $i <= $numSpokenLanguages; $i++) {
					if (trim ( $filter->filter ( $this->_request->getPost ( 'Languages' . $cont ) ) ) != '0') {
						$arrayLanguages [$i - 1] = trim ( $filter->filter ( $this->_request->getPost ( 'Languages' . $cont ) ) );
					}
					$cont++;
				}
				//delete all userlanguages
				$user_language = new UserLanguage ( );
				$user_language->deleteUserLanguage($user_id);
				
				for($z = 0; $z < count ( $arrayLanguages ); $z ++) {
					
					$data1 = array ('user_id' => $user_id, 'language_id' => $arrayLanguages [$z] );
					$user_language->insert ( $data1 );
				}
				
				
				//insert activity when a user updates his/her profile
				$screenName = $session->screenName;
				$userId = $session->userId;
				$variablesToReplace = array ('username' => $screenName ,
											 'gender' => ($session->user->gender =='m'?'his':'her')
											);
				$activityType = Constants::$_UPDATE_PROFILE_ACTIVITY;
				$activityUpdateProfile = new Activity ( );
				$activityUpdateProfile->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId );
				
				$this->_redirect("/editprofile/" .$session->screenName ."/profileinfo");
				
			} catch ( Exception $e ) {
				echo "An error has ocurred:" . $e->getMessage ();
			}
		
		}
	}
	
	public function removeprofilegoalshoutAction() {
		
		$mc = new Comment ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$userid = $this->_request->getParam ( 'userid', 0 );
		
		//find message id in order to find the owner of the message
		$comment = $mc->fetchRow ( "comment_id = " . $commentId );
		
		$userWhoDeletesComment = 2; //if 1 = message owner , 2 = profile owner
		
		if($session->userId == $comment->friend_id){
			$userWhoDeletesComment = 1;
		}
		
		$mc->updateDeleteComment($commentId , $userWhoDeletesComment );
		
		$this->_redirect("/profile/showprofilegoalshouts/id/" .$userid);
	
	}
	
	public function editprofilegoalshoutAction(){
		
		$mc = new Comment ( );
		
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$userid = $this->_request->getParam ( 'userid', 0 );
		$dataEditted = $this->_request->getParam ( 'dataEditted', null );
		
		$mc->updateComment($commentId , $dataEditted );
		
		$this->_redirect("/profile/showprofilegoalshouts/id/" .$userid);
		
	}
	
	public function reportabuseAction(){
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$userid = $this->_request->getParam ( 'userid', 0 );
		$dataReport = $this->_request->getParam ( 'dataReport', null );
		$reportType = $this->_request->getParam ( 'reportType', null );
		$to = $this->_request->getParam ( 'reportTo', null );
		$report = new Report();
		$data = array ('report_comment_id'  => $commentId, 
					   'report_text' 	    => $dataReport,
					   'report_type'        => $reportType,
					   'report_reported_to' => $to,
					   'report_comment_type'=> Constants::$_REPORT_GOALSHOUT
			  		   );
		
		$report->insert ( $data );
		
		$this->_redirect("/profile/showprofilegoalshouts/id/" .$userid);
		
	}
	
	public function reportuserabuseAction(){
		$session = new Zend_Session_Namespace('userSession');
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$dataReport = $this->_request->getParam ( 'dataReport', null );
		$reportType = $this->_request->getParam ( 'reportType', null );
		$report = new Report();
		$data = array ('report_comment_id' => $commentId, 
					   'report_text' 	   => $dataReport,
					   'report_type'       => $reportType,
					   'report_reported_to' => $commentId,
					   'report_comment_type'       => Constants::$_REPORT_USER
			  		   );
		
		$report->insert ( $data );
		$screenName = $session->screenName;
		$email = $session->email;
		$user = new User();
		$userbeingreported = $user->findUserUnique($commentId);
		$mail = new SendEmail();
		$config = Zend_Registry::get ( 'config' );  
		/*Send Mail to Friend for Request*/
		$mail = new SendEmail();
		$mail->set_from($email);
		$mail->set_to($config->email->confirmation->from);
		//$mail->set_to('chocheraz2003@yahoo.com');
		$mail->set_subject('Report User Message');
		$mail->set_template('reportuser');
		$variablesToReplaceEmail = array ('username' => $screenName, 'usernamereported' => $userbeingreported[0]['screen_name'] ,'reason' => $reportType , 'detail' =>$dataReport);
		$mail->set_variablesToReplace($variablesToReplaceEmail);
		$mail->sendMail();
		/*Send Mail to Friend for Request*/
		
	}
	
	
	public function givehighfiveAction(){
		
		$userid = $this->_request->getParam ( 'userid', 0 );
		$user = new User();
		$profile = $user->find ( $userid );
		$row = $profile->current ();
		
		$totalHighFives = $row->highfives + 1;
		
		$data = array ('highfives' => $totalHighFives);
		$user->update ( $data, 'user_id=' . $userid );
		
		//falta grabar el activity para saber quein dio el highfive
		
	}
	public function blockuserAction(){
		
		$session = new Zend_Session_Namespace ( "userSession" );
		$userFriend = new UserFriend ( );
		$friendId = $this->_request->getParam ( 'userid', null );
		$data = array ('blocked' => 'y');
		$userFriend = new UserFriend();
		$userFriend->updateUserFriendById($session->userId ,$friendId ,$data);
		
	}
	
	public function updateaccountsettingsAction(){
		
		$common = new Common ( );
		$user = new User();
		$session = new Zend_Session_Namespace ( "userSession" );
		$filter = new Zend_Filter_StripTags ( );
		$email = trim ( $filter->filter ( $this->_request->getPost ( 'email' ) ) );
		$oldpassword = trim ( $filter->filter ( $this->_request->getPost ( 'oldpassword' ) ) );
		$newpassword = trim ( $filter->filter ( $this->_request->getPost ( 'newpassword' ) ) );
		//validate same emails
		
		self::$logger->info('New Email : ' . $email);
		self::$logger->info('Old Password : ' . $oldpassword);
		self::$logger->info('New Password : ' . $newpassword);
		//validate if email exist in other accounts
		
		$existSameEmail = $user->findExistSameEmail($email , $session->userId);
		if($existSameEmail != null){
			self::$logger->debug('Error Email: ' . $email ." is already taken by another user");
			echo "Email: " . $email ." is already taken by another user"; 
			return;
		}
		//validate old password if it is ok
		$salt = $session->user->salt;
		$hash = $common->generateHash ( $oldpassword, $salt );
		self::$logger->info('Validating if old password is correct:');
		if ($hash !=$salt) {
			self::$logger->debug('Current Password is incorrect : ' . $oldpassword);
			echo 'Current Password is incorrect : Please Try Again';
			return;
		}
		//generate hash and change password
		
		
		$newpassword = trim ( $filter->filter ( $this->_request->getPost ( 'newpassword' ) ) );
		$common = new Common ( );
		$hash = $common->generateHash ( $newpassword, null );
		$passwordSha = sha1 ( $newpassword );
		//updating password in db
		$data = array ('password' => $passwordSha, 
					   'salt' => $hash, 
					   'date_update' => trim ( date ( "Y-m-d H:i:s" ) ), 
					   'resetPasswordKey' => null ,
					   'email' => $email);
		
		$rowsUpdated = $user->updateUserById ( $session->userId, $data );
		if($rowsUpdated > 0){
			self::$logger->debug('Account Settings Info updated correctly');
			echo 'ok';		
		}else {
			self::$logger->debug('There was an error updating account info');
			echo 'There was an error updating account info.Contact goalface support team';
		}
		
	}
	
public function savealertsAction(){
		
		$leagueAlerts = $this->_request->getPost ( 'leagueSendByEmailFlag' );
		$teamAlerts   = $this->_request->getPost ( 'teamSendByEmailFlag' );
		$playerAlerts = $this->_request->getPost ( 'playerSendByEmailFlag' );

                //Alerts FaceBook
                $leagueFBAlerts = $this->_request->getPost ( 'leagueSendByFaceBookFlag' );
                $teamFBAlerts   = $this->_request->getPost ( 'teamSendByFaceBookFlag' );
		$playerFBAlerts = $this->_request->getPost ( 'playerSendByFaceBookFlag' );
		
		$session = new Zend_Session_Namespace ( "userSession" );
		$user_id = $session->user->user_id;
		$user_league = new UserLeague();
		$user_team = new UserTeam();
		$user_player = new UserPlayer();
		$user = new User();
		//goalface User Alerts
		
		$checkEmailPrivateMessagesAlert = $this->_request->getPost ( 'emailPrivateMessages' );
		$checkEmailFriendInvitesAlert = $this->_request->getPost ( 'emailFriendInvites' );
		$checkEmailGoalShoutsAlert = $this->_request->getPost ( 'emailGoalShouts' );
		$checkPostCommentsAlert = $this->_request->getPost ( 'emailPostComments' );
		$checkEmailFriendActivitiesAlert = $this->_request->getPost ( 'emailFriendActivities' );
		//UserFrecuencies
		$emailPrivateMessagesFrecuency = $this->_request->getPost ( 'emailPrivateMessagesFrecuency' );
		$emailFriendInvitesFrecuency = $this->_request->getPost ( 'emailFriendInvitesFrecuency' );
		$emailGoalShoutsFrecuency = $this->_request->getPost ( 'emailGoalShoutsFrecuency' );
		$emailPostCommentsFrecuency = $this->_request->getPost ( 'emailPostCommentsFrecuency' );
		$emailFriendActivitiesFrecuency = $this->_request->getPost ( 'emailFriendActivitiesFrecuency' );
		
		if($checkEmailPrivateMessagesAlert!= null || $checkEmailFriendInvitesAlert!= null || $checkEmailGoalShoutsAlert!=null ||
			 $checkPostCommentsAlert!=null || $checkEmailFriendActivitiesAlert!=null){
			 	
			 	$data = array ('private_message_email' => $checkEmailPrivateMessagesAlert ,
								'private_message_frecuency' => $emailPrivateMessagesFrecuency,
			 					'friend_invites_email' => $checkEmailFriendInvitesAlert ,
								'friend_invites_frecuency' => $emailFriendInvitesFrecuency,
			 					'goalshouts_email' => $checkEmailGoalShoutsAlert ,
								'goalshouts_frecuency' => $emailGoalShoutsFrecuency,
			 					'commentpost_email' => $checkPostCommentsAlert ,
								'commenpost_frecuency' => $emailPostCommentsFrecuency,
			 					'friendactivity_email' => $checkEmailFriendActivitiesAlert ,
								'friendactivity_frecuency' => $emailFriendActivitiesFrecuency,
			 	);
			 	
			 	$user->updateUserById($user_id, $data);
			 	
			 }
		
		//user leagues - email
		if ($leagueAlerts != null) {
			foreach ( $leagueAlerts as $value ) {
				$dataInsert = explode ( '_', $value );
				$data1 = array ('alert_email' => $dataInsert [2] ,
								'alert_frecuency_type' => $dataInsert [1] );
				$user_league->updateUserLeague ( $user_id ,$dataInsert [0] , $data1);
			}
		}
                //user leagues - FaceBook Alert
                if ($leagueFBAlerts != null) {
                    foreach ( $leagueFBAlerts as $value ) {
                        $dataInsert = explode ( '_', $value );
                        $data1 = array ('alert_facebook' => $dataInsert [2] ,
                                                        'alert_frecuency_type' => $dataInsert [1] );
                        $user_league->updateUserLeague ( $user_id ,$dataInsert [0] , $data1);
                    }
		}

		//user teams - email
		if ($teamAlerts != null) {
			foreach ( $teamAlerts as $value ) {
				$dataInsert = explode ( '_', $value );
				$data1 = array ('alert_email' => $dataInsert [2] ,
								'alert_frecuency_type' => $dataInsert [1] );
				$user_team->updateUserTeam ( $user_id, $dataInsert [0] , $data1 );
			}
		}

                //user teams - FaceBook Alert
		if ($teamFBAlerts != null) {
			foreach ( $teamFBAlerts as $value ) {
				$dataInsert = explode ( '_', $value );
				$data1 = array ('alert_facebook' => $dataInsert [2] ,
								'alert_frecuency_type' => $dataInsert [1] );
				$user_team->updateUserTeam ( $user_id, $dataInsert [0] , $data1 );
			}
		}

		//user player - email
		if ($playerAlerts != null) {
			foreach ( $playerAlerts as $value ) {
				$dataInsert = explode ( '_', $value );
				$data1 = array ('alert_email' => $dataInsert [2] ,
								'alert_frecuency_type' => $dataInsert [1] );
				$user_player->updateUserPlayer ( $user_id ,  $dataInsert [0] , $data1 );
			}
		}

                //user player - FaceBook
		if ($playerFBAlerts != null) {
			foreach ( $playerFBAlerts as $value ) {
				$dataInsert = explode ( '_', $value );
				$data1 = array ('alert_facebook' => $dataInsert [2] ,
								'alert_frecuency_type' => $dataInsert [1] );
				$user_player->updateUserPlayer ( $user_id ,  $dataInsert [0] , $data1 );
			}
		}
		
	}
	
}
?>