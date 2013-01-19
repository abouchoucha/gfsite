<html>
<body style="margin:0px 0px 0px 0px; width:300px">
<div style="padding:0px 10px">
	<p>
	<form id="#post">
		<input id="PostCommentId" type="hidden" value="<?php echo $this->commentId; ?>"/>
		<input id="PostCommentSup" type="hidden" value="<?php echo $this->commentSup; ?>"/>
		<div>
			<textarea id="PostComment" rows="6" style="width:260px" style="text-align:left"><?php echo $this->comment; ?></textarea>
		</div>
	</form>
	</p>
</div>
</body>
</html>