<?php require_once 'seourlgen.php';
	  require_once 'urlGenHelper.php';  
	  $urlGenHelper = new UrlGenHelper();
	  $urlGen = new SeoUrlGen();
	  $session = new Zend_Session_Namespace('userSession');
?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $offset = $config->time->offset->daylight;
 ?>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

jQuery(document).ready(function() {
    jQuery("#commentGoalShoutId").charCounter(400); 
	
    jQuery("span[id^='feature_matchDate']").each(function() {
		  var date_time = jQuery(this).text();  
		  var date = new Date(date_time);
		  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
		  jQuery(this).text(formatDate(date_time_os, 'MMM dd,yyyy'));
	});
	
	jQuery("span[id^='feature_matchHour']").each(function() {
		  var date_time = jQuery(this).text();
		  var date = new Date(date_time);
		  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
		 jQuery(this).text(formatDate(date_time_os, 'HH:mm'));
	});


        //setup tabs - featured players and teams
        jQuery('#menu6content,#menu7content').hide();
        jQuery('#menu6content').show();
        jQuery('#menu6').addClass('active');
        jQuery('#featTab ul li').click(function(){
           // var thisClass = this.className;
            jQuery('#menu6content,#menu7content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            //show see more header link
            jQuery('#menu6_more,#menu7_more').hide();
            jQuery('#' + tab_id + '_more').show();
            jQuery('#menu6,#menu7').removeClass('active');
            jQuery(this).addClass('active');
       });

            //Setup Tabs - Featured Matches
       jQuery('#menu4content,#menu5content').hide();
       <?php if($this->nextMatch != null) {?>
            jQuery('#menu4content').show();
            jQuery('#menu4').addClass('active');
       <?php } else { ?>
            jQuery('#menu5content').show();
            jQuery('#menu5').addClass('active');
       <?php }  ?>
       jQuery('#matchTab ul li').click(function(){
            jQuery('#menu4content,#menu5content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            //show see more header link
            jQuery('#menu4_more,#menu5_more').hide();
            jQuery('#' + tab_id + '_more').show();
            jQuery('#menu4,#menu5').removeClass('active');
            jQuery(this).addClass('active');
        });
        
      //Setup Tabs - Head to Head
       jQuery('#menu1content,#menu2content').hide();
       jQuery('#menu1content').show();
       jQuery('#menu1').addClass('active');
        jQuery('#h2hTab ul li').click(function(){
            jQuery('#menu1content,#menu2content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu1,#menu2').removeClass('active');
            jQuery(this).addClass('active');
        
        });

        //Setup Tabs - WorldcupStats
       jQuery('#menu8content,#menu9content,#menu10content,#menu11content,#menu12content,#menu13content,#menu21content,#menu22content').hide();
       jQuery('#menu9content').show(); // goals default view
       jQuery('#menu9').addClass('active');
       jQuery('#statTab ul li').click(function(){
           
            jQuery('#menu8content,#menu9content,#menu10content,#menu11content,#menu12content,#menu13content,#menu21content,#menu22content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();

            //hide divs for wc 2010
            if (tab_id == 'menu9') {
               jQuery('#alltime,#wc2010').hide();
               jQuery('#wc2010').show();
            } else {
               jQuery('#alltime,#wc2010').hide();
               jQuery('#alltime').show();          
            }
            jQuery('#menu8,#menu9').removeClass('active');
            jQuery(this).addClass('active');

        });

        // Other Stat Divs    
        jQuery('#statsdropdown').change(function() {
              jQuery('#menu8content,#menu9content,#menu10content,#menu11content,#menu12content,#menu13content,#menu21content,#menu22content').hide();
              tab_id = jQuery(this).val();
              //show div content
              jQuery('#' + tab_id + 'content').show();
         });

         jQuery('#statsdropdownwc2010').change(function() {
              jQuery('#menu8content,#menu9content,#menu10content,#menu11content,#menu12content,#menu13content,#menu21content,#menu22content').hide();
              tab_id = jQuery(this).val();
              //show div content
              jQuery('#' + tab_id + 'content').show();
         });



        callRandonProfiles();
        <?php if($this->leagueId == 72) { ?>
        callFeaturedNews();
        <?php } ?>
		callHomeCompetitionGallery();
        //league round table default view
        //showLeagueRoundTables();

        //assign show stats function to event click "ok" button in dropdown
	//jQuery('#tablesidbutton').click(function(){
 	//	showLeagueRoundTables();
	// });

	jQuery('#addtofavoritecompetitiontrigger').click(function(){
		 addremovefavoritecompetition('add');
	});
	 jQuery('#removefromfavoritecompetitiontrigger').click(function(){
		 addremovefavoritecompetition('remove');
	});

    
    var seasonId = '<?php echo $this->seasonId; ?>';
    var activeSeason = '<?php echo $this->seasonActive; ?>';
    var archivedRoundActive = '<?php echo $this->roundId; ?>';



    jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
    
    if(seasonId == activeSeason){
        //current season matches;
        var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showmatchesbycountry';
        //load the first list by default in scores    
        
        //url = urlBase + 'today';
        //jQuery('#scoreboardResult').load(urlBase ,{countryid :'<?php echo $this->countryId;?>@last' , leagueid:'<?php echo $this->leagueId;?>' } );
        loadScoreBoardByTimeFrame('l7', urlBase , 'last' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@last','<?php echo $this->leagueId;?>');
    } else {
        //archived season matches;
        var page = 'scores';
        var pagecoming = 'comppage'
        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/roundid/'+archivedRoundActive+'/type/'+page+'/page/'+pagecoming;
       
       jQuery('#schedulesTabLi').hide();
       jQuery('#ScoresDateFilter').hide();
       jQuery('#scoreboardResult').load(url);
    }


    jQuery('#scoresTab').click(function() {
		loadScoresTab('scoresTabLi', urlBase, 'today', 'scoreboardResult' , 'ScoresDateFilter' ,'today' , 'tab_2' , '<?php echo $this->countryId;?>@today','<?php echo $this->leagueId;?>');
	 	jQuery('#tab_3').hide();
	});

    jQuery('#schedulesTab').click(function() {
		loadScoresTab('schedulesTabLi', urlBase, 'tomorrow', 'scoreboardResult2' , 'SchedulesDateFilter' ,'tomorrow' , 'tab_3' , '<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>' , '<?php echo $this->leagueId;?>');
	 	jQuery('#tab_2').hide();
	});

    //scores
    jQuery('#today').click(function(){
		loadScoreBoardByTimeFrame(this.id, urlBase , 'today','scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>','<?php echo $this->leagueId;?>');	
	 });

    jQuery('#l3').click(function(){
		loadScoreBoardByTimeFrame(this.id, urlBase , '-3' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@-3','<?php echo $this->leagueId;?>');			
     });

    jQuery('#l7').click(function(){
		loadScoreBoardByTimeFrame(this.id, urlBase , 'last' ,'scoreboardResult' ,'ScoresDateFilter','<?php echo $this->countryId;?>@last','<?php echo $this->leagueId;?>');			
     });
     //schedules
	jQuery('#tomorrow').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'tomorrow' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>','<?php echo $this->leagueId;?>');			
     });
	jQuery('#n3').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , '3' ,'scoreboardResult2' ,'SchedulesDateFilter','<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>','<?php echo $this->leagueId;?>');			
     });
	jQuery('#n7').click(function(){			
		loadScoreBoardByTimeFrame(this.id, urlBase , 'week' ,'scoreboardResult2' ,'SchedulesDateFilter' ,'<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>','<?php echo $this->leagueId;?>');			
     });

     //photos
    jQuery('#buttonPhotos').click(function(){ //
        showHomeCompetitionGallery();
    });

	showHideDivBox('competitionsScoresId','competitionsScoresBodyId');

	jQuery('#teamplayerselectida').change(function(){
    	var teamid = jQuery("#teamplayerselectida").val();
    	populateCombo('playerselectida', teamid);
    }); 

	jQuery('#teamplayerselectidb').change(function(){
    	var teamid = jQuery("#teamplayerselectidb").val();
    	populateCombo('playerselectidb', teamid);
    });
    
	//Click the button event! Head to head WC Teams
	jQuery('#compTeamsWC').click(function(){
		validateHead2HeadSelectTeams();
		var teama = jQuery("#teamselectida").val();
		var teamb = jQuery("#teamselectidb").val();
		if(teama != '0' && teamb != '0' && teama != teamb) {
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/teama/'+teama+'/teamb/'+teamb+'/competitionid/72';
			top.location.href = url;			
		}
	});	

	//Click the button event! Head to head WC Players
	jQuery('#compPlayersWC').click(function(){
		validateHead2HeadSelectPlayers();
		var teama = jQuery("#teamplayerselectida").val();
		var teamb = jQuery("#teamplayerselectidb").val();
		var playera = jQuery("#playerselectida").val();
		var playerb = jQuery("#playerselectidb").val();
		if(teama != '0' && teamb != '0' &&
				playera != '0' && playerb != '0' && playera != playerb) {
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headplayers/teama/'+teama+'/teamb/'+teamb+'/playera/'+playera+'/playerb/'+playerb+'/competitionid/72';
			top.location.href = url;			
		}
	});	
		 	
});

