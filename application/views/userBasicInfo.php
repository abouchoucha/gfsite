<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<?php $session = new Zend_Session_Namespace("userSession"); ?>

<script type="text/javascript">

jQuery(document).ready(function() {
	<?php if($this->accountvalidatedAndProfileCreated =='true') { ?>
		//jQuery('#ErrorMessages').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay').addClass('ErrorMessagesDisplayBlue');
		//jQuery('#ErrorMessages').html('Your email has been validated.Create your profile now');
		jQuery('#alertSuccessMessageId').show();
		jQuery('#alertErrorMessageId').show();
	<?php } ?>
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
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/create-profile',
			dataType : 'script',
			success: function(text){
					if(text!= ''){
           		    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
           			    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
           		    }else {
						top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/user/skiptomyprofile';
					}
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

<script>
    

 function addAnotherLanguage(){

		var currentNumberOLanguages = document.getElementById('language_count').value*1;
	 	jQuery('#languageerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		var j = 1;
		for (var i=0 ;i<currentNumberOLanguages ;i++)
	 	{
		 	var index = i+1;
		 	var fromCheck = jQuery('#Languages'+index).val();
		 	for (var j=0 ;j<currentNumberOLanguages ;j++){
		 		var index2 = j+1;
		 		var toCheck = jQuery('#Languages'+index2).val();
		 		if(index != index2){
			 		if(fromCheck == toCheck){
			 			jQuery('#languageerror').html('Ooops . You have to select a different additional language.');
				    	jQuery('#languageerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
				    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
				    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
				    	jQuery('#Languages'+index2).val('0');
				 		return;
			 		}
			 	}	
			}
		}
	 			
	 	
	 	if(currentNumberOLanguages >= 5){
			jQuery('#languageerror').html('Ooops . You can select up to 4 additional languages.');
	    	jQuery('#languageerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
			return;
	   }
	 	document.getElementById('language_count').value = (document.getElementById('language_count').value*1)+1;
	    var lastRow = jQuery('#lastrow');
	 	var cloneLanguage = jQuery('#Languages1').clone();
		var newId = "Languages" + document.getElementById('language_count').value;
		var newName = "Languages" + document.getElementById('language_count').value;
	 	cloneLanguage.attr("id",newId);
	 	cloneLanguage.attr("name",newName);
	 	cloneLanguage.removeAttr("disabled");
	 	cloneLanguage.removeAttr("class"); /*kill the class, since it is only needed for the first select box*/

	 	var removeAnchor = jQuery('#remove1').clone();
	 	removeAnchor.show();

	 	var newremoveId = "remove" + document.getElementById('language_count').value;
	 	removeAnchor.attr("id",newremoveId);

	 	removeAnchor.click(function () {

	 		  document.getElementById('language_count').value = (document.getElementById('language_count').value*1)-1;
	 	      jQuery('#'+newId).remove();
	 	      jQuery('#'+newremoveId).remove();

	 	    });


	 	cloneLanguage.insertBefore(lastRow);
	 	removeAnchor.insertAfter(cloneLanguage);

		/*append the br so it the select box will appear on the next line*/
	 	var Breaker = jQuery('#LangaugeBreaker').clone();
	 	Breaker.show();
	 	Breaker.insertAfter(removeAnchor);


	 	newName = "#"+newName;
	 	jQuery(newName+ " option[@value='0']").attr('selected', 'selected');

	 }


 </script>

            <div id="FormWrapper">
                <h3>Create Profile</h3>
                <div id="FormWrapperForBottomBackground">
                    <form id="BasicInformation" name="register_basic_form"  method="POST" action="#">
                    	<input name="language_count" id="language_count" value="1" type="hidden">
                    	<div id="FieldsetWrapper">
                    		<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
	                     	</div>
							
							<div id="alertSuccessMessageId" class="inlineMessageWide alertSucess" style="width:864px;margin-left:0px;">
								<p id="successMessageId">Your email has been validated. Create your profile now.</p>
								<span class="closemessage"></span>
							</div>
												
                    		<h5>Help your friends and GoalFace members with similiar interests find you.</h5>
                    		<span style="padding-left:20px;"><em>*</em> = Indicates Required Field</span>
                            <div id="ErrorMessages"></div>
							<fieldset>
								 <div id="screennameerror" class="ErrorMessageIndividual">You must choose a Display Name</div>
								 <label for="displayname">
									<em>*</em>Display Name:
								  </label>
								  <input type="text" name="screenname" id="screenname" value="<?php echo $this->screenname;?>" required="minmax:6:16">
								  <br/>
								  <p>
									Display names must have at least 6 characters and no more that 16.Choose carefully as you will not be able to change your display name later.
								  </p>
								  
                  <label for="firstname">
									First Name:
								  </label>
                  <input class="text" type="text" id="firstname" name="firstname" value="<?php echo $this->firstname;?>" />
                  
								  <h4 class="PrivateSettings">Privacy Settings</h4>
								  
                  <label for="lastname">
									Last Name:
								  </label>
								  <input class="text" type="text" id="lastname" name="lastname" value="<?php echo $this->lastname;?>" />



                  <select class="private" name="lnprivate">
                      <option value="0">Private</option>
                      <option value="1" selected="selected">Friends Only</option>
                       <option value="2">GoalFace Members</option>
								  </select>

								  <p></p>
								  <label for="gender">
									Gender:
								  </label>
									<fieldset>
										<input class="radio" name="gender" type="radio" checked="checked" value="m" /><label for="male">Male</label>
										<input class="radio" name="gender" type="radio" value="f"/><label for="female">Female</label>
										 <select class="private" name="gprivate">
                                            <option value="0">Private</option>
                                            <option value="1" selected="selected">Friends Only</option>
                                            <option value="2">GoalFace Members</option>
										</select>
									</fieldset>
								 <p></p>
								  <label for="dob">
									<em>*</em>Date of Birth:
								  </label>

									<?php $tempm = trim($session->month);?>
                  <select name="birth_month" disabled>
                    <option selected value=0>(Month)
                    <option <?php if($tempm == '01') echo 'selected'; ?> value=01 >January</option>
                    <option <?php if($tempm == '02') echo 'selected'; ?> value=02>February</option>
                    <option <?php if($tempm == '03') echo 'selected'; ?> value=03>March</option>
                    <option <?php if($tempm == '04') echo 'selected'; ?> value=04>April</option>
                    <option <?php if($tempm == '05') echo 'selected'; ?> value=05>May</option>
                    <option <?php if($tempm == '06') echo 'selected'; ?> value=06>June</option>
                    <option <?php if($tempm == '07') echo 'selected'; ?> value=07>July</option>
                    <option <?php if($tempm == '08') echo 'selected'; ?> value=08>August</option>
                    <option <?php if($tempm == '09') echo 'selected'; ?> value=09>September</option>
                    <option <?php if($tempm == '10') echo 'selected'; ?> value=10>October</option>
                    <option <?php if($tempm == '11') echo 'selected'; ?> value=11>November</option>
                    <option <?php if($tempm == '12') echo 'selected'; ?> value=12>December</option>
                  </select>
                  <?php $day = 1; ?>
                  <select name=birth_day disabled>
                    <option selected value=0>(Day)
                  <?php
                    $tempday = trim($session->day);
                    echo "Day:" . $tempday;
                    while ($day <= 31): ?>
                    <option <?php if($tempday == $day) echo 'selected'; ?> value="<?php if ($day < 10) {
                    echo "0" . $day;
                  }else {
                    echo $day;
                  }?>">
                 <?php
                  if ($day < 10) {
                    echo "0" . $day;
                  }else {
                    echo $day;
                  }

                  $day++;
                  endwhile; ?>
                  </select>

                  <?php
                  $year = date("Y");
                  $last_year = $year - 100;
                  ?>
                  <select name=birth_year disabled>
                    <option selected value=0>(Year)
                  <?php $temp = trim($session->year);
                  while ($year >= $last_year): ?>
                    <option <?php if($temp == $year) echo 'selected'; ?> value="<?php echo $year;?>"><?php echo $year;?>
                  <?php
                  $year--;
                  endwhile; ?>
                  </select>

                  <select class="private" name="dobprivate">
					  	      	<option value="dobprivate" selected="selected">Friends Only</option>
					  	      	<option value="dobprivate" >Private</option>
						        <option value="dobprivate">GoalFace Members</option>
			           </select>


								  <p></p>
								  <div id="countryliveerror" class="ErrorMessageIndividual">Select the country you live in.</div>
								  <label for="CountryILiveIn">
									<em>*</em>Country I Live In:
								  </label>
								  <select class="firstColumn" name="countrylive" id="countrylive"  required="nn">
					  	      <option value="">--- select ---</option>
            	     <?php
                    $countries = $session->countries;
                    $tempcl = $session->countryLiveId;
                    foreach ($countries as $country) { ?>
                      <option <?php if($tempcl == $country["country_id"]) echo 'selected'; ?> value="<?php echo $country["country_id"];?>"><?php echo $country["country_name"];?></option>
              	   <?php } ?>
                  </select>

								  <p></p>
								  <label for="CityILiveIn">
									City I Live In:
								  </label>
								  <input class="text" type="text" id="CityILiveIn" name="citylive" />
									<select class="private" name="clprivate">
                                       <option value="0">Private</option>
                                      <option value="1" selected="selected">Friends Only</option>
                                       <option value="2">GoalFace Members</option>
						      </select>
								  <p></p>
								  <label for="CountryImFrom">
									Country I'm From:
								  </label>
								  <select class="firstColumn" id="CountryImFrom" name="countryfrom">
					  	      <option value="">--select--</option>
                  <?php
                    foreach ($countries as $country) { ?>
                    <option  value="<?php echo $country["country_id"];?>"><?php echo $country["country_name"];?></option>
              	  <?php } ?>
            		 </select>
                      <select class="private" name="cfprivate">
                        <option value="0">Private</option>
                        <option value="1" selected="selected">Friends Only</option>
                       <option value="2">GoalFace Members</option>
			          </select>
								  <br/>
								  <p>
									Select if different from where you live.
								  </p>
								  <label for="CityImFrom">
									City I'm From:
								  </label>
								  <input class="text" type="text" id="CityImFrom" name="cityfrom" />
								  <select class="private" name="cityprivate">
					  	          <option value="1" selected="selected">Friends Only</option>
					  	          <option value="0" >Private</option>
						          <option value="2">GoalFace Members</option>
			            </select>
								  <p></p>
								  <div id="languageerror" class="ErrorMessageIndividual">Select your preferred language</div>
								  <label for="Languages">
									<em></em>Languages I speak:
								  </label>
								  <div id="languagesParent">
								  <select class="first" id="Languages1" name="Languages1" required="nn">
							  	    <?php $tempfl = $session->firstLanguage;?>
		                    		<option value="0" selected>Select Language</option>
												<?php foreach ($this->languages as $language) { ?>
								            <option value="<?php echo $language["language_id"];?>" <?php echo($tempfl==$language["language_id"]?"selected":"") ?>><?php echo $language["language_name"];?></option>
								            <?php } ?>
                  				</select>
                  						<a id="remove1" href="javascript:void(0)">Remove</a>

                  					<br id="LangaugeBreaker"/>
                  				  <div id="lastrow"></div>
								  <p>
									<a href="javascript:addAnotherLanguage();">Add another language.</a>
								  </p>
								  </div>

								  <label for="AboutMe">
									About Me:
								  </label>
								   <textarea id="AboutMe" name="aboutme"></textarea>
								   <p></p>

								   <input type="button" class="submit GreenGradient" value="Create my profile" id="submitButtonId"/>
							</fieldset>
						</div><!-- end of FieldSetWrapper -->
            </form>
            </div> <!--end FormWrapperForBottomBackground -->
        </div><!--end FormWrapper -->





