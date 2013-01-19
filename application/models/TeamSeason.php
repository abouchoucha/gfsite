<?php 
class TeamSeason extends Zend_Db_Table_Abstract 
{
	protected $_primary = "team_id";
  protected $_name = "teamseason" ;
  
  function init () {
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
	}
	
public function selectActiveCompetitionByTeam ( $teamId ) {
        $db = $this->getAdapter () ;
        $sql = " select ts.team_id,s.season_id,s.active,lc.competition_id, ";
        $sql .= " lc.competition_name,lc.competition_type,lc.format,lc.sort_priority " ;
        $sql .= " from teamseason ts, season s,league_competition lc " ;
        $sql .= " where ts.season_id = s.season_id " ;
        $sql .= " and s.competition_id = lc.competition_id " ;
        $sql .= " and s.active = 1 and ts.team_id = " . $teamId ;
        $sql .= " order by lc.sort_priority,s.season_id DESC " ;
        //echo $sql;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
}

    public function updateTeamSeason ( $teamId, $seasonId , $data ) {
	$db = $this->getAdapter();
        $where = $db->quoteInto("team_id = ?", $teamId)
                .$db->quoteInto("and season_id = ?", $seasonId);
        return $this->update($data, $where);
    }

    public function getSeasonNotInTeamSeason () {
        $db = $this->getAdapter();
        $sql = " select season_id  from season  WHERE season_id NOT IN (select distinct season_id from teamseason) " ;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
    }
    
    public function getActiveSeasonsByTeam($teamId) {
    
    	  $db = $this->getAdapter();
        $sql = " select ts.season_id,s.competition_id,lc.format ";
        $sql .= " from teamseason ts, season s, league_competition lc where ts.team_id = " . $teamId ;
        $sql .= " and ts.season_id = s.season_id  and s.competition_id = lc.competition_id and ts.active = 1 " ;
        //echo $sql;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;

    }
    
    public function getActiveSeasonByTeamLeague ($teamId) {
    	$db = $this->getAdapter();
        $sql = " select ts.season_id,s.competition_id,s.title as season_title,r.round_id,lc.competition_name,lc.format  " ;
        $sql .= " from teamseason ts, season s, league_competition lc, round r ";
        $sql .= " where ts.team_id = " . $teamId ;
        $sql .= " and ts.season_id = s.season_id and s.competition_id = lc.competition_id ";
        $sql .= " and r.season_id = s.season_id";
        $sql .= " and s.active = 1  and lc.format = 'Domestic league' " ;
        //echo $sql;
        $result = $db->query ( $sql ) ;
        $row = $result->fetch () ;
        return $row ;
    
    }
	
  
}  

