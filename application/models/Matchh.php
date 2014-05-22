<?php
class Matchh extends Zend_Db_Table_Abstract {
	protected $_primary = "match_id" ;
	protected $_name = "matchh" ;
	
	function init () {		
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;		
	}
		public function selectMatches() {
		$db = $this->getAdapter ();
		$sql = " select match_id  ";
		$sql .= " from matchh ";
		$sql .= " where match_id between 370758 and 371670 ";
		$sql .= " order by match_id ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	function selectMatchesSchedulesLeague($leagueId) {
		$db = $this->getAdapter ();
        $sql = " select distinct competition_id ";
        $sql .= " from matchh ";
        $sql .= " where match_date between curdate() and DATE_ADD(Curdate(), INTERVAL 8 DAY) ";
        $sql .= " and match_id LIKE 'G%' ";
        $sql .= " and match_status = 'fixture' ";
		if (!is_null($leagueId)) {
			$sql .= " and competition_id =" . $leagueId ;
		}
        //echo $sql . "<BR>";
        $result = $db->query ( $sql );
        $row = $result->fetchAll ();
        return $row;
	}
	
  	public function getmatchestoupdate ($leagueId) {
  	    $db = $this->getAdapter ();
        $sql = " select match_id,match_id_goalserve,static_id ";
        $sql .= " from matchh ";
        $sql .= " where match_date between curdate() and DATE_ADD(Curdate(), INTERVAL 8 DAY) ";
        $sql .= " and match_id LIKE 'G%' ";
        $sql .= " and match_status = 'fixture' ";
        $sql .= " and competition_id = ". $leagueId;
        //echo $sql . "<BR>";
        $result = $db->query ( $sql );
        $row = $result->fetchAll ();
        return $row;
    }
    
