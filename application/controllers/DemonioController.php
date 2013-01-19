<?php
require_once 'util/Constants.php';
require_once 'scripts/seourlgen.php';
require_once 'GoalFaceController.php';

class DemonioController extends GoalFaceController {
	public function init() {
		Zend_Loader::loadClass ('Zend_Debug');
		Zend_Loader::loadClass ('Player');
		Zend_Loader::loadClass ('Team');
		Zend_Loader::loadClass ('LeagueCompetition');
		Zend_Loader::loadClass ('PlayerSeason');
		Zend_Loader::loadClass ('TeamPlayerStats');
		Zend_Loader::loadClass ('TeamGoalieStats');
	}

	private function getplayerimage($playerid) {		
		$path_player_photos = $this->image_player . $playerid .'.jpg' ;
		//echo $path_player_photos . "<br>";
		if (file_exists($path_player_photos)) { 
			$imageplayer = '/players/'.$playerid.'.jpg';
		} else {
			$imageplayer = '/ProfileMale50.gif';
		}
		return $imageplayer;
	}

	
	
	public function indexAction() {
		$standing_country = $this->_request->getParam ( 'country', null );
		$feedpath = 'standings/'.$standing_country.'.xml';
        $xml = parent::getGoalserveFeed($feedpath);
		$teamdata = new Team ();
		
		//Zend_Debug::dump($xml);
		foreach ($xml->tournament->team as $team) {
		    echo $team['id'] ."<BR>";
		}	    
	}
	
