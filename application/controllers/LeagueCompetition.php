<?php
class LeagueCompetition extends Zend_Db_Table_Abstract {
	protected $_name = 'league_competition';
	protected $_primary = "competition_id";

        function init() {
		Zend_Loader::loadClass ( 'Zend_Debug' );
	}

    public function getLeaguePageFanFaceBookAlerts() {
		$db = $this->getAdapter();
		$sql = "SELECT l.*,u.facebookid,u.facebookaccesstoken from league_competition l inner join user u on l.user_id=u.user_id 
				WHERE l.user_id is not null and l.facebook_idPage is not null and u.facebookaccesstoken is not null";
		$result = $db->query ($sql);
		echo $sql;
		$row = $result->fetchAll();
		return $row;
	}
	
	public function selectLeaguePageFanFaceBookAlerts($competition_id) {
		$db = $this->getAdapter();
		$sql = "SELECT l.*,u.facebookid,u.facebookaccesstoken from league_competition l inner join user u on l.user_id=u.user_id 
				WHERE l.user_id is not null and l.facebook_idPage is not null and u.facebookaccesstoken is not null and competition_id=".$competition_id;
		$result = $db->query ($sql);
		echo $sql;
		$row = $result->fetchAll();
		return $row;
	}
	
	
    public function findAllLeagues(){
		$db = $this->getAdapter ();
        $sql = "select l.competition_id ,l.competition_name, l.type,l.format ,l.country_id,c.country_name";
        $sql .= " from league_competition l ,country c";
        $sql .= " where l.country_id = c.country_id and l.soccer_type = 'default' ";
        $sql .= " order by l.competition_id ";
        $result = $db->query ( $sql );
	$row = $result->fetchAll ();
	return $row;
    }


    public function findAllLeaguesLimit($from,$to){
	$db = $this->getAdapter ();
        $sql = "select l.competition_id ,l.competition_name, l.type,l.format ,l.country_id,c.country_name";
        $sql .= " from league_competition l ,country c";
        $sql .= " where l.country_id = c.country_id and l.soccer_type = 'default' ";
        $sql .= " and l.competition_id > " .$from ;
        $sql .= " and l.competition_id <= " .$to ;
        $sql .= " order by l.competition_id ";
        $result = $db->query ( $sql );
	$row = $result->fetchAll ();
	return $row;

	}


