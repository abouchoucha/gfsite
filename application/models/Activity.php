<?php
require_once 'Template.php';
require_once 'SendEmail.php';
//require_once 'library/Zrad/Zrad_Facebook.php';
require_once 'Zrad/cFacebook.php';

/**
 * Activity
 *
 * @author Juan Carlos Vasquez
 * @version
 */

/**
 * FOR testing :
 * Comment OUT = $facebook->api
 * Comment OUT = sendmail()
 */


class Activity extends Zend_Db_Table_Abstract {

    protected $_name = 'activity';
    protected $_primary = "activity_id";
    protected $db;
    private static $logger;
    private static $directory;
	private static $languages;

    function init() {
        $this->db = $this->getAdapter();
        self::$logger = Zend_Registry::get ( "logger" );
        Zend_Loader::loadClass ( 'Zend_Debug' );
        $config = Zend_Registry::get ( 'config' );
		self::$directory = './application/configuration/translations';
		self::$languages = array_keys($config->languages->toArray());
		//$this->db->query("SET NAMES 'utf8'");
    }


    // Added for Alert Translation JV 03-27
    private function setuplanguage($lang) {
        if(!in_array($lang, self::$languages)) {
            $lang = 'en';
        }
        $file = self::$directory. '/'. $lang . '.php';
        if(file_exists($file))
        {
           $translationstrings = include $file;
        } else {
           $translationstrings = include self::$directory . '/en.php';
        }
		if(empty($translationstrings) && !is_array($translationstrings)) {
            throw new Exception('Missing $translationStrings in language file');
        }
        $translate = new Zend_Translate('array', $translationStrings, $lang);
        return $translate;
    }

