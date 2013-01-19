<?php
class Suspendaccount extends Zend_Db_Table {
	
	protected $_primary = "user_id";
	protected $_name = "suspendaccount";
	
	public function isSuspendAccount($email){
		$db = $this->getAdapter ();
        $sql = "select motivesuspendaccount_id,motivesuspendaccount_text from motivesuspendaccount where motivesuspendaccount_enabled=1";
        $result = $db->query ( $sql );
        $rows = $result->fetchAll ();
		return $rows;
	}
	
}
?>