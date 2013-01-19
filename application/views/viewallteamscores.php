 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 <?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>


<script type="text/JavaScript">

 jQuery(document).ready(function() {

    

    /*var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showmatches/id/<?php echo $this->team[0]["team_id"]; ?>/status/';
		//load the first list by default in scores
	 	jQuery('#scoreboardResult').html('Loading...');
	 	url = urlBase + 'Played';
		jQuery('#scoreboardResult').load(url );

		//load the first list by default in schedules
		jQuery('#scoreboardResult2').html('Loading...');
		url = urlBase + 'Fixture';
	 	jQuery('#scoreboardResult2').load(url );

		*/

    var defaulttab = 'scores<?php //echo $this->default; ?>';
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

	     jQuery('#data').html('Loading...'); 
	     jQuery('#data').load(url);
		 jQuery('a.selected').removeClass('selected');
	     jQuery('li.selected').removeClass('selected');
	     jQuery('#' + tab).addClass('selected');
	     jQuery('#' + tab + 'Li').addClass('selected');

	}

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

    <div id="SecondColumnPlayerProfile" class="SecondColumnOfTwo" >
            <h1><?php echo $this->team[0]['team_name']; ?> Scores &amp; Schedules</h1>
                  
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                              <div class="SecondColumnProfile">
				                    <ul id="main_tabs" class="TabbedNav">
				                        <li id="scoresTabLi" class="Selected">
				                        	<a href="#" id="scoresTab" class="Selected">Scores</a>
				                        </li>
				                        <li id="schedulesTabLi">
				                        	<a href="#" id="schedulesTab">Schedules</a>
				                        </li>
				                    </ul>
				
				                    <br class="ClearBoth">
                                    
                                    <div class="tabswrapper" id="data">
                                      
                                     
                                    </div> 
                                    
                               </div>
                           </div>
                        </div>
     </div>
             
</div> <!--end ContentWrapper-->