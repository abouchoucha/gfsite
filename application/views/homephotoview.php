<script language = "javascript">

    jQuery(document).ready(function() {

       // jQuery("#photoitem_78").easyTooltip({
         //   useElement: "detail_78"
       // });

        <?php foreach ($this->homePhotos as $photoitem) { ?>
        jQuery("#photoitem_<?php echo $photoitem['image_id'];?>").easyTooltip({
            useElement: "detail_<?php echo $photoitem['image_id'];?>"
        });
        <?php } ?>

    });
    
</script>

<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

<?php $i = 0;
foreach ($this->homePhotos as $photoitem) { ?>

    <?php if (fmod($i, 2)==0){ ?>

	<div class="phimgs">
    <?php }?>
   
   		<span id="photoitem_<?php echo $photoitem["image_id"];?>" class="imggleft">
			<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/itemid/<?php echo $photoitem["image_id"];?>">
				<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/thumb/<?php echo $photoitem["image_file_name"];?>.jpg">
			</a>
		</span>

    <li id="detail_<?php echo $photoitem["image_id"];?>" style="display:none;text-align:left;">
        
        <?php echo $photoitem["image_caption"];?><br>
    </li>

    <?php if (fmod($i, 2)==1){ ?>
        </div>
    <?php }?>

    <?php $i++;?>
<?php }?>
