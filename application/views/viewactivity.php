
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
    $common = new Common();
    $urlGen = new SeoUrlGen(); 
    $config = Zend_Registry::get ( 'config' );
    $session = new Zend_Session_Namespace('userSession'); 
    $tempDate = '1234';
    $today = date ( "m-d-Y" ) ;
    $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;


   $pageURL = 'http://';
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
?>


 <div id="ContentWrapper" class="TwoColumnLayout">
             <div class="FirstColumn">
               <?php 
                   $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?> 
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>
                            
                        </div>
                    </div>

                    <!--feedback-->
                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
                    
      
                    <?php } ?>

                    <!--Facebook Like Module-->
                   <?php echo $this->render('include/navigationfacebook.php')?>


              </div><!--end FirstColumnOfThree-->

              <div class="SecondColumn" id="SecondColumnPlayerProfile">
                <h1><strong><?php echo $this->activitytitle; ?></strong></h1>
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                      <div class="SecondColumnProfile">
                        <div id="FriendsWrapper" class="FriendsWrapperTopAlign">
                            <div id="activity" class="friends_area activity_area">
                                <div class="leftcomment leftcommentactivitiy">

                                  <!-- If activity type is not 3 (players) so it is leagues / teams -->
                                  <?php if ($this->activity_category_id != 3) { ?>

                                    <div id="ActivityDetailsLogosScores">
                                       <div> 
                                          <a border="0" href="<?php echo $this->activityUrl;?>"> <img src="/public/images/teamlogos/<?php echo $this->team_a;?>.gif"></a>
                                           <span> <h3> <a href="<?php echo $this->activityUrl;?>"><?php echo $this->team_a_name;?> </a> </h3>
                                           </span>
                                        </div>
                                       
                                        <div> 
                                          <a border="0" href="<?php echo $this->activityUrl;?>"> <img src="/public/images/teamlogos/<?php echo $this->team_b;?>.gif"></a>
                                         <span> <h3> <a href="<?php echo $this->activityUrl;?>"><?php echo $this->team_b_name;?></a> </h3> </span>
                                       </div>
                                    </div>

                                  <?php } else { ?>
                                      <!-- players activities -->  
                                        <img id="iconActivity" title="" src="<?php echo $this->imagefacebook;?>"> 
                                        
                                  <?php }  ?>

                                </div>
                                
                                <div class="rightcomment rightcommentactivitiy">
                                    <span class="date">
                                    <?php
                                      if($today == date('m-d-Y' , strtotime($this->activity->activity_date))){
                                        echo 'Today at ' . date("H:i" , strtotime($this->activity->activity_date)) ;
                                      } else if($yesterday == date('m-d-Y' , strtotime($this->activity->activity_date))){
                                        echo 'Yesterday at ' . date("H:i" , strtotime($this->activity->activity_date)) ;
                                      } else {
                                        echo date(' F j' , strtotime($this->activity->activity_date)) ;
                                      }  

                                      //$stTitle = $activity["activitytype_name"];

                                      $stTitle = strip_tags($this->activity->activity_text);
                                      //$stURL = $pageURL;
                                      //$stImage = 'http://' . $config->path->index->server->name . $this->escape($this->activityimage);
                                      //$social_icons_images = 'http://' . $config->path->index->server->name  
                                      ?>
                                    </span>
                                    <p class="shoutp"><?php echo $stTitle; ?></p>
                                    <p><a href="<?php echo $this->activityUrl; ?>"><?php echo $this->activityUrl; ?></a></p>

                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>              
      </div> <!--end ContentWrapper-->
