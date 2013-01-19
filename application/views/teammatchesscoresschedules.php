<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>           
<?php $session = new Zend_Session_Namespace('userSession');?>


 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_full_scoreboard.phtml'); ?>
 

 
 <?php if (count($this->paginator) < 1){
	
 	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No matches scheduled.</div>";
	
 } else {  ?> 
      <?php 
      $i = 1;
      $roundid = null;
      $temp1 = 'league';
      $temp2 = 'date';
      foreach($this->paginator as $match) { 
          if($i % 2 == 1) {
                $style = "rowodd";
          }else{
                $style = "roweven";
          }  
	?>    
	 <?php
                $config = Zend_Registry::get ( 'config' );
                $path_team_logos_teama = $config->path->images->teamlogos . $match['cteama'].".gif" ;
                $path_team_logos_teamb = $config->path->images->teamlogos . $match['cteamb'].".gif" ;

                // team A logo
                if (file_exists($path_team_logos_teama)) { 	 
                	$small_logo_url_teama  = Zend_Registry::get("contextPath")."/utility/imagecrop?w=18&h=18&zc=1&src=".$this->root_crop."/teamlogos/".$match['cteama'].".gif"; 		
                } else {  
					$small_logo_url_teama  = Zend_Registry::get("contextPath")."/utility/imagecrop?w=18&h=18&zc=1&src=".$this->root_crop."/TeamText80.gif";
          	    }  
          	    // team B logo
          	     if (file_exists($path_team_logos_teamb)) { 	 
                	$small_logo_url_teamb  = Zend_Registry::get("contextPath")."/utility/imagecrop?w=18&h=18&zc=1&src=".$this->root_crop."/teamlogos/".$match['cteamb'].".gif"; 		
                } else {  
					$small_logo_url_teamb = Zend_Registry::get("contextPath")."/utility/imagecrop?w=18&h=18&zc=1&src=".$this->root_crop."/TeamText80.gif";
          	    }  
 
     ?>	
		   <div class="<?php echo $style;?>"> 
        	<ul>
            <li class="matchdate">
               <?php echo date ('M d, Y' , strtotime($match['mdate']));?>
            </li>
            <?php if($this->escape($match["teama"]) == ''){ ?>
              <li class="teama" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/tbd.gif') no-repeat left center;">  
                TBD
              </li>
            <?php }else {  ?>
            
            <li class="teama <?php if(trim($match["winner"]) == trim($match["cteama"])){ ?>winner<?php  } ?>" 
            style="background:url('<?php echo $small_logo_url_teama;?>') no-repeat left center;">
            
              <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                <?php echo $this->escape($match["teama"]) ;?>
              </a>
              
            </li>
            <?php } ?>          
            <li class="timescore">
                <?php if((trim($match["status"]) == 'Played') OR (trim($match["status"]) == 'Playing')) { ?>
                  <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                    <?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>
                  </a> 
                <?php }else {  ?>
                    <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">vs</a>
  	         <?php  }  ?>
            </li>
            
            <?php if($this->escape($match["teamb"]) == ''){ ?>
              <li class="teamb" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/tbd.gif') no-repeat left center;">  
                TBD
              </li>         
            <?php } else { ?>
              <li class="teamb <?php if(trim($match["winner"]) == trim($match["cteamb"])){ ?>winner<?php  } ?>" 
              style="background:url('<?php echo $small_logo_url_teamb;?>') no-repeat left center;">
                  <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                    <?php echo $this->escape($match["teamb"]);?>
                  </a>
              </li>
            <?php  }  ?>
            <li class="matchdetails">
              <?php if($match["status"] == 'Played'){ ?>
               <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Final</a>
              <?php }else if($match["status"] == 'Playing'){  ?>
      		      <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                  Playing <?php echo $match["match_minute"];?>
                </a>
      	     <?php }else if($match["status"] == 'Suspended'){  ?>
      		      Suspended
      	     <?php } else if($match["status"] == 'Fixture'){  ?>
      	  		 <a href="<?php echo $urlGen->getMatchPageUrl($match["league"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">	                	
	                <?php echo date('H:i',strtotime($match['mdate'] ." ". $match['time'])) ?>
	                               	             
                </a>
              <?php  } ?>
            </li>
        </ul>
    </div>     
		     

	<?php $i++; } ?>
	
	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_full_scoreboard.phtml'); ?> 
	 
<?php  } ?>   