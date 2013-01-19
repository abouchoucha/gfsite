
<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>

<?php $i = 0;
foreach ($this->homeCompetitionPhotos as $photoitem) { ?>

    <?php if (fmod($i, 2)==0){ ?>

        <?php if ($i==0){ ?>
            <ul class="PhotoGallery Clearfix First">
        <?php } elseif (fmod($i, 4)==0){ ?>
            <ul class="PhotoGallery Clearfix">
        <?php } else{ ?>
            <ul class="PhotoGallery Clearfix Alt">
        <?php }?>
    <?php }?>
        <li id="photoitem_<?php echo $photoitem["image_id"];?>">
          <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/id/<?php echo $photoitem["image_id"];?>">
            <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/thumb/<?php echo $photoitem["image_file_name"];?>.jpg"/>
          </a>
        </li>

    <?php if (fmod($i, 2)==1){ ?>
        </ul>
    <?php }?>

    <?php $i++;?>
<?php }?>

