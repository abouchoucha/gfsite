<?php
class Player extends Zend_Db_Table_Abstract {

	protected $_primary = "player_id" ;
	protected $_name = 'player' ;
	function init () {
		Zend_Loader::loadClass ( 'Team' ) ;
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
		Zend_Loader::loadClass ( 'Pagination' ) ;
	}

	// public function selectPlayerPageFanFaceBookAlerts($playerId) {
	// 	$db = $this->getAdapter ();
	// 	$sql = "select p.*,u.facebookid,u.facebookaccesstoken from player p inner join user u on p.user_id=u.user_id
	// 			where p.user_id is not null and p.facebook_idPage is not null and u.facebookaccesstoken is not null
	// 			and p.player_id=".$playerId;
	// 	//echo $sql;
	// 	$result = $db->query ( $sql );
	// 	$row = $result->fetchAll ();
	// 	//Zend_Debug::dump($row);
	// 	return $row;
	// }

	public function selectPlayerPageFanFaceBookAlerts($playerId) {
		$db = $this->getAdapter ();
		$sql = " select fb.fbpage_id, u.facebookid, u.facebookaccesstoken, lang.language_code,fb.fbpage_details,p.player_id,p.player_nickname,p.player_firstname,p.player_lastname, p.player_common_name,fb.language ";
		$sql .= " FROM fbpagealert fb ";
		$sql .= " INNER JOIN player p ON p.player_id = fb.entity_id ";
		$sql .= " INNER JOIN user u ON u.user_id = fb.user_id ";
		$sql .= " INNER JOIN language lang ON lang.language_id = fb.language_id ";
		$sql .= " AND fb.entity_id = ".$playerId;
		$sql .= " AND fb.entity_type = 'player' ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//Zend_Debug::dump($row);
		return $row;
	}

	public function lastInsertedId(){
		$db = $this->getAdapter () ;
		$sql = " select MAX(player_id) AS max_id FROM player " ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		$maxid = $row[0]['max_id'];
		return $maxid ;

	}

