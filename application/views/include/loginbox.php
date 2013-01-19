<?php $session = new Zend_Session_Namespace('userSession');?> 
 <?php require_once 'seourlgen.php'; require_once 'Common.php';?>
<?php $urlGen = new SeoUrlGen(); 
	  $common = new Common();	?>   
<?php $config = Zend_Registry::get ( 'config' );
       $root_crop = $config->path->crop;
?> 
 
<div id="ProfileSignIn">
   <?php //if ($session->mainPhoto!=null) { ?>
   
   
    <?php if ($session->fbuser == null) { ?>
		<a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">
			<?php if($common->startsWith($session->mainPhoto , '<img') || $session->mainPhoto==""){ ?>
				<img style="float: left; height: 30px; padding-right: 10px; width: 30px;" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ProfileMale30.gif">
			<?php } else {?>
				<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=30&h=30&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/photos/<?php echo $session->mainPhoto;?>" /> 
			<?php }?>
		</a>
    <?php } else {  ?>
		<a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">     		
			<?php $result = $session->fbuser; ?>
				<img src="https://graph.facebook.com/<?php echo $result['id'];//$session->duser; ?>/picture">
		</a>
    <?php } ?>

	<h3>Hello <a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>"><?php echo $session->screenName;?></a></h3>
		<?php
		$result = $session->fbuser['email'];	  	 
		if($result!=null){
			$logout = $session->logoutUrl;
			echo '<a href="'.$logout.'">Sign Out</a>';
		}else{?>
			<a href="<?php echo Zend_Registry::get("contextPath"); ?>/sign-out" title="Sign Out">Sign Out</a>
		<?php }?>
    <div>
        My Profile:  <a href="<?php echo $urlGen->getUserProfilePage($session->screenName,True);?>">View</a> | <a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">Edit</a><br>
    </div>

</div>