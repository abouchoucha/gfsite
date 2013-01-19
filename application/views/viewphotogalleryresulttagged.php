  <?php require_once 'Common.php'; ?>
  <?php require_once 'seourlgen.php'; ?> 
	<?php $urlGen = new SeoUrlGen ( ); ?>
    
<?php echo $this->paginationControl($this->paginatorTagList,'Sliding','scripts/my_pagination_control_div_ajax.phtml'); ?> 
<ul class="PhotoGallery Clearfix">
    <?php foreach ($this->paginatorTagList as $photoitem) { ?>
      <li id="photoitem_<?php echo $photoitem["image_id"];?>">
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogalleryitem/id/<?php echo $photoitem["image_id"];?>">
          <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/gallery/thumb/<?php echo $photoitem["image_file_name"];?>.jpg" alt="caption"/>
        </a>
        <br/>        
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotoitem/id/<?php echo $photoitem["image_id"];?>">Enlarge</a>
      </li>

       <li id="detail_<?php echo $photoitem["image_id"];?>" style="display:none;text-align:left;">
    
          <b>Source: </b>AFP<br>
          
      </li>
      
    <?php }?>
 </ul>