	public function findLeaguesByContinent($region_ids) {
		$db = $this->getAdapter ();
		
		//$sql = " select c.country_name,c.country_id, l.competition_name,l.competition_id ";
		$sql = " select c.country_name,c.country_id,c.priority, l.competition_name,l.competition_id,l.regional ";
		$sql .= " from region r, country c , league_competition l ";
		$sql .= " where r.region_id = c.region_id  ";
		$sql .= " and r.region_id in (" . $region_ids . ") ";
		$sql .= " and c.country_id = l.country_id ";
		//$sql .= " order by l.regional desc, c.country_name,l.sort_priority ";
		$sql .= " order by l.regional desc,c.priority,l.competition_id ";
		//echo $sql . "<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
    //Using region_group_ids to query competitions by region, americas, europe, asian etc.
    public function findRegionalCompetitionsByRegion($regionGroupId) {
		$db = $this->getAdapter ();
        $sql = " select l.competition_name,l.competition_id,l.soccer_type,r.region_id, r.region_group_id ";
        $sql .= " from region r, country c , league_competition l ";
        $sql .= " where r.region_id = c.region_id ";
        $sql .= " and r.region_group_id =" . $regionGroupId;
        $sql .= " and l.soccer_type = 'default' ";
        $sql .= " and c.country_id = l.country_id and l.format = 'International cup' ";
        $sql .= " order by l.competition_id asc, c.country_name ";
        //echo $sql . "<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
    }

    //find regional competitions per region and type = club or international
	 public function findCompetitionsRegionalByRegion($regionGroupId,$teamtype = null) {
		$db = $this->getAdapter ();
        $sql = " select l.competition_name,l.competition_id,l.soccer_type,l.type,r.region_id, r.region_group_id ";
        $sql .= " from region r, country c , league_competition l ";
        $sql .= " where r.region_id = c.region_id ";
        $sql .= " and r.region_group_id =" . $regionGroupId;
        $sql .= " and l.soccer_type = 'default' ";
        $sql .= " and c.country_id = l.country_id ";
		if(!is_null($teamtype)){
			$sql .= " and l.type = '" .$teamtype ."'" ;
		}
        $sql .= " and l.format = 'international cup' ";
        $sql .= " and l.active = 1 ";
		$sql .= " order by l.competition_id asc, c.country_name ";
        //echo $sql . "<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
    }
	
	
	
//deprecated
    public function findRegionalCompetitionsByContinent($region_ids, $regional_ids) {
		$db = $this->getAdapter ();
		$sql = " select  l.competition_name,l.competition_id,r.region_id  ";
		$sql .= " from region r, country c , league_competition l where r.region_id = c.region_id  ";
		$sql .= " and r.region_id in (" . $region_ids . ") and c.country_id = l.country_id and l.regional in (" . $regional_ids . ") ";
		$sql .= " and l.soccer_type = 'default' and l.active = 1";
        $sql .= " order by l.competition_id asc, c.country_name";
		//echo $sql . "<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}


	public function findRegionalCompetitionsByContinent2($regiongroupname){
		$db = $this->getAdapter ();
		$sql = " SELECT l.competition_name,l.competition_id,r.region_id  ";
		$sql .= " FROM region r, country c , league_competition l ,regiongroup rg ";
		$sql .= " WHERE r.region_id = c.region_id AND  ";
		$sql .= " r.region_group_id = rg.region_group_id AND rg.region_group_id =" .$regiongroupname;
		$sql .= " AND c.country_id = l.country_id AND l.regional IN (1,2) ORDER BY l.competition_id ASC, c.country_name ";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
		
	}
	

    public function findDomesticCompetitionsByCountryInRegion($regionGroupId) {
        $db = $this->getAdapter ();
        $sql = "  select c.country_name,c.country_id,count(*) as num_of_leagues ,r.region_id ";
        $sql .= " from region r, country c , league_competition l , regiongroup rg ";
        $sql .= " where r.region_id = c.region_id ";
        $sql .= " and r.region_group_id = rg.region_group_id AND rg.region_group_id = ". $regionGroupId ;
        $sql .= " and c.country_id = l.country_id ";
        $sql .= " and l.type = 'club' ";
        $sql .= " and l.active = 1 ";
        $sql .= " and c.country_id > 8 ";
        $sql .= " group by c.country_id ";
        $sql .= " order by c.priority ";
        //echo $sql . "<br>";
        $result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;


    }

	public function findDomesticCompetitionsByContinent($region_ids, $regional_ids) {
		$db = $this->getAdapter ();
		$sql = " select c.country_name,c.country_id,count(*) as num_of_leagues ,r.region_id";
		$sql .= " from region r, country c , league_competition l  ";
		$sql .= " where r.region_id = c.region_id and r.region_id in (" . $region_ids . ") and c.country_id = l.country_id and l.active = 1 and ";
		$sql .= " l.regional in (" . $regional_ids . ") and l.active = 1  "; // added to hide countries and league that we don't cover JV-05-21-11
		$sql .= " group by c.country_id ";
		$sql .= " order by c.priority ";
		//$sql .= " limit 10 "; 
		//echo $sql ."<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	
	
	public function findDomesticCompetitionsByCountry($country_id , $isyourleague = null , $byName = null) {
		$db = $this->getAdapter ();
		$sql = "  select l.competition_id,l.competition_gs_id,l.competition_name,l.sort_priority,c.country_id,c.country_name,rg.region_group_id  ";
		if(!is_null($isyourleague)){
			$sql .= "  , (select competition_id from userleague where user_id = 79 and competition_id = l.competition_id ) as isyourleague ";
		}	  
		$sql .= " from league_competition l , country c ,  region r, regiongroup rg ";
		$sql .= " where ";
		if(!is_null($byName)){
			$sql .= " lower(l.competition_name) like lower('%$byName%')";
		}else {
			$sql .= " c.country_id=" . $country_id;
		}	 
		$sql .= " and l.country_id = c.country_id ";
		$sql .= " and c.region_id = r.region_id and r.region_group_id = rg.region_group_id ";
		$sql .= " and l.active = 1 ";
		$sql .= " order by l.sort_priority ASC ";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	public function findDomesticCompetitionsByCountryLeagues($country_id) {
		$db = $this->getAdapter ();
		$sql = "  select l.competition_id,l.competition_gs_id,l.competition_name,c.country_id,c.country_name,rg.region_group_id ";
		$sql .= " from league_competition l , country c ,  region r, regiongroup rg ";
		$sql .= " where c.country_id=" . $country_id;
		$sql .= " and l.country_id = c.country_id ";
		$sql .= " and c.region_id = r.region_id and r.region_group_id = rg.region_group_id ";
        $sql .= " and l.type = 'club' and (l.format = 'Domestic cup' or l.format = 'Domestic league') ";
		$sql .= " order by l.competition_id ";
		//echo $sql ."<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

    public function findDomesticCompetitionsByCountryDomesticLeagues($country_id) {

		$db = $this->getAdapter ();
		$sql = "  select l.competition_id,l.competition_gs_id,l.competition_name,c.country_id,c.country_name,rg.region_group_id,s.season_id,s.title, ro.round_id,ro.round_title ";
		$sql .= " from league_competition l ,season s, round ro, country c , region r, regiongroup rg ";
		$sql .= " where c.country_id=" . $country_id;
		$sql .= " and l.competition_id = s.competition_id and l.country_id = c.country_id ";		
        $sql .= " and  s.season_id = ro.season_id ";
		$sql .= " and c.region_id = r.region_id and r.region_group_id = rg.region_group_id ";		
		$sql .= " and s.active = 1 and l.active = 1";
		$sql .= " and l.format = 'Domestic League' ";
		$sql .= " order by l.sort_priority ";
		//echo "<BR>".$sql ."<br>";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;

	}


	public function findCompetitionById($leagueid) {
		$db = $this->getAdapter ();
		
		$sql = " SELECT l.competition_id, ";  
		$sql .= " l.competition_gs_id, ";
		$sql .= " l.competition_name, ";
		$sql .= " l.type,l.format, "; 
		$sql .= " r.region_id, ";
		$sql .= " r.region_name, ";
		$sql .= " rg.region_group_id, "; 
		$sql .= " rg.region_group_name, ";
		$sql .= " l.country_id, ";
		$sql .= " c.country_name, ";
		$sql .= " c.country_code_iso2,";
		$sql .= " l.regional";
		$sql .= " FROM league_competition l ";
		$sql .= " INNER JOIN country c ON l.country_id = c.country_id "; 
		$sql .= " INNER JOIN region r ON c.region_id = r.region_id "; 
		$sql .= " INNER JOIN  regiongroup rg ON r.region_group_id = rg.region_group_id  ";
		$sql .= " WHERE competition_id =" . $leagueid;
        //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetch ();
		return $row;
	}
	
	
	public function findCompetitionByGoalserveId($league_gs_id) {
		$db = $this->getAdapter ();
		$sql = " SELECT * from league_competition WHERE competition_gs_id = ".$league_gs_id;	
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetch ();
		return $row;
	
	}
	
	public function findTopCompetitions() {
		$db = $this->getAdapter ();
		
		$sql = "select lc.competition_id, lc.competition_name, lc.regional, lc.competition_seoname ";
		$sql .= " from topcompetition tc ";
		$sql .= " inner join league_competition lc on tc.competition_id = lc.competition_id ";
		$sql .= " ORDER BY Rand() LIMIT 5";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
		public function findLeagueAutoComplete($search) {
		$db = $this->getAdapter ();
		
		$sql = " SELECT l.competition_id, ";  
		$sql .= " l.competition_name, ";
		$sql .= " l.type,l.format, "; 
		$sql .= " r.region_name, ";
		$sql .= " rg.region_group_name, ";
		$sql .= " c.country_name, ";
		$sql .= " c.country_id, ";
		$sql .= " c.country_code,";
		$sql .= " l.regional";
		$sql .= " FROM league_competition l ";
		$sql .= " INNER JOIN country c ON l.country_id = c.country_id "; 
		$sql .= " INNER JOIN region r ON c.region_id = r.region_id "; 
		$sql .= " INNER JOIN  regiongroup rg ON r.region_group_id = rg.region_group_id  ";
		$sql .= "where l.competition_name like '%$search%' ORDER BY l.competition_name LIMIT 10";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	public function findRegionGroupPerCountry($countryId) {
		$db = $this->getAdapter ();
		$sql = " select rg.region_group_seoname";
    	$sql .= " from region r , country c ,regiongroup rg ";
		$sql .= " where c.region_id = r.region_id ";
		$sql .= " and rg.region_group_id = r.region_group_id ";
		$sql .= "and c.country_id =" . $countryId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ( 0 );
		return $row;

	}


//   public function findRegionGroupPerCountry($countryId) {
//		$db = $this->getAdapter ();
//		$sql = " select rg.region_group_seoname, rg.region_group_name";
//    	$sql .= " from region r , country c ,regiongroup rg ";
//		$sql .= " where c.region_id = r.region_id ";
//		$sql .= " and rg.region_group_id = r.region_group_id ";
//		$sql .= "and c.country_id =" . $countryId;
//		echo $sql;
//		$result = $db->query ( $sql );
//		$row = $result->fetchAll ();
//		return $row;
//
//	}
//
	public function findFeaturedCompetitions($count) {
		$db = $this->getAdapter ();
		$sql = " select l.competition_id , l.competition_name ,l.regional, r.region_id , r.region_name,rg.region_group_name ,l.country_id,c.country_name ";
		$sql .= "from featuredleagues fl, league_competition l , country c , region r,regiongroup rg ";
		$sql .= "where fl.competition_id = l.competition_id and l.country_id = c.country_id and c.region_id = r.region_id and r.region_group_id =rg.region_group_id ";
		//$sql .= " order by rand() limit " . $count;
                $sql .= " order by sort_order limit " . $count; //added for the worldcup
		//echo $sql;
    	$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	
	public function findPopularCompetitions($count,$userid) {
		$db = $this->getAdapter ();
		$sql = " SELECT ul.competition_id, ";
		$sql .= " lc.competition_name, ";
		$sql .= " lc.regional, ";
		$sql .= " lc.country_id, ";
		$sql .= " r.region_id, ";
		$sql .= " r.region_name, ";
		$sql .= " rg.region_group_name, ";
		$sql .= " c.country_name ";
		$sql .= " FROM userleague ul ";
		$sql .= " INNER JOIN league_competition lc ON lc.competition_id = ul.competition_id ";
		$sql .= " INNER JOIN country c ON c.country_id = lc.country_id ";
		$sql .= " INNER JOIN region r ON c.region_id = r.region_id ";
		$sql .= " INNER JOIN regiongroup rg ON r.region_group_id =rg.region_group_id ";
		if(!is_null($userid)){
			$sql .= " WHERE NOT EXISTS (SELECT 1 FROM userleague ule WHERE ule.competition_id = ul.competition_id and ule.user_id =". $userid . ") ";
		}
		$sql .= " GROUP By ul.competition_id ";
		$sql .= " ORDER BY rand() ";
		if(!is_null($count)){
			$sql .= "  limit 0, ".$count;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function updateCompetition ( $competitionid , $data ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "competition_id = ?", $competitionid ) ;
		return $this->update ( $data, $where ) ;
	}
	


   public function getLeaguesForSearchResult ($leagueid) {
        $db = $this->getAdapter ();
        $sql = "select l.competition_id ,l.competition_name, l.country_id,c.country_name,l.type,l.format, r.region_id ,rg.region_group_name ";
        $sql .= " from league_competition l , country c , region r, regiongroup rg ";
        $sql .= " where l.country_id = c.country_id ";
        $sql .= " and r.region_group_id = rg.region_group_id";
        $sql .= " and c.region_id = r.region_id ";
        $sql .= " and competition_id = " . $leagueid ;
        $result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;

   }
   
	public function findTopCompetitionsByCountry ($countryid) {
        $db = $this->getAdapter ();
        $sql = "select competition_id ";
        $sql .= " from league_competition ";
        $sql .= " where country_id =" . $countryid;
        $sql .= " and topcompetition = 1";
        $result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;

   }
  
   public function getTopScorersPerSeason($seasonId,$teamId = null,$limit = null,$roundId) {
   		$db = $this->getAdapter ();
   		$sql = " SELECT allgoals.player_id, ";
   		$sql .= " SUM(allgoals.goals) as total_goals, ";
   		$sql .= " alllineups.lineups as lineups, ";
   		$sql .= " p.player_common_name, ";
   		$sql .= " p.player_name_short, ";
   		$sql .= " t.team_id,t.team_name,t.team_seoname, ";
   		$sql .= " t.country_id, ";
   		$sql .= " p.player_nationality, ";
   		$sql .= " c.country_name ";
   		$sql .= " FROM ( " ;
   		$sql .= " SELECT me.player_id,COUNT(me.player_id) AS goals,me.team_id ";
   		$sql .= " FROM matchevent me INNER JOIN matchh m ON m.match_id = me.match_id ";
   		$sql .= " AND m.season_id = ". $seasonId;
   		if(!is_null($roundId)){
			$sql .= " AND m.round_id =". $roundId;
		}
   		$sql .= " AND me.event_type_id = 'PG' ";
   		$sql .= " GROUP BY me.player_id";
   		$sql .= " UNION";
   		$sql .= " SELECT me.player_id,COUNT(me.player_id) AS goals,me.team_id ";
   		$sql .= " FROM matchevent me INNER JOIN matchh m ON m.match_id = me.match_id ";
   		$sql .= " AND m.season_id = ".$seasonId;
      	if(!is_null($roundId)){
			$sql .= " AND m.round_id =". $roundId;
		}
   		$sql .= " AND me.event_type_id = 'G' ";
   		$sql .= " GROUP BY me.player_id";
   		$sql .= " ) as allgoals ";
   		//$sql .= " INNER JOIN ( ";  // was modified to work even when lineups are not available
   		$sql .= " LEFT OUTER JOIN ( ";
		$sql .= "	SELECT ";
		$sql .= "		me.player_id, ";
		$sql .= "		COUNT(me.player_id)AS lineups, ";
		$sql .= "		me.team_id ";
		$sql .= "	FROM ";
		$sql .= "		matchevent me ";
		$sql .= "	INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= "	AND m.season_id = ".$seasonId; 
      	if(!is_null($roundId)){
			$sql .= " AND m.round_id =". $roundId;
		}
		$sql .= "	AND me.event_type_id = 'L' "; 
		$sql .= "	GROUP BY me.player_id ";
		$sql .= " ) as alllineups ON alllineups.player_id = allgoals.player_id ";
   		$sql .= " INNER JOIN player p ON allgoals.player_id = p.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = allgoals.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = p.player_nationality ";
		if(!is_null($teamId)){
			$sql .= " AND t.team_id =". $teamId;
		}
   		$sql .= " GROUP BY allgoals.player_id ";
   		$sql .= " ORDER BY total_goals DESC";
   		if(!is_null($limit)){
			$sql .= " limit " . $limit;
		}
   		//echo $sql;
   		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
   		
   }
   
      public function getLineupsPerSeason($seasonId,$teamId = null) {
   		$db = $this->getAdapter ();
   		$sql = " SELECT me.player_id, "; 
   		$sql .= " COUNT(me.player_id) AS lineups, ";   
   		$sql .= " COUNT(me.player_id)*90 as minutes, ";  
   		$sql .= " p.player_common_name, "; 
   		$sql .= " p.player_name_short, "; 
   		$sql .= " t.team_id, "; 
   		$sql .= " t.team_name, "; 
   		$sql .= " t.country_id, ";
   		$sql .= " p.player_nationality, ";
   		$sql .= " c.country_name ";
		$sql .= " FROM matchevent me ";  
		$sql .= " INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= " INNER JOIN player p ON p.player_id = me.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = me.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = p.player_nationality "; 
		$sql .= " WHERE m.season_id = " .$seasonId ;
		$sql .= " AND me.event_type_id = 'L' ";
      	if(!is_null($teamId)){
			$sql .= " AND t.team_id =". $teamId;
		} 
		$sql .= " GROUP BY me.player_id "; 
		$sql .= " ORDER BY lineups DESC "; 
        //echo $sql;
   		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
   }
   
    public function getDisciplinaryPerSeason($seasonId,$teamId = null) {
    	$db = $this->getAdapter ();
   		$sql = " SELECT yt.player_id, ";
		$sql .= " p.player_common_name, ";
		$sql .= " p.player_name_short, ";
		$sql .= " t.team_id, ";
	    $sql .= " t.team_name, ";
	    $sql .= " t.country_id, ";
   		$sql .= " p.player_nationality, ";
   		$sql .= " c.country_name, ";
		$sql .= " yellowcards, ";
		$sql .= " IFNULL(redcards,0) as redcards, ";
		$sql .= " yellowcards + IFNULL(redcards,0) as totalcards ";
		$sql .= " FROM ";
		$sql .= "	(SELECT ";
		$sql .= "		me.player_id, ";
		$sql .= "		COUNT(me.player_id)AS yellowcards, ";
		$sql .= "		me.team_id ";
		$sql .= "	 	FROM ";
		$sql .= "		matchevent me ";
		$sql .= "	    INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= "		WHERE m.season_id = ".$seasonId ;
		$sql .= "		AND me.event_type_id = 'YC' ";
		$sql .= "		AND me.team_id = ". $teamId;
		$sql .= "		GROUP BY ";
		$sql .= "		me.player_id) as yt ";
		$sql .= " LEFT OUTER JOIN ";
		$sql .= "		(SELECT ";
		$sql .= "			me.player_id, ";
		$sql .= "			COUNT(me.player_id)AS redcards, ";
		$sql .= "			me.team_id ";
		$sql .= "			FROM ";
		$sql .= "			matchevent me ";
		$sql .= "		INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= "		WHERE m.season_id = ".$seasonId ;
		$sql .= "		AND me.event_type_id = 'RC' ";
		$sql .= "		AND me.team_id = ". $teamId;
		$sql .= "		GROUP BY ";
		$sql .= "		me.player_id) as rt ";
		$sql .= " ON yt.player_id= rt.player_id ";
		$sql .= " INNER JOIN player p ON p.player_id = yt.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = yt.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = p.player_nationality "; 
		$sql .= " ORDER BY totalcards DESC ";
   		//echo $sql;
   		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
    }
   
   
   
      public function getYellowCardsPerSeason($seasonId,$teamId = null,$limit = null,$roundId) {
   		$db = $this->getAdapter ();
   		$sql = " SELECT me.player_id,COUNT(me.player_id) AS yellowcards,p.player_common_name,p.player_name_short,t.team_id,t.team_name,t.country_id ";
		$sql .= " FROM matchevent me ";  
		$sql .= " INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= " INNER JOIN player p ON p.player_id = me.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = me.team_id "; 
		$sql .= " WHERE m.season_id = " .$seasonId ;
      	if(!is_null($roundId)){
			$sql .= " AND m.round_id =". $roundId;
		}
		$sql .= " AND me.event_type_id = 'YC' ";
      	if(!is_null($teamId)){
			$sql .= " AND t.team_id =". $teamId;
		} 
		$sql .= " GROUP BY me.player_id "; 
		$sql .= " ORDER BY yellowcards DESC "; 
      	if(!is_null($limit)){
			$sql .= " limit " . $limit;
		}
        //echo $sql;
   		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
   }
   

      public function getRedCardsPerSeason($seasonId,$teamId = null,$limit = null,$roundId) {
   		$db = $this->getAdapter ();
		$sql = " SELECT me.player_id,COUNT(me.player_id) AS redcards,p.player_common_name,p.player_name_short,t.team_id,t.team_name,t.country_id ";
		$sql .= " FROM matchevent me "; 
		$sql .= " INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= " INNER JOIN player p ON p.player_id = me.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = me.team_id "; 
		$sql .= " WHERE m.season_id = " .$seasonId ;
      	if(!is_null($roundId)){
			$sql .= " AND m.round_id =". $roundId;
		}
		$sql .= "  AND me.event_type_id = 'RC' "; 
		if(!is_null($teamId)){
			$sql .= " AND t.team_id =". $teamId;
		} 
		$sql .= " GROUP BY me.player_id "; 
		$sql .= " ORDER BY redcards DESC ";
        if(!is_null($limit)){
			$sql .= " limit " . $limit;
		}
        //echo $sql;
   		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
   		
   }

   


}
?>