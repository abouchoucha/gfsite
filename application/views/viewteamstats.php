<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
?>
<script type="text/JavaScript">

	jQuery(document).ready(function() {
	      jQuery('.tabscontainer').hide();
	      jQuery('#tab1AllContent').show();
	      jQuery('#tab1').addClass('active');
		  // Team Top Performers 
	      jQuery('.tabscontainer').hide();
	      jQuery('#tab1AllContent').show(); // goals default view   
		  jQuery('#teamStatTab ul li').click(function(){
			 		jQuery('.tabscontainer').hide();
					tab_id = jQuery(this).attr('id');
					//show div content
					jQuery('#' + tab_id + 'AllContent').show();
					jQuery('.tabbedOptions').removeClass('active');
					jQuery(this).addClass('active');
					return false;
	        });
	      
	});

</script>

<div id="ContentWrapper">

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
              
       
                    <?php } else {?>


                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
                      
                <?php } ?>

              	<!--Team Profile Badge-->
        		<?php echo $this->render('include/badgeTeamNew.php');?>

		         <!--Team Profile left Menu-->
        <div class="img-shadow">
            <?php echo $this->render('include/navigationTeam.php');?>
        </div>
                    
        </div><!--/FirstColumn-->
                
        <div id="SecondColumnStats" class="SecondColumn">
            <div class="ammid">
                <h1 style="float:left;"><?php echo $this->teamname;?> Top Performers</h1>
                
                <div id="teamStatTab" class="tabs">
					<ul>
						<li id="tab1" class="tabbedOptions"><a href="javascript:void(0);">Top Scorers</a></li>
						<li id="tab2" class="tabbedOptions"><a href="javascript:void(0);">Appereances</a></li>
						<li id="tab3" class="tabbedOptions"><a href="javascript:void(0);">Discipline</a></li>
					</ul>
				</div>
                
                <div id="tab1AllContent" class="tabscontainer">
            		<p class="all">&nbsp;</p>
            		<div class="scores">
			          <ul>
			              <li class="name">Player</li>
			              <li class="score">
			                 <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="">
			              </li>
			              <li id="stattile" class="cont">GP</li>
			              <li class="team">Nationality</li>
			              <li class="profile">&nbsp;</li>
			
			          </ul>
			      </div>
			        <?php $i = 1;?>
					  <?php foreach ($this->topscorerca as $item) { ?>
					     <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
					     		<div class="<?php echo $style; ?>">
						          <ul>
						              <li class="name">
										<a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>"><?php echo $item['player_name_short']; ?></a>
						              </li> 
						              <li class="score">
											<?php echo $item['total_goals']; ?> 
						               </li>
						               <li class="cont"><?php echo $item['lineups']; ?></li>
						               <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        			<a href=""><?php echo $item['country_name']; ?></a>
						      			</li>  
						              <li class="profile">
						                  <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>" title="<?php echo $item["player_common_name"]; ?>">Profile &raquo;</a>
						              </li>
						          </ul>
						      </div>

                      <?php $i++; } ?>
                </div>
                
                <div id="tab2AllContent" class="tabscontainer">
  					<p class="all">&nbsp;</p>
            		<div class="scores">
			          <ul>
			              <li class="name">Player</li>
			               <li class="score">         
					      	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/appear.png" alt="Appereances">                     
					       </li>
			              	<li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/clock.gif" alt="Minutes">
					       </li>  
					       <li class="team">Nationality</li>
			              <li class="profile">&nbsp;</li>
			          </ul>
			        </div>
			        <?php $i = 1;?>
					  <?php foreach ($this->lineups as $item) { ?>
					     <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
					     		<div class="<?php echo $style; ?>">
						          <ul>
						              <li class="name">
										<a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>"><?php echo $item['player_name_short']; ?></a>
						              </li>
						              <li class="score">
						              		<?php echo $item['lineups']; ?>
						              </li>
						              <li class="score">
											<?php echo $item['minutes']; ?> 
						               </li>
						          	  <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        			<a href=""><?php echo $item['country_name']; ?></a>
						      		  </li>  
						              <li class="profile">
						                  <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>" title="<?php echo $item["player_common_name"]; ?>">Profile &raquo;</a>
						              </li>
						          </ul>
						      </div>

                      <?php $i++; } ?>
			        
                </div>
                
                <div id="tab3AllContent" class="tabscontainer">
 					<p class="all">&nbsp;</p>
            		<div class="scores">
			          <ul>
			              <li class="name">Player</li>
			              <li class="score">
			              	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="">
			              </li>
			              <li class="score">
			              	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="">
			              </li>
			              <li class="score">Total</li>
			                <li class="team">Nationality</li>
							<li class="profile">&nbsp;</li>
			          </ul>
			      </div>
                    <?php $i = 1;?>
					  <?php foreach ($this->allcards as $item) { ?>
					     <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
					     		<div class="<?php echo $style; ?>">
						          <ul>
						              <li class="name">
										<a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>"><?php echo $item['player_name_short']; ?></a>
						              </li>
						              <li class="score">
						              		<?php echo $item['yellowcards']; ?>
						              </li>
						              <li class="score">
											<?php echo $item['redcards']; ?> 
						               </li>
						              <li class="score">
						                  <?php echo $item['totalcards']; ?> 
						              </li>
						                <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        			<a href=""><?php echo $item['country_name']; ?></a>
						      		  </li>  
						              <li class="profile">
						                  <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>" title="<?php echo $item["player_common_name"]; ?>">Profile &raquo;</a>
						              </li>
						          </ul>
						      </div>

                      <?php $i++; } ?>
                </div>

           		
            
         </div>
            
      </div><!--/SecondColumn--> 
  
</div>
