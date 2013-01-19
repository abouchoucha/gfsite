
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
            
    <?php
        //if($this->paginator != null ) {

        if(sizeof($this->paginator) > 0 ) {

        $temp = '999';
        $temp1 = 'aaa';

        //foreach($this->nrmatches as $nrmatch ) :
        foreach($this->paginator as $nrmatch) :
    ?>
          
            <div class="DropShadowHeader BrownGradientForDropShadowHeader">
              <div class="DownArrow" onclick="toggleCompetition('<?php echo str_replace(" ","",$this->escape($nrmatch["competition_name"]));?>',this);"></div>
                  <h4 class="WithArrowToLeft">
                   <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($nrmatch["competition_name"], $nrmatch["league"], True); ?>">
                   	<?php echo $this->escape($nrmatch["competition_name"]);?>
                   </a>
                 </h4>
            </div>
            <div class="Scores" id="<?php echo str_replace(" ","",$this->escape($nrmatch["competition_name"]));?>">

            <?php foreach($this->paginator as $match) :

                if(trim($match["league"]) == trim($nrmatch["league"])){
                if(trim($temp) != trim($match["league"]) . trim($match["mdate"])){ 
            ?>
                    <span style="font-weight: bold;">
                        <?php echo date ('l - F j , Y' , strtotime($match['mdate']));?>
                    </span>
                       <?php } ?> 

                 <p></p> 

                 <ul>
                    <li class="TeamName">
                        <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?>
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teama"], True); ?>" title="<?php echo $this->escape($match["teama"]) ;?>"><?php echo "<strong>" . $this->escape($match["teama"]) ."</strong>";?></a>
                        <?php }else { ?>
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teama"], True); ?>" title="<?php echo $this->escape($match["teama"]) ;?>"><?php echo $this->escape($match["teama"]) ;?></a>
                        <?php  } ?>  
                    </li>
                     <li class="Score">
                      <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
                          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["fs_team_a"]);?>
                          - <?php echo $this->escape($match["fs_team_b"]);?></a>
                     <?php }else {  ?>
                          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
                     <?php  }  ?>

                     </li>
                    <li class="TeamName">
                        <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teamb"], True); ?>" title="<?php echo $this->escape($match["teamb"]) ;?>"><?php echo "<strong>" . $this->escape($match["teamb"]) ."</strong>";?></a>
                        <?php }else { ?>
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teamb"], True); ?>" title="<?php echo $this->escape($match["teamb"]) ;?>"><?php echo $this->escape($match["teamb"]) ;?></a>
                        <?php } ?> 
                    </li>
                   <li class="Time GameOn">
                    <?php if($match["status"] == 'Played'){ ?>
                         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
                    <?php }else if($match["status"] == 'Playing'){  ?>
                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing (<?php echo $match["match_minute"];?>')</a>
                    <?php }else if($match["status"] == 'Suspended'){  ?>
                              Suspended
                    <?php } else if($match["status"] == 'Fixture'){  ?>
                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                <?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>
                               </a>
                  <?php  } ?>
                 </li>

                 </ul>
                 <p></p>
            <?php 
                             } //end if for country
                 $temp = $match["league"] . $match["mdate"];
                 $temp1 = $match["league"];
                 endforeach;
            ?>


            </div>

          	  <?php    
                  endforeach;
                    
            }else {
                   
                  echo 'No matches scheduled.';
                  
            }

    ?> <!-- End of scoreboards-->
            
        

  







