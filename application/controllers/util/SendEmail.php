<?php
require_once 'Zend/Registry.php';
require_once 'application/controllers/util/Template.php';

class SendEmail {
	
	private $_from;
	private $_to;
	private $_subject;
	private $_template;
	private $_variablesToReplace;
	private $_message;
	private $_fileContents;
	private $_fileName;
	/**
	 * @return unknown
	 */
	public function get_message() {
		return $this->_message;
	}
	/**
	 * @param $_fileName the $_fileName to set
	 */
	public function set_fileName($_fileName) {
		$this->_fileName = $_fileName;
	}

	/**
	 * @param $_fileContents the $_fileContents to set
	 */
	public function set_fileContents($_fileContents) {
		$this->_fileContents = $_fileContents;
	}

	/**
	 * @return the $_fileName
	 */
	public function get_fileName() {
		return $this->_fileName;
	}

	/**
	 * @return the $_fileContents
	 */
	public function get_fileContents() {
		return $this->_fileContents;
	}

	
	/**
	 * @param unknown_type $__message
	 */
	public function set_message($_message) {
		$this->_message = $_message;
	}

	
	/**
	 * @return unknown
	 */
	public function get_from() {
		return $this->_from;
	}
	
	/**
	 * @return unknown
	 */
	public function get_subject() {
		return $this->_subject;
	}
	
	/**
	 * @return unknown
	 */
	public function get_template() {
		return $this->_template;
	}
	
	/**
	 * @return unknown
	 */
	public function get_to() {
		return $this->_to;
	}
	
	/**
	 * @return unknown
	 */
	public function get_variablesToReplace() {
		return $this->_variablesToReplace;
	}
	
	/**
	 * @param unknown_type $_from
	 */
	public function set_from($_from) {
		$this->_from = $_from;
	}
	
	/**
	 * @param unknown_type $_subject
	 */
	public function set_subject($_subject) {
		$this->_subject = $_subject;
	}
	
	/**
	 * @param unknown_type $_template
	 */
	public function set_template($_template) {
		$this->_template = $_template;
	}
	
	/**
	 * @param unknown_type $_to
	 */
	public function set_to($_to) {
		$this->_to = $_to;
	}
	
	/**
	 * @param unknown_type $_variablesToReplace
	 */
	public function set_variablesToReplace($_variablesToReplace) {
		$this->_variablesToReplace = $_variablesToReplace;
	}
	
	public function sendMail(){
		
		$templater = new Template();
		$messageFromTemplate = $templater->buff_template($this->_template);

		// added to force return path email to the same as _from email address
        $tr = new Zend_Mail_Transport_Sendmail('-f'.$this->_from);
        Zend_Mail::setDefaultTransport($tr);

		$messageToSend = $templater->parse_variables($messageFromTemplate , $this->_variablesToReplace);
		$mail = Zend_Registry::get ( 'mail' );
		$mail->clearRecipients();
		$mail->setBodyHtml ( $messageToSend );
		$mail->setBodyText ( strip_tags($messageToSend));
		$mail->clearFrom();
		$mail->setFrom ( $this->_from, 'GoalFace.com' );
		$mail->addTo ( $this->_to );
		$mail->clearSubject();
		$mail->setSubject ($this->_subject );
		//Zend_Debug::dump($messageToSend);
		try {
			$mail->send ();
		} catch ( Exception $e ) {
			echo $e->getMessage ();
			return;
		}
		
	}
	
	public function sendMailWithAttachment(){
		
		$templater = new Template();
		$messageFromTemplate = $templater->buff_template($this->_template);

		// added to force return path email to the same as _from email address
        $tr = new Zend_Mail_Transport_Sendmail('-f'.$this->_from);
        Zend_Mail::setDefaultTransport($tr);

		$messageToSend = $templater->parse_variables($messageFromTemplate , $this->_variablesToReplace);
		$mail = Zend_Registry::get ( 'mail' );
		$mail->setBodyHtml ( $messageToSend );
		$mail->setBodyText ( strip_tags($messageToSend));
		$mail->setFrom ( $this->_from, 'GoalFace Team' );
		$mail->addTo ( $this->_to );
		$mail->setSubject ($this->_subject );
		$fileContents = file_get_contents($this->_fileContents );
		//Zend_Debug::dump($fileContents);
		$attachment = $mail->createAttachment($fileContents);
		$attachment->filename = $this->_fileName;
		//$attachment->type        = 'image/jpeg';
		//$attachment->disposition = Zend_Mime::DISPOSITION_INLINE;
		//$attachment->encoding    = Zend_Mime::ENCODING_8BIT;
		//Zend_Debug::dump($messageToSend);
		try {
			$mail->send ();
		} catch ( Exception $e ) {
			echo $e->getMessage ();
			return;
		}
		
	}
	
	
	
	
	
	public function sendSimpleEMail(){
		
		
		$mail = Zend_Registry::get ( 'mail' );
		$mail->setBodyHtml ( $this->_message );
		$mail->setBodyText ( strip_tags( $this->_message));
		$mail->setFrom ( $this->_from, 'GoalFace Team' );
		$mail->addTo ( $this->_to );
		$mail->setSubject ($this->_subject );
		//Zend_Debug::dump($messageToSend);
		try {
			$mail->send ();
		} catch ( Exception $e ) {
			echo $e->getMessage ();
			return;
		}
		
	}

}

?>
