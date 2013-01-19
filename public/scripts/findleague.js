jQuery(document).ready(function() {
	var leagueRssFeedUrl = contextPath + "/competitions/rss/id/";	
		//attach autocomplete
		jQuery("#leagueSearchInput").autocomplete({
			 minLength: 2,
			 width:500,
			//define callback to format results
			source: function(req, add){  
			  
	        //pass request to server  
	        $.getJSON("/competitions/autocompleteleague?callback=?", req, function(data) {  

	            //create array for response objects  
	            var suggestions = [];  

	            //process response  
	            $.each(data, function(i, val)
	                    {  
	            			suggestions.push(
	                    			{
	                        			label:val.competition_name,
	                        			value:val.competition_name,
	                        			league_id:val.competition_id,
	                        			competition_type: val.type,
	                        			competition_format: val.type,
	                        			competition_region_name: val.region_name,
	                        			competition_region_group_name: val.region_group_name,
	                        			competition_country_name: val.country_name,
	                        			competition_country_id: val.country_id,
	                        			competition_country_code: val.country_code,
	                        			flag_path:contextPath + "/utility/imagecrop?w=18&h=12&zc=1&src=" + contextPath + root_crop + "/flags/" +  val.country_id + ".png",
	                        			regional:val.regional
	                  
	                        		}
	                        	);  
	       		 		}
		 		);  
	            //Adding Search League "term" link to the list of results.
	            suggestions.push(
            			{
                			label:'Search Leagues for',
                			value:req.term,
                			search:true,
                			url: contextPath + 'search/?q=' + req.term + '&t=leagues'
                		}
                	);  

	        	//pass array to callback  
	        	add(suggestions);  
	   		 });  
			},  
			

			//define select handler
			select: function(e, ui) {
			
				 $("#searchLeagueId").val(ui.item.league_id);
				 $("#searchLeagueName").val(ui.item.label);
				 $("#subscribeLeagueSearchButtons").fadeIn("slow");
				 $(".leagueRssFeedSubscribe").attr("href", leagueRssFeedUrl + ui.item.league_id );
				 //Redirect to search page
				 if(ui.item.search)
				 {
					 top.location.href = ui.item.url;
				 }
				
		
			},
			//Opening the menu
			open: function(event, ui) {
				//Clear the Subscribe link
				 $("#subscribeLeagueSearchButtons").fadeOut('fast');
				 $("#searchLeagueId").val('');
				 $("#searchLeagueName").val('');
				
			},

			//define change handler
			focus: function() {

				
			}
		})
		
		.data("autocomplete")._renderItem = function( ul, item ) {
			
			var itemHtml= '<a>'+ item.label + ' &nbsp;<span>&nbsp; ' + item.competition_country_code + '&nbsp;<img src="' + item.flag_path +  '"/>&nbsp;' + item.competition_format + '</span>' + ' </a>' ;
			if(item.search)
			{
				itemHtml =  '---------------------------------------------------<br><a href="' +contextPath + 'search/?q=' + item.value+ '&t=leagues">'+ item.label + ' "' + item.value + '"&raquo;</a>';
			}
			
			return $( "<li></li>" )
           .data( "item.autocomplete", item )
           .append(itemHtml )
           .appendTo( ul );
       };

   		//Refresh the popular leagues
		jQuery('.refreshLeague').click(function()
				{
					$("#popularLeaguesContainer").mask("Refreshing...");
					$("#popularLeaguesContainer").load(contextPath + "rss/refreshpopularleagues", function(){
						
						$("#popularLeaguesContainer").unmask();
					})
				}
		);

		
		//Subscribe behavior for dynamic links
		
		jQuery('.subscribeToSearchLeagueRSS').click(function()
				{
					
					var leagueId =  $("#searchLeagueId").val();
					var leagueName = $("#searchLeagueName").val();
					//alert("league Id: " +  leagueId + " league name: " +  leagueName );
					subscribeToLeague( leagueId, leagueName ); 
					return false;
				}
		);
		//Make sure this value matches the value assigned in the search input form
		var defaultSearchValue="Enter league or tournament name";
		//Clear the input on focus
		jQuery("#leagueSearchInput").focus(function()
				{
					if(this.value == defaultSearchValue)
					{
						$(this).val('');
						$("#searchLeagueId").val('');
						$("#searchLeagueName").val('');
					}
				}
		)
		jQuery("#leagueSearchInput").blur(function()
				{
					if(this.value == '')
					{
						 $(this).val(defaultSearchValue);
					}
				}
		)
		
		
		
		//Browse by Club or National league Monitoring
		jQuery("input[name='searchWidget_leagueSelectionType']").change(function()
			{
				var selectionType = $("input[name='searchWidget_leagueSelectionType']:checked").val();  
				lazyLoadCountryList(); 
				jQuery("#searchWidget_leagueCountry").val("");
				jQuery("#searchWidget_leagueClub").val('');
				if(selectionType == '1')
				{
					jQuery("#searchWidget_leagueClub").html("<option value='0'>Select A Club League</option>");
				}
				else
				{
					jQuery("#searchWidget_leagueClub").html("<option value='0'>Select A National League</option>");
				}
				toggleLeagueBrowseSubscribeButton();     
			
			}
		);
		//Lazy load the country list
		jQuery("#searchWidget_leagueCountry").click(function()
				{
					lazyLoadLeagueCountryList();
				}
		);

		function lazyLoadLeagueCountryList()
		{
			//Load the list of countries by Ajax
			if(jQuery('#searchWidget_leagueCountry').children('option').length <= 1 )
			{
				loadComboBox('searchWidget_leagueCountry', contextPath + '/rss/getLeagueCountriesJson');					
			}     
		}

		//Load either the League combo or the League combo on changing the country, depending on Club/National browsing
		jQuery("#searchWidget_leagueCountry").change(function()
				{
					var country_id =  jQuery("#searchWidget_leagueCountry").val()
					var typeCode = "league";
					var comboId =  'searchWidget_leagueSelected';
					//Set the hidden input values
					populateWidgetCombo(comboId, country_id, typeCode);					
					toggleLeagueBrowseSubscribeButton();
					
				}
		);
		//Load the league roster if a club is selected
		jQuery("#searchWidget_leagueSelected").change(function()
				{
					 
					 var leagueId = $('#searchWidget_leagueSelected').val();
					 $("#searchLeagueId").val(leagueId);
					 $("#searchLeagueName").val($('#searchWidget_leagueSelected option:selected').text());
					 toggleLeagueBrowseSubscribeButton();
					 var leagueId = $('#searchLeagueId').val();
					
				}
		);

		
	
		//Shows the susbscribe link if a league is selected
		function toggleLeagueBrowseSubscribeButton()
		{
			
			
				if(parseInt(jQuery("#searchWidget_leagueSelected").val()) > 1)
				{
					jQuery('#subscribeLeagueBrowseButtons').fadeIn('fast');		
				}
				else
				{	
					jQuery('#subscribeLeagueBrowseButtons').fadeOut('fast');	
				}
				 //$("#searchLeagueId").val(jQuery('#searchWidget_leagueClub').val());
				 $(".leagueRssFeedSubscribe").attr("href", leagueRssFeedUrl + jQuery('#searchWidget_leagueSelected').val() );
			
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
		     	ajaxload = 'ajaxloaderLeaguePlayer';
			}
			else if(data == 'club' || data == 'national')
			{
				url = contextPath + '/team/findleaguesbycountry';
				ajaxload = 'ajaxloaderLeague';
			}
			else if(data == 'leagueplayer')
			{
				 url = contextPath + '/player/findplayersbyleague';
				 ajaxload = 'ajaxloaderPlayer';
			}
			else if(data == 'league')
			{
				url = contextPath + '/competitions/searchcompetitionsselect';
			}	 	
			$("#leagueACSearchContainer").mask("Loading...");
			jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
				$("#leagueACSearchContainer").unmask();
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
		lazyLoadLeagueCountryList();
		
		
		
	 	

});
 

 function subscribeToLeague(league_id, league_name)
	{
	 	
	 	if(!league_id)
		{
			alert("No league was specified to subscribe to!");
		}
		else
		{
			
			jQuery('#modalBodyId').show();
			 jQuery.ajax(
						{
							type: 'POST',
							url :  contextPath + '/competitions/getleague',
							dataType:'json',
							data : ({id: league_id}),
							success: function(data)
							{
								if(data.status=="1")
								{
									addfavoriteleague(data);
								}
								else
								{
									jQuery('#modalBodyId').hide();
									alert(data.msg);
									
								}
							}	
						});

			
		}
		return false;
	}
 

