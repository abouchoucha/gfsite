<script type="text/JavaScript">
	var initPlayerASelection = "<?php echo $this->playera[0]["player_id"];?>";
	var initPlayerANameSelection = "<?php echo $this->playera[0]["completename"];?>";
	var initPlayerATeamSelection = "<?php echo $this->playera[0]["team_id"];?>";
	var initPlayerACountrySelection = "<?php echo $this->playera[0]["player_country"];?>";

	var initPlayerBSelection = "<?php echo $this->playerb[0]["player_id"];?>";
	var initPlayerBNameSelection = "<?php echo $this->playerb[0]["completename"];?>";
	var initPlayerBTeamSelection = "<?php echo $this->playerb[0]["team_id"];?>";
	var initPlayerBCountrySelection = "<?php echo $this->playerb[0]["player_country"];?>";
</script>	
	
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>
     
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<?php //require_once 'Common.php'; ?>
            
<div id="ContentWrapper" class="TwoColumnLayout">
      <div class="FirstColumn">
           <?php
                $session = new Zend_Session_Namespace ( 'userSession' );
                if($session->email != null){
            ?>
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <?php include 'include/loginbox.php';?>

                    </div>
                </div>
                
            

                <?php }else { ?>

                <!--Me box Non-authenticated-->
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <?php include 'include/loginNonAuthBox.php';?>
                    </div>
                </div>


                <!--Goalface Register Ad-->

                <?php } ?>
                     <!--Players Directory left Menu-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/navigationplayersdirectory.php');?>
                    </div>

                <!--List of Country competitions-->

             
            </div><!--end FirstColumnOfThree-->

        <div id="SecondColumnHeadtoHead" class="SecondColumnOfTwo" >
			<p class="titlehead">
				<span class="title"><?php echo $this->playera[0]["completename"];?> vs. <?php echo $this->playerb[0]["completename"];?></span>
				<span class="tweeterbutton">
              		<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a>
              	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
          		</span>
                 <span class="fbbutton">
				    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>&layout=button_count&show_faces=false&width=80&action=like&font=verdana&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:22px;padding-bottom:3px;" allowTransparency="true"></iframe>
	        	</span>
			</p>
            <!-- munch -->
                <div class="munch">

                        <div class="munch1">
                                <p class="mleft">
                                        <span class="mleft1">
                                                <span class="image1">
                         										
                										 <?php
						                					$path_player_photos = $config->path->images->players . $this->playera[0]["player_id"] .".jpg" ;
						               						if (file_exists($path_player_photos)) { 
						          						?>		
                                  							<a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playera[0]["player_id"], true, $this->playera[0]["completename"]); ?>">
  																<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $this->playera[0]["player_id"]; ?>.jpg"/>
  	    		  			       							</a>
  	    		  			      						<?php } else {  ?>
  	    		  			        						<a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playera[0]["player_id"], true, $this->playera[0]["completename"]); ?>">
  																<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/> 
  	    		  			         						</a>
  	    		  			       						<?php }   ?>
               
                                                                                                          
                                                </span>
                                                <span class="change">
                                                        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playera[0]["player_id"], true, $this->playera[0]["completename"]); ?>">
                                                                <?php echo $this->playera[0]["completename"];?>
                                                        </a>
                                                </span>
                                                <span class="change1">
                                                 
                                                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playera[0]["player_id"], true, $this->playera[0]["completename"]); ?>">
                                                    	<?php echo $this->playera[0]['player_position']; ?>
                                                    </a>
                                                    <br/> 
                                                    <a  class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->playera[0]["country_name"],$this->playera[0]["country_id"], True); ?>">
                                                    	<?php echo $this->playera[0]['country_name']; ?>
                                                    </a>
                                                    <br/>                                               
                                                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->playera_teamid,$this->playera_teamseoclub, True); ?>" title=" <?php echo $this->playera_teamclub;?>">
                                                        <?php echo $this->playera[0]["team_name"];?>
                                                    </a>
                                                    <br/>  
                                                    <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                    	<?php echo $this->competitionName; ?>
                                                    </a>
                                                </span>
                                                 <span class="team">
													<a id="selectPlayerA" href="#" onclick="changePlayerSelection('left');">Change Player</a>
												</span>
                                        </span>
                                        <span class="mleft1">
                                                <span class="vs">VS</span>
                                                <span class="change">&nbsp;</span>
                                                <span class="change1">&nbsp;<br>&nbsp;</span>
                                               
												<!--  <a href="javascript:window.history.back()">Regresar</a>   -->                                             
                                        </span>
                                        <span class="mleft1">

                                              	<span class="image1">
                                                 	<?php
															$path_player_photos = $config->path->images->players . $this->playera[0]["player_id"] .".jpg" ;
															if (file_exists($path_player_photos)) { 
													?>		
												  				<a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playerb[0]["player_id"], true, $this->playerb[0]["completename"]); ?>">
																	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $this->playerb[0]["player_id"]; ?>.jpg"/>
																</a>
													<?php } else {  ?>
																<a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playerb[0]["player_id"], true, $this->playerb[0]["completename"]); ?>">
																	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/> 
																</a>
													<?php }   ?>
                                                </span>

                                                <span class="change">
                                                        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playerb[0]["player_id"], true, $this->playerb[0]["completename"]); ?>">
                                                                <?php echo $this->playerb[0]["completename"];?>
                                                        </a>
                                                </span>
                                                <span class="change1">
                                               		 <a href="<?php echo $urlGen->getPlayerMasterProfileUrl("", "", "", $this->playerb[0]["player_id"], true, $this->playerb[0]["completename"]); ?>">
                                                    	<?php echo $this->playerb[0]['player_position']; ?>
                                                    </a>
                                                    <br/> 
                                               		
                                               		<a  class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->playerb[0]["country_name"],$this->playerb[0]["country_id"], True); ?>">
                                                    	<?php echo $this->playerb[0]['country_name']; ?>
                                                    </a>
                                                    <br/> 
                                                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->playerb_teamid,$this->playerb_teamseoclub, True); ?>" title="<?php echo $this->playerb_teamclub;?>">
                                                        <?php echo $this->playerb[0]["team_name"];?>
                                                    </a>
                                                    <br/>
                                                    <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                  		<?php echo $this->competitionName; ?>
                                          			</a>
                                               		
                                        		</span>
                                        		 <span class="team">
													<a id="selectPlayerB" href="#" onclick="changePlayerSelection('right');">Change Player</a>
												</span>
                                    </span>
                                </p>

                                <div class="win">
                                		<div class="scores">
                                			<span>Current Season Statistics</span>
                                		</div>
                                        <div class="win1">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->playera_gamesplayed;?></li>
                                                        <li class="winmid">Games</li>

                                                        <li class="winright"><?php echo $this->playerb_gamesplayed;?></li>
                                                </ul>
                                        </div>
                                 
                                        <div class="win2">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->playera_glscored;?></li>
                                                        <li class="winmid">Goals</li>

                                                        <li class="winright"><?php echo $this->playerb_glscored;?></li>
                                                </ul>
                                        </div>
                                       
                                                                    
                                      
                                        <div class="win1">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->playera_yc;?></li>
                                                        <li class="winmid"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg"> Yellow</li>

                                                        <li class="winright"><?php echo $this->playerb_yc;?></li>
                                                </ul>
                                        </div>
                                        <div class="win2">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->playera_rc;?></li>
                                                        <li class="winmid"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg"> Red</li>

                                                        <li class="winright"><?php echo $this->playerb_rc;?></li>
                                                </ul>
                                        </div>                                      
                                </div>
                        </div>
                        <div class="prof2">
	                        <div class="scores"> 
									<ul>
											<li class="name">Season</li>
											<li class="score">GP</li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get('contextPath'); ?>/public/images/score1.jpg"></li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg"></li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg"></li>
									
											<li class="name">Season</li>
											<li class="score">GP</li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get('contextPath'); ?>/public/images/score1.jpg"></li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg"></li>
											<li class="score"><img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg"></li>
									</ul>
									
							</div>
							<?php 
							$rowStyle = 'scores1';
							for($i=0;$i < $this->seasonrowcounter; $i++)
							{
								if($i % 2 == 0)
								{
									$rowStyle = 'scores2';
								}
								else
								{
									$rowStyle = 'scores1';
								}
							
							?>
							<div class="<?php echo $rowStyle?>"> 
									<ul>
											<li class="name"><?php echo (isset($this->seasonstatsa[$i]) && isset($this->seasonstatsa[$i]["season_name"]))?$this->seasonstatsa[$i]["season_name"]:'';?></li>
											<li class="score"><?php echo (isset($this->seasonstatsa[$i]) && isset($this->seasonstatsa[$i]["gp"]))?$this->seasonstatsa[$i]["gp"]:''  ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsa[$i]) && isset($this->seasonstatsa[$i]["gl"]))?$this->seasonstatsa[$i]["gl"]:'' ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsa[$i]) && isset($this->seasonstatsa[$i]["yc"]))?$this->seasonstatsa[$i]["yc"]:''  ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsa[$i]) && isset($this->seasonstatsa[$i]["rc"]))?$this->seasonstatsa[$i]["rc"]:'' ;?></li>
											
											<li class="name"><?php echo (isset($this->seasonstatsb[$i]) && isset($this->seasonstatsb[$i]["season_name"]))?$this->seasonstatsb[$i]["season_name"]:'';?></li>
											<li class="score"><?php echo (isset($this->seasonstatsb[$i]) && isset($this->seasonstatsb[$i]["gp"]))?$this->seasonstatsb[$i]["gp"]:''  ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsb[$i]) && isset($this->seasonstatsb[$i]["gl"]))?$this->seasonstatsb[$i]["gl"]:'' ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsb[$i]) && isset($this->seasonstatsb[$i]["yc"]))?$this->seasonstatsb[$i]["yc"]:''  ;?></li>
											<li class="score"><?php echo (isset($this->seasonstatsb[$i]) && isset($this->seasonstatsb[$i]["rc"]))?$this->seasonstatsb[$i]["rc"]:'' ;?></li>
											
									</ul>
							</div>
							<?php 
							
							}
							
							?>
							
                        	
                        </div>
                </div>
             <!-- munch -->
            <div class="ads">
                &nbsp;
            </div>
        </div><!--end SecondColumnOfTwo -->
 </div> <!--end ContentWrapper-->
 <script>
	 //Preloaded variables for this page
	//The values here will be used to preninitialize the player or team Id and Name (AutoSuggest)
	//You can set server side variables here as shown and they will be use for preinitialization
	
	var playerAInitId=  "<?php echo $this->playera[0]["player_id"];?>";
	var playerAInitName = "<?php echo $this->playera[0]["completename"];?>";
	var playerAInitTeamId="";
	var playerAInitTeamName="";
	var playerAInitCountryId="";

	var playerBInitId=  "<?php echo $this->playerb[0]["player_id"];?>";
	var playerBInitName = "<?php echo $this->playerb[0]["completename"];?>";
	var playerBInitTeamId="";
	var playerBInitTeamName="";
	var playerBInitCountryId="";
	
	
</script>
<?php include 'include/playerh2h.php';?>

