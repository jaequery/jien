<?php

class Application_Model_DbTable_Role extends My_Model
{
    protected $_name = 'Role';
    protected $_alias = 'role';
 	protected $_soft_delete = false;

    public function save($data, $where=''){

        $below = Jien::model('Role')->Where('mptt_left > ' . $data['mptt_left'])->get()->rows();
        $above = Jien::model('Role')->Where('mptt_left < ' . $data['mptt_left'])->andWhere('mptt_right > ' . $data['mptt_right'])->get()->rows();
        if(!empty($below)){
            foreach($below as $update){
                unset($update['created']);
                unset($update['updated']);
                unset($update['deleted']);
                unset($update['active']);
                $update['mptt_left'] += 2;
                $update['mptt_right'] += 2;
                parent::save($update,$where);
            }
        }
        if(!empty($above)){
            foreach($above as $update){
                unset($update['created']);
                unset($update['updated']);
                unset($update['deleted']);
                unset($update['active']);
                $update['mptt_right'] += 2;
                parent::save($update,$where);
            }
        }

        $parent = array(
            'role_id' => $data['id'],
            'mptt_right' => $data['mptt_right'] + 2
        );
        $child = array(
            'role' => $data['childName'],
            'mptt_left' => $data['mptt_left'] + 1,
            'mptt_right' => $data['mptt_left'] + 2
        );

        $id = parent::save($parent,$where);
        $id = parent::save($child,$where);

        return $id;
    }
}