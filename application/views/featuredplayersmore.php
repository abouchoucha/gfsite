<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $session = new Zend_Session_Namespace('userSession');
 ?>
      
      
<div id="ContentWrapper" class="TwoColumnLayout">
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

                    <?php }else { ?>
            
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
         
                    <?php } ?>
                       
                    <!--Players Directory left Menu-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/navigationplayersdirectory.php');?>
                    </div>
                    <?php  if($session->email == null){  ?>
                    <!--Goalface Join Now-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                        </a>
                        </div>
                    </div>
                <?php  } ?>

            </div><!--end FirstColumnOfTwo-->

            <div id="SecondColumnPlayerProfile" class="SecondColumn">
              <h1>Featured Football Players</h1>
              	<div class="img-shadow">
          			<div class="WrapperForDropShadow">
          				<div class="SecondColumnProfile">
          				   	<ul class="FriendSearch">
                              <li class="Search">
                                <form id="searchplayersform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                                	<label>Search Players</label>
                                	
                                    <input id="search-players" type="text" class="text"  name="q"/>
									<input id="t" type="hidden" value="players" class="hidden"  name="t"/>
                                  <input type="submit" class="Submit" value="Search"/>
                                </form>
                              </li>
                              <li class="PopularSearches searchPlayers">
                              		Popular Players:  
                              		<?php $i = 0; foreach($this->paginator as $pp) { if($i<3){?>
                              			<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_firstname"].' '.$pp["player_lastname"] ?>">
	      					          		<?php echo $pp["player_firstname"].' '.$pp["player_lastname"] ?>
	      				          	  	</a><?php if ($i != 2){echo ",";} ?> 
                              		<?php $i= $i+1;}} ?>
                              </li>
                            </ul><!-- /SearchSelections--> 
                            
                            <div id="FriendsWrapper">
                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
                          
    			  	               <?php 
    			  	               $playerCounter = 0;
    			  	               foreach ($this->paginator as $data) { 
    			  	                  $playerCounter++;
		                            if($playerCounter==1){
	                           ?>
	                             <ul class="LayoutFourPictures" style="float:left;">
                                  <?php } ?>
    			  	                <li>
    			  	              
        		  			             <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
        		  				              <strong><?php echo $data["player_name_short"]?></strong>
        	  				             </a>
        		  		    	         <br/>
        		  		    	        <?php
							                $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
							                if (file_exists($path_player_photos)) { 
										 ?>
            		  			            <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
            		  				            <img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg"/>
                                     		</a>
                                         <?php } else {  ?>
                                          	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
                		  				         <img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>
                                         	</a>
                                         <?php } ?>  						  			             
        		  			             <br/>
        		  			             <strong>Team: </strong><a  href="<?php echo $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_seoname"], true)?>" title="<?php echo $data["team_name"] ?>"><?php echo $data["team_name"] ?></a>
        		  			             <br/>
                      		             <strong>Country: </strong><a  href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["player_country"], True); ?>" title="<?php echo $data["country_name"] ?>"><?php echo $data["country_name"] ?></a>
                      		             <br/>
                      		             <strong>Position: </strong><?php echo $data["player_position"] ?>
                      		             <br/>
                      		             <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
                      		             View Profile &raquo;
                      		             </a>
                      		             
                      		             <?php if ($session->email != null) { ?>
                          					<?php if($session->userId == $data['user_id']) { ?>
                                                  <a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                            			</a>
                                            			<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                            			</a>
                                              <?php } else { ?>
                                              	<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                            			</a>
                                            			<a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                            				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                        		</a>
                                                        <?php }  ?>
                            			 <?php } else { ?>
                            					    <a id="btn_playerid_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                            							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                            						</a>
                            			 <?php }  ?>	
                      		             
                      		             
                            </li>   
            	            	<?php 
                            if($playerCounter==4){
                              $playerCounter = 0;
                              echo '</ul>';
                            }
                          ?>
                        <?php } ?>			            
    		                   </ul>

                              <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
    		                  <br class="clearleft"/> 				                      			                      
      			          	</div>
                        </div><!--end SecondColumnProfile-->	
                      </div><!--end wrapperForDropShadow-->	
                 </div>	<!--end img-shadow-->									                           
            </div><!--end SecondColumnPlayerProfile and SecondColumn-->
                
</div> <!--end ContentWrapper-->
<?php include 'include/playerh2h.php';?>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>