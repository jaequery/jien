<?php

class Application_Model_DbTable_Datatype extends My_Model
{
    protected $_name = 'Datatype';
    protected $_alias = 'datatype';

    public function delete($where){
        parent::delete($where);
        
        unlink(getcwd() . '/../application/models/DbTable/' . $this->_name . '.php');
        unlink(getcwd() . '/../application/views/default/admin/' . strtolower($this->_name)).'.phtml';
        unlink(getcwd() . '/../application/views/default/admin/' . strtolower(Jien_Plural::pluralize($this->_name))).'.phtml';
    }
}