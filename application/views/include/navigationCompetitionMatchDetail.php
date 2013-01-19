<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>

<div style="float: left; padding: 8px 0px;" class="WrapperForDropShadow">

  <?php if ($this->competitionFormat == 'Domestic league') { ?>

	<?php //echo $this->countryName; ?>
	<ul>
       <li class="<?php echo($this->menuSelected == 'competition'?'active':''); ?>">
          <a class="<?php echo($this->submenuSelected != 'none'?'noarrow':''); ?>" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>">
                 <?php echo $this->competitionName; ?>
           </a>
        </li>
        <li>
        	<ul>
        		<?php foreach($this->roundList as $rounds) {?>
        			<?php if ($this->totalrounds > 1) { ?>
	        		     <li id="domesticround<?php echo $rounds["round_id"]; ?>" class="<?php echo($this->roundmenuSelected == $rounds["round_id"]?'active':''); ?>">
	                          <a href=""><?php echo $rounds["round_title"]; ?></a> 
	                     </li>    
                    <?php } ?> 
                    
                    <?php if ($rounds["type"] == 'table') { ?>
                            <li "id="menu_<?php echo $rounds["round_id"]; ?>"">
                               <ul>
                                   <li class="<?php echo($this->submenuSelected == 'tables'?'active':''); ?>">
                                       <a href="<?php echo $urlGen->getTablesUrl("tables",$this->roundId ,$this->countryName ,$this->seasonTitle , $this->competitionName ,True) ?>">Tables</a>
                                    </li>
                                    <li class="<?php echo($this->submenuSelected == 'scores'?'active':''); ?>">
                                        <a href="<?php echo $urlGen->getTablesUrl("scores",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->competitionName ,True) ?>">Scoreboard</a>
                                    </li>

                                     <li class="<?php echo($this->submenuSelected == 'schedules'?'active':''); ?>">
                                        <a href="<?php echo $urlGen->getTablesUrl("schedules",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->competitionName,True) ?>">Schedule</a>
                                    </li>
                                    <li class="<?php echo($this->submenuSelected == 'teams'?'active':''); ?>">
                                        <a href="<?php echo $urlGen->getTablesUrl("teams",$this->roundId , $this->countryName ,$this->seasonTitle ,  $this->competitionName ,True) ?>">Teams</a>
                                    </li>
                                </ul>
                              </li>
                            <?php } ?>
                         		
        		<?php } ?>
        	</ul>
        </li>
         
        
     </ul>


  <!--International Cup (champions league,worldcup,copa libertadores-->    
  <?php } elseif($this->competitionFormat == 'Domestic cup') { ?>

					<?php //echo $this->countryName; ?>


  <!--International Cup (champions league,worldcup,copa libertadores--> 
  <!--$this->compFormat == 'International cup'--> 
  <?php } else { ?>

	<?php //echo $this->regionNameTitle; ?>
	
    <ul>
       <li class="<?php echo($this->menuSelected == 'competition'?'active':''); ?>">
          <a class="<?php echo($this->submenuSelected != 'none'?'noarrow':''); ?>" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>">
               <?php echo $this->competitionName; ?>
          </a>
       </li>
       <li>
          <ul>
          	<?php foreach($this->roundList as $rounds) { ?>
          		<?php if($rounds['type'] == 'table') { ?>
	                  <li id="groupstagemenu">
	              		 <a href="<?php echo $urlGen->getTablesUrl("tables",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->competitionName,True) ?>">
	                      <?php echo $rounds["round_title"]; ?>
	                      </a>
	             	 </li>
            	 <?php } else { ?>
                  	<li>
                		 <a href="<?php echo $urlGen->getTablesUrl("scores",$rounds['round_id'] ,$this->countryName ,$this->seasonTitle , $this->competitionName,True) ?>">
                             <?php echo $rounds["round_title"]; ?> 
                         </a>		                             
                	</li>
            	
            	 <?php } ?>	
              <?php } ?>
            </ul>
        </li>
     </ul>
     
  <?php } ?>
  
</div>
