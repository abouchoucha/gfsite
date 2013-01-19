<?php 
class UserLeague extends Zend_Db_Table_Abstract {

      protected $_primary = "user_id";
      protected $_name   = 'userleague';
      

	 public function findUserCompetition($userId ,$leagueId ){
		$db = $this->getAdapter ();
		$sql = " select * from userleague ";
		$sql .= " where user_id = " . $userId;
		$sql .= " and competition_id = ". $leagueId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findUserCompetitionByCountry($countryIds ){
		$db = $this->getAdapter ();
		$sql = " select * from league_competition ";
		$sql .= " where country_id in (".$countryIds .")";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findAllUserCompetitions($userId , $from = null, $footer = null ){
		$db = $this->getAdapter ();
		$sql = " select l.competition_name ,c.country_name ,c.country_id ,l.competition_id, l.regional, l.competition_seoname, r.region_id,r.region_name,rg.region_group_id,rg.region_group_name, ";
		$sql .= " ul.alert_email,ul.alert_sms,ul.alert_facebook,ul.alert_twitter,ul.alert_frecuency_type";
		$sql .= " from userleague ul , league_competition l , country c , region r,regiongroup rg ";
		$sql .= " where ul.competition_id = l.competition_id ";
		$sql .= " and l.country_id = c.country_id ";
        $sql .= " and r.region_id = c.region_id ";
        $sql .= " and r.region_group_id = rg.region_group_id ";
		$sql .= " and ul.user_id = " . $userId;
		$sql .= " order by l.regional desc,c.priority,l.competition_id ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",10";
		}
        if(!is_null($footer)){
			$sql .= " LIMIT " . $footer ;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findAllUserGames($userId){
		$db = $this->getAdapter ();
		
		$sql = " SELECT m.match_id , t1.team_name as teama,t1.team_seoname as teamaseoname, t1.team_id as teama_id , t2.team_name as teamb,t2.team_seoname as teambseoname, t2.team_id as teamb_id , m.fs_team_a , m.fs_team_b, m.match_status ,l.competition_id , l.competition_name ,c.country_name ,c.country_id,m.match_date";
		$sql .= " FROM usermatch um, matchh m , team t1 , team t2 , league_competition l,country c ";
		$sql .= " WHERE um.match_id = m.match_id ";
		$sql .= " AND m.team_a = t1.team_id ";
		$sql .= " AND m.team_b = t2.team_id ";
		$sql .= " AND m.competition_id = l.competition_id ";
		$sql .= " AND m.country_id = c.country_id ";
		$sql .= " AND um.user_id = " . $userId;
		
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;	
		
		
	}
	
	public function deleteUserLeague($userId ,$leagueId  ){
		$db = $this->getAdapter ();
		$db->delete( 'userleague' , ' user_id='.$userId .' and competition_id='.$leagueId);
	}
	
	public function deleteUserLeagueByUserId($userId ){
		$db = $this->getAdapter ();
		$db->delete( 'userleague' ,  'user_id='.$userId);
	}
	
	public function findUserCountryCompetitions($userId){
		$db = $this->getAdapter ();
		
		$sql = " select distinct c.country_id  ";
		$sql .= " from userleague u , country c ";
		$sql .= " where u.user_id = " . $userId;
		$sql .= " and c.country_id = u.country_id ";
		$sql .= " union ";
		$sql .= " select distinct l.country_id from userteam u, ";
		$sql .= " team t,league_competition l where u.team_id = t.team_id ";
		$sql .= " and t.country_id = l.country_id and user_id  =" . $userId;
		
		
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
		
	}
	
	
     //get which users-league relations have checked to receive email scores/schedules alerts of their favorite leagues
	   public function getUserLeagueEmailAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = "select l.* from userleague l inner join user u on u.user_id=l.user_id where u.user_enabled=1 and l.alert_email = '1' and u.email is not null and u.email!='' ";//" select * from userleague where alert_email = '1' " ;
	   	 	$result = $db->query ($sql);
	   	 	//echo $sql;
	      	$row = $result->fetchAll();
	      	return $row;
	   }

       public function getUserLeagueFaceBookAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = "select * from userleague l inner join user u on u.user_id=l.user_id where u.user_enabled=1 and alert_facebook = '1' and facebookid is not null";
	   	 	$result = $db->query ($sql);
	   	 	echo $sql;
	      	$row = $result->fetchAll();
	      	return $row;
	   }
	
	/*USER LEAGUE 24 or 48 hr notice between '20110628070000' and '20110629065959'*/
	public function countScheduleMatchLeagueAlerts($competition_id, $startDate, $endDate) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = "select count(*) as totalmatches ,m.season_id,m.round_id ,s.title from matchh m , season s " ;
	   	 	$sql.= " where m.competition_id = ". $competition_id ;
	   	 	$sql.= " and s.season_id = m.season_id ";
	   	 	$sql.= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($startDate))."' and '".strftime('%Y%m%d%H%M%S',strtotime($endDate))."'";
	   	 	//echo "<br>" . $sql . "<br>";
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetch();
	      	return $row;
      }
    
	public function updateUserLeague($userId, $competitionId  , $data) {
			$db = $this->getAdapter ();
			$where = 'user_id = ' . $userId . ' and competition_id = ' . $competitionId ;
			$db->update ( 'userleague', $data, $where );
	}
      
  }
?>
