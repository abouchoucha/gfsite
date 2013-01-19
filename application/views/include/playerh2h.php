<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>
<!-- New Form with AutoComplete -->
<div class="ammid" id="h2hcontainer" >
	<div class="rsleft">
		<div class="fdplayer" id="playerASearchContainer">		
			<span class="close"  id="aboutclose2">
        <a class="closeModalCls" href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/cross1.jpg" alt=""/></a>
      </span>
			<p style="clear:both"></p>
			<h3>Compare Players</h3>
			<p class="rssBodyText">Enter the names of the players you would like to compare in the form fields below.</p>
			<div id="h2hvalidations" class="ErrorMessageIndividual">
				<h3>Please correct the following errors:</h3>
				<ul>
				</ul>
			</div>
			<span class="fpser"><b>Player 1:</b></span>
			<span class="fpser">
				<label>Search:</label>
				<input type="text"  value="" id="playerASearchInput" class="autosuggestfield mailtb playerautocomp ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
				<input type="hidden" value="" class="playerAIdInput" id="playerA_Id">
			</span>
			
			<!--
			<span class="fpser orLabel">OR</span>
			<div class="sbox">
				<div class="sbox_labels">
					<span class="sel"><label>Browse By:</label></span>
					<span class="nation"><input type="radio" class="teamTypeCls"  id="rbTeamTypePlayerA" name="rbTeamTypePlayerA" value="2" > Nationality</span>
					<span class="club"><input type="radio" class="teamTypeCls"  id="rbTeamTypePlayerA" name="rbTeamTypePlayerA" checked="checked"  value="1">Club Team</span>
					
					
				</div>	
				<div class="sbox_controls">
					<span class="selcon" id="countryAselection">
						<select class="selcon1 countrySelectCls" id="countryteama" >
							<option selected="selected">Select Country</option>
							<?php foreach ($this->countries as $league) 
							{
								$selectedOption = "";	
								
							?>
			                        <option value="<?php echo $league["country_id"];?>" <?php echo $selectedOption;?>><?php echo $league["country_name"];?></option>
			                <?php
							} 
			                ?>
						</select>
					</span>
					<span class="selcon" id="teamAselection">
						<select class="selcon1 teamSelectCls" id="teamselectida">
							  <option value="0" selected>Select A Team</option>
						</select>
					</span>
					<span class="selcon" id="playerAselection">
						<select class="selcon1 playerSelectCls" id="playerselectida">
							  <option value="0" selected>Select A Player</option>
						</select>
					</span>
				</div>
			</div>
			-->
			
			<h1 class="versus">VS</h1>
			
			
			<span class="fpser"><b>Player 2:</b></span>
			<span class="fpser">
				<label>Search:</label>
				<input type="text"  value="" id="playerBSearchInput" class="autosuggestfield mailtb playerbutocomp ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
				<input type="hidden" value="" id="playerB_Id">
			</span>
			
			<!--
			<span class="fpser orLabel">OR</span>
			<div class="sbox">
				<div class="sbox_labels">
					<span class="sel"><label>Browse By:</label></span>
					<span class="nation"><input type="radio" class="teamTypeCls"  id="rbTeamTypePlayerB" name="rbTeamTypePlayerB" value="2" > Nationality</span>
					<span class="club"><input type="radio" class="teamTypeCls"  id="rbTeamTypePlayerB" name="rbTeamTypePlayerB" checked="checked"  value="1">Club Team</span>
					
					
				</div>	
				<div class="sbox_controls">
					<span class="selcon" id="countryBselection">
						<select class="selcon1 countrySelectCls" id="countryteamb">
							<option selected="selected">Select Country</option>
							<?php foreach ($this->countries as $league) 
							{
								$selectedOption = "";	
								
								?>
			                        <option value="<?php echo $league["country_id"];?>" <?php echo $selectedOption;?>><?php echo $league["country_name"];?></option>
			                <?php 
							} 
			                ?>
						</select>
					</span>
					<span class="selcon" id="teamBselection">
						<select class="selcon1 teamSelectCls" id="teamselectidb">
							 <option value="0" selected>Select A Team</option>
						</select>
					</span>
					<span class="selcon" id="playerBselection">
						<select class="selcon1 playerSelectCls" id="playerselectidb">
							  <option value="0" selected>Select A Player</option>
						</select>
					</span>
				</div>
			</div>
		  -->
		  
			<span class="view1"><input type="button" value="View Head To Head" name="viewHead2Head" id="viewHead2Head"/></span>
			<span class="cancel"><input id="cancelclose1" type="button" value="cancel" name=""/></span>
			
			
		
		</div>
		
	</div>
</div>

<div id="backgroundPopup"></div>
	
<script type="text/JavaScript">

