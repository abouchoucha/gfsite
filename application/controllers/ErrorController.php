<?php

/**
 * The error controller
 *
 */
class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        // Ensure the default view suffix is used so we always return good 
        // content
        $this->_helper->viewRenderer->setViewSuffix('phtml');

        // Grab the error object from the request
        $errors = $this->_getParam('error_handler'); 
        
        

        // $errors will be an object set as a parameter of the request object, 
        // type is a property
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER: 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION: 
                // 404 error -- controller or action not found - Page Not Found
                $view = Zend_Registry::get ( 'view' );
                $this->getResponse()->setHttpResponseCode(404); 
                $view->requestURL = $_SERVER["REQUEST_URI"];
                $this->view->message = 'Page not found';
                //$this->_response->setBody ( $this->view->render ( '404.phtml' ) ) ;
                $view->actionTemplate = '404.php';
                $view->is404Page = 'true';
                $config = Zend_Registry::get ( 'config' );
                $view->captchapublickey  = $config->captcha->public->key;
                $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
                break; 
            default: 
                // application error - Internal Error
                $view = Zend_Registry::get ( 'view' );
                $this->getResponse()->setHttpResponseCode(500); 
                $this->view->message = 'Application error'; 
                //$this->_response->setBody ( $this->view->render ( '500.phtml' ) ) ;
                $view->actionTemplate = '500.php';
                $view->is404Page = 'true';
                $this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
                break; 
        } 
        
	// pass the environment to the view script so we can conditionally 
        // display more/less information
        $this->view->env = $this->getInvokeArg('env'); 
        
        // pass the actual exception object to the view
        $this->view->exception = $errors->exception; 
        
        // pass the request to the view
        $this->view->request   = $errors->request;     	
    }
}

?>