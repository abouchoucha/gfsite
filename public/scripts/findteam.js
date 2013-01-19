jQuery(document).ready(function() {
	var teamRssFeedUrl = contextPath + "/team/rss/id/";	

		//attach autocomplete
		jQuery("#teamSearchInput").autocomplete({
			 minLength: 2,
			 width:500,
			//define callback to format results
			source: function(req, add){  
			  
	        //pass request to server  
	        $.getJSON("/team/autocompleteteam?callback=?", req, function(data) {  

	            //create array for response objects  
	            var suggestions = [];  
	            //console.dir(req);
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
	            //uncomment if the search should be done by any other type than 'clubs'
	            //var teamTypeCode = (jQuery("input[name='searchWidget_teamSelectionType']:checked").val() == '1')?'clubs':'national';
	            //Adding Search "term" link to the list of results.
	            suggestions.push(
            			{
                			label:'Search Clubs for',
                			value:req.term,
                			search:true,
                			url: contextPath + 'search/?q=' + req.term + '&t=clubs'
                		}
                	);  

	        	//pass array to callback  
	        	add(suggestions);  
	   		 });  
			},  
			

			//define select handler
			select: function(e, ui) {
			
				//alert("Team Id: " + ui.item.team_id + " team name: " + ui.item.label); 
				$("#searchTeamId").val(ui.item.team_id);
				 $("#searchTeamName").val(ui.item.label);
				 $("#subscribeTeamSearchButtons").fadeIn("slow");
				 $(".teamRssFeedSubscribe").attr("href", teamRssFeedUrl + ui.item.team_id );
				 //Redirect to search page
				 if(ui.item.search)
				 {
					 top.location.href = ui.item.url;
				 }
				
		
			},
			//Opening the menu
			open: function(event, ui) {
				//Clear the Subscribe link
				 $("#subscribeTeamSearchButtons").fadeOut('fast');
				 $("#searchTeamId").val('');
				 $("#searchTeamName").val('');
				
			},

			//define change handler
			focus: function() {

				
			}
		})
		
		.data("autocomplete")._renderItem = function( ul, item ) {
			var itemHtml=  '<a>'+ item.label + ' &nbsp;<span><strong> ' + item.country_code + '</strong>&nbsp;' + '<img src="' + item.flag_path +  '"/></span></a>';
			if(item.search)
			{
				//var teamTypeCode = (jQuery("input[name='searchWidget_teamSelectionType']:checked").val() == '1')?'clubs':'national';
				var teamTypeCode = 'clubs';
				itemHtml =  '---------------------------------------------------<br><a href="' +contextPath + 'search/?q=' + item.value+ '&t=' + teamTypeCode + '">'+ item.label + ' "' + item.value + '"&raquo;</a>';
			}
			
			return $( "<li></li>" )
           .data( "item.autocomplete", item )
           .append( itemHtml)
           .appendTo( ul );
       };


		
		//Subscribe behavior for dynamic links
		
		jQuery('.subscribeToSearchTeamRSS').click(function()
				{
					subscribeToTeam( $("#searchTeamId").val(), $("#searchTeamName").val() ); 
					return false;
				}
		);
		
		//Refresh the popular teams
		
		jQuery('.refreshTeam').click(function()
				{
					$("#popularTeamsContainer").mask("Refreshing...");
					$("#popularTeamsContainer").load(contextPath + "rss/refreshpopularteams", function(){
						
						$("#popularTeamsContainer").unmask();
					})
				}
		);
		//Make sure this value matches the value assigned in the search input form
		var defaultSearchValue="Enter team name";
		
		//Clear the input on focus
		jQuery("#teamSearchInput").focus(function()
				{
					if(this.value == defaultSearchValue)
					{
						 $(this).val('');
						 $("#searchTeamId").val('');
						 $("#searchTeamName").val('');
					}
				}
		)
		
		//Set the input to default value on blur
		jQuery("#teamSearchInput").blur(function()
				{
					if(this.value == '')
					{
						 $(this).val(defaultSearchValue);
					}
				}
		)
		
		
		
		//Browse by Club or National team Monitoring
		jQuery("input[name='searchWidget_teamSelectionType']").change(function()
			{
				var selectionType = $("input[name='searchWidget_teamSelectionType']:checked").val();  
				lazyLoadCountryList(); 
				jQuery("#searchWidget_teamCountry").val("");
				jQuery("#searchWidget_teamClub").val('');
				if(selectionType == '1')
				{
					jQuery("#searchWidget_teamClub").html("<option value='0'>Select A Club Team</option>");
				}
				else
				{
					jQuery("#searchWidget_teamClub").html("<option value='0'>Select A National Team</option>");
				}
				toggleTeamBrowseSubscribeButton();     
			
			}
		);
		//Lazy load the country list
		jQuery("#searchWidget_teamCountry").click(function()
				{
					lazyLoadCountryList();
				}
		);

		function lazyLoadCountryList()
		{
			//Load the list of countries by Ajax
			if(jQuery('#searchWidget_teamCountry').children('option').length <= 1 )
			{
				loadComboBox('searchWidget_teamCountry', contextPath + '/rss/getCountriesJson');					
			}     
		}

		//Load either the Team combo or the Team combo on changing the country, depending on Club/National browsing
		jQuery("#searchWidget_teamCountry").change(function()
				{
					var country_id =  jQuery("#searchWidget_teamCountry").val()
					var teamTypeCode = (jQuery("input[name='searchWidget_teamSelectionType']:checked").val() == '1')?'club':'national';
					var comboId =  'searchWidget_teamClub';
					//Set the hidden input values
					populateWidgetCombo(comboId, country_id, teamTypeCode);					
					toggleTeamBrowseSubscribeButton();
					
				}
		);
		//Load the team roster if a club is selected
		jQuery("#searchWidget_teamClub").change(function()
				{
					
					 $("#searchTeamId").val($('#searchWidget_teamClub').val());
					 $("#searchTeamName").val($('#searchWidget_teamClub option:selected').text());
					 toggleTeamBrowseSubscribeButton();
					
				}
		);

		
	
		//Shows the susbscribe link if a team is selected
		function toggleTeamBrowseSubscribeButton()
		{
			
			
				if(parseInt(jQuery("#searchWidget_teamClub").val()) > 1)
				{
					jQuery('#subscribeTeamBrowseButtons').fadeIn('fast');		
				}
				else
				{	
					jQuery('#subscribeTeamBrowseButtons').fadeOut('fast');	
				}
				 $("#searchTeamId").val(jQuery('#searchWidget_teamClub').val());
				 $(".teamRssFeedSubscribe").attr("href", teamRssFeedUrl + jQuery('#searchWidget_teamClub').val() );
			
		}

		
		

		//Populates the comboboxes
		function populateWidgetCombo(dtarget , id , data)
		{
			var url = null;
			var ajaxload = null;
			//alert("populate combo " + dtarget + " id: " + id + " data type: " + data);
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
			$("#teamACSearchContainer").mask("Loading...");
			jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
				$("#teamACSearchContainer").unmask();
			});
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

		//Populate the country box
		lazyLoadCountryList();
		
		
		
	 	

});
 

 function subscribeToTeam(team_id, team_name)
	{
		if(!team_id)
		{
			alert("No team was specified to subscribe to!");
		}
		else
		{
			
			jQuery('#modalBodyId').show();
			 jQuery.ajax(
						{
							type: 'POST',
							url :  contextPath + '/team/getuniqueteam',
							dataType:'json',
							data : ({id: team_id}),
							success: function(data)
							{
								if(data.status=="1")
								{
									addfavoriteteam(data);
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
 

function addfavoriteteam(teamInfo)
{
     if(!teamInfo || !teamInfo.team_details)
     {
    	 jQuery('#modalBodyId').hide();
    	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteteamtrigger', onHide: closeModal });
    	 jQuery('#addFavoriteModal').jqmShow();
    	 jQuery('#modalBodyResponseId').html("Sorry, something went wrong with suscribing to this team's updates! Try later.");
    	 jQuery('#acceptFavoriteModalButtonId').hide();
		 jQuery('#modalBodyResponseId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
		 return;
     }
	 var teamId = teamInfo.team_details.team_id;	
	 var teamName = teamInfo.team_details.team_name;	
	 var teamImageId = '#team' + teamId + 'profileImage';
	 var teamImageFileName = teamInfo.team_image;
	 //If the team is part of popular load, just get the src
	 var favoriteImageUrl =  $(teamImageId).attr('src');
	 if (!favoriteImageUrl)
	 {
	     favoriteImageUrl = teamInfo.team_image_path;
	 }
	 var isFavorite = teamInfo.isFavorite;
	 /*if (!favoriteImageUrl)
	 {
	     favoriteImageUrl = contextPath + '/utility/imageCrop?w=60&h=60&zc=1&src=' + contextPath + root_crop + '/teamlogos/' + teamId + '.gif';
	 }
	 else
	 {
		 favoriteImageUrl =contextPath = '/utility/imageCrop?w=60&h=60&zc=1&src=' + contextPath + root_crop + '/TeamText.gif';
	 }*/
	 
	
	
	//jQuery('#modalBodyId').show();
     jQuery('#modalBodyResponseId').hide();
     jQuery('#acceptFavoriteModalButtonId').show();
     jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
     jQuery('#modalFavoriteTitleId').html('Add to Favorites');
     jQuery('#dataText1').html('Add ' + teamName + ' to your favorite teams?');
     jQuery('#title5Id').html(teamName);
     jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteteamtrigger', onHide: closeModal });
     jQuery('#checkBoxUpdates').show();
     jQuery('#addFavoriteModal').jqmShow();
     jQuery('#favoriteImageSrcId').attr('src',favoriteImageUrl);
     jQuery("#acceptFavoriteModalButtonId").unbind();
     jQuery('#acceptFavoriteModalButtonId').click(function(){
    	 
	 
	 if(isFavorite === "true")
	 {
		
		 jQuery('#modalBodyResponseId').html("You've already subscribed to team " + teamName + "'s updates!");
		 jQuery('#modalBodyResponseId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
		 return;
	 }	 
	 var updatesCheck = "0";
	 if(jQuery("#updatesCheck").is(':checked')){
			 updatesCheck = "1";
		 }
	 jQuery.ajax({
	         type: 'POST',
	         url :  contextPath+ "team/addfavorite",
	         data : ({teamId: teamId, updatesCheck : updatesCheck}),
	         dataType:'json',
	         success: function(data){
				 	jQuery('#modalBodyId').hide();
			 		//console.dir(data);
			 		jQuery('#acceptFavoriteModalButtonId').hide();
			 		jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
			 		if(data.status == "1")
					{
				 		jQuery('#modalBodyResponseId').html("Team " + teamName + " has been added to your favorites.");
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
