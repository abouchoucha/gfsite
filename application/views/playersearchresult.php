			
			<?php  if (count($this->paginator) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No players found with your search criteria.</div>";
					}else{
					echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_modal.phtml'); 
					?>
			<table class="BlueHeader" style="border:1px solid #6F9BBC;">
                
            <tbody>
                  <tr>
                    <th>Player Name</th>
                    <th>Club</th>
                    <th>Country</th>
                    <th>Position</th>
                    <th>&nbsp;</th>
                  </tr>
                  <?php  
								        	$i = 1;
								        	
                                    			foreach ($this->paginator as $player) { 
	                                    		if($i % 2 == 1)
				  	         					  {
				 	              				    $style = "background-color:#FFFFFF;";
				 	              					
				 	         					  }else{
					              					$style = ""; 
				 	              					
					         					  }
	                                    			
                                    			?> 
                  <tr style="<?php echo $style;?>">
                    <td><?php echo $player["player_name_short"];?></td>
                    <td><?php echo $player["team_name"];?></td>
                    <td><?php echo $player["country_name"];?></td>
                    <td><?php echo $player["player_position"];?></td>
                    <?php if ($player["isyourplayer"] == null){?>
                    <td><a title="Add Player" href="javascript:doAddPlayer('<?php echo $player["player_id"];?>','<?php echo $player["player_name_short"];?>')">Add</a></td>
                    <?php } else {?>
                    <td>Added</td>
                    <?php }?>
				 </tr>
                     
                     <?php 
											$i++;	
												}
								         
						?>					
		                                    
                        
                </tbody>
                 </table>               
                 
                 <?php }?>
