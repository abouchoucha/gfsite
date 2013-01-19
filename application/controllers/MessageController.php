<?php
require_once 'application/controllers/GoalFaceController.php';
require_once 'application/controllers/util/Constants.php';
require_once 'application/models/Message.php';
require_once 'application/models/User.php';
require_once 'application/models/Activity.php';
require_once 'application/models/UserFriend.php';
require_once 'scripts/seourlgen.php';
require_once 'util/Common.php';
require_once 'util/SendEmail.php';
require_once 'application/controllers/util/Constants.php';

class MessageController extends GoalFaceController   {

	private $siteMessage = 'siteMessageView.tpl.php';
	private static $urlGen = null;
	 
	function init(){
		Zend_Loader::loadClass('Message');
		Zend_Loader::loadClass('User');
		Zend_Loader::loadClass('UserFriend');
		Zend_Loader::loadClass('Zend_Filter_StripTags');
		Zend_Loader::loadClass('Zend_Debug');
		Zend_Loader::loadClass('Zend_Controller_Request_Http');
		self::$urlGen = new SeoUrlGen ();
		parent::init ();
		$this->updateLastActivityUserLoggedIn();
	}

	function indexAction(){
		$view = Zend_Registry::get('view');
		$sess = new Zend_Session_Namespace('userSession');
		$param1 = $sess->userId;
		
		if ($sess->screenName == null) {
			$referrer = $_SERVER['REQUEST_URI'];
			$sess->referrer = $referrer;
			$this->_redirect ( "/sign-in");
			return;
		}
		
		$type = $this->_request->getParam('type');
		$statusFilter = $this->_request->getParam('status');
		$view->status = $statusFilter;
		$mess = new Message(); 
		//pagination params

		$sumaArr = $mess->doCountMessagesbyUser($param1);
		$cantSent = $mess->doCountSentMessagesbyUser($param1);
		$cantReq = $mess->doCountRequestMessagesbyUser($param1);
		$cantDel = $mess->doCountDeletedMessagesbyUser($param1);
		$arrCants = array("cantSent"=> $cantSent[0]["total"], "cantReq"=> $cantReq[0]["total"], "cantMess"=> $sumaArr[0]["total"] , "cantDel"=> $cantDel[0]["total"]);
		
		if($sumaArr[0]["total"] == 0){
			$sess->newMessages = 0;
		}
		
		$messageType = null;
		$messagesubType = null;
		$status = 'n';
		if($type == Constants::$_MESSAGE_INBOX){
			$messagesubType = '0';
			if($statusFilter != 'all'){
				$status = $statusFilter;
			}
			 
		}else if($type == Constants::$_MESSAGE_SENT){
			$messagesubType = '1';
		/*}else if($type == Constants::$_MESSAGE_REQUEST){
			$messageType = '8'; */
		}else if($type == Constants::$_MESSAGE_DELETED){
			$status = '5';
		}	
		
		$results = $mess->findMessagesByType($param1 , $messageType , $messagesubType , $status);
		
		$states = array(array("id_description"=>3, "description"=>"Read"), 
						array("id_description" => 4, "description"=>"Pending") ,
						array("id_description" => 12, "description"=>"Responded"),
						array("id_description" => 10, "description"=>"Accepted"),
						array("id_description" => 11, "description"=>"Rejected"));
		
		
		$sentok = $this->_request->getParam('sent');
		
		//ZF paginator
		$pageNumber = $this->_request->getParam('page');
        if (empty($pageNumber)){
            $pageNumber = 1;
        }
        $paginator = Zend_Paginator::factory($results);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($this->getNumberOfItemsPerPage());
        $this->view->paginator = $paginator;
        //Zf Paginator
		
		$view = Zend_Registry::get('view');
		$view->sentok = $sentok;
		
		$view->states = $states;
		$view->cants = $arrCants;
		$view->title = "GoalFace.com | Inbox Messages";
		$view->messageType = $type;
		$view->actionTemplate = 'messagesIndex.php';
		$this->_response->setBody($view->render('site.tpl.php'));

	}

	
	public function showmessagedetailAction(){

		$view = Zend_Registry::get('view');
		$idmessage = $this->_request->getParam ( 'message', 0 );
		$type = $this->_request->getParam ( 'type', null );
		$mess = new Message();
		//message update to 'read' once it's opened if type es private message
		
		$results = $mess->findUniqueMessage($idmessage);	
		$res = $results[0];
		
		if($res['typedescription'] == "private message"){
			$data = array('message_status' => 3);
			$mess->updateMessage($idmessage, $data);
		}
		
//		if($type == "sent"){
//			$results = $mess->findUniqueMessageReply($idmessage);
//		}else{
//			$results = $mess->findUniqueMessage($idmessage);	
//		}
		
		
		$view->message = $res;
		$view->messagetype = $type;
		
		$view = Zend_Registry::get ( 'view' ) ;
		$this->_response->setBody ( $view->render ( 'messageDetail.php' ) ) ;

	}

