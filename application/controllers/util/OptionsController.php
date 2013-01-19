<?php
require_once 'GoalFaceController.php';
require_once 'util/Common.php';
require_once 'Zend/Session.php';
require_once 'Zend/Debug.php';
require_once 'FeedBack.php';
require_once 'RegisterBeta.php';

class OptionsController extends GoalFaceController {
	
	private static $logger;
	private static $logger2;
	
	public function init(){
		parent::init();
		self::$logger = Zend_Registry::get("logger");
		self::$logger2 = Zend_Registry::get("logger2");
		$this->updateLastActivityUserLoggedIn();
	}
	
	public function reportbugAction(){
	  $request = $this->getRequest();
      //Zend_Debug::dump($request->getPost ());
						
    if ($request->isPost ()) {  
      $filters = array (
  					'bugtextarea' => 'StringTrim',
                    'requestedpage' => 'StringTrim',
      				 
  				);
    //Validate textare message is not less than 1 character and not more than 200
      $validation = array(
                       'bugtextarea' => array (
                          array ('StringLength', 1, 200 )                    
                       ),
                       'type'=> array (
									        array ('StringLength', 1, 10 )
					   ),
					   'requestedpage'=> array (
									        array ('StringLength', 1, 500),	
						)			        
                    );
                    
      //Initializa User Session
      $session = new Zend_Session_Namespace ( "userSession" );
      $errors = null;
	  $jserror = '';             
      //Initialize Zend_Filter_Input (ZFI) passing it the entire getPost() array
			$zfi = new Zend_Filter_Input ( $filters, $validation, $request->getPost () );
			if ($zfi->isValid ()) {
			   	//Fetch the data from zfi directly and create an array for Zend_Db to insert to Feedback tbl
				  $dataArray = array ( );
				  if($session->email == null){
					   $dataArray ['name'] = '';
				  }else {
					   $dataArray ['name'] = $session->screenName;
				  }	
				  if($session->email == null){
					   $dataArray ['emailaddress'] = '';
				  }else {
					   $dataArray ['emailaddress'] = $session->email;
				  }	
				  $dataArray ['subject'] = 'Report Bug Message';
				  $dataArray ['message'] = $zfi->bugtextarea;
				  $dataArray ['type'] = $zfi->type;
				  $dataArray ['404page'] = $zfi->requestedpage ;
				  
			if($session->email == null ){	  
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
				  
			}	  
				  $feedback = new FeedBack() ;
				  try{
  					//Zend_Debug::dump($clean);
  					$rowsUpdated = $feedback->insert ($dataArray );
  					if($rowsUpdated > 0){
  						self::$logger->debug('FeedBack table updated correctly');
  					}else {
  						self::$logger->debug('There was an error updating Feedback Table');
  						echo 'There was an error updating the Error Repor Form.Contact goalface support team';
  					}
				  } 
				  catch ( Exception $e ) {
					   echo $e->getMessage();
					   return;
				  }	  
      } else {
          //The form didn't validate, get the messages from ZFI
				  self::$logger->debug('Invalida data entered');
				  self::$logger->debug($zfi->isValid ());
      } 
    } else {
      // If form was not submitted  ..Do we need to get in here 
    }
  }
	
