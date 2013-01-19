<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>

<ul class="PreviousNextStory PreviousNextFeaturedStory">
	
</ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_special.phtml'); ?>


<div class="NewsStoryColumnOne FeaturedNews">
                              		                   			
<?php $feedPhoto = new NewsFeedPhoto(); ?>
	<?php $i = 1; foreach($this->paginator as $item) { ?>
			<?php $photos = $feedPhoto->selectNewsArticlePhotos($item["news_id"]); ?>
			<?php if($photos != null & $photos[0] != null) {?>
			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>">
        <img style="border:1px solid #CCCCCC" src="<?php echo Zend_Registry::get('contextPath'); ?>/public/feed/afp/<?php echo $photos[0]['photo_thumb_file'];?>" />
			</a>
      		<?php } ?>
		<h3><a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>"><?php echo $item["news_headline"]; ?></a></h3>

		<p class="Stats Posted">
              <?php echo $item["news_provider"]; ?> - <?php  echo date ('l - F j , Y H:i' , strtotime($item["news_this_created"]));?>
		</p> 
		<div class="Comments">
			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>#comments">
				Comments (<?php echo $item["numcomments"]; ?>)
			</a>
			<span>
			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>#rate">
			  Rating <?php printf ("%01.2f", $item['rating']!=null?$item['rating']:'0') ;?>
			 (<?php echo $item["news_total_votes"]!=null?$item["news_total_votes"]:"0"; ?> vote(s))</a>
			</span>
			<span>
			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>">
				Views (<?php echo $item["numreads"]; ?>)
			</a>
			</span>
			<span>
			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>">
				Shares (<?php echo $item["num_shares"]; ?>)
			</a>	
			</span>	
		</div>
		
		<div <?php if ($photos != null & $photos[0] != null){ echo 'class="NewsWithImage"'; }else{ echo 'class="NewsWithOutImage"';}?>>
			 <?php
                $split_point = ".";
                $parts = explode ($split_point, $item["news_body_content"]);
                echo $parts[0] ;
             ?>

            
            ... <a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>"><strong>more &raquo;</strong></a>
		</div>
		<ul class="FeaturedNewsToolbar <?php if ($i == 5){echo "FeaturedNewsToolbarNoTopBorder";}?>">
			<li class="Share_"><a href="#"><!--Share---></a></li>
			<li class="Comment_"><a href=""><!--Comment---></a></li>
			<li class="Rate"><a href="#"><!--Rate---></a></li>
		</ul>	
<?php $i = $i+1; } ?>

</div>          
