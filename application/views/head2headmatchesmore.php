<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 

<div class="munch">
	<p class="burg"><?php echo $this->match[0]["t1"];?> vs. <?php echo $this->match[0]["t2"];?> . Head - to - Head Match Ups</p>
	<div class="munch1">
		<p class="mleft">
			<span class="mleft1">		
				<span class="image1">
					<a href="#">
						<?php
							$config = Zend_Registry::get ( 'config' );
							$path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_a"].".gif" ;
							if (file_exists($path_team_logos))
							{  ?>
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_a"]?>.gif"/>
							<?php } else {  ?>
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
						<?php } ?>
					</a>										
				</span>
				<span class="change"><?php echo $this->match[0]["t1"];?> </span>
				<span class="change1"><?php echo $this->competitionName;?> <br/> <?php echo $this->competitionCountry;?> </span>									
			</span>
			<span class="mleft1">		
				<span class="vs">VS</span>									
			</span>
			<span class="mleft1">
				<span class="image1">
					<?php
						$config = Zend_Registry::get ( 'config' );
						$path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_b"].".gif" ;
						if (file_exists($path_team_logos))
						{  ?>
							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_b"]?>.gif"/>
						<?php } else {  ?>
							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
					<?php } ?>
				</span>
				<span class="change"><?php echo $this->match[0]["t2"];?></span>
				<span class="change1"><?php echo $this->competitionName;?> <br/> <?php echo $this->competitionCountry;?> </span>									
			</span>
			<div>
				<p class="mleft">
					<span class="mleft1">
						<span class="team"><a href="#" id="select" align="center">Change Teams</a></span>
					</span>
				</p>
			</div>
		</p>
		
		<div class="win">
			<div class="win1">
				<ul>
					<li class="winleft"><?php echo $this->teamAwins;?></li>
					<li class="winmid">Wins</li>

					<li class="winright"><?php echo $this->teamBwins;?></li>
				</ul>
			</div>
			<div class="win2">
				<ul>
					<li class="winleft"><?php echo $this->teamAlosses;?></li>
					<li class="winmid">Losses</li>

					<li class="winright"><?php echo $this->teamBlosses;?></li>
				</ul>
			</div> 
			<div class="win1">
				<ul>
					<li class="winleft"><?php echo $this->teamties;?></li>
					<li class="winmid">Draws</li>
					<li class="winright"><?php echo $this->teamties;?></li>

				</ul>
			</div>
			<div class="win2">
				<ul>
					<li class="winleft"><?php echo $this->teamAclean;?></li>
					<li class="winmid">Clean Sheets</li>
					<li class="winright"><?php echo $this->teamBclean;?></li>		
				</ul>
			</div>
			<div id="head2headmatches"> 
				<?php if(sizeof($this->paginator) > 0 ) {
					      $temp1 = 'league';
					      $temp2 = 'date';
				?>	                                    
                          	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
                           	<?php    
                           		$i = 1;
        						foreach($this->paginator as $match) { 
						          	if($i % 2 == 1) {
						                $style = "mar";
						          	} else{
						                $style = "feb";
						          	}  	
                           	?>								
									<div class="<?php echo $style;?>">
										<ul>
											<li class="mar1">
												<?php if(trim($temp2) != trim($match["seasonId"]) . trim($match["mdate"])) {
		      				                      	echo date ('M d, Y' , strtotime($match['mdate']));?>
		      				                    <?php } else { ?>&nbsp;<?php } ?>
											</li>
											<li class="mar1"><?php echo $this->escape($match["competition_name"]);?></li>
											<li class="mar2">
												<a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teamaseoname"], True); ?>">
													<?php echo $this->escape($match["teama"]) ;?>
												</a>
												<?php if($match["status"] == 'Played' || $match["status"] == 'Playing'){ ?>
				                                &nbsp;<?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>&nbsp;
				                                <?php } else { ?>
				                                &nbsp; vs.
				                                <?php } ?>
				                                <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teambseoname"], True); ?>">
													<?php echo $this->escape($match["teamb"]);?>
												</a>
											</li>					
											<li class="mar3">
												<?php if($match["status"] == 'Played'){ ?>
		          				                  <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Details</a>
		          				                <?php } else if($match["status"] == 'Playing'){  ?>
		          				      		      <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
			          				                  <?php echo $match["match_minute"];?>'
			          				              </a>
		          				      	        <?php } else if($match["status"] == 'Suspended'){  ?>
		          				      		        Suspended
		          				      	        <?php } else if($match["status"] == 'Fixture'){  ?>
		          				      	  		     <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
		          				                  		<?php echo date("H:i",strtotime($match["time"]));?>
		          				                  	 </a>
		          				                <?php } ?>
											</li>
										</ul>
									</div>													
							 <?php 
								    $temp1 = $match["league"] . $match["seasonId"] . $match["mdate"];   
								    $temp2 = $match["seasonId"] . $match["mdate"];
								    $i++;
								} 
							?> 
							<div class="btm">		
								<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
							</div>
				<?php } else {
					  	echo 'No matches.';
					  }
				?>									
			</div>
		</div>		
	</div>			
</div>