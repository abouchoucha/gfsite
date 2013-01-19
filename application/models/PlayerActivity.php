<?php

class PlayerActivity extends Zend_Db_Table_Abstract {
	
	protected $_name = 'playeractivity';
	protected $_primary = "activity_id";
	
	public function insertPlayerActivityByActivityType($activityTypeId, $variablesToReplace, $playerId) {
		
		//update table Activity with activity Type
		//parameters to pass: activity Type + parameters to update
		$rateProfile = new ActivityType ( );
		$activityType = $rateProfile->find ( $activityTypeId );
		$currentActivity = $activityType->current ();
		$customMessage = $currentActivity->activitytype_text;
		//parse txt msgactivitytype_id
		$templater = new Template ( );
		$newCustomMessage = $templater->parse_variables ( $customMessage, $variablesToReplace );
		//update the Activity Table
		$dataNewActivity = array ('activity_activitytype_id' => $currentActivity->activitytype_id, 'activity_date' => trim ( date ( "Y-m-d H:i:s" ) ), 'activity_player_id' => $playerId, 'activity_icon' => $currentActivity->activitytype_icon, 'activity_text' => $newCustomMessage );
		
		$activityPlayer = new PlayerActivity ( );
		$activityPlayer->insert ( $dataNewActivity );
		
		return null;
	}
	
}

?>
