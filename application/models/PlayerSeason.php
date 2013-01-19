<?php 
class PlayerSeason extends Zend_Db_Table_Abstract 
{
	protected $_primary = "team_id";
    protected $_name = "playerseason" ;
  
  function init () {
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
	}
	

    public function selectSeasonsPerNational ($teamId) {
        $db = $this->getAdapter();
        $sql = "  SELECT distinct ps.season_id,lc.competition_id,lc.competition_name,s.title,s.start_date,s.end_date " ;
        $sql .= " FROM playerseason ps ";
        $sql .= " INNER JOIN season s ON s.season_id = ps.season_id ";
        $sql .= " INNER JOIN league_competition lc ON s.competition_id = lc.competition_id ";
		$sql .= " WHERE ps.team_id =" . $teamId;
		$sql .= " ORDER by s.start_date DESC ";
		//echo $sql;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
    }
    
	public function selectPlayersNationalBySeason($teamId,$seasonId) {
		$db = $this->getAdapter();
		$sql = " SELECT p.player_id, ";
		$sql .= " p.player_firstname, "; 
		$sql .= " p.player_lastname, ";
		$sql .= " p.player_name_short, ";
		$sql .= " p.player_common_name, ";
		$sql .= " p.player_nickname, ";
		$sql .= " p.player_position, ";
		$sql .= " p.player_country, ";
		$sql .= " t2.team_id, ";
		$sql .= " t2.team_name, ";
		$sql .= " t2.team_seoname, ";
		$sql .= " c.country_id, ";
		$sql .= " c.country_name ";
		$sql .= " FROM player p "; 
		$sql .= " INNER join playerseason ps on p.player_id = ps.player_id "; 
		$sql .= " INNER join team t on ps.team_id = t.team_id ";
		$sql .= " INNER join playercategory pc on p.player_position = pc.player_category_name ";  
		$sql .= " LEFT join teamplayer tp on tp.player_id = ps.player_id ";
		$sql .= " INNER join team t2 on t2.team_id = tp.team_id ";
		$sql .= " INNER join country c on t2.country_id = c.country_id ";
		if(!is_null($teamId)){
			$sql .= " AND ps.team_id = " . $teamId;
		}
		$sql .= " and ps.season_id = " . $seasonId;
		$sql .= " AND t2.team_type = 'club' ";
		$sql .= " AND tp.actual_team = 1";
		$sql .= " ORDER BY pc.player_category_id ";
		//echo $sql;
    $result = $db->query ( $sql ) ;
    $row = $result->fetchAll () ;
    return $row ;
	}
	
	//Return all players for all teams for the respective season
	public function getPlayerTeamBySeason($seasonId,$teamId =null) {
	  $db = $this->getAdapter();
	  $sql = " SELECT p.player_id, p.player_name_short,p.player_position,t.team_id,t.team_name,s.title as season_title,lc.competition_id,competition_gs_id,lc.competition_name ";
    $sql .= " FROM playerseason ps "; 
    $sql .= " INNER JOIN player p ON p.player_id = ps.player_id ";
    $sql .= " INNER JOIN team t ON t.team_id = ps.team_id ";
    $sql .= " INNER JOIN season s ON s.season_id = ps.season_id ";
    $sql .= " INNER JOIN league_competition lc ON s.competition_id = lc.competition_id ";
    $sql .= " WHERE ps.season_id = " . $seasonId;
		if(!is_null($teamId)){
			$sql .= " AND ps.team_id = " . $teamId;
		}
    $sql .= " ORDER BY t.team_name LIMIT 10";
	  	//echo $sql;
    $result = $db->query ( $sql ) ;
    $row = $result->fetchAll () ;
    return $row ;  
	}
}  