	public function feedbackAction() {
		$request = $this->getRequest();
		//Zend_Debug::dump($request->getPost ());
		//Determine if processing a post request
		
		if ($request->isPost ()) {
			//Filter tags from the name field
			$filters = array (
							'name' => 'StripTags',
							'subject' => 'StripTags'
						);
			//Validate name is not less than 1 character and not more than 64
			$validation = array ('name' => array (
									array ('StringLength', 1, 30 ),
									Zend_Filter_Input::MESSAGES => array(
										array(
											Zend_Validate_StringLength::TOO_SHORT => 'Name cannot be blank',
											Zend_Validate_StringLength::TOO_LONG => 'Name field is too long'
											)
										)
									),
								'emailaddress'=>array (
								 	array('StringLength', 1, 64) ,
								 		  'EmailAddress',
								 		  'breakChainOnFailure' => true
								 	),
								'messagefeedback' => array (
									array ('StringLength', 1, 500 )
									),
								 'subjectfeedback'=> array (
									array ('StringLength', 1, 30 )
									),
								  'type'=> array (
									array ('StringLength', 1, 10 )
									)	
								);
								
			//validation the image code
			$session = new Zend_Session_Namespace ( "userSession" );
			
			//comment for BETA PHASE - UNCOMMMENT when going LIVE
			/*require_once('application/controllers/util/recaptchalib.php');
			$config = Zend_Registry::get ( 'config' );
			$privatekey  = $config->captcha->private->key;
			
			
			if($session->email == null){
				$errors = null;
				$jserror = '';
				$resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);
				if (!$resp->is_valid) {	
					//echo 'Sorry the number you entered does not match the one in the image. Please try again' ;
					//return ;
					$jserror .= 'Recaptcha.reload();';
					$jserror .= 'jQuery(\'html, body\').animate({scrollTop:0}, \'slow\');';
					$errors .= "Sorry the number you entered does not match the one in the image. Please try again.<br>";
					$jserror .= 'jQuery(\'#recaptcha_response_fielderror\').html(\'Sorry the characters you entered does not match the one in the image. Please try again.\');';
					$jserror .= 'jQuery(\'#recaptcha_response_fielderror\').addClass(\'ErrorMessageIndividualDisplay\');';
				
				}
				if (isset ( $errors )) {
					echo $jserror;
					return;
				}					
			}					
			*/	//comment for BETA PHASE - UNCOMMMENT when going LIVE	
      			
			//Initialize Zend_Filter_Input (ZFI) passing it the entire getPost() array
			$zfi = new Zend_Filter_Input ( $filters, $validation, $request->getPost () );
			//If the validators passed this will be true
			//Zend_Debug::dump($zfi->isValid ());
			if ($zfi->isValid ()) {
				//Fetch the data from zfi directly and create an array for Zend_Db
				$clean = array ( );
				
				if($session->email == null){
					$clean ['name'] = $zfi->name;
				}else {
					$clean ['name'] = $session->screenName;
				}	
				if($session->email == null){
					$clean ['emailaddress'] = $zfi->emailaddress;
				}else {
					$clean ['emailaddress'] = $session->email;
				}	
				$clean ['subject'] = $zfi->subjectfeedback;
				$clean ['message'] = $zfi->messagefeedback;
				$clean ['type'] = $zfi->type;
				//Create an instance of the customers table and insert the $clean row
				$feedback = new FeedBack() ;
				
        try{
					//Zend_Debug::dump($clean);
					$rowsUpdated = $feedback->insert ($clean );
					if($rowsUpdated > 0){
						self::$logger->debug('FeedBack table updated correctly');
					}else {
						self::$logger->debug('There was an error updating account info');
						echo 'There was an error updating feedback.Contact goalface support team';
					}
				} 
          catch ( Exception $e ) {
					   echo $e->getMessage();
					   return;
				  }	
				//Redirect to the display page after adding
				//echo 'Thanks for sending a feedback.';
			} else {
				//The form didn't validate, get the messages from ZFI
				self::$logger->debug('Entered to the invalida data');
				self::$logger->debug($zfi->isValid ());
			}
		}else {
			$view = Zend_Registry::get ( 'view' );
			$view->title = "Goalface Feedback";
			$sent = $this->_request->getUserParam ( 'sent' ,'ko' );
			$view->sent = $sent; 
			$config = Zend_Registry::get ( 'config' );
			$view->captchapublickey  = $config->captcha->public->key;
			$view->actionTemplate = 'feedback.php';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
			
		}
		//Not a post request, check for flash messages and expose to the view
//		if ($this->getHelper ( 'FlashMessenger' )->hasMessages ()) {
//			$this->view->messages = $this->getHelper ( 'FlashMessenger' )->getMessages ();
//		}
		//The view renderer will now automatically render the add.phtml template
	}
	
	
	public function preregisterAction(){
		
		$email = $this->_request->getParam ( 'email' );
		$name = $this->_request->getParam ( 'name', "" );
		$sql_dob = $this->_request->getParam ( 'sql_dob', "" );
		$country= $this->_request->getParam ( 'country', "" );
		$language= $this->_request->getParam ( 'language', "");		
		$preregister = new RegisterBeta();
		
		$data = array ('email' => $email, 
					   'name' 	   => $name,
					   'dob'       => $sql_dob,
					   'country'   => $country,
					   'language'  => $language,
					   'regcreated' => trim ( date ( "Y-m-d H:i:s" ) )
			  		   );
		$preregister->insert ( $data );

               //prepare to send email
               $conf_to = "$email";
               $conf_subject = "Thank you for your interest in GoalFace!";
               $conf_body   = "Hi ".$conf_to.",<BR><BR>Your GoalFace application has been received and is currently under review by our scouts. If
              things check out, you'll receive an email invitation to be among the elite on GoalFace. Please remember to
              include <a href=\"mailto:community@goalface.com\">community@goalface.com</a> in your email address book to
              ensure you receive the invitation.<br><br>Cheers!<br>The GoalFace Team\r\n";
               $conf_headers  = 'MIME-Version: 1.0' . "\r\n";
               $conf_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               // Additional headers
               $conf_headers .= 'From: GoalFace Team <community@goalface.com>' . "\r\n";
               $conf_headers .= 'Reply-To: community@goalface.com' . "\r\n";

                // Send the confirmation mail using PHPs mail() function
                mail($conf_to, $conf_subject, $conf_body, $conf_headers);


	}
	
	
	
		

