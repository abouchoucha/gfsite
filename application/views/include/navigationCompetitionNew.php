<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<div class="WrapperForDropShadow"> 

    <?php if ($this->compType == 'club') { ?><!-- 1. Club Teams Competition-->

        <?php if ($this->compFormat == 'International cup') { ?><!-- 1.1 International Club Competitions-->
                <ul class="leftnavlist">
                     <li class="leftmenutitle">International Competitions</li>
                     <?php foreach($this->rcountry as $international) { ?>
                        <?php if ($this->leagueId == $international["competition_id"] ) { ?>
                           <li class="active">
                              <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>">
                                  <?php echo $international["competition_name"]; ?>
                              </a>
                              <ul id="leftsubnavlist">
                              <?php foreach($this->roundList as $rounds) { ?>
                                <?php if ($rounds["round_id"] == $this->currentActiveRound) { ?>
                                  <li id="subactive" class="roundactive">
                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                        <?php echo $rounds["round_title"]; ?> 
                                    </a>
                                    <ul id="leftsubnavlist">
                                        <li id="subactive" class="<?php echo($this->submenuSelected == 'sb'?'roundactive':'roundnoactive'); ?>">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/roundid/<?php echo $this->currentActiveRound;?>/sm/sb">Scoreboard</a>
                                        </li>
                                        <li id="subactive" class="<?php echo($this->submenuSelected == 'sc'?'roundactive':'roundnoactive'); ?>">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/roundid/<?php echo $this->currentActiveRound;?>/sm/sc">Schedule</a>
                                        </li>
                                        <?php if ($rounds["type"] == 'table') { ?>
                                         <li id="subactive" class="<?php echo($this->submenuSelected == 'tb'?'roundactive':'roundnoactive'); ?>">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfulltable/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/roundid/<?php echo $this->currentActiveRound;?>/sm/tb">Tables</a>
                                         </li>
                                        <?php }  ?>
                                    </ul>
                                 </li>
                               <?php } else { ?>
                                 <li id="subactive" class="roundnoactive">
                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                        <?php echo $rounds["round_title"]; ?>
                                    </a>
                                 </li>
                               <?php } ?>

                              <?php }  ?>
                              </ul>
                           </li>
                         <?php } else {  ?>
                            <li>
                              <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>">
                                <?php echo $international["competition_name"]; ?>
                              </a>
                            </li>
                         <?php }  ?>
                     <?php }  ?>
                </ul>
                
        <?php } else { ?><!-- 1.2. Domestic Club Competitions-->
   
                <ul class="leftnavlist">
                     <li class="leftmenutitle">Domestic Competitions1</li>
                     <?php foreach($this->dcountry as $domestic) { ?>
                        <?php if ($domestic["competition_id"] == $this->leagueId) { ?> <!--current competition requested page-->
                             <li class="active">
                                 <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($domestic["competition_name"], $domestic["competition_id"], True); ?>">
                                    <?php echo $domestic["competition_name"]; ?>
                                 </a>
                              <?php if ($this->compFormat == 'Domestic league') { ?><!--'1.2.1 Domestic League Competition-->
                                 <ul id="leftsubnavlist">
                                 <?php foreach($this->roundList as $rounds) {?>
                                      <?php if ($rounds["round_id"] == $this->currentActiveRound) { ?>
                                         <li id="subactive" class="roundactive">
                                            <?php if ($rounds["round_title"] != 'Regular Season') { ?>
                                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                                    <?php echo $rounds["round_title"]; ?>
                                                </a>
                                            <?php } ?>

                                             <ul id="leftsubnavlist">
                                                <li id="subactive" class="<?php echo($this->submenuSelected == 'ft'?'roundactive':'roundnoactive'); ?>">
                                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfulltable/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/ft">Full Table</a>
                                                </li>
                                                <li id="subactive" class="<?php echo($this->submenuSelected == 'fs'?'roundactive':'roundnoactive'); ?>">
                                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/fs">Scoreboard</a>
                                                </li>
                                                <li id="subactive" class="<?php echo($this->submenuSelected == 'fsc'?'roundactive':'roundnoactive'); ?>">
                                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/d/y/sm/fsc">Schedule</a>
                                                </li>
                                                <li id="subactive" class="<?php echo($this->submenuSelected == 'te'?'roundactive':'roundnoactive'); ?>">
                                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionsteams/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/te">Teams</a>
                                                </li>
                                             </ul>
                                         </li>
                                        <?php } else { ?>
                                         <li id="subactive" class="roundnoactive">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                                <?php echo $rounds["round_title"]; ?> --
                                            </a>
                                         </li>

                                        <?php } ?>

                                 <?php }  ?>
                                 </ul>
                              <?php } else { ?><!--'1.2.2 Domestic Cup Competition-->
                                  <ul id="leftsubnavlist">
                                    <?php foreach($this->roundList as $rounds) {?>
           
                                        <?php if ($rounds["round_id"] == $this->roundActive) { ?>
                                          <li id="subactive" class="roundactive">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"];?>">
                                              <?php echo $rounds["round_title"]; ?>
                                            </a>
                                          </li>
                                        <?php } else { ?>
                                          <li id="subactive" class="roundnoactive">
                                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"];?>">
                                              <?php echo $rounds["round_title"]; ?>
                                            </a>
                                          </li>
                                        <?php }  ?>

                                    <?php }  ?>
                                  </ul>
                             <?php }  ?>
                             </li>
                        <?php } else { ?>
                             <li>
                                 <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($domestic["competition_name"], $domestic["competition_id"], True); ?>">
                                    <?php echo $domestic["competition_name"]; ?>
                                 </a>
                             </li>
                        <?php }  ?>
                     <?php }  ?>

                     <li class="leftmenutitle">International Competitions</li>
                     <?php foreach($this->rcountry as $international) { ?>
                     <li>
                        <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>">
                            <?php echo $international["competition_name"]; ?>
                        </a>
                     </li>
                     <?php }  ?>
               </ul>
       <?php } ?>
   
    <?php } else {  ?>  
        <ul class="leftnavlist">
            <li class="leftmenutitle">International Competitions</li>
                 <?php foreach($this->rcountry as $international) { ?>
                     <li>
                        <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>">
                            <?php echo $international["competition_name"]; ?>
                        </a>
                     </li>
                 <?php }  ?>
        </ul>
    <?php } ?>

</div>