function validateHead2HeadSelectTeams() {
	var teama = jQuery("#teamselectida").val();
	var teamb = jQuery("#teamselectidb").val();
	if(teama == teamb) {
		jQuery('#errorTeamsWC').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
	} else {
		jQuery('#errorTeamsWC').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	}	
}

function validateHead2HeadSelectPlayers() {
	var playera = jQuery("#playerselectida").val();
	var playerb = jQuery("#playerselectidb").val();
	if(playera == playerb) {
		jQuery('#errorPlayersWC').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
	} else {
		jQuery('#errorPlayersWC').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	}	
}

function callRandonProfiles()
{
    jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom/competitionId/<?php echo $this->leagueId;?>',
        dataType : 'text',
        success: function (text) {
            jQuery('#randomprofiles').html(text);
        }
    });

}

function callHomeCompetitionGallery(){
    jQuery('#PhotoGalleryList').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomepagesphotos/numphotos/4/typeid/3/id/<?php echo $this->leagueId;?>',
        dataType : 'text',
        success: function (text) {
			jQuery('#PhotoGalleryList').html(text);
		}
     });
}
function callFeaturedNews(){

    jQuery('#FeaturedNewsList').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/news/homefeaturednews/numfeeds/5/cat/<?php echo urlencode("world cup");?>',
        dataType : 'text',
        success: function (text) {
                jQuery('#FeaturedNewsList').html(text);
		}
     });

}
function showHomeCompetitionGallery () {
	var numPhotos = jQuery("#slcgallery").val();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomepagesphotos/numphotos/'+numPhotos+'/typeid/3/id/<?php echo $this->leagueId;?>';
	jQuery('#PhotoGalleryList').html('Loading...');
	jQuery('#PhotoGalleryList').load(url);


}

