<?php

class PlayerCurrentStats extends Zend_Db_Table_Abstract{
	protected $_primary = "stats_id";
	protected $_name = 'playercurrentstats';
	
	public function getPlayerStatsBySeason($playerId , $seasonId){
	$db = $this->getAdapter();
        $sql = " select gp ,gl , yc ,rc ,si  sout, gstarted from playercurrentstats  WHERE player_id = " . $playerId . " and season_id = " .$seasonId ;
        $result = $db->query ( $sql ) ;
        $row = $result->fetch () ;
        return $row ;
		
	}
}

?>