<?php   require_once 'scripts/seourlgen.php';
$session = new Zend_Session_Namespace('userSession');
?>
<script>
	jQuery(document).ready(function() {

		jQuery('input:checkbox#removeButtonId').click(function(){
			clickUnClickCheckBoxes(this.checked);
		 }); 
		//gridListViewDisplay();
		removeUserFavorities('Game');

	});

	
	
</script>


<?php if (count($this->paginator) < 1){
			if($session->isMyProfile == 'y'){
		    	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite games.</div>";
			}else if($session->isMyProfile == 'n'){
				echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite games.</div>";
			}
		    echo "<br>";
  } else { ?>

 <ul class="Friendtoolbar" style="margin:10px 0px;">
		<?php if($session->isMyProfile == 'y') { ?>
		<li class="Buttons">
            <input class="chkbx" type="checkbox" id="removeButtonId" value="" style="float: left;"/>
			<input class="submit blue" type="button" value="Remove" id="removeFavoritiesButtonId"/>
		</li>
		<?php }?>
		 <!-- li id="listView" class="OtherView"></li>
		 <li i="gridView" ></li>-->	
  </ul>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>
<br><br>
	<div id="listDisplayFavorities">
         <?php  foreach ($this->paginator as $match) {
         	$urlGen = new SeoUrlGen();
         	$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($match["country_name"],$match["country_id"] , true);?>
        <ul class="FavoritePlayers">
            <li>
            	<?php if($session->isMyProfile == 'y') { ?>
                 <input class="chkbx" type="checkbox" name="arrayFavorities" value="<?php echo $match["match_id"];?>" style="float: left;"/>
                <?php }?>
                
                <?php
                      $config = Zend_Registry::get ( 'config' );
                      $path_team_logos_teama = $config->path->images->teamlogos . $match['teama_id'].".gif" ;
                      $path_team_logos_teamb = $config->path->images->teamlogos . $match['teamb_id'].".gif" ;
                    ?>
                  
               <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teama_id"],$match["teamaseoname"], True); ?>">
                  <?php if (file_exists($path_team_logos_teama)) {  ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $match['teama_id']?>.gif">
                 <?php } else { ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
                 <?php } ?>
               </a>
            </li>
            <li>
                <h3>
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teama_id"],$match["teamaseoname"], True); ?>" title="<?php echo $match["teama"];?>">
                        <?php echo $match["teama"];?>
                    </a>
                    	<?php echo $match["fs_team_a"];?> - <?php echo $match["fs_team_b"];?> 
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teamb_id"],$match["teambseoname"], True); ?>" title="<?php echo $match["teamb"];?>">
                        <?php echo $match["teamb"];?>
                    </a>
                </h3>
               <strong>Country:</strong><a href="<?php echo $seoCountry;?>" title="<?php echo $match["country_name"];?>"><?php echo $match["country_name"];?></a>
                <br/>
               <strong>Competition:</strong><a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["competition_id"], True);?>" title="<?php echo $match["competition_name"];?>"><?php echo $match["competition_name"];?></a>
                <br/>
                <strong>Date:</strong><?php echo  date ('l - F j , Y' , strtotime($match['match_date']));?></a>
                <br/>
             </li>
             <li>
            	<a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teamb_id"],$match["teambseoname"], True); ?>">
                  <?php if (file_exists($path_team_logos_teamb)) {  ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $match['teamb_id']?>.gif">
                 <?php } else { ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
                 <?php } ?>
               </a>
            </li>
             <li class="ViewProfile">
				<a title="" href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["match_id"], true); ?>"> View Match Details >> </a>
				<br/>
				<?php if($session->isMyProfile == 'y') { ?>
				<a title="remove" href="javascript:removefavoriteIndividual('<?php echo $match["match_id"];?>','<?php echo $match["teama"];?> vs.<?php echo $match["teamb"];?>','','Game')"> Remove </a>
										
				<?php } ?>
			</li>
        </ul>
        <?php }?>

    </div>

<ul class="Friendtoolbar" style="margin:10px 0px;">
		
		<?php if($session->isMyProfile == 'y') { ?>
		<li class="Buttons">
            <input class="chkbx" type="checkbox" id="removeButtonId" value="" style="float: left;"/>
			<input class="submit blue" type="button" value="Remove" id="removeFavoritiesButtonId"/>
		</li>
		<?php } ?>
		 <!-- li id="listView" class="OtherView"></li>
		 <li i="gridView" ></li>-->	
  </ul>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml');  ?>


<?php } ?>