function loadScoresTab(tabid, url, date, container , filter ,defaultsearchid , tabtoshow , countryid , leagueId){

	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	jQuery('li.Selected').removeClass('Selected');
    jQuery('#'+tabid).addClass('Selected');
    jQuery('#'+ filter+' a').removeClass('filterSelected');
 	jQuery('#'+defaultsearchid).addClass('filterSelected');
    jQuery('#'+container).html('');
    jQuery('#'+container).html("<div class='ajaxload widget'></div>");
	jQuery('#'+tabtoshow).show();
	jQuery('#'+container).load(url ,{countryid :countryid , date:date , leagueid : leagueId, initDateTime : initDateTime, endDateTime : endDateTime} );
	
}
function loadScoreBoardByTimeFrame(id, url, date, container , filter ,countryid ,leagueid)
{
	var initDateTime = formatDate(getCurrentInitTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	var endDateTime = formatDate(getCurrentEndTime(+2.0),'yyyy-MM-dd HH:mm:ss');
	 jQuery('#'+ filter+' a').removeClass('filterSelected');
 	 jQuery('#' + id).addClass('filterSelected');
	 jQuery('#' + container).html("<div class='ajaxload widget'></div>"); 			
	 jQuery('#' + container).load(url , {date : date , countryid :countryid , leagueid : leagueid, initDateTime : initDateTime, endDateTime : endDateTime});
} 

	
function showMatchesByTimeFrame(target , button  , select, url){

        jQuery('#' +button).click(function(){ //se ejecuta con el evento onCLick
                var $this = jQuery('#'+select);
                var dateId = $this.val();
                jQuery('#' +target).html('Loading...');
                jQuery('#' +target).load(url ,{countryid :'<?php echo $this->countryId;?>@<?php echo $this->leagueId;?>' ,date : dateId ,leagueId:'<?php echo $this->leagueId;?>' } );
          });
}
	
function addremovefavoritecompetition(type){

         jQuery('#modalBodyId').show();
         jQuery('#modalBodyResponseId').hide();
         jQuery('#acceptFavoriteModalButtonId').show();
         jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
         if(type == 'add'){
                jQuery('#modalFavoriteTitleId').html('Add <?php echo $this->compName;?> as your favorite competition?');
         }else if(type == 'remove'){
                 jQuery('#modalFavoriteTitleId').html('Remove <?php echo $this->compName;?> as your favorite competition?');
         }	 	 
         jQuery('#dataText1').html('<?php echo $this->compName;?>');
         jQuery('#title1Id').html('Country:');
         jQuery('#dataText2').html("<?php echo $this->countryName; ?>");
         jQuery('#dataText2').attr('href','<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryName,$this->countryId, true); ?>');

         if(type == 'add'){
                jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoritecompetitiontrigger', onHide: closeModal });
         }else if(type == 'remove'){
                 jQuery('#addFavoriteModal').jqm({trigger: '#removefromfavoritecompetitiontrigger', onHide: closeModal });
         }
         jQuery('#addFavoriteModal').jqmShow();

         var favoriteImage = null;

        <?php
         $config = Zend_Registry::get ( 'config' );
         $path_comp_logos = $config->path->images->complogos . $this->leagueId.".gif" ;

         if (file_exists($path_comp_logos)){  ?>
       			favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/competitionlogos/<?php  echo $this->leagueId .'.gif'?>';
		<?php } else {  ?>
				favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/LeagueText120.gif';
    	<?php  } ?>

		 
		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var leagueId = '<?php echo $this->leagueId; ?>*<?php echo $this->countryId; ?>';	
		
		 var url = null;
		 var htmlResponse = null;
		 if(type == 'add'){ 
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/addfavorite' ;
			 htmlResponse = 'Competition <?php echo $this->compName;?> has been added to your favorite competitions.';
		 }else if(type == 'remove'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/removefavorite' ;
			 htmlResponse = 'Competition <?php echo $this->compName;?> has been removed from your favorite competitions.';
		 }	 	 
		 


		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  url,
				data : ({leagueId:leagueId }),
				success: function(data){
					jQuery('#modalBodyResponseId').html(htmlResponse);
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					if(type == 'add'){ 
						jQuery('#favorite').hide();
					 	jQuery('#remove').show()
					}else if(type == 'remove'){
				 		jQuery('#remove').hide();
				 		jQuery('#favorite').show();
				 	}		
				 	 
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
			})
			
		 });	
	}	


function addGoalShout(){

    var commentText = jQuery('#commentGoalShoutId').val();
    if(jQuery.trim(commentText) == ''){
        jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
        return;
    }
    var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout';

    var commentType = 7;
    var idtocomment = '<?php echo $this->leagueId; ?>';
    var screennametocomment = '<?php echo $session->screenName;?>';
    var countryid = '<?php echo $this->countryId;?>';
    
    jQuery('#goalshoutId').load(url ,{leagueid :idtocomment , countryid : countryid , commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment , comment : commentText});
    jQuery('#commentGoalShoutId').val('');

}



 function populateCombo(dtarget , id){
	 var url = null;
	 var ajaxload = null;
     url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showworldcupplayerbyteam/id/'+id;
     ajaxload = 'ajaxloaderTeamPlayer';
	 jQuery('#'+ajaxload).show();
		 jQuery('#'+dtarget).load(url , function(){
			 jQuery('#'+ajaxload).hide();
		 });
 }
 
</script>

