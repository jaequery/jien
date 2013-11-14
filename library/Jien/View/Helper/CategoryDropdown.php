<?php
class Jien_View_Helper_CategoryDropdown {

	public function categoryDropdown($type, $category_id = ''){
		$categories = Jien::model("Category")->where("category.type = '{$type}'")->orderBy("category.path ASC")->get()->rows();
		$html = "<option value=''></option>";
		foreach($categories AS $record){
			$c = substr_count($record['path'], ',');
			$x = str_repeat('<span class="child-arrow">&ndash; </span>', $c);
			$sel = '';
			if($category_id == $record['category_id']){
				$sel = 'selected';
			}
			$html .= "<option value='{$record['category_id']}' {$sel}>{$x} {$record['category']}</option>";
		}
		return $html;
	}

}