<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require_once 'util/SameClassName.php';
/**
 * Description of RestController
 *
 * @author VASQUEZJ
 */
require_once 'Zend/Service/Twitter.php';
 
class TestController extends Zend_Controller_Action {
	
	function init() {

	}
	
	public function indexAction() {
            //retrive the config handle
            $this->config = Zend_Registry::get('config');
            $view = Zend_Registry::get ( 'view' );

            $username = 'kokovasquez';
            $password = 'ch0ch3r@z';

            $view->username = $username;

            //Connect to the twitter service
            //$twitter = new Zend_Service_Twitter($this->config->twitter->username,$this->config->twitter->pswd);
            $twitter = new Zend_Service_Twitter($username,$password);
            //retrive last 5 postings
            $response = $twitter->status->friendsTimeline();
            $view->posts = $response;

            //Zend_Debug::dump($response);


            // we create the form
            $updateForm = new Zend_Form();
		
            $status = new Zend_Form_Element_Text('status');
            $status->setLabel('New Twitter Status')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

            $submit = new Zend_Form_Element_Submit('submit');
            $submit->setLabel('Update');

            $updateForm->addElements(array($status, $submit));

            // we send the form to the view
            $view->updateForm = $updateForm;
		
		
            // we check if there was any POST
            if ($this->getRequest()->isPost()){
                $formData = $this->_request->getPost();
                // checking if the form data is valid (if we have a new status or not)
                if ($view->updateForm->isValid($formData)){
                    // our form is valid, we can update our status
                    $twitterStatus = $formData['status'];

                    $response = $twitter->status->update($twitterStatus);
                }
            }

            $view->actionTemplate = 'twitter.php';
            $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );

	}

        public function updatetweetsAction () {



        }

}

?>
