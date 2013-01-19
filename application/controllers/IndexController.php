<?php
require_once 'Zend/Session/Namespace.php';
require_once 'application/controllers/GoalFaceController.php';
require_once 'application/controllers/util/PageType.php';
require_once 'application/controllers/util/PageTitleGen.php';
require_once 'application/controllers/util/MetaDescriptionGen.php';
require_once 'Zend/Loader.php';
require_once 'application/controllers/util/MetaKeywordGen.php';
require_once 'application/models/Feed.php';
require_once 'application/models/Team.php';
require_once 'application/models/Player.php';
require_once 'Zend/Registry.php';
require_once 'application/models/Message.php';
require_once 'Common.php';
require_once 'scripts/seourlgen.php';
//require ("library/Zrad/Zrad_Facebook.php");
require_once 'Zrad/Zrad_Facebook.php';require_once 'Zrad/cFacebook.php';

class IndexController extends GoalFaceController  {
	
	function init() {
		$this->updateLastActivityUserLoggedIn();
		parent::init();
	}
	public function indexAction() {
		$session = new Zend_Session_Namespace('userSession');
		
		if ($session->user != null) {
			$this->_redirect ( "/myhome" );
		}				$config = Zend_Registry::get ( 'config' );		$appId = $config->facebook->appid;		$secret = $config->facebook->secret;		$servername = $config->path->index->server->name;				$valTemp = cFacebook::loginFacebook('sign-in',$session,$appId,$secret,$servername);		if($valTemp != null){			$this->_redirect ( "/login/fbdologin" );		}
		
		//parent::validateLoginExpired();
		
		$logout = $this->_request->getParam ( 'logout' );
		if($logout !='true'){
			//$this->checkifUserIsRemembered();
		}	
		
		$view = Zend_Registry::get ( 'view' );
		
		$title = new PageTitleGen();		
		$keywords = new MetaKeywordGen();
		$description = new MetaDescriptionGen();
	
		$view->title = $title->getPageTitle('',PageType::$_HOME_PAGE);
		$view->keywords = $keywords->getMetaKeywords('', PageType::$_HOME_PAGE);
		$view->description = $description->getMetaDescription('', PageType::$_HOME_PAGE);
		$view->imagefacebook = "http://www.goalface.com/public/images/GoalFaceBall200x200.gif";
		
		//New Messages per User
		//echo $myNamespace->email;
		$countries = new Country();
		$userLeague = new UserLeague();
		$countrylist =  null;
	    $team = new Team();
	    $player = new Player();
		
		$view->featuredPlayers = $player->findFeaturedPlayers ( 6 );
		if($session->email != null){
			$countrylist =  $countries->selectCountriesScoreboard();
			$view->countryList = $countrylist ;
    		//Find User Country Competitions
			$userCompetitions = $userLeague->findUserCountryCompetitions($session->userId);
			$view->userCompetitions = $userCompetitions;						//Find if user has some fav on featured 6 teams             $view->featuredTeams = $team->findFeaturedTeams (6,$session->userId);        
		} else {		    $view->featuredTeams = $team->findFeaturedTeams (6); 		}
		$view->actionTemplate = 'home.php';
		$view->selected = 'today';		
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
    function authenticatedhomeAction () {		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'userSession' );

		if ($session->user == null) {
			$this->_redirect("/sign-in");
		}
		$view->dashboardMenuSelected = 'home';
		
		$view->title = "Welcome, " . $session->screenName . "! | GoalFace.com";
		//$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_PLAYERS_POPULAR_PAGE );
		//$view->description = $description->getMetaDescription ( '', PageType::$_PLAYERS_POPULAR_PAGE );
		$view->actionTemplate = 'homeauthenticated.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
     }
     
     function showscorescheduleAction(){
     	 //get the scored from now until 7 days ago
        $view = Zend_Registry::get ( 'view' );
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
     	$limit = $this->_request->getParam ( 'limit', null );
     	$compId = $this->_request->getParam ( 'compId', null );
        $fechas = $this->calculateDates ();
        $typeOfMatches = $this->_request->getParam ( 'date', 'played' );
        //Zend_Debug::dump($fechas);
        $match = new Matchh();
		$mymatches = $match->selectFavoriteLeaguesMatches($session->userId ,$limit ,$typeOfMatches , $typeOfMatches=='fixture'?'asc':'desc',$compId ,$fechas);
		$view->limit = $limit;  
		//pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($mymatches);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage(50);
        $view->league = $compId;
        $this->view->paginator = $paginator; 
        $view->typeOfResults = $typeOfMatches;
		$this->_response->setBody ( $view->render ( 'myresults.php' ) );
     }
     