    public function getteammapfromfixtureAction() {
      //$stage = $this->_request->getParam ( 'stage', null );
      $teamdata = new Team ();
	    $feedpath = 'soccerfixtures/brazil/Carioca1-TacaGuanabara';
	    //$feedpath = 'soccerhistory/spain/Primera-2006-2007';
	    $xml = parent::getGoalserveFeed($feedpath);
      $seasonid = 131125;
      $competitionId = 240; 
	    $stage['stage_id'] = 11251330;
	    $match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");
	    //$match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']");
	    foreach($match_aggregate as $aggregate) {
	        foreach ($aggregate->match as $match) {
	           $rowTeamLocal = $teamdata->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] ); 
	           $rowTeamVisitor = $teamdata->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
	           //echo "UPDATE team SET team_gs_id = " . $match->localteam ['id']  ." WHERE team_id = ". $rowTeamLocal['team_id'] .";  ". $match->localteam['name'] . "<br>";
	           //echo "UPDATE team SET team_gs_id = " . $match->visitorteam ['id'] ." WHERE team_id = ". $rowTeamVisitor['team_id'] .";  ". $match->visitorteam['name'] . "<br>";
	           //echo "http://www.goalface.com/goalservetogoalface/updatesquad/league/".$competitionId."/team/".$rowTeamLocal['team_id']. "<br>";
              //echo "http://www.goalface.com/goalservetogoalface/updatesquad/league/".$competitionId."/team/".$rowTeamVisitor['team_id']. "<br>";
             echo "INSERT INTO teamseason VALUES(".$rowTeamLocal['team_id'].",".$seasonid.",0);<br>";
	        echo "INSERT INTO teamseason VALUES(".$rowTeamVisitor['team_id'].",".$seasonid.",0);<br>";
	        }
	    }
	}
	
	
	public function getplayerimageAction() {
    $player = new Player ();
    $playerId = $this->_request->getParam ( 'player', null );
    //$teamId = $this->_request->getParam ( 'team', null );
		//$rowplayers = $player->findAllPlayersForSearch(140000,142000);
    if (isset($playerId)) {
      $rowplayers = $player->findPlayerProfileDetails($playerId);
		} 
		//if (isset($teamId)) {
     // $rowplayers = $player->findPlayerTeamNoPic($playerId);
      //Zend_Debug::dump($rowplayers);
		//} 
		foreach ($rowplayers as $rowplayer) {
			$playerxml = parent::getGoalserveFeed('soccerstats/player/'.$rowplayer['player_id']);		
			if ($playerxml != null || $playerxml != '') {
				$string = $playerxml->player->image;
				if ($string != "") {

					$img = imagecreatefromstring(base64_decode($string));
					if($img != false)
					{
					  //imagejpeg($img, 'C:\wamp\www\goalface08\public\images\playerphotos\\'. $rowplayer['player_id'].'.jpg');
				    imagejpeg($img, '/home/goalface/public_html/goalfaceapp/public/images/players/'. $rowplayer['player_id'].'.jpg');
					  echo "image for player = ".$rowplayer['player_id']." saved<br><br>";
					} 
				}
			}
		}
	}
	
	public function getteamimageAction() {
	    $team = new Team();
	    $rowteams = $team->selectTeamsBySeason(111041);
        //Zend_Debug::dump($rowteams);
	    foreach ($rowteams as $rowteam) {	            
	        $teamxml = parent::getGoalserveFeed('soccerstats/team/'.$rowteam['team_gs_id']);
    	        if ($teamxml != null || $teamxml != '') {
    				  $string = $teamxml->team->image;
    				  if ($string != "") {
        					$img = imagecreatefromstring(base64_decode($string));
        					if($img != false)
        					{
        					   imagegif($img, 'C:\wamp\www\goalface08\public\images\teamphotos\\'. $rowteam['team_id'].'.gif');
        					   //imagejpeg($img, '/Users/kokus/pictures/team/'. $rowteam['team_id'].'.gif'); 
        					   echo "image for team = ".$rowteam['team_id']." saved<br><br>";
        					} 
        				}
			        }
	   }
	}

	//getmapteams/country/argentina

	public function getteamsbyseasonAction() {
	    $standing_country = $this->_request->getParam ( 'country', null );
	    $seasonid = $this->_request->getParam ( 'season', null );
	    $competitionId = $this->_request->getParam ( 'league', null );
		//$feedpath = 'standings/'.$standing_country.'.xml';
		 $feedpath = 'standings/venezuela.xml';
        $xml = parent::getGoalserveFeed($feedpath);
        $teamdata = new Team ();
        
		// foreach ($xml->tournament as $group) {
		//     //echo $group['name']."<BR>";
		//     foreach ($group->team as $team) {
  //   		    $rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
  //   			 //echo "INSERT INTO teamseason VALUES(".$rowTeam['team_id'].",".$seasonid.",0);<br>";
  //   			echo "UPDATE team SET team_gs_id = " . $team['id'] ." WHERE team_id = ". $rowTeam['team_id'] .";  ". $team['name'] . "<br>";
  //   			//echo 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/' . $team['id'] ."<br>";
  //   			//echo "http://www.goalface.com/goalservetogoalface/updatesquad/league/".$competitionId."/team/".$rowTeam['team_id']. "<br>";
		//      }
		// }

		foreach ($xml->tournament->team as $team) {
			$rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
	        //echo "UPDATE team SET team_gs_id = " . $team['id'] ." WHERE team_id = ". $rowTeam['team_id'] .";  ". $team[ 'name'] . "<br>";
			//echo "INSERT INTO teamseason VALUES(".$rowTeam['team_id'].",".$seasonid.",0);<br>";
			echo "http://www.goalface.com/goalservetogoalface/updatesquad/league/".$competitionId."/team/".$rowTeam['team_id']. "<br>";			
		}
		
/*		$rowTeam ['team_gs_id'] = 13904; 
		$xml = $this->getgsfeed('soccerstats/team/'.$rowTeam ['team_gs_id']);
				
				foreach ( $xml->team as $teamxml ) {
					foreach ( $teamxml->squad->player as $playerxml ) {
						echo $playerxml['id']."--".$playerxml['name']."-->".mb_convert_encoding($playerxml ['name'], "ISO-8859-1", mb_detect_encoding($playerxml ['name'], "UTF-8, ISO-8859-1, ISO-8859-15", true))."<br>";
					}
				}*/
	}


	public function updateplayerdetailsAction() {
	    $team = new Team ();
		$teamplayer = new TeamPlayer ();
		$player = new Player ();
		$country = new Country ();
		$league = new LeagueCompetition();
		$teamid = $this->_request->getParam ( 'team', null );
		$leagueid = $this->_request->getParam ( 'league', null );
		$teamsLeague = $team->getTeamsPerCompetitionParse($leagueid,$teamid);
		// iterate over all team on a league
		foreach($teamsLeague as $teamleague) {
		    //get team information array
			$rowTeam = $team->fetchRow ( 'team_id = ' . $teamleague['team_id'] );
		    echo "<strong>".$rowTeam['team_name']."</strong><br><br>";
		    if ($rowTeam ['team_gs_id'] != null) {   
    			$feedpath = 'soccerstats/team/'.$rowTeam ['team_gs_id'];
          $xml = parent::getGoalserveFeed($feedpath);
          foreach ( $xml->team as $teamxml ) {
    				foreach ( $teamxml->squad->player as $playerxml ) {
    					//fetch player xml info from feed
					    $rowPlayer = $player->fetchRow ( 'player_id = ' . $playerxml ['id'] );
					    if ($rowPlayer != null) {
					        $feedpathplayer = 'soccerstats/player/'.$playerxml ['id'];
					        $xmlplayer = parent::getGoalserveFeed($feedpathplayer);	
					        if ($xmlplayer != null || $xmlplayer != '') {
					            foreach ($xmlplayer->player as $xmlPlayer) {
					                echo $xmlPlayer->name."<BR>";
					                foreach ($xmlPlayer->statistic->club as $statclub) {
					                    $rowTeamStat = $team->fetchRow ( 'team_gs_id = ' . $statclub['id'] );
					                    $rowTeamLeagueStat = $rowTeamStat->fetchRow ( 'competition_id = ' . $statclub['league_id'] );
					                    
					                    if ($rowTeamStat != null) {
					                        $readcards = $statclub ['redcards'] + $statclub ['yellowred'];
					                        $playerStatArray = array (
            											'player_id' => $xmlPlayer ['id'], 
            											//'First_Name' => $xmlplayer->player->firstname, 
            											//'Last_Name' => $xmlplayer->player->lastname, 
            											'team_id' => $rowTeamStat ['team_id'], 
            											'team_name' => htmlspecialchars ( $statclub ['name'], ENT_COMPAT, 'UTF-8' ), 
            											'season_id' => null, 
            											'season_name' => $statclub ['season'], 
            											'competition_id' => $rowTeamLeagueStat ['competition_id'], 
            											'competition_name' => $rowTeamLeagueStat ['competition_name'], 
            											'minp' => $statclub ['minutes'], 
            											'gp' => $statclub ['appearences'], 
            											'sb' => $statclub ['substitute_in'], 
            											'gl' => $statclub ['goals'], 
            											'yc' => $statclub ['yellowcards'], 
            											'rc' => $readcards );
            											//insert in playerstars
            											//$playerSeasonStat->insert ( $playerStatArray );
            											//Zend_Debug::dump($playerStatArray);
					                    } else {
					                        echo $statclub['name'] ." - ". $statclub['season']. "UPDATE team SET team_gs_id = " . $statclub['id'] ." WHERE team_id = XXX ". $statclub['name'] ."<br>";
					                    }
					                    
					                }
					            }
					            echo "===========================================";
					        }
					    }    
    				}
                }		
			} else {
		        echo "Team Id :" . $teamid . " has not been mapped";
			} 
		}   
	}
	
  //Insert player stats based on teams squads per season, reads XML feed and insert teamplayerstats table
  // Spain1  - 2011-12 (6226)
  // England 1 - 2011-12 ( 6118)
	public function getseasonplayerstatsAction() {
		$playerseason = new PlayerSeason();
		$playerSeasonStat = new TeamPlayerStats ();
		$playerGoalkeeperSeasonStat = new TeamGoalieStats();
		$seasonId = $this->_request->getParam ( 'season', null );
		$seasonplayers = $playerseason->getPlayerTeamBySeason($seasonId);

		foreach ($seasonplayers as $player) {
			$feedpathplayer = 'soccerstats/player/'.$player ['player_id'];
			$xmlplayer = parent::getGoalserveFeed($feedpathplayer);	
			if ($xmlplayer != null || $xmlplayer != '') {
				foreach ($xmlplayer->player as $xmlPlayer) {
					//echo $xmlPlayer->name."<BR>";
						foreach ($xmlPlayer->statistic->club as $statclub) {
							if (($statclub ['season'] == $player['season_title']) && ($statclub['league_id'] == $player ['competition_gs_id']))  { 
								if ($player ['player_position'] != 'Goalkeeper' ) {
									$playerStatArray = array ( 
												 'player_id' => $player ['player_id'], 
												 'First_Name' => $xmlplayer->player->firstname, 
												 'Last_Name' => $xmlplayer->player->lastname, 
												 'team_id' => $player ['team_id'], 
												 'team_name' => $player ['team_name'],
												 'season_id' => $seasonId, 
												 'season_name' => $statclub ['season'], 
												 'competition_id' => $player ['competition_id'], 
												 'competition_name' => $player ['competition_name'], 
												 'minp' => $statclub ['minutes'], 
												 'gp' => $statclub ['appearences'], 
												 'sb' => $statclub ['substitute_in'], 
												 'gl' => $statclub ['goals'], 
												 'yc' => $statclub ['yellowcards'], 
												 'rc' => $statclub ['redcards'] 
									);   
									//insert in playerstars
									//$playerSeasonStat->insert ( $playerStatArray );
									echo $xmlPlayer->name."--". $statclub ['season'] ." UPDATED <BR>";
									//Zend_Debug::dump($playerStatArray);
								} else {
									$playerStatArray = array ( 
												 'player_id' => $player ['player_id'], 
												 'player_first_name' => $xmlplayer->player->firstname, 
												 'player_last_name' => $xmlplayer->player->lastname, 
												 'team_id' => $player ['team_id'], 
												 'team_name' => $player ['team_name'],
												 'season_id' => $seasonId, 
												 'season_name' => $statclub ['season'], 
												 'competition_id' => $player ['competition_id'], 
												 'league' => $player ['competition_name'], 
												 'minutes' => $statclub ['minutes'], 
												 'games_played' => $statclub ['appearences'], 
												 'substitute' => $statclub ['substitute_in'], 
												 'yellow_cards' => $statclub ['yellowcards'], 
												 'red_cards' => $statclub ['redcards'] 
									);   
									//$playerGoalkeeperSeasonStat->insert ( $playerStatArray );
									echo $xmlPlayer->name."--". $statclub ['season'] . "GOALKEEPER UPDATED <BR>";
									//Zend_Debug::dump($playerStatArray);
								}
							}
						}
				}
				echo "UPDATE DONE";
			}
		} 
	}

}
?>
