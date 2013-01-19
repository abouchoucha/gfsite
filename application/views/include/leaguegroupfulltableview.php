<?php
    require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
  ?>
                                     
                                     	<?php foreach ($this->leagueTable as $tournament ){ ?>
		                                      <table id="LeagueTable" width="100%" cellspacing="0" cellpadding="1" border="0">
		                                          <tr style="border-bottom:1px solid #D5D3D4;">
		                                              <td class="tableTopHead" colspan="4"><?php echo $tournament['name'];?></td>
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
		   											<?php $i = 1; ?>
			                                        <?php   foreach ($tournament as $ranking ) {  ?> 
			                                        	<?php if($i % 2 == 1) {$style = 'odd';}else{$style = 'even';}?>
			                                        		<tr class="<?php echo $style; ?>">
			                                                <td class="first">
			                                                  <?php echo $ranking['position'] ?>
			                                                </td>
			                                                <td>
			                                                    &nbsp;
			                                                </td>
			                                                <td class="logo">
 															<?php require_once 'Team.php';
                                               						$equipo = new Team();
                                                					//$row = $team->fetchRow($team->select()->where('team_id = ?',  $ranking['team_id']));
                                                					$teamRow = $equipo->fetchRow( 'team_gs_id = ' . $ranking['id'] );
                                                					$country_flag = $teamRow->country_id .".png";
                                         						?>
                                          						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $country_flag; ?>" alt="<?php echo $ranking['name']; ?>">
			                                                </td>
			                                                <td class="teamname">
						                                     <a href="<?php echo $urlGen->getClubMasterProfileUrl($teamRow['team_id'],$teamRow['team_seoname'], True); ?>">
						
						                                      	 <?php 
									                        			if(strlen($this->escape($ranking['name'])) > 15) {
																	   		echo substr($this->escape($ranking['name']), 0, 15) . '...';
																		} else {
																			echo $this->escape($ranking['name']);
																		}
													             ?>
						                                      </a>
			                                                </td>
			                           						<td><?php
														        echo $ranking->overall['gp']?></td>
																<td><?php
														        echo $ranking->overall['w']?></td>
																<td><?php
														        echo $ranking->overall['d']?></td>
																<td><?php
														        echo $ranking->overall['l']?></td>
																<td><?php
														        echo $ranking->overall['gs']?></td>
																<td><?php
														        echo $ranking->overall['ga']?></td>
																<td><?php
														        echo $ranking->home['w']?></td>
																<td><?php
														        echo $ranking->home['d']?></td>
																<td><?php
														        echo $ranking->home['l']?></td>
																<td><?php
														        echo $ranking->home['gs']?></td>
																<td><?php
														        echo $ranking->home['ga']?></td>
																<td><?php
														        echo $ranking->away['w']?></td>
																<td><?php
														        echo $ranking->away['d']?></td>
																<td><?php
														        echo $ranking->away['l']?></td>
																<td><?php
														        echo $ranking->away['gs']?></td>
																<td><?php
														        echo $ranking->away['ga']?></td>
														       	<td><?php
														        echo $ranking->total['gd']?></td>
																<td class="last"><?php
														        echo $ranking->total['p']?></td>
			                                            </tr>
			                                        
			                                         <?php $i++; } ?>
		                                        </table>
                                     	<?php }  ?>  
