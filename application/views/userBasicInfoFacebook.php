<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<?php $session = new Zend_Session_Namespace("userSession");
$view = Zend_Registry::get ( 'view' ); 
?>

<script type="text/javascript">

jQuery(document).ready(function() {
	<?php if($this->accountvalidatedAndProfileCreated =='true') { ?>
		//jQuery('#ErrorMessages').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay').addClass('ErrorMessagesDisplayBlue');
		//jQuery('#ErrorMessages').html('Your email has been validated.Create your profile now');
		jQuery('#alertSuccessMessageId').show();
		jQuery('#alertErrorMessageId').show();
	<?php }?>
	jQuery("#AboutMe").charCounter(500);
	onlyAlfaNumerics('screenname');
	jQuery('#Languages1').attr("disabled","disabled");
	jQuery('#remove1').hide();

	//Close inline message using cross image 
	jQuery(".inlineMessageWide .closemessage").click(function(){
		jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
	 }); 


	jQuery('#screenname').blur(function(){
		var screenname = jQuery('#screenname').val();
		jQuery.ajax({
			type: 'GET',
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/user/validatescreenname/screenname/'+screenname,
			dataType : 'script',
			success: function(text){
					if(text!= ''){
						jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
           			    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
           		    }else {
           		    	jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessages');
           			    jQuery('#screennameerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');;
               		}
			}
			
		})
	});
	
	jQuery('#submitButtonId').click(function(){
		
		valid = validaNewForm('BasicInformation');
		if(!valid){
	  		return;
	  	}
		
		jQuery.ajax({
			type: 'POST',
			data: jQuery("#BasicInformation").serialize(),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/user/createfbprofile',
			dataType : 'script',
			success: function(text){
					
			}
			
		})
		
	});	

});		

function onlyAlfaNumerics(formId){

	//called when key is pressed in textbox
	jQuery("#"+formId).keypress(function (e)  
	{ 
		if( e.which!=8 && e.which!=0 && (e.which<48  || (57 < e.which && e.which <= 65) || (65+25 < e.which && e.which < 97) || e.which > 97+25 )) 
		{
	  		return false;
      	}
	});
	
	
}
</script>

            <div id="FormWrapper">
                <h3>Create Profile</h3>
                <div id="FormWrapperForBottomBackground">
                    <form id="BasicInformation" name="register_basic_form"  method="POST" action="#">
                    	<input name="language_count" id="language_count" value="1" type="hidden">
                    	<div id="FieldsetWrapper">
                    		<div id="ErrorMessages" class="ErrorMessages" style="width:864px;margin-left:0px;">
                    		    <div id="MainErrorMessage">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
	                     	</div>
							
							<div id="alertSuccessMessageId" class="inlineMessageWide alertSucess" style="width:864px;margin-left:0px;">
								<p id="successMessageId"><?php echo $session->fbuser['name']; ?>, You have been succesfully authenticated by Facebook.</p>
								<span class="closemessage"></span>
							</div>
												
                    		<h5>Just two more steps...                      <a href="<?php echo $session->logoutUrl; ?>">Sign Out FaceBook</a></h5>
							
							<div id="fb-root"></div>
                    		<span style="padding-left:20px;"><em>*</em> = Indicates Required Field</span>
                            <div id="ErrorMessages"></div>
							<fieldset>
								 <div id="screennameerror" class="ErrorMessageIndividual">You must choose a Display Name</div>
								 <label for="displayname">
									<em>*</em>Display Name:
								  </label>
								  <input type="text" name="screenname" id="screenname" value="" required="minmax:6:16">
								  <br/>
								  <p>
									Display names must have at least 6 characters and no more that 16.Choose carefully as you will not be able to change your display name later.
								  </p>
								  
								  
								  <div id="countryliveerror" class="ErrorMessageIndividual">Select the country you live in.</div>
								  <label for="CountryILiveIn">
									<em>*</em>Where are you from?:
								  </label>
											  <select class="firstColumn" name="countrylive" id="countrylive"  required="nn">
								  <option value="">--- Select Country ---</option>
			            	     <?php
			                    $countries = $session->countries;
			                    $tempcl = $session->countryLiveId;
			                    foreach ($countries as $country) { ?>
			                      <option <?php if($tempcl == $country["country_id"]) echo 'selected'; ?> value="<?php echo $country["country_id"];?>"><?php echo $country["country_name"];?></option>
			              	   <?php } 
							   //$session->userCreated=true;
							   ?>
			                  </select>							  
								   <input type="button" class="submit GreenGradient" value="Create my profile" id="submitButtonId"/>
							</fieldset>
						</div><!-- end of FieldSetWrapper -->
            </form>
            </div> <!--end FormWrapperForBottomBackground -->
        </div><!--end FormWrapper -->




