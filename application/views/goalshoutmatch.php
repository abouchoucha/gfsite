<script type="text/JavaScript">

jQuery(document).ready(function() {

	jQuery('#commentGoalShoutId').focus(function() {
		 jQuery('#commentGoalShoutId').html('');
		 jQuery('#leaveGSButtonId').removeAttr('disabled'); 
	 });
	
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


function editGoalShout(id){
	
	jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShoutTrigger', onHide: closeModal });
	jQuery('#editGoalShoutModal').jqmShow();
	var dataEdit = jQuery('#goalshout'+id).html();
	jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));
	
		jQuery('#acceptEditGoalShoutButtonId').click(function() {
			var commentText = jQuery('#textgoalshoutEdit').val();
			if(jQuery.trim(commentText) == ''){
				jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	 			return;
	 		 }
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/editmatchgoalshout';
			var matchid = '<?php echo $this->match[0]["match_id"];?>';	
			var dataEditted = jQuery('#textgoalshoutEdit').val();
			jQuery('#editGoalShoutModal').jqmHide();
			jQuery('#data').html('Loading...'); 
			jQuery('#data').load(url , {matchid :matchid , id : id , dataEditted : dataEditted});
			
		});
}

function deleteGoalShout(id){

 jQuery('#acceptModalButtonId').show();
 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	
 jQuery('#modalTitleConfirmationId').html('DELETE GOOOALTALK?');
 jQuery('#messageConfirmationTextId').html('Are you sure you want to delete a goooaltalk');	
 
 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal });
 jQuery('#messageConfirmationId').jqmShow();
 
 jQuery("#acceptModalButtonId").unbind();
	
 jQuery('#acceptModalButtonId').click(function(){
		
	 	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/removematchgoalshout/matchid/<?php echo $this->match[0]["match_id"];?>/id/'+id;
		jQuery('#data').html('Loading...'); 
		jQuery('#data').load(url ,'' , function (){
			jQuery('#messageConfirmationTextId').html('Your goooaltalk has been deleted.');
			jQuery('#acceptModalButtonId').hide();
			jQuery('#cancelModalButtonId').attr('value','Close');
			jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
		});
		 
  });	
}		

function reportAbuse(id , reportTo){

	jQuery('#reportTypeId').val('0');
	jQuery('#textReportAbuseId').val(''); 
	jQuery('#textReportAbuseId').attr('disabled','disabled');
	jQuery('#reportAbuseBodyId').show();
	jQuery('#reportAbuseBodyResponseId').hide();
	jQuery('#acceptReportAbuseButtonId').show(); 
	jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	
	jQuery('#reportAbuseTitleId').html('REPORT GOALTALK ABUSE?');
	jQuery('#reportAbuseTextId').html('Are you sure you want to report abuse in this goooaltalk?');	

	jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal });
	jQuery('#reportAbuseModal').jqmShow();
	
	jQuery("#acceptReportAbuseButtonId").unbind();
	jQuery('#acceptReportAbuseButtonId').click(function() {

		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/reportabuse';
		var matchid = '<?php echo $this->match[0]["match_id"];?>';
		var dataReport = jQuery('#textReportAbuseId').val();
		var reportType = jQuery('#reportTypeId').val();

		jQuery('#data').load(url ,{matchid :matchid , id : id ,reportTo : reportTo, dataReport : dataReport ,reportType:reportType} , function (){
			jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
			jQuery('#reportAbuseBodyId').hide();
			jQuery('#reportAbuseBodyResponseId').show();
			jQuery('#acceptReportAbuseButtonId').hide();
			jQuery('#cancelReportAbuseButtonId').attr('value','Close');
			jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
		});
	});	
 }

</script>



<?php $session = new Zend_Session_Namespace('userSession');?>
 <div  class="WrapperForDropShadow">
        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
          <h4 id="totalMatchCommentsId" class="NoArrowLeft"></h4>
        </div>
        
        <div id="data">
        
		</div>

 </div>