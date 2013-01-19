<?php $session = new Zend_Session_Namespace('userSession');?>
<script type="text/JavaScript">  
 
	jQuery(document).ready(function() {
		 jQuery('#remove1').hide();

		 jQuery('#cancelButtonId').click(function() {
			 top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/profiles/<?php echo $session->screenName;?>'; 
		 });
	});


	function updateBasicInfo(){
		  
		  valid = validaNewForm('BasicInformation');
		  if(!valid){
		    return;
		  }

		  jQuery.ajax({
				type: 'POST',
				data: jQuery("#BasicInformation").serialize(),
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/updateuserbasicinfo',
				success: function(data){
					 top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName;?>/profileinfo/profileok';
				}	
			})
		} 

	
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
	 			
		if(currentNumberOLanguages == 4){
			jQuery('#languageerror').html('Ooops . You can select up to 4 additional languages.');
	    	jQuery('#languageerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
	    	jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
	    	jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
			return;
	   }
	 	
	 	document.getElementById('language_count').value = (document.getElementById('language_count').value*1)+1;
	 	var languageCount = jQuery('#language_count').val();
	    var lastRow = jQuery('#lastrow');
	 	var cloneLanguage = jQuery('#Languages1').clone();
		var newId = "Languages" + languageCount;
		var newName = "Languages" + languageCount;
	 	cloneLanguage.attr("id",newId);
	 	cloneLanguage.attr("name",newName);
	 	cloneLanguage.removeAttr("disabled");
	 	cloneLanguage.removeAttr("class"); /*kill the class, since it is only needed for the first select box*/

	 	var removeAnchor = jQuery('#remove1').clone();
	 	removeAnchor.show();

	 	var newremoveId = "remove" + languageCount;
	 	removeAnchor.attr("id",newremoveId);

	 	removeAnchor.click(function () {

	 		  document.getElementById('language_count').value = (languageCount*1)-1;
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
<form id="BasicInformation" name="register_basic_form"  method="POST" action="">
        <input name="language_count" id="language_count" value="<?php echo sizeof($this->spokenLanguages);?>" type="hidden">
        <div id="ErrorMessages" style='display:none'></div>
        <fieldset>
             <label for="displayname">
                <em>*</em>Display Name:
              </label>
              <input type="text" name="screenname" id="screenname" disabled="true" value="<?php echo $this->user->screen_name;?>" required="minmax:6:16">
              <br/>
              <p>
                You cannot change your display name.
              </p>
              <label for="firstname">
                First Name:
              </label>
              <input class="text" type="text" id="firstname" name="firstname" value="<?php echo $this->user->first_name;?>" />
              <select class="private" name="fnprivate">
                    <option value="0" <?php echo ($this->user->firstname_priv == '0'?'selected':'')?>>Private</option>
                    <option value="1" <?php echo ($this->user->firstname_priv == '1'?'selected':'')?>>Only my friends</option>
                    <option value="2" <?php echo ($this->user->firstname_priv == '2'?'selected':'')?>>Goalface users</option>
              </select>
              <p></p>
              <label for="lastname">
                Last Name:
              </label>
              <input class="text" type="text" id="lastname" name="lastname" value="<?php echo $this->user->last_name;?>" />

             <select class="private" name="lnprivate">
                    <option value="0" <?php echo ($this->user->lastname_priv == '0'?'selected':'')?>>Private</option>
                    <option value="1" <?php echo ($this->user->lastname_priv == '1'?'selected':'')?>>Only my friends</option>
                    <option value="2" <?php echo ($this->user->lastname_priv == '2'?'selected':'')?>>Goalface users</option>
              </select>

              <p></p>
              <label for="gender">
                Gender:
              </label>
                <fieldset>
                    <?php $checkedm = '';
                          $checkedf	= '';
                        if($this->user->gender == 'm'){
                            $checkedm = 'checked';
                        }else {
                            $checkedf = 'checked';
                        }
                    ?>
                    <input class="radio" name="gender" type="radio" value="m"  <?php echo $checkedm;?>/><label for="male">Male</label>
                    <input class="radio" name="gender" type="radio" value="f" <?php echo $checkedf;?>/><label for="female">Female</label>
                     <select class="private" name="gprivate">
                          <option value="0" <?php echo ($this->user->gender_priv == '0'?'selected':'')?>>Private</option>
                          <option value="1" <?php echo ($this->user->gender_priv == '1'?'selected':'')?>>Only my friends</option>
                          <option value="2" <?php echo ($this->user->gender_priv == '2'?'selected':'')?>>Goalface users</option>
                    </select>
                </fieldset>
             <p></p>
              <label for="dob">
                <em>*</em>Date of Birth:
              </label>

                <?php $tempm = trim($this->month);?>
<select name="birth_month" >
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
					<select name=birth_day>
					<option selected value=0>(Day)
					<?php  
					$tempday = trim($this->day); 
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
					//Zend::dump($session->countries[]);
					?>
					<select name=birth_year>
					<option selected value=0>(Year)
					<?php $temp = trim($this->year); 
					while ($year >= $last_year): ?>
					<option <?php if($temp == $year) echo 'selected'; ?> value="<?php echo $year;?>"><?php echo $year;?>
					<?php 
					$year--;
					endwhile; ?>
</select>

<select class="private" name="dobprivate">
            <option value="0" <?php echo ($this->user->dob_check == '0'?'selected':'')?>>Private</option>
            <option value="1" <?php echo ($this->user->dob_check == '1'?'selected':'')?>>Only my friends</option>
            <option value="2" <?php echo ($this->user->dob_check == '2'?'selected':'')?>>Goalface users</option>
   </select>
<?php 
    $countries = $session->countries;
	$tempcl = $this->user->country_live;
	$tempc2 = $this->user->country_birth;
?>
              <p></p>
              <label for="CountryILiveIn">
                <em>*</em>Country I Live In:
              </label>
              <select class="firstColumn" name="countrylive" required="nn">
          <option value="">--- select ---</option>
 <?php
foreach ($countries as $country) { ?> 
  <option <?php if($tempcl == $country["country_id"]) echo 'selected'; ?> value="<?php echo $country["country_id"];?>"><?php echo $country["country_name"];?></option>
<?php } ?>
</select>

              <p></p>
              <label for="CityILiveIn">
                City I Live In:
              </label>
              <input class="text" type="text" id="CityILiveIn" name="citylive" value="<?php echo $this->user->city_live;?>" />

         <select class="private" name="clprivate">
            <option value="0" <?php echo ($this->user->city_live_priv == '0'?'selected':'')?>>Private</option>
            <option value="1" <?php echo ($this->user->city_live_priv == '1'?'selected':'')?>>Only my friends</option>
            <option value="2" <?php echo ($this->user->city_live_priv == '2'?'selected':'')?>>Goalface users</option>
          </select>
              <p></p>
              <label for="CountryImFrom">
                Country I'm From:
              </label>
              <select class="firstColumn" id="CountryImFrom" name="countryfrom">
          <option value="">--select--</option>
<?php 
foreach ($countries as $country) { ?> 
<option  <?php if($tempc2 == $country["country_id"]) echo 'selected'; ?> value="<?php echo $country["country_id"];?>"><?php echo $country["country_name"];?></option>
<?php } ?>
 </select>
            <select class="private" name="cfprivate">
                    <option value="0" <?php echo ($this->user->country_birth_priv == '0'?'selected':'')?>>Private</option>
                    <option value="1" <?php echo ($this->user->country_birth_priv == '1'?'selected':'')?>>Only my friends</option>
                    <option value="2" <?php echo ($this->user->country_birth_priv == '2'?'selected':'')?>>Goalface users</option>
      </select>
              <br/>
              <p>
                Select if different from where you live.
              </p>
              <label for="CityImFrom">
                City I'm From:
              </label>
              <input class="text" type="text" id="CityImFrom" name="cityfrom" value="<?php echo $this->user->city_birth;?>" />
              <select class="private" name="cityprivate">
              <option value="0" <?php echo ($this->user->city_birth_priv == '0'?'selected':'')?>>Private</option>
              <option value="1" <?php echo ($this->user->city_birth_priv == '1'?'selected':'')?>>Only my friends</option>
              <option value="2" <?php echo ($this->user->city_birth_priv == '2'?'selected':'')?>>Goalface users</option>
    </select>
              <p></p>
              <div id="languageerror" class="ErrorMessageIndividual">Select your preferred language</div>
              <label for="Languages">
                <em>*</em>Languages I Speak:
              </label>
	        <div id="languagesParent">     
			<?php 
              $cont = 1;
              foreach ($this->spokenLanguages as $spklanguage) { ?>
                  
              <select class="<?php echo($cont == 1 ? 'first' :'')?>" id="Languages<?php echo $cont ?>" name="Languages<?php echo $cont ?>" required="nn">
                <?php $tempfl = $session->firstLanguage;?>
                <option value="0" selected>Select Language</option>
                            <?php foreach ($this->languages as $language) { ?>
                        <option value="<?php echo $language["language_id"];?>" <?php echo($spklanguage["language_id"]==$language["language_id"]?"selected":"") ?>><?php echo $language["language_name"];?></option>
                        <?php } ?>
              </select>
              
              		<?php if($cont == 1){ ?>
                    <a id="remove<?php echo $cont ?>" href="javascript:void(0)">Remove</a>
                    
                    <?php }?>
              <br id="LangaugeBreaker"/>
              
              <?php 
              	$cont++;	
              	} ?>
              <div id="lastrow"></div>
              <p>
                <a href="javascript:addAnotherLanguage();">Add another language.</a>
              </p>
			 </div>

              <label for="AboutMe">
                About Me:
              </label>
               <textarea id="AboutMe" name="aboutme"><?php echo $this->user->aboutme_text;?></textarea>
               <p></p>
			<fieldset class="AddToPhotoButtonWrapper">
               <input type="button" class="submit GreenGradient" value="Save" onclick="javascript:updateBasicInfo()"/>
               
               <input type="button" class="submit GreenGradient" id="cancelButtonId" value="Cancel"/>
            </fieldset>   
        </fieldset>

 </form>
            




