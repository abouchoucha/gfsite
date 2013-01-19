<?php 
class Language extends Zend_Db_Table_Abstract
  {

      protected $_primary = "language_id";
	  protected $_name = 'language';

      public function selectLanguagesOrdered()
      {
            $db = $this->getAdapter ();
			$sql = " select language_id ,language_name ";
			$sql .= " from language ";
			$sql .= " order by language_name ";
			//echo $sql;
			$result = $db->query ( $sql );
			$row = $result->fetchAll ();
			return $row;
      }
  }
?>
