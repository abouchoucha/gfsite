<?php
class TeamPlayer extends Zend_Db_Table_Abstract
{
    protected $_primary = "team_id";
    protected $_name = "teamplayer";

    public function findTeamPlayer($playerId , $teamId){
        $db = $this->getAdapter();
        $sql =  " select * ";
        $sql .= " from teamplayer ";
        $sql .= " where team_id = ".$teamId;
        $sql .= " and player_id = ".$playerId;
        //echo $sql;
        $result = $db->query ($sql);
        $row = $result->fetchAll();
        return $row;
    }

    public function findCurrentTeamPlayer($playerId , $teamType , $actualTeam , $teamId = null){
    	$db = $this->getAdapter();
        $sql =  " select tp.team_id ";
        $sql .= " from teamplayer tp, team t ";
        $sql .= " where tp.team_id = t.team_id ";
        $sql .= " and tp.player_id = ".$playerId;
        $sql .= " and tp.actual_team = " .$actualTeam;
        $sql .= " and t.team_type = '" .$teamType ."'" ;
        if(!is_null($teamId)){
        	$sql .= " and tp.team_id = " .$teamId  ;
        }
        //echo $sql;
        $result = $db->query ($sql);
        $row = $result->fetchColumn() ;
        return $row;
    }

	public function findCurrentTeamPlayerTemp($playerId , $teamType,$teamId = null){
    	$db = $this->getAdapter();
        $sql =  " select tp.team_id ";
        $sql .= " from teamplayer tp, team t ";
        $sql .= " where tp.team_id = t.team_id ";
        $sql .= " and tp.player_id = ".$playerId;
        $sql .= " and (tp.actual_team = 1 or tp.actual_team = 2)";
        $sql .= " and t.team_type = '" .$teamType ."'" ;
        if(!is_null($teamId)){
        	$sql .= " and tp.team_id = " .$teamId  ;
        }
        //echo $sql;
        $result = $db->query ($sql);
        $row = $result->fetchColumn() ;
        return $row;
    }

    public function findActiveTeamBoolean ($playerid ,$teamid) {
    	$db = $this->getAdapter();
    	 $sql =  " select tp.team_id ";
    	 $sql .= " from teamplayer tp ";
    	 $sql .= " where player_id =" .$playerid ;
    	 $sql .= " where team_id =" .$teamid ;
    	 $sql .= " and actual_team = 1 ";
    	 $result = $db->query ($sql);
         $row = $result->fetchAll();
         return $row;

    }

    public function findAllPlayersByTeam($teamId){

    	$db = $this->getAdapter();
        $sql = " select player_id from teamplayer where team_id = ". $teamId ." and actual_team = 1 " ;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
    }

    public function findAllPlayersByTeamTemp($teamId){

    	$db = $this->getAdapter();
        $sql = " select player_id,team_id from teamplayer where team_id = ". $teamId ." and actual_team = 2 " ;
        $result = $db->query ( $sql ) ;
        $row = $result->fetchAll () ;
        return $row ;
    }

    public function updateTeamPlayer($playerid ,$teamid ,$data)
    {
        $db = $this->getAdapter();
        $where = $db->quoteInto("team_id = ?", $teamid)
                .$db->quoteInto("and player_id = ?", $playerid);
        return $this->update($data, $where);
    }

    public function updateTeamPlayerByPlayerId($playerId , $data){
    	$db = $this->getAdapter();
        $where = $db->quoteInto("and player_id = ?", $playerId);
        return $this->update($data, $where);
    }

    public function updateTeamPlayerTemp($teamId,$data) {
    	$db = $this->getAdapter ();
    	$where[] = "team_id=".$teamId;
		  $where[] = "actual_team = 1";
    	$db->update( 'teamplayer' ,$data ,$where);
    }
    
    public function updateTeamPlayerbyId($id,$data){
     	$db = $this->getAdapter();
      $where = $db->quoteInto("and id = ?", $id);
      return $this->update($data, $where);
    }
    	
  	public function deleteTeamPlayer($teamId,$playerId ){
		$db = $this->getAdapter ();
		$db->delete( 'teamplayer' , 'team_id='.$teamId .' and player_id='.$playerId);
	}


}
