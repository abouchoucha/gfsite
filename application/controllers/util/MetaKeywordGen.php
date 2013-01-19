<?php
    class MetaKeywordGen
	{
		function init () 
		{
			Zend_Loader::loadClass ( 'PageType' ) ;						
		}
		
		// Common Method that get used on every page...
		public function getMetaKeywords($rowset, $pageType)
		{
			switch($pageType)
			{
				case PageType::$_PLAYER_MASTER_PAGE:
					return $this->getPlayerMasterProfileKeywords($rowset);
					break;	
				case PageType::$_PLAYERS_BY_ALPHABET:
					return $this->getPlayersByAlphabetKeywords($rowset);
					break;
				case PageType::$_PLAYERS_MAIN_PAGE:
					return $this->getFeaturedPlayersKeywords();
					break;
					
					// Clubs
				case PageType::$_CLUBS_MAIN_PAGE:
					return $this->getTeamsIndexKeywords();
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
					return $this->getScoreScheduleKeywords();
					break;
					
				case PageType::$_SCORES_AND_SCHEDULES_MATCH_DETAILS_PAGE:
					return $this->getMatchDetailsKeywords($rowset);
					break;
					
					// News
				case "topnewspage":
					return $this->getTopNewsKeywords();
					break;
					
					// Profiles
				case PageType::$_PROFILES_MAIN_PAGE:
					return $this->getProfilesMainPageKeywords();
					break;
				case "searchuserprofiles":
					return $this->getSearchUserProfilesKeywords();
					break;
				case "userprofile":
					return $this->getUserProfileKeywords($rowset);
					break;
				case "showshoutout":
					return $this->getUserShoutOutKeywords($rowset);
					break;
					
				// Leagues & Tournaments
				case PageType::$_LEAGUES_MAIN_PAGE:
					return $this->getLeaguesIndexPageKeywords();
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
					// Leagues & Tournaments
				case PageType::$_LEAGUES_FEATURED:
					return $this->getFeaturedLeagues();
					break;				
				// Main - Home page
				case PageType::$_HOME_PAGE:
					return $this->getHomePageKeywords();
					break;
					
				// Search Pgae
				case PageType::$_SEARCH_MAIN_PAGE:
					return $this->getSearchHomePageKeywords();
					break;
					
				// User Registration
				case PageType::$_USER_REGISTRATION:
					return $this->getUserRegistrationPageKeywords();
					break;	

				// Subscribe Updates and Alerts 
				case PageType::$_UPDATES_ALERTS_SUBSCRIBE:
					return $this->getUpdatesAlertsSubscribePageKeywords();
					break;		
			}	
			return "";		
		}
		
		// Main - Home Page
		private function getHomePageKeywords()
		{
			return "FIFA World Cup, UEFA Euro, UEFA  Champions League, Football News, Live Player Profiles, Live Soccer Scores, Soccer Players, Soccer  Videos, Clubs, Soccer Teams, Football Forums, Football Pictures, Match Tickets, Soccer Blogs, Soccer Fans";
		}
		
		// Master Profile
		private function getPlayerMasterProfileKeywords($rowset)
		{
			//return $rowset[0]['player_common_name']." profile,".$rowset[0]['player_common_name']." goals,".$rowset[0]['player_firstname']." ". $rowset[0]['player_lastname'].",".$rowset[0]['team_name'].",".$rowset[0]['country_name'];
			
			
		}
		// Players by alphabet
		private function getPlayersByAlphabetKeywords($rowset)
		{
			return "Soccer player names starting with letter ".$rowset;
		}
		// Featured Players
		private function getFeaturedPlayersKeywords()
		{
			return "Complete soccer playes list";
		}
		// Teams Main Page
		private function getTeamsIndexKeywords()
		{
			return "Football National Teams, Football Domestic Clubs, National Teams, Domestic Clubs, Football Clubs, Football Teams";
		}
		// Current Scheduels and Scores
		private function getScoreScheduleKeywords()
		{
			return "Complete list of live soccer scores and match schedules";
		}
		// Match Details
		private function getMatchDetailsKeywords($rowset)
		{
		return $rowset[0]["t1"]." vs ".$rowset[0]["t2"]." match,".$rowset[0]["t1"]." vs ".$rowset[0]["t2"]." score,".$rowset[0]["t1"]." vs ".$rowset[0]["t2"]." fixture,".$rowset[0]["t1"]." vs ".$rowset[0]["t2"]." game,".$rowset[0]["t1"]." vs ".$rowset[0]["t2"]." schedule, ".$rowset[0]["competition_name"];	
		}
		// Top new from around the world
		private function getTopNewsKeywords()
		{
			return "Top soccer news form around the world";
		}
		// Main Profiles Page
		private function getProfilesMainPageKeywords()
		{
			return "Football Fans, Fan Profiles, Football Fan Community, Football Social Network";
		}
		// Search User Profiles
		private function getSearchUserProfilesKeywords()
		{
			return "Search soccer fan profiles";
		}
		// User Profile Page
		private function getUserProfileKeywords($profile)
		{
			return "Soccer fan ".$profile->screen_name." profile";
		}
		// User Shout outs
		private function getUserShoutOutKeywords($profile)
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
			return "clubs and national teams in...";
		}
		// Teams List by Country
		private function getTeamMasterPage($rowset)
		{
			return $rowset[0]['team_name'] . " profile,".$rowset[0]['team_name'] . " scores,".$rowset[0]['team_name'] . " schedule,".$rowset[0]['team_name'] . " fixtures,".$rowset[0]['team_name_official'].",".$rowset[0]['team_city'].",".$rowset[0]['country_name'];
		}
	
		// Leagues and Tournaments Pages
		// Main Page - Leagues & Tournaments
		private function getLeaguesIndexPageKeywords()
		{
			return "European Championship, UEFA Cup, Copa America, AFC Asian Cup, Soccer World Cup, African Cup of Nations, Regional Tournaments, Domestic leagues";
		}
		// Leagues by Region
		private function getLeaguesInRegionPage($regionName)
		{
			return $regionName." Soccer Leagues, ".$regionName ." Tournaments, ".$regionName." Regional Tournaments, domestic leagues";
		}
		// 	Leagues by Region
		private function getLeaguesInCountryPage($regionName)
		{
			return $regionName." Soccer Leagues, Live Scores, Player profiles, Statistics";
		}
		// Regional League
		private function getRegionalLeaguePage($tournamentName)
		{
			return $tournamentName." Soccer League, Live Scores, Statistics, Schedules, Player profiles";
		}
		
		private function getFeaturedLeagues()
		{
			return "Most Comprehensive Football Leagues and Tournaments - European Championship, UEFA Cup, Copa America, AFC Asian Cup, Soccer World Cup, African Cup of Nations, Regional Tournaments, Domestic leagues";
		}
	
		// 	Search Main Page
		private function getSearchHomePageKeywords()
		{
			return "Football News, Football Clubs, National Teams, Schedules, Blogs, and Fan Profiles";
		}
	
		//User Registration
		private function getUserRegistrationPageKeywords()
		{
			return "Goalface user registration, Join Football Community";
		}
		// Updates Alerts Subscribe
		private function getUpdatesAlertsSubscribePageKeywords()
		{
			return "Alerts, Updates, Football, Soccer, Football Players, Soccer Teams, Football Clubs, Football Tournaments, Soccer Leagues";
		}
	}
?>