<?php class UrlGenHelper {

	public function seoEncode($str)
	{
		// $str = str_replace(" ", "-", $str);
		// $str = strtolower($str);
		// $str = urlencode($str);		
		// return strval($str);
		return $this->slug($str);
	}
	
	public function seoEncodePlayers($str)
	{
		 $str = str_replace(" ", "-", $str);
		 $str = strtolower($str);
		 //$str = urlencode($str);		
		 //return strval($str);
		 return $this->slug($str);
	}
	
	public function replace($str1 , $str2 , $str)
	{
		 $str = str_replace($str1, $str2, $str);
		 $str = strtolower($str);
		return $this->slug($str);
	}
	
	

	function slug($str)
	{
		$str = strtolower(trim($str));
//	    $str = preg_replace('/[^a-z0-9-\']/', '-', $str);
//		$str = preg_replace('/\'/', "", $str);
//		$str = preg_replace('/-+/', "-", $str);
		return $str;
	}
}

?>