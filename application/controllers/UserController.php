<?php
require_once 'application/models/Country.php';
require_once 'application/controllers/util/MetaKeywordGen.php';
require_once 'application/models/UserPlayer.php';
require_once 'application/controllers/util/PageTitleGen.php';
require_once 'application/models/LeagueCompetition.php';
require_once 'application/models/User.php';
require_once 'application/models/Team.php';
require_once 'application/models/UserTeam.php';
require_once 'application/models/Language.php';
require_once 'application/models/Competitionfile.php';
require_once 'application/controllers/util/PageType.php';
require_once 'application/models/Player.php';
require_once 'application/models/UserLeague.php';
require_once 'application/controllers/util/Constants.php';
require_once 'application/models/Activity.php';
require_once 'application/models/UserLanguage.php';
require_once 'application/controllers/util/MetaDescriptionGen.php';
require_once 'util/Common.php';
require_once 'util/config.php';
require_once 'util/functions.php';
require_once 'scripts/seourlgen.php';
require_once 'application/controllers/util/ActivityAction.php';
require_once 'Zrad/Zrad_Facebook.php';require_once 'Zrad/cFacebook.php';

class UserController extends Zend_Controller_Action {
	
	private $siteUserRegister = 'siteUserRegister.tpl.php';
	private $siteUserRegisterCorporate = 'siteUserRegisterCorporate.tpl.php';
	private static $logger;
	private static $logger2;
	private static $urlGen = null;
	
	function init() {
		self::$logger = Zend_Registry::get("logger");
		self::$logger2 = Zend_Registry::get("logger2");
		self::$urlGen = new SeoUrlGen ( );
	}
	
	public function validateemailAction(){
		
		$user = new User ( );
		$jserror = '';
		$email = trim ( $this->_request->getParam( 'email') );
		$exist = $user->findUniqueUser ( $email );
		if ($exist != null) {
			$jserror .= 'jQuery(\'#emailerror\').html(\'' . $email . '  , has already been taken.  Please try another email address.\');';
			$jserror .= 'jQuery(\'#emailerror\').addClass(\'ErrorMessageIndividualDisplay\');';
			echo  $jserror ;
			return;
		}
	}
	
