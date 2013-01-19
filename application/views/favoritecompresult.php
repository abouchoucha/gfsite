<?php require_once 'scripts/seourlgen.php';
	$session = new Zend_Session_Namespace('userSession');
?>

			<?php if (count($this->favLeagues) < 1){
					if($session->isMyProfile == 'y'){
			    		echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite competitions.</div>";
					}else if($session->isMyProfile == 'n'){
						echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite competitions.</div>";
					}
			    echo "<br>";
  				}else {
		    		 foreach ($this->favLeagues as $data) {
	         	$urlGen = new SeoUrlGen();
	         	?>
			<ul class="MyFavorites">
				
				<li class="Picture">
            	<?php
		    		 $url = null;
					if ($data ["regional"] == "1") {
						$url = $urlGen->getShowRegionalCompetitionsByRegionUrl ( $data ["competition_name"], $data ["competition_id"], true );
					} elseif ($data ["regional"] == "2") {
						$url = $urlGen->getShowNationalTeamCompetitionsUrl ( $data ["competition_name"], $data ["competition_id"], true );
					} elseif ($data ["regional"] == "0") {
						$url = $urlGen->getShowDomesticCompetitionUrl ( $data ["competition_name"], $data ["competition_id"], true );
					}
					$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($data ["country_name"],$data ["country_id"], true);
					
					$config = Zend_Registry::get ( 'config' );
	                $path_comp_logos = $config->path->images->complogos . $data["competition_id"].".gif" ;
					
					if (file_exists($path_comp_logos)){
							$compImage = '/competitionlogos/' . $data["competition_id"].".gif";
					}else {
							$compImage = "/LeagueText120.gif";
					}
                    ?>
                  
               <a href="<?php echo $url; ?>">
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?><?php echo $compImage?>">
                 
               </a>
           		 </li>
				<li class="Title">
					<a href="<?php echo $url; ?>" title="">
                        <?php echo $data ['competition_name'];?><br>(<a href="<?php echo $seoCountry;?>"> <?php echo $data ['country_name'] ;?></a>)
                    </a>
                </li>
				
	         	
	       	 </ul>
	       	 <?php } ?>
	       	 <div class="LinkWrapper">
	       	 	<?php if($session->isMyProfile == 'y'){ ?>
			    		<a class="OrangeLink" href="/profile/editfavorities/t/comp">See All My Favorite Competitions &gt;</a>
				<?php	}else if($session->isMyProfile == 'n'){ ?>
						<a class="OrangeLink" href="/profile/editfavorities/t/comp/username/<?php echo $session->currentUser->screen_name;?>">See All <?php echo $session->currentUser->screen_name;?> Favorite Leagues & Competitions &gt;</a>
				<?php	} ?>
					</div>  
				
			<?php } ?>			 