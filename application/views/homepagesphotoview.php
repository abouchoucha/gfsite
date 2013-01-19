<script language = "javascript" type="text/javascript">

    jQuery(document).ready(function() {


        <?php foreach ($this->homeCompetitionPhotos as $photoitem) { ?>
        jQuery("#photoitem_<?php echo $photoitem['image_id'];?>").easyTooltip({
            useElement: "detail_<?php echo $photoitem['image_id'];?>"
        });
        <?php } ?>

    });

</script>

<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen ( ); ?>


<?php if(sizeOf($this->homeCompetitionPhotos) == 0){
    echo "<br><center><strong>No Photos Available</strong></center>";
}else { 
	$i = 0;

foreach ($this->homeCompetitionPhotos as $photoitem) { ?>

    <?php if (fmod($i, 2)==0){ ?>

	<div class="phimgs">
    <?php }?>
		<span id="photoitem_<?php echo $photoitem["image_id"];?>" class="imggleft">
			<a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/<?php echo $this->type;?>/id/<?php echo $this->elementid;?>/itemid/<?php echo $photoitem["image_id"];?>">
				<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/thumb/<?php echo $photoitem["image_file_name"];?>.jpg">
			</a>
		</span>

      <span id="detail_<?php echo $photoitem["image_id"];?>" style="display:none;text-align:left;">
          <?php echo $photoitem["image_caption"];?><br>
          <b>Source: </b>pics-united<br>
      </span>

    <?php if (fmod($i, 2)==1){ ?>
        </div>
    <?php }?>

    <?php $i++;?>
<?php }
 }?>


