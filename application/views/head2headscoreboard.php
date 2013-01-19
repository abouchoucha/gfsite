<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 

<?php if(sizeof($this->paginator) > 0 ) {
	      $temp1 = 'league';
	      $temp2 = 'date';
?>	                                    
		<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml'); ?>
        <?php    
            $i = 1;
        	foreach($this->paginator as $match) { 
	          	if($i % 2 == 1) {
	                $style = "mar";
	          	} else{
	                $style = "feb";
	          	}  	
        ?>								
				<div class="<?php echo $style;?>">
					<ul>
						<li class="mar1">
							<?php if(trim($temp2) != trim($match["seasonId"]) . trim($match["mdate"])) {
      	                      	echo date ('M d, Y' , strtotime($match['mdate']));?>
      	                    <?php } else { ?>&nbsp;<?php } ?>
						</li>
						<li class="mar1"><?php echo $this->escape($match["competition_name"]);?></li>
						<li class="mar2">
							<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
								<?php echo $this->escape($match["teama"]) ;?>
							</a>
							<?php if($match["status"] == 'Played' || $match["status"] == 'Playing'){ ?>
                                &nbsp;<?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>&nbsp;
                                <?php } else { ?>
                                &nbsp; vs.
                                <?php } ?>
                                <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
								<?php echo $this->escape($match["teamb"]);?>
							</a>
						</li>					
						<li class="mar3">
							<?php if($match["status"] == 'Played'){ ?>
          	                  <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Details</a>
          	                <?php } else if($match["status"] == 'Playing'){  ?>
          	      		      <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
          		                  <?php echo $match["match_minute"];?>'
          		              </a>
          	      	        <?php } else if($match["status"] == 'Suspended'){  ?>
          	      		        Suspended
          	      	        <?php } else if($match["status"] == 'Fixture'){  ?>
          	      	  		     <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
          	                  		<?php echo date("H:i",strtotime($match["time"]));?>
          	                  	 </a>
          	                <?php } ?>
						</li>
					</ul>
				</div>													
		 <?php 
			    $temp1 = $match["league"] . $match["seasonId"] . $match["mdate"];   
			    $temp2 = $match["seasonId"] . $match["mdate"];
			    $i++;
			} 
		?> 
		<div class="btm">		
			<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml'); ?>
		</div>
<?php } else {
	  	echo 'No matches.';
	  }
?>		