    public function insertUserActivityByActivityType(
													$activityTypeId, //1
													$variablesToReplace , //2
													$loggedUserId = null, //3
													$isShownHome = 1, //4
													$playerId = null, //5
													$teamId = null, //6
													$dateEvent = null, //7
													$image = null, //8
													$matchId = null, //9
													$alert = null,//10
													$competition_id = null //11
													)
	{

		//update table Activity with activity Type
		//parameters to pass: activity Type + parameters to update
		$rateProfile = new ActivityType();
		$activityType = $rateProfile->find($activityTypeId);
		$currentActivity = $activityType->current();
		$customMessage = $currentActivity->activitytype_text;

		$templater = new Template();
		$newCustomMessage = $templater->parse_variables($customMessage, $variablesToReplace);

		if (!is_null($dateEvent)) {

			if(count(explode("/",$dateEvent))>1){
				$formato = 'd/m/Y H:i:s';
				$dateEvent = DateTime::createFromFormat($formato, $dateEvent);
				$dateEvent = $dateEvent->format('Y-m-d H:i:s');
			}else if(count(explode(",",$dateEvent))>1){
				//Jun 9, 2012 5:11:00 PM
				$formato = "M d, Y h:i:s A";
				$dateEvent = DateTime::createFromFormat($formato, $dateEvent);
				$dateEvent = $dateEvent->format('Y-m-d H:i:s');
			}
			$newDate = $dateEvent;

		}else{

			$dateAux = new Zend_Date ();
			$newDate = $dateAux->toString('Y-M-d H:m:s');

		}

		//echo '<br>Fecha que ingresar√° a la BD:'.$newDate;
		//self::$logger->debug ( "->Date Out:".$dateEvent);

		//INSERT Activities - update the Activity Table
		$dataNewActivity = array(
			'activity_activitytype_id' => $currentActivity->activitytype_id,
			'activity_date' => $newDate,
			'activity_user_id' => $loggedUserId,
			'activity_icon' => $currentActivity->activitytype_icon,
			'activity_text' => $newCustomMessage,
			'activity_home_display' => $isShownHome,
			'activity_player_id' => $playerId,
			'activity_team_id' => $teamId,
			'activity_image' => $image,
			'activity_match_id' => $matchId
		);

		// INSERT ACTIVITIES on Database
		$activtityUser = new Activity();
		$activtityUser->insert($dataNewActivity);

		// JUST FOR TEST ->>>>>
		//$alert = null;

		// Start - Send Alerts
		if ($alert != null) {

			self::$logger->debug ( "==>START EMAIL and FB alert Loop <==");
			self::$logger->debug ( "=================================");
			$config = Zend_Registry::get('config');
			$app_id = $config->facebook->appid;
			$app_secret = $config->facebook->secret;
			$fb_goalface_id = $config->facebook->goalface->id;
			$fb_goalface_language = $config->facebook->goalface->language;
			$fb_goalface_access_token = $config->facebook->accessToken;

			//****************** TEAMS ************************//
			//               RESULTS ALERTS                    //
			// *************************************************//

			if ($teamId != null) {
				echo '<br>act id: '.$currentActivity->activitytype_id.' - team:'.$teamId;
				self::$logger->debug ('->Activitytype_id: '.$currentActivity->activitytype_id.' - teamId:'.$teamId);
				$typeOfResult = null;
				$newArray = null;

				//Debug Error when team B (your fav team) losses
				if (Constants::$_MATCH_SCORE_TEAM_A_WON == $activityTypeId) {
					$newArray = array('typeOfResult' => 'defeated');
				} else if (Constants::$_MATCH_SCORE_TEAM_B_LOST == $activityTypeId) {
					$newArray = array('typeOfResult' => 'lost to');
				} else if (Constants::$_MATCH_SCORE_TEAM_DRAW == $activityTypeId) {
					$newArray = array('typeOfResult' => 'registered a draw against');
				}

				if($newArray!=null) {

					echo "<BR>Before Iterating team alerts:" . $teamId;
					self::$logger->debug ('->Before Iterating team alerts:' . $teamId);

					$userteam = new UserTeam();
					$teamAlerts = $userteam->getUserTeamIdEmailAlerts($teamId);
					$teamAlertsFaceBook = $userteam->getUserTeamIdFaceBookAlerts($teamId);

					$mail = new SendEmail();
					$mail->set_subject($variablesToReplace['teama_name'] . ' Match Alert');
					$mail->set_from($config->email->confirmation->from);
					$mail->set_template('teamresultalert');

					echo '<br>Score: '.$variablesToReplace['score'].'</br>';

					$variablesToReplaceMail = array_merge((array) $variablesToReplace, (array) $newArray);
					$variablesToReplaceMail['match_seoname'] = $_SERVER ['SERVER_NAME'] .Zend_Registry::get("contextPath") . $variablesToReplaceMail['match_seoname'];
					$variablesToReplace = array_merge((array) $variablesToReplace, (array) $newArray);


					// ========================= EMAIL ====================================//
					// Send to Users with Team Alerts                                      //
					// ========================================================================//
					foreach ($teamAlerts as $alert) {
						echo '<br><<MATCH ALERTS>>Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .$alert['alert_frecuency_type']." for team:" .$alert['team_id'];
						$mail->set_to($alert["email"]);
						$variablesToReplaceMail['userEmail'] = $alert["email"];
						$variablesToReplaceMail['alertSettingsUrl'] = "http://" . $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . "/editprofile/" . $alert['screen_name'] . "/settings";
						$mail->set_variablesToReplace($variablesToReplaceMail);
						$mail->sendMail();
					}

					$variablesToReplace['match_seoname']=$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") .$variablesToReplace['match_seoname'];
					echo '<br>UrlPos_01'.$variablesToReplace['match_seoname'];

					/** FACEBOOK -User Facebook Wall with Team Alerts */

					self::$logger->debug ( "----> Send Teams(Activity) Alert <----");
					foreach ($teamAlertsFaceBook as $alert) {

						self::$logger->debug ( "------->appId:".$app_id);
						self::$logger->debug ( "------->secret:".$app_secret);
						self::$logger->debug ( "------->message: GoalFace Alert");
						self::$logger->debug ( "------->name:".$variablesToReplace['teama_name']." - ".$variablesToReplace['typeOfResult']." - ".$variablesToReplace['teamb_name']. "(".$variablesToReplace['score'].")");
						self::$logger->debug ( "------->link:".$variablesToReplace['match_seoname']);
						self::$logger->debug ( "------->picture: http://www.goalface.com/public/images/GoalFaceBall200x200.gif");
						self::$logger->debug ( "------->actions: http://".$variablesToReplace['match_seoname']);
						$facebook = new Facebook(array(
													'appId'  => $app_id,
													'secret' => $app_secret
									));

						try{
							$parametros=array(
							'message' =>  'GoalFace Alert',
							'name' => $variablesToReplace['teama_name'].' '.$variablesToReplace['typeOfResult'].' '.$variablesToReplace['teamb_name'].' ('.$variablesToReplace['score'].')',
							'link' => $variablesToReplace['match_seoname'],
							'picture' => 'http://www.goalface.com/public/images/teamlogos/'.$teamId.'.gif',
							'actions' => array(array('name' => 'Ver Detalle!',
							'link' => 'http://'.$variablesToReplace['match_seoname']))
										);

						  	$res = $facebook->api('/'.$alert["facebookid"].'/feed', 'POST', $parametros);

						} catch (FacebookApiException $e) {
							echo ' No se pudo enviar -> /'.$alert["facebookid"].'/feed Message:'.$e->getMessage();
							self::$logger->debug ( '----*****> Error Write in Team(Activity) Alert: No se pudo enviar -> '.$alert["facebookid"].'Message:'.$e->getMessage());
							echo ' Code:'.$e->getCode();
							echo ' Line:'.$e->getLine();
						}
					}
					 self::$logger->debug ("----> End Teams(Activity)Alert <----");


					// ========================= FACEBOOK ====================================//
					// Write Team Alerts in COMPETITION FB Pages                              //
					// =======================================================================//
					self::$logger->debug ( "----> Send Write in Page Competition(Activity) Alert <----");

					$link='http://'.$variablesToReplace['match_seoname'];
					if($competition_id!=null){

						echo '<br>Write in Facebook Pages COMPETITION <br>';
						$league = new LeagueCompetition ();
						$rowset = $league->selectLeaguePageFanFaceBookAlerts( $competition_id );

						if ($rowset != null) {
							foreach ($rowset as $row) {
								// get translation ready for $row['language_code'] language
								$translate = $this->setuplanguage($row['language_code']);

								self::$logger->debug ( "------->Page Name:".$row['fbpage_details']);
								self::$logger->debug ( "------->Page ID:".$row['fbpage_id']);
								self::$logger->debug ( "------->appId:".$app_id);
								self::$logger->debug ( "------->secret:".$app_secret);
								self::$logger->debug ( "------->access_token:".$row['facebookaccesstoken']);
								self::$logger->debug ( "------->message:".$variablesToReplace['teama_name'].' '.$translate->_($variablesToReplace['typeOfResult']).' '.$variablesToReplace['teamb_name'].' ('.$variablesToReplace['score'].')');
								self::$logger->debug ( "------->link:".$link);
								self::$logger->debug ( "------->language:".$row['language_code']);


								$facebook = new Facebook(array(
									'appId'  => $app_id,
									'secret' => $app_secret
								));

								echo '<br>link:'.$link;
								try{
									$args=array(
										'access_token' => $row['facebookaccesstoken'],
										'message' => $variablesToReplace['teama_name'].' '.$translate->_($variablesToReplace['typeOfResult']).' '.$variablesToReplace['teamb_name'].' ('.$variablesToReplace['score'].')',
										'link' => $link
									);

									$post_id = $facebook->api("/".$row['fbpage_id']."/links","post",$args);

										echo '<br>Escribi√≥ en COMPETITION:'.$row['fbpage_id'];
								}catch (FacebookApiException $e){
									echo ' No se pudo enviar -> '.$competition_id.' - idPage:'.$row['fbpage_id'].'Message:'.$e->getMessage();
									self::$logger->debug ( '----*****> Error Write in Page COMPETITION(Activity) Alert: No se pudo enviar -> '.$competition_id.' - idPage:'.$row['fbpage_id'].'Message:'.$e->getMessage());
									echo ' Code:'.$e->getCode();
									echo ' Line:'.$e->getLine();
								}
							}
						}
					}
					self::$logger->debug ( "----> End Write in Page Competition(Activity) Alert <----");

					// ========================= FACEBOOK ====================================//
					// Write Team Alerts in TEAM FB Pages                            //
					// =======================================================================//
					self::$logger->debug ( "----> Send Write in Page Team(Activity) Alert <----");

					echo '<br>Antes de escribir en la p√°gina de TEAM'.$teamId;
					echo '<br>Write in Facebook Pages TEAM ';
					$team = new Team();

					// Modified on Team Model to accept a language 03/27 by JV
					$rowset = $team->selectTeamPageFanFaceBookAlerts ( $teamId );
					if ($rowset != null) {
					foreach ($rowset as $row) {
						// get translation ready for $row['language_code'] language
						$translate = $this->setuplanguage($row['language_code']);

						self::$logger->debug ( "------->Page Name:".$row['fbpage_details']);
						self::$logger->debug ( "------->Page ID:".$row['fbpage_id']);
						self::$logger->debug ( "------->appId:".$app_id);
						self::$logger->debug ( "------->secret:".$app_secret);
						self::$logger->debug ( "------->access_token:".$row['facebookaccesstoken']);
						self::$logger->debug ( "------->message:".$variablesToReplace['teama_name'].' '.$translate->_($variablesToReplace['typeOfResult']).' '.$variablesToReplace['teamb_name'].' ('.$variablesToReplace['score'].')');
						self::$logger->debug ( "------->link:".$link);
						self::$logger->debug ( "------->language:".$row['language_code']);

						$facebook = new Facebook(array(
							'appId'  => $app_id,
							'secret' => $app_secret
						));

						echo '<br>link:'.$link;
						try{

							$args=array(
								'access_token'  => $row['facebookaccesstoken'],
								'message' => $variablesToReplace['teama_name'].' '.$translate->_($variablesToReplace['typeOfResult']).' '.$variablesToReplace['teamb_name'].' ('.$variablesToReplace['score'].')',
								'link' => $link
							);

							$post_id = $facebook->api("/".$row['fbpage_id']."/links","post",$args);

						}catch (FacebookApiException $e){
							echo ' No se pudo enviar -> '.$teamId.' - idPage:'.$row['fbpage_id'].'Message:'.$e->getMessage();
							self::$logger->debug ( '----*****> Error Write in Page Team(Activity) Alert: No se pudo enviar -> '.$teamId.' - idPage:'.$row['fbpage_id'].'Message:'.$e->getMessage());
							echo ' Code:'.$e->getCode();
							echo ' Line:'.$e->getLine();
						}
					}
					}
					self::$logger->debug ( "----> End Write in Page Team(Activity) Alert <----");

				}
			}


			//****************** PLAYERS ************************//
			//               EMAIL ALERTS                 //
			// *************************************************//

			if ($playerId != null) {
				echo '<br>act id-: '.$currentActivity->activitytype_id.' - player:'.$playerId;

				$goalsArray = array(Constants::$_GOAL_SCORED_ACTIVITY,
							Constants::$_OWNGOAL_SCORED_ACTIVITY,
							Constants::$_PENALTY_SCORED_ACTIVITY,
							Constants::$_DOUBLE_PLAYER_ACTIVITY,
							Constants::$_HAT_TRICK_PLAYER_ACTIVITY,
							Constants::$_CLEAN_SHEET_PLAYER_ACTIVITY);
				$cardsArray = array(Constants::$_RED_CARD_ACTIVITY,
							Constants::$_YELLOW_CARD_ACTIVITY,
							Constants::$_2ND_YELLOW_CARD_ACTIVITY);
				$sustitutionArray = array(Constants::$_SUBSTITUTE_IN_ACTIVITY,
										  Constants::$_SUBSTITUTE_OUT_ACTIVITY);

				$userplayer = new UserPlayer();

				$mail = new SendEmail();
				$mail->set_from($config->email->confirmation->from);
				$mail->set_template('playereventalert');

				/** EMAIL - Send to Users with Player Alerts */
				$playerAlerts = $userplayer->getUserPlayerIdEmailAlerts($playerId);
				foreach ($playerAlerts as $alert) {

					$playerFrequencyTypes = explode(',', $alert['alert_frecuency_type']);
					echo "<BR>Before Iterating Player event alerts:" . $playerId . "and activity type id = " . $activityTypeId;
					//echo "Player Name:" . $variablesToReplaceMail['player_name'];
					//$userRow = $user->findUserUnique($alert["user_id"]);
					$mail->set_to($alert["email"]);
					$typeOfResult = null;
					$newArray = null;
					if (in_array($activityTypeId, $goalsArray)) {
						if (in_array(Constants::$_PLAYER_GOAL_ALERTS, $playerFrequencyTypes)) {
							echo '<br>-->PLAYER GOAL ALERTS-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_GOAL_ALERTS." for player:" .$alert['player_id'];
							$mail->set_subject($variablesToReplace['player_name'] . ' Goal Alert');
							if (Constants::$_GOAL_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a goal in');
							} else if (Constants::$_OWNGOAL_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored an own goal in');
							} else if (Constants::$_PENALTY_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a penalty in');
							} else if (Constants::$_DOUBLE_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a double in');
							} else if (Constants::$_HAT_TRICK_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a hat-trick in');
							} else if (Constants::$_CLEAN_SHEET_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'registered a clean sheet in');
							}
						}
					}else if (in_array($activityTypeId, $cardsArray)) {
						if (in_array(Constants::$_PLAYER_CARD_ALERTS, $playerFrequencyTypes)) {
							echo '<br>-->PLAYER CARD ALERTS-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_CARD_ALERTS." for player:" .$alert['player_id'];
							$mail->set_subject($variablesToReplace['player_name'] . ' Match Discisplinary Alert');
							if (Constants::$_RED_CARD_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'received a red card in');
							} else if (Constants::$_YELLOW_CARD_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'received a yellow card in');
							} else if (Constants::$_2ND_YELLOW_CARD_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'received a 2nd yellow (red) card in');
							}
						}
					}else if (in_array($activityTypeId, $sustitutionArray)) {
						if (in_array(Constants::$_PLAYER_SUSTITUTION, $playerFrequencyTypes)) {
							echo '<br>-->PLAYER SUSTITUTION-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_SUSTITUTION." for player:" .$alert['player_id'];
							$mail->set_subject($variablesToReplace['player_name'] . ' Match Sustitution Alert');
							if (Constants::$_SUBSTITUTE_IN_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'in Activity');
							} else if (Constants::$_SUBSTITUTE_OUT_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'out Activity');
							}
						}
					}else if (Constants::$_PLAYER_LINE_UP_ACTIVITY == $activityTypeId) {
						if (in_array(Constants::$_PLAYER_APPEREANCES_ALERTS, $playerFrequencyTypes)) {
							//echo '<br>-->PLAYER APPEREANCES ALERTS-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_APPEREANCES_ALERTS." for player:" .$alert['player_id'];
							$mail->set_subject($variablesToReplace['player_name'] . ' Match Appearance Alert');
							$newArray = array('typeOfPlayerEvent' => 'started');
						}
					}

					if ($newArray != null) {
						$variablesToReplaceMail = array_merge((array) $variablesToReplace, (array) $newArray);
						$variablesToReplaceMail['player_name_seo'] = "http://" . $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $variablesToReplaceMail['player_name_seo'];
						$variablesToReplaceMail['match_url'] = "http://" . $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $variablesToReplaceMail['match_url'];
						$variablesToReplaceMail['userEmail'] = $alert["email"];
						$variablesToReplaceMail['alertSettingsUrl'] = "http://" . $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . "/editprofile/" . $alert['screen_name'] . "/settings";
						$mail->set_variablesToReplace($variablesToReplaceMail);
						$mail->sendMail();
					}
				}

				// ========================= FACEBOOK ====================================//
				// Write in Users Facebook Wall with Players Alerts   - TO DISCUSS                      //
				// =======================================================================//

				self::$logger->debug ( "----> Send Player(Activity) Alert <----");
				$playerAlertsFaceBook = $userplayer->getUserPlayerIdFaceBookAlerts($playerId);
				foreach ($playerAlertsFaceBook as $alert) {
					$playerFrequencyTypes = explode(',', $alert['alert_frecuency_type']);
					$typeOfResult = null;
					$newArray = null;
					$title='';
					if (in_array($activityTypeId, $goalsArray)) {
						if (in_array(Constants::$_PLAYER_GOAL_ALERTS, $playerFrequencyTypes)) {
							echo '<br>-->PLAYER GOAL ALERTS-->Sending FACEBOOK for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_GOAL_ALERTS." for player:" .$alert['player_id'];
							//$mail->set_subject($variablesToReplace['player_name'] . ' Goal Alert');
							$title = $variablesToReplace['player_name'] . ' Goal Alert';
							if (Constants::$_GOAL_SCORED_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'scored a goal in');
							} else if (Constants::$_OWNGOAL_SCORED_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'scored an own goal in');
							} else if (Constants::$_PENALTY_SCORED_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'scored a penalty in');
							} else if (Constants::$_DOUBLE_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a double in');
							} else if (Constants::$_HAT_TRICK_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a hat-trick in');
							} else if (Constants::$_CLEAN_SHEET_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'registered a clean sheet in');
							}
						}
					}else if (in_array($activityTypeId, $cardsArray)) {
						if (in_array(Constants::$_PLAYER_CARD_ALERTS, $playerFrequencyTypes)) {
							//echo '<br>-->PLAYER CARD ALERTS-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_CARD_ALERTS." for player:" .$alert['player_id'];
							//$mail->set_subject($variablesToReplace['player_name'] . ' Match Discisplinary Alert');
							$title = $variablesToReplace['player_name'] . ' Match Discisplinary Alert';
							if (Constants::$_RED_CARD_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'received a red card in');
							} else if (Constants::$_YELLOW_CARD_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'received a yellow card in');
							} else if (Constants::$_2ND_YELLOW_CARD_ACTIVITY == $activityTypeId) {
							$newArray = array('typeOfPlayerEvent' => 'received a 2nd yellow (red) card in');
							}
						}
					}else if (Constants::$_PLAYER_LINE_UP_ACTIVITY == $activityTypeId) {
						if(in_array(Constants::$_PLAYER_APPEREANCES_ALERTS, $playerFrequencyTypes)) {
							//echo '<br>-->PLAYER APPEREANCES ALERTS-->Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_APPEREANCES_ALERTS." for player:" .$alert['player_id'];
							//$mail->set_subject($variablesToReplace['player_name'] . ' Match Appearance Alert');
							$title = $variablesToReplace['player_name'] . ' Match Appearance Alert';
							$newArray = array('typeOfPlayerEvent' => 'started');
						}
					}

					if ($newArray != null) {
						$message = $variablesToReplace['player_name'].' '.$newArray['typeOfPlayerEvent'];
						//$variablesToReplace['match_url'] = $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $variablesToReplace['match_url'];
						$variablesToReplaceTemp=$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $variablesToReplace['match_url'];
						echo '<br>PosUrl_03'.$variablesToReplaceTemp['match_url'];
						$facebook = new Facebook(array(
						  'appId'  => $app_id,
						  'secret' => $app_secret
						));

						try{
							self::$logger->debug ( "------->appId:".$app_id);
							self::$logger->debug ( "------->secret:".$app_secret);
							self::$logger->debug ( "------->message:".$message.' the '.$variablesToReplace['match_playing'].' Match.');
							self::$logger->debug ( "------->name:".$title);
							self::$logger->debug ( "------->link:".$variablesToReplaceTemp['match_url']);
							self::$logger->debug ( "------->picture:".'http://www.goalface.com/public/images/players/'.$alert['player_id'].'.jpg');
							self::$logger->debug ( "------->actions:"."http://" .$variablesToReplaceTemp['match_url']);

							$parametros=array(
							'message' => $message.' the '.$variablesToReplace['match_playing'].' Match.',
							'name' => $title,
							'link' =>  $variablesToReplaceTemp['match_url'],
							'picture' => 'http://www.goalface.com/public/images/players/'.$alert['player_id'].'.jpg',
							'actions' => array(array('name' => 'Ver Detalle!',
							'link' => "http://" .$variablesToReplaceTemp['match_url'])));

						     $res = $facebook->api('/'.$alert["facebookid"].'/feed', 'POST', $parametros);

						}catch (FacebookApiException $e){
							echo ' No se pudo enviar -> '.$alert["facebookid"].' Message:'.$e->getMessage();
							self::$logger->debug ( '----*****> Error Player(Activity) Alert: No se pudo enviar -> '.$alert["facebookid"].'Message:'.$e->getMessage());
							echo ' Code:'.$e->getCode();
							echo ' Line:'.$e->getLine();
						}
					}
				}
				self::$logger->debug ( "----> End Player(Activity) Alert <----");

				// ========================= FACEBOOK ====================================//
				// Write player Alerts in PLAYERS FB pages                              //
				// =======================================================================//

				$urlGen = new SeoUrlGen();
				$player = new Player();
				$rowset = $player->selectPlayerPageFanFaceBookAlerts ( $playerId );

				if ($rowset != null) {
					foreach($rowset as $row) {

						//self::$logger->debug ( "Language Code:  ". $row['language_code']);
						//get translation ready for $row['language_code'] language
						//$translate = $this->setuplanguage($row['language_code']);


						echo '<br><strong>Write in Facebook Pages PLAYER</strong><br>';
						self::$logger->debug ( "----> Send Write in Page Player(Activity) Alert <----");
						$typeOfResult = null;
						$title = null;
						$newArray = null;

						if (in_array($activityTypeId, $goalsArray)) {

							echo '<br>Goal' . $alert["user_id"] . ' Frecuency: ' .Constants::$_PLAYER_GOAL_ALERTS." for player:" .$alert['player_id'];
							$title = $variablesToReplace['player_name'] . ' Goal Alert. ';

							if (Constants::$_GOAL_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a goal in');
							} else if (Constants::$_OWNGOAL_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored an own goal in');
							} else if (Constants::$_PENALTY_SCORED_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'scored a penalty in');
							} else if (Constants::$_CLEAN_SHEET_PLAYER_ACTIVITY == $activityTypeId) {
								$newArray = array('typeOfPlayerEvent' => 'registered a clean sheet in');
							}

						} else if (Constants::$_PLAYER_LINE_UP_ACTIVITY == $activityTypeId) {

							$title = $variablesToReplace['player_name'] . ' Match Appearance Alert. ';
							$newArray = array('typeOfPlayerEvent' => 'started');

						} else if (Constants::$_SUBSTITUTE_IN_ACTIVITY == $activityTypeId) {

							$title = $variablesToReplace['player_name'] . ' Sub-in Alert. ';
							$newArray = array('typeOfPlayerEvent' => 'in Activity');
						}

						if($newArray!=null){


							// 11-01-12 Added JV and Miguel to validate if any instance already has www.goalface.com, if so it won't be added
							if (strrpos($variablesToReplace['match_url'],'www.goalface.com') == FALSE) {
								$clean_match_url = 'http://www.goalface.com'. $variablesToReplace['match_url'];
							} else {
								$url_Temp=$variablesToReplace['match_url'];
								$pos_bug=strrpos($url_Temp,'www.goalface.com')+strlen('www.goalface.com');
								$clean_match_url = 'http://www.goalface.com'.substr($url_Temp,$pos_bug);
							}

							self::$logger->debug ( "------->Languages:  ". $row['language']);
							$myLanguageArray = explode(',', $row['language']);
							$message = '';
							$translate = '';

							foreach ($myLanguageArray as $languages) {
								$translate = $this->setuplanguage($languages);
								$message .= $variablesToReplace['player_name'].' '.$translate->_($newArray['typeOfPlayerEvent']).' '.$variablesToReplace['match_playing'].$translate->_('match_message_suffix');
								if (true !== empty($message)) {
									$message .= ' // ';
								}
							}

							//trim last
							$message = rtrim($message, '// ');
							$message .= ' ' . $clean_match_url;

							//$message = $variablesToReplace['player_name'].' '.$translate->_($newArray['typeOfPlayerEvent']).' '.$variablesToReplace['match_playing'].$translate->_('match_message_suffix').' '.$clean_match_url;


							$link = 'http://www.goalface.com'.$urlGen->getPlayerMasterProfileUrl($row["player_nickname"], $row["player_firstname"], $row["player_lastname"], $row["player_id"], true ,$row["player_common_name"]);


							self::$logger->debug ( "------->Page Name:".$row['fbpage_details']);
							self::$logger->debug ( "------->Page ID:".$row['fbpage_id']);
							self::$logger->debug ( "------->appId:".$app_id);
							self::$logger->debug ( "------->secret:".$app_secret);
							self::$logger->debug ( "------->access_token:".$row['facebookaccesstoken']);
							self::$logger->debug ( "------->message:".$message);
							self::$logger->debug ( "------->link:".$link);

							$facebook = new Facebook(array(
								'appId'  => $app_id,
								'secret' => $app_secret
							));

							echo '<br>Message: '.$message;
							echo '<br>Link: '.$link;
							try{
								$args=array(
								'access_token'  => $row['facebookaccesstoken'],
								'message' => $message,
								'link' => $link
								);

								$post_id = $facebook->api("/".$row['fbpage_id']."/links","post",$args);

							}catch (FacebookApiException $e){
								echo ' No se pudo enviar -> '.$playerId.' - idPage:'.$row['fbpage_id'].'Message:'.$e->getMessage();
								self::$logger->debug ( '----*****> Error Page Player(Activity) Alert: No se pudo enviar -> Id_Page:'.$row['fbpage_id'].'Message:'.$e->getMessage());
								echo ' Code:'.$e->getCode();
								echo ' Line:'.$e->getLine();
							}


						}

						self::$logger->debug ( "----> End Write in Page Player(Activity) Alert <----");

					}
				}

				// ========================= FACEBOOK ====================================//
				// Write Different Alerts in GOALFACE FB page                             //
				// =======================================================================//
				self::$logger->debug ( "----> Send Write in GoalFace FB page (Activity) Alert <----");
				$newArray = null;
				$title = '';

				$row = $player->findUniquePlayer( $playerId );

				// Filter Alert Type to Posts, for now only hat-tricks and doubles
				if (in_array($activityTypeId, $goalsArray)) {

					if (Constants::$_GOAL_SCORED_ACTIVITY == $activityTypeId) {
						$title = $variablesToReplace['player_name'] . ' goal Alert. ';
						$newArray = array('typeOfPlayerEvent' => 'scored a goal in');
					} else if (Constants::$_DOUBLE_PLAYER_ACTIVITY == $activityTypeId) {
						$title = $variablesToReplace['player_name'] . ' double Alert. ';
						$newArray = array('typeOfPlayerEvent' => 'scored a double in');
					} else if (Constants::$_HAT_TRICK_PLAYER_ACTIVITY == $activityTypeId) {
						$title = $variablesToReplace['player_name'] . ' hat-trick Alert. ';
						$newArray = array('typeOfPlayerEvent' => 'scored a hat-trick in');
					}

				}

				if($newArray!=null){
					// 11-01-12 Added JV and Miguel to validate if any instance already has www.goalface.com, if so it won't be added
					if (strrpos($variablesToReplace['match_url'],'www.goalface.com') == FALSE) {
						$clean_match_url = 'http://www.goalface.com'. $variablesToReplace['match_url'];
					} else {
						$url_Temp=$variablesToReplace['match_url'];
						$pos_bug=strrpos($url_Temp,'www.goalface.com')+strlen('www.goalface.com');
						$clean_match_url = 'http://www.goalface.com'.substr($url_Temp,$pos_bug);
					}

					$translate = $this->setuplanguage($fb_goalface_language);
					$message = $variablesToReplace['player_name'].' '.$translate->_($newArray['typeOfPlayerEvent']).' '.$variablesToReplace['match_playing'].$translate->_('match_message_suffix').' '.$clean_match_url;

					$link = 'http://www.goalface.com'.$urlGen->getPlayerMasterProfileUrl($row["player_nickname"], $row["player_firstname"], $row["player_lastname"], $row["player_id"], true ,$row["player_common_name"]);

					self::$logger->debug ( "------->Page Name: GoalFace FB page");
					self::$logger->debug ( "------->Page ID:".$fb_goalface_id);
					self::$logger->debug ( "------->appId:".$app_id);
					self::$logger->debug ( "------->secret:".$app_secret);
					self::$logger->debug ( "------->access_token:".$fb_goalface_access_token);
					self::$logger->debug ( "------->message:".$message);
					self::$logger->debug ( "------->link:".$link);

					$facebook = new Facebook(array(
								'appId'  => $app_id,
								'secret' => $app_secret
								));

					echo '<br>Message: '.$message;
					echo '<br>Link: '.$link;
					try{
						$args=array(
						'access_token'  => $fb_goalface_access_token,
						'message' => $message,
						'link' => $link
						);

						$post_id = $facebook->api("/".$fb_goalface_id."/links","post",$args);

					}catch (FacebookApiException $e){
						echo ' No se pudo enviar -> '.$playerId.' - idPage:'.$fb_goalface_id.'Message:'.$e->getMessage();
						self::$logger->debug ( '----*****> Error Page Player(Activity) Alert: No se pudo enviar -> Id_Page:'.$fb_goalface_id.'Message:'.$e->getMessage());
						echo ' Code:'.$e->getCode();
						echo ' Line:'.$e->getLine();
					}
				}

			}
		} // End - Send Alerts ONLY
    }


    public function findAllActivities($userId, $limit = null, $activityTypeId = '0') {

        $sql = " select a.activity_date,'n' activitytype_icon,a.activity_text,t.activitytype_name ,uf.friend_id ,u2.screen_name,u2.main_photo,'' commentid,'' commentdeleted, a.activity_id ";
        $sql .= " from activity a,activitytype t, user u , userfriend uf ,user u2 ";
        $sql .= " where a.activity_user_id = u.user_id ";
        $sql .= " and uf.friend_id = u2.user_id ";
        $sql .= " and uf.friend_id = u.user_id and uf.user_id =" . $userId;
        $sql .= " and a.activity_activitytype_id = t.activitytype_id ";
        $sql .= " and (activitytype_category_id = 1 OR activitytype_category_id = 2 OR activitytype_category_id = 3 OR activitytype_category_id = 4 OR activitytype_category_id = 5) ";
        $sql .= " and uf.infriendfeed = 'y'";
        if ($activityTypeId != '0') {
            $sql .= " and a.activity_activitytype_id = " . $activityTypeId;
        } else {
            $sql .= " and a.activity_activitytype_id in (1,4,5,10,11,12,21,22,23,24,26,27,29,30)";
        }
        $sql .= " union ";
        $sql .= " select a.activity_date,'n' activitytype_icon,a.activity_text,t.activitytype_name ,a.activity_user_id ,u.screen_name,u.main_photo,'' commentid,'' commentdeleted, a.activity_id ";
        $sql .= " from activity a, activitytype t ,user u   ";
        $sql .= " where a.activity_user_id = u.user_id and u.user_id =  " . $userId;
        $sql .= " and a.activity_activitytype_id = t.activitytype_id  ";
        if ($activityTypeId != '0') {
            $sql .= " and a.activity_activitytype_id = " . $activityTypeId;
        }
        $sql .= " union ";
        $sql .= " select uc.comment_date as activity_date, 'y' activitytype_icon,uc.comment_data as activity_text,uc.comment_party_id,uc.friend_id,";
        $sql .= " u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo,comment_id as commentid, comment_deleted as commentdeleted,'' activity_id";
        $sql .= " from comment uc ,user u ";
        $sql .= " where (uc.comment_party_id =  " . $userId;
        $sql .= " or uc.comment_party_id in (select friend_id from userfriend where user_id = " . $userId . " and infriendfeed = 'y'))";
        $sql .= " and uc.friend_id = u.user_id ";
        $sql .= " and (uc.comment_type = 9 or uc.comment_type = 10)  ";
        $sql .= " and uc.comment_super_id is null ";
        $sql .= " order by 1 desc ";
        if (!is_null($limit)) {
            $sql .= " limit " . $limit;
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function findActivityComments($activityId, $limit = null) {

        $sql = " select uc.comment_date as activity_date, 'y' activitytype_icon,uc.comment_data as activity_text,uc.comment_party_id,uc.friend_id,  ";
        $sql .= " u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo,comment_id as commentid ";
        $sql .= " from comment uc ,user u ";
        $sql .= " where uc.comment_super_id =" . $activityId;
        $sql .= " and uc.friend_id = u.user_id ";
        $sql .= " and uc.comment_type = " . Constants::$_COMMENT_ACTIVITY;
        if (!is_null($limit) != null) {
            $sql .= "  limit " . $limit;
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function findRepliesByBroadcast($commentSuperId, $limit = null) {

        $sql = " select uc.comment_date as activity_date, 'y' activitytype_icon,uc.comment_data as activity_text, uc.comment_deleted, uc.comment_party_id,uc.friend_id,  ";
        $sql .= " u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo,comment_id as commentid ";
        $sql .= " from comment uc ,user u ";
        $sql .= " where uc.comment_super_id =" . $commentSuperId;
        $sql .= " and uc.comment_deleted = 0 ";
        $sql .= " and uc.friend_id = u.user_id ";
        if (!is_null($limit) != null) {
            $sql .= "  limit " . $limit;
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function findBroadcastByUserFriends($userId, $activityTypeId = '0', $limit = '15', $haslimit = 'y') {
        $sql = " select uc.comment_date as activity_date, 'y' activitytype_icon,uc.comment_data as activity_text,uc.comment_party_id,uc.friend_id,";
        $sql .= " u.screen_name as screen_name,ifnull(u.main_photo,'ProfileMale50.gif') main_photo,comment_id as commentid ";
        $sql .= " from comment uc ,user u ";
        $sql .= " where (uc.comment_party_id = " . $userId . " or uc.comment_party_id in (select friend_id from userfriend where user_id = " . $userId . " and infriendfeed = 'y'))";
        $sql .= " and uc.friend_id = u.user_id ";
        $sql .= " and uc.comment_type =9 ";
        $sql .= " and uc.comment_super_id is null ";
        if ($activityTypeId == 2) {
            $sql .= " and uc.friend_id not in (" . $userId . ")";
        } else if ($activityTypeId == 4) {
            $sql .= " and uc.friend_id in (" . $userId . ")";
        }
        $sql .= " order by 1 desc ";
        if ($haslimit == 'y') {
            if ($limit == '15') {
                $sql .= " limit " . $limit;
            } else {
                $sql .= " limit " . $limit;
            }
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function findActivitiesPerUser($userId, $activityTypeId = '0', $limit = '15', $haslimit = 'y') {

        $sql = " select a.activity_date,t.activitytype_icon,a.activity_text,t.activitytype_name ,uf.friend_id ,u2.screen_name,u2.main_photo";
        $sql .= " from activity a,activitytype t, user u , userfriend uf ,user u2 ";
        $sql .= " where a.activity_user_id = u.user_id ";
        $sql .= " and uf.friend_id = u2.user_id ";
        $sql .= " and uf.friend_id = u.user_id and uf.user_id =" . $userId;
        $sql .= " and a.activity_activitytype_id = t.activitytype_id ";
        $sql .= " and (activitytype_category_id = 1 OR activitytype_category_id = 2 OR activitytype_category_id = 3 OR activitytype_category_id = 4 OR activitytype_category_id = 5) ";
        $sql .= " and uf.infriendfeed = 'y'";
        if ($activityTypeId != '0') {
            $sql .= " and a.activity_activitytype_id = " . $activityTypeId;
        } else {
            $sql .= " and a.activity_activitytype_id in (1,4,5,10,11,12,21,22,23,24,26,27,29,30)";
        }

        $sql .= " order by activity_date desc";
        if ($haslimit == 'y') {
            if ($limit == '15') {
                $sql .= " limit " . $limit;
            } else {
                $sql .= " limit " . $limit;
            }
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function findMyActivities($userId, $activityTypeId = '0', $limit = '15', $haslimit = 'y') {

        $sql = " select a.activity_date,t.activitytype_icon,a.activity_text,t.activitytype_name,a.activity_user_id ,u.screen_name,u.main_photo";
        $sql .= " from activity a, activitytype t ,user u ";
        $sql .= " where a.activity_user_id = u.user_id and u.user_id and a.activity_user_id = " . $userId;
        //$sql .= " and u.user_id =" . $userId ;
        $sql .= " and a.activity_activitytype_id = t.activitytype_id ";
        //$sql .= " and (activitytype_category_id = 1 or activitytype_category_id = 2 or activitytype_category_id = 3 or activitytype_category_id = 4 or activitytype_category_id = 5) ";
        if ($activityTypeId != '0') {
            $sql .= " and a.activity_activitytype_id = " . $activityTypeId;
        }//else {
        //$sql .= " and a.activity_activitytype_id in (1,4,5,10,11,12,20,21,22,23,24,26,27,29,30)";
        //}
        $sql .= " order by a.activity_date desc";
        if ($haslimit == 'y') {
            if ($limit == '15') {
                $sql .= " limit " . $limit;
            } else {
                $sql .= " limit " . $limit;
            }
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function selectAllActivitiesCommunity($activityId, $limit = 15, $date = null, $timezone = '+00:00') {
        $datestart = $date . " 00:00:00";
        $dateend = $date . " 23:59:59";

        $sql = " select DATE_FORMAT(CONVERT_TZ(a.activity_date , '+01:00','$timezone'),'%Y-%m-%d %H:%i:%s') as activity_date,";
        $sql .= " a.activity_id, ";
        $sql .= " a.activity_icon, ";
        $sql .= " a.activity_text, ";
        $sql .= " a.activity_player_id, ";
        $sql .= " a.activity_team_id,";
        $sql .= " a.activity_match_id,";
        $sql .= " at.activitytype_category_id, ";
        $sql .= " at.activitytype_name, '' as screen_name ";
        $sql .= " from activity a ,activitytype at";
        if ($activityId != null) {
            $sql .= " where activity_activitytype_id in ( select activitytype_id from activitytype where activitytype_category_id =" . $activityId . ")";
            $sql .="  and a.activity_activitytype_id = at.activitytype_id ";
        } else {
            $sql .= " where a.activity_activitytype_id = at.activitytype_id  ";
        }
        $sql .= " and a.activity_home_display = '1' ";
        if ($date != null) {
            $sql .= " and a.activity_date > '" . $datestart . "' and a.activity_date < '" . $dateend . "'";
        }
        if ($activityId == 1 /* or $activityId == null */) {
            $sql .= " union  ";
            $sql .= " select DATE_FORMAT(CONVERT_TZ(uc.comment_date , '+01:00','$timezone'),'%Y-%m-%d %H:%i:%s') as activity_date, 'y' activitytype_icon,uc.comment_data as activity_text,ifnull(u.main_photo,'ProfileMale50.gif') main_photo,u.screen_name as screen_name  ";
            $sql .= " from comment uc ,user u   ";
            $sql .= " where uc.friend_id = u.user_id  and uc.comment_type in (1,9)   ";
            $sql .= " and uc.is_public =1    ";
            if ($date != null) {
                $sql .= " and uc.comment_date > '" . $datestart . "' and uc.comment_date < '" . $dateend . "'";
            }
        }
        $sql .= " order by 1 desc  ";

        if ($limit != null) {
            $sql .= " limit " . $limit;
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function selectActivitiesPerRssFeed() {

        $sql = " select a.activity_date,a.activity_icon,a.activity_text,at.activitytype_name, '' as screen_name,activity_match_id ";
        $sql .= " from activity a ,activitytype at  ";
        $sql .= " where activity_activitytype_id= at.activitytype_id   ";
        $sql .= " and  at.activitytype_category_id in (2,3) ";
        $sql .= " and a.activity_activitytype_id = at.activitytype_id and a.activity_home_display = '1'  ";
        $sql .= " order by 1 desc limit 15 ";
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function selectActivitiesPerTeamPerType($teamId, $type = '0', $limit = 'y') {

        $sql = " select a.activity_date,at.activitytype_icon,a.activity_text,at.activitytype_name ";
        $sql .= " from activity a,activitytype at ";
        $sql .= " where a.activity_team_id =" . $teamId;
        $sql .= " and a.activity_activitytype_id = at.activitytype_id  ";
        $sql .= " and activitytype_category_id = 2 ";

        if ($type == '1') {
            $sql .= " and at.activitytype_id IN (28) ";
        } elseif ($type == '4') {
            $sql .= " and at.activitytype_id IN (22,27) ";
        }
        $sql .= " order by a.activity_date desc";
        if ($limit == 'y') {
            $sql .= " limit 15";
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function selectActivitiesPerPlayer($playerId, $type, $limit = 'y') {

        $sql = " select a.activity_date,t.activitytype_icon,a.activity_text,t.activitytype_name ";
        $sql .= " from activity a,activitytype t ";
        $sql .= " where a.activity_player_id = " . $playerId;
        //$sql .= " and p.player_id =" . $playerId;
        $sql .= " and a.activity_activitytype_id = t.activitytype_id  ";
        //$sql  .= " and activitytype_category_id = 3 ";
        if ($type == '1') {
            $sql .= " and t.activitytype_id IN (14,7) ";
        } elseif ($type == '2') {
            $sql .= " and t.activitytype_id IN (31,19) ";
        } elseif ($type == '3') {
            $sql .= " and t.activitytype_id IN (16,17,18) ";
        }
        $sql .= " order by activity_date desc";
        if ($limit == 'y') {
            $sql .= " limit 15";
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function selectAllMyUpdates($userId, $limit = null, $activity) {

        $sql = "";
        if ($activity == 0 or $activity == 2) {
            $sql .= " select a.activity_date,t.activitytype_icon,a.activity_text,t.activitytype_name ,activity_image,activity_match_id ";
            $sql .= " from activity a,activitytype t , userplayer u  ";
            $sql .= " where a.activity_player_id = u.player_id  ";
            $sql .= " and u.user_id =" . $userId;
            $sql .= " and a.activity_activitytype_id = t.activitytype_id  ";
            $sql .= " and activitytype_id not in (3,12,21) ";
        }
        if ($activity == 0) {
            $sql .= " union  ";
        }
        if ($activity == 0 or $activity == 3) {

            $sql .= " select a.activity_date,at.activitytype_icon,a.activity_text,at.activitytype_name ,activity_image,activity_match_id ";
            $sql .= " from activity a,activitytype at , userteam u  ";
            $sql .= " where a.activity_team_id = u.team_id  ";
            $sql .= " and u.user_id =" . $userId;
            $sql .= " and a.activity_activitytype_id = at.activitytype_id and activitytype_category_id = 2  ";
            $sql .= " and activitytype_id not in (2,22,27)  ";
        }
        $sql .= " order by activity_date desc  ";
        if (!is_null($limit)) {
            $sql .= " limit " . $limit;
        }
        //echo $sql;
        $result = $this->db->query($sql);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function getMatchResultsActivities($teamId) {
        $db = $this->getAdapter();
        $sql = " select * from activity   ";
        $sql .= " where activity_team_id = " . $teamId;
        $sql .= " and activity_activitytype_id in (34,35,36)   ";
        $sql .= " and activity_alert_sent = 'N'  ";
        //echo $sql;
        $result = $db->query($sql);
        $row = $result->fetchAll();
        return $row;
    }

    public function updateActivity($activityId, $data) {
        $db = $this->getAdapter();
        $where = 'activity_id =' . $activityId;
        $db->update('activity', $data, $where);
    }
    
    public function findActivity($activityId,$timezone = '+00:00') {
        $sql = " select DATE_FORMAT(CONVERT_TZ(a.activity_date , '+01:00','$timezone'),'%Y-%m-%d %H:%i:%s') as activity_date,";
        $sql .= " a.activity_id, ";
        $sql .= " a.activity_icon, ";
        $sql .= " a.activity_text, ";
        $sql .= " a.activity_player_id, ";
        $sql .= " a.activity_team_id, ";
        $sql .= " at.activitytype_name, '' as screen_name, ";
        $sql .= " at.activitytype_category_id, ";
        $sql .= " a.activity_match_id ";
        $sql .= " FROM activity a ";
        $sql .= " INNER JOIN activitytype at ON a.activity_activitytype_id = at.activitytype_id ";
        $sql .= " WHERE a.activity_id = " . $activityId;
        //echo $sql;
        $result = $this->db->query($sql);
        $row = $result->fetchAll();
        return $row;
    }

}
