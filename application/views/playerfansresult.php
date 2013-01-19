<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen();
require_once 'Common.php'; 
$common = new Common(); ?>  
 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_grid_list.phtml',array('ajaxdata'=>'ajaxdata','type'=>$this->type)); ?>
                				
                					<div id="listDisplayFriends">	
                						<?php   $cont = 1;
											    foreach ($this->paginator as $data) { 
									     ?> 	
									     	<ul class="FavoritePlayers">
    											  	<li>
    											  		
    										           <a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['nickname'];?>" href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>">
    										    			<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo ($data['picture']!=null || $data['picture']!=''? $data['picture'] :"ProfileMale.gif"); ?>" />
    										    		</a>
    												  </li>
    												  <li>
    												  	<h3><a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data["nickname"];?>"><?php echo $data["nickname"];?></a></h3>
    												  	
    													<?php echo $data['location'];?><br>
    												  <?php echo $data['numfriends'];?> Friend(s)<br>
    												    Member Since: <?php echo date ('M Y' , strtotime($data['registration_date']));?><br>
		                            					Last Updated: <?php echo $common->convertDates($data['date_update'])?><br>
		                            					Rating: <?php printf ("%01.2f", $data['rating']!=null?$data['rating']:'0') ;?> (<?php echo $data['total_votes'];?>)<br>
    												    <input type="hidden" id="cityfan<?php echo $data["userId"];?>" value="<?php echo $data["city_live"];?>">
    												    <input type="hidden" id="joinedfan<?php echo $data["userId"];?>" value="<?php echo date( 'M Y', strtotime ( $data["registration_date"]) );?>">
    												 <li class="ViewProfile">
    												   <a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data["nickname"];?>">
    												   View Profile
    												   </a>
    												       
    												      <?php if ($session->userId != $data["userId"]){ ?>
    												      <br> 
    											      	   
    												       	
    											       		<?php if($data["isfriend"] == 'n'){
                                          							if ($session->email == null) { ?>
    													       			<input id="addtofriendsNotLoggedtrigger" onclick="loginModal()" class="submit" type="button" value="Add as a friend" style="display: inline;"/>
    											              <?php } else { ?>
    										                          <input id="addtofriendsLoggedtrigger" onclick="addFriends('<?php echo $data["userId"];?>' ,'<?php echo $data["nickname"];?>' , '<?php echo $data["picture"];?>')" class="submit" type="button" value="Add as a friend" style="display: inline;"/>
    										                  <?php  } ?>
    																
    												       	<?php } else { ?>
    												       			<input id="removefromfriendsLoggedtrigger" onclick="removeFriends('<?php echo $data["userId"];?>' ,'<?php echo $data["nickname"];?>' , '<?php echo $data["picture"];?>')" class="submit" type="button" value="Remove from friends" style="display: inline;"/>
    										                    </br>
    												       	<?php } ?>
    											       	  
    											       	   
    											       		
    											       		
    											       		<?php }?>
    											
    											 	</li>
    											</ul>
									     
                						<?php }?>
                					</div>
                					
                					<div id="gridDisplayFriends" class="closeDiv">
                						<?php  
	                                      // Retrive data from teams as a normal array
	                                      $userCounter = 0;
	                                      foreach ($this->paginator as $data) {
	                                        $userCounter++;
	                                        if($userCounter==1){
	                                            ?>
	                                            <ul class="LayoutFourPictures">
	                                        <?php } ?>
	                                            
	                                            <li>
                                              		<a  href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>"><?php echo $data['nickname'];?></a>
                              						<br/>
                              						<a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['nickname'];?>" href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>">
    								            		<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo ($data['picture']!=null || $data['picture']!=''? $data['picture'] :"ProfileMale.gif"); ?>" />
    							            		</a>
                      								<br/>    
                                              		<?php echo $data['location'];?>
                                            	</li>
	                                        
	                                        	 <?php 	                                          
		                                            if($userCounter==4 ){
		                                                $userCounter = 0;
		                                                echo '</ul>';
		                                            } elseif ($userCounter==$this->totalplayerfans ) { 
		                                                echo '</ul>'; 
		                                            }
	                                            ?>
	                                        
	                                         <?php } ?>
                					</div>
                				   <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_grid_list.phtml',array('ajaxdata'=>'ajaxdata','type'=>$this->type)); ?>