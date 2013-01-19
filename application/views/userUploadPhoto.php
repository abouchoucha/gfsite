<?php $session = new Zend_Session_Namespace("registerSession"); ?>
<script type="text/javascript">




function uploadimg (){


	 if($F('myfile') == '' ){
	    $('ErrorMessages').innerHTML = "You need to select a valid file and then click Upload Photo";
    	$('ErrorMessages').className = "ErrorMessagesDisplay ErrorMessagesDisplayBlue";
    	return;
	 }
	 if(!$('photorights').checked){
	 	$('ErrorMessages').innerHTML = "You must check the terms and conditions as outlined below.";
    	$('ErrorMessages').className = "ErrorMessagesDisplay ErrorMessagesDisplayBlue";
    	return;
	 }
	 $('uploadform').submit();


}
function removeimg(image ) {

     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/user/deletephoto';
     var target = 'UploadPhotoContainer';
     var pars = {img: image };


     var myAjax = new Ajax.Updater(target, url, {
                                                method: 'get',
                                                parameters: pars,
                                                evalScripts:true
                                                }
                                            );

}

function skipUploadPhoto(){

    window.location = "<?php echo Zend_Registry::get("contextPath"); ?>/user/skipUploadPhoto"

}

function continueRegistration(){

    window.location = "<?php echo Zend_Registry::get("contextPath"); ?>/user/addfavorities"

}


</script>



<div id="FormWrapper">
    <h3>Add Photo To Your Profile</h3>
      <div id="FormWrapperForBottomBackground">
        <form id="uploadform" action="<?php echo Zend_Registry::get("contextPath"); ?>/user/uploadphoto" method="post" enctype="multipart/form-data">

          <div id="FieldsetWrapper">
            <div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">There is an error uploading the file.</div>
	           </div>

              <h5>Upload your profile photo below</h5>

                  <div id="UploadPhotoContainer">
      				<?php

				    	$route = $_SERVER['DOCUMENT_ROOT'] . Zend_Registry::get("contextPath");
				    	//Check if we have any images in our gallery.
				       if ($session->filename !=''){

				    		//Show the big img.
				    		if (is_file ($route . $GLOBALS['imagesfolder'] . "/" . $session->filename)){

				    					?>
				    	<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=100&h=100&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $session->filename; ?> " />

				        <input type="button" class="submit GreenGradient" value="Remove" onclick="removeimg('<?php echo $session->filename; ?>')"/>
                      	<input type="button" class="submit GreenGradient" value="Continue >" onclick="continueRegistration();"/>


                <?php
				    		}
				    	} else { ?>
				     		    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/silueta.gif" height="100" width="100" alt="photo" />
						<?php } ?>
               		</div><!--/UploadPhotoContainer-->

							<fieldset class="SecondColumnOfTwo" id="UploadPhotoFieldset">
                  Upload a photo of yourself and stand out from the crowd.  Plus, your profile
                  will look empty without you.
                  <p></p>

                  <input type="file" id="myfile" name="myfile" size="50"/>
                  <p></p>

                  <fieldset>
                    <input class="checkbox" type="checkbox" value="1" name="photorights" id="photorights"/>
                    <label for="CheckUploadPhotoRights">I certify that I have the right to use this picture and that it does not violate the terms outlined below</label>
                  </fieldset>

	                <input type="button" class="submit GreenGradient" value="Upload Photo" onclick="uploadimg()"/>
                  <p></p>
                  11You can upload JPG,GIF, or PNG (maximum size is 200 KB).Photos may NOT contain nudity,sexually explicit content, offensive material, or copyrighted images.

                <br class="clearleft"/>

						  <fieldset class="AddToPhotoButtonWrapper">
						<?php if ($session->filename ==''){ ?>
							 <input type="button" class="submit GreenGradient" name="Register" value="Previous" onclick="javascript:history.back(1) "/>
							 <input type="button" class="submit GreenGradient" name="Continue" value="Continue >" onclick="skipUploadPhoto()"/>
							 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/skiptomyprofile">Skip to My Profile</a>
							 <?php } ?>
							<br class="clearleft"/>

							</fieldset>

						</div><!-- end of FieldSetWrapper -->
                    </form>
                </div> <!--end FormWrapperForBottomBackground -->
  </div><!--end FormWrapper -->


