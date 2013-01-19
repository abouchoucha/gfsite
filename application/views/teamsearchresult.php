		<?php  if (count($this->paginator) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No teams found with your search criteria.</div>";
				}else{
					echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_modal.phtml'); 
				?>
			<table class="BlueHeader" style="border:1px solid #6F9BBC;">
                
							            <tbody>
							                  <tr>
							                    <th>Team Name</th>
							                    <th>Country</th>
							                    <th>&nbsp;</th>
							                  </tr>
							                  <?php  
															        	$i = 1;
															        		foreach ($this->paginator as $team) { 
								                                    		if($i % 2 == 1)
											  	         					  {
											 	              				    $style = "background-color:#FFFFFF;";
											 	              					
											 	         					  }else{
												              					$style = ""; 
											 	              					
												         					  }
								                                    			
							                                    			?> 
							                  <tr style="<?php echo $style;?>">
							                    <td><?php echo $team["team_name"];?></td>
							                    <td><?php echo $team["country_name"];?></td>
							                    <?php if ($team["isyourteam"] == null){?>
							                    <td><a title="Add Team" href="javascript:doAddTeam('<?php echo $team["team_id"];?>','<?php echo $team["team_name"];?>')">Add</a></td>
							                    <?php } else {?>
							                    <td>Added</a></td>
							                    <?php }?>
											 </tr>
							                     
							                     <?php 
																		$i++;	
																			}
															         
																		
									   				?>                                 
							                        
							                </tbody>
                				 </table>  
                              <?php }?>  