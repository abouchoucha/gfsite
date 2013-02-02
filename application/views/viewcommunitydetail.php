
<?php
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen(); ?>
<?php  $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'Common.php'; ?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>


<script type="text/JavaScript">

    jQuery(document).ready(function() {

        addShareThisButton();

        showActivitiesTab('allActivity','all');

	    jQuery('#allActivityTab').click(function() {
            showActivitiesTab('allActivity','all');
        });

        jQuery('#membersTab').click(function() {
            showActivitiesTab('members','1');
        });
         
        jQuery('#playersTab').click(function() {
            showActivitiesTab('players','3');
        });
         
        jQuery('#teamsTab').click(function() {
            showActivitiesTab('teams','2');
        });
         
         jQuery('#competitionsTab').click(function() {
            showActivitiesTab('competitions','4');
        });

         jQuery('#atoday').click(function(){
         	showGoalFacePulse('atoday','');
    	 })

        jQuery('#ayesterday').click(function(){
           	showGoalFacePulse('ayesterday','1');
      	});

        jQuery('#a2days').click(function(){
        	 showGoalFacePulse('a2days','2');
        });

        jQuery('#a3days').click(function(){
        	 showGoalFacePulse('a3days','3');
        });

        jQuery('#a4days').click(function(){
        	 showGoalFacePulse('a4days','4');
        });

        jQuery('#a5days').click(function(){
        	 showGoalFacePulse('a5days','5');
        });

        jQuery('#a6days').click(function(){
        	 showGoalFacePulse('a6days','6');
        });

        jQuery('#a7days').click(function(){
        	 showGoalFacePulse('a7days','7');
        });



    });

    function showGoalFacePulse(titledays , days){
    	var timezone = calculate_time_zone();
    	jQuery('#PulseDetailWrapper a').removeClass('filterSelected');
     	jQuery('#'+titledays).addClass('filterSelected');
     	var activityId = jQuery("#activityId").val();
     	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/community/showallacitivitiesview/timezone/'+timezone+'/activityId/'+activityId +'/days/'+days;
        jQuery('#ajaxdata').html("<div class='ajaxload scoresMain'></div>");
       // jQuery('#ajaxdata').load( url , { activityId : activityId  , days : days,timezone : timezone} );
       
        jQuery('#ajaxdata').load( url , { activityId : activityId  , days : days,timezone : timezone}, function() {
            addShareThisButton();
        });


    }



    function showActivitiesTab(tab,activityId){
    	var timezone = calculate_time_zone();
        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/community/showallacitivitiesview';
        jQuery('#ajaxdata').html("<div class='ajaxload scoresMain'></div>");
        //jQuery('#ajaxdata').load( url , { activityId : activityId , timezone : timezone } );
        
        jQuery('#ajaxdata').load( url , { activityId : activityId , timezone : timezone } , function() {        
            addShareThisButton();
        });

        jQuery('a.Selected').removeClass('Selected');
        jQuery('li.Selected').removeClass('Selected');

        jQuery('#' + tab).addClass('Selected');
        jQuery('#' + tab + 'tab').addClass('Selected');

        jQuery('#PulseDetailWrapper a').removeClass('filterSelected');
     	  jQuery('#atoday').addClass('filterSelected');

    }

    function addShareThisButton () {
        //stButtons = null;
        jQuery.getScript("http://w.sharethis.com/button/buttons.js", function() {
            var switchTo5x = true;
            stLight.options({publisher: "bf8f5586-8640-4cce-9bca-c5c558b3c0a1",popup:"true"});
            stLight.readyRun = false;
        });
    }

</script>


<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumn">
       <?php
            $session = new Zend_Session_Namespace ( 'userSession' );
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
            <div class="img-shadow">
                <div class="WrapperForDropShadow">
                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">
                   <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" />
                </a>
                </div>
            </div>
            <?php } ?>

             
        </div><!--end FirstColumnOfThree-->

        <div class="SecondColumn" id="SecondColumnPlayerProfile">
        <h1>GoalFace Pulse</h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">
                    <ul class="TabbedNav" id="main_tabs">
                        <li id="allActivity" class=""><a id="allActivityTab" href="javascript:void(0)">All Activity</a></li>
                        <li id="members"><a id="membersTab" href="javascript:void(0)">Members</a></li>
                        <li id="players"><a id="playersTab" href="javascript:void(0)">Players</a></li>
                        <li id="teams"><a id="teamsTab" href="javascript:void(0)">Teams</a></li>
                        <li id="competitions"><a id="competitionsTab" href="javascript:void(0)">Competitions</a></li>
                    </ul>

                    <br class="ClearBoth"/>
                    
                    <div id="PulseDetailWrapper">
                        <div id="activitiesDateFilter" class="FriendLinks">
                           Show:
                                 <a id="atoday" class="filterSelected" href="javascript:void(0)">Today</a>
                                  |
                                  <a id="ayesterday" href="javascript:void(0)">Yesterday</a>
                                  |
                                  <a id="a2days" href="javascript:void(0)">2 days ago</a>
                                  |
                                  <a id="a3days" href="javascript:void(0)">3 days ago</a>
                                  |
                                   <a id="a4days" href="javascript:void(0)">4 days ago</a>
                                  |
                                  <a id="a5days" href="javascript:void(0)">5 days ago</a>
                                  |
                                  <a id="a6days" href="javascript:void(0)">6 days ago</a>
                                  |
                                  <a id="a7days" href="javascript:void(0)">7 days ago</a>
                        </div>

                        <div id="ajaxdata" style="margin-top: 10px;">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end Second Column-->

 </div> <!--end ContentWrapper-->



             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
