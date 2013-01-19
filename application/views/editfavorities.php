<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>
    <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>


<script type="text/JavaScript">
	jQuery(document).ready(function() {
		showEditFavoritiesTab('<?php echo $this->favType?>');

	});	

	function showEditFavoritiesTab(tab){
    	 
         var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/'+tab;
         <?php if ($this->username != ''){ ?>
         url = url + '/username/<?php echo $this->username?>';
         <?php } ?>	
         url = url + '/page/';
         jQuery('#data').html("<div class='ajaxload widget'></div>");
 		 jQuery('#data').load(url);
         jQuery('.Selected').removeClass('Selected');
		 jQuery('#' + tab).addClass('Selected');

	}

	function clickUnClickCheckBoxes(checked){
		jQuery("input[name='arrayFavorities']").each(		
				function() 
				{ 
					jQuery(this).attr('checked',checked); 
			   	}
		);

	}

	function gridListViewDisplay(){
		jQuery('ul>li#listView').click(function(){
			if(jQuery('#listDisplayFavorities').is(":hidden")){
				 jQuery('#gridDisplayFavorities').hide();
			     jQuery('#listDisplayFavorities').show();
      			 jQuery('ul>li#listView').removeClass('ListViewOff');
          		 jQuery('ul>li#listView').addClass('ListViewOn');
    			 jQuery('ul>li#gridView').removeClass('GridViewOn');
      		     jQuery('ul>li#gridView').addClass('GridViewOff');
			 }
		});
		jQuery('ul>li#gridView').click(function(){
			if(jQuery('#gridDisplayFavorities').is(":hidden")){
				 jQuery('#listDisplayFavorities').hide();
    			 jQuery('#gridDisplayFavorities').show();
      		     jQuery('ul>li#listView').removeClass('ListViewOn');
          	  	 jQuery('ul>li#listView').addClass('ListViewOff');
          	   	 jQuery('ul>li#gridView').removeClass('GridViewOff');
          	   	 jQuery('ul>li#gridView').addClass('GridViewOn');
			}
		});		

	}
	
	function removeUserFavorities(type){

		jQuery('input:button#removeFavoritiesButtonId').click(function(){

			var cont = 0;
			var query_string = '';
			jQuery("input[name='arrayFavorities']").each(			
				function() 
				    { 
				        if(jQuery(this).is(':checked')){
							cont++;
						}	 
				         
				}
			);
			jQuery('#acceptModalButtonId').unbind();
			if(cont == 0){
				jQuery('#modalTitleConfirmationId').html('REMOVE '+type);
				jQuery('#messageConfirmationTextId').html('You do not have any ' +type+ '(s) selected.  Select the '+type+'(s) you want to remove from your favorities');
				jQuery('#acceptModalButtonId').addClass('jqmClose');
				jQuery('#messageConfirmationId').jqm({trigger: '#removePlayersIdButton', onHide: closeModal });
				jQuery('#messageConfirmationId').jqmShow();
				return false;
			}
			jQuery('#modalTitleConfirmationId').html('REMOVE ' +type);
			jQuery('#messageConfirmationTextId').html('Are you sure you want to remove the selected ' +type+ '(s)  from your favorities?');
			jQuery('#messageConfirmationId').jqm({trigger: '#removePlayersIdButton', onHide: closeModal });
			jQuery('#messageConfirmationId').jqmShow();
			jQuery('#acceptModalButtonId').click(function(){
				doRemoveFavorites(type);
			});	
			
			return false;
			
		});

	}

	function doRemoveFavorites(type){

		var query_string = '';
		
		jQuery("input[name='arrayFavorities']").each(			
			function() 
			{ 
				if(jQuery(this).is(':checked')){
					query_string += "&arrayFavorities[]=" + this.value;
				}
			}
		);
		 var url = null;
		 if(type == 'player'){
			url = '<?php echo Zend_Registry::get ( "contextPath" );?>/player/removefavoriteplayers';
		}else if(type == 'team'){
			url = '<?php echo Zend_Registry::get ( "contextPath" );?>/team/removefavoriteteams';
		}else if(type == 'competition'){
			url = '<?php echo Zend_Registry::get ( "contextPath" );?>/competitions/removefavoriteleagues';	
		}else if(type == 'Game'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/removefavoritegames';
		 } 
			 
	     
		 jQuery.ajax({
           type: 'POST',
           url : url,
           data : "id=1" + query_string ,
           success: function (text) {
 					
 					jQuery('#messageConfirmationId').jqmHide();	
 					jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
					jQuery('#addFavoriteModal').jqmShow();
					jQuery('#modalBodyResponseId').html('The selected '+ type+'s have been removed from your favorites.');
					jQuery('#modalFavoriteTitleId').html('remove '+type);
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					jQuery('#data').html(text);
 			}
        });	
	}

	


	function removefavoriteIndividual(id , favoritename , image , type){

		 jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalFavoriteTitleId').html('Remove ' +type);
		 jQuery('#dataText1').html(favoritename);
		 
		 /*jQuery('#dataText1').attr('href','<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true);?>');
		 jQuery('#title1Id').html('Team:');
		 jQuery('#dataText2').html("<?php echo $this->playerteamclub;?>");
		 jQuery('#dataText2').attr('href','<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamclub, True ); ?>');

		 jQuery('#title2Id').html('Nationality:');
		 jQuery('#dataText3').html("<?php if (empty($this->playercountry)){echo 'Unavailable'; } else {echo  $this->playercountry;} ?>");
		 jQuery('#title3Id').html('Position:');
		 jQuery('#dataText4').html("<?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?>");
		 */
		 jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
		 jQuery('#addFavoriteModal').jqmShow();
		 
		 var favoriteImage = null;

		 if (image != '') { 
			 if(type == 'Player'){
		 		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/'+image;
			 }else if(type =='Team' ){
					
			 }	
	  	 } else { 
	  		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text.gif';
	  	 } 	
		 
		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var location = null ;
		 if(type == 'Player'){	
			 location = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/players/page/';
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/removefavorite';
		}else if(type == 'Team'){
			 location = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/teams/page/';
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/removefavorite';
		 }else if(type == 'Competition'){
			 location = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/comp/page/';
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/removefavorite';
		 }else if(type == 'Game'){
			 location = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/editAction/games/page/';
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/removefavorite';
		 }
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  url,
				data : ({id: id}),
				success: function(data){
					jQuery('#modalBodyResponseId').html(type  +' ' + favoritename+' has been removed from your favorites.');
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					jQuery('#data').load(location);
				}	
			})
			
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
			 ajaxload = 'ajaxloaderCompetition';
		 }	 	
		 jQuery('#'+ajaxload).show();
		 jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
			 jQuery('#'+ajaxload).hide();
		 });
	} 
	
 </script>




 <div id="ContentWrapper" class="TwoColumnLayout">
          <div class="FirstColumnOfThree">
                <?php
                   $session = new Zend_Session_Namespace('userSession');
                ?>
                    <!-- START Profile Box Include -->
                	<?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
              		<!-- END Profile Box Include -->	
              
             

                </div><!--/FirstColumn-->
             <!--end FirstColumn-->
             <div class="SecondColumn">
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                            <h4><?php echo $this->screenname;?> Favorites</h4>
                        </div>
                        <div class="SecondColumnBlueBackground">
                                <ul class="TabbedNav" id="main_tabs">
                                    <li id="players"><a href="javascript:showEditFavoritiesTab('players')">Players</a></li>
                                    <li id="teams" class="Selected"><a href="javascript:showEditFavoritiesTab('teams')">Clubs & Teams</a></li>
                                    <li id="comp"><a href="javascript:showEditFavoritiesTab('comp')">Competitions</a></li>
                                    <li id="games"><a href="javascript:showEditFavoritiesTab('games')">Games</a></li>
<!--                                    <li id="profiles"><a href="javascript:showEditFavoritiesTab('profiles')">COMMUNITY</a></li>-->
<!--                                    <li id="other"><a href="javascript:showEditFavoritiesTab('other')">OTHER FAVORITES</a></li>-->

                                </ul>

                                <br class="ClearBoth" />
                                	
									<div id="data" class="TabbedContent">
										<!--Individual tab pages show up here: editplayers.php, editteams.php etc-->
									</div>
                                

                        </div>
                    </div>
                </div>



	 </div><!--//SecondColumn-->
         </div><!--//ContentWrapper-->



