<?php 
$config = Zend_Registry::get ( 'config' );
$offset = $config->time->offset->daylight; 
 ?>

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
                    	 		
                    	 }   ?>">        
                        <?php echo $this->escape($nrmatch["cname"]);?>    
                    </a>        
                </h4>
    
                <span>      
                (<?php echo $this->escape($nrmatch["matchescount"]);?><?php echo $this->escape($nrmatch["matchescount"])>1?" matches)":" match)";?>     
                </span>

          </div>
          
          <div class="toggle_container" id="<?php echo str_replace(" ","",$this->escape($nrmatch["cname"]));?>">
				<?php if ($nrmatch["matchescount"] > 0 ){ ?>
					<div class="matchesdata">
					 	 <?php  	

                      foreach($this->matches as $match) {

                        if(trim($match["country"]) == trim($nrmatch["country"])) { 

                  ?> 
                  
      
                 		
                  		<?php	if(trim($temp2) != trim($match["country"]) . trim($match["mdate"])){						
                        echo "<p style='margin-left:0;margin-top:0px;' class='day'>" . date ('l - F j , Y' , strtotime($match['mdate'])) . "</p>";?>
                      <?php } ?>
                      
                      <?php if(trim($temp1) != trim($match["league"]) . trim($match["country"]). trim($match["mdate"])){ ?>          
                         <p class="baltic">
                            <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["league"], True); ?>">
                              <?php echo $this->escape($match["competition_name"]);?>
                            </a>
                          </p>
                      <?php } ?>
                  		
                  		<ul>
                    		<li class="teamhome">
                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                              <?php 
                        			if(strlen($this->escape($match["teama"])) > 15) {
								   		echo substr($this->escape($match["teama"]), 0, 15) . '...';
									} else {
										echo $this->escape($match["teama"]);
									}
                              ?>
                            </a>
                         </li>
                          <li class="score">
                            <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                  <?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>
                              </a>
                            <?php }else {  ?>
                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
                            <?php  }  ?>
                        </li>
                        <li class="teamaway">
                          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                            <?php 
                        			if(strlen($this->escape($match["teamb"])) > 15) {
								   		echo substr($this->escape($match["teamb"]), 0, 15) . '...';
									} else {
										echo $this->escape($match["teamb"]);
									}
                              ?>
                          </a>
                        </li>
                         <li class="final">

                          <?php if($match["status"] == 'Played'){ ?>
                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                            		<strong>Final</strong>
                            </a>
                             <?php }else if($match["status"] == 'Playing'){  ?>
                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                            	<?php echo "<strong>" . $match["match_minute"] ."'</strong>";?>
                            </a>
                            <?php }else if($match["status"] == 'Suspended'){  ?>
                            Suspended
                            <?php } else if($match["status"] == 'Fixture'){  ?>
                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                            <span id="matchHour_<?php echo $match["matchid"] ?>" >
								<?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>
                             </span>
                            </a>
                             <?php //echo $match['mdate'] ?>
                          <?php  } else if($match["status"] == 'Postponed'){ ?>
                            Postponed
                          <?php } ?>
                       </li>
                  		
                  		</ul>

                  		<?php  } 

                    		$temp1 = $match["league"] . $match["country"] . $match["mdate"];
                    		$temp2 = $match["country"] . $match["mdate"];

 			            } ?>

					</div>
				<?php } else {?>
					 <div class="matchesdata">
  			           No matches played. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/live-scores/<?php echo $nrmatch["region_group_seoname"];?>/schedulesTab">Click here to see scheduled games</a>.
  			        </div>   
				<?php } ?>
		  </div>
<?php 

	$contcountry++;

     }  ?>
     
     
   
