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
            $scaffold = new Jien_Scaffold();
            $scaffold->delete($datatype);
        }

        return $res;
    }

}