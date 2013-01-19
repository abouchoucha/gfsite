<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>             
    <?php $i = 1; foreach ($this->newsFeeds as $item) { if($i == 1){?>
      <div id="mainStory" class="newsItemFirstHome">
      	<ul style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/feed/afp/<?php echo $this->firstNewsPhoto ?>')">
      		<li>
      			<a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>" title="<?php echo $item["news_headline"]; ?>"><?php echo $item["news_headline"]; ?></a>
      			<?php echo substr($item["news_body_content"], 0, 170); ?>... <a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>">more >></a>
      		</li>
      	</ul>
      </div>
      <?php } else { ?>
      <div class="newsItem <?php if ($i == $this->numberFeeds){echo "newsItemNoBorder";}?>">
      		<h6><a href="<?php echo $urlGen->getNewsArticlePageUrl($item["news_headline"], $item["news_id"], true); ?>" title="<?php echo $item["news_headline"]; ?>"><?php echo $item["news_headline"]; ?></a></h6>
      </div>
 <?php }$i = $i+1; }?>
      
    
