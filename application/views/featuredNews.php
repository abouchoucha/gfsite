
<link
    href='<?php
    echo Zend_Registry::get ( "contextPath" );
    ?>/public/styles/wordcloud.css'
    rel="stylesheet" type="text/css" media="screen" />

<?php
require_once 'application/controllers/util/wordcloud.class.php'?>

<script language="javascript">


    jQuery(document).ready(function() {
        jQuery('#featured').addClass('filterSelected');
        var urlBase = '<?php echo Zend_Registry::get ( "contextPath" );?>/news/searchfeaturednews/search/';

        //load the first list by default
       // jQuery('#newboxresult .FeaturedNews').html('Loading...');
       jQuery("#newboxresult .FeaturedNews").html("<div class='ajaxload'></div>");

        url = urlBase + 'default/page/';
        jQuery('#newboxresult').load(url);

        jQuery('#featured').click(function(){
            loaddata(this.id, urlBase + 'default/page/');
        });

        jQuery('#mr').click(function(){
            loaddata(this.id, urlBase + 'mostread/page/');
        });
        jQuery('#mc').click(function(){
            loaddata(this.id, urlBase + 'mostcommented/page/');
        });

        jQuery('#hr').click(function(){
            loaddata(this.id, urlBase + 'highestrated/page/');
        });

        jQuery('#ms').click(function(){
            loaddata(this.id, urlBase + 'mostshared/page/');
        });

    });

    function loaddata(id, url)
    {
        jQuery('#FeaturedNewsFilter a').removeClass('filterSelected');
        jQuery('#' + id).addClass('filterSelected');
        jQuery('#newboxresult .PreviousNextStory').html('');
        jQuery('#newboxresult .FeaturedNews').html("<div class='ajaxload'></div>");
        jQuery('#newboxresult').load(url);
    }

</script>


<?php
require_once 'Common.php';
$common = new Common ( );
?>
<?php

require_once 'seourlgen.php';
$urlGen = new SeoUrlGen ( );
?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumn">
        <?php
        $session = new Zend_Session_Namespace ( 'userSession' );
        if ($session->email != null) {
            ?>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <?php
                include 'include/loginbox.php';
                ?>
            
            </div>
        </div>
        
        <?php
    } else {
        ?>
    
        <!--Me box Non-authenticated-->
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <?php
                include 'include/loginNonAuthBox.php';
                ?>
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
    


    </div>
    <!--end FirstColumnOfThree-->
    
    <div id="SecondColumnPlayerProfile" class="SecondColumn">
        <h1>Featured News111</h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">
                  	<ul class="FriendSearch NewsSearch">
                      <li class="Search">
                        <form id="searchplayersform" method="post">
                            <label>Search News</label>
                          <input id="search-players" type="text" class="text"  name="searchtext" onclick="submitsearch()"/>
                          <input type="submit" class="Submit" value="GO"/>
                        </form>
                      </li>
                      <li class="PopularSearches">
                        News Searches:
                        <a title="R.Van Nistelrooy" href="<?php echo Zend_Registry::get("contextPath"); ?>/players/ruud-nistelrooy_46/">R.Van Nistelrooy</a>

                        ,
                        <a title="Serie A" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/serie%20a_13/">Italy Serie A</a>
                        ,
                        <a title="Ligue 1" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/ligue%201_16/">France Ligue1</a>
                        </li>
                        <!--<li class="AdvancedSearch"><a href="#">Advanced Search</a></li>-->
                     </ul>
                    
                    <div id="FriendsWrapper" class="NewsStory">
                        <div id="FeaturedNewsFilter" class="FeaturedNewsSort">
                            <a id="featured" href="#">Most Recent</a> | 
                            <a id="mr" href="#">Most Read</a> |
                            <a id="mc" href="#">Most Commented</a> | 
                            <a id="hr" href="#">Highest Rated</a> |
                            <a id="ms" href="#">Most Shared</a>
                       </div>
                                                                          
                        <div class="RSSFeed"><a
                                href="<?php
                                echo Zend_Registry::get ( "contextPath" );
                                ?>/news/rss"
                            class="OrangeLink">RSS News Feed</a></div>
                        <!--  div id="data">
                           <ul class="PreviousNextStory PreviousNextFeaturedStory"></ul>
                           <div class="NewsStoryColumnOne FeaturedNews">Loading...</div>
                        </div>-->
                        <div id="newboxresult">
                                <div class="NewsStoryColumnOne FeaturedNews"><div class='ajaxload'></div></div>
                        </div>
                        <div class="NewsStoryColumnTwo">
                            
                            
                            <div class="TopNews"><!--- delete this style after putting back tag--->
                                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                    <a href="<?php echo $urlGen->getShowNewsWorldPageUrl ( true ); ?>">
                                        <h4 class="NoArrowLeft">Top News</h4>
                                    </a>
                                    <span> 
                                        <a href="<?php echo $urlGen->getShowNewsWorldPageUrl ( true ); ?>">More &gt;</a>
                                    </span>
                                </div>
                                
                                <?php
                                foreach ( $this->selectedFeeds as $item ) {
                                    ?>
                                <h5>
                                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/news/showexternalnews/id/<?php echo $item['feed_news_id']; ?>"title="<?php echo $item ["feed_news_title"]; ?>">
                                        <?php echo $item ["feed_news_title"]; ?>
                                    </a>
                                </h5>
                                <p>
                                    <?php echo $item ["feed_news_description"];?>
                                </p>
                                <!--<span>##<em>|</em>Rating #.#</span>-->
                                    <?php
                                }
                                ?>

                                <a href="<?php echo $urlGen->getShowNewsWorldPageUrl ( true );?>" class="OrangeLink">See More &gt;</a>
                                
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<!--end ContentWrapper-->
