<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

    <?php
        if ($this->playerdetailsFull != null){

    ?>
            <ul class="Header">
                <li class="ColumnOneStats">Team</li>
                <li class="ColumnTwoStats">Season</li>
                <li class="ColumnThreeStats">Gp</li>
                <?php if ($this->playerposition != 'Goalkeeper'){ ?>
                    <li class="ColumnFourStats">Gl</li>
                <?php  } else { ?>
                  <li class="ColumnFourStats">Ga</li>
                <?php  }  ?>
                <li class="ColumnFiveStats">Yc</li>
                <li class="ColumnSixStats">Rc</li>

                <li class="ColumnSevenStats">Competition</li>

            </ul>
        <?php
                $i = 1;
                //foreach ( $this->playerdetails as $details ) {
                foreach ( $this->playerdetailsFull as $details ) {

                            if ($i % 2 == 1) {
                                $style = 'AltRow';
                                //$hoverstyle = $hovercolor1;
                            } else {
                                $style = '';
                                //$hoverstyle = $hovercolor2;
                            }
                            ?>
                        <ul class="<?php  echo $style; ?>">
                            <li class="ColumnOneStats">
                                <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $details ["team_id"], $details ["team_seoname"], True ); ?>">
                                    <?php echo $this->escape ( $details ["team_name"] ); ?>
                                </a>
                            </li>
                            <li class="ColumnTwoStats">
                                <?php echo $details["season_name"];?>
                            </li>
                            <li class="ColumnThreeStats">
                                <?php echo $details ["gp"];?>
                            </li>


                             <?php if ($this->playerposition != 'Goalkeeper'){ ?>
                                <li class="ColumnFourStats">
                                    <?php echo $details ["gl"];?>
                                </li>
                            <?php  } else { ?>
                                <li class="ColumnFourStats">
                                    <?php echo $details ["ga"];?>
                                </li>
                             <?php  }  ?>

                            <li class="ColumnFiveStats">
                                <?php echo $details ["yc"];?>
                            </li>
                            <li class="ColumnSixStats">
                                <?php echo $details ["rc"];?>
                             </li>
                            <li class="ColumnSevenStats">
                            <?php if ($details ["competition_name"] == 'World Cup Qualifications Europe') { ?>
                              WCQ
                             <?php } else { ?>
                              <?php echo $details ["competition_name"];?>
                             <?php } ?>
                            </li>
                        </ul>
                        <?php
                        $i ++;
                }

           } else {
              echo "<p class='NoData'>Career Statistics Unavailable for Player</p>";
           }
?>