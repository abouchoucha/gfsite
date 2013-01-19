<style type="text/css">

div.autocomplete {
  position:absolute;
  width:250px;
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  height:32px;
  cursor:pointer;
}

</style>

        <div id="wrapper">
       
            <div id="FormWrapper">
			
                <h3>Player Information</h3>
                
                
                <div id="FormWrapperForBottomBackground">
                    <form id="BasicInformation">
                    	<div id="FieldsetWrapper">
                    		<h5>Find Player</h5>
                    		
							<fieldset>
							  <label for="shortname">
								Search:
							  </label>

							  <input type="text" id="autocomplete" name="autocomplete_parameter"/>
								<span id="indicator" style="display: none">
								  <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/autocomplete_spinner.gif" alt="Working..." />
								</span>
							  <div id="autocomplete_choices" class="autocomplete"></div>

							  	<script language="javascript" type="text/javascript">
								    new Ajax.Autocompleter("autocomplete", "autocomplete_choices", "<?php echo Zend_Registry::get("contextPath"); ?>/player/findPlayers", 
									{afterUpdateElement : getSelectionId, paramName: "value" , minChars: 4, indicator: 'indicator', frequency: 0.1});
									
									function getSelectionId(text, li) {
    									alert (li.id);
									}
								</script>
	
							  <p>							
							  </p>
							</fieldset>
						
												
							<h5>Edit Player</h5>
							<fieldset>
								  <label for="shortname">
									Short Name:
								  </label>
								  <input class="text" type="text" id="shortname" name="displayname" value="<?php echo $this->playernameshort; ?>"/>
								  <p></p>
								  
								  <label for="firstname">
									First Name:
								  </label>

								  <input class="text" type="text" id="firstname" name="firstname" value="<?php echo $this->playerfname; ?>"/>
								  <p></p>
								  <label for="lastname">
									Last Name:
								  </label>
								  <input class="text" type="text" id="lastname" name="lastname" value="<?php echo $this->playerlname; ?>"/>
								  <p></p>
								  
								  <label for="position">
									Position:
								  </label>							
								  <input class="text" type="text" id="position" name="position" value="<?php echo $this->playerposition; ?>"/>
								  <p></p>
								
								  <label for="dob">
									Day of Birth:
								  </label>
								  <input class="text" type="text" id="dob" name="dob" value="<?php echo $this->playerdob; ?>"/>
								  <p></p>
								  
							
								  <label for="shortbio">
									Short Bio:
								  </label>
								   <textarea id="shortbio" name="shorbio"><?php echo $this->playershortbio; ?></textarea>
								   <p></p>

								   <input type="submit" class="submit GreenGradient" value="Save"/>
								  
								   
							</fieldset>
							
								
							
							
							
							
						</div><!-- end of FieldSetWrapper -->
                    </form>
                </div> <!--end FormWrapperForBottomBackground -->
            </div><!--end FormWrapper -->
        </div> <!--end wrapper-->




