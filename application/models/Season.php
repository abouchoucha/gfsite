<?php
class Season extends Zend_Db_Table_Abstract {
	protected $_primary = "season_id";
	protected $_name = 'season';
	
	public function updateSeason($competitionid, $seasonid, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "competition_id = ?", $competitionid ) . $db->quoteInto ( "and season_id = ?", $seasonid );
		return $this->update ( $data, $where );
	}


 	public function findLeagueBySeason($seasonId) {
                $db = $this->getAdapter ();
                $sql = " SELECT ";
                $sql .= " lc.competition_id, ";
                $sql .= " lc.competition_name, ";
                $sql .= " lc.format, ";
                $sql .= " s.season_id, ";
                $sql .= " s.title as season_title";
                $sql .= " FROM season s ";
                $sql .= " INNER JOIN league_competition lc ON s.competition_id = lc.competition_id ";
                $sql .= " WHERE s.season_id = " . $seasonId;
                $result = $db->query ( $sql );
                $row = $result->fetch();
                return $row;

        }
        
	public function getLeagueSeasons($competitionId) {
		$db = $this->getAdapter ();
		$sql = " select season_id, title ";
		$sql .= " from season";
		$sql .= " where competition_id =" . $competitionId;
		$sql .= " order by start_date desc ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getLeagueSeasonsActive($competitionId) {
		$db = $this->getAdapter ();
		$sql = " select season_id, title ";
		$sql .= " from season ";
		$sql .= " where competition_id =" . $competitionId;
		$sql .= " and active = 1 " ;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	public function selectActiveSeasons ($limit = null) {
		$db = $this->getAdapter ();
		$sql = " select season_id from season where active = '1' order by season_id ";
		if($limit != null){
			$sql .= " limit ".$limit; 
		}
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	public function selectActiveSeasonsTables ($limit = null) {
		$db = $this->getAdapter ();
		$sql = " select distinct season_id from matchh where match_date >  '2009-11-04' order by season_id ";
		if($limit != null){
			$sql .= " limit ".$limit; 
		}
        //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getActiveSeasonPerCompetition($competitioId) {
		$db = $this->getAdapter ();
		$sql = " SELECT competition_id,season_id FROM season WHERE active = 1; ";
	}

  public function getActiveCompetitionPerTeamSeason($teamId,$currentseason) {
    $db = $this->getAdapter (); 
    $sql = " SELECT DISTINCT m.competition_id ";
    $sql .= " FROM matchh m ";
    $sql .= " INNER JOIN season s ON s.season_id = m.season_id ";
    $sql .= " AND s.title ='" . $currentseason . "'";
    $sql .= " AND (m.team_a = " . $teamId ." OR m.team_b = " . $teamId . ")";
    //echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
  }

}
?>
