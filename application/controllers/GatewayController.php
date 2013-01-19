<?php

/**
 * GatewayController
 * 
 * @author
 * @version  
 */

require_once 'Zend/Controller/Action.php';

class GatewayController extends Zend_Controller_Action {
	public function init(){
		$this->_helper->viewRenderer->setNoRender();
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		//$view = Zend_Registry::getInstance()->get('view');
		//echo $this->view->getScriptPaths();
		//$this->getHelper('ViewRenderer')->setNoRender();      
		$server = new Zend_Amf_Server();
		$server->setProduction(true);
		$server->setClass('PruebaService');
		//$server->addDirectory( dirname(__FILE__) . '/../services/' );
		echo($server->handle());
	}
}