     function morescoresAction(){
     	
     	$view = Zend_Registry::get ( 'view' );
        $session = new Zend_Session_Namespace ( 'userSession' );
    	if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
        $view->title = "My Scores & Schedules | GoalFace.com";
        $type = $this->_request->getParam ( 'type', null );
        $view->type = $type;
        $userLeague = new UserLeague();
        $userCompetitions = $userLeague->findAllUserCompetitions($session->userId);
        $view->userCompetitions = $userCompetitions;
        
     	$view->dashboardMenuSelected = 'scores';
        $view->actionTemplate = 'scoresauthenticatedmore.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
        
        
     }
     function moreupdatesAction(){
     	
     	$view = Zend_Registry::get ( 'view' );
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
     	$view->dashboardMenuSelected = 'updates';
     	$view->actionTemplate = 'moreupdates.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
     	
     }
     
	function moreactivitiesAction(){
     	
		$view = Zend_Registry::get ( 'view' );
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
     	$view->dashboardMenuSelected = 'friends';
     	$view->actionTemplate = 'moreactivities.php';
        $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
     	
     }
     
     function showmessagerepliesAction(){
     	$view = Zend_Registry::get ( 'view' );
     	$commentid = $this->_request->getParam ( 'commentid', null );
     	$isActivityComment = $this->_request->getParam ( 'isActivityComment', null );
     	$view->commentid = $commentid;
     	$view->isActivityComment = $isActivityComment;
     	$this->_response->setBody ( $view->render ( 'viewmessagereplies.php' ) );
     }
     

     function showmyupdatesAction(){
     	$view = Zend_Registry::get ( 'view' );
        $session = new Zend_Session_Namespace ( 'userSession' );
	    if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
     	$type = $this->_request->getParam ( 'activityId', 0 );
     	$limit = $this->_request->getParam ( 'limit', null );
     	
    
     	$activity = new Activity();
     	$allupdates = $activity->selectAllMyUpdates($session->userId , $limit , $type);
     	$view->limit = $limit;
     	$pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($allupdates);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage(50);
     	$this->view->paginator = $paginator; 
     	$this->_response->setBody ( $view->render ( 'mystuffupdates.php' ) );
     }
     
     function showactivitiesAction(){
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	$view = Zend_Registry::get ( 'view' );
      	if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
     	
     	$type = $this->_request->getParam ( 'activityId', 0 );
     	$limit = null;
     	$haslimit = $this->_request->getParam ( 'haslimit', 'n' );
     	
     	if($haslimit != 'n'){
     		$limit = 10;
     	}
        $activity = new Activity();
        #echo "Activity Type: " . $type;
     	if($type == 0){
     		$allActivities = $activity->findAllActivities($session->userId ,$limit , $type);
     	}else if($type == 1){//friends activity
     		$allActivities = $activity->findActivitiesPerUser($session->userId ,0 , $limit ,$haslimit );
     	}else if($type == 2){//friends broadcast	
     		$allActivities = $activity->findBroadcastByUserFriends($session->userId ,$type , $limit ,$haslimit );
     	}else if($type == 3){ //myactivy
     		$allActivities = $activity->findMyActivities($session->userId  , 0 , $limit , $haslimit);
     	}else if($type == 4){
     		$allActivities = $activity->findBroadcastByUserFriends($session->userId ,$type , $limit ,$haslimit);
     	}
     	$view->limit = $limit;	
     	$pageNumber = $this->_request->getParam('page'); 
     	if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($allActivities);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage(50);
     	$this->view->paginator = $paginator; 
     	
     	$this->_response->setBody ( $view->render ( 'myactivitybroadcast.php' ) );
     }
     
     
    function addbroadcastAction(){
    	
    	$session = new Zend_Session_Namespace ( 'userSession' );
     	$view = Zend_Registry::get ( 'view' );
      	if ($session->screenName == null) {
			$view->sessionTimeout = true;
			return;
		}
    	$message_wall = $this->_request->getParam ( 'message_wall', null );
    	$type = $this->_request->getParam ( 'type', null );
    	$comment_type = Constants::$_COMMENT_BROADCAST;
    	$commentid = $this->_request->getParam ( 'commentid', null );
    	//If this is a comment on an activity the param is not null
    	$activity_id = $this->_request->getParam ( 'isActivityComment', null );
    	#echo "$activity_id: " . $activity_id;
    	if(filter_var($activity_id, FILTER_VALIDATE_BOOLEAN) )
    	{
    		//Record comment as comment on activity
    		$comment_type = Constants::$_COMMENT_ACTIVITY;
    	}
    	$comment_create = trim ( date ( "Y-m-d H:i:s" ) );
		$data = array ('comment_party_id' => $session->userId, 
						'friend_id' => $session->userId, 
						'comment_data' => $message_wall, 
						'comment_date' => $comment_create, 
						'comment_type' => $comment_type,
						'comment_super_id' => $commentid,
						'region_group_id' => null,
						'league_id' => null, 
						'photo_id' => null, 
						'is_public' => $type
						);
		
		$comment = new Comment ( );
		$broadcastId = $comment->insert ( $data );
		//Zend_Debug::dump($broadcastId);
		//update user table with the id of the last comment id (broadcast)
		$user = new User();
		$data = array ('last_broadcast' => $broadcastId);
		$user->updateUserById($session->userId ,$data);
    	
    }
    
    

