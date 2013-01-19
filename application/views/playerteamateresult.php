<?php 

$session = new Zend_Session_Namespace ( 'userSession' ); 
$config = Zend_Registry::get ( 'config' );

?>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_grid_list.phtml',array('ajaxdata'=>'ajaxdata','type'=>$this->type)); ?>				 
	    					     	
	    					     	<div id="listDisplayFriends">	
	                             	<?php   
	                        			$urlGen = new SeoUrlGen();
	                        	        foreach ($this->paginator as $player) {
	                        	           $player_name_seo = $urlGen->getPlayerMasterProfileUrl($player["player_nickname"], $player["player_firstname"], $player["player_lastname"], $player["player_id"], true ,$player["player_common_name"]);	
	                              ?>
	
	                            	<ul class="FavoritePlayers">
	                            	  	<li>
	                                     <?php
                							$path_player_photos = $config->path->images->players . $player["player_id"] .".jpg" ;
                							if (file_exists($path_player_photos)) { ?>
	                                     
	                                      		<a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
	      											<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $player["player_id"]; ?>.jpg"/>    						  				              
	      	    		  			              </a>
	      	    		  			            <?php } else {  ?>
	      	    		  			              <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
	      											<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>    						  				              
	      	    		  			              </a>
	      	    		  			     <?php }   ?>
	    
	                            		  </li>
	                            		  <li>
	                            		  	<h3><a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>"><?php echo $player["player_name_short"];?></a></h3>
	                            	
	                            		   <strong>Club:</strong><a  href="<?php echo $urlGen->getClubMasterProfileUrl ( $player["team_id"], $player["team_seoname"], True ); ?>" title="<?php echo $player["team_name"];?>"><?php echo $player["team_name"];?></a>
	                            		   <br/>
	                            		   <strong>Country:</strong><a  href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($player["country_name"], $player["player_country"], True); ?>" title="<?php echo $player["country_name"];?>"><?php echo $player["country_name"];?></a>
	                            		   <br/>
	                            		   <strong>Position:</strong><?php echo $player["player_position"];?>
	                            		    <br/>
	                            		   <strong>DOB:</strong><?php echo $player["player_dob"];?>
	                            		    
	                            		 </li>
	                            		 <li class="MoreStats">
	                            		   <strong>Games Played:</strong><?php echo ($player["gamesplayed"]!=null?$player["gamesplayed"]:"n/a");?><br>
	                            		   <strong>Goals Scored:</strong><?php echo ($player["goalscored"]!=null?$player["gamesplayed"]:"n/a");?><br/>
	                            		   <strong>Yellow Cards:</strong><?php echo ($player["yellowcards"]!=null?$player["gamesplayed"]:"n/a");?><br/>
	                            		   <strong>Red Cards:</strong><?php echo ($player["redcards"]!=null?$player["gamesplayed"]:"n/a");?><br/>
	                            		 </li>
	                            		 <li class="ViewProfile">
	                            		   <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">View Profile</a>
	                           				<br>
	                            	      	<?php if($player["ismyplayer"] == 'n'){
	                                      					
									       		if ($session->email == null) { ?>
										       			<input id="addtofavoriteplayerNonLoggedtrigger" onclick="loginModal()" class="submit" type="button" value="Add to favorites" style="display: inline;"/>
								                            
							                  <?php } else { ?>
							                            <input id="addtofavoriteplayerLoggedtrigger" onclick="addfavoriteplayer('<?php echo $player["player_id"];?>','<?php echo $player["player_name_short"];?>','<?php echo $player["imagefilename"]; ?>')" class="submit" type="button" value="Add to favorites" style="display: inline;"/>
							                  <?php  } ?>
													
									       	<?php } else { ?>
									       			<input id="addtofavoriteplayerLoggedtrigger" onclick="javascript:removefavoriteplayer('<?php echo $player["player_id"];?>','<?php echo $player["player_name_short"];?>','<?php echo $player["imagefilename"]; ?>')" class="submit" type="button" value="Remove from favorites" style="display: inline;"/>
							                    </br>
									       	<?php } ?>
	                            	
				                            	 	</li>
				                            	</ul>
				                            
				                            <?php } ?>
	
				                     </div>
	
									 <div id="gridDisplayFriends" class="closeDiv">
	                            		<?php  
	                                      // Retrive data from teams as a normal array
	                                      $userCounter = 0;
	                                      foreach ($this->paginator as $data) {
	                                        $userCounter++;
	                                        if($userCounter==1){
	                                            ?>
	                                            <ul class="LayoutFourPictures">
	                                        <?php } ?>
	                                            
	                                            <li>
	                                        
	                                              <a href="<?php echo $player_name_seo;?>" title="<?php echo $data["player_name_short"];?>">
	                                              	<?php echo $data["player_name_short"];?>
	                                              </a>
	                              				 <br/>
	                              					<!-- <a href="<?php //echo $player_name_seo;?>" title="<?php //echo $player_name_seo;?>"> -->
	    								            			
	    								            <?php
			                							$path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
			                							if (file_exists($path_player_photos)) { ?>
				                                     
				                                      		<a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
				      											<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg"/>    						  				              
				      	    		  			             </a>
				      	    		  			            <?php } else {  ?>
				      	    		  			              <a href="<?php echo $player_name_seo;?>" title="<?php echo $player["player_name_short"];?>">
				      											<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>    						  				              
				      	    		  			              </a>
				      	    		  			     <?php }  ?>
	    							
	    								            			
	                                              <br/>
	                                              <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $data["team_id"], $data["team_seoname"], True ); ?>" title="<?php echo $data["team_name"];?>"><?php echo $data["team_name"];?></a>
	                                              <br/>
	                                              <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["player_country"], True); ?>" title="<?php echo $data["country_name"];?>"><?php echo $data["country_name"];?></a>
	                                              </a><br>
	                                              <?php echo $data["player_position"];?>
	                                            </li>
	                              
	                                          <?php 
	                                          
	                                            if($userCounter==4 ){
	                                                $userCounter = 0;
	                                                echo '</ul>';
	                                            } elseif ($userCounter==$this->totalteammates ) { 
	                                                echo '</ul>'; 
	                                            }
	                                            ?>
	                                         
	                                    <?php } ?>
	                               </div>
								   
								   <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_grid_list.phtml',array('ajaxdata'=>'ajaxdata','type'=>$this->type)); ?>