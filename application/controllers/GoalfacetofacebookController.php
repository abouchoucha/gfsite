<?php
require_once 'application/controllers/util/Constants.php';
require_once 'application/controllers/util/Common.php';
require_once 'scripts/seourlgen.php';

class GoalfacetofacebookController extends Zend_Controller_Action {

	public $region_name = null;
	public $regional_Heading = null;
	public $country = null;
	private static $regionGroupNames = null;
	private static $regionGroupNameHeader = null;
	private static $urlGen = null;
	
	function init() {
		Zend_Loader::loadClass ( 'LeagueCompetition' );
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'Season' );
		Zend_Loader::loadClass ( 'Round' );
		Zend_Loader::loadClass ( 'Groupp' );
		Zend_Loader::loadClass ( 'Ranking' );
		Zend_Loader::loadClass ( 'UserLeague' );
		Zend_Loader::loadClass ( 'UserTeam' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'PageTitleGen' );
		Zend_Loader::loadClass ( 'MetaKeywordGen' );
		Zend_Loader::loadClass ( 'MetaDescriptionGen' );
		Zend_Loader::loadClass ( 'NewsFeed' );
		Zend_Loader::loadClass ( 'Region' );
		Zend_Loader::loadClass ( 'WorldCupPlayerStats' );
		Zend_Loader::loadClass ( 'Tournament' );
		//Zend_Loader::loadClass ( 'Standing' );
		Zend_Loader::loadClass ( 'GoalserveStanding');
		
		$this->view = Zend_Registry::get ( 'view' );
		$config = Zend_Registry::get ( 'config' );
		
		self::$urlGen = new SeoUrlGen ();
		
		parent::init ();
	
		
	}

	public function homeAction() {
		$view = Zend_Registry::get ( 'view' );
		
		$this->_response->setBody ( $this->view->render ( 'fb_home.php' ) );	
	}
	
	public function invitefriendsAction() {
		$view = Zend_Registry::get ( 'view' );
		
		$this->_response->setBody ( $this->view->render ( 'invitefriendsfb.php' ) );	
	}
	
	//using new feed
	public function showtablesAction() {
		$view = Zend_Registry::get ( 'view' );
		$leagueid = $this->_request->getParam ( 'compid', 0 );
		//Competition/League Information
		$lea_comp = new LeagueCompetition ();
		$competitionRow = $lea_comp->findCompetitionById ( $leagueid );
		//get standing information from new feed
		$standing = new GoalserveStanding();
		$rowstanding = $standing->fetchRow ( 'competition_id = ' . $leagueid );
		
		
		if ($competitionRow ['format'] == 'Domestic league') {

		} 
		
		if ($competitionRow ['format'] == 'International cup') {
			//Zend_Debug::dump($rowstanding);
		} 
	}
	
	
	
	
	
}
?>
