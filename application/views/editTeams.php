<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();
$session = new Zend_Session_Namespace('userSession');
?>

<script language="javascript">

	jQuery(document).ready(function() {

		jQuery('input:checkbox#removeButtonId').click(function(){
			clickUnClickCheckBoxes(this.checked);
		 });
		gridListViewDisplay();
		removeUserFavorities('team');

		jQuery('#addFavClubTeams').click(function(){
			showUpTeamModal('club');
		});
		jQuery('#addFavNationalTeams').click(function(){
			showUpTeamModal('national');
		});

		jQuery("#countryteamselectId").change(function(){
	    	var countryid = jQuery("#countryteamselectId").val();
	    	populateCombo('teamselectid2',countryid ,jQuery('#typeOfTeam').val());
	        
	    });
		
		
	});


	function showUpTeamModal(type){

		jQuery('#countryteamselectId').val('0');
		jQuery('#teamselectid2').find('option').remove().end().append('<option value="0">Select Team</option>').val('0');

		jQuery('#addTeamModal').jqm({ onHide: closeModal });
	   	jQuery('#addTeamModal').jqmShow();
		jQuery('#typeOfTeam').val(type);

	   	jQuery("#acceptTeamModalButtonId").unbind();
	   	jQuery("#acceptTeamModalButtonId").click(function(){
	   		jQuery('#addTeamModal').jqmHide();
	   		var teamId = jQuery('#teamselectid2').val();
	   		if(teamId != 0){
	   			doAddTeam(teamId , jQuery('#teamselectid2 option:selected').text());
			}	
	    });
	}
	
	function doAddTeam(teamId , teamName){

		var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/team/addfavorite';
		jQuery.ajax({
	           type: 'POST',
	           url : url,
	           data : "fromPage=edit&teamId=" + teamId ,
	           success: function (text) {
					
					jQuery('#addFavoriteModal').jqm({onHide: closeModal });
					jQuery('#addFavoriteModal').jqmShow();
					jQuery('#modalFavoriteTitleId').html('add team to favorities');
    				if(text == 'ko'){
    					jQuery('#modalBodyResponseId').html('Error: Team '  + teamName+' is already in your favorites.');
    					jQuery('#modalBodyId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#acceptFavoriteModalButtonId').hide();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Accept');
        			}else{
        				jQuery('#modalBodyResponseId').html('Team '  + teamName+' has been added to your favorites.');
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
                        <input type="hidden" id="typeOfTeam" value="" />
                        <td class="addfavtbl_right"><br><br>
                        <img class="closeDiv" id="ajaxloaderTeam" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader3.gif"> </td>
                        <td class="addfavtbl_right">
                         <select id="countryteamselectId" name="country_1" >
                            <option value="0" selected>Select Country</option>
                                <?php foreach ($session->countries as $league) { ?>
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


<?php if($session->isMyProfile == 'y') { ?>
<input id="addFavClubTeams" class="submit" type="button" value="Add Club Teams to Favorites" style="display: inline;margin-left:20px;
margin-top:20px;"/>
<input id="addFavNationalTeams" class="submit" type="button" value="Add Nationals Teams to Favorites" style="display: inline;"/>
<?php } ?>
<br><br>

<?php if (count($this->paginator) < 1){
					if($session->isMyProfile == 'y'){
				    	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite teams.</div>";
					}else if($session->isMyProfile == 'n'){
						echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite teams.</div>";
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
	</li>
</ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>

<br><br>
<div id="listDisplayFavorities" class="closeDiv">
         <?php  foreach ($this->paginator as $unteam) {
         	$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($unteam["country_name"],$unteam["country_id"] , true);?>
        <ul class="FavoritePlayers">
            <li>
            	<?php if($session->isMyProfile == 'y') { ?>
                 <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $unteam["team_id"];?>" style="float: left;"/>
                <?php }?>
               <a href="<?php echo $urlGen->getClubMasterProfileUrl($unteam["team_id"],$unteam["team_seoname"], True); ?>" title="<?php echo $unteam["team_name"];?>">
                  <?php
                      $config = Zend_Registry::get ( 'config' );
                      $path_team_logos = $config->path->images->teamlogos . $unteam['team_id'].".gif" ;
                      $config = Zend_Registry::get ( 'config' );
					  $this->root_crop = $config->path->crop;

                    ?>
                  <?php if (file_exists($path_team_logos) && filesize($path_team_logos) > 0) {  ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $unteam['team_id']?>.gif">
                 <?php } else { ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
                 <?php } ?>
               </a>
            </li>
            <li>
                <h3>
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($unteam["team_id"],$unteam["team_seoname"], True); ?>" title="<?php echo $unteam["team_name"];?>">
                        <?php echo $unteam["team_name"];?>
                    </a>
                </h3>
               <strong>Country:</strong><a href="<?php echo $seoCountry;?>" title="<?php echo $unteam["country_name"];?>"><?php echo $unteam["country_name"];?></a>
                <br/>
               
             </li>
             <li class="ViewProfile">
				<a title="<?php echo $unteam["team_name"];?>" href="<?php echo $urlGen->getClubMasterProfileUrl($unteam["team_id"],$unteam["team_seoname"], True); ?>"> View Team >> </a>
				<br/>
				<?php if($session->isMyProfile == 'y') { ?>
				<a title="remove" href="javascript:removefavoriteIndividual('<?php echo $unteam["team_id"];?>','<?php echo $unteam["team_name"];?>','<?php echo $unteam['team_id']?>.gif','Team')"> Remove </a>
										
				<?php } ?>
			</li>
        </ul>
        <?php }?>

    </div>
	<div id="gridDisplayFavorities">
 		<?php  
                     $userCounter = 0;
                     if($this->paginator != null){
                        foreach ($this->paginator as $unteam) {
                        	$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($unteam["country_name"],$unteam["country_id"] , true);
		                	$userCounter++;
	                        if($userCounter==1){
	                            ?>
	                            <ul class="LayoutFourPictures">
	                            <?php } ?>
	                            
	                        <li>
	                        <?php if($session->isMyProfile == 'y') { ?>
	                        <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $unteam["team_id"];?>" style="float: left;"/>
	                        <?php }?>
	                         <a href="<?php echo $urlGen->getClubMasterProfileUrl($unteam["team_id"],$unteam["team_seoname"], True); ?>" title="<?php echo $unteam["team_name"];?>">
			                    <?php echo $unteam["team_name"];?>
			                </a>
	                            <br/>
	                          
	                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($unteam["team_id"],$unteam["team_seoname"], True); ?>" title="<?php echo $unteam["team_name"];?>">
							     <?php
				                      $config = Zend_Registry::get ( 'config' );
				                      $path_team_logos = $config->path->images->teamlogos . $unteam['team_id'].".gif" ;
				                    ?>
				                  <?php if (file_exists($path_team_logos)) {  ?>
				                    <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $unteam['team_id']?>.gif">
				                 <?php } else { ?>
				                    <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
				                 <?php } ?>
							   </a>
	                            <br/>
	                            	<a href="<?php echo $seoCountry;?>" title="<?php echo $unteam["country_name"];?>"><?php echo $unteam["country_name"];?></a>
	                        </li>
	                                        
	                          <?php 
	                              if($userCounter==4){
	                                $userCounter = 0;
	                                echo '</ul>';
	                              }
	                          ?>
				<?php } ?>

    <?php  }else {?>
        <label>You don`t have favorite teams.</label>
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
