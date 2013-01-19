<h1>My Latest Tweets</h1>
<?php if (count($this->posts) > 0) { ?>

	 <?php foreach($this->posts AS $post) { ?>

	  <p>On <b><?php echo date('m.d.y @ H:m:s',strtotime($post->created_at)); ?></b>, by <?php $post->screen_name; ?> <BR><BR>
	  <?php echo $post->text; ?>
	  </p>

	 <?php } ?>

 <?php } ?>
 
 <BR>
 
 <?php echo $this->updateForm; ?>
