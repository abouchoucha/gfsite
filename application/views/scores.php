<script type="text/JavaScript">

jQuery(document).ready(function() {

	checkCookie();

	<?php if($this->continent != null){?>
		showScoreBoardByContinent('<?php echo($this->continent);?>','<?php echo($this->continentid);?>');
 	<?php }?>
 	
	 jQuery('#international').click(function(){
     	showScoreBoardByContinent('international','8');
	 });
     jQuery('#europe').click(function(){
      	showScoreBoardByContinent('europe','1');
      });
     jQuery('#americas').click(function(){
      	showScoreBoardByContinent('americas','2');
      });
     jQuery('#africa').click(function(){
      	showScoreBoardByContinent('africa','5');
      });
     jQuery('#asia').click(function(){
       	showScoreBoardByContinent('asia','6');
       });
     jQuery('#topmatches').click(function(){
        	jQuery('#GameSearchCriteria a').removeClass('filterSelected');
        	jQuery('#allm').removeClass('filterSelected');
	     	jQuery('#livematches').removeClass('filterSelected');
        	jQuery('#topmatches').addClass('filterSelected');
        	showScoresScheduleTab('scoresTab','n');
        });
     jQuery('#livematches').click(function(){
        	jQuery('#GameSearchCriteria a').removeClass('filterSelected');
        	jQuery('#topmatches').removeClass('filterSelected');
         	jQuery('#allm').removeClass('filterSelected');
        	jQuery('#livematches').addClass('filterSelected');
        	showScoresByStatus('Playing');
        });		
     jQuery('#allm').click(function(){
	     	jQuery('#GameSearchCriteria a').removeClass('filterSelected');
	     	jQuery('#topmatches').removeClass('filterSelected');
	     	jQuery('#livematches').removeClass('filterSelected');
	     	jQuery('#allm').addClass('filterSelected');
	     	showScoresByRegion('0' , 'all');
     });

     jQuery('#30s').click(function() {
		jQuery('#60s').removeClass('filterSelected');
		jQuery('#turnoff').removeClass('filterSelected');
		jQuery('#30s').addClass('filterSelected');
  		setCookie('refresh',30,30);
  		refreshInterval = setInterval(showScoresScheduleTab, 30*1000);
 	});
     jQuery('#60s').click(function() {
 		jQuery('#30s').removeClass('filterSelected');
		jQuery('#turnoff').removeClass('filterSelected');
		jQuery('#60s').addClass('filterSelected');
   		setCookie('refresh',60,30);
   		refreshInterval = setInterval(showScoresScheduleTab, 60*1000);
  	});
     jQuery('#turnoff').click(function() {
  		jQuery('#60s').removeClass('filterSelected');
		jQuery('#30s').removeClass('filterSelected');
		jQuery('#turnoff').addClass('filterSelected');
  		delete_Cookie('refresh');
  		clearInterval(refreshInterval);
   	});		


     jQuery('#scoresButton').click(function(){ //
 	 	showMatchesByTimeFrame();
  	 }); 
});	

function showScoreBoardByContinent(continent , continentId){
	jQuery('#GameSearchCriteria a').removeClass('filterSelected');
 	jQuery('#'+continent).addClass('filterSelected');
 	showScoresByRegion(continentId , continent);
}


</script>


      <div id="GameSearchCriteria" class="FriendLinks">

           <a id="topmatches" class="filterSelected" href="javascript:void(0)">Top Matches</a>
            |
            <a id="international" href="javascript:void(0)">FIFA</a>
            |
            <a id="europe" href="javascript:void(0)">Europe</a>
            |
            <a id="americas" href="javascript:void(0)">Americas</a>
            |
             <a id="africa" href="javascript:void(0)">Africa</a>
            |
            <a id="asia" href="javascript:void(0)">Asia & Pacific Islands</a>
      </div>
      
      <!--div class="RSSFeed">
        <a class="OrangeLink" href="#">RSS Scores Feed</a>
      </div-->

        <ul id="GameSearchCriteriaDrop">
          <li>
            <form id="homescores" name="homescores" action="">
              <input type="hidden" id="pageId" name="page" value="scores">
              <input type="hidden" id="regionId" name="regionId" value="">
              <input type="hidden" id="typeId" name="type" value="scores">
              Show: 
              <select id="dateId" name="date">
		                   <option value="today" <?php echo $this->selected == 'today'?'selected':''?>>Today</option>
		                   <option value="yesterday" <?php echo $this->selected == 'yesterday'?'selected':''?>>Yesterday</option>
		                   <option value="-3" <?php echo $this->selected == '-3'?'selected':''?>>Last 3 days</option>
		                   <option value="last" <?php echo $this->selected == ''?'last':''?>>Last 7 days</option>
		          </select>
		          <input style="display:inline;" type="button" id="scoresButton" value="Ok" class="submit">
            </form>
          </li>
        </ul>
        <!-- ul>
           <li style="margin-left:250px;padding:2px;"><a id="allm" href="javascript:void(0)">All Matches</a></li>
           <li style="padding:2px;">
              <a id="topmatches" href="javascript:void(0)">Top Matches</a>
           </li>
           <li style="padding:2px;">
              <a id="livematches" href="javascript:void(0)">Live Matches</a>
           </li>
        </ul-->
        
        <ul id="ThirdLevelOpenCloseAll">
           
          <li><a id="OpenRegionScores" class="filterSelected" href="javascript:openAll()">Open All</a> | <a id="CloseRegionScores" href="javascript:closeAll()">Close All</a></li>
          
        </ul>

    <div id="ScoresDataModule" class="ScorecardContainerRightColumn">
        <?php include 'scoreboardviewscores.php' ?>
    </div>
       
