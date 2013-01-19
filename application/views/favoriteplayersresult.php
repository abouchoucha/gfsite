			<?php require_once 'scripts/seourlgen.php';
				$session = new Zend_Session_Namespace('userSession');
			?>
			<?php if (count($this->favPlayers) < 1){
					if($session->isMyProfile == 'y'){
			    		echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite players.</div>";
					}else if($session->isMyProfile == 'n'){
						echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite players.</div>";
					}
			    echo "<br>";
  				}else {
		    		 foreach ($this->favPlayers as $player) {
	         	$urlGen = new SeoUrlGen();
	         	?>
			<ul class="MyFavorites">
				
				<li class="Picture">
            	<?php
                    $playerObject = new Player();
					$country = new Country ( );
            		$url = $urlGen->getPlayerMasterProfileUrl ( $player ["player_nickname"], $player ["player_firstname"], $player ["player_lastname"], $player ["player_id"], true ,$player["player_common_name"]);
					$rowsetClub = $playerObject->getActualClubTeam ( $player ["player_id"] );
					$countryBirth = $country->fetchRow ( 'country_id=' . $player ["player_country"] );
					$actualClubId = $rowsetClub !=null ? $rowsetClub [0] ["team_id"]:null;
					$actualClubName = $rowsetClub !=null ? $rowsetClub [0] ["team_name"] : "Retired";
					$actualClubSeoName = $rowsetClub [0] ["team_seoname"];
					$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($countryBirth->country_name,$countryBirth->country_id, true);
					$seoTeam = $urlGen->getClubMasterProfileUrl($actualClubId, $actualClubSeoName, True);
		    		$playerImage =  "/Player1Text80.gif";
					if($player['imagefilename'] != null or $player['imagefilename']!= ''){
						$playerImage = '/players/' . $player['imagefilename'];
					}
                    ?>
                  
               <a href="<?php echo $url; ?>">
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?><?php echo $playerImage?>">
                 
               </a>
           		 </li>
				<li class="Title">
					<a href="<?php echo $url; ?>" title="">
                        <?php echo $player ['player_name_short'];?>
                    </a>
                </li>
				<li class="Title">
	         		<strong>Team :</strong><a href="<?php echo $seoTeam;?>" title=""><?php echo $actualClubName;?></a><br>
	         		<strong>Country :</strong><a href="<?php echo $seoCountry;?>"><?php echo $countryBirth->country_name;?></a><br>
	         		<strong>Position :</strong><?php echo  $player ['player_position'] ;?></a><br> 

	         	</li>
	         	
	       	 </ul>
	       	 <?php } ?>
	       	 <div class="LinkWrapper">
	       	 	<?php if($session->isMyProfile == 'y'){ ?>
			    		<a class="OrangeLink" href="/profile/editfavorities/t/players">See All My Favorite Players &gt;</a>
				<?php	}else if($session->isMyProfile == 'n'){ ?>
						<a class="OrangeLink" href="/profile/editfavorities/t/players/username/<?php echo $session->currentUser->screen_name;?>">See All <?php echo $session->currentUser->screen_name;?> My Favorite Players &gt;</a>
				<?php	} ?>
					</div>  
				
			<?php } ?>			 