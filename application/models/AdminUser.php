<?php
class AdminUser extends Zend_Db_Table {
	
	protected $_primary = "user_id";
	protected $_name = "user";
	
	public function findUniqueUser($email) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email );
		return $this->fetchRow ( $where );
	}
	
	public function updateUser($email, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email );
		return $this->update ( $data, $where );
	}
	
	public function updateUserById($userId, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "user_id = ?", $userId );
		return $this->update ( $data, $where );
	}

	public function findUserFriends($userId, $from = null, $to = null, $type = null) {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live  as location , main_photo ,u.user_id as userId,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  uf.user_id = " . $userId;
		
		if($type == 'recently'){
			$sql .= " order by date_update desc";
		}
		if (!is_null($from)) {
			$sql .= " LIMIT " . $from . "," . $to;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function countUserFriends($userId) {
		
		$db = $this->getAdapter ();
		$sql = " select count(*) ";
		$sql .= " from userfriend uf,`user` u ";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  uf.user_id = " . $userId;
		
		//echo $sql;
		$result = $db->query ( $sql );
		$column = $result->fetchColumn ( 0 );
		return $column;
	}
	
	
	public function findUserFriendsMostPopular($userId, $from = null) {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  uf.user_id = " . $userId;
		$sql .= " order by numfriends desc ";
		if (!is_null($from)) {
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUserFriendsMostActive($userId, $from = null){
		
		$db = $this->getAdapter ();
		$sql = " select  count(*) as totalActivities,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId "; 
		$sql .= " from activity a,activitytype t, user u , userfriend uf  ";
		$sql .= " where a.activity_user_id = u.user_id ";  
		$sql .= " and a.activity_activitytype_id = t.activitytype_id  ";
		$sql .= " and activitytype_category_id = 1 ";
		$sql .= " and uf.friend_id = u.user_id  "; 
		$sql .= " and uf.user_id =" . $userId;
		$sql .= " group by u.user_id ";
		$sql .= " order by totalActivities desc ";
		if (!is_null($from)) {
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
		
	}
		
	public function searchUserProfiles($searchtext) {
		
		$db = $this->getAdapter ();
		$sql = " select u.user_id, u.screen_name, u.email, u.first_name,u.last_name, u.registration_date";
		$sql .= " from `user` u ";
		$sql .= " where upper(u.screen_name) LIKE  '%" . $searchtext . "%' or upper(email) LIKE '%" . $searchtext . "%' or upper(first_name) LIKE '%" . $searchtext . "%' or upper(last_name) LIKE '%" . $searchtext . "%' ";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUserProfilesRandom() {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live  as location , main_photo as picture,u.user_id as userId ";
		$sql .= " from `user` u ";
		$sql .= " where flag_confirm ='1' and first_login='1' ";
		$sql .= " order by RAND() LIMIT 6";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function findUserProfilesByPlayer($playerId) {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live as location , main_photo as picture,u.user_id as userId  ";
		$sql .= " from `user` u , userplayer up ";
		$sql .= " where u.user_id = up.user_id ";
		$sql .= " and up.player_id = " . $playerId;
		$sql .= " and u.flag_confirm ='1' and u.first_login='1'  ";
		$sql .= " order by RAND() LIMIT 6";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function findUserProfilesByTeam($teamId) {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live as location , main_photo as picture,u.user_id as userId  ";
		$sql .= " from `user` u , userteam ut ";
		$sql .= " where u.user_id = ut.user_id ";
		$sql .= " and ut.team_id = " . $teamId;
		$sql .= " and u.flag_confirm ='1' and u.first_login='1'  ";
		$sql .= " order by RAND() LIMIT 6";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function activateAccount($key, $hash) {
		$db = $this->getAdapter ();
		$data ['flag_confirm'] = '1';
		$where = $db->quoteInto ( "email = ?", $key ) . $db->quoteInto ( "and password = ?", $hash );
		return $this->update ( $data, $where );
	}
	
	public function validateAccount($email) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email ) . $db->quoteInto ( "and flag_confirm = ?", '1' );
		return $this->fetchRow ( $where );
	}
	
	public function findUsers($userId) {
		$db = $this->getAdapter ();
		$sql = "select user_id, screen_name, concat(first_name,' ' ,last_name) as nombre from user where flag_confirm = 1 and first_login = 1";
		$sql .= " and user_id <> " . $userId;
		//echo $sql; 
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function countTotalUsers() {
		
		$db = $this->getAdapter ();
		$sql = " select count(user_id) from `user` u ";
		$sql .= " where flag_confirm ='1' and first_login='1'";
		$result = $db->query ( $sql );
		$column = $result->fetchColumn ( 0 );
		//echo $column;
		return $column;
	}
	
	public function findUserProfiles($from = null , $type) {
		
		$db = $this->getAdapter ();
		$sql = " select u.user_id,u.screen_name,u.city_live,u.main_photo,u.registration_date ,c.country_name,c.country_id,(select count(*)
																							 from userfriend
																							  where user_id=u.user_id) as numfriends";
		$sql .= " from `user` u , country c";
		$sql .= " where flag_confirm ='1' and first_login='1' ";
		$sql .= " and u.country_live = c.country_id ";
		
		if($type == 'newest'){
			$sql .= " order by registration_date desc";
		}
		if($type == 'recently'){
			$sql .= " order by date_update desc";
		}
		
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql ."<br>";
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findMostActiveUsers($from = null){
		
		$db = $this->getAdapter ();
		$sql = " select count(*) as totalActivities ,u.user_id ,u.screen_name,u.city_live,u.main_photo ,c.country_name,c.country_id ";
		$sql .= " from activity a , user u ,country c ";
		$sql .= " where a.activity_user_id = u.user_id  ";
		$sql .= " and  u.country_live = c.country_id  ";
		$sql .= " group by  u.user_id  ";
		$sql .= " order by totalActivities desc ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
		
	}
	
	public function findMostPopularUsers($from = null){
		
		$db = $this->getAdapter ();
		$sql = " select count(*) as numfriends ,uf.user_id,u2.screen_name,u2.city_live,u2.main_photo ,c.country_name,c.country_id ";
		$sql .= " from userfriend uf, user u , user  u2 ,country c ";
		$sql .= " where uf.friend_id = u.user_id  ";
		$sql .= " and  u.country_live = c.country_id   ";
		$sql .= " and uf.user_id = u2.user_id   ";
		$sql .= " group by  uf.user_id ";
		$sql .= " order by numfriends desc ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
		
	}
	
	public function findUsersLikeMe($from = null , $userId){
		
		$db = $this->getAdapter ();
	
		$sql = " select distinct l.user_id ,u.screen_name as screen_name , u.city_live  as location ,c.country_name, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userleague l , user u, country c ";
		$sql .= " where competition_id in ( ";
		$sql .= " 			select competition_id from userleague ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= " 			) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.screen_name as screen_name , u.city_live  as location ,c.country_name, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from usermatch l , user u, country c ";
		$sql .= " where match_id in ( ";
		$sql .= " 			select match_id from usermatch ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= " 			) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.screen_name as screen_name , u.city_live  as location ,c.country_name, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userplayer l , user u, country c ";
		$sql .= " where player_id in ( ";
		$sql .= " 			select player_id from userplayer ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= "					) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.screen_name as screen_name , u.city_live  as location ,c.country_name, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userteam l , user u, country c ";
		$sql .= " where team_id in ( ";
		$sql .= " 			select team_id from userteam ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= " 			) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	
	public function findUserUnique($user_id) {
		$db = $this->getAdapter ();
		$sql = "select user_id, screen_name, main_photo from user where user_id = " . $user_id;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	
	public function findUniqueDisplayName($screenName) {
		$db = $this->getAdapter ();
		$sql = "select screen_name from user ";
		$sql .= "where screen_name = '"  . $screenName . "'";
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUsersOnline($from = null) {
		$db = $this->getAdapter ();
		
		$sql = " select distinct uo.ip,uo.userid ,u.screen_name,u.city_live,u.main_photo ,c.country_name,c.country_id ,(select count(*) from userfriend where user_id = userid) as numfriends ";
		$sql .= " from useronline uo , user u , country c  ";
		$sql .= " where u.country_live = c.country_id ";
		$sql .= " and uo.userid  = u.user_id ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}	
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUsersFriendsOnline($userId , $from = null) {
		$db = $this->getAdapter ();
		
		$sql = " select distinct uo.ip,uo.userid ,u.date_update,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId,(select count(*) from userfriend where user_id=u.user_id) as numfriends "; 
		$sql .= " from useronline uo,userfriend uf,`user` u  ";
		$sql .= " where uf.friend_id = u.user_id  ";
		$sql .= " and uo.userid  = u.user_id ";
		$sql .= " and uf.user_id = " .$userId;
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}	
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function getFlaggedUserDetails($userId) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "user_id = ?", $userId ) ;
		return $this->fetchRow ( $where ) ;
	}


	public function findFlaggedUsers($from = null){
		
		$db = $this->getAdapter ();
		$sql = " select user_id, screen_name, email, first_name,last_name, registration_date ";
		$sql .= " from user u";
		//$sql .= " where a.activity_user_id = u.user_id  ";
		//$sql .= " order by totalActivities desc ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
		
	}
}
?>
