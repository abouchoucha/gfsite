<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php $config = Zend_Registry::get ( 'config' );
       $root_crop = $config->path->crop;
?>  

<div id="randomprofiles" class="nmatch">
                       			    
<?php
    if ($this->totalUserFriends > 0) {
        $userCounter = 0;
        foreach($this->UserFriendProfiles as $profile) {
          $userCounter++; ?>

            <?php if($userCounter==1){  ?>
                <div class="imgs imgs2">
            	<?php }  ?>
        
      	    	 <p class="<?php if($userCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">
      	            <a style="color:#FFFFFF;" href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" itle="<?php $profile["nickname"]; ?>">
      	              <?php echo $profile["nickname"];?> 
      	            </a>
      	              <a href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" title="<?php echo $profile["nickname"]; ?>">
      	                 <?php if ($profile["main_photo"] != null) { ?> 
      	                      <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/photos/<?php echo $profile["main_photo"];?>" alt="<?php echo $profile["nickname"];?>"/>
      	                 <?php } else  { ?>
      	                      <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/ProfileMale.gif" alt="<?php echo $profile["nickname"];?>"/>
      	                <?php } ?>
      	              </a>
      	              <a style="color:#01395B;font-weight:bold;" href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" title="<?php echo $profile["nickname"] ?>">
      	                <span><?php echo $profile["location"];?></span>
      	              </a>
      	          </p>
          
             <?php if($userCounter==3 OR $this->totalUserFriends < 3){ $userCounter = 0; ?>
                  </div>
              <?php } ?>

          <?php } ?>
      
   <?php } else { ?>
   
   
        <?php echo "no friend"; ?>  
   


   <?php } ?>
</div>
