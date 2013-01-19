<?php

	ini_set("memory_limit","164M");
	init();
	generatePlayersSiteMapFile();
	//generateClubsSiteMapFile();
	//generateProfilesSiteMapFile();
	//generateLeaguesSiteMapFile();
	//generateNewsSiteMapFile();
	
	
	// Need to improve this method
	//generateScoresSchedulesSiteMapFile();
	
	function init()
	{
		
                     
        set_include_path('.' . PATH_SEPARATOR . '../library'
                     . PATH_SEPARATOR . '../application/models/'
                     . PATH_SEPARATOR . '../application/controllers/util/'
                     . PATH_SEPARATOR . get_include_path());
                     

		//load clasess
		
		include "Zend/Loader.php";
     
		
		Zend_Loader::loadClass('Zend_Registry');
		Zend_Loader::loadClass('Zend_View');
		Zend_Loader::loadClass('Zend_Config_Ini');
		Zend_Loader::loadClass('Zend_Db');
        Zend_Loader::loadClass('Zend_Date');
		Zend_Loader::loadClass('Zend_Db_Table');
		Zend_Loader::loadClass('Zend_Filter_Input');
		//require_once '../application/models/Player.php';
		Zend_Loader::loadClass ( 'Player', '../application/models/' ) ;
		Zend_Loader::loadClass ( 'Matchh', '../application/models/' ) ;
		Zend_Loader::loadClass ( 'Team', '../application/models/' ) ;
		Zend_Loader::loadClass ( 'LeagueCompetition', '../application/models/' ) ;
		Zend_Loader::loadClass ( 'NewsFeed', '../application/models/' ) ;
		Zend_Loader::loadClass('User','../application/models/');
		Zend_Loader::loadClass ( 'Country','../application/models/' ) ;
		Zend_Loader::loadClass ( 'Pagination', '../application/controllers/util/' ) ;
		Zend_Loader::loadClass ( 'seourlgen') ;
		Zend_Loader::loadClass ( 'urlGenHelper') ;
		
		
		$config = new Zend_Config_Ini('../application/config.ini', 'prod_server');
		Zend_Registry::set('config', $config);

		$db = Zend_Db::factory($config->db->adapter,$config->db->config->toArray());
		Zend_Db_Table::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
		Zend_Registry::set('contextPath', '');
		Zend_Registry::set('appDirectory', '/home/goalface/public_html/beta/application');
		Zend_Registry::set('playerSiteMapFile', '/sitemap-players.xml');
		Zend_Registry::set('leaguesSiteMapFile', '/sitemap-leagues.xml');
		Zend_Registry::set('clubsSiteMapFile', '/sitemap-clubs.xml');
		Zend_Registry::set('scoresSiteMapFile', '/sitemap-scores.xml');
		Zend_Registry::set('profilesSiteMapFile', '/sitemap-profiles.xml');
		Zend_Registry::set('newsSiteMapFile', '/sitemap-news.xml');
        //echo $siteMapFileLocation;
	}

	function generatePlayersSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		//For Linux Server - Create an empty sitemap-players.xml file on the  /var/www/path_to_xml_files  path 
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "playerSiteMapFile" );
		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
				
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		if ($fp) {
		   
			$player = new player ( ) ;
			// Generate for all the players Master profile.
			$rowset = $player->findAllPlayers () ;
			$urlGen = new SeoUrlGen();
			foreach ($rowset as $data)
			{				
				$loc = $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;				
			}

//			 //players starting with alphabets
//			$alphabet_array = array ( 'A' , 'B' , 'C' , 'D' , 'E' , 'F' , 'G' , 'H' , 'I' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'P' , 'Q' , 'R' , 'S' , 'T' , 'U' , 'V' , 'W' , 'X ' , 'Y' , 'Z' ) ;
//			for ( $i = 0 ; $i < count ( $alphabet_array ) ; $i ++ ) 
//			{
//				$totalRows = $player->countPlayersByLetter2 ($alphabet_array[$i]) ;
//
//				// Calculate the no of pages.
//				$noOfPages = $totalRows/20;	
//				 //See if there is any remainder
//				$isAnotherPageExists = $totalRows%20;
//				if ($isAnotherPageExists != 0)
//				{
//					$noOfPages+=$noOfPages;
//				}			
//				for($j = 1 ; $j <= $noOfPages; $j++) 
//				{
//					$loc = $urlGen->getPlayersStartingWithAlphabetUrl($alphabet_array[$i], $j, True);
//					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
//					$urlsetValue = $urlsetValue.$url;
//				}
//			}
//				
			$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
			//echo $finalXML;
		   fwrite($fp, $finalXML);
		   fclose($fp);
		}
		else 
		{
			echo "exiting...";
			exit;
		}
	}

	function generateClubsSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		
		//For Linux Server - Create an empty sitemap-clubs.xml file on the  /var/www/path_to_xml_files  path 
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "clubsSiteMapFile" );
		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
		
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		if ($fp) {
		   $urlGen = new SeoUrlGen();
		   //ToDO: need to populate the region pages
		  
		   // get the country name based on the region
		    $country = new Country ( ) ;
			$europe = $country->findCountryByRegion ( 1 ) ;
			$americas = $country->findCountryByRegion ( '2,3,4' ) ;
			$asiapacific = $country->findCountryByRegion ( '6,7' ) ;
			$africa = $country->findCountryByRegion ( 5 ) ;
			
			$team = new Team();
			// Europe COuntry List and their links
			foreach($europe as $listeurope) 
			{
        		$loc = $urlGen->getClubsInACountryWithRegion($listeurope["country_name"], '0', 'europe', $listeurope["country_id"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
				$teams = $team->selectTeamsByCountry($listeurope["country_id"]);
				foreach($teams as $indteam)
				{										
        			$loc = $urlGen->getClubMasterProfileUrl($indteam["team_id"], $indteam["team_name"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;				
				}
			}
		
			// America 
			foreach($americas as $listamericas) 
			{
				$loc = $urlGen->getClubsInACountryWithRegion($listamericas["country_name"], '0', 'americas', $listamericas["country_id"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;	
			}
			
			// Asia Pacific 
			foreach($asiapacific as $listasiapacific) 
			{
				$loc = $urlGen->getClubsInACountryWithRegion($listasiapacific["country_name"], '0', 'asia', $listasiapacific["country_id"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;	
			}
			
			// Africa
			foreach($africa as $listafrica) 
			{
				$loc = $urlGen->getClubsInACountryWithRegion($listafrica["country_name"], '0', 'africa', $listafrica["country_id"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;	
			} 
			$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
			//echo $finalXML;
		   fwrite($fp, $finalXML);
		   fclose($fp);
		}
		else 
		{
			echo "exiting...";
			exit;
		}
	}	
	
	function generateLeaguesSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		//For Linux Server - Create an empty sitemap-leagues.xml file on the  /var/www/path_to_xml_files  path 
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "leaguesSiteMapFile" );
		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
		
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		if ($fp) {
		   $urlGen = new SeoUrlGen();
		   //ToDO: need to populate the region pages
		  
		   // load an object of type leaguecompetition
		   $lea_comp = new LeagueCompetition ( ) ;
		   
		   // Get regional competition list and contry names under each region (group)
		   $lea_comp = new LeagueCompetition ( ) ;
			$rafrica = $lea_comp->findRegionalCompetitionsByContinent ( 5, '1,2' ) ; //Africa
			$ramericas = $lea_comp->findRegionalCompetitionsByContinent ( '2,3,4', '1,2' ) ; //Americas
			$rasiapacific = $lea_comp->findRegionalCompetitionsByContinent ( '6,7', '1,2' ) ; //Asia & Pacific Islands
			$reurope = $lea_comp->findRegionalCompetitionsByContinent ( 1, '1,2' ) ; //Europe
			$rfifa = $lea_comp->findRegionalCompetitionsByContinent ( 8, '1,2' ) ; //FiFA
			//Domestic Competitions
			$dafrica = $lea_comp->findDomesticCompetitionsByContinent ( 5, '0' ) ; //Africa
			$damericas = $lea_comp->findDomesticCompetitionsByContinent ( '2,3,4', '0' ) ; //Americas
			$dasiapacific = $lea_comp->findDomesticCompetitionsByContinent ( '6,7', '0' ) ; //Asia & Pacific Islands
			$deurope = $lea_comp->findDomesticCompetitionsByContinent ( 1, '0' ) ; //Europe
			$dfifa = $lea_comp->findDomesticCompetitionsByContinent ( 8, '0' ) ; //FiFA
			$regionGroupNames = array("european", "asian", "african", "american", "international");
			// TOP Level (region level urls
			foreach($regionGroupNames as $regiongroup)
			{
				$loc = $urlGen->getShowRegionUrl($regiongroup,true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;				
			}
			
			// Europe Regional Competitions
			foreach($reurope as $europeRegional)
			{
				$loc = $urlGen->getShowRegionalCompetitionsByRegionUrl($europeRegional["competition_name"], $europeRegional["competition_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// Europe Contry List to dispaly their domestic competitions
			foreach($deurope as $europeCountry)
			{
				$loc = $urlGen->getShowDomesticCompetitionsByCountryUrl($europeCountry["country_name"], $europeCountry["country_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
				// Dommestic Competitions for each country
				$dcountry = $lea_comp->findDomesticCompetitionsByCountry (  $europeCountry["country_id"] ) ; //By Country
				foreach($dcountry as $countryDomestic)
				{
					$loc = $urlGen->getShowDomesticCompetitionUrl($countryDomestic["competition_name"], $countryDomestic["competition_id"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;
				}
			}
			
			// American Regional Competitions
			foreach($ramericas as $americaRegional)
			{
				$loc = $urlGen->getShowRegionalCompetitionsByRegionUrl($americaRegional["competition_name"], $americaRegional["competition_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// America Contry List to dispaly their domestic competitions
			foreach($damericas as $americaCountry)
			{
				$loc = $urlGen->getShowDomesticCompetitionsByCountryUrl($americaCountry["country_name"], $americaCountry["country_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
			// Dommestic Competitions for each country
				$dcountry = $lea_comp->findDomesticCompetitionsByCountry (  $americaCountry["country_id"] ) ; //By Country
				foreach($dcountry as $countryDomestic)
				{
					$loc = $urlGen->getShowDomesticCompetitionUrl($countryDomestic["competition_name"], $countryDomestic["competition_id"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;
				}
			}
			
			// Africa Regional Competitions
			foreach($rafrica as $africaRegional)
			{
				$loc = $urlGen->getShowRegionalCompetitionsByRegionUrl($africaRegional["competition_name"], $africaRegional["competition_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// Africa Contry List to dispaly their domestic competitions
			foreach($dafrica as $africaCountry)
			{
				$loc = $urlGen->getShowDomesticCompetitionsByCountryUrl($africaCountry["country_name"], $africaCountry["country_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
			// Dommestic Competitions for each country
				$dcountry = $lea_comp->findDomesticCompetitionsByCountry (  $africaCountry["country_id"] ) ; //By Country
				foreach($dcountry as $countryDomestic)
				{
					$loc = $urlGen->getShowDomesticCompetitionUrl($countryDomestic["competition_name"], $countryDomestic["competition_id"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;
				}
			}
			
			// 	Asia Pacific Regional Competitions
			foreach($rasiapacific as $asiaRegional)
			{
				$loc = $urlGen->getShowRegionalCompetitionsByRegionUrl($asiaRegional["competition_name"], $asiaRegional["competition_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// Africa Contry List to dispaly their domestic competitions
			foreach($dasiapacific as $asiaCountry)
			{
				$loc = $urlGen->getShowDomesticCompetitionsByCountryUrl($asiaCountry["country_name"], $asiaCountry["country_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
			// Dommestic Competitions for each country
				$dcountry = $lea_comp->findDomesticCompetitionsByCountry (  $asiaCountry["country_id"] ) ; //By Country
				foreach($dcountry as $countryDomestic)
				{
					$loc = $urlGen->getShowDomesticCompetitionUrl($countryDomestic["competition_name"], $countryDomestic["competition_id"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;
				}
			}
			
		// international Regional Competitions
			foreach($rfifa as $fifaRegional)
			{
				$loc = $urlGen->getShowRegionalCompetitionsByRegionUrl($fifaRegional["competition_name"], $fifaRegional["competition_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// Africa Contry List to dispaly their domestic competitions
			foreach($dfifa as $fifaCountry)
			{
				$loc = $urlGen->getShowDomesticCompetitionsByCountryUrl($fifaCountry["country_name"], $fifaCountry["country_id"],true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			/*
			$team = new Team();
			// Europe COuntry List and their links
			foreach($europe as $listeurope) 
			{
        		$loc = $urlGen->getClubsInACountryWithRegion($listeurope["country_name"], '0', 'europe', $listeurope["country_id"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
				
				$teams = $team->selectTeamsByCountry($listeurope["country_id"]);
				foreach($teams as $indteam)
				{										
        			$loc = $urlGen->getClubMasterProfileUrl($indteam["team_id"], $indteam["team_name"], True);
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;				
				}
			}		
			*/
			$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
			//echo $finalXML;
		   fwrite($fp, $finalXML);
		   fclose($fp);
		}
		else 
		{
			echo "exiting...";
			exit;
		}
	}	
	
	function generateScoresSchedulesSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		//For Linux Server - Create an empty sitemap-leagues.xml file on the  /var/www/path_to_xml_files  path 
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "scoresSiteMapFile" );
		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
		
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		if ($fp) {
		   $urlGen = new SeoUrlGen();
		   //ToDO: need to populate the region pages
		  
		   // load an object of type leaguecompetition
		   $mat = new Matchh();
		   $beginDate = "2008/6/20";
		   $todays_date = date("m/d/y"); ;
		   $ts = strtotime ( $todays_date ) ;
		   $one_week = 24 * 60 * 60 ;
			//$one_week_after = date ( "Y-m-d", ($ts + 7 * $one_week) ) ;
			$one_week_after = "2008/8/1";
			$scores_schedules = $mat->selectAllMatchesByCountryLeague($beginDate, $one_week_after, '0', '0', null);
			echo count($scores_schedules);
			foreach($scores_schedules as $match)
			{
				$loc = $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;
			}
			// TOP Level (region level urls
			//foreach($regionGroupNames as $regiongroup)
			//{
			//	$loc = $urlGen->getShowRegionUrl($regiongroup,true);
			//	$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>daily</changefreq>$nl$indent</url>$nl";
			//	$urlsetValue = $urlsetValue.$url;				
			//}
			
			$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
			//echo $finalXML;
		   fwrite($fp, $finalXML);
		   fclose($fp);
		}
		else 
		{
			echo "exiting...";
			exit;
		}
	}	
	
	function generateProfilesSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		//change here as well
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "profilesSiteMapFile" );
		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
		//$session = new Zend_Session_Namespace('userSession');
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		if ($fp) {
		   $urlGen = new SeoUrlGen();
		   
		   $user = new User();
		   //$users = $user->findUsers($session->userId);
           $users = $user->findUsersAll();
		    
		   foreach($users as $profile)
		   {
		   		$loc = $urlGen->getUserProfilePage($profile["screen_name"], True);
				$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>hourly</changefreq>$nl$indent</url>$nl";
				$urlsetValue = $urlsetValue.$url;			
		   }					
			 
			$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
			//echo $finalXML;
		   fwrite($fp, $finalXML);
		   fclose($fp);
		}
		else 
		{
			echo "exiting...";
			exit;
		}
	}

	function generateNewsSiteMapFile()
	{
		// set up the new line and indent variables
		$nl = "\n";
		$indent = "  ";
		
		//For Linux Server - Create an empty sitemap-players.xml file on the  /var/www/path_to_xml_files  path 
		$siteMapFileLocation = Zend_Registry::get ( "appDirectory" ).Zend_Registry::get ( "newsSiteMapFile" );
		

		
		// SiteMap file Header
		$xmlHeader = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$nl";

		$urlsetOpen = "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.9\" 
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
		xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.9 
		http://www.google.com/schemas/sitemap/0.9/sitemap.xsd\">$nl";
		$urlsetValue = "";
		$urlsetClose = "</urlset>$nl";
				
		$fp = fopen($siteMapFileLocation, "w") or die('counld not open file');
		try {
			if ($fp) {		   	
				$newsFeed = new NewsFeed();
				// Each Article has its own page
				// So, get all the news feeds;
				$rowset = $newsFeed->selectNewsFeeds();
				
				$urlGen = new SeoUrlGen();			
				foreach ($rowset as $data)
				{	
					$loc = $urlGen->getIndividualNewsArticlePageUrl($data["news_headline"], $data["news_id"], true);				
					$url = "$indent<url>$nl$indent$indent<loc>".$loc."</loc>$nl$indent$indent<priority>1</priority>$nl$indent$indent<changefreq>monthly</changefreq>$nl$indent</url>$nl";
					$urlsetValue = $urlsetValue.$url;				
				}
				$finalXML =  $xmlHeader.$urlsetOpen.$urlsetValue.$urlsetClose;
				//echo $finalXML;
			   fwrite($fp, $finalXML);
			   fclose($fp);
			}
			else 
			{
				echo "exiting...";
				exit;
			}
		}
		catch (Exception $e) 
			{
    			echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
	
	?>