function addfavoriteleague(leagueInfo)
{
     if(!leagueInfo || !leagueInfo.league_details)
     {
    	 jQuery('#modalBodyId').hide();
    	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteleaguetrigger', onHide: closeModal });
    	 jQuery('#addFavoriteModal').jqmShow();
    	 jQuery('#modalBodyResponseId').html("Sorry, something went wrong with suscribing to this league's updates! Try later.");
    	 jQuery('#acceptFavoriteModalButtonId').hide();
		 jQuery('#modalBodyResponseId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
		 return;
     }
 
	 var leagueId = leagueInfo.league_details.competition_id;	
	 var leagueName = leagueInfo.league_details.competition_name;	
	 var leagueImageId = '#league' + leagueId + 'profileImage';
	 var leagueImageFileName = leagueInfo.league_image;
	 //If the league is part of popular load, just get the src
	 var favoriteImageUrl =  $(leagueImageId).attr('src');
	 if (!favoriteImageUrl)
	 {
	     favoriteImageUrl = leagueInfo.league_image_path;
	 }
	 var isFavorite = leagueInfo.isFavorite;
	
	//jQuery('#modalBodyId').show();
     jQuery('#modalBodyResponseId').hide();
     jQuery('#acceptFavoriteModalButtonId').show();
     jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
     jQuery('#modalFavoriteTitleId').html('Add to Favorites');
     jQuery('#dataText1').html('Add ' + leagueName + ' to your favorite leagues?');
     jQuery('#title5Id').html(leagueName);
     jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteleaguetrigger', onHide: closeModal });
     jQuery('#checkBoxUpdates').show();
     jQuery('#addFavoriteModal').jqmShow();
     jQuery('#favoriteImageSrcId').attr('src',favoriteImageUrl);
     jQuery("#acceptFavoriteModalButtonId").unbind();
     jQuery('#acceptFavoriteModalButtonId').click(function(){
    	 
	 
	 if(isFavorite === "true")
	 {
		
		 jQuery('#modalBodyResponseId').html("You've already subscribed to " + leagueName + "'s updates!");
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
	         url :  contextPath+ "competitions/addfavorite",
	         data : ({leagueId: leagueId, updatesCheck : updatesCheck}),
	         dataType:'json',
	         success: function(data){
				 	jQuery('#modalBodyId').hide();
			 		//console.dir(data);
			 		jQuery('#acceptFavoriteModalButtonId').hide();
			 		jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
			 		if(data.status == "1")
					{
				 		jQuery('#modalBodyResponseId').html(leagueName + " has been added to your favorites.");
				 		jQuery('#modalBodyResponseId').show();
				 		jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 1000).jqmHide();
						
					}
					else
					{
						//console.log(data.msg);
						jQuery('#modalBodyResponseId').html(data.msg);
						jQuery('#modalBodyResponseId').show();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					}
	         }
	     })
	
	 });
     } 
