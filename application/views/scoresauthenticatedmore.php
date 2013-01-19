<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
	$common = new Common();
?>

<script type="text/JavaScript">
    jQuery(document).ready(function(){

    	//callMyResults('last','menu1content','0');

    	//setup tabs - scores and schedules
        jQuery('#menu1content,#menu2content').hide();
        <?php if ($this->type == 'scores'){?>
        	jQuery('#menu1content').show();
        	jQuery('#menu1').addClass('active');
        <?php }else if ($this->type == 'schedules'){ ?>
        	jQuery('#menu2content').show();
        	jQuery('#menu2').addClass('active');
        <?php } ?>
        
        jQuery('#tab_scores ul li').click(function(){
            jQuery('#menu1content,#menu2content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu1,#menu2').removeClass('active');
            jQuery(this).addClass('active');
        });

        //setup tabs - updates
        jQuery('#menu3content,#menu4content').hide();
        jQuery('#menu3content').show();
        jQuery('#menu3').addClass('active');
        jQuery('#tab_updates ul li').click(function(){
            jQuery('#menu3content,#menu4content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu3,#menu4').removeClass('active');
            jQuery(this).addClass('active');
        });

		<?php if ($this->type == 'schedules'){?>
			callMyResults('fixture','menu2content','0');
	        jQuery('#menu1content').hide();
		<?php }else if ($this->type == 'scores'){ ?>
			callMyResults('played','menu1content','0');
			jQuery('#menu2content').hide();
		<?php } ?>
        
        jQuery('#mySchedulesTab').click(function() {
    		callMyResults('fixture','menu2content','0');
    		jQuery('#menu1content').hide();
		});

    	jQuery('#myScoresTab').click(function() {
    		callMyResults('played','menu1content','0');
    		jQuery('#menu2content').hide();
		});

	 });


    function callMyResults(date,div,compId){
       	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
   		var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
   		var baseUrl = '<?php echo Zend_Registry::get("contextPath"); ?>/index/showscoreschedule/initDateTime/'+initDateTime+'/endDateTime/'+endDateTime;
   		if(compId != '0'){
   			baseUrl += '/compId/'+compId;
       	}
   		
      	jQuery('#'+div).html("<div class='ajaxload widgetlong'></div>");
     	jQuery.ajax({
            method: 'get',
            url : baseUrl+'/date/'+date,
            dataType : 'text',
            success: function (text) {
     		  if(text !=''){
  		  		 jQuery('#'+div).html(text);
              }else{
  				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
  	          }   
             }
         });

      }

	function showUpdatesByCategory(){

    	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmyupdates/limit/10';
    	var activityId = jQuery("#activityId").val();
    	jQuery('#data_updates').html("<div class='ajaxload widgetlong'></div>");
    	jQuery('#data_updates').load(url ,{ activityId : activityId });
    }
</script>

<div id="ContentWrapper" class="TwoColumnLayout">

     <div class="FirstColumn">
     		<?php echo $this->render('include/topleftbanner.php')?>
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
                    
               <?php } ?>
 				<div class="img-shadow" id="leftnav">
				    <?php include 'include/myauthleftnavigation.php';?>
                </div>
     </div><!--end FirstColumnOfThree-->

     <div id="SecondColumnFullHome" class="SecondColumnOfTwo">
        <div class="ammid">
            <!-- <p class="ad">Ad image here</p> -->
            	<div class="aleft">
           
                    <h1 style="width:253px">Scores &amp; Schedules</h1>
                   
                    <p class="" style="width:300px;float:left;">
                    	<select style="float: left;" id="myleaguesId">
                    	<option value="0" <?php echo ($this->competitonId == 0?'selected':'')?> >All Leagues</option>
                    			<?php foreach($this->userCompetitions as $comp) { ?>
                          <option value="<?php echo $comp["competition_id"]; ?>" <?php echo ($this->competitonId == $comp["competition_id"]?'selected':'')?> ><?php echo $comp["competition_name"]; ?>(<?php echo $comp["country_name"]; ?>)</option>
                        <?php }  ?>
                 		</select>
                 		 <input type="submit" class="submit" value="Ok" id="buttonLeagues" style="display: inline;">
                    
                    </p>
                    <div id="tab_scores" class="tabs">
                            <ul>
                               <li id="menu1"><a id="myScoresTab" href="javascript:void(0);">Scores</a></li>
                               <li id="menu2"><a id="mySchedulesTab" href="javascript:void(0);">Schedules</a></li>
                            </ul>
                    </div>
                    <div class="regional" id="menu1content">
                        
                    </div>
                    
                    <div class="regional" id="menu2content" style='display:none;'>
                            
                    </div>
                    <!-- /Scores & Schedules -->
                   
                    

                </div>
        </div>
    </div><!--end SecondColumnOfTwo -->

</div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
