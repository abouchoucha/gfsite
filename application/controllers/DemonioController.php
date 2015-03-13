<?php
require_once 'util/Constants.php';
require_once 'util/Common.php';
require_once 'scripts/seourlgen.php';
require_once 'GoalFaceController.php';

class DemonioController extends GoalFaceController {


	private static $host_domain;
	private static $server_host;

	public function init() {

		Zend_Loader::loadClass ('Zend_Debug');
		Zend_Loader::loadClass ('Player');
		Zend_Loader::loadClass ('Team');
		Zend_Loader::loadClass ('LeagueCompetition');
		Zend_Loader::loadClass ('PlayerSeason');
		Zend_Loader::loadClass ('TeamPlayerStats');
		Zend_Loader::loadClass ('TeamGoalieStats');
		Zend_Loader::loadClass ('Round');
		Zend_Loader::loadClass ('Stadium');
		Zend_Loader::loadClass ('TeamSeason');

		$config = Zend_Registry::get ( 'config' );
		self::$host_domain = $config->path->index->server->name;
		self::$server_host = $config->server->host;
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
	
	//Fetches Player Data from Feed
	private function getplayerfeed($id,$shortname) {
  
    $common = new Common ();
  	$date = new Zend_Date ();
		$today = $date->toString ( 'Y-MM-dd H:mm:ss' );
    $country = new Country ();
    // fetch player feed from provider
    $feedpath = 'soccerstats/player/' . $id;
    $player = parent::getGoalserveFeed($feedpath);
        
    
    foreach ($player->player as $xmlPlayer) {
      	$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->birthcountry . '"' );
  		$rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->nationality . '"' );
      	$birthdate = str_replace('/', '-', $xmlPlayer->birthdate);
      	$dob_date = date ( "Y-m-d", strtotime ($birthdate) );
      	$arr_height = explode ( " ", $xmlPlayer->height, 2 );
  		$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
  		$player_height = $arr_height [0];
  		$player_weight = $arr_weight [0];
  		if ($xmlPlayer->position == 'Attacker') {
  			$player_position = 'Forward';
  		} else {
  			$player_position = $xmlPlayer->position;
  		}
  		
  		//Load Data Player Array
      $dataPlayer = array ('player_id' => $xmlPlayer ['id'], //1
  			'player_firstname' => $xmlPlayer->firstname, //2
  			'player_middlename' => '',
  			'player_lastname' => $xmlPlayer->lastname, //3
  			'player_common_name' => $common->stripAccents($xmlPlayer->name),
  			'player_name_short' => $shortname,
  			'player_dob' => $dob_date, //4
  			'player_dob_city' => $xmlPlayer->birthplace,
  			'player_type' => 'player', //5
  			'player_country' => $rowBirthCountry ['country_id'], //5
  			'player_nationality' => $rowNationalityCountry ['country_id'], //5
  			'player_position' => $player_position, //5
  			'player_creation' => $today,
  			'player_height' => $player_height,
  			'player_weight' => $player_weight, 
      );
    }
        
