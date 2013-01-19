<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 

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
