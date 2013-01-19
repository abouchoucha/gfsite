<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();?>

<?php require_once 'Common.php'; 
	$common = new Common();

   $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
    $session = new Zend_Session_Namespace('userSession');
 ?> 
<?php
	$count = 0; 
	foreach($this->popularPlayers as $player)
	{
		//print_r($player);
	if($count == 0 || $count==6)
		{	
	?>
			<div class="mailrow">
	<?php 
		}
		$itemClass = "mail1";
		if($count > 5)
		{
			$itemClass = "mail2";
		}
?>
	<p class="<?php echo $itemClass?>">
	<span class="mailimg">
	 <a id="player<?php echo $player["player_id"];?>profileLink" href="<?php echo Zend_Registry::get("contextPath"). $urlGen->getPlayerMasterProfileUrl("", "", "", $player["player_id"], true, $player["player_common_name"]); ?>" title="<?php echo $player["player_common_name"] ?>">
                                                                
         <?php
	           $path_player_photos = $config->path->images->players . $player["player_id"] .".jpg" ;
	           if (file_exists($path_player_photos)) 
	           { 
	     ?>                   

            <img id="player<?php echo $player["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $player["player_id"]?>.jpg" alt="<?php echo $player["player_common_name"]; ?> Profile Image"/>
                          
          	 <?php 
			} 
			else 
			{  
			?>
                <img id="player<?php echo $player["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>
          <?php 
			} 
			?>		
		  </a>
	</span>
	<span class="mailname"> <?php echo $player["player_name_short"];?></span>
	<span class="mailsub"><a href="javascript:" onclick="subscribeToPlayer(<?php echo $player["player_id"];?>);"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $player["player_name_short"];?>'s updates"></a></span>
	<span class="mailsub"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $player["player_id"]; ?>"><img alt="Rss updates" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a></span>
</p>
<?php 
		$count++;
	if($count==6 || $count == 12)
		{
?>
		</div>
<?php 
		}
	}
?>