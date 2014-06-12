<?php
/**
 * CommunityController
 * 
 * @author
 * @version 
 */
require_once 'util/Common.php';
require_once 'scripts/seourlgen.php';
require_once 'GoalFaceController.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Feed.php';
//require_once 'application/models/Activity.php';
require_once 'Zend/Controller/Action.php';

class CommunityController extends GoalFaceController {
	
	function init(){
		
		$this->updateLastActivityUserLoggedIn();
        Zend_Loader::loadClass ( 'Pagination' );
        Zend_Loader::loadClass ( 'Activity' );
        Zend_Loader::loadClass ( 'Zend_Date' );

        parent::init ();
		
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated CommunityController::indexAction() default action
	}

    public function showuniqueactivityAction () {
    	$view = Zend_Registry::get('view');
    	$config = Zend_Registry::get ('config');
		$urlGen = new SeoUrlGen();

    	$activityModel = new Activity();
    	$matchModel = new Matchh();
    	$timezone =  $this->_request->getParam ( 'timezone', '+00:00' ); //default is GMT
    	$activityId = $this->_request->getParam ( 'activityId', 0 ); 
      	$activity = $activityModel->find($activityId)->current();
      
      	$result = $activityModel->findActivity($activityId,$timezone);
      	$result_match = $matchModel->findMatchById($activity->activity_match_id);
  
      	//Zend_Debug::dump($result_match);

        $view->activity = $activity;
        if ($activity->activity_player_id > 1) {
        	
        	$path_player_photos = $config->path->images->players . $activity->activity_player_id .".jpg" ; 
        	if (file_exists($path_player_photos)) { 
        		$activity_image = $config->path->crop . "/players/" . $activity->activity_player_id . ".jpg";
        	} else {
        		$activity_image = $config->path->crop . "/icons/100/" .$activity->activity_icon;
        	}

        } else {
        	
        	if($activity->activity_icon == 'y') {
        		$activity_image = $config->path->crop . "/photos/" . $activity->activity_type_name;
        	} else {
        		$activity_image = $config->path->crop . "/icons/100/" .$activity->activity_icon;
        	}

        }

       
        $activityUrl = $urlGen->getMatchPageUrl($result_match[0]["competition_name"], $result_match[0]["t1"], $result_match[0]["t2"], $result_match[0]["match_id"], true);

       
       	$view->activitytitle = $result[0]['activitytype_name'] . " Alert";

       	if ($result[0]['activitytype_category_id'] != 3) {
       		$view->imagefacebook = "/public/images/competitionlogos/" . $result_match[0]["competition_id"] . ".gif";
       	} else {
      		$view->imagefacebook = 'http://' . $config->path->index->server->name . $activity_image;
       		$view->imagefacebook = $activity_image;
       	}
       	$view->activityUrl = 'http://www.goalface.com' . $activityUrl;
       	$view->activity_category_id = $result[0]['activitytype_category_id'];
       	$view->team_a = $result_match[0]['team_a'];
       	$view->team_a_name = $result_match[0]["t1"];
		$view->team_b = $result_match[0]['team_b'];
		$view->team_b_name = $result_match[0]["t2"];
		$view->competition_id = $result_match[0]["competition_id"];
 
       	//Using a Template
       	$view->actionTemplate = 'viewactivity.php';
		$this->_response->setBody ( $view->render ( 'site3.tpl.php' ) );
    }



	public function showallactivitiesAction(){
		$view = Zend_Registry::get ( 'view' ) ;
		//$cache = $this->getCache();
		$timezone =  $this->_request->getParam ( 'timezone', '+00:00' ); //default is GMT
		$activity = new Activity();
		//if ($this->_request->isPost ()) {
			//search by category
			$activityId = $this->_request->getParam ( 'activityId', 0 );
			
			if ($activityId != 'all') {
				$result = $activity->selectAllActivitiesCommunity ( $activityId , 15 , null ,$timezone);
			} else {
				$result = $activity->selectAllActivitiesCommunity (null , 15 , null ,$timezone );
			}
		//Zend_Debug::dump($timezone);
		$view->activities = $result;
		$this->_response->setBody ( $view->render ( 'viewcommunityhome.php' ) );
	
	}

    public function showallactivititiesdetailAction() {
        $view = Zend_Registry::get ( 'view' ) ;
        
        //$this->view->optionTab = $this->_request->getParam ( "optionTab");
        $view->optionTab = '';
   
		$view->actionTemplate = 'viewcommunitydetail.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

	public function showallacitivitiesviewAction (){
        $view = Zend_Registry::get ( 'view' );
        $activity = new Activity();
        $timezone =  $this->_request->getParam ( 'timezone', '+00:00' ); //default is GMT
		//Zend_Debug::dump($timezone);
        $activityId = $this->_request->getParam ( 'activityId','all' );
        $days = $this->_request->getParam ( 'days', null );
        
        $date = new Zend_Date();
        //Zend_Debug::dump($date->toString ( 'yyyy-MM-dd' ));
        if($days != NULL){
            $date->sub ( $days, Zend_Date::DAY );
        }
            
        $newdate = $date->toString ( 'yyyy-MM-dd' );
        
       
        if ($activityId != 'all') {
            $result = $activity->selectAllActivitiesCommunity ( $activityId , null , $newdate , $timezone);
        } else {
            $result = $activity->selectAllActivitiesCommunity (null , null , $newdate , $timezone);
        }
        //Get the page number , default 1
        $pageNumber = $this->_request->getParam('page',1);
        //if (empty($pageNumber)){
           // $pageNumber = 1;
        //}
        //Object of Zend_Paginator
        $paginator = Zend_Paginator::factory($result);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage()); // $this->getNumberOfItemsPerPage() comes from ini
        $this->view->paginator = $paginator;

        //$view->activities = $result;
        $view->activityId = $activityId;
        $this->_response->setBody ( $view->render ( 'viewcommunitydetailview.php' ) );
    }
	
	public function showrssfeedAction() {
		
		//$session = new Zend_Session_Namespace('userSession');
		/*if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}*/
		$activity = new Activity ( );
		$resultActivities = $activity->selectActivitiesPerRssFeed ();
		//$resultActivities = $activity->selectAllActivitiesCommunity ();
		
		$domain = 'http://' . $this->getRequest ()->getServer ( 'HTTP_HOST' );
		
		$feedData = array ('title' => sprintf ( "GoalFace Pulse" ), 
							'link' => $domain, 
							'charset' => 'UTF-8', 
							'entries' => array () );
		$urlGen = new SeoUrlGen();  
		// build feed entries based on returned posts
		$matchObject = new Matchh();
		foreach ( $resultActivities as $post ) {
				if($post['activity_match_id']!= null){
					$match = $matchObject->findMatchById ($post['activity_match_id'] );
					$feedUrl = $urlGen->getMatchPageUrl($match [0]["competition_name"], $match [0]["t1"], $match [0]["t2"], $match [0]["match_id"], true);
				}else {
					continue;
				}	
					$entry = array ('title' => strip_tags($post ['activity_text']), 
									'link' => $domain . $feedUrl, 
									'description' => '', 
									'lastUpdate' => strtotime ( $post ['activity_date'] ) );
				
			$feedData ['entries'] [] = $entry;
		}
		
		// create feed based on created data
		$feed = Zend_Feed::importArray ( $feedData, 'rss' );
		
		// disable auto-rendering since we're outputting an image
		$this->_helper->viewRenderer->setNoRender ();
		
		// output the feed to the browser
		$feed->send ();
	
	}

}
