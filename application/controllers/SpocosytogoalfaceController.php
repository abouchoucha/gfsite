<?php
/**
 * SpocosyToGoalFaceController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
require_once 'scripts/seourlgen.php';

class SpocosytogoalfaceController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	private static $logger;
    
    public function init() {
        self::$logger = Zend_Registry::get("logger");
    }
    
	public function indexAction() {
		// TODO Auto-generated SpocosyToGoalFaceController::indexAction() default action
	}
	
	public function insertmatchesAction() {
		
		$stageid =  $this->_request->getParam ( 'stage', null );
		$matchid =  $this->_request->getParam ( 'match', null );
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
	    $matches = $event->getMatchesScoreboard ( $fechas ,'all' , $stageid, $matchid );
		$urlGen = new SeoUrlGen();    
		foreach ( $matches as $match ) {
			self::$logger->debug('Match is :' . $match['game_status'] . "->" . $match['status_text'] ."->" . $match ['event_id'] );
			$game_status = null;
            if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Not started'){
               $game_status = 'Fixture';
            }else if(trim($match['game_status']) == 'inprogress'){
               $game_status = 'Playing';
            }else if(trim($match['game_status']) == 'finished'){
               $game_status = 'Played';
            }else if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Cancelled'){
               $game_status = 'Cancelled';
            }else if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Postponed'){
               $game_status = 'Postponed';
            }else {
               $game_status = 'Unknown'; 
            }
			self::$logger->debug('Game Status is:' . $game_status);
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
			$winner = '';
			if($existmatch != null){
				self::$logger->debug('Match Exists:' . $match ['event_id'] );
				$matchFound = $matchObject->findMatchById ( $existmatch->match_id );
				
				if($matchFound != null){// && $matchFound[0]['match_status']!= 'Played'){
					if($match ['game_status'] == 'finished'){
						//self::$logger->debug('Match Status:' . $match ['game_status'] );
						if ($match ['home_score'] > $match ['away_score']) {
							$winner = $goalface_team_A_id; //3
						} else if ($match ['home_score'] < $match ['away_score']) {
							$winner = $goalface_team_B_id;
						} else {
							$winner = 999; //draws
						}
						//$matchUrl = $urlGen->getMatchPageUrl($matchFound [0]["competition_name"], $matchFound [0]["t1"], $matchFound [0]["t2"], $matchFound [0]["match_id"], true);
		                //$matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
		                //$matchscore =  $match ['home_score'] . " - " . $match ['away_score'] ;
		                //$dateEvent = new Zend_Date($match['startdate']." ".$match['starttime'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
		            	//$timeEvent = $dateEvent->addMinute (95);
						//self::updateMatchResultActivity($matchFound, $match , $urlGen ,$matchUrl , $matchscore , $timeEvent);
						
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
				self::$logger->debug('Updating Match: ' . $match ['event_id'] );
			
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
				 	self::$logger->debug('Inserting new Match: ' . $match ['event_id'] );
				 	$matchObject->insert ( $data );
				 }
				 //Zend_Debug::dump($data);
				//echo 'Inserting Match: <strong>' . $match ['match_id'] . '</strong><br>';
			} //end if
		

		}
	}
	
	
	public function insertmatcheventsAction() {
		try {
           	$s_mt = explode(" ",microtime());
           	date_default_timezone_set('Europe/Copenhagen');
			$date = new Zend_Date ();
			$stageid =  $this->_request->getParam ( 'stage', null );
			$matchid =  $this->_request->getParam ( 'match', null );
			$update =  $this->_request->getParam ( 'update', 'false' );
			$urlGen = new SeoUrlGen();
			//$date = new Zend_Date ('Sep	 19, 2010', null, 'en_US');
	        //Changed by JV from YYYY to yyyy - bug - 010110
	        //echo $newdate;
	        $fechas [] = null;
	        //$yesterday = $date->subDay(1);
	        $date->subHour(3); 
	        $beforedate = $date->toString ( 'yyyy-MM-dd HH:mm:ss' );
	        $date->addHour(5); 
	        $endDate = $date->toString ( 'yyyy-MM-dd HH:mm:ss' );
	        //echo $beforedate;
	        //echo "<br>" .$endDate;
	        $fechas[1] = $beforedate;
	        $fechas[5] = $endDate ; 
	        $team = new Team();    
			$matchEvent = new Event ( );
			$matchObject = new Matchh();
			$livematches = $matchEvent->getMatchesScoreboard ( $fechas ,'live' ,$stageid,$matchid );
			$goalface_team_A_id = null;
            $goalface_team_B_id = null; 
			foreach ( $livematches as $match ) {
				
				self::$logger->debug('Iterating over match id: ' . $match ['event_id'] . "");
				$game_status = null;
				if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Not started'){
	               $game_status = 'Fixture';
	            }else if(trim($match['game_status']) == 'inprogress'){
	               $game_status = 'Playing';
	            }else if(trim($match['game_status']) == 'finished'){
	               $game_status = 'Played';
	            }else if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Cancelled'){
	               $game_status = 'Cancelled';
	            }else if(trim($match['game_status']) == 'notstarted' && trim($match['status_text']) == 'Postponed'){
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
				$winner = '';
				$existmatch = $matchObject->fetchRow ( 'match_id_spocosy = ' . $match ['event_id'] );
				if($existmatch != null){
					self::$logger->debug('Match Exists:' . $match ['event_id'] );
					$matchFound = $matchObject->findMatchById ( $existmatch->match_id );
					
					if($matchFound != null){
						$matchUrl = $urlGen->getMatchPageUrl($matchFound [0]["competition_name"], $matchFound [0]["t1"], $matchFound [0]["t2"], $matchFound [0]["match_id"], true);
		                $matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
		                $matchscore =  $match ['home_score'] . " - " . $match ['away_score'] ;
		                $dateEvent = new Zend_Date($match['startdate']." ".$match['starttime'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
		                if($match ['game_status'] == 'inprogress'){
			                if ($matchFound [0] ["match_status"] == 'Fixture' or $matchFound [0] ["match_status"] == 'Postponed') {
								//insert a new event
								self::$logger->debug('Match is Fixture or Postponed:' . $match ['event_id'] );
								$variablesToReplace = array ('match_playing' => $matchTeams, 'match_url' => $matchUrl );
								$activityType = Constants::$_MATCH_STARTS_ACTIVITY;
								$activityMatch = new Activity ( );
								$activityType = Constants::$_MATCH_STARTS_ACTIVITY;
								$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, null, $dateEvent, null, $matchFound [0] ['match_id'] ); //for Team A
							}
		                }
						if($match ['game_status'] == 'finished'){
							self::$logger->debug('Match Status:' . $match ['game_status'] );
							if ($match ['home_score'] > $match ['away_score']) {
								$winner = $goalface_team_A_id; //3
							} else if ($match ['home_score'] < $match ['away_score']) {
								$winner = $goalface_team_B_id;
							} else {
								$winner = 999; //draws
							}
							
			                
			            	$timeEvent = $dateEvent->addMinute (95);
							if($matchFound[0]['match_status']!= 'Played'){
			            		self::updateMatchResultActivity($matchFound, $match , $urlGen ,$matchUrl , $matchscore , $timeEvent);
							}
						}
						
						//Update Match Events
			 
						self::updateTeamPlayer($match , $matchFound  , null ,$urlGen , $matchUrl , $matchTeams);
						
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
					//echo 'Existe match:' . $match['event_id'] ."<br>";
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
				}
			}
			
        } catch ( Exception $e ) {
            self::$logger->err("Caught exception: " . get_class ( $e ) . " ->" .$e->getMessage ());
            self::$logger->err($e->getTraceAsString() . "\n-----------------------------");
        }
        //echo 'Update Process Finished';
        //put this at the end of your php script
		$e_mt = explode(" ",microtime());
		$s = (($e_mt[1] + $e_mt[0]) - ($s_mt[1] + $s_mt[0]));
		echo "<br>script executed in ".$s." seconds";
		self::$logger->debug($s/60)."m ".($s%60)." s";
        //self::$logger->debug("ending Time: " . date("F j, Y, g:i a s"));
	}
	
	public function updateMatchResultActivity($matchFound,$match, $urlGen ,$matchUrl , $matchscore , $timeEvent) {
		
		//if ($matchFound [0] ["match_status"] == 'Played') {
			self::$logger->debug("Inserting Match Activity: ");
			$activityMatch = new Activity ();
			//Insert Activity for teams
			$teama_seoname = $urlGen->getClubMasterProfileUrl ( $matchFound [0] ["team_a"], $matchFound [0] ["t1seoname"], True );
			$teamb_seoname = $urlGen->getClubMasterProfileUrl ( $matchFound [0] ["team_b"], $matchFound [0] ["t2seoname"], True );
			$variablesToReplace = array (//'teama_seoname' => $teama_seoname,
			'teama_name' => $matchFound [0] ["t1"], //'teamb_seoname' => $teamb_seoname ,
			'teamb_name' => $matchFound [0] ["t2"], 'match_seoname' => $matchUrl, 'score' => $matchscore );
			$activityMatch = new Activity ();
			if ($match ['home_score'] > $match ['away_score']) { //team A WON
				$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
				$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';
				$activityType = Constants::$_MATCH_SCORE_TEAM_A_WON;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'] ); //for Team A
				$activityType = Constants::$_MATCH_SCORE_TEAM_B_LOST;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'] ); //for Team B
				self::$logger->debug ( 'Inserted Match A Won: ' );
			}
			if ($match ['home_score'] < $match ['away_score']) { //team B WON
				$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';
				$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
				$variablesToReplace = array (//'teama_seoname' => $teamb_seoname,
				'teama_name' => $matchFound [0] ["t2"], //'teamb_seoname' => $teama_seoname ,
				'teamb_name' => $matchFound [0] ["t1"], 'match_seoname' => $matchUrl, 'score' => $matchscore );
				$activityType = Constants::$_MATCH_SCORE_TEAM_B_LOST;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'] ); //for Team A
				$activityType = Constants::$_MATCH_SCORE_TEAM_A_WON;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'] ); //for Team B
				self::$logger->debug ( 'Inserted Match B Won: ' );
			}
			if ($match ['home_score'] == $match ['away_score']) { //Draw
				$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
				$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';
				$activityType = Constants::$_MATCH_SCORE_TEAM_DRAW;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'] ); //for Team A
				$variablesToReplace = array (//'teama_seoname' => $teamb_seoname,
				'teama_name' => $matchFound [0] ["t2"], //'teamb_seoname' => $teama_seoname ,
				'teamb_name' => $matchFound [0] ["t1"], 'match_seoname' => $matchUrl, 'score' => $matchscore );
				$activityType = Constants::$_MATCH_SCORE_TEAM_DRAW;
				$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'] ); //for Team B
				self::$logger->debug ( 'Inserted Draw Activity: ' );
			}
		//}
	
	}
	
	public function updateTeamPlayer($match , $matchFound ,$clientSoccer ,$urlGen ,$matchUrl , $matchTeams) {
        $teamplayer = new TeamPlayer();
        $matchEvent = new MatchEvent ( );
        $player_name_seo = null;
        $player_shortname = null;
        $eventMatch = new Event();
        $team = new Team();
        $player = new Player();
        $playerNotFound = new PlayerNotFound();
        //load the directory for player images
       
		
        //search lineups and incidentes per match
        $allLineUps = $eventMatch->getAllLineUps($match ['event_id']);
		
        foreach ( $allLineUps as $event ) { //continue label
            //self::$logger->debug($event);
            if($event['field_position'] > 0) { //it is a lineup
	            $playerteamid = $team->fetchRow ( 'team_spocosy_id = ' . $event ['team_id'] );
	            
            	if ($matchFound != null && $playerteamid !=null) {
	            	
	            	self::$logger->debug ('Searching player from scoposy with id: ' . $event ['team_id']);
	            	$playerid = $player->fetchRow ( 'player_spocosy_id = ' . $event ['player_id'] );
	                
	            	if($playerid != null){
	                	self::$logger->debug ('Found player: ' . $playerid->player_id . 'for player scoposy ' .$event ['player_id']);
	                	$playersPathName  = '/players/';
		            	$playerImageName = $player->getPlayerProfileImage($playerid->player_id);
		            	if($playerImageName !=null){
		            		$playersPathName .= $playerImageName[0]['imagefilename'];
		            	}else {
		            		$playersPathName = '/Player1Text.gif';
		            	}
	                	
	                }else {
	                	self::$logger->debug ('Player NOT FOUND for player scoposy: ' . $event ['player_id']);
	                	//insert player in new db and continue with next item
	                	//before inserting validate if it has not been inserted before
	                	$newPlayerInserted = $playerNotFound->fetchRow ( 'player_spocosy_id = ' . $event ['player_id'] );
	                	if($newPlayerInserted == null){
		                	$dataPlayerNew = array (
		                        'player_spocosy_id' => $event ['player_id'], //2
		                        'match_id' => $matchFound[0]['match_id'],
		                		'stage_id' => $match ['stage_id']
		                        );//
							$playerNotFound->insert($dataPlayerNew);
		                	self::$logger->debug ('Player Inserted in the table playernotfound: ' . $event ['player_id']);
	                	}
	                	continue ;
	                }	
	            	$event['player_id'] = $playerid->player_id;
	                
	            	$dataEvent = array ('event_id' => 'I' . $event ['id'], //1
	                        'player_id' => $playerid->player_id, //2
	                        'event_type_id' => 'L',
	                        'match_id' => $matchFound[0]['match_id'],
	                        'event_minute' => null,
	                        'team_id' => $playerteamid->team_id,
	            		    'jersey_number' => $event['shirt_number'],
	                        'time' => trim ( date('Y-m-d H:i:s',strtotime($event['date_event']))));//4
	
	                $existevent = $matchEvent->fetchRow ( "event_id = 'I" . $event ['id'] ."'");
					self::$logger->debug("Verifying event: "."event_id = 'I" . $event ['id'] ."'");
	                if ($existevent == null) {
	                	self::$logger->debug("Event: " . $event ['id'] ."does not exist in goalface DB.Starting insert..");
	                    self::$logger->debug ('Inserting event_id: ' . $event ['id'] . '->' . $dataEvent ['event_type_id']);
	                    //Zend_Debug::dump($dataEvent);
	                    $matchEvent->insert ( $dataEvent );
	                    
	                    //was extracted from lines 324 325
	                    $player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true ,$playerid["player_common_name"]);
	                    $player_shortname = $playerid ['player_name_short'];
	                    //echo "<hr>";
	                    $matchscore = " ( " . $match ['home_score'] . " - " . $match ['away_score'] . " ) ";
	                    $event['date_event'] = $match ['startdate'] ." ".$match ['starttime'];
	                    self::insertLineUpActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchFound[0]['match_id']);
	
	                } //end exist event null
	            } //end if matchevent is null
           }
        }
        //incidentes
		$allIncidents = $eventMatch->getAllIncidents($match ['event_id']);
		
        foreach ( $allIncidents as $event ) {
            //self::$logger->debug($event);
            
        	$incidentType = null;
        	if($event['name'] == 'Yellow card') {
        		$incidentType = 'YC'; 
        	}else if($event['name'] == 'Red card'){
        		$incidentType = 'RC';
        	}else if($event['name'] == 'Regular goal'){
        		$incidentType = 'G';
        	}else if($event['name'] == 'Assist'){
        		$incidentType = 'A';
        	}else if($event['name'] == 'Substitution in'){
        		$incidentType = 'SI';
        	}else if($event['name'] == 'Substitution out'){
        		$incidentType = 'SO';
        	}else if($event['name'] == 'Penalty'){
        		$incidentType = 'PG';
        	}else if($event['name'] == 'Missed penalty'){
        		$incidentType = 'PM';
        	}else if($event['name'] == 'Own goal'){
        		$incidentType = 'OG';
        	}else if($event['name'] == 'Yellow card 2'){
        		$incidentType = 'Y2C';
        	}else {
        		$incidentType = 'UNK'; 
        	}
        	$event['eventtype'] = $incidentType;

        	$playerteamid = $team->fetchRow ( 'team_spocosy_id = ' . $event ['team_id'] );
        	$playerid = $player->fetchRow ( 'player_spocosy_id = ' . $event ['player_id'] );
        	
            if($playerid != null){
                self::$logger->debug ('Found player: ' . $playerid->player_id . 'for player scoposy ' .$event ['player_id']);
                $playerImageName = $player->getPlayerProfileImage($playerid->player_id);
                $playersPathName  = '/players/';
	        	if($playerImageName !=null){
	            	$playersPathName .= $playerImageName[0]['imagefilename'];
	            }else {
	            	$playersPathName = '/Player1Text.gif';
	            }
            }else {
                self::$logger->debug ('Player NOT FOUND for player scoposy: ' . $event ['player_id']);
                //insert player in new db and continue with next item
            	//before inserting validate if it has not been inserted before
                $newPlayerInserted = $playerNotFound->fetchRow ( 'player_spocosy_id = ' . $event ['player_id'] );
                if($newPlayerInserted == null){
	                $dataPlayerNew = array (
	                        'player_spocosy_id' => $event ['player_id'], //2
	                        'match_id' => $matchFound[0]['match_id'],
	                		'stage_id' => $match ['stage_id']
	                        );//
					$playerNotFound->insert($dataPlayerNew);
	                self::$logger->debug ('Player Inserted in  table playernotfound: ' . $event ['player_id']);
                }
                continue ;
            }	
        	$event['player_id'] = $playerid->player_id;
        	
        	
            if ($matchFound != null && $playerteamid !=null) {
            	
            	$dateEvent = new Zend_Date($match['startdate']." ".$match['starttime'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
            	
            	//echo $dateEvent->toString ( 'yyyy-MM-dd HH:mm:ss');
            	//echo 'minutes added:' . $event ['game_minute'];
            	$timeEvent = $dateEvent->addMinute ( $event ['game_minute'] );
            	//Zend_Debug::dump($timeEvent);
            	//echo "<br>" .$timeEvent->toString ( 'yyyy-MM-dd HH:mm:ss');;
                $dataEvent = array ('event_id' => (string)'I' . $event ['id'], //1
                        'player_id' => $playerid->player_id, //2
                        'event_type_id' => $incidentType,
                        'match_id' => (string)$matchFound[0]['match_id'],
                        'event_minute' => $event ['game_minute'],
                        'team_id' => $playerteamid->team_id,
                		'jersey_number' => null,
                        'time' => trim ( date('Y-m-d H:i:s',strtotime($event['date_event']))));//4

                $existevent = $matchEvent->fetchRow ( "event_id = 'I" . $event ['id'] ."'");
				
                if ($existevent == null) {
                	$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true ,$playerid["player_common_name"]);
	                $player_shortname = $playerid ['player_name_short'];
                    self::$logger->debug ('Inserting event_id: ' . $event ['id'] . '->' . $dataEvent ['event_type_id']);
                   //Zend_Debug::dump($dataEvent);
                    $matchEvent->insert ( $dataEvent );
					$event['date_event'] = $timeEvent;
                    //echo "<hr>";
                    $matchscore = " ( " . $match ['home_score'] . " - " . $match ['away_score'] . " ) ";
                    self::insertGoalScoredActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchFound[0]['match_id']);
                    self::insertCardsActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchFound[0]['match_id']);
                    self::insertSubstitutionActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName, $matchFound[0]['match_id']);


                } //end exist event null
            } //end if matchevent is null
           
        }

    }
    
    public function insertLineUpActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName , $matchId) {

        	self::$logger->debug("Inserting Line Up: ". $event['id'] );
            $variablesToReplace = array ('player_name_seo' => $player_name_seo, 'player_name' => $player_shortname, 'player_id' => $event ['player_id'], 'match_playing' => $matchTeams, 'match_url' => $matchUrl, 'match_score' => $matchscore, 'event_minute' => null );
            //Zend_Debug::dump($variablesToReplace);
            $activityType = Constants::$_PLAYER_LINE_UP_ACTIVITY;
            $activityBlogComment = new Activity ( );
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null  ,0 ,$event ['player_id'] ,null ,$event['date_event'],$playersPathName ,$matchId );
	
    }
    
	public function insertGoalScoredActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchId) {

        if ($event ['eventtype'] == 'G' or $event ['eventtype'] == 'OG' or $event ['eventtype'] == 'PG') {
            if ($event ['eventtype'] == 'G') {
                $activityType = Constants::$_GOAL_SCORED_ACTIVITY;
            }
            if ($event ['eventtype'] == 'OG') {
                $activityType = Constants::$_OWNGOAL_SCORED_ACTIVITY;
            }
            if ($event ['eventtype'] == 'PG') {
                $activityType = Constants::$_PENALTY_SCORED_ACTIVITY;
            }

            $variablesToReplace = array ('player_name_seo' => $player_name_seo,
                    'player_name' => $player_shortname,
                    'player_id' => $event ['player_id'],
                    'match_playing' => $matchTeams,
                    'match_url' => $matchUrl,
                    'match_score' => $matchscore,
                    'event_minute' => $event ['game_minute'] );

            $activityBlogComment = new Activity ( );
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null , 1 , $event ['player_id'], null , $event['date_event'] ,$playersPathName ,$matchId);

        }
    }

    public function insertCardsActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchId) {

        if ($event ['eventtype'] == 'YC' or $event ['eventtype'] == 'RC' or $event ['eventtype'] == 'Y2C') {
            $activityType = null;
            if ($event ['eventtype'] == 'YC') {
                $activityType = Constants::$_YELLOW_CARD_ACTIVITY;
            }
            if ($event ['eventtype'] == 'RC') {
                $activityType = Constants::$_RED_CARD_ACTIVITY;
            }
            if ($event ['eventtype'] == 'Y2C') {
                $activityType = Constants::$_2ND_YELLOW_CARD_ACTIVITY;
            }

            $variablesToReplace = array ('player_name_seo' => $player_name_seo,
                    'player_name' => $player_shortname,
                    'player_id' => $event ['player_id'],
                    'match_playing' => $matchTeams,
                    'match_url' => $matchUrl,
                    'match_score' => $matchscore,
                    'event_minute' => $event ['game_minute'] );

            $activityBlogComment = new Activity ( );
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,0 ,$event ['player_id'], null ,$event['date_event'] ,$playersPathName ,$matchId);
        }
    }

    public function insertSubstitutionActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName , $matchId) {

        if ($event ['eventtype'] == 'SI' or $event ['eventtype'] == 'SO') {
            $activityType = null;
            if ($event ['eventtype'] == 'SI') {
                $activityType = Constants::$_SUBSTITUTE_IN_ACTIVITY;
            }
            if ($event ['eventtype'] == 'SO') {
                $activityType = Constants::$_SUBSTITUTE_OUT_ACTIVITY;
            }
            $variablesToReplace = array ('player_name_seo' => $player_name_seo, 'player_name' => $player_shortname, 'player_id' => $event ['player_id'], 'match_playing' => $matchTeams, 'match_url' => $matchUrl, 'match_score' => $matchscore, 'event_minute' => $event ['game_minute'] );
            $activityBlogComment = new Activity ( );
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,0 , $event ['player_id'] , null , $event['date_event'] ,$playersPathName ,$matchId);

        }
    }


   public function insertMatch($matches){
   	   $team = new Team();
   	   $matchObject = new Matchh();
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
				$winner = '';
				if($match ['status_text'] == 'Finished'){
					if ($match ['home_score'] > $match ['away_score']) {
						$winner = $goalface_team_A_id; //3
					} else if ($match ['home_score'] < $match ['away_score']) {
						$winner = $goalface_team_B_id;
					} else {
						$winner = 999; //draws
					}
				}
				//Team A Result
				$teamAResult = $match ['home_score'] != '-' ? $match ['home_score'] : 0;
				//Team B Result
				$teamBResult = $match ['away_score'] != '-' ? $match ['away_score'] : 0;
				
				if ($existmatch != null) {
					//if exists Update
					//echo 'Existe match:' . $match['event_id'] ."<br>";
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
					$data = array (
					'match_id' => 'I' . $match ['event_id'],
					'match_id_spocosy' => $match ['event_id'] , 
					'country_id' => $match ['country_id'], 
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
					 	try {
					 		$matchObject->insert ( $data );
					 		self::$logger->debug("Inserting Match: <strong>' . $match ['match_id'] . " );
					  	} catch ( Exception $e ) {
            				self::$logger->err("Caught exception: " . get_class ( $e ) . " ->" .$e->getMessage ());
            				self::$logger->err($e->getTraceAsString() . "\n-----------------------------");
			          	}
					 	
					 }
					 
					
				} //end if
		}
   	
   } 

   public function insertmatchesbyseasonAction(){
   		$event = new Event();
   		
   		//$seasons = $event->getAllSeasons();
   		//foreach ( $seasons as $season ) {
   			//$matchesBySeason = $event->selectAllMatchesBySeason($season['t_stage_id']);
   			$matchesBySeason = $event->selectAllMatchesBySeason(821290);
   			foreach ( $matchesBySeason as $match ) {
   				//insert match in goalface_db if not exists
   				self::insertMatch($matchesBySeason);
   			}
   		//}
   }

   
   
	//Inserts New player from spocosy Id
	public function insertplayerdetailsAction() {
		$participantid =  $this->_request->getParam ( 'id', null );
		$participant = new Participant();
		$player = new Player();
		$teamplayer = new TeamPlayer();
		$last_inserted_id = $player->lastInsertedId();
		$result = $participant->getPlayerDetailsAll($participantid);
		$resultTeams = $participant->getTeamsPlayerActive($participantid);
		//Zend_Debug::dump($resultTeams);
		if ($result != null) {
			
			$dataPlayer = array (
							'player_id' => $last_inserted_id + 1, 
							'player_spocosy_id' => $participantid,
                			'player_firstname' => $result [0]['firstname'],
							'player_middlename' => null,
                           	'player_lastname' => $result [0]['lastname'], 
                            'player_name_short' => (substr($result [0]['firstname'], 0, 1)."."." ".$result [0]['lastname']), 
                           	'player_dob' => $result [0]['dob'],
                           	'player_dob_city' => null,
                            'player_type' => 'player', 
                            'player_country' => $result [0]['country_id'], 
							'player_nationality' => $result [0]['country_id'], 
                           	'player_position' => $result [0]['position'], 
							'player_height' => $result [0]['height'], 
							'player_weight' => $result [0]['weight'], 
                           	'player_seoname' => $result [0]['firstname'] . $result [0]['lastname'], 
                          	'player_nickname' => $result [0]['firstname'], 
                          	'player_common_name' => ($result [0]['firstname'] ." ". $result [0]['lastname']) ,
                          	'player_creation' => trim ( date ( "Y-m-d H:i:s" ) ) 
			);
			
			//insert player table
			echo "Inserting New Player: ". ($last_inserted_id + 1) . " - " . $result [0]['firstname'] . $result [0]['lastname'];
			$player->insert($dataPlayer);
			echo "New Player Inserted: " .($last_inserted_id + 1) ."<BR>";
			//insert teamplayer table
			
			
			foreach($resultTeams as $data) {
				$dataTeamPlayer = array (
							'player_id' => $last_inserted_id + 1,	
							'team_id' => $data['team_id'],
							'actual_team' => '1',
							'start_date' => $data['date_from'],
							'end_date' => $data['date_to'],						
				);
				// inser team player relation
				echo "Inserting Team Player<BR>";
				$teamplayer->insert($dataTeamPlayer);
				echo "Finished Inserting Team Player";
			}

		}
		
	
	}   
   
   
 //Coming from spocosy team roaster
	public function updateteamplayerAction() {
		$config = Zend_Registry::get ( 'config' );
		$file = $config->path->log->teamplayer;
		/***FILE LOG **/
		$logger = new Zend_Log ();
		$writer = new Zend_Log_Writer_Stream ( $file );
		$logger->addWriter ( $writer );
		
		
		//$stageid =  $this->_request->getParam ( 'stage', null );
		$team_id = $this->_request->getParam ( 'team', null );
		//$playerId = null; //$this->_request->getParam ( 'player', null );		
		//$db = Zend_Registry::get ( 'db' );
		//$stmt = $db->query ( 'select player_spocosy_id from player where player_spocosy_id is not null and player_id = 147134' );
		//$results = $stmt->fetchAll ();
		$team = new Team ();
		$participant = new Participant ();
		$tp = new TeamPlayer ();
		$player = new Player ();
		
		$playersteam = $participant->getPlayersByTeam($team_id);
		Zend_Debug::dump($playersteam);
		
		/*`*/
		
