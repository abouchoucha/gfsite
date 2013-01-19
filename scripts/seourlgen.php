<?php require_once 'urlGenHelper.php';

class SeoUrlGen 
{
	// Specify the Domain Name...	
	private $_domainName = null;
	private $_urlGenHelper = null;
	function __construct() {
	
		// in Prodcution this variable should be assign to. 
		//get the domain name here from the server 
		
		
		$this->_domainName = Zend_Registry::get ( "contextPath" );
		$this->_urlGenHelper = new UrlGenHelper();
	}
	
	public function domainNameInclude($includeDomain, $currentUrl)
	{		
		if ($includeDomain==True)
		{	
			return $this->_domainName.$currentUrl;
		}
		else
		{
			return $currentUrl;
		}
	}

	// Players main url
	// /Players/
	public function getPlayersMainUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/players/");
	}

	// Players Featured Url
	public function getFeaturedPlayersUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/players/featured/");
	}
	
// 		Players Featured Url
	public function getPopularPlayersUrl($includeDomain)
	{
		
		return $this->domainNameInclude($includeDomain, '/players/popular/');
	}
	
	// Player Master Profile URL
	// - /Players/nickName-FullName/
    public function getPlayerMasterProfileUrl2($nickName, $firstName, $lastName, $playerId, $includeDomain)
	{	
		if ($nickName != null || $nickName != "" || $nickName != $firstName || $nickName != $lastName)
		{
			$nickName = "";
		}
		else 
		{
			$nickName = "-".$nickName;	
		}
		$returnVal = "/players/".rawurlencode($this->_urlGenHelper->seoEncodePlayers($firstName)) ."-". rawurlencode($this->_urlGenHelper->seoEncodePlayers($lastName)).rawurlencode($this->_urlGenHelper->seoEncodePlayers($nickName))."_".$playerId."/";		
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	public function getPlayerMasterProfileUrl($nickName=null, $firstName=null, $lastName=null, $playerId=null, $includeDomain=null ,$playerCommonName = null )
	{	
		$returnVal = "/players/".rawurlencode($this->_urlGenHelper->seoEncodePlayers($playerCommonName)) ."_".$playerId."/";		
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
		// Individual Player statistics
	// - /Players/Searilized-Name_{Player-Id}/stats/
    public function getPlayerMasterProfileStatsUrl($nickName, $firstName, $lastName, $playerId, $includeDomain)
	{	
		if ($nickName != null || $nickName != "" || $nickName != $firstName || $nickName != $lastName)
		{
			$nickName = "";
		}
		else 
		{
			$nickName = "-".$nickName;	
		}
		$returnVal = "/players/".rawurlencode($this->_urlGenHelper->seoEncode($firstName)) ."-". rawurlencode($this->_urlGenHelper->seoEncode($lastName)).rawurlencode($this->_urlGenHelper->seoEncode($nickName))."_".$playerId."/stats/";		
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Individual Player TeamMates
	// - /Players/Searilized-Name_{Player-Id}/teammates/
    public function getPlayerMasterProfileTeammatesUrl($nickName, $firstName, $lastName, $playerId, $includeDomain)
	{	
		if ($nickName != null || $nickName != "" || $nickName != $firstName || $nickName != $lastName)
		{
			$nickName = "";
		}
		else 
		{
			$nickName = "-".$nickName;	
		}
		$returnVal = "/players/".rawurlencode($this->_urlGenHelper->seoEncode($firstName)) ."-". rawurlencode($this->_urlGenHelper->seoEncode($lastName)).rawurlencode($this->_urlGenHelper->seoEncode($nickName))."_".$playerId."/teammates/";		
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Players starting with alphabet
	// - /players/startswith_'char'	/
	// - /players/startswith_'char'/2/	 -  for page no 2
	public function getPlayersStartingWithAlphabetUrl($alphabet, $pagingId, $includeDomain , $position , $countryId)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			//$returnVal= "/players/startswith-".$alphabet."/";
			$returnVal= "/player/showplayersbyalphabet/letter/".$alphabet;
		}
		else
		{
			//$returnVal = "/players/startswith-".$alphabet."/".$pagingId."/";
			$returnVal = "/player/showplayersbyalphabet/letter/".$alphabet;
			
		}
		//added by JCVB
		if($position !=null){
			$returnVal .= "/position/" . $position ;
		}
		if($countryId !=null){
			$returnVal .= "/c/" . $countryId ;
		}
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	// Players starting with alphabet and Position
	// - /players/startswith_'char'	/position/
	// - /players/startswith_'char'/position/2/	 -  for page no 2
	public function getPlayersStartingWithAlphabetAndPositionUrl($alphabet, $position, $pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal= "/players/startswith-".$alphabet."/".$position."/";
		}
		else
		{
			$returnVal = "/players/startswith-".$alphabet."/".$position."/".$pagingId."/";
		}
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Players by popularity
	// - /players/most-popular/pageid/
	public function getPlayersByPopularityUrl($pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/players/most-popular/";
		}
		else
		{
			$returnVal = "/players/most-popular/".$pagingId."/";
		}
		
		return domainNameInclude($includeDomain, $returnVal);
	}
	
	// Players by recently added to our list
	// - /players/new/pageid/
	public function getPlayersByRecentlyAddedUrl($pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/players/new/";
		}
		else
		{
			$returnVal = "/players/new/".$pagingId."/";
		}
		
		return domainNameInclude($includeDomain, $returnVal);
	}

	// players by Clubs - lists all the club names
	// - /players/clubs/pageid/
	public function getPlayersByClubsUrl($pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/players/clubs/";
		}
		else
		{			
			$returnVal = "/players/clubs/".$pagingId."/";
		}
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}	
	
	// Players in a Club
	// - /players/club-clubname/pageid/
	public function getPlayersInAClubUrl($clubName, $pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/players/club-".$clubName."/";
		}
		else
		{
			
			$returnVal = "/players/club-".$clubName."/".$pagingId."/";
		}
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Player profile for a club
	// - /players/club-clubname/playername/
	public function getPlayerProfileForClubUrl($clubName, $playerName, $includeDomain)
	{
		$returnVal = "/players/club-".$clubName."/".$playerName."/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

	// Players profiles by national team - lists all the national teams
	// - /players/national/pageid/
	public function getPlayersByNationalTeamsUrl($pagingId, $includeDomain)
	{
		$returnVal = "";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/players/national/";
		}
		else
		{
			
			$returnVal = "/players/national/".$pagingId."/";
		}
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}		
	
	// players in a National Team
	// - /players/national-players/pageid
	public function getPlayersInANationalTeamUrl($nationalTeam, $pagingId, $includeDomain)
	{
		$returnVal ="";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = $nationalTeam."/national-players/";
		}
		else
		{
			
			$returnVal = $nationalTeam."/national-players/".$pagingId."/";
		}

		return $this->domainNameInclude($includeDomain, $returnVal);
	}	
	
	// Clubs main url
	// /Clubs/
	public function getClubsMainUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/teams/");
	}
	
	// More Featured Teams
	// /Clubs/featured/
	public function getFeaturedTeamsUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/teams/featured/");
	}
