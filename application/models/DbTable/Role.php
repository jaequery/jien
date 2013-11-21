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
                parent::save($update);
            }
        }
        if(!empty($above)){
            foreach($above as $update){
                unset($update['created']);
                unset($update['updated']);
                unset($update['deleted']);
                unset($update['active']);
                $update['mptt_right'] += 2;
                parent::save($update);
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

    public function delete($data){
        $delete = Jien::model('Role')
            ->Where("mptt_left >= {$data['mptt_left']}")
            ->andWhere("mptt_right <= {$data['mptt_right']}")
            ->get()
            ->rows();

        foreach($delete as $item){
            $id = parent::delete($item['role_id']);
        }

        $left = Jien::model('Role')
            ->Where("mptt_left > {$data['mptt_left']}")
            ->get()
            ->rows();

        foreach($left as $update){
            unset($update['created']);
            unset($update['updated']);
            unset($update['deleted']);
            unset($update['active']);
            $update['mptt_left'] = $update['mptt_left'] - (($data['mptt_right'] - $data['mptt_left']) + 1 );
            parent::save($update);
        }

        $right = Jien::model('Role')
            ->Where("mptt_right > {$data['mptt_right']}")
            ->get()
            ->rows();

        foreach($right as $update){
            unset($update['created']);
            unset($update['updated']);
            unset($update['deleted']);
            unset($update['active']);
            $update['mptt_right'] = $update['mptt_right'] - (($data['mptt_right'] - $data['mptt_left']) + 1 );
            parent::save($update);
        }

        return $id;
    }
}