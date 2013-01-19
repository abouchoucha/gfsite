<?php
/**
 * AdminController
 * 
 * @author Kwasi Brown
 * @version  1.0
 */

require_once 'util/Common.php';
require_once 'util/BreadCrumbs.php';
require_once 'util/Text/Password.php';

class AdminController extends Zend_Controller_Action {
	public $breadcrumbs;
	private $siteUserRegister = 'siteUserRegister.tpl.php';
	
	function init() {
		
		/*$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}*/
		
		$this->view = Zend_Registry::get ( 'view' );
		Zend_Loader::loadClass ( 'AdminUser' );
		Zend_Loader::loadClass ( 'AccountComment' );
		Zend_Loader::loadClass ( 'Report' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Zend_Feed' );
		Zend_Loader::loadClass ( 'Pagination' );
		$this->breadcrumbs = new Breadcrumbs ();
		$this->breadcrumbs->addStep ( 'Dashboard', $this->getUrl ( null, 'admin' ) );
		$this->view->actualURL = $this->_request->getPathInfo ();
	}
	
	public function getUrl($action = null, $controller = null) {
		$url = rtrim ( $this->getRequest ()->getBaseUrl (), '/' );
		$url .= $this->_helper->url->simple ( $action, $controller );
		
		return $url;
	}
	
	function indexAction() {
	
	}
	
	private function generatepasswd() {
		// create password
		$tp = new Text_Password ();
		return $tp->create ();
	}
	
	public function editPlayerAction() {
		$view = Zend_Registry::get ( 'view' );
		$playerId = $this->_request->getParam ( 'id', 0 );
		$player = new Player ();
		$rowset = $player->find ( $playerId );
		if ($rowset->valid ()) {
			$row = $rowset->current ();
		}
		
		$view->playernameshort = $row->player_name_short;
		$view->playerfname = $row->player_firstname;
		$view->playerlname = $row->player_lastname;
		$view->playerposition = $row->player_position;
		$view->playerdob = $row->player_dob;
		$view->playerdobcity = $row->player_dob_city;
		$view->playershortbio = $row->player_short_bio;
		
		$view->actionTemplate = 'editplayerform.tpl.php';
		$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
	}
	
	public function manageusersAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		$user = new AdminUser ();
		$view = Zend_Registry::get ( 'view' );
		
		$flaggedusers = $user->findFlaggedUsers ();
		
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		$paginator = Zend_Paginator::factory ( $flaggedusers );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 10 );
		$view->paginator = $paginator;
		$view->actionTemplate = 'adminUserManager.php';
		$this->breadcrumbs->addStep ( 'Manage Users' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$this->_response->setBody ( $view->render ( 'admin.tpl.php' ) );
		
	}
	
	public function usernamerecordAction() {
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		$userId = $this->_request->getParam ( 'id', 0 );
		$user = new AdminUser ();
		$rowset = $user->getFlaggedUserDetails ( $userId );
		//Zend_Debug::dump($rowset);
		$view = Zend_Registry::get ( 'view' );
		
		$view->uid = $rowset->user_id;
		$session = new Zend_Session_Namespace ( 'adminSession' );
		$session->userId = $rowset->user_id;
		$view->screen_name = $rowset->screen_name;
		$view->email = $rowset->email;
		$view->first_name = $rowset->first_name;
		$view->last_name = $rowset->last_name;
		$view->registration_date = $rowset->registration_date;
		$view->date_update = $rowset->date_update;
		
		$this->breadcrumbs->addStep ( 'Manage Users', '/admin/manageusers/', true );
		$this->breadcrumbs->addStep ( $rowset->screen_name . ' Record' );
		$this->view->breadcrumbs = $this->breadcrumbs;
		$view->actionTemplate = 'adminUserRecord.php';
		$this->_response->setBody ( $view->render ( 'admin.tpl.php' ) );
	}
	
