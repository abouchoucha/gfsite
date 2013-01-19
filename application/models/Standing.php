<?php
class Standing extends Zend_Db_Table_Abstract {
	protected $_name = 'standing';
	protected $_primary = "id";
   

	 public function init()
        {
        //load the other adapter
          $this->db2 = Zend_Registry::get('dbspocosy');
        }

  
    public function getTeamLeagueStanding($roundId) {
          $sql =  " SELECT t.team_id, ";
          $sql .= "  t.team_seoname, ";
          $sql .= "  p.id, ";
          $sql .= "  p.name, ";
          $sql .= "  sp.rank AS rank, ";
          $sql .= "  MIN(IF(sd.code = 'points', sd.value, NULL)) AS points, ";
          $sql .= "  MIN(IF(sd.code = 'played', sd.value, NULL)) AS played, ";
          $sql .= "  MIN(IF(sd.code = 'wins', sd.value, NULL)) AS wins, ";
          $sql .= "  MIN(IF(sd.code = 'draws', sd.value, NULL)) AS draws, ";
          $sql .= "  MIN(IF(sd.code = 'defeits', sd.value, NULL)) AS defeits, ";
          $sql .= "  MIN(IF(sd.code = 'goalsfor', sd.value, NULL)) AS goalsfor, ";
          $sql .= "  MIN(IF(sd.code = 'goalsagainst', sd.value, NULL)) AS goalsagainst ";
          $sql .= "  FROM ";
          $sql .= "  standing AS s INNER JOIN ";
          $sql .= "  standing_participants AS sp ON s.id = sp.standingFK INNER JOIN ";
          $sql .= "  standing_data AS sd ON sp.id = sd.standing_participantsFK INNER JOIN ";
          $sql .= "  participant AS p ON sp.participantFK = p.id ";
          $sql .= "  INNER JOIN goalface_beta.team AS t ON p.id = t.team_spocosy_id ";
          $sql .= "  WHERE ";
          $sql .= "  s.object = 'tournament_stage' AND ";
          $sql .= "  s.objectFK = " . $roundId ;
          $sql .= "  AND s.name = 'Ligatable' ";
          $sql .= "  AND sp.del = 'no' ";
          $sql .= "  GROUP BY ";
          $sql .= "  sp.id ";
          $sql .= "  ORDER BY ";
          $sql .= "  rank ";
          //echo $sql;
          return $this->db2->fetchAll($sql);
    }
    
    
    public function getTeamLeagueStandingFull($roundId) {
          $sql =  " SELECT t.team_id,";
          $sql .= "  t.team_seoname, ";
          $sql .= "  t.team_name, ";
          $sql .= "  sp.rank AS rank, ";
          $sql .= "  p.name, ";
          $sql .= "  MIN(IF(sd.code = 'played', sd.value, NULL)) AS played, ";
          $sql .= "  MIN(IF(sd.code = 'wins', sd.value, NULL)) AS wins, ";
          $sql .= "  MIN(IF(sd.code = 'draws', sd.value, NULL)) AS draws, ";
          $sql .= "  MIN(IF(sd.code = 'defeits', sd.value, NULL)) AS defeits, ";
          $sql .= "  MIN(IF(sd.code = 'goalsfor', sd.value, NULL)) AS goalsfor, ";
          $sql .= "  MIN(IF(sd.code = 'goalsagainst', sd.value, NULL)) AS goalsagainst, ";
          $sql .= "  MIN(IF(sd.code = 'playedhome' , sd.value, NULL)) AS playedhome, ";
          $sql .= "  MIN(IF(sd.code = 'winshome', sd.value, NULL)) AS winshome, ";
          $sql .= "  MIN(IF(sd.code = 'drawshome', sd.value, NULL)) AS drawshome, ";
          $sql .= "  MIN(IF(sd.code = 'defeitshome', sd.value, NULL)) AS defeitshome, ";
          $sql .= "  MIN(IF(sd.code = 'goalsforhome', sd.value, NULL)) AS goalsforhome, ";
          $sql .= "  MIN(IF(sd.code = 'goalsagainsthome', sd.value, NULL)) AS goalsagainsthome, ";
          $sql .= "  MIN(IF(sd.code = 'playedaway', sd.value, NULL)) AS playedaway, ";
          $sql .= "  MIN(IF(sd.code = 'winsaway', sd.value, NULL)) AS winsaway, ";
          $sql .= "  MIN(IF(sd.code = 'drawsaway', sd.value, NULL)) AS drawsaway, ";
          $sql .= "  MIN(IF(sd.code = 'defeitsaway', sd.value, NULL)) AS defeitsaway, ";
          $sql .= "  MIN(IF(sd.code = 'goalsforaway', sd.value, NULL)) AS goalsforaway, ";
          $sql .= "  MIN(IF(sd.code = 'goalsagainstaway', sd.value, NULL)) AS goalsagainstaway, ";
          $sql .= "  MIN(IF(sd.code = 'points', sd.value, NULL)) AS points ";
          $sql .= "  FROM ";
          $sql .= "  standing AS s INNER JOIN ";
          $sql .= "  standing_participants AS sp ON s.id = sp.standingFK INNER JOIN ";
          $sql .= "  standing_data AS sd ON sp.id = sd.standing_participantsFK INNER JOIN ";
          $sql .= "  participant AS p ON sp.participantFK = p.id ";
          $sql .= "  INNER JOIN goalface_beta.team AS t ON p.id = t.team_spocosy_id ";
          $sql .= "  WHERE ";
          $sql .= "  s.object = 'tournament_stage' AND ";
          $sql .= "  s.objectFK = " . $roundId;
          $sql .= "  AND s.name = 'Ligatable' ";
          $sql .= "  AND sp.del = 'no' ";
          $sql .= "  GROUP BY ";
          $sql .= "  sp.id ";
          $sql .= "  ORDER BY ";
          $sql .= "  rank ";
          //echo $sql;
          return $this->db2->fetchAll($sql);
    }
    
    
    //get Stadings per team in their domestic league
    public function getTeamIndividualStanding($roundId,$teamId) {
          $sql =  " SELECT ";
          $sql .= " p.id, ";
          $sql .= " p.name, ";
          $sql .= " sp.rank AS rank, ";
          $sql .= " MIN(IF(sd.code = 'points', sd.value, NULL)) AS points, ";
          $sql .= " MIN(IF(sd.code = 'played', sd.value, NULL)) AS played, ";
          $sql .= " MIN(IF(sd.code = 'wins', sd.value, NULL)) AS wins, ";
          $sql .= " MIN(IF(sd.code = 'draws', sd.value, NULL)) AS draws, ";
          $sql .= " MIN(IF(sd.code = 'defeits', sd.value, NULL)) AS defeits, ";
          $sql .= " MIN(IF(sd.code = 'goalsfor', sd.value, NULL)) AS goalsfor, ";
          $sql .= " MIN(IF(sd.code = 'goalsagainst', sd.value, NULL)) AS goalsagainst ";
          $sql .= " FROM ";
          $sql .= " standing AS s INNER JOIN ";
          $sql .= " standing_participants AS sp ON s.id = sp.standingFK INNER JOIN ";
          $sql .= " standing_data AS sd ON sp.id = sd.standing_participantsFK INNER JOIN ";
          $sql .= " participant AS p ON sp.participantFK = p.id ";
          $sql .= " WHERE ";
          $sql .= " s.object = 'tournament_stage' AND ";
          $sql .= " s.objectFK = " . $roundId ;
          $sql .= " AND s.name = 'Ligatable' AND p.id = " . $teamId ;
          $sql .= " AND sp.del = 'no' ";
          $sql .= " GROUP BY ";
          $sql .= " sp.id ";
          $sql .= " ORDER BY ";
          $sql .= " rank ";
          //echo $sql;
          return $this->db2->fetchAll($sql);
     }
     
    
    public function getLeagueTopScorers($roundIdList, $compType = null) {
        $sql = "SELECT ";
      	$sql .= " gp.player_id,gp.player_nickname,gp.player_firstname,gp.player_lastname,gp.player_common_name, p.name AS player_name , ";  
      	$sql .= " MIN(IF(sd.code = 'topscore_team_fk',p2.name, NULL)) AS team_name, ";
      	$sql .= " SUM(IF(sd.code = 'goals', sd.value, NULL)) AS goals ";
        $sql .= " FROM ";
      	$sql .= " standing AS s INNER JOIN ";
      	$sql .= " standing_participants AS sp ON s.id = sp.standingFK INNER JOIN ";
      	$sql .= " standing_data AS sd ON sp.id = sd.standing_participantsFK INNER JOIN ";
      	$sql .= " participant AS p ON sp.participantFK = p.id LEFT JOIN ";
      	$sql .= " participant AS p2 ON sd.value = p2.id ";
		$sql .= " INNER JOIN goalface_beta.player AS gp ON p.id = gp.player_spocosy_id ";
        $sql .= " WHERE ";
      	$sql .= " s.object = 'tournament_stage' AND ";
      	
      	if($compType == 'International cup'){
      		$sql .= "  s.objectFK IN  (" .  $roundIdList . ")"; ;
      	}else{
      		$sql .= "  s.objectFK = ". $roundIdList ;
      	}
      	$sql .= " AND s.name = 'Topscorer list' AND ";
      	$sql .= " sd.code IN ('topscore_team_fk', 'goals') "; 
      	$sql .= " AND sp.del = 'no' ";    
        $sql .= " GROUP BY  ";
      	$sql .= " sp.participantFK ";
        $sql .= " ORDER BY ";
        $sql .= " SUM(IF(sd.code = 'goals', sd.value, NULL)) DESC, player_name LIMIT 10";
        //echo $sql;
      	return $this->db2->fetchAll($sql);
    }

}
?>