<?php 
	$urlGen = new SeoUrlGen();
?>

            <?php {
            $rowcolor1 = '#F0F0F2';
	     				$rowcolor2 = '#FFFFFF';
 	     				// the background colors on mouseover
	     				$hovercolor1 = '#BAD4EB'; 	    
						$hovercolor2 = '#DCE9F4';
						 } 
						 if($this->players != null){
						?>
						
						
						<table class="small"  width="100%" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
							<tr>
        						<td bgcolor="#CCCCCC"><b>Player name</b></td>
						        <td bgcolor="#CCCCCC"><b>Club</b></td>
						        <td bgcolor="#CCCCCC"><b>Country</b></td>
						        <td bgcolor="#CCCCCC"><b>Position</b></td>
						        <td bgcolor="#CCCCCC" align="center"><b>Age</b></td>
					       </tr>
						 <?php $i = 1; 
						 	   $common = new Common();
						 	   //$cont = $this->pagination->getFrom();
						 ?>
						 
						 <?php foreach ($this->players as $data) 
						 	{
						 		  if($i % 2 == 1)
  	         					  {
 	              				    $style = $rowcolor1;
 	              					$hoverstyle = $hovercolor1;
 	         					  }else{
	              					$style = $rowcolor2; 
 	              					$hoverstyle = $hovercolor2;
	         					  }
						 ?>
      						<tr>
								<td bgcolor="<?php echo $style; ?>"><a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>"><?php echo $data["player_name_short"]; ?></a></td>
								<td bgcolor="<?php echo $style; ?>">&nbsp;&nbsp;&nbsp;<?php echo $data["team_name"]; ?></td>
								<td bgcolor="<?php echo $style; ?>"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["player_country"]; ?>.png">&nbsp;<?php echo $data["country_name"]; ?></td>
								<td bgcolor="<?php echo $style; ?>"><?php echo $data["player_position"]; ?></td>
								<td align="center" bgcolor="<?php echo $style; ?>"><?php echo $common->GetAge($data["player_dob"]); ?></td>
			     		   </tr>
						  <?php $i++;
								//$cont++; 
						 		} ?>		   
						</table>
						<?php
						}else {?>
               <label>No players added recently.</label>
            <?php }?>

                     
                   
