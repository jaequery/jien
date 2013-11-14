<?php
class Jien_View_Helper_Editable {

	public $view;

	public function setView(Zend_View_Interface $view){
        $this->view = $view;
    }

	public function editable($name, $type = 'text', $default = ''){
		$content = $default;
		$data = Jien::model("Editable")->where("name = '{$name}'")->get()->row();
		if(!empty($data)){
			$content = $data['content'];
		}
		$html = $content;
		return $html;
	}

}
