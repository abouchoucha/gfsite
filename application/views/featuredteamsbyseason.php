<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); 
$session = new Zend_Session_Namespace('userSession');
?> 

 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'data')); ?>

       <?php  
        // Retrive data from teams as a normal array
        $teamCounter = 0;
        foreach ($this->paginator as $data) {
          $teamCounter++;
          if($teamCounter==1){
         ?>
          <ul class="LayoutFourPicturesBig" style="float:left;">
              <?php } ?>              
               <li>
                    <a title="<?php echo $data['team_name'];?>" href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>">
                      <span><?php echo $data['team_name'];?></span>
                    </a>
                  
                  <?php
                   $config = Zend_Registry::get ( 'config' );
                    $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;
                  if (file_exists($path_team_logos)) { ?>
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>">
                        <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif"/>
                    </a> 
                  <?php } else {  ?>
                    <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>">
                       <img class="logo120" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
                    </a> 
                  <?php }   ?>
                  
                  <!-- Subscribe and Unsubscribe Button -->
                      <?php if ($session->email != null) { ?>
    					<?php if($session->userId == $data['user_id']) { ?>
    					     <a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    					<?php } else { ?>
    						 <a id="btn_team_on_<?php echo $data["team_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    						<a id="btn_team_off_<?php echo $data["team_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    						</a>
    					<?php } ?>
    					
    				<?php } else { ?>
    				     <a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $data["team_id"];?>, '<?php echo $data['team_name'];?>');">
    						<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["team_name"];?>'s updates">
    					</a>
    				<?php } ?>	
				
              </li>

          <?php 
            if($teamCounter==4){
              $teamCounter = 0;
              echo '</ul>';
            }
          ?>
        <?php } ?>
        </ul>

 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'data')); ?>

<br class="clearleft"/>
