<?php require_once 'seourlgen.php'; 
	$urlGen = new SeoUrlGen();
	$config = Zend_Registry::get ( 'config' );
	$server = $config->server->host;
	?>
<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<div class="profilee1">
    <p class="imggg1">
      <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->teamid,$this->teamseoname, True); ?>" title="<?php echo $this->teamname;?>">
       <?php
                $config = Zend_Registry::get ( 'config' );
                $path_team_logos = $config->path->images->teamlogos . $this->teamid .".gif" ;
                if (file_exists($path_team_logos))
            { ?>
              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->teamid ; ?>.gif"/>
          <?php } else {  ?>
              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText.gif"/>
          <?php }  ?>
      </a>
    </p>
    
    <p class="posit">
      <?php if($this->teamtype=='club') {  ?> 
      
      	Country: &nbsp;
      	<a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryname,$this->countryid, true); ?>">
           	<?php echo $this->countryname; ?>
      	</a>
      	<br>
      	League: &nbsp;
      	<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->domesticleaguename, $this->domesticleagueid, True); ?>">
      		<?php echo $this->domesticleaguename;?>
      	</a>

      <?php } else { ?>
      
      	Region: &nbsp;
         <a href="">
           	<?php echo $this->regionname; ?>
      	</a>
      	
      <?php }  ?>
    </p>
    
    
<!--
     <?php //$i = 1;
          	//foreach ($this->competitionactive as $data) { 
     ?> 
     	<?php //if($data['format']=='Domestic league') {  ?> 
     		<p class="posit">League: &nbsp;<a href="<?php //echo $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True); ?>">
     			<?php //echo $data["competition_name"]; ?></a>
     		</p>
     	<?php //} else { ?> 
     		<p class="posit"><a href="<?php //echo $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True); ?>">
     			<?php //echo $data["competition_name"]; ?></a>
     		</p>
     	<?php //}  ?> 
     
     <?php //}  ?> 
    
   -->
   
   
   <!-- <p class="im1">Add to Favorites</p>
    <p class="im2">Remove from Favorites</p>-->
    <p class="imfb" ><script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script><a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank">Share on Facebook</a></p>
    <p class="im3"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/rss/id/<?php echo $this->teamid; ?>">Subscribe to Updates</a></p>
     
     <p class="im4">
     	<a id="selectt1" href="#">Head-to-Head Challenge</a>
     </p>
     
     <p class="im5">
     
			<!-- AddToAny BEGIN -->
			<a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=http%3A%2F%2Fwww.goalface.com&amp;linkname=GoalFace">Share This Profile</a>
			<script type="text/javascript">
			var a2a_config = a2a_config || {};
			a2a_config.linkname = "GoalFace - <?php echo $this->teamname;?> Profile";
			a2a_config.linkurl = "<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>";
			a2a_config.onclick = 1;
			a2a_config.num_services = 6;
			a2a_config.prioritize = ["facebook", "twitter", "yahoo_bookmarks", "digg", "google_bookmarks", "stumbleupon"];
			</script>
			<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
			<!-- AddToAny END -->

    </p>
    <?php //include 'teamh2h.php';?>
</div>