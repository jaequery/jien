<?php

class Application_Model_DbTable_User extends My_Model
{
    protected $_name = 'User';
    protected $_alias = 'u';
    protected $_soft_delete = true;

    public function save($data, $where = ''){
 		// hash password with bcrypt
     if(!empty($data['password'])){
        $hash = new Jien_Auth_Hash(8, false);
        $data['password'] = $hash->hashPassword($data['password']);
    }

 		// if username is empty, use email as the username
    if(empty($data['email']) && !empty($data['username'])){
        $data['email'] = $data['username'];
    }

    $id = parent::save($data, $where);

    // 2 factor auth
    if(!empty($data['authenticator'])){
        $authenticator_check = Jien::model('Authenticator')->Where('authenticator_user_id = ' . $id)->get()->row();
        if(empty($authenticator_check)){
            $auth_data = array(
                "authenticator_user_id" => $id,
                "authenticator_secret" => $data['auth_secret'],
                "authenticator_sms" => $data['auth_sms']
                );

        }else{
            $auth_data = array(
                "authenticator_user_id" => $id,
                "authenticator_sms" => $data['auth_sms']
                );
        }
        
        try {
            Jien::model('Authenticator')->save($auth_data);
        }catch(Exception $e){
            error_log(var_export($auth_data,true));    
            error_log($e->getMessage());    
        }
    }else{
        $authenticator_check = Jien::model('Authenticator')->Where('authenticator_user_id = ' . $id)->get()->row();
        if(!empty($authenticator_check)){
            Jien::model('Authenticator')->delete($authenticator_check['authenticator_id']);
        }
    }
    return $id;
}

public function joinRole(){
 $this->leftJoin("Role role", "u.role_id = role.role_id", "role.role");
 return $this;
}

public function joinProvider(){
 $this->leftJoin("Provider provider", "u.provider_id = provider.provider_id", "provider.provider");
 return $this;
}

}