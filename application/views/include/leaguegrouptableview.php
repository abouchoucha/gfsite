<?php require_once 'seourlgen.php';
	  require_once 'urlGenHelper.php';  
	  $urlGenHelper = new UrlGenHelper();
	  $urlGen = new SeoUrlGen();
?> 	
<?php
    require_once 'Round.php';
    $round = new Round();
    require_once 'Team.php';
    $equipo = new Team();	
  ?>
  
<table id="LeagueTable" width="100%" cellspacing="0" cellpadding="1" border="0">
<?php foreach ($this->leagueTable as $tournament ){ ?>
    <?php $roundRow = $round->fetchRow( 'round_id = ' . $tournament['stage_id'] ); ?>
    <?php if($roundRow['type'] == 'table') { ?>
        <tr class="gheading">
        	<td class="ghtd" nowrap colspan="8">
        	    <?php if($this->compFormat == 'Domestic league') { ?>
        	    	 <?php  $titlesplit = explode ( ":", $tournament['league'] ); ?>
        	        <?php echo $titlesplit[1];?>
        	    <?php } else { ?>   
        	        <?php  $titlesplit = explode ( ":", $tournament['name'] ); ?>
        	        <?php echo $titlesplit[1];?>
        	     <?php } ?>  
        	</td>
        </tr>
        
        <tr class="lheading"  align="left">
            <th class="lhtd">#</th>
            <th class="lhtd">&nbsp;</th>
            <th class="lhtd">Team</th>
            <th class="lhtd">MP</th>
            <th class="lhtd">W</th>
            <th class="lhtd">D</th>
            <th class="lhtd">L</th>
            <th class="lhtd">Pts</th>
         </tr>
         
          <?php $i = 1; 
                 foreach ($tournament as $team ) {  ?>
                 <?php $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $team['id'] ); ?>
                   <?php if($i % 2 == 1) { $style = 'odd'; }else{ $style = 'even';}?>
                   
                      	<tr class="<?php echo $style; ?>">
                            <td class="first">
                            	<?php echo $team['position'] ?>
                            </td>
                             <td class="logo">
                                <?php if($this->compFormat == 'Domestic league') { ?>
                                     <?php
                					        $config = Zend_Registry::get('config');
                					        $path_team_logos = $config->path->images->teamlogos . $teamRow['team_id'] .".gif";
                					        if (file_exists($path_team_logos)) {
                					        ?>
                					            <img src="<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/teamlogos/<?php echo $teamRow['team_id']?>.gif" />
                					          <?php  } else { ?>
                					            <img src="<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/TeamText80.gif" />
                					         <?php  } ?>
                                   
                                <?php } else {?> <!--International cup --> 
                                        <?php 
                                             $country_flag = $teamRow['country_id'] .".png";
                                         ?>
                                          <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $country_flag; ?>" alt="<?php echo $team['name']; ?>">
        	
		                        <?php } ?>
                             </td>
                            <td>
                              	<a href="<?php echo $urlGen->getClubMasterProfileUrl($teamRow['team_id'],$teamRow['team_seoname'], True); ?>">
                                   <?php //echo mb_convert_encoding($ranking['name'], "ISO-8859-1", "UTF-8");  ?>
                                   
                                    <?php 
                        			if(strlen($this->escape($team['name'])) > 15) {
        						   		echo substr($this->escape($team['name']), 0, 15) . '...';
        							} else {
        								echo $this->escape($team['name']);
        							}
                                    ?>
                            	</a> 
                            </td>
            
                            <td><?php echo $team->overall['gp'] ?></td>
                            <td><?php echo $team->overall['w'] ?></td>
                            <td><?php echo $team->overall['d'] ?></td>
                            <td><?php echo $team->overall['l'] ?></td>
                            <td class="last"><?php echo $team->total['p'] ?></td>
                      </tr>

		        <?php $i++; } ?>
         
    <?php } ?>
<?php } ?>
</table>
