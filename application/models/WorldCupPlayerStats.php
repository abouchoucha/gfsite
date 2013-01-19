<?php
class WorldCupPlayerStats extends Zend_Db_Table_Abstract {

    protected $_primary = "stats_id";
		protected $_name = 'worldcup_player_stats';

       
	public function getAllGoalsScored($count=null , $seasonid) {
		$db = $this->getAdapter ();
		$sql = " select wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id, sum(gp) as gamesplayed ,sum(gl) as totalgoals ,p.player_common_name ";
		$sql .= " from worldcup_player_stats wc";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
        if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }	
        $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having totalgoals > 0 order by totalgoals desc ";
        if(!is_null($count)){
            $sql .= " limit 0, ".$count;
         }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	} 
	
	public function getAllGamesPlayed($count=null , $seasonid) {
		$db = $this->getAdapter ();
		$sql = " select wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id, sum(gp) as gamesplayed, p.player_common_name ";
		$sql .= " from worldcup_player_stats wc";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
        if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }	
        $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having gamesplayed > 0 order by gamesplayed desc ";
        if(!is_null($count)){
            $sql .= " limit 0, ".$count;
         }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	} 
	
	public function getAllMinutesPlayed($count=null ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id,sum(gp) as gamesplayed ,sum(minp) as minutes,p.player_common_name ";
		$sql .= " from worldcup_player_stats wc";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
        if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }
        $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having minutes > 0 order by minutes desc ";
        if(!is_null($count)){
            $sql .= " limit 0, ".$count;
        }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	} 
	public function getAllAssists($count=null ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select  wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id, sum(gp) as gamesplayed ,sum(ast) as assists,p.player_common_name ";
		$sql .= " from worldcup_player_stats wc ";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
	    if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }
                $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having assists > 0 order by assists desc ";
                   if(!is_null($count)){
                    $sql .= "  limit 0, ".$count;
                }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}


        public function getAllYellowCards($count=null ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select  wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id, sum(gp) as gamesplayed ,sum(yc) as yellow,p.player_common_name ";
		$sql .= " from worldcup_player_stats wc ";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
	    if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }
                $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having yellow > 0 order by yellow desc ";
                   if(!is_null($count)){
                    $sql .= "  limit 0, ".$count;
                }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

        public function getAllRedCards($count=null ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select  wc.player_id ,wc.Last_Name , wc.First_Name ,wc.team_id,wc.team_name,t.country_id, sum(gp) as gamesplayed ,sum(rc) as red,p.player_common_name ";
		$sql .= " from worldcup_player_stats wc ";
		$sql .= " left outer join player p on wc.player_id = p.player_id ";
		$sql .= " inner join team t on t.team_id = wc.team_id ";
	    if($seasonid != 'all'){
        	$sql .= " and wc.season_id = " . $seasonid;
        }
                $sql .= " and wc.player_id is not null ";
		$sql .= " group by wc.player_id,wc.Last_Name having red > 0 order by red desc ";
                   if(!is_null($count)){
                    $sql .= "  limit 0, ".$count;
                }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}


        public function getAllWCPlayed($count=null) {
            $db = $this->getAdapter ();
            $sql = "select  wc.player_id,wc.First_Name,wc.Last_Name,wc.team_id,wc.team_name,t.country_id, count(wc.season_name) as appear ";
            $sql .= " from worldcup_player_stats wc  ";
            $sql .= " left outer join player p on wc.player_id = p.player_id ";
            $sql .= " inner join team t on t.team_id = wc.team_id ";
            $sql .= " where wc.player_id is not null ";
            $sql .= " group by wc.player_id ";
            $sql .= " having appear > 0 ";
            $sql .= " order by appear desc ";
             if(!is_null($count)){
                    $sql .= "  limit 0, ".$count;
                }
            //echo $sql;
            $result = $db->query ( $sql );
            $row = $result->fetchAll ();
            return $row;
	}

	public function getPlayersByTeam($competitionId,$teamid) {
		$db = $this->getAdapter ();
		$sql = " select distinct player_id ,Last_Name , First_Name from worldcup_player_stats ";
		$sql .= " where competition_id = ".$competitionId;
		$sql .= " and team_id = ".$teamid;
		$sql .= " and player_id is not null ";
		$sql .= " order by last_name, first_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getPlayerById($teamid,$playerid) {
		$db = $this->getAdapter ();
		$sql = " select distinct wp.player_id, p.player_country, wp.team_id, CONCAT_WS(' ', wp.first_name, wp.last_name) completename, ";
		$sql .= " wp.team_name, wp.competition_id, wp.competition_name, wp.season_name, ";
		$sql .= " sum(wp.gp) gp, sum(wp.sb) sb, sum(wp.minp) minp, sum(wp.gl) gl, sum(wp.hd) hd, sum(wp.fk) fk, ";
		$sql .= " sum(wp.gin) gin, sum(wp.gout) gout, sum(wp.pn) pn, sum(wp.pa) pa, ";
		$sql .= " sum(wp.ast) ast, sum(wp.dd) dd, sum(wp.sht) sht, sum(wp.gw) gw, sum(wp.fls) fls, sum(wp.yc) yc, sum(wp.rc) rc, ";
		$sql .= " c.country_name, p.player_position, pimg.imagefilename";
		$sql .= " from country c,  teamplayerstats wp ";
		$sql .= " left outer join player p on wp.player_id = p.player_id "; 
		$sql .= " left outer join playerimage pimg on p.player_id = pimg.player_id ";
		$sql .= " where wp.team_id = ".$teamid; 
		$sql .= " and wp.player_id = ".$playerid;
		$sql .= " and p.player_country = c.country_id ";
		$sql .= " group by player_id";
		$sql .= " order by season_name desc";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//var_dump($row);
		return $row;
	}
	
	public function getPlayerStatsByCompetitionId($playerid, $competitionid)
	{
		$db = $this->getAdapter ();
		$sql = " select * from teamplayerstats ";
		$sql .= " where player_id = ". $playerid;
		$sql .= " and competition_id = " . $competitionid;
		$sql .= " order by season_name desc";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		echo $sql;
		//var_dump($row);
		return $row;
		
	}


	
}	 
	
?>
