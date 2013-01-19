
//Set this data and make your life easier
	//it will automatically be able to load the
	var playerAConfig={
			configType:"A",
			//Name attribute of the team selection radio input for player A
			teamTypeInputName:"rbTeamTypePlayerA",
			//Id attribute of the first player country dropdown
			countrySelectId:"countryteama",
			//Id attribute of the first player team dropdown
			teamSelectId:"teamselectida",
			//Id attribute of the first player team dropdown container
			teamSelectContainerId:"teamAselection",
			//Id attribute of the player dropdpwn
			playerSelectId:"playerselectida",
			//Id attribute of auto suggest box hidden input
			playerIdInput:"playerA_Id",
			//Id attribute of the auto suggest text input
			autoSuggestSearchFieldId:"playerASearchInput",
			//Error Message
			validationErrorMessage:"Please use the \"Search\" or \"Browse By fields\" to select a player as Player 1"

	};
	

	var playerBConfig={
			configType:"B",
			//Name attribute of the team selection radio input for player B
			teamTypeInputName:"rbTeamTypePlayerB",
			//Id attribute of the second player country dropdown
			countrySelectId:"countryteamb",
			//Id attribute of the second player team dropdown
			teamSelectId:"teamselectidb",
			//Id attribute of the second player team dropdown container
			teamSelectContainerId:"teamBselection",
			//Id attribute of the player dropdpwn
			playerSelectId:"playerselectidb",
			//Id attribute of auto suggest box hidden input
			playerIdInput:"playerB_Id",
			//Id attribute of the auto suggest text input
			autoSuggestSearchFieldId:"playerBSearchInput",
			//Error Message
			validationErrorMessage:"Please use the \"Search\" or \"Browse By\" fields to select a player as Player 2"

	};
	
	
	var teamAConfig={
			configType:"A",
			//Name attribute of the team selection radio input for team A
			teamTypeInputName:"rbTeamTypeTeamA",
			//Id attribute of the first team country dropdown
			countrySelectId:"countryteama",
			//Id attribute of the team dropdown
			teamSelectId:"teamselectida",
			//Id attribute of the team dropdown container
			teamSelectContainerId:"teamAselection",
			//Id attribute of auto suggest box hidden input
			teamIdInput:"teamA_Id",
			//Id attribute of the auto suggest text input
			autoSuggestSearchFieldId:"teamASearchInput",
			//Error Message
			validationErrorMessage:"Please use the \"Search\" or \"Browse By fields\" to select a team as Team 1"

	};
	

	var teamBConfig={
			configType:"B",
			//Name attribute of the team selection radio input for team B
			teamTypeInputName:"rbTeamTypeTeamB",
			//Id attribute of the second team country dropdown
			countrySelectId:"countryteamb",
			//Id attribute of the team dropdown
			teamSelectId:"teamselectidb",
			//Id attribute of the team dropdown container
			teamSelectContainerId:"teamBselection",
			//Id attribute of auto suggest box hidden input
			teamIdInput:"teamB_Id",
			//Id attribute of the auto suggest text input
			autoSuggestSearchFieldId:"teamBSearchInput",
			//Error Message
			validationErrorMessage:"Please use the \"Search\" or \"Browse By\" fields to select a team as Team 2"

	};
	//Init variables used to initialize the fields in the form on some pages, will be defined on the pages themselve but defined here for reference
	if(playerAInitId === undefined)
	{
		var playerAInitId="";
	}
	if(playerAInitName === undefined)
	{
		var playerAInitName = "";
	}
	if(playerAInitTeamId === undefined)
	{
		var playerAInitTeamId="";
	}
	if(playerAInitTeamName === undefined)
	{
		var playerAInitTeamName="";
	}
	if(playerAInitCountryId === undefined)
	{
		var playerAInitCountryId="";
	}
	if(playerAInitCountryName === undefined)
	{
		var playerAInitCountryName="";
	}
	if(playerBInitId === undefined)
	{
		var playerBInitId="";
	}
	if(playerBInitName === undefined)
	{
		var playerBInitName = "";	
	}
	if(playerBInitTeamId === undefined)
	{
		var playerBInitTeamId="";
	}
	if(playerBInitTeamName === undefined)
	{
		var playerBInitTeamName="";
	}
	if(playerBInitCountryId === undefined)
	{
		var playerBInitCountryId="";
	}
	if(teamAInitId === undefined)
	{
		var teamAInitId="";
	}
	if(teamAInitName === undefined)
	{
		var teamAInitName = "";
	}
	if(teamAInitTeamId === undefined)
	{
		var teamAInitTeamId="";
	}
	if(teamAInitTeamName === undefined)
	{
		var teamAInitTeamName="";
	}
	if(teamAInitCountryId === undefined)
	{
		var teamAInitCountryId="";
	}
	if(teamAInitCountryName === undefined)
	{
		var teamAInitCountryName="";
	}
	if(teamBInitId === undefined)
	{
		var teamBInitId="";
	}
	if(teamBInitName === undefined)
	{
		var teamBInitName = "";
	}
	if(teamBInitTeamId === undefined)
	{
		var teamBInitTeamId="";
	}
	if(teamBInitTeamName === undefined)
	{
		var teamBInitTeamName="";
	}
	if(teamBInitCountryId === undefined)
	{
		var teamBInitCountryId="";
	}
	
	
	/*
	* If any input in the preloadConfig are set (Player Id, Player Name), initialize the field with that value
	*/
	function preLoadPlayerValues(configA,configB)
	{
		//Initialize Player A
		if(playerAInitId && playerAInitName)
		{
			jQuery("#" + configA.playerIdInput).val(playerAInitId);
			jQuery("#" + configA.autoSuggestSearchFieldId).val(playerAInitName);
			jQuery("#" + configA.autoSuggestSearchFieldId).addClass("selected");
			
			/*
			jQuery("#" + configA.playerSelectId).html('');
			var optionList = {
					0:"Select A Player",
					playerAInitId:playerAInitName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configA.playerSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
			*/
		}
		if(playerAInitTeamId && playerAInitTeamName)
		{
			
			jQuery("#" + configA.teamSelectId).html('');
			var optionList = {
					0:"Select A Team",
					playerAInitTeamId:playerAInitTeamName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configA.teamSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
					
		}
		if(playerAInitCountryId)
		{
			jQuery("#" + configA.countrySelectId).val(playerAInitCountryId);
		}
		//Initialize Player B
		if(playerBInitId && playerBInitName)
		{
			jQuery("#" + configB.playerIdInput).val(playerBInitId);
			jQuery("#" + configB.autoSuggestSearchFieldId).val(playerBInitName);
			jQuery("#" + configB.autoSuggestSearchFieldId).addClass("selected");
			
			/*
			jQuery("#" + configB.playerSelectId).html('');
			var optionList = {
					0:"Select A Player",
					playerBInitId:playerBInitName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configB.playerSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
			*/
		}
		if(playerBInitTeamId && playerBInitTeamName)
		{
			jQuery("#" + configB.teamSelectId).html('');
			var optionList = {
					0:"Select A Team",
					playerBInitTeamId:playerBInitTeamName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configB.teamSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
			
					
		}
		if(playerBInitCountryId)
		{
			jQuery("#" + configB.countrySelectId).val(playerBInitCountryId);
		}
		
	}
	
	/*
	* If any input in the preloadConfig are set (Team Id, Team Name), initialize the field with that value
	*/
	function preLoadTeamValues(configA,configB)
	{
		//Initialize Team A
		//console.log("TeamAInitId: " + teamAInitId + " teamAInitName: " + teamAInitName );
		if(teamAInitId && teamAInitName)
		{
			jQuery("#" + configA.teamIdInput).val(teamAInitId);
			jQuery("#" + configA.autoSuggestSearchFieldId).val(teamAInitName);
			jQuery("#" + configA.autoSuggestSearchFieldId).addClass("selected");
			/*
			//Uncomment if you want to preload the dropdown
			jQuery("#" + configA.teamSelectId).html('');
			var optionList = {
					0:"Select A Team",
					teamAInitId:teamAInitName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configA.teamSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
			*/

			
		}
		
		if(teamAInitCountryId)
		{
			jQuery("#" + configA.countrySelectId).val(teamAInitCountryId);
		}
		//Initialize Team B
		//console.log("TeamBInitId: " + teamBInitId + " teamBInitName: " + teamBInitName );
		if(teamBInitId && teamBInitName)
		{
			jQuery("#" + configB.teamIdInput).val(teamBInitId);
			jQuery("#" + configB.autoSuggestSearchFieldId).val(teamBInitName);	
			jQuery("#" + configB.autoSuggestSearchFieldId).addClass("selected");
			
			/*
			//Uncomment if you want to preload the dropdown
			jQuery("#" + configB.teamSelectId).html('');
			var optionList = {
					0:"Select A Team",
					teamBInitId:teamBInitName
			};
			$.each(optionList, function(val, text) {
			 
			    jQuery("#" + configB.teamSelectId).append(
						jQuery('<option></option>').val(val).html(text)
			    ).val(val);
			});
			*/
		}
		
		if(teamBInitCountryId)
		{
			jQuery("#" + configB.countrySelectId).val(teamBInitCountryId);
		}
		
	}

	function resetPlayerInfo(config)
	{
		jQuery("#" + config.playerIdInput).val('');
		jQuery("#" + config.autoSuggestSearchFieldId).val('');
		jQuery("#" + config.autoSuggestSearchFieldId).removeClass("selected");
		jQuery("#" + config.teamSelectId).val("0");
		jQuery("#" + config.playerSelectId).val("0");
		jQuery("#" + config.countrySelectId).val("0");
	}

	function preloadPlayerInfo(config)
	{
		if(config.configType=="A")
		{
			playerInitId = playerAInitId;
			playerInitName = playerAInitName;
			playerInitTeamId = playerAInitTeamId;
			playerInitTeamName = playerAInitTeamName;
			playerInitCountryId = playerAInitCountryId;
		}
		else
		{
			playerInitId = playerBInitId;
			playerInitName = playerBInitName;
			playerInitTeamId = playerBInitTeamId;
			playerInitTeamName = playerBInitTeamName;
			playerInitCountryId = playerBInitCountryId;
		}
		
		if(playerInitId && playerInitName)
		{
			jQuery("#" + config.playerIdInput).val(playerInitId);
			jQuery("#" + config.autoSuggestSearchFieldId).val(playerInitName);
			jQuery("#" + config.playerSelectId).append(
					jQuery('<option></option>').val(playerInitId).html(playerInitName)
		    ).val(playerInitId);
			jQuery("#" + config.autoSuggestSearchFieldId).addClass("selected");
		}
		if(playerInitTeamId && playerInitTeamName)
		{
			jQuery("#" + config.teamSelectId).append(
					jQuery('<option></option>').val(playerInitTeamId).html(playerInitTeamName)
		    ).val(playerAInitTeamId);
					
		}
		if(playerInitCountryId)
		{
			jQuery("#" + config.countrySelectId).val(playerInitCountryId);
		}
	}
	
	
	function resetTeamInfo(config)
	{
		jQuery("#" + config.teamIdInput).val('');
		jQuery("#" + config.autoSuggestSearchFieldId).val('');
		jQuery("#" + config.autoSuggestSearchFieldId).removeClass("selected");
		jQuery("#" + config.teamSelectId).val("0");
		jQuery("#" + config.countrySelectId).val("0");
	}

	function preloadTeamInfo(config)
	{
		if(config.configType=="A")
		{
			teamInitId = teamAInitId;
			teamInitName = teamAInitName;
			teamInitTeamId = teamAInitTeamId;
			teamInitTeamName = teamAInitTeamName;
			teamInitCountryId = teamAInitCountryId;
		}
		else
		{
			teamInitId = teamBInitId;
			teamInitName = teamBInitName;
			teamInitTeamId = teamBInitTeamId;
			teamInitTeamName = teamBInitTeamName;
			teamInitCountryId = teamBInitCountryId;
		}
		//console.log("teamInitId: " + teamInitId + " teamInitId: " + teamInitId );
		if(teamInitId && teamInitName)
		{
			jQuery("#" + config.teamIdInput).val(teamInitId);
			jQuery("#" + config.autoSuggestSearchFieldId).val(teamInitName);
			jQuery("#" + config.teamSelectId).append(
					jQuery('<option></option>').val(teamInitId).html(teamInitName)
		    ).val(teamInitId);
			jQuery("#" + config.autoSuggestSearchFieldId).addClass("selected");
		}
		
		if(teamInitCountryId)
		{
			jQuery("#" + config.countrySelectId).val(teamInitCountryId);
		}
	}

	/*
	* Pass it an element and it will look into all the properties to see if one matches the ID of the element and returns the configuration
		object for that element
	*/

	function getPlayerConfig(element)
	{
		var config = {};
		for(var attribute in playerAConfig)
		{
			//console.log("Player A attribute value: " + playerAConfig[attribute] + "- id: " + element.attr("id") );
			if(element.attr("id") == playerAConfig[attribute] || element.attr("name") == playerAConfig[attribute])
			{
				return playerAConfig;
			}
		}
		for(var attribute in playerBConfig)
		{
			//console.log("Player B attribute value: " + attribute + "- id: " + element.attr("id") );
			if(element.attr("id") == playerBConfig[attribute] || element.attr("name") == playerBConfig[attribute])
			{
				return playerBConfig;
			}
		}
	}
	
	/*
	* Pass it an element and it will look into all the properties to see if one matches the ID of the element and returns the configuration
		object for that element
	*/

	function getTeamConfig(element)
	{
		var config = {};
		for(var attribute in teamAConfig)
		{
			//console.log("attribute: " + attribute + "- id: " + element.attr("id") );
			if(element.attr("id") == teamAConfig[attribute] || element.attr("name") == teamAConfig[attribute])
			{
				return teamAConfig;
			}
		}
		for(var attribute in teamBConfig)
		{
			//console.log("attribute: " + attribute + "- id: " + element.attr("id") );
			if(element.attr("id") == teamBConfig[attribute] || element.attr("name") == teamBConfig[attribute])
			{
				return teamBConfig;
			}
		}
	}
	
	/*
	*	Clears the validation error messages
	*/
	function clearValidationMessages()
	{
		jQuery("#h2hvalidations > ul").html('');
		if(jQuery("#h2hvalidations").is(':visible'))
		{
			jQuery("#h2hvalidations").fadeOut(200);
		}
	}


//loading popup with jQuery magic!
function loadPopup(popupname){
	//loads popup only if it is disabled
	
		jQuery("#backgroundPopup").css({
			"opacity": "1.7"
		});
		jQuery("#backgroundPopup").fadeIn("slow");
		jQuery("#" + popupname).fadeIn("slow");
		window.scroll(0,0);
		
}


function disablePopup1(){
		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#selectteam").fadeOut("slow");
}
function disablePopup2(){
		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#h2hcontainer").fadeOut("slow");
}



//centering popup
function centerPopup(popupname){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = jQuery("#" + popupname ).height();
	var popupWidth = jQuery("#" + popupname).width();
	//centering
	jQuery("#" + popupname).css({
		"position": "absolute",
		"top": 50,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	jQuery("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

/*
* Changes the currently selected player, leaves the other one intact
*/
function changePlayerSelection(selectedSide)
{
	if(selectedSide == 'left')
	{
		preloadPlayerInfo(playerBConfig);
		resetPlayerInfo(playerAConfig);
		loadHead2HeadPopup();
		
	}
	else
	{
		preloadPlayerInfo(playerAConfig);
		resetPlayerInfo(playerBConfig);
		loadHead2HeadPopup();
	}
	return false;
}
/*
* Changes the currently selected team, leaves the other one intact
*/
function changeTeamSelection(selectedSide)
{
	if(selectedSide == 'left')
	{
		preloadTeamInfo(teamBConfig);
		resetTeamInfo(teamAConfig);
		loadHead2HeadPopup();
		
	}
	else
	{
		preloadTeamInfo(teamAConfig);
		resetTeamInfo(teamBConfig);
		loadHead2HeadPopup();
	}
	return false;
}


function loadHead2HeadPopup()
{
	//centering with css
	centerPopup('h2hcontainer');
	//load popup
	loadPopup('h2hcontainer');
}

//CONTROLLING EVENTS IN jQuery
jQuery(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	jQuery("#select,#selectButton").click(function(){
		preLoadTeamValues(teamAConfig,teamBConfig);
		loadHead2HeadPopup();
		return false;
	});	
				
	jQuery("#select1").click(function(){
		loadHead2HeadPopup()
		return false;
	});	
	
	
	
	//Team Autocomplete H2H
	jQuery("#selectt1").click(function(){
		preLoadTeamValues(teamAConfig,teamBConfig);
		loadHead2HeadPopup();
		return false;
	});	
	


	//Player Autocomplete H2H
	jQuery("#selectplayer1,#select1").click(function(){
		preLoadPlayerValues(playerAConfig,playerBConfig);
		loadHead2HeadPopup();
		return false;
	});	
	
	


				
	//CLOSING POPUP
	//Click the x event!
	
	jQuery("#aboutclose1").click(function(){
		disablePopup1();
	});	
	jQuery("#aboutclose2").click(function(){
		disablePopup2();
	});	
	jQuery("#cancelclose").click(function(){
		disablePopup1();
	});
	jQuery("#cancelclose1").click(function(){
		disablePopup2();
	});
	

	jQuery(document).keypress(function(e){
		if(e.keyCode==27){
			disablePopup1();
			disablePopup2();
		}
	});
	

});