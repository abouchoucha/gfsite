<?php
class UserComment extends Zend_Db_Table_Abstract {
	
	protected $_primary = "comment_id";
	protected $_name = 'usercomment';
	
	public function findCommentsByUser($userId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.comment_id,uc.user_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from usercomment uc,user u ";
		$sql .= " where  uc.user_id  =" . $userId;
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
	
	public function findCommentsPerMatch($matchId, $limit = null) {
		
		$db = $this->getAdapter ();
		
		$sql = " select uc.user_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo  ";
		$sql .= " from usercomment uc,matchh m ,user u ";
		$sql .= " where m.match_id =" . $matchId;
		$sql .= " and m.match_id = uc.user_id";
		$sql .= " and uc.friend_id = u.user_id  ";
		$sql .= " and uc.comment_type = 2 ";
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
		$sql = " select uc.user_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from usercomment uc,user u ,player p";
		$sql .= " where  uc.user_id = p.player_id and p.player_id  =" . $playerId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = 3 ";
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findCommentsPerNews($newsId, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select uc.user_id,uc.friend_id,uc.comment_data as comment,uc.comment_date ,u.screen_name as screen_name,u.main_photo ";
		$sql .= " from usercomment uc,user u ,newsfeed n";
		$sql .= " where  uc.user_id = n.news_id and n.news_id  =" . $newsId;
		$sql .= " and uc.friend_id = u.user_id ";
		$sql .= " and uc.comment_type = 4 ";
		$sql .= " order by uc.comment_date desc ";
		if ($limit != null) {
			$sql .= " limit 0, " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
 	public function deleteUserComment($userId ,$commentId ){
      	$db = $this->getAdapter ();
		$db->delete( 'usercomment' , 'user_id='.$userId .' and comment_id='.$commentId);
      }

}
?>
