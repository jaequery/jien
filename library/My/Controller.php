<?php

class My_Controller extends Jien_Controller {

    public function init(){
        parent::init();

        // if it doesn't exist, it will use the default
        $theme = THEME;
        $this->view->addScriptPath(APPLICATION_PATH.'/views/'.$theme.'/');
        $this->layout('site');

        // set title
        $this->view->title = TITLE;

    }

    public function categoriesAction(){
        $type = $this->params('type');
        $category_id = $this->params('category_id');
        $categories = Jien::model("Category")->where("category.type = '{$type}'")->orderBy("category.path ASC")->get()->rows();
        $html = "<option value=''></option>";
        foreach($categories AS $record){
            $c = strlen($record['path']);
            $x = str_repeat('-', $c - 1);
            $sel = '';
            if($category_id == $record['category_id']){
                $sel = 'selected';
            }
            $html .= "<option value='{$record['category_id']}' {$sel}>{$x} {$record['category']}</option>";
        }
        $res = array("html"=>$html);
        $this->json($res, 200);
    }

    protected function authenticate($username, $password, $role_id = '', $auth_code = ''){

        $user_id = Jien::model('User')->Where('username = "' . $username . '"')->get()->row('user_id');
        $auth_check = Jien::model('Authenticator')->Where('authenticator_user_id = ' . $user_id)->get()->row();

        if(!empty($auth_check) && empty($auth_code)){
            return false;
        }else if(!empty($auth_check) && !empty($auth_code)){
            $ga = new GoogleAuthenticator();
            if($ga->checkCode($auth_check['authenticator_secret'], $auth_code)){
                $adapter = $this->_getAuthAdapter($role_id);
                $adapter->setIdentity($username);
                $adapter->setCredential($password);

                $result = $this->auth->authenticate($adapter);
                if ($result->isValid()) {
                    $user = $adapter->getResultRowObject();
                    $this->setUser($user);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else if(empty($auth_check)){
            $adapter = $this->_getAuthAdapter($role_id);
            $adapter->setIdentity($username);
            $adapter->setCredential($password);

            $result = $this->auth->authenticate($adapter);
            if ($result->isValid()) {
                $user = $adapter->getResultRowObject();
                $this->setUser($user);
                return true;
            }
        }

        return false;
    }

    protected function _getAuthAdapter($role_id = '') {
        $authAdapter = new Jien_Auth_Adapter_DbTable(Jien::db(), "User", "username", "password", "");
        $select = $authAdapter->getDbSelect();
        if($role_id != ''){
            $select->where("role_id >= {$role_id} AND active=1");
        }else{
            $select->where('active=1');
        }
        return $authAdapter;
    }

    /**
     * Get My_Auth_Adapter_Facebook adapter
     *
     * @return My_Auth_Adapter_Facebook
     */
    protected function _getFacebookAdapter() {
        return new My_Auth_Adapter_Facebook(FACEBOOK_APPID, FACEBOOK_SECRET, FACEBOOK_REDIRECTURI, FACEBOOK_SCOPE);
    }

    /**
     * Get My_Auth_Adapter_Oauth_Twitter adapter
     *
     * @return My_Auth_Adapter_Oauth_Twitter
     */
    protected function _getTwitterAdapter() {
        return new My_Auth_Adapter_Oauth_Twitter(array(), TWITTER_APPID, TWITTER_SECRET, TWITTER_REDIRECTURI);
    }

}
