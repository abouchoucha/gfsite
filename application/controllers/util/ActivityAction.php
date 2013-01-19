<?php
require_once 'scripts/seourlgen.php';

class ActivityAction {
	
	private $urlGen = null;
	
	
	function __construct () {
		$this->urlGen = new SeoUrlGen ( ); ;
	}
	
	public function addCompetitionActivity($screenName , $leagueId , $countryId , $gender , $userId) {
		
		$country = new Country();
		$countryRow = $country->findCountryById($countryId);
		$leagueCompetition = new LeagueCompetition();
		$leagueRow = $leagueCompetition->findCompetitionById($leagueId);
		$competition_name_seo = $this->urlGen->getShowTournamentUrl ( $leagueRow ["competition_name"], $leagueRow ["competition_id"], true );
		$countryNameSeo = $this->urlGen->getShowDomesticCompetitionsByCountryUrl ( $countryRow->country_name, $countryRow->country_id, True );
		
		$variablesToReplace = array ('username' => $screenName, 
									 'competition_name_seo' => $competition_name_seo, 
									 'competition_name' => $leagueRow['competition_name'], 
									 'country_name_seo' => $countryNameSeo, 
									 'country_name' => $countryRow->country_name, 
									 'gender' => ($gender == 'm' ? 'his' : 'her') );
		$activityType = Constants::$_ADD_FAVORITE_COMPETITION_ACTIVITY;
		$activityAddFavoriteCompetition = new Activity ( );
		$activityAddFavoriteCompetition->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId );
	
	}
	
	public function addPlayerActivity($screenName , $gender , $userId , $playerId){
		
		$player = new Player();
		$rowset = $player->findUniquePlayer($playerId);
		
		$player_name_seo = $this->urlGen->getPlayerMasterProfileUrl ( $rowset->player_nickname, $rowset->player_firstname, $rowset->player_lastname, $rowset->player_id, true ,$rowset->player_common_name);
		
		$variablesToReplace = array ('username' => $screenName,
									 'player_name_seo' => $player_name_seo,
									 'player_name' => $rowset->player_name_short,
									 'gender' => ($gender =='m'?'his':'her')					
																		);
		$activityType = Constants::$_ADD_FAVORITE_PLAYER_ACTIVITY;
		$activityAddFavoritePlayer = new Activity ( );
		$activityAddFavoritePlayer->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId  );
		//a new activity for the playeriteself
		$activityAddFavoritePlayer->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $playerId ,'n' );
		
	}
	
	public function addTeamActivity($screenName , $gender , $userId , $teamId){
		
		$team = new Team();
		$rowset = $team->findUniqueTeam($teamId);
		
		$team_name_seo = $this->urlGen->getClubMasterProfileUrl($rowset[0]['team_id'], $rowset[0]['team_name'], true);
		
		$variablesToReplace = array ('username' => $screenName ,
									 'team_name_seo' => $team_name_seo ,
									 'team_name' => $rowset[0]['team_name'],
									 'gender' => ($gender =='m'?'his':'her')			
																		);
		$activityType = Constants::$_ADD_FAVORITE_TEAM_ACTIVITY;
		$activityAddFavoriteTeam = new Activity ( );
		$activityAddFavoriteTeam->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $userId );
		//add an activity to the team itself
		$activityAddFavoriteTeam->insertUserActivityByActivityType ( $activityType, $variablesToReplace, $teamId ,'n');
		
		
		
	}
	
}		
