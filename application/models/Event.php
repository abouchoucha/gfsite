<?php
class Event extends Zend_Db_Table_Abstract {
    protected $_name = 'event';
    protected $_primary = "id";


    public function init()
    {
    //load the other adapter
      	$this->db2 = Zend_Registry::get('dbspocosy');
    }

	public function getMatchesScoreboard ($fechas , $live = null, $stageid = null , $matchid = null) {
        $sql = " SELECT ";
        $sql .= " ts.id as stage_id,es.status_type as game_status ,t.id as season_id, ts.id as round_id,e.id AS event_id, ";
        $sql .= " tt.id AS ttemplate_id, ";
        $sql .= " tt.name AS ttemplate_name, ";
        $sql .= " ts.tournamentFK, ";
        $sql .= " ts.countryFK AS country_e_id, ";
        $sql .= " cg.country_id,";
        $sql .= " c.name AS country_name,";
        $sql .= " glc.competition_id,";
        $sql .= " ts.id AS stage_id,";
        $sql .= " ts.name AS stage_name,";
        $sql .= " DATE_FORMAT(e.startdate, '%Y-%m-%d') AS startdate, ";
        $sql .= " DATE_FORMAT(e.startdate, '%H:%i') AS starttime,";
        $sql .= " MIN(IF( ep.number = 1, p.id, NULL)) AS home_team_id,";
        $sql .= " MIN(IF( ep.number = 1, p.name, NULL)) AS home_team,";
        $sql .= " IF(e.status_type = 'notstarted', '-',(MIN(IF(ep.number = 1, r.value, NULL)))) AS home_score,";
        $sql .= " IF(e.status_type = 'notstarted', '-',(MIN(IF(ep.number = 2, r.value, NULL)))) AS away_score,";
        $sql .= " MIN(IF(ep.number = 2, p.id, NULL)) AS away_team_id,";
        $sql .= " MIN(IF(ep.number = 2, p.name, NULL)) AS away_team,";
        $sql .= " es.name AS status_text ";
        $sql .= " FROM ";
        $sql .= " tournament_template AS tt LEFT JOIN ";
        $sql .= " gleague_competition AS glc ON glc.tournament_templateFK = tt.id INNER JOIN ";
        $sql .= " tournament AS t ON t.tournament_templateFK = tt.id INNER JOIN ";
        $sql .= " tournament_stage AS ts ON t.id = ts.tournamentFK INNER JOIN ";
        $sql .= " country AS c ON ts.countryFK = c.id INNER JOIN ";
        $sql .= " countryg AS cg ON c.id = cg.country_e_id INNER JOIN ";
        $sql .= " event AS e ON ts.id = e.tournament_stageFK INNER JOIN ";
        $sql .= " event_participants AS ep ON e.id = ep.eventFK LEFT JOIN ";
        $sql .= " status_desc AS es ON e.status_descFK = es.id LEFT JOIN ";
        $sql .= " participant AS p ON ep.participantFK = p.id LEFT JOIN ";
        $sql .= " result AS r ON ep.id = r.event_participantsFK AND r.result_code = 'runningscore' LEFT JOIN ";
        $sql .= " property AS prop ON e.id = prop.objectFK AND prop.object ='event' "; // AND prop.name = 'Live' (no need to filter by live or not by enetpulse
        //$sql .= " AND glc.topcompetition = 1 ";
        $sql .= " WHERE ";
        $sql .= " tt.sportFK = '1'  AND ";
        //$sql .= "e.startdate BETWEEN '2010-08-28 00:00:00' AND '2010-08-28 23:59:59' AND ";
        //echo $stageid;
        if(is_null($stageid) && is_null($matchid)){
        	     	
        		$sql .= " e.startdate BETWEEN '" .$fechas[1] ."' AND '". $fechas[5] ."' AND ";
       
		}

        //$sql .= " prop.value = 'yes' ";
        $sql .= " tt.gender = 'male'";
        if(!is_null($live)){
           
            if(!is_null($stageid)){ 
            	if($live == 'live'){
            	//if stage is not null grab only the stage id requested
	            	$sql .= " AND es.status_type = 'finished' "; //for testing purposes
	            	$sql .= " AND ts.id =" . $stageid;
            	}else {
            		$sql .= " AND ts.id =" . $stageid;
            	}
            }else {
            	if($live == 'live'){
            		$sql .= " AND es.status_type in ('inprogress','finished')"; //for testing purposes
            	}
            }
        }
        if(!is_null($matchid)){
        	$sql .= " AND e.id ="  .$matchid ;// for testing with a match
		}
        //$sql .= " AND ts.id IN (820745,821429,820219,820919) ";
        $sql .= " GROUP BY ";
        $sql .= " e.id ";
        $sql .= " ORDER BY ";
        $sql .= " cg.priority ,cg.country_id, ts.tournamentFK,e.tournament_stageFK,e.startdate, e.id ";
        //$sql .= " limit 1 "; //for testing with one record
        //echo $sql ."<br>";
        return $this->db2->fetchAll($sql);
    }

    

