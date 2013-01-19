<?php

class UserFriend extends Zend_Db_Table_Abstract {
	
	protected $_primary = "friend_id";
	protected $_name = 'userfriend';
	
	function init() {
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Pagination' );
	}
	
	public function findUserFriend($userId, $friendId) {
		$db = $this->getAdapter ();
		$sql = " select * from userfriend uf inner join user u on u.user_id=uf.friend_id where u.user_enabled=1 ";
		$sql .= " and uf.user_id =" . $userId;
		$sql .= " and uf.friend_id = " . $friendId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	public function deleteUserFriend($userId, $friendId) {
		$db = $this->getAdapter ();
		$db->delete ( 'userfriend', 'user_id=' . $userId . ' and friend_id=' . $friendId );
	}
	
	public function deleteAllUserFriend($userId) {
		$db = $this->getAdapter ();
		$db->delete ( 'userfriend', 'user_id=' . $userId);
	}
	
	public function updateStateOfBlockedComment($userId, $friendId, $state) {
		$data ['comment_state'] = $state;
		///Zend_Debug::dump($data);
		$db = $this->getAdapter ();
		$where = 'friend_id = ' . $friendId . ' and user_id = ' . $userId;
		Zend_Debug::dump ( $where );
		$db->update ( 'userfriend', $data, $where );
	}
	
	public function updateUserFriendById($userId, $friendId , $data) {
		$db = $this->getAdapter ();
		$where = 'friend_id = ' . $friendId . ' and user_id = ' . $userId;
		$db->update ( 'userfriend', $data, $where );
	}
}
?>