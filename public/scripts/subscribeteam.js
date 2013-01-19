function subscribeToTeam(team_id, team_name)
{
	if(!isLoggedIn)
	{
		loginModal();
		return;
	}
	
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

function unsubscribeToTeam(team_id, team_name)
{
	if(!isLoggedIn)
	{
		loginModal();
		return;
	}
	
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
								removefavoriteteam(data);
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
     
	//console.log(contextPath);
     
	 jQuery.ajax({
	         type: 'POST',
	         url :  contextPath+ "/team/addfavorite",
	         data : ({teamId: teamId, updatesCheck : updatesCheck}),
	         dataType:'json',
	
	         success: function(data){
				 	jQuery('#modalBodyId').hide();
			 		//console.dir(data);
			 		jQuery('#acceptFavoriteModalButtonId').hide();
			 		jQuery('#btn_team_on_'+ teamId).addClass('ScoresClosed');
			 		jQuery('#btn_team_off_'+ teamId).removeClass('ScoresClosed');
				 	
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


function removefavoriteteam(teamInfo){

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
	 
    jQuery('#modalBodyId').show();
    jQuery('#modalBodyResponseId').hide();
    jQuery('#acceptFavoriteModalButtonId').show();
    jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
    jQuery('#modalFavoriteTitleId').html('Remove From Favorities');
    jQuery('#dataText1').html('Remove ' +teamName + ' from your favorite teams?');
    jQuery('#title1Id').html('Country:');
    jQuery('#dataText2').html(teamName);
    jQuery('#favoriteImageSrcId').attr('src',favoriteImageUrl); 
    jQuery('#checkBoxUpdates').hide();
    jQuery('#addFavoriteModal').jqm({trigger: '#removefromfavoriteteamtrigger', onHide: closeModal });
    jQuery('#addFavoriteModal').jqmShow();
   
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

    jQuery("#acceptFavoriteModalButtonId").unbind();
    jQuery('#acceptFavoriteModalButtonId').click(function(){
        jQuery.ajax({
            type: 'POST',
            url :  contextPath+ "/team/removefavorite",
            data : ({teamId: teamId}),
            success: function(data){
                jQuery('#modalBodyResponseId').html(teamName + ' has been removed from your favorite teams.');
                jQuery('#modalBodyId').hide();
                jQuery('#modalBodyResponseId').show();
                jQuery('#acceptFavoriteModalButtonId').hide();
                jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
                jQuery('#remove').removeClass('Display').addClass('ScoresClosed');
                jQuery('#favorite').removeClass('ScoresClosed').addClass('Display');
            	jQuery('#btn_team_off_'+ teamId).addClass('ScoresClosed');
		 		jQuery('#btn_team_on_'+ teamId).removeClass('ScoresClosed');
                jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
            }
        })

    });
}