    /*
     * Added by Abou Kone 07/17/2011 to support Edit and Delete on Activity Broadcast
     * 
     */
	public function editgoalshoutAction(){
		
		$mc = new Comment ( );
		//Get the data to update
		$commentId = $this->_request->getParam ( 'id', 0 );
		$dataEditted = $this->_request->getParam ( 'dataEditted', null );
		$redirect =  $this->_request->getParam ( 'redirect', null );	
		$mc->updateComment($commentId , $dataEditted );
		
		$this->_redirect($redirect);
		
	}
	
	/*
	 * 
	 */
	public function removegoalshoutAction() {
		
		$mc = new Comment ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		//find message id in order to find the owner of the message
		$comment = $mc->fetchRow ( "comment_id = " . $commentId );
		$userWhoDeletesComment = 2; //if 1 = message owner , 2 = profile owner
		
		if($session->userId == $comment->friend_id){
			$userWhoDeletesComment = 1;
		}
		$redirect =  $this->_request->getParam ( 'redirect', null );	
		$mc->updateDeleteComment($commentId , $userWhoDeletesComment );
		$results = array("success"=>true, "message"=>"", "comment_id"=>$commentId
		, "comment"=>$comment, "userWhoDeletesComment"=>$userWhoDeletesComment);

        $this->_helper->viewRenderer->setNoRender(true);
		echo Zend_Json::encode($results);
		
		
	
	}
	public function showrssfeedactivityAction() {
		
		$user = new User();
		$view = new Zend_View();
		$view->setEscape('htmlentities');
		$email = trim ( $this->_request->getParam ( 'email', 0 ) );
		$key = trim ( $this->_request->getParam ('key', 0 ) );
		$rowset = $user->findUniqueUser ( $email );
		
		if ($rowset !=null && $rowset->salt == $key){
		
			$activity = new Activity ( );
			$resultActivities = $activity->findAllActivities($rowset->user_id ,20 , 0 );
			
			$domain = 'http://' . $this->getRequest ()->getServer ( 'HTTP_HOST' );
			
			$feedData = array ('title' => sprintf ( "(" .$rowset->screen_name. " ) Friends Activity & Broadcasts" ), 'link' => $domain, 'charset' => 'UTF-8', 'entries' => array () );
			
			// build feed entries based on returned posts
			foreach ( $resultActivities as $post ) {
				
				$entry = array ('title' => strip_tags($post ['activity_text']) ,
								'link' => $domain, 
								'description' => '', 
								'lastUpdate' => strtotime ( $post ['activity_date'] ) )
	
				;
				$feedData ['entries'] [] = $entry;
			}
			
			// create feed based on created data
			$feed = Zend_Feed::importArray ( $feedData, 'rss' );
			
			// disable auto-rendering since we're outputting an image
			$this->_helper->viewRenderer->setNoRender ();
			
			// output the feed to the browser
			$feed->send ();
		}else {
			echo "Access Denied";
			//echo "[ Click here to proceed ]";
		}
	
	}
	public function showrssfeedupdatesAction(){
		
		$user = new User();
		$email = trim ( $this->_request->getParam ( 'email', 0 ) );
		$key = trim ( $this->_request->getParam ('key', 0 ) );
		$urlGen = new SeoUrlGen();  
		$rowset = $user->findUniqueUser ( $email );
		
		if ($rowset !=null && $rowset->salt == $key){
	     	$activity = new Activity();
	     	$resultActivities = $activity->selectAllMyUpdates($rowset->user_id ,20 , 0);
	     	
	     	$domain = 'http://' . $this->getRequest ()->getServer ( 'HTTP_HOST' );
			
			$feedData = array ('title' => sprintf ( "(" .$rowset->screen_name. " ) Updates" ), 'link' => $domain, 'charset' => 'UTF-8', 'entries' => array () );
			
			// build feed entries based on returned posts
			$matchObject = new Matchh();
			foreach ( $resultActivities as $post ) {
				
				$match = $matchObject->findMatchById ($post['activity_match_id'] );
				$feedUrl = $urlGen->getMatchPageUrl($match [0]["competition_name"], $match [0]["t1"], $match [0]["t2"], $match [0]["match_id"], true);
				
				$entry = array ('title' => strip_tags($post ['activity_text']), 
								'link' => $domain . $feedUrl, 
								'description' => '', 
								'lastUpdate' => strtotime ( $post ['activity_date'] ) )
	
				;
				$feedData ['entries'] [] = $entry;
			}
			
			// create feed based on created data
			$feed = Zend_Feed::importArray ( $feedData, 'rss' );
			
			// disable auto-rendering since we're outputting an image
			$this->_helper->viewRenderer->setNoRender ();
			
			// output the feed to the browser
			$feed->send ();
		}else {
			echo "Access Denied";
			//echo "[ Click here to proceed ]";
		}
		
	}
	
