<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jqModal.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.autocomplete.js" type="text/javascript"></script>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jqModal.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jquery.autocomplete.css' rel="stylesheet" type="text/css" media="screen"/>

<script type="text/javascript">

jQuery(document).ready(function() {

	
	focusBlurElement('ft1','club');
	focusBlurElement('ft2','club');
	focusBlurElement('fnt1','national');
	focusBlurElement('fnt2','national');
	focusBlurElement('fp1','player');
	focusBlurElement('fp2','player');
	focusBlurElement('fp3','player');
	focusBlurElement('fp4','player');
	focusBlurElement('fp5','player');
	//for clubs
    /*jQuery("#ft1").autocomplete('<?php //echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/club', {
		autoFill: true,
		mustMatch: true,
		matchContains: true,
		cacheLength : 20,
		delay : 200
    });*/
    jQuery("#ft1").autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/club');
    jQuery("#ft2").autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/club');
    //for national teams
    jQuery("#fnt1").autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/national');
    jQuery("#fnt2").autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/national');


	//players
    for (var j=3 ;j<=40 ;j++){
		//for clubs
    	jQuery("#ft"+j).autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/club');
    	focusBlurElement("ft"+j,'club');
    	//for national teams
    	jQuery("#fnt"+j).autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteambyname/t/national');
    	focusBlurElement("fnt"+j ,'national');
    }
    <?php for ($i = 1; $i <= 40; $i++) {?>
    jQuery('#ft<?echo $i;?>').result(function(event, data, formatted ) {
		if(data != undefined){
			jQuery('#clubteam<?echo $i;?>').val(data[1]);
			validateRepeatedElements('club','clubteam');
    	}
		
    });
    jQuery('#fnt<?echo $i;?>').result(function(event, data, formatted ) {
		jQuery('#nationalteam<?echo $i;?>').val(data[1]);
		validateRepeatedElements('national','nationalteam');
    });
    <?php } ?>
    
    //player
    for (var j=1 ;j<=40 ;j++){
        jQuery('#fp'+j).autocomplete('<?php echo Zend_Registry::get("contextPath"); ?>/player/findplayersregister');
        focusBlurElement('fp'+j,'player');
    }	
    <?php for ($i = 1; $i <= 40; $i++) {?>
    jQuery('#fp<?echo $i;?>').result(function(event, data, formatted ) {
		jQuery('#playerId<?echo $i;?>').val(data[1]);
		focusBlurElement("fp<?echo $i;?>" ,'player');
		validateRepeatedElements('player','playerId');
    });
    <?php } ?>
	<?php for ($i = 1; $i <= 40; $i++) {?>
    jQuery("#countryleagueselectid<?echo $i;?>").change(function(){
    	var countryid = jQuery("#countryleagueselectid<?echo $i;?>").val();
    	populateCombo('leagueselectid<?echo $i;?>',countryid ,'league');
    });
    jQuery("#leagueselectid<?echo $i;?>").change(function(){
    	validateRepeatedElements('league','leagueselectid');
    });
    <?php } ?>

    

    jQuery("#addclubid").click(function(){
    	var nextClubCounter = parseInt(jQuery("#teamscountid").val()) + 1;
    	if(nextClubCounter > 40){
    		jQuery('#clubadderror').html('Ooops.You can select up to  40 club teams .');
	    	jQuery('#clubadderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
        }
    	jQuery("#removeclub"+nextClubCounter).click(function(){
    		var previousClubCounter = parseInt(jQuery("#teamscountid").val()) - 1;
        	jQuery("#club"+nextClubCounter).hide();	
        	jQuery("#teamscountid").val(previousClubCounter);
    	});	
    	
    	jQuery("#club"+nextClubCounter).show();
    	jQuery("#ft"+nextClubCounter).addClass('fav_input');
    	jQuery("#ft"+nextClubCounter).val('Enter club team name');
    	jQuery("#teamscountid").val(nextClubCounter);
    });
    jQuery("#addnationalid").click(function(){
    	var nextClubCounter = parseInt(jQuery("#natteamscountid").val()) + 1;
    	if(nextClubCounter > 40){
    		jQuery('#nationalclubadderror').html('Ooops.You can select up to 40 national teams .');
	    	jQuery('#nationalclubadderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
        }
    	jQuery("#removenclub"+nextClubCounter).click(function(){
    		var previousClubCounter = parseInt(jQuery("#natteamscountid").val()) - 1;
        	jQuery("#nclub"+nextClubCounter).hide();	
        	jQuery("#natteamscountid").val(previousClubCounter);
    	});
    	jQuery("#nclub"+nextClubCounter).show();
    	jQuery("#fnt"+nextClubCounter).addClass('fav_input');
    	jQuery("#fnt"+nextClubCounter).val('Enter national team name');
    	jQuery("#natteamscountid").val(nextClubCounter);
    });  
    jQuery("#addplayerid").click(function(){
    	var nextClubCounter = parseInt(jQuery("#playerscountid").val()) + 1;
    	if(nextClubCounter > 40){
    		jQuery('#playeradderror').html('Ooops.You can select up to 40 favorite players.');
	    	jQuery('#playeradderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
        }
    	jQuery("#removeplayer"+nextClubCounter).click(function(){
    		var previousClubCounter = parseInt(jQuery("#playerscountid").val()) - 1;
        	jQuery("#player"+nextClubCounter).hide();	
        	jQuery("#playerscountid").val(previousClubCounter);
        	validateRepeatedElements('player','playerId');
    	});
    	jQuery("#player"+nextClubCounter).show();
    	jQuery("#fp"+nextClubCounter).addClass('fav_input');
    	jQuery("#fp"+nextClubCounter).val('Enter player name');
    	jQuery("#playerscountid").val(nextClubCounter);
    });

    jQuery("#addleagueid").click(function(){
        var nextClubCounter = parseInt(jQuery("#leaguescountid").val()) + 1;
    	if(nextClubCounter > 40){
    		jQuery('#leagueadderror').html('Ooops.You can select up to 40 favorite leagues.');
	    	jQuery('#leagueadderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
        }
    	jQuery("#removeleague"+nextClubCounter).click(function(){
    		var previousClubCounter = parseInt(jQuery("#leaguescountid").val()) - 1;
        	jQuery("#leaguecountry"+nextClubCounter).hide();	
        	jQuery("#leaguescountid").val(previousClubCounter);
    	});
    	
    	jQuery("#leaguecountry"+nextClubCounter).show();
    	jQuery("#leaguescountid").val(nextClubCounter);
    });

    	
    jQuery("#nationalityid").attr("checked", "checked");   
    jQuery('#teamselectid').hide();
    jQuery("input[@name='playerradio']").change(function(){
        if (jQuery("input[@name='playerradio']:checked").val() == 'clubteam'){
        	jQuery('#countryselectId').val('0');
        	jQuery('#teamselectid').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');
        	jQuery('#playerselectid').find('option').remove().end().append('<option value="0">Select Player</option>').val('0');   
        	jQuery('#teamselectid').show();
        }else{
        	jQuery('#countryselectId').val('0');
        	jQuery('#teamselectid').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');
        	jQuery('#playerselectid').find('option').remove().end().append('<option value="0">Select Player</option>').val('0');
        	jQuery('#teamselectid').hide();
        }   
    });

	jQuery("#countryselectId").change(function(){
    	var radiochecked =jQuery("input[@name='playerradio']:checked").val();
    	var countryid = jQuery("#countryselectId").val();
    	if(radiochecked == 'clubteam'){
			//jQuery('#playerselectid').html('Loading...');
			populateCombo('teamselectid',countryid ,'club');
        }else if(radiochecked == 'nationality'){
        	populateCombo('playerselectid',countryid ,'player');
        }
    });    
    //onchange the teamscombo
    jQuery("#teamselectid").change(function(){
    	var teamid = jQuery("#teamselectid").val();
    	populateCombo('playerselectid',teamid ,'teamplayer');
    });
    jQuery("#countryleagueselectid").change(function(){
    	var countryid = jQuery("#countryleagueselectid").val();
    	populateCombo('leagueselectid',countryid ,'league');
    });

	//on change the team country
    
    jQuery("#countryteamselectId").change(function(){
    	var countryid = jQuery("#countryteamselectId").val();
    	populateCombo('teamselectid2',countryid ,jQuery('#typeOfTeam').val());
        
    });
    

});


function focusBlurElement(id , type){
	var inputTitle ='';
	if(type == 'club'){
		inputTitle ='Enter club team name';
	}else if(type == 'national'){
		inputTitle ='Enter national team name';
	}else if(type == 'player'){
		inputTitle ='Enter player name';
	}

	jQuery("#"+id).focus(function(){
		if(jQuery.trim(jQuery(this).val())==inputTitle){
			jQuery(this).val('');
			jQuery("#"+id).removeClass('fav_input');
		}
	});	
	jQuery("#"+id).blur(function(){
		if(jQuery.trim(jQuery(this).val())==''){
			jQuery(this).val(inputTitle);
			jQuery("#"+id).addClass('fav_input');
		}
	});
	
}

function validateRepeatedElements(type ,typeOfElement){
	var currentNumberOfElements = null;
	var errorid = null;
	var typeelement = null;
	var typetext = null;
	if(type == 'club'){
		currentNumberOfElements = parseInt(jQuery("#teamscountid").val());
		errorid = 'clubadderror';
		typeelement = 'club team';
		typetext = 'ft';
	}else if(type == 'national'){
		currentNumberOfElements = parseInt(jQuery("#natteamscountid").val());
		errorid = 'nationalclubadderror';
		typeelement = 'national team';
		typetext = 'fnt';
	}else if(type == 'player'){
		currentNumberOfElements = parseInt(jQuery("#playerscountid").val());
		errorid = 'playeradderror';
		typeelement = type;
		typetext = 'fp';
	}else if(type == 'league'){
		currentNumberOfElements = parseInt(jQuery("#leaguescountid").val());
		errorid = 'leagueadderror';
		typeelement = 'favorite league';
	}	
	var j = 1;
	for (var i=0 ;i<currentNumberOfElements ;i++)
 	{
	 	var index = i+1;
	 	var fromCheck = jQuery('#'+typeOfElement+index).val();
	 	for (var j=0 ;j<currentNumberOfElements ;j++){
	 		var index2 = j+1;
	 		//alert("index1:"+index +" index2:"+index2);
	 		var toCheck = jQuery('#'+typeOfElement+index2).val();
	 		if(index != index2){
	 			if(fromCheck!='' && toCheck!='' && fromCheck == toCheck){
		 			//alert(fromCheck +'-'+ toCheck);
			 		jQuery('#'+errorid).html('Ooops . You cannot select the same favorite '+typeelement);
			    	jQuery('#'+errorid).removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			    	jQuery('#ErrorMessages').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
			    	jQuery('#MainErrorMessage').html('Ooops, there was a problem with the information your entered.Please correct the fields highlighted below.');
			    	jQuery('#'+typetext+index2).addClass('fav_input');
			    	jQuery('#'+typetext+index2).val('Enter '+typeelement+ 'name');
			    	return;
		 		}else{
		 			jQuery('#'+errorid).removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			    	jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
				 }
		 	}	
		}
	}
}




var closeModal = function(h) { 
    //t.html('Please Wait...');  // Clear Content HTML on Hide.
    h.o.remove(); // remove overlay
    h.w.fadeOut(888); // hide window
 };

 function showUpPlayerModal(variable){

	jQuery('#countryselectId').val('0');
	jQuery('#teamselectid').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');
	jQuery('#playerselectid').find('option').remove().end().append('<option value="0">Select Player</option>').val('0');
	
	jQuery('#teamselectid').hide();
	jQuery("#nationalityid").attr("checked", "checked");
	
	jQuery('#addPlayerModal').jqm({trigger: '#addFavPlayers', onHide: closeModal });
   	jQuery('#addPlayerModal').jqmShow();
	jQuery("#acceptModalButtonId").unbind();
   	jQuery("#acceptModalButtonId").click(function(){
   	   	var playerid = jQuery('#playerselectid').val();
   	   	if(playerid != 0){
	   		jQuery('#fp'+variable).val(jQuery('#playerselectid option:selected').text());
			jQuery('#playerId'+variable).val(jQuery('#playerselectid').val());
			validateRepeatedElements('player','playerId');
			jQuery("#fp"+variable).removeClass('fav_input');
			jQuery('#addPlayerModal').animate({opacity: '+=0'}, 1500).jqmHide();
   	   	} 
    });
}

 function showUpTeamModal(variable, type){

	jQuery('#countryteamselectId').val('0');
	jQuery('#teamselectid2').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');

	jQuery('#addTeamModal').jqm({ onHide: closeModal });
   	jQuery('#addTeamModal').jqmShow();
	jQuery('#typeOfTeam').val(type);

   	var elementId = null;
   	var hiddendata = null;
	if(type == 'club'){
		elementId = 'ft'+variable;
		hiddendata = 'clubteam'+variable;
	}else if(type == 'national'){
		elementId = 'fnt'+variable;
		hiddendata = 'nationalteam'+variable;
	}
		
   	jQuery("#acceptTeamModalButtonId").unbind();
   	jQuery("#acceptTeamModalButtonId").click(function(){
   		var teamId = jQuery('#teamselectid2').val();
   		if(teamId != 0){
			jQuery('#'+elementId).val(jQuery('#teamselectid2 option:selected').text());
			jQuery('#'+hiddendata).val(jQuery('#teamselectid2').val());
			jQuery("#"+elementId).removeClass('fav_input');
    		jQuery('#addTeamModal').animate({opacity: '+=0'}, 1500).jqmHide(); 
   		}	
    });
}
 
 
function populateCombo(dtarget , id , data){
	 var url = null;
	 var ajaxload = null;
	 if(data == 'player'){
     	url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findplayersbycountry';
     	ajaxload = 'ajaxloaderTeamPlayer';
	 }else if(data == 'club' || data == 'national'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findteamsbycountry';
		ajaxload = 'ajaxloaderTeam';
	 }else if(data == 'teamplayer'){
		 url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findplayersbyteam';
		 ajaxload = 'ajaxloaderPlayer';
	 }else if(data == 'league'){
		 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/searchcompetitionsselect';
	 }	 	
	 jQuery('#'+ajaxload).show();
	 jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
		 jQuery('#'+ajaxload).hide();
	 });
} 
 

</script>

<div id="addPlayerModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId">Find Player</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal AddFavoritesModal">
                <table id="addfavtbl_player" class="addfavtbl" cellspacing="0" border="0">
                    <tr>
                        <td class="addfavtbl_left">Browse by:</td>
                        <td class="addfavtbl_middle">

                            <input id="nationalityid" type="radio" checked="checked" value="nationality" name="playerradio"/>
                            <label for="nationality">Nationality</label>
                            <br/><br/>
                            <input id="clubteamid" type="radio" value="clubteam" name="playerradio"/>
                            <label for="club_team">Club Team</label>
                            <br/>

                        </td>
                        <td class="addfavtbl_ajax"><br><br><img id="ajaxloaderTeamPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"><br><br>
                        									<img  id="ajaxloaderPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"></td>
                        <td class="addfavtbl_right">
                         <select id="countryselectId" name="country_1" >
                            <option value="0" selected>Select Country</option>
                                <?php foreach ($this->countries as $league) { ?>
                            <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
                            <?php } ?>
                         </select>
                         
                         <select id="teamselectid" name="player_1">
                            <option value="0" selected>Select Team</option>
                         </select>
                         
                         <select id="playerselectid" name="player_1">
                            <option value="0" selected>Select Player</option>
                                
                         </select>

                        </td>
                    </tr>
                </table>
            </div>
            <ul class="ButtonWrapper">

                <li>
                    <input type="button" id="acceptModalButtonId" class="submit" value="Ok"/>
                    <input type="button" id="cancelModalButtonId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div>
 
 
 <div id="addTeamModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId">Find Team</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal AddFavoritesModal">
                <table id="addfavtbl_player" class="addfavtbl" cellspacing="0" border="0">
                    <tr>
                        <td class="addfavtbl_left">Browse by:</td>
                        <td class="addfavtbl_middle">

                            <label for="nationality">Country</label>
                            <br/><br/>
                            
                            <label for="club_team">Team</label>
                            <br/>

                        </td>
                        <input type="hidden" id="typeOfTeam" value=""/>
                        <td class="addfavtbl_right"><br><br><img class="closeDiv" id="ajaxloaderTeam" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"> </td>
                        <td class="addfavtbl_right">
                         <select id="countryteamselectId" name="country_1" >
                            <option value="0" selected>Select Country</option>
                                <?php foreach ($this->countries as $league) { ?>
                            <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
                            <?php } ?>
                         </select>
                         <select id="teamselectid2" name="player_1">
                            <option value="0" selected>Select Team</option>
                         </select>
                    
					</td>
                    </tr>
                </table>
            </div>
            <ul class="ButtonWrapper">

                <li>
                    <input type="button" id="acceptTeamModalButtonId" class="submit" value="Ok"/>
                    <input type="button" id="cancelTeamModalButtonId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div>
 
<div id="FormWrapper">
	

       <h3>Favorites</h3>
          <div id="FormWrapperForBottomBackground">
              <form id="favorites" name="teams_form" method="POST" action="<?php echo Zend_Registry::get("contextPath"); ?>/user/savefavorites">
              <input name="teamscounter" id="teamscountid" value="2" type="hidden">
			  <input name="nationalteamscounter" id="natteamscountid" value="2" type="hidden">
			  <input name="playerscounter" id="playerscountid" value="5" type="hidden">
			  <input name="leaguescounter" id="leaguescountid" value="1" type="hidden">

            	<div id="FieldsetWrapper">
            	<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
	            </div>
                    <fieldset id="favoritesTeamsFieldset" class="LabelTop">
                        <div id="teamsMaster">
                           <h5>Favorite Teams</h5>
                           <div class="SelectBoxWrapper">
	                           	<div id="club1" class="favoriteitem">
	                               <label for="fav_team" class="label_fav">Favorite Club Team 1: <a href="javascript:showUpTeamModal('1','club')">find club team</a></label><br />
	                               <input type="text" id="ft1" class="ac_input fav_input" value="Enter club team name"/><br />
	                               <input class="hidden" type="hidden" value="" id="clubteam1" name="clubteam1">
	                            </div> 
	                            <div id="club2" class="favoriteitem">
	                               <label for="fav_team" class="label_fav">Favorite Club Team 2: <a href="javascript:showUpTeamModal('2','club')"">find club team</a></label><br />
	                               <input type="text" id="ft2" class="ac_input fav_input" value="Enter club team name"/>
	                               <input class="hidden" type="hidden" value="" id="clubteam2" name="clubteam2">
	                            </div>   
                               <?php for ($i = 3; $i <= 40; $i++) {?>
                               <div id="club<?php echo $i;?>" class="favoriteitem closeDiv">
	                               <label id="labelc<?php echo $i;?>" for="fav_team" class="label_fav">Favorite Club Team <?php echo $i;?>: <a href="javascript:showUpTeamModal('<?php echo $i;?>','club')">find club team</a></label>
	                               <input type="text" id="ft<?php echo $i;?>" class="ac_input fav_input" value="Enter club team name"/>
	                               <input class="hidden" type="hidden" value="" id="clubteam<?php echo $i;?>" name="clubteam<?php echo $i;?>">
	                               <a id="removeclub<?php echo $i;?>" class="remove" style="" href="javascript:void(0);">Remove</a>
                               </div>
                               <?php } ?>
                               <br/>
                                <div id="clubadderror" class="ErrorMessageIndividual"></div>
                               <label for="add_fav_team"><a href="javascript:void(0);" id="addclubid">Add another club team </a></label>
                           </div>

                             <div class="SelectBoxWrapper">
                               <div id="nclub1" class="favoriteitem">
                                   <label for="fav_nat_team" class="label_fav">Favorite National Team 1: <a href="javascript:showUpTeamModal('1','national')">find national team</a></label><br />
                                   <input type="text" id="fnt1"  class="ac_input fav_input" value="Enter national team name"/><br />
                                   <input class="hidden" type="hidden" value="" id="nationalteam1" name="nationalteam1">
                               </div>
                               <div id="nclub1" class="favoriteitem">
                                   <label for="fav_nat_team" class="label_fav">Favorite National Team 2: <a href="javascript:showUpTeamModal('2','national')">find national team</a></label><br />
                                  <input type="text" id="fnt2"  class="ac_input fav_input" value="Enter national team name"/>
                                  <input class="hidden" type="hidden" value="" id="nationalteam2" name="nationalteam2">
                               </div>
                               <?php for ($i = 3; $i <= 40; $i++) {?>
                              
                               <div id="nclub<?php echo $i;?>" class="favoriteitem closeDiv">
	                               <label id="labelnc<?php echo $i;?>" for="fav_team" class="label_fav">Favorite National Team <?php echo $i;?>: <a href="javascript:showUpTeamModal('<?php echo $i;?>','national')">find national team</a></label>
	                               <input type="text" id="fnt<?php echo $i;?>"  class="ac_input fav_input" value="Enter national team name"/>
	                               <input class="hidden" type="hidden" value="" id="nationalteam<?php echo $i;?>" name="nationalteam<?php echo $i;?>">
                               <a id="removenclub<?php echo $i;?>" class="remove" style="" href="javascript:void(0);">Remove</a>
                               </div>
                               <?php } ?>
                               <br/>
                               <div id="nationalclubadderror" class="ErrorMessageIndividual"></div>
                               <label for="add_fav_nat_team"><a href="javascript:void(0);" id="addnationalid">Add another national team </a></label>
                             </div>
                        </div>

                        <div id="playersMaster">
                           <h5>Favorite Players</h5>
                             <div class="SelectBoxWrapper">
                                 <div id="player1" class="favoriteitem">
                                       <label for="fav_player" class="label_fav">Favorite Player 1: <a href="javascript:showUpPlayerModal('1')">find player</a></label><br />
                                       <input type="text" id="fp1" class="ac_input fav_input" value="Enter player name"/>
                                       <input class="hidden" type="hidden" value="" id="playerId1" name="playerId1">
                                  </div>
                                   <div id="player2" class="favoriteitem">
                                       <label for="fav_player" class="label_fav">Favorite Player 2: <a href="javascript:showUpPlayerModal('2')">find player</a></label><br />
                                       <input type="text" id="fp2" class="ac_input fav_input" value="Enter player name"/>
                                       <input class="hidden" type="hidden" value="" id="playerId2" name="playerId2">
                                   </div>
                                   <div id="player3" class="favoriteitem">
                                       <label for="fav_player" class="label_fav">Favorite Player 3: <a href="javascript:showUpPlayerModal('3')">find player</a></label><br />
                                       <input type="text" id="fp3" class="ac_input fav_input" value="Enter player name"/>
                                       <input class="hidden" type="hidden" value="" id="playerId3" name="playerId3">
                                   </div>
                                   <div id="player4" class="favoriteitem">
                                        <label for="fav_player" class="label_fav">Favorite Player 4: <a href="javascript:showUpPlayerModal('4')">find player</a></label><br />
                                       <input type="text" id="fp4" class="ac_input fav_input" value="Enter player name"/>
                                       <input class="hidden" type="hidden" value="" id="playerId4" name="playerId4">
                                    </div>
                                    <div id="player5" class="favoriteitem">
                                        <label for="fav_player" class="label_fav">Favorite Player 5: <a href="javascript:showUpPlayerModal('5')">find player</a></label><br />
                                       <input type="text" id="fp5" class="ac_input fav_input" value="Enter player name"/>
                                       <input class="hidden" type="hidden" value="" id="playerId5" name="playerId5">
                                    </div>
                               <br />
                               <?php for ($i = 6; $i <= 40; $i++) {?>
                               <div id="player<?php echo $i;?>" class="favoriteitem closeDiv">
	                               <label id="labelp<?php echo $i;?>" for="fav_player" class="label_fav">Favorite Player <?php echo $i;?>: <a href="javascript:showUpPlayerModal('<?php echo $i;?>')">find player</a></label>
	                               <input type="text" id="fp<?php echo $i;?>"  class="ac_input fav_input" value="Enter player name"/>
	                               <input class="hidden" type="hidden" value="" id="playerId<?php echo $i; ?>" name="playerId<?php echo $i;?>">
                               <a id="removeplayer<?php echo $i;?>" class="remove" style="" href="javascript:void(0);">Remove</a>
                               </div>
                               <?php } ?>
                               <div id="playeradderror" class="ErrorMessageIndividual"></div>
                               <label for="add_fav_player" id="addplayerid"><a href="javascript:void(0);">Add another favorite player</a></label>
                             </div>
                        </div>

                       <div id="leaguesMaster">
                            <h5>Favorite Leagues and Tournaments</h5>
                                <div class="SelectBoxWrapper">
                                	<?php for ($i = 1; $i <= 40; $i++) {?>
                                    <div id="leaguecountry<?php echo $i;?>" class="favoriteleagueitem <?php echo($i>1?'closeDiv':'')?>">
	                                    <select id="countryleagueselectid<?php echo $i;?>" name="countryleagueselectid<?php echo $i;?>">
	                                        <option value="0" selected>Select Country or Region</option>
	                                        <?php foreach ($this->regions as $region) { ?>
	                                        <option value="<?php echo $region["region_group_id"];?>"><?php echo $region["region_group_name"];?></option>
	                                        <?php } ?>
                                		    <?php foreach ($this->countries as $league) { ?>
	                                        <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
	                                        <?php } ?>
	                                     </select>
										  <select id="leagueselectid<?php echo $i;?>" name="leagueselectid<?php echo $i;?>">
	                                          <option value="0">Select League or Tournament</option>
	                                     </select>
	                                     <?php if($i > 1){?>
	                                     	<a id="removeleague<?php echo $i;?>" class="removeleague" style="" href="javascript:void(0);">Remove</a>
	                                     <?php } ?>
                                    </div>
                                  
                                    <?php } ?>
                                     
                                     <div id="leagueadderror" class="ErrorMessageIndividual"></div>
                                     <label for="add_fav_league" id="addleagueid"><a href="javascript:void(0);">Add another favorite league</a></label>
                                </div>
                       </div>

                        <div id="favButtons">
								<!-- input type="button" class="submit GreenGradient" name="Register" value="Previous" onclick="javascript:history.back(1) "/>-->
								<input type="submit" class="submit GreenGradient" name="Continue" value="Continue"/>
								<a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/skiptomyprofile">Skip to My Profile</a>
                         </div>
                   </fieldset>
          




				</div><!-- end of FieldSetWrapper -->

          </form>
      </div> <!--end FormWrapperForBottomBackground -->
  </div><!--end FormWrapper -->

            
            
            