<div id="ContentWrapper">
  <p class="flags">
      <span style="background-image: url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/flags/32x32/<?php echo $this->countryCodeIso; ?>.png);" class="flagtitle">
           <?php if ($this->compFormat == 'International cup') { ?>
              <?php echo $this->regionNameTitle; ?> - <?php echo $this->compName; ?>
              <?php } else { ?>
              <?php echo $this->countryName; ?> - <?php echo $this->compName; ?>
           <?php } ?>
      </span>
  
        <?php if($this->isFavorite == 'false') { ?>
             <?php if($session->email != null){ ?>
              <span id="favorite" class="add">
                  <a id="addtofavoritecompetitiontrigger" href="#">Add to Favorites</a>
              </span>
               <span id="remove" class="remove" style="display:none">
                  <a id="removefromfavoritecompetitiontrigger" href="#">Remove from Favorites</a>
              </span>
             <?php } else {?>
               <span id="favorite" class="add">
                  <a id="addtofavoritecompetitionNonLoggedtrigger" onclick="loginModal()" href="#">Add to Favorites</a>
              </span>
             <?php } ?>
         <?php }else {?>
             <span id="favorite" class="add" style="display:none">
                  <a id="addtofavoritecompetitiontrigger" href="#">Add to Favorites</a>
              </span>
               <span id="remove" class="remove">
                  <a id="removefromfavoritecompetitiontrigger" href="#">Remove from Favorites</a>
              </span>
         <?php }?>
  
      <?php if ($server == 'local') { ?>
      
         <span class="pre">

           <span class="lea">Archive</span>
        
              <select onchange="document.seasondropdown.submit();" id="seasonId" name="seasonId" class="sel">
                     <?php foreach($this->seasonList as $season) { ?>
                          <option value="<?php echo $season["season_id"]; ?>" <?php echo ($this->seasonId == $season["season_id"]?'selected':'')?> ><?php echo $season["title"]; ?></option>
                        <?php }  ?>
              </select>

          </span>
        
   	 <?php } ?>
              
  </p>
  
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
      
			<?php echo $this->render('include/feedbackbadge.php')?>
            <?php } else {?>


            <!--Me box Non-authenticated-->
            <div class="img-shadow">
                <div class="WrapperForDropShadow">
                    <?php include 'include/loginNonAuthBox.php';?>
                </div>
            </div>
              <?php echo $this->render('include/feedbackbadge.php')?>
        <?php } ?>

        <div id="leftnav" class="img-shadow">
            <?php echo $this->render('include/navigationCompetitionNew2.php');?>
        </div>
        
   </div><!--/FirstColumn-->
  
   <div class="SecondColumn">
   
    <!--New  Featured  Matches -->
      <div class="prof">
          <p class="mblack">
              <span class="black">Featured Matches</span>
              <span id="menu4_more" class="sm"><a href="<?php echo $urlGen->getTablesUrl("schedules",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">More &raquo;</a></span>
              <span id="menu5_more" class="sm" style="display:none;"><a href="<?php echo $urlGen->getTablesUrl("scores",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>">More &raquo;</a></span>          </p>
          <div id="matchTab" class="nxt">
              <ul>
                  <li id="menu4"><a href="javascript:void(0);">Next Match</a></li>
                  <li id="menu5"><a href="javascript:void(0);">Latest Match</a></li>
              </ul>
          </div>
          <div class="nmatch" id="menu4content">
             <?php if($this->nextMatch != null) {?>
                         <p class="kick">
             		Date: <span>
	                    	<span id="feature_matchDate_nxt">
	                    		<?php echo date ('l - F j , Y' , strtotime($this->nextMatch['mdate'])) ;?>
	                    	</span>	
                    	</span> <br> 		
             				
                    Kickoff: <span>
                    	<span id="feature_matchHour_nxt">
                    	<?php echo date('Y/m/d H:i:s',strtotime($this->nextMatch['mdate'] ." ". $this->nextMatch['TIME'])) ?> 
                    	</span>
					</span>
                    <br>
                 </p>
   
                  <div class="vss">
                          <p class="tfa">
                          
                              <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch["teama"], $this->nextMatch["teamb"], $this->nextMatch["matchid"], true);?>">
                               <?php
                                  $config = Zend_Registry::get ( 'config' );
                                  $path_team_logos = $config->path->images->teamlogos . $this->nextMatch["cteama"].".gif" ;

                                  if (file_exists($path_team_logos))
                                  {  ?>
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->nextMatch["cteama"]?>.gif" alt="<?php echo $this->nextMatch['teama'] ?>"/>

                                  <?php } else {  ?>

                                    <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif" alt="<?php echo $this->nextMatch['teama'] ?>">

                              <?php }   ?>
                              </a>
                              <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch["teama"], $this->nextMatch["teamb"], $this->nextMatch["matchid"], true);?>">

                                  <?php echo $this->nextMatch['teama'] ?>
                              </a>
                          </p>
                          
                            <?php if (($this->nextMatch["status"] == 'Playing') OR ($this->nextMatch["status"] == 'Fixture'))  {
                            	  if($this->nextMatch["status"] == 'Playing'){?>	
                                       <p class="vs1" style="width:77px;">
                                        <?php echo $this->nextMatch["fs_team_a"];?> - <?php echo $this->nextMatch["fs_team_b"];?><br>
                                      Playing &nbsp;<?php echo $this->nextMatch["match_minute"];?></p>
                           	 <?php } else { ?>
                              	<p class="vs1">vs</p>
                            	<?php }
                            		}  
                            	 ?>
            
                          <p class="fff">

                              <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch["teama"], $this->nextMatch["teamb"], $this->nextMatch["matchid"], true);?>">
                          	<?php
                              $path_team_logos = $config->path->images->teamlogos . $this->nextMatch["cteamb"].".gif" ;

                              if (file_exists($path_team_logos))
                              {  ?>
                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->nextMatch["cteamb"]?>.gif" alt="<?php echo $this->nextMatch['teamb'] ?>"/>

                              <?php } else {  ?>

                                <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif" alt="<?php echo $this->nextMatch['teamb'] ?>">

                          	<?php }   ?>
                          
                          	</a>
                          	<a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch["teama"], $this->nextMatch["teamb"], $this->nextMatch["matchid"], true);?>">
                                <?php echo $this->nextMatch['teamb'] ?>
                             </a>
                          
                          </p>
                  </div>
                  <p class="smatch">
                      <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch["teama"], $this->nextMatch["teamb"], $this->nextMatch["matchid"], true);?>">
                        <?php if (($this->nextMatch["status"] == 'Playing') OR ($this->nextMatch["status"] == 'Fixture'))  {?>
                          See Match Details &raquo;
                        <?php } else {  ?>
                          See Match Preview &raquo;
                        <?php }  ?>
                      </a>
                  </p>
                  <p class="smatch1">
                      <a class="OrangeLink" href="<?php echo $urlGen->getTablesUrl("schedules",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>"><?php echo $this->compName  ?> Match Schedule</a>
                  </p>
                <?php }else {
                  	echo "<br><center><strong>No Matches scheduled yet.</strong></center>";
                  }?>
              
          </div>
          
          <div class="nmatch" id="menu5content">
                  <?php if($this->previousMatch != null) {?>
                   <p class="kick">
                    Date: <span>
	                    	<span id="feature_matchDate_nxt">
	                    		<?php echo date ('l - F j , Y' , strtotime($this->previousMatch['mdate'])) ;?>
	                    	</span>	
                    	</span> <br> 
                    Kickoff: 
                    <span>
                    	<span id="feature_matchHour_nxt">
                    	<?php echo date('Y/m/d H:i:s',strtotime($this->previousMatch['mdate'] ." ". $this->previousMatch['TIME'])) ?> 
                    	</span>
                    </span><br>
                  </p>
                  <div class="vss">
                          <p class="tfa">

                           <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->previousMatch["cteama"], $this->previousMatch["teamaseoname"], True); ?>">
                          <?php
                              $config = Zend_Registry::get ( 'config' );
                              $path_team_logos = $config->path->images->teamlogos . $this->previousMatch["cteama"].".gif" ;

                              if (file_exists($path_team_logos))
                              {  ?>
                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->previousMatch["cteama"]?>.gif"/>

                              <?php } else {  ?>

                                <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif">

                          <?php }   ?>
                          </a>
                          <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch["teama"], $this->previousMatch["teamb"], $this->previousMatch["matchid"], true);?>">
                              <?php echo $this->previousMatch['teama'] ?>
                          </a>
                         
                          
                          </p>
                          
                            
                              <p class="vs1">
                              <?php echo $this->previousMatch["fs_team_a"];?> - <?php echo $this->previousMatch["fs_team_b"];?>
                              <br>Final</p>
                  			                                         
                          
                          <p class="fff">

	                          <a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch["teama"], $this->previousMatch["teamb"], $this->previousMatch["matchid"], true);?>">
	                          
	                           <?php
	                              
	                              $path_team_logos = $config->path->images->teamlogos . $this->previousMatch["cteamb"].".gif" ;
	
	                              if (file_exists($path_team_logos))
	                              {  ?>
	                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->previousMatch["cteamb"]?>.gif"/>
	
	                              <?php } else {  ?>
	
	                                <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif">
	
	                          <?php }   ?>
	                          </a>
	                           <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->previousMatch["cteamb"], $this->previousMatch["teambseoname"], True); ?>">
	                           <?php echo $this->previousMatch['teamb'] ?>
	                           </a>
                          
                          </p>
                  </div>
                  <p class="smatch"><a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch["teama"], $this->previousMatch["teamb"], $this->previousMatch["matchid"], true);?>">See Match Details &raquo;</a></p>

                  <p class="smatch1"><a class="OrangeLink" href="<?php echo $urlGen->getTablesUrl("scores",$this->roundId ,$this->countryName ,$this->seasonTitle ,  $this->compName ,True) ?>"><?php echo $this->compName;?> Match Scores</a></p>
                  <?php }else {
                  	echo "<br><center><strong>No Matches played yet.</strong></center>";
                  }?>
          </div>
      </div>
      
          <!--New  Featured  Teams and Players -->
     <div class="prof">
       	<p class="mblack">
              <span class="black">Featured Teams &amp; Players</span>
              <span id="menu6_more" class="sm"><a href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">More &raquo;</a></span>
              <span id="menu7_more" class="sm" style="display:none;"><a href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">More &raquo;</a></span>                        
        	</p>
        
          <div id="featTab" class="nxt">
              <ul>
                  <li id="menu6"><a href="javascript:void(0);">Teams</a></li>
                  <li id="menu7"><a href="javascript:void(0);">Players</a></li>
              </ul>
          </div>
          
         <div class="nmatch" id="menu6content">
          <?php
              $config = Zend_Registry::get ( 'config' );
              $teamCounter = 0;
              if(sizeOf($this->featuredTeams) == 0){
              	echo "<br><center><strong>No Data Available.</strong></center>";
              }else {
              
              
              foreach ($this->featuredTeams  as $data) {
               $teamCounter++;
          ?>
          <?php if($teamCounter==1){  ?>
            <div class="imgs">
          <?php }  ?>

              <p class="<?php if($teamCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">

                  <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                     <?php 
                        $path_team_logos = $config->path->images->teamlogos . $data['team_id'].".gif" ;   
                        if (file_exists($path_team_logos)) { ?> 
                          <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $data['team_id']?>.gif" alt="<?php echo $data['team_name'];?>"/>
                      <?php } else { ?>
                          <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText80.gif" alt="<?php echo $data['team_name'];?>"/>
                      <?php } ?>
                  </a>
                  <a href="<?php echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_seoname'], True); ?>" title="<?php echo $data['team_name'];?>">
                    <?php echo $data['team_name'];?>
                  </a>

              </p>

           <?php if($teamCounter==3){ $teamCounter = 0; ?>
              </div>
            <?php } ?>

              <?php } 
              }?>

                <p class="smatch1">
                    <a class="OrangeLink" href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE);?>">See More Featured Teams</a>
                </p>
            </div>
            
        <div class="nmatch" id="menu7content">
          <?php
              $config = Zend_Registry::get ( 'config' );
              $playerCounter = 0;
              if(sizeOf($this->featuredPlayers) == 0){
              	echo "<br><center><strong>No Data Available.</strong></center>";
              }else {
              foreach ($this->featuredPlayers  as $pp) {
               $playerCounter++;
          ?>
          
              <?php if($playerCounter==1){  ?>
                  <div class="imgs">
               <?php }  ?>

               <p class="<?php if($playerCounter==2){ echo "tfa1"; } else { echo "tfa"; } ?>">
                             
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_name_short"] ?>">
                          <?php
                             		if ($pp["imagefilename"]!='' or $pp["imagefilename"]!=null ){
                           ?>
                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/players/<?php echo $pp["imagefilename"] ?>" alt="<?php echo $pp["player_common_name"];?>"/>
                                   <?php } else  { ?>
                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/Player1Text80.gif" alt="<?php echo $pp["player_common_name"];?>"/>
                                  <?php } ?>
                      </a>
                      <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_name_short"] ?>">
                                <?php echo $pp["player_name_short"];?>
                      </a>
                </p>

           <?php if($playerCounter==3){ $playerCounter = 0; ?>
              </div>
            <?php } ?>

              <?php }
              } ?>

                <p class="smatch1">
                    <a class="OrangeLink" href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE);?>">See More Featured Players</a>
                </p>
            </div>
            
        
        </div>
     
     
     	<!-- Competition Table -->
            <?php if ($this->compFormat == 'Domestic league') { ?>
                    <div class="img-shadow">
                      <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                <h4 class="NoArrowLeft"><?php echo $this->compName; ?> Table <?php //echo $this->path; ?></h4> <?php  //echo $this->totalrounds; ?>
                            </div>
                             <?php  if ($this->totalrounds > 1) {  ?>
                                 <div class="BlueShaded DisplayDropdown">
                                  Show:
                                      <select id="leagueRoundId" class="slct" name="leagueRoundId">
                                                <?php foreach($this->roundList as $rounds) { ?>
                                                   <?php  if ($rounds["round_id"] == $this->currentActiveRound) {
                                                        $showselected = "selected";
                                                          } else {
                                                        $showselected = "";
                                                 } ?>
                                                    <option value="<?php echo $rounds["round_id"]; ?>" <?php echo $showselected; ?>>
                                                         <?php echo $rounds["round_title"]; ?>
                                                    </option>
                                                  <?php } ?>
                                       </select>
                                        <input type="submit" value="OK" class="submit" style="display:inline;" id="tablesidbutton">                                 
                                  </div>
                            <?php } ?>

                                 <div id="leaguetables">
			                     	<?php if ($this->leagueTable != null) { ?>   
			    
			                     	  <table id="LeagueTable" width="100%" cellspacing="0" cellpadding="1" border="0">
			                  
	                                      <!--   <tr class="gheading">
	                                          <td colspan="8"></td>
	                                        </tr>-->
	                                        <tr class="lheading"  align="left">
	                                            <th class="lheading">#</th>
	                                            <th class="lheading">&nbsp;</th>
	                                            <th class="lheading">Team</th>
	                                            <th class="lheading">MP</th>
	                                            <th class="lheading">W</th>
	                                            <th class="lheading">D</th>
	                                            <th class="lheading">L</th>
	                                            <th class="lheading">Pts</th>
	                                         </tr>
	                                            <?php $i = 1; ?>
	                                            <?php   foreach ($this->leagueTable as $ranking ) {  ?>
	                                                <?php
	                                                     if($i % 2 == 1) {
	                                                    $style = 'odd';
	                                                    //$hoverstyle = $hovercolor1;
	                                                 }else{
	                                                    $style = 'even';
	                                                //$hoverstyle = $hovercolor2;
	                                                 }
	                                               ?>
	                                            <tr class="<?php echo $style; ?>">
	                                                    <td class="first"><?php echo $ranking['rank'] ?></td>
	                                                    <td>
	                                                        
	                                                    </td>
	                                                    <td><a href="<?php echo $urlGen->getClubMasterProfileUrl($ranking['team_id'],$ranking['team_seoname'], True); ?>">
	                                                    
	                                                     <?php //echo mb_convert_encoding($ranking['name'], "ISO-8859-1", "UTF-8");  ?>
	                                                     <?php echo $ranking['name'];?>
	                                                    </a> </td>
	
	                                                    <td><?php echo $ranking['played'] ?></td>
	                                                    <td><?php echo $ranking['wins'] ?></td>
	                                                    <td><?php echo $ranking['draws'] ?></td>
	                                                    <td><?php echo $ranking['defeits'] ?></td>
	                                                    <td class="last"><?php echo $ranking["points"] ?></td>
	                                            </tr>
	
	                                          <?php $i++; } ?>
			                        </table>
			                     	 <?php } else { ?>
			                     	    <br><center><strong>No Data available.</strong></center><br>
			                     	 <?php } ?>
			                    </div>

                            <div class="SeeMoreNews">
                                <a class="OrangeLink" title="See Wide Table" href="<?php echo $urlGen->getTablesUrl("tables",$this->roundId ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">See Wide Table »</a>
                            </div>
                      
                      </div>
                    </div>
               <?php } else { ?>
               
               	<?php if($this->roundType == 'table'){ ?>
      				<div class="prof2">
                        <p class="mblack">
                            <span class="black"><?php echo $this->compName; ?></span>
                            <span class="sm">
                                <a href="<?php echo $urlGen->getTablesUrl("tables",$this->roundId ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>">More &raquo;</a>
                            </span>
                        </p>
                        
                       <!--BEGIN Group html --> 
                       <?php foreach ($this->leagueTable as $key=>$group ){ ?>
                       	<div class="groupa">
                            <p class="grpa"><?php echo $key;?></p>
                               <ul>
                                  <li class="flag">Team</li>
                                  <li class="name">&nbsp;</li>
                                  <li class="cont">GP</li>
                                  <li class="cont">W</li>
                                  <li class="cont">D</li>
                                  <li class="cont">L</li>
                                  <li class="cont"> Pts</li>
                               </ul>
                          </div>
                          <?php $i = 1; 
                          		foreach ($group as $ranking ) {  ?>
                               <?php if($i % 2 == 1) { $style = 'groupa1'; }else{ $style = 'groupa2';}?>
                        			<div class="<?php echo $style; ?>">
                                       <ul>
                                          <li class="flag">
                                                <?php require_once 'Team.php';
                                                       $team = new Team();
                                                        $row = $team->fetchRow($team->select()->where('team_id = ?',  $ranking['team_id']));
                                                        $country_flag = $row->country_id .".png";
                                                 ?>
                                                  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $country_flag; ?>" alt="<?php echo $ranking['name']; ?>">
                                          </li>
                                           <li class="name">
                                              <a href="<?php echo $urlGen->getClubMasterProfileUrl($ranking['team_id'],$urlGenHelper->replace(' ','-',$ranking['name']), True); ?>"><?php echo $ranking['name']; ?></a>
                                          </li>
                                          <li class="cont"><?php echo $ranking['played'] ?></li>
                                          <li class="cont"><?php echo $ranking['wins'] ?></li>
                                          <li class="cont"><?php echo $ranking['draws'] ?></li>
                                          <li class="cont"><?php echo $ranking['defeits'] ?></li>
                                          <li class="cont"><?php echo $ranking["points"] ?></li>
                                      </ul>
                                     </div>
                       			<?php $i++; }
                          			} ?>
                          		
                          		<p class="worldcup">
                          			<a class="OrangeLink" href="<?php echo $urlGen->getTablesUrl("tables",$this->roundId ,$this->countryName ,$this->seasonTitle , $this->compName,True) ?>" title="<?php echo $this->compName; ?> Full Table" href="">See <?php echo $this->compName; ?> Full Table</a>
                          		</p>

                     		</div>
                     	<?php } else { ?>
			               <div class="featured1" style="margin-top: 12px;">
			                    <p class="mblack">
			                        <span class="black"><?php echo $this->compName;?></span>
			                        <span class="sm">
			                            <a href="">More &raquo;</a></span>
			                    </p>
			
			                     <!-- Dropdown for scoreboard module
			                    <p class="all" id="stages">
			                        <select id="scoresdropdown">
			                            <option value="score1">&nbsp;Knock Out Stages</option>
			                            <option value="score2">&nbsp;Group Stage</option>
			                        </select>
			                    </p> -->
			                    <div id="menuXcontent" class="cont">
			                        <?php $i = 1; ?>
			                        <?php $roundname = ''; ?>
			                        <?php $matchdate = ''; ?>
			                        <?php foreach ( $this->allMatchesCompetition as $match ) {  ?>
			                    
			                           <?php if($match['round_title']!='Group Stage') {?>
			                             <?php if($matchdate != $match['mdate']) {?>
			                                 <div class="scores">
			                                    <ul>
			                                        <li class="roundname"><?php echo date('D M j',strtotime($match['mdate'])); ?>&nbsp;-&nbsp;<?php echo $match['round_title']; ?></li>
			                                    </ul>
			                                 </div>
			                            <?php } ?>
			
			                            <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
			                                <div class="<?php echo $style; ?>">
			                                    <ul>
			                                        <?php if($this->escape($match["teama"]) == ''){ ?>
			                                           <li class="teamname" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/tbd.gif') no-repeat left center;">
			                                            &nbsp;&nbsp;TBD
			                                           </li>
			                                        <?php } else {  ?>
			                                            <li class="teamname" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=14&h=14&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/teamlogos/<?php echo $match["cteama"]; ?>.gif') no-repeat left top;">
			                                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
			                                                <?php echo $match['teama']; ?>
			                                              </a>
			                                            </li>
			                                        <?php }  ?>
			                                        <?php if($match['status'] == 'Fixture') {?>
			                                            <li class="score">vs</li>
			                                         <?php } else{ ?>
			                                            <li class="score"><?php echo $match['fs_team_a']; ?>&nbsp;-&nbsp;<?php echo $match['fs_team_b']; ?></li>
			                                        <?php }  ?>
			                                        <?php if($this->escape($match["teamb"]) == ''){ ?>
			                                           <li class="teamname" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/tbd.gif') no-repeat left center;">
			                                           &nbsp;&nbsp;TBD
			                                           </li>
			                                        <?php } else {  ?>
			                                            <li class="teamname" style="background:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=14&h=14&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/teamlogos/<?php echo $match["cteamb"]; ?>.gif') no-repeat left top;">
			                                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
			                                                 <?php echo $match['teamb']; ?>
			                                              </a>
			                                            </li>
			                                        <?php }  ?>
			                                          <li>
			                                          <?php if($match['status'] == 'Fixture') {?>
			                                             <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
			                                              Preview &raquo;
			                                             </a>
			                                          <?php } else {  ?>
			                                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
			                                              Details &raquo;
			                                             </a>
			                                          <?php }  ?>
			
			                                        </li>
			                                    </ul>
			                                </div>
			                             <?php }  ?>
			                        <?php
			                            $roundname = $match['round_title'];
			                            $matchdate = $match['mdate'];
			                            $i++;
			                            } //end round
			                         ?>
			
			                    </div>
			                </div>
                     	
                     	<?php } ?> <!--END  else comptype  --> 
                     	
                     
                      
				<?php } ?>
      
      	
   </div><!--/SecondColumn-->

  
  <div class="ThirdColumn"> 
      <div class="img-shadow">
             <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                   <h4 class="NoArrowLeft"><?php echo $this->compName; ?> Top Scorers</h4>
                </div>
                <div id="LeagueTopScorers" class="TeamPsuedoTables">
              		<?php if(sizeOf($this->topscorers) == 0 ){
          		      echo "<center>No Data Available.</center>";
          		      
          		    }else { ?>
              		<ul class="Header">
              
              			<li class="ColumnOneLeague">Player</li>
              			<li class="ColumnTwoLeague">Goals</li>
              			<li class="ColumnThreeLeague">Team</li>
              		</ul>

          		    <?php $i = 1;
          		    
          		     foreach ($this->topscorers as $data) {
                            if($i % 2 == 1) {
                                $style = "";
                            }else{
                                $style = "AltRow";
                            }
          		    ?>

                        <ul class="<?php echo $style; ?>">
                      
                           <li class="ColumnOneLeague">
                            <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>">
                                <?php echo $data["player_name"]; ?>
                            </a>
                           </li>
                           <li class="ColumnTwoLeague">
                                <?php echo $data["goals"]; ?>
                           </li>
                           <li class="ColumnThreeLeague">
                           <?php echo $data['team_name'];?>
                                <!--a title="<?php //echo $data['team_name'];?>" href="<?php //echo $urlGen->getClubMasterProfileUrl($data['team_id'],$data['team_name'], True); ?>">
                                    
                                </a-->
                           </li>
                        </ul>

          	     <?php $i++; }
          		    } ?>

          	   </div>
            </div>
          </div>
          <!-- End Top Scorers --> 
          
          
        <!-- Competition Gallery --> 
        <div class="featured1">
		 	<p class="mblack">
				<span class="black"><?php echo $this->compName; ?> Pictures</span>
				 <span class="sm">
                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<?php echo $this->leagueId;?>/type/3" title="<?php echo $this->compName; ?> Pictures">More &raquo;</a>
               </span>
			</p> 
	       	<p class="show">                           
                 <label>Show:</label>                          
                 <select id="slcgallery" name="slcgallery" class="most">
 						<option value="4" selected="selected">Display 4 Photos</option>
                        <option value="8">Display 8 Photos</option>
                  </select>
                  <input style="display:inline;" type="submit" id="buttonPhotos" value="Ok" class="submit">                 
            </p>
            
            <div id="PhotoGalleryList" class="imgscontent">

            
            </div>

			<p class="modfooter"><a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<?php echo $this->leagueId;?>/type/3">See More <?php echo $this->compName; ?> Pictures &raquo;</a></p> 
	    </div>

        <?php if ($server == 'local') { ?>
        <div id="goalshoutId" class="img-shadow">
            <?php echo $this->partial('scripts/goalshouttemplate.phtml',array('totalGoalShouts'=>$this->totalGoalShouts, 'comments'=>$this->comments ,'elementid'=>$this->leagueId , 'typeofcomment'=>Constants::$_COMMENT_COMPETITION ));?>
        </div>   
        <?php } ?>
        
       
       <!-- Profiles --> 
        <div class="prof">
              <p class="mblack">
               <span class="black">Fan Profiles</span>
               <span class="sm" id="menu6_more">
                   <?php if($session->email == null){ ?>
                   <a href="javascript:loginModal();" title="GoalFace Fan Profiles">More »</a>
              	 <?php }else{?>
              	 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfans/id/<?php echo $this->leagueId;?>" title="GoalFace Fan Profiles">More »</a>
              	 <?php }?>
               </span>
              </p>
                         
              <div id="randomprofiles" class="nmatch">
                     
			</div>

           <p class="modfooter">
           <?php if($session->email == null){ ?>
             <a class="orangelink" href="javascript:loginModal();">See More Fans &raquo;</a>      
           	<?php }else{?>
           	<a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfans/id/<?php echo $this->leagueId;?>">See More Fans &raquo;</a>
           	<?php }?>
           	</p>
                        
       </div> 
          
         
  </div><!--/ThirdColumn-->  
  
  
</div>
