<?php
class Jien_View_Helper_Dropdown {

    /**
     * Creates dropdown options
     *
     * @param string $key
     * @param string $label
     * @param array $data
     * @param string $selected
     * @param string $default
     * @return unknown
     */
    public function dropdown($key, $label, $data=array(), $selected = '', $default = ''){
        $html = '';
        if(!empty($data)){
            $temp = $selected;
            foreach($data as $option){
                $class = '';
                if (!is_array($selected)){
                    if($option[$key] == $selected){
                        $class = " selected";
                    }
                } else {
                    foreach ($selected as $s) {
                        if($option[$key] == $s){
                            $class = " selected";
                        }
                    }
                }
                $html .= "<option value='{$option[$key]}'$class>{$option[$label]}</option>";
            }
        }
        return $html;
    }
}