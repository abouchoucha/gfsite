jQuery(document).ready(function() {
       
	var playerRssFeedUrl = contextPath + "/player/rss/id/";	
	//attach autocomplete
		jQuery("#playerSearchInput").autocomplete({
			 minLength: 2,
			 width:500,
			//define callback to format results
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
	            //Adding Search League "term" link to the list of results.
	            suggestions.push(
            			{
                			label:'Search Players for',
                			value:req.term,
                			search:true,
                			url: contextPath + 'search/?q=' + req.term + '&t=players'
                		}
                	);  

	        	//pass array to callback  
	        	add(suggestions);  
	   		 });  
			},  
			

			//define select handler
			select: function(e, ui) {
			
				 $("#searchPlayerId").val(ui.item.player_id);
				 $("#searchPlayerName").val(ui.item.label);
				 $("#subscribePlayerSearchButtons").fadeIn("slow");
				 $(".playerRssFeedSubscribe").attr("href", playerRssFeedUrl + ui.item.player_id );
				//Redirect to search page
				 if(ui.item.search)
				 {
					 top.location.href = ui.item.url;
				 }
		
			},
			//Opening the menu
			open: function(event, ui) {
				//Clear the Subscribe link
				 $("#subscribePlayerSearchButtons").fadeOut('fast');
				$("#searchPlayerId").val('');
				$("#searchPlayerName").val('');
				
			},

			//define change handler
			focus: function() {

				
			}
		})
		.data("autocomplete")._renderItem = function( ul, item ) {
			var itemHtml=  '<a>'+ item.label + '</a>';
			if(item.search)
			{
				itemHtml =  '---------------------------------------------------<br><a href="' +contextPath + 'search/?q=' + item.value+ '&t=players">'+ item.label + ' "' + item.value + '"&raquo;</a>';
			}
			
			return $( "<li></li>" )
           .data( "item.autocomplete", item )
           .append( itemHtml)
           .appendTo( ul );
       };
		
	
		//Subscribe behavior for dynamic links
		jQuery('.subscribeToSearchPlayerRSS').click(function()
				{
					subscribeToPlayer($("#searchPlayerId").val(), $("#searchPlayerName").val() ); 
					return false;
				}
		);
		
		//Refresh the popular players		
		jQuery('.refreshPlayer').click(function()
				{
					$("#popularPlayersContainer").mask("Refreshing...");
					$("#popularPlayersContainer").load(contextPath + "rss/refreshpopularplayers", function(){
						
						$("#popularPlayersContainer").unmask();
					})
				}
		);
		//Make sure this value matches the value assigned in the auto complete search input form
		var defaultSearchValue="Enter player name";
		//Clear the input on focus
		jQuery("#playerSearchInput").focus(function()
				{			 
					//console.log("it's a hit!!!");
					if(this.value == defaultSearchValue)
					{
						 $(this).val('');
						 $("#searchPlayerId").val('');
						 $("#searchPlayerName").val('');
						 $("#subscribePlayerSearchButtons").fadeOut('fast');
					}
				}
		)
		
		//Set the input to default value on blur
		jQuery("#playerSearchInput").blur(function()
				{
					if(this.value == '')
					{
						 $(this).val(defaultSearchValue);
					}
				}
		)
		
		//Browse by Club or National team Monitoring
		jQuery("input[name='searchWidget_playerSelectionType']").change(function()
			{
				var selectionType = $("input[name='searchWidget_playerSelectionType']:checked").val();  
				lazyLoadCountryList(); 
				jQuery("#searchWidget_playerSelected").html("<option value='0'>Select A Player</option>");
				jQuery("#searchWidget_playerClub").html("<option value='0'>Select A Club Team</option>");
				jQuery("#searchWidget_playerCountry").val("");
				if(selectionType == '0') 
				{
					
					jQuery("#searchWidget_playerClub").fadeOut('fast');
					
				} 
				else 
				{
					
					jQuery("#searchWidget_playerClub").fadeIn('fast');
										
				}
				togglePlayerBrowseSubscribeButton();     
			
			}
		);
		//Lazy load the country list
		jQuery("#searchWidget_playerCountry").click(function()
				{
					lazyLoadCountryList();
				}
		);

		function lazyLoadCountryList(countrySelectId)
		{
			//Load the list of countries by Ajax
			if(jQuery('#searchWidget_playerCountry').children('option').length <= 1 )
			{
				loadComboBox('searchWidget_playerCountry', contextPath + '/rss/getCountriesJson');					
			}     
		}

		//Load either the Player combo or the Team combo on changing the country, depending on Club/National browsing
		jQuery("#searchWidget_playerCountry").change(function()
				{
					var country_id =  jQuery("#searchWidget_playerCountry").val()
					//console.log("Selected Value: " + jQuery("input[name='searchWidget_playerSelectionType']").val());
					var teamTypeCode = (jQuery("input[name='searchWidget_playerSelectionType']:checked").val() == '1')?'club':'player';
					var comboId =  (jQuery("input[name='searchWidget_playerSelectionType']:checked").val() == '1')?'searchWidget_playerClub':'searchWidget_playerSelected'
					populateWidgetCombo(comboId, country_id, teamTypeCode);
					togglePlayerBrowseSubscribeButton();
				}
		);
		//Load the team roster if a club is selected
		jQuery("#searchWidget_playerClub").change(function()
				{
					var team_id =  jQuery("#searchWidget_playerClub").val();
					var teamTypeCode='teamplayer'
					var comboId =  'searchWidget_playerSelected';
					populateWidgetCombo(comboId, team_id, teamTypeCode);
					togglePlayerBrowseSubscribeButton();
				}
		);

		//Show the subscribe button for the player selected
		jQuery("#searchWidget_playerSelected").change(function()
				{
					togglePlayerBrowseSubscribeButton();
					//Set the hidden input values
					 $("#searchPlayerId").val($('#searchWidget_playerSelected').val());
					 $("#searchPlayerName").val($('#searchWidget_playerSelected option:selected').text());
					
				}
		);


		//Shows the susbscribe link if a player is selected
		function togglePlayerBrowseSubscribeButton()
		{
			if(parseInt(jQuery("#searchWidget_playerSelected").val()) > 1)
			{
				jQuery('#subscribePlayerBrowseButtons').fadeIn('fast');		
			}
			else
			{	
				jQuery('#subscribePlayerBrowseButtons').fadeOut('fast');	
			}
			 $("#searchPlayerId").val(jQuery('#searchWidget_playerSelected').val());
			 $(".playerRssFeedSubscribe").attr("href", playerRssFeedUrl + $('#searchWidget_playerSelected').val() );
		}

		
		

		//Populates the comboboxes
		function populateWidgetCombo(dtarget , id , data)
		{
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
			$("#playerACSearchContainer").mask("Loading...");
			jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
				 $("#playerACSearchContainer").unmask();
			});
		} 

		function loadComboBox(comboId, url)
		{
			//jQuery('#'+ajaxload).show();
			$.getJSON(url, function(data)
				{
					var comboBox = $('#' + comboId)
					$.each(data.countries, function() {
						comboBox.append($("<option />").val(this.country_id).text(this.country_name));
					});
										
				}
			);
		}

		//Populate the country box
		lazyLoadCountryList();

		
		
	 	

});
 

