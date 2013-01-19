<?php require_once 'scripts/seourlgen.php';
	$session = new Zend_Session_Namespace('userSession');
?>


<script language="javascript">
	

	jQuery(document).ready(function() {

		jQuery('input:checkbox#removeButtonId').click(function(){
			clickUnClickCheckBoxes(this.checked);
		 });

		jQuery('#addFavPlayers').click(function(){

			jQuery('#countryselectId').val('0');
			jQuery('#teamselectid').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');
			jQuery('#playerselectid').find('option').remove().end().append('<option value="0">Select Player</option>').val('0');
			
			jQuery('#teamselectid').hide();
			jQuery("#nationalityid").attr("checked", "checked");
			
			jQuery('#addPlayerModal').jqm({trigger: '#addFavPlayers', onHide: closeModal });
		   	jQuery('#addPlayerModal').jqmShow();
			jQuery("#acceptFavPlayerModalButtonId").unbind();
		   	jQuery("#acceptFavPlayerModalButtonId").click(function(){
		   		jQuery('#addPlayerModal').jqmHide();
		   	   	var playerid = jQuery('#playerselectid').val();
		   	   	if(playerid != 0){
			   		doAddPlayer(playerid , jQuery('#playerselectid option:selected').text());
		   	   	} 
		    });
		});
		
		//display views
		gridListViewDisplay();
		
		removeUserFavorities('player');


		jQuery("#nationalityid").attr("checked", "checked");   
	    jQuery('#teamselectid').hide();
	    jQuery("input[name='playerradio']").change(function(){
	        if (jQuery("input[name='playerradio']:checked").val() == 'clubteam'){
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
	});

	
	
	function findFavoritePlayerByLetter(letter){

		var url;
		if(letter !='all'){
			url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/players/letter/'+letter;
		}else {
			url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/players';
		}
		<?php if ($this->userId != ''){ ?>
        url = url + '/userid/<?php echo $this->userId?>';
        <?php } ?>	
        url = url + '/page/';
        jQuery('#data').html("<div class='ajaxload widget'></div>");
		jQuery('#data').load(url);
		
	}

	function doAddPlayer(playerId , playerName){

		var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/player/addfavorite';	
		jQuery.ajax({
	           type: 'POST',
	           url : url,
	           data : "fromPage=edit&playerId=" + playerId ,
	           
        		success: function (text) {
						
						jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
						jQuery('#addFavoriteModal').jqmShow();
						jQuery('#modalFavoriteTitleId').html('add player to favorities');
	    				if(text == 'ko'){
	    					jQuery('#modalBodyResponseId').html('Error: Player '  + playerName+' is already in your favorites.');
	    					jQuery('#modalBodyId').hide();
							jQuery('#modalBodyResponseId').show();
							jQuery('#acceptFavoriteModalButtonId').hide();
							jQuery('#cancelFavoriteModalButtonId').attr('value','Accept');
	        			}else{
							jQuery('#modalBodyResponseId').html('Player '  + playerName+' has been added to your favorites.');
							jQuery('#modalBodyId').hide();
							jQuery('#modalBodyResponseId').show();
							jQuery('#acceptFavoriteModalButtonId').hide();
							jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
							jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
			 				jQuery('#data').html(text);
        				}	
	 			}
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
                        <td class="addfavtbl_ajax">
                            <br><br><img id="ajaxloaderTeamPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"><br><br>
                        		<img id="ajaxloaderPlayer" class="closeDiv" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif">
                        </td>
                        <td class="addfavtbl_right">
                         <select id="countryselectId" name="country_1" >
                            <option value="0" selected>Select Country</option>
                                <?php foreach ($session->countries as $league) { ?>
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
                    <input type="button" id="acceptFavPlayerModalButtonId" class="submit" value="Ok"/>
                    <input type="button" id="cancelFavPlayerModalButtonId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div>

<?php 
if($session->isMyProfile == 'y') { ?>

<input id="addFavPlayers" class="submit" type="button" value="Add Players to Favorites" style="display: inline;"/>
<?php }?>
<br><br>
<?php if (count($this->paginator) < 1){
					if($session->isMyProfile == 'y'){
			    	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite players.</div>";
				}else if($session->isMyProfile == 'n'){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite players.</div>";
				}
			    echo "<br>";
  }else{ ?>
<div class="PlayerNav">

		<li class="PaginationAlphabet">
         <?php $urlGen = new SeoUrlGen(); ?>
         <?php if($this->letter != '') {?>
         	<a href="javascript:findFavoritePlayerByLetter('all')">All</a>
         	<?php }else {?>
         		All
         	<?php }?>						
		<?php	for($i = 0 ; $i < 25; $i++) { 	
			if($this->letter != $this->alphabetArray[$i]) {   ?>        
			<a href="javascript:findFavoritePlayerByLetter('<?php echo $this->alphabetArray[$i]; ?>')"><?php echo $this->alphabetArray[$i]; ?></a>
                <?php } else {
	                       echo $this->alphabetArray[$i];
	                 }
				}  
				if($this->letter != 'Z'){
									?>									
				<a href="javascript:findFavoritePlayerByLetter('<?php echo $this->alphabetArray[$i]; ?>')" class="last">Z</a>
			<?php } else {
                           echo 'Z';
                          } 
			
			?>
         </li>

</div>

 <ul class="Friendtoolbar" style="margin:10px 0px;">
		
		<?php if($session->isMyProfile == 'y') { ?>
		<li class="Buttons">
            <input class="chkbx" type="checkbox" id="removeButtonId" value="" style="float: left;"/>
			<input class="submit blue" type="button" value="Remove" id="removeFavoritiesButtonId"/>
		</li>
		<?php } ?>
		<li id="listView" class="ListViewOff" />
        <li id="gridView" class="GridViewOn"/>
  </ul>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>
<br><br>
	<div id="listDisplayFavorities" class="closeDiv">	
	<?php
        $cont = 1;
        $playerObject = new Player();
        $country = new Country ( );
        if($this->paginator != null){
            $urlGen = new SeoUrlGen();
            foreach ($this->paginator as $player) {
                $player_name_seo = $urlGen->getPlayerMasterProfileUrl($player["player_nickname"], $player["player_firstname"], $player["player_lastname"], $player["player_id"], true ,$player["player_common_name"]);
                $rowsetClub = $playerObject->getActualClubTeam ( $player ["player_id"] );
				$countryBirth = $country->fetchRow ( 'country_id=' . $player ["player_country"] );
				$actualClubId = $rowsetClub [0] ["team_id"];
				$actualClubName = $rowsetClub [0] ["team_name"];
				$actualClubSeoName = $rowsetClub [0] ["team_seoname"];
				$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($countryBirth->country_name,$countryBirth->country_id, true);
				$seoTeam = $urlGen->getClubMasterProfileUrl($actualClubId, $actualClubSeoName, True);
        ?>
   
	  <ul class="FavoritePlayers">
	  	<li>
	  	   <?php if($session->isMyProfile == 'y') { ?>
           <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $player["player_id"];?>" style="float: left;"/>
           <?php }?>
		   <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
		     
		   <?php if ($player['imagefilename']!=null || $player['imagefilename']!='') { ?>		
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $player['imagefilename']; ?>" />
           <?php } else { ?>
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text120.gif" />
           <?php } ?>
           </a>                 
		</li>
		<li class="playerinfo">
		  	<h3>
                <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
                    <?php echo $player["player_name_short"];?>
                </a>
            </h3>
            
           <strong>Club:</strong><a  href="<?php echo $seoTeam;?>" title=""><?php echo $actualClubName;?></a>
		   <br/>
		   <strong>Country:</strong><a  href="<?php echo $seoCountry;?>"><?php echo $countryBirth->country_name;?></a>
		   <br/>
		   <strong>Position:</strong><?php echo $player ['player_position'];?>
		    <br/>
		   <strong>DOB:</strong><?php echo date(' F j , Y' , strtotime($player["player_dob"]));?>
		 </li>
		 <li class="MoreStats">
		  <strong>Games Played:</strong><?php echo $player["gamesplayed"];?><br>
          <strong>Goals Scored:</strong><?php echo $player["goalscored"];?><br/>
          <strong>Yellow Cards:</strong><?php echo $player["yellowcards"];?><br/>
          <strong>Red Cards:</strong><?php echo $player["redcards"];?><br/>
		 </li>
		 <li class="ViewProfile">
		   <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">View Profile</a>
            <br>
         <?php if($session->isMyProfile == 'y') { ?>
	      <a href="javascript:removefavoriteIndividual('<?php echo $player["player_id"];?>','<?php echo $player["player_name_short"];?>','<?php echo $player["imagefilename"]; ?>','Player')" title="remove">Remove</a>
	     <?php } ?>  		
	       	  
	 	</li>
	</ul>
	<?php }
			}else {?>
        <label>You don`t have favorite players. Start adding now.<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/addplayer" id="modal_link">Add New Player</a></label>
     <?php }?>
	</div>
	<div id="gridDisplayFavorities">
 		<?php  
                     $userCounter = 0;
                     if($this->paginator != null){
                        foreach ($this->paginator as $player) {
		                $player_name_seo = $urlGen->getPlayerMasterProfileUrl($player["player_nickname"], $player["player_firstname"], $player["player_lastname"], $player["player_id"], true ,$player["player_common_name"]);
		                $rowsetClub = $playerObject->getActualClubTeam ( $player ["player_id"] );
						$countryBirth = $country->fetchRow ( 'country_id=' . $player ["player_country"] );
						$actualClubId = $rowsetClub !=null ? $rowsetClub [0] ["team_id"]:null;
						$actualClubName = $rowsetClub !=null ? $rowsetClub [0] ["team_name"] : "Retired";
						$actualClubSeoName = $rowsetClub [0] ["team_seoname"];
						$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($countryBirth->country_name,$countryBirth->country_id, true);
						$seoTeam = $urlGen->getClubMasterProfileUrl($actualClubId, $actualClubSeoName, True);
		                $userCounter++;
                        if($userCounter==1){
                            ?>
                            <ul class="LayoutFourPictures">
                            <?php } ?>
                            
                                <li>
                                 <?php if($session->isMyProfile == 'y') { ?>
                                  <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $player["player_id"];?>" style="float: left;"/>
                                 <?php }?>
                                 <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
                                    <?php echo $player["player_name_short"];?>
                                </a>
                                    <br/>

                                    <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
                                     <?php if ($player['imagefilename']!=null || $player['imagefilename']!='') { ?>
                                          <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $player['imagefilename']; ?>" />
                                   <?php } else { ?>
                                          <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text120.gif" />
                                   <?php } ?>
                                   </a>
                                    <br/>
									   <?php if($actualClubId !=null){?>
                                       <a href="<?php echo $seoTeam;?>" title=""><?php echo $actualClubName;?></a><br>
										<?php }else{ ?>
											<?php echo $actualClubName;?><br>
										<?php } ?>	
                                    <a  href="<?php echo $seoCountry;?>"><?php echo $countryBirth->country_name;?></a>
                                </li>
                                        
                          <?php 
                              if($userCounter==4){
                                $userCounter = 0;
                                echo '</ul>';
                              }
                          ?>
				<?php } ?>

    <?php  }else {?>
        <label>You don`t have favorite players. Start adding now.<a href="<?php echo Zend_Registry::get("contextPath"); ?>/Player/addPlayer" id="modal_link">Add New Player</a></label>
     <?php }?>

	</div>
	<ul class="Friendtoolbar" style="margin:10px 0px;">
		
		<?php if($session->isMyProfile == 'y') { ?>
		<li class="Buttons">
            <input class="chkbx" type="checkbox" id="removeButtonId" value="" style="float: left;"/>
			<input class="submit blue" type="button" value="Remove" id="removeFavoritiesButtonId"/>
		</li>
		<?php } ?>
		<li id="listView" class="ListViewOff" />
        <li id="gridView" class="GridViewOn"/>
  </ul>
  <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>
  
  
  <?php } ?>
