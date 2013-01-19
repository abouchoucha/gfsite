<div>
	<a href="/" class="logo">
        <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/logo.gif">
    </a>
</div>
<br>
<hr/>
<div style="text-align:justify">
<form>
  <input type="button" value=" Print this page " onclick="window.print();return false;" />
</form>

	<h2><?php echo $this->article["news_headline"]; ?></h2>
	<p>
		Posted: <?php echo $this->article["news_this_created"]; ?>
	</p>
	<p>
		<?php echo $this->article["news_byline"]; ?>
	</p>
	<p>
		<?php echo $this->article["news_provider"]; ?>
	</p>
  <p>
	  <?php echo $this->article["news_body_content"]; ?>
	</p>
	
</div>
<b><?php echo $this->referer; ?></b>