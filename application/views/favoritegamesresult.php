<?php require_once 'scripts/seourlgen.php';
	$session = new Zend_Session_Namespace('userSession');
?>

			<?php if (count($this->paginator) < 1){
					if($session->isMyProfile == 'y'){
			    		echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite games.</div>";
					}else if($session->isMyProfile == 'n'){
						echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite games.</div>";
					}
			    echo "<br>";
  				}else {
		    		 foreach ($this->favGames as $match) {
	         	$urlGen = new SeoUrlGen();
	         	$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($match["country_name"],$match["country_id"] , true);?>
			<ul class="MyFavorites">
				
				<li class="Picture">
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
				<li class="Title">
					<a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teama_id"],$match["teamaseoname"], True); ?>" title="<?php echo $match["teama"];?>">
                        <?php echo $match["teama"];?>
                    </a>
                    	<a title="" href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["match_id"], true); ?>"><?php echo $match["fs_team_a"];?> - <?php echo $match["fs_team_b"];?> </a>
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["teamb_id"],$match["teambseoname"], True); ?>" title="<?php echo $match["teamb"];?>">
                        <?php echo $match["teamb"];?>
                    </a>
				
				</li>
				<li class="Title">
	         		<strong>Country :</strong><a href="<?php echo $seoCountry;?>" title="<?php echo $match["country_name"];?>"><?php echo $match["country_name"];?></a><br>
	         		<strong>Competition :</strong><a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["competition_id"], True);?>" title="<?php echo $match["competition_name"];?>"><?php echo $match["competition_name"];?></a><br>
	         		<strong>Date :</strong><?php echo  date ('l - F j , Y' , strtotime($match['match_date']));?></a><br>

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
				
	       	 </ul>
	       	 <?php } ?>
	       	 <div class="LinkWrapper">
	       	 	<?php if($session->isMyProfile == 'y'){ ?>
			    		<a class="OrangeLink" href="/Profile/editfavorities/t/games">See All My Favorite Games &gt;</a>
				<?php	}else if($session->isMyProfile == 'n'){ ?>
						<a class="OrangeLink" href="/Profile/editfavorities/t/games/username/<?php echo $session->currentUser->screen_name;?>">See All <?php echo $session->currentUser->screen_name;?> My Favorite Games &gt;</a>
				<?php	} ?>
					</div>  
				
			<?php } ?>			 