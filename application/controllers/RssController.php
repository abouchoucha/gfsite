<?php
require_once 'GoalFaceController.php';
require_once 'scripts/seourlgen.php';
require_once 'util/Common.php';

class RssController extends GoalFaceController  {
	
	
	function init() 
	{
		
		parent::init();
		Zend_Loader::loadClass ( 'Player' );
		Zend_Loader::loadClass ( 'TeamSeason' );
		Zend_Loader::loadClass ( 'UserPlayer' );
		Zend_Loader::loadClass ( 'LeagueCompetition' );
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'Comment' );
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'TeamSeasonStats' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Pagination' );
		Zend_Loader::loadClass ( 'PageType' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'MetaKeywordGen' );
		Zend_Loader::loadClass ( 'MetaDescriptionGen' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
		Zend_Loader::loadClass ( 'PlayerActivity' );
		Zend_Loader::loadClass ( 'User' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'PlayerSeason');
		Zend_Loader::loadClass ( 'Comment' );
	}
	
	/**
	 * Gets a list of countries and returns the result in JSON format
	 */
	public function getcountriesjsonAction()
	{
		$league = new Competitionfile ();
		$rowCountry = $league->selectLeaguesByCountry ();	
		$this->_helper->viewRenderer->setNoRender(true);
		echo  Zend_Json::encode(array("countries"=>$rowCountry));
	}
	
/**
	 * Gets a list of countries and regions for leagues and tournaments and returns the result in JSON format
	 */
	public function getleaguecountriesjsonAction()
	{
		$country= new Country();
		//$rowCountry = $country->selectCountriesScoreboard();
		$rowCountry = $country->selectCountriesScoreboardActive();	
		$this->_helper->viewRenderer->setNoRender(true);
		echo  Zend_Json::encode(array("countries"=>$rowCountry));
	}
	
	public function indexAction()
	{
		$view = Zend_Registry::get ( 'view' );;
		$session = new Zend_Session_Namespace('userSession');
	
		$team = new Team();
		$player = new Player();
		$lea_comp = new LeagueCompetition ();
	
		if ($session->email != null) {
			$this->view->popularPlayers = $player->findPopularPlayersUser(12,$session->userId);
			$this->view->popularTeams = $team->findPopularTeamsUser (12,$session->userId);
			$this->view->popularLeagues = $lea_comp->findPopularCompetitions (12,$session->userId);
		} else {
			$this->view->popularPlayers = $player->findPopularPlayers (12);
			$this->view->popularTeams = $team->findPopularTeams (12);
			//$this->view->popularLeagues = $lea_comp->findFeaturedCompetitions (12);
			$this->view->popularLeagues = $lea_comp->findPopularCompetitions (12,null);
		}
			// Declare title, keywords, and description objects
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		
		$view->title = $title->getPageTitle ( '', PageType::$_UPDATES_ALERTS_SUBSCRIBE );
		$view->keywords = $keywords->getMetaKeywords ( '', PageType::$_UPDATES_ALERTS_SUBSCRIBE );
		$view->description = $description->getMetaDescription ( '', PageType::$_UPDATES_ALERTS_SUBSCRIBE );

		//Get list of countries to change players, and team names
		$league = new Competitionfile ();
		$rowCountry = $league->selectLeaguesByCountry ();	
		$view->countries = $rowCountry;
		$view->actionTemplate = 'rss_gallery.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
	}
	
	public function refreshpopularplayersAction()
	{
		$view = Zend_Registry::get ( 'view' );
    	$player = new Player();
    	$session = new Zend_Session_Namespace('userSession');
    	if ($session->email != null) 
    	{
    		$this->view->popularPlayers = $player->findPopularPlayersUser(12,$session->userId);
    	}
    	else
    	{
    		$this->view->popularPlayers = $player->findPopularPlayers (12);
    	}
    	$this->_response->setBody ( $this->view->render ( 'viewpopularplayersajax.php' ) );
	}
	
	public function refreshpopularteamsAction()
	{
		$view = Zend_Registry::get ( 'view' );
    	$team = new Team();
    	$session = new Zend_Session_Namespace('userSession');
    	if ($session->email != null) 
    	{
    		$this->view->popularTeams = $team->findPopularTeamsUser (12,$session->userId);
    	}
    	else
    	{
    		$this->view->popularTeams = $team->findPopularTeams (12);
    	}
    	$this->_response->setBody ( $this->view->render ( 'viewpopularteamsajax.php' ) );
	}
	
	public function refreshpopularleaguesAction()
	{
		$view = Zend_Registry::get ( 'view' );
    	$lea_comp = new LeagueCompetition ();
    	$session = new Zend_Session_Namespace('userSession');
    	if ($session->email != null) 
    	{
    		$this->view->popularLeagues = $lea_comp->findPopularCompetitions (12,$session->userId);
    	}
    	else
    	{
    		$this->view->popularLeagues = $lea_comp->findPopularCompetitions (12,null);
    	}
    	$this->_response->setBody ( $this->view->render ( 'viewpopularleaguesajax.php' ) );
	}
	

	
	
	
}