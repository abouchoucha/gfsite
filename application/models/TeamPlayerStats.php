<?php
class TeamPlayerStats extends Zend_Db_Table_Abstract 
{
	protected $_primary = "stats_id";
  	protected $_name = "teamplayerstats" ;
  
	function init () {
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
	}

	public function getPlayerById($teamid,$playerid) {
		$db = $this->getAdapter ();
		$sql = " select distinct p.player_id, p.player_country, wp.team_id, CONCAT_WS(' ', wp.first_name, wp.last_name) completename, ";
		$sql .= " wp.team_name, wp.competition_id, le.competition_name, le.country_id,wp.season_name, ";
		$sql .= " sum(wp.gp) gp, sum(wp.sb) sb, sum(wp.minp) minp, sum(wp.gl) gl, sum(wp.hd) hd, sum(wp.fk) fk, ";
		$sql .= " sum(wp.gin) gin, sum(wp.gout) gout, sum(wp.pn) pn, sum(wp.pa) pa, ";
		$sql .= " sum(wp.ast) ast, sum(wp.dd) dd, sum(wp.sht) sht, sum(wp.gw) gw, sum(wp.fls) fls, sum(wp.yc) yc, sum(wp.rc) rc, ";
		$sql .= " c.country_name, ";
		$sql .= " p.player_firstname, ";
		$sql .= " p.player_lastname, ";
		$sql .= " p.player_common_name, ";
		$sql .= " p.player_nickname, ";
		$sql .= " p.player_position ";
		$sql .= " FROM country c,  teamplayerstats wp ";
		$sql .= " INNER JOIN player p on wp.player_id = p.player_id "; 
		$sql .= " INNER JOIN league_competition le ON wp.competition_id = le.competition_id ";
		$sql .= " where p.player_id = ".$playerid;
		if(!is_null($teamid)){
			$sql .= " and wp.team_id = ".$teamid; 
		}
		$sql .= " and p.player_country = c.country_id ";
		$sql .= " group by player_id";
		$sql .= " order by season_name desc";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//var_dump($row);
		return $row;
	}
	
	public function getPlayerStatsBySeason($playerId , $seasonId){
	$db = $this->getAdapter();
        $sql = " select gp ,gl , yc ,rc ,si  sout, gstarted from  teamplayerstats  WHERE player_id = " . $playerId . " and season_id = " .$seasonId ;
        $result = $db->query ( $sql ) ;
        $row = $result->fetch () ;
        return $row ;
		
	}
	
	public function getPlayerStatsDomestic($playerid)
	{
		$db = $this->getAdapter ();
		$sql = " select * from teamplayerstats tps ";
		$sql .= " INNER JOIN league_competition lc ON lc.competition_id = tps.competition_id ";
		$sql .= " where tps.player_id = ". $playerid;
		$sql .= " and lc.format = 'Domestic league' ";
		$sql .= " order by season_name desc";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		//echo $sql;
		//var_dump($row);
		return $row;
		
	}
	

}