<?php
		//$stageid =  $this->_request->getParam ( 'stage', null );
		$event = new Event ( );
		//$date = new Zend_Date ('Aug 20, 2010', null, 'en_US');
		$date = new Zend_Date ();
        //Changed by JV from YYYY to yyyy - bug - 010110   
        $yesterday = $date->subDay(1);  
        $yesterday = $yesterday->toString ( 'yyyy-MM-dd' );
        $fechas[1] = $yesterday . " 00:00:00";
        $tomorrow = $date->addDay(2);  
        $tomorrow = $tomorrow->toString ( 'yyyy-MM-dd' );      
        //echo $tomorrow;      
        $fechas[5] = $tomorrow . " 23:59:59";                
	                                                        
        $matchObject = new Matchh ();                       
        $team = new Team();                                 
	    $matches = $event->getMatchesScoreboard ( $fechas ,null , $stageid );
		$urlGen = new SeoUrlGen();    
		foreach ( $matches as $match ) {
			
			$game_status = null;
            if($match['game_status'] == 'notstarted'){
               $game_status = 'Fixture';
            }else if($match['game_status'] == 'inprogress'){
               $game_status = 'Playing';
            }else if($match['game_status'] == 'finished'){
               $game_status = 'Played';
            }else if($match['game_status'] == 'cancelled'){
               $game_status = 'Cancelled';
            }else if($match['game_status'] == 'postponed'){
               $game_status = 'Postponed';
            }else {
               $game_status = 'Unknown'; 
            }
			
			$existTeamA = $team->fetchRow ( 'team_spocosy_id = ' . $match ['home_team_id'] );
            $existTeamB = $team->fetchRow ( 'team_spocosy_id = ' . $match ['away_team_id'] );
            $goalface_team_A_id = null;
            $goalface_team_B_id = null; 
            if ($existTeamA != null) {
            	$goalface_team_A_id = $existTeamA->team_id; 
            }
            if($existTeamB != null){
            	$goalface_team_B_id = $existTeamB->team_id;
            } 
			
			//verify if match exists in the DB
			$existmatch = $matchObject->fetchRow ( 'match_id_spocosy = ' . $match ['event_id'] );
			if($existmatch != null){
				self::$logger->debug('Match Exists:' . $match ['event_id'] );
				$matchFound = $matchObject->findMatchById ( $existmatch->match_id );
				$winner = '';
				if($matchFound != null && $matchFound[0]['match_status']!= 'Played'){
					if($match ['game_status'] == 'finished'){
						self::$logger->debug('Match Status:' . $match ['game_status'] );
						if ($match ['home_score'] > $match ['away_score']) {
							$winner = $goalface_team_A_id; //3
						} else if ($match ['home_score'] < $match ['away_score']) {
							$winner = $goalface_team_B_id;
						} else {
							$winner = 999; //draws
						}
						$matchUrl = $urlGen->getMatchPageUrl($matchFound [0]["competition_name"], $matchFound [0]["t1"], $matchFound [0]["t2"], $matchFound [0]["match_id"], true);
		                $matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
		                $matchscore =  $match ['home_score'] . " - " . $match ['away_score'] ;
		                $dateEvent = new Zend_Date($match['startdate']." ".$match['starttime'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
		            	$timeEvent = $dateEvent->addMinute (95);
						self::updateMatchResultActivity($matchFound, $match , $urlGen ,$matchUrl , $matchscore , $timeEvent);
						
					}
				}else {
					self::$logger->debug( "Match From spocosy: " . $match ['event_id']. " has problems with team ids:Team A ". ($existTeamA==null?$match ['home_team_id']:"") . "->Team B:" . ($existTeamA==null?$match ['away_team_id']:""));
				}
			}
			//Team A Result
			$teamAResult = $match ['home_score'] != '-' ? $match ['home_score'] : 0;
			//Team B Result
			$teamBResult = $match ['away_score'] != '-' ? $match ['away_score'] : 0;
			
			if ($existmatch != null) {
				//if exists Update
				echo 'Existe match:' . $match['event_id'] ."<br>";
				$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $match ['startdate'] . ' ' . $match ['starttime'] ) );
				$data = array ('match_date' => $match ['startdate'], //1
				'match_time' => $match ['starttime'], //2
				'match_date_time' => $datetime, 
        		'match_status' => $game_status, 
				'match_winner' => $winner, //3
				'team_a' => $goalface_team_A_id, 
        		'team_b' => $goalface_team_B_id, //4
				'fs_team_a' => $teamAResult, 
        		'fs_team_b' => $teamBResult, //5
				'season_id' => $match ['season_id'], 
				'round_id' => $match ['round_id'] );
				$matchObject->updateMatchScoposy ( $match ['event_id'], $data );
				//echo 'Updating Match: <strong>' . $match ['match_id'] . '</strong><br>';
			
			} else { //new match insert in the db
				//echo 'New match:<strong>' . $match['match_id'] ."</strong><br>";
				$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $match ['startdate'] . ' ' . $match ['starttime'] ) );
				$data = array ('match_id' => 'I' . $match ['event_id'],'match_id_spocosy' => $match ['event_id'] , 'country_id' => $match ['country_id'], 
				'competition_id' => $match ['competition_id'], 
				'match_date' => $match ['startdate'], //1
				'match_time' => $match ['starttime'], //2
				'match_date_time' => $datetime, 
        		'match_status' => $game_status, 
        		'match_winner' => $winner, //3
				'team_a' => $goalface_team_A_id, 
        		'team_b' => $goalface_team_B_id, //4
				'fs_team_a' => $teamAResult, 
        		'fs_team_b' => $teamBResult, //5
				'season_id' => $match ['season_id'], 
        		'round_id' => $match ['round_id'] );
				 if($match ['competition_id'] !=null){
				 	$matchObject->insert ( $data );
				 }
				 //Zend_Debug::dump($data);
				//echo 'Inserting Match: <strong>' . $match ['match_id'] . '</strong><br>';
			} //end if
		

		}
?>