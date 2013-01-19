<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>

 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'data')); ?>

       <?php  
        // Retrive data from teams as a normal array
        $teamCounter = 0;
        foreach ($this->paginator as $data) {
          $teamCounter++;
          if($teamCounter==1){
         ?>
          <ul class="LayoutFourPicturesBig" style="float:left;">
              <?php } ?>              
               <li>
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>"> 
                      <span><?php echo $data['player_name_short'];?></span>
                  </a>
                  
                  <?php
                   $config = Zend_Registry::get ( 'config' );                   
                   $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
                    
                  if (file_exists($path_player_photos)) { ?>                   
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
                        <img id="player<?php echo $data["player_id"];?>profileImage" class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg"/>
                    </a> 
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
              		        <!-- If authenticated check if player is favorite  -->      
              		        <?php if ($session->email != null) { ?>
              		        	<!-- Show sub or unsub if player is already users favorite  --> 
              		        	<?php if($session->userId == $data['user_id']) { ?>
	              		    		<a class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
										<img class="subscribe-img-swap-<?php echo $data["player_id"];?>" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
									</a>
	              		        <?php } else { ?>
				                     <a class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
										<img class="subscribe-img-swap-<?php echo $data["player_id"];?>" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
									</a>
								<?php }  ?>	
							<?php } else { ?>
							    <a class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
									<img class="subscribe-img-swap" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
								</a>
							<?php }  ?>	
              		               
                  <?php } else {  ?>
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
                       <img id="player<?php echo $data["player_id"];?>profileImage" class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>

                    </a> 
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
              		                     		        <!-- If authenticated check if player is favorite  -->      
              		        <?php if ($session->email != null) { ?>
              		        	<!-- Show sub or unsub if player is already users favorite  --> 
              		        	<?php if($session->userId == $data['user_id']) { ?>
	              		    		<a class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
										<img class="subscribe-img-swap-<?php echo $data["player_id"];?>" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
									</a>
	              		        <?php } else { ?>
				                     <a class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
										<img class="subscribe-img-swap-<?php echo $data["player_id"];?>" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
									</a>
								<?php }  ?>	
							<?php } else { ?>
							    <a class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
									<img class="subscribe-img-swap" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
								</a>
							<?php }  ?>	
              		               
                  <?php }   ?>
              </li>
          <?php 
            if($teamCounter==4){
              $teamCounter = 0;
              echo '</ul>';
            }
          ?>
        <?php } ?>
        </ul>

 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'data')); ?>

<br class="clearleft"/>

<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>
