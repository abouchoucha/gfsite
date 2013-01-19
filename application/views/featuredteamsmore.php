<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script language="javascript">
     jQuery(document).ready(function() {
        <?php if($this->type == 'club'){?> 
        		jQuery('#FeaturedTeamsClubs').show();
        		jQuery('#FeaturedTeamsNational').hide();
        		jQuery("#clubteams").addClass("filterSelected");
                jQuery("#natteams").removeClass("filterSelected");
        <?php	 }else { ?>
        		 jQuery('#FeaturedTeamsClubs').hide();
				 jQuery('#FeaturedTeamsNational').show();
				 jQuery("#clubteams").removeClass("filterSelected");
		         jQuery("#natteams").addClass("filterSelected");
        <?php    } ?>	 
        
        jQuery("#natteams").click(function(e){
        	jQuery('#FeaturedTeamsNational').html("<div class='ajaxload widget'></div>");
        	jQuery('#FeaturedTeamsClubs').hide();
	        jQuery('#FeaturedTeamsNational').show();
	        top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/teams/featured/national';
        });
        
        jQuery("#clubteams").click(function(e){
        	jQuery('#FeaturedTeamsClubs').html("<div class='ajaxload widget'></div>");
            jQuery('#FeaturedTeamsClubs').show();
            jQuery('#FeaturedTeamsNational').hide();
            top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/teams/featured/club';
        });
        
      });	
</script>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; 
$urlGen = new SeoUrlGen();?>
      
      <div id="ContentWrapper" class="TwoColumnLayout">

          <?php // echo $this->type;  (national) ?> 

          <div class="FirstColumn">
  
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
                    
   					
		        <!--Team Directoy Left Menu-->
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
                    

                </div><!--/FirstColumn-->

          <div id="SecondColumnPlayerProfile" class="SecondColumn">
                 <h1>Featured Football Teams</h1>                   
 						<div class="img-shadow">
							<div class="WrapperForDropShadow">
								<div class="SecondColumnProfile">
									<ul class="FriendSearch">
										<li class="Search">
											<form id="searchplayersform" action="/search/" method="get">
											<label>Search Teams</label>
											<input id="search-players" class="text" type="text" name="q"/>
											<input id="t" type="hidden" name="t" value="clubs"/>
											<input class="Submit" type="submit" value="Search"/>
											</form>
										</li>
										<li class="PopularSearches">
											 Popular Teams:
											<a title="Fiorentina" href="/teams/fiorentina_1259/">Fiorentina</a>
											,
											<a title="Croatia" href="/teams/croatia_514/">Croatia</a>
											,
											<a title="Coventry" href="/teams/coventry_692/">Coventry</a>
									  </li>
									</ul>
									<div id="FriendsWrapper">
									
										<div id="TeamSearchCriteria" class="FriendLinks">
      		          		     			Show: <a href="javascript:void(0)" id="clubteams" class="filterSelected">Club Teams</a>&nbsp;|&nbsp;<a href="javascript:void(0)" id="natteams" >National Teams</a>
      		          	    			</div>
      		          	    			<div id="FeaturedTeamsClubs">
      		          	    			<?php if($this->paginatorClub != null){?>
										 <?php echo $this->paginationControl($this->paginatorClub,'Sliding','scripts/my_pagination_control.phtml'); ?>
									   <?php  
			                            // Retrive data from teams as a normal array
			                            $teamCounter = 0;
			                            foreach ($this->paginatorClub as $data) { 
			                              $teamCounter++;
			                              if($teamCounter==1){
			                             ?>
			                                <ul class="LayoutFourPicturesBig" style="float:left;">
			                                  <?php } ?>
			                                  
			                                        <li>
			                                          <h3>
			                                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile"><?php echo $data['team_name'];?></a>
			                                          </h3>
			                                          <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
			                                            <?php
			                                                  $config = Zend_Registry::get ( 'config' );
			                                                  $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;
			
			                                                  if (file_exists($path_team_logos))
			                                                  {  ?>
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
			                                          <span><a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">View Profile &raquo;</a></span>
			                                          
			                                          
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
                                              <?php echo $this->paginationControl($this->paginatorClub,'Sliding','scripts/my_pagination_control.phtml'); ?>
			                              <?php } ?>                                            
			                            </div>    
			                                 
			                             <div id="FeaturedTeamsNational">
			                                 <?php if($this->paginatorNational != null){?>
					                            <?php echo $this->paginationControl($this->paginatorNational,'Sliding','scripts/my_pagination_control.phtml'); ?>	    
			                                    <?php
					                                // Retrive data from teams as a normal array
					                                $teamCounter = 0;
					                                foreach ($this->paginatorNational as $data) {
					                                  $teamCounter++;
					                                  if($teamCounter==1){
					                                 ?>
					                                    <ul class="LayoutFourPicturesBig" style="float:left;">
					                                      <?php } ?>
					
					                                      <li>
					                                      <strong>
					                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile"><?php echo $data['team_name'];?></a></strong>
					                                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">
					                                          <?php
					                                              $config = Zend_Registry::get ( 'config' );
					                                              $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;
					
					                                              if (file_exists($path_team_logos))
					                                              {  ?>
					
					                                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif"/>
					
					                                          <?php } else {  ?>
					
					                                               <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
					
					                                          <?php }   ?>
					                                        </a>
					                                        <strong>Country: </strong><a href="<?php echo $urlGen->getClubsInACountryWithRegion($data["country_name"], '0', $data["region_name"], $data["country_id"], True); ?>" title="<?php echo $data["country_name"]; ?> Domestic Soccer Leagues and Competitions"><?php echo $data['country_name'];?></a>
					                                                      <br>
					                                      <span><a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?> Team Profile">View Profile &raquo;</a></span>
					                                      
					                                      
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
                                                    <?php echo $this->paginationControl($this->paginatorNational,'Sliding','scripts/my_pagination_control.phtml'); ?>	    
					                         	<?php } ?>
			                                 </div>
			                                  
			                              </div>    
			                        	<br class="clearleft"/>
									</div>
								 </div>
							</div>
						
                   
                </div><!--/SecondColumnOfTwo-->        

 </div> <!--end ContentWrapper-->
<?php include 'include/teamh2h.php';?>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>