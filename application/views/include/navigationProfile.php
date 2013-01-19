<?php $session = new Zend_Session_Namespace('userSession');?>
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();
?>

     <ul class="leftnavlist">
         <li class="<?php echo($this->profileMenuSelected == 'profile'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo($session->isMyProfile == 'n'? $this->currentUser->screen_name:$session->screenName)?>">Profile</a>
         </li>
         <li class="<?php echo($this->profileMenuSelected == 'activity'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity<?php echo($session->isMyProfile == 'n'?'/username/'. $this->currentUser->screen_name:'')?>">Activity</a>
         </li>
         <li class="<?php echo($this->profileMenuSelected == 'friends'?'active':'noactive'); ?>">
         <?php if ($session->isMyProfile == 'y'){?>
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends">Friends</a>
            <?php }else {?>
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallhisfriends/id/<?php echo $session->currentUser->user_id;?>">Friends</a>
            <?php }?>
         </li>
         <li class="<?php echo($this->profileMenuSelected == 'favorities'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities<?php echo($session->isMyProfile == 'n'?'/username/'. $this->currentUser->screen_name:'')?>">Favorites</a>
         </li>
         <li class="<?php echo($this->profileMenuSelected == 'shouts'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallgoalshouts<?php echo($session->isMyProfile == 'n'?'/username/'. $this->currentUser->screen_name:'')?>">Goooal Shouts</a>
         </li>
     </ul>
