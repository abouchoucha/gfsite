<?php
require_once 'Zend/Session/Namespace.php';
require_once 'application/models/Activity.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Filter/StripTags.php';
require_once 'application/models/Country.php';
require_once 'application/models/User.php';
require_once 'application/models/UserLanguage.php';
require_once 'Zend/Loader.php';
require_once 'application/controllers/GoalFaceController.php';
require_once 'util/Common.php';
require_once 'Zend/Session.php';
require_once 'util/Constants.php';
require_once 'scripts/seourlgen.php';
//require ("library/Zrad/Zrad_Facebook.php");
require_once 'Zrad/Zrad_Facebook.php';require_once 'Zrad/cFacebook.php';

class LoginController extends GoalFaceController {
	private static $logger2;	private static $urlGen = null;
	function init() {		Zend_Loader::loadClass ( 'Player' );
		self::$logger2 = Zend_Registry::get ( "logger" );
		$this->updateLastActivityUserLoggedIn ();
		self::$urlGen = new SeoUrlGen ();
	}
	function fbdologinAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->fbuser != null) {
			$email = $session->fbuser ['email'];
			$user = new User ();
			$user = $user->findUniqueUser ( $email );
			if ($user != null) {
				$session->user = $user;
				$this->_redirect ( Zend_Registry::get ( "contextPath" ) . "/user/createfbprofile" );
			}
			$country = new Country ();
			$rows = $country->selectCountries ();
			$session->countries = $rows;
			$view = Zend_Registry::get ( 'view' );
			$view->actionTemplate = 'userBasicInfoFacebook.php';
			$this->_response->setBody ( $view->render ( 'siteUserRegister.tpl.php' ) );
		} else {
			$this->_redirect ( "/sign-in" );
		}
	}
	
	function indexAction() {
		$session = new Zend_Session_Namespace ( 'userSession' );
		$config = Zend_Registry::get ( 'config' );		$appId = $config->facebook->appid;		$secret = $config->facebook->secret;		$servername = $config->path->index->server->name;				$valTemp = cFacebook::loginFacebook('sign-in',$session,$appId,$secret,$servername);		if($valTemp != null){			$this->_redirect ( "/login/fbdologin" );		}				$view = Zend_Registry::get ( 'view' );
		//$view->idFacebook = $idFacebook;
		require_once 'browserdetection.php';
		$a_browser_data = browser_detection ( 'full' );
		$view->title = "Login to Global Football Social Network | GoalFace.com";		
		$view->errorLogin = "";
		if (isset ( $_COOKIE ["emailGoalface"] )) {		
			$view->email = $_COOKIE ["emailGoalface"];			
		} else {		
			$view->email = "";			
		}
		if (isset ( $_COOKIE ["passwordGoalface"] )) {
			$view->password = $_COOKIE ["passwordGoalface"];
		} else {
			$view->password = "";
		}
		if (isset ( $_COOKIE ["checked"] )) {
			if ($a_browser_data [0] == 'ie' or $a_browser_data [0] == 'saf') {
				$view->checked = $_COOKIE ["checked"];
			} else {
				$view->checked = 'true';
			}
		} else {
			if ($a_browser_data [0] == 'ie' or $a_browser_data [0] == 'saf') {
				$view->checked = '';
			} else {
				$view->checked = '';
			}
		}
		$servername = $config->path->index->server->name;
		$session = new Zend_Session_Namespace ( 'userSession' );
		$referer = null;
		if (! isset ( $_SERVER ['HTTP_REFERER'] )) { /*
		                                           * If not, set referrer as your
		                                           * domain
		                                           */
			
			$domain = $servername;
		} else {
			$referer = $_SERVER ['HTTP_REFERER'];
			$domain = parse_url ( $referer ); /*
			                                * If yes, parse referrer
			                                */		}
		if ($domain ['host'] == $servername) {
			if ($domain ['path'] == '/login') {
				$view->referrer = $domain ['host'];
			} else {
				$view->referrer = $referer;
			}
		} else {			/*
			 * The referrer is not your site, we redirect to your home page
			 */
			$view->referrer = "";		}
		if ($session->referrer != '') {
			$view->referrer = $session->referrer;
			$session->referrer = null;
		}
		if ($session->profileCreated != null) {
			$view->profileCreated = $session->profileCreated;
			self::$logger2->info ( 'Profile created:' . $view->profileCreated );
			$session->profileCreated = null;
		}				$player = new Player();		$playerlist = $player->findPopularPlayers(5);		//Zend_Debug::dump($playerlist);
		self::$logger2->info ( 'Redirecting to: loginForm.php 2' );
		$view->actionTemplate = 'loginForm.php';
		$this->_response->setBody ( $view->render ( 'siteUserRegister.tpl.php' ) );	}
	
	function dologinAction() {
		
		self::$logger2->info ( 'Beginning Login Action' );
		
		$filter = new Zend_Filter_StripTags ();
		
		$common = new Common ();
		
		$view = Zend_Registry::get ( 'view' );
		
		$view->title = "Welcome to GoalFace";
		
		$sessionCookie = new Zend_Session_Namespace ( 'userSessionCookie' );
		
		$email = trim ( $filter->filter ( $this->_request->getPost ( 'username', $sessionCookie->emailcookie ) ) );
		
		$psw = trim ( $filter->filter ( $this->_request->getPost ( 'password', $sessionCookie->pswcookie ) ) );
		
		$remember = trim ( $filter->filter ( $this->_request->getPost ( 'persistent', "1" ) ) );
		
		$previousVisitedUrl = trim ( $filter->filter ( $this->_request->getPost ( 'referrer', $sessionCookie->referrer ) ) );
		
		/*
		 * Only for Alpha Phase
		 */
		
		if ($previousVisitedUrl == 'http://www.goalface.com/' or $common->startsWith ( $previousVisitedUrl, 'http://preview.goalface.com/' )) {
			
			$previousVisitedUrl = 'http://www.goalface.com/';
		
		}
		
		/*
		 * Only for Alpha Phase
		 */
		
		// Zend_Debug::dump("-->".$previousVisitedUrl);
		
		$user = new User ();
		
		$rowset = $user->findUniqueUser ( $email );
		
		$valid = $user->validateAccount ( $email );
		
		if ($rowset == null) {
			
			$errorMsg = " The email address you entered is not in our records.Please check the email address you entered. ";
			
			$errorMsg .= " Are you trying to <a href=' " . Zend_Registry::get ( "contextPath" ) . "/register'>register for a new account";
			
			$jserror = 'jQuery(\'#MainErrorMessageLogin\').html("' . $errorMsg . '");';
			
			$jserror .= 'jQuery(\'#ErrorMessagesLogin\').removeClass(\'ErrorMessages\').addClass(\'ErrorMessagesDisplay\');';
			
			$jserror .= 'jQuery(\'#systemWorkingLoginForm\').hide();';
			
			echo $jserror;
			
			return;
		
		}
		
		if ($valid == null) {
			
			$jserror = 'jQuery(\'#MainErrorMessageLogin\').html(\'Your account has not been validated yet. Please check out your email account.\');';
			
			$jserror .= 'jQuery(\'#ErrorMessagesLogin\').removeClass(\'ErrorMessages\').addClass(\'ErrorMessagesDisplay\');';
			
			$jserror .= 'jQuery(\'#systemWorkingLoginForm\').hide();';
			
			echo $jserror;
			
			return;
		
		}
		
		$view->user = $rowset;
		
		// Zend:: dump ($rowset);
		
		$salt = $rowset->salt;
		
		$hash = $common->generateHash ( $psw, $salt );
		
		self::$logger2->info ( 'Remember:' . $remember );
		
		if ($hash == $salt) {
			
			// $view->actionTemplate = 'welcomePage.php';
			
			// create session
			
			if ($remember == "1") {
				
				setcookie ( "emailGoalface", $email, time () + 3600 * 24 * 15, '/', null, null ); // for
				                                                                                // 15
				                                                                                // days
				
				setcookie ( "passwordGoalface", $psw, time () + 3600 * 24 * 15, '/', null, null );
				
				setcookie ( "checked", "checked", time () + 3600 * 24 * 15, '/', null, null );
				
				self::$logger2->info ( 'Cookies were set' );
			
			} else {
				
				setcookie ( "emailGoalface", "", time () - 3600 * 24 * 15, '/', null, null ); // for
				                                                                              // 1
				                                                                              // hour
				
				setcookie ( "passwordGoalface", "", time () - 3600 * 24 * 15, '/', null, null );
				
				setcookie ( "checked", "", time () - 3600 * 24 * 15, '/', null, null );
				
				self::$logger2->info ( 'Cookies were not set' );
			
			}
			
			$country = new Country ();
			
			$session = new Zend_Session_Namespace ( "userSession" );
			
			// $session->setExpirationSeconds(120);
			
			$session->user = $rowset;
			
			$session->email = $email;
			
			// $session->psw = $psw;
			
			$session->userId = $rowset->user_id;
			
			if ($valid->first_login != '0') {
				
				$session->firstName = $rowset->first_name;
				
				$session->lastName = $rowset->last_name;
				
				$session->screenName = $rowset->screen_name;
				
				if ($rowset->main_photo != null || $rowset->main_photo != "") {
					
					$session->mainPhoto = $rowset->main_photo;
				
				}
				
				$session->bio = $rowset->aboutme_text;
			
			}
			
			$session->dateJoined = $rowset->registration_date;
			
			$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
			
			if ($countryLive != null) {
				
				$rowArray = $countryLive->toArray ();
				
				$session->countryLive = $rowArray ['country_name'];
				
				$session->countryLiveId = $rowArray ['country_id'];
			
			}
			
			$dob = explode ( '-', $rowset->dob );
			
			$session->year = $dob [0];
			
			$session->month = $dob [1];
			
			$session->day = $dob [2];
			
			$rows = $country->selectCountries ();
			
			$session->countries = $rows;
			
			$user_language = new UserLanguage ();
			
			$firstlang = $user_language->findLanguageFirstReg ( $rowset->user_id );
			
			$session->firstLanguage = $firstlang->language_id;
			
			if ($valid->first_login == '0') {
				
				// create first friend of user (goalface user) It is used
				// UserController->skiptomyprofile , REFACTOR!! )
				
				// $firstFriend = $user->findUniqueUser ( "fanfan@goalface.com"
				// );
				
				$current = $user->find ( 1 );
				
				$firstFriend = $current->current ();
				
				$userFriend = new UserFriend ();
				
				$data1 = array ("user_id" => $rowset->user_id, "friend_id" => $firstFriend->user_id );
				
				$data2 = array ("user_id" => $firstFriend->user_id, "friend_id" => $rowset->user_id );
				
				$userFriend->insert ( $data1 );
				
				$userFriend->insert ( $data2 );
				
				$session->accountvalidatedAndProfileCreated = 'true';
				
				echo 'window.location = "' . Zend_Registry::get ( "contextPath" ) . '/user/addinfo"';
			
			} else {
				
				// update login time
				
				$data = array ('last_login' => trim ( date ( "Y-m-d H:i:s" ) ), 'user_enabled' => 1 );
				
				$user->updateUser ( $session->email, $data );
				
				// If login comes from a modal with form open it in the next
				// page
				
				$modalOrigin = $this->_request->getPost ( 'origin' );
				
				$session->origin = $modalOrigin;
				
				// get number of messages in inbox
				
				$message = new Message ();
				
				$newMessages = $message->doCountMessagesbyUser ( $session->userId );
				
				$newMessagesTotal = $newMessages [0] ["total"];
				
				$session->newMessages = $newMessagesTotal;
				
				self::$logger2->info ( 'Redirecting to:' . $previousVisitedUrl );
				
				echo 'window.location = "' . Zend_Registry::get ( "contextPath" ) . ($previousVisitedUrl != '' ? $previousVisitedUrl : '/myhome') . '"';
			
			}
		
		} else {
			
			$jserror = 'jQuery(\'#MainErrorMessageLogin\').html(\'The login information you entered is not valid.Please try again.\');';
			
			$jserror .= 'jQuery(\'#ErrorMessagesLogin\').removeClass(\'ErrorMessages\').addClass(\'ErrorMessagesDisplay\');';
			
			$jserror .= 'jQuery(\'#systemWorkingLoginForm\').hide();';
			
			echo $jserror;
		
		}
	
	}
	
	public function verifypasswordAction() {
		
		self::$logger2->info ( 'Beginning Veryfying Password for account settings' );
		
		$session = new Zend_Session_Namespace ( "userSession" );
		
		$filter = new Zend_Filter_StripTags ();
		
		$common = new Common ();
		
		// $email = trim ( $filter->filter ( $this->_request->getPost (
		// 'username' ,$sessionCookie->emailcookie ) ) );
		
		$psw = trim ( $filter->filter ( $this->_request->getPost ( 'password' ) ) );
		
		$previousVisitedUrl = "/editprofile/" . $session->screenName . "/settings";
		
		// Zend_Debug::dump("-->".$previousVisitedUrl);
		
		$rowset = $session->user;
		
		$salt = $rowset->salt;
		
		$hash = $common->generateHash ( $psw, $salt );
		
		if ($hash == $salt) {
			
			$session->passwordVerify = "true";
			
			self::$logger2->info ( 'Redirecting to:' . $previousVisitedUrl );
			
			echo 'window.location = "' . Zend_Registry::get ( "contextPath" ) . ($previousVisitedUrl != '' ? $previousVisitedUrl : '/index') . '"';
		
		} else {
			
			$jserror = 'jQuery(\'form>div>#MainErrorMessageLogin\').html(\'The login information you entered is not valid.Please try again.\');';
			
			$jserror .= 'jQuery(\'form>div#LoginErrorMessagesId\').addClass(\'ErrorMessagesDisplay\');';
			
			$jserror .= 'jQuery(\'#systemWorkingLoginForm\').hide();';
			
			echo $jserror;
		
		}
	
	}
	
	function retrievepasswordAction() {
		
		$view = Zend_Registry::get ( 'view' );
		
		$view->title = "Retrieve Password";
		
		$user = new User ();
		
		if ($this->_request->isPost ()) {
			
			$filter = new Zend_Filter_StripTags ();
			
			$email = trim ( $filter->filter ( $this->_request->getPost ( 'emailaddressreset' ) ) );
			
			$rowset = $user->findUniqueUser ( $email );
			
			$jserror = '';
			
			if ($rowset == null) {
				
				$errorMsg = "The email address you entered does not match any record with us. Please re-enter your email address.";
				
				$jserror .= 'jQuery(\'#emailaddressreseterror\').html("' . $errorMsg . '");';
				
				$jserror .= 'jQuery(\'#emailaddressreseterror\').addClass(\'ErrorMessageIndividualDisplay\');';
				
				echo $jserror;
				
				return;
			
			}
			
			// generate a timestamp
			
			$cc = strtotime ( "now" );
			
			// save the timestamp in the db
			
			$data = array ('resetPasswordKey' => $cc );
			
			$user->updateUser ( $email, $data );
			
			// Send and email
			
			$config = Zend_Registry::get ( 'config' );
			
			/*
			 * Send Mail Retrieve Password Email
			 */
			
			$mail = new SendEmail ();
			
			$mail->set_from ( $config->email->confirmation->from );
			
			$mail->set_to ( $email );
			
			$mail->set_subject ( 'GoalFace Password Reset Info' );
			
			$mail->set_template ( 'resetpassword' );
			
			$variablesToReplaceEmail = array ('fname' => $rowset->first_name, 

			'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ), 

			'cc' => $cc, 'email' => $email )

			;
			
			$mail->set_variablesToReplace ( $variablesToReplaceEmail );
			
			$mail->sendMail ();
			
			/*
			 * Send Mail Retrieve Password Email
			 */
		
		} else {
			
			$email = trim ( $this->_request->getParam ( 'email', 0 ) );
			
			$cc = trim ( $this->_request->getParam ( 'cc', 0 ) );
			
			$rowset = $user->findUniqueUser ( $email );
			
			$title = new PageTitleGen ();
			
			// $keywords = new MetaKeywordGen ( );
			
			// $description = new MetaDescriptionGen ( );
			
			$view->title = $title->getPageTitle ( null, PageType::$_USER_PASSWORD_RESET );
			
			// $view->keywords = $keywords->getMetaKeywords ( null,
			// PageType::$_USER_PASSWORD_RESET );
			
			// $view->description = $description->getMetaDescription ( null,
			// PageType::$_USER_PASSWORD_RESET );
			
			if (($rowset == null || trim ( $rowset->resetPasswordKey ) != $cc) && ($email != '0')) {
				
				$view->actionTemplate = 'passwordform.php';
				
				$view->error = '1';
			
			} else if ($rowset != null && trim ( $rowset->resetPasswordKey ) == $cc) {
				
				$view->actionTemplate = 'resetpasswordform.php';
				
				$view->email = $email;
			
			} else {
				
				$view->actionTemplate = 'passwordform.php';
				
				$view->error = '0';
			
			}
			
			// $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
			
			$this->_response->setBody ( $view->render ( 'siteUserRegister.tpl.php' ) );
		
		}
	
	}
	
	function resetpasswordAction() {
		
		$view = Zend_Registry::get ( 'view' );
		
		$view->title = "GoalFace | Reset Password";
		
		$user = new User ();
		
		if ($this->_request->isPost ()) {
			
			// to change the password especific
			
			$filter = new Zend_Filter_StripTags ();
			
			$newpassword = trim ( $filter->filter ( $this->_request->getPost ( 'newpassword' ) ) );
			
			$common = new Common ();
			
			$hash = $common->generateHash ( $newpassword, null );
			
			$passwordSha = sha1 ( $newpassword );
			
			// updating password in db
			
			$data = array ('password' => $passwordSha, 'salt' => $hash, 'date_update' => trim ( date ( "Y-m-d H:i:s" ) ), 'resetPasswordKey' => null );
			
			$email = $this->_request->getPost ( 'email' );
			
			$rows_affected = $user->updateUser ( $email, $data );
			
			$rowset = $user->findUniqueUser ( $email );
			
			if ($rows_affected != null) {
				
				// Send and email confirming that a new password has been
				// created
				
				$config = Zend_Registry::get ( 'config' );
				
				/*
				 * Send Mail Reset Confirmation Password Email
				 */
				
				$mail = new SendEmail ();
				
				$mail->set_from ( $config->email->confirmation->from );
				
				$mail->set_to ( $email );
				
				$mail->set_subject ( 'GoalFace Password Reset Confirmation' );
				
				$mail->set_template ( 'resetpasswordconfirmation' );
				
				$variablesToReplaceEmail = array ('fname' => $rowset->first_name, 

				'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ), 

				'newpassword' => $newpassword )

				;
				
				$mail->set_variablesToReplace ( $variablesToReplaceEmail );
				
				$mail->sendMail ();
				
				/*
				 * Send Mail Retrieve Password Email
				 */
				
				$valid = $user->validateAccount ( $email );
				
				$session = new Zend_Session_Namespace ( "userSession" );
				
				$session->email = $rowset->email;
				
				$session->userId = $rowset->user_id;
				
				$session->isMyProfile = 'y';
				
				$session->firstName = $rowset->first_name;
				
				$session->lastName = $rowset->last_name;
				
				$session->screenName = $rowset->screen_name;
				
				$session->mainPhoto = $rowset->main_photo;
				
				$session->firsttimeviewprofile = 'false';
				
				$session->register = null;
				
				$session->dateJoined = $rowset->registration_date;
				
				$country = new Country ();
				
				$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
				
				if ($countryLive != null) {
					
					$rowArray = $countryLive->toArray ();
					
					$session->countryLive = $rowArray ['country_name'];
				
				}
				
				$session->bio = $rowset->aboutme_text;
				
				$session->user = $rowset;
				
				if ($valid->first_login == '1') {
					
					echo 'window.location = "' . Zend_Registry::get ( "contextPath" ) . '/index' . '"';
				
				} else {
					
					echo 'window.location = "' . Zend_Registry::get ( "contextPath" ) . '/user/addinfo' . '"';
				
				}
			
			}
		
		}
	
	}
	
	function dologoutAction() {
		
		$user = new User ();	
		$data = array ('last_logout' => trim ( date ( "Y-m-d H:i:s" ) ),					'last_activity' => 0 );
		$user->updateUser ( $session->email, $data );
		
		Zend_Session::destroy ( true, true );
		
		$view->title = "Please Login";
		$view->errorLogin = "";
		self::$logger2->info ( 'Finishing logging out and redirecting to HOMe' );
		$this->_redirect ( '/index/index/logout/true' );
	}

}
?>