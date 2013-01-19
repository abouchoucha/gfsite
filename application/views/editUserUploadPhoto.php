<?php $session = new Zend_Session_Namespace("userSession"); ?>

<form id="uploadform" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/edituploadphoto" method="post" enctype="multipart/form-data">
    

    <fieldset>
       <?php
       $route = $_SERVER['DOCUMENT_ROOT'] . Zend_Registry::get("contextPath");
        //Check if we have any images in our gallery.
       //echo "filename:" .$session->filename;
       if ($session->filename != null){
        //Show the big img.
            if (is_file ($route . $this->root_crop . "/photos/" . $session->filename)){ ?>
        
            <div id="UploadPhotoContainerEdit">
                <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo $session->filename; ?> " />
                <input type="button" class="submit GreenGradient" value="Remove Photo" onclick="removeimg('<?php echo $session->filename; ?>')"/>
            </div>
		<?php
            }
        } else { ?>
             <div id="UploadPhotoContainerEdit">
                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ProfileMale.gif" height="120" width="120" alt="photo" />
              </div>
        <?php } ?>

        <fieldset id="UploadPhotoFieldset" class="SecondColumnTabForms">
            <?php 
            if($session->filename != null) { ?>
            Click save if you like what you see. If you don't , click browse and select a new photo to upload.
            <?php } else {?>
            Click browse to find a new photo to upload and stand out of the crowd.
            <?php }?>
            <p/>
            <input id="myfile" type="file" size="50" name="myfile"/>
            <p/>
           <fieldset>
           <?php if($session->filename != null) { ?>
            <input id="photorights" class="checkbox" type="checkbox" name="photorights" value="1"/>
            <label for="CheckUploadPhotoRights">I certify that I have the right to use this picture and that it does not violate the terms outlined below</label>
           <?php }?> 
            </fieldset>       
            <p/>
            <fieldset>
            You can upload JPG,GIF, or PNG (maximum size is 200 KB).Photos may NOT contain nudity, sexually explicit content, offensive material, or copyrighted images.
            </fieldset>
            <br class="clearleft"/>
            <fieldset class="AddToPhotoButtonWrapper">
            <?php if($session->filename != null) { ?>
              <input class="submit GreenGradient" type="button" onclick="javascript:saveImage() " value="Save" name="save"/>
            <?php }?>  
              <input class="submit GreenGradient" type="button" onclick="uploadimg()" value="Upload" name="upload"/>
              <br class="clearleft"/>
            </fieldset>
        </fieldset>
    </fieldset>

</form>

