<?php

/**
 * GoalFaceController
 * 
 * @author Juan Carlos Vasquez
 * @version  1.0
 */

require_once 'Zend/Cache.php';
require_once 'application/models/User.php';
require_once 'Zend/Session.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Controller/Action.php';
require_once 'util/PageTitleGen.php';
require_once 'util/BreadCrumbs.php';
require_once 'scripts/seourlgen.php';

class GoalFaceController extends Zend_Controller_Action {
	
	public $breadcrumbs;
	public $view;
	public $pageTitle;
	private static $_cache = null;
	private $numberOfItemsPerPage;
	private static $logger;
	private static $urlGen = null;
	
	private $curl = null;
	private $gsPath = "http://www.goalserve.com/getfeed/4ddbf5f84af0486b9958389cd0a68718/";
	
	
	function init() {
		self::$logger = Zend_Registry::get("logger");
		$this->view = Zend_Registry::get ( 'view' );
		$config = Zend_Registry::get ( 'config' );
		$this->view->root_crop = $config->path->crop;
		$this->view->imageplayerpath = $config->path->images->players;
		
		//$this->title = new PageTitleGen ( );
		//Breadcrumbs for home page
		$this->breadcrumbs = new Breadcrumbs ( );
		$this->breadcrumbs->addStep ( 'Home', $this->getUrl ( null, '' ) );
		$this->view->actualURL = $this->_request->getPathInfo ();
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		self::$urlGen = new SeoUrlGen ();
		//$this->view->facebook = $this->facebookLogin();
		//$view->facebook = $this->facebookLogin();
		/*if ($session->screenName == null) {
			$this->_redirect (self::$urlGen->getCurrentPath(true) . "/login" );
		}*/
	}
	
	
	private function getgsPath() {
		return $this->gsPath;
	}
	
	private function setgsPath($gsPath) {
		$this->gsPath = $gsPath;
	}
	
	/*public function facebookLogin(){
		$config = Zend_Registry::get ( 'config' );
		self::$urlGen = new SeoUrlGen ();
		$Application_Id = $config->facebook->appid;
        $Application_Security = $config->facebook->secret;
        $CallBack = 'window.location = "' . Zend_Registry::get ( "contextPath" ) . '/login/fbdologin"';
         
        //Zend_Debug::dump($CallBack);
        $Permissions = $config->facebook->permissions; // explain ur permission like offline_status, email,sms etc http://developers.facebook.com/docs/authentication/permissions.
        $facebook = new Facebook_Api($Application_Id,$Application_Security,$Permissions,$CallBack);
		//Zend_Debug::dump($facebook->InformationInfo());
        $session = new Zend_Session_Namespace ( 'userSession' );
        $session->facebook = $facebook;
    	return $facebook;
	}*/
	
	
	/**
	 * @return get XML array from goalserve feed
	 */
	public function getGoalserveFeed($pathextra) {
		# INSTANTIATE CURL.
		$this->curl = curl_init();
		
		# CURL SETTINGS.
		
		$urlPath = $this->getgsPath() . $pathextra;


		curl_setopt($this->curl, CURLOPT_URL, $urlPath);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 0);
		# SETTINGS FOR HTTP PROXY.
		#curl_setopt($ch, CURLOPT_PROXY, "http://160.76.xxx.xxx:8080");
		#curl_setopt($ch, CURLOPT_PROXYPORT, 8080); 
		
		# GRAB THE XML FILE.
		$xmlResult = curl_exec($this->curl);
		
		curl_close($this->curl);
		
