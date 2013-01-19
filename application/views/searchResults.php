<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; 
require_once 'Player.php' ; ?>
<?php $urlGen = new SeoUrlGen(); ?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 



<script type="text/JavaScript">

jQuery(document).ready(function() {
	var searchCategory = '<?php echo ($this->searchCategory);?>'
	if(searchCategory == ''){
		searchAll('bestmatches','');
	}else{
		searchAll('s'+searchCategory, searchCategory);
	}
	
    jQuery("#snews").unbind();
    jQuery("#sphotos").unbind();
    jQuery("#steams").unbind();
    jQuery("#splayers").unbind();
    jQuery("#sleagues").unbind();
    jQuery("#sprofiles").unbind();
    jQuery("#sphotos").unbind();
    
    jQuery('#bestmatches').click(function(){
		searchAll('bestmatches','');
	 });
	jQuery('#sclubs').click(function(){
		searchAll('sclubs','clubs');
	 });
	
	jQuery('#splayers').click(function(){
		searchAll('splayers','players');
	 });
	jQuery('#sleagues').click(function(){
		searchAll('sleagues','leagues');
	 });
	jQuery('#sprofiles').click(function(){
		searchAll('sprofiles','profiles');
	 });
	

});
function searchAll(title , category){
	jQuery('#SearchResultsFilter a').removeClass('filterSelected');
 	jQuery('#'+title).addClass('filterSelected');
 	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/searchresult/q/<?php echo urlencode($this->searchString);?>';
 	if(category != ''){
 		url = url + "/t/"+category;
    }
 	jQuery('#ajaxdata').html("Searching...");
    jQuery('#ajaxdata').load( url);
}
</script>


<div id="ContentWrapper" class="TwoColumnLayout">
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

        

      <?php }else { ?>

        <!--Me box Non-authenticated-->
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <?php include 'include/loginNonAuthBox.php';?>
            </div>
        </div>

        <!--Goalface Register Ad-->
        <div class="img-shadow">
            <div class="WrapperForDropShadow"><a
                    href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">
                    <img class="JoinNowHome"
                     src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" /></a>
            </div>
        </div>

      <?php } ?>



    </div>

    <!--end FirstColumnOfTwo-->
    <div id="SecondColumnPlayerProfile" class="SecondColumn">
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">
                   <ul class="FriendSearch SearchResults">
                        <li class="Search">
                            <form action="<?php echo Zend_Registry::get("contextPath"); ?>/search/" method="get">
                              <input id="search-query_in_body" type="text" class="text results" name="q" value="<?php echo $this->escape($this->searchTerm); ?>" />
                              <input type="submit" class="Submit" value="GO" />
                           </form>
                        </li>
                        <!-- <li class="PopularSearches SearchResults">
                            Related Searches:
                            <a title="Michael Ballack" href="/players/michael-ballack_22/"> Michael Ballack </a>
                            ,
                            <a title="Arjen Robben" href="/players/arjen-robben_32/"> Arjen Robben </a>
                            ,
                            <a title="Ruud Van Nistelrooy" href="/players/ruud%20van-nistelrooy_46/"> Ruud Van Nistelrooy </a>
                        </li>-->
                    </ul>


                    <!-- /SearchSelections-->
                    <!-- <ul class="BluePager">
                        <li>Showing <?php //echo count($this->hits) ?> result(s)</li>
                        <li class="ResultsLinks"></li>
                    </ul>-->
                     <div id="searchContainer" class="SearchResults">
                         <div id="SearchResultsFilter" class="FeaturedNewsSort" style="width:600px;">
                          <a id="bestmatches" class="filterSelected" href="javascript:void(0)">Best Matches</a>
                          |
                          <a id="sclubs" href="javascript:void(0)">Teams</a>
                          |
                          <a id="splayers" href="javascript:void(0)">Players</a>
                          |
                          <a id="sleagues" href="javascript:void(0)">Leagues &amp; Tournaments</a>
                          |
                          <a id="sprofiles" href="javascript:void(0)">Profiles</a>
                        </div>
                         <div id="ajaxdata">


                        </div>
                     </div>
                </div>
           </div>
       </div>
     </div>
</div>
                        
				

	    
