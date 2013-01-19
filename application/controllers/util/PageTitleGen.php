<?php

require_once 'PageType.php';
    
class PageTitleGen
	{		
		function init () 
		{
			//Zend_Loader::loadClass ( 'PageType' ) ;						
		}
	
		// Common Method that get used on every page...
		public function getPageTitle($rowset, $pageType)
		{
			switch($pageType)
			{
				
				// Players
				case PageType::$_PLAYER_MASTER_PAGE:
					return $this->getPlayerMasterProfilePageTitle($rowset);
					break;
				case PageType::$_PLAYER_MASTER_PAGE_STATS:
					return $this->getPlayerMasterProfileStatsPageTitle($rowset);
					break;					
				case PageType::$_PLAYERS_BY_ALPHABET:
					return $this->getPlayersByAlphabetPageTitle($rowset);
					break;
				case PageType::$_PLAYERS_MAIN_PAGE:
					return $this->getFeaturedPlayersPageTitle();
					break;
				case PageType::$_PLAYERS_FEATURED_PAGE:
					return $this->getFeaturedPlayersMorePageTitle();
					break;
				case PageType::$_PLAYERS_POPULAR_PAGE:
					return $this->getPopularPlayersMorePageTitle();
					break;
					
					// Clubs
				case PageType::$_CLUBS_MAIN_PAGE:
					return $this->getTeamsIndexPageTitle();
					break;
				case PageType::$_CLUBS_IN_COUNTRY:
					return $this->getViewCountryTeamsPageTitle($rowset);
					break;
				case PageType::$_CLUB_MASTER_PAGE:
					return $this->getViewTeamMasterPageTitle($rowset);
					break;
				case PageType::$_CLUB_FEATURED_PAGE:
					return $this->getFeaturedTeamsMorePageTitle();
					break;
				case PageType::$_CLUB_POPULAR_PAGE:
					return $this->getPopularTeamsMorePageTitle();
					break;
				case PageType::$_CLUB_STATISTICS_PAGE:
					return $this->getClubStatisticsPageTitle($rowset);
					break;	
					// Scores and Schedules
				case PageType::$_SCORES_AND_SCHEDULES_MAIN_PAGE:
					return $this->getScoreSchedulePageTitle();
					break;
					
					// News
                 case PageType::$_NEWS_MAIN_PAGE:
                    return $this->getTopNewsPageTitle();
                    break;
				case PageType::$_NEWS_FEATURED_PAGE:
					return $this->getFeaturedNewsPageTitle();
					break;			
				case PageType::$_NEWS_ARTICLE_PAGE:
					return $this->getNewArticlePageTitle($rowset);
					break;
					
					// Profiles
				case PageType::$_PROFILES_MAIN_PAGE:
					return $this->getProfilesMainPageTitle();
					break;
					
				case "searchuserprofiles":
					return $this->getSearchUserProfilesPageTitle();
					break;
				case "userprofile":
					return $this->getUserProfilePageTitle($rowset);
					break;
				case "showshoutout":
					return $this->getUserShoutOutPageTitle($rowset);
					break;

					// Leagues & Tournaments
				case PageType::$_LEAGUES_MAIN_PAGE:
					return $this->getLeaguesIndexPageTitle();
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
				// Leagues & Tournaments - Featured
				case PageType::$_LEAGUES_FEATURED:
					return $this->getFeaturedTournamentsPage();
					break;					

					// Main - Home page
				case PageType::$_HOME_PAGE:
					return $this->getHomePageTitle();
					break;
					
				// Search Pgae
				case PageType::$_SEARCH_MAIN_PAGE:
					return $this->getSearchHomePageTitle();
					break;
					
					// User Registration
				case PageType::$_USER_REGISTRATION:
					return $this->getUserRegistrationPageTitle();
					break;	

						// User Add Info
				case PageType::$_USER_ADDINFO:
					return $this->getUserAddInfoPageTitle();
					break;	
					// User Add Info
				case PageType::$_USER_INVITEFRIENDS:
					return $this->getUserInviteFriendsPageTitle();
					break;	
					// User upload photo
				case PageType::$_USER_UPLOAD_PROFILE_PICTURE:
					return $this->getUserUploadProfilePhotoPageTitle();
					break;
					// User upload photo
				case PageType::$_USER_PASSWORD_RESET:
					return $this->getUserPasswordResetPageTitle();
					break;
					// Updates Alerts Subscribe
				case PageType::$_UPDATES_ALERTS_SUBSCRIBE:
					return $this->getUpdatesAlertsSubscribePageTitle();
					break;		
			}	
			return "";		
		}
		
		private function getHomePageTitle()
		{
			//return "GoalFace Football Community - Live Scores, Player Profiles, Football Tournaments, Team Matchups, Clubs, News, Videos,  Football Forums, Blogs";
			return "Live football/soccer scores, news and updates - GoalFace.com";
		}
		
		// Players Pages
		// Master Profile
		private function getPlayerMasterProfilePageTitle($rowset)
		{
			$nickName = $rowset->player_nickname;
			
			if ($nickName != null || $nickName != "" || $nickName != $rowset->player_firstname || $nickName != $rowset->player_lastname)
			{
				$nickName = "";
			}
			else 
			{
				$nickName = " ".$nickName."";	
			}
			//return $rowset->player_name_short." | GoalFace.com | ".$rowset->player_firstname." ". $rowset->player_lastname.$nickName."'s Profile - Goals, Stats, TeamMates, Fans and more";
			//return $rowset->player_firstname." && ". $rowset->player_lastname.$nickName." | GoalFace.com";
				return $rowset->player_common_name." Profile | GoalFace.com";

		}
		// Player's Stats page
		// Master Profile
		private function getPlayerMasterProfileStatsPageTitle($rowset)
		{
			$nickName = $rowset->player_nickname;
			if ($nickName != null || $nickName != "" || $nickName != $rowset->player_firstname || $nickName != $rowset->player_lastname)
			{
				$nickName = "";
			}
			else 
			{
				$nickName = " ".$nickName."";	
			}
			// return $rowset->player_firstname." ". $rowset->player_lastname.$nickName."'s Statistics | GoalFace.com | ".$rowset->player_lastname."'s  Statistics - Goals, Assists, Yellow Cards, Red Cards, Games Played and More";
			return $rowset->player_firstname." ". $rowset->player_lastname.$nickName." career statistics | GoalFace.com";
		}
		
		// Players by alphabet
		private function getPlayersByAlphabetPageTitle($rowset)
		{
			//return "Football player names starting with letter ".$rowset;
			return "Player Names Starting With '".$rowset."' | GoalFace.com | Comprehensive List of Football Player Names starting with Letter '".$rowset."'";
		}
		// Featured Players
		private function getFeaturedPlayersPageTitle()
		{
			return "Football Player Directory | GoalFace.com | Comprehensive Football National & Club Player Directory";
		}
		
	// Featured More Players
		private function getFeaturedPlayersMorePageTitle()
		{
			return "Football Featured Players | GoalFace.com | Find Featured Football National & Club Players";
		}
	// Popular More Players
		private function getPopularPlayersMorePageTitle()
		{
			return "Popular Football Players | GoalFace.com | Find Popular Football Players on GoalFace";
		}
		
		// Clubs Pages
		// Teams Main Page
		private function getTeamsIndexPageTitle()
		{
			return "Football Team Directory | GoalFace.com | Comprehensive Football National Team & Domestic Club Directory";
		}
		// Teams by Country Page
		private function getViewCountryTeamsPageTitle($rowset)
		{
			return $rowset." Football Clubs and National Team";
		}
		// Teams by Country Page
		private function getViewTeamMasterPageTitle($rowset)
		{
			return $rowset." Profile | GoalFace.com";
		}
	// Featured More Teams
		private function getFeaturedTeamsMorePageTitle()
		{
			return "Football Featured Teams | GoalFace.com | Find Featured Football Teams & Clubs";
		}
		
	// Featured More Teams
		private function getPopularTeamsMorePageTitle()
		{
			return "Football Popular Teams | GoalFace.com | Find Popular Football Teams & Clubs";
		}
		
	// Featured More Teams
		private function getClubStatisticsPageTitle($rowset)
		{
			return $rowset ." |League Performance | GoalFace.com ";
		}	
		
		
		// Current Scheduels and Scores		
		// Schedules and Scores
		private function getScoreSchedulePageTitle()
		{
			return "Football Live Scores and Match Schedules | GoalFace.com";
		}
		
		// News Pages

		// Top new from around the world - News without AFP news
		private function getFeaturedNewsPageTitle()
		{
                        return "Latest Football News | GoalFace.com | Read Latest Football news from around the World";
                }
		private function getTopNewsPageTitle()
		{
			return "Latest Football News | GoalFace.com | Read Latest Football news from around the World";
		}
		private function getNewArticlePageTitle($rowset)
		{
			return $rowset." | GoalFace.com";
		}
		
		// Profiles Pages
		// Search User Profiles
		private function getProfilesMainPageTitle()
		{
			return "Football Fan Profiles | GoalFace.com | Fan Profiles on Global Football Social Network";
		}
		// Search User Profiles
		private function getSearchUserProfilesPageTitle()
		{
			return "Search football fan profiles";
		}
		// User Profile Page
		private function getUserProfilePageTitle($profile)
		{
			return "Football fan ".$profile->screen_name." profile";
		}
		// User Shout outs
		private function getUserShoutOutPageTitle($profile)
		{
			return "Goal shout outs for ".$profile->screen_name;
		}
	
		// Leagues and Tournaments Pages
		// Main Page - Leagues & Tournaments
		private function getLeaguesIndexPageTitle()
		{
			return "Football Leagues and Tournaments | GoalFace.com | Football World Cup, FIFA, National, Domestic Tournaments";
		}
		// Featured Leagues/Tournaments Page
		private function getFeaturedTournamentsPage()
		{
			return "Featured Tournaments | GoalFace.com | Primera Devision, Super League, UEFA Champions League";
		}
	// Leagues by Region
		private function getLeaguesInRegionPage($regionName)
		{
			return $regionName." Football Leagues and Tournaments | GoalFace.com | Regional Tournaments, domestic leagues";
		}
		
		// Leagues by Region
		private function getLeaguesInCountryPage($regionName)
		{
			return $regionName." Domestic Football Leagues and Tournaments, Live Scores, Player profiles, Statistics";
		}
			// Regional League
		private function getRegionalLeaguePage($rowset)
		{
			return $rowset['country_name']." " .$rowset['competition_name'] ." | GoalFace.com";
		}
		
		
		// Search Main Page
		private function getSearchHomePageTitle()
		{
			return "Search for Football News, Clubs, National Teams, Schedules, Blogs, and Fan Profiles";
		}
	
		
		// User Registration
		private function getUserRegistrationPageTitle()
		{
			return "Join GoalFace.  The Premier Football Community Experience | GoalFace.com";
		}
		
		// 	User Registration
		private function getUserAddInfoPageTitle()
		{
			return "Create Your Football Fan Profile | GoalFace.com";
		}
		
	// 	User Invite Friends
		private function getUserInviteFriendsPageTitle()
		{
			return "Invite Friends to Join Your Football Social Network | GoalFace.com";
		}
		
	// 	User Invite Friends
		private function getUserUploadProfilePhotoPageTitle()
		{
			return "Upload Photo for Your Profile | GoalFace.com";
		}

	// 	User Invite Friends
		private function getUserPasswordResetPageTitle()
		{
			return "Reset Password | GoalFace.com";
		}
		
		private function getUpdatesAlertsSubscribePageTitle()
		{
			return "Updates and Alerts | GoalFace.com";
		}
		
		
	}
	
?>
