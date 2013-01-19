 <?php $config = Zend_Registry::get ( 'config' );
    $offset = $config->time->offset->daylight; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>

<?php if (count($this->matchesresult) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No matches currently scheduled.</div>";
				}else{  ?> 
	<?php foreach($this->matchesresult as $match){ ?>
        <span>
               <?php echo date ('l - F j , Y' , strtotime($match['mdate']));?> (<?php echo $match['competition'];?>)
        </span>
        
        <ul>
        	<?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
	            <li <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?> class="TeamName Winner">
	              <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
	                <?php echo $this->escape($match["teama"]);?>
	              </a>
	                <?php }else { ?>  class="TeamName">
	              <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
	                <?php echo $this->escape($match["teama"]);?>
	              </a>
	                <?php  } ?>
	            </li>
            <?php }else {  ?>
            	<li class="TeamName">
            		<a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
            			<?php echo $this->escape($match["teama"]);?>
            		</a>
            	</li>
            <?php } ?>
            
            
             <li class="Score"><?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $this->escape($match["fs_team_a"]);?>
                -
                <?php echo $this->escape($match["fs_team_b"]);?></a>
              <?php }else {  ?>
                  <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
                <?php  }  ?>
            </li>
            
            <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
	             <li <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>
	                  class="TeamName Winner">
	                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo "<strong>" . $this->escape($match["teamb"]) ."</strong>";?></a>
	                  <?php }else { ?>
	                 class="TeamName">
	                <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo  $this->escape($match["teamb"]);?></a>
	              <?php } ?>
	            </li>
             <?php }else {  ?>
            	<li class="TeamName">
            		<a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
            			<?php echo $this->escape($match["teamb"]);?>
            		</a>
            	</li>
            <?php } ?>
            
            <li class="Time GameOn"><?php if($match["status"] == 'Played'){ ?>
                 <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>

              <?php }else if($match["status"] == 'Playing'){  ?>
                      <a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Playing</a>
                  <?php }else if($match["status"] == 'Suspended'){  ?>
                      Suspended
                  <?php } else if($match["status"] == 'Fixture'){ ?>
            		<a href="<?php echo $urlGen->getMatchPageUrl($match['competition'], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
						<?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])); ?>
             		</a>
            <?php } ?>
            </li>
        </ul>
        <p/>
    <?php } ?>
  <?php } ?>  
    
