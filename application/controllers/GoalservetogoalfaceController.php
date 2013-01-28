<?php
require_once 'util/Constants.php';
require_once 'scripts/seourlgen.php';
require_once 'GoalFaceController.php';
require_once 'util/config.php';

class GoalservetogoalfaceController extends GoalFaceController {
	
	private $curl = null;
	
	//USE LIVE
	//private $gsPath = "http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/";	
	
	//USE when Testing LOCALHOST
	private $gsPath = "http://test.goalface.com/";	
	
	private static $logger;
	
	public function init() {
		self::$logger = Zend_Registry::get ( "logger" );
		Zend_Loader::loadClass ( 'Matchh' );
		Zend_Loader::loadClass ( 'LeagueCompetition' );
		Zend_Loader::loadClass ( 'Player' );
		Zend_Loader::loadClass ( 'Team' );
		Zend_Loader::loadClass ( 'Country' );
		Zend_Loader::loadClass ( 'TeamPlayer' );
		Zend_Loader::loadClass ( 'MatchEvent' );
		Zend_Loader::loadClass ( 'Season' );
		Zend_Loader::loadClass ( 'TeamSeason' );
		Zend_Loader::loadClass ( 'Round' );
		Zend_Loader::loadClass ( 'Groupp' );
		Zend_Loader::loadClass ( 'Ranking' );
		Zend_Loader::loadClass ( 'Zend_Date' );
		Zend_Loader::loadClass ( 'Zend_Debug' );
		Zend_Loader::loadClass ( 'Zend_Log_Writer_Stream' );
		Zend_Loader::loadClass ( 'Activity' );
		Zend_Loader::loadClass ( 'ActivityType' );
		Zend_Loader::loadClass ( 'EventType' );
		Zend_Loader::loadClass ( 'PlayerActivity' );
		Zend_Loader::loadClass ( 'TeamPlayerStats' );
		//Zend_Loader::loadClass ( 'Standing' );
		
		parent::init ();
		$this->image_player =  $this->view->imageplayerpath;
	}

	/**
	 * @return get XML array from goalserve feed
	 */
	private function getgsPath() {
		return $this->gsPath;
	}
	
	private function setgsPath($gsPath) {
		$this->gsPath = $gsPath;
	}
	
	private function getgsfeed($pathextra) {
		# INSTANTIATE CURL.
		$this->curl = curl_init ();
		
		# CURL SETTINGS.
		$urlPath = $this->getgsPath () . $pathextra;
		
		//echo "Connecting to: " . $urlPath;
		
  		curl_setopt ( $this->curl, CURLOPT_URL, $urlPath );
  		curl_setopt ( $this->curl, CURLOPT_RETURNTRANSFER, 1 );
  		curl_setopt ( $this->curl, CURLOPT_CONNECTTIMEOUT, 0 );
  		
  		# GRAB THE XML FILE.
  		$xmlResult = curl_exec ( $this->curl );
  		
  		curl_close ( $this->curl );
  		
  		if($xmlResult != null){
  			# SET UP XML OBJECT.
  			$xml = new SimpleXMLElement ( $xmlResult );
  			return $xml;
  		}
  		//Zend_Debug::dump($result);
  		return null;
		
		
	}
	
