<script language = "javascript">

jQuery(document).ready(function() {
 	jQuery('#today').addClass('filterSelected');
 	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showscoreboard/date/';

 	//load the first list by default	
 	jQuery('#scoreboard').html('Loading...');
 	url = urlBase + 'today'; 
	//jQuery('#scoreboard').load(url);

	jQuery('#l3').click(function(){			
		loadScoreBoard(this.id, urlBase , '-3');			
     });
	jQuery('#l7').click(function(){			
		loadScoreBoard(this.id, urlBase , '-7');			
     });
	
	 
});

function loadScoreBoard(id, url , date)
{
		jQuery('#ScoresDateFilter a').removeClass('filterSelected');
     	jQuery('#' + id).addClass('filterSelected');
		jQuery('#scoreboard').html('Loading...'); 			
		jQuery('#scoreboard').load(url , {date  : date});
}

</script>

	
<div class="FeaturedNewsSort" id="ScoresDateFilter">
   <a href="#" id="today" class="filterSelected">Today</a> |
   <a href="javascript:void(0);" id="l3">Last 3 Days</a> | 
   <a href="javascript:void(0);" id="l7">Last 7 Days</a> | 
</div>  
														 
<div id="ScorecardContainer">

<div id="scoreboard">

	<?php include 'scoreboardviewresult.php';?>

</div>
								
			
