<?php require_once 'scripts\seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 				
                            
                                <div id="ViewScores"><a href="javascript:openAll();">Open All</a>|<a href="javascript:closeAll();">Close All</a><a class="OrangeLink" href="">View Calendar</a>
                                </div>
                                
                             <?php
			                      $contcountry = 1;
				                    if($this->matches != null ) { 
				                      $temp1 = 'league';
				                      $temp2 = 'date';
				                     
                            foreach($this->nrmatches as $nrmatch) :
			                       if($contcountry <= 20){
			                       ?>
                                <div class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <div class="DownArrow" onclick="toggleCompetition('<?php echo $this->escape($nrmatch["cname"]);?>',this);"></div>
                                    <h4 style="background-image: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $this->escape($nrmatch["country"]);?>.png);" class="WithArrowToLeft WithCountryFlag">
                                    	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/Competitions/showCountry/id/<?php echo $this->escape($nrmatch["country"]);?>/regionId/<?php echo $this->escape($nrmatch["region"]);?>">
                                    		<?php echo $this->escape($nrmatch["cname"]);?>
										</a>
									</h4>
                                    <span>
                                        (<?php echo $this->escape($nrmatch["matchescount"]);?>
					                               <?php echo $this->escape($nrmatch["matchescount"])>1?"matches)":"match)";?>
                                    </span> 
                                </div>

                              <div class="Scores" id="<?php echo $this->escape($nrmatch["cname"]);?>">
                                  <?php foreach($this->matches as $match) : 
		                                if(trim($match["country"]) == trim($nrmatch["country"])){ ?>
		                     		 
		                     	        <?php	if(trim($temp2) != trim($match["country"]) . trim($match["mdate"])){ 
		                    	          echo "<span style='font-weight: bold;'>" . date ('l - F j , Y' , strtotime($match['mdate'])) . "</span>";?>
                    			       <?php } ?>
                    			
                                  <p></p>                                    
                                  <? if(trim($temp1) != trim($match["league"]) . trim($match["country"]). trim($match["mdate"])){ ?>
				                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/Competitions/showCompetition/compId/<?php echo $match["league"]; ?>"><strong><?php echo $this->escape($match["competition_name"]);?></strong></a></span>
				  					             <?php } ?>                                   
                                  <ul>
                                   <li <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?>
										                   class="TeamName Winner"><a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teama"], True); ?>"><?php echo "<strong>" . $this->escape($match["teama"]) ."</strong>";?></a>
										             <?php }else { ?>
										                class="TeamName "><a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teama"], True); ?>"><?php echo $this->escape($match["teama"]) ;?></a>
										                            <?php  } ?>
										              </li>
										              <li class="Score">
										              <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
				                              <a href=<?php echo Zend_Registry::get("contextPath"); ?>/scoreBoard/showMatchDetail/matchid/<?php echo $match["matchid"];?>><?php echo $this->escape($match["fs_team_a"]);?></a>
				                              -
				                    			    <a href=<?php echo Zend_Registry::get("contextPath"); ?>/scoreBoard/showMatchDetail/matchid/<?php echo $match["matchid"];?>><?php echo $this->escape($match["fs_team_b"]);?></a> 
				                         <?php }else {  ?>
				                      					 vs
				                      	 <?php  }  ?>
										
										            </li>
										            <li <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>
							                            class="TeamName Winner"><a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teamb"], True); ?>"><?php echo "<strong>" . $this->escape($match["teamb"]) ."</strong>";?></a>
							                                <?php }else { ?>
							                            class="TeamName"><a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teamb"], True); ?>"><?php echo $this->escape($match["teamb"]) ;?></a>
							                            <?php } ?> 
							                 </li>
										            <li class="Time GameOn">
										            <?php if($match["status"] == 'Played'){ ?>
			              			         <a href=<?php echo Zend_Registry::get("contextPath"); ?>/scoreBoard/showMatchDetail/matchid/<?php echo $match["matchid"];?>>Final</a>
			              			      <?php }else if($match["status"] == 'Playing'){  ?>
			              					      <a href=<?php echo Zend_Registry::get("contextPath"); ?>/scoreBoard/showMatchDetail/matchid/<?php echo $match["matchid"];?>>Playing</a>
			              					  <?php }else if($match["status"] == 'Suspended'){  ?>
			              					      Suspended
			              					  <?php } else if($match["status"] == 'Fixture'){ 
			                               echo date("H:i",strtotime($match["time"]));
			                           } ?>
			                         </li>
                            </ul>
								                                
                                
                                <?  } //end if for country
                              $temp1 = $match["league"] . $match["country"] . $match["mdate"];   
                              $temp2 = $match["country"] . $match["mdate"];
                              endforeach;?> 
                           	<p></p> 
                           </div>
							<? 
			                   $contcountry++;
			                   }  
			                   endforeach;
						}else{
							echo 'No matches scheduled.';                
						}
							                
			                ?>   
				
                                
                            
