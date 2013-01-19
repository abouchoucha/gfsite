<?php

class PaginateHelper {
	
	private $from;
	private $to;
	private $totalRows;
	private $fromPage;
	private $toPage;
	private $previouspage;
	private $nextpage;
	private $numPages;
	private $numPage;
	
	/**
	 * @return unknown
	 */
	public function getNumPage () {
		return $this->numPage ;
	}
	
	/**
	 * @param unknown_type $numPage
	 */
	public function setNumPage ( $numPage ) {
		$this->numPage = $numPage ;
	}
	function __construct () {
		
	}
	/**
	 * @return unknown
	 */
	public function getFrom () {
		return $this->from ;
	}
	
	/**
	 * @return unknown
	 */
	public function getFromPage () {
		return $this->fromPage ;
	}
	
	/**
	 * @return unknown
	 */
	public function getNextpage () {
		return $this->nextpage ;
	}
	
	/**
	 * @return unknown
	 */
	public function getNumPages () {
		return $this->numPages ;
	}
	
	/**
	 * @return unknown
	 */
	public function getPaginationUrl () {
		return $this->paginationUrl ;
	}
	
	/**
	 * @return unknown
	 */
	public function getPreviouspage () {
		return $this->previouspage ;
	}
	
	/**
	 * @return unknown
	 */
	public function getTo () {
		return $this->to ;
	}
	
	/**
	 * @return unknown
	 */
	public function getToPage () {
		return $this->toPage ;
	}
	
	/**
	 * @return unknown
	 */
	public function getTotalRows () {
		return $this->totalRows ;
	}
	
	/**
	 * @param unknown_type $from
	 */
	public function setFrom ( $from ) {
		$this->from = $from ;
	}
	
	/**
	 * @param unknown_type $fromPage
	 */
	public function setFromPage ( $fromPage ) {
		$this->fromPage = $fromPage ;
	}
	
	/**
	 * @param unknown_type $nextpage
	 */
	public function setNextpage ( $nextpage ) {
		$this->nextpage = $nextpage ;
	}
	
	/**
	 * @param unknown_type $numPages
	 */
	public function setNumPages ( $numPages ) {
		$this->numPages = $numPages ;
	}
	
	/**
	 * @param unknown_type $paginationUrl
	 */
	public function setPaginationUrl ( $paginationUrl ) {
		$this->paginationUrl = $paginationUrl ;
	}
	
	/**
	 * @param unknown_type $previouspage
	 */
	public function setPreviouspage ( $previouspage ) {
		$this->previouspage = $previouspage ;
	}
	
	/**
	 * @param unknown_type $to
	 */
	public function setTo ( $to ) {
		$this->to = $to ;
	}
	
	/**
	 * @param unknown_type $toPage
	 */
	public function setToPage ( $toPage ) {
		$this->toPage = $toPage ;
	}
	
	/**
	 * @param unknown_type $totalRows
	 */
	public function setTotalRows ( $totalRows ) {
		$this->totalRows = $totalRows ;
	}
	private $paginationUrl;
	
	
}

?>