	function inviteAction() {
		
		$view = Zend_Registry::get ( 'view' );
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_INVITEFRIENDS );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_INVITEFRIENDS );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_INVITEFRIENDS );
		
		$view->output_message = "";
		$filter = new Zend_Filter_StripTags ( );
		if ($this->_request->isPost ()) {
			$to = trim ( $filter->filter ( $this->_request->getPost ( 'to' ) ) );
			$subject = trim ( $filter->filter ( $this->_request->getPost ( 'subject' ) ) );
			$message = trim ( $filter->filter ( $this->_request->getPost ( 'message' ) ) );
			//split emails
			$arrayofmails = explode ( ',', $to );
			$validator = new Zend_Validate_EmailAddress ( );
			
			$jserror = '';
			//loop through array, validates each email and sent individually.
			if(count ( $arrayofmails ) <= 5){
				for($i = 0; $i < count ( $arrayofmails ); $i ++) {
					if (! $validator->isValid ( trim ( $arrayofmails [$i] ) )) {
						$jserror .= 'jQuery(\'#tomailerror\').removeClass("ErrorMessages").addClass("ErrorMessageIndividualDisplay");';
						if ($jserror != '') {
							$jserror .= 'jQuery(\'#ErrorMessages\').removeClass("ErrorMessageIndividualDisplay").addClass("ErrorMessagesDisplay");';
							$jserror .= 'jQuery(\'#ErrorMessages\').html("Ooops, there was a problem with the information you entered below. Please correct the highlighted fields.");';
							echo '<script>' . $jserror . ' </script>';
							return;
						}
					}
				}
			}else{
				$jserror .= 'jQuery(\'#tomailerror\').removeClass("ErrorMessages").addClass("ErrorMessageIndividualDisplay");';
				if ($jserror != '') {
					$jserror .= 'jQuery(\'#ErrorMessages\').removeClass("ErrorMessageIndividualDisplay").addClass("ErrorMessagesDisplay");';
					$jserror .= 'jQuery(\'#tomailerror\').html("You can invite up to five(5) friends.");';
					$jserror .= 'jQuery(\'#ErrorMessages\').html("Ooops, there was a problem with the information you entered below. Please correct the highlighted fields.");';
					echo '<script>' . $jserror . ' </script>';
					return;
				}
				
			}
			$session = new Zend_Session_Namespace('userSession');
			$screenName = $session->email;
			/*Send Mail to Friend for Request*/
			
			$variablesToReplaceEmail = array ('username' => $screenName, 
											  'urlName' =>  $_SERVER['SERVER_NAME'],
											  'customMessage' => $message);
			
			$config = Zend_Registry::get ( 'config' );
			/*Send Mail to Friend for Request*/
			$mail = new SendEmail();
			$mail->set_from($config->email->confirmation->from);
			$mail->set_subject($subject);
			$mail->set_template('invitefriends');
	    	$mail->set_variablesToReplace($variablesToReplaceEmail);
	    	
			for($i = 0; $i < count ( $arrayofmails ); $i ++) {
				$mail->set_to( $arrayofmails[$i]);
			}
			$mail->sendMail ();
		} 
	}
	
	
	public function sendthispagebyemailAction(){
		
		$from = $this->_request->getParam ( 'from');
		$config = Zend_Registry::get ( 'config' );
		if(trim($from) == '' ){
			$from = $config->email->confirmation->from;
		}
		$subject = $this->_request->getParam ('subject', "" );
		$message = $this->_request->getParam ('message', "" );
		$email = $this->_request->getParam ( 'to', "default" );
		$pageurl = $this->_request->getParam ( 'pageurl', "" );
		
		$session = new Zend_Session_Namespace('userSession');
		$screenName = $session->email!= null?$session->userName:$from;
		/*Send Mail to Friend for Request*/
		$mail = new SendEmail();
		$mail->set_from($from);
		$mail->set_to( $email);
		$mail->set_subject($subject);
		$mail->set_template('emailthispage');
		$variablesToReplaceEmail = array ('username' => $screenName, 
										  'pageUrl' => $pageurl,
										  'pageTitle' =>$subject,
										  'customMessage' => $message);
		$mail->set_variablesToReplace($variablesToReplaceEmail);
		$mail->sendMail();
		/*Send Mail to Friend for Request*/
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
        $fechas [7] = $temp2;
        return $fechas;

    }

}
?>