<?php require_once 'seourlgen.php';
	$urlGen = new SeoUrlGen();
 	$config = Zend_Registry::get ( 'config' );
?>

             <div class="scores">
                 <ul>
                 <li class="silueta">&nbsp;</li>
                    <li class="name">Name</li>
                    <li class="position">Position</li>
                    <li class="team">Team</li>
                  </ul>
              </div>
              
              <?php $i = 1;
			      foreach ($this->nationalroaster as $data) {
			          if($i % 2 == 1)
			          {
			              $style = "scores1";
			          }else{
			              $style = "scores2";
			          }
			   ?>

		             <div class="<?php echo $style; ?>">
		                   <ul>
		             		 <?php
						                $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
						                if (file_exists($path_player_photos)) { 
						          ?>
			                	<li class="silueta"><img alt="<?php echo $data["player_common_name"]; ?>"
			                    src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg" /></li>
			                <?php } else { ?>
			                	<li class="silueta"><img alt="<?php echo $data["player_common_name"]; ?>"
			                    src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale30.gif" /></li>
			                <?php } ?>

		                       <li class="name">
		                       	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>"><?php echo $data["player_name_short"]; ?></a>
		                       </li>
		                       <li class="position"><?php echo $data["player_position"]; ?></li>
		                       <li class="teamflag" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["country_id"]; ?>.png') no-repeat scroll left center transparent;">
		                            <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>"><?php echo $data["team_name"]; ?></a>
		                       </li>
		        
		                   </ul>
		             </div>
			<?php $i++; } ?>
 
