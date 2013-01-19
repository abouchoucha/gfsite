<?php
    require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
  ?>
  <table id="LeagueTable" width="100%" cellspacing="0" cellpadding="1" border="0">

   
        <?php 
        	require_once 'Team.php';
    		$equipo = new Team();	
    	
        ?>
    <?php foreach ($this->leagueTable as $tournament ){ ?>
     <?php if ($this->roundId == $tournament['stage_id']) { ?>
          <tr class="lheading"  align="left">
            <th class="lheading">#</th>
            <th class="lheading">&nbsp;</th>
            <th class="lheading">Team</th>
            <th class="lheading">MP</th>
            <th class="lheading">W</th>
            <th class="lheading">D</th>
            <th class="lheading">L</th>
            <th class="lheading">Pts</th>
         </tr> 
       <?php 
           $i = 1; 
           foreach($tournament as $team ) {  ?>
    
    	    <?php
 				$teamRow = $equipo->fetchRow( 'team_gs_id = ' . $team['id'] );                  				
 			?>
              <?php
                   if($i % 2 == 1) {
                  $style = 'odd';
                  //$hoverstyle = $hovercolor1;
               }else{
                  $style = 'even';
               //$hoverstyle = $hovercolor2;
               }
              ?>
                       
        	<tr class="<?php echo $style; ?>">
                <td class="first">
                	<?php echo $team['position']; ?>
                </td>
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
                          <td><a href="<?php echo $urlGen->getClubMasterProfileUrl($teamRow['team_id'],$teamRow['team_seoname'], True); ?>">
                          
                           <?php //echo mb_convert_encoding($ranking['name'], "ISO-8859-1", "UTF-8");  ?>
                           
                            <?php 
                			if(strlen($this->escape($team['name'])) > 15) {
						   		echo substr($this->escape($team['name']), 0, 15) . '...';
							} else {
								echo $this->escape($team['name']);
							}
                    ?>
                 
                 
                </a> </td>

                <td><?php echo $team->overall['gp'] ?></td>
                <td><?php echo $team->overall['w'] ?></td>
                <td><?php echo $team->overall['d'] ?></td>
                <td><?php echo $team->overall['l'] ?></td>       
                <td class="last"><?php echo $team->total['p'] ?></td>
          </tr>
    	
       <?php $i++; } ?>
       
       <?php  } ?> 
       
    <?php  } ?>  
</table>