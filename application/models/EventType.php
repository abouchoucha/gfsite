<?php

class EventType extends Zend_Db_Table_Abstract {
	
	protected $_name = 'eventtype';
	protected $_primary = "event_type_id";
	
	public function findEventTypeById($eventId) {
		
		$db = $this->getAdapter ();
		$sql = " select * from eventtype where event_type_id = '". $eventId ."'";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}

}

?>
