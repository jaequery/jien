<?php

class AdminController extends My_Controller {

    public function init(){

        parent::init();

        // remember me if cookie exists
        if(!empty($_COOKIE['remember']) && empty($_SESSION['user'])){
            $user = Jien::model("User")->joinRole()->joinProvider()->get(Jien::decrypt($_COOKIE['remember']['user_id'],SALT))->row();
            $this->setUser($user);
        }

        if($this->hasRole("moderator")){

            // set it to remember me for 7 days
            if(empty($_COOKIE['remember'])){
                $expires =  time()+(86400 * 7);
                setcookie("remember[user_id]", Jien::encrypt($_SESSION['user']['user_id'], SALT), $expires);
                setcookie("remember[expires]", $expires, $expires);
            }

            $this->layout($this->params('layout', 'admin'));

        }else{
            $this->layout('admin-loggedout');
            $this->view('index');
        }

        // view vars
        $this->title(TITLE);
        $this->view->data = new Jien_Model_Factory(); // model output, contains row()/rows()/pager()

    }

    // when admin actions are called via ajax, it just returns json
    public function postDispatch() {
        
        // if want only json, add to get/post request: output=json
        switch($this->params('output')){
            case "json":
                if(!empty($this->view->data)){
                    $this->json($this->view->data->rows(), 200);
                }
            break;
        }
        
    }

    // all the save/delete requests goes through here and calls on the model's save/delete accordingly
    public function dataAction(){
        $data = $this->params();

        $model = $data['model'];
        $cmd = $this->params('cmd', 'save');

        try {
            switch($cmd){
                case "save":
                $id = Jien::model($model)->save($data);
                $primary = Jien::model($model)->getPrimary();
                $this->json(array($primary=>$id), 200, 'saved');
                break;

                case "delete":
                $id = $this->params('id');
                $affected = Jien::model($model)->delete($id);
                $this->json(array("affected"=>$affected), 200, 'deleted');
                break;

                case "get":
                $id = $this->params('id');
                $affected = Jien::model($model)->get($id);
                $this->json($affected->row(), 200, 'returned');
                break;

            }
        }catch(Exception $e){
            $this->json(array(), 405, $e->getMessage());
        }
    }

    public function indexAction(){
        if(!empty($_SESSION['user'])){
            $this->_forward('dashboard');
        }
    }

    public function dashboardAction(){
    }

    public function scaffolderAction(){
        if($this->params('model') != ''){
            $model = $this->params('model');
            $scaffold = new Jien_Scaffold();
            $scaffold->generateFromTable($model);

            exit;
        }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = $this->params();
            $data['tbl_name'] = strtolower($data['tbl_name']);
            $data['tbl_name'] = ucfirst($data['tbl_name']);
            $scaffold = new Jien_Scaffold();
            $res = $scaffold->createTable($data);
            if($res){
                $scaffold->generateFromTable($data['tbl_name']);
            }
            $this->json($res);
            exit;
        }
    }

    public function usersAction(){

        if(!empty($_GET['datatable'])){
            Jien::model($this->view->model)->orderBy("u.user_id DESC")->joinProvider()->joinRole()->filter($this->params())->get();
            exit;
        }

        $this->view->model = "User";
        $this->view->primary = Jien::model($this->view->model)->getPrimary();
        $this->view->data = Jien::model($this->view->model)->orderBy("u.user_id DESC")->joinProvider()->joinRole()->withPager($this->params('page', 1))->filter($this->params())->get();
    }

    public function userAction(){
        $this->view->model = "User";
        $this->view->roles = Jien::model('Role')->get();
        $id = $this->params('id');
        $this->view->authenticator = new Jien_Model_Factory;
        if($id){
            $this->view->data = Jien::model($this->view->model)->get($id);
            $this->view->authenticator = Jien::Model('Authenticator')->Where('authenticator_user_id = ' . $id)->get();
        }
        
    }

    public function categoriesAction(){
        $this->view->model = "Category";
        $this->view->primary = Jien::model($this->view->model)->getPrimary();
        $this->view->data = Jien::model($this->view->model)
        ->orderBy("category.category_id DESC")
        ->filter($this->params())
        ->withPager($this->params('page', 1))
        ->get();
    }

    public function categoryAction(){
        $this->view->model = "Category";
        $id = $this->params('id');
        if($id){
            $this->view->data = Jien::model($this->view->model)->get($id);
        }
    }

    public function datatableAction(){
        $model = ucfirst($this->params('model'));        
        $fields = $this->params('fields');
        $fields = explode("|", $fields);
        echo Jien::getDatatable($model, $fields);
        exit;
    }

    public function datatypesAction(){
    	$this->view->model = "Datatype";
    	$this->view->primary = Jien::model($this->view->model)->getPrimary();
    	$this->view->data = Jien::model($this->view->model)->orderBy("datatype.datatype_id DESC")->withPager($this->params('page', 1))->filter($this->params())->get();
    }

    public function datatypeAction(){
    	$this->view->model = "Datatype";
    	$id = $this->params('id');
    	if($id){
    		$this->view->data = Jien::model($this->view->model)->get($id);
    	}
    }

    public function rolesAction(){
    	$this->view->model = "Role";
    	$this->view->primary = Jien::model($this->view->model)->getPrimary();
    	$this->view->data = Jien::model($this->view->model)->orderBy("role.role_id DESC")->withPager($this->params('page', 1))->filter($this->params())->get();
    }

    public function roleAction(){
    	$this->view->model = "Role";
    	$id = $this->params('id');
    	if($id){
    		$this->view->data = Jien::model($this->view->model)->get($id);
    	}
    }


    // skeleton - dont remove this line, it's for scaffolding reason //



}
