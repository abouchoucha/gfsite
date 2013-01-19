<?php

/**
 * CommonActionsController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class CommonController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function editgoalshoutAction() {
		
		$comment = new Comment();
		
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$elementid = $this->_request->getParam ( 'elementid', 0 );
		$dataEditted = $this->_request->getParam ( 'dataEditted', null );
		$typeofcomment = $this->_request->getParam ( 'typeofcomment', null );
		
		$comment->updateComment($commentId , $dataEditted );
		
		self::loadAllCommentsByType($elementid , $typeofcomment);
	}
	
	public function deletegoalshoutAction() {
		
		$mc = new Comment ( );
		$session = new Zend_Session_Namespace ( 'userSession' );
		//first delete goalshout
		$commentId = $this->_request->getParam ( 'id', 0 );
		$elementid = $this->_request->getParam ( 'elementid', 0 );
		$typeofcomment = $this->_request->getParam ( 'typeofcomment', null );
		
		//find message id in order to find the owner of the message
		$comment = $mc->fetchRow ( "comment_id = " . $commentId );
		
		$userWhoDeletesComment = 2; //if 1 = message owner , 2 = profile owner
		
		if($session->userId == $comment->friend_id){
			$userWhoDeletesComment = 1;
		}
		
		$mc->updateDeleteComment($commentId , $userWhoDeletesComment );
		
		self::loadAllCommentsByType($elementid , $typeofcomment);
	
	}
	
	public function reportgoalshoutAction() {
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
		
		self::loadAllCommentsByType($elementid , $typeofcomment);
		
	}
	
	public function loadAllCommentsByType($elementId , $typeofcomment){
		$view = Zend_Registry::get ( 'view' );
		$comment = new Comment();
		$regionComments = $comment->findComments($elementId , $typeofcomment , 10);
		$totalComments = $comment->findComments($elementId , $typeofcomment); 
		$view->totalGoalShouts = count($totalComments);
		$view->comments = $regionComments;
		$view->elementid = $elementId;
		$view->typeofcomment = $typeofcomment;
		$this->_response->setBody ( $view->render ( 'scripts/goalshouttemplate.phtml' ) );
		
	}

}
?>

