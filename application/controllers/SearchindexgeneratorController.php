<?php
require_once 'application/controllers/GoalFaceController.php';
require_once 'application/controllers/util/index/sitemapgen.php';
require_once 'application/controllers/util/index/searchIndexBuilder.php';

class SearchIndexGeneratorController extends GoalFaceController {
	
	private static $config;
	private static $contextPath;
	private static $appDirectory;
	private static $serverName;
	
	public function init() {
		self::$config = Zend_Registry::get ( 'config' );
		
		self::$appDirectory = self::$config->path->index->creator->directory;
		self::$contextPath = Zend_Registry::get ( 'contextPath' );
		self::$serverName = $_SERVER ['SERVER_NAME'];
		
		Zend_Registry::set ( 'playerSiteMapFile', '/sitemap-players.xml' );
		Zend_Registry::set ( 'leaguesSiteMapFile', '/sitemap-leagues.xml' );
		Zend_Registry::set ( 'clubsSiteMapFile', '/sitemap-clubs.xml' );
		Zend_Registry::set ( 'scoresSiteMapFile', '/sitemap-scores.xml' );
		Zend_Registry::set ( 'profilesSiteMapFile', '/sitemap-profiles.xml' );
		Zend_Registry::set ( 'newsSiteMapFile', '/sitemap-news.xml' );
		Zend_Registry::set ( 'photosSiteMapFile', '/sitemap-photos.xml' );
	
	}
	
	public function generateprofilessitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generateProfilesSiteMapFile ( self::$appDirectory );
		echo "sitemap-profiles.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	
	public function generateplayerssitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generatePlayersSiteMapFile ( self::$appDirectory );
		echo "sitemap-players.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	
	public function generateteamssitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generateClubsSiteMapFile ( self::$appDirectory );
		echo "sitemap-clubs.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	
	public function generatecompetitionsitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generateLeaguesSiteMapFile ( self::$appDirectory );
		echo "sitemap-leagues.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	public function generatenewssitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generateNewsSiteMapFile ( self::$appDirectory );
		echo "sitemap-news.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	
	public function generatephotossitemapAction() {
		$sitegenerator = new SiteMapGen ( );
		$sitegenerator->generatePhotosSiteMapFile ( self::$appDirectory );
		echo "sitemap-photos.xml generated!!";
		$this->_helper->viewRenderer->setNoRender ();
	}
	public function createprofilesindexAction() {
		
		// create index
		try {
			// change to here where we will store the indexes on the server outside the application page. 
			Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ( ) );
			$index = Zend_Search_Lucene::open ( self::$config->path->search->index );
			$indexbuilder = new SearchIndexBuilder ( );
			$indexbuilder->buildIndexByCategory ( $index, 'profiles', self::$appDirectory, self::$contextPath, self::$serverName );
		
		} catch ( Zend_Search_Lucene_Exception $ex ) {
			//echo $ex->getMessage();
			//echo $ex->getTraceAsString();
			//echo "First Exception: " . $ex;
			try {
				// change to here where we will store the indexes on the server outside the application page. 
				$index = Zend_Search_Lucene::create ( self::$config->path->search->index );
			} catch ( Zend_Search_Lucene_Exception $e ) {
				echo 'Unable to Create Index.', $e->getMessage ();
			}
		}
		
		$this->_helper->viewRenderer->setNoRender ();
	}
	
	public function createplayersindexAction() {
		
		echo "Start Time: " . date ( "F j, Y, g:i a s" );
		Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ( ) );
		$index = Zend_Search_Lucene::open ( self::$config->path->search->index );
		$indexbuilder = new SearchIndexBuilder ( );
		$indexbuilder->buildIndexByCategory ( $index, 'players', self::$appDirectory, self::$contextPath, self::$serverName );
		echo "End Time: " . date ( "F j, Y, g:i a s" );
		$this->_helper->viewRenderer->setNoRender ();
	
	}
	
	public function createteamsindexAction() {
		
		echo "Start Time: " . date ( "F j, Y, g:i a s" );
		Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ( ) );
		$index = Zend_Search_Lucene::open ( self::$config->path->search->index );
		$indexbuilder = new SearchIndexBuilder ( );
		$indexbuilder->buildIndexByCategory ( $index, 'club', self::$appDirectory, self::$contextPath, self::$serverName );
		echo "End Time: " . date ( "F j, Y, g:i a s" );
		$this->_helper->viewRenderer->setNoRender ();
	
	}
	
	public function createcompetitionsindexAction() {
		
		echo "Start Time: " . date ( "F j, Y, g:i a s" );
		Zend_Search_Lucene_Analysis_Analyzer::setDefault ( new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ( ) );
		$index = Zend_Search_Lucene::open ( self::$config->path->search->index );
		$indexbuilder = new SearchIndexBuilder ( );
		$indexbuilder->buildIndexByCategory ( $index, 'league', self::$appDirectory, self::$contextPath, self::$serverName );
		echo "End Time: " . date ( "F j, Y, g:i a s" );
		$this->_helper->viewRenderer->setNoRender ();
	
	}

        // pass parameter  /deletedocument/category/{parvalue} (news,players,teams, etc)
	public function deletedocumentAction() {
		try {
			//Open the index for reading.
			$index = Zend_Search_Lucene::open ( self::$config->path->search->index);
			//Create the term to delete the documents.
			$category = $this->_request->getParam ( 'category', "" );
			if($category == ''){
				echo 'Enter category to delete';
				return;
			}
			$hits = $index->find ( 'category:' . $category );
			foreach ( $hits as $hit ) {
				$index->delete ( $hit->id );
			}
			$index->commit ();
		} catch ( Zend_Search_Exception $e ) {
			echo $e->getMessage ();
		}
		echo 'Deletion completed<br/>';
		echo 'Total documents: ' . $index->numDocs ();
		//Suppress the view
		$this->_helper->viewRenderer->setNoRender ();
	}

}

?>