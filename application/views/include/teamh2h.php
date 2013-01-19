<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>
<!-- New Form with AutoComplete -->
<div class="ammid" id="h2hcontainer" >
	<div class="rsleft">
		<div class="fdplayer" id="teamASearchContainer">		
			<span class="close"  id="aboutclose2"><a class="closeModalCls" href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/cross1.jpg" alt=""/></a></span>
			<p style="clear:both"></p>
			<h3>Compare Teams</h3>
			<p class="rssBodyText">Enter the names of the teams you would like to compare in the form fields below.</p>
			<div id="h2hvalidations" class="ErrorMessageIndividual">
				<h3>Please correct the following errors:</h3>
				<ul>
				</ul>
			</div>
			<span class="fpser"><b>Team 1:</b></span>
			<span class="fpser">
				<label>Search:</label>
				<input type="text"  value="" id="teamASearchInput" class="autosuggestfield mailtb teamautocomp ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
				<input type="hidden" value="" class="teamAIdInput" id="teamA_Id">
			</span>
			<span class="fpser orLabel">OR</span>
			<div class="sbox">
				<div class="sbox_labels">
					<span class="sel"><label>Browse By:</label></span>
					<span class="nation"><input type="radio" class="teamTypeCls"  id="rbTeamTypeTeamA" name="rbTeamTypeTeamA" value="2" > National Team</span>
					<span class="club"><input type="radio" class="teamTypeCls"  id="rbTeamTypeTeamA" name="rbTeamTypeTeamA" checked="checked"  value="1">Club Team</span>
					
					
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
					
				</div>
			</div>
			<h1 class="versus">VS</h1>
			<span class="fpser"><b>Team 2:</b></span>
			<span class="fpser">
				<label>Search:</label>
				<input type="text"  value="" id="teamBSearchInput" class="autosuggestfield mailtb teambutocomp ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
				<input type="hidden" value="" id="teamB_Id">
			</span>
			<span class="fpser orLabel">OR</span>
			<div class="sbox">
				<div class="sbox_labels">
					<span class="sel"><label>Browse By:</label></span>
					<span class="nation"><input type="radio" class="teamTypeCls"  id="rbTeamTypeTeamB" name="rbTeamTypeTeamB" value="2" > National Team</span>
					<span class="club"><input type="radio" class="teamTypeCls"  id="rbTeamTypeTeamB" name="rbTeamTypeTeamB" checked="checked"  value="1">Club Team</span>
					
					
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
					
				</div>
			</div>
		   
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
		var config = getTeamConfig(jQuery(this));
		jQuery('#' + config.teamIdInput).val('');
		changeTeamType(config);
	}).
	blur(function(){
		if(jQuery(this).val() == "" )
		{
			jQuery(this).val('Start typing a team name...');
			var config = getTeamConfig(jQuery(this));
			changeTeamType(config);
		}
	});
	

	//attach autocomplete to team A input
	jQuery("#" + teamAConfig.autoSuggestSearchFieldId).autocomplete({
		 minLength: 2,
		 width:500,
		 selectFirst: true,
		 autoFocus: true,
		 autoSelect: true,
		//define callback to format results== 
		source: function(req, add){  
		  
        //pass request to server  
        $.getJSON("/team/autocompleteteam?callback=?", req, function(data) {  

            //create array for response objects  
            var suggestions = [];  

            //process response  
            $.each(data, function(i, val)
                    {  
            			suggestions.push(
                    			{
                    				label:val.team_name,
	                      			value:val.team_name,
	                      			team_id:val.team_id,
	                      			country_id: val.country_id,
	                      			country_code:val.country_code,
	                      			flag_path:contextPath + "/utility/imagecrop?w=18&h=12&zc=1&src=" + contextPath + root_crop + "/flags/" +  val.country_id + ".png"	
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
		
			 jQuery("#" + teamAConfig.teamIdInput).val(ui.item.team_id);
			 jQuery(this).addClass("selected");
	
		},

		//define select handler
		change: function() {

			
		}
	}).data("autocomplete")._renderItem = function( ul, item ) {
		return $( "<li></li>" )
       .data( "item.autocomplete", item )
       .append( '<a>'+ item.label + ' &nbsp;<span><strong> ' + item.country_code + '</strong>&nbsp;' + '<img src="' + item.flag_path +  '"/></span></a>' )
       .appendTo( ul );
   };

	//attach autocomplete to input B
	jQuery("#" + teamBConfig.autoSuggestSearchFieldId).autocomplete({
		selectFirst: true,
		 width:500,
		 minLength: 2,
		 autoFocus: true,
		 autoSelect: true,
		//define callback to format results
		source: function(req, add){  
		  
        //pass request to server  
        $.getJSON("/team/autocompleteteam?callback=?", req, function(data) {  

            //create array for response objects  
            var suggestions = [];  

            //process response  
            $.each(data, function(i, val){  
            suggestions.push(	{
            	label:val.team_name,
      			value:val.team_name,
      			team_id:val.team_id,
      			country_id: val.country_id,
      			country_code:val.country_code,
      			flag_path:contextPath + "/utility/imagecrop?w=18&h=12&zc=1&src=" + contextPath + root_crop + "/flags/" +  val.country_id + ".png"	
    		});  
       		 });  

        	//pass array to callback  
        	add(suggestions);  
   		 });  
		},  

		

		//define select handler
		select: function(e, ui) {

			 $("#" + teamBConfig.teamIdInput).val(ui.item.team_id);
			 jQuery(this).addClass("selected");
	
		},

		//define select handler
		change: function() {

			
		}
	}) .data("autocomplete")._renderItem = function( ul, item ) {
		return $( "<li></li>" )
	       .data( "item.autocomplete", item )
	       .append( '<a>'+ item.label + ' &nbsp;<span><strong> ' + item.country_code + '</strong>&nbsp;' + '<img src="' + item.flag_path +  '"/></span></a>' )
	       .appendTo( ul );
	   };

	
	//Update the diplay when choosing Club/National team
	jQuery('input.teamTypeCls').change(function(){
		var config = getTeamConfig(jQuery(this));
		changeTeamType(config);
	});

	//Load either the Player combo or the Team combo on changing the country, depending on the user Club/National browsing
	jQuery("select.countrySelectCls").change(function()
			{
				var config = getTeamConfig(jQuery(this));
				loadTeamList(config);
			}
	);


	//Update the Auto Suggest Box fors the team selected
	jQuery("select.teamSelectCls").change(function()
			{
				var config = getTeamConfig(jQuery(this));
				updateAutoSuggestField(config);
			}
	);
	

	/*
	* Clears the AutoSuggest input with
	*/
	function clearAutoSuggestField(config)
	{
		//Clear the hidden input value for teamId
		 $("#" + config.autoSuggestSearchFieldId).val('');
		 $("#" +  config.teamIdInput).val('');
		 jQuery("#" + config.autoSuggestSearchFieldId).removeClass("selected");
	}

	/*
	* Updates the AutoSuggest field with the value from the Dropdown selected team
	*/
	function updateAutoSuggestField(config)
	{
		//Set the hidden input values
		 $("#" + config.autoSuggestSearchFieldId).val($('#' + config.teamSelectId + '> option:selected').text());
		 $("#" +  config.teamIdInput).val($('#' + config.teamSelectId).val());
		 jQuery("#" + config.autoSuggestSearchFieldId).addClass("selected");
		 //console.log("selected team name: " + $('#' + config.teamSelectId + '> option:selected').text() + " id: " + $('#' + config.teamSelectId).val() );
	}
	
	
	/*
	* Load either the Team dropdown if Club is selected, or the Player dropdown if National is selected
	*/
	function loadTeamList(config)
	{
		var country_id =  jQuery("#"+ config.countrySelectId).val()
		//console.log("Selected Value: " + jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val() );
		//console.dir(config);
		var teamTypeCode = ( jQuery('input[name="' + config.teamTypeInputName + '"]:checked').val() == '1')?'club':'national';
		var comboIdToPopulate =  config.teamSelectId;
		populateWidgetCombo(comboIdToPopulate, country_id, teamTypeCode);
		clearAutoSuggestField(config);
		//console.log("back in call");
		
	}
	
	/*
	* Load the list of teams based on Club Team selection
	*/
	function loadPlayerList(config)
	{
		var team_id =  jQuery("#" + config.teamSelectId).val();
		var teamTypeCode='teamteam';
		clearAutoSuggestField(config);
		populateWidgetCombo(config.teamSelectId, team_id, teamTypeCode);
	}
	
	/*
	* Updates the display based on whether Club or National selection is performed
	*/
	function changeTeamType(config)
	{
		//console.log("rbTeamType: " + selectionType );
		lazyLoadCountryList(config.countrySelectId); 
		jQuery("#" + config.teamSelectId).html("<option value='0'>Select A Player</option>");
		jQuery("#" + config.teamSelectId).html("<option value='0'>Select A Club Team</option>");
		jQuery("#"+ config.countrySelectId).val("");
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
		if(data == 'team')
		{
	     	url = contextPath + '/team/findteamsbycountry';
	     	ajaxload = 'ajaxloaderTeamPlayer';
		}
		else if(data == 'club' || data == 'national')
		{
			url = contextPath + '/team/findteamsbycountry';
			ajaxload = 'ajaxloaderTeam';
		}
		else if(data == 'teamteam')
		{
			 url = contextPath + '/team/findteamsbyteam';
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

	//Shows the susbscribe link if a team is selected
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
			var playera = "";
			var playerb = "";
			var teama = jQuery("#" + teamAConfig.teamIdInput).val();
			var teamb = jQuery("#" + teamBConfig.teamIdInput).val();
			var competitionid = jQuery("#competitionid").val();
			if((teama !='0') && (teamb !='0')) 
	
			{
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/teama/'+teama+'/teamb/'+teamb;
				top.location.href = url;
				disablePopup1();
			}
		}
	});


	
	
	/*
	* Validates that there is a team A and team B selected by checking on the hidden teamId variable since it's the one needed to create the AutoComplete
	*/
	function validateHead2HeadSelectTeamsAndPlayers() {
		var teama_id = jQuery("#" + teamAConfig.teamIdInput).val();
		var teamb_id = jQuery("#" + teamBConfig.teamIdInput).val();
		if(!teama_id || !teamb_id)
		{
			
			validationHTML="";
			if(!teama_id)
			{
				validationHTML += "<li>" + teamAConfig.validationErrorMessage + "</li>"
			}
			if(!teamb_id)
			{
				validationHTML += "<li>" + teamBConfig.validationErrorMessage + "</li>"
			}
			jQuery("#h2hvalidations > ul").html(validationHTML);
			jQuery("#h2hvalidations").fadeIn(200);
			return false;
		}
		
		clearValidationMessages();
		
		return true;
		
	}

	

	//Hide or show the team selection

	lazyLoadCountryList(teamAConfig.countrySelectId);
	lazyLoadCountryList(teamBConfig.countrySelectId);
	//preLoadTeamValues(teamAConfig,teamBConfig);
	

});	
		

</script>







