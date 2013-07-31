<?php
require_once 'seourlgen.php';
require_once 'Team.php';
$urlGen = new SeoUrlGen();
$equipo = new Team();
$league = new LeagueCompetition();
?>

<?php if ($this->playerposition != 'Goalkeeper'){ ?>
    <table class="PlayerData">
        <tr>
            <th class="NoLeftBorder">Season</th>
            <th class="LeftAlign">Team</th>
            <th class="LeftAlign">League</th>
            <th>Gp</th>
            <th>Sb</th>
            <th>Min</th>
            <th>Gl</th>
            <!--<th>Pn</th>
            <th>As</th>
            --><th>Yc</th>
            <th class="NoRightBorder">Rc</th>
        </tr>

                <?php $i = 1; ?>
            				<?php foreach ($this->playerSeasonFull as $data)  {

                         if($i % 2 == 1){$style = "";}else{$style = "Even";}

            				?>

                    <tr class="<?php echo $style; ?>">
                        <td class="LeftAlign"><?php echo $data["season"]; ?></td>
                        <?php  $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); ?>
                        <td class="LeftAlign">
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($teamRow["team_id"], $teamRow["team_seoname"], true)  ?>">
                               <?php echo $this->escape($teamRow['team_name']); ?>
                            </a>
                        </td>
                        <?php  $leagueRow = $league->fetchRow( 'competition_gs_id = ' . $data['league_id'] ); ?>
                        <td class="LeftAlign">
                              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $leagueRow['country_id']; ?>.png">
                              </img>
                             <a href="<?php echo $urlGen->getShowTournamentUrl ( $leagueRow ["competition_name"], $leagueRow ["competition_id"], true ) ?>">
                                <?php echo $leagueRow['competition_name']; ?>
                             </a>
                        </td>
                        <td><?php if (empty($data['appearences'])){echo "--"; } else {echo $data['appearences'];}?></td>
                        <td class="RightAlign"><?php if (empty($data['substitute_in'])){echo "--"; } else {echo $data['substitute_in'];}?></td>
                        <td><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></td>
                        <td><?php if (empty($data['goals'])){echo "--"; } else {echo $data['goals'];}?></td>
                         <!--<td><?php //echo $details["pn"]; ?></td>
                       <td><?php //echo $details["ast"]; ?></td>
                        --><td><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></td>
                        <td class="NoRightBorder"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></td>
                    </tr>

        		        <?php $i++; } ?>
   </table>

                       <div class="StatLegend">
                        <ul class="Abbreviation">
                            <li>Gp</li>
                            <li>Sb</li>


                        </ul>
                        <ul class="Full">
                            <li>Games Played</li>
                            <li>Games as Sub</li>
                        </ul>
                         <ul class="Abbreviation">
            								<li>Min</li>
                            <li>Gl</li>


                        </ul>
                        <ul class="Full">
                           	<li>Minutes Played</li>
                            <li>Goals</li>
                        </ul>
                         <ul class="Abbreviation">
                             <li>Yc</li>
                            <li>Rc</li>
                        </ul>
               					<ul class="Full">
                              <li>Yellow Cards</li>
                            <li>Red Cards</li>

                        </ul>
                        <ul class="Abbreviation">
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>

                        </ul>
                        <ul class="Full Last">
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>

                        </ul>
                    </div>

    <?php } else { ?>
    <table class="PlayerData">
        <tr>
            <th class="NoLeftBorder">Season</th>
            <th class="LeftAlign">Team</th>
            <th class="LeftAlign">League</th>
            <th>Gp</th>
            <th>Min</th>
            <th>Sb</th>
            <th>Ga</th>
            <th>Gavg</th>
            <th>Cs</th>
            <th>YC</th>
            <th>RC</th>
    		</tr>
    		 <?php $i = 1; ?>
    				<?php foreach ($this->playerSeasonFull as $data)  {

                 if($i % 2 == 1){$style = "";}else{$style = "Even";}

    				?>

            <tr class="<?php echo $style; ?>">
                <td class="LeftAlign"><?php echo $data["season"]; ?></td>
                 <?php  $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); ?>
                <td class="LeftAlign"><a href="<?php echo $urlGen->getClubMasterProfileUrl($teamRow["team_id"], $teamRow["team_seoname"], true)  ?>">
                    <?php echo $this->escape($teamRow['team_name']); ?></a></td>
                <td><?php echo $data['league']; ?></td>
              	<td><?php if (empty($data['appearences'])){echo "--"; } else {echo $data['appearences'];}?></td>
              	<td><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></td>
                <td class="RightAlign"><?php if (empty($data['substitute_in'])){echo "--"; } else {echo $data['substitute_in'];}?></td>
                <td><?php if (empty($data['ga'])){echo "--"; } else {echo $data['ga'];}?></td>
                <td><?php if (empty($data['gavg'])){echo "--"; } else {echo $data['gavg'];}?></td>
                <td><?php if (empty($data['cleasheets'])){echo "--"; } else {echo $data['cleasheets'];}?></td>
                <td><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></td>
                <td><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></td>
            </tr>

		        <?php $i++; } ?>
    </table>

    <div class="StatLegend">
        <ul class="Abbreviation">
            <li>Gp</li>
            <li>Sb</li>
        </ul>
        <ul class="Full">
            <li>Games Played</li>
            <li>Games as Sub</li>
        </ul>
        <ul class="Abbreviation">
       			<li>Min</li>
            <li>Ga</li>
        </ul>
         <ul class="Full">
            <li>Minutes Played</li>
            <li>Goals Allowed</li>
        </ul>
        <ul class="Abbreviation">
          	<li>Gavg</li>
            <li>Cs</li>
        </ul>
         <ul class="Full">
            <li>Goals Allowed Avg</li>
            <li>Clean Sheets</li>
        </ul>

         <ul class="Abbreviation">
            <li>YC</li>
            <li>RC</li>
        </ul>
        <ul class="Full Last">
            <li>Yellow Cards</li>
            <li>Red Cards</li>
        </ul>
	</div>
    <?php } ?>



