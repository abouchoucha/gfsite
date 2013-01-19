<?php 
class UserLanguage extends Zend_Db_Table_Abstract
  {

      protected $_primary = 'user_id';
	  protected $_name   = 'userlanguage';
      
      public function findLanguageFirstReg($userId)
      {
          $db = $this->getAdapter();
          $where = $db->quoteInto("user_id = ?", $userId);
          return $this->fetchRow($where);
      }
      
      public function findLanguagesSpokenPerUser($userId)
      {
	      $db = $this->getAdapter();
	      $sql = " select l.language_name, l.language_id ";
		  $sql .= " from userlanguage ul, language l ";
		  $sql .= " where user_id = ". $userId;
		  $sql .= " and ul.language_id = l.language_id ";
      
      	  $result = $db->query ( $sql ) ;
		  $row = $result->fetchAll () ;
		  return $row ;
      }
      
      public function deleteUserLanguage($userId){
      	
      	 $db = $this->getAdapter ();
		 $db->delete( 'userlanguage' , 'user_id='.$userId);
      }
      	
      
  }
?>
