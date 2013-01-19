<?php 
class Groupp extends Zend_Db_Table_Abstract {
    protected $_primary = "group_id";
    protected $_name = 'groupp' ;
    
	function init () {

		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
	
	}
 
 	public function selectGroupsPerRound ($roundId) {
	    $db = $this->getAdapter () ;
		$where = $db->quoteInto ( "round_id = ?", $roundId ) ;
		$order = "group_id" ;
		return $this->fetchAll ( $where, $order ) ;
		
	}
	
	public function getGroupsPerRound($roundId) {
		$db = $this->getAdapter ();
		$sql = " select group_id, group_title ";
		$sql .= " from groupp";
		$sql .= " where round_id =" . $roundId;
		$sql .= " order by group_title asc ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	
 
}

?>