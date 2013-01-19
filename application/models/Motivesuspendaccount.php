<?php
class Motivesuspendaccount extends Zend_Db_Table {
	
	protected $_primary = "motivesuspendaccount_id";
	protected $_name = "motivesuspendaccount";
	
	public function findMotivesSuspendAccount(){
		$db = $this->getAdapter ();
        $sql = "select motivesuspendaccount_id,motivesuspendaccount_text from motivesuspendaccount where motivesuspendaccount_enabled=1";
        $result = $db->query ( $sql );
        $rows = $result->fetchAll ();
		return $rows;
	}
	
}
?>