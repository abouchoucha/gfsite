<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php require_once 'Common.php'; 
    $common = new Common();
?>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>

<div class="NewsStoryColumnOne FeaturedNews" style="width:600px;margin-left:40px;">
                              		                   		
	<?php foreach($this->paginator as $item) { ?>
         
            <h3 class="TopNewsExternal">
                <a href="<?php echo $item['feed_news_url']; ?>" onclick="window.open('<?php echo $item['feed_news_url']; ?>'); return false;">
                    <?php echo $item["feed_news_title"]; ?>
                </a>

            </h3>
            <?php if($item['feed_news_image_url'] != '') { ?>
                <img src="<?php echo $item['feed_news_image_url'];?>" class="floatRight" alt="" width="66" height="49">
            <?php } ?>
            <p class="Stats Posted">
             <strong><?php echo $common->convertDates($item['feed_news_date'])?></strong>&nbsp;-&nbsp;<?php echo $item['feed_news_source']; ?> - <?php  echo date ('l - F j , Y H:i' , strtotime($item["feed_news_date"]));?>
            </p>
            <p class="shortdescription">
            <?php echo substr($item["feed_news_description"], 0, 150); ?>... <a href="<?php echo $item["feed_news_url"]; ?>" onclick="window.open('<?php echo $item['feed_news_url']; ?>'); return false;"><strong>more &raquo;</strong></a>
            </p>
<?php } ?>

</div>  

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>

