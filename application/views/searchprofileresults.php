       	<ul class="LayoutFourPictures">
                     
                        <?php  
                            // Retrive data from teams as a normal array
                            $userCounter = 0;
                            foreach ($this->foundprofiles as $data) {
                              $userCounter++;
                              if($userCounter==1){
                             ?>
                                <ul class="LayoutFourPictures">
                                  <?php } ?>
                                  
                             <li>
                              <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/index/id/<?php echo $data['userid'];?>" title="">
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/<?php  echo $data['picture']?>"/>
							 </a>
                              <br/>
				             <a  href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/index/id/<?php echo $data['userid'];?>" title=""><?php echo $data['nickname'];?></a>
				             <br/>
                            </li>

                          <?php 
                            if($userCounter==4){
                              $userCounter = 0;
                              echo '</ul>';
                            }
                          ?>
                        <?php } ?>
                      </ul>
                      <br class="clearleft"/>
          </ul>
