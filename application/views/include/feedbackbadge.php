<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();
?>
<div class="img-shadow">
    <div class="WrapperForDropShadow">
        <div id="Feedback">
            <strong>Feedback</strong>
            <br />
				<a href="<?php echo $urlGen->getFeedbackPageUrl(true); ?>">Tell us what you think</a> about GoalFace.  We'd
            love to hear what you have to say.
        </div>
    </div>
</div>