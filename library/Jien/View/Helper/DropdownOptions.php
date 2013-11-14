<?php
class Jien_View_Helper_DropdownOptions {

	/**
	 * Creates dropdown options
	 *
	 * @param array $options
	 * @param string $selected
	 * @param string $default
	 * @return unknown
	 */
	public function dropdownOptions($options = array(), $selected = '', $default = ''){
		$html = '';
		if($options){
			foreach($options AS $value=>$label){
				$sel = '';
				if(!$value) $value = '';

				if($selected != ''){
					if($value == $selected){
						$sel = 'selected';
					}
				}else{
					if($value == $default){
						$sel = 'selected';
					}
				}
				$html .= "<option {$sel} value='{$value}'>{$label}</option>";
			}
		}
		return $html;
	}

}