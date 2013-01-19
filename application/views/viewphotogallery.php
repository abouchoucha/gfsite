
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?> 
<?php $urlGen = new SeoUrlGen ( ); ?>

 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script type="text/JavaScript">

    jQuery(document).ready(function() {

        jQuery('#nw').addClass('filterSelected');

        var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryframe';

        //added by JV to generate url from team, player or league photogallery
        var id = '<?php echo $this->elementid; ?>';
        var type_id = '<?php echo $this->typeid; ?>';
        if ( id != '' && type_id != '') {
            var urlBase = urlBase+'/type/<?php echo $this->typeid; ?>/id/<?php echo $this->elementid; ?>';
        }

        //load the first list by default
        jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
        jQuery('#'+ '<?php echo $this->idToSelect; ?>').addClass('filterSelected');
        jQuery('#ajaxdata').html("<div class='ajaxload scoresMain'></div>");
        jQuery('#ajaxdata').load(urlBase +'/filter/<?php echo $this->typeOfSearch; ?>' );




        jQuery('#nw').click(function(){
            loaddata(this.id, urlBase + '/filter/newest');
        });
        jQuery('#hr').click(function(){
            loaddata(this.id, urlBase + '/filter/mostrated');
        });
        jQuery('#mv').click(function(){
            loaddata(this.id, urlBase + '/filter/mostviewed');
        });

        jQuery('#refresh').click(function(){
            var typeOfSearch = jQuery("#typeOfSearch").val();
            jQuery('#ajaxdata').html("<div class='ajaxload scoresMain'></div>");
            jQuery('#ajaxdata').load(urlBase + '/filter/'+typeOfSearch);
        });

        /*jQuery('#searchPhotosIdButton').click(function(){
            search('photos');
        });*/
        jQuery(document).keydown(function(event) {
            if (event.keyCode == 13) {
                search('photos');
            }
        });
    });

    function loaddata(id, url)
    {
        jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
        jQuery('#' + id).addClass('filterSelected');
        jQuery('#ajaxdata ').html('Loading...');
        jQuery('#ajaxdata').load(url);
    }

    function search(category){
        var searchText = jQuery('#search-photos').val();
        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
        if(category != ''){
            url = url + "/t/"+category;
        }
        window.location = url;
    }

</script>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/common.js" type="text/javascript"></script>


<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumn">
        <?php
        $session = new Zend_Session_Namespace('userSession');
        if($session->email != null) {
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



        <?php if ($this->typeid == 1) { ?>
        <!--Team badge-->
         	<?php echo $this->render('include/badgeTeamNew.php');?>

        <!--Team Profile left Menu-->
        <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
                <?php echo $this->render('include/navigationTeam.php');?>
        </div>
            <?php } else if ($this->typeid == 2) { ?>
        <!--Player badge-->
          <?php echo $this->render('include/badgePlayerNew.php');?>


        <!--Player Profile left Menu-->
        <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
              <?php echo $this->render('include/navigationPlayerNew.php');?>
        </div>
         <?php }  ?>


      <?php  if($session->email == null){  ?>
 <!--Goalface Join Now-->
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
               <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
            </a>
            </div>
        </div>
    <?php  } ?>
    </div><!--end FirstColumn-->

    <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">

        <h1><?php echo $this->titlePage;?> Pictures </h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">

                    <ul class="FriendSearch">
                        <li class="Search">
                            <form id="searchprofilesform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                                <label>Search Photos</label>
                                <input id="search-photos" type="text" class="text"  name="q"/>
                                <input id="category" type="hidden" name="t" value="photos"/>
                                <input id="searchPhotosIdButton" type="submit" class="Submit" value="Search"/>                              
                        	</form>
                        </li>

                    </ul><!-- /SearchSelections-->

                    <div id="FriendsWrapper">
                        <div id="FanProfilesCriteria" class="FriendLinks">
                            View:
                            <a id="nw" href="javascript:void(0);">Newest</a>
                            <!-- commented until we develop |-->
                            <!-- a id="hr" href="javascript:void(0);">Highest Rated</a>|
                            <a id="mv" href="javascript:void(0);">Most Viewed</a -->

                        </div>
                        <ul class="Friendtoolbar">
                            <li class="Buttons">
                                    <!--<input type="checkbox" class="checkbox">-->
                                <input type="button" id="refresh" value="Refresh" class="submit blue">
                                <!--<input type="submit" value="Remove" class="submit blue">
                                <input type="submit" value="Add to Friend Feed" class="submit blue">-->
                            </li>

                            <!--List and Grid View 
                            <li class="OtherView">
                            </li>
                            <li>
                            </li>-->
                        </ul>

                        <div id="ajaxdata">


                        </div>

                    </div>

                </div>
            </div>
        </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->




    </div><!--end SecondColumn-->

</div> <!--end ContentWrapper-->
