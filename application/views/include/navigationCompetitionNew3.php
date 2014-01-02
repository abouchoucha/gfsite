<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>

<div style="float: left; padding-bottom: 8px;" class="WrapperForDropShadow">
  
    <!--International Cup (European Championships-->
        		  
             <ul>
               <li class="<?php echo($this->menuSelected == 'competition'?'active':''); ?>">
                  <a class="<?php echo($this->submenuSelected != 'none'?'noarrow':''); ?>" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->compName, $this->leagueId, True); ?>">
                        <?php echo $this->compName; ?>                   
                         <?php $score_schedules = 'scores'; ?>
                   </a>
                </li>
                <li>
                	<ul>
                		<?php $knockout = 0; ?>
                		
                		<?php foreach($this->roundList as $rounds) { ?>
		   					
                		    <?php if($rounds['type'] == 'table') { ?><!--group stage round with submenu'--> 
		   					
                		    
                		    <?php
		   						$roundDone = 0;
		   						$todaysdate = date("Y-m-d"); 
										if ($todaysdate < $rounds['start_date'] ) { 
  												$score_schedules = 'schedules';
										} elseif ($todaysdate <= $rounds['end_date'] && $this->todaysdate >= $rounds['start_date'] ) { 
											    $score_schedules = 'scores';
											    
										} elseif ($todaysdate > $rounds['end_date'] ) {
											    $score_schedules = 'scores';
											    $roundDone = 1;
										}
		
		   					  ?>

									<?php if ($roundDone == 1) {?>
									<li>
										<a href="<?php echo $urlGen->getTablesUrl($score_schedules,$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
											<?php echo $rounds["round_title"] ;?>
										</a>
									</li>
									<?php }?>

		                			<?php if($this->roundId == $rounds['round_id']) { ?>
		                				<?php if( $this->submenuSelected == 'tables' || $this->roundId == $rounds['round_id'] ) { ?>
				   							  <li id="groupsstagesubmenu" class="alignLeftMenu">		
				                                <ul>
				                                  	 <li class="<?php echo($this->submenuSelected == 'tables'?'active':''); ?>">
				                                       <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Groups</a>
				                                     </li>
				                                     
				                                     <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
					                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Scores</a>
					                                    </li>
				                                   
				                                    <?php if($roundDone == 0) { ?>
					                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
					                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Schedules</a>
					                                    </li>
				                                    <?php } ?>
				                                    <li class="<?php echo($this->submenuSelected == 'teams'?'active':''); ?>">
				                                      <a href="<?php echo $urlGen->getTablesUrl("teams",$rounds['round_id'] , $this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Teams</a>
				                                    </li>
				                                     <li class="<?php echo($this->submenuSelected == 'players'?'active':''); ?>">
					                                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionplayers/leagueid/72/">Featured Players</a>
					                                 </li>
				                                </ul>
				                             </li>
		                             	<?php  }  ?>
		                             <?php  }  ?>	
		           
		         					 
							<?php } else { ?>
					
								<?php if($rounds['type'] == 'cup') { ?>
									<?php  if($knockout == 0) { ?>

			                    		<li>
			                    			<!--Change the default view from scores to schedules if round start date hasn't started.  --> 
											<?php
			
													$roundDone = 0;
													$todaysdate = date("Y-m-d"); 
													if ($todaysdate < $rounds['start_date'] ) { 
			  												$score_schedules = 'schedules';
													} elseif ($todaysdate <= $rounds['end_date'] && $this->todaysdate >= $rounds['start_date'] ) { 
														    $score_schedules = 'scores';
														    
													} elseif ($this->todaysdate > $rounds['end_date'] ) {
														    $score_schedules = 'scores';
														    $roundDone = 1;
													}
									        //If knockout stages need to be open at the competition page - JV 06192
										    //$this->submenuSelected = $score_schedules;
										    
					                        $urlseason = Zend_Registry::get("contextPath") . '/competitions/showfullscoreboard/seasonid/' . $rounds["season_id"] . '/sm/' . $score_schedules;
					                        $urlround = $urlGen->getTablesUrl($score_schedules,$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True);
					                        $urlseason_scores = Zend_Registry::get("contextPath") . '/competitions/showfullscoreboard/seasonid/' . $rounds["season_id"] . '/sm/scores';
					                        $urlseason_schedules = Zend_Registry::get("contextPath") . '/competitions/showfullscoreboard/seasonid/' . $rounds["season_id"] . '/sm/schedules';
											$urlround_scores = $urlGen->getTablesUrl("scores",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True);
										    $urlround_schedules = $urlGen->getTablesUrl("schedules",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True);
					                       ?>
										    <a href="<?php echo Zend_Registry::get("contextPath"); ?><?php echo($this->knockoutstage == 1 ? $urlround : $urlseason) ?>">	
												Knock Out Stages
											</a>
										</li>
										<!--compare to null when the knockout stage has more than 1 round(8th,4th,semis,final) -->
											<?php if($this->roundId == $rounds['round_id'] || $this->roundId == "") { ?> 
			
												<?php if( $this->submenuSelected == 'scores' || $this->submenuSelected == 'schedules' ) { ?>
													 <li id="knockoutsubmenu" class="alignLeftMenu">
													    <ul>
														    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
				                                        		<a href="<?php echo Zend_Registry::get("contextPath"); ?><?php echo($this->knockoutstage == 1 ? $urlround_scores : $urlseason_scores );?>">
				                                        			Scores
				                                        		</a>
				                                    	  </li>
				                                    	  <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
				                                        	<a href="<?php echo Zend_Registry::get("contextPath"); ?><?php echo($this->knockoutstage == 1 ? $urlround_schedules : $urlseason_schedules );?>">Schedules</a>
				                                    	</li>
				                                 	  </ul>
				                              		</li>  
												<?php } ?>
												
												
											<?php } ?>
											
									<?php $knockout++; }  ?>
	
								<?php } else {  ?>	
									<!-- Rounds type cup1 and table 1  pre main rounds -->
									<?php 
										$roundDone = 0;
										$todaysdate = date("Y-m-d"); 
										
										if ($todaysdate < $rounds['start_date'] ) { 
  												$score_schedules = 'schedules';
										} elseif ($todaysdate <= $rounds['end_date'] && $this->todaysdate >= $rounds['start_date'] ) { 
											    $score_schedules = 'scores';											    
										} elseif ($todaysdate > $rounds['end_date'] ) {
											    $score_schedules = 'scores';
											    $roundDone = 1;
										}
										?>

									<li>
										<a href="<?php echo $urlGen->getTablesUrl($score_schedules,$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
											<?php echo $rounds["round_title"] ;?>
										</a>
									</li>
									<?php if( $this->submenuSelected != 'none' && $this->roundId == $rounds['round_id']) { ?>
										<li id="<?php echo $rounds["round_title"]; ?>_submenu" class="alignLeftMenu">  
										    <ul>
										    	
												    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Scores</a>
				                                    </li>
				                                    
			                              			<?php if($roundDone == 0) { ?>
				                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Schedules</a>
				                                    </li>
			                                  		<?php  } ?>
			                                 </ul>
			                              </li>  
									<?php } ?>
							    <?php }  ?>
							<?php }  ?>
                		<?php } ?>
                	</ul>
                </li>
          </ul>
</div><!--

   		 <div>
            Today :<?php echo $this->todaysdate; ?>
            <BR> Format : <?php echo $this->compFormat; ?>
            <BR> Type : <?php echo $this->compType; ?>
            <BR>roundActive :<?php echo $this->roundId; ?>
            <BR>roundId :<?php echo $this->roundId; ?>
            <BR> Round Type:  <?php echo $this->roundType; ?>
            <BR>submenu :<?php echo $this->submenuSelected;?>
            <BR> # rounds :<?php echo $this->totalrounds;?>
            
            <BR> #knockout :<?php if($knockout != null) { echo $knockout;} ?>
           	</div>     
      


--><script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>


