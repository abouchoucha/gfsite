<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
	$common = new Common();
?>

<script type="text/JavaScript">
    jQuery(document).ready(function(){

    		callStuffUpdates();
    	    jQuery('#buttonActivity').click(function(){ //
    	    	callStuffUpdates();
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

        
       	 
 });

	//Load Community activities
    function callStuffUpdates(){
        //jQuery('#data_updates').addClass('ajaxloadtabs');
    	jQuery('#data_updates').html("<div class='ajaxload widgetlong'></div>");
    	var activityId = jQuery("#activityId").val();	
    	jQuery.ajax({
            method: 'post',
            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmyupdates',
            dataType : 'text',
            data : { activityId : activityId },
            success: function (text) {
   		 		if(text !=''){
			  		  jQuery('#data_updates').html(text);
	            }else{
					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
		        }   
             }
         });
	}
	setInterval(callStuffUpdates,300000);

	function showUpdatesByCategory(){

    	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmyupdates';
    	var activityId = jQuery("#activityId").val();
    	jQuery('#data_updates').html("<div class='ajaxload widgetlong'></div>");
    	jQuery('#data_updates').load(url ,{ activityId : activityId });
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
                
               <?php } ?>

				
				<div class="img-shadow" id="leftnav">
				   <?php include 'include/myauthleftnavigation.php';?>
                </div>

     </div><!--end FirstColumnOfThree-->

     <div id="SecondColumnFullHome" class="SecondColumnOfTwo">
        <div class="ammid">
            <!-- <p class="ad">Ad image here</p> -->
            	<div class="aleft">
                    <!-- Updates & Alerts -->
                    <h2>Updates <!--  &amp; Alerts--> <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/showrssfeedupdates/email/<?php echo $session->email;?>/key/<?php echo $session->user->salt;?>"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/rss1.jpg" alt=""/></a></h2>
                    <div id="tab_updates" class="tabs">
                            <ul>
                                    <li id="menu3"><a href="javascript:void(0);">Updates</a></li>
                                    <!-- li id="menu4"><a href="javascript:void(0);">Alerts</a></li> -->
                            </ul>
                    </div>
                    <div class="regional" id="menu3content">
                        <table border="0" class="table" cellpadding="5" cellspacing="0">
                                <tr bgcolor="#ffffff">
                                        <td>
                                                Show:
                                                <select id="activityId" class="sell">
                                                        <option value="0">All Updates</option>
                                                        <!--  option value="1">Leagues &amp; Tournaments Only</option-->
                                                        <option value="2">Players Only</option>
                                                        <option value="3">Teams Only</option>
                                                </select>                                              
                                                <input type="submit" class="submit" value="Ok" id="buttonActivity" style="display: inline;">
                                        </td>
                                        <!-- td align="right"><a href="#">Manage Updates</a></td> -->
                                </tr>
                        </table>
                        <div id="data_updates">
                        
                        </div>
                    </div>
                    
                    <!-- /Updates & Alerts -->
                </div>
        </div>
    </div><!--end SecondColumnOfTwo -->

</div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
