

     <?php require_once 'Common.php'; ?>
        
        <?php 
  				    $common = new Common();
  			
                    foreach ($this->soccerRss as $item) {
                       echo "<a target='_blank' href=" . $item['feed_news_url'] . ">" . $item['feed_news_title'] ." </a> ";
                       echo "&nbsp;&nbsp;<strong>" . $common->convertDates($item['feed_news_date']) ."</strong>";	
                       echo "<p />"; 
                      
                  }?>     
                
  
      
      
      
      
      
      
      
  
