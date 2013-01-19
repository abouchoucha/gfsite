<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require_once 'util/SameClassName.php';
/**
 * Description of RestController
 *
 * @author VASQUEZJ
 */
require_once 'util/Common.php';

class RestController extends Zend_Controller_Action {
	
	function init() {
		Zend_Loader::loadClass ( 'Player' );
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'Zend_Debug' );

	}
	
	public function servicesAction() {
		$server = new Zend_Rest_Server ( );
		$server->setClass ( 'ApiCalls' );
		$server->handle ();
		$this->_helper->viewRenderer->setNoRender();
	}
}



class ApiCalls {

    public function getTeamDetails($teamId) {
        $team = new Team();
        //query on model uses fetchAll()
        $teamrow = $team->findUniqueTeam($teamId);
        $i = 1;
            foreach ($teamrow as $stat){
                 if ($i == 1) {
                  $xml = '<?xml version="1.0"?><teams>';
                }
                $xml .= '<team team_id="'. $teamId . 
                            '" team_name="'. $stat['team_name'].
  					        '" team_full_name="'. $stat['team_name_official'].
                            '" team_location="'. $stat['team_city']. 
                            '" team_founded="'. $stat['team_founded']. 
                            '" team_stadium="'. $stat['team_stadium'].
                            '" team_stadium_attendance="'. $stat['team_stadium_attendance'].'" />';

            $i = $i + 1;}
        $xml .= '</teams>';
        $xml = simplexml_load_string($xml);
    	return $xml;
    }
    
    public function getTeamPlayers($teamId) {
    	$team = new Team();
    	$players = $team->findPlayersbyTeam ( $teamId );
		$i = 1;
		foreach ($players as $data){ 
		   if ($i == 1) {
                  $xml = '<?xml version="1.0"?><players>';
            }
			$xml .= '<player player_id="'. $data['player_id']. 
                            '" player_common_name="'. $data['player_common_name'].	
                            '" player_position="'. $data['player_position'].'" />';
			$i = $i + 1;
		}
        $xml .= '</players>';
        $xml = simplexml_load_string($xml);
    	return $xml;
    	
    }


	public function getPlayerDetails ($playerId) {

  		$player = new Player ( );
        //query on model uses fetchRow()
		$stat = $player->findUniquePlayer ( $playerId );
        $common = new Common ( );
		$playerage = $common->GetAge ( $stat->player_dob );
        $xml ='<?xml version="1.0"?><players>
	    <player player_id="'.$playerId.'" player_first_name="'. $stat->player_firstname.'" player_last_name="'. $stat->player_lastname.'" player_common_name="'. $stat->player_common_name.'" player_dob="'. $stat->player_dob.'" player_dob_city="'. $stat->player_dob_city.'" player_age="'. $playerage.'" player_position="'. $stat->player_position.'" player_weight="'. $stat->player_weight.'" player_height="'. $stat->player_height.'"/>
	    </players>';
        $xml = simplexml_load_string($xml);
		
    	return $xml;
  }
  
  public function getPlayers() {
  
  }
  

  
  public function getPlayerTeammates($playerId) {
  		$player = new Player ( );
  		$teammates = $player->findTeammatesByPlayer($playerId);
  		$i = 1;
  		foreach ($teammates as $data){ 
		   if ($i == 1) {
                  $xml = '<?xml version="1.0"?><players>';
            }
			$xml .= '<player player_id="'. $data['player_id']. 
                            '" player_common_name="'. $data['player_common_name'].	
                            '" player_position="'. $data['player_position'].'" />';
			$i = $i + 1;
		}
        $xml .= '</players>';
        $xml = simplexml_load_string($xml);
    	return $xml;
 
  	}
	
	public function getPlayerStats($playerId) {
		$player = new Player ( );
		$rowset = $player->findUniquePlayer ( $playerId );
		$playerstats = $player->getPlayerTeamStatsDetails ( $playerId,1 );
		
		    $i = 1;
            foreach ($playerstats as $stat){
                if ($i == 1) {
                  $xml ='<?xml version="1.0"?><player player_id="'. $playerId . '" player_name="'. $stat['player_name_short'].'">';
                }
                  $xml .= '<season season_id="'. $stat['season_id'].'" season_name="'. $stat['season_name'].'"
  					 team_id="'. $stat['team_id'].'" team_name="'. $stat['team_name'].'" competition_name="'. $stat['competition_name'].'" 
  					 gp="'. $stat['gp'].'" sb="'. $stat['sb'].'" minp="'. $stat['minp'].'" gl="'. $stat['gl'].'" hd="'. $stat['hd'].'" 
  					 fk="'. $stat['fk'].'" gin="'. $stat['gin'].'" gout="'. $stat['gout'].'" pn="'. $stat['pn'].'" pa="'. $stat['pa'].'" ast="'. $stat['ast'].'" 
  					 dd="'. $stat['dd'].'" sht="'. $stat['sht'].'" gw="'. $stat['gw'].'" fls="'. $stat['fls'].'" yc="'. $stat['yc'].'" 
  					 rc="'. $stat['rc'].'" mpg="'. $stat['mpg'].'" sh="'. $stat['sh'].'" g_90="'. $stat['g_90'].'" a_90="'. $stat['a_90'].'"
  					 sh_90="'. $stat['sh_90'].'" fls_90="'. $stat['fls_90'].'" />';
		   $i = $i + 1;}
           $xml .= '</player>';
           $xml = simplexml_load_string($xml);
    	   return $xml;
	}

    public function getTeamStats ($teamId) {
        $team = new Team ( );
        $teamstats = $team->getTeamSeasonStats($teamId);
        $i = 1;
        foreach ($teamstats as $stat){
            if ($i == 1) {
                  $xml = '<?xml version="1.0"?><team team_id="'. $teamId . '" team_name="'. $stat['team_name'] .'">';
                }
            $xml .= '<season season_name="'. $stat['season_name'].'"
  					 competition_id="'. $stat['competition_id'].'" competition_name="'. $stat['competition_name'].'" rank="'. $stat['rank'].'"
  					 games_played="'. $stat['gp'].'" wins ="'. $stat['wn'].'" draws ="'. $stat['dr'].'" losses="'. $stat['ls'].'" gf="'. $stat['gf'].'"
  					 gfpg="'. $stat['gfpg'].'"  ga="'. $stat['ga'].'" gapg="'. $stat['gapg'].'"  points="'. $stat['pts'].'" points_per_game="'. $stat['ptspg'].'" wperce="'. $stat['wperce'].'" plusminus="'. $stat['plusminus'].'"
              hv="'. $stat['hv'].'" hd="'. $stat['hd'].'" hl="'. $stat['hl'].'" rw="'. $stat['rw'].'" rd="'. $stat['rd'].'" rl="'. $stat['rl'].'"
  					 support="'. $stat['support'].'" average="'. $stat['average'].'" red_cards="'. $stat['rc'].'" yellow_cards="'. $stat['yc'].'" cpg="'. $stat['cpg'].'"
  					 cs="'. $stat['cs'].'" />';
        $i = $i + 1;}
        $xml .= '</team>';
        $xml = simplexml_load_string($xml);
    	return $xml;
    }
    
    
    

}

?>
