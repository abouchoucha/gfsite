<?php require_once 'seourlgen.php'; ?>
 <?php $config = Zend_Registry::get ( 'config' );
    $offset = $config->time->offset->daylight; ?>
<?php $urlGen = new SeoUrlGen(); ?>

<script type="text/javascript">
$(document).ready(function(){
	
	$("span[id^='matchHour_']").each(function() {
	  var date_time = $(this).text();
	  var date = new Date(date_time);
	  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
	  $(this).text(formatDate(date_time_os, 'HH:mm'));	  
	});
	
});

</script>                      
 <?php //if($this->matches != null ) { 
      $temp1 = 'league';
      $temp2 = 'date';
  ?>

<?php foreach($this->nrmatches as $nrmatch) { ?>

  <div class="DropShadowHeader BrownGradientForDropShadowHeader">
    <div class="DownArrow" onclick="toggleCompetition('<?php echo str_replace(" ","",$this->escape($nrmatch["cname"]));?>',this);"></div>
      <h4 class="WithArrowToLeft WithCountryFlag" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $this->escape($nrmatch["country"])?>.png')">
        <a href="<?php if($nrmatch["country"] > 7)
        	 { 
        	 	echo $urlGen->getShowDomesticCompetitionsByCountryUrl($nrmatch["cname"],$nrmatch["country"], true); 
        	 } else { 
        	 	switch ($nrmatch["country"]) {
    			    case 1:
    			        echo $urlGen->getShowRegionUrl(strval('International'), True);
    			        break;
    			    case 2:
    			        echo $urlGen->getShowRegionUrl(strval('asian'), True);
    			        break;
    			    case 3:
    			        echo $urlGen->getShowRegionUrl(strval('african'), True);
    			        break;
    			    case 5:
    			        echo $urlGen->getShowRegionUrl(strval('americas'), True);
    			        break;
    			    case 7:
    			        echo $urlGen->getShowRegionUrl(strval('european'), True);
    			        break;    
    			}
        	 		
        	 }   ?>">        
            <?php echo $this->escape($nrmatch["cname"]);?>    
        </a> 
      </h4>

      <span>(<?php echo $this->escape($nrmatch["matchescount"]);?>
           <?php echo $this->escape($nrmatch["matchescount"])>1?"matches)":"match)";?>
      </span> 
  </div>
 

  <div class="Scores" id="<?php echo str_replace(" ","",$this->escape($nrmatch["cname"]));?>">
    <?php if ($nrmatch["matchescount"] > 0 ){ 
       foreach($this->matches as $match) { 
       	
      if(trim($match["country"]) == trim($nrmatch["country"])){ ?>

      <?php	if(trim($temp2) != trim($match["country"]) . trim($match["mdate"])){ 
        echo "<strong>" . date ('l - F j , Y' , strtotime($match['mdate'])). "</strong>";?>
      <?php } ?>
      <p></p>                                    
        <?php if(trim($temp1) != trim($match["league"]) . trim($match["country"]). trim($match["mdate"])){ ?>
          <a class="scoreLeagueTitle" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["league"], True); ?>"><strong><?php echo $this->escape($match["competition_name"]);?></strong></a>
       <?php } ?> 
         
      <ul id="GameDetails">
      
        <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?>
        <li class="TeamName Winner">
          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
            <strong><?php echo $this->escape($match["teama"]);?></strong>
          </a>
        </li>
            <?php }else { ?>
         <li class="TeamName">
            <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                <?php echo $this->escape($match["teama"]) ;?>
            </a>
         </li>
            <?php  } ?>
      
      
      
        <li class="Score">
          <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
        <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["fs_team_a"]);?>
          - <?php echo $this->escape($match["fs_team_b"]);?></a> 
        <?php }else {  ?>
  					 <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
  	    <?php  }  ?>
        </li>
        
        
        <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>
        <li class="TeamName Winner">
          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
            <strong><?php echo $this->escape($match["teamb"]);?></strong>
          </a>
        </li>
        <?php }else { ?>
        <li class="TeamName">
          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
            <?php echo $this->escape($match["teamb"]) ;?>
          </a>
        </li>
        <?php } ?> 
     
      <li class="Time GameOn">
      <?php if($match["status"] == 'Played'){ ?>
         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
      <?php }else if($match["status"] == 'Playing'){  ?>
		      <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
		      <?php echo $match["match_minute"];?> '</a>
	  <?php }else if($match["status"] == 'Postponed'){  ?>
		      Postponed
	  <?php } else if($match["status"] == 'Fixture'){  ?>
	  		<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
	  			<?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>  	
	  		</a>
      <?php  } ?>
     </li>
     
     <?php if ($this->includeMatchDetailsLinks == 'y'){?>
     <li class="MatchDetails">
     <?php if($this->actionTab == 'scoresTab'){ ?>
     	<?php if($match["status"] == 'Played'){ ?>
         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match details &raquo;</a>
        <?php }else if($match["status"] == 'Playing'){ ?>
     	   <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing &raquo;</a>
        <?php }else if($match["status"] == 'Played'){  ?>
      	   <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match details &raquo;</a>
        <?php }else if($match["status"] == 'Fixture'){  ?>
          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match preview &raquo;</a>
      <?php } 
     	}else {?>
     	    <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match preview &raquo;</a>
     	<?php } 
       } //end includematchdetailsLinks
      ?>
	</li>
   </ul>
<p></p>  
      
     <?php 
     $temp1 = $match["league"] . $match["country"] . $match["mdate"];   
     $temp2 = $match["country"] . $match["mdate"];
     } 
    } //end foreachmatches 
     ?> 
     <?php }else {
			echo 'No matches scheduled. <a href="#tab3">Click Here to See Scheduled Games</a>';
		}?>                                 
  </div>
  
<?php } ?>
                           
