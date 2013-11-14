<?php

class Qr{

    /**
     * generates qr code url
     *
     * @param string $model
     * @param boolean $new
     * @return object
     */
    static function url($qr_id){
        $base = base_convert($qr_id, 10, 36);
        
        $qr_url = 'http://' . SITE_URL . '/qr/' . $base;
        
        return $qr_url;
    }
}