	function gocomposeAction(){
		$view = Zend_Registry::get ( 'view' );
     	$session = new Zend_Session_Namespace ( 'userSession' );
     	if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		$view->title = "GoalFace.com | Compose Message";
		
		$userName = $this->_request->getParam('username');
		$messageId = $this->_request->getParam('messageid' , null);
		$currentUser = null;
		$user = new User();
		if($userName != ''){
			$currentUser = $user->fetchRow ( 'screen_name = "' . $userName . '"' );
			$this->view->name = $currentUser->first_name ." " .$currentUser->last_name;
			$this->view->userid = $currentUser->user_id;
			$this->view->screenname = $currentUser->screen_name; 
		}
		if($messageId != ''){
			$message = new Message();
			$messageRow = $message->findUniqueMessage($messageId);
			$this->view->subject = "Re: " . $messageRow[0]["subject"];
			$stringresponse = "\n\n\n";
			$stringresponse .= "On " . $messageRow[0]["date"] . ", ". $this->view->name ." wrote:\n";
			$stringresponse .="------------------------------------------\n";
			$stringresponse .= $messageRow[0]["content"] ;
			$this->view->message = $stringresponse;
		}
		
		$this->view->messageId = $messageId;
		
		$userList = $user->findUserFriends($session->userId);
		
		$this->view->userList = $userList;
		
		$this->breadcrumbs->addStep ( 'Fan Profiles', self::$urlGen->getMainProfilesPage(true));
		$this->breadcrumbs->addStep ( $session->screenName ,self::$urlGen->getUserProfilePage($session->screenName , true) );
       	$this->breadcrumbs->addStep ( 'Compose Message' );
       	$this->view->breadcrumbs = $this->breadcrumbs;
       	$view->actionTemplate = 'messagesForm.php';
		$this->_response->setBody($view->render('site.tpl.php'));
	}
	
	
	function addmessageAction(){
		
		$session = new Zend_Session_Namespace('userSession');
		$userIdSender = $session->userId;
		
		//Message instance definition and update
		$message = new Message();
		$filter = new Zend_Filter_StripTags();
		if ($this->_request->isPost()){
			
			$message_subject = trim($filter->filter($this->_request->getPost('subjectmc')));
			
			$message_content = trim($filter->filter($this->_request->getPost('content')));
			$type = 7; //Type of Message Mail
			$idsarray = trim($filter->filter($this->_request->getPost('idarray'))); //List of user ids to send message
			$sentto = trim($filter->filter($this->_request->getPost('to')));
			$ids = split(",", $idsarray);

			$message_img = "";
			$message_shout = "";
			
		
			//Two messages creation is needed for seak of the database.
			//se deben crear 2 mensajes uno(s) para el que envia(sent) y otro para el destinatario.
			//Create arrays of elements
			//print_r($sentto);
			$message_date = trim ( date ( "Y-m-d H:i:s" ));
			try{
				for($i=0;$i<count($ids);$i++){
					$data = array( 
							   'user_id'=> $ids[$i],//Message owner.
							   'message_subject'=> $message_subject,
							   'message_type'=> $type,
							   'message_date'=> $message_date,
							   'message_status'=> 4, //Pending
							   'message_content'=> $message_content,
							   'message_img' => $message_img,
							   'message_shout'=> $message_shout,
							   'user_from_id'=> $userIdSender,
							   'sent' => 0
					);
	
					
					
					$user = new User();
					$newFriendProfile = $user->findUserUnique($ids[$i]);
					//sende messages to recipients in real time
					if($newFriendProfile[0]['private_message_email'] == '1' && $newFriendProfile[0]['private_message_frecuency']=='1'){
						$screenName = $session->screenName;
						/*Send Mail to Friend for Request*/
						$mail = new SendEmail();
						$mail->set_from('webmaster@goalface.com');
						$mail->set_to( $newFriendProfile [0] ["email"]);
						//$mail->set_to( 'chocheraz2003@yahoo.com');
						$mail->set_subject($screenName.' sent you a message on GoalFace...');
						$mail->set_template('messagesentfriend');
						$variablesToReplaceEmail = array ('username' => $screenName, 'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ) );
						$mail->set_variablesToReplace($variablesToReplaceEmail);
						$mail->sendMail();
						$data['emaildelivered']='Y';
					}
					$message->insert($data);
					/*Send Mail to Friend for Request*/
					
				}
	
	
			}catch(Exception $e){
	
				echo $e->getMessage();
			}
	
			//Message for the sender in the outbox
			$data4Sender = array( //
						   'user_id'=> $userIdSender,
						   'message_subject'=> $message_subject,
						   'message_type'=> $type,
						   'message_date'=> $message_date,
						   'message_status'=> 2,
						   'message_content'=> $message_content,
						   'message_img' => $message_img,
						   'message_shout'=> $message_shout,
						   'user_from_id'=> $userIdSender,
						   'sent' => 1,
						   'sentto' => $sentto);	
				
			$message =  new Message();
	
			try {
				$message->insert($data4Sender);
	
			}catch(Exception $e){
				//$db->rollBack();
				echo $e->getMessage();
			}
		
		}

	}

	function replymessageAction(){
		$session = new Zend_Session_Namespace('userSession');
		$userIdSender = $session->userId;
		
		//Message instance definition and update
		$message = new Message();
		$filter = new Zend_Filter_StripTags();
		if ($this->_request->isPost()){
			
			$messageid = trim($filter->filter($this->_request->getPost('messageId')));
			$message_subject = trim($filter->filter($this->_request->getPost('subjectmc')));
			$message_content = trim($filter->filter($this->_request->getPost('content')));
			$type = 7; //Type of Message Mail
			$idsarray = trim($filter->filter($this->_request->getPost('idarray'))); //List of user ids to send message
			$sentto = trim($filter->filter($this->_request->getPost('to')));
			$ids = split(",", $idsarray);

			$message_img = "";
			$message_shout = "";
			
		
			//Two messages creation is needed for seak of the database.
			//Update the message being replied.
			
			$message_date = trim ( date ( "Y-m-d H:i:s" ));
			try{
				for($i=0;$i<count($ids);$i++){
					$data = array( 
							   'user_id'=> $ids[$i],//Message owner.
							   'message_subject'=> $message_subject,
							   'message_type'=> $type,
							   'message_date'=> $message_date,
							   'message_status'=> 4, //Unread
							   'message_content'=> $message_content,
							   'message_img' => $message_img,
							   'message_shout'=> $message_shout,
							   'user_from_id'=> $userIdSender,
							   'sent' => 0
					);
	
					$message->insert($data);
					
					$user = new User();
					$newFriendProfile = $user->findUserUnique($ids[$i]);
					$screenName = $session->screenName;
					/*Send Mail to Friend for Request*/
					$mail = new SendEmail();
					$mail->set_from('webmaster@goalface.com');
					$mail->set_to( $newFriendProfile [0] ["email"]);
					//$mail->set_to( 'chocheraz2003@yahoo.com');
					$mail->set_subject($screenName.' sent you a message on GoalFace...');
					$mail->set_template('messagesentfriend');
					$variablesToReplaceEmail = array ('username' => $screenName, 'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ) );
					$mail->set_variablesToReplace($variablesToReplaceEmail);
					$mail->sendMail();
					/*Send Mail to Friend for Request*/
					
				}
	
	
			}catch(Exception $e){
	
				echo $e->getMessage();
			}
	
			//Message for the sender in the outbox
			$data4Sender = array( //
						   'user_id'=> $userIdSender,
						   'message_subject'=> $message_subject,
						   'message_type'=> $type,
						   'message_date'=> $message_date,
						   'message_status'=> 2,
						   'message_content'=> $message_content,
						   'message_img' => $message_img,
						   'message_shout'=> $message_shout,
						   'user_from_id'=> $userIdSender,
						   'sent' => 1,
						   'sentto' => $sentto);	
				
			$message =  new Message();
	
			try {
				$message->insert($data4Sender);
	
			}catch(Exception $e){
				//$db->rollBack();
				echo $e->getMessage();
			}
			
			//update the status of the message being responded
			$data = array('message_status'=> 12);
			$message->updateMessage( $messageid , $data);
			
		
		}

	}

	
	function messageupdateAction(){
		
		$status = $this->_request->getPost( 'status' );
		$data = array('message_status' => $status );
		$arrayMessagesToUpdate = $this->_request->getPost( 'arrayMessages' );
		$message = new Message();
		foreach ( $arrayMessagesToUpdate as $messageId) {
			$message->updateMessage($messageId ,$data );
		}
	}


	function messagedeleteAction(){
		
		$arrayMessagesToUpdate = $this->_request->getPost( 'arrayMessages' );
		$message = new Message();
		foreach ( $arrayMessagesToUpdate as $messageId) {
			$message->deleteMessage($messageId);
		}
	}


	function addfriendrequestAction(){
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		$friend = $this->_request->getParam ( 'friend', 0 );
		$message_date = date ( "Y-m-d H:i:s" );

		$us = new User();
		$name = $us->findUserUnique($friend);
		

		$subject = "New Friend Request";
		$sess = new Zend_Session_Namespace('userSession');
		$param1 = $sess->userId;


		
		$data = array( 'user_id'=> $friend,
					   'message_subject'=> $subject,
					   'message_type'=> 8,
					   'message_date'=> $message_date,
					   'message_status'=> 4,
					   'message_content'=> 'Hey!, can i be your friend?',
					   'message_img' => '',
					   'message_shout'=> '',
					   'user_from_id'=> $param1,
					   'sent' => 0
						);
			
		$message =  new Message();

		try {
			$message->insert($data);

		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		/*Send Mail to Friend for Request*/
		$mail = new SendEmail();
		$mail->set_from('webmaster@goalface.com');
		$mail->set_to( $name [0] ["email"]);
		//$mail->set_to( 'chocheraz2003@yahoo.com');
		$mail->set_subject($session->screenName.' added you as a friend on Goalface...');
		$mail->set_template('addfriendrequest');
		$variablesToReplace = array ('username' => $session->screenName, 'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ) );
		$mail->set_variablesToReplace($variablesToReplace);
		$mail->sendMail();
		
		
		
		//Message Request for the sender in the outbox
		$data4Sender = array( //
					   'user_id'=> $param1,
					   'message_subject'=> "Friend Invite ",
					   'message_type'=> 9,
					   'message_date'=> $message_date,
					   'message_status'=> 4,
					   'message_content'=> 'Hey!, can i be your friend?',
					   'message_img' => '',
					   'message_shout'=> '',
					   'user_from_id'=> $friend,
					   'sent' => 1,
					   'sentto' => $name[0]['screen_name']);	
			
		$message =  new Message();
		$message->insert($data4Sender);
	
			
		
		}



	public function acceptfriendrequestAction(){

		$filter = new Zend_Filter_StripTags();
		$sess = new Zend_Session_Namespace('userSession');
		$user_id = $sess->userId ;
		$screenName = $sess->screenName ;
		$mesgId = trim($filter->filter($this->_request->getPost('messageRequestId')));
		$newUserFriendName = trim($filter->filter($this->_request->getPost('newUserFriendName')));
		$newFriendId = trim($filter->filter($this->_request->getPost('newFriendId')));
		$usrfrnd = new UserFriend();
		$data1 = array("user_id"=>$user_id,"friend_id"=>$newFriendId ,"infriendfeed"=>'y');
		$data2 = array("user_id"=>$newFriendId, "friend_id"=>$user_id ,"infriendfeed"=>'y');
		//update message with id = 10 Accpeted
		$datamess = array('message_status' => 10);
		$message =  new Message();
		
		try {
			//update request message with accepted or replied
			$message->updateMessage($mesgId ,$datamess);
			$usrfrnd->insert($data1);
			$usrfrnd->insert($data2);

		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		//let's get the email of the new friend to send a mail
		$user = new User();
		$newFriendProfile = $user->findUserUnique($newFriendId);
		
		/*Send Mail to Friend for Request*/
		$mail = new SendEmail();
		$mail->set_from('webmaster@goalface.com');
		$mail->set_to( $newFriendProfile [0] ["email"]);
		//$mail->set_to( 'chocheraz2003@yahoo.com');
		$mail->set_subject($screenName.' confirmed you as a friend on Goalface...');
		$mail->set_template('acceptfriendrequest');
		$variablesToReplaceEmail = array ('username' => $screenName, 'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ) );
		$mail->set_variablesToReplace($variablesToReplaceEmail);
		//$mail->sendMail();
		/*Send Mail to Friend for Request*/
		
		//Create a new Activity Event for your feed
		$variablesToReplace = array ('username01' => $screenName ,
									 'username02' => $newUserFriendName );
		$activityType = Constants::$_ADD_NEW_FRIEND_ACTIVITY;
		$activityAddFriend = new Activity ( );
		$activityAddFriend->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $user_id );
		$variablesToReplace = array ('username02' => $screenName ,
									 'username01' => $newUserFriendName );	
		$activityAddFriend->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $newFriendId ,'n' );
		//update the status of the message sent originally by the friend
		$messageId = $message->findFriendInviteMessage($newFriendId , $screenName);
		echo $messageId; 
		
		try {
			$message->updateMessage($messageId ,$datamess);	
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function removefromfriendsAction(){
		$session = new Zend_Session_Namespace ( 'userSession' );
		$friend = $this->_request->getParam ( 'friend', 0 );
		$userFriend = new UserFriend();
		$userFriend->deleteUserFriend ( $session->userId, $friend );
	}

}
