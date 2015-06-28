<?php
require_once 'util/Common.php';
require_once 'GoalFaceController.php';
require_once 'Zend/Search/Lucene.php';

class SearchController extends GoalFaceController {
	
	private static $logger;
	
	function init() {
		Zend_Loader::loadClass ( 'Search' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Pagination' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'MetaKeywordGen' );
		Zend_Loader::loadClass ( 'MetaDescriptionGen' );
        Zend_Loader::loadClass ( 'Player' );
        Zend_Loader::loadClass ( 'User' );
		parent::init ();
		$this->breadcrumbs->addStep ( 'Search Results', "" );
		$this->updateLastActivityUserLoggedIn();
		self::$logger = Zend_Registry::get("logger");
	}
	
	 function indexAction() {
   	$view = Zend_Registry::get ( 'view' ) ;
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->searchString = $this->_request->getParam ( "q");;
    $view->searchCategory = $this->_request->getParam ( "t");;
    $view->searchTerm = $view->searchString;
    $this->view->title = 'Search results for "' . $view->searchString . '"- GoalFace.com';
    $this->view->keywords = $keywords->getMetaKeywords ( '', PageType::$_PLAYERS_MAIN_PAGE );
		$this->view->description = $description->getMetaDescription ( '', PageType::$_PLAYERS_MAIN_PAGE );
    $view->actionTemplate = 'searchResults.php';
    $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
   }
	
	function searchresultAction() {
		$view = Zend_Registry::get ( 'view' ); 
		$session = new Zend_Session_Namespace('userSession');
		$userid = null;
		if($session->userId != null) {
		   $userid = $session->userId  ;
		} 
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
		
		//Here you map the path where the indexes are located
		$config = Zend_Registry::get ( 'config' );
		$path_search_index = $config->path->search->index;
		
		//Zend_Search_Lucene::setResultSetLimit ( 50 );
		$index = new Zend_Search_Lucene ( $path_search_index );
		$querystr = $this->_request->getParam ( 'q', 0 );
		//echo mb_detect_encoding($querystr);
		$querystrLower = trim(mb_strtolower ( $querystr , 'utf-8'));
		$queryType = $this->_request->getParam ( 't', 'best-matches' );
		
		$view->searchTerm = $this->_request->getParam ( "q");
                
		//$queryObj = Zend_Search_Lucene_Search_QueryParser::parse($querystr);
		try {
			$query = new Zend_Search_Lucene_Search_Query_Boolean ( );
			$queryTerms = explode ( ' ', $querystrLower );
			if (count ( $queryTerms ) > 1) {
				$subquery1 = new Zend_Search_Lucene_Search_Query_MultiTerm ( );
				foreach ( $queryTerms as $t ) {
					$subquery1->addTerm ( new Zend_Search_Lucene_Index_Term ( $t ), null );
				}
				$query->addSubquery ( $subquery1, true );
			} else {
				$term1 = new Zend_Search_Lucene_Index_Term ( $querystrLower, 'contents');
				$subquery1 = new Zend_Search_Lucene_Search_Query_Term ( $term1 );
				$query->addSubquery ( $subquery1, true );
			
			}
			if ($queryType != 'best-matches') {
				$term2 = new Zend_Search_Lucene_Index_Term ( $queryType, 'category' );
				$subquery2 = new Zend_Search_Lucene_Search_Query_Term ( $term2 );
				$query->addSubquery ( $subquery2, true  /* optional */);
			}
			
			//echo $query;
			$hits = $index->find ( $query );
			$searchterm = new Search ( );
			if (count ( $hits ) <= 0) {
				$topSearches = $searchterm->GetTopSearchTerms ();
				$view->topSearches = $topSearches;
			} else {
				$topSearches = $searchterm->AlterSearchTermCount ( $querystr );
			}
			$view->topSearches = $topSearches;
   
           $results = array();
            foreach ($hits as $hit)
            {
            	//echo $hit->category . "<br>" ;	
            	if($hit->category == "players")
                {
                   // this is a hack to get the ID
                   $temp = substr($hit->url, 0, strlen($hit->url) - 1 );
                    //Zend_Debug::dump($temp);
                    $playerId = (substr($temp, strripos($temp, '_') + 1)); 
                     $player = new Player ( );
                     $playerResult = $player->getPlayersForSearchResult($playerId,$userid);
                     if(count($playerResult) > 0)
                     {                        
                        array_push($results, array("type" => "players", "result" => $playerResult[0]));
                     }                    
                }
                elseif($hit->category == "clubs")
                {
                   // this is a hack to get the ID
                     $temp = substr($hit->url, 0, strlen($hit->url) - 1 );
					 					 $teamId = (substr($temp, strripos($temp, '_') + 1));
                     $team = new Team( );
                     $teamResult = $team->findTeamSearch($teamId,$userid);
                     if(count($teamResult) > 0)
                     {
                        array_push($results, array("type" => "teams", "result" => $teamResult[0]));
                     }
                }
            	elseif($hit->category == "leagues")
                {
                   	// this is a hack to get the ID
                   	 $temp = substr($hit->url, 0, strlen($hit->url) - 1 );
                   	 self::$logger->info("URL->" . $hit->url);
					 					 $leagueId = (substr($temp, strripos($temp, '_') + 1));
					 					 self::$logger->info("LeagueID->" . $leagueId);
                     $league = new LeagueCompetition();
                     $leagueResult = $league->getLeaguesForSearchResult($leagueId,$userid);
                     if(count($leagueResult) > 0)
                     {
                     	array_push($results, array("type" => "leagues", "result" => $leagueResult[0]));
                     }
                }
                 elseif($hit->category == "profiles")
                {
                   // this is a hack to get the ID
                   $temp = substr($hit->url, 0, strlen($hit->url) - 1 );
				   				 $screenName = (substr($temp, strripos($temp, '/') + 1));
                   $user = new User();
                   $profileResult = $user->findUsersSearch($screenName);
                    if(count($profileResult) > 0)
                    {
                       array_push($results, array("type" => "profiles", "result" => $profileResult[0]));
                    }
                }
                /*elseif($hit->category == "news")
                {
                	//Zend_Debug::dump($hit);
                    $feednews = new FeedNews();
                    $newsResult = $feednews->selectFeedNewsById($hit->feedid);
                    if(count($newsResult) > 0)
                    {
                       array_push($results, array("type" => "news", "result" => $newsResult[0]));
                    }
                }elseif($hit->category == "photos")
                {
                   // this is a hack to get the ID
                   $temp = substr($hit->url, 0, strlen($hit->url) );
                   $imageName = (substr($temp, strripos($temp, '/') + 1));
				   				 $imageName = (substr($imageName,0 , strripos($imageName, '.')));
				   				 $photo = new Photo();
                   $photoResult = $photo->findUniquePhotoByName($imageName);
                   if(count($photoResult) > 0)
                   {
                      array_push($results, array("type" => "photos", "result" => $photoResult[0]));
                   }
                }*/
            }
            //Zend_Debug::dump($results);
            $view->hits = $results;
            //$view->hits = $hits;
            $view->query = $query;
            //$view->search-query = $querystr;

            //pagination - getting request variables
            $pageNumber = $this->_request->getParam('page');
            if (empty($pageNumber)){
                $pageNumber = 1;
            }

            $paginator = Zend_Paginator::factory($results);
            $paginator->setCurrentPageNumber($pageNumber);
            $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
            $this->view->paginator = $paginator;
        
			
            
            $this->view->breadcrumbs = $this->breadcrumbs;
            $view->actionTemplate = 'searchResults.php';
            $type = $this->_request->getParam ( 'type', 1 );

            if ($type == 1) {
                    $this->_response->setBody ( $view->render ( 'searchResultsDetail.php' ) );
            } else {
                    $this->_response->setBody ( $view->render ( 'viewsearchresults.php' ) );
            }
		} catch ( Zend_Search_Lucene_Search_QueryParserException $e ) {
			echo "Query syntax error: " . $e->getMessage () . "\n";
		}
	}
	
}
?>