  //Change timezone HERE  +XX.XX or -XX.XX ---- ( Rest of year -00:01)  (November - April +00:00)
	public function selectAllMatchesByCountryLeague ( $dates , $countryId = null , $league_id = null , $matchid = null ) {
		$db = $this->getAdapter () ;
		$sql = "select ";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$dates[7]'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$dates[7]'),'%H:%i:%s') as time,";
		$sql .= "  t1.team_name as teama,m.fs_team_a,m.fs_team_b , " ;
		$sql .= " t2.team_name as teamb,m.match_status as status,m.competition_id as league,l.competition_name ,m.match_winner as winner , " ;
		$sql .= " m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid,m.match_minute as match_minute" ;
		$sql .= " from matchh m , team t1, team t2 ,league_competition l " ;
		$sql .= " where m.team_a =  t1.team_id " ;
		$sql .= " and   m.team_b =  t2.team_id " ;
                //$sql .= " and m.match_date between '2010-07-27' and '2010-07-31' " ;
		//$sql .= " and m.match_date between '" . $todays_date . "' and '" . $one_week_after . "'" ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		$sql .= " and m.competition_id = l.competition_id " ;
		if (!is_null($league_id)) {
			$sql .= " and m.competition_id =" . $league_id ;
		}
		if ($countryId != '0') {
			$sql .= " and m.country_id =" . $countryId ;
		}
		if ($matchid != '') {
			$sql .= " and match_id not in ('" . $matchid . "' ) " ;
		}
		$sql .= " order by league, mdate asc" ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	public function updateMatch ( $matchid , $data ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "match_id = ?", $matchid ) ;
		return $this->update ( $data, $where ) ;
	}
	
	public function updateMatchStatic ( $staticid , $data ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "static_id = ?", $staticid ) ;
		return $this->update ( $data, $where ) ;
	}
	
	public function updateMatchScoposy ( $matchid , $data ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "match_id_spocosy = ?", $matchid ) ;
		return $this->update ( $data, $where ) ;
	}
	
	public function updateMatchGoalServe ( $matchid , $data ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "match_id_goalserve = ?", $matchid ) ;
		return $this->update ( $data, $where ) ;
	}
	
	public function findMatchById2 ( $matchId, $competitionId = 0 ) {
		$db = $this->getAdapter () ;
		$sql = " select m.competition_id,l.competition_name,m.season_id,m.round_id,m.group_id,m.match_id,m.match_date,t1.team_name AS t1, t2.team_name AS t2,c2.country_name AS country_team_a, c3.country_name AS country_team_b,c2.country_id AS country_id_team_a ,c3.country_id AS country_id_team_b , m.team_a, m.team_b, t1.team_spocosy_id as team_a_spocosy, t2.team_spocosy_id as team_b_spocosy ,m.fs_team_a,fs_team_b,m.country_id,t1.team_stadium , c.region_id , m.match_status AS match_status " ;
		$sql .= " from matchh m, league_competition l , team t1, team t2 , country c , country c2 , country c3" ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id" ;
		$sql .= " and m.competition_id = l.competition_id" ;
		$sql .= " and match_id = '" . $matchId ."'";
		$sql .= " and m.country_id = c.country_id";
		$sql .= " and t1.country_id = c2.country_id" ;
		$sql .= " and t2.country_id = c3.country_id" ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		//echo $sql ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	
	}
	
	public function findMatchById ( $matchId, $competitionId = 0 ) {
		$db = $this->getAdapter () ;
		$sql = " select m.competition_id,l.competition_name,l.format,m.season_id,m.round_id,m.group_id,m.match_id,m.match_date,m.match_time ,t1.team_name AS t1,t1.team_seoname AS t1seoname, t2.team_name AS t2,t2.team_seoname AS t2seoname" ;
		$sql .= " ,c2.country_name AS country_team_a, c3.country_name AS country_team_b,c2.country_id AS country_id_team_a" ; 
		$sql .= " ,c3.country_id AS country_id_team_b , t1.team_id as team_idA, t2.team_id as team_idB," ;
 		$sql .= " m.team_a, m.team_b,t1.team_gs_id as team_a_gs, t2.team_gs_id as team_b_gs , m.fs_team_a,fs_team_b,m.country_id,t1.team_stadium, m.match_status AS match_status,m.match_id_goalserve,m.static_id, " ;
		$sql .= " v.venue_id,v.venue_name,v.venue_city ";
    $sql .= " from matchh m" ;
		$sql .= " inner join league_competition l ON l.competition_id = m.competition_id " ;
		$sql .= " left outer join venue v ON v.venue_id = m.venue_id";
		$sql .= " inner join team t1 on t1.team_id = m.team_a" ;
		$sql .= " inner join team t2 on t2.team_id = m.team_b" ;
		$sql .= " inner join country c2 on c2.country_id = t1.country_id" ;
		$sql .= " inner join country c3 on c3.country_id = t2.country_id" ;
		$sql .= " and  m.match_id = '" . $matchId ."'";
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		//echo $sql ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	
	}
	
	
	public function findMatchBySpocosyId2 ( $matchId, $competitionId = 0 ) {
		$db = $this->getAdapter () ;
		$sql = " select m.competition_id,l.competition_name,m.season_id,m.round_id,m.group_id,m.match_id,m.match_date,t1.team_name AS t1, t2.team_name AS t2,c2.country_name AS country_team_a, c3.country_name AS country_team_b,c2.country_id AS country_id_team_a ,c3.country_id AS country_id_team_b , m.team_a, m.team_b,m.fs_team_a,fs_team_b,m.country_id,t1.team_stadium , c.region_id , m.match_status AS match_status " ;
		$sql .= " from matchh m, league_competition l , team t1, team t2 , country c , country c2 , country c3" ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id" ;
		$sql .= " and m.competition_id = l.competition_id" ;
		$sql .= " and match_id_spocosy = " . $matchId ;
		$sql .= " and m.country_id = c.country_id";
		$sql .= " and t1.country_id = c2.country_id" ;
		$sql .= " and t2.country_id = c3.country_id" ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		//echo $sql ."<br>" ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	
	}
	
	public function findMatchBySpocosyId ( $matchId, $competitionId = 0 ) {
		$db = $this->getAdapter () ;
		$sql = " select m.competition_id,l.competition_name,m.season_id,m.round_id,m.group_id,m.match_id,m.match_date,t1.team_name AS t1, t2.team_name AS t2" ;
		$sql .= " ,c2.country_name AS country_team_a, c3.country_name AS country_team_b,c2.country_id AS country_id_team_a" ; 
		$sql .= " ,c3.country_id AS country_id_team_b ," ;
 		$sql .= " m.team_a, m.team_b,t1.team_spocosy_id as team_a_spocosy, t2.team_spocosy_id as team_b_spocosy , m.fs_team_a,fs_team_b,m.country_id,t1.team_stadium, m.match_status AS match_status" ;
		$sql .= " from matchh m" ;
		$sql .= " inner join league_competition l ON l.competition_id = m.competition_id " ;
		$sql .= " inner join team t1 on t1.team_id = m.team_a" ;
		$sql .= " inner join team t2 on t2.team_id = m.team_b" ;
		$sql .= " inner join country c2 on c2.country_id = t1.country_id" ;
		$sql .= " inner join country c3 on c3.country_id = t2.country_id" ;
		$sql .= " and  m.match_id_spocosy = '" . $matchId ."'";
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		//echo $sql ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	
	}
	
	//public function selectCurrentMatchesByCountryLeague ( $todays_date , $one_week_after ) {
	public function selectCurrentMatchesByCountryLeague ( $dates ) {
		$db = $this->getAdapter () ;
		$sql = " select m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, m.match_minute as match_minute, l.regional " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.competition_id = l.competition_id " ;
		//$sql .= " and m.match_date_time between '2010-07-27' and '2010-07-31' " ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		$sql .= " order by c.regional desc,c.priority,mdate,l.sort_priority, m.competition_id" ; //added sorting competition_id
		
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	
	}
	
	public function compareTeamsHead2Head($teamAId , $teamBId, $competitionId = 0){
		
		$db = $this->getAdapter () ;
		$sql = " select m.season_id as seasonId ,m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, l.type " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_a = " .$teamAId ." and  m.team_a = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union " ;
		$sql .= " select m.season_id as seasonId ,m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, l.type " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_a = " .$teamAId ." and  m.team_b = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union " ;
		$sql .= " select m.season_id as seasonId ,m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, l.type " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_b = " .$teamAId ." and  m.team_a = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union " ;
		$sql .= " select m.season_id as seasonId ,m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, l.type " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_b = " .$teamAId ." and  m.team_b = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " order by mdate desc" ; //
		
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
		
	}
	
	public function getYearFirstHead2HeadGame($teamAId , $teamBId, $competitionId = 0){
		$db = $this->getAdapter () ;
		
		
		$sql = " select min(year) as year from " ;
		$sql .= " ( " ;
		$sql .= " select year(min(m.match_date)) as year " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_a = " .$teamAId ." and  m.team_a = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union";
		$sql .= " select year(min(m.match_date)) as year " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_a = " .$teamAId ." and  m.team_b = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union";
		$sql .= " select year(min(m.match_date)) as year " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_b = " .$teamAId ." and  m.team_a = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " union";
		$sql .= " select year(min(m.match_date)) as year " ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.match_status = 'Played'";
		$sql .= " and  m.team_b = " .$teamAId ." and  m.team_b = " .$teamBId ;
		$sql .= " and m.competition_id = l.competition_id " ;
		if ($competitionId != '0') {
			$sql .= " and m.competition_id = ". $competitionId;		
		}
		$sql .= " ) data" ;
		
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	
	
	public function selectTopCountriesByPriority($limit , $regionId , $dates)
	{
		$db = $this->getAdapter ();
		$sql = " select distinct c.country_id   from country c,`matchh` m "; 
		$sql .= " where c.country_id = m.country_id ";  
		//$sql .= " and m.match_date between between '2010-07-27' and '2010-07-31'" ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		if ($regionId != '0') {
			$sql .= " and c.region_id =". $regionId ;
		}
		$sql .= " order by c.regional desc ,c.priority "; 
		$sql .= " LIMIT 0,".$limit ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	
//Change timezone HERE  +XX.XX or -XX.XX ---- ( Rest of year -00:01)  (November - April +00:00)
public function selectCurrentMatchesByRegion( $dates , $regionId , $countryId = null  ,$status = null ,$arrayTopCountries = null , $show) {
		$db = $this->getAdapter () ;
		$sql = " select " ;
		/*if($dates [6] == 'today'){
			$sql .= strftime("%Y%m%d",strtotime($dates[1]))." as mdate,";
		}else{
			$sql .= " m.match_date as mdate, ";
		}*/
		
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$dates[7]'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$dates[7]'),'%H:%i:%s') as time,";
		$sql .= " t1.team_name as teama,";
		//$sql .= " CASE WHEN CHAR_LENGTH(t1.team_name) > 15 THEN CONCAT(SUBSTRING(t1.team_name, 1, 15), '...') ELSE t1.team_name END AS teama,";
		$sql .= " m.fs_team_a,m.fs_team_b,";
		$sql .= " t2.team_name as teamb,";
		$sql .= " m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid , m.match_minute as match_minute" ;

		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l ,region r" ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		if ($arrayTopCountries != null) {
			$sql .= " and c.country_id in (" .$arrayTopCountries .")" ;
		}	
		$sql .= " and m.competition_id = l.competition_id " ;
		if($show == 'top'){ 
			$sql .= " and l.topcompetition = 1 " ;
		}
		//$sql .= " and m.match_date between '2010-07-25' and '2010-07-31'" ;
		
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		
		$sql .= " and c.region_id = r.region_id " ; //add for not show women -> and l.active = 1
		if ($regionId != '0') {
			$sql .= " and r.region_id in(" . $regionId . ")" ;
		}
		
		if ($countryId != '0') {
			$sql .= " and m.country_id=" . $countryId ;
		}
		if ($status != '0') {
			$sql .= " and m.match_status ='" . $status  . "'" ;
		}
		
		$sql .= " order by country,m.competition_id,mdate,m.match_time,m.match_status " ; //added sorting competition_id
		//echo $sql ;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		
		return $rows ;
	
	}
	
	// Need to add UNION to get matches per userteam (all matches per team which a user has as favorites) added JV..10/17/2010
	
	//public function selectCurrentMatchesBycountryLeagueLoggedIn ( $todays_date , $one_week_after , $user_id ) {
	public function selectCurrentMatchesBycountryLeagueLoggedIn ( $dates , $user_id , $auth = null ,$limit = null ,$competitionId = null) {
		$db = $this->getAdapter () ;
		$sql = " select m.match_date as mdate,m.match_time as time, t1.team_name as teama,m.fs_team_a,m.fs_team_b , t2.team_name as teamb,m.match_status as status,m.country_id as country,c.country_name as cname,m.competition_id as league,l.competition_name ,l.sort_priority as priority,m.match_winner as winner , m.team_a as cteama, m.team_b as cteamb ,m.match_id as matchid, m.match_minute as match_minute, l.regional" ;
		$sql .= " from matchh m , team t1, team t2 ,country c,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id  and m.country_id = c.country_id " ;
		$sql .= " and m.competition_id = l.competition_id " ;
		$sql .= " and m.competition_id in (select distinct competition_id " ;
		$sql .= "                           from userleague " ;
		$sql .= "                           where user_id =" . $user_id . ") " ;
		//$sql .= " and m.match_date_time between '2010-07-25' and '2010-07-31'" ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
        $sql .= " and l.soccer_type = 'default' ";
        if(!is_null($competitionId)){
         	$sql .= " and l.competition_id = " .$competitionId ;
        }
        if(!is_null($auth)){
        	$sql .= " order by mdate " .$auth  ." ,league" ;
        }else{
			$sql .= " order by c.regional desc,country,l.sort_priority,league,mdate " ;
        }
        if(!is_null($limit)){
        	$sql .= " limit " . $limit;
        }
        
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows =  $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	
	}
	
	//This Query needs to be optimized JV 09-02-2011
	public function selectFavoriteLeaguesMatches($user_id,$limit = null ,$typeOfMatches ,$auth , $competitionId = null, $fixtureDate = null) {
		$db = $this->getAdapter () ;
		/*$sql = " SELECT  m.season_id, ";
		$sql .= "        m.match_date as mdate, ";
		$sql .= "		 m.match_time as time, ";		
		$sql .= "		 t1.team_name as teama, ";
		$sql .= "		 m.fs_team_a, ";
		$sql .= "	     m.fs_team_b, ";
		$sql .= "		 t2.team_name as teamb, ";
		$sql .= "		 m.match_status as status, ";
		$sql .= "		 m.country_id as country, ";
		$sql .= "		 c.country_name as cname, ";
		$sql .= "		 m.competition_id as league, ";
		$sql .= "		 l.competition_name, ";
		$sql .= "		 l.sort_priority as priority ,";
		$sql .= "		 m.match_winner as winner, "; 
		$sql .= "		 m.team_a as cteama, "; 
		$sql .= "		 m.team_b as cteamb, ";
		$sql .= "		 m.match_id as matchid, "; 
		$sql .= "		 m.match_minute as match_minute ";
		$sql .= " FROM matchh m , team t1, team t2 ,country c,league_competition l, season s ";
		$sql .= " 		where m.team_a = t1.team_id and m.team_b = t2.team_id and m.country_id = c.country_id and m.competition_id = l.competition_id and m.season_id = s.season_id ";
		$sql .= " 		and m.competition_id in (select distinct competition_id from userleague where user_id = " . $user_id . ") " ;  
		$sql .= " 		and l.soccer_type = 'default' and s.active = 1 ";*/
		
		$sql = "	SELECT m.match_id as matchid, ";
		$sql .= "	m.season_id, ";
		$sql .= "	m.match_date as mdate, ";
		$sql .= "	match_time as time, ";
		$sql .= "	m.team_a as cteama, ";
		$sql .= "	m.team_b as cteamb, ";
		$sql .= "	t1.team_name as teama, ";
		$sql .= "	t2.team_name as teamb, ";
		$sql .= "	m.fs_team_a, ";
		$sql .= "	m.fs_team_b, ";
		$sql .= "	m.match_status as status, ";
		$sql .= "	m.match_winner as winner, ";
		$sql .= "	m.match_minute as match_minute, ";
		$sql .= "	l.competition_id as league, ";
		$sql .= "	l.competition_name, ";
		$sql .= "	l.sort_priority AS priority, ";
		$sql .= "	m.country_id AS country, ";
		$sql .= "	c.country_name AS cname ";
		$sql .= "	FROM league_competition l ";
		$sql .= "	INNER JOIN matchh m ON m.competition_id = l.competition_id ";
		$sql .= "	INNER JOIN team t1 ON t1.team_id = m.team_a ";
		$sql .= "	INNER JOIN team t2 ON t2.team_id = m.team_b ";
		$sql .= "	INNER JOIN season s ON m.season_id = s.season_id ";
		$sql .= "	INNER JOIN country c ON m.country_id = c.country_id ";
		$sql .= "	WHERE EXISTS ( ";
		$sql .= "			SELECT 1 ";
		$sql .= "			FROM userleague ulc ";
		$sql .= "			WHERE ulc.competition_id = l.competition_id ";	
		$sql .= "			AND ulc.user_id = " . $user_id . ") " ;
		$sql .= "	AND s.active = 1 " ;
		$sql .= "	AND l.soccer_type = 'default' " ;
		
		if(!is_null($competitionId)){
         	$sql .= " and l.competition_id = " .$competitionId ;
        }
		if($typeOfMatches == 'played'){
			$sql .= "	AND (m.match_status = 'Played' OR m.match_status = 'Playing') " ;
		}else {
			$sql .= " 		and m.match_status = 'Fixture' ";
			$sql .= " 		and m.match_date_time >= '".strftime("%Y%m%d%H%M%S",strtotime($fixtureDate[1]))."' ";
		}
		
	 	if(!is_null($auth)){
        	$sql .= " order by mdate " .$auth  ." ,league" ;
    }else{
			$sql .= " order by country,l.sort_priority,league,mdate " ;
    }
		
		if(!is_null($limit)){
        	$sql .= " limit " . $limit;
        }
    //echo $sql;
    $result = $db->query ( $sql ) ;
		$rows =  $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	//public function countMatchesBycountry ( $todays_date , $one_week_after ) {
	public function countMatchesBycountry ( $dates ) {
		$db = $this->getAdapter () ;
		$sql = " select count(*) as matchescount, m.country_id as country,c.country_name as cname,r.region_id as region, l.regional " ;
		$sql .= " from matchh m , country c ,league_competition l,team t1, team t2 ,region r" ;
		$sql .= " where  m.country_id = c.country_id " ;
		$sql .= " and m.competition_id = l.competition_id " ;
		//$sql .= " and m.match_date between '2010-07-27' and '2010-07-31'" ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		$sql .= " and c.region_id = r.region_id  " ;
		$sql .= " and m.team_a = t1.team_id and m.team_b = t2.team_id " ;
		$sql .= " group by country " ;
		$sql .= " order by c.regional desc,c.priority,l.sort_priority " ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	//public function countMatchesBycountryRegion($todays_date , $one_week_after , $regionId , $show ,$arrayTopCountries ,$limit = null ){
	public function countMatchesBycountryRegion($dates, $regionId , $show ,$arrayTopCountries ,$limit = null ){
		$db = $this->getAdapter () ;
		$sql = " SELECT DISTINCT rg.region_group_seoname,rg.region_name_gf, rg.region_group_id,c.country_name AS cname,c.country_id as country, c.regional as regional, r.region_id AS region ,c.priority "; 
				$sql .= " ,( SELECT COUNT(*)  FROM topmatches tm  " ;
                $sql .= " WHERE ";
                $sql .= " tm.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
                //$sql .= " tm.match_date_time between '2010-07-25' and '2010-07-31'" ;
                $sql .= " AND tm.country_id = c.country_id  " ;
                if($show == 'top'){
                    $sql .= " AND tm.topcompetition = 1  " ;
                }
                if ($regionId != '0') {
                	$sql .= " 		AND tm.region_id IN(" . $regionId . ")" ;
				}
                $sql .= " ) AS matchescount ";
				//$sql .= " ,'5' as matchescount ";
		
		$sql .= "FROM country c  " ;
		$sql .= " INNER JOIN region AS r ON r.region_id  = c.region_id "; 
		$sql .= " INNER JOIN regiongroup rg ON rg.region_group_id = r.region_group_id  " ;
		$sql .= " INNER JOIN league_competition lc on lc.country_id = c.country_id " ;
		$sql .= " WHERE lc.active = 1 " ;
		if ($arrayTopCountries != null) {
			$sql .= " AND c.country_id in (" .$arrayTopCountries .")" ;
		}	
		if ($regionId != '0') {
			$sql .= " AND r.region_id in(" . $regionId . ")" ;
			$sql .= " AND c.country_id <9001 ";
		}
		
		$sql .= " ORDER BY c.regional DESC, c.priority ASC  " ;
		if(!is_null($limit) == '10'){
			$sql .= "LIMIT " . $limit ;
		}
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
		
	}
	
	public function countMatchesBycountryRegionOld ( $todays_date , $one_week_after , $regionId , $countryId ,$status ,$arrayTopCountries =null) {
		$db = $this->getAdapter () ;
		$sql = " select count(*) as matchescount, m.country_id as country,c.country_name as cname,r.region_id as region,l.regional as regional  " ;
		$sql .= "from matchh m , country c ,league_competition l,team t1, team t2, region r " ;
		$sql .= "where  m.country_id = c.country_id " ;
		if ($arrayTopCountries != null) {
			$sql .= " and c.country_id in (" .$arrayTopCountries .")" ;
		}
		$sql .= "and m.competition_id = l.competition_id " ;
		$sql .= " and m.match_date between '" . $todays_date . "' and '" . $one_week_after . "'" ;
		$sql .= " and m.team_a = t1.team_id and m.team_b = t2.team_id " ;
		$sql .= " and c.region_id = r.region_id " ;
		if ($regionId != '0') {
			$sql .= " and r.region_id in(" . $regionId . ")" ;
		}
		
		if ($countryId != '0') {
			$sql .= " and m.country_id=" . $countryId ;
		}
		if ($status != '0') {
			$sql .= " and m.match_status ='" . $status  . "'" ;
		}
		$sql .= " group by country " ;
		$sql .= " order by l.regional desc,c.priority asc,country " ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	public function countMatchesByCountryLoggedIn ( $dates , $user_id ) {
		$db = $this->getAdapter () ;
		
		$commonsql  = "select distinct l.competition_id from userteam u,team t,league_competition l where u.team_id = t.team_id and t.country_id = l.country_id and user_id = ". $user_id ;
		$commonsql .= " union  " ;
		$commonsql .= " select distinct competition_id from userleague where user_id = ". $user_id ;
		
		$sql = " SELECT rg.region_group_seoname,rg.region_name_gf, rg.region_group_id ,c.country_name AS cname,c.country_id as country , r.region_id AS region ,c.priority,l.regional , COUNT(*) AS matchescount " ;
		$sql .= " FROM country c JOIN region AS r ON r.region_id = c.region_id  " ;
		$sql .= " JOIN regiongroup rg ON rg.region_group_id = r.region_group_id  " ;
		$sql .= " JOIN league_competition AS l ON l.country_id = c.country_id  " ;
		$sql .= " and l.competition_id in ( " . $commonsql . ") " ;
		$sql .= " JOIN topmatches AS tm ON tm.country_id = c.country_id " ;
                //$sql .= " AND tm.match_date_time between '2010-07-27' and '2010-07-31'" ;
		$sql .= " AND tm.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		$sql .= " AND tm.competition_id = l.competition_id  " ;
		$sql .= " group by c.country_id " ;
		$sql .= " ORDER BY c.regional DESC, c.priority ASC " ;
				
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		//Zend:: dump ($rows);
		return $rows ;
	}
	
	//public function countMatchesByLeague ( $todays_date , $one_week_after , $country , $league_id = null ) {
	public function countMatchesByLeague ( $dates , $country , $league_id = null ) {
		$db = $this->getAdapter () ;
		$sql = " select count(*) as matchescount,m.competition_id as league,l.competition_name " ;
		$sql .= " from matchh m , team t1, team t2 ,league_competition l " ;
		$sql .= " where m.team_a = t1.team_id and m.team_b = t2.team_id " ;
		//$sql .= " and m.match_date between '2010-07-27' and '2010-07-31'" ;
		$sql .= " and m.match_date_time between '".strftime("%Y%m%d%H%M%S",strtotime($dates[1]))."' and '".strftime('%Y%m%d%H%M%S',strtotime($dates[5]))."'";
		$sql .= " and m.competition_id = l.competition_id ";
		if ($country != '0') {
			$sql .= " and m.country_id =" . $country ;
		}
		
		if (!is_null($league_id)) {
			$sql .= " and m.competition_id =" . $league_id ;
		}
		$sql .= " group by league " ;
		$sql .= " order by m.match_date " ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	
	//Change timezone HERE  +XX.XX or -XX.XX ---- ( Rest of year -00:01)  (November - April +00:00)
	public function selectTotalPlayedMatchesBySeason2 ($seasonId = null , $roundList = null, $scoresOrSchedule = null , $timezone ='+00:00',$roundcount) {
		$db = $this->getAdapter () ;
		$sql = " SELECT s.season_id as seasonId, r.round_id,r.round_title,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$timezone'),'%H:%i:%s') as time,";	
		$sql .= " t1.team_name AS teama,m.fs_team_a,m.fs_team_b , t2.team_name AS teamb,m.match_status AS status, "; 
		$sql .= " m.competition_id AS league,l.competition_name,m.match_winner AS winner , m.team_a AS cteama, m.team_b AS cteamb ,m.match_id AS matchid ,m.match_minute ";
		$sql .= " FROM matchh m ";
		$sql .= " LEFT OUTER JOIN team AS t1 ON t1.team_id  = m.team_a  ";
		$sql .= " LEFT OUTER JOIN team AS t2 ON t2.team_id  = m.team_b ";
		$sql .= " JOIN league_competition AS l ON l.competition_id = m.competition_id ";
		$sql .= " JOIN season AS s ON s.season_id =  m.season_id  ";
		$sql .= " JOIN round AS r ON r.round_id =  m.round_id  ";
		$sql .= " WHERE m.season_id = " . $seasonId;
		if($roundcount > 1){
			$sql .= " AND m.round_id IN (" .$roundList ." )  ";
		} else {
			$sql .= " AND m.round_id = " . $roundList;
		}
		 //if querying by status
       	if(!is_null($scoresOrSchedule)){
           if($scoresOrSchedule == 'Played'){
              $sql .= " AND m.match_status IN ('Played','Playing')";
           }else{
              $sql .= " AND m.match_status = '".$scoresOrSchedule."'";
           }
        	if($scoresOrSchedule == 'Played'){
              	$sql .= " ORDER BY mdate DESC ";
          	}else if($scoresOrSchedule == 'Fixture'){
              	$sql .= " ORDER BY mdate,time DESC ";
          	}
         } else { //if querying by ALL for module 
            $sql .= " ORDER BY r.start_date DESC,mdate DESC";
         } 
         //echo $sql;    
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	
	
	//Change timezone HERE  +XX.XX or -XX.XX ---- ( Rest of year -00:01)  (November - April +00:00)
	public function selectTotalPlayedMatchesBySeason ( $seasonId = null ,$scoresOrSchedule = null , $roundId = null ,$roundType = null ,$roundList = null ,$compFormat = null, $timezone ='+00:00') {
		$db = $this->getAdapter () ;
		$sql = " SELECT ";
		if($compFormat == 'International cup' and $roundType == 'table'){
			if($roundType == 'table'){
				$sql .= " DISTINCT";
			}
		}
		$sql .= " s.season_id as seasonId, r.round_id,r.round_title,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$timezone'),'%Y-%m-%d') as mdate,";
		$sql .= " DATE_FORMAT(CONVERT_TZ(concat(m.match_date,' ',m.match_time) , '+00:00','$timezone'),'%H:%i:%s') as time,";	
		$sql .= " t1.team_name AS teama,m.fs_team_a,m.fs_team_b , t2.team_name AS teamb,m.match_status AS status, "; 
		$sql .= " m.competition_id AS league,l.competition_name,m.match_winner AS winner , m.team_a AS cteama, m.team_b AS cteamb ,m.match_id AS matchid ,m.match_minute ";
		$sql .= " FROM matchh m ";
		$sql .= " LEFT OUTER JOIN team AS t1 ON t1.team_id  = m.team_a  ";
		$sql .= " LEFT OUTER JOIN team AS t2 ON t2.team_id  = m.team_b ";
		$sql .= " JOIN league_competition AS l ON l.competition_id = m.competition_id ";
		$sql .= " JOIN season AS s ON s.season_id =  m.season_id  ";
		
			if($compFormat == 'International cup') {			
					if(!is_null($roundId)){
						$sql .= " JOIN round AS r ON r.round_id =  m.round_id  ";
						$sql .= " AND m.round_id = " . $roundId;
					}
					if(!is_null($seasonId)){
						$sql .= " AND m.season_id = " . $seasonId;
					}
					if(!is_null($roundList)){
						$sql .= " AND m.round_id IN (" .$roundList ." )  ";
					}
					
				//}
			}else{
					$sql .= " JOIN round AS r ON r.round_id =  m.round_id  ";
					if(!is_null($seasonId)){
						$sql .= " AND m.season_id = " . $seasonId;
					}
					if(!is_null($roundId)){
						$sql .= " AND m.round_id = " . $roundId;
					}
			}
			

	  //if querying by status
       if(!is_null($scoresOrSchedule)){
           if($scoresOrSchedule == 'Played'){
              $sql .= " AND m.match_status IN ('Played','Playing')";
           }else{
              $sql .= " AND m.match_status = '".$scoresOrSchedule."'";
           }
          if($scoresOrSchedule == 'Played'){
              $sql .= " ORDER BY mdate DESC ";
          }else if($scoresOrSchedule == 'Fixture'){
              $sql .= " ORDER BY mdate,time DESC ";
          }
         } else { //if querying by ALL for module 
             //$sql .= " ORDER BY mdate DESC";
             $sql .= " ORDER BY r.start_date DESC,mdate DESC";
         }               
		//echo $sql;
		//echo '<br>$$'.$compFormat.'&&';
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	
	public function countTotalMatchesBySeasonByStatus ( $seasonId = null ,$scoresOrSchedule = null , $roundId = null) {
		$db = $this->getAdapter () ;
		$sql = " SELECT s.season_id as seasonId, m.match_date AS mdate,m.match_time AS TIME, t1.team_name AS teama,m.fs_team_a,m.fs_team_b , t2.team_name AS teamb,m.match_status AS status, ";
		$sql .= " m.competition_id AS league,l.competition_name ,m.match_winner AS winner , m.team_a AS cteama, m.team_b AS cteamb ,m.match_id AS matchid ,m.match_time as time ";
		$sql .= " FROM matchh m , team t1, team t2 ,league_competition l ,season s ";
		$sql .= " WHERE m.team_a = t1.team_id AND m.team_b = t2.team_id  ";
		$sql .= " AND m.season_id = s.season_id ";
		$sql .= " AND m.competition_id = l.competition_id";
		if(!is_null($seasonId)){
			$sql .= " AND m.season_id = " . $seasonId;
		}
		if(!is_null($roundId)){
			$sql .= " AND m.round_id = " . $roundId;
		}
		$sql .= " AND m.match_status = '".$scoresOrSchedule."'";
		
        //echo $sql . "<br>";
		$result = $db->query ( $sql ) ;
		$column = $result->fetchColumn ( 0 );
		//echo $column;
		return $column;
	}
	
	
	public function getLastFiveMatches($teamId) {
		$db = $this->getAdapter ();
		$sql = " select match_winner,match_date  ";
		$sql .= " from matchh ";
		$sql .= " where (team_a = " .$teamId . " )";
		$sql .= " AND match_status = 'Played' ";
		$sql .= " UNION ";
		$sql .= " select match_winner,match_date from matchh ";
		$sql .= " where (team_b = " .$teamId . " )";
		$sql .= " AND match_status = 'Played' ";
		$sql .= " order by 2 desc limit 5 ";
	  //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getCleanSheetsPerRound ($roundId,$teamId) {
	    $db = $this->getAdapter ();
		$sql = " select count(*) from matchh "; 
	    $sql .= " where (team_a = " . $teamId ." or team_b = ". $teamId . " )";
	    $sql .= " and match_status = 'played' ";
	    $sql .= " and round_id = " . $roundId;
	    $sql .= " and match_winner = " . $teamId;
	    $sql .= " and (fs_team_a = 0 or fs_team_b = 0)";
	    //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn (0) ;
		return $row;
  }
  

    
   public function selectNextPreviousMatchByRound($roundIdList , $status  , $compType = null){
   	 	 $db = $this->getAdapter (); 
         $sql = " SELECT s.season_id as seasonId, m.match_date AS mdate,m.match_time AS TIME,  ";
         $sql .= " t1.team_name AS teama,t1.team_seoname AS teamaseoname,m.fs_team_a,m.fs_team_b , t2.team_name AS teamb,t2.team_seoname AS teambseoname,m.match_status AS status, m.competition_id AS league, ";
         $sql .= " l.competition_name ,m.match_winner AS winner , m.team_a AS cteama, m.team_b AS cteamb ,m.match_id AS matchid , ";
         $sql .= " m.match_time as time,m.match_minute FROM matchh m , team t1, team t2 ,league_competition l ,season s  ";
         $sql .= " WHERE m.team_a = t1.team_id AND m.team_b = t2.team_id AND m.season_id = s.season_id  ";
         $sql .= " AND m.competition_id = l.competition_id  AND ";
   		
         if($compType == 'International cup'){
      		$sql .= " m.round_id IN  (" .  $roundIdList . ")";
      	 }else{
      		$sql .= " m.round_id = " . $roundIdList;
      	 }
         $sql .= " AND m.match_status = '" . $status  . "'" ;
         if ($status == 'Played') {
         $sql .= " ORDER BY mdate desc,TIME desc  ";
         } else {
         $sql .= " ORDER BY mdate,TIME ASC  ";
         }
         $sql .= " limit 1 ";
         //echo $sql . "<br>";
         $result = $db->query ( $sql );
         $row = $result->fetch ();
         return $row;
   }     
}
?>
