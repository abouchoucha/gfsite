<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

<?php
        if ($this->playerdetailsFull != null){

?>
       <div class="scores">
            <ul>
                <li class="name">Season</li>
                <li class="team">Team</li>                             
                <li class="cont">Games</li>   
                <?php if ($this->playerposition != 'Goalkeeper'){ ?>
                <li class="score">
                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt=""/>
                </li>
                <?php  } else { ?>
                <li class="score">
                  Ga
                </li>
                <?php  }  ?>
                <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt=""/></li>
                <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt=""/></li>
            </ul>
         </div>
         
         
          <?php
                $i = 1;
                foreach ( $this->playerdetailsFull as $details ) {

                            if ($i % 2 == 1) {
                                $style = 'scores1';
                                //$hoverstyle = $hovercolor1;
                            } else {
                                $style = 'scores2';
                                //$hoverstyle = $hovercolor2;
                            }
          ?>
          
          <div class="<?php  echo $style; ?>">
              <ul>
                  <li class="name"><?php echo $details["season_name"];?></li>
                  <li class="team"> 
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $details ["team_id"], $details ["team_seoname"], True ); ?>">
                        <?php echo $this->escape ( $details ["team_name"] ); ?>
                    </a>
                  </li>
                  <li class="cont"><?php echo $details ["gp"];?></li>
                    <?php if ($this->playerposition != 'Goalkeeper'){ ?>
                          <li class="score">
                              <?php echo $details ["gl"];?>
                          </li>
                      <?php  } else { ?>
                         <li class="score">
                              <?php echo $details ["ga"];?>
                         </li>
                      <?php  }  ?>
                  <li class="score"><?php echo $details ["yc"];?></li>
                  <li class="score"><?php echo $details ["rc"];?></li>
              </ul>
          </div>
                       
          <?php
                        $i ++;
                }
         ?>

<?php
           } else {
              echo "<p class='NoData'>Career Statistics Unavailable for Player</p>";
           }
?>