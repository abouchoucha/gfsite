<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once 'application/controllers/util/Constants.php';
class ActivityFrecuency extends Zend_Db_Table_Abstract {
	
	protected $_primary = "alert_frecuency_id";
	protected $_name = 'alertfrecuency';
	
	public function selectActivityFrecuencyByCategory($categoryId) {
		$db = $this->getAdapter ();
		$sql = " select * from alertfrecuency ";
		$sql.= " where activitycat_id = " . $categoryId;
		//echo $sql ."<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	

}
?>
