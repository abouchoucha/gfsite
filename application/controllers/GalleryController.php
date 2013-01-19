<?php


class GalleryController extends GoalFaceController  {

	function init(){
		$this->updateLastActivityUserLoggedIn();
	}
	public function showGalleryPhotoAction () {
        
  	  $view = Zend_Registry::get ( 'view' );
			$view->title = "Photo Gallery";
			
			
			$view->actionTemplate = 'viewgallery.php';
			$this->_response->setBody ( $view->render ( 'site.tpl.php' ) );
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

}
?>
