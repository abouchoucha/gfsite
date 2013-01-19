			<?php require_once 'scripts/seourlgen.php';
				$session = new Zend_Session_Namespace('userSession');
			?>
			<?php if (count($this->favTeams) < 1){
					if($session->isMyProfile == 'y'){
			    		echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>You don't have any favorite competitions.</div>";
					}else if($session->isMyProfile == 'n'){
						echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>". $session->currentUser->screen_name  . " does not have any favorite competitions.</div>";
					}
			    echo "<br>";
  				}else {
		    		 foreach ($this->favTeams as $data) {
	         			$urlGen = new SeoUrlGen();
	         		?>
				<ul class="MyFavorites">
				
				<li class="Picture">
            	<?php
		    	$url = $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_seoname"], true);
				$seoCountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($data ["country_name"],$data ["country_id"], true);
				$config = Zend_Registry::get ( 'config' );
                $path_comp_logos = $config->path->images->teamlogos . $data["team_id"].".gif" ;
				
				if (file_exists($path_comp_logos)){
						$teamImage = '/teamlogos/' . $data["team_id"].".gif";
				}else {
						$teamImage = "/TeamText120.gif";
				}
                    ?>
                  
               <a href="<?php echo $url; ?>">
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?><?php echo $teamImage?>">
               </a> 
           		</li>
           		<?php if($this->teamType =='club'){ ?>
                <li class="Title">
					<a href="<?php echo $url; ?>" title="">
                        <?php echo $data ['team_name'];?><br>
                    </a>
                </li> 
                <li class="Title">
					<a href="<?php echo $seoCountry; ?>" title="">
                        <?php echo $data ['country_name'];?><br>
                    </a>
                </li>       
                <?php } else if($this->teamType =='national'){?>
					<li class="Title">
					<a href="<?php echo $url; ?>" title="">
                        <?php echo $data ['team_name'];?>
                    </a>
                    (<?php echo (trim($data["team_soccer_type"])=='default'?'Men':'Women');?>)
                    <br>
                </li>
	         	<?php } ?>
	       	 </ul>
	       	 <?php } ?>
	       	 <div class="LinkWrapper">
	       	 	<?php if($session->isMyProfile == 'y'){ ?>
			    		<a class="OrangeLink" href="/profile/editfavorities/t/teams">See All My Favorite Teams &gt;</a>
				<?php	}else if($session->isMyProfile == 'n'){ ?>
						<a class="OrangeLink" href="/profile/editfavorities/t/teams/username/<?php echo $session->currentUser->screen_name;?>">See All <?php echo $session->currentUser->screen_name;?> My Favorite Teams &gt;</a>
				<?php	} ?>
					</div>  
				
			<?php } ?>			 