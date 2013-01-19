<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once 'application/controllers/util/Constants.php';
class AccountComment extends Zend_Db_Table_Abstract {
	
	protected $_primary = "note_id";
	protected $_name = 'account_notes_log';
	
	public function findCommentsByUser($userId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select *";
		$sql .= " from account_notes_log anl ";
		$sql .= " where  anl.account_id  =" . $userId;
		$sql .= " order by anl.date_added desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function deleteComment($userId, $commentId) {
		$db = $this->getAdapter ();
		$db->delete ( 'account_notes_log', 'account_id=' . $userId . ' and note_id=' . $commentId );
	}
	
	public function findCommentsById($commentId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from comment uc,user u ";
		$sql .= " where uc.comment_id = " . $commentId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = 1 ";
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function updateStateOfComment($id, $state) {
		//		if (empty($data['comment_date'])){
		//			$data['comment_date'] = Date('Y-m-d H:i:s');
		//		}
		$data ['comment_state'] = $state; // bloqueado esta vez
		//Zend_Debug::dump ( $data );
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( 'comment_id = ?', $id );
		$db->update ( 'comment', $data, $where );
	}
	
	
	public function updateComment($id, $dataToUpdate) {
		
		$data ['comment_data'] = $dataToUpdate;
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( 'comment_id = ?', $id );
		$db->update ( 'comment', $data, $where );
	}
	
	public function updateDeleteComment($id, $dataToUpdate) {
		
		$data ['comment_deleted'] = $dataToUpdate;
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( 'comment_id = ?', $id );
		$db->update ( 'comment', $data, $where );
	}
	
	public function findCommentsSendedToUser($userId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_id,uc.comment_super_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo, ";
		$sql .= " if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) as comment_state, (select count(*) from comment c where c.comment_super_id = uc.comment_party_id) reply_count ";
		$sql .= " from comment uc,user u, userfriend uf ";
		$sql .= " where uc.comment_party_id  = " . $userId;
		$sql .= " and uc.comment_party_id = uf.user_id";
		$sql .= " and uc.friend_id = u.user_id and uc.friend_id = uf.friend_id";
		$sql .= " and uc.comment_type = 1 and (if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) = 1 or if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) = 0) ";
		$sql .= " order by uc.comment_date desc ";
		
		//echo  $sql;
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findCommentReplies($superId) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo, ";
		$sql .= " if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) as comment_state, (select count(*) from comment c where c.comment_super_id = uc.comment_party_id) reply_count ";
		$sql .= " from comment uc,user u, userfriend uf ";
		$sql .= " where uc.comment_super_id  =  " . $superId;
		$sql .= " and uc.comment_party_id = uf.user_id";
		$sql .= " and uc.friend_id = u.user_id and uc.friend_id = uf.friend_id";
		$sql .= " order by uc.comment_date desc ";
		
		//Zend_Debug::dump ( $sql );
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}

}
?>
