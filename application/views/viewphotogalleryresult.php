<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen ( ); ?>


<script language = "javascript" type="text/javascript">

    jQuery(document).ready(function() {


        <?php foreach ($this->paginator as $photoitem) { ?>
        jQuery("#photoitem_<?php echo $photoitem['image_id'];?>").easyTooltip({
            useElement: "detail_<?php echo $photoitem['image_id'];?>"
        });
        <?php } ?>

    });

</script>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>
<input type="hidden" name="typeOfSearch" id="typeOfSearch" value="<?php echo $this->typeOfSearch; ?>">

<ul class="PhotoGallery Clearfix">
    <?php foreach ($this->paginator as $photoitem) { ?>
      <li id="photoitem_<?php echo $photoitem["image_id"];?>">
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/<?php echo $this->type;?>/id/<?php echo $this->elementid;?>/itemid/<?php echo $photoitem["image_id"];?>">
          <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/thumb/<?php echo $photoitem["image_file_name"];?>.jpg" alt=""/>
        </a> 
        <br/>
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/type/<?php echo $this->type;?>/id/<?php echo $this->elementid;?>/itemid/<?php echo $photoitem["image_id"];?>">Enlarge</a>
      </li>

       <li id="detail_<?php echo $photoitem["image_id"];?>" style="display:none;text-align:left;">
          <?php echo $photoitem["image_caption"];?><br>
          <b>Source: </b>pics-united<br>
      </li>
      
    <?php }?>
 </ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>


