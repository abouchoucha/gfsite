<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
 	$session = new Zend_Session_Namespace('userAdminSession');
 	?>

<script type="text/JavaScript">
	var closeModal = function(h) { 
	    //t.html('Please Wait...');  // Clear Content HTML on Hide.
	    h.o.remove(); // remove overlay
	    h.w.fadeOut(888); // hide window
	};
	
	
	jQuery(document).ready(function() {

        //some jquery here

    });
    
        
</script>
<div id="reportAbuseModal" class="jqmGeneralWindow">
     <div class="standardModal">
        <div class="WrapperForDropShadow">
            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                <h4 id="reportAbuseTitleId"></h4>
                <div class="CloseButton jqmClose"></div>
            </div>
            <div id="reportAbuseBodyId" class="MessageModal">
                <!--set the background image here-->
                <ul>
                    <li>
                        <strong id="reportAbuseTextId"></strong>
                    </li>
                    <li>
                        Reason:<select id="reportTypeId" class="slct" name="Favorites1select">
		                            <option value="0">-Select Reason-</option>
		                            <option value="Obscenity/vulgarity">Obscenity/vulgarity</option>
		                            <option value="Hate speech">Hate speech</option>
		                            <option value="Personal attack">Personal attack</option>
		                            <option selected="selected" value="Advertising/Spam">Advertising/Spam</option>
		                            <option value="Copyright/Plagiarism">Copyright/Plagiarism</option>
		                            <option value="Other">Other</option>
                        	 </select>
                    </li>
                    <li>
                        <textarea rows=2 cols=30 disabled="disabled" id="textReportAbuseId"></textarea>
                    </li>

                </ul>
            </div>
            <div id="reportAbuseBodyResponseId" class="MessageModal closeDiv"></div>
            <ul class="ButtonWrapper">
                <li>
                  <input id="acceptReportAbuseButtonId" disabled="disabled" type="button" class="submit" value="Report"/>
                  <input id="cancelReportAbuseButtonId" type="button" class="submit jqmClose"/>
                </li>
            </ul>
        </div>
    </div>
 </div> <!--end wrapper-->

<!-- /header -->
    <div id="header">
        <div class="hleft"></div>
        <!-- hmid -->
        <div class="hmid">
            <a href="/">
            <p class="logo"></p>
            </a>
        </div>
        <!-- /hmid -->
        <div class="hright"></div>
    </div>
<!-- /header -->


<!-- /header -->
	<div id="menu">
			<!-- menuu -->
			<div class="menuu">
				<div class="mleft"></div>
				<div class="mmid">
					<ul id="chromemenu">
						<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/">Home</a></li>
						<li class="first selected"><a title="Admin Dashboard" href="<?php echo Zend_Registry::get("contextPath"); ?>">Dashboard</a></li>
                        <li><a title="User Management" href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/manageusers/">Users</a></li>
                        <li><a title="User Management" href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/dologout/">Sign Out</a></li>             
					</ul>
				</div>
				<div class="mright"></div>
			</div>
             <!-- /breadcrumbs-->
            <?php require_once 'Common.php';
             $common = new Common();
             if ($this->breadcrumbs != null){
             ?>
             <div class="full">
				<ul>

                <?php echo $common->getBreadCrumbs($this->breadcrumbs->getTrail());?>

             	</ul>
			</div>
            <?php }?>                   
    </div>
<!-- /header -->
