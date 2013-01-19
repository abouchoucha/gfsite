<?php
require_once 'PaginateHelper.php';

class Pagination {
	
	private $_totalRecords ;
	private $_recordPerPage ;
	private $_totalPages ;
	
	function __construct ( $totalRecords , $recordPerPage ) {
		$this->_totalRecords = $totalRecords ;
		$this->_recordPerPage = $recordPerPage ;
	}
	
	
	function doPagination( $currentPage){
		
		$helper = new PaginateHelper();
		
		$numPages = $this->_totalRecords / $this->_recordPerPage ;
		$numPages = ceil ( $numPages ) ;
		$to = null ;
		if ($numPages == $currentPage) {
			$temp = $this->_totalRecords - ($currentPage - 1) * $this->_recordPerPage ;
			//echo $temp ;
			if ($temp < $this->_recordPerPage) {
				$to = $this->_totalRecords ;
				$from = ($currentPage - 1) * $this->_recordPerPage ;
			} else {
				$to = $currentPage * $this->_recordPerPage ;
				$from = ($currentPage - 1) * $this->_recordPerPage ;
			}
		
		} else {
			if ($this->_totalRecords < $this->_recordPerPage) {
				$to = $this->_totalRecords ;
				$from = ($to - $this->_totalRecords) ;
			} else {
				$to = $currentPage * $this->_recordPerPage ;
				$from = ($to - $this->_recordPerPage) ;
			}
		}
		
		if ($currentPage <= 6) {
				$arrayBorderPages [ 0 ] = 1 ;
				$arrayBorderPages [ 1 ] = 11 ;
				if ($arrayBorderPages [ 1 ] > $numPages) {
					$arrayBorderPages [ 1 ] = $numPages ;
				}
			
		} else {
			$arrayBorderPages [ 0 ] = $currentPage - 5 ;
			$arrayBorderPages [ 1 ] = $currentPage + 5 ;
			if ($arrayBorderPages [ 1 ] > $numPages) {
				$arrayBorderPages [ 1 ] = $numPages ;
			}
		}
		$helper->setNumPages($numPages);
		$helper->setFrom($from + 1);
		$helper->setTo($to);
		$helper->setFromPage($arrayBorderPages[0]);
		$helper->setToPage($arrayBorderPages[1]);
		//
		$nextpage = $currentPage + 1 ;
		$previouspage = $currentPage - 1 ;
		$helper->setNextpage($nextpage);
		$helper->setPreviouspage($previouspage);
		$helper->setNumPage($currentPage);
		
		return $helper;
	}
}

?>
