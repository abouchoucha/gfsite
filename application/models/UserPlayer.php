<?php 
class UserPlayer extends Zend_Db_Table_Abstract
  {

      protected $_primary = "user_id";
      protected $_name   = 'userplayer';
      
      public function findUserPlayers($userId , $start11 = null , $from = null ,$letter = null,$format = null)
      {
          $db = $this->getAdapter();
          $sql =  " select p.player_country , p.player_position, p.player_name_short,p.player_common_name,p.player_id,p.player_firstname, ";
          $sql .= " p.player_lastname,p.player_seoname,p.player_nickname,p.player_common_name,u.greatest,u.startingeleven,u.mysquad,p.player_dob, ";
          $sql .= " u.alert_email,u.alert_sms,u.alert_facebook,u.alert_twitter,u.alert_frecuency_type";
          if($format != null){
             $sql .= "  ,pcs.gp as gamesplayed ,pcs.gl as goalscored, pcs.yc as yellowcards, pcs.rc as redcards ";
          }
          	
          $sql .= " from userplayer u ";
          $sql .= " inner join player as p on p.player_id = u.player_id ";
          $sql .= " and u.user_id ="  .$userId;
          
      	  if(!is_null($start11)){
          	$sql .= " and u.startingeleven = '1' ";
          }
      	  if(!is_null($letter)){
		$sql .= " and TRIM(SUBSTRING_INDEX(p.player_name_short, '.', -1)) like '$letter%' "; 
          }
          //$sql .= " left join playerimage as pim on pim.player_id = p.player_id ";
          if($format != null){
            $sql .= " left join playercurrentstats pcs on p.player_id = pcs.player_id ";
            $sql .= " and pcs.competition_format = '$format' " ;
          }
          $sql .= " order by p.player_name_short ";
          if(!is_null($from)){
			$sql .= " LIMIT " . $from . ",10";
		  }
		 // echo $sql . "<br>";
          $result = $db->query ($sql);
      	  $row = $result->fetchAll();
      	  return $row;
      }
      
      public function findUserPlayer($userId ,$playerId){
		
      	$db = $this->getAdapter ();
		$sql = " select * from userplayer ";
		$sql .= " where user_id =" . $userId;
		$sql .= " and player_id = ". $playerId;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
      }	
      
      public function deleteUserPlayer($userId ,$playerId ){
      	$db = $this->getAdapter ();
		$db->delete( 'userplayer' , 'player_id='.$playerId .' and user_id='.$userId);
      }
      
      public function deleteAllUserPlayers($userId){
      	$db = $this->getAdapter ();
		$db->delete( 'userplayer' , 'user_id='.$userId);
      }
      
      //get which users-player relations have checked to receive email scores/schedules alerts of their fav players
	   public function getUserPlayerEmailAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select * from userplayer where alert_email = '1' " ;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }

	   public function getUserPlayerFaceBookAlerts() {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select * from userplayer where alert_facebook = '1' " ;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }
	   
	   
  	   public function getUserPlayerIdEmailAlerts($playerId) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select u.screen_name,u.email,up.* from userplayer up ".
				"inner join user u on u.user_id=up.user_id ".
				"where u.user_enabled=1 and up.alert_email = '1' and up.player_id='".$playerId."' " ;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }

	   public function getUserPlayerIdFaceBookAlerts($playerId) {
	   	 	$db = $this->getAdapter();
	   	 	$sql = " select u.facebookid,u.screen_name,u.email,up.* from userplayer up ".
				"inner join user u on u.user_id=up.user_id ".
				"where u.user_enabled=1 and up.alert_facebook = '1' and up.player_id='".$playerId."' " ;;
	   	 	$result = $db->query ($sql);
	      	$row = $result->fetchAll();
	      	return $row;
	   }
     
	  	public function updateUserPlayer($userId, $playerId  , $data) {
			$db = $this->getAdapter ();
			$where = 'user_id = ' . $userId . ' and player_id = ' . $playerId ;
			$db->update ( 'userplayer', $data, $where );
		}


      
      
  }
?>