	public function edituserAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( 'adminSession' );
		$userId = $session->userId;
		$view->userId = $userId;
		$formToShow = null;
		$editAction = $this->_request->getParam ( "editaction" );
		if ($editAction == 'complaint') {
			$formToShow = 'editUserAccountPlaceholder.php';
		} else if ($editAction == 'priveleges') {
			$formToShow = 'editUserPrivelges.php';
		} else if ($editAction == 'account') {
			$formToShow = 'editUserAccountPlaceholder.php';
		} else if ($editAction == 'activity') {
			$formToShow = 'editUserAccountPlaceholder.php';
		} else if ($editAction == 'notes') {
			$formToShow = 'editUserAccountNotes.php';
		}
		$this->_response->setBody ( $view->render ( $formToShow ) );
	}
	
	public function findusersAction() {
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		$view = Zend_Registry::get ( 'view' );
		$queryString = ( string ) $this->_request->getParam ( 'q', 0 );
		$adminUser = new AdminUser ();
		$foundProfiles = $adminUser->searchUserProfiles ( $queryString );
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		
		$paginator = Zend_Paginator::factory ( $foundProfiles );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 10 );
		$view->paginator = $paginator;
		$view->showFilters = "n";	
		$this->_response->setBody ( $view->render ( 'adminUserManager.php' ) );
	}
	
	public function showaccountnotesAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$view = Zend_Registry::get ( 'view' );
		$ac = new AccountComment ();
		$id = ( int ) $this->_request->getParam ( 'id', 0 );
		$accountcomments = $ac->findCommentsByUser ( $id );
		$totalAcctComments = $ac->fetchAll ( "account_id=" . $id );
		$this->view->totalMatchComments = count ( $totalAcctComments );
		//$view->matchcomments = $matchcomments;
		

		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$paginator = Zend_Paginator::factory ( $accountcomments );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 5 );
		$view->paginator = $paginator;
		$view->user_id = $id;
		
		$this->_response->setBody ( $view->render ( 'accountnote.php' ) );
	
	}
	
	public function addaccountnoteAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		

		$commentText = $this->_request->getPost ( "comment" );
		$acctTocomment = $this->_request->getPost ( "idtocomment" );
		$userNameTocomment = $this->_request->getPost ( "screennametocomment" );
		$comment_create = trim ( date ( "Y-m-d H:i:s" ) );
		$data = array ('account_id' => $acctTocomment, 'admin_id' => 100000, 'note' => $commentText, 'date_added' => $comment_create );
		
		$comment = new AccountComment ();
		$comment->insert ( $data );
		
		$this->_redirect ( "/admin/showaccountnotes/id/" . $acctTocomment );
	}
	
	public function resetpasswdAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$userId = $this->_request->getParam ( 'id', 0 );
		$user = new AdminUser ();
		$password = $this->generatepasswd ();
		
		//generate hash and hash1 from password entered
		$common = new Common ();
		$hash = $common->generateHash ( $password, null );
		$passwordSha = sha1 ( $password );
		
		$data = array ('password' => $passwordSha, 'salt' => $hash );
		$rows_affected = $user->updateUserbyId ( $userId, $data );
		if ($rows_affected == 1) {
			$msg = "The password has been set to <b>" . $password . "</b>.";
		} else {
			$msg = "An problem occurred while reseting the password.";
		}
		echo $msg;
	}
	
	public function showaccountactivityAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$view = Zend_Registry::get ( 'view' );
		$userId = $this->_request->getParam ( 'id', 0 );
		
		//User Feed - Activity
		$activity = new Activity ();
		$activitiesPerUser = $activity->findMyActivities ( $userId );
		$view->activitiesPerUser = $activitiesPerUser;
		$view->totalFriendActivities = count ( $activitiesPerUser );
		
		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$paginator = Zend_Paginator::factory ( $activitiesPerUser );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 5 );
		$view->paginator = $paginator;
		//$view->user_id = $id;
		

		$this->_response->setBody ( $view->render ( 'accountactivity.php' ) );
	}
	
	public function showcomplaintsAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$view = Zend_Registry::get ( 'view' );
		$rpt = new Report ();
		$id = ( int ) $this->_request->getParam ( 'id', 0 );
		$type = $this->_request->getParam ( 'type', 0 );
		
		$this->view->type = $type;
		
		if ($type == 0) { //All Records
			$complaints = $rpt->findAllComplaints ( $id );
			$this->view->totalComplaints = count ( $complaints );
		} elseif ($type == 1) { //Complaints Against User
			$complaints = $rpt->findComplaintsPerUser ( $id );
			$this->view->totalComplaints = count ( $complaints );
		} elseif ($type == 2) { //Complaints by User
			$complaints = $rpt->findMyComplaints ( $id );
			$this->view->totalComplaints = count ( $complaints );
		}
		//$view->matchcomments = $matchcomments;
		

		$pageNumber = $this->_request->getParam ( 'page' );
		if (empty ( $pageNumber )) {
			$pageNumber = 1;
		}
		$paginator = Zend_Paginator::factory ( $complaints );
		$paginator->setCurrentPageNumber ( $pageNumber );
		$paginator->setItemCountPerPage ( 10 );
		$view->paginator = $paginator;
		//$view->user_id = $id;
		

		$this->_response->setBody ( $view->render ( 'accountcomplaints.php' ) );
	
	}
	
	public function reportcomplaintAction() {
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		$commentId = $this->_request->getParam ( 'id', 0 );
		$elementid = $this->_request->getParam ( 'elementid', 0 );
		$dataReport = $this->_request->getParam ( 'dataReport', null );
		$reportType = $this->_request->getParam ( 'reportType', null );
		$typeofcomment = $this->_request->getParam ( 'typeofcomment', null );
		$to = $this->_request->getParam ( 'reportTo', null );
		$report = new Report();
		$data = array ('report_comment_id' => $commentId, 
					   'report_text' 	   => $dataReport,
					   'report_reported_to' => $to,
					   'report_type'       => $reportType
						);
		
		$report->insert ( $data );
		
	}
	
	public function suspendaccountAction(){
		
		$session = new Zend_Session_Namespace ( 'userAdminSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/admin/login" );
		}
		
		//suspend Account
		$userId = (int)$this->_request->getParam ( 'userId', null );
		$suspended = (int)$this->_request->getParam ( 'suspendedTo', null );
		// 0 1 week , 1 2 weeks , 2 1 month
		$newDate = new Zend_Date();
		$toDate = null;
		if($suspended == 0){
			$toDate = $newDate->addDay(7);
		}else if($suspended == 1){
			$toDate = $newDate->addDay(14);
		}else if($suspended == 2){ 
			$toDate = $newDate->addDay(30);
		}
		//Zend_Debug::dump($newDate->toString ( 'yyyy-MM-dd HH:mm:ss'));
		//Zend_Debug::dump($toDate->toString ( 'yyyy-MM-dd HH:mm:ss'));
		$user = new User();
		$today = new Zend_Date();
		$data = array ('suspended' => '1',
						'suspended_from' => trim ( date('Y-m-d H:i:s',strtotime($today))),
						'suspended_to' => trim ( date('Y-m-d H:i:s',strtotime($toDate))),
						);
		$user->updateUserById($userId ,$data);
		
		
	}
	
	public function loginAction(){
		
		$view = Zend_Registry::get ( 'view' );
		$view->actionTemplate = 'loginFormAdmin.php';
		
		$this->_response->setBody ( $view->render ( 'siteUserRegister.tpl.php' ) );
		
	}
	
	public function dologinAction() {
		
		$filter = new Zend_Filter_StripTags ( );
		$common = new Common ( );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "Welcome to GoalFace";
		$sessionCookie = new Zend_Session_Namespace ( 'userSessionCookie' );
		$username = trim ( $filter->filter ( $this->_request->getPost ( 'username' ,$sessionCookie->emailcookie ) ) );
		$psw = trim ( $filter->filter ( $this->_request->getPost( 'password' , $sessionCookie->pswcookie ) ) );
		$remember = trim ( $filter->filter ( $this->_request->getPost ( 'persistent' ,"1" )) );
		
		
		if ($username != "admin" or $psw != "Pa55w0rd") {
			$errorMsg =  " The UserName you entered is not in our records.Please check the email address you entered. ";
			$errorMsg.= " Are you trying to <a href=' " . Zend_Registry::get ( "contextPath" ) . "/register'>register for a new account";
			$jserror = 'jQuery(\'#MainErrorMessageLogin\').html(\'The login information you entered is not valid.Please try again.\');';
			$jserror .= 'jQuery(\'#ErrorMessagesLogin\').removeClass(\'ErrorMessages\').addClass(\'ErrorMessagesDisplay\');';
			$jserror .= 'jQuery(\'#systemWorkingLoginForm\').hide();';
			echo $jserror;
		
			return;
		}else {
			$session = new Zend_Session_Namespace("userAdminSession");
			//$session->setExpirationSeconds(120);
			$session->screenName = "adminUser";
			echo 'window.location = "' .Zend_Registry::get ( "contextPath" ) . '/admin/manageusers'. '"';
			
		}
	}
	
	public function dologoutAction() {
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace("userAdminSession");
		if($session->screenName == null){
			$this->_redirect('/admin/login');
		}
		
		Zend_Session::destroy ( true, true );
		$this->_redirect ( '/admin/login' );
		
	}
	
}
?>
