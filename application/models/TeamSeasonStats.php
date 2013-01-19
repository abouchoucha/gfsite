<?php
class TeamSeasonStats extends Zend_Db_Table_Abstract 
{
	protected $_primary = "stats_id";
  	protected $_name = "teamseasonstats" ;
  
	function init () {
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
	}
        
	public function selectTeamSeasonStatsByCompetition ( $competitionId ) {
		$db = $this->getAdapter () ;
        $sql = " SELECT distinct team_id, team_name from teamseasonstats WHERE competition_id = " . $competitionId ;
        //echo $sql;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
	}

    public function getAllGoalsScored($count,$compId ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select tss.team_id, tss.team_name,t.country_id,count(season_id) as appear, sum(gp) as gamesplayed,sum(gf) as totalgoals ";
		$sql .= " from teamseasonstats tss, team t ";
		$sql .= " WHERE tss.competition_id = ". $compId;
    $sql .= " AND tss.team_id = t.team_id ";
    if($seasonid != 'all'){
        $sql .= " and tss.season_id = " . $seasonid; 
    }
		$sql .= " group by tss.team_id having totalgoals > 0 ORDER by totalgoals DESC ";
    if(!is_null($count)){
        $sql .= " limit 0, ".$count;
    }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

    public function getAllCleanSheets($count,$compId ,$seasonid) {
		$db = $this->getAdapter ();
		$sql = " select tss.team_id, tss.team_name,t.country_id,count(season_id) as appear, sum(gp) as gamesplayed,sum(cs) as cleansheets ";
		$sql .= " from teamseasonstats tss, team t ";
		$sql .= " WHERE tss.competition_id = ". $compId;
        $sql .= " AND tss.team_id = t.team_id ";
    	if($seasonid != 'all'){
        	$sql .= " and tss.season_id = " . $seasonid; 
        }
		$sql .= " group by tss.team_id having cleansheets > 0 ORDER by cleansheets DESC ";
        if(!is_null($count)){
            $sql .= " limit 0, ".$count;
        }
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

    public function getAllSeasonsPlayed($count,$leagueid) {
        $db = $this->getAdapter ();
        $sql = "  select tss.team_id, tss.team_name ,t.country_id, count(tss.season_name) as appear ";
        $sql .= " from teamseasonstats tss ";
        $sql .= " inner join team t on t.team_id = tss.team_id ";
        $sql .= " where competition_id = " .  $leagueid;
        $sql .= " group by tss.team_id ";
        $sql .= " having appear > 0 ";
        $sql .= " order by appear desc ";
         if(!is_null($count)){
            $sql .= " limit 0, ".$count;
        }
                	//echo $sql;
        $result = $db->query ( $sql );
        $row = $result->fetchAll ();
        return $row;
    }
    
    public function getTeamLeaguePerformance($teamid) {
    	$db = $this->getAdapter ();
    	$sql = " SELECT ";
		$sql .= " ts.team_id,";
		$sql .= " t.team_name,";
		$sql .= " t.team_seoname,";
		$sql .= " ts.competition_id,";
		$sql .= " ts.competition_name,";
		$sql .= " ts.season_name,";
		$sql .= " ts.rank,";
		$sql .= " ts.gp,";
		$sql .= " ts.wn,";
		$sql .= " ts.dr,";
		$sql .= " ts.ls,";
		$sql .= " ts.gf,";
		$sql .= " ts.ga,";
		$sql .= " ts.gf-ts.ga as diff,";
		$sql .= " ts.hw,";
		$sql .= " ts.hd,";
		$sql .= " ts.hl,";
		$sql .= " IFNULL(ts.hgf,'-') as hgf,";
		$sql .= " IFNULL(ts.hga,'-') as hga,";
		$sql .= " ts.aw,";
		$sql .= " ts.ad,";
		$sql .= " ts.al,";
		$sql .= " IFNULL(ts.agf,'-') as agf, ";
		$sql .= " IFNULL(ts.aga,'-') as aga, ";
		$sql .= " ts.pts";
		$sql .= " FROM teamseasonstats ts "; 
		$sql .= " INNER JOIN league_competition lc ON lc.competition_id = ts.competition_id ";
		$sql .= " INNER JOIN team t on t.team_id = ts.team_id ";
		$sql .= " WHERE ts.team_id = " . $teamid;
		$sql .= " AND lc.format = 'Domestic league' "; 
		$sql .= " ORDER BY ts.season_name DESC ";
    	$result = $db->query ( $sql );
        $row = $result->fetchAll ();
        return $row;
    }
    

}