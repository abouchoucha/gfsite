<?php
class Round extends Zend_Db_Table_Abstract {
	protected $_primary = "round_id";
	protected $_name = 'round';


    public function findLeagueSeasonRound($roundId) {
        $db = $this->getAdapter ();
        $sql = " SELECT ";
        $sql .= " lc.competition_id, ";
        $sql .= " lc.competition_name, ";
        $sql .= " lc.format, ";
        $sql .= " s.season_id, ";
        $sql .= " s.title as season_title, ";
        $sql .= " r.round_id, ";
        $sql .= " r.round_title, ";
        $sql .= " r.type as round_type ";
        $sql .= " FROM round r ";
        $sql .= " INNER JOIN season s ON s.season_id  = r.season_id ";
        $sql .= " INNER JOIN league_competition lc ON s.competition_id = lc.competition_id ";
        $sql .= " WHERE r.round_id = " . $roundId;
        $result = $db->query ( $sql );
        $row = $result->fetch();
        return $row;
      }


	public function selectActiveRoundPerCompetition($leagueId) {
		$db = $this->getAdapter ();
		$sql = " SELECT s.season_id, r.round_id ";
		$sql .= " FROM 	league_competition AS lc "; 
		$sql .= " INNER JOIN ";
		$sql .= " season as s ON s.competition_id = lc.competition_id ";
		$sql .= " INNER JOIN ";
		$sql .= " round as r ON r.season_id = s.season_id "; 
		$sql .= " WHERE s.active = 1 and lc.competition_id = " . $leagueId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll();
		return $row;
	}
	
	public function selectRoundsWithGroups() {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "round_title = ?", 'Group Stage' );
		$order = "round_id";
		return $this->fetchAll ( $where, $order );
	}
	
	public function selectRoundsTypeTable() {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "type = ?", 'table' );
		$order = "round_id";
		return $this->fetchAll ( $where, $order );
	}
	
	public function selectRoundsByType($seasonId,$CupType){
		$db = $this->getAdapter ();
		$sql = " select * ";
		$sql .= " from round ";
		$sql .= " where season_id = " . $seasonId;
		$sql .= " and type = '".$CupType."'"; 
		$sql .= " order by round_id";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getSeasonRounds($seasonId) {
		$db = $this->getAdapter ();
		$sql = " select * ";
		$sql .= " from round ";
		$sql .= " where season_id = " . $seasonId;
		$sql .= " order by start_date desc";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getRoundsPerSeasonTable($seasonId) {
		$db = $this->getAdapter ();
		$sql = " select * ";
		$sql .= " from round ";
		$sql .= " where season_id = " . $seasonId;
		$sql .= " and type = 'table' ";
		$sql .= " order by start_date asc ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}
	
	public function getSeasonRoundsUpdate($seasonId) {
		$db = $this->getAdapter ();
		$sql = " select * ";
		$sql .= " from round";
		$sql .= " where season_id =" . $seasonId;
		//$sql .= " and end_date > now() ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	
	}

}

?>