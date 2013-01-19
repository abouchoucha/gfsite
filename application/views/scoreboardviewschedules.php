 <?php $config = Zend_Registry::get ( 'config' );
    $offset = $config->time->offset->daylight; ?>

<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 
<?php

    $contcountry = 1;
    $temp1 = 'league';
    $temp2 = 'date';

    foreach($this->nrmatches as $nrmatch){
?>
		  <div class="DropShadowHeader BrownGradientForDropShadowHeader">

                <div class="DownArrow" onclick="toggleCompetition('<?php echo str_replace(" ","",$this->escape($nrmatch["cname"]));?>',this);"></div>       
                <h4 style="background-image: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $this->escape($nrmatch["country"]);?>.png);" class="WithArrowToLeft WithCountryFlag">
                     <a href="<?php if($nrmatch["country"] > 7)
                    	 { 
                    	 	echo $urlGen->getShowDomesticCompetitionsByCountryUrl($nrmatch["cname"],$nrmatch["country"], true); 
                    	 } else { 
                    	 	switch ($nrmatch["country"]) {
							    case 1:
							        echo $urlGen->getShowRegionUrl(strval('International'), True);
							        break;
							    case 2:
							        echo $urlGen->getShowRegionUrl(strval('asian'), True);
							        break;
							    case 3:
							        echo $urlGen->getShowRegionUrl(strval('african'), True);
							        break;
							    case 5:
							        echo $urlGen->getShowRegionUrl(strval('americas'), True);
							        break;
							    case 7:
							        echo $urlGen->getShowRegionUrl(strval('european'), True);
							        break;    
							}
                    	 		
                    	 } ?>">        
                        <?php echo $this->escape($nrmatch["cname"]);?>      
                    </a>        
                </h4>
        
                <span>       
                (<?php echo $this->escape($nrmatch["matchescount"]);?><?php echo $this->escape($nrmatch["matchescount"])>1?" matches)":" match)";?>     
                </span>

          </div>

          <div class="Scores" id="<?php echo str_replace(" ","",$this->escape($nrmatch["cname"]));?>">
				<?php if ($nrmatch["matchescount"] > 0 ){ ?>
						
				<?php  foreach($this->matches as $match) {

                        	if(trim($match["country"]) == trim($nrmatch["country"])) { 

                  ?> 
				
								<?php if($this->selected == 'tomorrow' or $this->selected == '3' or $this->selected == 'week'){ 
		                                 if(trim($match["league"]) == 8 or trim($match["league"]) == 43 or trim($match["league"]) == 45 or trim($match["league"]) == 70 ){ ?>
		                        
		                                   <?php   
		                                        echo "Schedule information is not available due to restrictions by the league"; 
		                                        break; 
		                                    ?> 
		                        <?php 
		                                  }
		                        }   ?> 
						
								
							  <?php	if(trim($temp2) != trim($match["country"]) . trim($match["mdate"])){						
		                        echo "<p style='margin-left:0;margin-top:0px;' class='day'>" . date ('l - F j , Y' , strtotime($match['mdate'])) . "</p>";?>
		                      <?php } ?>
						
							    <p></p>   
							                                     
		        			  <?php if(trim($temp1) != trim($match["league"]) . trim($match["country"]). trim($match["mdate"])){ ?>
		          				<a class="scoreLeagueTitle" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["league"], True); ?>">
		          				<strong><?php echo $this->escape($match["competition_name"]);?></strong></a>
		       				  <?php } ?> 

							  <ul id="GameDetails">
							  
								<li class="TeamName">
                            		<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                              			<?php echo $this->escape($match["teama"]) ;?>
                            		</a>
                         		</li>
				
								<li class="Score">
		                            <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
		                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
		                                  <?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>
		                              </a>
		                            <?php }else {  ?>
		                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
		                            <?php  }  ?>
		                        </li>
		                        
		                        <li class="TeamName">
                          			<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                            			<?php echo $this->escape($match["teamb"]) ;?>
                          			</a>
                       		   </li>
                       		   
                       		   <li class="MatchDetails">
		                          <?php if($match["status"] == 'Played'){ ?>
		                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
		                             <?php }else if($match["status"] == 'Playing'){  ?>
		                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing <?php //echo "(" . $match["match_minute"] ."')";?></a>
		                            <?php }else if($match["status"] == 'Suspended'){  ?>
		                            Suspended
		                            <?php } else if($match["status"] == 'Fixture'){  ?>
		                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
		                            <span id="matchHour_<?php echo $match["matchid"] ?>" >
		                               <?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>
		                             </span>
		                            </a>
		                          <?php  } else if($match["status"] == 'Postponed'){ ?>
		                            Postponed
		                          <?php } ?>
                       			</li>
							 </ul>
				<p></p>  
				   <?php  } 

                    		$temp1 = $match["league"] . $match["country"] . $match["mdate"];
                    		$temp2 = $match["country"] . $match["mdate"];

 			          } ?>
				
				<?php } else {?>
					 <div class="matchesdata">
  			           No matches played. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/<?php echo $nrmatch["region_group_seoname"];?>/schedulesTab">Click here to see scheduled games</a>.
  			        </div>   
				<?php } ?>
		  </div>
<?php 

	$contcountry++;

     }  ?>
     
     
   