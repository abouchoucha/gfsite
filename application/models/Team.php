<?php
class Team extends Zend_Db_Table_Abstract {
	
	protected $_primary = "team_id";
	protected $_name = 'team';
	
	function init() {
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Pagination' );
	}
	
	//public function selectTeamsByCountry ( $country ) {
	//$db = $this->getAdapter () ;
	//$where = $db->quoteInto ( "country_id = ?", $country ) . $db->quoteInto ( "AND team_type = ?", 'club' ) ;
	//$order = "team_name" ;
	//return $this->fetchAll ( $where, $order ) ;
	//}
	public function selectTeamsPageFanFaceBookAlerts() {
		$db = $this->getAdapter ();
		$sql = "select t.*,u.facebookid,u.facebookaccesstoken from team t inner join user u on t.user_id=u.user_id
				where t.user_id is not null and t.facebook_idPage is not null and u.facebookaccesstoken is not null";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectTeamPageFanFaceBookAlerts($teamId) {
		$db = $this->getAdapter ();
		$sql = "select t.*,u.facebookid,u.facebookaccesstoken from team t inner join user u on t.user_id=u.user_id
				where t.user_id is not null and t.facebook_idPage is not null and u.facebookaccesstoken is not null and team_id=".$teamId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}	
	
	public function selectTeamsByCountry($country, $teamtype = 'club') {
		$db = $this->getAdapter ();
		$sql = " select team_id,team_name,team_seoname ,team_type,team_soccer_type from team where country_id = " . $country;
		$sql .= " AND  team_type = '" . $teamtype . "'";
		$sql .= " ORDER by team_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function countTeamsByCountry($country, $compId) {
		$db = $this->getAdapter ();
		$sql = " select count(*) as teamcount ";
		$sql .= " from team t, teamseason ts,season s, league_competition lc ";
		$sql .= " where t.team_id = ts.team_id ";
		$sql .= " and ts.season_id = s.season_id ";
		$sql .= " and s.competition_id = lc.competition_id ";
		$sql .= " and s.active = 1 ";
		$sql .= " and lc.country_id = " . $country;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ();
		return $row;
	}
	
	public function countTeamsByCompetitionActive($compId) {
		$db = $this->getAdapter ();
		$sql = " select count(*) as teamcount ";
		$sql .= " from team t, teamseason ts,season s, league_competition lc ";
		$sql .= " where t.team_id = ts.team_id ";
		$sql .= " and ts.season_id = s.season_id ";
		$sql .= " and s.competition_id = lc.competition_id ";
		$sql .= " and s.active = 1 ";
		$sql .= " and lc.competition_id = " . $compId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ();
		return $row;
	}
	
	public function selectTeamsByCountryByLimit($country, $from) {
		$db = $this->getAdapter ();
		$sql = "  select team_id,team_name,team_seoname ";
		$sql .= " from team ";
		$sql .= " where country_id =" . $country;
		$sql .= " AND team_type = 'club'";
		$sql .= " LIMIT " . $from . ",20";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function selectNationalTeams() {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "team_type = ?", 'national' );
		$order = "team_name";
		return $this->fetchAll ( $where, $order );
	}
	
	public function selectNationalTeams1() {
		$db = $this->getAdapter ();
		$sql = " select team_id ,team_seoname , team_name_official,team_soccer_type ";
		$sql .= " from team ";
		$sql .= " where team_type = 'national'";
		$sql .= " order by team_soccer_type,team_name ";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function selectNationalTeams2($country) {
		$db = $this->getAdapter ();
		$sql = " select team_id ,team_seoname,team_name_official,team_soccer_type ";
		$sql .= " from team ";
		$sql .= " where team_type = 'national'";
		$sql .= " and country_id =" . $country;
		$sql .= " order by team_id ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function selectNationalTeamByCountryType($country) {
		$db = $this->getAdapter ();
		$sql = " select IF(team_soccer_type = 'default', 'Men','Women') AS team_type, team_name ,team_seoname ,team_name_official ,team_id";
		$sql .= " from team ";
		$sql .= " where country_id =" . $country;
		$sql .= " and team_type = 'national' ";
		$result = $db->query ( $sql );
		//echo $sql; 
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findUniqueTeam($teamId) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, ";
		$sql .= " t.team_spocosy_id, "; 
		$sql .= " t.team_gs_id, ";
		$sql .= " t.team_name, ";
		$sql .= " t.team_seoname, ";
		$sql .= " t.team_name_official, ";
		$sql .= " team_type, ";
		$sql .= " t.team_city, ";
		$sql .= " t.team_stadium, ";
		$sql .= " t.team_stadium_attendance, ";
		$sql .= " t.team_address, ";
		$sql .= " t.team_postal_zip, ";
		$sql .= " t.team_founded, ";
		$sql .= " t.team_url, ";
		$sql .= " t.team_additional_info, ";
		$sql .= " t.team_nickname, ";
		$sql .= " t.team_manager, ";
		$sql .= " t.team_chairman, ";
		$sql .= " team_federation, ";
		$sql .= " c.country_name, ";
		$sql .= " c.country_id, ";
		$sql .= " c.region_id, ";
		$sql .= " r.region_name, ";
		$sql .= " rg.region_group_name ";
		$sql .= " FROM team t ";
		$sql .= " INNER JOIN country c ON t.country_id = c.country_id  ";
		$sql .= " INNER JOIN region r ON r.region_id = c.region_id  ";
		$sql .= " INNER JOIN regiongroup rg ON rg.region_group_id = r.region_group_id  ";
		$sql .= " where t.team_id =" . $teamId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findUniqueTeamBySeoName($teamSeoName) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, ";
		$sql .= " t.team_spocosy_id, "; 
		$sql .= " t.team_gs_id, ";
		$sql .= " t.team_name, ";
		$sql .= " t.team_seoname, ";
		$sql .= " t.team_name_official, ";
		$sql .= " team_type, ";
		$sql .= " t.team_city, ";
		$sql .= " t.team_stadium, ";
		$sql .= " t.team_stadium_attendance, ";
		$sql .= " t.team_address, ";
		$sql .= " t.team_postal_zip, ";
		$sql .= " t.team_founded, ";
		$sql .= " t.team_url, ";
		$sql .= " t.team_additional_info, ";
		$sql .= " t.team_nickname, ";
		$sql .= " t.team_manager, ";
		$sql .= " t.team_chairman, ";
		$sql .= " team_federation, ";
		$sql .= " c.country_name, ";
		$sql .= " c.country_id, ";
		$sql .= " c.region_id, ";
		$sql .= " r.region_name, ";
		$sql .= " rg.region_group_name ";
		$sql .= " FROM team t ";
		$sql .= " INNER JOIN country c ON t.country_id = c.country_id  ";
		$sql .= " INNER JOIN region r ON r.region_id = c.region_id  ";
		$sql .= " INNER JOIN regiongroup rg ON rg.region_group_id = r.region_group_id  ";
		$sql .= " where t.team_seoname ='" . $teamSeoName . "'";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	// Applies to Current squad in clubs
	public function findPlayersbyTeam($teamId, $userid = null) {
		$db = $this->getAdapter ();
		$sql = " select p.player_id,p.player_firstname, p.player_lastname,p.player_name_short,p.player_common_name,p.player_position,p.player_country,p.player_nationality,p.player_nickname,p.player_seoname,p.player_dob,tp.jersey_number,c.country_name ";
		if (! is_null ( $userid )) {
			$sql .= " ,(SELECT IF(COUNT(*)=1,'y','n') FROM userplayer WHERE user_id = $userid AND player_id = p.player_id) ismyplayer";
		} else {
			$sql .= ", 'n' as ismyplayer";
		}
		$sql .= " from player p  ";
		$sql .= " inner join teamplayer tp on tp.player_id = p.player_id ";
		$sql .= " inner join team t on tp.team_id = t.team_id ";
		$sql .= " inner join country c on p.player_nationality = c.country_id ";
		$sql .= " inner join playercategory pc on p.player_position = pc.player_category_name ";
		$sql .= " and tp.actual_team = 1 ";
		//$sql .= " and t.team_type = 'club' " ;
		$sql .= " and  tp.team_id =" . $teamId;
		$sql .= " order by pc.player_category_id,p.player_firstname";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function countPlayersbyTeamSearch($teamId) {
		$db = $this->getAdapter ();
		$sql = " select count(*)";
		$sql .= " from teamplayer tp, player p,team t, country c  ";
		$sql .= " where tp.player_id = p.player_id ";
		$sql .= " and p.player_nationality = c.country_id ";
		$sql .= " and tp.team_id = t.team_id ";
		$sql .= " and tp.actual_team = 1 ";
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and t.team_type = 'club' ";
		$sql .= " and  tp.team_id =" . $teamId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ();
		return $row;
	}
	
	public function findPlayersbyTeamSearch($userId, $teamId, $from = null) {
		$db = $this->getAdapter ();
		$sql = " select p.player_id,p.player_firstname, p.player_lastname,p.player_name_short,p.player_position,p.player_country,p.player_nationality,p.player_nickname,p.player_seoname,c.country_name,tp.jersey_number,t.team_name,t.team_seoname";
		if (! is_null ( $userId )) {
			$sql .= " ,(select player_id from userplayer where user_id = $userId and player_id = p.player_id ) as isyourplayer ";
		} else {
			$sql .= " ,NULL as isyourplayer ";
		}
		$sql .= " from teamplayer tp, player p,team t, country c  ";
		$sql .= " where tp.player_id = p.player_id ";
		$sql .= " and p.player_nationality = c.country_id ";
		$sql .= " and tp.team_id = t.team_id ";
		$sql .= " and tp.actual_team = 1 ";
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and t.team_type = 'club' ";
		$sql .= " and  tp.team_id =" . $teamId;
		if (! is_null ( $from )) {
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}
	
    public function selectMatchesPerTeam($teamId, $status, $limit = 'n',$timezone ='+00:00') {
		$db = $this->getAdapter ();
		$sql = null;
		if ($status == 'Played') {
			$sql = self::selectMatchesPerTeamPlayed ( $teamId,$timezone );
		
		} else if ($status == 'Fixture') {
			$sql = self::selectMatchesPerTeamScheduled ( $teamId,$timezone );
		}
		if ($limit == 'y') {
			$sql .= " limit 0,5 ";
		} elseif ($limit == 'one') {
			$sql .= " limit 1 ";
		}
		
		$result = $db->query ( $sql );
		//echo $sql ;
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function selectMatchesPerTeamPlayed($teamId,$timezone) {
		
		$sql = " select m.match_id as matchid, ";
		//$sql .= " m.match_date as mdate, ";
		//$sql .= " m.match_time as time, ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%H:%i:%s') as time,";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname,";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname ,m.match_status as status,m.competition_id as league,";
		$sql .= " m.match_winner as winner ,m.match_minute as match_minute ";
		$sql .= " from matchh m , league_competition l,team t1, team t2 ";
		$sql .= " where m.team_a=" . $teamId . " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id ";
		$sql .= " and m.match_status ='Played' ";
		$sql .= " union ";
		$sql .= " select m.match_id as matchid ,  ";
		$sql .= " m.match_date as mdate, ";
		$sql .= " m.match_time as time, ";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname,";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname ,m.match_status as status,m.competition_id as league,";
		$sql .= " m.match_winner as winner ,m.match_minute as match_minute "; 
		$sql .= " from matchh m , league_competition l,team t1, team t2 ";
		$sql .= " where m.team_a=" . $teamId . " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id ";
		$sql .= " and m.match_status ='Playing'";
		$sql .= " union ";
		$sql .= " select m.match_id as matchid , ";
		//$sql .= " m.match_date as mdate, ";
		//$sql .= " m.match_time as time, ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%H:%i:%s') as time,";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname,";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname,m.match_status as status,m.competition_id as league,";
		$sql .= " m.match_winner as winner ,m.match_minute as match_minute "; 
		$sql .= " from matchh m , league_competition l,team t1, team t2 ";
		$sql .= " where m.team_b=" . $teamId . " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id ";
		$sql .= " and  m.match_status ='Played' ";
		$sql .= " union";
		$sql .= " select m.match_id as matchid ,";
		//$sql .= " m.match_date as mdate, ";
		//$sql .= " m.match_time as time, ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%H:%i:%s') as time,";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname,";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname,m.match_status as status,m.competition_id as league,";
		$sql .= " m.match_winner as winner ,m.match_minute as match_minute ";
		$sql .= " from matchh m , league_competition l,team t1, team t2 ";
		$sql .= " where m.team_b=" . $teamId . " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id ";
		$sql .= " and  m.match_status ='Playing'";
		$sql .= " order by 2 desc,11 ";
		return $sql;
	}
	
	public function selectMatchesPerTeamScheduled($teamId,$timezone) {
		$sql = " select m.match_id as matchid, ";
		//$sql .= " m.match_date as mdate, ";
		//$sql .= " m.match_time as time, ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%H:%i:%s') as time,";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname, ";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname,m.match_status as status, ";
		$sql .= " m.competition_id as league,m.match_winner as winner ,m.match_minute as match_minute ";
		$sql .= " from matchh m , league_competition l,team t1, team t2  ";
		$sql .= " where m.team_a=" . $teamId;
		$sql .= " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id  ";
		$sql .= " and m.match_status ='Fixture' ";
		$sql .= " and m.match_date >= CURDATE() ";
		$sql .= " union  ";
		$sql .= " select m.match_id as matchid , ";
		//$sql .= " m.match_date as mdate, ";
		//$sql .= " m.match_time as time, ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+02:00','$timezone'),'%H:%i:%s') as time,";
		$sql .= " t1.team_id as cteama,t2.team_id as cteamb, t1.team_name as teama,t1.team_seoname as teamaseoname, ";
		$sql .= " m.fs_team_a,m.fs_team_b ,l.competition_name as competition,t2.team_name as teamb,t2.team_seoname as teambseoname,m.match_status as status, ";
		$sql .= " m.competition_id as league,m.match_winner as winner ,m.match_minute as match_minute ";
		$sql .= " from matchh m , league_competition l,team t1, team t2  ";
		$sql .= " where m.team_b=" . $teamId;
		$sql .= " and m.team_a = t1.team_id and m.team_b = t2.team_id and m.competition_id = l.competition_id  ";
		$sql .= " and m.match_status ='Fixture'  ";
		$sql .= " and m.match_date >= CURDATE() ";
		$sql .= " order by mdate  ";
		return $sql;
	}
	
	
	
	public function selectThrophyByTeam($teamId) {
		$db = $this->getAdapter ();
		$sql = " select t.years,lc.competition_name,t.position";
		$sql .= " from throphy t, league_competition lc ";
		$sql .= " where lc.competition_id = t.competition_id ";
		$sql .= " and  t.team_id =" . $teamId;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findTeamByName($criteria, $type = null, $userId = null) {
		$db = $this->getAdapter ();
		$sql = "select t.team_id,t.team_name_official,t.team_name,t.team_seoname, c.country_name,t.team_soccer_type";
		if (! is_null ( $userId )) {
			$sql .= " ,(select team_id from userteam where user_id = " . $userId . " and team_id = t.team_id ) as isyourteam ";
		}
		$sql .= " from team t, country c";
		$sql .= " where ( lower(t.team_name_official) like lower('%$criteria%') ";
		$sql .= " or lower(t.team_name) like lower('%$criteria%') ";
		$sql .= " ) ";
		$sql .= " and t.country_id = c.country_id";
		if (! is_null ( $type )) {
			$sql .= " and t.team_type = '" . $type . "'";
		}
		$sql .= " order by t.team_id,t.team_name_official";
		$sql .= " limit 10";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findTopTeams() {
		$db = $this->getAdapter ();
		$sql = "SELECT T.team_id, T.team_name,T.team_seoname FROM topteam TT INNER JOIN team T on TT.team_id = T.team_id ORDER BY Rand() LIMIT 5";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function findUserTeams() {
		$db = $this->getAdapter ();
		$sql = "SELECT T.team_id, T.team_name,T.team_seoname FROM userteam UT INNER JOIN team T on UT.team_id = T.team_id ORDER BY Rand() LIMIT 5";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function selectFeaturedTeams($type = null, $limit = null, $regiongroupid = null, $userid = null ) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, t.team_name,t.team_seoname,c.country_id,c.country_name, r.region_name ";
		 if (! is_null ( $userid )) {
			$sql .= " ,ust.user_id " ;
		}
		$sql .= " FROM featuredteams ft ";
        $sql .= " INNER JOIN team t ON ft.team_id = t.team_id ";
        $sql .= " INNER JOIN country c ON c.country_id = t.country_id ";
        $sql .= " INNER JOIN region r ON c.region_id = r.region_id ";
		if (! is_null ( $userid )) {
			$sql .= " LEFT JOIN (SELECT user_id,team_id FROM userteam WHERE user_id = ". $userid .") ust ON ust.team_id = t.team_id "; 
		}
		if (! is_null ( $regiongroupid )) {
			$sql .= "  AND r.region_group_id = " . $regiongroupid;
		}
		if (! is_null ( $type )) {
			$sql .= " and t.team_type = '" . $type . "'";
		}
		$sql .= " order by ft.sort_order, rand()  ";
		if (! is_null ( $limit )) {
			$sql .= " limit " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function selectTeamsBySeason($seasonid, $limit = null,$userid = null) {
		$db = $this->getAdapter ();
		$sql = " SELECT t.team_id, t.team_gs_id,t.team_name,t.team_seoname ";
	    if (! is_null ( $userid )) {
			$sql .= " ,ust.user_id " ;
		}
		$sql .= " FROM team t  ";
		$sql .= " INNER JOIN teamseason ts ON t.team_id = ts.team_id ";
		if (! is_null ( $userid )) {
		    $sql .= " LEFT JOIN (SELECT user_id,team_id FROM userteam WHERE user_id = ". $userid .") ust ON ust.team_id = t.team_id "; 
		}
		$sql .= " WHERE ts.season_id = " . $seasonid;
		if (! is_null ( $limit )) {
		    $sql .= " ORDER by rand() ";
			$sql .= " limit " . $limit;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	public function findFeaturedTeams($count,$userid = null) {
		$db = $this->getAdapter ();
	    $sql = "SELECT t.team_id, t.team_name,t.team_seoname, c.country_id, c.country_name,r.region_name ";
	    if (! is_null ( $userid )) {
			$sql .= " ,ust.user_id " ;
		}
        $sql .= " FROM featuredteams ft ";
        $sql .= " INNER JOIN team t ON ft.team_id = t.team_id ";
        $sql .= " INNER JOIN country c ON c.country_id = t.country_id ";
        $sql .= " INNER JOIN region r ON c.region_id = r.region_id ";
			if (! is_null ( $userid )) {
		    $sql .= " LEFT JOIN (SELECT user_id,team_id FROM userteam WHERE user_id = ". $userid .") ust ON ust.team_id = t.team_id "; 
		}
        $sql .= " ORDER BY RAND() LIMIT " . $count;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectPopularTeams($type) {
		$db = $this->getAdapter ();
		$sql = " select distinct t.team_id, t.team_name,t.team_seoname, c.country_id, c.country_name, r.region_name ";		
		$sql .= " from userteam pt";
		$sql .= " where pt.team_id = t.team_id ";
		$sql .= " and t.country_id = c.country_id ";
		$sql .= " and c.region_id = r.region_id ";
		$sql .= " and t.team_type = '" . $type . "'";
		$sql .= " order by t.team_id ";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	//works good
	public function findPopularTeams($count = null,$userid = null,$type = null) {
		$db = $this->getAdapter ();
		$sql = " SELECT ut.team_id, ";
		$sql .= " t.team_name, ";
		$sql .= " t.team_seoname, ";
		$sql .= " t.team_name_official, ";
		$sql .= " c.country_id, ";
		$sql .= " c.country_name, ";
		$sql .= " r.region_name ";
		if (! is_null ( $userid )) {
			$sql .= " ,ust.user_id " ;
		}
		$sql .= " FROM userteam ut ";
		$sql .= " INNER JOIN team t ON t.team_id = ut.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = t.country_id ";
		$sql .= " INNER JOIN region r ON c.region_id = r.region_id ";
		if (! is_null ( $userid )) {
			$sql .= " LEFT JOIN (SELECT user_id,team_id FROM userteam WHERE user_id = ". $userid .") ust ON ust.team_id = t.team_id ";
		}
		if (! is_null ( $type )) {
		    $sql .= " WHERE  t.team_type = '" . $type . "'";
		}
		$sql .= " GROUP BY ut.team_id ";
		$sql .= " ORDER BY rand() ";
		if(!is_null($count)){
			$sql .= "  limit 0, ".$count;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findPopularTeamsUser($count,$userid) {
		$db = $this->getAdapter ();
		$sql = " SELECT ut.team_id, ";
		$sql .= " t.team_name, ";
		$sql .= " t.team_seoname, ";
		$sql .= " t.team_name_official, ";
		$sql .= " c.country_name ";
		$sql .= " FROM userteam ut ";
		$sql .= " INNER JOIN team t ON t.team_id = ut.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = t.country_id ";
		$sql .= " WHERE NOT EXISTS (SELECT 1 FROM userteam utm WHERE utm.team_id = ut.team_id and utm.user_id = ". $userid .") ";
		$sql .= " GROUP BY ut.team_id ";
		$sql .= " ORDER BY rand() ";
		if(!is_null($count)){
			$sql .= "  limit 0, ".$count;
		}
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	//used on building sitemap-clubs.xml
	public function findAllTeams($count) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, replace(t.team_name, '&', '&amp;') as team_name,t.team_seoname, c.country_name ,replace(t.team_name_official, '&', '&amp;') as team_name_official ";
		$sql .= " from team t, country c ";
		$sql .= " where t.country_id = c.country_id and t.team_soccer_type = 'default' ";
		$sql .= " order by t.team_id limit 0, " . $count;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findTeamsAutoComplete ( $search ) {
		$db = $this->getAdapter () ;
		$sql = " select t.team_id, t.team_name, c.country_id,c.country_code " ;
		$sql .= " from team t " ;
		$sql .= " INNER JOIN country c ON c.country_id = t.country_id " ;
		$sql .= " where t.team_name like '$search%' ORDER BY t.team_name LIMIT 5" ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	
	public function getPopularTeamsRandom($count) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, t.team_name,t.team_seoname, c.country_name ";
		$sql .= " from userteam pt, team t, country c ";
		$sql .= " where pt.team_id = t.team_id ";
		$sql .= " and t.country_id = c.country_id ";
		$sql .= "  order by rand() limit " . $count;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	public function getTeamsPerCompetitionParse($compId,$teamid) {
		$db = $this->getAdapter ();
		$sql = " SELECT ts.team_id ";
		$sql .= " FROM season s ";
		$sql .= " INNER JOIN teamseason ts ON ts.season_id = s.season_id ";
		$sql .= " WHERE s.active = 1 AND s.competition_id = " . $compId ;
		if (! is_null ( $teamid )) {
			$sql .= " AND ts.team_id = " . $teamid;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getTeamsInCompetitionByLimit($compId, $from, $userId) {
		$db = $this->getAdapter ();
		$sql = " select t.team_id, t.team_name ,t.team_seoname ,c.country_name ";
		if (! is_null ( $userId )) {
			$sql .= " ,(select team_id from userteam where user_id = " . $userId . " and team_id = t.team_id ) as isyourteam ";
		}
		$sql .= " from team t, teamseason ts, season s, league_competition lc ,country c";
		$sql .= " where t.team_id = ts.team_id ";
		$sql .= " and ts.season_id = s.season_id ";
		$sql .= " and s.competition_id = lc.competition_id ";
		$sql .= " and t.country_id = c.country_id";
		$sql .= " and s.active = 1 and lc.competition_id =" . $compId;
		if (! is_null ( $from )) {
			$sql .= " LIMIT " . $from . ",20";
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getTeamSeasonStats($teamId) {
		$db = $this->getAdapter ();
		$sql = " SELECT  ";
		$sql .= " c.country_name, ";
		$sql .= " ts.team_id, ";
		$sql .= " ts.team_name, ";
		$sql .= " ts.competition_id, ";
		$sql .= " ts.competition_name, ";
		$sql .= " ts.season_name, ";
		$sql .= " ts.rank, ";
		$sql .= " ts.gp, ";
		$sql .= " ts.wn, ";
		$sql .= " ts.dr, ";
		$sql .= " ts.ls, ";
		$sql .= " ts.gf, ";
		$sql .= " ts.gfpg, ";
		$sql .= " ts.ga, ";
		$sql .= " ts.gapg, ";
		$sql .= " ts.pts, ";
		$sql .= " ts.ptspg, ";
		$sql .= " ts.wperce, ";
		$sql .= " ts.plusminus, ";
		$sql .= " ts.hw, ";
		$sql .= " ts.hd, ";
		$sql .= " ts.hl, ";
		$sql .= " ts.aw, ";
		$sql .= " ts.ad, ";
		$sql .= " ts.al, ";
		$sql .= " ts.support, ";
		$sql .= " ts.average, ";
		$sql .= " ts.rc, ";
		$sql .= " ts.yc, ";
		$sql .= " ts.cpg, ";
		$sql .= " ts.cs ";
		$sql .= "FROM teamseasonstats ts,league_competition lc, country c ";
		$sql .= " WHERE ts.competition_id = lc.competition_id ";
		$sql .= " AND lc.country_id = c.country_id ";
		$sql .= " AND ts.team_id = " . $teamId;
		$sql .= " ORDER BY season_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getTeamTrophy($teamId) {
		$db = $this->getAdapter ();
		$sql = " SELECT tss.season_name,tss.competition_id,lc.competition_name, tss.rank,lc.type,lc.format ";
		$sql .= " FROM teamseasonstats tss, league_competition lc ";
		$sql .= " WHERE team_id = " . $teamId;
		$sql .= " AND lc.competition_id = tss.competition_id ";
		$sql .= " ORDER BY season_name DESC ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findTeamSearch($teamId = null,$userid=null) {
		
		$db = $this->getAdapter ();
		$sql = " select t.team_id, t.team_name,t.team_seoname,t.team_name_official,t.team_city,t.team_stadium,t.team_additional_info,c.country_name,c.country_id,c.region_id";
	  if (! is_null ( $userid )) {
			$sql .= " ,ust.user_id " ;
		}
		$sql .= " FROM team t ";
		$sql .= " INNER JOIN country c ON t.country_id = c.country_id ";
		if (! is_null ( $userid )) {
		    $sql .= " LEFT JOIN (SELECT user_id,team_id FROM userteam WHERE user_id = ". $userid .") ust ON ust.team_id = t.team_id "; 
		}
		if (! is_null ( $teamId )) {
			$sql .= " WHERE t.team_id =" . $teamId;
		}
	  //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}

}
?>