function subscribeToPlayer(player_id, player_name)
{
	if(!isLoggedIn)
	{
		loginModal();
		return;
	}
	 if(!player_id)
		{
			apprise("No player was specified to subscribe to!");
		}
		else
		{

			var updatesCheck = 1, jsonAction=1;
			jQuery('#modalBodyId').show();
			 jQuery.ajax(
						{
							type: 'POST',
							url :  contextPath + '/player/getuniqueplayer',
							dataType:'json',
							data : ({id: player_id}),
							success: function(data)
							{
								if(data.status=="1")
								{
									addfavoriteplayer(data);
								}
								else
								{
									jQuery('#modalBodyId').hide();
									apprise(data.msg);
									
								}
							}	
						});
			
		}
		return false;
	}
 
 function addfavoriteplayer(playerInfo)
 {

	 if(!playerInfo)
     {
    	 jQuery('#modalBodyResponseId').html("Sorry, something went wrong with suscribing to this player's updates! Try later");
		 jQuery('#modalBodyResponseId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
		 return;
     }
	 var playerId = playerInfo.player_details.player_id;	
	 var playerName = playerInfo.player_details.player_common_name;	
	 var playerImageId = '#player' + playerId + 'profileImage';
	 var playerImageFileName = playerInfo.player_image;
	 //If the player is part of popular load, just get the src
	 var favoriteImageUrl =  $(playerImageId).attr('src');
	 var isFavorite = playerInfo.isFavorite;
	 
	 //console.log(favoriteImageUrl);

	 if(!favoriteImageUrl)
	 { 
		 if(playerImageFileName)
		 {
			 favoriteImageUrl = contextPath + "/utility/imagecrop?w=80&h=80&zc=1&src=" + contextPath + root_crop + "/players/" +  playerImageFilename;
		 }
		 else
		 {
			 favoriteImageUrl = contextPath + "/utility/imagecrop?w=80&h=80&zc=1&src=" + contextPath + root_crop + "/ProfileMale.gif";
				
		 }
	 }
	
	 if(isFavorite === "true")
	 {
		 jQuery('#modalBodyResponseId').html("You've already subscribed to " + playerName + "'s updates!");
		 jQuery('#modalBodyResponseId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
		 return;
		 return;
	 }
//	 jQuery('#modalBodyId').show();
	 jQuery('#modalBodyResponseId').hide();
	 jQuery('#acceptFavoriteModalButtonId').show();
	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
	 jQuery('#modalFavoriteTitleId').html('Add to your Favorites');
	 jQuery('#dataText1').html("Add " + playerName +  " to your favorite Players?");
	 jQuery('#dataText1').attr('href', $('#player' + playerId + 'profileLink').attr('href'));
	 
	 jQuery('#title5Id').html(playerName);
	 
	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });

	 jQuery('#checkBoxUpdates').show();
	 
	 jQuery('#addFavoriteModal').jqmShow();
	
	
	 //console.log(playerImageId);
	 jQuery('#favoriteImageSrcId').attr('src',favoriteImageUrl);

	 jQuery("#acceptFavoriteModalButtonId").unbind();
	 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 var updatesCheck = "0";
		 if(jQuery("#updatesCheck").is(':checked')){
			 updatesCheck = "1";
		 }
		
		 jQuery.ajax({
				type: 'POST',
				url :  contextPath + '/player/addfavorite',
				data : ({playerId: playerId , updatesCheck : updatesCheck,jsonAction:'true'}),
				dataType:'json',
				success: function(data)
				{
			 		jQuery('#modalBodyId').hide();
			 		//console.dir(data);
			 		jQuery('#acceptFavoriteModalButtonId').hide();
			 		jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
			 		if(data.status == "1")
					{
				 		jQuery('#modalBodyResponseId').html(playerName + " has been added to your favorites.");
				 		jQuery('#modalBodyResponseId').show();
				 		jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
						
					}
					else
					{
						//console.log(data.msg);
						jQuery('#modalBodyResponseId').html(data.msg);
						jQuery('#modalBodyResponseId').show();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					}
					
				}	
			});
	 });	
}