    public function getMatchesCountScoreboard ($fechas) {
        $sql = " SELECT ";
        $sql .= "cg.country_id,cg.country_e_id,c.name AS country_name,count(e.id) AS matchescount ";
        $sql .= "FROM ";
        $sql .= "tournament_template AS tt INNER JOIN ";
        $sql .= "tournament AS t ON t.tournament_templateFK = tt.id INNER JOIN ";
        $sql .= "tournament_stage AS ts ON t.id = ts.tournamentFK INNER JOIN ";
        $sql .= "country AS c ON ts.countryFK = c.id INNER JOIN ";
        $sql .= "countryg AS cg ON c.id = cg.country_e_id INNER JOIN ";
        $sql .= "event AS e ON ts.id = e.tournament_stageFK INNER JOIN ";
        //$sql .= "event_participants AS ep ON e.id = ep.eventFK LEFT JOIN "; 
        $sql .= "status_desc AS es ON e.status_descFK = es.id LEFT JOIN ";
        //$sql .= "participant AS p ON ep.participantFK = p.id LEFT JOIN ";
        //$sql .= "result AS r ON ep.id = r.event_participantsFK AND r.result_code = 'runningscore' LEFT JOIN ";
        $sql .= "property AS prop ON e.id = prop.objectFK AND prop.object ='event' AND prop.name = 'Live' ";
        $sql .= "WHERE ";
        $sql .= "tt.sportFK = '1'  AND ";
        //$sql .= "e.startdate BETWEEN '2010-07-24 00:00:00' AND '2010-07-24 23:59:59' AND ";
        $sql .= "e.startdate BETWEEN '" .$fechas[1] ."' AND '". $fechas[5] ."' AND ";
        $sql .= "prop.value = 'yes' ";
        $sql .= "GROUP BY ";
        $sql .= "c.name ";
        $sql .= "ORDER BY ";
        $sql .= "cg.priority,cg.country_id, e.startdate, e.id ";
        //echo $sql;
        return $this->db2->fetchAll($sql);
    }
    
    public function getAllLineUps($matchId){
    	
    	$sql = " SELECT p.name as player_name ,l.id , l.participantFK AS player_id, l.shirt_number,l.lineup_typeFK,ltype.name AS player_position, l.pos AS field_position, ep.participantFK as team_id, l.ut as date_event ";
		$sql .= " FROM lineup AS l ";
		$sql .= " INNER JOIN lineup_type as ltype ON ltype.id = l.lineup_typeFK ";
		$sql .= " INNER JOIN event_participants as ep on l.event_participantsFK = ep.id ";
		$sql .= " INNER JOIN participant as p on l.participantFK = p.id ";
		$sql .= " WHERE ep.eventFK = '" .$matchId ."'";
		//echo $sql; 
    	return $this->db2->fetchAll($sql);
    }

