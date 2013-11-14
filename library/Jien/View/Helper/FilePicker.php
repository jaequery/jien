<?php
class Jien_View_Helper_FilePicker {

	/**
	 * Creates filepicker.io
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $selected
	 * @param string $default
	 * @return unknown
	 */
	public function FilePicker($name, $selected="", $type="image", $default="http://cdn4.iconfinder.com/data/icons/simplicio/48x48/file_add.png"){
		$html = '';
		$input_type = 'text';
		if($type == "image"){
			if($selected != ""){
				$html .= "<img class='filepicker' src='{$selected}/convert?w=100&h=100&fit=scale'>";
			}else{
				$html .= "<img class='filepicker' src='{$default}'>";
			}
			$input_type = 'hidden';
		}

		$html .= "<input class='filepicker_hidden' type='{$input_type}' name='{$name}' value='{$selected}'>";
		return $html;
	}

}