<?php
    class MetaDescriptionGen
	{
		function init () 
		{
			Zend_Loader::loadClass ( 'PageType' ) ;						
		}
		
		// Common Method that get used on every page...
		public function getMetaDescription($rowset, $pageType)
		{
			switch($pageType)
			{
				// Players
				case PageType::$_PLAYER_MASTER_PAGE:
					return $this->getPlayerMasterProfileDescription($rowset);
					break;	
				case PageType::$_PLAYERS_BY_ALPHABET:
					return $this->getPlayersByAlphabetDescription($rowset);
					break;
				case PageType::$_PLAYERS_MAIN_PAGE:
					return $this->getFeaturedPlayersDescription();
					break;
					
					// Clubs
				case PageType::$_CLUBS_MAIN_PAGE:
					return $this->getTeamsIndexDescription();
					break;
				case PageType::$_CLUBS_IN_REGION_BY_COUNTRY:
					return $this->getTeamsListByRegion($rowset);
					break;
				case PageType::$_CLUBS_IN_COUNTRY:
					return $this->getTeamsListByCountry($rowset);
					break;
				case PageType::$_CLUB_MASTER_PAGE:
					return $this->getTeamMasterPage($rowset);
					break;
					
						
					// Scores And Schedules
				case "showscoreschedule":
					return $this->getScoreScheduleDescription();
					break;
					
				case PageType::$_SCORES_AND_SCHEDULES_MATCH_DETAILS_PAGE:
					return $this->getMatchDetailsDescription($rowset);
					break;
					
					// News
				case "topnewspage":
					return $this->getTopNewsDescription();
					break;
					
					// Profiles
				case PageType::$_PROFILES_MAIN_PAGE:
					return $this->getProfilesMainPageDescription();
					break;					
				case "searchuserprofiles":
					return $this->getSearchUserProfilesDescription();
					break;
				case "userprofile":
					return $this->getUserProfileDescription($rowset);
					break;
				case "showshoutout":
					return $this->getUserShoutOutDescription($rowset);
					break;				
					
					// Leagues & Tournaments
				case PageType::$_LEAGUES_MAIN_PAGE:
					return $this->getLeaguesIndexPage();
					break;
					// Leagues & Tournaments
				case PageType::$_LEAGUES_BY_REGION:
					return $this->getLeaguesInRegionPage($rowset);
					break;
				// Leagues & Tournaments
				case PageType::$_LEAGUES_BY_COUNTRY:
					return $this->getLeaguesInCountryPage($rowset);
					break;
					// Leagues & Tournaments
				case PageType::$_REGIONAL_LEAGUE:
					return $this->getRegionalLeaguePage($rowset);
					break;
					// Featured Leagues
				case PageType::$_LEAGUES_FEATURED:
					return $this->getFeaturedLeagues();
					break;	
					
					
					// Main - Home page
				case PageType::$_HOME_PAGE:
					return $this->getHomePageDescription();
					break;
					
						// Search Pgae
				case PageType::$_SEARCH_MAIN_PAGE:
					return $this->getSearchHomePageDescription();
					break;
					
				// User Registration
				case PageType::$_USER_REGISTRATION:
					return $this->getUserRegistrationPageDescription();
					break;

				// Subscribe Updates and Alerts 
				case PageType::$_UPDATES_ALERTS_SUBSCRIBE:
					return $this->getUpdatesAlertsSubscribePageDescription();
					break;	
			}			
		}
		
		// Main - Home Page
		private function getHomePageDescription()
		{
			return "Fun, easy and 100% football. Live updates, scores, schedules, player profiles, team news, league standings, fans, photos and more!";
		}
		
		// Master Profile
		private function getPlayerMasterProfileDescription($rowset)
		{
			return "Live updates, goals, statistics, photos and more!";
		}
		// Players by alphabet
		private function getPlayersByAlphabetDescription($rowset)
		{
			return "Find all the Soccer players whose names start with letter ".$rowset;
		}
		// Featured Players
		private function getFeaturedPlayersDescription()
		{
			return "The most Comprehensive Football National Player and Club Player Directory on the Web.";
		}
		// Teams Main Page
		private function getTeamsIndexDescription()
		{
			return "The most Comprehensive Football National Team and Domestic Club Directory on the web";
		}
		// Current Scheduels and Scores
		private function getScoreScheduleDescription()
		{
			return "View Complete list of live soccer scores and match schedules happening around the world for each national team and soccer club";
		}
		// Match Details 
		private function getMatchDetailsDescription($rowset)
		{
			return "Live match updates, goals, lineups, head-to-head, news, photos, and more!";
		}
		// Top new from around the world
		private function getTopNewsDescription()
		{
			return "Most Comprehensive Football News on the Web from Around the World";
		}
		// Main Profiles Page
		private function getProfilesMainPageDescription()
		{
			return "Global Football Fans Profiles on Football Social Network";
		}
		// Search User Profiles
		private function getSearchUserProfilesDescription()
		{
			return "Search soccer fan profiles";
		}
		// User Profile Page
		private function getUserProfileDescription($profile)
		{
			return "User profile or soccer fan with userid - ".$profile->screen_name;
		}
		// User Shout outs
		private function getUserShoutOutDescription($profile)
		{
			return "Goal shout outs for ".$profile->screen_name;
		}
		// Teams List by Region
		private function getTeamsListByRegion($region)
		{
			//TODO:
			return "clubs and national teams in...";
		}
		// Teams List by Country
		private function getTeamsListByCountry($region)
		{
			//TODO:
			return "clubs and national teams in...";
		}
		// Teams List by Country
		private function getTeamMasterPage($teamName)
		{
			return "Live updates, goals, scores, schedules, statistics, player profiles, news, photos and more!";
		}
	
	// 	Leagues and Tournaments Pages
		// Main Page - Leagues & Tournaments
		private function getLeaguesIndexPage()
		{
			return "Most Comprehensive Football Leagues and Tournaments - European Championship, UEFA Cup, Copa America, AFC Asian Cup, Soccer World Cup, African Cup of Nations, Regional Tournaments, Domestic leagues";
		}
	// Leagues by Region
		private function getLeaguesInRegionPage($regionName)
		{
			return $regionName." Soccer Leagues and Tournaments - Regional Tournaments, domestic leagues";
		}
	// Leagues by Region
		private function getLeaguesInCountryPage($regionName)
		{
			return $regionName." Domestic Soccer Leagues and Tournaments, Live Scores, Player profiles, Statistics";
		}
		// Regional League
		private function getRegionalLeaguePage($tournamentName)
		{
			return "Scores, schedules, standings, statistics, news and more!";
		}
		
		// Featured Leagues
		private function getFeaturedLeagues()
		{
			return "European Championship, UEFA Cup, Copa America, AFC Asian Cup, Soccer World Cup, African Cup of Nations, Regional Tournaments, Domestic leagues";
		}
		
		
		// Search Main Page
		private function getSearchHomePageDescription()
		{
			return "Search for Football News, Clubs, National Teams, Schedules, Blogs, and Fan Profiles";
		}
	
		// User Registration
		private function getUserRegistrationPageDescription()
		{
			return "Register in to Goalface Football Community";
		}
		
		// Updates Alerts Subscribe
		private function getUpdatesAlertsSubscribePageDescription()
		{
			return "Updates and Alerts - Football Soccer Teams, Players, League and Tournaments";
		}
	}
?>
