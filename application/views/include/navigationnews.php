<?php $session = new Zend_Session_Namespace('userSession');?>
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<div class="WrapperForDropShadow">
     <ul class="leftnavlist">
         <li class="<?php echo($this->newsMenuSelected == 'featured'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getMainNewsPage ( true );?>" title="Featured News">Featured News</a>
         </li>
         <li class="<?php echo($this->newsMenuSelected == 'top'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getShowNewsWorldPageUrl ( true );?>" title="Top News">Top News</a>
         </li>
     </ul>
</div>