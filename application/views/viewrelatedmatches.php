<?php require_once 'seourlgen.php'; ?>
 <?php $config = Zend_Registry::get ( 'config' );
    $offset = $config->time->offset->daylight; ?>
  
<?php $urlGen = new SeoUrlGen(); ?> 
<?php 
  if($this->othermatches != null ) {
      $temp = '999';
?>  
    <?php foreach($this->othermatches as $match) :
          if(trim($temp) !=  trim($match["mdate"])){ ?>
		   <?php if($this->selectedRestriction == 'tomorrow' or $this->selectedRestriction == '3' or $this->selectedRestriction == 'week'){
                   if(trim($match["league"]) == 8 or trim($match["league"]) == 43 or trim($match["league"]) == 45 or trim($match["league"]) == 70 ){
                                echo "Schedule information is not available due to restrictions by the league";
                                break ;
                                }
                   }
           ?>   	
           <span style="font-weight: bold;">
               <?php echo date ('l - F j , Y' , strtotime($match['mdate']));?>
           </span>

    <?php } ?>
    <ul>
        <li <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?> class="TeamName Winner">
                  <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["teama"]);?></a>
                    <?php }else { ?>
                  class="TeamName">
                  <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["teama"]);?></a>
                <?php  } ?>
        </li>
        <li class="Score"><?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["fs_team_a"]);?>
                -
                <?php echo $this->escape($match["fs_team_b"]);?></a>
              <?php }else {  ?>
                  <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
                <?php  }  ?>
        </li>
        <li <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>
                  class="TeamName Winner">
                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo "<strong>" . $this->escape($match["teamb"]) ."</strong>";?></a>
                  <?php }else { ?>
                 class="TeamName">
                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["teamb"]);?></a>
              <?php } ?>
        </li>
        <li class="Time GameOn"><?php if($match["status"] == 'Played'){ ?>
                 <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
              <?php }else if($match["status"] == 'Playing'){  ?>
                      <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing</a>
                  <?php }else if($match["status"] == 'Suspended'){  ?>
                      Suspended
                  <?php } else if($match["status"] == 'Fixture'){ ?> 
             <a href="<?php echo $urlGen->getMatchPageUrl($match['competition_name'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
             			<span id="matchHour_<?php echo $match["matchid"] ?>">
             				<?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])); ?>
             			</span>
             </a>
         <?php } ?>
       </li>
    </ul>


    <!-- End of scoreboards-->
    <?php $temp = $match["mdate"];
            endforeach;?>

<?php 
    }else {
    	if($this->selectedRestriction == 'today'){
        	echo 'No related matches played';
    	}else if ($this->selectedRestriction == '3' or $this->selectedRestriction == 'week'){
    		echo 'No related matches scheduled';
    	}
      }
 ?>