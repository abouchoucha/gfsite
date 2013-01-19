<?php

/**
 * Tags
 *  
 * @author chocheraz
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Tags extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tags';
	protected $_primary = 'id';
	
  public function findTagByName($name)
    {
        $where = $this->getAdapter()->quoteInto('tag = ?', $name);
        return $this->fetchRow($where);
    }
	
	
	public function findTags($limit = null) {
		
		$db = $this->getAdapter ();
		$sql = " SELECT * FROM tags GROUP BY tag ORDER BY count DESC ";
		if(!is_null($limit)){
			$sql .= " LIMIT  " .$limit;
		}
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ();
		return $rows;
	
	}
	
	public function updateTag($tag, $data) {
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "tag = ?", $tag );
		return $this->update ( $data, $where );
	}

}
