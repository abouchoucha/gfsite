
<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

<?php if ($this->topscorercomp != null) { ?> 
	  <div class="scores">                        
	    <ul>                            
	      <li class="name" style="width:120px">Player</li>                            
	      <li class="team" style="width:113px">Team</li>                            
	      <!-- <li class="cont">GP</li>   -->                        
	      <li class="score">                              
	        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt=""></li>                        
	    </ul>                    
	  </div>   
	   <?php $i = 1; ?>
                    <?php foreach ($this->topscorercomp->tournament->player as $item) { ?>
                    <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?> 
                    
                     <?php 
                                $team = new Team();
                                $row = $team->fetchRow($team->select()->where('team_gs_id = ?',  $item['team_id']));
                                //Zend_Debug::dump ($row);
                                $country_flag = $row->country_id .".png";
                                $team_gf_id = $row->team_id;
                                $team_gf_name = $row->team_name;
                                $team_gf_seoname = $row->team_seoname;
                                
                                $player = new Player();
                                $rowplayer = $player->fetchRow($player->select()->where('player_id = ?',  $item['id']));
                                $player_common_name = $rowplayer->player_common_name;
                           ?>
                    
                                                                                                                     
		  <div class="<?php echo $style; ?>">                            
		    <ul>                                
		      <li class="name" style="width:120px">                                                                  
		        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["id"], true ,$player_common_name); ?>">
		        	<?php echo $item['name']; ?></a></li>                                
		      <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/teamlogos/<?php echo $team_gf_id?>.gif') no-repeat scroll left center transparent;">                                  
		        <a href="<?php echo $urlGen->getClubMasterProfileUrl($team_gf_id,$team_gf_seoname, True); ?>">
		        	<?php echo $item['team']; ?>
		        </a>
		      </li>                                
		     <!-- <li class="cont">7</li>  -->                               
		      <li class="score"><?php echo $item['goals']; ?></li>                            
		    </ul>                        
		  </div> 
		<?php $i++; } ?>

<?php } else {?>

 <br><center><strong>No Data available.</strong></center><br>
 
<?php } ?>