	function registerAction() {
		$view = Zend_Registry::get ( 'view' );
		$site = $this->_request->getParam ( 'site', null );
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_REGISTRATION );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_REGISTRATION );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_REGISTRATION );
		
		$todays_date = date ( "Y-m-d H:i:s" );
		
		$this_year = date ( "Y" );
		$view->error_message = "";		$session = new Zend_Session_Namespace ( 'userSession' );		
		$config = Zend_Registry::get ( 'config' );		$appId = $config->facebook->appid;		$secret = $config->facebook->secret;		$servername = $config->path->index->server->name;				$valTemp = cFacebook::loginFacebook('sign-in',$session,$appId,$secret,$servername);		if($valTemp != null){			$this->_redirect ( "/login/fbdologin" );		}
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( );
			$email = trim ( $filter->filter ( $this->_request->getPost ( 'email' ) ) );
			$password = trim ( $filter->filter ( $this->_request->getPost ( 'password' ) ) );
			$dobfull = trim ( $filter->filter ( $this->_request->getPost ( 'birth_year' ) ) ) . "-" . trim ( $filter->filter ( $this->_request->getPost ( 'birth_month' ) ) ) . "-" . trim ( $filter->filter ( $this->_request->getPost ( 'birth_day' ) ) );
			$dob_check = trim ( $filter->filter ( $this->_request->getPost ( 'dob_check' ) ) );
			$country = trim ( $filter->filter ( $this->_request->getPost ( 'country' ) ) );
			$language = trim ( $filter->filter ( $this->_request->getPost ( 'language' ) ) );
			
			$errors = null;
			//validate if user already exists
			$user = new User ( );
			$exist = $user->findUniqueUser ( $email );
			//Zend_Debug::dump($exist);
			$jserror = '';
			
			if ($exist != null) {
				$errors .= "Sorry a user with the email <strong>" . $email . "</strong> already exists.<br>";
				$jserror .= 'jQuery(\'#emailerror\').html(\'Sorry a user with the email ' . $email . '  already exists.Choose another.\');';
				$jserror .= 'jQuery(\'#emailerror\').addClass(\'ErrorMessageIndividualDisplay\');';
			}
			//user has to be at least 13 years older
			$new_year = date ( $dobfull );
			$difference = $this_year - $new_year;
			if ($difference < 13) {
				//echo "Sorry but you must be at least 13 years old." ;
				//return ;
				$errors .= "Sorry but you must be at least 13 years old.<br>";
				$jserror .= 'jQuery(\'#birth_yearerror\').html(\'Sorry but you must be at least 13 years old.\');';
				$jserror .= 'jQuery(\'#birth_yearerror\').addClass(\'ErrorMessageIndividualDisplay\');';
			}
			//validation the image code
			require_once('application/controllers/util/recaptchalib.php');
			$config = Zend_Registry::get ( 'config' );
			$privatekey  = $config->captcha->private->key;
			$resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);
			                                
			//if ($wordimage != $session->image_random_value) {
			if (!$resp->is_valid) {	
				//echo 'Sorry the number you entered does not match the one in the image. Please try again' ;
				//return ;
				$errors.=  'Sorry the characters you entered does not match the one in the image. Please try again.<br>';
				$jserror .= 'jQuery(\'#recaptcha_response_fielderror\').html(\'Sorry the characters you entered does not match the one in the image. Please try again.\');';
				$jserror .= 'jQuery(\'#recaptcha_response_fielderror\').addClass(\'ErrorMessageIndividualDisplay\');';
			}
			if (isset ( $errors )) {
				//echo $errors;
				$jserror .= 'Recaptcha.reload();';
				$jserror .= 'jQuery(\'html, body\').animate({scrollTop:0}, \'slow\');';
				$jserror .= 'jQuery(\'#ErrorMessages\').removeClass(\'ErrorMessage\').addClass(\'ErrorMessageDisplay\');';
				$jserror .= 'jQuery(\'#ErrorMessages\').html(\'Ooops, there was a problem with the information you entered below. Please correct the highlighted fields.\');';
				echo  $jserror ;
				return;
			}
			
			//generate hash and hash1 from password entered
			$common = new Common ( );
			$hash = $common->generateHash ( $password, null );
			$passwordSha = sha1 ( $password );
			
			//create array for elements in the form and table to insert
			$data = array ('dob' => $dobfull,
                            'dob_check' => $dob_check,
                            'country_live' => $country,
                            'email' => $email,
                            'password' => $passwordSha,
                            'salt' => $hash,
                            'registration_date' => trim ( $todays_date ),
                            'flag_confirm' => '0', //unconfirmed
                            'first_login' => '0' );
			
			//data for user_language
			$user = new User ( );
			$user_language = new UserLanguage ( );
						
			$user_id = $user->insert ( $data );
			
			$data1 = array ('user_id' => $user_id, 'language_id' => $language, 'first_reg' => "1" );
			$user_language->insert ( $data1 );
			
			$config = Zend_Registry::get ( 'config' );
			/*Send Mail to Friend for Request*/
			$mail = new SendEmail();
			$mail->set_from($config->email->confirmation->from);
			$mail->set_to( $email);
			$mail->set_subject('GoalFace account confirmation');
			$mail->set_template('accoutconfirmationrequired');
			$variablesToReplaceEmail = array ('emailUser' => $email, 
						'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ),
						'passwordSha' => $passwordSha, 'user_id' => $user_id
											);
			$mail->set_variablesToReplace($variablesToReplaceEmail);
			$mail->sendMail();
			/*Send Mail to Friend for Request*/

			/**CODE ADDED  IN ORDER TO CHANGE THE FLOW FROM REGISTER FORM TO ADD INFO*/
			//$session = new Zend_Session_Namespace("registerSession");
			/*$session = new Zend_Session_Namespace("userSession");
			$rowset = $user->findUniqueUser ( $email );
			$dob = explode ( '-', $rowset->dob );
			$session->year = $dob [0];
			$session->month = $dob [1];
			$session->day = $dob [2];
			$country = new Country ( );
			$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
		
			if ($countryLive != null) {
				$rowArray = $countryLive->toArray ();
				$session->countryLive = $rowArray ['country_name'];
				$session->countryLiveId = $rowArray ['country_id'];
			}
			$user_language = new UserLanguage ( );
			$firstlang = $user_language->findLanguageFirstReg ( $rowset->user_id );
			$session->firstLanguage = $firstlang->language_id;
			$session->email = $email;
			$session->userId = $rowset->user_id;	
			$session->register = 'true';*/	
			/**CODE ADDED  IN ORDER TO CHANGE THE FLOW FROM REGISTER FORM TO ADD INFO*/
			
	}else{
			$view->email = '';
			$view->password = '';
			$view->password2 = '';
			$view->dob_check = '';
			$view->language = '';
			$view->wordimage = '';
			//$language = new Language();
			$country = new Country ( );
			$rows = $country->selectCountries ();
			$view->countries = $rows;
			$config = Zend_Registry::get ( 'config' );
	  		$view->captchapublickey  = $config->captcha->public->key;
	  		if ($site == null) {
				$view->actionTemplate = 'userRegisterForm.php';
				$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
	  		} else {
	  			$view->actionTemplate = 'userRegisterFormCorporate.php';
	  			$this->_response->setBody ( $view->render ( $this->siteUserRegisterCorporate ) );
	  		}
			
	}	

}	
	function confirmaccountAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Registration Confirmation";
		$session = new Zend_Session_Namespace("userSession");
		//validation the click of the user
		$key = trim ( $this->_request->getParam ( 'key', null ) );
		$hash = trim ( $this->_request->getParam ( 'hash', null ) );
		$user = new User ( );
		
		if ($key != null && $hash != null) {
			$success = $user->activateAccount ( $key, $hash );
			$userRow = $user->findUniqueUser($key);
			//echo "Success:" . $success;
			if ($success == 0 && $userRow->flag_confirm == '1' && $userRow->first_login == '0'  or ($success == 1)) {
				//it has already been confirmed but profile has not yet been created
				$session->accountvalidatedAndProfileCreated = 'true';
				$session->email = $userRow->email;
				$this->_redirect('/create-profile');
			}else if($userRow->first_login == '1' && $userRow->flag_confirm == '1' && $success == 0){
				//user has already create his/her profile redirect to the login page
				//could send a customizes message
				$session->profileCreated = 'true';
				$this->_redirect('/login');
			}
		}else{
			throw new Exception('Confirmation Error: Send an email to community@goalface.com'); 
		}	
	}
	
	public function showregisterconfirmationAction(){
		
		$view = Zend_Registry::get ( 'view' );
		$view->actionTemplate = 'userConfirmation.php';
		$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
		
	}
	
	public function cancellinkAction() {
		//seria bueno revisar los constraints pq se crean 2 registros pa un user en userfriend el amigo default
		//y hay q borrarlo tambien cuando haya el amarre de la db
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Registration Confirmation";
		$session = new Zend_Session_Namespace("userSession");
		//validation the click of the user
		$key = trim ( $this->_request->getParam ( 'key', null ) );
		$hash = trim ( $this->_request->getParam ( 'hash', null ) );
		$user = new User ( );
		
		if ($key != null && $hash != null) {
			$userRow = $user->findUniqueUser($key);
			//echo "Success:" . $success;
			if ($userRow->flag_confirm == '1' && $userRow->first_login == '0') {
				//it has already been confirmed but profile has not yet been created
				$session->accountvalidatedAndProfileCreated = 'true';
				$this->_redirect('/create-profile');
			}else if($userRow->flag_confirm == '1' && $userRow->first_login == '1') {
				$session->accountvalidatedAndProfileCreated = 'true';
				$this->_redirect('/login');
			}else {	
				$user -> deleteUser($userRow->user_id);
				$view->actionTemplate = 'deleteUserConfirmation.php';
				$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
			}
		}else{
			throw new Exception('Cancel Account Error: Send an email to community@goalface.com'); 
		}	
	}
	
	function inviteAction() {
		
		//$this->validateRegisterProcess ();
		
		$view = Zend_Registry::get ( 'view' );
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_INVITEFRIENDS );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_INVITEFRIENDS );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_INVITEFRIENDS );
		$errors = null;
		$view->output_message = "";
		$filter = new Zend_Filter_StripTags ( );
		if ($this->_request->isPost ()) {
			$to = trim ( $filter->filter ( $this->_request->getPost ( 'to' ) ) );
			$subject = trim ( $filter->filter ( $this->_request->getPost ( 'subject' ) ) );
			$message = trim ( $filter->filter ( $this->_request->getPost ( 'message' ) ) );
			$type = trim ( $filter->filter ( $this->_request->getPost ( 'type' ) ) );
			//split emails
			$arrayofmails = explode ( ',', $to );
			$validator = new Zend_Validate_EmailAddress ( );
			
			$jserror = '';
			if ($to == '' && $type == '2') {
				echo '<script>window.location = "' . Zend_Registry::get ( "contextPath" ) . '/user/uploadphoto"</script>';
				return;
			}
			//loop through array, validates each email and sent individually.
			for($i = 0; $i < count ( $arrayofmails ); $i ++) {
				if (! $validator->isValid ( trim ( $arrayofmails [$i] ) )) {
					$errors .= "error";
					$jserror .= 'jQuery(\'#tomailerror\').addClass(\'ErrorMessageIndividualDisplay\');';
					if ($jserror != '') {
						$jserror .= 'jQuery(\'#ErrorMessages\').addClass(\'ErrorMessageDisplay\');';
					}
				}
			}
			
			if (isset ( $errors )) {
				//echo $errors;
				$jserror .= 'jQuery(\'html, body\').animate({scrollTop:0}, \'slow\');';
				$jserror .= 'jQuery(\'#ErrorMessages\').removeClass(\'ErrorMessage\').addClass(\'ErrorMessagesDisplay\');';
				$jserror .= 'jQuery(\'#MainErrorMessage\').html(\'Ooops, there was a problem with the information you entered below. Please correct the highlighted fields.\');';
				echo  $jserror ;
				return;
			}
			
			//To do List - test this functionality using SMTP for sending multiple emails
			$mail = Zend_Registry::get ( 'mail' );
			$mail->setFrom ( 'community@goalface.com', 'GoalFace Team' );
			$mail->setBodyHtml ( $message );
			$mail->setSubject ( $subject );
			for($i = 0; $i < count ( $arrayofmails ); $i ++) {
				//echo 'Sending to:' . $arrayofmails [$i] . "<br>";
				$mail->addTo ( $arrayofmails [$i] );
				$mail->send ();
			
			}
			
			$jserror .= 'jQuery(\'html, body\').animate({scrollTop:0}, \'slow\');';
			$jserror .= 'jQuery(\'#tomailerror\').removeClass(\'ErrorMessageIndividualDisplay \').addClass(\'ErrorMessageIndividual\');';
			$jserror .= 'jQuery(\'#ErrorMessages\').removeClass(\'ErrorMessage\').addClass(\'ErrorMessagesDisplayBlue\');';
			$jserror .= 'jQuery(\'#MainErrorMessage\').html(\'Your invitations have been sent.\');';
			echo  $jserror ;
				
			
		} else {
			//display empty form
			$view->actionTemplate = 'userInviteFriends.php';
			$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
		}
	}
	
	function importaddressAction() {
		
		$view = Zend_Registry::get ( 'view' );
		//$view->actionTemplate = 'abimporter.php';
		$this->_response->setBody ( $view->render ( 'abimporter.php' ) );
	}
	
	public function validatescreennameAction(){
			
			$user = new User ( );
			$jserror = '';
			$screenname = trim ( $this->_request->getParam( 'screenname') );
			$exist = $user->findUniqueDisplayName ( $screenname );
			if ($exist != null) {
				$jserror .= 'jQuery(\'#screennameerror\').html(\'' . $screenname . '  , has already been taken.  Please try another screenname.\');';
				$jserror .= 'jQuery(\'#screennameerror\').addClass(\'ErrorMessageIndividualDisplay\');';
				echo  $jserror ;
				return;
			}
		}
	
	function addinfoAction() {
		
		$session = new Zend_Session_Namespace("userSession");
		
		$view = Zend_Registry::get ( 'view' );
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_ADDINFO );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_ADDINFO );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_ADDINFO );
		
		$errors = array ();
		$view->errors = "";
		$filter = new Zend_Filter_StripTags ( );
		$user = new User ( );
		
		if ($this->_request->isPost ()) { //to insert basic info
			$screenname = trim ( $filter->filter ( $this->_request->getPost ( 'screenname' ) ) );
			$firstname = trim ( $filter->filter ( $this->_request->getPost ( 'firstname' ) ) );
			$lastname = trim ( $filter->filter ( $this->_request->getPost ( 'lastname' ) ) );
			$fnprivate = trim ( $filter->filter ( $this->_request->getPost ( 'fnprivate' ) ) ); //new
			$lnprivate = trim ( $filter->filter ( $this->_request->getPost ( 'lnprivate' ) ) ); //new
			$gender = trim ( $filter->filter ( $this->_request->getPost ( 'gender' ) , null ) );
			$gprivate = trim ( $filter->filter ( $this->_request->getPost ( 'gprivate' ) ) );
			$dobprivate = trim ( $filter->filter ( $this->_request->getPost ( 'dobprivate' ) ) );
			$countrylive = trim ( $filter->filter ( $this->_request->getPost ( 'countrylive' ) ) );
			$citylive = trim ( $filter->filter ( $this->_request->getPost ( 'citylive' ) ) );
			$clprivate = trim ( $filter->filter ( $this->_request->getPost ( 'clprivate' ) ) );
			$countryfrom = trim ( $filter->filter ( $this->_request->getPost ( 'countryfrom' ) ) );
			$cfprivate = trim ( $filter->filter ( $this->_request->getPost ( 'cfprivate' ) ) );
			$cityfrom = trim ( $filter->filter ( $this->_request->getPost ( 'cityfrom' ) ) );
			$cityprivate = trim ( $filter->filter ( $this->_request->getPost ( 'cityprivate' ) ) );
			$langspeak = trim ( $filter->filter ( $this->_request->getPost ( 'langspeak' ) ) );
			$aboutme = trim ( $filter->filter ( $this->_request->getPost ( 'aboutme' ) ) );
			
			//other languages spoken
			

			$numSpokenLanguages = trim ( $filter->filter ( $this->_request->getPost ( 'language_count' ) ) );
			$arrayLanguages = array ();
			//$session = new Zend_Session_Namespace ( "registerSession" );
			
			$user_id = $session->userId;
			for($i = 2; $i <= $numSpokenLanguages; $i ++) {
				if (trim ( $filter->filter ( $this->_request->getPost ( 'Languages' . $i ) ) ) != '0') {
					$arrayLanguages [$i - 2] = trim ( $filter->filter ( $this->_request->getPost ( 'Languages' . $i ) ) );
				}
			}
			
			$exist = $user->findUniqueDisplayName ( $screenname );
			$jserror = '';
			if ($exist != null) {
				$jserror .= 'jQuery(\'#screennameerror\').html(\'The display name you entered is not available.Choose another.\');';
				$jserror .= 'jQuery(\'#screennameerror\').addClass(\'ErrorMessageIndividualDisplay\');';
			}
			
			if ($jserror != '') {
				//echo $errors;
				$jserror .= 'jQuery(\'#ErrorMessages\').html(\'Ooops, there was a problem with the information you entered below. Please correct the highlighted fields.\');';
				echo $jserror;
				return;
			}
			
			$view->screenname = $screenname;
			$view->firstname = $firstname;
			$view->lastname = $lastname;
			if (count ( $errors ) > 0) {
				$view->errors = $errors;
				$this->_response->setBody ( $view->render ( 'userBasicInfo.php' ) );
			} else {
				
				$data = array ('screen_name' => $screenname, 'first_name' => $firstname, 'last_name' => $lastname, 'firstname_priv' => $fnprivate, //1
								'lastname_priv' => $lnprivate, //2
								'gender' => $gender, 'gender_priv' => $gprivate, //3
								'country_live' => $countrylive, 'country_live_priv' => $clprivate, //4
								'city_live' => $citylive, 'city_live_priv' => $clprivate, //5
								'country_birth' => $countryfrom, 'country_birth_priv' => $cfprivate, //6
								'city_birth' => $cityfrom, 'city_birth_priv' => $cfprivate, //7
								'aboutme_text' => $aboutme );
				
				//put some values in the session
				$session->screenName = $screenname;
				$session->gender = $gender!= null ? $gender : 'm';
				if ($countryfrom != null || $countryfrom != '') {
					$session->countryFrom = $countryfrom;
				}
				$email = $session->email;
				$user->updateUser ( $email, $data );
				//insert languages spoken
				$user_language = new UserLanguage ( );
				for($z = 0; $z < count ( $arrayLanguages ); $z ++) {
					$data1 = array ('user_id' => $user_id, 'language_id' => $arrayLanguages [$z] );
					$user_language->insert ( $data1 );
				}
				//send welcome email
				$config = Zend_Registry::get ( 'config' );
				/*Send Mail to Friend for Request*/
				$mail = new SendEmail();
				$mail->set_from($config->email->confirmation->from);
				$mail->set_to( $email);
				$mail->set_subject('Welcome to GoalFace');
				$mail->set_template('welcomeemail');
				$variablesToReplaceEmail = array ('username' => $screenname, 
												  'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" )
												);
				$mail->set_variablesToReplace($variablesToReplaceEmail);
				$mail->sendMail();
				/*Send Mail to Friend for Request*/
			}
		
		} else { //show the form
			
			if($session->accountvalidatedAndProfileCreated == null){
				$this->validateRegisterProcess ();
			}

			$view->screenname = "";
			$view->firstname = "";
			$view->lastname = "";
			$language = new Language ( );
			$country = new Country ( );
			$countries = $country->selectCountries ();
			$view->languages = $language->selectLanguagesOrdered ();
			$session = new Zend_Session_Namespace ( "userSession" );
			$session->countries = $countries;
			
			if($session->accountvalidatedAndProfileCreated != null){
				$view->accountvalidatedAndProfileCreated = $session->accountvalidatedAndProfileCreated;
				$session->accountvalidatedAndProfileCreated = null;
			}
			$user = new User();
			$rowset = $user->findUniqueUser ( $session->email );
			$dob = explode ( '-', $rowset->dob );
			$session->year = $dob [0];
			$session->month = $dob [1];
			$session->day = $dob [2];
			$country = new Country ( );
			$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
		
			if ($countryLive != null) {
				$rowArray = $countryLive->toArray ();
				$session->countryLive = $rowArray ['country_name'];
				$session->countryLiveId = $rowArray ['country_id'];
			}
			$user_language = new UserLanguage ( );
			$firstlang = $user_language->findLanguageFirstReg ( $rowset->user_id );
			$session->firstLanguage = $firstlang->language_id;
			$session->userId = $rowset->user_id;	
			$view->actionTemplate = 'userBasicInfo.php';
			$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
		}
	
	}
	
	function skiptomyprofileAction() {
		
		//This is first login and account gets validated automatically
		$data = array ('first_login' => "1" ,
					   'flag_confirm' => "1");
		
		$user = new User ( );
		$country = new Country ( );
		$session = new Zend_Session_Namespace ( "userSession" );
		$email = $session->email;
		$user->updateUser ( $email, $data );
		
		$rowset = $user->findUniqueUser ( $email );
		$session->firstName = $rowset->first_name;
		$session->lastName = $rowset->last_name;
		$session->screenName = $rowset->screen_name;
		$session->mainPhoto = $rowset->main_photo;
		$session->bio = $rowset->aboutme_text;
		$session->user = $rowset;
		
		$session->dateJoined = $rowset->registration_date;
		$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
		if ($countryLive != null) {
			$rowArray = $countryLive->toArray ();
			$session->countryLive = $rowArray ['country_name'];
		}
		//This chunk of code is repeated in LoginController->dologin
		//$firstFriend = $user->findUniqueUser ( "fanfan@goalface.com" );
		/*
		$current = $user->find(1);
		$firstFriend = $current->current();
		$userFriend = new UserFriend();
		$data1 = array("user_id"=>$rowset->user_id,"friend_id"=>$firstFriend->user_id);
		$data2 = array("user_id"=>$firstFriend->user_id, "friend_id"=>$rowset->user_id);
		$userFriend->insert($data1);
		$userFriend->insert($data2);*/
		 
		/*insert world cup league favorites 72*/
		$user_league = new UserLeague();
		$data = array ('user_id' => $session->userId , 'competition_id' => 72, 'country_id' => 1 );
		$user_league->insert ( $data );
		/*insert world cup league favorites*/
		/*insert top leagues for country of birth*/
		$league_competition = new LeagueCompetition();
		$topCompetitionsByCountry = $league_competition->findTopCompetitionsByCountry($rowset->country_live);
		foreach ( $topCompetitionsByCountry as $competition ) {
			$data = array ('user_id' => $session->userId , 'competition_id' => $competition['competition_id'], 'country_id' => $rowset->country_live );
			$user_league->insert ( $data );
		}
		/*insert top leagues for country of birth*/
		

		$session->firsttimeviewprofile = 'true';
		$session->register = null;
		$urlGen = new SeoUrlGen();
		$this->_redirect ($urlGen->getUserProfilePage($rowset->screen_name,True));
	}
	
	function skipinviteAction() {
		//send a remainder email and forward to the photo page
		$this->_redirect ( '/user/uploadphoto' );
	
	}
	function skipuploadphotoAction() {
		//send a remainder email and forward to the photo page
		$this->_redirect ( '/user/addfavorities' );
	}
	
	function findplayersAction() {
		
		$filter = new Zend_Filter_StripTags ( );
		//$param1 = trim ( $filter->filter ( $this->_request->getPost ( 'value' ) ) );
		if ($this->_request->isPost ()) {
			$param1 = trim ( $filter->filter ( $this->_request->getPost ( 'value' ) ) );
		} else {
			$param1 = trim ( $filter->filter ( $this->getRequest ()->getParam ( 'q' ) ) );
		}
		$player = new Player ( );
		//$results = $player->findPlayers ( $param1 ) ;
		$results = $player->findPlayerByName ( $param1 );
		//echo '<ul>';
		foreach ( $results as $result ) {
			echo '<li id="' . $result ['player_id'] . '">' . $result ['player_name_short'] . '</li>';
			//echo $result ['player_name_short'] ."|" .$result ['player_id'] . "\n";
		}
		//echo '</ul>';
	

	}
	
	function savefavoritesAction() {
		
		//$session = new Zend_Session_Namespace ( "registerSession" );
		$session = new Zend_Session_Namespace("userSession");
		$filter = new Zend_Filter_StripTags ( );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Favorite Leagues";
		$user_team = new UserTeam ( );
		$user_player = new UserPlayer ( );
		$user_league = new UserLeague();
		$user_friend = new UserFriend(); 
			
		if ($this->_request->isPost ()) {
			
			$user_id = $session->userId;
			$screenName = $session->screenName;
			$gender = $session->gender;
			//delete teams , players and leagues if the user goes back in the browser
			$user_team->deleteAllUserTeam($user_id);
			$user_player->deleteAllUserPlayers($user_id);
			$user_league->deleteUserLeagueByUserId($user_id);
			$user_friend->deleteAllUserFriend($user_id);
			$user_friend->deleteUserFriend(1 , $user_id);
			
			$numTeams = trim ( $filter->filter ( $this->_request->getParam ( 'teamscounter' ) ) );
			$numNatTeams = trim ( $filter->filter ( $this->_request->getParam ( 'teamscounter' ) ) );
			$numPlayers = trim ( $filter->filter ( $this->_request->getParam ( 'playerscounter' ) ) );
			$numLeagues = trim ( $filter->filter ( $this->_request->getParam ( 'leaguescounter' ) ) );
			
			$arrayTeams = array ();
			$arrayPlayers = array ();
			$arrayLeagues = array ();
			
			//Club Teams
			for($i = 1; $i <= $numTeams; $i ++) {
				if (trim ( $filter->filter ( $this->_request->getParam ( 'clubteam' . $i ) ) ) != '') {
					$arrayTeams [$i - 1] = trim ( $filter->filter ( $this->_request->getParam ( 'clubteam' . $i ) ) );
				}
			}
			//National Teams
			for($y = 1; $y <= $numNatTeams; $y ++) {
				if (trim ( $filter->filter ( $this->_request->getParam ( 'nationalteam' . $y ) ) != '' )) {
					$arrayTeams [$i - 1] = trim ( $filter->filter ( $this->_request->getParam ( 'nationalteam' . $y ) ) );
					$i ++;
				}
			}
			//Players
			for($z = 1; $z <= $numPlayers; $z ++) {
				if (trim ( $filter->filter ( $this->_request->getParam ( 'playerId' . $z ) ) != '' )) {
					$arrayPlayers [$z - 1] = trim ( $filter->filter ( $this->_request->getParam ( 'playerId' . $z ) ) );
				}
			}
			for($w = 1; $w <= $numLeagues; $w ++) {
				if (trim ( $filter->filter ( $this->_request->getParam ( 'leagueselectid' . $w ) ) != '0' )) {
					$arrayLeagues [$w - 1] = trim ( $filter->filter ( $this->_request->getParam ( 'leagueselectid' . $w ) ) ) ."*" .trim ( $filter->filter ( $this->_request->getParam ( 'countryleagueselectid' . $w ) ) );
				}
			}
			//Leagues
			//Zend_Debug::dump($arrayTeams);
			//Zend_Debug::dump($arrayPlayers);
			//Zend_Debug::dump($arrayLeagues);
			
			//inserting the teams for a user
			
			
				
			$db = $user_team->getAdapter ();
			$db->beginTransaction ();
			
			// attempt a query.
			// if it succeeds, commit the changes;
			// if it fails, roll back.
			$activityAction = new ActivityAction();
			try {
				//insert userTeams
				foreach ( $arrayTeams as $teamId ) {
					if ($teamId != '') {
						$datateam = array ('user_id' => $user_id, 'team_id' => $teamId );
						$user_team->insert ( $datateam );
						$activityAction->addTeamActivity($screenName, $gender ,$user_id ,$teamId);
					}
				}
				//insert userPlayers
				foreach ( $arrayPlayers as $playerId ) {
					if ($playerId != '') {
						$dataplayer = array ('user_id' => $user_id, 'player_id' => $playerId );
						$user_player->insert ( $dataplayer );
						$activityAction->addPlayerActivity($screenName , $gender , $user_id , $playerId);
					}
				}
				//insert userLeagues
				foreach ( $arrayLeagues as $league ) {
					if ($league != '') {
						$countryleague = explode ( '*', $league );
						$dataleague = array ('user_id' => $user_id, 'competition_id' => $countryleague[0], 'country_id' => $countryleague[1] );
						$user_league->insert ( $dataleague );
						$activityAction->addCompetitionActivity($screenName, $countryleague[0] , $countryleague[1] , $gender ,$user_id );
					}
				}
				
				$db->commit ();
				$this->_redirect ( '/user/skiptomyprofile' );
			} catch ( Exception $e ) {
				$db->rollBack ();
				self::$logger->info($e->getMessage ());
				self::$logger->info($e->getTraceAsString ());
				echo $e->getMessage ();
			}
		
		}
	
	}
	
	function loadleaguesbycontinentAction() {
		
		$this->validateRegisterProcess ();
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Favorite Leagues";
		
		$lea_comp = new LeagueCompetition ( );
		$africa = $lea_comp->findLeaguesByContinent ( 5 ); //Africa
		$americas = $lea_comp->findLeaguesByContinent ( '2,3,4' ); //Americas
		$asiapacific = $lea_comp->findLeaguesByContinent ( '6,7' ); //Asia & Pacific Islands
		$europe = $lea_comp->findLeaguesByContinent ( 1 ); //Europe
		$fifa = $lea_comp->findLeaguesByContinent ( 8 ); //FiFA
		$view->africa = $africa;
		$view->americas = $americas;
		$view->asiapacific = $asiapacific;
		$view->europe = $europe;
		$view->fifa = $fifa;
		$view->nrafrica = count ( $africa );
		$view->nramericas = count ( $americas );
		$view->nrasiapacific = count ( $asiapacific );
		$view->nreurope = count ( $europe );
		$view->nrfifa = count ( $fifa );
		
		//find Leagues of country user lives in for preselect in form
		$session = new Zend_Session_Namespace ( "registerSession" );
		
		$tempcl = $session->countryLiveId;
		$tempc2 = $session->countryFrom;
		
		$userCompCountryLives = $lea_comp->findDomesticCompetitionsByCountry ( $tempcl ); //get from session
		//Country Im from
		$userCompCountryImFrom = array ();
		if ($tempcl != $tempc2) {
			$userCompCountryImFrom = $lea_comp->findDomesticCompetitionsByCountry ( $tempc2 );
		}
		$userCompAllCountries = array_merge ( $userCompCountryLives, $userCompCountryImFrom );
		$compPreSelected = null;
		$compIds = null;
		$cont = 1;
		$regionTemp = 0;
		
		foreach ( $userCompAllCountries as $comp ) {
			
			if ($comp ["region_group_id"] != $regionTemp) {
				
				if ($comp ["region_group_id"] != $regionTemp) {
					if ($regionTemp != 0) {
						$compPreSelected .= "]";
					}
				}
				if ($cont < sizeof ( $userCompAllCountries ) && $regionTemp != 0) {
					$compPreSelected .= ",";
				}
				
				$compPreSelected .= "'zone" . $comp ["region_group_id"] . "[]': ";
				$compPreSelected .= "[";
			}
			$compPreSelected .= "\"";
			$compPreSelected .= $comp ["country_name"] . "*" . $comp ["competition_name"] . "*" . $comp ["competition_id"] . "*" . $comp ["country_id"];
			$compPreSelected .= "\"";
			if ($cont < sizeof ( $userCompAllCountries )) {
				$compPreSelected .= ",";
			}
			
			$compIds .= "jQuery(\"#" . $comp ["competition_id"] . "\").attr('checked', 'true');";
			$cont ++;
			$regionTemp = $comp ["region_group_id"];
		}
		$compPreSelected .= "]";
		$view->compPreSelected = $compPreSelected;
		$view->leagueIds = $compIds;
		
		$view->actionTemplate = 'userLeagues.php';
		$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
	}
	
	function removeselectedcomp($array, $arrayRemove, $contName, $numCont) {
		
		$temp = 'aaaa';
		$session = new Zend_Session_Namespace ( "registerSession" );
		//echo sizeof($array) - sizeof($arrayRemove);
		if ((sizeof ( $array ) - sizeof ( $arrayRemove )) > 0) {
			echo '<h4 class="WithArrowToLeft"> ' . $contName . '</h4>';
		}
		//unset arrays items
		

		foreach ( $array as $key => $value ) {
			
			$arrayofLeagues = explode ( '*', $value );
			foreach ( $arrayRemove as $value2 ) {
				$arrayofLeaguesRemove = explode ( '*', $value2 );
				if ($arrayofLeagues [2] == $arrayofLeaguesRemove [0]) {
					unset ( $array [$key] );
				}
			}
		}
		//Zend::dump($array);
		

		foreach ( $array as $value ) {
			$arrayofLeagues = explode ( '*', $value );
			if (trim ( $temp != trim ( $arrayofLeagues [0] ) )) {
				//echo '<label for=\'cb1\' style=\'padding-right:3px;display:block;\'><strong>'  . $arrayofLeagues[0] . '</strong></label>';
				echo '<h6>' . $arrayofLeagues [0] . '</h6>';
			}
			//echo '<label id=\'LABEL'. $arrayofLeagues[2].'s\' for=\'cb'. $arrayofLeagues[2].'s\' style=\'padding-right:3px;display:block;\'><input name="zone'.$numCont.'s[]" value='. $arrayofLeagues[2]."*" . $arrayofLeagues[3]. ' type=\'checkbox\' id=\'cb'. $arrayofLeagues[2].'s\'>'  . $arrayofLeagues[1]. '</label>';//echo '<input name="zone1s[]" value=\'\' type=\'checkbox\' checked id=\'cb1480\'>'  . $arrayofLeagues[1] . '<br>';
			//echo '<input type=\'checkbox\' class=\'checkbox\' name=\'zone'.$numCont.'s[]" value='. $arrayofLeagues[2] ."*" . $arrayofLeagues[3]. 'id=\'cb'. $arrayofLeagues[2].'s\'><label>'  . $arrayofLeagues[1]. '</label>';
			$checkValue = $arrayofLeagues [2] . '*' . $arrayofLeagues [3];
			echo '<input type="checkbox" value=' . $checkValue . ' name="zone' . $numCont . 's[]" class="checkbox"/>';
			echo '<label>' . $arrayofLeagues [1] . '</label>';
			echo '<br />';
			$temp = $arrayofLeagues [0];
		}
		
		//Update the array of selected leagues stored in the session
		switch ($numCont) {
			case 1 :
				$session->arrayzone1 = $array;
				break;
			case 2 :
				$session->arrayzone2 = $array;
				break;
			case 3 :
				$session->arrayzone3 = $array;
				break;
			case 4 :
				$session->arrayzone4 = $array;
				break;
			case 5 :
				$session->arrayzone5 = $array;
				break;
		}
		//Zend::dump($array);
	}
	
	function selectcompetitionsAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Competitions";
		
		$arrayzone1 = $this->_request->getPost ( 'zone1' );
		$arrayzone2 = $this->_request->getPost ( 'zone2' );
		$arrayzone3 = $this->_request->getPost ( 'zone3' );
		$arrayzone4 = $this->_request->getPost ( 'zone4' );
		$arrayzone5 = $this->_request->getPost ( 'zone5' );
		
		$session = new Zend_Session_Namespace ( "registerSession" );
		$session->arrayzone1 = $arrayzone1;
		$session->arrayzone2 = $arrayzone2;
		$session->arrayzone3 = $arrayzone3;
		$session->arrayzone4 = $arrayzone4;
		$session->arrayzone5 = $arrayzone5;
		
		//echo '<strong>Your Favorite Selected Competitions</strong><br>';
		//AFRICA
		if ($arrayzone1 != false) {
			$this->writeSelectedComp ( $arrayzone1, 'Americas', 1 );
		}
		//AMERICAS
		if ($arrayzone2 != false) {
			$this->writeSelectedComp ( $arrayzone2, 'Europe', 2 );
		}
		//ASIA PACIFIC
		if ($arrayzone3 != false) {
			$this->writeSelectedComp ( $arrayzone3, 'Africa', 3 );
		}
		//EUROPE
		if ($arrayzone4 != false) {
			$this->writeSelectedComp ( $arrayzone4, 'Asia & Pacific Islands', 4 );
		}
		//FIFA
		if ($arrayzone5 != false) {
			$this->writeSelectedComp ( $arrayzone5, 'FIFA Competitions', 5 );
		}
	}
	
	function writeselectedcomp($array, $contName, $numCont) {
		$temp = 'aaaa';
		if (sizeof ( $array ) > 0) {
			echo '<h4 class="WithArrowToLeft"> ' . $contName . '</h2>';
		}
		foreach ( $array as $key => $value ) {
			$arrayofLeagues = explode ( '*', $value );
			if (trim ( $temp != trim ( $arrayofLeagues [0] ) )) {
				echo '<h6>' . $arrayofLeagues [0] . '</h6>';
			}
			//echo '<input class=\'checkbox\' name=\''zone'.$numCont.'s[]\'' value='. $arrayofLeagues[2]."*" . $arrayofLeagues[3]. ' type=\'checkbox\' id=\'cb'. $arrayofLeagues[2].'s\'>';
			//echo '<input type=\'checkbox\' class=\'checkbox\' name=\'zone'.$numCont.'s[]" value=' . $arrayofLeagues[2] ."*" . $arrayofLeagues[3]. ">;
			$checkValue = $arrayofLeagues [2] . '*' . $arrayofLeagues [3];
			echo '<input type="checkbox" value=' . $checkValue . ' name="zone' . $numCont . 's[]" class="checkbox"/>';
			echo '<label>' . $arrayofLeagues [1] . '</label>';
			echo '<br />';
			$temp = $arrayofLeagues [0];
		}
	}
	
	function removecompetitionsAction() {
		
		$session = new Zend_Session_Namespace ( "registerSession" );
		$arrayzone1 = $session->arrayzone1;
		$arrayzone2 = $session->arrayzone2;
		$arrayzone3 = $session->arrayzone3;
		$arrayzone4 = $session->arrayzone4;
		$arrayzone5 = $session->arrayzone5;
		
		$arrayzone1s = $this->_request->getPost ( 'zone1s' );
		//Zend_Debug::dump($arrayzone1s);
		$arrayzone2s = $this->_request->getPost ( 'zone2s' );
		$arrayzone3s = $this->_request->getPost ( 'zone3s' );
		$arrayzone4s = $this->_request->getPost ( 'zone4s' );
		$arrayzone5s = $this->_request->getPost ( 'zone5s' );
		
		//echo '<strong>Your Favorite Selected Competitions</strong><br>';
		

		if ($arrayzone1 != false && $arrayzone1s != false) {
			$this->removeSelectedComp ( $arrayzone1, $arrayzone1s, 'Americas', 1 );
		} else {
			if ($arrayzone1 != false) {
				$this->writeSelectedComp ( $arrayzone1, 'Americas', 1 );
			}
		}
		if ($arrayzone2 != false && $arrayzone2s != false) {
			$this->removeSelectedComp ( $arrayzone2, $arrayzone2s, 'Europe', 2 );
		} else {
			if ($arrayzone2 != false) {
				$this->writeSelectedComp ( $arrayzone2, 'Europe', 2 );
			}
		}
		if ($arrayzone3 != false && $arrayzone3s != false) {
			$this->removeSelectedComp ( $arrayzone3, $arrayzone3s, 'Africa', 3 );
		} else {
			if ($arrayzone3 != false) {
				$this->writeSelectedComp ( $arrayzone3, 'Africa', 3 );
			}
		}
		if ($arrayzone4 != false && $arrayzone4s != false) {
			$this->removeSelectedComp ( $arrayzone4, $arrayzone4s, 'Asia & Pacific Islands', 4 );
		} else {
			if ($arrayzone4 != false) {
				$this->writeSelectedComp ( $arrayzone4, 'Asia & Pacific Islands', 4 );
			}
		}
		if ($arrayzone5 != false && $arrayzone5s != false) {
			$this->removeSelectedComp ( $arrayzone5, $arrayzone5s, 'FIFA Competitions', 5 );
		} else {
			if ($arrayzone5 != false) {
				$this->writeSelectedComp ( $arrayzone5, 'FIFA Competitions', 5 );
			}
		}
	
	}
	
	function savecompetitionsAction() {
		$view = Zend_Registry::get ( 'view' );
		$view->title = "Saved Competitions";
		$filter = new Zend_Filter_StripTags ( );
		
		if ($this->_request->isPost ()) { //to insert basic info
			$arrayzone1s = $this->_request->getPost ( 'zone1s' );
			$arrayzone2s = $this->_request->getPost ( 'zone2s' );
			$arrayzone3s = $this->_request->getPost ( 'zone3s' );
			$arrayzone4s = $this->_request->getPost ( 'zone4s' );
			$arrayzone5s = $this->_request->getPost ( 'zone5s' );
		}
		$user_league = new UserLeague ( );
		
		try {
			//Zend_Debug::dump($this->_request->getPost());
			if ($arrayzone1s != null) {
				$this->insertUserCompetitions ( $user_league, $arrayzone1s );
			}
			if ($arrayzone2s != null) {
				$this->insertUserCompetitions ( $user_league, $arrayzone2s );
			}
			if ($arrayzone3s != null) {
				$this->insertUserCompetitions ( $user_league, $arrayzone3s );
			}
			if ($arrayzone4s != null) {
				$this->insertUserCompetitions ( $user_league, $arrayzone4s );
			}
			if ($arrayzone5s != null) {
				$this->insertUserCompetitions ( $user_league, $arrayzone5s );
			}
			
			//update the flag of first login
			$data = array ('first_login' => "1" );
			
			$user = new User ( );
			$country = new Country ( );
			//unbound registerSession
			$sessionRegister = new Zend_Session_Namespace ( "registerSession" );
			$email = $sessionRegister->email;
			Zend_Session::namespaceUnset ( "registerSession" );
			$session = new Zend_Session_Namespace ( "userSession" );
			
			$user->updateUser ( $email, $data );
			
			$rowset = $user->findUniqueUser ( $email );
			$session->firstName = $rowset->first_name;
			$session->lastName = $rowset->last_name;
			$session->screenName = $rowset->screen_name;
			$session->mainPhoto = $rowset->main_photo;
			//$thumb = explode ( '.', $rowset->main_photo );
			//$session->thumbPhoto = $thumb [0] . '_thumb.' . $thumb [1];
			$session->bio = $rowset->aboutme_text;
			
			$session->dateJoined = $rowset->registration_date;
			$countryLive = $country->fetchRow ( 'country_id = ' . $rowset->country_live );
			if ($countryLive != null) {
				$rowArray = $countryLive->toArray ();
				$session->countryLive = $rowArray ['country_name'];
			}
			$url = Zend_Registry::get ( "contextPath" ) . "/profiles/" . $rowset->screen_name;
			echo '<script>window.location = "' . $url . '"  </script>';
		
		} catch ( Exception $e ) {
			
			echo $e->getMessage ();
		}
	
	}
	
	function insertusercompetitions($user_league, $array) {
		
		$session = new Zend_Session_Namespace ( "registerSession" );
		$user_id = $session->userId;
		foreach ( $array as $value ) {
			$dataInsert = explode ( '*', $value );
			
			$data1 = array ('user_id' => $user_id, 'competition_id' => $dataInsert [0], 'country_id' => $dataInsert [1] );
			$user_league->insert ( $data1 );
		}
	
	}
	
	function addfavoritiesAction() {
		
		//$this->validateRegisterProcess ();
		$session = new Zend_Session_Namespace ( "registerSession" );
		$team = new Team ( );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Favorites";
		
		$image = $session->filename;
		//update the user tambien with bigPhoto image name
		$data = array ('main_photo' => $image );
		
		$user = new User ( );
		$email = $session->email;
		$user->updateUser ( $email, $data );
		
		$league = new Competitionfile ( );
		$row = $league->selectLeaguesByCountry ();
		
		$session->countries = $row;
		$view->countries = $row;
		
		$regiongroup = new RegionGroup();
		$regionRows = $regiongroup->getAllRegions();
		$view->regions = $regionRows;
		 
		$view->actionTemplate = 'userFavorites.php';
		$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
		
	}
	
	function wordimageAction() {
		
		//session_start();
		// generate 5 digit random number
		$rand = rand ( 10000, 99999 );
		// create the hash for the random number and put it in the session
		

		$session = new Zend_Session_Namespace ( "registerSession" );
		//$session->image_random_value = md5($rand);
		$session->image_random_value = $rand;
		// create the image
		$image = imagecreate ( 60, 30 );
		
		// use white as the background image
		$bgColor = imagecolorallocate ( $image, 255, 255, 255 );
		
		// the text color is black
		$textColor = imagecolorallocate ( $image, 0, 0, 0 );
		
		// write the random number
		imagestring ( $image, 5, 5, 8, $rand, $textColor );
		
		// send several headers to make sure the image is not cached
		// taken directly from the PHP Manual
		// Date in the past
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		
		// always modified
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		
		// HTTP/1.1
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		
		// HTTP/1.0
		header ( "Pragma: no-cache" );
		
		// send the content type header so the image is displayed properly
		header ( 'Content-type: image/jpeg' );
		
		// send the image to the browser
		imagejpeg ( $image );
		
		// destroy the image to free up the memory
		imagedestroy ( $image );
	
	}
	
	function generatecaptchaAction() {
		$width = 200; //isset($_GET['width']) ? $_GET['width'] : '120';
		$height = 50; //isset($_GET['height']) ? $_GET['height'] : '40';
		$characters = isset ( $_GET ['characters'] ) && $_GET ['characters'] > 1 ? $_GET ['characters'] : '6';
		
		$captcha = new CaptchaSecurityImages ( $width, $height, $characters );
	
	}
	
	function deletephotoAction() {
		$session = new Zend_Session_Namespace ( "registerSession" );
		$view = Zend_Registry::get ( 'view' );
		$view->title = "User Upload Photo";
		$view->actionTemplate = 'userUploadPhoto.php';
		$route = $_SERVER ['DOCUMENT_ROOT'] . Zend_Registry::get ( "contextPath" );
		$temp = $this->_request->getParam ( 'img', 0 );
		
		$image = $route . $GLOBALS ['imagesfolder'] . "/" . $temp;
		$jserror = null;
		$deleteMessage = null;
		//Check for a valid image.
		if (is_file ( $image )) {
			//Attempt to remove the image.
			if (unlink ( $image )) {
				//remove the thumbnail
				$deleteMessage = 'Photo successfully removed.';
				echo '<img src="' . Zend_Registry::get ( "contextPath" ) . '/public/images/silueta.gif" width="100" height="100">';
				$session->filename = '';
			
			} else {
				$deleteMessage = 'Photo could not be removed.';
			}
			
			//print the js messages
			$jserror .= '$(\'MainErrorMessage\').innerHTML = "' . $deleteMessage . '";';
			$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
			echo '<script>' . $jserror . ' </script>';
			return;
		}
	
	}
	
	function validateregisterprocess() {
		$session = new Zend_Session_Namespace ( "userSession" );
		$email = $session->register;
		if ($email == null) {
			$this->_redirect ( '/register' );
		}
	}
	
	function uploadphotoAction() {
		
		$this->validateRegisterProcess ();
		
		$view = Zend_Registry::get ( 'view' );
		$session = new Zend_Session_Namespace ( "registerSession" );
		
		$title = new PageTitleGen ( );
		$keywords = new MetaKeywordGen ( );
		$description = new MetaDescriptionGen ( );
		$view->title = $title->getPageTitle ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		$view->keywords = $keywords->getMetaKeywords ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		$view->description = $description->getMetaDescription ( null, PageType::$_USER_UPLOAD_PROFILE_PICTURE );
		
		$route = $_SERVER ['DOCUMENT_ROOT'] . Zend_Registry::get ( "contextPath" );
		$view->actionTemplate = 'userUploadPhoto.php';
		$imageUploadError = null;
		
		$jserror = null;
		
		if (strtolower ( $_SERVER ['REQUEST_METHOD'] ) == 'post') {
			
			if ($this->_request->getParam ( "photorights" ) != '1') {
				$jserror .= '$(\'MainErrorMessage\').innerHTML = "You must check the terms and conditions as outlined below.";';
				$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
				echo '<script>' . $jserror . ' </script>';
				$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
				return;
			}
			
			$_FILES ['myfile'] ['name'] = strtolower ( $_FILES ['myfile'] ['name'] );
			//If we have a valid file.
			if (isset ( $_FILES ['myfile'] )) {
				if ($_FILES ['myfile'] ['error'] == 0) {
					//Then we need to confirm it is of a file type we want.
					if (in_array ( $_FILES ['myfile'] ['type'], $GLOBALS ['allowedmimetypes'] )) {
						//verify the size of the file
						if ($_FILES ['myfile'] ['size'] / 1000 > 200.0) {
							//echo 'Image size cannot exceed 200 Kb';
							$imageUploadError = 'Image size cannot exceed 200 Kb';
							$jserror .= '$(\'MainErrorMessage\').innerHTML = "' . $imageUploadError . '";';
							$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
							echo '<script>' . $jserror . ' </script>';
							$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
							return;
						}
						//Then we can perform the copy.
						

						if (! move_uploaded_file ( $_FILES ['myfile'] ['tmp_name'], $route . $GLOBALS ['imagesfolder'] . "/" . $_FILES ['myfile'] ['name'] )) {
							$imageUploadError = "Ooops, there was a problem!  Try uploading your photo again.";
							//echo "There was an error uploading the file.";
						}
					}
				} else {
					//echo "There is an error uploadind file.";
					$imageUploadError = "You need to select a valid file and then click Upload Photo";
				}
				//echo "Success uploading image:" . $_FILES['myfile']['name'];
				$view->filename = $_FILES ['myfile'] ['name'];
				$session->filename = $_FILES ['myfile'] ['name'];
				if ($imageUploadError != null) {
					$jserror .= '$(\'MainErrorMessage\').innerHTML = "' . $imageUploadError . '";';
					$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
				} else {
					$jserror .= '$(\'MainErrorMessage\').innerHTML = "Photo was uploaded successfully.";';
					$jserror .= '$(\'ErrorMessages\').className = "ErrorMessagesDisplay";';
				}
				echo '<script>' . $jserror . ' </script>';
				
				$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
				
				return;
			}
		} else {
			$session->filename = '';
			$view->actionTemplate = 'userUploadPhoto.php';
			$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
		}
	
	}
	
	public function smtptestAction() {
		/*$config = array( 'port' => 25 ,
                'auth' => 'login',
                'username' => 'jorge@vgmediagroup.com',
                'password' => 'Pa55w0rd');

		Zend_Registry::set('smtp', new Zend_Mail_Transport_Smtp('mail.vgmediagroup.com', $config));*/
		
		$mail = new Zend_Mail ( );
		$mail->setBodyText ( 'This is the text of the mail.' ); //Alternatively using setBodyHtml for HTML messages
		$mail->setFrom ( 'info@vgmediagroup.com', 'Goalface Team' ); //Adds a 'from' header
		$mail->addTo ( 'kokovasquez@yahoo.com', 'KoKo' );
		$mail->setSubject ( 'TestSubject' );
		$mail->send ();
		echo 'Mail Sent';
	
	}
	
	public function tosAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "GoalFace Terms of Service";
		$view->actionTemplate = 'tos.php';
		$this->_response->setBody ( $view->render ( $this->siteUserRegister ) );
	}
	
	public function tospfAction() {
		
		$view = Zend_Registry::get ( 'view' );
		$view->title = "GoalFace Terms of Service - Printer Friendly";
		$this->_response->setBody ( $view->render ( 'tospf.php' ) );
	}
	public function createfbprofileAction(){				$session = new Zend_Session_Namespace ( 'userSession' );							if ($session->fbuser!=null) {									$filter = new Zend_Filter_StripTags ();						$session->firsttimeviewprofile = 'false';						$countryIdLive = trim ( $filter->filter ( $this->_request->getPost ( 'countrylive' ) ) );						$screenname = trim ( $filter->filter ( $this->_request->getPost ( 'screenname' ) ) );						$todays_date = date ( "Y-m-d H:i:s" );									$user_id  = null;						$country_live = null; 												$user=new User();			$result=$session->fbuser;				if ($session->user==null){					//$array1 = explode ( ',', '' ); //$result["CurrentAddress"]				//$array2 = explode ( ',', $result["PermanentAddress"]);								$country = new Country();												$data = array (	'screen_name'  => $screenname, 						'first_name'   => $result["first_name"],							'last_name'    => $result["last_name"],						'main_photo'	  => '',//$result["ImageLink"],						'city_birth'   => '',//$array1!=null?trim($array1[0]):null,						'city_live'    => '',//$array2!=null?trim($array2[0]):null,						'country_live' => $countryIdLive,						'country_birth'=> $countryIdLive,						'dob' => date('Y-m-d', strtotime($result["birthday"])),						'gender' => ($result["gender"]=='male'?'m':'f'),						'email' => $result["email"],						'registration_date' => trim ( $todays_date ),						'flag_confirm' => '1',						'first_login' => '1',						'facebookid' => $result['id'],						'facebookaccesstoken' => $session->daccessToken						);				//$user=new User();				$user_id = $user->insert( $data );				$user = $user->findUniqueUser ($result["email"]);				$session->user=$user;				$user_league = new UserLeague();				$data = array ('user_id' => $user_id , 'competition_id' => 72, 'country_id' => 1 );				$user_league->insert( $data );				$league_competition = new LeagueCompetition();								if($countryIdLive != null){					$topCompetitionsByCountry = $league_competition->findTopCompetitionsByCountry($countryIdLive);					foreach ( $topCompetitionsByCountry as $competition ) {						$data = array ('user_id' => $user_id , 'competition_id' => $competition['competition_id'], 'country_id' => $countryIdLive );						$user_league->insert ( $data );										}								}											$session->firsttimeviewprofile = 'true';												$config = Zend_Registry::get ( 'config' );												$mail = new SendEmail();				$mail->set_from($config->email->confirmation->from);				$mail->set_to( $result["email"]);				$mail->set_subject('Welcome to GoalFace');				$mail->set_template('welcomeemail');				$variablesToReplaceEmail = array ('username' => $screenname,													'contextPath' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get ( "contextPath" ));													$mail->set_variablesToReplace($variablesToReplaceEmail);													$mail->sendMail();				$valaux="";			}else { 								$user=new User();				$user_id = $session->user->user_id;				if($session->user->facebookid==null && $result['id']!=null){					//echo ' cambio AT '.$session->accesToken.' - ';					$user->updateDateFB($user_id,$result['id']);				}				echo ' El Usuario '.$session->user->screen_name." ya se encuentra registrado. Su id es: ".$user_id." ";				$valaux="T";										$user->updateAccesTokenUser($user_id,$session->daccessToken);					/*$user=new User();								$user->deleteUser($user_id);*/						}						$session->mainPhoto = $session->user->main_photo;			$session->email = $session->user->email;			$session->userId = $session->user->user_id;			$session->screenName = $session->user->screen_name;						if($session->redirectPage!=null){				$this->_redirect($session->redirectPage);			}						if ($valaux != "") {				$this->_redirect(Zend_Registry::get ( "contextPath" ) . "/index");			}						echo 'window.location = "' .Zend_Registry::get ( "contextPath" ) . '/index' . '"';		}else{			$this->_redirect("/sign-in");		}	}
}
?>