	public function findPlayers ( $search ) {
		$db = $this->getAdapter () ;
		$sql = " select player_id, player_name_short " ;
		$sql .= " from player " ;
		$sql .= " where player_lastname like '$search%' " ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function findPlayersAutoComplete ( $search ) {
		$db = $this->getAdapter () ;
		$sql = " select player_id, player_common_name " ;
		$sql .= " from player " ;
		//$sql .= " where player_lastname like '$search%' or player_firstname like '$search%'  ORDER BY player_common_name LIMIT 4" ;
		$sql .= " where CONCAT_WS(' ',player_firstname,player_lastname,player_common_name) LIKE '%$search%' ";
		$sql .= " ORDER BY player_common_name LIMIT 10 ";
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function findAllPlayers ($until)
	{
		$db = $this->getAdapter () ;
		$db->query("SET NAMES 'utf8'");
		$sql = " select distinct p.player_nickname,p.player_firstname, p.player_lastname, p.player_seoname, p.player_id, p.player_country,p.player_nationality,p.player_name_short,p.player_common_name,p.player_position" ;
		$sql .= " from player p "; // ORDER BY p.player_id ASC LIMIT 0," .$until ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	public function findAllPlayersForSearch($from,$to) {
    	$db = $this->getAdapter () ;
        $db->query("SET NAMES 'utf8'");
		$sql = " select p.player_id,p.player_name_short,p.player_dob,p.player_position,p.player_country,p.player_nationality,p.player_nickname,p.player_firstname,p.player_lastname,p.player_seoname ,p.player_common_name " ;
		$sql .= " from player p" ;
        $sql .= " where p.player_id > " .$from ;
        $sql .= " and p.player_id <= " .$to ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

    public function findPopularPlayersTestIndex($count)
	{
		$db = $this->getAdapter();
		$db->query("SET NAMES 'utf8'"); // Needed only to build the xml (not needed to populate inside a page)
		$sql = "select distinct p.player_nickname,p.player_firstname, p.player_lastname, p.player_seoname, p.player_id, p.player_country,p.player_nationality,c.country_name,p.player_name_short,p.player_common_name,p.player_position, tp.team_id,t.team_name,t.team_seoname";
  		//$sql .= " from popularplayers fp  ";
	    $sql .= " from userplayer up  ";
	    $sql .= "  inner join player p on up.player_id = p.player_id  ";
	    $sql .= "  inner join country c on p.player_country = c.country_id  ";
	    $sql .= "  inner join teamplayer tp on tp.player_id = p.player_id  ";
	    $sql .= "  inner join  team t on t.team_id = tp.team_id  ";
	    $sql .= "  and tp.actual_team = 1 ";
	    $sql .= " and t.team_type ='club' ";
	    if(!is_null($count)){
	    	$sql .= "  order by up.player_id limit 0, ".$count;
	    }
	  	//echo $sql;
	    $result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
			//Zend_Debug::dump($row);
		return $row ;

	}


	public function getPlayersForSearchResult($playerId,$userid=null) {
    	$db = $this->getAdapter () ;
        $sql = " SELECT ";
		$sql .= " p.player_id, ";
		$sql .= " p.player_firstname, ";
		$sql .= " p.player_lastname, ";
		$sql .= " p.player_name_short, ";
		$sql .= " p.player_dob,";
		$sql .= " p.player_position, ";
		$sql .= " p.player_country, ";
		$sql .= " p.player_nationality, ";
		$sql .= " p.player_nickname,";
		$sql .= " p.player_seoname, ";
		$sql .= " p.player_common_name, ";
		$sql .= " c.country_id, ";
		$sql .= " c.country_name,";
		$sql .= " tp.team_id, ";
		$sql .= " t.team_name, ";
		$sql .= " t.team_seoname ";
	  if (! is_null ( $userid )) {
			$sql .= " ,usp.user_id " ;
	    }
		$sql .= " FROM ";
		$sql .= " player p";
		$sql .= " INNER JOIN country c ON p.player_nationality = c.country_id ";
		$sql .= " INNER JOIN teamplayer tp ON tp.player_id = p.player_id ";
		$sql .= " INNER JOIN team t ON t.team_id = tp.team_id ";
	  if (! is_null ( $userid )) {
    		$sql .= " LEFT JOIN (SELECT user_id,player_id FROM userplayer WHERE user_id = ". $userid .") usp ON usp.player_id = p.player_id ";
    }

		$sql .= " WHERE tp.player_id =".$playerId ;
		$sql .= " AND tp.actual_team = 1  ";
		$sql .= " AND t.team_type = 'club' ";
    //echo $sql;
    $result = $db->query ( $sql ) ;
		$row = $result->fetchAll() ;
		return $row ;
	}

	public function findUniquePlayer ( $playerId ) {
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "player_id = ?", $playerId ) ;
		return $this->fetchRow ( $where ) ;
	}

	public function findUniquePlayerDetailed($playerId) {
    	$db = $this->getAdapter () ;
         $sql = " SELECT ";
		 $sql .= " p.player_id, ";
		 $sql .= " p.player_common_name, ";
		 $sql .= " p.player_firstname, ";
		 $sql .= " p.player_lastname, ";
		 $sql .= " p.player_name_short, ";
		 $sql .= " p.player_nickname, ";
		 $sql .= " p.player_dob, ";
		 $sql .= " p.player_position, ";
		 $sql .= " p.player_country, ";
		 $sql .= " p.player_nationality, ";
		 $sql .= " t.team_id, ";
		 $sql .= " t.team_name, ";
		 $sql .= " c.country_name ";
		$sql .= " FROM player p ";
		$sql .= " LEFT OUTER JOIN teamplayer tp ON tp.player_id = p.player_id ";
		$sql .= " LEFT OUTER JOIN team t ON t.team_id = tp.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = p.player_nationality ";
		$sql .= " WHERE p.player_id =".$playerId ;
		$sql .= " AND tp.player_id =".$playerId ;
		$sql .= " AND	tp.actual_team = 1 ";
		$sql .= " AND t.team_type = 'club'  ";
        //echo $sql;
        $result = $db->query ( $sql ) ;
		$row = $result->fetchAll() ;
		return $row ;
	}


	public function findPlayerProfileDetails ($playerId,$userid = null) {
		$db = $this->getAdapter () ;
		$sql = " SELECT ";
		$sql .= " p.player_id, ";
		$sql .= " p.player_common_name, ";
		$sql .= " p.player_firstname, ";
		$sql .= " p.player_lastname, ";
		$sql .= " p.player_name_short, ";
		$sql .= " p.player_nickname, ";
		$sql .= " p.player_dob, ";
		$sql .= " p.player_position, ";
		$sql .= " p.player_country, ";
		$sql .= " p.player_nationality, ";
		$sql .= " c.country_name ";
		if (! is_null ( $userid )) {
			$sql .= " ,(SELECT IF(COUNT(*)=1,'y','n') FROM userplayer WHERE user_id = $userid AND player_id = p.player_id) as ismyplayer";
		} else {
			$sql .= ", 'n' as ismyplayer";
		}
		$sql .= " FROM player p ";
		$sql .= " INNER JOIN country c ON c.country_id = p.player_nationality ";
		$sql .= " LEFT OUTER JOIN playerimage pimg ON pimg.player_id = p.player_id ";
		$sql .= " WHERE p.player_id = ".$playerId ;
		//echo $sql;
        $result = $db->query ( $sql ) ;
		$row = $result->fetchAll() ;
		return $row ;
}


	public function getPlayerProfileImage ($playerId)
	{
		$db = $this->getAdapter () ;
		$sql = " select player_id, imagefilename, imagelocation" ;
		$sql .= " from playerimage " ;
		$sql .= " where player_id =".$playerId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}


	public function getActualClubTeam ($playerId) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,tp.team_id,t.team_name,t.team_seoname ,t.team_type,tp.actual_team,tp.jersey_number, s.competition_id,s.season_id ";
		$sql .= " from player p, teamplayer tp, team t, teamseason ts, season s " ;
		$sql .= " where tp.player_id = p.player_id  ";
		$sql .= " and ts.team_id = t.team_id ";
		$sql .= " and ts.season_id = s.season_id ";
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and p.player_id =".$playerId ;
		$sql .= " and tp.actual_team = '1' " ;
		$sql .= " and t.team_type = 'club' " ;
		$sql .= " order by t.team_type desc ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}


    public function  getActualClubTeamSeason($playerId)  {
        $db = $this->getAdapter () ;
		$sql = " SELECT tp.team_id,tp.actual_team,t.team_name,t.team_gs_id,t.team_seoname, ";
    $sql .= " t.team_type,lc.competition_id,lc.competition_name,s.season_id,s.title,c.country_id,c.country_name ";
		$sql .= " FROM teamplayer tp ";
		$sql .= " INNER JOIN team t ON t.team_id = tp.team_id ";
		$sql .= " INNER JOIN country c ON c.country_id = t.country_id ";
		$sql .= " INNER JOIN teamseason ts ON ts.team_id = t.team_id ";
		$sql .= " INNER JOIN season s ON ts.season_id = s.season_id ";
		$sql .= " INNER JOIN league_competition lc ON lc.competition_id = s.competition_id ";
		$sql .= " WHERE tp.player_id = " . $playerId;
		$sql .= " AND t.team_type = 'club' AND actual_team != 0 AND s.active = 1 AND lc.format = 'domestic league' ";
		$sql .= " ORDER By s.start_date DESC ";
    //echo $sql;
    $result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		return $row ;
    }





	public function getActualNationalTeam ($playerId) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,tp.team_id,t.country_id,t.team_name,t.team_seoname,t.team_type,tp.actual_team,tp.jersey_number ";
		$sql .= " from player p, teamplayer tp, team t " ;
		$sql .= " where tp.player_id = p.player_id  ";
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and p.player_id =".$playerId ;
		$sql .= " and tp.actual_team = '1' " ;
		$sql .= " and t.team_type = 'national' " ;
		$sql .= " order by t.team_type desc ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function getPlayerTeamDetails ($playerId) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,p.player_name_short,tp.team_id,t.team_name,t.team_seoname,t.team_type,tp.jersey_number,tp.actual_team, s.title, tp.gp, tp.sb, tp.minp, tp.gl, tp.hd, tp.fk, tp.gin, tp.gout, tp.pn, tp.pa,";
		$sql .= " tp.ast, tp.dd,tp.sht, tp.gw,tp.fls, tp.yc, tp.rc, tp.mpg, tp.sh, tp.g_90,tp.a_90, tp.sh_90, tp.fls_90 " ;
		$sql .= " from player p, teamplayer tp, team t, season s " ;
		$sql .= " where tp.player_id = p.player_id ";
		$sql .= " and tp.team_id = t.team_id ";
		$sql .= " and tp.season_id = s.season_id ";
		$sql .= " and p.player_id =".$playerId ;
		$sql .= " order by t.team_type,s.title desc ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function getPlayerTeamStatsDetailsOld ($playerId) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,p.player_name_short,p.player_common_name,tp.team_id,t.team_name,t.team_seoname,t.team_type,s.title, tp.gp, tp.sb, tp.minp, tp.gl, tp.hd, tp.fk, tp.gin, tp.gout, tp.pn, tp.pa, tp.ast, tp.dd,tp.sht,"; 		  $sql .= "tp.gw,tp.fls, tp.yc, tp.rc, tp.mpg, tp.sh, tp.g_90,tp.a_90, tp.sh_90, tp.fls_90 ";
		$sql .= " from player p, teamplayerstats tp, team t, season s " ;
		$sql .= " where tp.player_id = p.player_id ";
		$sql .= " and tp.team_id = t.team_id ";
		$sql .= " and tp.season_id = s.season_id ";
		$sql .= " and p.player_id =".$playerId ;
		$sql .= " order by t.team_type,s.title desc ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

   public function getPlayerTeamStatsDetails ($playerId,$format) {
		$db = $this->getAdapter () ;
		$sql = "select p.player_name_short,p.player_common_name,tp.team_id,tp.team_name,t.team_seoname,tp.season_id,tp.season_name,lc.competition_id,lc.competition_name,tp.gp, tp.sb, tp.minp, tp.gl, tp.hd, tp.fk, tp.gin, tp.gout, tp.pn, tp.pa, tp.ast, tp.dd,tp.sht,tp.gw,tp.fls, tp.yc, tp.rc, tp.mpg, tp.sh, tp.g_90,tp.a_90, tp.sh_90, tp.fls_90";
		$sql .= " from teamplayerstats tp,team t,player p,league_competition lc" ;
		$sql .= " where tp.player_id = ".$playerId ;
      	$sql .= " and p.player_id = tp.player_id ";
      	$sql .= " and tp.team_id = t.team_id ";
      	$sql .= " and tp.competition_id = lc.competition_id ";
      if($format == 1){
        $sql .= " and lc.type = 'club' and lc.format = 'Domestic league' ";
        $sql .= " order by tp.season_name desc";
      } elseif ($format == 2) {
        $sql .= " and lc.type = 'club' and lc.format = 'International cup' ";
        $sql .= " order by tp.season_name desc";
      } else {
        $sql .= " and lc.type = 'international' and lc.format = 'International cup' ";
        $sql .= " order by tp.season_name desc, competition_id ";
      }
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

   public function getPlayerTeamTotalStatsDetails ($playerId,$format) {
		$db = $this->getAdapter () ;
		$sql = "select p.player_name_short,p.player_common_name,tp.team_id,tp.team_name,t.team_seoname,tp.season_id,tp.season_name,lc.competition_id,tp.competition_name,sum(tp.gp) as total_gp,sum(tp.minp) as total_minp,sum(tp.gl) as total_gl,sum(tp.yc) as total_yc,sum(tp.rc) as total_rc";
		$sql .= " from teamplayerstats tp,team t,player p,league_competition lc" ;
		$sql .= " where tp.player_id = ".$playerId ;
      	$sql .= " and p.player_id = tp.player_id ";
      	$sql .= " and tp.team_id = t.team_id ";
      	$sql .= " and tp.competition_id = lc.competition_id ";
      if($format == 1){
        $sql .= " and lc.type = 'club' and lc.format = 'Domestic league' ";
        $sql .= " order by tp.season_name desc";
      } elseif ($format == 2) {
        $sql .= " and lc.type = 'club' and lc.format = 'International cup' ";
        $sql .= " order by tp.season_name desc";
      } else {
        $sql .= " and lc.type = 'international' and lc.format = 'International cup' ";
        $sql .= " order by tp.season_name desc, competition_id ";
      }
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}


    public function getPlayerKeeperTeamStatsDetails ($playerId,$format) {
        $db = $this->getAdapter () ;
        $sql = " select p.player_name_short,p.player_common_name,tp.team_id,t.team_name,t.team_seoname,tp.season_id,tp.season_name,lc.competition_id,lc.competition_name, ";
        $sql .= " tp.games_played AS gp, tp.substitute as sb, tp.minutes as minp, tp.wins, tp.draws, tp.loses, tp.goals_allowed AS ga, ";
		$sql .= " tp.goals_against_average as gavg, tp.clean_sheets AS cs, tp.shots_allowed, tp.save_percentage, tp.save_per_90_min, tp.yellow_cards AS yc, tp.red_cards AS rc ";
        $sql .= " from teamgoaliestats tp,team t,player p,league_competition lc" ;
		$sql .= " where tp.player_id = ".$playerId ;
        $sql .= " and p.player_id = tp.player_id ";
        $sql .= " and tp.team_id = t.team_id ";
        $sql .= " and tp.competition_id = lc.competition_id ";
        if($format == 1){
            $sql .= " and lc.type = 'club' and lc.format = 'Domestic league' ";
            $sql .= " order by tp.season_name desc";
        } elseif ($format == 2) {
            $sql .= " and lc.type = 'club' and lc.format = 'International cup' ";
            $sql .= " order by tp.season_name desc";
        } else {
            $sql .= " and lc.type = 'international' and lc.format = 'International cup' ";
            $sql .= " order by tp.season_name desc, competition_id ";
        }
        $result = $db->query ( $sql ) ;
    //echo $sql ."<br>";
		$row = $result->fetchAll () ;
		return $row ;
    }

 public function getPlayerKeeperTeamTotalStatsDetails ($playerId,$format) {
        $db = $this->getAdapter () ;
        $sql = " SELECT p.player_name_short,p.player_common_name,tp.team_id,tp.team_name,t.team_seoname,tp.season_id,tp.season_name,lc.competition_id,lc.competition_name, " ;
        $sql .= " sum(tp.games_played) AS total_gp,sum(tp.goals_allowed) AS total_ga,sum(tp.yellow_cards) AS total_yc,sum(tp.red_cards) AS total_rc,sum(tp.clean_sheets) AS total_cs";
        $sql .= " from teamgoaliestats tp,team t,player p,league_competition lc" ;
		$sql .= " where tp.player_id = ".$playerId ;
        $sql .= " and p.player_id = tp.player_id ";
        $sql .= " and tp.team_id = t.team_id ";
        $sql .= " and tp.competition_id = lc.competition_id ";
        if($format == 1){
            $sql .= " and lc.type = 'club' and lc.format = 'Domestic league' ";
            $sql .= " order by tp.season_name desc";
        } elseif ($format == 2) {
            $sql .= " and lc.type = 'club' and lc.format = 'International cup' ";
            $sql .= " order by tp.season_name desc";
        } else {
            $sql .= " and lc.type = 'international' and lc.format = 'International cup' ";
            $sql .= " order by tp.season_name desc, competition_id ";
        }
        $result = $db->query ( $sql ) ;
        //echo $sql ."<br>";
		$row = $result->fetchAll () ;
		return $row ;
    }

	public function updatePlayer ( $playerid , $data ) {
		$db = $this->getAdapter () ;
        $db->query("SET NAMES 'utf8'");
		$where = $db->quoteInto ( "player_id = ?", $playerid ) ;
		return $this->update ( $data, $where ) ;
	}


	public function selectPlayersByAlphabet ( $letter ,$position = null , $dates = null , $countryId = null) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id, p.player_name_short,p.player_common_name,p.player_lastname, p.player_firstname, p.player_nickname, p.player_seoname, p.player_common_name " ;
		$sql .= " from player p, playerimage pimg " ;
		$sql .= " where p.player_lastname like '$letter%' " ;
        $sql .= " and p.player_id = pimg.player_id ";

		if($position!= null){
			$sql .= " and player_position = '$position' " ;
		}
		if($dates!= null){
			$sql .= " and player_creation  between '" . $dates[0] . "' and '" .  $dates[1] . "'" ;
		}
		if($countryId!= null){
			$sql .= " and player_country =" . $countryId  ;
		}
		$sql .= " order by player_lastname limit 0,20" ;


		$result = $db->query ( $sql ) ;
		//echo $sql ."<br>";
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function selectPlayers ( $letter = null , $from = null, $position = null ,$countryId = null ,$dates = null) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id, p.player_name_short,p.player_common_name,p.player_firstname, p.player_lastname,p.player_nickname,p.player_seoname,p.player_position,p.player_dob,t.team_id,t.team_name,t.team_seoname,p.player_country,p.player_nationality,c.country_name " ;
		$sql .= " from player p,teamplayer tp, team t,country c " ;
		$sql .= " where  p.player_id = tp.player_id  AND tp.team_id = t.team_id AND p.player_nationality = c.country_id AND t.team_type = 'club' AND tp.actual_team = 1" ;
		if($letter != null){
			$sql .= " AND  p.player_lastname like '$letter%' ";
		}
		if($position!= null){
			$sql .= " and player_position = '$position' " ;
		}
		if($countryId!= null){
			$sql .= " and player_country = " . $countryId ;
		}
		if($dates!= null){
			$sql .= " and player_creation  between '" . $dates[0] . "' and '" .  $dates[1] . "'" ;
		}
		$sql .= " order by player_lastname" ;
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20" ;
		}
		//echo $sql ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function countPlayersByLetter ( $letter, $position = null , $dates = null , $countryId = null) {
		$db = $this->getAdapter () ;
		$sql = " select count(*) as playercount from player where player_lastname like '$letter%' " ;
		if($position!= null){
			$sql .= " and player_position = '$position' " ;
		}
		if($dates!= null){
			$sql .= " and player_creation  between '" . $dates[0] . "' and '" .  $dates[1] . "'" ;
		}
		if($countryId!= null){
			$sql .= " and player_country =" . $countryId  ;
		}
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchColumn () ;
		return $row ;
	}

	public function countPlayersByLetter2 ( $letter , $position = null ,$countryId = null ,$dates = null ) {
		$db = $this->getAdapter () ;
		$sql = "  select count(*) as playercount " ;
		$sql .= " from player p,teamplayer tp, team t,country c " ;
		$sql .= " where  p.player_id = tp.player_id  AND tp.team_id = t.team_id AND t.country_id =c.country_id AND t.team_type = 'club' AND tp.actual_team = 1 ";
		if($letter != null){
			$sql .= " AND  p.player_lastname like '$letter%' " ;
		}
		if($position!= null){
			$sql .= " and player_position = '$position' " ;
		}
		if($countryId!= null){
			$sql .= " and player_country = " . $countryId ;
		}
		if($dates!= null){
			$sql .= " and player_creation  between '" . $dates[0] . "' and '" .  $dates[1] . "'" ;
		}

		$sql .= " order by player_lastname" ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchColumn () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function findPlayersByLetterName($letter){
		$db = $this->getAdapter () ;
		$sql = "  select p.player_id,p.player_firstname,p.player_lastname, p.player_nickname ,p.player_seoname,p.player_common_name" ;
		$sql .= " from player p " ;
		$sql .= " where p.player_lastname like '$letter%' " ;
		$sql .= " order by p.player_lastname " ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function findPlayersByTeam($letter){
		$db = $this->getAdapter () ;
		$sql = "  select p.player_id,p.player_common_name,p.player_firstname,p.player_lastname,p.player_firstname, p.player_nickname , p.player_position,p.player_seoname,p.player_dob, t.team_id , t.team_name_official, tp.actual_team " ;
		$sql .= " from player p , teamplayer tp, team t " ;
		$sql .= " where p.player_id = tp.player_id " ;
		$sql .= " and tp.team_id = t.team_id " ;
		$sql .= " and t.team_name_official like '%$letter%' " ;
		$sql .= " and tp.actual_team = 1 " ;
		$sql .= " order by p.player_lastname, t.team_name_official " ;
		//echo $sql;
 		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function findPlayersByCountry($letter){
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,p.player_firstname,p.player_lastname, p.player_common_name,p.player_nickname , p.player_position,p.player_seoname ,c.country_name " ;
		$sql .= " from player p , country c " ;
		$sql .= " where  p.player_nationality = c.country_id " ;
		$sql .= " and c.country_name like '$letter%' " ;
		$sql .= " order by p.player_lastname, c.country_name ";

		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function findPlayersByCountryId($countryId){
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,player_name_short,p.player_common_name,p.player_firstname,p.player_lastname, p.player_nickname , p.player_position,p.player_seoname ,c.country_name " ;
		$sql .= " from player p , country c " ;
		$sql .= " where  p.player_nationality = c.country_id " ;
		$sql .= " and c.country_id =" . $countryId  ;
		$sql .= " order by p.player_firstname ";
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function findTeammatesByPlayer($teamId ,$playerId ,$userid = null,$format = null) {
		$db = $this->getAdapter () ;
		$sql = " select p.player_id,p.player_name_short,p.player_common_name,p.player_dob,p.player_position,p.player_country,p.player_nationality,p.player_nickname,
				p.player_firstname,p.player_lastname,p.player_seoname ,p.player_common_name ,c.country_name,t.team_id,t.team_name,t.team_seoname ";
		if($format != null){
			$sql .= ",pcs.gp as gamesplayed ,pcs.gl as goalscored, pcs.yc as yellowcards, pcs.rc as redcards ";
		}
        if(!is_null($userid)){
			$sql .= " ,(SELECT IF(COUNT(*)=1,'y','n') FROM userplayer WHERE user_id = $userid AND player_id = p.player_id) ismyplayer" ;
		}else {
			$sql .= ", 'n' as ismyplayer";
		}
		$sql .= " from player p ";
        $sql .= " inner join teamplayer tp on tp.player_id = p.player_id ";
        $sql .= " inner join team t on tp.team_id = t.team_id ";
        $sql .= " inner join country c on p.player_nationality = c.country_id ";
        $sql .= " inner join playercategory pc on p.player_position = pc.player_category_name ";
        $sql .= " and tp.actual_team = 1 ";
        $sql .= " and t.team_type = 'club' ";
        $sql .= " and tp.team_id = " . $teamId ;
        $sql .= " and p.player_id <> " . $playerId ;
        if($format != null){
        	 $sql .= " left outer join playercurrentstats pcs on p.player_id = pcs.player_id ";
		 $sql .= " and pcs.competition_format = '$format' " ;
        }
        $sql .= " order by pc.player_category_id ";

		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}


	public function countTotalPlayersByName($criteria){

		$db = $this->getAdapter () ;
		$sql = "select count(*) ";
		$sql .= " from player p,teamplayer tp, team t , country c  ";
		$sql .= " where(lower(p.player_lastname) like lower('%$criteria%') ";
		$sql .= " or lower(p.player_firstname)   like lower('%$criteria%') ";
		$sql .= " or lower(p.player_name_short)  like lower('%$criteria%') ";
		$sql .= " or lower(p.player_nickname) 	  like lower('%$criteria%') ";
		$sql .= " or lower(CONCAT_WS(' ',p.player_firstname ,p.player_lastname)) like lower('%$criteria%') ";
		$sql .= " ) ";
		$sql .= " and p.player_id = tp.player_id ";
		$sql .= " and p.player_nationality = c.country_id " ;
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and tp.actual_team = 1  ";
		$sql .= " and t.team_type = 'club' ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchColumn () ;
		return $row ;

	}


	public function findPlayerByName($criteria , $from = null , $userId = null){

		$db = $this->getAdapter () ;
		$sql = "select p.player_id,p.player_common_name,p.player_firstname,p.player_lastname, p.player_nickname , p.player_position ,p.player_nickname,p.player_name_short,p.player_seoname ,t.team_name,t.team_seoname,c.country_name ";
		if(!is_null($userId)){
			$sql .= " ,(select player_id from userplayer where user_id = $userId and player_id = p.player_id ) as isyourplayer ";
		}else {
			$sql .= " ,NULL as isyourplayer ";
		}
		$sql .= " from player p,teamplayer tp, team t , country c  ";
		$sql .= " where(lower(p.player_lastname) like lower('%$criteria%') ";
		$sql .= " or lower(p.player_firstname)   like lower('%$criteria%') ";
		$sql .= " or lower(p.player_name_short)  like lower('%$criteria%') ";
		$sql .= " or lower(p.player_nickname) 	  like lower('%$criteria%') ";
		$sql .= " or lower(CONCAT_WS(' ',p.player_firstname ,p.player_lastname)) like lower('%$criteria%') ";
		$sql .= " ) ";
		$sql .= " and p.player_id = tp.player_id ";
		$sql .= " and p.player_nationality = c.country_id " ;
		$sql .= " and tp.team_id = t.team_id  ";
		$sql .= " and tp.actual_team = 1  ";
		$sql .= " and t.team_type = 'club' ";
		if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",20" ;
		}


//select p.player_id,p.player_firstname,p.player_lastname, p.player_nickname , p.player_position ,p.player_nickname,player_seoname
//from player p
//where SUBSTRING(p.player_seoname,1,4) = 'Piza'

		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;

		}


    public function getGoalsCurrentSeason ($playerId,$seasonId) {
		$db = $this->getAdapter () ;
        //Select All Goals per Active Competiton Per Player --m.season_id
		$sql = " select count(*) as goalsSeason " ;
        $sql .= " from matchevent me, matchh m ";
        $sql .= " where (event_type_id = 'G' or event_type_id = 'PG') " ;
        $sql .= " and me.match_id = m.match_id " ;
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function getYellowCardsCurrentSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as ycSeason ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= " where event_type_id = 'YC' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

       	public function getRedCardsCurrentSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as rcSeason ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= " where event_type_id = 'RC' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

    public function getGamesStartedSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as gamesStarted ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= "where event_type_id = 'L' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

    public function getGamesSubInSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as gamesSubIn ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= "where event_type_id = 'SI' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

    public function getGamesSubOutSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as gamesSubOut ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= "where event_type_id = 'SO' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

      public function getGamesTotalSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as gamesTotal ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= "where (event_type_id = 'SI' OR event_type_id = 'L' )";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	public function getAssistsTotalSeason ($playerId,$seasonId) {
        $db = $this->getAdapter () ;
        $sql = " select count(*) as assistsTotal ";
        $sql .= " from matchevent me, matchh m ";
        $sql .= "where event_type_id = 'A' ";
        $sql .= " and me.match_id = m.match_id ";
        $sql .= " and me.player_id = " . $playerId ;
        $sql .= " and m.season_id = " . $seasonId ;
        //echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetch () ;
		//Zend_Debug::dump($row);
		return $row ;
	}


	/*public function getGoalPerMatchCurrentSeason($playerId,$seasonId) {
		db = $this->getAdapter () ;
		SELECT m.match_date,me.player_id,ta.team_name,tb.team_name,event_type_id,event_minute from matchevent me
INNER JOIN matchh m ON me.match_id = m.match_id
INNER JOIN team ta ON ta.team_id = team_a
INNER JOIN team tb ON tb.team_id = team_b
WHERE me.player_id = 13192
AND me.event_type_id = 'G'
AND m.season_id = 6118
ORDER by match_date DESC
	} */


	public function findPlayerEvents($playerId){
		$db = $this->getAdapter () ;
		//
		$sql = "select m.match_date,m.match_time,m.team_a,m.team_b,m.fs_team_a,m.fs_team_b,me.*,p.player_nickname,p.player_seoname,p.player_name_short   ";
		$sql .= " from matchevent me , matchh m  ,player p  ";
		$sql .= " where m.match_id = me.match_id   ";
		$sql .= " and me.player_id = p.player_id ";
		$sql .= " and me.player_id = " . $playerId;
		//$sql .= " order by m.match_date desc   ";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;

	}

	public function findTopPlayers()
	{
		$db = $this->getAdapter () ;
		$sql = "select p.player_nickname,p.player_common_name,p.player_firstname, p.player_lastname, p.player_seoname, p.player_id, p.player_name_short,p.player_common_name from topplayer tp inner join player p on tp.player_id = p.player_id ORDER BY Rand() LIMIT 5";
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}


public function findUserPlayers()
	{
		$db = $this->getAdapter () ;
		$sql = "select p.player_nickname,p.player_common_name,p.player_firstname, p.player_lastname, p.player_seoname, p.player_id, p.player_name_short,p.player_common_name from userplayer up inner join player p on up.player_id = p.player_id ORDER BY Rand() LIMIT 5";
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}



public function findPlayersBySeason ($seasonId, $count,$userid = null)   {

    $db = $this->getAdapter () ;
    $sql = " SELECT ts.team_id,";
    $sql .= " t.team_name,t.team_seoname,";
    $sql .= " tp.player_id,";
    $sql .= " p.player_firstname,";
    $sql .= " p.player_lastname,";
    $sql .= " p.player_name_short,";
    $sql .= " p.player_common_name,";
    $sql .= " p.player_nationality as player_country,";
    $sql .= " c.country_name,";
    $sql .= " p.player_position, p.player_nickname,pimg.imagefilename";
    if (! is_null ( $userid )) {
			$sql .= " ,usp.user_id " ;
	}
    $sql .= " FROM teamseason AS ts";
    $sql .= " INNER JOIN teamplayer AS tp on ts.team_id = tp.team_id";
    $sql .= " INNER JOIN player AS p on tp.player_id = p.player_id";
    $sql .= " LEFT OUTER JOIN playerimage AS pimg on p.player_id = pimg.player_id";
    $sql .= " INNER JOIN country AS c on p.player_nationality = c.country_id";
    $sql .= " INNER JOIN team AS t on t.team_id = tp.team_id";
    if (! is_null ( $userid )) {
		$sql .= " LEFT JOIN (SELECT user_id,player_id FROM userplayer WHERE user_id = ". $userid .") usp ON usp.player_id = tp.player_id ";
	}
    $sql .= " WHERE tp.actual_team = 1";
    $sql .= " AND ts.season_id = " . $seasonId;
    $sql .= " ORDER BY rand() desc ";
    if(!is_null($count)){
       $sql .= " LIMIT " . $count;
    }
  //echo $sql;
  $result = $db->query ( $sql ) ;
    $row = $result->fetchAll () ;
    //Zend_Debug::dump($row);
    return $row ;
  }



public function findFeaturedPlayers($count = null ,$regiongroupid = null,$userid = null)
	{
        $db = $this->getAdapter () ;

		$sql = "select distinct p.player_nickname,
				p.player_id,
				p.player_common_name,
				p.player_firstname,
				p.player_lastname,
				p.player_seoname,
				p.player_country,
				p.player_nationality,
				c.country_name,
				p.player_name_short,
				p.player_position,
				tp.team_id,
				t.team_name,
				t.team_seoname,
				p.player_common_name ";
	    if (! is_null ( $userid )) {
			$sql .= " ,usp.user_id " ;
	    }
        $sql .= " from featuredplayers fp  ";
        $sql .= "  inner join player p on fp.player_id = p.player_id  ";
        $sql .= "  inner join country c on p.player_nationality = c.country_id  ";
        $sql .= "  inner join teamplayer tp on tp.player_id = p.player_id  ";
        $sql .= "  and tp.actual_team = 1 ";
        $sql .= "  inner join team t on t.team_id = tp.team_id  ";
    	if (! is_null ( $userid )) {
    		$sql .= " LEFT JOIN (SELECT user_id,player_id FROM userplayer WHERE user_id = ". $userid .") usp ON usp.player_id = fp.player_id ";
    	}
        $sql .= "  and t.team_type ='club' ";
    	if(!is_null($regiongroupid)){
        	$sql .= "  INNER JOIN region r ON  r.region_id = c.region_id";
 			$sql .= "  AND r.region_group_id =" . $regiongroupid;
        }
	     $sql .= " ORDER BY rand() desc ";
        if(!is_null($count)){
           $sql .= " LIMIT " . $count;
        }
        //echo $sql;
        $result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}

	// JOINS userplayer table to bring all players added by user on a joined table
	public function findFeaturedPlayersUser($userid)
	{
        $db = $this->getAdapter () ;
	 	$sql = " SELECT DISTINCT ";
				 	$sql .= " p.player_nickname, ";
					$sql .= " p.player_id, ";
					$sql .= " p.player_common_name, ";
					$sql .= " p.player_firstname, ";
					$sql .= " p.player_lastname, ";
					$sql .= " p.player_seoname, ";
					$sql .= " p.player_country,";
					$sql .= " p.player_nationality, ";
					$sql .= " c.country_name, ";
					$sql .= " p.player_name_short,";
					$sql .= " p.player_position, ";
					$sql .= " tp.team_id, ";
					$sql .= " t.team_name, ";
					$sql .= " t.team_seoname, ";
					$sql .= " p.player_common_name, ";
  					$sql .= " upl.user_id ";
					$sql .= " FROM ";
						$sql .= " featuredplayers fp ";
					$sql .= " INNER JOIN player p ON fp.player_id = p.player_id ";
					$sql .= " INNER JOIN country c ON p.player_nationality = c.country_id ";
					$sql .= " LEFT JOIN (SELECT user_id,player_id FROM userplayer WHERE user_id = ". $userid .") upl ON upl.player_id = fp.player_id ";
					$sql .= " LEFT JOIN teamplayer tp ON fp.player_id = tp.player_id ";
					$sql .= " INNER JOIN team t ON t.team_id = tp.team_id ";
					$sql .= " AND tp.actual_team = 1 ";
					$sql .= " AND t.team_type = 'club' ";
		//echo $sql;
	    $result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function findPopularPlayers($count,$userid=null)
	{
		$db = $this->getAdapter();
	    $sql = " SELECT up.player_id, ";
	    $sql .= " p.player_nickname, ";
	    $sql .= " p.player_common_name, ";
	    $sql .= " p.player_firstname, ";
	    $sql .= " p.player_lastname, ";
	    $sql .= " p.player_seoname, ";
	    $sql .= " p.player_country, ";
	    $sql .= " p.player_nationality, ";
	    $sql .= " p.player_name_short, ";
	    $sql .= " p.player_position, ";
	    $sql .= " p.player_common_name, ";
	    $sql .= " c.country_name, ";
	    $sql .= " tp.team_id, ";
	    $sql .= " t.team_name, ";
	    $sql .= " t.team_seoname ";
	    if (! is_null ( $userid )) {
			$sql .= " ,usp.user_id " ;
	    }
	    $sql .= " FROM userplayer up ";
	    $sql .= " INNER JOIN player p ON up.player_id = p.player_id ";
	    $sql .= " INNER JOIN country c ON p.player_nationality = c.country_id ";
	    $sql .= " INNER JOIN teamplayer tp ON tp.player_id = p.player_id ";
	    $sql .= " INNER JOIN team t ON t.team_id = tp.team_id ";
	    if (! is_null ( $userid )) {
    		$sql .= " LEFT JOIN (SELECT user_id,player_id FROM userplayer WHERE user_id = ". $userid .") usp ON usp.player_id = up.player_id ";
    	}
	    $sql .= " GROUP BY up.player_id ";
	    $sql .= " ORDER BY rand() ";
	    if(!is_null($count)){
	    	$sql .= "  limit 0, ".$count;
	    }
	  	//echo $sql;
	    $result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
			//Zend_Debug::dump($row);
		return $row ;
	}

	public function findPopularPlayersUser($count,$userid)
	{
		$db = $this->getAdapter();
	    $sql = " SELECT up.player_id, ";
	    $sql .= " p.player_nickname, ";
	    $sql .= " p.player_common_name, ";
	    $sql .= " p.player_firstname, ";
	    $sql .= " p.player_lastname, ";
	    $sql .= " p.player_seoname, ";
	    $sql .= " p.player_country, ";
	    $sql .= " p.player_nationality, ";
	    $sql .= " p.player_name_short, ";
	    $sql .= " p.player_position, ";
	    $sql .= " p.player_common_name, ";
	    $sql .= " c.country_name, ";
	    $sql .= " tp.team_id, ";
	    $sql .= " t.team_name, ";
	    $sql .= " t.team_seoname ";
	    $sql .= " FROM userplayer up ";
	    $sql .= " INNER JOIN player p ON up.player_id = p.player_id ";
	    $sql .= " INNER JOIN country c ON p.player_nationality = c.country_id ";
	    $sql .= " INNER JOIN teamplayer tp ON tp.player_id = p.player_id ";
	    $sql .= " INNER JOIN team t ON t.team_id = tp.team_id ";
	    $sql .= " WHERE NOT EXISTS (SELECT 1 FROM userplayer upl WHERE up.player_id = upl.player_id and upl.user_id = ". $userid .") ";
	    $sql .= " GROUP BY up.player_id ";
	    $sql .= " ORDER BY rand() ";
		if(!is_null($count)){
			$sql .= "  limit 0, ".$count;
		}
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;
	}


	public function selectThrophyByPlayer ( $playerId ) {
	   	$db = $this->getAdapter () ;
	   	$sql = "select  tp.years,lc.competition_name,tp.position" ;
	   	$sql .= " from throphy_player tp, league_competition lc " ;
	   	$sql .= " where lc.competition_id = tp.competition_id " ;
	   	$sql .= " and  tp.player_id =" . $playerId ;
	  	$result = $db->query ( $sql ) ;
		  $row = $result->fetchAll () ;
			return $row ;
	}

	public function getTopScorersByRegion ( $regionIds, $limitlist ) {
	    $db = $this->getAdapter () ;
	   	$sql = " select p.player_id,p.player_name_short,p.player_common_name,p.player_nickname, p.player_firstname, p.player_lastname,m.country_id,c.country_name,c.country_code,t.team_id, t.team_name,t.team_seoname, COUNT(*) as goalstotal " ;
	   	$sql .= " from matchevent me, matchh m,player p, teamplayer tp, team t, season s, country c  " ;
	   	$sql .= " where me.match_id= m.match_id " ;
	   	$sql .= " and (me.event_type_id = 'PG' or me.event_type_id = 'G') " ;
	   	$sql .= " and m.country_id IN (select c.country_id from country c, region r where c.region_id = r.region_id and r.region_group_id = " . $regionIds. " and regional= 0 )";
	   	$sql .= " and m.country_id = c.country_id " ;
	   	$sql .= " and p.player_id = me.player_id " ;
	    $sql .= " and tp.player_id = p.player_id " ;
	    $sql .= " and t.team_id = tp.team_id " ;
	    $sql .= " and tp.actual_team = 1 " ;
	    $sql .= " and t.team_type = 'club' " ;
	    $sql .= " and m.season_id = s.season_id ";
	    $sql .= " and s.active = 1 ";
	   	$sql .= " group by me.player_id " ;
	   	$sql .= " order by goalstotal desc " ;
	   	$sql .= " limit " . $limitlist ;
	   	//echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;


  }

	public function getTopScorersByCountry ( $countryId, $seasonId, $limitlist ) {
	  	$db = $this->getAdapter () ;
	   	$sql = " select p.player_id,p.player_name_short,p.player_common_name,p.player_nickname, p.player_firstname, p.player_lastname ,p.player_common_name , m.country_id,c.country_name,c.country_code,t.team_id, t.team_name,t.team_seoname, COUNT(*) as goalstotal " ;
	   	$sql .= " from matchevent me, matchh m,player p, teamplayer tp, team t, season s,country c  " ;
	   	$sql .= " where me.match_id= m.match_id " ;
	   	$sql .= " and (me.event_type_id = 'PG' or me.event_type_id = 'G') " ;
	   	$sql .= " and m.country_id =" . $countryId ;
	   	$sql .= " and m.country_id = c.country_id " ;
	   	$sql .= " and p.player_id = me.player_id " ;
	    $sql .= " and tp.player_id = p.player_id " ;
	    $sql .= " and t.team_id = tp.team_id " ;
	    $sql .= " and tp.actual_team = 1 " ;
	    $sql .= " and t.team_type = 'club' " ;
        $sql .= " and m.season_id =" . $seasonId ;
	    $sql .= " and m.season_id = s.season_id ";
	    $sql .= " and s.active = 1 ";
	   	$sql .= " group by me.player_id " ;
	   	$sql .= " order by goalstotal desc " ;
	   	$sql .= " limit " . $limitlist ;
        //echo $sql;
  		$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
	}

	public function getTopScorersByLeagueCurrent ( $seasonId,$compType,$limitlist ) {
	  	$db = $this->getAdapter () ;
	   	$sql = " select p.player_id,p.player_name_short,p.player_common_name, p.player_firstname, p.player_lastname, p.player_nickname,t.team_id, t.team_name,t.team_seoname, COUNT(*) as goalstotal " ;
	   	$sql .= " from matchevent me, matchh m,player p, teamplayer tp, team t  " ;
	   	$sql .= " where me.match_id= m.match_id " ;
	   	$sql .= " and (me.event_type_id = 'PG' or me.event_type_id = 'G') " ;
	   	$sql .= " and m.season_id = " . $seasonId ;
	   	$sql .= " and p.player_id = me.player_id " ;
	    $sql .= " and tp.player_id = p.player_id " ;
	    $sql .= " and t.team_id = tp.team_id " ;
	    $sql .= " and tp.actual_team = 1 " ;
        if($compType == 'club'){
			$sql .= " and t.team_type = 'club' " ;
		} else {
            $sql .= " and t.team_type = 'national' " ;
        }
	   	$sql .= " group by me.player_id " ;
	   	$sql .= " order by goalstotal desc " ;
	   	$sql .= " limit " . $limitlist ;
	   	//echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
	}

	public function getTopScorersByLeagueArchive ($season_Id , $limit = null) {
        $db = $this->getAdapter () ;
        $sql = " SELECT tp.player_id,p.player_name_short,p.player_common_name,p.player_nickname,p.player_firstname,p.player_lastname,tp.team_id,tp.team_name,t.team_seoname, tp.gl as goalstotal ";
        $sql .= " FROM teamplayerstats tp,player p ";
        $sql .= " WHERE tp.player_id = p.player_id ";
        $sql .= " AND tp.season_id = ". $season_Id ;
        $sql .= " AND tp.gl > 0 " ;
        $sql .= " ORDER by tp.gl desc ";
        if(!is_null($limit)){
			$sql .= " LIMIT " . $limit ;
		}
        //echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
    }

	public function getTopScorersByCountryCompetition ( $countryId, $compId, $limitlist  ) {
	  	$db = $this->getAdapter () ;
	   	$sql = " select p.player_name_short,p.player_common_name,p.player_firstname, p.player_lastname, t.team_name,t.team_seoname, COUNT(*) as goalstotal " ;
	   	$sql .= " from matchevent me, matchh m,player p, teamplayer tp, team t  " ;
	   	$sql .= " where me.match_id= m.match_id " ;
	   	$sql .= " and (me.event_type_id = 'PG' or me.event_type_id = 'G') " ;
	   	$sql .= " and m.country_id = " . $countryId ;
	   	$sql .= " and m.competition_id = " . $compId ;
	   	$sql .= " and p.player_id = me.player_id " ;
	    $sql .= " and tp.player_id = p.player_id " ;
	    $sql .= " and t.team_id = tp.team_id " ;
	    $sql .= " and tp.actual_team = 1 " ;
	    $sql .= " and t.team_type = 'club' " ;
	   	$sql .= " group by me.player_id " ;
	   	$sql .= " order by goalstotal desc " ;
	   	$sql .= " limit " . $limitlist ;
	  	$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}

	public function getPlayersCountryNull(){
		$db = $this->getAdapter () ;
		$sql = " SELECT player_id FROM player WHERE player_country is null ";
		//echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
	}

    public function getPlayersBySeasonNational($seasonId){
		$db = $this->getAdapter () ;
		$sql = " SELECT player_id FROM playerseason WHERE season_id =" . $seasonId;
		//echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
	}

	public function getPlayerMatchEventsTeamsSelect($playerId) {
	   	$db = $this->getAdapter () ;
		$sql = " SELECT DISTINCT ";
		$sql .= " IF (m.team_a = me.team_id, m.team_b,m.team_a) as team_other_id, ";
		$sql .= " IF (m.team_a = me.team_id, t2.team_name,t1.team_name) as team_other_name, ";
		$sql .= " IF (m.team_a = me.team_id, t2.team_seoname,t1.team_seoname) as team_other_seoname, ";
		$sql .= " IF (m.team_a = me.team_id, t2.team_type,t1.team_type) as team_other_type ";
		$sql .= " FROM matchevent me ";
		$sql .= " INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= " INNER JOIN season s ON m.season_id = s.season_id";
		$sql .= " INNER JOIN league_competition lc on m.competition_id = lc.competition_id ";
		$sql .= " INNER JOIN team t1 ON t1.team_id = m.team_a ";
		$sql .= " INNER JOIN team t2 ON t2.team_id = m.team_b";
		$sql .= " WHERE me.player_id =". $playerId;
		$sql .= " AND me.team_id is not null ";
		$sql .= " ORDER BY team_other_type,team_other_name";
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}


	public function getPlayerMatchEventsTeams($playerId,$teamId=null) {
		$db = $this->getAdapter () ;
		$sql = " SELECT lc.competition_name,lc.competition_id,s.title,me.match_id,m.match_date,m.team_a,m.team_b, ";
		$sql .= " IF (m.team_a = me.team_id, m.team_b,m.team_a) as team_other_id, ";
		$sql .= " IF (m.team_a = me.team_id, t2.team_name,t1.team_name) as team_other_name, ";
		$sql .= " IF (m.team_a = me.team_id, t2.team_seoname,t1.team_seoname) as team_other_seoname, ";
		$sql .= " IF(m.team_a = me.team_id,t1.team_name,t2.team_name)AS team_current_name, ";
		$sql .= " IF(m.team_a = me.team_id,m.fs_team_b,m.fs_team_a)AS ga, ";
		$sql .= " SUM(me.event_type_id = 'G') as gl, ";
	  $sql .= " SUM(me.event_type_id = 'L') as ln, ";
	  $sql .= " SUM(me.event_type_id = 'SI')AS si, ";
	  $sql .= " SUM(IF(me.event_type_id = 'SI', 90-(me.event_minute),0)) AS subin_minutes, ";
	  $sql .= " SUM(me.event_type_id = 'L')*90 + SUM(IF(me.event_type_id = 'SI',(93 -(me.event_minute)),0)) as full_minutes, ";
	  $sql .= " SUM(me.event_type_id = 'YC') as yc, ";
	  $sql .= " SUM(me.event_type_id = 'RC') as rc ";
		$sql .= " FROM matchevent me ";
		$sql .= " INNER JOIN matchh m ON m.match_id = me.match_id ";
		$sql .= " INNER JOIN season s ON m.season_id = s.season_id";
		$sql .= " INNER JOIN league_competition lc on m.competition_id = lc.competition_id ";
		$sql .= " INNER JOIN team t1 ON t1.team_id = m.team_a ";
		$sql .= " INNER JOIN team t2 ON t2.team_id = m.team_b";
		$sql .= " WHERE me.player_id =". $playerId;
		$sql .= " AND me.team_id is not null ";
		if(!is_null($teamId)){
		    $sql .= " AND (m.team_a =". $teamId ." OR m.team_b =". $teamId .")";
		}
		$sql .= " GROUP BY me.match_id";
		$sql .= " ORDER BY m.match_date DESC ";
		//$sql .= " LIMIT 25" ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}






	//get player match events (lineups, appear,subs in,goals, yellow and red per season, round,
	public function getPlayerMatchEventsTotals($playerId,$competitionId,$seasontitle) {
    $db = $this->getAdapter () ;
	  $sql = " SELECT ";
	  $sql .= " previous_table.league_id, ";
  	$sql .= " previous_table.league, ";
    $sql .= " SUM(ln) as total_lineups, ";
    $sql .= " SUM(si)AS total_subins,  ";
    $sql .= " SUM(ln + si) AS total_appear,   ";
    $sql .= " SUM(ln)* 90 AS total_minutes,  ";
    $sql .= " SUM(subin_minutes) as total_subin_minutes,";
    $sql .= " SUM(ln*90 + subin_minutes) AS total_full_minutes,";
    $sql .= " SUM(gl) as total_goals, ";
    $sql .= " COUNT(IF(ga=0,1,NULL))as total_clean_sheets, ";
    $sql .= " SUM(ga) as total_goals_allowed, ";
    $sql .= " SUM(yc) as total_yellow_cards, ";
    $sql .= " SUM(rc) as total_red_cards ";
    $sql .= " FROM ( ";
        $sql .= "    	SELECT ";
        $sql .= "     	lc.competition_name as league, ";
        $sql .= "     	lc.competition_id as league_id, ";
        $sql .= "    		s.title, ";
        $sql .= "    		me.match_id, ";
        $sql .= "    		m.match_date, ";
        $sql .= "    		m.team_a, ";
        $sql .= "    		m.team_b, ";
        $sql .= "     	IF(m.team_a = me.team_id, m.team_b, m.team_a )AS team_other_id, ";
        $sql .= "     	IF(m.team_a = me.team_id, t2.team_name, t1.team_name )AS team_other_name, ";
        $sql .= "     	IF(m.team_a = me.team_id,t2.team_seoname, t1.team_seoname )AS team_other_seoname,  ";
        $sql .= "     	IF(m.team_a = me.team_id,t1.team_name,t2.team_name)AS team_current_name,  ";
        $sql .= "     	IF(m.team_a = me.team_id,m.fs_team_b,m.fs_team_a )AS ga,  ";
        $sql .= "    		SUM(me.event_type_id = 'G')AS gl,  ";
        $sql .= "    		SUM(me.event_type_id = 'L')AS ln,  ";
        $sql .= "    		SUM(me.event_type_id = 'SI')AS si,  ";
        $sql .= " 			SUM(IF(me.event_type_id = 'SI', 93-(me.event_minute),0)) AS subin_minutes, ";
        $sql .= "    		SUM(me.event_type_id = 'YC')AS yc,  ";
        $sql .= "    		SUM(me.event_type_id = 'RC')AS rc  ";
        $sql .= "    FROM   ";
        $sql .= "    		matchevent me   ";
        $sql .= "    		INNER JOIN matchh m ON m.match_id = me.match_id   ";
        $sql .= "    		INNER JOIN season s ON m.season_id = s.season_id  ";
        $sql .= "    		INNER JOIN league_competition lc ON m.competition_id = lc.competition_id  ";
        $sql .= "    		INNER JOIN team t1 ON t1.team_id = m.team_a  ";
        $sql .= "    		INNER JOIN team t2 ON t2.team_id = m.team_b  ";
        $sql .= "    WHERE  ";
        $sql .= "    		me.player_id = " . $playerId;
        $sql .= "    		AND me.team_id IS NOT NULL   ";
        /* filter by season and competition_id */
        if(!is_null($seasontitle)){
        $sql .= " AND s.title ='" . $seasontitle . "'";
        }
        $sql .= "    		AND lc.competition_id = " . $competitionId;
        /* end of filter */
        $sql .= "    		GROUP BY   ";
        $sql .= "    			me.match_id   ";
        $sql .= "    		ORDER BY   ";
        $sql .= "    			m.match_date DESC  ";
        $sql .= " ) as previous_table  ";
	//echo $sql;
	$result = $db->query ( $sql ) ;
	$row = $result->fetchAll () ;
	return $row ;

	}

	public function getPlayersUpdateImages($from,$to){
		$db = $this->getAdapter () ;
		$sql = " SELECT DISTINCT tp.player_id, p.player_name_short FROM teamplayer tp INNER JOIN player p ON p.player_id = tp.player_id ";
		$sql .= " where tp.player_id > " .$from ;
    $sql .= " and tp.player_id <= " .$to ;
    $sql .= " ORDER by tp.player_id ASC ";
		echo $sql;
	  	$result = $db->query ( $sql ) ;
	  	$row = $result->fetchAll () ;
		return $row ;
	}




}
?>
