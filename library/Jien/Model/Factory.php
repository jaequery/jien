<?php

class Jien_Model_Factory {

	protected $_data;
	protected $_pager;
	protected $_select;

	public function __construct($data = array(), $select = ''){
		if(!empty($data)){
			$this->setData($data);
		}
		if(!empty($select)){
			$this->_select = $select;
		}
	}

	//
	// getters
	//

	// retrieve single row (can pass optional field param to get just the field)
	public function row($field = '', $default = ''){

		if(empty($this->_data)){
			if($default != ''){
				return $default;
			}
			return false;
		}

		if($field != ''){
			$row = '';
			if(!empty($this->_data[0][$field])){
				$row = $this->_data[0][$field];
			}else if($default != ''){
				$row = $default;
			}
		}else{
			$row = $this->_data[0];
		}
		return $row;
	}

	// retrieve multiple rows
	public function rows(){
		$rows = array();
		if(!empty($this->_data)){
			$rows = $this->_data;
		}
		return $rows;
	}

	public function pager(){
		return $this->_pager;
	}

	public function count(){
		return count($this->_data);
	}

	public function sql(){
		return $this->_select->__toString();
	}


	//
	// setters
	//

	public function setData($data){
		$this->_data = $data;
	}

	public function setPager($pager){
		$this->_pager = $pager;
	}

}