    public function aboutAction() {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface About";
      $view->actionTemplate = 'about.php';
      $this->breadcrumbs->addStep ('About', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
    }

    public function guidelinesAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Guidelines";
      $this->breadcrumbs->addStep ('Guidelines', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'guidelines.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

   public function termsAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Terms";
      $this->breadcrumbs->addStep ('Terms', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'terms.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

   public function privacyAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Privacy";
      $this->breadcrumbs->addStep ('Privacy', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'privacy.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

   public function safetytipsAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Safety Tips";
      $this->breadcrumbs->addStep ('Safety Tips', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'safetytips.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

   public function contactusAction () {
      $view = Zend_Registry::get ( 'view' );
      $sent = $this->_request->getUserParam ( 'sent' ,'ko' );
	  $view->sent = $sent; 
      $view->title = "Goalface Contact us";
      $this->breadcrumbs->addStep ('Contact Us', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $config = Zend_Registry::get ( 'config' );
	  $view->captchapublickey  = $config->captcha->public->key;
      $view->actionTemplate = 'contactus.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }


   public function advertiseAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Advertise with us";
      $this->breadcrumbs->addStep ('Advertise with Us', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'advertise.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }


     public function disclaimerAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Disclaimer";
      $this->breadcrumbs->addStep ('Disclaimer', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'disclaimer.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

   public function trademarkAction () {
      $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface Trademark";
      $this->breadcrumbs->addStep ('Trademark', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'trademarks.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

    }

	public function helpAction () {
        
  	  $view = Zend_Registry::get ( 'view' );
      $view->title = "Goalface FAQ/Help";
      $this->breadcrumbs->addStep ('Help/FAQ', "");
      $this->view->breadcrumbs = $this->breadcrumbs;
      $view->actionTemplate = 'help.php';
      $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
  }	
	
	public function addAction() {
		
		$request = $this->getRequest();
		//Determine if processing a post request
		
		if ($request->isPost ()) {
			//Filter tags from the name field
			$filters = array ('name' => 'StripTags' );
			//Validate name is not less than 1 character and not more than 64
			$validation = array ('name' => array (array ('StringLength', 1, 64 ) ) );
			//Initialize Zend_Filter_Input (ZFI) passing it the entire getPost() array
			$zfi = new Zend_Filter_Input ( $filters, $validation, $request->getPost () );
			
			//If the validators passed this will be true
			if ($zfi->isValid ()) {
				//Fetch the data from zfi directly and create an array for Zend_Db
				$clean = array ( );
				$clean ['name'] = $zfi->name;
				//Create an instance of the customers table and insert the $clean row
				$feedback = new FeedBack() ;
				$feedback->insert ( $clean );
				//Redirect to the display page after adding
				$this->getHelper ( 'redirector' )->goto ( 'index' );
			} else {
				//The form didn't validate, get the messages from ZFI
				foreach ( $zfi->getMessages () as $field => $messages ) {
					//Put each ZFI message into the FlashMessenger so it shows on the form
					foreach ( $messages as $message ) {
						$this->getHelper ( 'FlashMessenger' )->addMessage ( $field . ' : ' . $message );
					}
				}
				//Redirect back to the input form, but with messages
				$this->getHelper ( 'redirector' )->goto ( 'add' );
			}
		}else {
			$view = Zend_Registry::get ( 'view' );
		$view->title = "Goalface Feedback";
		$view->actionTemplate = 'feedback.php';
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
			
		}
		//Not a post request, check for flash messages and expose to the view
//		if ($this->getHelper ( 'FlashMessenger' )->hasMessages ()) {
//			$this->view->messages = $this->getHelper ( 'FlashMessenger' )->getMessages ();
//		}
		//The view renderer will now automatically render the add.phtml template
		

		
	
	}
	
	/**
	 * This method is called when framework did not find 
	 * controller that will handle the request.
	 */
	public function noRouteAction() {
		header ( 'HTTP/1.1 404 Not found' );
		//$view = new Zend_View();
		$view = Zend_Registry::get ( 'view' );
		$view->setScriptPath ( './_/application/views' );
		$view->pageTitle = '404 error';
		echo $view->render ( 'Pages/ServicePages/404.php' );
	}
	
//	public function __call($action, $arguments) {
//		echo $action;
//		$this->_redirect ( '/Login/' );
//	}

}
