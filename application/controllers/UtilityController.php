<?php
require_once 'util/Thumbnail.php';

/**
 * UtilityController
 * 
 * @author
 * @version 
 */

class UtilityController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function imagecropnewAction()  
 	{  
 		//
 		
 		if (! isset ( $_REQUEST ["src"] )) {
			die ( "no image specified" );
		}
		
		// clean params before use
		$src = self::clean_source ( $_REQUEST ["src"] );
		// set document root
		$doc_root = self::get_document_root ( $src );
		
		// get path to image on file system
		$sourcePath = $doc_root . '/' . $src;
 		$w = preg_replace ( "/[^0-9]+/", "", self::get_request ( 'w', 100 ) );
		$h = preg_replace ( "/[^0-9]+/", "", self::get_request ( 'h', 100 ) );
		
	
 		$destPath = $doc_root . '/' . Zend_Registry::get("contextPath") . '/public/images/photos/thumbs/' . md5($src) .'.JPG';
 		
 		
 		//echo $destPath;
 		self::doCrop($sourcePath, $destPath, $w, $h);
	}
	
	private function doCrop($sourcePath, $destPath, $w, $h, $q = 100){
		
		$thumb = new Thumbnail($sourcePath);  
		$thumb->crop(0,0,$w, $h);  
		$thumb->save($destPath, $q);  
		header ( "Content-Type: image/jpeg"  );
		echo file_get_contents ( $destPath );
		
	}
	
	
	public function imageAction() {
		$request = $this->getRequest ();
		$response = $this->getResponse ();
		
		$picName = $request->getQuery ( 'pic' );
		$w = ( int ) $request->getQuery ( 'w' );
		$h = ( int ) $request->getQuery ( 'h' );
		
		//$hash = $request->getQuery ( 'hash' );
		

		//$realHash = DatabaseObject_BlogPostImage::GetImageHash ( $id, $w, $h );
		

		// disable autorendering since we're outputting an image
		$this->_helper->viewRenderer->setNoRender ();
		
		//		$image = new DatabaseObject_BlogPostImage ( $this->db );
		//		if (! $image->load ( $id )) {
		//			// image not found
		//			$response->setHttpResponseCode ( 404 );
		//			return;
		//		}
		

		try {
			$fullpath = self::createThumbnail ( $w, $h, $picName );
			//echo "Full Path:" . $fullpath;
		} catch ( Exception $ex ) {
			//$fullpath = $image->getFullPath ();
			echo $ex->getMessage ();
		}
		
		$info = getImageSize ( $fullpath );
		
		$response->setHeader ( 'content-type', 'image/jpeg' );
		$response->setHeader ( 'content-length', filesize ( $fullpath ) );
		echo file_get_contents ( $fullpath );
	}
	
	public function uploadFile($path) {
		if (! file_exists ( $path ) || ! is_file ( $path ))
			throw new Exception ( 'Unable to find uploaded file' );
		
		if (! is_readable ( $path ))
			throw new Exception ( 'Unable to read uploaded file' );
		
		$this->_uploadedFile = $path;
	}
	
	public function getFullPath($picName) {
		$fullPath = sprintf ( '%s/%s', self::GetUploadPath (), $picName );
		//echo $fullPath;
		return $fullPath;
	}
	
	public function createThumbnail($maxW, $maxH, $picName) {
		$fullpath = $this->getFullpath ( $picName );
		
		$ts = ( int ) filemtime ( $fullpath );
		$info = getImageSize ( $fullpath );
		
		$w = $info [0]; // original width
		$h = $info [1]; // original height
		

		$ratio = $w / $h; // width:height ratio
		

		$maxW = min ( $w, $maxW ); // new width can't be more than $maxW
		if ($maxW == 0) // check if only max height has been specified
			$maxW = $w;
		
		$maxH = min ( $h, $maxH ); // new height can't be more than $maxH
		if ($maxH == 0) // check if only max width has been specified
			$maxH = $h;
		
		$newW = $maxW; // first use the max width to determine new
		$newH = $newW / $ratio; // height by using original image w:h ratio
		

		if ($newH > $maxH) { // check if new height is too big, and if
			$newH = $maxH; // so determine the new width based on the
			$newW = $newH * $ratio; // max height
		}
		
		if ($w == $newW && $h == $newH) {
			// no thumbnail required, just return the original path
			return $fullpath;
		}
		
		switch ($info [2]) {
			case IMAGETYPE_GIF :
				$infunc = 'ImageCreateFromGif';
				$outfunc = 'ImageGif';
				break;
			
			case IMAGETYPE_JPEG :
				$infunc = 'ImageCreateFromJpeg';
				$outfunc = 'ImageJpeg';
				break;
			
			case IMAGETYPE_PNG :
				$infunc = 'ImageCreateFromPng';
				$outfunc = 'ImagePng';
				break;
			
			default :
				throw new Exception ( 'Invalid image type' );
		}
		// create a unique filename based on the specified options
		$filename = sprintf ( '%s.%dx%d.%d', $picName, $newW, $newH, $ts );
		
		// autocreate the directory for storing thumbnails
		$path = self::GetThumbnailPath ();
		if (! file_exists ( $path ))
			mkdir ( $path, 0777 );
		
		if (! is_writable ( $path ))
			throw new Exception ( 'Unable to write to thumbnail dir' );
			
		// determine the full path for the new thumbnail
		$thumbPath = sprintf ( '%s/%s', $path, $filename );
		
		if (! file_exists ( $thumbPath )) {
			
			// read the image in to GD
			$im = @$infunc ( $fullpath );
			if (! $im)
				throw new Exception ( 'Unable to read image file' );
				
			// create the output image
			$thumb = ImageCreateTrueColor ( $newW, $newH );
			
			// now resample the original image to the new image
			ImageCopyResampled ( $thumb, $im, 0, 0, 0, 0, $newW, $newH, $w, $h );
			
			$outfunc ( $thumb, $thumbPath );
		}
		
		if (! file_exists ( $thumbPath ))
			throw new Exception ( 'Unknown error occurred creating thumbnail' );
		if (! is_readable ( $thumbPath ))
			throw new Exception ( 'Unable to read thumbnail' );
		
		return $thumbPath;
	}
	
	public static function GetImageHash($id, $w, $h) {
		$id = ( int ) $id;
		$w = ( int ) $w;
		$h = ( int ) $h;
		
		return md5 ( sprintf ( '%s,%s,%s', $id, $w, $h ) );
	}
	
	public static function GetUploadPath() {
		$config = Zend_Registry::get ( 'config' );
		
		return sprintf ( '%s/photos', $config->photo->paths->data );
	}
	
	public static function GetThumbnailPath() {
		$config = Zend_Registry::get ( 'config' );
		
		return sprintf ( '%s/thumbs', $config->photo->paths->data );
	}
	
	//new TRYOUT
	

	

	
	public function imagecropAction() {
		
		// TimThumb script created by Tim McDaniels and Darren Hoyt with tweaks by Ben Gillbanks
		// http://code.google.com/p/timthumb/
		

		// MIT License: http://www.opensource.org/licenses/mit-license.php
		

		/* Parameters allowed: */
		
		// w: width
		// h: height
		// zc: zoom crop (0 or 1)
		// q: quality (default is 75 and max is 100)
		

		// HTML example: <img src="/scripts/timthumb.php?src=/images/whatever.jpg&w=150&h=200&zc=1" alt="" />
		
		$config = Zend_Registry::get ( 'config' );
		

		if (! isset ( $_REQUEST ["src"] )) {
			die ( "no image specified" );
		}
		
		// clean params before use
		$src = self::clean_source ( $_REQUEST ["src"] );
		
		// set document root
		$doc_root = self::get_document_root ( $src );
		
		// get path to image on file system
		$src = $doc_root . '/' . $src;
		
		$new_width = preg_replace ( "/[^0-9]+/", "", self::get_request ( 'w', 100 ) );
		$new_height = preg_replace ( "/[^0-9]+/", "", self::get_request ( 'h', 100 ) );
		$zoom_crop = preg_replace ( "/[^0-9]+/", "", self::get_request ( 'zc', 1 ) );
		$quality = preg_replace ( "/[^0-9]+/", "", self::get_request ( '9', 80 ) );
		
		// set path to cache directory (default is ./cache)
		// this can be changed to a different location
		//$cache_dir = './cache';
		$cache_dir = $config->path->cache;
		
		// get mime type of src
		$mime_type = self::mime_type ( $src );
		
		// check to see if this image is in the cache already
		//self::check_cache ( $cache_dir, $mime_type );
		
		// make sure that the src is gif/jpg/png
		if (! self::valid_src_mime_type ( $mime_type )) {
			$error = "Invalid src mime type: $mime_type";
			die ( $error );
		}
		
		// check to see if GD function exist
		if (! function_exists ( 'imagecreatetruecolor' )) {
			$error = "GD Library Error: imagecreatetruecolor does not exist";
			die ( $error );
		}
		
		if (strlen ( $src ) && file_exists ( $src )) {
			
			// open the existing image
			$image = self::open_image ( $mime_type, $src );
			if ($image === false) {
				die ( 'Unable to open image : ' . $src );
			}
			
			// Get original width and height
			$width = imagesx ( $image );
			$height = imagesy ( $image );
			
			// don't allow new width or height to be greater than the original
			if ($new_width > $width) {
				$new_width = $width;
			}
			if ($new_height > $height) {
				$new_height = $height;
			}
			
			// generate new w/h if not provided
			if ($new_width && ! $new_height) {
				$new_height = $height * ($new_width / $width);
			} elseif ($new_height && ! $new_width) {
				$new_width = $width * ($new_height / $height);
			} elseif (! $new_width && ! $new_height) {
				$new_width = $width;
				$new_height = $height;
			}
			
			// create a new true color image
			$canvas = imagecreatetruecolor ( $new_width, $new_height );
			
			if ($zoom_crop) {
				
				$src_x = $src_y = 0;
				$src_w = $width;
				$src_h = $height;
				
				$cmp_x = $width / $new_width;
				$cmp_y = $height / $new_height;
				
				// calculate x or y coordinate and width or height of source
				
				if ($cmp_x > $cmp_y) {
					
					$src_w = round ( ($width / $cmp_x * $cmp_y) );
					$src_x = round ( ($width - ($width / $cmp_x * $cmp_y)) / 2 );
				
				} elseif ($cmp_y > $cmp_x) {
					
					$src_h = round ( ($height / $cmp_y * $cmp_x) );
					$src_y = round ( ($height - ($height / $cmp_y * $cmp_x)) / 2 );
				
				}
				// change 0,0 to start from top 
				//imagecopyresampled ( $canvas, $image, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h );
			 imagecopyresampled ( $canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h );
			
			} else {
				
				// copy and resize part of an image with resampling
				imagecopyresampled ( $canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			
			}
			
			// output image to browser based on mime type
			self::show_image ( $mime_type, $canvas, $quality, $cache_dir );
			
			// remove image from memory
			imagedestroy ( $canvas );
		
		} else {
			
			if (strlen ( $src )) {
				echo $src . ' not found.';
			} else {
				echo 'no source specified.';
			}
		
		}
	
	}
	
	function show_image($mime_type, $image_resized, $quality, $cache_dir) {
		
		$cache_file_name = null;
		// check to see if we can write to the cache directory
//		$is_writable = 0;
//		$cache_file_name = $cache_dir . '/' . self::get_cache_file ();
//		
//		if (touch ( $cache_file_name )) {
//			// give 666 permissions so that the developer 
//			// can overwrite web server user
//			chmod ( $cache_file_name, 0666 );
//			$is_writable = 1;
//		} else {
//			$cache_file_name = NULL;
//			header ( 'Content-type: ' . $mime_type );
//		}
		
		if (stristr ( $mime_type, 'gif' )) {
			imagegif ( $image_resized );
		} elseif (stristr ( $mime_type, 'jpeg' )) {
			imagejpeg ( $image_resized, "", $quality );
		} elseif (stristr ( $mime_type, 'png' )) {
			imagepng ( $image_resized, "", ceil ( $quality / 10 ) );
		}
//		if ($is_writable) {
//			self::show_cache_file ( $cache_dir, $mime_type );
//		}
		header ( "Content-Type: ".$mime_type  );
		
		exit ();
	
	}
	
	function get_request($property, $default = 0) {
		
		if (isset ( $_REQUEST [$property] )) {
			return $_REQUEST [$property];
		} else {
			return $default;
		}
	
	}
	
	function open_image($mime_type, $src) {
		
		if (stristr ( $mime_type, 'gif' )) {
			$image = imagecreatefromgif ( $src );
		} elseif (stristr ( $mime_type, 'jpeg' )) {
			@ini_set ( 'gd.jpeg_ignore_warning', 1 );
			$image = imagecreatefromjpeg ( $src );
		} elseif (stristr ( $mime_type, 'png' )) {
			$image = imagecreatefrompng ( $src );
		}
		return $image;
	
	}
	
	function mime_type($file) {
		
		$os = strtolower ( php_uname () );
		$mime_type = '';
		
		// use PECL fileinfo to determine mime type
		if (function_exists ( 'finfo_open' )) {
			$finfo = finfo_open ( FILEINFO_MIME );
			$mime_type = finfo_file ( $finfo, $file );
			finfo_close ( $finfo );
		}
		
		// try to determine mime type by using unix file command
		// this should not be executed on windows
		if (! self::valid_src_mime_type ( $mime_type ) && ! (stristr ( 'windows', php_uname () ))) {
			if (preg_match ( "/freebsd|linux/", $os )) {
				$mime_type = trim ( @shell_exec ( 'file -bi $file' ) );
			}
		}
		
		// use file's extension to determine mime type
		if (! self::valid_src_mime_type ( $mime_type )) {
			//echo $file;
			$frags = explode ( ".", $file );
			//Zend_Debug::dump($frags);
			$ext = strtolower ( $frags [count ( $frags ) - 1] );
			//Zend_Debug::dump($ext);
			$types = array ('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif' );
			if (strlen ( $ext ) && strlen ( $types [$ext] )) {
				$mime_type = $types [$ext];
			}
			
			// if no extension provided, default to jpg
			if (! strlen ( $ext ) && ! valid_src_mime_type ( $mime_type )) {
				$mime_type = 'image/jpeg';
			}
		}
		return $mime_type;
	
	}
	
	function valid_src_mime_type($mime_type) {
		
		if (preg_match ( "/jpg|jpeg|gif|png/i", $mime_type )) {
			return 1;
		}
		return 0;
	
	}
	
	function check_cache($cache_dir, $mime_type) {
		
		// make sure cache dir exists
		if (! file_exists ( $cache_dir )) {
			// give 777 permissions so that developer can overwrite
			// files created by web server user
			mkdir ( $cache_dir );
			chmod ( $cache_dir, 0777 );
		}
		
		self::show_cache_file ( $cache_dir, $mime_type );
	
	}
	
	function show_cache_file($cache_dir, $mime_type) {
		
		$cache_file = $cache_dir . '/' . self::get_cache_file ();
		
		if (file_exists ( $cache_file )) {
			
			if (isset ( $_SERVER ["HTTP_IF_MODIFIED_SINCE"] )) {
				
				// check for updates
				$if_modified_since = preg_replace ( '/;.*$/', '', $_SERVER ["HTTP_IF_MODIFIED_SINCE"] );
				$gmdate_mod = gmdate ( 'D, d M Y H:i:s', filemtime ( $cache_file ) );
				
				if (strstr ( $gmdate_mod, 'GMT' )) {
					$gmdate_mod .= " GMT";
				}
				
				if ($if_modified_since == $gmdate_mod) {
					header ( "HTTP/1.1 304 Not Modified" );
					exit ();
				}
			
			}
			
			$fileSize = filesize ( $cache_file );
			
			// send headers then display image
			header ( "Content-Type: " . $mime_type );
			header ( "Accept-Ranges: bytes" );
			header ( "Last-Modified: " . gmdate ( 'D, d M Y H:i:s', filemtime ( $cache_file ) ) . " GMT" );
			header ( "Content-Length: " . $fileSize );
			header ( "Cache-Control: max-age=9999, must-revalidate" );
			header ( "Etag: " . md5 ( $fileSize . $gmdate_mod ) );
			header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + 9999 ) . "GMT" );
			readfile ( $cache_file );
			exit ();
		
		}
	
	}
	
	function get_cache_file() {
		
		global $quality;
		
		static $cache_file;
		if (! $cache_file) {
			$frags = split ( "\.", $_REQUEST ['src'] );
			$ext = strtolower ( $frags [count ( $frags ) - 1] );
			if (! self::valid_extension ( $ext )) {
				$ext = 'jpg';
			}
			$cachename = self::get_request ( 'src', 'timthumb' ) . self::get_request ( 'w', 100 ) . self::get_request ( 'h', 100 ) . self::get_request ( 'zc', 1 ) . self::get_request ( '9', 80 );
			$cache_file = md5 ( $cachename ) . '.' . $ext;
		}
		return $cache_file;
	
	}
	
	function valid_extension($ext) {
		
		if (preg_match ( "/jpg|jpeg|png|gif/i", $ext ))
			return 1;
		return 0;
	
	}
	
	function clean_source($src) {
		
		// remove http/ https/ ftp
		$src = preg_replace ( "/^((ht|f)tp(s|):\/\/)/i", "", $src );
		// remove domain name from the source url
		$src = str_replace ( $_SERVER ["HTTP_HOST"], "", $src );
		
		//$src = preg_replace( "/(?:^\/+|\.{2,}\/+?)/", "", $src );
		//$src = preg_replace( '/^\w+:\/\/[^\/]+/', '', $src );
		

		// don't allow users the ability to use '../' 
		// in order to gain access to files below document root
		

		// src should be specified relative to document root like:
		// src=images/img.jpg or src=/images/img.jpg
		// not like:
		// src=../images/img.jpg
		$src = preg_replace ( "/\.\.+\//", "", $src );
		
		return $src;
	
	}
	
	function get_document_root($src) {
		if (@file_exists ( $_SERVER ['DOCUMENT_ROOT'] . '/' . $src )) {
			return $_SERVER ['DOCUMENT_ROOT'];
		}
		// the relative paths below are useful if timthumb is moved outside of document root
		// specifically if installed in wordpress themes like mimbo pro:
		// /wp-content/themes/mimbopro/scripts/timthumb.php
		$paths = array ('..', '../..', '../../..', '../../../..' );
		foreach ( $paths as $path ) {
			if (@file_exists ( $path . '/' . $src )) {
				return $path;
			}
		}
	
	}
	


}
?>