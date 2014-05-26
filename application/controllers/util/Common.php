<?php

class Common {
	
	protected $SALT_LENGTH = 9;
	
	function convertDates($timeEvent , $format = null) {
		date_default_timezone_set('America/New_York');
		$session_time = strtotime ( $timeEvent );
		$time_difference = time () - $session_time;
		//echo "Fecha:" . date('Y-m-d h:i:s', $session_time);;
		//echo "Hoy:" . date('Y-m-d h:i:s', time ());
		$seconds = $time_difference;
		$minutes = round ( $time_difference / 60 );
		$hours = round ( $time_difference / 3600 );
		$days = round ( $time_difference / 86400 );
		$weeks = round ( $time_difference / 604800 );
		$months = round ( $time_difference / 2419200 );
		$years = round ( $time_difference / 29030400 );
		// Seconds
		//echo $hours; 
		if ($seconds <= 60) {
			echo "$seconds seconds ago";
		} //Minutes
		else if ($minutes <= 60) {
			
			if ($minutes == 1) {
				echo "one minute ago";
			} else {
				echo "$minutes minutes ago";
			}
		
		} //Hours
		
		else if ($hours <= 24) {
			if(!is_null($format)){
				echo "Today";
			}else{
				if ($hours == 1) {
					echo "one hour ago";
				} else {
					echo "$hours hours ago";
				}
			}	
		
		} //Days
		else if ($days <= 7) {
			
			if ($days == 1) {
				echo "1 day ago";
			} else {
				echo "$days days ago";
			}
		
		} //Weeks
		else if ($weeks <= 4) {
			
			if ($weeks == 1) {
				echo "one week ago";
			} else {
				echo "$weeks weeks ago";
			}
		
		} //Months
		else if ($months <= 12) {
			
			if ($months == 1) {
				echo "one month ago";
			} else {
				echo "$months months ago";
			}
		
		} //Years
		else {
			
			if ($years == 1) {
				echo "one year ago";
			} else {
				echo "$years years ago";
			}
		
		}
	
	}
	
	public function convertDates2($lastDate) {
		
		//s$ = strtotime(date("D,j M Y G:i:s T"),$lastDate);
		//echo $lastDate;

		$date1 = strtotime ( $lastDate );
		if ($date1 != '') {
			$today = strtotime ( date ( "Y-m-d h:i:s" ) );
			echo $today . ">>>" . $date1;
			$long = $today - $date1;
			//echo $long ."<br>";
			if ($long == 0) {
				return "Today";
			}
			
			$years = round ( $long / 31104000 );
			$remainder00 = $long % 31104000;
			
			$months = round ( $remainder00 / 2592000 );
			$remainder0 = $long % 2592000;
			
			$days = round ( $remainder0 / 86400 );
			//echo $days . "<br>";
			$remainder1 = $long % 86400;
			$hours = round ( $remainder1 / (60 * 60) );
			$remainder = $remainder1 % (60 * 60);
			$minutes = round ( $remainder / 60 );
			
			if ($years == 1) {
				$years .= " year ago";
				return $years;
			} else if ($years > 1) {
				$years .= " years ago";
				return $years;
			}
			if ($months == 1) {
				$months .= " month ago";
				return $months;
			} else if ($months > 1) {
				$months .= " months ago";
				return $months;
			}
			$numweek = $days / 7;
			$isweek = $days % 7;
			//echo $isweek . ">>" .$numweek ;
			if ($isweek == 0 && $numweek == 1) {
				$numweek .= " week ago";
				return $numweek;
			} else if ($numweek > 1) {
				return date ( 'Y/m/d', $date1 );
			}
			if ($days == 1) {
				$days .= " day ago";
				return $days;
			} else if ($days > 1) {
				$days .= " days ago";
				return $days;
			}
			if ($hours == 1) {
				$hours .= " hour ago";
				return $hours;
			} else if ($hours > 1) {
				$hours .= " hours ago";
				return $hours;
			}
			
			if ($minutes == 1) {
				$minutes .= " minute ago";
				return $minutes;
			} else if ($minutes > 1) {
				$minutes .= " minutes ago";
				return $minutes;
			} else if ($minutes < 1) {
				$minutes = "Less than a minute ago";
				return $minutes;
			}
		} else {
			return "Never";
		}
		return null;
	
	}
	
