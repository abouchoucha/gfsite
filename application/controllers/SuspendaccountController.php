<?php
require_once 'application/models/Motivesuspendaccount.php';
require_once 'application/models/User.php';
require_once 'application/models/Suspendaccount.php';

class SuspendaccountController extends Zend_Controller_Action {
	
	private static $logger;	
	
	function init() {
		self::$logger = Zend_Registry::get("logger");
	}
	
	function getmotivesAction(){
	
		$motivesSA = new Motivesuspendaccount();
		$allmotives = $motivesSA->findMotivesSuspendAccount();

		foreach ( $allmotives as $motives ) {
			echo "<option value='".$motives['motivesuspendaccount_id']."'>".$motives['motivesuspendaccount_text']."</option>";
		}
		
	}
	
	function usersuspendaccountAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( "userSession" );
		
		$user = new User ();
		$data = array ('last_logout' => trim ( date ( "Y-m-d H:i:s" ) ) );
		$user->updateUser ( $session->email, $data );
		
		$data = array ('last_activity' => 0, 'user_enabled' =>0 );
		$user->updateUser ( $session->email, $data );
		
		$rowset = $user->findUniqueUser ( $session->email );
		
		echo $rowset['user_id']." - ".$this->_request->getPost('motiveId');
		
		$dataSuspend = array('user_id' => (int)$rowset['user_id'],
            'motivesuspendaccount_id' => (int)$this->_request->getPost('motiveId'),
            'suspendaccount_date' => date( "Y-m-d H:i:s" ),
            'suspendaccount_enabled' => 1
        );

		$dbSuspend = new Suspendaccount();
		$dbSuspend->insert($dataSuspend);
		
		Zend_Session::destroy ( true, true );
		
		$view->title = "Please Login";
		$view->errorLogin = "";
		$this->_redirect ( '/index/index/logout/true' );
		
	}
	
}
?>