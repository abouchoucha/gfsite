<?php
ini_set("memory_limit","1024M");
set_include_path('.' . PATH_SEPARATOR . '../library'
                     . PATH_SEPARATOR . './application/models/'
                     . PATH_SEPARATOR . './application/controllers/util/'
                     . PATH_SEPARATOR . get_include_path());
					 
//Load some components
require_once 'Zend/Search/Lucene.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Log.php';
require_once 'Zend/Log/Writer/Stream.php';
require_once 'Zend/Uri.php';
require_once 'Zend/Session/Namespace.php';

//Define some constants
define('APP_ROOT',realpath(dirname(dirname(__FILE__))));

	// create index
	try
	{
		// change to here where we will store the indexes on the server outside the application page. 
		
		$index = Zend_Search_Lucene::open('/home/goalface/www/beta/search/index');
		$index->setMaxBufferedDocs(100);
		$index->setMergeFactor(50);
	}
	catch(Zend_Search_Lucene_Exception $ex)
	{
		try
		{
			// change to here where we will store the indexes on the server outside the application page. 
			$index = Zend_Search_Lucene::create('/home/goalface/www/beta/search/index');
			$index->setMaxBufferedDocs(100);
			$index->setMergeFactor(50);
		}
		catch(Zend_Search_Lucune_Exception $e)
		{
			echo 'Unable to Create Index.';
		}
	}
	

indexPlayersPages($index, 'player');
//indexPlayersPages($index,'club');
//indexPlayersPages($index,'league');
//indexPlayersPages($index,'scores');
//indexPlayersPages($index,'news');
//indexPlayersPages($index,'profiles');

// Load Players Pages and index them
function indexPlayersPages($index, $indexType)
{
	//echo "MergeFactor: " . $index->getMergeFactor();
	
	if ($indexType=='player')
	{
		// Load the SiteMap file and index each url
		//Here it needs to know where this XML files are creatted preco
		$siteMapFile = '../application/sitemap-players.xml';
		$linkCategory = 'player';
	}
	elseif($indexType=='club')
	{
		$siteMapFile = '../application/sitemap-clubs.xml';	
		$linkCategory = 'club';
	}
	elseif($indexType=='league')
	{
		$siteMapFile = '../application/sitemap-leagues.xml';	
		$linkCategory = 'leagues';
	}
	elseif($indexType=='scores')
	{
		$siteMapFile = '../application/sitemap-scores.xml';	
		$linkCategory = 'scores';
	}
	elseif($indexType=='news')
	{
		$siteMapFile = '../application/sitemap-news.xml';	
		$linkCategory = 'news';
	}
	 elseif($indexType=='profiles')
	{
		$siteMapFile = '../application/sitemap-profiles.xml'; 
		$linkCategory = 'profiles';
		$session = new Zend_Session_Namespace ( 'userSession' );
		$session->screenName = 'chocheraz';
	}
	// load file 
	$xml = simplexml_load_file($siteMapFile) or die ('Unable to load XML file!'); 
	
	$client = new Zend_Http_Client();
	$client->setConfig(array('timeout'=>30));
	$client->setConfig(array('maxredirects' => 2));
	$client->setConfig(array('keepalive' => true));

	// access XML data 
	$start = time();
	foreach($xml->url as $node)
	{
		try
		{
			// Index Each node ()URL )
			echo "indexing... " . $node->loc."\n";	
			$target = "http://beta.goalface.com".$node->loc;
		        //echo "requesting: " . $target . "\n";	
			$client->setAuth('goalface', '!nth3f4ce', Zend_Http_Client::AUTH_BASIC);	
			$client->setUri((string)$target);
			$response = $client->request('POST');
			
			if($response->isSuccessful())
			{
				$body=$response->getBody();
				$doc=Zend_Search_Lucene_Document_Html::loadHTML($body);
				//Create document			
				$doc1 = new Zend_Search_Lucene_Document();
	        		//$title = substr($body,stripos($body,'<title>',0),stripos($body,'</title>',0));
				$begPos = stripos($body,"<title>")+7;
				$endPos = stripos($body,"</title>")-$begPos;
				$title = substr($body,$begPos,$endPos);
				
				try 
				{
					$query = new Zend_Search_Lucene_Search_Query_Term(
						new Zend_Search_Lucene_Index_Term($node->loc, 'url'));
					$hits = $index->find($query);
					foreach ($hits as $hit)
					{
						$index->delete($hit->loc);
					}
					//$entityid = substr($node->loc, strpos($node->loc, "_",0) + 1, strlen($node->loc) - 1);
					//$doc1->addField(Zend_Search_Lucene_Field::Keyword('entityid', $entityid));
			    		$doc1->addField(Zend_Search_Lucene_Field::Keyword('url', $node->loc));
	    				$doc1->addField(Zend_Search_Lucene_Field::Text('category',$linkCategory));
			    		$doc1->addField(Zend_Search_Lucene_Field::Text('articletitle', $title));
			    		$doc1->addField(Zend_Search_Lucene_Field::Text('contents', sanitize($body)));

			   		$index->addDocument($doc1);
	    				$index->commit();
	    			}
	    			catch(Exception $ex)
				{
					
				}
					
			}
			else
			{
				//$log->warning("Requesting$urlreturnedHTTP".$response->getStatus());
				echo 'Failed';
			}
		}
		catch(Zend_Http_Client_Exception $ex)
		{
		
		}
		catch(Zend_Uri_Exception $ex)
		{
			
		}
	}
	$stop = time();
	echo "Index time: " . ($stop - $start) . " seconds\n";
	
	$start = time();
	$index->optimize();	
	$stop = time();
	echo "Optimization time: " . ($stop - $start) . " seconds";
	
}
function sanitize($input) {
	//return htmlentities(strip_tags( $input ));
	return (strip_tags( $input ));
}
?>
