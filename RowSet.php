<?php
class Model_DbTable_HandlerSocket_RowSet implements SeekableIterator, Countable
{
	private $_position = 0;
	private $_data = array();
	private $_count = 0;
	private $_columnsMapping  = array();
	
	public function __construct($columns, $data)
	{
		$this->_data = $data;
		$this->_columnsMapping = $columns;
		$this->_count = count($data);
	}
	
	/**
	 * 輸出Array
	 *
	 * @return array
	 * @author eddie
	 * @version 0.06 2012-07-13
	 */
	public function toArray()
	{
		return $this->_data;
	}
	
	/**
	 * 取得欄位Array
	 *
	 * @return array
	 * @author eddie
	 * @version 0.06 2012-07-13
	 */
	public function getColumns()
	{
		return $this->_columns;
	}
	
	/**
	 * 當前row的array index
	 *
	 * @return int
	 * @author eddie
	 * @version 0.06 2012-07-13
	 */
	public function currentRowPosition()
	{
		return $this->_position;
	}
	
	/**
	 * 取得當前row, 並將指標推下一筆
	 *
	 * @return Model_DbTable_HandlerSocket_Row || false
	 * @author eddie
	 * @version 0.06 2012-07-13
	 */
	private function _getRow($rowIndex)
	{
		if (!isset($this->_data[$rowIndex])) {
			return false;
		}
		$data = $this->_data[$rowIndex];
		return new Model_DbTable_HandlerSocket_Row($this->_columnsMapping, $data);
	}
	
	/**
	 * Rewind 返回position至第一個Row
	 * Required by interface Iterator.
	 *
	 * @author eddie
	 * @return Zend_Db_Table_Rowset_Abstract Fluent interface.
	 * @version 0.06 2012-07-13
	 */
	public function rewind()
	{
		$this->_position = 0;
		return $this;
	}
	
	/**
	 * 返回集合RowSet裡的當前元素Row
	 * Required by interface Iterator.
	 *
	 * @author eddie
	 * @return Model_DbTable_HandlerSocket_Row 集合RowSet裡的當前元素Row
	 * @version 0.06 2012-07-13
	 */
	public function current()
	{
		if ($this->valid() === false) {
			return null;
		}
		// return the row object
		return $this->_getRow($this->_position);
	}
	
	/**
	 * 返回當前 row 的 rowset key
	 * Required by interface Iterator.
	 *
	 * @author eddie
	 * @return int
	 * @version 0.06 2012-07-13
	 */
	public function key()
	{
		return $this->_position;
	}
	
	/**
	 * 移至下一個row
	 * Required by interface Iterator.
	 * 
	 * @author eddie
	 * @return void
	 * @version 0.06 2012-07-13
	 */
	public function next()
	{
		++$this->_position;
	}
	
	/**
	 * 檢查是否超超出 rowset data index
	 * Required by interface Iterator.
	 * 
	 * @author eddie
	 * @return bool 如果超出回傳False
	 * @version 0.06 2012-07-13 
	 */
	public function valid()
	{
		return $this->_position >= 0 && $this->_position < $this->_count;
	}
	
	/**
	 * 取代直接取用 $_position
	 *
	 * @param int $position Row array的index
	 * @author eddie
	 * @return Model_DbTable_HandlerSocket_RowSet
	 * @version 0.06 2012-07-13
	 */
	public function seek($position)
	{
		$position = (int) $position;
		if ($position < 0 || $position >= $this->_count) {
			throw new Exception("Illegal index {$position}");
		}
		$this->_position = $position;
		return $this;
	}
	
	/**
	 * 此RowSet的資料筆數
	 *
	 * @author eddie
	 * @return void
	 * @version 0.06 2012-07-13
	 */
	public function count()
	{
		return count($this->_count);
	}
}