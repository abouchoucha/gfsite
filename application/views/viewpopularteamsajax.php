<?php
$urlGen = new SeoUrlGen();?>

<?php require_once 'Common.php'; 
	$common = new Common();

   $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
    $session = new Zend_Session_Namespace('userSession');
 ?> 
<?php 
	$count=0;
	foreach($this->popularTeams as $team)
	{
		//print_r($team);
		if($count == 0 || $count==6)
		{	
	?>
			<div class="mailrow">
	<?php 
		}
	    $config = Zend_Registry::get ( 'config' );
	    $path_team_logos = $config->path->images->teamlogos . $team['team_id'].".gif" ;
		$itemClass = "mail1";
		if($count > 5)
		{
			$itemClass = "mail2";
		}
?>
	<p class="<?php echo $itemClass?>">
	<span class="mailimg">
	 <a id="team<?php echo $team["team_id"];?>profileLink" href="<?php echo $urlGen->getClubMasterProfileUrl($team['team_id'],$team['team_seoname'], true); ?>" title="<?php echo $team["team_name"] ?>">
			<?php 
			if ( file_exists($path_team_logos) ) 
			{ 
			?>
                   <img id="team<?php echo $team["team_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $team['team_id']?>.gif"/>
            <?php 
			} 
			else 
			{  
			?>
             <img id="team<?php echo $team["team_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText120.gif"/>
           <?php 
			} 
			?>
	</a>
	
	</span>
	<span class="mailname"> <?php echo $team["team_name"];?></span>
	<?php $teamName = $team["team_name"]; ?>
	<span class="mailsub"><a href="javascript:" onclick="subscribeToTeam(<?php echo $team["team_id"];?>, '<?php echo $teamName;?>');"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $team["team_name"];?>'s updates"></a></span>
	<span class="mailsub"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/rss/id/<?php echo $team["team_id"]; ?>"><img alt="Rss updates" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/feed_rss_small.png" />&nbsp;RSS Updates</a></span>
	
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