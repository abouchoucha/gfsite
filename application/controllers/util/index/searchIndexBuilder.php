<?php
require_once 'application/controllers/util/simple_html_dom.php';
require_once 'application/controllers/util/Common.php';
// Load Players Pages and index them
class SearchIndexBuilder {
	
	private static $logger;
	
	function buildIndexByCategory($index, $indexType , $appDirectory , $contextPath , $serverName)
	{
		
		$siteMapFile = null;
		
		if ($indexType=='players')
		{
			// Load the SiteMap file and index each url
			//Here it needs to know where this XML files are creatted preco
			$siteMapFile = $appDirectory .'/sitemap-players.xml';
			$linkCategory = 'players';
		}
		elseif($indexType=='clubs')
		{
			$siteMapFile = $appDirectory .'/sitemap-clubs.xml';
			$linkCategory = 'clubs';
		}
		elseif($indexType=='leagues')
		{
			$siteMapFile = $appDirectory .'/sitemap-leagues.xml';
			$linkCategory = 'leagues';
		}
		elseif($indexType=='scores')
		{
			$siteMapFile = $appDirectory .'/sitemap-scores.xml';
			$linkCategory = 'scores';
		}
		elseif($indexType=='news')
		{
			$siteMapFile = $appDirectory .'/sitemap-news.xml';	
			$linkCategory = 'news';
		}
		 elseif($indexType=='profiles')
		{
			$siteMapFile = $appDirectory .'/sitemap-profiles.xml'; 
			$linkCategory = 'profiles';
		}elseif($indexType=='photos')
		{
			$siteMapFile = $appDirectory .'/sitemap-photos.xml'; 
			$linkCategory = 'photos';
		}
		// load file 
		$xml = simplexml_load_file($siteMapFile) or die ('Unable to load XML file!'); 
		//self::$logger = Zend_Registry::get("logger");
		// access XML data
		$cont = 1; 
		foreach($xml->url as $node)
		{
			// Index Each node ()URL )
			$target = "http://" . $serverName . $contextPath  . $node->loc;
			//self::$logger->info("Element: ". $cont ."-> Indexing... " . $target);
			echo("Element: ". $cont ."-> Indexing... " . $target);
			
			//Create document
						
			$doc1 = new Zend_Search_Lucene_Document();
        	
			$query = new Zend_Search_Lucene_Search_Query_Term(
				new Zend_Search_Lucene_Index_Term($node->loc));
			$hits = $index->find($query);
			//Zend_Debug::dump($hits);
			echo 'About to delete ' . sizeof($hits) . " for url" .$node->loc . "<br>";
			foreach ($hits as $hit)
			{
				try {
					$index->delete($hit->id);
				
				}catch(Zend_Search_Exception $e){
					echo $e->getMessage();
				}
			}

			$common = new Common();
			$doc1->addField(Zend_Search_Lucene_Field::Keyword('url', $node->loc , 'utf-8'));
			echo "URL:" . $doc1->getField('url')->value ." added \n";
		    $doc1->addField(Zend_Search_Lucene_Field::Keyword('category',$linkCategory ,'utf-8'));
		    if($linkCategory == 'news'){
		    	$doc1->addField(Zend_Search_Lucene_Field::Text('feedid',$node->feedid ,'utf-8'));
		    	echo "FEED ID:" . $node->feedid ." added \n";
		    }
    		//echo $node->shortname ."<br>";
		    //echo $node->fullname ."<br>";
		    $shortnamereplaced = $common->normalize_special_characters(mb_convert_encoding(utf8_decode($node->shortname), 'ISO-8859-1', 'UTF-8'));
		    //$shortnamereplaced = mb_convert_encoding(utf8_decode($node->shortname), 'ISO-8859-1', 'UTF-8');
		    $fullnamereplaced = $common->normalize_special_characters(mb_convert_encoding(utf8_decode($node->fullname), 'ISO-8859-1', 'UTF-8'));
		    //$fullnamereplaced = mb_convert_encoding(utf8_decode($node->fullname), 'ISO-8859-1', 'UTF-8');
        $nicknamenamereplaced = $common->normalize_special_characters(mb_convert_encoding(utf8_decode($node->nickname), 'ISO-8859-1', 'UTF-8'));
		    
		    $contents = $nicknamenamereplaced ." ". mb_convert_encoding(utf8_decode($node->nickname), 'ISO-8859-1', 'UTF-8') ." ".$shortnamereplaced . " " . mb_convert_encoding(utf8_decode($node->shortname), 'ISO-8859-1', 'UTF-8') ." ". $fullnamereplaced . " " . mb_convert_encoding(utf8_decode($node->fullname), 'ISO-8859-1', 'UTF-8');  
		    
		    echo "<br>UTF-8-DECODE-REPLACE:" . $common->str_replace_utf(utf8_decode($node->shortname));
		    echo "<br>UTF-8-CONVERT ENCODING:" .  mb_convert_encoding(utf8_decode($node->shortname), 'ISO-8859-1', 'UTF-8');
		    echo "<br>AFTER REPLACING NON ISO CHARS:" . $common->normalize_special_characters(mb_convert_encoding(utf8_decode($node->shortname), 'ISO-8859-1', 'UTF-8'));
		    echo "<br>UTF-8-DECODE:" . utf8_decode($node->shortname);
		    
		    $doc1->addField(Zend_Search_Lucene_Field::UnStored('contents', $contents ));
		    echo "Field : contents " . $doc1->getField('contents')->value ." added \n";
			
		    $index->addDocument($doc1);
		    
    		$index->commit();

			$cont++;; 
		}
		$index->optimize();
		echo 'Total documents In the index: '.$index->numDocs() . "<br>";	
	}
	
	function buildNewIndexForUser($index, $indexType , $appDirectory , $contextPath , $serverName){
		
		
	}
function sanitize($input) {
	//return htmlentities(strip_tags( $input ));
	return (strip_tags( $input ));
}
}
?>
