<?php
class Jien_View_Helper_FilePickerMultiple {

	/**
	 * Creates filepicker.io
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $selected
	 * @param string $default
	 * @return unknown
	 */
	public function FilePickerMultiple($name, $files=array(), $type="image", $default="http://cdn4.iconfinder.com/data/icons/simplicio/48x48/file_add.png"){
		$html = '';
		$input_type = 'text';
		foreach($files AS $key=>$value){
			if($type == "image"){
				if($selected != ""){
					$html .= "<img class='filepicker_multiple' src='{$selected}/convert?w=100&h=100&fit=scale'>";
				}else{
					$html .= "<img class='filepicker_multiple' src='{$default}'>";
				}
				$input_type = 'hidden';

			}
			$html .= "<input type='{$input_type}' name='{$name}' value='{$selected}'>&nbsp;";
		}
		$html .= '<button class="btn btn-mini trig_product_img_add" type="button"><i class="icon-plus"></i></button>';
		return $html;
	}

}