// /Clubs/
	public function getPopularTeamsUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/teams/popular/");
	}
	
	// Clubs in a Country
	// /clubs/country name
	public function getClubsInACountryWithRegion($countryName, $pagingId, $regionName, $countryId, $includeDomain)
	{
		$returnVal ="";
		if ($pagingId==0 || $pagingId==1)
		{
			$returnVal = "/".$this->_urlGenHelper->seoEncode($regionName)."/".$this->_urlGenHelper->seoEncode($countryName)."-football-clubs-and-national-team_".$countryId."/";
		}
		//else
		//{
			
			//$returnVal = "/".$regionId."/".$countryName."/clubs-and-national-teams-list/".$pagingId."/".$countryId."/";
		//}

		return $this->domainNameInclude($includeDomain, $returnVal);
	}
		
	// Club Master Profile URL
	// - //nickName-FullName/
    public function getClubMasterProfileUrl($teamId, $teamName, $includeDomain)
	{		
		//$returnVal = "/teams/".rawurlencode($this->_urlGenHelper->seoEncode($teamName));
		$returnVal = "/teams/".rawurlencode($this->_urlGenHelper->seoEncode($teamName)) ."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Main Leagues and Competitions Page
	// - //football-leagues-and-competitions/
	public function getMainLeaguesAndCompetitionsUrl($includeDomain)
	{
		$returnVal = "/tournaments/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Show Region
	// - //regionName/soccer-leagues-and-competitions_regionId
	/*public function getShowRegionUrl($regionName, $regionId, $includeDomain)
	{
		$returnVal = "/".$regionName ."/soccer-leagues-and-competitions_". $regionId."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}*/
	public function getShowRegionUrl($regionName, $includeDomain)
	{
		//$returnVal = "/".$regionName."-leagues-tournaments/";
		$returnVal = "/tournaments/".$this->_urlGenHelper->seoEncode($regionName)."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	public function getShowTournamentUrl($tournamentName, $tournamentId, $includeDomain)
	{
		$returnVal = "/tournaments/".$this->_urlGenHelper->seoEncodePlayers($tournamentName)."_$tournamentId/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	public function getTablesUrl($type, $roundId ,$countryName , $period , $compName , $includeDomain){
		
		$returnVal = "/".$this->_urlGenHelper->seoEncode($type). "/". $this->_urlGenHelper->seoEncode($countryName) . "/". $this->_urlGenHelper->seoEncodePlayers( $this->_urlGenHelper->replace('/','-',$period))."/". 
				$this->_urlGenHelper->seoEncodePlayers($compName) . "_". $roundId; 
				
		return $this->domainNameInclude($includeDomain, $returnVal);
		
	}
	
	public function getTeamScoreScheduleUrl($type, $teamId ,$teamName , $includeDomain){
		
		$returnVal = "/".$this->_urlGenHelper->seoEncode($type). "/".
				$this->_urlGenHelper->seoEncodePlayers($teamName) . "_". $teamId; 
				
		return $this->domainNameInclude($includeDomain, $returnVal);
		
	}
	
	public function getTablesUrlInternational($type, $roundId , $roundName , $countryName , $period , $compName , $includeDomain){
		
		$returnVal = "/".$this->_urlGenHelper->seoEncodePlayers($type). "/". $this->_urlGenHelper->seoEncodePlayers($countryName) . "/". $this->_urlGenHelper->seoEncode( $this->_urlGenHelper->replace('/','-',$this->_urlGenHelper->seoEncodePlayers($period)))."/". 
				$this->_urlGenHelper->seoEncodePlayers($roundName). "/". $this->_urlGenHelper->seoEncodePlayers($compName) . "_". $roundId; 
				
		return $this->domainNameInclude($includeDomain, $returnVal);
		
	}
	// Show Domestic Competitions by Country
	// - /regionName/CountryName-dommestic-soccer-leagues-and-competitions_countryId
	public function getShowDomesticCompetitionsByCountryUrl($countryName, $countryId, $includeDomain)
	{
		$returnVal = "/".$this->_urlGenHelper->seoEncode($countryName)."-domestic-soccer-leagues-and-competitions_". $countryId."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	// Show Region Competitions by Region
	public function getShowRegionalCompetitionsByRegionUrl($compName, $compId, $includeDomain)
	{
		return $this->getShowTournamentUrl($compName, $compId, $includeDomain);
	}
// Show Region Competitions by Region
	public function getShowDomesticCompetitionUrl($compName, $compId, $includeDomain)
	{		
		return $this->getShowTournamentUrl($compName, $compId, $includeDomain);
	}
	// Show national team Competitions
	public function getShowNationalTeamCompetitionsUrl($compName, $compId, $includeDomain)
	{		
		return $this->getShowTournamentUrl($compName, $compId, $includeDomain);
	}
	
	// show featured competitions url
	public function getShowFeaturedCompetitionsUrl($includeDomain)
	{		
		return $this->domainNameInclude($includeDomain, "/tournaments/featured/");
	}
	
	// Search URL
	// /search/
	public function getSearchMainUrl($includeDomain)
	{
		$returnVal = "/search/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Search URL
	// /search/?q=blah
	public function getSearchUrl($searchTerm, $includeDomain)
	{
		$returnVal = "/search/?q=".$searchTerm;
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Individual User Profile Page
	// - /profiles/userName
	public function getUserProfilePage($userName, $includeDomain)
	{
		$returnVal = "/profiles/".$this->_urlGenHelper->seoEncode($userName)."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Edit User Profile Page
	// - /editprofile/userName
	public function getEditProfilePage($userName, $includeDomain , $profileItem)
	{
		$returnVal = "/editprofile/".$this->_urlGenHelper->seoEncode($userName)."/";
		
		$returnVal = $returnVal . $profileItem;
		
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	
	// User Profiles main Page - /showFeaturedProfiles
	// - /profiles/
	public function getMainProfilesPage($includeDomain)
	{
		$returnVal = "/profiles/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	// User Profiles search page - searchProfiles
	// - /profiles/userName
	public function getSearchProfilesPage($includeDomain)
	{
		$returnVal = "/profiles/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Main Pages - Root Level
	// - /soccer-news
	public function getMainNewsPage($includeDomain)
	{
		$returnVal = "/news/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	// News Article Page
	public function getNewsArticlePageUrl($articleTitle, $articleId, $includeDomain)
	{
		$returnVal = "/news/".$this->_urlGenHelper->seoEncode($articleTitle) ."/".$articleId."/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Individual News Item URL
	public function getIndividualNewsArticlePageUrl($newsTitle, $newsArticleID, $includeDomain)
	{
		$returnVal = "/news/".$this->_urlGenHelper->seoEncode($newsTitle)."/".$newsArticleID."/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Show News World
	public function getShowNewsWorldPageUrl($includeDomain)
	{
		return $this->domainNameInclude($includeDomain, "/news/shownewsworld");
	}
	
	// Main page - Scores & Schedules
	// - //live-soccer-scores-match-schedules	
	public function getMainScoresAndMatchesPageUrl($includeDomain)
	{
		$returnVal = "/live-scores-match-schedules/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}	
	
	// Match URL
	// - //matches/league-name/teama-vs-teamb/matchid/
	public function getMatchPageUrl($compName, $teamA, $teamB, $matchID, $includeDomain)
	{
		$returnVal = "/matches/".$this->_urlGenHelper->seoEncodePlayers($compName)."/".$this->_urlGenHelper->seoEncodePlayers($teamA)."-vs-".$this->_urlGenHelper->seoEncodePlayers($teamB)."/".$matchID."/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// User Urls
	// - // user/register/
	public function getUserRegisterPageUrl($includeDomain)
	{
		$returnVal = "/register/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Photos
	// - // photos/
	public function getPhotosPageUrl($includeDomain)
	{
		$returnVal = "/photos/";
		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
	// Feedback
	public function getFeedbackPageUrl($includeDomain)
	{
		$returnVal = "/feedback/";
		
		return $this->domainNameInclude($includeDomain, $returnVal);
	}

    // About
	public function getAboutPageUrl($includeDomain)
	{
		$returnVal = "/about/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

    // Guidelines
	public function getGuidelinesPageUrl($includeDomain)
	{
		$returnVal = "/guidelines/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

    // Terms
	public function getTermsPageUrl($includeDomain)
	{
		$returnVal = "/terms/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

     // Privacy
	public function getPrivacyPageUrl($includeDomain)
	{
		$returnVal = "/privacy/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

        // help and faq
	public function getHelpFaqPageUrl($includeDomain)
	{
		$returnVal = "/faq/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}


    // Disclaimer
	public function getDisclaimerPageUrl($includeDomain)
	{
		$returnVal = "/disclaimer/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

     // Contact Us
	public function getContactUsPageUrl($includeDomain)
	{
		$returnVal = "/contact-us/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

       // Advertise
	public function getAdvertisePageUrl($includeDomain)
	{
		$returnVal = "/advertise-with-us/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}

         // Trademark
	public function getTrademarkPageUrl($includeDomain)
	{
		$returnVal = "/trademark/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}
	
     // RSS Gallery
	public function getRssGalleryPage($includeDomain)
	{
		$returnVal = "/rss/";

		return $this->domainNameInclude($includeDomain, $returnVal);
	}


}
?>
