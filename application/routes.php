<?php

$router = $frontController->getRouter();

//My Home
$route = new Zend_Controller_Router_Route_Static('myhome', array('controller' => 'index' , 'action' => 'authenticatedhome'));
$router->addRoute('myhome' , $route);

//User Registration
$route = new Zend_Controller_Router_Route_Static('register', array('controller' => 'user' , 'action' => 'register'));
$router->addRoute('register' , $route);
        
// Player Related Routing
$route = new Zend_Controller_Router_Route(
    'players/featured/:page',
    array(
    	'page'=> '1',
    	'controller' => 'player',
        'action'     => 'showmorefeaturedplayers'
    ));
$router->addRoute('featuredplayers', $route);


$route = new Zend_Controller_Router_Route(
    'players/popular/:page',
    array(
    	'page'=> '1',
    	'controller' => 'player',
        'action'     => 'showmorepopularplayers'
    ));
    

$router->addRoute('popularplayers', $route);

$route = new Zend_Controller_Router_Route_Regex('players/([^_/]*)_([^/]*)/teammates', array('action' => 'showplayersteammates','controller' => 'player'),array(2 => 'id'));
$router->addRoute('players/player-name_id/teammates', $route);

$route = new Zend_Controller_Router_Route_Regex('players/([^_/]*)_([^/]*)/stats', array(
	'action' => 'showplayerstatsdetail',
	'controller' => 'player'),array(2 => 'id'));
$router->addRoute('players/player-name_id/stats', $route);


$route = new Zend_Controller_Router_Route_Regex('players/([^_/]*)_([^/]*)', array('action' => 'showuniqueplayer','controller' => 'player'),array(2 => 'id'));
$router->addRoute('players/player-name_id', $route);

$route = new Zend_Controller_Router_Route_Regex('players/startswith-([^/]*)/([^/]*)', array('action' => 'showplayersbyalphabet','controller' => 'player'),array(1 => 'letter', 2=> 'page'));
$router->addRoute('players/startswith/pagenum', $route);

$route = new Zend_Controller_Router_Route_Regex('players/startswith-([^/]*)', array('action' => 'showplayersbyalphabet','controller' => 'player'),array(1 => 'letter'));
$router->addRoute('players/startswith', $route);

$route = new Zend_Controller_Router_Route_Static('players', array('controller' => 'player', 'action' => 'showfeaturedplayers'));
$router->addRoute('players', $route);

// Clubs Related Routing
$route = new Zend_Controller_Router_Route_Regex('([^/]*)/([^/]*)-football-clubs-and-national-team_([^/]*)/([^/]*)', array('action' => 'showfeaturedteams','controller' => 'team'),array(3 => 'id', 4=>'page',2 => 'countryname'));
$router->addRoute('clubs-by-country/pagenum', $route);

$route = new Zend_Controller_Router_Route_Regex('([^/]*)/([^/]*)-football-clubs-and-national-team_([^/]*)', array('action' => 'showfeaturedteams','controller' => 'team'),array(3 => 'id',2 => 'countryname'));
$router->addRoute('clubs-by-country', $route);

$route = new Zend_Controller_Router_Route_Regex('teams/([^_]*)_([^/]*)', array('action' => 'showolduniqueteam','controller' => 'team'),array(2 => 'id'));
$router->addRoute('club-main-url', $route);

$route = new Zend_Controller_Router_Route_Regex('teams/([^_]*)', array('action' => 'showuniqueteam','controller' => 'team'),array(1 => 'name')); 
$router->addRoute('team-main-url-without-id', $route); 

$route = new Zend_Controller_Router_Route_Static(
							'sign-out', 
							array(
									'controller' => 'login',
        							'action'     => 'dologout'
							));
$router->addRoute('sign-out', $route);
$route = new Zend_Controller_Router_Route_Static(
							'sign-in', 
							array(
									'controller' => 'login',
        							'action'     => 'index'
							));