    return $dataPlayer;
  }
  
	private function saveplayerimage($playerId) {
	  
	    $feedpath = 'soccerstats/player/' . $playerId;
	    $playerxml = parent::getGoalserveFeed($feedpath);
	    $string = $playerxml->player->image;
	    
	    if (self::$server_host == 'beta') {
			self::$server_host = 'goalfaceapp';
		}
			
	    if ($string != null || $string != '') {
			$filesize = strlen(base64_decode($string));
      		// create image only if incoming file size is greater than the size of the default "no image yet"
			if ($filesize > 8624) {
				$img = imagecreatefromstring(base64_decode($string));
				if($img != false)
				{
					imagejpeg($img, '/home/goalface/public_html/'. self::$server_host . '/public/images/players/'. $playerId .'.jpg');
				}
				imagedestroy($img);
				echo "Image for: " . $playerId . " Added<br>\n";
				//Zend_Debug::dump("Image for: " . $playerId . " Added<br>\n");
			}
		}			
	}
	

	public function indexAction() {
		$standing_country = $this->_request->getParam ( 'country', null );
		$feedpath = 'standings/'.$standing_country.'.xml';
        $xml = parent::getGoalserveFeed($feedpath);
		$teamdata = new Team ();

		
		foreach ($xml->tournament->team as $team) {
		    echo $team['id'] ."<BR>";
		}
	}


	//getteammapfromfixture/country/COUNTY_NAME_URL_FEED/league/COMPETITION_NAME_URL_FEED/season/SEASON_ID/stage/STAGE_ID/aggr/1 
    public function getteammapfromfixtureAction() {
      
      $teamdata = new Team ();
      $teamseason = new TeamSeason ();
  	  $country = $this->_request->getParam ( 'country', null );
  	  $competition = $this->_request->getParam ( 'league', null );
  	  $stage = $this->_request->getParam ( 'stage', null );
  	  $aggr = $this->_request->getParam ( 'aggr', 0 );
  	  $seasonId = $this->_request->getParam ( 'season', null );
  	  $competitionId = $this->_request->getParam ( 'competition', null );
  	  $feedpath = 'soccerfixtures/'.$country.'/'.$competition;
      $xml = parent::getGoalserveFeed($feedpath);

    
      if ($aggr == 1) {
          $match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage."']/aggregate");
      } else {
          $match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage."']");
      }
      
  		
  	  //$match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");
  	  //$match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']");
  	  //$match_aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage."']");
  
	  foreach($match_aggregate as $aggregate) { 
	  	
	  		echo $aggregate->match . "<BR>";

	  	    foreach ($aggregate->match as $match) {
	  	       $rowTeamLocal = $teamdata->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
	  	       $rowTeamVisitor = $teamdata->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
	  		     echo "UPDATE team SET team_gs_id = " . $match->localteam ['id']  ." WHERE team_id = ". $rowTeamLocal['team_id'] .";  ". $match->localteam['name'] . "<br>";
	  	       echo "UPDATE team SET team_gs_id = " . $match->visitorteam ['id'] ." WHERE team_id = ". $rowTeamVisitor['team_id'] .";  ". $match->visitorteam['name'] . "<br>";
	  	     }
	  	     
	  	     foreach ($aggregate->match as $match) {
	  	       $rowTeamLocal = $teamdata->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
	  	       $rowTeamVisitor = $teamdata->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
	  	       echo "http://www.goalface.com/demonio/updatesquad/league/".$competitionId."/team/".$rowTeamLocal['team_id']. "<br>";
	           echo "http://www.goalface.com/demonio/updatesquad/league/".$competitionId."/team/".$rowTeamVisitor['team_id']. "<br>";
	  	     }
	  	     
	  	    foreach ($aggregate->match as $match) {
	  	       $rowTeamLocal = $teamdata->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
	  	       $rowTeamVisitor = $teamdata->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
	  	       if ($rowTeamLocal) {
	  	       		$WhereTeamLocalSeasonExist= array('team_id = ?' => $rowTeamLocal['team_id'], 'season_id = ?' => $seasonId);
	  	       		$rowTeamLocalSeason = $teamseason->fetchRow ($WhereTeamLocalSeasonExist);
	  	       		if (!$rowTeamLocalSeason) {
	  	       			//echo 'team' . $rowTeamLocal['team_id'] . " local not on teamseason table<br>";
	  	       			echo "INSERT INTO teamseason VALUES(".$rowTeamLocal['team_id'].",".$seasonId.",0);<br>";
	  	       		}
	  	       }
	  	       if ($rowTeamVisitor) {
	  	       		$WhereTeamVisitorSeasonExist= array('team_id = ?' => $rowTeamVisitor['team_id'], 'season_id = ?' => $seasonId);
	  	       		$rowTeamVisitorSeason = $teamseason->fetchRow ($WhereTeamVisitorSeasonExist);
	  	       		if (!$rowTeamVisitorSeason) {
	  	       			//echo 'team' . $rowTeamVisitor['team_id'] . " visitor not on teamseason table<br>";
	  	       			echo "INSERT INTO teamseason VALUES(".$rowTeamVisitor['team_id'].",".$seasonId.",0);<br>";
	  	       		}
	  	       }
	  	     } 
	  	}

	}

	// Individual Player 
	public function getplayerimageAction() {
	    $player = new Player ();
	    $playerId = $this->_request->getParam ( 'player', null );
		//$rowplayers = $player->findAllPlayersForSearch(140000,142000);
		if (isset($playerId)) {
			$rowplayers = $player->findPlayerProfileDetails($playerId);
		}
		foreach ($rowplayers as $rowplayer) {
			$playerxml = parent::getGoalserveFeed('soccerstats/player/'.$rowplayer['player_id']);

			if ($playerxml != null || $playerxml != '') {
				$string = $playerxml->player->image;
				if ($string != null || $string != '') {
          		$filesize = strlen(base64_decode($string));
          		if ($filesize > 8624) {
            		$img = imagecreatefromstring(base64_decode($string));
  					if($img != false)
  					{
  						imagejpeg($img, '/home/goalface/public_html/staging/public/images/feedplayers/'. $rowplayer['player_id'].'.jpg');
  					  echo "image for player = ".$rowplayer['player_id']." saved<br><br>";
  					}
  					imagedestroy($img);
         		 }
				}
			}
		}
	}


 //demonio/updateplayerimages/league/33
 //demonio/updateplayerimages/league/33/team/2277
 
	public function updateplayerimagesAction() {
		$config = Zend_Registry::get( 'config' );
    $file = $config->path->log->playerstats;
     /***FILE LOG **/
    $logger = new Zend_Log();
    $writer = new Zend_Log_Writer_Stream($file);
    $logger->addWriter($writer);

		if (self::$server_host == 'beta') {
			self::$server_host = 'goalfaceapp';
		} elseif(self::$server_host == 'staging') {
      self::$server_host = 'staging';
    }

		$league = $this->_request->getParam ( 'league', null );
		$teamid = $this->_request->getParam ( 'team', null );

		$player = new Player ();
		$team = new Team();
		$teamplayer = new TeamPlayer ();;
		$teams = $team->getTeamsPerCompetitionParse($league,$teamid);
    foreach ($teams as $team) {
  	   $players = $teamplayer->findAllPlayersByTeam($team['team_id']);
        foreach ($players as $rowplayer) {
    			$image_file = '/home/goalface/public_html/'. self::$server_host .'/public/images/players/'. $rowplayer['player_id'].'.jpg';
    			//Show only players with no images in goalface
    			if (!file_exists($image_file)) {
    				echo "The image file ". $image_file. " does not exist for ". $rowplayer['player_id']."<BR>\n";
    	
    				$playerxml = parent::getGoalserveFeed('soccerstats/player/'.$rowplayer['player_id']);
    				if ($playerxml != null || $playerxml != '') {
    					$string = $playerxml->player->image;
    					if ($string != null || $string != '') {
    						$filesize = strlen(base64_decode($string));
    						// create image only if incoming file size is greater than the size of the default "no image yet"
    						if ($filesize > 8624) {
    							$img = imagecreatefromstring(base64_decode($string));
    							if($img != false)
    							{
    								imagejpeg($img, '/home/goalface/public_html/'. self::$server_host . '/public/images/players/'. $rowplayer['player_id'].'.jpg');
    								$logger->info("Image for player ".$rowplayer['player_id']. "(" .$rowplayer['player_id'] .") has been added.");
    								echo "Image added for player" . $rowplayer['player_id'] ."<BR>\n";
    							}
    							imagedestroy($img);
    						}
    					}
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

//http://staging.goalface.com/demonio/getteamsbyseason/country/bolivia/league/69/season/13141109

	public function getteamsbyseasonAction() {
	    $standing_country = $this->_request->getParam ( 'country', null );
	    $seasonid = $this->_request->getParam ( 'season', null );
	    $competitionId = $this->_request->getParam ( 'league', null );
		$feedpath = 'standings/'.$standing_country.'.xml';
      	$xml = parent::getGoalserveFeed($feedpath);
      	$teamdata = new Team ();

		foreach ($xml->tournament as $group) {
		 	foreach ($group->team as $team) {
    			$rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
	    		echo "UPDATE team SET team_gs_id = " . $team['id'] ." WHERE team_id = ". $rowTeam['team_id'] .";  ". $team[ 'name'] . "<br>";
		     }
		 }

		foreach ($xml->tournament as $group) {
		  	foreach ($group->team as $team) {
		     	$rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
		  		echo "INSERT INTO teamseason VALUES(".$rowTeam['team_id'].",".$seasonid.",0);<br>";
		     }
		}

		foreach ($xml->tournament as $group) {
		  	foreach ($group->team as $team) {
		    	$rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
		   		echo "http://www.goalface.com/demonio/updatesquad/league/".$competitionId."/team/".$rowTeam['team_id']. "<br>";
		     }
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
            											$playerSeasonStat->insert ( $playerStatArray );
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

	public function syncstageroundsactiveAction() {
		$insert_action_url = 'http://'. self::$host_domain .'/goalservetogoalface/insertmatches/';
		$league = new LeagueCompetition();
		$round = new Round();
		$rowleague = $league->getActiveCompetitions();
		foreach ($rowleague as $row) {
			$xml = parent::getGoalserveFeed($row['fixtures']);
			$fixtures = explode("/", $row['fixtures']);
			$fixture_country = $fixtures[1];
			$fixture_league = $fixtures[2];

			echo "<strong>". $fixtures[1] ." </strong><br>";
			echo $fixtures[2] ." ( ". $row['competition_id']  .") - season : (" . $row['season_id']. ")<br>";

			if ($xml != null) {

				//IMPORTANT - Only get child nodes of <stage><match> ..</match></stage> where it has content , exclude <stage></stage>
				$xml_stages = $xml->xpath('/results/tournament/stage[count(*)>0]');

				foreach ($xml_stages as $stage) {

					$round_row = $round->findLeagueSeasonRound($stage['stage_id']);

				 	if ($round_row == null) {

				 		echo  $stage['stage_id'] ." - " . $stage['name'] . " - " . $stage['round'] . " - Not in DB<br>";

				 		$stage_start_date = array();
				 		$stage_end_date = array();

				 		$matches = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match");
							if ($matches != null) {
								$xpath_start_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match[1]/@date";
					 			$stage_start_date = $xml->xpath($xpath_start_date);
					 			$xpath_end_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match[last()]/@date";
					 			$stage_end_date = $xml->xpath($xpath_end_date);
							} else {
								$weeks = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week");
								if ($weeks != null) {
									$xpath_start_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[1]/match[1]/@date";
									$stage_start_date = $xml->xpath($xpath_start_date);
									$xpath_end_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[last()]/match[last()]/@date";
									$stage_end_date = $xml->xpath($xpath_end_date);
								} else {

									$aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");
										if ($aggregate != null) {
											$xpath_start_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate[1]/match[1]/@date";
											$stage_start_date = $xml->xpath($xpath_start_date);
											$xpath_end_date = "/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate[last()]/match[last()]/@date";
											$stage_end_date = $xml->xpath($xpath_end_date);
										}

									}
							}



				 		$my_start_date = date ( "Y-m-d", strtotime ( $stage_start_date[0]['date'] ) );
				 		$my_end_date = date ( "Y-m-d", strtotime ( $stage_end_date[0]['date'] ) );


				 		// Insert new stage in round table
				 		$round_data = array(
				 			'round_id' => (string)$stage['stage_id'],
				 			'season_id' => $row['season_id'],
				 			'round_title' => (string)$stage['name'],
				 			'start_date' => $my_start_date,
				 			'end_date' => $my_end_date,
				 			'type' => $this->getRoundType($stage['name'],$stage['round']),
				 		);

				 		//insert new round
				 		//$round->insert($round_data);
				 		echo "round id : " . (string)$stage['stage_id'] . " Inserted<br>";

				 		// run insertmatches using round(stage_id) just inserterted

				 		$feed_url_fixtures = $insert_action_url ."country/" . $fixture_country . "/fixture/" . $fixture_league . "/stage/".$stage['stage_id'];
				 		//system("wget -O - ". $feed_url_fixtures);

				 		echo "matches inserted for stage: " .$stage['stage_id'] ."<br>";
				 		echo "===================================================<br><br>";

				 	} else {
				 		echo $stage['stage_id']  . " - Up to Date <br>";
				 	}

				}
			}
		}

	}

	private function getRoundType($stage_name,$stage_round) {

		if ($stage_round == 'Knock Out') {
			if($stage_name == 'Final') {
				$type = 'cup';
			} elseif ($stage_name == 'Semi-finals') {
				$type = 'cup';
			} elseif  ($stage_name == 'Quarter-finals') {
				$type = 'cup';
			} elseif  ($stage_name == '8th Finals') {
				$type = 'cup';
			} elseif ($stage_name == '3rd Place Final') {
				$type = 'cup';
			} elseif ($stage_name == '1st Round'){
				$type = 'cup1';
			} elseif ($stage_name == '2nd Round'){
				$type = 'cup1';
			} elseif ($stage_name == '3rd Round'){
				$type = 'cup1';
			} else {
				$type = 'cup';
			}

		} elseif ($stage_round == 'Group Stage') {

			$type = 'table';

		} else {
			//round = qualifying
			$type = 'cup1';
		}

		return $type;
	}


	public function getstadiumAction() {
		$team = new Team();
		$stadium = new Stadium();
		$row = $team->findAllTeamsMapped();
		//Zend_Debug::dump($row);
		foreach ($row as $rowteam) {
			$feedpathteam = 'soccerstats/team/'.$rowteam['team_gs_id'];
			$xmlteam = parent::getGoalserveFeed($feedpathteam);
			if ($xmlteam != null || $xmlteam != '') {

				if ($xmlteam->team['id'] == $rowteam['team_gs_id']) {

					foreach ($xmlteam->team as $xmlTeam) {

						// get image and decode to save it locally
						$string = $xmlTeam->venue_image;
						if ($string != "") {
        					$img = imagecreatefromstring(base64_decode($string));
        					if($img != false)
        					{
        					   imagejpeg($img, '/home/goalface/public_html/staging/public/images/venues/'. $xmlTeam->venue_id.'.jpg');
        					   echo "image for venue = ".$xmlTeam->venue_id." saved<br>";
        					}
        				}
        				// build data array
						$stadium_data = array(
				 			'stadium_id' => $xmlTeam->venue_id,
				 			//'stadium_name' => $xmlTeam->venue_name,
				 			'stadium_name' => htmlentities($xmlTeam->venue_name, ENT_QUOTES, 'UTF-8'),
				 			'stadium_attendance' =>$xmlTeam->venue_capacity,
				 			'stadium_country_id' => $rowteam['country_id'],
				 			'stadium_surface' => $xmlTeam->venue_surface,
				 			'stadium_team_id' => $rowteam['team_id'],
				 		);
						$stadium->insert($stadium_data);
						echo "record for venue = ".$xmlTeam->venue_id." saved<br><br>";
						//Zend_Debug::dump($stadium_data);
					}
				}
			}
		}
	}


	
 	public function updatenationalsquadAction() {

		$date = new Zend_Date ();
		$today = $date->toString ( 'Y-MM-dd H:mm:ss' );
		$teamplayer = new TeamPlayer ();
		$player = new Player ();
		$team = new Team ();
		$country = new Country ();
		$teamid = $this->_request->getParam ( 'team', null );
		$seasonid = $this->_request->getParam ( 'season', null );
		$rowTeam = $team->fetchRow ( 'team_id = ' . $teamid );
		echo "<strong>".$rowTeam['team_name']."</strong><br><br>";
		if ($rowTeam ['team_gs_id'] != null) {
			$xml = parent::getGoalserveFeed('soccerstats/team/'.$rowTeam ['team_gs_id']);
			foreach ( $xml->team as $teamxml ) {
				foreach ( $teamxml->squad->player as $playerxml ) {
					echo "INSERT INTO playerseason VALUES (". $playerxml ['id'] .",". $teamid ."," .$seasonid .");<br>";

					//check if player exists GoalFace DB
					$rowPlayer = $player->fetchRow ( 'player_id = ' . $playerxml ['id'] );
					// Player Doesnt' exist
					if ($rowPlayer == null) {
						echo "<a href=''>". $playerxml ['id'] . "</a><br>";
					}

				}
			}
		}
	}




	
	//Zend_Debug::dump($xml->team->squad);
	// updatesquad/league/7 - ALL teams from la liga (7))
 	// updatesquad/league/7/team/2017 - Only team id

	public function updatesquadAction() {
	  	
	  	$config = Zend_Registry::get( 'config' );
    	$file = $config->path->log->updatesquad;
    	/***FILE LOG **/
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream($file);
        $logger->addWriter($writer);
		$date = new Zend_Date ();
		$today = $date->toString ( 'Y-MM-dd H:mm:ss' );
		$team = new Team ();
		$teamplayer = new TeamPlayer ();
		$player = new Player ();
		$teamplayer = new TeamPlayer ();
		$country = new Country ();
		$teamid = $this->_request->getParam ( 'team', null );
		$leagueid = $this->_request->getParam ( 'league', null );
		$teams_league = $team->getTeamsPerCompetitionParse($leagueid,$teamid);
		$CurrentTeamPlayers = $teamplayer->findAllPlayersByTeam($teamid);
		// get all players current on team
		$player_team = array();
		foreach ($CurrentTeamPlayers as $teamplayers) {
			$player_team[] = $teamplayers['player_id'];
		}
		//Zend_Debug::dump($player_team);
		// iterate over all team on a league
		foreach($teams_league as $teamleague) {
			//get team information array
			$rowTeam = $team->fetchRow ( 'team_id = ' . $teamleague['team_id'] );
			if ($rowTeam ['team_gs_id'] != null)  {

				$feed_team_path = 'soccerstats/team/' . $rowTeam ['team_gs_id'];
				$player_feed_team = array();
   				$xml = parent::getGoalserveFeed($feed_team_path);
   				//Iterate over each player on squad from feed
   				foreach ( $xml->team->squad->player as $playersquad ) {
   					//put all squad from feed in array()
   					$player_feed_team[] = $playersquad['id'];
   					//check if player exists GoalFace DB
					$rowPlayer = $player->fetchRow ( 'player_id = ' . $playersquad ['id'] );
					// data array for team - relation
					$dataTeamPlayer = array ('player_id' => $playersquad ['id'],
		                                        'team_id' => $teamid, 
		                                        'actual_team' => '1' );	
					$dataTeamPlayerUpdate =  array ('team_id' => $teamid,'actual_team' => '1' );
					
					//Player Does not exist DB
					if ($rowPlayer == null) {
						//Add New player to GF
						//Assign player-team relation
						$player_short_name = $playersquad ['name'];
						$playersData = $this->getplayerfeed($playersquad ['id'],$player_short_name);
   						//Insert New Player
              			$player->insert ($playersData);
              			echo "------><strong>".$playersquad ['id']. " ". $playersquad ['name']." INSERTED into GoalFace DB</strong><br>\n";
              			//insert teamplayer relation
						        $teamplayer->insert($dataTeamPlayer ); 			
		              	echo "------><strong>".$playersquad ['id']. " ". $playersquad ['name']." INSERTED INSERT INTO " . $teamleague['team_id'] ." SQUAD</strong><br>\n";
						        //Save player image from feed if Exists
              			$this->saveplayerimage($playersquad ['id']);
					} else {

						//Player exists
						$currentteamsclub = $teamplayer->findCurrentTeamByType($playersquad['id'],'club');
            
						if ($currentteamsclub) {
						
							//Player has club team relations
							if (count($currentteamsclub) > 1) {
								//delete all relations and add correct one
								foreach ($currentteamsclub as $club) {	
                  	
									echo "Player" . $playersquad ['name']. "(" .$playersquad['id'] . ") deleted relation with team" . $club['team_id'] . "<BR>\n";
									$teamplayer->deleteTeamPlayer($club['team_id'],$playersquad['id']);
								}
								//Add new only one relation 
								echo "Added Player " . $playersquad ['name'] . "(" .$playersquad['id'] .") relation with team " . $teamid . "<BR>\n";
								$teamplayer->insert($dataTeamPlayer );

							} else {
								//check if relation is correct if not update 
								foreach ($currentteamsclub as $teamsclub) {
								  //Zend_Debug::dump($currentteamsclub);
									if ($teamsclub['team_id'] == $teamid && $teamsclub['actual_team'] == 1 ) {
										echo "Team relation for player " . $playersquad ['name']. "(" .$playersquad['id']. ") with team " . $teamid . " OK\n"; 
									} else {
										echo "Player " . $playersquad ['name'] . "(" .$playersquad['id']. ") updated TO team ". $teamid ." from " . $teamsclub['team_id']. " team.<BR>\n";
										$dataTeamPlayerUpdate =  array ('team_id' => $teamid,'actual_team' => '1' );								
			              $teamplayer->updateTeamPlayer($playersquad['id'],$teamsclub['team_id'],$dataTeamPlayerUpdate);
									}							
								}
							}
						} else {
							//Player has NO club relation 
							$teamplayer->insert($dataTeamPlayer );
							echo "Added for player " . $playersquad ['name'] . "(" .$playersquad['id']. ") new relation with team " . $teamid . "<BR>\n";
						}
					}
   				}
   				// Remove orphas from team GF DB - We have two arrays $player_feed_team and $player_team
   				$orphans = array_diff($player_team,$player_feed_team);
   				//Zend_Debug::dump($orphans);
   				echo "<b>Orphan Players</b><br>\n";
   				if(!empty($orphans)) {
   					foreach ($orphans as $playerorphan) {
   						$currentteamsclub = $teamplayer->findCurrentTeamByType($playerorphan,'club');
   						$teamplayer->deleteTeamPlayer($teamid,$playerorphan );
   						echo "Player '. $playerorphan.' removed from team relation<br>\n";
   						foreach ($currentteamsclub as $club) {		
							   echo "Player " . $playerorphan . " delete relation with team " . $club['team_id'] . "<BR>\n";
							   $teamplayer->deleteTeamPlayer($club['team_id'],$playerorphan);
  							 $feed_team_path = 'soccerstats/player/' . $playerorphan;
  	            			$xmlplayerfeed = parent::getGoalserveFeed($feed_team_path);
                  			if ($xmlplayerfeed != null || $xmlplayerfeed != '') {
                  				foreach ($xmlplayerfeed->player as $xmlPlayer) {
                  					if(!empty($xmlPlayer->teamid) AND  $xmlPlayer->teamid != null AND $xmlPlayer->teamid != 0) {
                  						$rowTeamNewCurrent = $team->fetchRow ( 'team_gs_id = ' . $xmlPlayer->teamid );
                  						if ($rowTeamNewCurrent != "") {
                  							$dataTeamPlayerNew = array ('player_id' => $playerorphan,
  		                                        'team_id' => $rowTeamNewCurrent['team_id'], 
  		                                        'actual_team' => '1' );	
                  							$teamplayer->insert($dataTeamPlayerNew);
                  							echo "New Team for Player " .$playerorphan . " is " . $rowTeamNewCurrent['team_id'] ."<br>\n";
                  						} else {
                  							echo "Team " . $xmlPlayer->teamid . " not Mapped!!<br>\n";
                  							$logger->info("Feed Team Not Mapped : " .$xmlPlayer->teamid );
                  						}
                  					} 
                  				}
                  			}
						  }
   					}
   				} else {
            echo "No Orphan Players<br><br>\n";
            return;
          }
			} 
		}	
	}

}
?>
