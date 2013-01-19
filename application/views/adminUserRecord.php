 <script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/validate.js" type="text/javascript"></script>
 
 <script type="text/JavaScript">  
 
 	jQuery(document).ready(function() {
 		showEditAccountTab('complaint');

 		jQuery('#reportTypeId').change(function(){
			 var selectValue = jQuery('#reportTypeId').val();
			 if(selectValue == 0){
				 jQuery('#textReportAbuseId').attr('disabled','disabled'); 
				 jQuery('#acceptReportAbuseButtonId').attr('disabled','disabled'); 
			 }else {
			 	jQuery('#textReportAbuseId').removeAttr('disabled');
			 	jQuery('#acceptReportAbuseButtonId').removeAttr('disabled');
			 }	 
	        });
 		
	 });

 	
   function addNote(){
	var commentText = jQuery('#commentGoalShoutId').val();
	if(jQuery.trim(commentText) == ''){
		jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	 	return;
	}
	var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/admin/addaccountnote';
	var commentType = jQuery('#commentTypeId').val();
	var idtocomment = jQuery('#idtocommentId').val();
	var screennametocomment = jQuery('#screennametocommentId').val();
	var matchid = jQuery('#matchid').val();
	var matchname = jQuery('#matchnameId').val(); 
	jQuery('#data').load(url ,{commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment ,matchid :matchid,matchname:matchname, comment : commentText});
	jQuery('#commentGoalShoutId').val('');
	         
			 
   }
 
   function showEditAccountTab(tab){
     var target = 'data';
     if (tab == 'notes') {
         	 var url = '<?php echo Zend_Registry::get("contextPath");?>/admin/showaccountnotes/id/<?php echo $this->uid; ?>';
	 } else if (tab == 'complaint') {
         	 var url = '<?php echo Zend_Registry::get("contextPath");?>/admin/showcomplaints/id/<?php echo $this->uid; ?>';
	 } else if (tab == 'activity') {
         	 var url = '<?php echo Zend_Registry::get("contextPath");?>/admin/showaccountactivity/id/<?php echo $this->uid; ?>';
	 } else { 
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/admin/edituser/editaction/'+tab;
	}

     jQuery('#data').html("<div class='ajaxload widgetlong'></div>");
  	 jQuery.ajax({
          method: 'get',
          url : url,
          dataType : 'text',
          success: function (text) {
              jQuery('#data').html(text);
           }
       });
      	
	     jQuery('.Selected').removeClass('Selected');
		 jQuery('#' + tab).addClass('Selected');
    
   }
   
 
	function addComplaint(id, reportTo){

			jQuery('#reportTypeId').val('0');
			jQuery('#textReportAbuseId').val(''); 
			jQuery('#textReportAbuseId').attr('disabled','disabled');
			jQuery('#reportAbuseBodyId').show();
			jQuery('#reportAbuseBodyResponseId').hide();
			jQuery('#acceptReportAbuseButtonId').show(); 
			jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	
			jQuery('#reportAbuseTitleId').html('ADD COMPLANT?');
			jQuery('#reportAbuseTextId').html('Are you sure you want to add a complaint for this user??');	

			jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseModal', onHide: closeModal });
			jQuery('#reportAbuseModal').jqmShow();
			
			jQuery("#acceptReportAbuseButtonId").unbind();
			jQuery('#acceptReportAbuseButtonId').click(function() {

				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/admin/reportcomplaint';
				var userid = '<?php echo $this->playerid;?>';
				var dataReport = jQuery('#textReportAbuseId').val();
				var reportType = jQuery('#reportTypeId').val();

				jQuery('#data').load(url ,{userid :userid , id : id ,reportTo : reportTo , dataReport : dataReport ,reportType:reportType} , function (){
					jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
					jQuery('#reportAbuseBodyId').hide();
					jQuery('#reportAbuseBodyResponseId').show();
					jQuery('#acceptReportAbuseButtonId').hide();
					jQuery('#cancelReportAbuseButtonId').attr('value','Close');
					jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
					//load the complaint
					findComplaints(0);
				});
			});	
	}	
	
   

function findComplaints(type){
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/admin/showcomplaints/id/<?php echo $this->uid;?>/type/'+type;
	jQuery('#data').html('Loading...'); 
	jQuery('#data').load(url);
}
 </script> 
 
 <link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/themes/spread.css' rel="stylesheet" type="text/css" media="screen"/>
  
<a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/manageusers">&lt;&lt; Back to Manage Users</a>
<br>
<br>
<h2>Record For: <?php echo $this->screen_name; ?></h2> <div style="float:right;"><a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/resetpasswd/id/<?php echo $this->uid; ?>">reset password</a></div>
<h3>Overview</h3>
<table width=700px>
	<tr>
		<TD>Status: <b>Flagged(falta cambiar)</b></TD>
		<td>Member Since <? echo date ('M d, Y' , strtotime($this->registration_date)); ?></td>
	</tr>
	<tr>
		<TD>Current E-mail:</TD>
		<td>Registration E-mail: <?php echo $this->email; ?></td>
	</tr>
	<tr>
		<TD>First Name: <?php echo $this->first_name; ?></TD>
		<td>Last Login: </td>
	</tr>
	<tr>
		<TD>Last Name: <?php echo $this->last_name; ?></TD>
		<td>Last Profile Update: <? echo date ('M d, Y' , strtotime($this->date_update)); ?></td>
	</tr>
	<tr>
		<TD>&nbsp;</TD>
		<td>Most Recent Activity: </td>
	</tr>
</table>


 <div class="SecondColumnBackground">
        <ul class="TabbedNav TabbedNavAdmin" id="main_tabs">
           <li id="complaint" class="Selected"><a href="javascript:showEditAccountTab('complaint')">Complaints</a></li>
           <li id="priveleges"><a href="javascript:showEditAccountTab('priveleges')">Suspended Priveleges</a></li>
           <li id="account"><a href="javascript:showEditAccountTab('account')">Account History</a></li>
           <li id="activity"><a href="javascript:showEditAccountTab('activity')">Activity History</a></li>
           <li id="notes"><a href="javascript:showEditAccountTab('notes')">Notes</a></li>
        </ul>
                                
        <br class="ClearBoth" />
        
        <div id="data" class="tabswrapper">
                                   	
        </div>
 </div>
