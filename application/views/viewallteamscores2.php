 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 <?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>


<script type="text/JavaScript">

jQuery(document).ready(function() {

    var defaulttab = '<?php echo $this->teamMenuSelected; ?>';
	if(defaulttab == 'scores'){
		showScoresScheduleTab('scoresTab');
	}else if(defaulttab == 'schedules'){
		showScoresScheduleTab('schedulesTab');
	}
	jQuery('#scoresTab').click(function() {
		showScoresScheduleTab('scoresTab');
	  });
	jQuery('#schedulesTab').click(function() {
		showScoresScheduleTab('schedulesTab');
	}); 


});


 function showScoresScheduleTab(tab){
		var page = null;
		var date = null;
		var status = null;
		if(tab == ''){ 
			tab = 'scoresTab';
			page = 'scores';
		}
		if(tab == 'scoresTab'){
			page = 'scores';
			status = 'Played';
		}else if(tab == 'schedulesTab'){
			page = 'schedules'
			status = 'Fixture';
		}
		var timezone = calculate_time_zone();
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showmatches/timezone/'+timezone+'/id/<?php echo $this->team[0]["team_id"]; ?>/status/'+status;	
		jQuery('#fullscoreboardcontent').html("<div class='ajaxloadscores'></div>");
		jQuery('#fullscoreboardcontent').load(url);
	    jQuery('li.active').removeClass('active');
	    jQuery('#' + tab + 'Li').addClass('active');
	}
 jQuery('#fullscoreboardcontent').html("<div class='ajaxloadscores'></div>");
 jQuery('#fullscoreboardcontent').load(url);
</script>


<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>

<div id="ContentWrapper" class="TwoColumnLayout">

    <div class="FirstColumn">

                <?php
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

                    <!--Goalface Register Ad-->

                    <?php } ?>

                    <!--Team Profile Badge-->

			        <div class="img-shadow">
			         	<?php echo $this->render('include/badgeTeamNew.php');?>
			        </div>

                  <!--Team Profile left Menu-->
                    <div class="img-shadow">
                       <?php echo $this->render('include/navigationTeam.php');?>
                    </div>

  <?php  if($session->email == null){  ?>
	<!--Goalface Join Now-->
	    <div class="img-shadow">
	        <div class="WrapperForDropShadow">
	        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
	           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
	        </a>
	        </div>
	    </div>
	<?php  } ?>


    </div><!--end FirstColumnOfTwo-->

     <div id="SecondColumnFullHome" class="SecondColumnOfTwo">
        <div class="ammid">
            <!--<p class="ad">< img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/adspot.jpg" alt="" width="720px"/></p> -->
            	<div class="aleft">
            		<h1><?php echo $this->team[0]['team_name']; ?> Scores &amp; Schedules</h1>
            		 <div id="tab_scores" class="tabs">
                            <ul>
                               <li id="scoresTabLi"><a id="scoresTab" href="javascript:void(0);">Scores</a></li>
                               <li id="schedulesTabLi"><a id="schedulesTab" href="javascript:void(0);">Schedules</a></li>
                            </ul>
                    </div>
                    
                    <div class="scorestabs" id="fullscoreboardcontent">
                        	
                    </div>
            		
            		
            		
            		<!--<ul id="main_tabs" class="TabbedNav">
                       <li id="scoresTabLi" class="Selected">
				            <a href="#" id="scoresTab" class="Selected">Scores</a>
				          </li>
                        <li id="schedulesTabLi">
                        	<a href="#" id="schedulesTab">Schedules</a>
                        </li>
                    </ul>
				
				           <br class="ClearBoth">
                                    
                      <div class="tabswrapper" id="data">
                                      
                                     
                     </div>--> 
                    	
                    <!-- /Scores & Schedules -->
            	
            	
            	</div>
            	 

        </div>
     </div>
             
</div> <!--end ContentWrapper-->



