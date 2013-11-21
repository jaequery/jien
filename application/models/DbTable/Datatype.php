<?php

class Application_Model_DbTable_Datatype extends My_Model
{
    protected $_name = 'Datatype';
    protected $_alias = 'datatype';

    public function delete($where = ''){

        $model = Jien::model("Datatype")->select('datatype')->where($where)->get()->row();
        $datatype = $model['datatype'];
        $res = parent::delete($where);

        // delete scaffolded files
        if($res){
            Jien::db()->query("DROP Table $datatype");
            unlink(getcwd() . '/../application/models/DbTable/' . $datatype . '.php');
            unlink(getcwd() . '/../application/views/default/admin/' . strtolower($datatype).'.phtml');
            unlink(getcwd() . '/../application/views/default/admin/' . strtolower(Jien_Plural::pluralize($datatype)) .'.phtml' );
        }

    }

}