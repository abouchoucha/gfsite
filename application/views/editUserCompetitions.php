<?php   require_once 'scripts/seourlgen.php';
$session = new Zend_Session_Namespace('userSession');
?>

<script>
	jQuery(document).ready(function() {

		
	jQuery('input:checkbox#removeButtonId').click(function(){
			clickUnClickCheckBoxes(this.checked);
		 }); 
		gridListViewDisplay();
		removeUserFavorities('competition');

		jQuery('#addFavCompetitions').click(function(){
			showUpCompetitionModal();
		});

		jQuery("#countryteamselectId").change(function(){
	    	var countryid = jQuery("#countryteamselectId").val();
	    	populateCombo('leagueselectid',countryid ,'league');
	    });

		
	});


	function showUpCompetitionModal(){

		jQuery('#countryteamselectId').val('0');
		jQuery('#leagueselectid').find('option').remove().end().append('<option value="0">Select League or Tournament</option>').val('0');

		jQuery('#addCompetitionModal').jqm({ onHide: closeModal });
	   	jQuery('#addCompetitionModal').jqmShow();

	   	jQuery("#acceptCompetitionModalButtonId").unbind();
	   	jQuery("#acceptCompetitionModalButtonId").click(function(){
	   		var competitionId = jQuery('#leagueselectid').val();
	   		var countryId = jQuery('#countryteamselectId').val();
	   		if(competitionId != 0){
	   			doAddLeague(competitionId +'*'+countryId , jQuery('#leagueselectid option:selected').text() , jQuery('#countryteamselectId option:selected').text());
			}	
	    });
	}

	function doAddLeague(leagueId ,compName , countryName){

		var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/competitions/addfavorite';
      
		jQuery.ajax({
	           type: 'POST',
	           url : url,
	           data : "fromPage=edit&leagueId=" + leagueId ,
	           success: function (text) {
					jQuery('#addCompetitionModal').jqmHide();
					jQuery('#addFavoriteModal').jqm({onHide: closeModal });
					jQuery('#addFavoriteModal').jqmShow();
					jQuery('#modalFavoriteTitleId').html('add competition to favorities');
  					if(text == 'ko'){
	  					jQuery('#modalBodyResponseId').html('Error: Competition '  + compName+' is already in your favorites.');
	  					jQuery('#modalBodyId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#acceptFavoriteModalButtonId').hide();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Accept');
      				}else{
      					jQuery('#modalBodyResponseId').html('Competition '  + compName+' from '+countryName +' has been added to your favorites.');
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


<div id="addCompetitionModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="modalTitleId">Find Competition</h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div class="MessageModal AddFavoritesModal">
                <table id="addfavtbl_player" class="addfavtbl" cellspacing="0" border="0">
                    <tr>
                        <td class="addfavtbl_left">Browse by:</td>
                        <td class="addfavtbl_middle">

                            <label for="nationality">Country or Region</label>
                            <br/><br/>
                            
                            <label for="club_team">League or Tournament</label>
                            <br/>

                        </td>
                        <input type="hidden" id="typeOfTeam" value=""/>
                        <td class="addfavtbl_right"><br><br><img class="closeDiv" id="ajaxloaderCompetition" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"> </td>
                        <td class="addfavtbl_right">
                         <select id="countryteamselectId" name="country_1" >
                            <option value="0" selected>Select Region or Country</option>
                            <?php foreach ($session->regions as $region) { ?>
                               <option value="<?php echo $region["region_group_id"];?>"><?php echo $region["region_group_name"];?></option>
                               <?php } ?>
                             <?php foreach ($session->countries as $league) { ?>
                            <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
                            <?php } ?>
                         </select>
                         <select id="leagueselectid" name="leagueselectid">
                            <option value="0" selected>Select League or Tournament</option>
                         </select>
                    
					</td>
                    </tr>
                </table>
            </div>
            <ul class="ButtonWrapper">

                <li>
                    <input type="button" id="acceptCompetitionModalButtonId" class="submit" value="Ok"/>
                    <input type="button" id="cancelCompetitionModalButtonId"  class="submit jqmClose" value="Cancel"/>
                </li>
            </ul>
        </div>
    </div>
 </div>


<div class="PlayerNav">
    <?php if($session->isMyProfile == 'y') { ?>
        <input id="addFavCompetitions" class="submit" type="button" value="Add Leagues to Favorites" style="display: inline;margin-bottom:10px;
margin-left:20px;
margin-top:20px;"/>
    <?php }?>
</div>

<?php if (count($this->paginator) < 1 ){
	if($session->isMyProfile == 'y'){
    	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite competitions.</div>";
	}else if($session->isMyProfile == 'n'){
		echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite competitions.</div>";
	}
    echo "<br>";
  }else{ ?>

 <ul class="Friendtoolbar" style="margin:10px 0px;">
		<?php if($session->isMyProfile == 'y') { ?>
		<li class="Buttons">
            <input class="chkbx" type="checkbox" id="removeButtonId" value="" style="float: left;"/>
			<input class="submit blue" type="button" value="Remove" id="removeFavoritiesButtonId"/>
		</li>
		<?php }?>
		<li id="listView" class="ListViewOff" />
        <li id="gridView" class="GridViewOn"/>
	
  </ul>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>

	<div id="listDisplayFavorities" class="closeDiv">	
	<?php   $cont = 1;
			if($this->paginator != null){
				$urlGen = new SeoUrlGen();
	            foreach ($this->paginator as $league) { 

	            	$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($league["country_name"],$league["country_id"] , true);
	            	
	            	if($league["regional"] == "1")
					{ 					
						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl($league["competition_name"],$league["competition_id"],true);
					}
					elseif($league["regional"] == "2")
					{ 					
						$url = $urlGen->getShowNationalTeamCompetitionsUrl($league["competition_name"],$league["competition_id"],true);
					}
					elseif($league["regional"] == "0")
					{ 						
						$url = $urlGen->getShowDomesticCompetitionUrl($league["competition_name"],$league["competition_id"],true);
					} 		
            ?> 	

	  <ul class="FavoritePlayers">
	  	<li>
	  		<?php if($session->isMyProfile == 'y') { ?>
            <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $league["competition_id"];?>*<?php echo $league["country_id"];?>" style="float: left;"/>
            <?php } ?>
		   <a href="<?php echo $url;?>" title="<?php echo $league["competition_name"];?>">
		    <?php
                   $config = Zend_Registry::get ( 'config' );
                   $path_comp_logos = $config->path->images->complogos . $league["competition_id"].".gif" ;
              ?>
              <?php if (file_exists($path_comp_logos) && filesize($path_comp_logos) > 0) {  ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/competitionlogos/<?php echo $league["competition_id"];?>.gif">
             <?php } else { ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif"/>
             <?php } ?>
		   </a>
		  </li>
		  <li>
		  	<h3><a href="<?php echo $url;?>" title="<?php echo $league["competition_name"];?>"><?php echo $league["competition_name"];?></a></h3>
	
		   <strong>Country:</strong><a  href="<?php echo $seoCountry;?>" title=""><?php echo $league["country_name"];?></a>
		 <li class="ViewProfile">
		   <a href="<?php echo $url;?>" title="<?php echo $league["competition_name"];?>">
		   View Competition
		   </a>
		       <br>
		       <?php if($session->isMyProfile == 'y') { ?>
	      	  <a href="javascript:removefavoriteIndividual('<?php echo $league["competition_id"];?>','<?php echo $league["competition_name"];?>','<?php echo $league["competition_id"];?>.gif','Competition')" title="remove">
	       		Remove
	       	  </a>
	       	  <?php }?>
	
	 	</li>
	</ul>
	<?php }
			}else {?>
        <label>You don`t have favorite leagues&competitions.</label>
     <?php }?>


</div>
<div id="gridDisplayFavorities">
 		<?php  
                     $userCounter = 0;
                     if($this->paginator != null){
                        foreach ($this->paginator as $league) {

                            $competition_name_seo = $urlGen->getShowTournamentUrl ( $league ["competition_name"], $league ["competition_id"], true );
                        	
                        	if($league["regional"] == "1")
							{
                              
                                $regionName = $this->regionGroupNames[mb_strtolower($league['region_group_name'])]; 
                                $url = $urlGen->getShowRegionUrl(strval($regionName[0]), True);
							}
							elseif($league["regional"] == "2")
							{ 					
                                $regionName = $this->regionGroupNames[mb_strtolower($league['region_group_name'])];
                                $url = $urlGen->getShowRegionUrl(strval($regionName[0]), True);
							}
							elseif($league["regional"] == "0")
							{
                                $seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($league["country_name"],$league["country_id"] , true);
                                $regionName = $this->regionGroupNames[mb_strtolower($league['region_group_name'])];
								$url = $urlGen->getShowRegionUrl(strval($regionName[0]), True);
                               
							} 	
			                $userCounter++;
	                        if($userCounter==1){
	                            ?>
	                            <ul class="LayoutFourPictures">
	                            <?php } ?>
                                    <li>
                                     <?php if($session->isMyProfile == 'y') { ?>
                                        <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $league["competition_id"];?>*<?php echo $league["country_id"];?>" style="float: left;"/>
                                      <?php }?>

                                        <a class="LeagueTitle" title="<?php echo $league["competition_name"];?>" href="<?php echo $competition_name_seo;?>">
                                            <?php echo $league["competition_name"];?>
                                        </a>
                                        <a title="<?php echo $league["competition_name"];?>" href="<?php echo $url;?>">
                                             <?php
                                                     $config = Zend_Registry::get ( 'config' );
                                                     $path_comp_logos = $config->path->images->complogos . $league["competition_id"].".gif" ;
                                                ?>
                                              <?php if (file_exists($path_comp_logos)) {  ?>
                                                      <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/competitionlogos/<?php echo $league["competition_id"];?>.gif">
                                               <?php } else { ?>
                                                      <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif"/>
                                               <?php } ?>
                                        </a>

                                        <?php if ($league["regional"] == "0") { ?>
                                            <strong>Country: </strong>
                                            <a title="<?php echo $league["country_name"];?>" href="<?php echo $seoCountry;?>"><?php echo $league["country_name"];?></a>
                                            <br/>
                                         <?php }  ?>

                                        <strong>Region: </strong>
                                        <a title="<?php echo $league['region_group_name'];?>" href="<?php echo $url; ?>"><?php echo $league['region_group_name'];?></a>
                                       
                                     </li>
   
                          <?php 
                              if($userCounter==4){
                                $userCounter = 0;
                                echo '</ul>';
                              }
                          ?>
				<?php } ?>

    <?php  }else {?>
        <label>You don`t have favorite competitions.</label>
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
