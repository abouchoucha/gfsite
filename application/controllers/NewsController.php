<?php

require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';

class NewsController extends GoalFaceController {
	
	public $title = null;		
	public $keywords = null;
	public $description = null;
	private static $logger;
	
	function init() {
            Zend_Loader::loadClass ( 'Feed' );
            Zend_Loader::loadClass ( 'User' );
            Zend_Loader::loadClass ( 'FeedNews' );
            Zend_Loader::loadClass ( 'NewsFeed' );
            Zend_Loader::loadClass ( 'Tags' );
            Zend_Loader::loadClass ( 'NewsFeedPhoto' );
            Zend_Loader::loadClass ( 'PageTitleGen' );
            Zend_Loader::loadClass ( 'Zend_Debug' );
            Zend_Loader::loadClass ( 'Zend_Date' );
			Zend_Loader::loadClass ( 'Zend_Feed_Rss' );

            parent::init ();
            $this->title = new PageTitleGen();
            $this->keywords = new MetaKeywordGen();
            $this->description = new MetaDescriptionGen();

            self::$logger = Zend_Registry::get("logger");

            $this->updateLastActivityUserLoggedIn();
	}
	
	
	
        //Page without AFP news
        public function indexAction() {
            //$this->checkifUserIsRemembered();

            $view = Zend_Registry::get ( 'view' );
            $this->view->title = $this->title->getPageTitle ( '', PageType::$_NEWS_MAIN_PAGE );

            //$this->view->soccerRss = $this->fetchRssHeadLines ( 25 );
            $category = $this->_request->getParam ( 'cat', null );
            $this->view->category = $category;
            if ($category == 'world cup') {
                $menuSelected = 'competition';
                $view->menuSelected = $menuSelected;
                $submenuSelected = 'news';
                $view->submenuSelected = $submenuSelected;
                $view->leagueId = 72;
            }

            $this->view->actionTemplate = 'newsAroundWorld.php';
            //$this->view->actionTemplate = 'latestnews.php';
            $this->_response->setBody ( $this->view->render ( 'site.tpl.php' ) );

        }


	public function searchtopnewsAction() {

            $feedNews = new FeedNews ( );

            $typeOfSearch = $this->_request->getParam ( 'search', '' );  
            $category = $this->_request->getParam ( 'cat', null );
			 	
            $resultNewsFeeds = $feedNews->selectLatestFeeds( null, null, $typeOfSearch, $category );

     
            //pagination - getting request variables
            $pageNumber = $this->_request->getParam('page');
            if (empty($pageNumber)){
                $pageNumber = 1;
            }
            $paginator = Zend_Paginator::factory($resultNewsFeeds);
            $paginator->setCurrentPageNumber($pageNumber);
            $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
            $this->view->paginator = $paginator;
            //Zend_Debug::dump($paginator);

            //$this->view->selectedFeeds = $selectedFeeds;
            $this->breadcrumbs->addStep ( 'Top News RSS' );
            $this->view->breadcrumbs = $this->breadcrumbs;
            $this->_response->setBody ( $this->view->render ( 'topnewsresult.php' ) );

	}

        // Loads Featured News Page with AFP feed
//	public function indexAction() {
//
//		$this->checkifUserIsRemembered();
//
//		$view = Zend_Registry::get ( 'view' );
//		$this->view->title = $this->title->getPageTitle ( '', PageType::$_NEWS_FEATURED_PAGE );
//
//		$feedNews = new FeedNews ( );
//		$selectedFeeds = $feedNews->selectLatestFeeds ( 0, 3, "default" );
//		$this->view->selectedFeeds = $selectedFeeds;
//		//fetch Cloud tags
//		$tags = new Tags();
//		$tags = $tags->findTags(6);
//		$this->view->tags = $tags;
//
//                $view->newsMenuSelected = 'featured';
//
//                $urlGen = new SeoUrlGen();
//		$this->breadcrumbs->addStep ('Featured News', $urlGen->getMainNewsPage(true));
//		//echo Zend_Debug::dump($this->breadcrumbs);
//		//$logger = Zend_Registry::get('logger2');
//		//$logger->info(Zend_Debug::dump($this->breadcrumbs));
//		$this->view->breadcrumbs = $this->breadcrumbs;
//
//		$view->actionTemplate = 'featuredNews.php';
//		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
//	}
	