$router->addRoute('invite-friends', $route);
$route = new Zend_Controller_Router_Route_Static(
							'invite-friends', 
							array(
									'controller' => 'user',
        							'action'     => 'invite'
							));							
							
							
$router->addRoute('sign-in', $route);


$route = new Zend_Controller_Router_Route(
    'teams/featured/:type/:page',
    array(
    	'page'=> '1',
    	'type'=> 'club', // changed to 'national' from 'club' to have it default for the worldcup
        'controller' => 'team',
        'action'     => 'showfeaturedteamsmore'
    ));

$router->addRoute('featured-teams', $route);

$route = new Zend_Controller_Router_Route(
    'teams/popular/:type/:page',
    array(
    	'page'=> '1',
    	'type'=> 'club',
        'controller' => 'team',
        'action'     => 'showmostpopularteams'
    ));
$router->addRoute('popular-teams', $route);

$route = new Zend_Controller_Router_Route_Static('teams', array('controller' => 'team', 'action' => ''));
$router->addRoute('teams', $route);

//copa america redirect clean url
$route = new Zend_Controller_Router_Route('copaamerica/:compid', 
			array('compid' => '288' ,'controller' => 'competitions', 'action' => 'showcompetition'));
$router->addRoute('copaamerica', $route);

//Euro2012 redirect clean url
$route = new Zend_Controller_Router_Route('euro2012/:compid', 
			array('compid' => '25' ,'controller' => 'competitions', 'action' => 'showcompetition'));
$router->addRoute('euro2012', $route);


// Scores and Schedules
$route = new Zend_Controller_Router_Route_Static('live-scores-match-schedules', array('controller' => 'scoreboard', 'action' => 'showscoreschedule'));
$router->addRoute('scoresandschedules', $route);

//$route = new Zend_Controller_Router_Route_Regex('live-scores/([^/]*)/([^/]*)', array('controller' => 'scoreboard', 'action' => 'showscoreschedule'),array(1=>'continent',2=>'continentid',3=>'continentid'));
$route = new Zend_Controller_Router_Route(
    'live-scores/:continent/:type',
    array(
    	'type'=> 'scoresTab',
        'controller' => 'scoreboard',
        'action'     => 'showscoreschedule'
    ));
$router->addRoute('livescores', $route);

$route = new Zend_Controller_Router_Route_Regex('matches/([^_]*)/([^/]*)-vs-([^_]*)/([^/]*)', array('controller' => 'scoreboard', 'action' => 'showmatchdetail'),array(4=>'matchid'));
$router->addRoute('matchdetails', $route);


// Soccer News
$route = new Zend_Controller_Router_Route_Regex('news/([^/]*)/([^/]*)', array('controller' => 'news', 'action' => 'shownewsstory'),array(2 => 'id'));
$router->addRoute('news/Article', $route);
$route = new Zend_Controller_Router_Route_Static('news', array('controller' => 'news', 'action' => ''));
$router->addRoute('news', $route);

$route = new Zend_Controller_Router_Route_Regex('news/([^/]*)', array('controller' => 'news', 'action' => ''),array(1 => 'cat'));
$router->addRoute('news/category', $route);


// User Profiles
$route = new Zend_Controller_Router_Route_Regex('profiles/([^/]*)', array('controller' => 'profile' , 'action' => 'index'),array(1=>'username'));
$router->addRoute('userProfile' , $route);

$route = new Zend_Controller_Router_Route(
    'editprofile/:username/:t/:sent',
    array(
    	'sent'=> '',
        'controller' => 'profile',
        'action'     => 'edit'
    ));
$router->addRoute('userEditProfile' , $route);



//Line 63 changed action by showallprofiles. It is the new landing action for the profiles. Changed by JCVB
$route = new Zend_Controller_Router_Route_Static('profiles', array('controller' => 'profile', 'action' => 'showallprofiles'));
$router->addRoute('profiles', $route);

