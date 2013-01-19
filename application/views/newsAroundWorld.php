 <script language="javascript">

jQuery(document).ready(function() {
        //jQuery('#recent').addClass('filterSelected');
        var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/news/searchtopnews/search/default';
        //load the first list by default
        <?php if ($this->category != null){ ?>
        urlBase = urlBase + '/cat/<?php echo urlencode($this->category);?>'
        <?php } ?>
        jQuery('#ajaxdata').html("<div class='ajaxload scoresMain'></div>");
        jQuery('#ajaxdata').load(urlBase);
		
    //Custom RSS News
	  jQuery('#loginModal').jqm({trigger: '#customNews' });
	  //submit login
	  jQuery('#submitButton').click(function() {
	            login();
	          });
	
	  jQuery('#searchNewsIdButton').click(function(){
	 	 	search('news');
		 });
	 jQuery(document).keydown(function(event) {
		if (event.keyCode == 13) {
			search('news');
		}
	});  

	 jQuery('#myNewsTab').click(function() {
			jQuery('li.Selected').removeClass('Selected');
			jQuery('#mynews').addClass('Selected');
			jQuery('#ajaxdata').html("<div class='ajaxload widget'></div>");
	     	jQuery('#ajaxdata').html("Coming soon...");
	 });

	 jQuery('#topNewsTab').click(function() {
			jQuery('li.Selected').removeClass('Selected');
			jQuery('#topnews').addClass('Selected');
			jQuery('#ajaxdata').html("<div class='ajaxload widget'></div>");
			jQuery('#ajaxdata').load(urlBase);
	 });	

});
 function search(category){
	
	var searchText = jQuery('#search-news').val();
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
 	if(category != ''){
 		url = url + "/t/"+category;
    }
    //alert(url);
    window.location = url;
}

</script>

<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();?>

<?php require_once 'Common.php'; 
$common = new Common();
?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 

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

            

            <!--Goalface Join Now-->
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                       <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                    </a>
                    </div>
                </div>


            <?php } ?>

    </div><!--end FirstColumnOfThree-->

    <div id="SecondColumnPlayerProfile" class="SecondColumn">
        <h1 >News</h1>
        <!--<div><a class="trigger" id="customNews" href="">Customize News</a></div>-->

        <div class="img-shadow">
            <div class="WrapperForDropShadow">
              <div class="SecondColumnProfile">
   
                      <ul class="FriendSearch">
                        <li class="Search">
                            <form id="searchprofilesform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                                <label>Search News</label>
                                <input id="search-news" type="text" class="text"  name="q"/>
                                <input id="category" type="hidden" name="t" value="news"/>
                                <input id="searchNewsIdButton" type="button" class="Submit" value="GO"/>
                            </form>
                        </li>

                    </ul><!-- /SearchSelections-->

                    <br class="ClearBoth">
                    <ul id="main_tabs" class="TabbedNav">
                       	    <li class="Selected" id="topnews">
                       	    <a href="javascript:void(0)" id="topNewsTab">
                       	    	<?php echo ($this->category!=null?$this->category:'Top')?> News</a></li>
                        <?php if($session->email != null) { ?>
                        	<!-- <li id="mynews"><a href="javascript:void(0)" id="myNewsTab">My News</a></li>-->
                        <?php } ?>
                        
                       
                    </ul>
                    <br class="ClearBoth">
                    
                    <div id="PulseDetailWrapper">

                        <div id="ajaxdata">


                        </div>
                        
                    </div>
              </div>
            </div>
        </div>

    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

</div> <!--end ContentWrapper-->

