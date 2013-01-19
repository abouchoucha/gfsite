<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 
<div id="ContentWrapper" class="TwoColumnLayout">

    <div class="FirstColumnOfThree">

                <?php
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>
                    
                    <?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                    <!--Goalface Register Ad-->

                    <?php } ?>

				
                 <!--Team Profile Badge-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/badgeTeam.php');?>
                    </div>

                  <!--Team Profile left Menu-->
                    <div class="img-shadow">
                       <?php echo $this->render('include/navigationTeam.php');?>
                    </div>

   </div><!--end FirstColumnOfTwo-->

   <div class="SecondColumn" id="SecondColumnPlayerProfile">
         <h1><?php echo $this->team[0]['team_name']; ?> League Performance</h1>
           <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <!--Some Text Here-->
                <table class="PlayerData">
                    <tr>
                        <th class="NoLeftBorder">Season</th>
                        <th class="LeftAlign">League</th>
                        <th>Rank</th>
                        <th>Gp</th>
                        <th>Wn</th>
                        <th>Dr</th>
                        <th>Ls</th>
                        <th>Gf</th>                 
                        <th>Ga</th>                    
                        <th>Pts</th>
                        <th>HW</th>
                        <th>HD</th>
                        <th>HL</th>
                        <th>AW</th>
                        <th>AD</th>
                        <th>AL</th>
                        <th>AAttn</th>
                        <th>Rc</th>
                        <th>Yc</th>
                        <th class="NoRightBorder">Cs</th>
                    </tr>

                    <?php $i = 1; ?>
                    <?php foreach ($this->teamStatsFull as $details)  {
                      if($i % 2 == 1)
                      {
                        $style = "";
                      }else{
                        $style = "Even";
                      }
                    ?>
                        <tr class="<?php echo $style; ?>">
                            <td class="LeftAlign"><?php echo $details["season_name"]; ?></td>
                            <td class="LeftAlign"><?php echo $details["competition_name"]; ?></td>
                            <td><?php echo $details["rank"]; ?></td>
                            <td><?php echo $details["gp"]; ?></td>
                            <td><?php echo $details["wn"]; ?></td>
                            <td><?php echo $details["dr"]; ?></td>
                            <td><?php echo $details["ls"]; ?></td>
                            <td><?php echo $details["gf"]; ?></td>                            
                            <td><?php echo $details["ga"]; ?></td>                       
                            <td><?php echo $details["pts"]; ?></td>
                            <td><?php echo $details["hw"]; ?></td>
                            <td><?php echo $details["hd"]; ?></td>
                            <td><?php echo $details["hl"]; ?></td>
                              <td><?php echo $details["aw"]; ?></td>
                            <td><?php echo $details["ad"]; ?></td>
                            <td><?php echo $details["al"]; ?></td>
                            <td class="RightAlign"><?php echo $details["average"]; ?></td>
                            <td><?php echo $details["rc"]; ?></td>
                            <td><?php echo $details["yc"]; ?></td>
                            <td class="NoRightBorder"><?php echo $details["cs"];?></td>
                        </tr>
                    <?php $i++; } ?>
                 </table>
            </div>
           </div>
                   <div class="StatLegend">
                        <ul class="Abbreviation">
                            <li>Gp</li>
                            <li>Wn</li>
                            <li>Dr</li>
                            <li>Ls</li>
                            <li>Gf</li>
                        </ul>
                        <ul class="Full">
                            <li>Games Played</li>
                            <li>Wins</li>
                            <li>Draws</li>
                            <li>Losses</li>
                            <li>Goals Favor</li>
                        </ul>
                         <ul class="Abbreviation">
                            <li>Ga</li>
                            <li>Pts</li>
                            <li>HW</li>
                            <li>HD</li>
                            <li>HL</li>
                        </ul>
                        <ul class="Full">
                            <li>Goals Against</li>
                            <li>Points</li>
                            <li>Home Wins</li>
                            <li>Home Draw</li>
                            <li>Home Losses</li>
                        </ul>
                         <ul class="Abbreviation">
                            <li>AW</li>
                            <li>AD</li>
                            <li>AL</li>
                            <li>AAttn</li>
                            <li>Yc</li>
                        </ul>
               		<ul class="Full">
                            <li>Away Wins</li>
                            <li>Away Draws</li>
                            <li>Away Losses</li>
                            <li>Average Attendance</li>
                            <li>Yellow Cards</li>


                        </ul>
                        <ul class="Abbreviation">
                            <li>Rc</li>
                            <li>Cs</li>
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                            <!--<li>A/90</li>-->
                            <li>&nbsp;</li>
                            <!--<li>Fls/90</li>-->
                        </ul>
                        <ul class="Full Last">
                            <li>Red Cards</li>
                            <li>Clean Sheets</li>
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                            <!--<li>Asssits Per 90 Minutes</li>-->
                            <li>&nbsp;</li>
                            <!--<li>Fouls Per 90 Minutes</li>-->
                        </ul>

                    </div>
    </div><!--end SecondColumnOfTwo-->
             
</div> <!--end ContentWrapper-->
