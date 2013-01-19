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
	$count=0;
	foreach($this->popularLeagues as $league)
	{
		//print_r($league);
	if($count == 0 || $count==6)
		{	
	?>
			<div class="mailrow">
	<?php 
		}
	    $config = Zend_Registry::get ( 'config' );
        $path_comp_logos = $config->path->images->complogos . $league['competition_id'].".gif" ;
		$itemClass = "mail1";
		if($count > 5)
		{
			$itemClass = "mail2";
		}
?>
	<p class="<?php echo $itemClass?>">
	<span class="mailimg">
	 <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($league["competition_name"], $league["competition_id"], True); ?>" title="<?php echo $league["competition_name"] ?>">
			<?php 
			if ( file_exists($path_comp_logos) ) 
			{ 
			?>
             	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/competitionlogos/<?php echo $league['competition_id']?>.gif"/>
            <?php 
			} 
			else 
			{  
			?>
                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif"/>
           <?php 
			} 
			?>
	</a>
	
	</span>
	<span class="mailname"> <?php echo $league["competition_name"];?></span>
	<?php $leagueName = $league["competition_name"]; ?>
	<span class="mailsub"><a href="javascript:" onclick="subscribeToLeague(<?php echo $league["competition_id"];?>, '<?php echo $leagueName;?>');"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $league["competition_name"];?>'s updates"></a></span>
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