//		$teamRow = $team->fetchRow( 'team_id = ' . $team_id );
//		
//		$teamSpocosyId = $teamRow->team_spocosy_id;
//		$playerTeamListSpocosy = $participant->getPlayersByTeam($teamSpocosyId);
//		//Zend_Debug::dump($playerTeamListSpocosy);
//		foreach ( $playerTeamListSpocosy as $teamplayer ) {
//			$playerRow = $player->fetchRow ( 'player_spocosy_id = ' . $teamplayer['participantFK'] );
//			$playerid = $playerRow['player_id'];
//			//Zend_Debug::dump($playerRow);
//			//if ($playerid != null) {
//				echo $teamplayer['participantFK']. "-" . $playerid . "<BR>";
//			//}
//		}
		
	}
	
public function updateplayercurrentstatsAction() {
		
		$config = Zend_Registry::get ( 'config' );
		$file = $config->path->log->playerstats;
		
		/***FILE LOG **/
		$logger = new Zend_Log ();
		$writer = new Zend_Log_Writer_Stream ( $file );
		$logger->addWriter ( $writer );
		
		// Spanish La liga season 2010-11 seasonid =  5451 
		//$teamId = (int)$this->_request->getParam('teamId', 0);
		$seasonId = ( int ) $this->_request->getParam ( 'season', 0 );
		
		//get all seasons per team
		$team = new Team ();
		$playerX = new Player ();
		$teamplayer = new TeamPlayer ();
		$season = new Season ();
		$playerCurrStat = new PlayerCurrentStats();
		$seasonRow = $season->fetchRow ( 'season_id = ' . $seasonId );
		//$playerCurrStat = new WorldCupPlayerStats ();
		$teamsbyseason = $team->selectTeamsBySeason ( $seasonId );
		Zend_Debug::dump($teamsbyseason);
		foreach ( $teamsbyseason as $teamseason ) {
			
			//get all players from team
			$playersList = $teamplayer->findAllPlayersByTeam ( $teamseason ['team_id'] );
			
			$logger->debug ( "Inserting Statistics for Team:" . $teamseason ['team_id'] );
			
			foreach ( $playersList as $player ) {
				
				$logger->debug ( "Inserting Statistics for Player:" . $player ['player_id'] . " and season:" . $seasonId );
				
				$gp = $playerX->getGamesTotalSeason ( $player ['player_id'], $seasonId );
				$gl = $playerX->getGoalsCurrentSeason ( $player ['player_id'], $seasonId );
				$yc = $playerX->getYellowCardsCurrentSeason ( $player ['player_id'], $seasonId );
				$rc = $playerX->getRedCardsCurrentSeason ( $player ['player_id'], $seasonId );
				$si = null; //$playerX->getGamesSubInCurrentSeason($player ['player_id'], $season['season_id']);
				$sout = null; //$playerX->getGameSubOutCurrentSeason($player ['player_id'], $season['season_id']);
				$gstarted = null; //$playerX->getGamesStartedCurrentSeason($player ['player_id'], $season['season_id']);
				

				$playerStat = array ('player_id' => $player ['player_id'], //1
									'team_id' => $teamseason ['team_id'], //2
									'season_id' => $seasonId, 
									'competition_id' => $seasonRow ['competition_id'], //'competition_format' => $season['format'],
									'gp' => $gp ['gamesTotal'], 
									'gl' => $gl ['goalsSeason'], 
									'yc' => $yc ['ycSeason'], 
									'rc' => $rc ['rcSeason'] )
									//'si' => $si,
									//'sout' => $sout,
									//'gstarted' => $gstarted,
				;
				
				//INSERT Stats
				//$playerCurrStat->insert ( $playerStat );
				//Zend_Debug::dump($playerStat);
				$logger->debug ( "Finish Inserting:" );
			
			}
		}
	
	}
	
	public function insertplayermatcheventsAction() {
		try {
           	$s_mt = explode(" ",microtime());
           	date_default_timezone_set('Europe/Copenhagen');
			$date = new Zend_Date ();
			$stageid =  $this->_request->getParam ( 'stage', null );
			$matchid =  $this->_request->getParam ( 'match', null );
			$urlGen = new SeoUrlGen();
			$fechas [] = null;
	       
	        $team = new Team();    
			$matchEvent = new Event ( );
			$matchObject = new Matchh();
			$livematches = $matchEvent->getMatchesScoreboard ( $fechas ,'live' ,$stageid ,$matchid);
			$goalface_team_A_id = null;
            $goalface_team_B_id = null; 
            //Zend_Debug::dump($matchid);
			foreach ( $livematches as $match ) {
				
				self::$logger->debug('Iterating over match id: ' . $match ['event_id'] . "");
				$game_status = null;
				$existmatch = $matchObject->fetchRow ( 'match_id_spocosy = ' . $match ['event_id'] );
				if($existmatch != null){
					self::$logger->debug('Match Exists:' . $match ['event_id'] );
					$matchFound = $matchObject->findMatchById ( $existmatch->match_id );

					if($matchFound != null){
						$matchUrl = $urlGen->getMatchPageUrl($matchFound [0]["competition_name"], $matchFound [0]["t1"], $matchFound [0]["t2"], $matchFound [0]["match_id"], true);
		                $matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
		                $matchscore =  $match ['home_score'] . " - " . $match ['away_score'] ;
		                //$dateEvent = new Zend_Date($match['startdate']." ".$match['starttime'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
						self::updateTeamPlayer($match , $matchFound  , null ,$urlGen , $matchUrl , $matchTeams);
					}
				}
			}	
        } catch ( Exception $e ) {
            self::$logger->err("Caught exception: " . get_class ( $e ) . " ->" .$e->getMessage ());
            self::$logger->err($e->getTraceAsString() . "\n-----------------------------");
        }
        //echo 'Update Process Finished';
        //put this at the end of your php script
		$e_mt = explode(" ",microtime());
		$s = (($e_mt[1] + $e_mt[0]) - ($s_mt[1] + $s_mt[0]));
		echo "<br>script executed in ".$s." seconds";
		self::$logger->debug($s/60)."m ".($s%60)." s";
        //self::$logger->debug("ending Time: " . date("F j, Y, g:i a s"));
	}
	

	
     
	
}
?>