$route = new Zend_Controller_Router_Route('username/:username/:action/*', array('controller' => 'profile' , 'action' => 'index'));
$router->addRoute('user' , $route);	

$route = new Zend_Controller_Router_Route('confirm/:key/:hash/*', array('controller' => 'user' , 'action' => 'confirmaccount'));
$router->addRoute('confirmaccount' , $route);	

$route = new Zend_Controller_Router_Route('cancellink/:key/:hash/*', array('controller' => 'user' , 'action' => 'cancellink'));
$router->addRoute('cancelccount' , $route);

//Options Pages
$route = new Zend_Controller_Router_Route(
    'feedback/:sent',
    array(
    	'sent'=> 'ko',
        'controller' => 'options',
        'action'     => 'feedback'
    ));

$router->addRoute('feedback' , $route);	


$route = new Zend_Controller_Router_Route_Static('activate-account', array('controller' => 'user' , 'action' => 'showregisterconfirmation'));
$router->addRoute('activate-account' , $route);

$route = new Zend_Controller_Router_Route_Static('create-profile', array('controller' => 'user' , 'action' => 'addinfo'));
$router->addRoute('create-profile' , $route);

$route = new Zend_Controller_Router_Route_Static('about', array('controller' => 'options' , 'action' => 'about'));
$router->addRoute('about' , $route);

$route = new Zend_Controller_Router_Route_Static('guidelines', array('controller' => 'options' , 'action' => 'guidelines'));
$router->addRoute('guidelines' , $route);

$route = new Zend_Controller_Router_Route_Static('terms', array('controller' => 'options' , 'action' => 'terms'));
$router->addRoute('terms' , $route);

$route = new Zend_Controller_Router_Route_Static('privacy', array('controller' => 'options' , 'action' => 'privacy'));
$router->addRoute('privacy' , $route);

$route = new Zend_Controller_Router_Route_Static('disclaimer', array('controller' => 'options' , 'action' => 'disclaimer'));
$router->addRoute('disclaimer' , $route);

$route = new Zend_Controller_Router_Route(
    'contact-us/:sent',
    array(
    	'sent'=> 'ko',
        'controller' => 'options',
        'action'     => 'contactus'
    ));
$router->addRoute('contactus' , $route);

$route = new Zend_Controller_Router_Route_Static('advertise-with-us', array('controller' => 'options' , 'action' => 'advertise'));
$router->addRoute('advertise' , $route);

$route = new Zend_Controller_Router_Route_Static('trademark', array('controller' => 'options' , 'action' => 'trademark'));
$router->addRoute('trademark' , $route);

$route = new Zend_Controller_Router_Route_Static('faq', array('controller' => 'options' , 'action' => 'help'));
$router->addRoute('help' , $route);




// Leagues and Competitions
$route = new Zend_Controller_Router_Route_Regex('([^/]*)-domestic-soccer-leagues-and-competitions_([^/]*)', array('controller' => 'competitions', 'action' => 'showcountry'),array(2 => 'id'));
$router->addRoute('competitionsByCountry', $route);


$route = new Zend_Controller_Router_Route_Regex('([^/]*)/([^/]*)/([^/]*)/([^/]*)_([^/]*)', array('controller' => 'competitions', 'action' => 'showfullscoreboard'),
				array(1 =>'sm' , 5 => 'roundid'));
$router->addRoute('competitionsScoresSchedules', $route);

$route = new Zend_Controller_Router_Route_Regex('([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)_([^/]*)', array('controller' => 'competitions', 'action' => 'showfullscoreboard'),
				array(1 =>'sm' , 6 => 'roundid'));
$router->addRoute('competitionsScoresSchedulesInternational', $route);

$route = new Zend_Controller_Router_Route_Regex('tables/([^/]*)/([^/]*)/([^/]*)_([^/]*)', array('controller' => 'competitions', 'action' => 'showcompetitionfulltable'),
				array(4 => 'roundid'));
