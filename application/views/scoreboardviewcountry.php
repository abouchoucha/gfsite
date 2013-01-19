
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
            
            <?php
                if($this->matches != null ) { 
                $temp = '999';
		        $temp1 = 'aaa';
		         
                foreach($this->nrmatches as $nrmatch ) : 
            ?>
            
                    <div class="DropShadowHeader BrownGradientForDropShadowHeader">
                          <div class="DownArrow" onclick="toggleCompetition('<?php echo str_replace(" ","",$this->escape($nrmatch["competition_name"]));?>',this);"></div>
                              <h4 class="WithArrowToLeft">
                                <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($nrmatch["competition_name"], $nrmatch["league"], True); ?>"><?php echo $this->escape($nrmatch["competition_name"]);?></a>
                             </h4>
                              <span>(<?php echo $this->escape($nrmatch["matchescount"]);?>
					               <?php echo $this->escape($nrmatch["matchescount"])>1?"matches)":"match)";?>
                              </span> 
                    </div>
                    
                    <div class="toggle_container" id="<?php echo str_replace(" ","",$this->escape($nrmatch["competition_name"]));?>">
                    	<div class="matchesdata">
                    		<?php foreach($this->matches as $match) :
		  						if(trim($match["league"]) == trim($nrmatch["league"])){
		                       		if(trim($temp) != trim($match["league"]) . trim($match["mdate"])){ 
		                    ?>
		                    
		                    	<?php if($this->selectedRestriction == 'tomorrow' or $this->selectedRestriction == '3' or $this->selectedRestriction == 'week'){
		                                if(trim($match["league"]) == 8 or trim($match["league"]) == 43 or trim($match["league"]) == 45 or trim($match["league"]) == 70 ){
		                                	echo "Schedule information is not available due to restrictions by the league";
		                                	break ;
		                                }
		                          }
		                     	?>    
		                    
		                      <p class="day" style="margin-left:0;margin-top:0px;padding-bottom: 10px;"><?php echo date ('l - F j , Y' , strtotime($match['mdate']));?></p>
					          <?php } ?> 
                    		  
                    		  <ul>
                    		  	  <li class="teamhome">
                                    	<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>" title="<?php echo $this->escape($match["teama"]) ;?>">
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
			                      		<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["fs_team_a"]);?>
			                      		- <?php echo $this->escape($match["fs_team_b"]);?></a>
      			                 	<?php }else {  ?>
      			                      <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
      			                 	<?php  }  ?>
			          		      </li>
			          		      <li class="teamaway">

                                    	<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>" title="<?php echo $this->escape($match["teamb"]) ;?>">
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
			                         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
        			                <?php }else if($match["status"] == 'Playing'){  ?>
        			                          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing <?php //echo $match["match_minute"];?></a>
        			                <?php }else if($match["status"] == 'Suspended'){  ?>
        			                          Suspended
        			                <?php } else if($match["status"] == 'Fixture'){  ?>
			                            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
				                          <?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>
			                            </a>
			                       <?php  } ?>
			                     </li>
                    		  </ul>
                    		 <?php 
							   		} //end if for country
                         				$temp = $match["league"] . $match["mdate"];
      					         		$temp1 = $match["league"];
      					      endforeach;
                    		?>
                    	</div>   	        
                    </div>
            
          	  <?php    
                  endforeach;
				    
                }else { 
              ?>    
                <div class="toggle_container">
                    	<div class="matchesdata">
                        	<p class="day" style="margin-left:0;margin-top:0px;padding-bottom: 10px;">No matches scheduled.</p>
                        </div>
                </div> 
             <?php  }  ?> <!-- End of scoreboards-->
                   
            
            
        

  







