<?php

class ErrorController extends My_Controller
{

    public function init()
    {

    	parent::init();

    	/* Initialize action controller here */
        if(substr($_SERVER['REQUEST_URI'], 0, 6) != '/error'){

        	$errors = $this->_getParam('error_handler');

        	$this->view->error = $errors->exception;

        	//Jien::debug($exception->getMessage());
        	//Jien::errorLog($exception->getMessage());

//        	header("Location: /error");
//        	exit;
        }

    }

    public function errorAction(){
    }

}