		# SET UP XML OBJECT.
		$xml = new SimpleXMLElement( $xmlResult );
		//Zend_Debug::dump($result);
		return $xml;
	}
	
	/**
	 * @return unknown
	 */
	public function getNumberOfItemsPerPage() {
		$config = Zend_Registry::get ( 'config' );
		$this->numberOfItemsPerPage  = $config->path->pagination->numberitems;
		return $this->numberOfItemsPerPage;
	}
	
	/**
	 * @param unknown_type $numberOfItemsPerPage
	 */
	public function setNumberOfItemsPerPage($numberOfItemsPerPage) {
		$this->numberOfItemsPerPage = $numberOfItemsPerPage;
	}

	
	
	
	public function getUrl($action = null, $controller = null) {
		$url = rtrim ( $this->getRequest ()->getBaseUrl (), '/' );
		$url .= $this->_helper->url->simple ( $action, $controller );
		return $url;
	}
	
	public function updateLastActivityUserLoggedIn(){
		$session = new Zend_Session_Namespace('userSession');
		$user = new User();
		if($session->email !=null){
			$data = array ('last_activity' => time());
			$user->updateUser ( $session->email, $data );
		}
	}
	
	public function validateLoginExpired() {
		if (!Zend_Session::isDestroyed ()) {
			$this->_redirect ( "/login" );
		}
		
	}
	
	public function isUserLoggedIn(){
		
		$session = new Zend_Session_Namespace ( 'userSession' );
		if ($session->screenName == null) {
			$this->_redirect ( "/login" );
		}
		
	}
	
	public function postDispatch() {
		$this->view->breadcrumbs = $this->breadcrumbs;
	
	}
	
	static public function getCache() {
		$config = Zend_Registry::get ( 'config' );
    	$cache_dir = $config->path->cache;
    
		if (self::$_cache != null) {
			return self::$_cache;
		} // if (self::$_db != null)
		
		//Frontend attributes of what we're caching.
		$frontendoption = array('cache_id_prefix' => 'goalface_',
								'lifetime' => 300,
								'automatic_serialization'=> true);
		//Backend attributes
		$backendOptions = array('cache_dir' => $cache_dir);
			
		self::$_cache = Zend_Cache::factory ( 'Core', 'File', $frontendoption,
															  $backendOptions
															  );
		return self::$_cache;
	}
	
	public function checkifUserIsRemembered(){
	   /* Check if user has been remembered */
		$referrer = null;
		$session = new Zend_Session_Namespace('userSession');
		if($session->email == null){
			$referrer =  $_SERVER["REQUEST_URI"];
			$sessionCookie = new Zend_Session_Namespace ( 'userSessionCookie' );
			if(isset($_COOKIE['emailGoalface']) && isset($_COOKIE['passwordGoalface'])){
				self::$logger->info("Email Coockie Remember:" . $_COOKIE['emailGoalface']);
				self::$logger->info("Email Coockie passwordGoalface:" . $_COOKIE['passwordGoalface']);
				$sessionCookie->emailcookie =  $_COOKIE['emailGoalface'];
	            $sessionCookie->pswcookie =    $_COOKIE['passwordGoalface'];
	            $sessionCookie->referrer =    $referrer!=null ? $referrer : "";
				$this->_forward('dologin','login' );
				self::$logger->info("Do Login was executed:");
	        }
		}
	}

	public function getMatches($xml,$comp_gs_fix_name=null,$stageid=null,$weeknumber=null,$matchid=null,$competition_stage_id=null) {
		
		$stages = $xml->xpath("/results/tournament/stage");
				
		$allmatches = array();
				
				if ($stages != null) { //With Stages
					
					if ($stageid == null) { // tournaments with rounds all matches		
								
						$allstages = $xml->xpath("/results/tournament/stage");  //all stages
						
						//pass to a function that returns $allmatches[];
						foreach($allstages as $stage) {

							$matches = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match");
							if ($matches != null) {
								foreach($stage->match as $match) {
									$match['stageid'] = $stage['stage_id'];
									$match['week'] = null;
									$match['aggregate'] = null;
									$allmatches[] = $match;
									
								}
							} else {
								$weeks = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week");
								if ($weeks != null) {

									foreach($stage->week as $week) {
										foreach ($week->match as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = $week['number']; 
											$match['aggregate'] = null;
											$allmatches[] = $match;
										}
									}
								} else {
									
									$aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");									
										if ($aggregate != null) {
											$i = 1;
											foreach($stage->aggregate as $aggregates) {
												foreach($aggregates->match as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = null;
													$match['aggregate'] = 'A'.$stage['stage_id'].$i;
													$allmatches[] = $match;
												}
											$i++; }
										}
										
								}
							}
							
						} //pass to a function that returns $allmatches[];
						
						
					} else { // tournaments with rounds filtered by stage id 
						
						$stage['stage_id'] = $stageid;
						$allstages = $xml->xpath("/results/tournament/stage[@stage_id='".$stageid."']");
						//Zend_Debug::dump($allstages)."<BR>";
						//pass to a function that returns $allmatches[];
						foreach($allstages as $stage) {
								echo $matchid."<br>";
								//insertmatches/country/intl/fixture/friendlies/stage/15352763
								$matches = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match");
								if ($matches != null) {
									if ($matchid == null) {
										foreach($stage->match as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = null;
											$match['aggregate'] = null;
											$allmatches[] = $match;
											
										}
									} else {
										//insertmatches/country/intl/fixture/friendlies/stage/15352763/matchid/1193000
										$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/match[@id='".$matchid."']");
										foreach ($matchunique as $match) {
											$match['stageid'] = $stage['stage_id'];
											$match['week'] = null;
											$match['aggregate'] = null;
											$allmatches[] = $match;
										}
										
									}
	
								} else {
									
									$weeks = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week");
									if ($weeks != null) {
										
										if ($weeknumber !=null) {
											
											if ($matchid == null) {
												$week_matches =  $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[@number='".$weeknumber."']");
												foreach ($week_matches as $week){
													foreach ($week->match as $match) {
														$match['stageid'] = $stage['stage_id'];
														$match['week'] = $weeknumber;
														$match['aggregate'] = null;
														$allmatches[] = $match;
													}
												}
											} else {
												//only match filtered
												$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/week[@number='".$weeknumber."']/match[@id='".$matchid."']");
												foreach ($matchunique as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = $weeknumber;
													$match['aggregate'] = null;
													$allmatches[] = $match;
												}
											}
											
										} else {
											foreach($stage->week as $week) {
												foreach ($week->match as $match) {
													$match['stageid'] = $stage['stage_id'];
													$match['week'] = $week['number']; 
													$match['aggregate'] = null;
													$allmatches[] = $match;
												}
											}
										}
									} else {
										echo "here";
											$aggregate = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate");									
											if ($aggregate != null) {
												if ($matchid == null) {
													$i = 1;
													foreach($stage->aggregate as $aggregates) {
	
															foreach($aggregates->match as $match) {
																$match['stageid'] = $stage['stage_id'];
																$match['week'] = null;
																$match['aggregate'] = 'A'.$stage['stage_id'].$i;
																$allmatches[] = $match;
															}									
													$i++; }
												} else {
													//match unique filter with aggregate
													$matchunique = $xml->xpath("/results/tournament/stage[@stage_id='".$stage['stage_id']."']/aggregate/match[@id='".$matchid."']");
													foreach ($matchunique as $match) {
														$match['stageid'] = $stage['stage_id'];
														$match['week'] = null;
														$match['aggregate'] = null;
														$allmatches[] = $match;
													}
												}
											}
											
									}
								}
							}
							//pass to a function that returns $allmatches[];
		
					} 
				} else { // Without Stages
					
					//all matches
					if ($weeknumber == null) {
						$matcharrayweek = $xml->xpath("/results/tournament/week");  
						foreach ($matcharrayweek as $week) {
							foreach ($week->match as $match) {
								
								$match['stageid'] = $competition_stage_id;
								$match['week'] = $week['number'];
								$match['aggregate'] = null;
								$allmatches[] = $match;
							}
						}
						
					} else {
						// Filtered by week /week/xxxx;
						$allmatchweek = $xml->xpath("/results/tournament/week[@number='".$weeknumber."']");  //filter by match and week
						//echo $weeknumber;
						foreach ($allmatchweek as $week) {
							foreach ($week->match as $match) {
								$match['stageid'] = $competition_stage_id;
								$match['week'] = $weeknumber;
								$match['aggregate'] = null;
								$allmatches[] = $match;
							}
						}
							
					}
					
				}
				
		return $allmatches;
	}
}
