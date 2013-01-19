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

function unsubscribeToPlayer(player_id, player_name) {
	 if(!player_id)
		{
			apprise("No player was specified to unsubscribe to!");
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
									removefavoriteplayer(data);
									
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
	 }

jQuery('#modalBodyId').show();
	 jQuery('#modalBodyResponseId').hide();
	 jQuery('#acceptFavoriteModalButtonId').show();
	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
	 jQuery('#modalFavoriteTitleId').html('Subscribe');
	 jQuery('#dataText1').html("Subscribe to " + playerName +  "'s updates.");
	 jQuery('#dataText1').attr('href', $('#player' + playerId + 'profileLink').attr('href'));
	 
	 jQuery('#title5Id').html(playerName);	 
	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
	 jQuery('#checkBoxUpdates').show();	 
	 jQuery('#addFavoriteModal').jqmShow();
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
					jQuery('#acceptFavoriteModalButtonId').hide();
			 		jQuery('#btn_player_on_'+ playerId).addClass('ScoresClosed');
			 		jQuery('#btn_player_off_'+ playerId).removeClass('ScoresClosed');
			 		
				 	if(data.status == "1")
					{
				 		jQuery('#modalBodyResponseId').html("You have been subscribed to " + playerName + "'s updates.");
				 		jQuery('#modalBodyResponseId').show();
				 		jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();

					}
					else
					{
						jQuery('#modalBodyResponseId').html(data.msg);
						jQuery('#modalBodyResponseId').show();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					}
	
				}	
			});
	 });	
}

function removefavoriteplayer(playerInfo){

	 if(!playerInfo)
	 {
		 jQuery('#modalBodyResponseId').html("Sorry, something went wrong with unsuscribing to this player's updates! Try later");
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
	 //console.log(playerName);
	 
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
	 

	 //jQuery('#modalBodyId').show();
	 jQuery('#modalBodyResponseId').hide();
	 jQuery('#acceptFavoriteModalButtonId').show();
	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
	 jQuery('#modalFavoriteTitleId').html('Remove from Favorites?');
	 jQuery('#dataText1').html("Remove " + playerName +  " from your favorite Players?");
	 jQuery('#dataText1').attr('href', $('#player' + playerId + 'profileLink').attr('href'));
	 
	 //jQuery('#title5Id').html(playerName);	 
	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
	 jQuery('#checkBoxUpdates').hide();	 
	 jQuery('#addFavoriteModal').jqmShow();
	 jQuery('#favoriteImageSrcId').attr('src',favoriteImageUrl);
	 
	 jQuery("#acceptFavoriteModalButtonId").unbind();
	 jQuery('#acceptFavoriteModalButtonId').click(function(){

		 jQuery.ajax({
			 type: 'POST',
				url :  contextPath + '/player/removefavorite',
				data : ({id: playerId}),
				success: function(data){
					jQuery('#modalBodyResponseId').html("You have been unsubscribed from " + playerName + "'s updates.");
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#btn_player_off_'+ playerId).addClass('ScoresClosed');
			 		jQuery('#btn_player_on_'+ playerId).removeClass('ScoresClosed');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
		});
		
	 });
	
}