jQuery(document).ready(function() {
	

	//Player A complete
	jQuery('autosuggestfield').focus(function(){
		jQuery(this).val('');
		var config = getPlayerConfig(jQuery(this));
		jQuery('#' + config.playerIdInput).val('');
		changeTeamType(config);
	}).
	blur(function(){
		if(jQuery(this).val() == "" )
		{
			jQuery(this).val('Start typing a player name...');
			var config = getPlayerConfig(jQuery(this));
			changeTeamType(config);
		}
	});
	

	//attach autocomplete to player A input
	jQuery("#" + playerAConfig.autoSuggestSearchFieldId).autocomplete({
		 minLength: 2,
		 selectFirst: true,
		 autoFocus: true,
		 autoSelect: true,
		//define callback to format results== 
		source: function(req, add){  
		  
        //pass request to server  
        $.getJSON("/player/autocompleteplayer?callback=?", req, function(data) {  

            //create array for response objects  
            var suggestions = [];  

            //process response  
            $.each(data, function(i, val)
                    {  
            			suggestions.push(
                    			{
                        			label:val.player_common_name,
                        			value:val.player_common_name,
                        			player_id:val.player_id
                        		}
                        	);  
       		 		}
	 		);  

        	//pass array to callback  
        	add(suggestions);  
   		 });  
		},  

		

		//define select handler
		select: function(e, ui) {
		
			 jQuery("#" + playerAConfig.playerIdInput).val(ui.item.player_id);
			 jQuery(this).addClass("selected");
	
		},

		//define select handler
		change: function() {

			
		}
	});

	//attach autocomplete to input B
	jQuery("#" + playerBConfig.autoSuggestSearchFieldId).autocomplete({
		selectFirst: true,
		 minLength: 2,
		 autoFocus: true,
		 autoSelect: true,
		//define callback to format results
		source: function(req, add){  
		  
        //pass request to server  
        $.getJSON("/player/autocompleteplayer?callback=?", req, function(data) {  

            //create array for response objects  
            var suggestions = [];  

            //process response  
            $.each(data, function(i, val){  
            suggestions.push(	{
    			label:val.player_common_name,
    			value:val.player_common_name,
    			player_id:val.player_id
    		});  
       		 });  

        	//pass array to callback  
        	add(suggestions);  
   		 });  
		},  

		

		//define select handler
		select: function(e, ui) {

			 $("#" + playerBConfig.playerIdInput).val(ui.item.player_id);
			 jQuery(this).addClass("selected");
	
		},

		//define select handler
		change: function() {

			
		}
	});

	
	//Update the diplay when choosing Club/National team
	jQuery('input.teamTypeCls').change(function(){
		var config = getPlayerConfig(jQuery(this));
		changeTeamType(config);
	});

	//Load either the Player combo or the Team combo on changing the country, depending on the user Club/National browsing
	jQuery("select.countrySelectCls").change(function()
			{
				var config = getPlayerConfig(jQuery(this));
				loadTeamList(config);
			}
	);

	//Load the team roster if a club is selected
	jQuery("select.teamSelectCls").change(function()
			{
				var config = getPlayerConfig(jQuery(this));
				loadPlayerList(config);
			}
	);

	//Update the Auto Suggest Box fors the player selected
	jQuery("select.playerSelectCls").change(function()
			{
				var config = getPlayerConfig(jQuery(this));
				updateAutoSuggestField(config);
			}
	);
	

	/*
	* Clears the AutoSuggest input with
	*/
	function clearAutoSuggestField(config)
	{
		//Clear the hidden input value for playerId
		 $("#" + config.autoSuggestSearchFieldId).val('');
		 $("#" +  config.playerIdInput).val('');
		 jQuery("#" + config.autoSuggestSearchFieldId).removeClass("selected");
	}

	/*
	* Updates the AutoSuggest field with the value from the Dropdown selected player
	*/
	function updateAutoSuggestField(config)
	{
		//Set the hidden input values
		 $("#" + config.autoSuggestSearchFieldId).val($('#' + config.playerSelectId + '> option:selected').text());
		 $("#" +  config.playerIdInput).val($('#' + config.playerSelectId).val());
		 jQuery("#" + config.autoSuggestSearchFieldId).addClass("selected");
		 //console.log("selected player name: " + $('#' + config.playerSelectId + '> option:selected').text() + " id: " + $('#' + config.playerSelectId).val() );
	}
	
	
	/*
	* Load either the Team dropdown if Club is selected, or the Player dropdown if National is selected
	*/
	function loadTeamList(config)
	{
		var country_id =  jQuery("#"+ config.countrySelectId).val();
		//console.log("Selected Value: " + jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val() );
		//console.dir(config);
		var teamTypeCode = ( jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val() == '1')?'club':'player';
		var comboIdToPopulate =  (jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val() == '1')?config.teamSelectId:config.playerSelectId
		populateWidgetCombo(comboIdToPopulate, country_id, teamTypeCode);
		clearAutoSuggestField(config);
		//console.log("back in call");
		
	}
	
	/*
	* Load the list of players based on Club Team selection
	*/
	function loadPlayerList(config)
	{
		var team_id =  jQuery("#" + config.teamSelectId).val();
		var teamTypeCode='teamplayer';
		clearAutoSuggestField(config);
		populateWidgetCombo(config.playerSelectId, team_id, teamTypeCode);
	}
	
	/*
	* Updates the display based on whether Club or National selection is performed
	*/
	function changeTeamType(config)
	{
		//console.log("rbTeamType: " + selectionType );
		lazyLoadCountryList(config.countrySelectId); 
		jQuery("#" + config.playerSelectId).html("<option value='0'>Select A Player</option>");
		jQuery("#" + config.teamSelectId).html("<option value='0'>Select A Club Team</option>");
		jQuery("#"+ config.countrySelectId).val("");
		toggleTeamSelect(config);
		clearAutoSuggestField(config);

	}

	function resetPlayer(config)
	{
		
	}


	//Populates the comboboxes
	function populateWidgetCombo(dtarget , id , data)
	{
		//console.trace();
		var url = null;
		var ajaxload = null;
		//console.log("populate combo " + dtarget + " id: " + id + " data type: " + data);
		if(data == 'player')
		{
	     	url = contextPath + '/player/findplayersbycountry';
	     	ajaxload = 'ajaxloaderTeamPlayer';
		}
		else if(data == 'club' || data == 'national')
		{
			url = contextPath + '/team/findteamsbycountry';
			ajaxload = 'ajaxloaderTeam';
		}
		else if(data == 'teamplayer')
		{
			 url = contextPath + '/player/findplayersbyteam';
			 ajaxload = 'ajaxloaderPlayer';
		}
		else if(data == 'league')
		{
			url = contextPath + '/competitions/searchcompetitionsselect';
		}	 	

		$("#h2hcontainer").mask("Loading...");
		jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
			$("#h2hcontainer").unmask();
		});
		//console.log("The ajax call is done!");
	} 

	function loadComboBox(comboId, url)
	{
		//jQuery('#'+ajaxload).show();
	
		
		$.getJSON(url, function(data)
			{
				var comboBox = $('#' + comboId);
				$.each(data.countries, function() {
					comboBox.append($("<option />").val(this.country_id).text(this.country_name));
					
				});
				
									
			}
		);
	}

	/*
	* Loads the country list in the dropdown with id countrySelectId
	*/
	function lazyLoadCountryList(countrySelectId)
	{
		//Load the list of countries by Ajax
		if(jQuery('#' + countrySelectId).children('option').length <= 1 )
		{
			loadComboBox(countrySelectId, contextPath + '/rss/getCountriesJson');					
		}     
	}

	//Shows the susbscribe link if a player is selected
	function toggleTeamSelect(config)
	{
		var selectionType = jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val();
		if(selectionType == '1') 
		{
			//Show the club dropdown
			jQuery("#" + config.teamSelectContainerId).fadeIn(200);
			
		} 
		else 
		{
			//Hide the club dropdown
			jQuery("#" + config.teamSelectContainerId).fadeOut('fast');
								
		}
	}


	//Click the View Head to Head button event. Handle validation and submit if it passes 
	jQuery('#viewHead2Head').click(function(){
		if(validateHead2HeadSelectTeamsAndPlayers())
		{
			var teama = "";
			var teamb = "";
			var playera = jQuery("#" + playerAConfig.playerIdInput).val();
			var playerb = jQuery("#" + playerBConfig.playerIdInput).val();
			var competitionid = jQuery("#competitionid").val();
			if((playera !='0') && (playerb !='0')) 
	
			{
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headplayers/teama/'+teama+'/teamb/'+teamb+'/playera/'+playera+'/playerb/'+playerb;
				top.location.href = url;
				disablePopup1();
			}
		}
	});


	
	
	/*
	* Validates that there is a player A and player B selected by checking on the hidden playerId variable since it's the one needed to create the AutoComplete
	*/
	function validateHead2HeadSelectTeamsAndPlayers() {
		var playera_id = jQuery("#" + playerAConfig.playerIdInput).val();
		var playerb_id = jQuery("#" + playerBConfig.playerIdInput).val();
		if(!playera_id || !playerb_id)
		{
			
			validationHTML="";
			if(!playera_id)
			{
				validationHTML += "<li>" + playerAConfig.validationErrorMessage + "</li>";
			}
			if(!playerb_id)
			{
				validationHTML += "<li>" + playerBConfig.validationErrorMessage + "</li>";
			}
			jQuery("#h2hvalidations > ul").html(validationHTML);
			jQuery("#h2hvalidations").fadeIn(200);
			return false;
		}
		clearValidationMessages();
		return true;
		
	}

	

	//Hide or show the team selection
	toggleTeamSelect(playerAConfig);
	toggleTeamSelect(playerBConfig);
	lazyLoadCountryList(playerAConfig.countrySelectId);
	lazyLoadCountryList(playerBConfig.countrySelectId);
	//preLoadPlayerValues(playerAConfig,playerBConfig);
	

});	
		

</script>
