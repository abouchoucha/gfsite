<?php 
class Ranking extends Zend_Db_Table_Abstract {
    protected $_primary = "ranking_id";
    protected $_name = 'ranking' ;
    
    
function init () {
		Zend_Loader::loadClass ( 'Groupp' ) ;
		Zend_Loader::loadClass ( 'Team' ) ;
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
		Zend_Loader::loadClass ( 'Pagination' ) ;
	}
 
public function getRankingTableByRound ( $roundId,$gtype ) {
		$db = $this->getAdapter () ;
		if ($gtype == 0) {
			$sql = " select  r.resultstable_type,r.group_id,r.rank,r.rankt,r.team_id,t.team_name,r.matches_total,r.matches_won,r.matches_draw,r.matches_lost,r.goals_pro,r.goals_against,r.points,r.last_rank,r.last_playedmatch_date " ;
			$sql .= " from ranking r,team t " ;
			$sql .= " where r.round_id =" . $roundId;
			$sql .= " and  r.resultstable_type = 'total' " ;
			$sql .= " and t.team_id = r.team_id " ;
    		$sql .= " order by r.rank" ;
		} else {
			$sql = " select  r.resultstable_type,r.group_id,g.group_title,r.rank,r.rankt,r.team_id,t.team_name,r.matches_total,r.matches_won,r.matches_draw,r.matches_lost,r.goals_pro,r.goals_against,r.points,r.last_rank,r.last_playedmatch_date " ;
			$sql .= " from ranking r,groupp g,team t " ;
			$sql .= " where r.round_id =" . $roundId;
			$sql .= " and  r.resultstable_type = 'total' " ;
			$sql .= " and t.team_id = r.team_id " ;
    		$sql .= " and  g.group_id = r.group_id order by r.group_id,r.rank" ;
	
		}
		//echo $sql;
		echo '<br>Group Flag Model ='.$gtype;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		//Zend_Debug::dump($row);
		return $row ;


}
	
}

?>