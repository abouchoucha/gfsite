<?php
//Windows
//set_include_path('.' . PATH_SEPARATOR . '../library'
					 //. PATH_SEPARATOR . 'C:\phplibraries\ZendFramework-1.8.0b1\library'
					 //	. PATH_SEPARATOR . 'C:\wamp\frameworks\ZendFramework-1.7.8\library'
                   //  . PATH_SEPARATOR . 'C:\wamp\www\goalface'
                    // . PATH_SEPARATOR . get_include_path());

//Unix - PROD server  
set_include_path('.' . PATH_SEPARATOR . '../library'
					 . PATH_SEPARATOR . '/home/goalface/public_html/goalfaceapp/library'
           . PATH_SEPARATOR . '/home/goalface/public_html/goalfaceapp'
          . PATH_SEPARATOR . get_include_path());



require_once 'application/controllers/util/index/searchIndexBuilder.php';
require_once 'Zend/Search/Lucene.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';


// **change** to server variable of config ini
$config = new Zend_Config_Ini('application/config.ini', 'beta');     
$indexbuilder = new SearchIndexBuilder();
$appDirectory = $config->path->index->creator->directory;
$contextPath = '';
$serverName = $config->path->index->server->name;
$indexRepository = $config->path->search->index ;

//build the Index
Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());

try	
	{
		$index = Zend_Search_Lucene::open($indexRepository);
	}
	catch(Zend_Search_Lucene_Exception $ex)
	{
		try

		{
			// change to here where we will store the indexes on the server outside the application page. 
			$index = Zend_Search_Lucene::create($indexRepository);
		}
		catch(Zend_Search_Lucene_Exception $e)
		{
			echo 'Unable to Create Index.' ,$e->getMessage();
		}
	}
	 //$indexbuilder->buildIndexByCategory($index, 'profiles', $appDirectory , $contextPath , $serverName);
    // $indexbuilder->buildIndexByCategory($index, 'clubs', $appDirectory , $contextPath , $serverName);
	$indexbuilder->buildIndexByCategory($index, 'players', $appDirectory , $contextPath , $serverName);
     //$indexbuilder->buildIndexByCategory($index, 'leagues', $appDirectory , $contextPath , $serverName);
    //$indexbuilder->buildIndexByCategory($index, 'news', $appDirectory , $contextPath , $serverName);
     //$indexbuilder->buildIndexByCategory($index, 'photos', $appDirectory , $contextPath , $serverName);
?>
