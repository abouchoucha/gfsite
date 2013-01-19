<?php
    require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
  ?>
<table id="LeagueTable" width="100%" cellspacing="0" cellpadding="1"
	border="0">
	<tr style="border-bottom: 1px solid #D5D3D4;">
		<td class="tableTopHead" colspan="4"></td>
		<td class="tableTopHead" align="center" colspan="6">Total</td>
		<td class="tableTopHead" align="center" colspan="5">Home</td>
		<td class="tableTopHead" align="center" colspan="6">Away</td>
		<td class="tableTopHead"></td>
	</tr>
	<tr class="" align="left">
		<th class="lheading">#</th>
		<th class="lheading">&nbsp;</th>
		<th class="lheading logo">&nbsp;</th>
		<th class="lheading">Team</th>
		<th class="lheading mp">MP</th>
		<th class="lheading">W</th>
		<th class="lheading">D</th>
		<th class="lheading">L</th>
		<th class="lheading">GS</th>
		<th class="lheading">GA</th>
		<th class="lheading home mp">W</th>
		<th class="lheading home">D</th>
		<th class="lheading home">L</th>
		<th class="lheading home">GS</th>
		<th class="lheading home">GA</th>
		<th class="lheading away mp">W</th>
		<th class="lheading away">D</th>
		<th class="lheading away">L</th>
		<th class="lheading away">GS</th>
		<th class="lheading away">GA</th>
		<th class="lheading away">+/-</th>
		<th class="lheading away">Pts</th>
	</tr>
      <?php 
      		$i = 1;                                   
       		require_once 'Team.php';
       		$equipo = new Team();
      ?>
      <?php   foreach ($this->leagueTable as $team ) {  ?>
      <?php		$teamRow = $equipo->fetchRow( 'team_gs_id = ' . $team['id'] ); 

                if($i % 2 == 1) {
                   $style = 'odd';
                   //$hoverstyle = $hovercolor1;
                }else{
                	$style = 'even';
                   //$hoverstyle = $hovercolor2;
                }
      ?>
      <tr class="<?php echo $style; ?>">
		<td class="first"><?php echo $team['position'] ?></td>
		<td>&nbsp;</td>
		<td class="logo">
                                                                                               
        <?php
        $config = Zend_Registry::get('config');
        $path_team_logos = $config->path->images->teamlogos . $teamRow['team_id'] .".gif";
        if (file_exists($path_team_logos)) {
        ?>
            <img src="<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/teamlogos/<?php echo $teamRow['team_id']?>.gif" />

          <?php  } else { ?>
            <img src="<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/TeamText80.gif" />

      <?php
        }
        ?>
                                              
                                                </td>
		<td><a
			href="<?php
        echo $urlGen->getClubMasterProfileUrl($teamRow['team_id'], 
        $teamRow['team_seoname'], True);
        ?>">
                                                  
        <?php
        echo $teamRow['team_name'];
        ?>
                                                  </a></td>
		<td><?php
        echo $team->overall['gp']?></td>
		<td><?php
        echo $team->overall['w']?></td>
		<td><?php
        echo $team->overall['d']?></td>
		<td><?php
        echo $team->overall['l']?></td>
		<td><?php
        echo $team->overall['gs']?></td>
		<td><?php
        echo $team->overall['ga']?></td>
		<td><?php
        echo $team->home['w']?></td>
		<td><?php
        echo $team->home['d']?></td>
		<td><?php
        echo $team->home['l']?></td>
		<td><?php
        echo $team->home['gs']?></td>
		<td><?php
        echo $team->home['ga']?></td>
		<td><?php
        echo $team->away['w']?></td>
		<td><?php
        echo $team->away['d']?></td>
		<td><?php
        echo $team->away['l']?></td>
		<td><?php
        echo $team->away['gs']?></td>
		<td><?php
        echo $team->away['ga']?></td>
        <td><?php
        echo $team->total['gd']?></td>
		<td class="last"><?php
        echo $team->total['p']?></td>
	</tr>
    <?php
        $i ++;
    }
    ?>
</table>