    public function getAllIncidents($matchId){
    	
    	$sql = " SELECT p.name as player_name,i.id , i.incident_typeFK,itype.name,i.elapsed as game_minute, i.ref_participantFK AS player_id , ep.participantFK as team_id,i.ut as date_event ";
		$sql .= " FROM incident AS i  ";
		$sql .= " INNER JOIN incident_type as itype ON itype.id = i.incident_typeFK ";
		$sql .= " INNER JOIN event_participants as ep on i.event_participantsFK = ep.id ";
		$sql .= " INNER JOIN participant as p on i.ref_participantFK = p.id ";
		$sql .= " WHERE ep.eventFK = '" .$matchId ."'";
		$sql .= " ORDER BY game_minute" ;
		//echo $sql; 
    	return $this->db2->fetchAll($sql);
    }
    
    public function getAllSeasons() {
    	
    	$sql = " SELECT ";
		$sql .= " t.id AS tournament_id, ";
		$sql .= " ts.id AS t_stage_id, ";
		$sql .= " glc.competition_id AS competition_id, ";
		$sql .= " ts.startdate, ";
		$sql .= " ts.enddate, ";
		$sql .= " t.name AS season_name     ";
		$sql .= " FROM tournament AS t "; 
		$sql .= " INNER JOIN tournament_template as tt ON tt.id = t.tournament_templateFK "; 
		$sql .= " INNER JOIN tournament_stage AS ts ON ts.tournamentFK = t.id  ";
		$sql .= " LEFT JOIN gleague_competition AS glc ON glc.tournament_templateFK = tt.id ";
		$sql .= " WHERE t.name LIKE '2010/2011%' and competition_id is not null ";
    	return $this->db2->fetchAll($sql);
    	
    }
    
    public function selectAllMatchesBySeason($seasonId){
    	
    	$sql = " SELECT es.status_type as game_status , e.id,t.id as season_id, ts.id as round_id,e.id AS event_id,ts.tournamentFK, ts.countryFK AS country_e_id, cg.country_id, "; 
        $sql .= " glc.competition_id, ts.id AS stage_id, ts.name AS stage_name, "; 
        $sql .= " DATE_FORMAT(e.startdate, '%Y-%m-%d') AS startdate, "; 
        $sql .= " DATE_FORMAT(e.startdate, '%H:%i') AS starttime, "; 
        $sql .= " MIN(IF( ep.number = 1, p.id, NULL)) AS home_team_id,";
		$sql .= " IFNULL(SUM(IF(ep.number = 1, r.value, NULL)),'-') AS home_score, "; 
        $sql .= " IFNULL(SUM(IF(ep.number = 2, r.value, NULL)),'-') AS away_score, "; 
        $sql .= " MIN(IF(ep.number = 2, p.id, NULL)) AS away_team_id";
        $sql .= " FROM tournament_template AS tt ";  
		$sql .= " LEFT JOIN gleague_competition AS glc ON glc.tournament_templateFK = tt.id ";  
		$sql .= " INNER JOIN tournament AS t ON t.tournament_templateFK = tt.id  "; 
		$sql .= " INNER JOIN tournament_stage AS ts ON t.id = ts.tournamentFK  "; 
		$sql .= " INNER JOIN country AS c ON ts.countryFK = c.id  "; 
		$sql .= " INNER JOIN countryg AS cg ON c.id = cg.country_e_id ";  
		$sql .= " INNER JOIN event AS e ON ts.id = e.tournament_stageFK  "; 
		$sql .= " INNER JOIN event_participants AS ep ON e.id = ep.eventFK  "; 
		$sql .= " LEFT JOIN participant AS p ON ep.participantFK = p.id  "; 
		$sql .= " LEFT JOIN result AS r ON ep.id = r.event_participantsFK  "; 
		$sql .= " LEFT JOIN status_desc AS es ON e.status_descFK = es.id ";
		$sql .= " AND r.del = 'no' "; 
		$sql .= " WHERE e.tournament_stageFK =" . $seasonId ;
		$sql .= " AND e.del = 'no' AND ";
		$sql .= " p.del = 'no' "; 
		$sql .= " GROUP BY e.id "; 
		$sql .= " ORDER BY e.startdate, e.id "; 
		//echo $sql; 
    	
    	return $this->db2->fetchAll($sql);
    	
    }
    

}
?>
