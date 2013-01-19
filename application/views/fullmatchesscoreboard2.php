<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 



 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div.phtml'); ?> 
                      
<div id="ScorecardContainer">
  <?php if(sizeof($this->paginator) > 0 ) { 
      $temp1 = 'league';
      $temp2 = 'date';
  ?>  

  <div class="Scores" id="">
    <?php 
    	foreach($this->paginator as $match) { ?>
		<?php if(trim($temp2) != trim($match["seasonId"]) . trim($match["mdate"])){ 
        echo "<strong>" . date ('l - F j , Y' , strtotime($match['mdate'])). "</strong>";?>
      <?php } ?>
      <p></p>                                    
         
      <ul>
      
        <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?>
        <li class="TeamName Winner">
          <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teamaseoname"], True); ?>">
            <strong><?php echo $this->escape($match["teama"]);?></strong>
          </a>
        </li>
            <?php }else { ?>
         <li class="TeamName"><a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteama"], $match["teamaseoname"], True); ?>"><?php echo $this->escape($match["teama"]) ;?></a></li>
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
          <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teambseoname"], True); ?>">
            <strong><?php echo $this->escape($match["teamb"]);?></strong>
          </a>
        </li>
        <?php }else { ?>
        <li class="TeamName">
          <a href="<?php echo $urlGen->getClubMasterProfileUrl($match["cteamb"], $match["teambseoname"], True); ?>"><?php echo $this->escape($match["teamb"]) ;?></a>
        </li>
        <?php } ?> 
     
      <li class="Time GameOn">
      <?php if($match["status"] == 'Played'){ ?>
         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
      <?php }else if($match["status"] == 'Playing'){  ?>
		      <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $match["match_minute"];?>'</a>
	  <?php }else if($match["status"] == 'Suspended'){  ?>
		      Suspended
	  <?php } else if($match["status"] == 'Fixture'){  ?>
	  		<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo date("H:i",strtotime($match["time"]));?></a>
      <?php  } ?>
     </li>
     <?php if ($this->includeMatchDetailsLinks == 'y'){?>
     <li class="MatchDetails">
     <?php if($this->actionTab == 'scoresTab'){ ?>
     	<?php if($match["status"] == 'Played'){ ?>
         <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match details &raquo;</a>
        <?php }else if($match["status"] == 'Playing'){ ?>
     	   <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">match details &raquo;</a>
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
     $temp1 = $match["league"] . $match["seasonId"] . $match["mdate"];   
     $temp2 = $match["seasonId"] . $match["mdate"];
      
    } //end foreachmatches 
     ?>                                  
  </div>
<?php 
 }else {
  echo 'No matches scheduled.';
}    ?>       
</div> 
                           
