 <?php $session = new Zend_Session_Namespace('userSession');?>
 <script type="text/JavaScript">

jQuery(document).ready(function() {
	 jQuery('#addtofavoriteplayertrigger2').click(function(){
		 addfavoriteplayer();
 	 });
	 jQuery('#addtofavoriteteamtrigger2').click(function(){
         addfavoriteteam();
     });
	 jQuery('#addtofavoritematchtrigger2').click(function(){
         addfavoritematch();
     });
	 jQuery('#addtofavoritecompetitiontrigger2').click(function(){
		 addremovefavoritecompetition('add');
	});
});	 
</script>
 <?php require_once 'seourlgen.php'; ?>
 <?php $urlGen = new SeoUrlGen(); ?>     
 <?php 
 	  $userCounter = 0;
      foreach($this->randomprofiles as $profile) {
         $userCounter++;
  ?>  
  
   		<?php if($userCounter==1){  ?>
          <div class="imgs imgs2">
      	<?php }  ?>
  
	    	 <p class="<?php if($userCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">
	            <a style="color:#FFFFFF;" href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" title="<?php $profile["nickname"]; ?>">
	              <?php echo $profile["nickname"];?>
	            </a>
	              <a href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" title="<?php echo $profile["nickname"]; ?>">
	                 <?php if ($profile["picture"] != null) { ?> 
	                      <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $profile["picture"];?>" alt="<?php echo $profile["nickname"];?>"/>
	                 <?php } else  { ?>
	                      <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" alt="<?php echo $profile["nickname"];?>"/>
	                <?php } ?>
	              </a>
	              <a style="color:#01395B;font-weight:bold;" href="<?php echo $urlGen->getUserProfilePage($profile["nickname"],True);?>" title="<?php echo $profile["nickname"] ?>">
	                <span><?php echo $profile["countrylive"];?></span>
	              </a>
	          </p>
    
       <?php if($userCounter==3 ){ 
       	     $userCounter = 0; ?>
       	
            </div>
        <?php } ?>
        
      <?php } ?>
    