	// 
	private function getdirectfeed($pathurl) {
		# INSTANTIATE CURL.
		$this->curl = curl_init ();
		echo "Connecting to: " . $pathurl;
		
		curl_setopt ( $this->curl, CURLOPT_URL, $pathurl );
		curl_setopt ( $this->curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $this->curl, CURLOPT_CONNECTTIMEOUT, 0 );
		
		# GRAB THE XML FILE.
		$xmlResult = curl_exec ( $this->curl );
		
		curl_close ( $this->curl );
		if($xmlResult != null){
			# SET UP XML OBJECT.
			$xml = new SimpleXMLElement ( $xmlResult );
			return $xml;
		}
		//Zend_Debug::dump($result);
		return null;
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

	/**
	 * @see validatematches()
	 * function for getlivematches()
	 */
	private function emailerror($competition_id,$match_fix_id) {
	    /*Send Mail alerting match problem*/
				$mail = new SendEmail();
				$mail->set_from('webmaster@goalface.com');
				$mail->set_to( 'kokovasquez@gmail.com');
				$mail->set_subject(' Goalserve feed parsing problem -- match...');
				$mail->set_template('matchparseproblem');
				$variablesToReplaceEmail = array ('competition' => $competition_id, 
															            'match_gs' => $match_fix_id );
				$mail->set_variablesToReplace($variablesToReplaceEmail);
				//$mail->sendMail();
}
	
	private function validateteams($competition_id,$match) {
	    $matchObject = new Matchh ();
	    $team = new Team();
	    $goalface_team_A_id = null;
			$goalface_team_B_id = null;
	    $existmatch = NULL;
	    $team_id_message = array();    
			//Match IS PARSED only if localteam and visitor ids are not empty and have a matching and valid team gf id			
			$existTeamA = $team->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
			$existTeamB = $team->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
				if ($existTeamA != null) {
					$goalface_team_A_id = $existTeamA->team_id;
				} 
				if ($existTeamB != null) {
					$goalface_team_B_id = $existTeamB->team_id;
				}
				if ($goalface_team_A_id == null || $goalface_team_B_id ==null) {
    				return FALSE;
				} else {
					 return  TRUE;
					
				    //verify if match exists in the DB
				    //$winner = '';
				    //$existmatch = $matchObject->fetchRow ( 'match_id_goalserve = ' . $match['fix_id'] );
				}

			
			//return an array of the match on the feed if exist or returns a null array
			//return $teamvalid;
	}
	
	
	public function indexAction() {
	
	}

	/**
	 * @see GoalserveXMLPuller::getlivematches()
	 * $match = array match from goalserve feed
	 * $matchgf = array match from GoalFace Database
	 */
	
	public function getlivematchesAction() {
		
	    try {
	    	//Use when LIVE feed xml
			//$xml = $this->getgsfeed ( 'soccernew/home' );
				
	    	//Use when Testing LOCALHOST
				//$xml = $this->getgsfeed ( '020512_home.xml' );
				$xml = $this->getgsfeed ( 'home_spain_1110.xml' );
				
				$date = new Zend_Date ();
				$today = $date->toString ( 'yyyy-MM-dd' );
				$gs_today = $date->toString ( 'dd.MM.YYY HH:mm:ss' );
				$urlGen = new SeoUrlGen ();
				$league = new LeagueCompetition ();
				$matchObject = new Matchh ();
				$team = new Team ();
				$homeScore = null;
				$scoreAway = null;
				$playerNotFound = new PlayerNotFound ();
				$matchEvent = new MatchEvent ();
				$playerModel = new Player ();
				$matchMinute = null;
				$flagEvent=false;
				
				foreach ( $xml->category as $competition ) {
					$row = $league->findCompetitionByGoalserveId ( $competition ['id'] );
					if ($row != null and $row['active'] == 1 ) { //Only Active competitions get IN
						if ($row ['competition_id'] == 7){ // USE for testing LOCALHOST
							echo "<br><strong>" . $row ['competition_id'] . " " . $row ['competition_name'] . "</strong><br>";
							foreach ( $competition->matches->match as $match ) {
								if ($match['fix_id'] != "") { 
									//if ($match ['fix_id'] == 937993) { //USE for testing LOCALHOST	
									if ($match->localteam ['id'] != "" AND $match->visitorteam ['id'] != "") {
										//Validates the match teams checks for team gs id and if match is on the database
										$teams_valid = $this->validateteams($row ['competition_id'],$match);
										if ($teams_valid === true) {
											//$existmatch = $matchObject->fetchRow ( 'match_id_goalserve = ' . $match['fix_id'] );
											$existmatch_id = 'G' . $match['fix_id'];
											//Validates the match , checks if match fix_id exists on the GOALFACE DB
											$matchFound = $matchObject->findMatchById ( $existmatch_id );
											if ($matchFound != null) {
												//PARSING CODE GOES HERE
											 	$homeScore = $match->localteam ['goals'];
											 	$scoreAway = $match->visitorteam ['goals'];
												if (strpos ( $match ['status'], ":" )) {
												 	$game_status = 'Fixture';	
												} else if ((is_numeric ( trim ( $match ['status'] ) ))) {
												 	$game_status = 'Playing';
												 	$matchMinute = $match ['status'];
												} else if (trim ( $match ['status'] ) == 'FT') {
												 	$game_status = 'Played';
												 	$matchMinute = '90';
												} else if (trim ( $match ['status'] ) == 'Postp.') {
												 	$game_status = 'Postponed';
												} else if (trim ( $match ['status'] ) == 'Cancl.') {
												 	$game_status = 'Postponed';
												} else if (trim ( $match ['status'] ) == 'HT') {
												 	$game_status = 'Playing';
												 	$matchMinute = '45';
												} else if (trim ( $match ['status'] ) == 'AET') {	
												 	$game_status = 'Played';
												  $matchMinute = '120';
												} else if (trim ( $match ['status'] ) == 'Pen.') {	
												 	$game_status = 'Played';
												 	$matchMinute = '120';
												} else {
												 	$game_status = 'Unknown';
												}
											 	echo "<br> PARSING VALID match: " . $match['fix_id'] . " status: " . $game_status . "<br>";
											 
												$matchUrl = $urlGen->getMatchPageUrl ( $matchFound [0] ["competition_name"], $matchFound [0] ["t1"], $matchFound [0] ["t2"], $matchFound [0] ["match_id"], true );
												$matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
												$matchscore = $homeScore . " - " . $scoreAway;
												$dateEvent = new Zend_Date ( $match['formatted_date'] . " " . $match['time'], 'dd.MM.yyyy HH:mm', null, new Zend_Locale ( 'en_US' ) );
												//Zend_Debug::dump($dateEvent);
											 
											 	//MATCH STATUS FROM FEED - PLAYING/PLAYED
												if ($game_status == 'Playing' || $game_status == 'Played' ){
													//CHECK STATUS in GoalFace DB if FIXTURE or POSTPONE change it to Playing next round skip
													if ($matchFound [0] ["match_status"] == 'Fixture' or $matchFound [0] ["match_status"] == 'Postponed') {
														//INSERT Match Start Event
														$variablesToReplace = array ('match_playing' => $matchTeams,
																					 'match_url' => $matchUrl );
														$activityMatch = new Activity ();
														$activityType = Constants::$_MATCH_STARTS_ACTIVITY;
														$activityMatch->insertUserActivityByActivityType ( $activityType,
																										  $variablesToReplace,
																										  null,
																										  '1', null,
																										  null,
																										  $dateEvent,
																										  null,
																										  $matchFound [0] ['match_id'],
																										  1);
														
													}
													// ITERATE and UPDATE match every time the cron runs in 5 minutes  
													echo  "Updating match at minute:" . $match ['status'] ."<BR>";
													//Team A Result
													$teamAResult = $homeScore != '?' ? $homeScore : 0;
													//Team B Result
													$teamBResult = $scoreAway != '?' ? $scoreAway : 0;
													$datetime = strftime ( '%d%m%Y%H%M', strtotime ( $match ['startdate'] . ' ' . $match ['starttime'] ) );
													$data = array (//'match_date' => $match ['startdate'], //1
																//'match_time' => $match ['starttime'], //2
																//'match_date_time' => $datetime, 
																'match_status' => $game_status, 
																//'match_winner' => $winner, //3
																//'team_a' => $goalface_team_A_id, 'team_b' => $goalface_team_B_id ,//4
																'fs_team_a' => $teamAResult, 
																'fs_team_b' => $teamBResult, //5
																'match_minute' => $matchMinute
																//'season_id' => $match ['season_id'], 
																//'round_id' => $match ['round_id'] 
															);
													//Zend_Debug::dump ( $data );
													$matchObject->updateMatchGoalServe ( $match ['fix_id'], $data );
													
													
												} elseif ( $game_status == 'Played') { //MATCH STATUS FROM FEED - PLAYED 
												 	if($matchFound [0] ["match_status"] == 'Playing') { //MATCH STATUS ON DATABAE  - PLAYING
														// match has ended
														/* ENTER CODE HERE */
														echo "Match has finished and add activity end match :". $match ['status'] ."<br>";
													}
												 	
												} else { //MATCH STATUS FROM FEED - FIXTURE
													//game still in fixture update match date time if necessary
													$mydate = date ( "Y-m-d H:i:s", strtotime ( $match ['date'] . " " . $match ['time'] ) );
													//get zend date based on feed date
													$zf_date = new Zend_Date ( $mydate, Zend_Date::ISO_8601 );
													//add 2 hrs to get the gmt +2 like enetpulse
													$gf_date = $zf_date->addHour ( 0 );
													//get new date and time
													$new_gf_date = $gf_date->toString ( 'yyyy-MM-dd' );
													$new_gf_time = $gf_date->toString ( 'HH:mm:ss' );
													// concatenate date + time for datetime field 
													$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $new_gf_date . ' ' . $new_gf_time ) );
													$newdatearray = array ('match_date' => $new_gf_date, //1
																			'match_time' => $new_gf_time, //2
																			'match_date_time' => $datetime 
																			//'match_status' => $game_status, 
																			//'match_winner' => $winner, //3
																			//'team_a' => $goalface_team_A_id, 'team_b' => $goalface_team_B_id ,//4
																			//'fs_team_a' => $teamAResult, 
																			//'fs_team_b' => $teamBResult, //5
																			//'match_minute' => $matchMinute
																			//'season_id' => $match ['season_id'], 
																			//'round_id' => $match ['round_id'] 
																	);
													Zend_Debug::dump($newdatearray);
													if ($matchFound [0] ["match_date"] != $new_gf_date || $matchFound [0] ["match_time"] != $new_gf_time) {	
														//$matchObject->updateMatchGoalServe ( $match ['fix_id'], $newdatearray );
													}
												}
											 
											 
											} else {
												// Match fix_id DOESN'T EXIST on GoalFace Database
												echo "<br> NOT PARSE INVALID match: " . $match ['fix_id'] . "<br>";
											}
										} else {
											//email error one the teams have an empty GoalFace ids
			  							$this->emailerror($row ['competition_id'],$match['fix_id']);
											echo "Match :" . $match['fix_id']  ." has a problem with GoalFace team Ids<br>";	
										}
									} else {
										//email error one the teams have an empty goalserve ids
										$this->emailerror($row ['competition_id'],$match['fix_id']);
										echo "Match :" . $match['fix_id']  ." has one or both team goalserve Ids empty<br>";
									}	
									//} //END Filter 1 match ONLY for testing LOCALHOST
								}
								echo "<br>------------------------------------<br>";
							} //END match	
						} // END Filter 1 competition ONLY for testing LOCALHOST
					} // //Only Active competitions get IN
				} // END competition
			} catch ( Exception $e ) {
				self::$logger->err ( "Caught GENERAL exception: " . get_class ( $e ) . " ->" . $e->getMessage () );
				self::$logger->err ( $e->getTraceAsString () . "\n-----------------------------" );
			}
	}
	
	
	private function insertBasicInfoPlayer($playerObject,$team_goalface_id) {
		
		self::$logger->debug ( "En insertBasicInfoPlayer para: ". $playerObject['id'] . " y teamId" . $team_goalface_id);
		$date = new Zend_Date ();
		$today = $date->toString ( 'Y-MM-dd H:mm:ss' );
		$teamplayer = new TeamPlayer ();
		$player = new Player ();
		$team = new Team();
		$teamPlayer = new TeamPlayer();
		$country = new Country ();
		
		$playerPosition = null;
		if ($playerObject['pos'] == "G") {
			$playerPosition = "Goalkeeper";
		}else if($playerObject['pos'] == "D"){
			$playerPosition = "Defender";
		}else if($playerObject['pos'] == "M"){
			$playerPosition = "Midfielder";
		}else if($playerObject['pos'] == "F"){
			$playerPosition = "Forward";
		}
	
		//<player number="4" name="Luisï¿½o" pos="D" id="147700"/>
		$dataPlayer = array ('player_id' => $playerObject['id'], //1
						'player_firstname' => $playerObject['name'], //2
						'player_middlename' => '',
						'player_lastname' => $playerObject['name'], //3
						'player_common_name' => $playerObject['name'],
						'player_name_short' => $playerObject['name'],
						'player_dob' => null, //4
						'player_dob_city' => null,
						'player_type' => 'player', //5
						'player_country' => null, //5
						'player_nationality' => null, //5
						'player_position' => $playerPosition, //5
						'player_creation' => $today,
						'player_height' => null,
						'player_weight' => null );
	
	
				$dataTeamPlayer = array ('player_id' => $playerObject['id'],
						'team_id' => $team_goalface_id,
						'actual_team' => '1',
						'jersey_number' => '',
						'start_date' => '',
						'end_date' => '' );
	
			
			$player->insert($dataPlayer);
			$teamPlayer->insert($dataTeamPlayer);
			self::$logger->debug ( "Player Basic Inserted Ok and updated teamPlayer: ". $playerObject['id'] . " y teamId" . $team_goalface_id);
			//find the new player and return it
			$player = new Player();
			$playerid = $player->fetchRow ( 'player_id = ' . $playerObject['id'] );
			return $playerid;
		
		//Zend_Debug::dump($dataPlayer);
	
	}
	
	private function insertNewPlayer($playerid,$team_goalface_id) {
		
		self::$logger->debug ( "En insertNewPlayer para: ". $playerid . " y teamId" . $team_goalface_id);
		$date = new Zend_Date ();
		$today = $date->toString ( 'Y-MM-dd H:mm:ss' );
		$teamplayer = new TeamPlayer ();
		$player = new Player ();
		$team = new Team();
		$teamPlayer = new TeamPlayer();
		$country = new Country ();
	
		//USER when LIVE
		$xml_player = $this->getgsfeed('soccerstats/player/'.$playerid);
		
		//USE when Testing LOCALHOST
		//$xml_player = $this->getdirectfeed('http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/player/'.$playerid);
		
		
		if ($xml_player != null || $xml_player != '') {
			foreach ( $xml_player->player as $xmlPlayer ) {
				$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->birthcountry . '"' );
				$rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->nationality . '"' );
				$mydate = date ( "Y-m-d", strtotime ( $xmlPlayer->birthdate ) );
				$arr_height = explode ( " ", $xmlPlayer->height, 2 );
				$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
				$player_height = $arr_height [0];
				$player_weight = $arr_weight [0];
				if ($xmlPlayer->position == "Attacker") {
					$xmlPlayer->position = "Forward";
				}
	
				$dataPlayer = array ('player_id' => $playerid, //1
						'player_firstname' => $xmlPlayer->firstname, //2
						'player_middlename' => '',
						'player_lastname' => $xmlPlayer->lastname, //3
						'player_common_name' => $xmlPlayer->name,
						'player_name_short' => (substr ( $xmlPlayer->firstname, 0, 1 ) . "." . " " . $xmlPlayer->lastname),
						'player_dob' => $mydate, //4
						'player_dob_city' => $xmlPlayer->birtplace,
						'player_type' => 'player', //5
						'player_country' => $rowBirthCountry ['country_id'], //5
						'player_nationality' => $rowNationalityCountry ['country_id'], //5
						'player_position' => $xmlPlayer->position, //5
						'player_creation' => $today,
						'player_height' => $player_height,
						'player_weight' => $player_weight );
	
	
				$dataTeamPlayer = array ('player_id' => $playerid,
						'team_id' => $team_goalface_id,
						'actual_team' => '1',
						'jersey_number' => '',
						'start_date' => '',
						'end_date' => '' );
	
			}
			$player->insert($dataPlayer);
			$teamPlayer->insert($dataTeamPlayer);
			self::$logger->debug ( "Player Inserted Ok and updated teamPlayer: ". $playerid . " y teamId" . $team_goalface_id);
			//find the new player and return it
			$player = new Player();
			$playerid = $player->fetchRow ( 'player_id = ' . $playerid );
			return $playerid;
		}
		return null;
		//Zend_Debug::dump($dataPlayer);
	
	}
	
	private function insertLineUpActivity($event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $matchId,$alert) {
		
		self::$logger->debug ( "Inserting Line Up: " . $event ['id'] );
		$variablesToReplace = array ('player_name_seo' => $player_name_seo, 
									'player_name' => $player_shortname, 
									'player_id' => $event ['player_id'], 
									'match_playing' => $matchTeams, 
									'match_url' => $matchUrl, 
									'match_score' => $matchscore, 
									'event_minute' => null );
		//Zend_Debug::dump($variablesToReplace);
		$activityType = Constants::$_PLAYER_LINE_UP_ACTIVITY;
		$activityBlogComment = new Activity ();
		$activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, 0, $event ['player_id'], null, $event ['date_event'], $playersPathName, $matchId,$alert );
	
	}
	
   	private function insertSubstitutionActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName , $matchId,$alert) {

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
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,0 , $event ['player_id'] , null , $event['date_event'] ,$playersPathName ,$matchId, $alert);

        }
    }
    
	private function insertGoalScoredActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchId,$alert) {

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
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null , 1 , $event ['player_id'], null , $event['date_event'] ,$playersPathName ,$matchId, $alert);

        }
    }

    private function insertCardsActivity($event ,$player_name_seo ,$player_shortname ,$matchTeams ,$matchUrl ,$matchscore ,$playersPathName ,$matchId) {

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
            $activityBlogComment->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null ,0 ,$event ['player_id'], null ,$event['date_event'] ,$playersPathName ,$matchId,1);
        }
    }
	
	private function updateMatchResultActivity($matchFound, $homeScore, $awayScore, $urlGen, $matchUrl, $matchscore, $timeEvent,  $competition_id=null) {
		
		//if ($matchFound [0] ["match_status"] == 'Played') {
		self::$logger->debug ( "En updateMatchResultActivity" );
		$activityMatch = new Activity ();
		//Insert Activity for teams
		//$teama_seoname = $urlGen->getClubMasterProfileUrl ( $matchFound [0] ["team_a"], $matchFound [0] ["t1seoname"], True );
		//$teamb_seoname = $urlGen->getClubMasterProfileUrl ( $matchFound [0] ["team_b"], $matchFound [0] ["t2seoname"], True );
		/**/
		$variablesToReplace = array (//'teama_seoname' => $teama_seoname,
			'teama_name' => $matchFound [0] ["t1"], //'teamb_seoname' => $teamb_seoname ,
			'teamb_name' => $matchFound [0] ["t2"], 
			'match_seoname' => $matchUrl, 
			'score' => $matchscore );
		$variablesToReplaceAlter = array (
			'teama_name' => $matchFound [0] ["t2"],
			'teamb_name' => $matchFound [0] ["t1"], 
			'match_seoname' => $matchUrl, 
			'score' => $matchscore );
		
		/**/
		$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
		$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';
			
		$activityMatch = new Activity ();
		
		if ($homeScore > $awayScore) { //team A WON
			/*$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
			$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';*/
			$activityType = Constants::$_MATCH_SCORE_TEAM_A_WON;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'],1,$competition_id); //for Team A
			
			$activityType = Constants::$_MATCH_SCORE_TEAM_B_LOST;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplaceAlter, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'],1,null); //for Team B
			self::$logger->debug ( 'Inserted Match A Won: ' );
		}else if ($homeScore < $awayScore) { //team B WON
			/*$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';
			$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';*/
			/*$variablesToReplace = array (//'teama_seoname' => $teamb_seoname,
				'teama_name' => $matchFound [0] ["t2"], //'teamb_seoname' => $teama_seoname ,
				'teamb_name' => $matchFound [0] ["t1"], 'match_seoname' => $matchUrl, 'score' => $matchscore );*/
			$activityType = Constants::$_MATCH_SCORE_TEAM_B_LOST;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'],1,$competition_id); //for Team A
			$activityType = Constants::$_MATCH_SCORE_TEAM_A_WON;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplaceAlter, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'],1,null); //for Team B
			self::$logger->debug ( 'Inserted Match B Won: ' );
		}else if ($homeScore == $awayScore) { //Draw
			/*$teamWinnerPathName = '/teamlogos/' . $matchFound [0] ["team_a"] . '.gif';
			$teamLosserPathName = '/teamlogos/' . $matchFound [0] ["team_b"] . '.gif';*/
			$activityType = Constants::$_MATCH_SCORE_TEAM_DRAW;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplace, null, '1', null, $matchFound [0] ['team_a'], $timeEvent, $teamWinnerPathName, $matchFound [0] ['match_id'],1,$competition_id); //for Team A
			/*$variablesToReplace = array (//'teama_seoname' => $teamb_seoname,
				'teama_name' => $matchFound [0] ["t2"], //'teamb_seoname' => $teama_seoname ,
				'teamb_name' => $matchFound [0] ["t1"], 'match_seoname' => $matchUrl, 'score' => $matchscore );*/
			$activityType = Constants::$_MATCH_SCORE_TEAM_DRAW;
			$activityMatch->insertUserActivityByActivityType ( $activityType, $variablesToReplaceAlter, null, '0', null, $matchFound [0] ['team_b'], $timeEvent, $teamLosserPathName, $matchFound [0] ['match_id'],1,null); //for Team B
			self::$logger->debug ( 'Inserted Draw Activity: ' );
		}
	
		//}
	

	}
	
	private function insertMatchEvents($match, $matchFound, $clientSoccer, $urlGen, $matchUrl, $matchTeams , $isLive = null) {
		
		self::$logger->debug ( 'En insertMatchEvents ----->' );
		
		$teamplayer = new TeamPlayer ();
		$matchEvent = new MatchEvent ();
		$player_name_seo = null;
		$player_shortname = null;
		//$eventMatch = new Event ();
		$team = new Team ();
		$player = new Player ();
		$playerNotFound = new PlayerNotFound ();
		
		
		$localTeamId = $match->localteam ['id'];
		$visitorTeamId = $match->visitorteam ['id'];
		
		//Zend_Debug::dump($match->events->event);
		foreach ( $match->events->event as $event ) {
			//self::$logger->debug($event);
			try{
				$incidentType = null;
				if ($event ['type'] == 'yellowcard') {
					$incidentType = 'YC';
				} else if ($event ['type'] == 'redcard') {
					$incidentType = 'RC';
				} else if ($event ['type'] == 'goal') {
					$incidentType = 'G';
				} else if ($event ['type'] == 'Assist') {
					$incidentType = 'A';
				} else if ($event ['type'] == 'Substitution in') {
					$incidentType = 'SI';
				} else if ($event ['type'] == 'Substitution out') {
					$incidentType = 'SO';
				} else if ($event ['type'] == 'Penalty') {
					$incidentType = 'PG';
				} else if ($event ['type'] == 'Missed penalty') {
					$incidentType = 'PM';
				} else if ($event ['type'] == 'Own goal') {
					$incidentType = 'OG';
				} else if ($event ['type'] == 'yellowred') {
					$incidentType = 'Y2C';
				} else {
					$incidentType = 'UNK';
				}
				$event ['eventtype'] = $incidentType;
				
					
				if($event ['team'] == 'visitorteam'){
					$teamId = $visitorTeamId;
				}else {
					$teamId = $localTeamId;
				}	 
				if($event['type'] == 'yellowred'){
					$event['type'] = 'redcard';
				}
				
				$playerteamid = $team->fetchRow ( 'team_gs_id = ' . $teamId );
				
				if($event ['playerId'] == '' and $match ['commentary_available'] != ""){ // por ahora si viene un playerId en blanco q no haga nada, para sacar mas adelante
					
					$xmlComentary = $this->getgsfeed ( 'commentaries/' . $match ['commentary_available'] . ".xml" );
					//$xmlComentary = $this->getgsfeed ( 'http://test.goalface.com/commentaries/brazil_a_90.xml' );
					
					//was looking x id, was replaced to look x fix_id - JV 11-08-11
					$xyz = "//match[@id='".$match['fix_id']."']/summary/".$event['team']."/".$event['type']."s/player[@minute='".$event['minute']."']";
					echo 'Searching:' . $xyz;
					self::$logger->debug ( 'Searching : ' . $xyz );
					
					// Player id not found on home live feed , look for it on commentary based on team, event and minute
					// changed to look x fix_id instead of id jv 11-08-11
					$card = $xmlComentary->xpath("//match[@id='".$match['fix_id']."']/summary/".$event['team']."/".$event['type']."s/player[@minute='".$event['minute']."']");
					//Zend_Debug::dump($card[0]);
					if($card != null){
						$event ['playerId'] = $card[0]['id'];
						self::$logger->debug ( 'Player ID missing on live feed, was found on commentary feed : ' .$card[0]['id'] );
					} else {
						// look for player id on event time minus 1 minute - JV 11-08-11
						$event['minute'] = $event['minute'] - 1;
						$cardminusone = $xmlComentary->xpath("//match[@id='".$match['fix_id']."']/summary/".$event['team']."/".$event['type']."s/player[@minute='".$event['minute']."']");
						$event ['playerId'] = $cardminusone[0]['id'];
						self::$logger->debug ( 'Player ID missing on live found on live commentary minus 1 minute: ' .$card[0]['id'] );
					} 
					
				}
				
				self::$logger->debug ( 'Searching player: ' . $event ['playerId'] . ' for player goalserve ' . $event ['playerId'] );
				$playerid = $player->fetchRow ( 'player_id = ' . $event ['playerId'] );
					$playersPathName = '';
					if ($playerid != null) {
						self::$logger->debug ( 'Found player: ' . $playerid->player_id . ' for player goalserve ' . $event ['playerId'] );
						$playerImageName = $player->getPlayerProfileImage ( $playerid->player_id );
						$playersPathName = $this->getplayerimage($playerid->player_id);
					} else {
						self::$logger->debug ( 'Player NOT FOUND for playerId: ' .$event ['playerId'] );
						$playerid = self::insertNewPlayer($event ['playerId'] , $playerteamid->team_id);
						if($playerid == null){
							//insert a player with minimal data
							$playerid = self::insertBasicInfoPlayer($event, $playerteamid->team_id);
						}
					}
					$event ['player_id'] = $playerid->player_id;
					//echo "PlayerTeamId " . $playerid->player_id;
					if ($matchFound != null && $playerteamid != null) {
						
						$dateEvent = new Zend_Date ( $match ['formatted_date'] . " " . $match ['time'], 'dd.MM.yyyy HH:mm', null, new Zend_Locale ( 'en_US' ) );
					
						$event ['date_event'] =  $dateEvent->toString('yyyy-MM-dd HH:mm:ss');
						//echo "Event Date: " . $event ['date_event'] . "<br>";
						$eventDate = $event ['date_event']; 
						if($isLive != null){
							$eventDate =  trim ( date('Y-m-d H:i:s',strtotime($event ['date_event'])));
						}
						
						$dataEvent = array ('event_id' => ( string ) 'G' . $event ['eventid'], //1
											'player_id' => $playerid->player_id, //2
											'event_type_id' => $incidentType, 
											'match_id' => ( string ) $matchFound [0] ['match_id'], 
											'event_minute' => $event ['minute'], 'team_id' => $playerteamid->team_id, 
											'jersey_number' => null, 
											'time' => $eventDate ); //4 

						$existevent = $matchEvent->fetchRow ( "event_id = 'G" . $event ['eventid'] . "'" );
						
						
						if ($existevent == null) {
							$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
							$player_shortname = $playerid ['player_name_short'];
							self::$logger->debug ( 'Inserting event_id: ' . $event ['eventid'] . '->' . $dataEvent ['event_type_id'] );
							
							//Insert Events from Home Live Feed - If event type is "G" and player position Goalkeeper" Skip
							if($dataEvent['event_type_id']=='G') {
								if ($playerid ['player_position'] != 'Goalkeeper') {
									$matchEvent->insert ( $dataEvent );
								}
							} else {
								$matchEvent->insert ( $dataEvent );
							}

							$matchscore =  $event['result'];
							if ($playerid ['player_position'] != 'Goalkeeper') {
								//Insert Activities
								self::insertGoalScoredActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $matchFound [0] ['match_id'],1);
							}
							self::insertCardsActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $matchFound [0] ['match_id'],1);
						} else {
							self::$logger->debug ( 'Event: ' . "G" . $event ['eventid'] . '->' . $dataEvent ['event_type_id'] . " already exists in DB");
							
						}//end exist event null
					} //end if matchevent is null
			
			} catch ( Exception $e ) {
			self::$logger->err ( "Caught MATCH EVENT exception: " . get_class ( $e ) . " ->" . $e->getMessage () );
			self::$logger->err ( $e->getTraceAsString () . "\n-----------------------------" );
		 }
		}
	}
	
	
	public function updatematcheventsAction(){
		
		try {
			//get live feed xml
			//$xml = $this->getgsfeed ( 'http://test.goalface.com/livedata_90.xml' );
			$xml = $this->getgsfeed ( 'soccernew/eurocups');
			$matchDate = "02.11.2011";
			
			$date = new Zend_Date ();
			$today = $date->toString ( 'yyyy-MM-dd' );
			$gs_today = $date->toString ( 'dd.MM.YYY HH:mm:ss' );
			echo "<br>" . $gs_today; 
			
			$urlGen = new SeoUrlGen ();
			$league = new LeagueCompetition ();
			$matchObject = new Matchh ();
			$team = new Team ();
			$homeScore = null;
			$scoreAway = null;
			$playerNotFound = new PlayerNotFound ();
			$matchEvent = new MatchEvent ();
			$playerModel = new Player ();
			$matchMinute = null;
			
			
			$matches = $xml->xpath("//match[@formatted_date='".$matchDate."']");
			
			foreach ( $matches as $match ) {
				
				//if($match['fix_id'] == '863190'){
				
				echo $match['fix_id'] . "<br>";
				$existmatch = $matchObject->fetchRow ( 'match_id_goalserve = ' . $match ['fix_id'] );
							
				if ($existmatch != null) {
					self::$logger->debug ( 'Match Exists:' . $match ['fix_id'] );
					$matchFound = $matchObject->findMatchById ( $existmatch->match_id );
					$matchUrl = $urlGen->getMatchPageUrl ( $matchFound [0] ["competition_name"], $matchFound [0] ["t1"], $matchFound [0] ["t2"], $matchFound [0] ["match_id"], true );
					$matchTeams = $matchFound [0] ["t1"] . " vs " . $matchFound [0] ["t2"];
					$matchscore = $homeScore . " - " . $scoreAway;
					$dateEvent = new Zend_Date ( $match ['formatted_date'] . " " . $match ['time'], 'dd.MM.yyyy HH:mm', null, new Zend_Locale ( 'en_US' ) );
					echo "<br>" . $dateEvent->toString();
					$existTeamA = $team->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
					$existTeamB = $team->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
					$goalface_team_A_id = null;
					$goalface_team_B_id = null;
					if ($existTeamA != null) {
						$goalface_team_A_id = $existTeamA->team_id;
					}
					if ($existTeamB != null) {
						$goalface_team_B_id = $existTeamB->team_id;
					}
						
					$homeScore = $match->localteam['goals'];
					$scoreAway = $match->visitorteam['goals'];
					if ($homeScore > $scoreAway) {
						$winner = $goalface_team_A_id; //3
					} else if ($homeScore < $scoreAway) {
						$winner = $goalface_team_B_id;
					} else {
						$winner = 999; //draws
					}
					
					//GOAL EVENTS
					self::insertMatchEvents ( $match, $matchFound, null, $urlGen, $matchUrl, $matchTeams , true);
					

				//1. UPDATE MATCH
					self::$logger->debug ( "Match Exists: Updating Match:" .$match ['fix_id'] . " to Played");
					//$datetime = strftime ( '%d%m%Y%H%M', strtotime ( $match ['startdate'] . ' ' . $match ['starttime'] ) );
					$data = array (			    //'match_date' => $match ['startdate'], //1
												//'match_time' => $match ['starttime'], //2
												//'match_date_time' => $datetime, 
												'match_status' => "Played", 
												'match_winner' => $winner, //3
												'fs_team_a' => $homeScore, 
												'fs_team_b' => $scoreAway, //5
												'match_minute' => '90'
												 
												);
					$matchObject->updateMatchGoalServe ( $match ['fix_id'], $data );	
				}							
			//}
			}	
			
			
			
		} catch ( Exception $e ) {
			self::$logger->err ( "Caught MATCH EVENT exception: " . get_class ( $e ) . " ->" . $e->getMessage () );
			self::$logger->err ( $e->getTraceAsString () . "\n-----------------------------" );
		 }
		 
		 $this->_helper->viewRenderer->setNoRender ();
		
	}

	private function getMatches2($xml,$comp_country,$comp_gs_fix_name,$stageid,$weeknumber,$matchid,$competition_stage_id) {
		
		if ($xml->tournament['stage_id'] != null) {
			echo "Stage Id: " . $xml->tournament['stage_id'];
		} else {
			echo "Stage id is null";
		}
		//Zend_Debug::dump($xml)."<BR>";
	}
	private function getMatches($xml,$comp_country,$comp_gs_fix_name,$stageid,$weeknumber,$matchid,$competition_stage_id) {
		
		$stages = $xml->xpath("/results/tournament/stage");
				
		$allmatches = array();
				
				if ($stages != null) { //With Stages
					
					if ($stageid == null) { // tournaments with rounds all matches		
								
						$allstages = $xml->xpath("/results/tournament/stage");  //all stages
						
						//pass to a function that returns $allmatches[];
						foreach($allstages as $stage) {

							$matches = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match");
							if ($matches != null) {
								foreach($stage->match as $match) {
									$match['stageid'] = $stage['stage_id'];
									$match['week'] = null;
									$match['aggregate'] = null;
									$allmatches[] = $match;
									
								}
							} else {
								$weeks = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week");
								if ($weeks != null) {

									foreach($stage->week as $week) {
										foreach ($week->match as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = $week['number']; 
											$match['aggregate'] = null;
											$allmatches[] = $match;
										}
									}
								} else {
									
									$aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");									
										if ($aggregate != null) {
											$i = 1;
											foreach($stage->aggregate as $aggregates) {
												foreach($aggregates->match as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = null;
													$match['aggregate'] = 'A'.$stage['stage_id'].$i;
													$allmatches[] = $match;
												}
											$i++; }
										}
										
								}
							}
							
						} //pass to a function that returns $allmatches[];
						
						
					} else { // tournaments with rounds filtered by stage id 
						
						$stage['stage_id'] = $stageid;
						$allstages = $xml->xpath("/results/tournament/stage[@stage_id='".$stageid."']");
						//Zend_Debug::dump($allstages)."<BR>";
						//pass to a function that returns $allmatches[];
						foreach($allstages as $stage) {
								echo $matchid."<br>";
								//insertmatches/country/intl/fixture/friendlies/stage/15352763
								$matches = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match");
								if ($matches != null) {
									if ($matchid == null) {
										foreach($stage->match as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = null;
											$match['aggregate'] = null;
											$allmatches[] = $match;
											
										}
									} else {
										//insertmatches/country/intl/fixture/friendlies/stage/15352763/matchid/1193000
										$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match[@id='".$matchid."']");
										foreach ($matchunique as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = null;
											$match['aggregate'] = null;
											$allmatches[] = $match;
										}
										
									}
	
								} else {
									
									$weeks = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week");
									if ($weeks != null) {
										
										if ($weeknumber !=null) {
											
											if ($matchid == null) {
												$week_matches =  $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[@number='".$weeknumber."']");
												foreach ($week_matches as $week){
													foreach ($week->match as $match) {
														$match['stageid'] = $stage['stage_id'];
														$match['week'] = $weeknumber;
														$match['aggregate'] = null;
														$allmatches[] = $match;
													}
												}
											} else {
												//only match filtered
												$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[@number='".$weeknumber."']/match[@id='".$matchid."']");
												foreach ($matchunique as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = $weeknumber;
													$match['aggregate'] = null;
													$allmatches[] = $match;
												}
											}
											
										} else {
											foreach($stage->week as $week) {
												foreach ($week->match as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = $week['number']; 
													$match['aggregate'] = null;
													$allmatches[] = $match;
												}
											}
										}
									} else {
										echo "here";
											$aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");									
											if ($aggregate != null) {
												if ($matchid == null) {
													$i = 1;
													foreach($stage->aggregate as $aggregates) {
	
															foreach($aggregates->match as $match) {
																$match['stageid'] = $stage['stage_id'];
																$match['week'] = null;
																$match['aggregate'] = 'A'.$stage['stage_id'].$i;
																$allmatches[] = $match;
															}									
													$i++; }
												} else {
													//match unique filter with aggregate
													$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate/match[@id='".$matchid."']");
													foreach ($matchunique as $match) {
														$match['stageid'] = $stage['stage_id'];
														$match['week'] = null;
														$match['aggregate'] = null;
														$allmatches[] = $match;
													}
												}
											}
											
									}
								}
							}
							//pass to a function that returns $allmatches[];
		
					} 
				} else { // Without Stages - Regular Seasons European Mostly
					
					//all matches only parameter passed was stage id
					if ($weeknumber == null) {
						$matcharrayweek = $xml->xpath("/results/tournament/week");  
						foreach ($matcharrayweek as $week) {
							foreach ($week->match as $match) {
								
								$match['stageid'] = $competition_stage_id;
								$match['week'] = $week['number'];
								$match['aggregate'] = null;
								$allmatches[] = $match;
							}
						}
						
					} else {
						if ($matchid != null) {
							//only match filtered
							$matchunique = $xml->xpath("/results/tournament/week[@number='".$weeknumber."']/match[@id='".$matchid."']");
							foreach ($matchunique as $match) {
								$match['stageid'] = $competition_stage_id;
								$match['week'] = $weeknumber;
								$match['aggregate'] = null;
								$allmatches[] = $match;
							}
							
						} else {
							// Filtered by week /week/xxxx; passed stage id, week - all matches from the week
							$allmatchweek = $xml->xpath("/results/tournament/week[@number='".$weeknumber."']");  //filter by match and week
							//echo $weeknumber;
							foreach ($allmatchweek as $week) {
								foreach ($week->match as $match) {
									$match['stageid'] = $competition_stage_id;
									$match['week'] = $weeknumber;
									$match['aggregate'] = null;
									$allmatches[] = $match;
								}
							}	
							
						}		
					}
					
				}
				
		return $allmatches;
	}
	
	public function updateschedulesAction() {
		$config = Zend_Registry::get( 'config' );
        $file = $config->path->log->updatedmatches;

        /***FILE LOG **/
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream($file);
        $logger->addWriter($writer);
		$urlGen = new SeoUrlGen ();
		$date = new Zend_Date ();
        $matchObject = new Matchh ();
        $league = new LeagueCompetition ();
        $gsstanding = new GoalserveStanding();
        $league_id = $this->_request->getParam ( 'league', null );
        $matchleague = $matchObject->selectMatchesSchedulesLeague($league_id);
        //Zend_Debug::dump($matchleague);	
        foreach($matchleague as $league) {
        	$rowstanding = $gsstanding->fetchRow ( 'competition_id = ' . $league['competition_id'] );
        	echo $rowstanding['competition_id']." -- ".$rowstanding['fixtures']."<br>";
        	$logger->info("Competition: " .$rowstanding['competition_id']." -- ".$rowstanding['fixtures']);
        	$xmlMatches = $this->getgsfeed (trim($rowstanding['fixtures']));
        	$matches = $matchObject->getmatchestoupdate($league['competition_id']);
        	//Zend_Debug::dump($matches);
        	
        	foreach ($matches as $match) {
        		echo "Match id GF:" .$match['match_id_goalserve']."<br>";
        		$matchX = $xmlMatches->xpath("//match[@id='".$match['match_id_goalserve']."']");
        		
				
        		if ($matchX != null) {	
		        			echo " match found on feed<br>";
		        			// convert feed date and time to format Y-m-d H:i:s to be used on creating zend date
		        			if ( $matchX[0]['time'] =='TBA' || $matchX[0]['time'] == 'Postp.' || $match['time'] == 'Cancl.' {
		        				$matchX[0]['time'] ="15:00:00";
		        			}
		        			
		        			
							$mydate = date ( "Y-m-d H:i:s", strtotime ( $matchX[0]['date'] . " " . $matchX[0]['time'] ) );
							//get zend date based on feed date	
							$zf_date = new Zend_Date ( $mydate, Zend_Date::ISO_8601 );
							//add 2 hrs to get the gmt +2 like enetpulse
							$gf_date = $zf_date->addHour ( 0 );
							//get new date and time
							$new_gf_date = $gf_date->toString ( 'yyyy-MM-dd' );
							$new_gf_time = $gf_date->toString ( 'HH:mm:ss' );
							// concatenate date + time for datetime field 
							$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $new_gf_date . ' ' . $new_gf_time ) );
		        			
		        			$datamatch = array (
										'match_date' => $new_gf_date, //1
										'match_time' => $new_gf_time, //2
										'match_date_time' => $datetime, 
		        				'static_id' => 	(int)$match['static_id'],
							);
							echo 'Updating Match ID ' . $match['match_id']."<br><br>";
							$matchObject->updateMatch($match['match_id'] ,$datamatch );		
	        		} else {
        			echo " match NOT found on feed<br>";
        			if ($match['static_id'] != null ) {
        				$matchXstatic = $xmlMatches->xpath("//match[@static_id='".$match['static_id']."']");
        				if ($matchXstatic != null) {
		        			if ($matchXstatic[0]['time'] =='TBA' || $matchXstatic[0]['time'] =='Postp.' ) {
		        				$matchXstatic[0]['time'] ="15:00:00";
		        			}
							// convert feed date and time to format Y-m-d H:i:s to be used on creating zend date
							$mydate = date ( "Y-m-d H:i:s", strtotime ( $matchXstatic[0]['date'] . " " . $matchXstatic[0]['time'] ) );
							//get zend date based on feed date	
							$zf_date = new Zend_Date ( $mydate, Zend_Date::ISO_8601 );
							//add 2 hrs to get the gmt +2 like enetpulse
							$gf_date = $zf_date->addHour ( 0 );
							//get new date and time
							$new_gf_date = $gf_date->toString ( 'yyyy-MM-dd' );
							$new_gf_time = $gf_date->toString ( 'HH:mm:ss' );
							// concatenate date + time for datetime field 
							$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $new_gf_date . ' ' . $new_gf_time ) );
								
							$datamatch = array (
										'match_id'	=> 'G'. $matchXstatic[0]['id'],
										'match_date' => $new_gf_date, //1
										'match_time' => $new_gf_time, //2
										'match_date_time' => $datetime, 
										'match_id_goalserve' => (string)$matchXstatic[0]['id'],		
							);
							
							$matchObject->updateMatchStatic( $match['static_id'],$datamatch );
							echo 'Changing Match ID Changed from: <strong>' . $match['match_id_goalserve'] . '</strong> -to <strong>:'.$matchXstatic[0]['id'].'</strong><br><br>';
							$logger->info("Updating Match ID Changed from: " .$match['match_id_goalserve']." to ".$matchXstatic[0]['id']);
        				} else {
        					echo "static_id not found on database <br>";
        				}
						
        			} else {
        				echo "Match id is null<br>";
        			}
						

        		}
        		
        		echo "=================================================<br>";
        	}
        	
        	
        }
	}
	
	
	
	// format example:
	// insertmatches/country/spain/fixture/Cup
	// insertmatches/country/spain/fixture/Cup/stage/12345
	// insertmatches/country/spain/fixture/Cup/stage/12345/week/1
	// insertmatches/country/spain/fixture/primera
	///insertmatches/country/spain/fixture/primera/week/1
	
	public function insertmatches2Action() {
		$date = new Zend_Date ();
        $matchObject = new Matchh ();
		$matchEventObject = new MatchEvent ();
        $league = new LeagueCompetition ();
   		$team = new Team ();
   		$player = new Player();
		$urlGen = new SeoUrlGen ();
		
		$comp_country = $this->_request->getParam ( 'country', null );
		$comp_gs_fix_name = $this->_request->getParam ( 'fixture', null );
		$stageid = $this->_request->getParam ( 'stage', null );
		$weeknumber = $this->_request->getParam ( 'week', null );
		$matchid = $this->_request->getParam ( 'matchid', null );
		$history = $this->_request->getParam ( 'history', null );
		if ($history == null) {
			$xml = $this->getgsfeed('soccerfixtures/'.$comp_country.'/'.$comp_gs_fix_name);
		} else  {
			$xml = $this->getgsfeed('soccerhistory/'.$comp_country.'/'.$comp_gs_fix_name);
		}
		
		$competition ['stage_id'] = $stageid;
		
		$allmatches = self::getMatches($xml,$comp_country,$comp_gs_fix_name,$stageid,$weeknumber,$matchid,$competition ['stage_id']);
		Zend_Debug::dump($allmatches)."<BR>";
	}
	
    public function insertmatchesAction() {
    	
        $date = new Zend_Date ();
        $matchObject = new Matchh ();
		$matchEventObject = new MatchEvent ();
        $league = new LeagueCompetition ();
   		$team = new Team ();
   		$player = new Player();
		$urlGen = new SeoUrlGen ();
		
		$comp_country = $this->_request->getParam ( 'country', null );
		$comp_gs_fix_name = $this->_request->getParam ( 'fixture', null );
		$stageid = $this->_request->getParam ( 'stage', null );
		$weeknumber = $this->_request->getParam ( 'week', null );
		$matchid = $this->_request->getParam ( 'matchid', null );
		$history = $this->_request->getParam ( 'history', null );
		
		if ($history == null) {
			$xml = $this->getgsfeed('soccerfixtures/'.$comp_country.'/'.$comp_gs_fix_name);
		} else  {
			$xml = $this->getgsfeed('soccerhistory/'.$comp_country.'/'.$comp_gs_fix_name);
		}
		
		foreach ( $xml->tournament as $competition ) {

			echo '<br><br>-><b>Competition :</b> ' . $competition ['id'] . "<BR>";
			
			$row = $league->findCompetitionByGoalserveId ( $competition ['id'] );
			
			if ($row != null) {
				
				$allmatches = self::getMatches($xml,$comp_country,$comp_gs_fix_name,$stageid,$weeknumber,$matchid,$competition ['stage_id']);

				foreach ($allmatches as $match) {
					
						//Does match exist on GoalFace DB
						$matchExist = $matchObject->fetchRow ( 'match_id_goalserve = ' .$match ['id'] );
						
						$match_id = 'G'. $match ['id'];
						$rowTeamA = $team->fetchRow ( 'team_gs_id = ' . $match->localteam ['id'] );
						$rowTeamB = $team->fetchRow ( 'team_gs_id = ' . $match->visitorteam ['id'] );
						$matchTeams = $rowTeamA ["team_name"] . " vs " . $rowTeamB ["team_name"] ;
						$matchUrl = $urlGen->getMatchPageUrl ( $row ["competition_name"], $rowTeamA ["team_name"], $rowTeamB ["team_name"], $match_id, true );
						
						if ((int)$match->localteam ['score'] > (int)$match->visitorteam ['score']) { 
							$team_id_winner = $rowTeamA ['team_id'];
						} elseif ((int)$match->visitorteam ['score'] > (int)$match->localteam ['score']) {
							$team_id_winner = $rowTeamB ['team_id'];
						} else {
							$team_id_winner = '999';
						}
						
						if ($match ['time'] == 'TBA') {
							$timefeed = '16:00';
						} else {
							$timefeed = $match ['time'];
						}
						
						if ($match->localteam ['score'] != "" and $match->visitorteam ['score'] != ""){
						 	$mstatus = 'Played';
						 } else {
						 	$mstatus = 'Fixture';
						 }
						 
						 
						// convert feed date and time to format Y-m-d H:i:s to be used on creating zend date
						$mydate = date ( "Y-m-d H:i:s", strtotime ( $match ['date'] . " " . $timefeed ) );
						//get zend date based on feed date	
						$zf_date = new Zend_Date ( $mydate, Zend_Date::ISO_8601 );
						//add 2 hrs to get the gmt +2 like enetpulse
						$gf_date = $zf_date->addHour ( 0 );
						//get new date and time
						$new_gf_date = $gf_date->toString ( 'yyyy-MM-dd' );
						$new_gf_time = $gf_date->toString ( 'HH:mm:ss' );
						// concatenate date + time for datetime field 
						$datetime = strftime ( '%Y%m%d%H%M%S', strtotime ( $new_gf_date . ' ' . $new_gf_time ) );
						// remove  / from 2011/2012 and also remove 20 to get 1112 and concatenate with competition id
						$season = str_replace ( '20', '', str_replace ( '/', '', $competition ['season'] ) ) . '' . $competition ['id'];
						

						//match doesn't exist on GoalFace DB insert it
						if ($matchExist == null ) {
							$datamatch = array (	
								'match_id' => $match_id, 
								'match_id_goalserve' => $match ['id'], 
								'country_id' => $row ['country_id'],
								'competition_id' => $row ['competition_id'],
								'match_date' => $new_gf_date, //1
								'match_time' => $new_gf_time, //2
								'match_date_time' => $datetime, 
								'match_status' => $mstatus,		
								'match_winner' => $team_id_winner, //3
								'team_a' => $rowTeamA ['team_id'], 
								'team_b' => $rowTeamB ['team_id'], //4
								'fs_team_a' => $match->localteam ['ft_score'], 
								'fs_team_b' => $match->visitorteam ['ft_score'],
								'season_id' => $season, 
								'round_id' => $match ['stageid'],
								'venue_id' => $match ['venue_id'], 
								'week' => $match['week'],
								'aggrid' => $match['aggregate'],
								'static_id' => $match ['static_id'], 
							);
							$matchObject->insert ( $datamatch );
							echo 'Inserting Match: <strong>' . $match_id . '</strong> - Date:'.$new_gf_date.' - Time: '.$new_gf_time.'<br>';
						} else {
							$datamatch = array (	
								'match_date' => $new_gf_date, //1
								'match_time' => $new_gf_time, //2
								'match_date_time' => $datetime, 
								'match_status' => $mstatus,		
								'match_winner' => $team_id_winner, //3
								'fs_team_a' => $match->localteam ['ft_score'], 
								'fs_team_b' => $match->visitorteam ['ft_score'],
							);
							$matchObject->updateMatch( $match_id,$datamatch );
							echo 'Updating Match: <strong>' . $match_id . '</strong> - Date:'.$new_gf_date.' - Time: '.$new_gf_time.'<br>';
						}
						
						// Insert Events
						if ($match->halftime ['score'] != "" ) {
							// Insert Goal events
							$i = 1;
							foreach ($match->goals->goal as $goal) {
								
								if ($goal['playerid'] != "") {
									if ($goal['team'] == 'localteam') {
										$team_id_event = $rowTeamA ['team_id'];
									} else {
										$team_id_event = $rowTeamB ['team_id'];
									}
								
									$dataevent = array (
										'event_id' => 'E' . $match ['id'] . $i, 
										'player_id' => $goal['playerid'],
										'event_type_id'=>'G',
										'match_id' => 'G' . $match ['id'],
										'team_id' => $team_id_event,
										'event_minute' => $goal['minute'], 
										'time' => $new_gf_date ." ". $new_gf_time,
									);
									
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $i . '</strong> Event: <b>G</b> - Player: '. $goal['playerid'] .'<br>';
									$matchEventObject->insert ( $dataevent );
									
									
									//Activiy Goal
									$event = null; 
									$event = array (
										'id' => 'E' . $match ['id'] . $i, 
										'eventtype' => 'G',
										'player_id' => $goal['playerid'], 
										'game_minute' =>$goal['minute'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
									);
									
									$playerid = $player->fetchRow ( 'player_id = ' . $goal['playerid'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = $goal['score'];
									$playersPathName = $this->getplayerimage($goal['playerid']);
									
									self::insertGoalScoredActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null );
								} else {
									self::$logger->debug ( "Player Id missing for event Goal on match:" .$match ['id'] ." - ". $match->localteam ['name'] ."vs. ".$match->visitorteam ['name'] );
								}
							$i++;}

							// Insert Lineups events
							// localteam
							$j = $i;
							foreach($match->lineups->localteam->player as $lineuplocal) {
								if ($lineuplocal['id'] != "") {	
									$datalineuplocal = array (
										'event_id' => 'E' . $match ['id'] . $j, 
										'player_id' => $lineuplocal['id'],
										'event_type_id'=>'L',
										'match_id' => 'G' . $match ['id'],
										'team_id' => $rowTeamA ['team_id'],
										'time' => $new_gf_date ." ". $new_gf_time,
										'jersey_number' => $lineuplocal['number'],
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $j . '</strong> Event: <b>L</b> - Team: '. $rowTeamA ['team_id'] .' - Player: '. $lineuplocal['id'] .'<br>';
									//Zend_Debug::dump($datalineuplocal)."<BR>";
									$matchEventObject->insert ( $datalineuplocal );
									
									//Activity Lineup Local
									$playerid = $player->fetchRow ( 'player_id = ' . $lineuplocal['id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathName = $this->getplayerimage($lineuplocal['playerid']);
									$event = null;
									$event = array(
										'id' => 'E' . $match ['id'] . $j,
										'eventtype' => 'L',
										'player_id' => $lineuplocal['id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
									);

									self::insertLineUpActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null );

								} else {
									self::$logger->debug ( "Player Id missing for event Lineup on match:" .$match ['id'] ." for team: ". $rowTeamA ['team_id']);
								}
							$j++;}
							
							// visitorteam
							$k = $j;
							foreach($match->lineups->visitorteam->player as $lineupvisitor) {
								if ($lineupvisitor['id'] != "") {		
									$datalineupvisitor = array (
										'event_id' => 'E' . $match ['id'] . $k, 
										'player_id' => $lineupvisitor['id'],
										'event_type_id'=>'L',
										'match_id' => 'G' . $match ['id'],
										'team_id' => $rowTeamB ['team_id'],
										'time' => $new_gf_date ." ". $new_gf_time,
										'jersey_number' => $lineupvisitor['number'],
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $k . '</strong> Event: <b>L</b> - Team: '. $rowTeamB ['team_id'] .' - Player: '. $lineupvisitor['id'] .'<br>';
									//Zend_Debug::dump($datalineupvisitor)."<BR>";
									$matchEventObject->insert ( $datalineupvisitor );
									
									//Activity Lineup Visitor
									$playerid = $player->fetchRow ( 'player_id = ' . $lineupvisitor['id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathName = $this->getplayerimage($lineupvisitor['playerid']);
									$event = null;
									$event = array(
										'id' => 'E' . $match ['id'] . $j,
										'eventtype' => 'L',
										'player_id' => $lineupvisitor['id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
									);

									self::insertLineUpActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null );

								} else {
									self::$logger->debug ( "Player Id missing for event Lineup on match:" .$match ['id'] ." for team: ". $rowTeamB ['team_id']);
								}												
							$k++;}

							//localteam subs
							$l = $k;
						 	$ll = $k +1;
							foreach($match->substitutions->localteam->substitution as $sublocal){
								if ($sublocal['minute'] != "") {
									//Event Sub Local In
									$datasublocalIn = array(
										'event_id' => 'E' . $match ['id'] . $l, 
										'player_id' => $sublocal['player_in_id'],
										'event_type_id'=>'SI',
										'match_id' => 'G' . $match ['id'],
										'event_minute' => $sublocal['minute'], 
										'team_id' => $rowTeamA ['team_id'],
										'time' => $new_gf_date ." ". $new_gf_time,
										'jersey_number' => $sublocal['player_in_number'], 
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $l . '</strong> Event: <b>SI</b> - Team: '. $rowTeamA ['team_id'] .' - Player: '. $sublocal['player_in_id'] .'<br>';
									//Zend_Debug::dump($datasublocalIn)."<BR>";
									$matchEventObject->insert ( $datasublocalIn );
									
									//activity Sub Local In
									$playerid = $player->fetchRow ( 'player_id = ' . $sublocal['player_in_id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathNameIn = $this->getplayerimage($sublocal['player_in_id']);
									$event = null;
									$eventIn = array(
										'id' => 'E' . $match ['id'] . $l,
										'eventtype' => 'SI',
										'player_id' => $sublocal['player_in_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $sublocal['minute']
									);

									self::insertSubstitutionActivity ( $eventIn, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathNameIn,$match_id,null );
									
									//Event Sub Local Out
									$datasublocalOut = array(
										'event_id' => 'E' . $match ['id'] . $ll, 
										'player_id' => $sublocal['player_out_id'],
										'event_type_id'=>'SO',
										'match_id' => 'G' . $match ['id'],
										'event_minute' => $sublocal['minute'], 
										'team_id' => $rowTeamA ['team_id'],
										'time' => $new_gf_date ." ". $new_gf_time,
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $ll . '</strong> Event: <b>SO</b> - Team: '. $rowTeamA ['team_id'] .' - Player: '. $sublocal['player_out_id'] .'<br>';
									//Zend_Debug::dump($datasublocalOut)."<BR>";
									$matchEventObject->insert ( $datasublocalOut );
									
									//activity Sub Local Out
									$playerid = $player->fetchRow ( 'player_id = ' . $sublocal['player_out_id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathNameOut = $this->getplayerimage($sublocal['player_out_id']);
									$event = null;
									$eventOut = array(
										'id' => 'E' . $match ['id'] . $ll,
										'eventtype' => 'SO',
										'player_id' => $sublocal['player_out_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $sublocal['minute']
									);
									self::insertSubstitutionActivity ( $eventOut, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathNameOut,$match_id,null );
								}
							$l = $l+2;$ll=$ll+2;}

							//visitorteam subs
							$m = $l;
							$n = $ll;
							foreach($match->substitutions->visitorteam->substitution as $subvisitor){
								if ($subvisitor['minute'] != "") {
									$datasubvisitorIn = array(
										'event_id' => 'E' . $match ['id'] . $m,
										'player_id' => $subvisitor['player_in_id'],
										'event_type_id'=>'SI',
										'match_id' => 'G' . $match ['id'],
										'event_minute' => $subvisitor['minute'],
										'team_id' => $rowTeamB ['team_id'], 
										'time' => $new_gf_date ." ". $new_gf_time,
										'jersey_number' => $subvisitor['player_in_number'], 
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $m . '</strong> Event: <b>SI</b> - Team: '. $rowTeamB ['team_id'] .' - Player: '. $subvisitor['player_in_id'] .'<br>';
									//Zend_Debug::dump($datasubvisitorIn)."<BR>";
									$matchEventObject->insert ( $datasubvisitorIn );
									
									//activity Sub Visitor In
									$playerid = $player->fetchRow ( 'player_id = ' . $subvisitor['player_in_id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathNameIn = $this->getplayerimage($subvisitor['player_in_id']);
									$eventIn = null;
									$eventIn = array(
										'id' => 'E' . $match ['id'] . $m,
										'eventtype' => 'SI',
										'player_id' => $subvisitor['player_in_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $subvisitor['minute']
									);
									self::insertSubstitutionActivity ( $eventIn, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathNameIn,$match_id,null );
									
									$datasubvisitorOut = array(
										'event_id' => 'E' . $match ['id'] . $n,
										'player_id' => $subvisitor['player_out_id'],
										'event_type_id'=>'SO',
										'match_id' => 'G' . $match ['id'],
										'event_minute' => $subvisitor['minute'], 
										'team_id' => $rowTeamB ['team_id'],
										'time' => $new_gf_date ." ". $new_gf_time,
									);
									echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $n . '</strong> Event: <b>SO</b> - Team: '. $rowTeamB ['team_id'] .' - Player: '. $subvisitor['player_out_id'] .'<br>';
									//Zend_Debug::dump($datasubvisitorOut)."<BR>";
									$matchEventObject->insert ( $datasubvisitorOut );
									
									//activity Sub Visitor Out
									$playerid = $player->fetchRow ( 'player_id = ' . $subvisitor['player_out_id'] );
									$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
									$player_shortname = $playerid ['player_name_short'];
									$matchscore = null;
									$playersPathNameOut = $this->getplayerimage($subvisitor['player_out_id']);
									$eventOut = null;
									$eventOut = array(
										'id' => 'E' . $match ['id'] . $n,
										'eventtype' => 'SO',
										'player_id' => $subvisitor['player_out_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $subvisitor['minute']
									);
									self::insertSubstitutionActivity ( $eventOut, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathNameOut,$match_id,null );
									
								}
							$m=$m+2;$n=$n+2;}
							
							// localteam cards
							$p = $m;
							foreach($match->lineups->localteam->player as $lineuplocal) {
								if($lineuplocal['booking'] != "") {
									if ($lineuplocal['id'] != "") {
										$event_card_local = explode(" ",$lineuplocal['booking']);
										// lineup local cards event
										$datacardlocal = array (
											'event_id' => 'E' . $match ['id'] . $p, 
											'player_id' => $lineuplocal['id'],
											'event_type_id'=>$event_card_local[0],
											'match_id' => 'G' . $match ['id'],
											'team_id' => $rowTeamA ['team_id'],
											'event_minute' => $event_card_local[1],
											'time' => $new_gf_date ." ". $new_gf_time,
										);
										echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $p. '</strong> Event: <b>'.$event_card_local[0].'</b> - Team: '. $rowTeamA ['team_id'] .' - Player: '. $lineuplocal['id'] .'<br>';
										$matchEventObject->insert ( $datacardlocal );
										
										// lineup local cards activity
										$playerid = $player->fetchRow ( 'player_id = ' . $lineuplocal['id'] );
										$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
										$player_shortname = $playerid ['player_name_short'];
										$matchscore = null;
										$playersPathName = $this->getplayerimage($lineuplocal['id']);
										$event = array(
										'id' => 'E' . $match ['id'] . $p,
										'eventtype' => $event_card_local[0],
										'player_id' => $lineuplocal['id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $event_card_local[1]
									);
										self::insertCardsActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null);
									}
								}
							$p++;}
								
							// visitor team cards
							$q=$p;
							foreach($match->lineups->visitorteam->player as $lineupvisitor) {
								if($lineupvisitor['booking'] != "") {
									if ($lineupvisitor['id'] != "") {
										$event_card_visitor = explode(" ",$lineupvisitor['booking']);
										// lineup visitor cards event
										$datacardvisitor = array (
											'event_id' => 'E' . $match ['id'] . $q, 
											'player_id' => $lineupvisitor['id'],
											'event_type_id'=>$event_card_visitor[0],
											'match_id' => 'G' . $match ['id'],
											'team_id' => $rowTeamB ['team_id'],
											'event_minute' => $event_card_visitor[1],
											'time' => $new_gf_date ." ". $new_gf_time,
										);
										echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $q. '</strong> Event: <b>'.$event_card_visitor[0].'</b> - Team: '. $rowTeamB ['team_id'] .' - Player: '. $lineupvisitor['id'] .'<br>';
										$matchEventObject->insert ( $datacardvisitor );
										
										// lineup visitor cards activity
										$playerid = $player->fetchRow ( 'player_id = ' . $lineupvisitor['id'] );
										$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
										$player_shortname = $playerid ['player_name_short'];
										$matchscore = null;
										$playersPathName = $this->getplayerimage($lineupvisitor['id']);
										$event = array(
										'id' => 'E' . $match ['id'] . $q,
										'eventtype' => $event_card_local[0],
										'player_id' => $lineupvisitor['id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $event_card_local[1]
									);
										self::insertCardsActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null);
										
									}
								}
							$q++;}

							//localteam sub cards
							$r = $q;
							foreach($match->substitutions->localteam->substitution as $sublocal){
								
								if ($sublocal['player_in_booking'] != "") {
										$event_card_sublocal = explode(" ",$sublocal['player_in_booking']);
										$datacardlocalsub = array (
											'event_id' => 'E' . $match ['id'] . $r, 
											'player_id' => $sublocal['player_in_id'],
											'event_type_id'=>$event_card_sublocal[0],
											'match_id' => 'G' . $match ['id'],
											'team_id' => $rowTeamA ['team_id'],
											'event_minute' => $event_card_sublocal[1],
											'time' => $new_gf_date ." ". $new_gf_time,
											);
										echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $r. '</strong> Event: <b>'.$event_card_sublocal[0].'</b> - Team: '. $rowTeamA ['team_id'] .' - Player: '. $sublocal['player_in_id'] .'<br>';
										$matchEventObject->insert ( $datacardlocalsub );
										
										//subs In localteam cards activity
										$playerid = $player->fetchRow ( 'player_id = ' . $sublocal['player_in_id'] );
										$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
										$player_shortname = $playerid ['player_name_short'];
										$matchscore = null;
										$playersPathName = $this->getplayerimage($sublocal['player_in_id']);
										$event = array(
										'id' => 'E' . $match ['id'] . $q,
										'eventtype' => $event_card_local[0],
										'player_id' => $sublocal['player_in_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $event_card_local[1]
									);
										self::insertCardsActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null);
										
								}
							$q++;}
							
							//visitorteam sub cards
							$s = $r;
							foreach($match->substitutions->visitorteam->substitution as $subvisitor){
								if ($subvisitor['player_in_booking'] != "") {
										$event_card_subvisitor = explode(" ",$subvisitor['player_in_booking']);
										$datacardlocalvisitor = array (
											'event_id' => 'E' . $match ['id'] . $s, 
											'player_id' => $subvisitor['player_in_id'],
											'event_type_id'=>$event_card_subvisitor[0],
											'match_id' => 'G' . $match ['id'],
											'team_id' => $rowTeamB ['team_id'],
											'event_minute' => $event_card_subvisitor[1],
											'time' => $new_gf_date ." ". $new_gf_time,
											);
										echo '---->Inserting Matchevent: <strong>' . 'E' . $match ['id'] . $s. '</strong> Event: <b>'.$event_card_subvisitor[0].'</b> - Team: '. $rowTeamB ['team_id'] .' - Player: '. $subvisitor['player_in_id'] .'<br>';
										$matchEventObject->insert ( $datacardlocalvisitor );
										
										//subs In visitor cards activity

										$playerid = $player->fetchRow ( 'player_id = ' . $subvisitor['player_in_id'] );
										$player_name_seo = $urlGen->getPlayerMasterProfileUrl ( $playerid ["player_nickname"], $playerid ["player_firstname"], $playerid ["player_lastname"], $playerid ["player_id"], true, $playerid ["player_common_name"] );
										$player_shortname = $playerid ['player_name_short'];
										$matchscore = null;
										$playersPathName = $this->getplayerimage($subvisitor['player_in_id']);
										$event = array(
										'id' => 'E' . $match ['id'] . $q,
										'eventtype' => $event_card_local[0],
										'player_id' => $subvisitor['player_in_id'],
										'date_event' => $new_gf_date ." ". $new_gf_time,
										'game_minute'=>  $event_card_local[1]
									);
										self::insertCardsActivity ( $event, $player_name_seo, $player_shortname, $matchTeams, $matchUrl, $matchscore, $playersPathName, $match_id,null);
										
								}
							$s++;}	

							
						} // Loop only when game status playing and halftime score not null
								
				} // End of foreach All Matches Loop
					
			} // End if row is !- null
			
		} // End of foreach Tournament
		
    }

    
    //$rowplayer = $player->fetchRow($player->select()->where('player_id = ?',  $item['id']));
    public function updateplayerdetailsAction() {
    	
    	$player = new Player ();
    	$country = new Country();
    	$seasonId = $this->_request->getParam ( 'season', null );
    	$rowplayer = $player->findPlayersBySeason($seasonId);
    	foreach($rowplayer as $playerlist) {
          $xmlPlayer = $this->getgsfeed('/soccerstats/player/'.$playerlist['player_id']);   	  
      		if ($xmlPlayer != null || $xmlPlayer != '') {
  	    		foreach($xmlPlayer->player as $playerxml){
  	    		 $dataPlayer = array ('player_common_name' => $playerxml->name, );
  	    		 echo $playerlist['player_id']." Name: ". $playerxml->name ." UPDATED<br>";
  					$player->updatePlayer($playerlist['player_id'], $dataPlayer );
  	    		}
  	    	}
	    }
    	
    	
  /*  	
    	foreach($rowplayer as $playerlist) {
    		$xmlPlayer = $this->getgsfeed('/soccerstats/player/'.$playerlist['player_id']);
    		if ($xmlPlayer != null || $xmlPlayer != '') {
	    		foreach($xmlPlayer->player as $playerxml){
	    			echo $playerxml['id']."-".$playerxml->nationality."-".$playerxml->birthcountry."-"."<br>";
	    			
	    			$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $playerxml->birthcountry . '"' );
					  $rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $playerxml->nationality . '"' );
					
					if ($rowBirthCountry != null ) {
						if($rowNationalityCountry !=null){
							$player_country = $rowBirthCountry ['country_id'];
							$player_nationality = $rowNationalityCountry ['country_id'];
						} else {
							$player_country = $rowBirthCountry ['country_id'];
							$player_nationality = $rowBirthCountry ['country_id'];
						}
						$dataPlayer = array ('player_country' => $player_country,
										            'player_nationality' => $player_nationality, );
						$player->updatePlayer($playerlist['player_id'], $dataPlayer );
						echo $playerlist['player_id']." Country: ". $player_country." nationality:".$player_nationality ." UPDATED<br>";
					}
					echo"=========<br>";
	    		}
    		}
    	}
  */
    	//Zend_Debug::dump($rowplayer);
    }
    
    
/*		public function updateplayerdetailsAction() {
		$date = new Zend_Date ();
		$player = new Player ();
		$team = new Team();
		$playerid = $this->_request->getParam ( 'player', null );
		$teamid = $this->_request->getParam ( 'team', null );
		$rowTeam = $team->fetchRow ( 'team_id = ' . $teamid );
		
		if ($rowTeam ['team_gs_id'] != null) {
			$urlteam = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/' . $rowTeam ['team_gs_id'];
			$request_url_team = file_get_contents ( $urlteam );
			$xmlteam = new SimpleXMLElement ( $request_url_team );
			foreach ( $xmlteam->team as $teamxml ) {
				foreach ( $teamxml->squad->player as $playerxml ) {	
						$urlplayer = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/player/' .  $playerxml ['id'];
						$request_url_player = file_get_contents ( $urlplayer );
						if (! empty ( $request_url_player )) {
							$xml = new SimpleXMLElement ( $request_url_player );
							foreach ( $xml->player as $playerxml ) {
								//height and weight
								$arr_height = explode ( " ", $playerxml->height, 2 );
								$arr_weight = explode ( " ", $playerxml->weight, 2 );
								$player_height = $arr_height [0];
								$player_weight = $arr_weight [0];
								$player_birth_date = date ( "Y-m-d", strtotime ( $playerxml->birthdate ) );
								if ($playerxml->position == 'Attacker') {
									$playerxml->position = 'Forward';
								}
								$dataPlayer = array ('player_id' => $playerxml ['id'], 
													'player_dob' => $player_birth_date,
													'player_dob_city' => $playerxml->birthplace, 
													'player_height' => $player_height, 
													'player_weight' => $player_weight, 
													'player_position' => $playerxml->position,
													);
								//Zend_Debug::dump($dataPlayer);
								$player->updatePlayer ( $playerxml ['id'], $dataPlayer );
								echo $playerxml ['id'] . "--" . $playerxml->name . " player info has been UPDATED<BR>";
							}
							
						}	
				}
			}
		}
		
		return;
	}*/
	
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
			$xml = $this->getgsfeed('soccerstats/team/'.$rowTeam ['team_gs_id']);
			foreach ( $xml->team as $teamxml ) {
				foreach ( $teamxml->squad->player as $playerxml ) {
					echo "INSERT INTO playerseason VALUES (". $playerxml ['id'] .",". $teamid ."," .$seasonid .");<br>";
					
					//check if player exists GoalFace DB
					$rowPlayer = $player->fetchRow ( 'player_id = ' . $playerxml ['id'] );
					
					//fetch player xml info from feed
					$xmlplayer = $this->getgsfeed('soccerstats/player/'.$playerxml ['id']);
					
					// Player Doesnt' exist
					if ($rowPlayer == null) {
						if ($xmlplayer != null || $xmlplayer != '') {
						  	
						  	foreach ($xmlplayer->player as $xmlPlayer) {
						  		
				  				$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->birthcountry . '"' );
								$rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->nationality . '"' );
								$mydate = date ( "Y-m-d", strtotime ( $xmlPlayer->birthdate ) );
								$arr_height = explode ( " ", $xmlPlayer->height, 2 );
								$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
								$player_height = $arr_height [0];
								$player_weight = $arr_weight [0];
						  	    if ($xmlPlayer->position == 'Attacker') {
								    $playerposition = 'Forward';
								} else {
								    $playerposition = $xmlPlayer->position;
								}
								
								$dataPlayer = array ('player_id' => $xmlPlayer ['id'], //1
									'player_firstname' => $xmlPlayer->firstname, //2
									'player_middlename' => '', 
									'player_lastname' => $xmlPlayer->lastname, //3
									'player_common_name' => $xmlPlayer->name, 
									'player_name_short' => $playerxml ['name'],
									'player_dob' => $mydate, //4
									'player_dob_city' => $xmlPlayer->birthplace, 
									'player_type' => 'player', //5
									'player_country' => $rowBirthCountry ['country_id'], //5
									'player_nationality' => $rowNationalityCountry ['country_id'], //5
									'player_position' => $playerposition, //5
									'player_creation' => $today, 
									'player_height' => $player_height, 
									'player_weight' => $player_weight );
						  	}
	
						  	//insert new player
						  	$player->insert ( $dataPlayer );
							//echo "Inserting new player: " . $xmlPlayer ['id'] . "-> " . $xmlPlayer->name . "<br>";
							
							if ($xmlPlayer->teamid != '') {
								
								$rowTeamCurrentFeed = $team->fetchRow ( 'team_gs_id = ' . (int)$xmlPlayer->teamid );
							
								if ($rowTeamCurrentFeed != null ) {
									//insert teamplayer relation
									$dataTeamPlayer = array ('player_id' => $xmlPlayer ['id'],'team_id' => $rowTeamCurrentFeed['team_id'], 'actual_team' => '1' );		
									$teamplayer->insert ( $dataTeamPlayer );
									//echo "Inserting new teamplayer: " . $xmlPlayer ['id'] . "-> " .$rowTeamCurrentFeed['team_id'] . "<br>";
								} else {
									//echo "team: " .$xmlPlayer->teamid ."--". $xmlPlayer->team."needs to be mapped<br>";
								}
							} else {
								//echo "Player: " . $xmlPlayer ['id'] . " Has No Current team On feed<br>";
							}

								
						  }
					} else {
					// Player Exists
						
						if ($xmlplayer != null || $xmlplayer != '') {
							foreach ($xmlplayer->player as $xmlPlayer) {
								//Updating Player Details Missing Fields
								$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->birthcountry . '"' );
								$rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->nationality . '"' );
								$mydate = date ( "Y-m-d", strtotime ( $xmlPlayer->birthdate ) );
								$arr_height = explode ( " ", $xmlPlayer->height, 2 );
								$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
								$player_height = $arr_height [0];
								$player_weight = $arr_weight [0];
								if ($xmlPlayer->position == 'Attacker') {
								    $playerposition = 'Forward';
								} else {
								    $playerposition = $xmlPlayer->position;
								}
								$dataPlayer = array (
									'player_dob' => $mydate, //4
									'player_dob_city' => $xmlPlayer->birthplace, 
									'player_position' => $playerposition, //5
									'player_height' => $player_height, 
									'player_weight' => $player_weight );

								$player->updatePlayer ( $playerxml ['id'], $dataPlayer );
								
								//Find current team (club) per each player GoalFace
								$currentteam = $teamplayer->findCurrentTeamPlayer ( $playerxml ['id'], 'club',1 );

								$rowTeamCurrentFeed = $team->fetchRow ( 'team_gs_id = ' . (int)$xmlPlayer->teamid );
								//echo "==================<br>";
								//echo "<strong>Player:</strong> " .$xmlPlayer->name. "<br>";
								if ($xmlPlayer->teamid != '') {
									if ($currentteam == null) {
										
										
										//No Current team ophan - Update to current to this team
										//echo "ORPHAN player goalface: <br>" ;
										if($rowTeamCurrentFeed != null) {
											$dataTeamPlayer = array ('player_id' => $playerxml ['id'], 'team_id' => $rowTeamCurrentFeed['team_id'], 'actual_team' => '1' );
											$teamplayer->insert ( $dataTeamPlayer );
											//echo "----->Inserting New Current Team for Orphan : " . $playerxml ['id'] . "-> " . $rowTeamCurrentFeed['team_id']  . "<br>";
										} else {
											//echo $playerxml ['id'] ." Exists --- ORPHAN goalface but feed team: <strong>".$xmlPlayer->teamid." -- ".$xmlPlayer->team ."</strong> not mapped<br>" ;
										}
										
									} else {
										
										if ($rowTeamCurrentFeed == null) {
											//echo $playerxml ['id'] ." Exists --- but current team feed team: <strong>".$xmlPlayer->teamid ." -- ".$xmlPlayer->team . "</strong> not mapped<br>" ;
										} else {
											if ($rowTeamCurrentFeed['team_id'] != $currentteam) {
												//echo $playerxml ['id'] ." Exists --- club team goalface: " .$currentteam .",,, different Club team from feed:".$rowTeamCurrentFeed['team_id']."<br>";
												
												$dataTeamPlayerUpdate = array ('actual_team' => '0' );
												//echo "----->Updating Old Current Team : " . $playerxml ['id'] . "-> " . $currentteam . "<br>";
												$teamplayer->updateTeamPlayer ( $playerxml ['id'], $currentteam, $dataTeamPlayerUpdate );
												$dataTeamPlayer = array ('player_id' => $playerxml ['id'], 'team_id' => $rowTeamCurrentFeed['team_id'], 'actual_team' => '1' );
												//echo "----->Inserting New Current Team : " . $playerxml ['id'] . "-> " . $rowTeamCurrentFeed['team_id'] . "<br>";
												$teamplayer->insert ( $dataTeamPlayer );
												
											} else {
												//echo $playerxml ['id']. " club team goalface: " .$currentteam .",,,SAME Club team from feed:".$rowTeamCurrentFeed['team_id']."<br>";
												
							
												
											}
										}
										
									}
									//echo "==================<br>";
								} else {
									//echo "Player: " . $xmlPlayer ['id'] . " Has No Current team On feed<br>";
								}
							}
						}
					}
				}
				
			}
		}
	}
	
	
	
	
 
	
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
		$country = new Country ();
		$teamid = $this->_request->getParam ( 'team', null );
		$leagueid = $this->_request->getParam ( 'league', null );
		$logger->info("Competition: " .$leagueid);
		$teamsLeague = $team->getTeamsPerCompetitionParse($leagueid,$teamid);
		
		// iterate over all team on a league
		foreach($teamsLeague as $teamleague) {
			
			$CurrentTeamPlayers = $teamplayer->findAllPlayersByTeam($teamleague['team_id']);
			
				foreach ($CurrentTeamPlayers as $teamplayers) {
					$playerteam[] = $teamplayers['player_id'];
				}
				
				//get team information array
				$rowTeam = $team->fetchRow ( 'team_id = ' . $teamleague['team_id'] );
				

				echo "<strong>".$rowTeam['team_name']."</strong><br><br>";
				if ($rowTeam ['team_gs_id'] != null) {
					
					$xml = $this->getgsfeed('soccerstats/team/'.$rowTeam ['team_gs_id']);
					
					foreach ( $xml->team as $teamxml ) {
						foreach ( $teamxml->squad->player as $playerxml ) {
							
							echo "<strong>".$playerxml ['id']. " ". $playerxml ['name']."</strong><br>";
							
							$player_feed_array[] = $playerxml ['id'];
							//check if player exists GoalFace DB
							$rowPlayer = $player->fetchRow ( 'player_id = ' . $playerxml ['id'] );
							
							//fetch player xml info from feed
							$xmlplayer = $this->getgsfeed('soccerstats/player/'.$playerxml ['id']);
							
							if ($rowPlayer == null) {
							  /*Player Doesnt Exist*/
							  if ($xmlplayer != null || $xmlplayer != '') {
							  	
							  	foreach ($xmlplayer->player as $xmlPlayer) {
							  		
					  				$rowBirthCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->birthcountry . '"' );
									$rowNationalityCountry = $country->fetchRow ( 'country_name = "' . $xmlPlayer->nationality . '"' );
									$mydate = date ( "Y-m-d", strtotime ( $xmlPlayer->birthdate ) );
									$arr_height = explode ( " ", $xmlPlayer->height, 2 );
									$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
									$player_height = $arr_height [0];
									$player_weight = $arr_weight [0];
									if ($xmlPlayer->position == 'Attacker') {
										$player_position = 'Forward';
									} else {
										$player_position = $xmlPlayer->position;
									}
									$dataPlayer = array ('player_id' => $xmlPlayer ['id'], //1
										'player_firstname' => $xmlPlayer->firstname, //2
										'player_middlename' => '', 
										'player_lastname' => $xmlPlayer->lastname, //3
										'player_common_name' => $xmlPlayer->name, 
										//'player_name_short' => mb_convert_encoding($playerxml ['name'], "ISO-8859-1", mb_detect_encoding($playerxml ['name'], "UTF-8, ISO-8859-1, ISO-8859-15", true)),
										'player_name_short' => utf8_encode($playerxml ['name']),
										'player_dob' => $mydate, //4
										'player_dob_city' => $xmlPlayer->birthplace, 
										'player_type' => 'player', //5
										'player_country' => $rowBirthCountry ['country_id'], //5
										'player_nationality' => $rowNationalityCountry ['country_id'], //5
										'player_position' => $player_position, //5
										'player_creation' => $today, 
										'player_height' => $player_height, 
										'player_weight' => $player_weight );
							  	}
								
							  	//Zend_Debug::dump($dataPlayer);
								
							  	//insert new player
							  	$player->getAdapter()->query("SET NAMES 'utf8'");

							  	$player->insert ($dataPlayer);
								echo "Inserting new player: " . $xmlPlayer ['id'] . "-> " . $xmlPlayer->name . "<br>";
		
								//insert teamplayer relation
								$dataTeamPlayer = array ('player_id' => $xmlPlayer ['id'],'team_id' => $teamid, 'actual_team' => '1' );	
								$teamplayer->insert ( $dataTeamPlayer );
								echo "Inserting new teamplayer: " . $xmlPlayer ['id'] . "-> " . $teamid . "<br>";
							  }
							} else {
							  /*Player Exist*/
								echo "<br><strong>EXISTS</strong><br>";
							
								
								if ($xmlplayer != null || $xmlplayer != '') {
									foreach ($xmlplayer->player as $xmlPlayer) {
		
										//Updating Player Details Missing Fields
										$mydate = date ( "Y-m-d", strtotime ( $xmlPlayer->birthdate ) );
										$arr_height = explode ( " ", $xmlPlayer->height, 2 );
										$arr_weight = explode ( " ", $xmlPlayer->weight, 2 );
										$player_height = $arr_height [0];
										$player_weight = $arr_weight [0];
										if ($xmlPlayer->position == 'Attacker') {
											$player_position = 'Forward';
										} else {
											$player_position = $xmlPlayer->position;
										}
										
											$dataPlayer = array (
												'player_dob' => $mydate, //4
												'player_dob_city' => $xmlPlayer->birthplace, 
												'player_position' => $player_position, //5
											    //'player_name_short' => mb_convert_encoding($playerxml ['name'], "ISO-8859-1", mb_detect_encoding($playerxml ['name'], "UTF-8, ISO-8859-1, ISO-8859-15", true)),
												'player_height' => $player_height, 
												'player_weight' => $player_weight );
		
										$player->updatePlayer ( $playerxml ['id'], $dataPlayer );
										
										//Find current team (club) per each player from feed against DB
										$currentteam = $teamplayer->findCurrentTeamPlayer( $playerxml ['id'], $rowTeam['team_type'],1);
										
										if ($currentteam != null) {
											if($currentteam != $teamid) {
												echo "----->Player has a Different Current Team on DB : " . $playerxml ['id'] . "-> " . $currentteam . "<br>";
												$dataTeamPlayerUpdate = array ('actual_team' => '0' );
												echo "----->Updating Old Current Team - actual = cero : " . $playerxml ['id'] . "-> " . $currentteam . "<br>";
												$teamplayer->updateTeamPlayer ( $playerxml ['id'], $currentteam, $dataTeamPlayerUpdate );
												$dataTeamPlayer = array ('player_id' => $playerxml ['id'], 'team_id' => $teamid, 'actual_team' => '1' );
												$teamplayer->insert ( $dataTeamPlayer );
												echo "----->Inserting New Current Team - actual = 1: " . $playerxml ['id'] . "-> " . $teamid . "<br>";
											} else {
												echo "----->Player Already has Same Current Team : " . $playerxml ['id'] . "-> " . $teamid . "<br>";
											}
										} else {
											//No Current team ophan - Update to current to this team
											 echo $xmlPlayer ['id'] ." - ". $xmlPlayer->name . " -- Orphan Player with No current club team in DB:<br>";
											 $dataTeamPlayer = array ('player_id' => $playerxml ['id'], 'team_id' => $teamid, 'actual_team' => '1' );
											 $teamplayer->insert ( $dataTeamPlayer );
											 echo "----->Inserting New Current Team : " . $playerxml ['id'] . "-> " . $teamid . "<br>";
										}
										
		
									}
								}	
							}
		
						}
		
					}
		
					echo "<br>Orphans from DB<br><br>";
					$players_relocate = array_diff($playerteam,$player_feed_array);
		
					if ($players_relocate != null) {
						 foreach ($players_relocate as $playerorphan) {
						 	//fetch player info from feed to update
						 	$xmlplayerfeed = null;
							$xmlplayerfeed = $this->getgsfeed('soccerstats/player/'.$playerorphan);
							if ($xmlplayerfeed != null || $xmlplayerfeed != '') {
								foreach ($xmlplayerfeed->player as $xmlPlayer) {
									if ($xmlPlayer->teamid != '') {
										$rowTeamNewCurrent = $team->fetchRow ( 'team_gs_id = ' . $xmlPlayer->teamid );
										//Update to new team
										$dataTeamPlayerUpdate = array ('actual_team' => '0' );
										$teamplayer->updateTeamPlayer ( $playerorphan , $teamid, $dataTeamPlayerUpdate );
										//new team
										$dataTeamPlayerUpdateNew = array ('player_id' => $playerorphan, 'team_id' => $rowTeamNewCurrent['team_id'], 'actual_team' => '1' );
										$teamplayer->insert ( $dataTeamPlayerUpdateNew );
										echo $xmlPlayer->name ." ".$playerorphan . "----->Updating old to (0) ".$teamid ." and (1) Current Team : -> " . $rowTeamNewCurrent['team_id'] . "<br>";
									} else {
										// no current team in feed , update current team to 0 and leave it
										$dataTeamPlayerUpdate = array ('actual_team' => '0' );
										$teamplayer->updateTeamPlayer ( $playerorphan , $teamid, $dataTeamPlayerUpdate );
										echo $xmlPlayer->name ." ".$playerorphan . "----->Updating old to (0) ".$teamid ." No current team on feed <br>";
									}
		
								}
							}
						 }
					}
		
				} else {
					echo "Team Id :" . $teamid . " has not been mapped";
				}
			
			$logger->info("Team Updated : " .$teamleague['team_id']. " - ".$teamleague['team_name']);
			echo "Team Updated : " .$teamleague['team_id']. " - ".$teamleague['team_name']."<BR>";
		}

	}
	
	//getmapteams/country/argentina1.xml
	public function  getmapteamsAction() {
		$standing_country = $this->_request->getParam ( 'country', null );
		$xml = $this->getgsfeed('standings/'.$standing_country.'.xml');
		$teamdata = new Team ();
		
		//Zend_Debug::dump($xml);
		foreach ($xml->tournament->team as $team) {
			$rowTeam = $teamdata->fetchRow ( 'team_gs_id = ' . $team['id'] );
			echo "UPDATE team SET team_gs_id = " . $team['id'] ." WHERE team_id = ". $rowTeam['team_id'] .";  ". $team['name'] . "<br>";
		}
/*		$rowTeam ['team_gs_id'] = 13904; 
		$xml = $this->getgsfeed('soccerstats/team/'.$rowTeam ['team_gs_id']);
				
				foreach ( $xml->team as $teamxml ) {
					foreach ( $teamxml->squad->player as $playerxml ) {
						echo $playerxml['id']."--".$playerxml['name']."-->".mb_convert_encoding($playerxml ['name'], "ISO-8859-1", mb_detect_encoding($playerxml ['name'], "UTF-8, ISO-8859-1, ISO-8859-15", true))."<br>";
					}
				}*/
	}
	
	public function getplayerseasonstatsAction() {
		$team = new Team ();
		$player = new Player ();
		$league = new LeagueCompetition ();
		$playerSeasonStat = new TeamPlayerStats ();
		$seasonId = ( int ) $this->_request->getParam ( 'season', 0 );
		$season_name = $this->_request->getParam ( 'seasonname', null );
		$leaguename = $this->_request->getParam ( 'leaguename', null );
		$teamId = ( int ) $this->_request->getParam ( 'team', 0 );
		$playerId = ( int ) $this->_request->getParam ( 'player', 0 );
		$teamsbyseason = $team->selectTeamsBySeason ( $seasonId );
		
		// All playes on current teams per season
		if ($seasonId != 0) {
			//Zend_Debug::dump($teamsbyseason);
			foreach ( $teamsbyseason as $teamseason ) {
				if ($teamseason ['team_gs_id'] != null) {
					$url = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/' . $teamseason ['team_gs_id'];
					//echo $teamseason['team_name']."-<a href='".$url."'>".$url."</a><BR>";
					$request_url = file_get_contents ( $url );
					
					$xml = new SimpleXMLElement ( $request_url );
					foreach ( $xml->team as $teamxml ) {
						//echo $teamxml->name."<BR>";
						foreach ( $teamxml->squad->player as $playerxml ) {
							if ($playerxml ['position'] != 'G') {
								$urlplayer = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/player/' . $playerxml ['id'];
								echo "<b>Player :</b> " . $playerxml ['id'] . "-" . $playerxml ['name'] . " - " . $urlplayer . "<BR>";
								$request_urlplayer = file_get_contents ( $urlplayer );
								if (! empty ( $request_urlplayer )) {
									$xmlplayer = new SimpleXMLElement ( $request_urlplayer );
									foreach ( $xmlplayer->player->statistic->club as $playerstat ) {
										if ($playerstat ['season'] == '2011/2012') {
											$competition = $league->findCompetitionByGoalserveId ( $playerstat ['league_id'] );
											if ($competition != null) {
												
												$readcards = $playerstat ['redcards'] + $playerstat ['yellowred'];
												$rowTeam = $team->fetchRow ( 'team_gs_id = ' . $playerstat ['id'] );
												$playerStatArray = array (
												'player_id' => $playerxml ['id'], 
												'First_Name' => $xmlplayer->player->firstname, 
												'Last_Name' => $xmlplayer->player->lastname, 
												'team_id' => $rowTeam ['team_id'], 
												'team_name' => htmlspecialchars ( $playerstat ['name'], ENT_COMPAT, 'UTF-8' ), 
												'season_id' => null, 
												'season_name' => $playerstat ['season'], 
												'competition_id' => $competition ['competition_id'], 
												'competition_name' => $competition ['competition_name'], 
												'minp' => $playerstat ['minutes'], 
												'gp' => $playerstat ['appearences'], 
												'sb' => $playerstat ['substitute_in'], 
												'gl' => $playerstat ['goals'], 
												'yc' => $playerstat ['yellowcards'], 
												'rc' => $readcards );
												//Zend_Debug::dump($playerStatArray);
												//insert in playerstars
												$playerSeasonStat->insert ( $playerStatArray );
											
											}
										}
									}
								}
							}
						}
					}
				
				}
			}
		}
		// All playes on current team
		if ($teamId != 0) {
			$rowTeam = $team->fetchRow ( 'team_id = ' . $teamId );
			if ($rowTeam ['team_gs_id'] != null) {
				$url = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/team/' . $rowTeam ['team_gs_id'];
				$request_url = file_get_contents ( $url );
				$xml = new SimpleXMLElement ( $request_url );
				foreach ( $xml->team as $teamxml ) {
					foreach ( $teamxml->squad->player as $playerxml ) {

							$urlplayer = 'http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/soccerstats/player/' . $playerxml ['id'];
							echo "<b>Player :</b> " . $playerxml ['id'] . "-" . $playerxml ['name'] . " - <a href='" . $urlplayer . "'>" . $urlplayer . "</a><BR>";
							$request_urlplayer = file_get_contents ( $urlplayer );
							if (! empty ( $request_urlplayer )) {
								$xmlplayer = new SimpleXMLElement ( $request_urlplayer );

								foreach ( $xmlplayer->player->statistic->club as $playerstat ) {
									if ($playerstat ['season'] == $season_name && $playerstat ['league']  == $leaguename) {
										$competition = $league->findCompetitionByGoalserveId ( $playerstat ['league_id'] );
										if ($competition != null) {
											//echo "gs_id: " . $playerstat['id']." - gf_id : ". $competition['competition_id']." - league :".$competition['competition_name']."-".$playerstat['season']."<BR>";
											$rowTeam = $team->fetchRow ( 'team_gs_id = ' . $playerstat ['id'] );
											$readcards = $playerstat ['redcards'] + $playerstat ['yellowred'];
											$playerStatArray = array ('player_id' => $playerxml ['id'], 'First_Name' => $xmlplayer->player->firstname, 'Last_Name' => $xmlplayer->player->lastname, 'team_id' => $rowTeam ['team_id'], 'team_name' => htmlspecialchars ( $playerstat ['name'], ENT_COMPAT, 'UTF-8' ), 'season_id' => null, 'season_name' => $playerstat ['season'], 'competition_id' => $competition ['competition_id'], 'competition_name' => $competition ['competition_name'], 'minp' => $playerstat ['minutes'], 'gp' => $playerstat ['appearences'], 'sb' => $playerstat ['substitute_in'], 'gl' => $playerstat ['goals'], 'yc' => $playerstat ['yellowcards'], 'rc' => $readcards );
											//Zend_Debug::dump($playerStatArray);
											//insert in playerstars
											$playerSeasonStat->insert ( $playerStatArray );
										
											//echo "gs_id: " . $playerstat['id']." - gf_id : ". $competition['competition_id']." - league :".$competition['competition_name']."-".$playerstat['season']." INSERTED<BR>";
										}
									}
								}
							}

					}
					echo "<HR>";
				}
			}
		
		}
	
	}

}
?>