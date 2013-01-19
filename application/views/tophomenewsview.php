<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php require_once 'Common.php';
    $common = new Common();
?>
         
    <?php $i = 1; 
    	
		   	foreach ($this->newsFeeds as $item) { 
		   		
			   		if($i == 1){?>
			      <div id="mainStory" class="newsItemFirstHome">
			      	<ul style="padding-left: 0;background-image:url('<?php //echo $item['feed_news_image_url'] ?>')">
			      		<li class="homenewstitle">
			      			<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" onclick="window.open('<?php echo $item->link; ?>'); return false;">
			      					<?php echo $item->title; ?></a>
			      		</li>
			      		<li class="homenewsdescription">
			      			<?php echo $item->description; ?>... <a href="<?php echo $item->link; ?>" onclick="window.open('<?php echo $item->link; ?>'); return false;">more&nbsp;&raquo;</a>
			      		</li>
			
			      	</ul>
			      </div>
			      <?php } else { ?>
			      <div class="newsItem <?php if ($i == $this->numberFeeds){echo "newsItemNoBorder";}?>">
			          <h6>
			            <a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" onclick="window.open('<?php echo $item->link; ?>'); return false;">
			            <?php echo $item->title; ?>
			            </a>
			            &nbsp;<span><?php echo $common->convertDates($item->pubDate)?></span>
			          </h6>
			      </div>
			 	<?php }

		 	 $i = $i+1; 
		 	 if($i>$this->numberFeeds) 
         	 break;
    	}

	?>
      
    