	public function generateHash($plainText, $salt = null) {
		if ($salt === null) {
			$salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, $this->SALT_LENGTH );
		} else {
			$salt = substr ( $salt, 0, $this->SALT_LENGTH );
		}
		return $salt . sha1 ( $salt . $plainText );
	}
	
	//this function is called by addWordImage Action
	public function random_string($len = 5, $str = '') {
		for($i = 1; $i <= $len; $i ++) {
			//generates a random number that will be the ASCII code of the character. We only want numbers (ascii code from 48 to 57) and caps letters.
			$ord = rand ( 48, 90 );
			if ((($ord >= 48) && ($ord <= 57)) || (($ord >= 65) && ($ord <= 90)))
				$str .= chr ( $ord );
			else
				//If the number is not good we generate another one
				$str .= $this->random_string ( 1 );
		}
		return $str;
	}
	
	//returns the age based on the dob
	//returns the age based on the dob
	public function GetAge($DOB) {
		
		$birth = explode ( "-", $DOB );
		$age = date ( "Y" ) - $birth [0];
		
		if ($age < 99) {
			if (($birth [1] > date ( "m" )) || ($birth [1] == date ( "m" ) && date ( "d" ) < $birth [2])) {
				$age -= 1;
			
			}
			return $age;
		
		} else {
			
			$age = "n/a";
			return $age;
		}
	
	}
	
	//set the session variable
	public function setLoginSessionVariables($rowset) {
		$country = new Country ();
		$session = new Zend_Session_Namespace ( "userSession" );
		$session->email = $rowset->email;
		//$session->psw = $psw;
		$session->firstName = $rowset->first_name;
		$session->lastName = $rowset->last_name;
		$session->userId = $rowset->user_id;
		$session->screenName = $rowset->screen_name;
		$session->mainPhoto = $rowset->main_photo;
		
		$session->bio = $rowset->aboutme_text;
		$session->dateJoined = $rowset->registration_date;
		// 			$countryLive = $country->fetchRow('country_id = ' .$rowset->country_live);
		// 			if($countryLive != null){
		//         	$rowArray = $countryLive->toArray();
		//         	$session->countryLive = $rowArray['country_name'];
		//       }
		$dob = explode ( '-', $rowset->dob );
		$session->year = $dob [0];
		$session->month = $dob [1];
		$session->day = $dob [2];
	}
	
	function adv_count_words($str, $numwords) {
		$words = 0;
		$newtext = 0;
		$str = eregi_replace ( " +", " ", $str );
		$array = explode ( " ", $str );
		for($i = 0; $i < count ( $array ); $i ++) {
			if (eregi ( "[0-9A-Za-z�-��-��-�]", $array [$i] ))
				$words ++;
			if ($words <= $numwords) {
				$newtext += strlen ( $array [$i] ) + 1;
			}
		}
		
		return substr ( $str, 0, $newtext );
	}
	
	function getBreadCrumbs($data) {
		$params = array ('trail' => $data, 'separator' => '<li>|</li>', 'truncate' => 40 );
		
		$links = array ();
		$numSteps = count ( $params ['trail'] );
		for($i = 0; $i < $numSteps; $i ++) {
			$step = $params ['trail'] [$i];
			
			// build the link if it's set and isn't the last step
			if (strlen ( $step ['link'] ) > 0 && $i < $numSteps - 1) {
				$links [] = sprintf ( '<li><a href="%s" title="%s">%s</a></li>', htmlSpecialChars ( $step ['link'] ), htmlSpecialChars ( $step ['title'] ), htmlSpecialChars ( $step ['title'] ) );
			} else {
				// either the link isn't set, or it's the last step
				$links [] = sprintf ( '<li class="last">%s</li>', htmlSpecialChars ( $step ['title'] ) );
			}
		}
		
		// join the links using the specified separator
		return join ( $params ['separator'], $links );
	}
	
	function serializemmp($toserialize) {
		if ($toserialize instanceof SimpleXMLElement) {
			$stdClass = new stdClass ();
			$stdClass->type = get_class ( $toserialize );
			$stdClass->data = $toserialize->asXml ();
		}
		return serialize ( $stdClass );
	}
	
	function unserializemmp($tounserialize) {
		$tounserialize = unserialize ( $tounserialize );
		if ($tounserialize instanceof stdClass) {
			if ($tounserialize->type == "SimpleXMLElement") {
				$tounserialize = simplexml_load_string ( $tounserialize->data );
			}
		}
		return $tounserialize;
	}
	
	function str_replace_utf($str) {
		$search = array ('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�' );
		
		$replace = array ('a', 'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'oe', 'o', 'o', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O', 'u', 'u', 'u', 'U', 'U', 'U', 'c', 'C', 'N', 'n' );
		
		return str_replace ( $search, $replace, $str );
	
	}
	function mb_str_replace($subject) {
		
		$search = array ('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�' );
		
		$replace = array ('a', 'a', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'oe', 'o', 'o', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O', 'u', 'u', 'u', 'U', 'U', 'U', 'c', 'C', 'N', 'n' );
		
		if (is_array ( $subject )) {
			foreach ( $subject as $key => $val ) {
				$subject [$key] = mb_str_replace ( ( string ) $search, $replace, $subject [$key] );
			}
			return $subject;
		}
		$pattern = '/[' . preg_quote ( implode ( '', ( array ) $search ), '/' ) . ']/u';
		if (is_array ( $search )) {
			if (is_array ( $replace )) {
				$len = min ( count ( $search ), count ( $replace ) );
				$table = array_combine ( array_slice ( $search, 0, $len ), array_slice ( $replace, 0, $len ) );
				$f = create_function ( '$match', '$table = ' . var_export ( $table, true ) . '; return array_key_exists($match[0], $table) ? $table[$match[0]] : $match[0];' );
				$subject = preg_replace_callback ( $pattern, $f, $subject );
				return $subject;
			}
		}
		$subject = preg_replace ( $pattern, ( string ) $replace, $subject );
		return $subject;
	}
	
	/*==================================
	Replaces special characters with non-special equivalents
	==================================*/
	function normalize_special_characters($str) {
		# Quotes cleanup
		$str = ereg_replace ( chr ( ord ( "`" ) ), "'", $str ); # `
		$str = ereg_replace ( chr ( ord ( "�" ) ), "'", $str ); # �
		$str = ereg_replace ( chr ( ord ( "�" ) ), ",", $str ); # �
		$str = ereg_replace ( chr ( ord ( "`" ) ), "'", $str ); # `
		$str = ereg_replace ( chr ( ord ( "�" ) ), "'", $str ); # �
		$str = ereg_replace ( chr ( ord ( "�" ) ), "\"", $str ); # �
		$str = ereg_replace ( chr ( ord ( "�" ) ), "\"", $str ); # �
		$str = ereg_replace ( chr ( ord ( "�" ) ), "'", $str ); # �
		

		$unwanted_array = array ('�' => 'S', '�' => 's', '�' => 'Z', '�' => 'z', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'C', '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'I', '�' => 'I', '�' => 'I', '�' => 'I', '�' => 'N', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'U', '�' => 'U', '�' => 'U', '�' => 'U', '�' => 'Y', '�' => 'B', '�' => 'Ss', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'c', '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'o', '�' => 'n', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'y', '�' => 'y', '�' => 'b', '�' => 'y' );
		$str = strtr ( $str, $unwanted_array );
		
		# Bullets, dashes, and trademarks
		$str = ereg_replace ( chr ( 149 ), "&#8226;", $str ); # bullet �
		$str = ereg_replace ( chr ( 150 ), "&ndash;", $str ); # en dash
		$str = ereg_replace ( chr ( 151 ), "&mdash;", $str ); # em dash
		$str = ereg_replace ( chr ( 153 ), "&#8482;", $str ); # trademark
		$str = ereg_replace ( chr ( 169 ), "&copy;", $str ); # copyright mark
		$str = ereg_replace ( chr ( 174 ), "&reg;", $str ); # registration mark
		

		return $str;
	}
	function startsWith($haystack, $needle) {
		// @param mixed haystack: Object to look in
		// @param string needle: String to look for
		// @return: if found, true, otherwise false
		

		if (is_array ( $haystack )) {
			foreach ( $haystack as $hay ) {
				if (substr ( $hay, 0, strlen ( $needle ) ) == $needle) {
					return true;
				}
			}
			//return in_array($needle, $haystack);
			return false;
		} else {
			return (substr ( $haystack, 0, strlen ( $needle ) ) == $needle);
		}
	}
	
	public function calculateDates($temp = null) {
		
		$temp2 = $this->_request->getParam ( 'date', '0' );
		
		if (trim ( $temp2 ) == "") {
			$temp2 = 'today';
		}
		if (trim ( $temp2 ) == "0") {
			$temp2 = $temp;
		}
		$date = '';
		$todays_date = date ( "Y-m-d" );
		$todays_init_date = $this->_request->getParam ( 'initDateTime', '0' );
		$todays_end_date = $this->_request->getParam ( 'endDateTime', '0' );
		//echo $todays_init_date . ' - ' . $todays_end_date;
		//$todays_date = date("D,j M Y G:i:s T");
		//$todays_date = '2007-02-10';
		$ts = strtotime ( $todays_date );
		$tsid = strtotime ( $todays_init_date );
		$tsed = strtotime ( $todays_end_date );
		# figure out what 7 days is in seconds
		$one_week = 24 * 60 * 60;
		$one_week_after = 0;
		$one_week_after_init = 0;
		$one_week_after_end = 0;
		Zend_Loader::loadClass ( 'Zend_Filter_StripTags' );
		$filter = new Zend_Filter_StripTags ();
		if ($this->_request->isPost ()) {
			$date = trim ( $filter->filter ( $this->_request->getParam ( 'date' ) ) );
			
			//$post = Zend_Registry::get('post');
			//$date = trim($post->getEscaped('date'));
			if ($date == "") {
				$date = "today";
			}
			if ($date == 'today') {
				$one_week_after = $todays_date;
				$one_week_after_init = $todays_init_date;
				$one_week_after_end = $todays_end_date;
			} else if ($date == 'tomorrow') {
				$todays_date = date ( "Y-m-d H:i:s", ($ts + (1 * $one_week)) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid + (1 * $one_week)) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed + (1 * $one_week)) );
				$one_week_after = date ( "Y-m-d H:i:s", ($ts + (1 * $one_week)) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + (1 * $one_week)) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + (1 * $one_week)) );
			} else if ($date == 'week') {
				$todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
				$one_week_after = date ( "Y-m-d H:i:s", ($ts + 7 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 7 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 7 * $one_week) );
			} else if ($date == 'last') {
				$one_week_after = $todays_date;
				$one_week_after_init = $todays_init_date;
				$one_week_after_end = $todays_end_date;
				$todays_date = date ( "Y-m-d H:i:s", ($ts - 7 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 7 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 7 * $one_week) );
			} else if ($date == '-3') {
				$one_week_after = $todays_date;
				$one_week_after_init = $todays_init_date;
				$one_week_after_end = $todays_end_date;
				$todays_date = date ( "Y-m-d H:i:s", ($ts - 3 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 3 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 3 * $one_week) );
			} else if ($date == '3') {
				$todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
				$one_week_after = date ( "Y-m-d H:i:s", ($ts + 3 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 3 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 3 * $one_week) );
			} else if ($date == 'yesterday') {
				$one_week_after = date ( "Y-m-d H:i:s", ($ts - 1 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid - 1 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed - 1 * $one_week) );
				$todays_date = $one_week_after;
				$todays_init_date = $one_week_after_init;
				$todays_end_date = $one_week_after_end;
			}
		
		} else {
			$date = trim ( $filter->filter ( $this->_request->getParam ( 'date' ) ) );
			if ($temp2 == 'today') {
				$one_week_after = $todays_date;
				$one_week_after_init = $todays_init_date;
				$one_week_after_end = $todays_end_date;
			} else if ($date == 'tomorrow') {
				$todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
				$one_week_after = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
			} else if ($temp2 == 'week') {
				$todays_date = date ( "Y-m-d H:i:s", ($ts + 1 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid + 1 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed + 1 * $one_week) );
				$one_week_after = date ( "Y-m-d H:i:s", ($ts + 7 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid + 7 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed + 7 * $one_week) );
			} else if ($temp2 == 'last') {
				$one_week_after = $todays_date;
				$one_week_after_init = $todays_init_date;
				$one_week_after_end = $todays_end_date;
				$todays_date = date ( "Y-m-d H:i:s", ($ts - 7 * $one_week) );
				$todays_init_date = date ( "Y-m-d H:i:s", ($tsid - 7 * $one_week) );
				$todays_end_date = date ( "Y-m-d H:i:s", ($tsed - 7 * $one_week) );
			} else if ($date == 'yesterday') {
				$one_week_after = date ( "Y-m-d H:i:s", ($ts - 1 * $one_week) );
				$one_week_after_init = date ( "Y-m-d H:i:s", ($tsid - 1 * $one_week) );
				$one_week_after_end = date ( "Y-m-d H:i:s", ($tsed - 1 * $one_week) );
				$todays_date = $one_week_after;
				$todays_init_date = $one_week_after_init;
				$todays_end_date = $one_week_after_end;
			}
			$date = $temp2;
		}
		$fechas [0] = $todays_date;
		$fechas [1] = $todays_init_date;
		$fechas [2] = $todays_end_date;
		$fechas [3] = $one_week_after;
		$fechas [4] = $one_week_after_init;
		$fechas [5] = $one_week_after_end;
		
		$fechas [6] = $date;
		return $fechas;
	
	}
	
	public function imageResize($width, $height, $target) {
		//takes the larger size of the width and height and applies the  
		//formula accordingly...this is so this script will work  
		//dynamically with any size image 
		if ($width > $height) {
		$percentage = ($target / $width);
		} else {
		$percentage = ($target / $height);
		}
		
		//gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);
		
		//returns the new sizes in html image tag format...this is so you
		//can plug this function inside an image tag and just get the
		
		return "width=\"$width\" height=\"$height\""; 
		
	
	}
	
	
	
	
	public function array_sort($array, $on, $order = SORT_ASC) {
		$new_array = array ();
		$sortable_array = array ();
		
		if (count ( $array ) > 0) {
			foreach ( $array as $k => $v ) {
				if (is_array ( $v )) {
					foreach ( $v as $k2 => $v2 ) {
						if ($k2 == $on) {
							$sortable_array [$k] = $v2;
						}
					}
				} else {
					$sortable_array [$k] = $v;
				}
			}
			
			switch ($order) {
				case SORT_ASC :
					asort ( $sortable_array );
					break;
				case SORT_DESC :
					arsort ( $sortable_array );
					break;
			}
			
			foreach ( $sortable_array as $k => $v ) {
				$new_array [$k] = $array [$k];
			}
		}
		
		return $new_array;
	}
	
	


	public function parse_tweet_feed($feed) {
	    $stepOne = explode("<content type=\"html\">", $feed);
	    $stepTwo = explode("</content>", $stepOne[1]);
	    $tweet = $stepTwo[0];
		$tweet = htmlspecialchars_decode($tweet,ENT_QUOTES);
	    return $tweet;
	}
	
	public function autoConvert($text) {	
    	$text = preg_replace("/((http(s?):\/\/)|(www\.))([\w\.]+)([a-zA-Z0-9?&%.;:\/=+_-]+)/i", "<a href='http$3://$4$5$6' target='_blank'>$2$4$5$6</a>", $text);
    	$text = preg_replace("/(?<=\A|[^A-Za-z0-9_])@([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/$1' target='_blank'>$0</a>", $text);
    	$text = preg_replace("/(?<=\A|[^A-Za-z0-9_])#([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/search?q=%23$1' target='_blank'>$0</a>", $text);
    	return $text;
	}

	// http://www.inkplant.com/code/social-media-share-buttons.php
	public function social_links($args) {
	    if (!is_array($args)) { return false; }
	    if (substr($args['url'],0,4) != 'http') { return false; }
	    if (!is_array($args['platforms'])) { return false; }

	    $title = $args['title'];
	    if (!$title) { $title = '(Untitled)'; }
	    
	    //this function currently handles the following 4 social media platforms:
	    $platforms = array(
	      'facebook'=>'Facebook'
	     ,'twitter'=>'Twitter'
	     ,'pinterest'=>'Pinterest'
	     ,'google-plus'=>'Google+'
	    );

	    $urls = array();
	    
	    //Facebook
	    if (in_array('facebook',$args['platforms'])) {
	        //Facebook
	        $urls['facebook'] = 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($args['url']);
	    }

	    //Twitter
	    if (in_array('twitter',$args['platforms'])) {
	        //shorten the title if necessary so this fits under Twitter's 140 character cap
	        $url_chars = 23; //shortened by t.co
	        if ($args['twitter_username']) { $via_chars = strlen(' via @'.$args['twitter_username']); } else { $via_chars = 0; }
	        $max_chars = 140 - $url_chars - $via_chars;
	        $text = $title;
	        if (strlen($text) > $max_chars) { $text = substr($text,0,($max_chars-3)).'...'; }
	        $urls['twitter'] = 'https://twitter.com/intent/tweet?text='.urlencode($text).'&url='.urlencode($args['url']);
	        if ($args['twitter_username']) { $urls['twitter'] .= '&via='.urlencode($args['twitter_username']); }
	    }

	    //Pinterest
	    if ((in_array('pinterest',$args['platforms'])) && ($args['media_url'])) {
	        $urls['pinterest'] = 'http://pinterest.com/pin/create/button/?url='.urlencode($args['url']).'&media='.urlencode($args['media_url']).'&description='.urlencode($title); 
	    }

	    //Google+
	    if (in_array('google-plus',$args['platforms'])) {
	        $urls['google-plus'] = 'https://plus.google.com/share?url='.urlencode($args['url']).'&hl=en-US'; //the language is set to US English here
	    }

	    if (in_array($args['image_size'],array(16,24,32))) { $image_size = $args['image_size']; } else { $image_size = false; }
	    if ($args['image_path']) { $path = $args['image_path']; } else { $path = './'; }

	    $links = array();
	    foreach ($args['platforms'] as $key) {
	        if (array_key_exists($key,$platforms)) {
	            if ($image_size) {
	                $src = $path.$image_size.'px/'.$key.'.png';
	                $alt = 'Share this on '.$platforms[$key];
	                $caption = '<img src="'.$src.'" alt="'.$alt.'" title="'.$alt.'" style="width:'.$image_size.'px;height:'.$image_size.'px;border:0;" />';
	            } else {
	                $caption = $platforms[$key];
	            }
	            $url = $urls[$key];
	            $links[] = '<a href="'.$url.'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;">'.$caption.'</a>';
	        }
	    }

	    if (array_key_exists('separator',$args)) { $separator = $args['separator']; } else { $separator = ' '; }
	    return implode($separator,$links);
	}


}
?>