$router->addRoute('competitionsTables', $route);

$route = new Zend_Controller_Router_Route_Regex('teams/([^/]*)/([^/]*)/([^/]*)_([^/]*)', array('controller' => 'competitions', 'action' => 'showcompetitionsteams'),
				array(4 => 'roundid'));
$router->addRoute('competitionsTeams', $route);
//Team scores/schedules SEO
$route = new Zend_Controller_Router_Route_Regex('teams/([^/]*)/([^/]*)_([^/]*)', array('controller' => 'team', 'action' => 'showteamscoresschedules'),
				array(1 =>'sm' , 3 => 'id'));
$router->addRoute('teamScoresSchedules', $route);									



$route = new Zend_Controller_Router_Route_Static('tournaments/european', array('controller' => 'competitions', 'action' => 'showregion', 'name' => 'european'));
$router->addroute('tournaments-european', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments/americas', array('controller' => 'competitions', 'action' => 'showregion', 'name' => 'americas'));
$router->addroute('tournaments-american', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments/african', array('controller' => 'competitions', 'action' => 'showregion', 'name' => 'african'));
$router->addroute('tournaments-african', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments/asian', array('controller' => 'competitions', 'action' => 'showregion', 'name' => 'asian'));
$router->addroute('tournaments-asian', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments/international', array('controller' => 'competitions', 'action' => 'showregion', 'name' => 'international'));
$router->addroute('tournaments-international', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments/featured', array('controller' => 'competitions', 'action' => 'showfeaturedcompetitions'));
$router->addroute('tournaments-featured', $route);

$route = new Zend_Controller_Router_Route_Regex('tournaments/([^_]*)_([^/]*)', array('controller' => 'competitions', 'action' => 'showcompetition'),array(2 => 'compid'));
$router->addroute('domesticcompetition', $route);

$route = new Zend_Controller_Router_Route_Static('tournaments', array('controller' => 'competitions', 'action' => ''));
$router->addRoute('competitions', $route);

$route = new Zend_Controller_Router_Route(
    'compose/:username/:messageid',
    array(
    	'username'=> '',
    	'messageid'=>'',
        'controller' => 'message',
        'action'     => 'gocompose'
 ));

$router->addRoute('composemessage', $route);

$route = new Zend_Controller_Router_Route(
    'messagecenter/:type/:page/:status/:sent/',
    array(
    	'sent' => '',
    	'status' => 'all' ,
    	'type' => 'inbox',
    	'page'=> '1',
    	'controller' => 'message',
        'action'     => 'index'
 ));
$router->addRoute('messagecenter', $route);


//Photos
$route = new Zend_Controller_Router_Route(
    'photos/:page',
    array(
    	'page'=> '1',
        'controller' => 'photo',
        'action'     => 'showphotogallery'
    ));

$router->addRoute('photos', $route);

//AMF Channel
$route = new Zend_Controller_Router_Route_Static('gateway', array('controller' => 'gateway', 'action' => 'index'));
$router->addroute('gateway', $route);

//Facebook Applications Routes
$route = new Zend_Controller_Router_Route('/facebook/:type/:id/:roundid/:timezone/:fb/:page', 
										   array('controller' => 'scoreboard', 
        										  'action' => 'showfullmatchesbyseason',
                                                  'type' => '',
                                                  'id' => '',
                                                  'roundid' => '',
                                                  'timezone' =>    '',
        										  'fb' =>    '',
                                                  'page' => '1'));  
$router->addroute('scoresschedulesfb', $route);
										   
//RSS Gallery
$route = new Zend_Controller_Router_Route_Static('rss_gallery', array('controller' => 'rss', 'action' => ''));
$router->addRoute('rss_gallery', $route);

//RSS Gallery
$route = new Zend_Controller_Router_Route_Static('subscribe', array('controller' => 'rss', 'action' => ''));
$router->addRoute('subscribe', $route);