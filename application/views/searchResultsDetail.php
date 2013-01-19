<?php require_once 'seourlgen.php'; ?>
<?php 
	$urlGen = new SeoUrlGen(); 
 	$config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
     $session = new Zend_Session_Namespace('userSession');
?>
                   <!-- <div id="FriendsWrapper">-->
                        <?php 
                     
                        if(count($this->hits)==0) {?>
                        <div id="noSearchResults">
                          
                            <h2>There were <strong>no matches</strong> to your search for: <strong
                                style="color: red;">"<?php echo $this->searchTerm ?>"</strong></h2>
                            <p>Suggestions:</p>
                            <ul>
                                <li>Make sure all words are spelled correctly.</li>
                                <li>Try different keywords.</li>
                                <li>Try more generic keywords.</li>
                            </ul>
                            <div>
                                <ul>
                                    <?php foreach($this->topSearches as $topSearch ) {?>
                                    <li>- <a href="<?php echo $urlGen->getSearchUrl($topSearch["searchTerm"],True) ?>" title="search for <?php echo $topSearch["searchTerm"] ?>"><?php echo $topSearch["searchTerm"] ?></a>
                                            
                                      </li>
                                    <?php }?>
                                </ul>
                            </div>
                        </div>
                        <?php } else {?>
                        
                        <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>
                         
                          <?php foreach ($this->paginator as $hit) { ?>
                            <?php if($hit["type"] === "players"){ ?>
                            <!-- Player Profile -->
                              <ul class="search-item-list">  
                                  <li class="search-item">
                                   <div class="thumbnail">
                                      <?php $link = $urlGen->getPlayerMasterProfileUrl($hit["result"]["player_nickname"], $hit["result"]["player_firstname"], $hit["result"]["player_lastname"], $hit["result"]["player_id"], true ,$hit["result"]["player_common_name"]) ?>
                                      <?php $linkclub = $urlGen->getClubMasterProfileUrl($hit["result"]["team_id"], $hit["result"]["team_seoname"], true)  ?>
                                      <?php $linkcountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($hit["result"]["country_name"], $hit["result"]["country_id"], True); ?>
                                       <a href="<?php echo $link; ?>">
                                           <?php
						                		$path_player_photos = $config->path->images->players . $hit["result"]["player_id"] .".jpg" ;
						                		if (file_exists($path_player_photos)) { 
						          		   ?>
                                              <img id="player<?php echo $hit["result"]["player_id"];?>profileImage" class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $hit["result"]["player_id"]; ?>.jpg" alt="<?php echo $hit["result"]["player_common_name"]; ?>"/>
                                          <?php } else {  ?>
                                               <img id="player<?php echo $hit["result"]["player_id"];?>profileImage" class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text120.gif" alt="<?php echo $hit["result"]["player_common_name"]; ?>"/>
                                          <?php } ?>
                                      </a>
                                      
                                      <?php if ($session->email != null) { ?>
                                  					<?php if($session->userId == $hit["result"]["user_id"]) { ?>
                                              <a id="btn_player_off_<?php echo $hit["result"]["player_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $hit["result"]["player_id"];?>);">
                                        				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["player_common_name"];?>'s updates">
                                        			</a>
                                        			<a id="btn_player_on_<?php echo $hit["result"]["player_id"];?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $hit["result"]["player_id"];?>);">
                                        				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["player_common_name"];?>'s updates">
                                        			</a>
                                          <?php } else { ?>
                                          	<a id="btn_player_on_<?php echo $hit["result"]["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $hit["result"]["player_id"];?>);">
                                        				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["player_common_name"];?>'s updates">
                                        			</a>
                                        			<a id="btn_player_off_<?php echo $hit["result"]["player_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $hit["result"]["player_id"];?>);">
                                        				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["player_common_name"];?>'s updates">
                                        		</a>
                                        <?php }  ?>
                        					<?php } else { ?>
                        					    <a id="btn_playerid_<?php echo $hit["result"]["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $hit["result"]["player_id"];?>);">
                        							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["player_common_name"];?>'s updates">
                        						</a>
                        					<?php }  ?>	
                                      
                                    </div>
                                    <div class="item-info">
                                       
                                            <a style="font-size:14px;font-weight:bold;" href="<?php echo $link ?>"><?php echo $hit["result"]["player_name_short"]  ?></a><br>
                                            <strong>Current Club:</strong> <a href="<?php echo $linkclub ?>"><?php echo $hit["result"]["team_name"] ?></a><br>
                                            <strong>Nationality:</strong> <a href="<?php echo $linkcountry ?>"><?php echo $hit["result"]["country_name"] ?></a><br>
                                            <strong>Position:</strong> <?php echo $hit["result"]["player_position"] ?><br>
                                            <?php $common = new Common ( ); ?>
                                            <strong>Age:</strong> <?php echo $common->GetAge ($hit["result"]["player_dob"]) ?><br>
                                            <strong>Date of Birth:</strong> <?php echo date('F d, Y',strtotime($hit["result"]["player_dob"]))  ?><br>
                                            
                                    </div>
                                    <div class="clearFix"></div>
                                  </li>
                             </ul>

                        <?php } elseif ($hit["type"] == "teams") {?>

                        <!-- Team Profile -->
                        <ul class="search-item-list">  
                              <li class="search-item">
                                <div class="thumbnail">
                                  <?php $link = $urlGen->getClubMasterProfileUrl($hit["result"]["team_id"], $hit["result"]["team_seoname"], true) ?>
                                  <?php $linkcountry = $urlGen->getShowDomesticCompetitionsByCountryUrl($hit["result"]["country_name"], $hit["result"]["country_id"], True); ?>
                                  <a href="<?php echo $link; ?>">
                                     <?php
                                      $path_team_logos = $config->path->images->teamlogos . $hit["result"]["team_id"].".gif" ;
                                      if (file_exists($path_team_logos)) {  ?>
                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $hit["result"]["team_id"]?>.gif" alt="<?php echo $hit["result"]["team_name"] ?>"/>
                                      <?php } else {  ?>
                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif" alt="<?php echo $hit["result"]["team_name"] ?>"/>
                                      <?php }   ?>
                                  </a>
                                  <?php if ($session->email != null) { ?>
                            					<?php if($session->userId == $hit["result"]['user_id']) { ?>
                            					   <a id="btn_team_off_<?php echo $hit["result"]["team_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $hit["result"]["team_id"];?>, '<?php echo $hit["result"]["team_name"];?>');">
                            							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["team_name"];?>'s updates">
                            						</a>
                            						 <a id="btn_team_on_<?php echo $hit["result"]["team_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $hit["result"]["team_id"];?>, '<?php echo $hit["result"]["team_name"];?>');">
                            							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["team_name"];?>'s updates">
                            						</a>
                            					<?php } else { ?>
                            						 <a id="btn_team_on_<?php echo $hit["result"]["team_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $hit["result"]["team_id"];?>, '<?php echo $hit["result"]["team_name"];?>');">
                            							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["team_name"];?>'s updates">
                            						</a>
                            						<a id="btn_team_off_<?php echo $hit["result"]["team_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $hit["result"]["team_id"];?>, '<?php echo $hit["result"]["team_name"];?>');">
                            							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["team_name"];?>'s updates">
                            						</a>
                            					<?php } ?>
                    					
                        			   <?php } else { ?>
                        				     <a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $hit["result"]["team_id"];?>, '<?php echo $hit["result"]["team_name"];?>');">
                        						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["team_name"];?>'s updates">
                        					</a>
                        			   <?php } ?>	
    			   
                                </div>
                                 <div class="item-info">
                                  
                                        <a style="font-size:14px;font-weight:bold;" href="<?php echo $link ?>"><?php echo $hit["result"]["team_name"] ?></a><br>
                                        <strong>Country:</strong> <a href="<?php echo $linkcountry; ?>"><?php echo $hit["result"]["country_name"] ?></a><br>
                                        <!--<strong>League:</strong>-->
                                        <?php  if (!empty($hit["result"]["team_city"])) {echo "<strong>Location:</strong>&nbsp;" . $hit["result"]["team_city"]."<br>"; } ?>
                                        <?php  if (!empty($hit["result"]["team_stadium"])) {echo "<strong>Stadium:</strong>&nbsp;" . $hit["result"]["team_stadium"]."<br>"; } ?>
                                        <?php  if (!empty($hit["result"]["team_additional_info"])) {echo $hit["result"]["team_additional_info"]."<br>"; } ?>
                
                                   
                                </div>
                                <div class="clearFix"></div>
                              </li>
                    				</ul>
                       
                         <?php } elseif ($hit["type"] == "profiles") {?>


                           <!-- User Profile -->
                           <ul class="search-item-list">  
                             <li class="search-item">
                                <div class="thumbnail">
                                  <?php $link = $urlGen->getUserProfilePage($hit["result"]["screen_name"], true) ?>
                                  <a href="<?php echo $link; ?>">
                                     <?php

                                      if ($hit["result"]['main_photo']!=null || $hit["result"]['main_photo']!='') {
                                       ?>
                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo $hit["result"]['main_photo']; ?>" alt="<?php echo $hit["result"]["screen_name"] ?>"/>
                                      <?php } else {  ?>
                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="<?php echo $hit["result"]["screen_name"] ?>"/>
                                      <?php }   ?>
                                  </a>
                                </div>
                                <div class="item-info">
                                    
                                        <a style="font-size:14px;font-weight:bold;" href="<?php echo $link ?>"><?php echo $hit["result"]["screen_name"] ?></a><br>
                                        <?php //$common = new Common ( ); ?>
                                         <strong>Member Since:</strong>&nbsp;<?php echo date('F d, Y',strtotime($hit["result"]["registration_date"]))  ?><br>
                                         <?php  if (!empty($hit["result"]["aboutme_text"])) {echo $hit["result"]["aboutme_text"]."<br>"; } ?>

                                   
                                </div>
                                <div class="clearFix"></div>
                              </li>
                            </ul> 

                        <?php } elseif ($hit["type"] == "leagues") {?>

                        <!-- Competition Profile -->
                 				<ul class="search-item-list">  
                 						<li class="search-item">
                                <div class="thumbnail">
                                  <?php $link = $urlGen->getShowRegionalCompetitionsByRegionUrl($hit["result"]["competition_name"], $hit["result"]["competition_id"], True);?>
                                  <a href="<?php echo $link; ?>">
                                     <?php
                                      $path_comp_logos = $config->path->images->complogos . $hit["result"]['competition_id'].".gif" ;
                                      if (file_exists($path_comp_logos))
                                      {  ?>
                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/competitionlogos/<?php echo $hit["result"]['competition_id']?>.gif" alt="<?php echo $hit["result"]["competition_name"] ?>"/>
                                      <?php } else {  ?>

                                        <img class="search" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif" alt="<?php echo $hit["result"]["competition_name"] ?>"/>
                                      <?php }   ?>
                                  </a>
                                  <?php if ($session->email != null) { ?>
																			<?php if($session->userId == $hit["result"]['user_id']) { ?>
                                    	   <a id="btn_league_off_<?php echo $hit["result"]["competition_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToLeague(<?php echo $hit["result"]["competition_id"];?>, '<?php echo $hit["result"]["competition_name"];?>');">
                                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["competition_name"];?>'s updates">
                                    		</a>
                                    		 <a id="btn_league_on_<?php echo $hit["result"]["competition_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToLeague(<?php echo $hit["result"]["competition_id"];?>, '<?php echo $hit["result"]["competition_name"];?>');">
                                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["competition_name"];?>'s updates">
                                    		</a>
                                    	<?php } else { ?>
                                    		 <a id="btn_league_on_<?php echo $hit["result"]["competition_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToLeague(<?php echo $hit["result"]["competition_id"];?>, '<?php echo $hit["result"]["competition_name"];?>');">
                                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["competition_name"];?>'s updates">
                                    		</a>
                                    		<a id="btn_league_off_<?php echo $hit["result"]["competition_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToLeague(<?php echo $hit["result"]["competition_id"];?>, '<?php echo $hit["result"]["competition_name"];?>');">
                                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["competition_name"];?>'s updates">
                                    		</a>
                                    	<?php } ?>
                        					<?php } else {  ?>
                        							 <a class="subscribe" href="javascript:" onclick="subscribeToLeague(<?php echo $hit["result"]["competition_id"];?>, '<?php echo $hit["result"]["competition_name"];?>');">
                        								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $hit["result"]["competition_name"];?>'s updates">
                        							</a> 
                                  <?php }  ?>
                                </div>
                                <div class="item-info">
                                  
                                        <a style="font-size:14px;font-weight:bold;" href="<?php echo $link ?>"><?php echo $hit["result"]["competition_name"] ?></a><br>
                                        <strong>Country:</strong> <?php echo $hit["result"]["country_name"] ?><br>
                                        <strong>Region:</strong> <?php echo $hit["result"]["region_group_name"] ?><br>
                                    
                                </div>
                                <div class="clearFix"></div>
                              </li>
                          </ul>
                       <?php } ?>
                  
                <?php } ?>
                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>
	<?php } ?>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeleague.js" type="text/javascript"></script>


