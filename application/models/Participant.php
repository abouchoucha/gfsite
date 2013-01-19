<?php
class Participant extends Zend_Db_Table_Abstract {
	protected $_name = 'particpant';
	protected $_primary = "id";
   

	 public function init()
        {
        //load the other adapter
          $this->db2 = Zend_Registry::get('dbspocosy');
        }
        

   public function getPlayerDetails($playerid) {
        $sql = " SELECT pa.id,pa.name, p.name,p.value ";
        $sql .= " FROM property as p INNER JOIN participant as pa ON pa.id = p.objectFK ";
        $sql .= " WHERE p.objectFK = ". $playerid;
        return $this->db2->fetchAll($sql);
   }
   
   
   //get player details from spocosy
   public function getPlayerDetailsAll($playerid){
   	$sql = " SELECT p.id as participant_id, ";
	$sql .= " p.name, ";
	$sql .= " MIN(IF(l.language_typeFK = 7, l.name, NULL)) AS firstname, ";
	$sql .= " MIN(IF(l.language_typeFK = 8, l.name, NULL)) AS lastname, ";
	$sql .= " MIN(IF(prop.name = 'date_of_birth', prop.value, NULL)) AS dob, ";
	$sql .= " MIN(IF(prop.name = 'position', prop.value, NULL)) AS position, ";
	$sql .= " MIN(IF(prop.name = 'height', prop.value, NULL)) AS height, ";
	$sql .= " MIN(IF(prop.name = 'weight', prop.value, NULL)) AS weight, "; 
	$sql .= " MIN(IF(prop.name = 'status', prop.value, NULL)) AS status, ";
	$sql .= " p.countryFK, ";
	$sql .= " cg.country_id, ";
	$sql .= " cg.country_name, ";
	$sql .= " p.ut ";
	$sql .= " FROM participant p ";
	$sql .= " INNER JOIN countryg cg ON p.countryFK = country_e_id ";
	$sql .= " INNER JOIN language l ON l.objectFK = p.id ";
	$sql .= " INNER JOIN property prop ON prop.objectFK = p.id";
	$sql .= " WHERE p.del = 'no' ";
	$sql .= " AND p.id = ". $playerid; 
	return $this->db2->fetchAll($sql);
   }

  //get active players by team if nation team say $national = yes else no
   public function getPlayerActiveTeam($playerid,$national) {
        $sql = " SELECT  p.id AS player_id, ";
        $sql .= "        p.name AS player_name, ";
        $sql .= "        p.countryFK AS country_id, ";
        $sql .= "        c.name AS country_name, ";
        $sql .= "        op.object AS object, ";
        $sql .= "        op.objectFK as team_id, ";
        $sql .= "        p2.name as team_name, ";
        $sql .= "        op.participant_type as type, ";
        $sql .= "        op.active as team_status ";
        $sql .= " FROM   participant AS p INNER JOIN ";
        $sql .= "        country as c ON c.id = p.countryFK INNER JOIN ";
        $sql .= "        object_participants as op ON op.participantFK = p.id INNER JOIN ";
        $sql .= "        participant AS p2 ON p2.id = op.objectFK INNER JOIN ";
        $sql .= "        property AS pp ON op.objectFK = pp.objectFK ";
        $sql .= " WHERE  op.object = 'participant' ";
        $sql .= " AND    p.id = ". $playerid;
        $sql .= " AND    pp.name ='IsNationalTeam' and pp.value = '$national'  ";
        $sql .= " AND    op.active = 'yes' ";
        return $this->db2->fetchAll($sql);

   }

    public function getPlayersByTeam($teamID){
		$sql = " SELECT par.id, ";
		$sql .= " gbp.player_id, ";
		$sql .= " par.name, ";
		$sql .= " gbp.player_position, ";
		$sql .= " obp.participant_type ";
		$sql .= " FROM object_participants as obp ";
		$sql .= " INNER JOIN participant as par ON par.id = obp.participantfK ";
		$sql .= " LEFT JOIN goalface_beta.player as gbp ON gbp.player_spocosy_id = par.id ";
		$sql .= " WHERE obp.object='participant' ";
		$sql .= " AND obp.objectfk= " . $teamID;
		$sql .= " AND obp.active='yes' ";
		$sql .= " AND obp.del='no' ";
		$sql .= " AND par.del='no' ";
        return $this->db2->fetchAll($sql);
    }
    
    

    
    
    public function getTeamStanding($roundId) {
          $sql =  " SELECT ";
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
          $sql .= "  WHERE ";
          $sql .= "  s.object = 'tournament_stage' AND ";
          $sql .= "  s.objectFK = " . $roundId ;
          $sql .= "  AND s.name = 'Ligatable' ";
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
          $sql .= " GROUP BY ";
          $sql .= " sp.id ";
          $sql .= " ORDER BY ";
          $sql .= " rank ";
          //echo $sql;
          return $this->db2->fetchAll($sql);
    }
    
    public function getAllPlayerActiveTeams($playerId){
    	
    	$sql = " select op.objectFK as teamId , p.value as isnational , op.active  ";
		$sql .= " from object_participants op ";
		$sql .= " INNER JOIN property as p ON p.objectFK = op.objectFK  ";
		$sql .= " and   p.object = 'participant' and p.name = 'IsNationalTeam' and p.del ='no' ";
		$sql .= " and op.participantFk = " .$playerId ." and op.objectFK <> 1 ";
		$sql .= " and op.active = 'yes' ";
		//echo $sql;
    	return $this->db2->fetchAll($sql);
    }
    
    public function getTeamsPlayerActive($playerId) {
    	$sql = " SELECT op.participantFK, ";
		$sql .= " op.objectFK as team_spocosy_id, ";
		$sql .= " gt.team_id as team_id, ";
		$sql .= " p.name, ";
		$sql .= " op.date_from, ";
		$sql .= " op.date_to, ";
		$sql .= " op.active, ";
		$sql .= " op.ut as player_creation ";
		$sql .= " FROM object_participants op ";
		$sql .= " INNER JOIN participant p ON p.id = op.objectFK ";
		$sql .= " LEFT JOIN gteam gt ON gt.team_spocosy_id = op.objectFK ";
		$sql .= " WHERE  op.object = 'participant' AND op.del ='no' AND op.active = 'yes' ";
		$sql .= " AND op.participantFK = " . $playerId;
    	return $this->db2->fetchAll($sql);
    }
 

}
?>
