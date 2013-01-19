<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalfacefb.css' rel="stylesheet" type="text/css" media="screen"/> 	




<div class="fbcontainer">
<!--only show banner for copa america-->
<?php if($this->seasonId == 5784){  ?>	
	<div class="mmid" style="margin-botton:20px;">			
			<div class="march" style="border:none;">
				<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/fb_copaamerica_banner.png">
			</div>
	</div>
<?php } elseif($this->seasonId == 5985) {  ?>
	<div class="mmid" style="margin-botton:20px;">			
			<div class="march" style="border:none;">
				<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/fb_copadeoro_banner.png">
			</div>
	</div>
<?php } elseif($this->seasonId == 6131) {  ?>
	<div class="mmid" style="margin-botton:20px;">			
			<div class="march" style="border:none;">
				<img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/fb_uefacl_banner.png">
			</div>
	</div>
<?php } ?>
	<div class="mmid">			
		<div class="march">
		
            <?php
                      $config = Zend_Registry::get ( 'config' );
                      $teamCounter = 0;
                      foreach ($this->paginator  as $data) {
                       $teamCounter++;
                  ?>
                  <?php if($teamCounter==1){  ?>
                    <div class="imgs">
                  <?php }  ?>

                      <p class="<?php if($teamCounter==4){ echo "tfa1"; } else { echo "tfa"; } ?>">
            
                          <a target="-blank" href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                             <?php 
                                $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;   
                                if (file_exists($path_team_logos)) { ?> 
                                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif" alt="<?php echo $data['team_name'];?>"/>
                              <?php } else { ?>
                                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif" alt="<?php echo $data['team_name'];?>"/>
                              <?php } ?>
                          </a>
                          <a target="_blank" href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                            <?php echo $data['team_name'];?>
                          </a>
                      </p>

                       <?php
                          if($teamCounter==4){
                            $teamCounter = 0;
                            echo '</div>';
                          }
                       ?>

                   <?php } ?>   
      
    </div>
  </div>
  
  <div class="">
	 <?php if($this->seasonId == 9999){  ?>	
	  	<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js#appId=138437206228611"></script>
		<script>
		FB.init({
		appId : '138437206228611',
		status : true,
		cookie : true,
		xfbml : true
		});
		</script>
		<fb:comments width="480"></fb:comments>
	   <?php } ?>
  </div>  
</div>



