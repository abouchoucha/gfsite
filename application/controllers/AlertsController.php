<?php

/**
 * AlertsController
 * 
 * @author Juan Carlos Vasquez
 * @version 1.0
 */
require_once 'Zend/Controller/Action.php';
require_once 'scripts/seourlgen.php';
require_once 'application/controllers/util/Constants.php';
require_once("Zrad/Zrad_Facebook.php");

class AlertsController extends Zend_Controller_Action {

    /**
     * The default action - show the home page
     */
	private static $logger;
	
	public function init() {
  		self::$logger = Zend_Registry::get ( "logger" );
  		Zend_Loader::loadClass ( 'Zend_Debug' );
	}
  
    public function indexAction() {
        // TODO Auto-generated AlertsController::indexAction() default action
        echo 'hello';
    }

    public function sendleaguealertsAction() {

        $urlGen = new SeoUrlGen();
        $config = Zend_Registry::get('config');
        $user = new User();
        $usLocale = new Zend_Locale('en_US');
		Zend_Registry::set('Zend_Locale', $usLocale);
        $date = new Zend_Date ();
        $now = $date->toString('yyyy-MM-dd HH:mm:ss');
        echo "Today:" . $now;
        $OneDayInAdvanceInit = $date->addDay(1);
        $OneDayInAdvanceInitFormated = $OneDayInAdvanceInit->toString('yyyy-MM-dd') . ' 07:00:00';
        $matchDateOneDay = $OneDayInAdvanceInit->toString('EEEE - MMMM dd, yyyy');
        $OneDayInAdvance = $date->addDay(1);
        $matchDateTwoDay = $OneDayInAdvanceInit->toString('EEEE - MMMM dd, yyyy');
        $OneDayInAdvanceFormated = $OneDayInAdvanceInit->toString('yyyy-MM-dd') . ' 06:59:59';
        echo "<br>24 hr From: " . $OneDayInAdvanceInitFormated . " until :" . $OneDayInAdvanceFormated;
        $twoDaysInAdvance = $date->addDay(1);
        $twoDaysInAdvanceFormated = $twoDaysInAdvance->toString('yyyy-MM-dd') . ' 06:59:59';
        echo "<br>48 hr From: " . $OneDayInAdvanceFormated . " until :" . $twoDaysInAdvanceFormated;

        $userleague = new UserLeague();
        $leagueCompetition = new LeagueCompetition();
        $leagueAlerts = $userleague->getUserLeagueEmailAlerts();
        $leagueAlertsFaceBook = $userleague->getUserLeagueFaceBookAlerts();
        $leagueAlertsPageFanFaceBook = $leagueCompetition->getLeaguePageFanFaceBookAlerts();//Page Fan Facebook
        $matchesCount = 0;
        $matchDate = null;
        $mail = new SendEmail();
        $mail->set_from($config->email->confirmation->from);
        foreach ($leagueAlerts as $alert) {
            echo '<br>Iterating over:' . $alert['competition_id'] . "-->" . $alert['user_id'];
            if ($alert['alert_frecuency_type'] == Constants::$_24HOURSINADVANCE) {
                $matchesCount = $userleague->countScheduleMatchLeagueAlerts($alert['competition_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
                $matchDate = $matchDateOneDay;
            } else if ($alert['alert_frecuency_type'] == Constants::$_48HOURSINADVANCE) {
                $matchesCount = $userleague->countScheduleMatchLeagueAlerts($alert['competition_id'], $OneDayInAdvanceFormated, $twoDaysInAdvanceFormated);
                $matchDate = $matchDateTwoDay;
            }
            echo "Count: " . $matchesCount['totalmatches'];

            if ($matchesCount['totalmatches'] > 0) {
                //find userdetails
                //Send Alert By Email
                echo '<br>Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' . $alert['alert_frecuency_type'] . " for league:" . $alert['competition_id'];
                $userRow = $user->findUserUnique($alert["user_id"]);
                $competitionRow = $leagueCompetition->findCompetitionById($alert['competition_id']);
                $leagueUrl = $urlGen->getTablesUrl("schedules", $matchesCount["round_id"], $competitionRow['country_name'], $matchesCount["title"], $competitionRow['competition_name'], True);
                $email_subject = $competitionRow['country_name'] . "  " . $competitionRow['competition_name'] . " Scheduled Match Alert";
                $mail->set_subject($email_subject);
                $mail->set_to($userRow[0]["email"]);
                $mail->set_template('leagueemailalert');
                $variablesToReplaceEmail = array('userEmail' => $userRow[0]['email'],
                    'leagueUrl' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $leagueUrl,
                    'alertSettingsUrl' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . "/editprofile/" . $userRow[0]['screen_name'] . "/settings#updates",
                    'leagueName' => $competitionRow['competition_name'],
                    'matchDate' => $matchDate,
                    'countryName' => $competitionRow['country_name']
                );
                //Zend_Debug::dump($variablesToReplaceEmail);
                $mail->set_variablesToReplace($variablesToReplaceEmail);
                $mail->sendMail();
                $matchesCount = 0;
                //Send AlertBy Email
            }
        }

		$app_id = $config->facebook->appid;
		$app_secret = $config->facebook->secret;
		
		self::$logger->debug ( "----> Send League Alert <----");
        foreach ($leagueAlertsFaceBook as $alert) {
			if ($alert['alert_frecuency_type'] == Constants::$_24HOURSINADVANCE) {
				$matchesCount = $userleague->countScheduleMatchLeagueAlerts($alert['competition_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
				$matchDate = $matchDateOneDay;
			} else if ($alert['alert_frecuency_type'] == Constants::$_48HOURSINADVANCE) {
				$matchesCount = $userleague->countScheduleMatchLeagueAlerts($alert['competition_id'], $OneDayInAdvanceFormated, $twoDaysInAdvanceFormated);
				$matchDate = $matchDateTwoDay;
			}
			
			self::$logger->debug ( "------->Total Matches:".$matchesCount['totalmatches']);
			if ($matchesCount['totalmatches'] > 0) {
				$userRow = $user->findUserUnique($alert["user_id"]);
				$competitionRow = $leagueCompetition->findCompetitionById($alert['competition_id']);
				$leagueUrl = $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $urlGen->getTablesUrl("schedules", $matchesCount["round_id"], $competitionRow['country_name'], $matchesCount["title"], $competitionRow['competition_name'], True);
				$message = $competitionRow['country_name'] . "  " . $competitionRow['competition_name'] .' '. $matchesCount["title"]. " Scheduled Match Alert";
				
				try{
					self::$logger->debug ( "------->appId:".$app_id);
					self::$logger->debug ( "------->secret:".$app_secret);
					self::$logger->debug ( "------->message:".$competitionRow['country_name'] . "  " . $competitionRow['competition_name'] . ' has games scheduled for '. $matchDate);
					self::$logger->debug ( "------->name:".$message);
					self::$logger->debug ( "------->link:".$leagueUrl);
					self::$logger->debug ( "------->picture:".'http://www.goalface.com/public/images/GoalFaceBall200x200.gif');
					self::$logger->debug ( "------->actions:".'http://'.$leagueUrl);
					
					$facebook = new Facebook(array(
					  'appId'  => $app_id,
					  'secret' => $app_secret
					));
				
					$parametros=array(
						'message' => $competitionRow['country_name'] . "  " . $competitionRow['competition_name'] . ' has games scheduled for '. $matchDate,
						'name' => $message,
						'link' => $leagueUrl,
						'picture' => 'http://www.goalface.com/public/images/GoalFaceBall200x200.gif',
						'actions' => array(array('name' => 'Ver Detalle','link' => 'http://'.$leagueUrl))
					);

					$res = $facebook->api('/'.$userRow[0]["facebookid"].'/feed', 'POST', $parametros);
				}catch (FacebookApiException $e){
					self::$logger->debug ( "----*****> Error League Alert: No se pudo enviar -> Message:".$e->getMessage());
					echo ' No se pudo enviar -> Message:'.$e->getMessage();
					echo ' app_id:'.$app_id;
					echo ' app_secret:'.$app_secret;
					echo ' FBid: '.$userRow[0]["facebookid"];
					self::$logger->debug (' FBid: '.$userRow[0]["facebookid"]);
					echo ' Code:'.$e->getCode();
					echo ' Line:'.$e->getLine();
				}

				$matchesCount = 0;
			}
        }
        self::$logger->debug ( "----> End League Alert <----");
        
        self::$logger->debug ( "----> Send League Page Alert <----");
    	foreach ($leagueAlertsPageFanFaceBook as $alert) {
			$matchesCount = $userleague->countScheduleMatchLeagueAlerts($alert['competition_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
			$matchDate = $matchDateOneDay;
			echo "Count: " . $matchesCount['totalmatches'];
			
			self::$logger->debug ( "------->Total Matches:".$matchesCount['totalmatches']);
			if ($matchesCount['totalmatches'] > 0) {
				$competitionRow = $leagueCompetition->findCompetitionById($alert['competition_id']);
				$leagueUrl = $urlGen->getTablesUrl("schedules", $matchesCount["round_id"], $competitionRow['country_name'], $matchesCount["title"], $competitionRow['competition_name'], True);
				$message = substr($matchesCount["title"],5,strlen($matchesCount["title"])-1). " Scheduled Match Alert";
				$leagueUrl = $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $leagueUrl;		
				
				try{
					self::$logger->debug ( "------->appId:".$app_id);
					self::$logger->debug ( "------->secret:".$app_secret);
					self::$logger->debug ( "------->message:".$competitionRow['country_name'] . "  " . $competitionRow['competition_name'] . ' has games scheduled for '. $matchDate.' http://'.$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $urlGen->getTablesUrl("schedules", $matchesCount["round_id"], $competitionRow['country_name'], $matchesCount["title"], $competitionRow['competition_name'], True));
					self::$logger->debug ( "------->link:".'http://www.goalface.com/tournaments/'.$competitionRow['competition_seoname'].'_'.$competitionRow['competition_id'].'/');
					
					$facebook = new Facebook(array(
					  'appId'  => $app_id,
					  'secret' => $app_secret
					));
					
					echo "<br>Acces: ".$alert ['facebookaccesstoken'];
					echo "<br>IdPage: ".$alert["facebook_idPage"];
					$args = array(
						'access_token'  => $alert ['facebookaccesstoken'],
						'message' => $competitionRow['country_name'] . "  " . $competitionRow['competition_name'] . ' has games scheduled for '. $matchDate.' http://'.$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $urlGen->getTablesUrl("schedules", $matchesCount["round_id"], $competitionRow['country_name'], $matchesCount["title"], $competitionRow['competition_name'], True),
						'link' => 'http://www.goalface.com/tournaments/'.$competitionRow['competition_seoname'].'_'.$competitionRow['competition_id'].'/'
						);
					$post_id = $facebook->api("/".$alert["facebook_idPage"]."/links","post",$args);
				}catch (FacebookApiException $e){
					echo ' No se pudo enviar -> Message:'.$e->getMessage();
					self::$logger->debug ( "----*****> Error League Page Alert: No se pudo enviar -> Message:".$e->getMessage());
				}

				$matchesCount = 0;
			}
        }
        self::$logger->debug ( "----> End League Page Alert <----");

        $this->_helper->viewRenderer->setNoRender();
    }

    public function sendteamalertsAction() {

        $urlGen = new SeoUrlGen();
        $config = Zend_Registry::get('config');
        $user = new User();
        $usLocale = new Zend_Locale('en_US');
		Zend_Registry::set('Zend_Locale', $usLocale);
        $date = new Zend_Date ();
        $now = $date->toString('yyyy-MM-dd HH:mm:ss');
        $OneDayInAdvanceInit = $date->addDay(1);
        $OneDayInAdvanceInitFormated = $OneDayInAdvanceInit->toString('yyyy-MM-dd') . ' 07:00:00';
        $matchDateOneDay = $OneDayInAdvanceInit->toString('EEEE - MMMM dd, yyyy');
        $OneDayInAdvance = $date->addDay(1);
        $matchDateTwoDay = $OneDayInAdvanceInit->toString('EEEE - MMMM dd, yyyy');
        $OneDayInAdvanceFormated = $OneDayInAdvanceInit->toString('yyyy-MM-dd') . ' 06:59:59';
        $twoDaysInAdvance = $date->addDay(1);
        $twoDaysInAdvanceFormated = $twoDaysInAdvance->toString('yyyy-MM-dd') . ' 06:59:59';
        echo "Today:" . $now;
        echo "<br>24 hr From: " . $OneDayInAdvanceInitFormated . " until :" . $OneDayInAdvanceFormated;
        echo "<br>48 hr From: " . $OneDayInAdvanceFormated . " until :" . $twoDaysInAdvanceFormated;


        $userteam = new UserTeam();
        $team = new Team();
        $activity = new Activity();
        $teamAlerts = $userteam->getUserTeamEmailAlerts();
		$teamAlertsFaceBook = $userteam->getUserTeamFaceBookAlerts();
		$teamAlertsPageFanFaceBook = $team->selectTeamsPageFanFaceBookAlerts();//Page Fan Euro Facebook
		
        $matchesCount = 0;
        $matchDate = null;
        $mail = new SendEmail();
        $mail->set_from($config->email->confirmation->from);
        foreach ($teamAlerts as $alert) {
            echo '<br>Iterating over:' . $alert['team_id'] . "-->" . $alert['user_id'] . "-->" . $alert['alert_frecuency_type'];
            if ($alert['alert_frecuency_type'] == Constants::$_MATCH_NOTIFICATIONS_ONLY || $alert['alert_frecuency_type'] == Constants::$_ALLMATCH_NOTIFICATIONS) {
                $matchesCount = $userteam->countScheduleMatchAlerts($alert['team_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
                $matchDate = $matchDateTwoDay;
            }
            echo "Count: " . $matchesCount;
            
            if ($matchesCount > 0) {
				$userRow = $user->findUserUnique($alert["user_id"]);
                //Send Alert By Email
                echo '<br><<MATCH NOTIFICATION>>Sending mail for user:' . $alert["user_id"] . ' Frecuency: ' . $alert['alert_frecuency_type'] . " for team:" . $alert['team_id'];
                $teamRow = $team->findUniqueTeam($alert['team_id']);
                $teamUrl = '/teams' . $urlGen->getTeamScoreScheduleUrl("schedules", $alert['team_id'], $teamRow[0]['team_name'], True);
                $email_subject = $teamRow[0]['team_name'] . " Scheduled Match Alert";
                $mail->set_to($userRow[0]["email"]);
                $mail->set_subject($email_subject);
                $mail->set_template('teamemailalert');
                $variablesToReplaceEmail = array('userEmail' => $userRow[0]['email'],
                    'alertSettingsUrl' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . "/editprofile/" . $userRow[0]['screen_name'] . "/settings#updates",
                    'teamUrl' => $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $teamUrl,
                    'teamName' => $teamRow[0]['team_name'],
                );
                Zend_Debug::dump($variablesToReplaceEmail);
                $mail->set_variablesToReplace($variablesToReplaceEmail);
                $mail->sendMail();
                $matchesCount = 0;
                //Send AlertBy Email
            }
        }
		
		$app_id = $config->facebook->appid;
		$app_secret = $config->facebook->secret;
		
		self::$logger->debug ( "----> Send Team Alert <----");
        foreach ($teamAlertsFaceBook as $alert) {
			echo '<br>Iterating over:' . $alert['team_id'] . "-->" . $alert['user_id'] . "-->" . $alert['alert_frecuency_type'];
            if ($alert['alert_frecuency_type'] == Constants::$_MATCH_NOTIFICATIONS_ONLY || $alert['alert_frecuency_type'] == Constants::$_ALLMATCH_NOTIFICATIONS) {
                $matchesCount = $userteam->countScheduleMatchAlerts($alert['team_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
            }

            self::$logger->debug ( "------->Total Matches:".$matchesCount['totalmatches']);
            if ($matchesCount > 0) {
            	$userRow = $user->findUserUnique($alert["user_id"]);
                $teamRow = $team->findUniqueTeam($alert['team_id']);
                $teamUrl = $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . '/teams' . $urlGen->getTeamScoreScheduleUrl("schedules", $alert['team_id'], $teamRow[0]['team_name'], True);
                
				try{
					self::$logger->debug ( "------->appId:".$app_id);
					self::$logger->debug ( "------->secret:".$app_secret);
					self::$logger->debug ( "------->message:".$teamRow[0]['team_name'] . ' has a game scheduled for tomorrow (note: all scheduled games are subject to change).');
					self::$logger->debug ( "------->name:".$teamRow[0]['team_name'] . ' Scheduled Match Alert');
					self::$logger->debug ( "------->link:".$teamUrl);
					self::$logger->debug ( "------->picture:".'http://www.goalface.com/public/images/teamlogos/'.$alert['team_id'].'.gif');
					self::$logger->debug ( "------->actions:".'http://'.$teamUrl);
					
					$facebook = new Facebook(array(
					  'appId'  => $app_id,
					  'secret' => $app_secret
					));
				
					$parametros=array(
						'message' => $teamRow[0]['team_name'] . ' has a game scheduled for tomorrow (note: all scheduled games are subject to change).',
						'name' => $teamRow[0]['team_name'] . ' Scheduled Match Alert',
						'link' => $teamUrl,
						'picture' => 'http://www.goalface.com/public/images/teamlogos/'.$alert['team_id'].'.gif',
						'actions' => array(array('name' => 'Ver Detalle','link' => 'http://'.$teamUrl))
					);

					$res = $facebook->api('/'.$userRow[0]["facebookid"].'/feed', 'POST', $parametros);
				}catch (FacebookApiException $e){
					self::$logger->debug ( "----*****> Error Team Alert: No se pudo enviar -> Message:".$e->getMessage());
					echo ' No se pudo enviar -> Message:'.$e->getMessage();
					echo ' app_id:'.$app_id;
					echo ' app_secret:'.$app_secret;
					echo ' FBid: '.$userRow[0]["facebookid"];
					self::$logger->debug (' FBid: '.$userRow[0]["facebookid"]);
					echo ' Code:'.$e->getCode();
					echo ' Line:'.$e->getLine();
				}

				$matchesCount = 0;
				//Send AlertBy Email
			}
        }
        self::$logger->debug ( "----> End Team Alert <----");
        
        self::$logger->debug ( "----> Send Team Page Alert <----");
    	foreach ($teamAlertsPageFanFaceBook as $alert) {
			$matchesCount = $userteam->countScheduleMatchAlerts($alert['team_id'], $OneDayInAdvanceInitFormated, $OneDayInAdvanceFormated);
			$matchDate = $matchDateTwoDay;
			echo "Count: " . $matchesCount['totalmatches'];
			
			self::$logger->debug ( "------->Total Matches:".$matchesCount['totalmatches']);
			if ($matchesCount['totalmatches'] > 0) {
				$teamRow = $team->findUniqueTeam($alert['team_id']);
                $teamUrl = '/teams' . $urlGen->getTeamScoreScheduleUrl("schedules", $alert['team_id'], $teamRow[0]['team_name'], True);	
				
				try{
					self::$logger->debug ( "------->appId:".$app_id);
					self::$logger->debug ( "------->secret:".$app_secret);
					self::$logger->debug ( "------->message:".$teamRow[0]['team_name'] . ' has a game scheduled for tomorrow. http://'.$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $teamUrl);
					self::$logger->debug ( "------->link:".'http://www.goalface.com/teams/'.$teamRow[0]['team_seoname'].'/');
					
					$facebook = new Facebook(array(
					  'appId'  => $app_id,
					  'secret' => $app_secret
					));
					
					echo "<br>Acces: ".$alert ['facebookaccesstoken'];
					echo "<br>IdPage: ".$alert["facebook_idPage"];
					echo "<br>url: http://".$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $teamUrl;
					$args = array(
						'access_token'  => $alert ['facebookaccesstoken'],
						'message' => $teamRow[0]['team_name'] . ' has a game scheduled for tomorrow. http://'.$_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath") . $teamUrl,
						'link' => 'http://www.goalface.com/teams/'.$teamRow[0]['team_seoname'].'/'
						);
					$post_id = $facebook->api("/".$alert["facebook_idPage"]."/links","post",$args);
				}catch (FacebookApiException $e){
					echo ' No se pudo enviar -> Message:'.$e->getMessage();
					self::$logger->debug ( "----*****> Error Team Page Alert: No se pudo enviar -> Message:".$e->getMessage());
				}

				$matchesCount = 0;
			}
        }
        self::$logger->debug ( "----> End Team Page Alert <----");

        $this->_helper->viewRenderer->setNoRender();
    }

    public function sendgoalfacealertsAction() {
        //Private Messages Alert
        $user = new User();
        $message = new Message();
        $date = new Zend_Date ();
        $from = $date->toString('yyyy-MM-dd' . ' 00:00:00');
        $to = $date->toString('yyyy-MM-dd' . ' 23:59:59');

        $usersWhoReceiceMailDaily = $user->getUsersDailyPrivateMessageAlert();
        $emailComposedMessage = '';
        $mail = new SendEmail();
        $config = Zend_Registry::get('config');
        $contextPath = $_SERVER ['SERVER_NAME'] . Zend_Registry::get("contextPath");
        $data = array('emaildelivered' => 'Y');
        foreach ($usersWhoReceiceMailDaily as $alert) {
            //search pending messages to be sent by email
            $pendingMessages = $message->getEmailPendingMessages($alert['user_id'], $from, $to);
            foreach ($pendingMessages as $pendmessage) {
                echo "<br>Mail from :" . $pendmessage['sentByName'];
                $emailComposedMessage.= $pendmessage['sentByName'] . " has left you a <a href='http://" . $contextPath . "/messagecenter'>message</a><br>";
                //update message
                $message->updateMessage($pendmessage['message_id'], array('emaildelivered' => 'Y'));
            }
            //send email
            $mail->set_from($config->email->confirmation->from);
            $mail->set_to($alert['email']);
            $mail->set_subject("Pending Private Messages");
            $mail->set_message($emailComposedMessage);
            $mail->sendSimpleEMail();
        }

        //Friend Invites
        //Goooal Shouts
        //Comments on your posts
        //Friends Activity & Broadcasts
        $this->_helper->viewRenderer->setNoRender();
    }

}

