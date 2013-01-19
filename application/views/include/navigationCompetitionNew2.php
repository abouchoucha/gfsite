<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<div style="float: left; padding: 8px 0px;" class="WrapperForDropShadow">

    <?php if ($this->compFormat == 'Domestic league') { ?>
         <ul>
              <li class="<?php echo($this->menuSelected == 'competition'?'active':''); ?>">
                 <a class="<?php echo($this->submenuSelected != 'none'?'noarrow':''); ?>" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->compName, $this->leagueId, True); ?>">
					<?php echo $this->compName; ?> 
				</a>
              </li>
              <li>
                <ul>
                
                <?php $knockout= null; ?>
                  <?php foreach($this->roundList as $rounds) {?>
                  
                	 <?php if( $this->roundId == $rounds['round_id']) { ?>  <!--round selected to display--> 
                	 
		                 	<?php if ($this->totalrounds > 1) { ?>
		            
				               <?php if ($rounds["type"] == 'table') { ?>
				                     	
						                <li id="domesticround_<?php echo $rounds["round_id"]; ?>">
				                            <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">
				                            	<?php echo $rounds["round_title"]; ?> 
				                            </a>
				                        </li>
				                     	
				                     	<!--Sub menu-->
				                      		<li id="menu_<?php echo $rounds["round_id"]; ?>" class="alignLeftMenu">
				                               <ul>
				                                   <li class="<?php echo($this->submenuSelected == 'tables'?'active':''); ?>">
				                                       <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">Tables</a>
				                                    </li>
				                                    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Scoreboard</a>
				                                    </li>
				
				                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName,True) ?>">Schedule</a>
				                                    </li>
				                                    <li class="<?php echo($this->submenuSelected == 'teams'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("teams",$rounds["round_id"] , $this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Teams</a>
				                                    </li>
				                                </ul>
			                              	</li>
				                     	<?php } else { ?>
				                     	
				                			<li id="domesticround_<?php echo $rounds["round_id"]; ?>">
				                            		<a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">
				                            			<?php echo $rounds["round_title"]; ?>
				                            		</a>
				                        	</li>
				                     	

				                     		<li id="menu_<?php echo $rounds["round_id"]; ?>" class="alignLeftMenu">
				                               <ul>
				                                    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Scoreboard</a>
				                                    </li>
				
				                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName,True) ?>">Schedule</a>
				                                    </li>
				                                </ul>
			                              	</li>
				                     	<?php }  ?>
			                    
			                  <?php } else { ?>
			                  
			                  		<?php if ($rounds["type"] == 'table') { ?>
			                      		<li id="menu_<?php echo $rounds["round_id"]; ?>" class="alignLeftMenu">
			                               <ul>
			                                   <li class="<?php echo($this->submenuSelected == 'tables'?'active':''); ?>">
			                                       <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">Tables</a>
			                                    </li>
			                                    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
			                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Scoreboard</a>
			                                    </li>
			
			                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
			                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName,True) ?>">Schedule</a>
			                                    </li>
			                                    <li class="<?php echo($this->submenuSelected == 'teams'?'active':''); ?>">
			                                        <a href="<?php echo $urlGen->getTablesUrl("teams",$rounds["round_id"] , $this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Teams</a>
			                                    </li>
			                                </ul>
		                              	</li>
			                     	<?php } else { ?>
			                     		<li id="menu_<?php echo $rounds["round_id"]; ?>" class="alignLeftMenu">
			                               <ul>
			                                    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
			                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">Scoreboard</a>
			                                    </li>
			
			                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
			                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle ,  $this->compName,True) ?>">Schedule</a>
			                                    </li>
			                                </ul>
		                              	</li>
			                     
			                     	<?php }  ?>
			                  
			                  <?php }  ?>
	                     	
	                   	<?php } else {  ?>
	                   	  	<?php if ($rounds["type"] == 'table') { ?>
		                     	 <li id="domesticround_<?php echo $rounds["round_id"]; ?>">                     	 
		                            <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">
		                            	<?php echo $rounds["round_title"]; ?>
		                           </a>
		                        </li>
		                    <?php } else {  ?>
		                     	 <li id="domesticround_<?php echo $rounds["round_id"]; ?>">                     	 
		                            <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds["round_id"] ,$this->countryName ,$this->seasonTitle , $this->compName ,True) ?>">
		                            	<?php echo $rounds["round_title"]; ?>
		                           </a>
		                        </li>		                    
		                    
 							<?php }  ?>
	                    <?php }  ?>
	                     	
                	<?php } ?> <!--End foreach round--> 

              	</ul>
              </li>
         </ul> 
     
    <!--Domestic Cup (Coppa Italia, Pokal, Copa del Rey-->
    <?php } elseif($this->compFormat == 'Domestic cup') { ?>
    

            <ul>
              	<li class="<?php echo($this->menuSelected == 'competition'?'active':''); ?>">
                  <a class="<?php echo($this->submenuSelected != 'none'?'noarrow':''); ?>" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->compName, $this->leagueId, True); ?>">
                     <?php echo $this->compName; ?>
                  </a>
                </li>
                	 <?php $knockout = null; ?>
						
	                <?php foreach($this->roundList as $rounds) {  ?>
	                			<?php 
										$roundDone = 0;
										$todaysdate = date("Y-m-d"); 
										$score_schedules = 'schedules';
										if ($todaysdate < $rounds['start_date'] ) { 
  												$score_schedules = 'schedules';
										} elseif ($todaysdate <= $rounds['end_date'] && $todaysdate >= $rounds['start_date'] ) { 
											    $score_schedules = 'scores';											    
										} elseif ($todaysdate > $rounds['end_date'] ) {
											    $score_schedules = 'scores';
											    $roundDone = 1;
										}
								?>
	                			
	                			
	                			 <li style="margin-left: 20px;">
									<a href="<?php echo $urlGen->getTablesUrl($score_schedules,$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
										<?php echo $rounds["round_title"]; ?>
									</a>
								  </li>
									<?php if( $this->submenuSelected != 'none' && $this->roundId == $rounds['round_id']) { ?>
										<li id="<?php echo $rounds["round_title"]; ?>_submenu" class="alignLeftMenu">  
										    <ul>
										    	
												    <li style="margin-left:15px;" class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Scores</a>
				                                    </li>
			                              			<?php if($roundDone == 0) { ?>
				                                     <li style="margin-left:15px;" class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
				                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">Schedules</a>
				                                    </li>
			                                  		<?php } ?>
			                                 </ul>
			                              </li>  
									<?php } ?>

	                		<?php }  ?>
	        
                
            </ul>
    
    <!--International Cup (champions league,worldcup,copa libertadores--> 
    <!--$this->compFormat == 'International cup'--> 
    <?php } else { ?>
		
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
										} elseif ($todaysdate <= $rounds['end_date'] && $todaysdate >= $rounds['start_date'] ) { 
											    $score_schedules = 'scores';
											    
										} elseif ($todaysdate > $rounds['end_date'] ) {
											    $score_schedules = 'scores';
											    $roundDone = 1;
										}
		
		   					  ?>
			   					<?php //if( $this->submenuSelected != 'none' && $this->roundId == $rounds['round_id']) { ?>
			   					<?php if( $this->roundId == $rounds['round_id']) { ?>
			   					    <li id="groupstagemenu">
			                			 <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
			                                <?php echo $rounds["round_title"]; ?>
			                             </a>
		                			</li>
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
				                                </ul>
				                             </li>
		                             	<?php  }  ?>
		                             <?php  }  ?>	
		                             	
		                         <?php  } else {  ?>
		                         	<li>
		                         		<a href="<?php echo $urlGen->getTablesUrl("tables",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
		                         			<?php echo $rounds["round_title"]; ?>
		                         		</a>
		                         	</li>
		                         <?php  }  ?>
							<?php } else { ?>
								<?php  if($rounds['type'] == 'cup') { ?>
								
									<?php  if($knockout == 0) { ?>

			                    		<li>
			                    			<!--Change the default view from scores to schedules if round start date hasn't started.  --> 
											<?php
			
													$roundDone = 0;
													$todaysdate = date("Y-m-d"); 
													if ($todaysdate < $rounds['start_date'] ) { 
			  												$score_schedules = 'schedules';
													} elseif ($todaysdate <= $rounds['end_date'] && $todaysdate >= $rounds['start_date'] ) { 
														    $score_schedules = 'scores';
														    
													} elseif ($todaysdate > $rounds['end_date'] ) {
														    $score_schedules = 'scores';
														    $roundDone = 1;
													}
									
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
									
						<?php } else { ?>	
		
							<?php 
										$roundDone = 0;
										$todaysdate = date("Y-m-d"); 
										
										if ($todaysdate < $rounds['start_date'] ) { 
  												$score_schedules = 'schedules';
										} elseif ($todaysdate <= $rounds['end_date'] && $todaysdate >= $rounds['start_date'] ) { 
											    $score_schedules = 'scores';											    
										} elseif ($todaysdate > $rounds['end_date'] ) {
											    $score_schedules = 'scores';
											    $roundDone = 1;
										}
										
							?>
						
									<li>
										<a href="<?php echo $urlGen->getTablesUrl($score_schedules,$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">
											<?php echo $rounds["round_title"]; ?>
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
			                                  		<?php } ?>
			                                 </ul>
			                              </li>  
									<?php } ?>
								<?php } ?>	
							<?php }  ?>
                		<?php } ?>
                	</ul>
                </li>
          </ul>
            
<?php } ?>
<!--     	  <div> 
            Today :<?php //echo $this->todaysdate; ?>
            <BR> Today HERE : <?php //echo $todaysdate; ?>
            <BR> Format : <?php //echo $this->compFormat; ?>
            <BR> Type : <?php //echo $this->compType; ?>
            <BR>roundActive :<?php //echo $this->roundId; ?>
            <BR>roundId :<?php //echo $this->roundId; ?>
            <BR>round start :<?php //echo $rounds['start_date']; ?>
            <BR>round end :<?php //echo $rounds['end_date']; ?>
            <BR> Round Type:  <?php //echo $this->roundType; ?>
            <BR>submenu :<?php //echo $this->submenuSelected;?>
            <BR> # rounds :<?php //echo $this->totalrounds;?>
            <BR> #knockout :<?php //if($knockout != null) { echo $knockout;} ?>
          </div>     -->
         
</div>
<div class="" style="float: left; padding:0px;border:none;">
     <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Subscriptions and Alers">                          
         <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banner_generic_league.png" style="margin-top:10px;"/>
  	</a>
</div>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>