<?php

class UserMatch extends Zend_Db_Table_Abstract{
	protected $_primary = "user_id";
	protected $_name = 'usermatch';
	
	public function findUserMatch($userId ,$matchId ){
		$db = $this->getAdapter ();
		$sql = " select * from usermatch ";
		$sql .= " where user_id =" . $userId;
		$sql .= " and match_id = '" . $matchId ."'";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function deleteUserMatch($userId ,$matchId ){
		$db = $this->getAdapter ();
		$db->delete( 'usermatch' , "match_id='".$matchId ."' and user_id=".$userId);
	}
	
}

?>
