<?php
class Jien_View_Helper_Pager {

	public $view;

	public function setView(Zend_View_Interface $view){
        $this->view = $view;
    }

	public function pager($pager, $file){
		return $this->view->paginationControl($pager, 'Sliding', $file, $_GET);
	}

}