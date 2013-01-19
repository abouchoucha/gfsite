<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<div class="WrapperForDropShadow"> 
  <ul class="leftnavlist">
  <?php if ($this->compType == 'club' and $this->compRegional == 0) { ?>
    <li class="leftmenutitle">Domestic Competitions</li>
     <?php foreach($this->dcountry as $domestic) { ?>
         <?php if ($domestic["competition_id"] == $this->leagueId) { ?>
            <li class="active">
                <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($domestic["competition_name"], $domestic["competition_id"], True); ?>">
                    <?php echo $domestic["competition_name"]; ?>
                </a>
                <ul id="leftsubnavlist">
                     <?php foreach($this->roundList as $rounds) {?>
                        <?php if ($rounds["round_id"] == $this->roundActive) { ?>
                            <li id="subactive" class="roundactive">
                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                    <?php echo $rounds["round_title"]; ?>
                                </a>
                            </li>
                               <?php  if (sizeOf($this->roundList) == 1) { ?>
                               <ul>
                                    <li id="subactive" class="<?php echo($this->submenuSelected == 'ft'?'roundactive':'roundnoactive'); ?>"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfulltable/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/ft">Full Table</a></li>
                                    <li id="subactive" class="<?php echo($this->submenuSelected == 'fs'?'roundactive':'roundnoactive'); ?>"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/fs">Full Scoreboard</a></li>
                                    <li id="subactive" class="<?php echo($this->submenuSelected == 'fsc'?'roundactive':'roundnoactive'); ?>"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/d/y/sm/fsc">Full Schedule</a></li>
                                    <li id="subactive" class="<?php echo($this->submenuSelected == 'te'?'roundactive':'roundnoactive'); ?>"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionsteams/leagueid/<?php echo $this->leagueId;?>/seasonid/<?php echo $this->seasonId;?>/sm/te">Teams</a></li>
                               </ul>
                                <?php }?>

                        <?php } else { ?>
                        <?php  if (sizeOf($this->roundList) > 1) { ?>
                            <li id="subactive"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>"><?php echo $rounds["round_title"]; ?></a></li>
                            <?php }else { ?>
                                <li id="subactive" class="roundnoactive"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetition/compid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"];?>"><?php echo $rounds["round_title"]; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                      <?php }  ?>
                 </ul>
            </li>
           <?php } else { ?>
              <li><a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($domestic["competition_name"], $domestic["competition_id"], True); ?>"><?php echo $domestic["competition_name"]; ?></a></li>
         <?php }  ?>
     <?php } ?>
   <?php } ?>

    <li class="leftmenutitle">International Competitions</li>
    <?php foreach($this->rcountry as $international) { ?>
            <?php if ($this->leagueId == $international["competition_id"] ) { ?>
                <li class="active">
                    <a class="current" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>">
                        <?php echo $international["competition_name"]; ?>
                    </a>
                    <ul id="leftsubnavlist">
                       <?php foreach($this->roundList as $rounds) { ?>
                            <?php if ($rounds["round_id"] == $this->roundActive) { ?>
                            <li id="subactive" class="roundactive">
                                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/<?php echo $this->leagueId;?>/roundid/<?php echo $rounds["round_id"]; ?>">
                                    <?php echo $rounds["round_title"]; ?>
                                </a>
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
            <?php }else { ?>
                <li><a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>"><?php echo $international["competition_name"]; ?></a></li>
            <?php }  ?>

    <?php } ?>
   </ul>
</div>