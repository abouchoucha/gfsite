<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php 
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
?>


<div class="WrapperForDropShadow">
     <ul class="leftnavlist">
         <li class="<?php echo($this->playerMenuSelected == 'profile'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname); ?>">Profile</a>
         </li>             
         <li class="<?php echo($this->playerMenuSelected == 'activity'?'active':'noactive'); ?>">
             <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayeractivity/id/<?php echo $this->playerid; ?>">Player Activity</a>
         </li>
         <li class="<?php echo($this->playerMenuSelected == 'stats'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerstatsdetail/id/<?php echo $this->playerid; ?>">Player Statistics</a>
         </li> 
         <li class="<?php echo($this->playerMenuSelected == 'mates'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>">Teammates</a>
         </li>
         <li class="<?php echo($this->playerMenuSelected == 'fans'?'active':'noactive'); ?>">
            <?php if ($session->email != null) { ?>
            	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfans/id/<?php echo $this->playerid; ?>">Fans</a>
             <?php } else { ?>
           		<a href="javascript:loginModal();">Fans</a>
         	<?php }  ?> 
         </li>
         
         <li class="<?php echo($this->playerMenuSelected == 'shouts'?'active':'noactive'); ?>">
            <?php if ($session->email != null) { ?>
           	 	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayergoalshouts/id/<?php echo $this->playerid; ?>">Goooal Shouts</a>
            <?php } else { ?>
           		<a href="javascript:loginModal();">Goooal Shouts </a>
         	<?php }  ?> 
         </li>
         
         <!--<li class="<php echo($this->playerMenuSelected == 'pics'?'active':'noactive'); ?>">
            <a href="<php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<php echo $this->playerid; ?>/type/2">Pictures</a>
         </li>-->
         
     </ul>
</div>

