<?php
class Search extends Zend_Db_Table_Abstract 
{
	protected $_primary = "searchterm" ;
	protected $_name = 'searchterms' ;
	function init () 
	{	
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;	
	}
	
	public function GetSearchTermCount ( $search ) 
	{
		$searchTerm = strtolower($search);
		$db = $this->getAdapter () ;
		$sql = " select searchTerm, Count " ;
		$sql .= " from searchterms " ;
		$sql .= " where searchTerm = '$searchTerm'" ;
		$result = $db->fetchRow ( $sql ) ;
		return $result ;
	}
	
	public function UpdateSearchTermCount ( $search ) 
	{
		$searchTerm = strtolower($search);
		$db = $this->getAdapter () ;
		$sql = "searchTerm = '$searchTerm'" ;
		$data = array('count' => new Zend_Db_Expr('`count`+1'));  
		$result = $db->update ("searchterms",$data, $sql ) ;
		return $result ;
	}
	
	public function AddSearchTerm ( $search ) 
	{
		$searchTerm = strtolower($search);
		$db = $this->getAdapter () ;
		$data = array("searchTerm" => $searchTerm, "count" => "1");  
		$result = $db->insert ("searchterms", $data) ;
	}
	public function AlterSearchTermCount($search)
	{
		if ($this->GetSearchTermCount($search) != False)
		{
			$this->UpdateSearchTermCount($search);
		}
		else
		{
			$this->AddSearchTerm($search);	
		}
	}
	public function GetTopSearchTerms()
	{	
		$db = $this->getAdapter () ;
		$sql = " Select searchTerm, count " ;
		$sql .= " from searchterms " ;
		$sql .= " order by count desc limit 0,10" ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}	
}
?>
