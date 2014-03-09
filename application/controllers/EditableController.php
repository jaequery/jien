<?php

class EditableController extends My_Controller {

    public function init(){
        parent::init();

        if(!$this->hasRole("moderator")){
    		$this->json(array(), 403);
        }
    }

    public function saveAction(){
    	$post = $_POST;

    	$data = Jien::model("Editable")->where("name = '{$post['name']}' ")->get()->row();

    	// update
    	if(!empty($data)){
    		Jien::model("Editable")->update($post, "name = '{$post['name']}'");

    	// add new
    	}else{
    		$payload = array(
    			"name" => $post['name'],
    			"content" => $post['content'],
    			"type" => $post['type'],
    		);
    		Jien::model("Editable")->save($payload);
    	}

		$this->json($this->params(), 200);
    }

}
