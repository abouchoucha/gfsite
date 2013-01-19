 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
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

                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                    <?php } ?>
 				
               
              </div><!--end FirstColumnOfThree-->

                <div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1>Head to Head Matches</h1>                   
                   
                    <div id="SecondColumnHighlightBoxContent">
                    
                    
                    
                    <?php 
                      echo '<strong>' . $this->xmlheadmatches ['team_a_title'] . "</strong> - <strong>". $this->xmlheadmatches ['team_b_title'] ."<br><br>";
                    	foreach ( $this->xmlheadmatches->match as $matchitem ) {
			                   echo $matchitem ['date']." - ".$matchitem ['team_A'] ."&nbsp;". $matchitem ['fs_A']. " - " . $matchitem ['team_B'] ."&nbsp;". $matchitem ['fs_B']."<BR>";
			                }
                    ?>
                      		
	
                         
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

             </div> <!--end ContentWrapper-->