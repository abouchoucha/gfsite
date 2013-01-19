 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
 
  <?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 


<script type="text/JavaScript">

function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1; 
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    } 
  }
return "";
}

function setCookie(c_name,value,expiredays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+
	((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}
//this deletes the cookie when called
function delete_Cookie( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + "=" +
	( ( path ) ? ";path=" + path : "") +
	( ( domain ) ? ";domain=" + domain : "" ) +
	";expires=Thu, 01-Jan-1970 00:00:01 GMT";
	}


function checkCookie()
{
	refresh = getCookie('refresh');
	
	if (refresh!=null && refresh!="")
  	{
	  	if(refresh == '30'){
	  		jQuery('#30s').addClass('filterSelected');
		}
	  	if(refresh == '60'){
	  		jQuery('#60s').addClass('filterSelected');
		}
  	}else{
  		jQuery('#turnoff').addClass('filterSelected');
  	}
}  

jQuery(document).ready(function() {
	
	showScoresScheduleTab('<?php echo $this->type ?>' ,'y');
	jQuery('#scoresTab').click(function() {
		showScoresScheduleTab('scoresTab' , 'y');
	  });
	jQuery('#schedulesTab').click(function() {
		showScoresScheduleTab('schedulesTab' ,'y');
	});

});

function showScoresScheduleTab(tab , defaultview){
	var continent = '<?php echo $this->continent ?>';
	var continentid = '<?php echo $this->continentid ?>';
	var page = null;
	var date = null;
	if(tab == ''){ 
		tab = 'scoresTab';
		page = 'scores';
		date = 'today';
	}
	if(tab == 'scoresTab'){
		page = 'scores';
		date = 'today';
	}else if(tab == 'schedulesTab'){
		page = 'schedules';
		date = 'tomorrow';
	}
	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var timezone = calculate_time_zone();
	
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboardbyregion';
     if(defaultview == 'y'){	
	     jQuery('#ScoresSchedulesWrapper').html("<div class='ajaxload scoresMain'></div>");
		 jQuery('#ScoresSchedulesWrapper').load(url ,{show: 'all' , type : page ,date :date , status : 'Top' ,defaultView : defaultview , continent : continent , continentid : continentid, timezone : timezone ,initDateTime : initDateTime, endDateTime : endDateTime});
     }else if(defaultview == 'n'){
         jQuery('#ScoresDataModule').html("<div class='ajaxload scoresMain'></div>");
		 jQuery('#ScoresDataModule').load(url ,{show: 'all' , type : page ,date :date , status : 'Top' ,defaultView : defaultview , continent : continent, continentid : continentid,timezone : timezone , initDateTime : initDateTime, endDateTime : endDateTime});
      }
     jQuery('a.Selected').removeClass('Selected');
     jQuery('li.Selected').removeClass('Selected');
     jQuery('#' + tab).addClass('Selected');
     jQuery('#' + tab + 'Li').addClass('Selected');

}

//refreshing after X milisecs

refresh = getCookie('refresh');
var milisecs = refresh * 1000;
//alert(refresh * 1000);
if (refresh!=null && refresh!="")
{
	refreshInterval = setInterval(showScoresScheduleTab, milisecs);
}else {
	
}	


function showMatchesByTimeFrame(){
	
	var regionId = jQuery('#regionId').val();
	var dateId = jQuery('#dateId').val();
	var typeId = jQuery('#typeId').val();
	var pageId = jQuery('#pageId').val();
	var regionName = jQuery('.filterSelected').attr('id');
	var initDateTime = formatDate(getCurrentInitTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var timezone = calculate_time_zone();
	
	if(regionId == ''){
	   document.homescores.regionId.value = '0';
	}
	var regionId = jQuery('#regionId').val();
	jQuery('#ScoresDataModule').html("<div class='ajaxload scoresMain'></div>");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboardbyregion';
	jQuery('#ScoresDataModule').load( url , {date:dateId, page:pageId , name : regionName ,type:typeId,timezone : timezone ,initDateTime : initDateTime, endDateTime : endDateTime});
	
}

function showScoresByRegion(regionId , regionName){
	jQuery('#ScoresDataModule').html("<div class='ajaxload scoresMain'></div>");
	var type = jQuery('#typeId').val();
    var date = jQuery('#dateId').val();
	var initDateTime = formatDate(getCurrentInitTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var timezone = calculate_time_zone();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboardbyregion/show/all/page/scores';
	jQuery('#ScoresDataModule').load( url , {date:date, name : regionName ,type:type,timezone:timezone, initDateTime : initDateTime, endDateTime : endDateTime});
}

function showScoresByStatus(status){
	jQuery('#ScoresDataModule').html("<div class='ajaxload scoresMain'></div>");
	var regionId = jQuery('#regionId').val();
	var type = jQuery('#typeId').val();
    var date = jQuery('#dateId').val();
    var initDateTime = formatDate(getCurrentInitTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+1.0),'yyyy-MM-dd HH:mm:ss');
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboardbyregion/show/all/page/scores';
    jQuery('#ScoresDataModule').load( url, {date:date, name : regionName ,type:type, initDateTime : initDateTime, endDateTime : endDateTime, status : status} );
}


function closeAll(){

	jQuery('div.Scores').hide();
	jQuery('div.DownArrow').removeClass('DownArrow').addClass('RightArrow');
    jQuery('#OpenRegionScores').removeClass('filterSelected');
    jQuery('#CloseRegionScores').addClass('filterSelected');
}

function openAll(){

	jQuery('div.Scores').show();
	jQuery('div.RightArrow').removeClass('RightArrow').addClass('DownArrow');
	jQuery('#CloseRegionScores').removeClass('filterSelected');
    jQuery('#OpenRegionScores').addClass('filterSelected');
}

</script>

      
<?php require_once 'Common.php';
   $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
?>

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

           

 
            <!--Goalface Join Now-->
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                       <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                    </a>
                    </div>
                </div>

            <?php } ?>
            
            <!--Facebook Like Module-->
            <?php echo $this->render('include/navigationfacebook.php')?>
            


        </div><!--end FirstColumnOfThree-->

        <div class="SecondColumn" id="SecondColumnPlayerProfile">
        <h1>Scores &amp; Schedules</h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">
                    <ul class="TabbedNav" id="main_tabs">
                        <li id="scoresTabLi"><a id="scoresTab" href="#">Scores</a></li>
                        <li id="schedulesTabLi"><a id="schedulesTab" href="#">Schedules</a></li>
                    </ul>

                    <br class="ClearBoth"/>
                    
                    <div id="ScoresSchedulesWrapper">
                        Some text goes here
                    </div>
               </div>
            </div>
        </div>
    </div><!--end Second Column-->


        <!--end SecondColumnOfTwo -->



 </div> <!--end ContentWrapper-->

             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
