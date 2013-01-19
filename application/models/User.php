<?php
class User extends Zend_Db_Table {
	
	protected $_primary = "user_id";
	protected $_name = "user";
	
	public function updateAccesTokenUser($userid,$facebookaccesstoken) {
		$db = $this->getAdapter ();
		$data ['facebookaccesstoken'] = $facebookaccesstoken;
		$where = $db->quoteInto ( "user_id = ?", $userid );
		return $this->update ( $data, $where );
	}
	
	public function findUniqueUser($email) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email );
		return $this->fetchRow ( $where );
	}
	
	public function findExistSameEmail($email , $userId) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email ).
				 $db->quoteInto( "and user_id <> ?", $userId );;
		return $this->fetchRow ( $where );
	}
	
	public function updateUser($email, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email );
		return $this->update ( $data, $where );
	}
	
	public function updateDateFB($userid,$idfb) {
		$db = $this->getAdapter ();
		$data ['facebookid'] = $idfb;
		$where = $db->quoteInto ( "user_id = ?", $userid );
		return $this->update ( $data, $where );
	}
	
	public function updateUserById($id, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "user_id = ?", $id );
		return $this->update ( $data, $where );
	}
	
	public function findUserFriends($userId, $from = null, $to = null, $type = null) {
		
		$db = $this->getAdapter ();
		$sql = " select c.country_name,c.country_id ,total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed,concat(first_name,' ' ,last_name) as nombre, u.screen_name as nickname , u.city_live  as location , main_photo ,u.user_id as userId,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ,country c";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  u.country_live = c.country_id ";
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
		$sql = " select c.country_name,c.country_id  ,total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ,country c";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  u.country_live = c.country_id ";
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
	
	
	public function findUserFriendsInFriendFeed($userId, $from = null) {
		
		$db = $this->getAdapter ();
		$sql = " select c.country_name,c.country_id ,total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ,country c ";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  u.country_live = c.country_id ";
		$sql .= " and  uf.user_id = " . $userId;
		$sql .= " and uf.infriendfeed = 'y' ";
		if (!is_null($from)) {
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUserFriendsSearchText($userId, $from= null ,$searchtext = null) {
		
		$db = $this->getAdapter ();
		$sql = " select c.country_name,c.country_id ,total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		$sql .= " from userfriend uf,`user` u ,country c ";
		$sql .= " where uf.friend_id = u.user_id";
		$sql .= " and  u.country_live = c.country_id ";
		$sql .= " and  uf.user_id = " . $userId;
		$sql .= " and (upper(u.screen_name) LIKE  '%" . $searchtext . "%' or upper(email) LIKE '%" . $searchtext . "%' or upper(first_name) LIKE '%" . $searchtext . "%' or upper(last_name) LIKE '%" . $searchtext . "%' or upper(city_live) LIKE '%" . $searchtext . "%' or upper(city_birth) LIKE '%" . $searchtext . "%' or upper(aboutme_text) LIKE '%" . $searchtext . "%' )";
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
		$sql = " select  count(*) as totalActivities,c.country_name,c.country_id , total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed,u.screen_name as nickname , u.city_live as location , main_photo ,u.user_id as userId,(select count(*) from userfriend where user_id=u.user_id) as numfriends "; 
		$sql .= " from activity a,activitytype t, user u , userfriend uf ,country c ";
		$sql .= " where a.activity_user_id = u.user_id  ";
		$sql .= " and  u.country_live = c.country_id ";  
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
		$sql = " select u.screen_name as nickname , u.city_live  as location , main_photo as picture,u.user_id as userId ";
		$sql .= " from `user` u ";
		$sql .= " where upper(u.screen_name) LIKE  '%" . $searchtext . "%' ";
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUserProfilesRandom($limit = 6) {
		
		$db = $this->getAdapter ();
		$sql = " select u.screen_name as nickname , u.city_live  as location ,c.country_name as countrylive,main_photo as picture,u.user_id as userId ";
		$sql .= " from user u, country c ";
		$sql .= " where flag_confirm ='1' and first_login='1' ";
        $sql .= " and c.country_id = u.country_live ";
		$sql .= " order by RAND() LIMIT " . $limit;
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function findUserProfilesByPlayer($playerId,$limit=null ,$userid = null) {
		
		$db = $this->getAdapter ();
		$sql = " select u.city_live,u.registration_date,u.date_update,u.screen_name as nickname , u.city_live as location, c.country_name as countrylive, main_photo as picture,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends, u.total_value/u.total_votes as rating,u.total_votes  ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from `user` u , userplayer up, country c ";
		$sql .= " where u.user_id = up.user_id ";
        $sql .= " and  c.country_id = u.country_live ";
		$sql .= " and up.player_id = " . $playerId;
		$sql .= " and u.flag_confirm ='1' and u.first_login='1'  ";
		if($limit != null){
		  $sql .= " order by RAND() LIMIT ". $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function findUserProfilesByTournament($competitionId,$limit=null ,$userid = null) {
		
		$db = $this->getAdapter ();
		$sql = " select u.city_live,u.registration_date,u.date_update,u.screen_name as nickname , u.city_live as location ,c.country_name as countrylive, main_photo as picture,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends, u.total_value/u.total_votes as rating,u.total_votes  ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from `user` u , userleague ul, country c ";
		$sql .= " where u.user_id = ul.user_id ";
        $sql .= " and  c.country_id = u.country_live ";
		$sql .= " and ul.competition_id = " . $competitionId;
		$sql .= " and u.flag_confirm ='1' and u.first_login='1'  ";
		if($limit != null){
		  $sql .= " order by RAND() LIMIT ". $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function findUserProfilesByTeam($teamId ,$limit=null ,$userid = null) {
		
		$db = $this->getAdapter ();
		$sql = " select distinct u.city_live,u.registration_date,u.date_update,u.screen_name as nickname , u.city_live as location ,c.country_name as countrylive, main_photo as picture,u.user_id as userId  ,(select count(*) from userfriend where user_id=u.user_id) as numfriends, u.total_value/u.total_votes as rating,u.total_votes  ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from user u , userteam ut, country c ";
		$sql .= " where u.user_id = ut.user_id ";
		$sql .= " and ut.team_id in (" . $teamId .")";
        $sql .= " and  c.country_id = u.country_live ";
		$sql .= " and u.flag_confirm ='1' and u.first_login='1'  ";
		if($limit != null){
		  $sql .= " order by RAND() LIMIT ". $limit;
		}
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


    	public function findUsersAll() {
		$db = $this->getAdapter ();
		$sql = "SELECT u.user_id, u.screen_name, CONCAT(u.first_name,' ' ,u.last_name) AS name ,u.city_live,u.city_birth ,c.country_name AS country_live,c1.country_name AS country_birth
			FROM user u , country c , country c1 
			WHERE flag_confirm = 1 AND first_login = 1
			AND c.country_id = u.country_live
			AND c1.country_id = u.country_birth";
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
	
	public function findUserProfiles( $from = null,$type , $userid = null) {
		
		$db = $this->getAdapter ();
		$sql = " select u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.user_id,u.screen_name,u.city_live,u.main_photo,u.registration_date ,c.country_name,c.country_id,(select count(*)
																							 from userfriend
																							  where user_id=u.user_id) as numfriends";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
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
	
	public function findMostActiveUsers($from = null , $userid = null){
		
		$db = $this->getAdapter ();
		$sql = " select count(*) as totalActivities ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.user_id ,u.screen_name,u.city_live,u.main_photo ,c.country_name,c.country_id ,(select count(*)
																																									 from userfriend
																																									  where user_id=u.user_id) as numfriends ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
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
	
	public function findMostPopularUsers($from = null ,$userid = null){
		
		$db = $this->getAdapter ();
		$sql = " select count(*) as numfriends ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed, uf.user_id,u2.screen_name,u2.city_live,u2.main_photo ,c.country_name,c.country_id ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from userfriend uf, user u , user  u2 ,country c ";
		$sql .= " where uf.friend_id = u.user_id  ";
		$sql .= " and  u.country_live = c.country_id ";
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
	
		$sql = " select distinct l.user_id ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.screen_name as screen_name , u.city_live  as location ,c.country_name,c.country_id, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		if(!is_null($userId)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userId AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from userleague l , user u, country c ";
		$sql .= " where competition_id in ( ";
		$sql .= " 			select competition_id from userleague ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= " 			) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.screen_name as screen_name , u.city_live  as location ,c.country_name,c.country_id, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		if(!is_null($userId)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userId AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from usermatch l , user u, country c ";
		$sql .= " where match_id in ( ";
		$sql .= " 			select match_id from usermatch ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= " 			) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.screen_name as screen_name , u.city_live  as location ,c.country_name,c.country_id, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		if(!is_null($userId)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userId AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from userplayer l , user u, country c ";
		$sql .= " where player_id in ( ";
		$sql .= " 			select player_id from userplayer ";
		$sql .= " 			where user_id = " .$userId;
		$sql .= "					) ";
		$sql .= " and l.user_id = u.user_id ";
		$sql .= " and u.country_live = c.country_id "; 
		$sql .= " and l.user_id <> " .$userId;
		
		$sql .= " union ";
		
		$sql .= " select distinct l.user_id ,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.screen_name as screen_name , u.city_live  as location ,c.country_name,c.country_id, main_photo ,u.user_id as userId ,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
		if(!is_null($userId)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userId AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
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
	
	
	public function findUserUnique($userid) {
		$db = $this->getAdapter ();
		$sql = "select user_id, screen_name, main_photo ,email,private_message_email,private_message_frecuency,friend_invites_email, ";
		$sql .= "friend_invites_frecuency,goalshouts_email,goalshouts_frecuency,commentpost_email,commenpost_frecuency,friendactivity_email,friendactivity_frecuency,facebookid ";
		$sql .= " from user where user_id = " . $userid;
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
	
	public function findUsersOnline($from = null , $userid) {
		$db = $this->getAdapter ();
		
		$sql = " select total_value/u.total_votes as rating,u.total_votes , u.date_update,u.total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,u.user_id,u.screen_name,u.city_live,u.main_photo ,c.country_name,c.country_id ,(select count(*) from userfriend where user_id = u.user_id) as numfriends ";
		if(!is_null($userid)){
			$sql .= " , (SELECT IF(COUNT(*)=1,'y','n') FROM userfriend WHERE user_id = $userid AND friend_id = u.user_id) AS isfriend" ;
		}else {
			$sql .= ", 'n' as isfriend";
		}	
		$sql .= " from user u , country c  ";
		$sql .= " where u.country_live = c.country_id ";
		$sql .= " and UNIX_TIMESTAMP() - u.last_activity <= 1200 ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}	
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUsersFriendsOnline($userId , $from = null) {
		$db = $this->getAdapter ();
		$sql = " SELECT total_value/u.total_votes as rating,u.total_votes , u.date_update,u.registration_date ,uf.infriendfeed, u.user_id,u.screen_name as nickname,u.city_live as location,u.main_photo ,c.country_name,c.country_id ,(SELECT COUNT(*) FROM userfriend WHERE user_id = u.user_id) AS numfriends  ";
		$sql .= " from user u , userfriend uf ,country c  ";
		$sql .= " where u.country_live = c.country_id  ";
		$sql .= " AND UNIX_TIMESTAMP() - u.last_activity <= 1200 ";
		$sql .= " AND  uf.friend_id = u.user_id ";
		$sql .= " AND uf.user_id = " .$userId;
		
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20";
		}	
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	}


    public function findUsersSearch ($screenName) {
        $db = $this->getAdapter ();
        $sql = "select user_id, screen_name, main_photo ,email,registration_date,aboutme_text,(select count(*) from userfriend where user_id=u.user_id) as numfriends ";
        $sql .= " from user u ";
        $sql .= " where flag_confirm ='1' and first_login='1'";
        $sql .= " and  u.screen_name = '" . $screenName ."'" ;
		$result = $db->query ( $sql );
        $rows = $result->fetchAll ();
		return $rows;

    }
    
	public function isUserOnline($email){
    	
    	$db = $this->getAdapter ();
		$where = $db->quoteInto ( "email = ?", $email ) . " and UNIX_TIMESTAMP() - last_activity <= 1200";
		return $this->fetchRow ( $where );
		
    }
	
	public function deleteUser($userId) {
		$db = $this->getAdapter ();
		$db->delete ( 'user', 'user_id=' . $userId );
	}
	
	public function getUsersDailyPrivateMessageAlert(){
		$db = $this->getAdapter ();
        $sql = "select user_id, screen_name, main_photo ,email,private_message_email,private_message_frecuency ";
		$sql .= "from user where private_message_email = '1' and private_message_frecuency = '2'";
        $result = $db->query ( $sql );
        $rows = $result->fetchAll ();
		return $rows;
					
		
	}
}
?>