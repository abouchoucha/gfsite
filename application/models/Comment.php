<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once 'application/controllers/util/Constants.php';
class Comment extends Zend_Db_Table_Abstract {
	
	protected $_primary = "comment_id";
	protected $_name = 'comment';
	
	public function findCommentsByUser($userId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo ";
		$sql .= " from comment uc,user u ,userfriend uf ";
		$sql .= " where  uc.comment_party_id  =" . $userId;
		$sql .= " and uc.comment_party_id = uf.user_id ";
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.friend_id = uf.friend_id ";
		$sql .= " and uc.comment_type = 1 ";
		$sql .= " and (if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) = 1 or if (uc.comment_state = 0, uf.comment_state ,uc.comment_state) = 0) ";
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql ."<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerMatch($matchId, $limit = null) {
		
		$db = $this->getAdapter ();
		
		$sql = " select uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo  ";
		$sql .= " from comment uc,matchh m ,user u ";
		$sql .= " where m.match_id ='" . $matchId ."'";
		$sql .= " and m.match_id = uc.comment_party_id";
		$sql .= " and uc.friend_id = u.user_id  ";
		$sql .= " and uc.comment_type = " . Constants::$_COMMENT_MATCH;
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerPlayer($playerId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_deleted,uc.comment_id , uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from comment uc,user u ,player p";
		$sql .= " where  uc.comment_party_id = p.player_id and p.player_id  =" . $playerId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = " . Constants::$_COMMENT_PLAYER;
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerTeam($teamId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from comment uc,user u ,team t";
		$sql .= " where  uc.comment_party_id = t.team_id and t.team_id  =" . $teamId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type =" . Constants::$_COMMENT_TEAM;
		$sql .= " order by uc.comment_date desc ";
		if (!is_null($limit)){
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findComments($elementId, $type, $limit = null) {
		if($type == Constants::$_COMMENT_REGION){
			return self::findCommentsPerRegion($elementId, $limit);
		}else if($type == Constants::$_COMMENT_COMPETITION){
			return self::findCommentsPerCompetition($elementId, $limit);
		}
		
	}
	
	public function findCommentsPerCompetition($competitionId, $limit = null) {
		$db = $this->getAdapter ();
		
		$sql = " select uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment, uc.comment_date ,u.screen_name AS screen_name,u.main_photo ,uc.comment_type , ";
		$sql .= " uc.comment_date ,u.screen_name as screen_name,u.main_photo ,uc.comment_type ";
		$sql .= " from comment uc,user u    ";
		$sql .= " where  uc.league_id  = " .$competitionId;
		$sql .= " and uc.friend_id = u.user_id  ";
		$sql .= " and uc.comment_type in  (" . Constants::$_COMMENT_PLAYER ." , " .Constants::$_COMMENT_TEAM ." , " .Constants::$_COMMENT_COMPETITION .") ";
		$sql .= " order by uc.comment_date desc  ";
		if (!is_null($limit)){
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerRegion($regionGroupId, $limit = null) {
		$db = $this->getAdapter ();
		
		$sql = " select uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment, ";
		$sql .= " uc.comment_date ,u.screen_name as screen_name,u.main_photo ,uc.comment_type ";
		$sql .= " from comment uc,user u   ";
		$sql .= " where  uc.region_group_id = " .$regionGroupId;
		$sql .= " and uc.friend_id = u.user_id  ";
		$sql .= " and uc.comment_type in  (" . Constants::$_COMMENT_PLAYER ." , " .Constants::$_COMMENT_TEAM ." , " .Constants::$_COMMENT_REGION .") ";
		$sql .= " order by uc.comment_date desc  ";
		if (!is_null($limit)){
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	
	public function findCommentsPerNews($newsAfpId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select distinct uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from comment uc,user u ,newsfeed n";
		$sql .= " where uc.comment_party_id = n.news_afp_id and n.news_afp_id  ='" . $newsAfpId . "'";
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = 4 ";
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerPhoto($photoId, $limit = null) {
		$db = $this->getAdapter ();
		
		$sql = " select distinct uc.comment_deleted,uc.comment_id,uc.comment_party_id,uc.friend_id,uc.comment_data  ";
		$sql .= " as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from comment uc,user u ,photo p ";
		$sql .= " where uc.photo_id = p.image_id and p.image_id  = " . $photoId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = 8  ";
		$sql .= " order by uc.comment_date desc ";

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
		$db->delete ( 'comment', 'user_id=' . $userId . ' and comment_id=' . $commentId );
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
		Zend_Debug::dump ( $data );
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
