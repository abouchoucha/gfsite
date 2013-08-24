<?php
class MatchEvent extends Zend_Db_Table_Abstract
{
  protected $_primary = "event_id";
  protected $_name   = 'matchevent';
    
  public function findMatchType($matchId) {
    $db = $this->getAdapter();
    	
    $sql = " select l.type ";
	$sql .= " from `matchh` m , league_competition l ";
	$sql .= " where m.competition_id = l.competition_id ";
	$sql .= " and match_id = '" . $matchId ."'";
  //echo $sql; 
	$result = $db->query ($sql);
    $row = $result->fetchColumn();
    return $row;
  }
	
	public function findEventsByMatch($matchId , $matchType) {
      
	  $db = $this->getAdapter();
     
   	  $sql = " select me.event_id,et.event_name,et.event_type_id, me.event_minute ,m.team_a,m.team_b, ";
   	  $sql .= " me.player_id,me.jersey_number,p.player_name_short ,p.player_common_name,p.player_firstname,p.player_lastname,p.player_nickname,t.team_id ";
	  $sql .= " from matchevent me, matchh m ,eventtype et, player p ,team t,playercategory pc ";
	  $sql .= " where me.match_id ='" . $matchId ."'";
	  $sql .= " and me.match_id = m.match_id  ";
	  $sql .= " and me.event_type_id = et.event_type_id ";
	  $sql .= " and me.player_id = p.player_id ";
	  $sql .= " and p.player_position = pc.player_category_name ";
	  $sql .= " and me.team_id = t.team_id  ";
	  if($matchType == 'international'){
	  	$sql .= " and t.team_type = 'national' ";
	  }else if($matchType == 'club'){
	  $sql .= " and t.team_type = 'club' ";
	  }	
	  $sql .= " ORDER BY t.team_id,me.event_type_id,me.event_minute desc,pc.player_category_id ";
      //echo $sql;
      $result = $db->query ($sql);
      $row = $result->fetchAll ();
      return $row;
      
    }
    
    public function deleteMatchEvents($matchId , $eventType){
    	
    	  $db = $this->getAdapter ();
  		  $db->delete( 'matchevent' , "match_id ='" . $matchId ."' and event_type_id = '"  .$eventType . "'"); 	
    }
    
    public function findMatchEventsByRange($from , $to) {
    
    	  $db = $this->getAdapter();
     	  $sql = " select me.event_id,me.player_id , me.event_type_id , me.match_id, me.event_minute,me.time  ";
		  $sql .= " from matchevent me";
		  $sql .= " where event_id between " .$from . " and " . $to;
		  //echo $sql;
		  $result = $db->query ($sql);
	      $row = $result->fetchAll ();
	      return $row;	
    
    }
	
	//Goals
	public function findGoalsByMatchCount($matchId) {
	  $db = $this->getAdapter();
	  $sql = "  select me.player_id, p.player_name_short,p.player_nickname,p.player_firstname,p.player_lastname,p.player_common_name, me.event_type_id, COUNT(*) as goals";
	  $sql .= " from matchevent me, matchh m , player p ,team t,playercategory pc "; 
	  $sql .= " where me.match_id ='" . $matchId ."'";
	  $sql .= " and me.match_id = m.match_id ";  
	  $sql .= " and me.player_id = p.player_id ";
	  $sql .= " and p.player_position = pc.player_category_name "; 
	  $sql .= " and me.team_id = t.team_id ";
	  $sql .= " and me.event_type_id = 'G' ";
	  $sql .= " GROUP BY me.player_id,me.event_type_id ";
	  $sql .= " ORDER BY t.team_id,me.event_type_id,me.event_minute desc,pc.player_category_id ";
	  //echo $sql;
	  $result = $db->query ($sql);
	  $row = $result->fetchAll ();
	  return $row;	
	}
    
    //CleanSheets
    public function findCleenSheetsByMatch($matchId,$teamId) {
	  $db = $this->getAdapter();
	  $sql = " select me.event_id,me.event_type_id, me.event_minute ,m.team_a,m.team_b, ";
      $sql .= " me.player_id,p.player_name_short, p.player_position, p.player_common_name,p.player_firstname,p.player_lastname,p.player_nickname,t.team_id ";
      $sql .= " from matchevent me, matchh m , player p ,team t,playercategory pc ";
      $sql .= " where me.match_id ='" . $matchId ."'";
      $sql .= " and me.match_id = m.match_id "; 
      $sql .= " and me.player_id = p.player_id ";
      $sql .= " and p.player_position = pc.player_category_name ";
      $sql .= " and me.team_id = t.team_id ";
      $sql .= " and me.event_type_id = 'L' ";   
      $sql .= " and me.team_id = " . $teamId;
      $sql .= " and (p.player_position = 'Goalkeeper' OR p.player_position = 'Defender') ";
      $sql .= " ORDER BY t.team_id,me.event_type_id,me.event_minute desc,pc.player_category_id  ";
	  //echo $sql;
	  $result = $db->query ($sql);
	  $row = $result->fetchAll ();
	  return $row;	
	}
    
	
	
	
    

}