	public function searchfeaturednewsAction() {
		
		$newsFeed = new NewsFeed ( );
		$typeOfSearch = $this->_request->getParam ( 'search', '' );
		
		$resultNewsFeeds = $newsFeed->selectNewsFeeds ( null, null, $typeOfSearch ,'y');
		
		//pagination - getting request variables
                $pageNumber = $this->_request->getParam('page');
                if (empty($pageNumber)){
                    $pageNumber = 1;
                }
                $paginator = Zend_Paginator::factory($resultNewsFeeds);
                $paginator->setCurrentPageNumber($pageNumber);
                $paginator->setItemCountPerPage(5);
                $this->view->paginator = $paginator;
		
		
		//$this->view->newsCount = $totalRows;
		$this->view->typeOfSearch = $typeOfSearch;
		
		//Zend_Debug::dump($selectedFeeds);
		//$this->view->selectedFeeds = $selectedFeeds;
		$this->breadcrumbs->addStep ( 'Featured News' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$this->_response->setBody ( $this->view->render ( 'featuredNewsResult.php' ) );
	
	}
	
	public function rssAction() {

		
		$newsFeed = new NewsFeed ( );
		$newsFeeds = $newsFeed->selectNewsFeeds ( 1, 15 ,null ,'y');
		$urlGen = new SeoUrlGen ( );
		$domain = 'http://' . $this->getRequest ()->getServer ( 'HTTP_HOST' );
		
		$feedData = array ('title' => sprintf ( "Featured News " ), 'link' => $domain, 'charset' => 'UTF-8', 'entries' => array () );
		
		$feedPhoto = new NewsFeedPhoto ( );
		// build feed entries based on returned posts
		

		$feedData = array ('title' => 'GoalFace.com', 'link' => 'http://goalface.com/news/rss', 'description' => 'For Fans of the beautiful Game.', 'language' => 'en-us', 'charset' => 'utf-8', 'pubDate' => strtotime ( time () ), 'generator' => 'Zend Framework Zend_Feed', 'entries' => array () );
		foreach ( $newsFeeds as $post ) {
			
			$photos = $feedPhoto->selectNewsArticlePhotos ( $post ["news_id"] );
			$imageUrl = Zend_Registry::get ( 'contextPath' ) . "/public/feed/afp/" . $photos [0] ['photo_thumb_file'];
			
			$feedData ['entries'] [] = array (

			'title' => $post ["news_headline"], 
			'link' =>  $domain  .$urlGen->getNewsArticlePageUrl ( $post ["news_headline"], $post ["news_id"], true ), 
			'description' => substr ( $post ["news_body_content"], 0, stripos ( $post ["news_body_content"], " ", 300 ) ), 
			'lastUpdate' => strtotime ( $post ['news_this_created'] ), 
			'image' => $imageUrl, 
			'pubDate' => strtotime ( $post ['news_this_created'] ) );
		}
		
		// create feed based on created data
		$feed = Zend_Feed::importArray ( $feedData, 'rss' );
		
		// disable auto-rendering since we're outputting an image
		$this->_helper->viewRenderer->setNoRender ();
		
		// output the feed to the browser
		$feed->send ();
	
	}
	
	public function ratenewsAction() {
		
		$value = $this->_request->getParam ('rating', 0 );
		$idToRate = $this->_request->getParam ('idToRate', 0 );
		
		$news = new NewsFeed();
		$rowset = $news->selectNewsArticle ( $idToRate );
		
		$temp = $rowset[0]['news_total_votes'] + 1;
		$temp2 = $rowset[0]['news_total_rating_count'] + $value;
		
		$data = array ('news_total_votes' => $temp, 'news_total_rating_count' => $temp2 );
		
		$news->updateNewsFeed( $data, $idToRate );
		
		echo $temp2/$temp;
		
	}
	

   
		//noy used anymore - replaced by index action
	public function shownewsworldAction() {
		$view = Zend_Registry::get ( 'view' );
                $this->view->title = $this->title->getPageTitle ( '', PageType::$_NEWS_MAIN_PAGE );
		
		//$this->view->soccerRss = $this->fetchRssHeadLines ( 25 );
		$view->newsMenuSelected = 'top';
        
		$newsFeed = new NewsFeed ();
		$this->view->selectedNewsFeeds = $newsFeed->selectNewsFeeds ( 0 , 3 , null ,'y');
		$this->breadcrumbs->addStep ( 'Top News' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$this->view->actionTemplate = 'newsAroundWorld.php';
		//$this->view->actionTemplate = 'latestnews.php';
		$this->_response->setBody ( $this->view->render ( 'site.tpl.php' ) );
	}
	
	
	public function sharenewsAction(){
		
		$news = new NewsFeed();
		$newsArticleId = $this->_request->getParam ( 'id', 1 );
		$newsArticle = $news->selectNewsArticle ( $newsArticleId );
		$data = array ('news_num_shares' => $newsArticle[0]['news_num_shares'] + 1 );
		$news->updateNewsFeed ( $data, $newsArticleId );
		echo "News with id was shared:" . $newsArticleId;
		
	}
	

	

	public function fetchrssheadlines($numfeeds) {
		
		if ($numfeeds == 0) {
			$numfeeds = 5;
		}
		$feedNews = new FeedNews ( );
		$selectedFeeds = $feedNews->selectLatestFeeds ( 0 , $numfeeds ,"default" );
		
		return $selectedFeeds;
	
	}
	
	public function shownewsAction() {
		
		$post = Zend::registry ( 'post' );
		$rssId = trim ( $post->noTags ( 'rsssource' ) );
		$soccerRss = $this->fetchRssHeadLines ( $rssId );
		echo '<ul class="top-news">';
		$cont = 1;
		foreach ( $soccerRss as $item ) {
			if ($cont <= 7) {
				$date1 = strtotime ( $item->pubDate () ) . "<br>"; //date("D,j M Y G:i:s T", ($item->pubDate()));
				$date11 = $this->convert_tz ( $item->pubDate (), 'D,j M Y G:i:s T', 'EDT' );
				//echo $date11;
				$date2 = strtotime ( date ( "D,j M Y G:i:s T" ) );
				$tdy = date ( "D,j M Y G:i:s T" );
				$long = $date2 - $date1;
				$hours = round ( $long / (60 * 60) );
				$remainder = $long % (60 * 60);
				$minutes = round ( $remainder / 60 );
				//echo '>'.$tdy.'>>'.$date2 .'>' . $date1 . '>' . $item->pubDate();
				

				if ($hours >= 1) {
					$hours .= " hours ";
				} else {
					$hours = '';
				}
				if ($minutes >= 1) {
					$minutes .= " minutes ago";
				} else {
					$minutes = '';
				}
				echo "<li>";
				echo "<a href=" . $item->link () . ">" . $item->title () . " </a><strong>Updated:" . $hours . "" . $minutes . "</strong>";
				echo "</li>";
				$cont ++;
			}
		
		}
		echo '<li class="last"><a href="#" title="See more news from around the world">See more news from around the world</a></li>';
		echo '</ul>';
	}
	
	//	public function showFeaturedNewsAction() {
	// 		//change this to News Story
	//		//$this->view->title = $this->title->getPageTitle('',PageTYpe::$_NEWS_MAIN_PAGE);
	//		
	//		$newsFeed = new NewsFeed();
	//		$this->view->newsFeeds = $newsFeed->selectNewsFeeds(10);
	//		$this->view->newsCount = $newsFeed->getTotalNewsCount();
	//		$feedNews = new FeedNews();
	//		$selectedFeeds = $feedNews->selectLatestFeeds(5);
	//		
	//		//Zend_Debug::dump($selectedFeeds);
	//		$this->view->selectedFeeds = $selectedFeeds;
	//		$this->breadcrumbs->addStep('Featured News');
	//		$this->view->breadcrumbs = $this->breadcrumbs;
	//		$this->view->actionTemplate = 'featuredNews.php';
	//		$this->_response->setBody($this->view->render('site.tpl.php'));
	// }
	

//	public function homefeaturednewsAction() {
//		//change this to News Story
//		//$this->view->title = $this->title->getPageTitle('',PageTYpe::$_NEWS_MAIN_PAGE);
//		$numfeeds = ( int ) $this->_request->getParam ( 'numfeeds', 0 );
//
//                //to use with AFP
//                $newsFeed = new NewsFeed ( );
//		$newsFeedPhoto = new NewsFeedPhoto ( );
//
//
//
//		//$cache = $this->getCache();
//		//if (!$feeds = $cache->load('newsHomePage')) {
//			$feeds = $newsFeed->selectNewsFeedsHome ( 0, $numfeeds, 'default' );
//		//	$cache->save($feeds,'newsHomePage');
//	    	//Zend_Debug::dump("Using Non cached data");
//		//} else {
//			//Zend_Debug::dump("Using cached data");
//
//		//}
//		$this->view->newsFeeds = $feeds;
//                $this->view->numberFeeds = $numfeeds;
//		$firstFeed = $feeds [0];
//		//if (!$firstFeedPhotos = $cache->load('firstPhotoNews')) {
//			$firstFeedPhotos = $newsFeedPhoto->selectNewsArticlePhotos ( $firstFeed ["news_id"] );
//			//$cache->save($firstFeedPhotos,'firstPhotoNews');
//	    	//Zend_Debug::dump("Using Non cached data");
//		//} else {
//			//Zend_Debug::dump("Using cached data");
//
//		//}
//
//		$firstFeedThumbPhoto = null;
//		if ($firstFeedPhotos != null) {
//		  $firstFeedThumbPhotoId = $firstFeedPhotos [0] ["photo_id"];
//			$firstFeedThumbPhoto = $firstFeedPhotos [0] ["photo_thumb_file"];
//		}
//		//echo $firstFeedThumbPhoto;
//		$this->view->firstNewsPhotoId = $firstFeedThumbPhotoId;
//		$this->view->firstNewsPhoto = $firstFeedThumbPhoto;
//
//		$this->_response->setBody ( $this->view->render ( 'topfeaturednewsview.php' ) );
//	}
//

	private function loadRssFeed ($url) {
  		try {
			$feed = new Zend_Feed_Rss($url);
  		} catch (Zend_Feed_Exception $e) {
       		echo "feed import failed"; 
        	return null;
 		}
   		return $feed;    
 	}
	
	public function homefeaturednewsAction() {
		$numfeeds = ( int ) $this->_request->getParam ( 'numfeeds', 0 );
		//get news url from DB
		$feed = new Feed ( );
      	//$feedNews = new FeedNews ( );
      	$resultfeeds = $feed->selectFeeds ();
      	$feed_parsed = $this->loadRssFeed($resultfeeds[0]['feed_url']);
      	$this->view->newsFeeds = $feed_parsed;
		$this->view->numberFeeds = $numfeeds;
		
		$this->_response->setBody ( $this->view->render ( 'tophomenewsview.php' ) );
	}

	public function shownewsstoryAction() {
		$view = Zend_Registry::get ( 'view' );
		$newsArticleId = $this->_request->getParam ( 'id', 0 );
		
		
		//$feedPhotos = $newsPhoto->selectNewsArticlePhotos($newsArticleId);
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		//$this->view->featuredNews =$news->getIndividualArticleNews(); 
		$news = new NewsFeed();
		$rowset = $news->selectNewsArticle ( $newsArticleId );
		$newsArticle = $rowset [0];
		
		$view->title = $title->getPageTitle ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
		$view->keywords = $keywords->getMetaKeywords ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
		$view->description = $description->getMetaDescription ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
		
		$view->article = $newsArticle;
		$view->newsMenuSelected = 'featured';

		$nextRowset = $news->selectNextNewsArticle ( $newsArticleId );
		if ($nextRowset != NULL and $nextRowset [0] != NULL) {
			$view->nextArticle = $nextRowset [0];
		} else {
			$view->nextArticle = null;
		}
		$previousRowset = $news->selectPreviousNewsArticle ( $newsArticleId );
		if ($previousRowset != NULL and $previousRowset [0] != NULL) {
			$view->previousArticle = $previousRowset [0];
		} else {
			$view->previousArticle = null;
		}
		$photoRowset = $newsPhoto->selectNewsArticlePhotos ( $newsArticleId );
		//echo $rowset[0] ["news_provider"];
		//Zend_Debug::dump($photoRowset);
		$firstFeedQuickPhoto = null;
		if ($photoRowset != null) {
		  $firstFeedPhotooId = $photoRowset [0] ["photo_id"];
		  $firstFeedQuickPhoto = $photoRowset [0] ["photo_quicklook_file"];
	      $firstFeedQuickPhoto_width = $photoRowset [0] ["photo_quicklook_width"];
	      $FirstFeedQuickPhotoCaption = $photoRowset [0] ["photo_caption"];
		}
		$this->view->PhotoId = $firstFeedPhotooId;
		$this->view->firstNewsQuickPhoto = $firstFeedQuickPhoto;
		$this->view->PhotoWidth = $firstFeedQuickPhoto_width;
		$this->view->firstNewsQuickPhotoCaption = $FirstFeedQuickPhotoCaption;
		$this->view->newsArticleId = $newsArticleId;
		$view->photos = $photoRowset;
		
		//fetch comments per news
		$comment = new Comment ( );
		
		$commentsPerArticle = $comment->findCommentsPerNews ( $newsArticle ['news_afp_id'], null );
		$this->view->comments = $commentsPerArticle;
		$this->view->totalComments = count ( $commentsPerArticle );
		
		 //pagination - getting request variables
        $pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($commentsPerArticle);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->paginator = $paginator;
		
		//update number of reads testing
		
		$data = array ('news_number_reads' => $newsArticle ['news_number_reads'] + 1 );
		$news->updateNewsFeed ( $data, $newsArticleId );
		
		$feedNews = new FeedNews ( );
		$selectedFeeds = $feedNews->selectLatestFeeds ( 0 ,3, "default");
		$this->view->selectedFeeds = $selectedFeeds;
		//rating
		$rating = null;
		if($newsArticle ['news_total_votes'] > 0){
			$rating = round($newsArticle ['news_total_rating_count']/$newsArticle ['news_total_votes'] ,1);
		}else {
			$rating = "0.0";
		}	  
		$this->view->rating = $rating;
		$this->view->totalVotes = $newsArticle ['news_total_votes'];
		//breadcrumbs
		$urlGen = new SeoUrlGen();
		$this->breadcrumbs->addStep ('Featured News', $urlGen->getMainNewsPage(true));
		$this->breadcrumbs->addStep ($newsArticle['news_headline'] );
		$this->view->breadcrumbs = $this->breadcrumbs;
		
		$view->actionTemplate = 'newsStory.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	
	  public function shownewsstorypfAction () {
        
	  	  $view = Zend_Registry::get ( 'view' );
	  	  $newsArticleId = $this->_request->getParam ( 'id', 0 );
	  	  $news = new NewsFeed ( );
	  	  
	  	  $title = new PageTitleGen ( );
		  $keywords = new MetaKeywordGen ( );
		  $description = new MetaDescriptionGen ( );
		  
		  $rowset = $news->selectNewsArticle ( $newsArticleId );
		  $newsArticle = $rowset [0];
		  
		  $view->title = $title->getPageTitle ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
		  $view->keywords = $keywords->getMetaKeywords ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
		  $view->description = $description->getMetaDescription ( $rowset [0] ["news_headline"], PageType::$_NEWS_ARTICLE_PAGE );
	 	  $view->article = $newsArticle;
		  $view->referer = $_SERVER['HTTP_REFERER'];
		  $this->_response->setBody ( $view->render ( 'newsStorypf.php' ) );
  }	
	

	public function showmoretagscloudAction() {
		$view = Zend_Registry::get ( 'view' );
		
		//fetch Cloud tags
		$tags = new Tags();
		$tags = $tags->findTags();
		$this->view->tags = $tags;
		
		$view->actionTemplate = 'newsTagCloud.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function sendnewsbyemailAction(){
		$urlGen = new SeoUrlGen();
		$news = new NewsFeed();
		$config = Zend_Registry::get ( 'config' );
		$from = $this->_request->getParam ( 'from');
		if(trim($from) == '' ){
			$from = $config->email->confirmation->from;
		}
		$subject = $this->_request->getParam ('subject', "" );
		$message = $this->_request->getParam ('message', "" );
		$email = $this->_request->getParam ( 'to', "default" );
		$newsId = $this->_request->getParam ( 'id', 0 );
		$rowset = $news->selectNewsArticle ( $newsId );
	  	$newsArticle = $rowset [0];
		$context = Zend_Registry::get('contextPath');
	  	$newsUrl = $_SERVER['SERVER_NAME'] . $context  . $urlGen->getNewsArticlePageUrl($newsArticle["news_headline"], $newsArticle["news_id"], true);
		//Zend_Debug::dump($newsUrl);
		
		//let's get the email of the new friend to send a mail
		$session = new Zend_Session_Namespace('userSession');
		
		$screenName = $session->email!= null?$session->userName :$from;
	  	/*Send Mail to Friend for Request*/
		$mail = new SendEmail();
		$mail->set_from($from);
		$mail->set_to( $email);
		$mail->set_subject($subject);
		$mail->set_template('emailarticle');
		$variablesToReplaceEmail = array ('username' => $screenName, 
										  'newsArticleUrl' => $newsUrl,
										  'headLineNews' =>$newsArticle['news_headline'],
										  'customMessage' => $message);
		$mail->set_variablesToReplace($variablesToReplaceEmail);
		$mail->sendMail();
		/*Send Mail to Friend for Request*/
	  	
	}
	
	public function showexternalnewsAction(){
		$view = Zend_Registry::get ( 'view' );
		$newsId = $this->_request->getParam ( 'id', 0 );
		$feedNews = new FeedNews();
		
		$newsResult = $feedNews->find($newsId);
		
		$view->urlNewsUrl = $newsResult[0]->feed_news_url;
		$view->urlNewsTitle = $newsResult[0]->feed_news_title;
		$view->title = $newsResult[0]->feed_news_title . " from " . $newsResult[0]->feed_news_source. "found on goalface.com";
		$view->actionTemplate = 'externalnews.php';
		$this->_response->setBody ( $view->render ( 'siteExternalNews.tpl.php' ) );
		
	}
	
	public function editnewscommentAction(){
		
		$mc = new Comment ( );
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$dataEditted = $this->_request->getParam ( 'dataEditted', null );
		
		$mc->updateComment($commentId , $dataEditted );
		
	}
	
	public function removenewscommentAction() {
		
		$mc = new Comment ( );
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		
		$userWhoDeletesComment = 1; 
		
		$mc->updateDeleteComment($commentId , $userWhoDeletesComment );
		
	}
	
	public function reportabuseAction(){
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$dataReport = $this->_request->getParam ( 'dataReport', null );
		$reportType = $this->_request->getParam ( 'reportType', null );
		$report = new Report();
		$data = array ('report_comment_id' => $commentId, 
					   'report_text' 	   => $dataReport,
					   'report_type'       => $reportType,
					   'report_comment_type'       => Constants::$_REPORT_COMMENT_NEWS
			  		   );
		
		$report->insert ( $data );
		
	}
	

}
?>
