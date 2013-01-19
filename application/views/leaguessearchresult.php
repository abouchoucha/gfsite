		<?php  if (count($this->paginator) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No leagues/tournaments found with your search criteria.</div>";
				}else{
					echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_modal.phtml'); 
				?>
		<table class="BlueHeader" style="border:1px solid #6F9BBC;">
                
							            <tbody>
							                  <tr>
							                    <th>Competition Name</th>
							                    <th>Country</th>
							                    <th>&nbsp;</th>
							                  </tr>
							                  <?php  
															        	$i = 1;
															        	
							                                    		foreach ($this->paginator as $league) { 
								                                    		if($i % 2 == 1)
											  	         					  {
											 	              				    $style = "background-color:#FFFFFF;";
											 	              					
											 	         					  }else{
												              					$style = ""; 
											 	              					
												         					  }
								                                    			
							                                    			?> 
							                  <tr style="<?php echo $style;?>">
							                    <td><?php echo $league["competition_name"];?></td>
							                    <td><?php echo $league["country_name"];?></td>
							                    <?php if ($league["isyourleague"] == null){?>
							                    <td><a title="Add League" href="javascript:doAddLeague('<?php echo $league["competition_id"];?>*<?php echo $league["country_id"];?>','<?php echo $league["competition_name"];?>','<?php echo $league["country_name"];?>')">Add</a></td>
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