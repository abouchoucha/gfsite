<script type="text/JavaScript">

    jQuery(document).ready(function() {

    	<?php if($this->continent != null){?>
    		showSchedulesByContinent('<?php echo($this->continentid);?>' , '<?php echo($this->continent);?>');
 		<?php }?>
        jQuery('#international').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#international').addClass('filterSelected');
            showScoresByRegion('8' , 'international');
        });
        jQuery('#europe').click(function(){
            showSchedulesByContinent('1' , 'europe');
        });
        jQuery('#americas').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#americas').addClass('filterSelected');
            showScoresByRegion('2' , 'americas');
        });
        jQuery('#africa').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#africa').addClass('filterSelected');
            showScoresByRegion('5' , 'africa');
        });
        jQuery('#asia').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#asia').addClass('filterSelected');
            showScoresByRegion('6' , 'asia');
        });
        jQuery('#topmatches').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#allm').removeClass('filterSelected');
            jQuery('#topmatches').addClass('filterSelected');
            showScoresScheduleTab('schedulesTab','n');
        });

        jQuery('#allm').click(function(){
            jQuery('#GameSearchCriteria a').removeClass('filterSelected');
            jQuery('#topmatches').removeClass('filterSelected');
            jQuery('#allm').addClass('filterSelected');
            showScoresByRegion('0' , 'all');
        });

        jQuery('#schedulesButton').click(function(){ //
            showMatchesByTimeFrame();
        });

    });

    function showSchedulesByContinent(continentId ,continent){
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

<!-- div class="RSSFeed">
<a class="OrangeLink" href="#">RSS Scores Feed</a>
</div-->


<ul id="GameSearchCriteriaDrop">
    <li>
        <form id="homescores" name="homescores" action="#">
            <input type="hidden" id="pageId" name="page" value="scores">
            <input type="hidden" id="regionId" name="regionId" value="">
            <input type="hidden" id="typeId" name="type" value="schedules">
            Show:
            <select id="dateId" name="date">
                <option value="tomorrow" <?php echo $this->selected == 'tomorrow'?'selected':''?>>Tomorrow</option>
                <option value="3" <?php echo $this->selected == '3'?'selected':''?>>Next 3 days</option>
                <option value="week" <?php echo $this->selected == 'week'?'selected':''?>>Next 7 days</option>
            </select>
            <input style="display:inline;" type="button" id="schedulesButton" value="Ok" class="submit">
        </form>
    </li>

</ul>
<!--ul>
<li style="margin-left:250px;padding:2px;"><a id="allm" href="javascript:void(0)">All Matches</a></li>
<li style="padding:2px;">
<a id="topmatches" href="javascript:void(0)">Top Matches</a>
</li>
</ul-->

<ul id="ThirdLevelOpenCloseAll">
      
  <li><a id="OpenRegionScores" class="filterSelected" href="javascript:openAll()">Open All</a> | <a id="CloseRegionScores" href="javascript:closeAll()">Close All</a></li>

</ul>

<div id="ScoresDataModule" class="ScorecardContainerRightColumn">
    <?php include 'scoreboardviewschedules.php' ?>
</div> 
