<?php require_once 'Common.php'; ?>
 <?php require_once 'seourlgen.php';
 $urlGen = new SeoUrlGen();?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 <script type="text/JavaScript">
 
 jQuery(document).ready(function() {
 
 	/*jQuery('#searchIdButton').click(function(){
 	 	search('clubs');
	 });*/
 	jQuery(document).keydown(function(event) {
		if (event.keyCode == 13) {
			search('clubs');
		}
	});  
 });
 
 function search(category){
	
	var searchText = jQuery('#search-players').val();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
 	if(category != ''){
 		url = url + "/t/"+category;
    }
   window.location = url;
}
 </script> 
 
<div id="ContentWrapper" class="TwoColumnLayout">

	
	<div class="FirstColumn">
		<?php echo $this->render('include/topleftbanner.php')?>
		<?php
            $session = new Zend_Session_Namespace('userSession');
            if($session->email != null){
        ?>
			<div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
              </div>
      

            <!--Team Directoy Left Menu-->
            <div class="img-shadow">
               <?php echo $this->render('include/navigationteamsdirectory.php');?>
            </div>

		<?php }else { ?>

			<!--Me box Non-authenticated-->
			<div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
             </div>

			 

            <div class="img-shadow">
               <?php echo $this->render('include/navigationteamsdirectory.php');?>
            </div>

          <!--Goalface Join Now-->
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                       <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                    </a>
                    </div>
                </div>
		<?php } ?>
		
		<!--Facebook Like Module-->
     <?php echo $this->render('include/navigationfacebook.php')?>
		

	</div><!--end FirstColumn-->
	<div id="SecondColumnPlayerProfile" class="SecondColumn">
		<h1>Football Team Directory</h1>
        	<div class="img-shadow">
    						<div class="WrapperForDropShadow">
    							<div class="SecondColumnProfile">
    							   	<ul class="FriendSearch">
                        <li class="Search">
                          <form id="searchplayersform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                          	<label>Search Teams:</label>
                            <input id="search-players" type="text" class="text"  name="q"/>
						    <input id="t" type="hidden" value="clubs"  name="t"/>
                            <input type="submit" id="searchIdButton" class="Submit" value="Search"/>
                          </form>
                        </li>
                        <li class="PopularSearchesTeams">
                        		Popular Teams:

                        		<?php $i = 0;  foreach($this->popularTeamsRandom as $data) { if($i<3){  ?>
                                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
                                    <?php echo $data['team_name'];?></a>
                                <?php if ($i != 2){echo ",";} ?>
                              		<?php $i= $i+1;}} ?>
                        </li>
                      </ul><!-- /SearchSelections-->
                      <div id="FriendsWrapper">
					          	    <div class="FriendLinks">
					          		     <span style="font-size:16px;font-weight:bold;">Featured Teams</span>&nbsp;<a href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE) ?>" title="Featured Teams">See More &raquo;</a>
					               </div>
                          
                        

                            <?php
                            // Retrive data from teams as a normal array
                            $teamCounter = 0;
                            foreach ($this->featuredTeamsFour as $data) {
                              $teamCounter++;
                              if($teamCounter==1){
                             ?>
                                <ul class="LayoutFourPicturesBig">
                                  <?php } ?>

                                 <li>
                                      <strong>
                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
                                          <span style="font-size:12px"><?php echo $data['team_name'];?></span>
                                        </a>
                                      </strong>
                                      <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
                                          <?php
                                              $config = Zend_Registry::get ( 'config' );
                                              $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;
                                          if (file_exists($path_team_logos)) { ?>
                                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif"/>

                                            <?php } else {  ?>

                                               <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>

                                          <?php }   ?>
                                      </a>
                                      <strong>Country: </strong>

                                      <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["country_id"], True); ?>" title="<?php echo $data["country_name"]; ?> Domestic Soccer Leagues and Competitions">
                                          <?php echo $data['country_name'];?>
                                      </a>
                                      <br>
                                      <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">View Profile &raquo;</a>
                                      
                                      <!-- Subscribe and Unsubscribe Button -->
					                  <?php if ($session->email != null) { ?>
					    					<?php if($session->userId == $data['user_id']) { ?>
					    					     <a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
					    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
					    						</a>
					    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
					    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
					    						</a>
					    					<?php } else { ?>
					    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
					    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
					    						</a>
					    						<a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
					    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
					    						</a>
		    								<?php } ?>
		    					
					    				<?php } else { ?>
					    				     <a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
					    						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
					    					</a>
					    				<?php } ?>	
                                </li>

                              <?php
                                if($teamCounter==4){
                                  $teamCounter = 0;
                                  echo '</ul>';
                                }
                              ?>
                            <?php } ?>
                            </ul>

                        <br class="clearleft"/>

				                  <div class="FriendLinks">
  					          		     <span style="font-size:16px;font-weight:bold;">Most Popular Teams</span>&nbsp;<a href="<?php echo $urlGen->getPopularTeamsUrl(TRUE) ?>" title="Popular Teams">See More &raquo;</a>
  					          	  </div>

					                <br class="clearleft"/>


                                <?php
                                // Retrive data from teams as a normal array
                                $teamCounter = 0;
                                foreach ($this->popularTeamsTwelve as $data) {
                                  $teamCounter++;
                                  if($teamCounter==1){
                                 ?>
                                    <ul class="LayoutSixPictures">
                                      <?php } ?>

                                      <li>
                                        <strong>
                                      	 <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
                                      		<span style="font-size:9px"><?php echo $data['team_name'];?></span>
                                      	</a>
                                      </strong>
                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
                                           <?php 
                
                                            $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;   
                                            if (file_exists($path_team_logos)) { ?>                    

                                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif"/>

                                            <?php } else {  ?>

                                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif"/>
                                                
                                            <?php } ?>
                                        </a>
                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile"><span style="font-size:9px;">View Profile &raquo;</span></a>
                                      
                                      <!-- Subscribe and Unsubscribe Button -->
										                  <?php if ($session->email != null) { ?>
													    					<?php if($session->userId == $data['user_id']) { ?>
													    					     <a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
													    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
													    						</a>
													    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
													    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
													    						</a>
													    					<?php } else { ?>
													    	
													    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
													    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
													    						</a>
													    						<a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
													    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
													    						</a>
										    								<?php } ?>
										    					
													    				<?php } else { ?>
													    				     <a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
													    						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
													    					</a>
													    				<?php } ?>	

                                   </li>

                                  <?php
                                    if($teamCounter==6){
                                      $teamCounter = 0;
                                      echo '</ul>';
                                    }
                                  ?>
                                <?php } ?>
                              </ul>

                              <br class="clearleft"/> <!-- Previous Class used RegionContentTeamList -->
                                <span style="font-size: 16px; font-weight: bold;">Team Directory</span>

                                  <h4 class="TeamSubTitle">Europe</h4> 
                                  <div id="TeamCountryListTable">
                                    <?php echo $this->partial('scripts/country_three_column.phtml',array('region'=>$this->europe, 'totalCountries'=>$this->totalEurope ,'urlGen'=>$urlGen ));?>
                                  </div>
                                  <h4 class="TeamSubTitle">Americas</h4>
                                  <div id="TeamCountryListTable">
                                    <?php echo $this->partial('scripts/country_three_column.phtml',array('region'=>$this->americas, 'totalCountries'=>$this->totalAmericas ,'urlGen'=>$urlGen ));?>
                                  </div>
                                  <h4 class="TeamSubTitle">Africa</h4>
                                  <div id="TeamCountryListTable">
                                    <?php echo $this->partial('scripts/country_three_column.phtml',array('region'=>$this->africa, 'totalCountries'=>$this->totalAfrica ,'urlGen'=>$urlGen ));?>
                                  </div>
                                   <h4 class="TeamSubTitle">Asia & Pacific Islands</h4>
                                  <div id="TeamCountryListTable">
                                    <?php echo $this->partial('scripts/country_three_column.phtml',array('region'=>$this->asiapacific, 'totalCountries'=>$this->totalAsia ,'urlGen'=>$urlGen ));?>
                                  </div>
      					    </div>
                        </div>
                </div>
            </div>
	</div>    <!--end SecondColumnPlayerProfile-->
	<?php include 'include/teamh2h.php';?>
	<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>
