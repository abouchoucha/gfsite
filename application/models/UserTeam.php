<?php
class UserTeam extends Zend_Db_Table_Abstract {
	
	protected $_primary = "user_id";
	protected $_name = 'userteam';
	
	public function findUserTeams($userId) {
		$db = $this->getAdapter ();
		$sql = " select t.team_name,t.team_seoname ,t.team_id,u.alert_email,u.alert_sms,u.alert_facebook,u.alert_twitter,u.alert_frecuency_type ";
		$sql .= " from userteam u , team t  ";
		$sql .= " where u.user_id =" . $userId;
		$sql .= " and u.team_id = t.team_id ";
		$sql .= " order by t.team_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findUserTeamsRandom($userId ,$teamType){
		$db = $this->getAdapter ();
		$sql = " select t.team_name,t.team_seoname,t.team_id from userteam u , team t ";
		$sql .= " where user_id =" . $userId;
		$sql .= " and u.team_id = t.team_id ";
		$sql .= " and t.team_type = '" . $teamType . "'";
		$sql .= " order by RAND() LIMIT 1 ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetch();
		return $row;
		
	}
	
	public function findUserTeamsByType($userId, $teamType , $from = null) {
		
		$db = $this->getAdapter ();
		$sql = " select t.team_name,t.team_seoname ,t.team_id ,c.country_id, c.country_name ,team_soccer_type";
		$sql .= " from userteam u , team t ,country c";
		$sql .= " where u.user_id =" . $userId;
		$sql .= " and u.team_id = t.team_id ";
		$sql .= " and t.country_id = c.country_id ";
		if($teamType !=null){
			$sql .= " and t.team_type = '" . $teamType . "'";
		}
		$sql .= " order by t.team_name ";
		
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",10";
		}	
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findUserTeam($userId ,$teamId=null ){
		$db = $this->getAdapter ();
		$sql = " select * from userteam  ";
		$sql .= " where user_id =" . $userId;
		if (! is_null ( $teamId )) {
		    $sql .= " and team_id = ". $teamId;
		}
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function deleteUserTeam($userId ,$teamId ){
		$db = $this->getAdapter ();
		$db->delete( 'userteam' , 'team_id='.$teamId .' and user_id='.$userId);
	}
	
	public function deleteAllUserTeam($userId){
		$db = $this->getAdapter ();
		$db->delete( 'userteam' , 'user_id='.$userId);
	}
	
	
	/* Update Alerts Form quries */
	
	/*caso activity */
	public function getTeamUpdateActivity() {
      	$db = $this->getAdapter();
      	$sql = " select * from activity a , userteam ut , activitytype at ";
      	$sql .= " where a.activity_team_id = ut.team_id ";
      	$sql .= " and a.activity_activitytype_id in (28,34,35,36) ";
      	$sql .= " and a.activity_alert_sent = 'N' ";
      	$sql .= " and ut.alert_email = 1 ";
      	// echo $sql . "<br>";
         $result = $db->query ($sql);
      	 $row = $result->fetchAll();
      	 return $row;
      }
	
       /*caso in advance 24 or 48 hr*/
      	public function getTeamAlertActivity() {
	      	$db = $this->getAdapter();
	      	$sql = " select * from activity a , userteam ut , activitytype at ";
	      	$sql .= " where a.activity_team_id = ut.team_id ";
	      	$sql .= " and a.activity_activitytype_id in (28,34,35,36) ";
	      	$sql .= " and a.activity_alert_sent = 'N' ";
	      	$sql .= " and ut.alert_email = 1 ";
	      	// echo $sql . "<br>";
	      	$result = $db->query ($sql);
	      	 $row = $result->fetchAll();
	      	 return $row;
      }
	
      //get which users-teams relations have checked to receive email scores/schedules alerts of their teams
	   public function getUserTeamEmailAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select * from userteam ut inner join user u on u.user_id=ut.user_id where u.user_enabled=1 and ut.alert_email = '1' " ;
	   	 	//echo $sql;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }

	   public function getUserTeamFaceBookAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select * from userteam ut inner join user u on u.user_id=ut.user_id where u.user_enabled=1 and ut.alert_facebook = '1' " ;
	   	 	//echo $sql;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }
	   
	   public function getUserTeamIdEmailAlerts($teamId) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select u.screen_name,u.email,ut.* from userteam ut ".
	   	 		"inner join user u on u.user_id=ut.user_id ".
	   	 		"where u.user_enabled=1 and ut.alert_email = '1' and ut.team_id='".$teamId."' ";
	   	 	//echo $sql;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }

	   public function getUserTeamIdFaceBookAlerts($teamId) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select u.facebookid,u.screen_name,u.email,ut.* from userteam ut ".
	   	 		"inner join user u on u.user_id=ut.user_id ".
	   	 		"where u.user_enabled=1 and ut.alert_facebook = '1' and ut.team_id='".$teamId."' ";
	   	 	//echo $sql;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }
	   
      
	   /*caso in advance 24 or 48 hr*/
	   public function countScheduleMatchAlerts($teamId, $startDate, $endDate) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select  count(*) from matchh " ;
	   	 	$sql.= " where (team_a = " . $teamId ." or team_b = ". $teamId . " )";
	   	 	$sql.= " and match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($startDate))."' and '".strftime('%Y%m%d%H%M%S',strtotime($endDate))."'";
	   	 	//echo "<br>" . $sql . "<br>";
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchColumn(0);
	      	return $row;
	   }

	public function updateUserTeam($userId, $teamId  , $data) {
			$db = $this->getAdapter ();
			$where = 'user_id = ' . $userId . ' and team_id = ' . $teamId ;
			$db->update ( 'userteam', $data, $where );
	}


}
?>