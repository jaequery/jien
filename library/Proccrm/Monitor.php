<?php

class Proccrm_Monitor {

    function monitor($url, $mid = array(), $alt_url = null){

        //original url
        $url = trim($url);
        $x = explode(" ", $url);
        $url = $x[0];
        if(!strstr($url, 'http')){
            $url = 'http://' . $url;
        }

        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        if(!strstr($url, 'www.')){
            $url = 'www.' . $url;
        }
        $url = 'http://' . $url;

        //alt url
        if($alt_url != null){
            $alt_url = trim($alt_url);
            $x = explode(" ", $alt_url);
            $alt_url = $x[0];
            if(!strstr($alt_url, 'http')){
                $alt_url = 'http://' . $alt_url;
            }

            $alt_url = str_replace("https://", "", $alt_url);
            $alt_url = str_replace("http://", "", $alt_url);
            if(!strstr($alt_url, 'www.')){
                $alt_url = 'www.' . $alt_url;
            }
            $alt_url = 'http://' . $alt_url;
        }

        $date = date("Y-m-d h:i:s a", time());

        // add new or grab existing
        try {
            // insert new to db
            $data = array(
                'url' => $url,
                'active' => 1,
                );

            if(!empty($mid['account_name'])){
                $data['account_name'] = $mid['account_name'];
                $data['corp_name'] = $mid['corp_name'];
            }

            $site_id = Jien::model("Site")->save($data);

        } catch(Exception $e){

            // get existing
            $site = Jien::model("Site")->where("url = '{$url}'")->get()->row();

        }

        // set site id
        if(empty($site)) $site = Jien::model("Site")->get($site_id)->row();
        $site_id = $site['site_id'];

        // skip inactive sites
        if($site['site_status'] == 'inactive'){
            $this->info("site is inactive, skipping ...");
            return false;
        }

        // get site's http response
        $response = $this->grab($url);

        // get alt site's http response
        $response_alt = $this->grab($alt_url);



        // try https
        if(empty($response)){
            $this->info('- http not found, trying https');
            $url = str_replace("http://", "https://", $url);
            $response = $this->grab($url);
        }

        if(empty($response_alt)){
            $this->info('- http not found, trying https');
            $alt_url = str_replace("http://", "https://", $alt_url);
            $response_alt = $this->grab($alt_url);
        }


        // try https without www
        if(empty($response)){
            $this->info('- trying https without www');
            $url = str_replace("www.", "", $url);
            $response = $this->grab($url);
        }

        if(empty($response_alt)){
            $this->info('- trying https without www');
            $alt_url = str_replace("www.", "", $alt_url);
            $response_alt = $this->grab($alt_url);
        }

        // try http without www
        if(empty($response)){
            $this->info('- trying http without www');
            $url = str_replace("https://", "http://", $url);
            $response = $this->grab($url);
        }

        if(empty($response_alt)){
            $this->info('- trying http without www');
            $alt_url = str_replace("https://", "http://", $alt_url);
            $response_alt = $this->grab($alt_url);
        }

        $iframe_flag = strpos($response,"<iframe");

        if(!empty($iframe_flag)){
            $iframe_detected = true;
            $temp['site_id'] = $site_id;
            $temp['iframe'] = true;
            Jien::model("Site")->save($temp);
        }

        // check to see if it timed out
        if(!empty($response)){

            // add new to db
            if(empty($site['current_source'])){

                $this->info("- inserting ...");

                if($alt_url == null){
                    $orig_screen = $this->screenshot($url);
                }else{
                    $orig_screen = $this->screenshot($alt_url);
                }

                $site_data = array(
                    'site' => $site_id,
                    'code' => $response,
                    'iframe_code' => $response_alt,
                    'screen' => $orig_screen
                );

                $siteData_id = Jien::model('SiteData')->save($site_data);

                $data = array(
                    'site_id' => $site_id,
                    'current_source' => $siteData_id,
                    'checked' => $date,
                    'site_status' => 'new',
                    'match_rate' => 100,
                    );
                $filesize = $this->getfilesize($orig_screen);
                if($filesize < 3000){
                    $data['orig_screen'] = '';
                    $data['site_status'] = 'down';
                    $data['match_rate'] = 0;
                    $data['downed'] = $date;
                }
                $site_id = Jien::model("Site")->save($data);
                $this->info("- done!");

            // update db and perform comparison check with original
            }else if(!empty($site['current_source']) && empty($site['new_source'])){

                $current_code = Jien::model('SiteData')->get($site['current_source'])->row();
                // get diff
                $this->info("- comparing ...");
                $orig_code = $current_code['code'];
                $orig_code_alt = $current_code['iframe_code'];
                $new_code = $response;
                $new_code_alt = $response_alt;
                similar_text($orig_code, $new_code, $match_rate);
                //$match_rate = 70;
                $this->info("- done!");
                $this->info("- match rate: $match_rate");
                similar_text($orig_code_alt, $new_code_alt, $match_rate_alt);
                $this->info("- iframe match rate: $match_rate_alt");

                if(!empty($response_alt)){
                    if($match_rate < MATCH_RATE || $match_rate_alt < MATCH_RATE){
                        $changed = $date;
                        $site_status = 'changed';
                    }else{
                        $changed = null;
                        $site_status = 'active';
                    }
                }else{
                    if($match_rate < MATCH_RATE){
                        $changed = $date;
                        $site_status = 'changed';
                    }else{
                        $changed = null;
                        $site_status = 'active';
                    }
                }

                // take screenshot

                if($alt_url == null){
                    $new_screen = $this->screenshot($url, '_new');
                }else{
                    $new_screen = $this->screenshot($alt_url, '_new');
                }

                // take diff
                $diff = Jien::htmlDiff(htmlentities($current_code['code']), htmlentities($new_code));
                $diff_alt = Jien::htmlDiff(htmlentities($current_code['iframe_code']), htmlentities($new_code_alt));

                $site_data = array(
                    'site' => $site_id,
                    'code' => $response,
                    'iframe_code' => $response_alt,
                    'screen' => $new_screen
                );

                $siteData_id = Jien::model('SiteData')->save($site_data);

                $data = array(
                    'site_id' => $site_id,
                    'new_source' => $siteData_id,
                    'match_rate' => $match_rate,
                    'match_rate_alt' => $match_rate_alt,
                    'checked' => $date,
                    'changed' => $changed,
                    'diff' => $diff,
                    'diff_alt' => $diff_alt,
                    'site_status' => $site_status,
                    );

                $filesize = $this->getfilesize($new_screen);
                if($filesize < 3000){
                    $data['new_screen'] = '';
                    $data['site_status'] = 'down';
                    $data['match_rate'] = 0;
                    $data['downed'] = $date;
                }

                Jien::model("Site")->save($data);

            }else{
                //3rd time scanned.
                $current_code = Jien::model('SiteData')->get($site['current_source'])->row();
                // get diff
                $this->info("- comparing ...");
                $orig_code = $current_code['code'];
                $orig_code_alt = $current_code['iframe_code'];
                $new_code = $response;
                $new_code_alt = $response_alt;
                similar_text($orig_code, $new_code, $match_rate);
                //$match_rate = 70;
                $this->info("- done!");
                $this->info("- match rate: $match_rate");
                similar_text($orig_code_alt, $new_code_alt, $match_rate_alt);
                $this->info("- iframe match rate: $match_rate_alt");

                if(!empty($response_alt)){
                    if($match_rate < MATCH_RATE || $match_rate_alt < MATCH_RATE){
                        $changed = $date;
                        $site_status = 'changed';
                    }else{
                        $changed = null;
                        $site_status = 'active';
                    }
                }else{
                    if($match_rate < MATCH_RATE){
                        $changed = $date;
                        $site_status = 'changed';
                    }else{
                        $changed = null;
                        $site_status = 'active';
                    }
                }


                // take screenshot

                if($alt_url == null){
                    $new_screen = $this->screenshot($url, '_new');
                }else{
                    $new_screen = $this->screenshot($alt_url, '_new');
                }

                // take diff
                $diff = Jien::htmlDiff(htmlentities($current_code['code']), htmlentities($new_code));
                $diff_alt = Jien::htmlDiff(htmlentities($current_code['iframe_code']), htmlentities($new_code_alt));

                $site_data = array(
                    'site' => $site_id,
                    'code' => $response,
                    'iframe_code' => $response_alt,
                    'screen' => $new_screen
                );

                $siteData_id = Jien::model('SiteData')->save($site_data);

                $data = array(
                    'site_id' => $site_id,
                    'new_source' => $siteData_id,
                    'match_rate' => $match_rate,
                    'match_rate_alt' => $match_rate_alt,
                    'checked' => $date,
                    'changed' => $changed,
                    'diff' => $diff,
                    'diff_alt' => $diff_alt,
                    'site_status' => $site_status,
                );

                $filesize = $this->getfilesize($new_screen);
                if($filesize < 3000){
                    $data['new_screen'] = '';
                    $data['site_status'] = 'down';
                    $data['match_rate'] = 0;
                    $data['downed'] = $date;
                }

                Jien::model("Site")->save($data);
            }

        // site timed out
        }else{

            $this->info("site not reachable!");
            Jien::model("Site")->save(array(
                "site_id" => $site_id,
                "downed" => $date,
                "changed" => $date,
                "checked" => $date,
                "match_rate" => 0,
                'new_code' => '',
                'site_status' => 'down',
                ));
        }

    }

    function grab($url){
        $this->info("- grabbing {$url}...");
        //$result = shell_exec("wget -S -q -O - http://www.girlsgonewild.com 2>&1");
        //$result = shell_exec("/usr/bin/curl -L -3 {$url}");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt($ch, CURLOPT_NOPROGRESS, 0 );
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/proccrm_cookiejar");
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        $result = curl_exec($ch);
        return $result;
    }

    function getfilesize($path){
        $filesize = filesize(dirname(__FILE__) . "/../../public".$path);
        return $filesize;
    }

    function screenshot($site_url, $prefix = ''){
        $this->info("- taking screenshot ...");
        $url = "http://yoshimoshi.com:3000/?url={$site_url}";
        $filename = md5($url);
        $img_path = "/screenshots/{$filename}{$prefix}.png";
        $save_path = dirname(__FILE__) . "/../../public{$img_path}";
        $result = shell_exec("/usr/bin/curl {$url} > $save_path");
        $filesize = filesize($save_path);
        $this->info("/usr/bin/curl {$url} > {$save_path}");

        // try again if file size too small with https
        if($filesize < 3000){
            $this->info("FILE SIZE ({$filesize}) {$save_path} SMALL, redoing!");
            if(strstr("https", $site_url)){
                $site_url = str_replace("https", "http", $site_url);
            }else{
                $site_url = str_replace("http", "https", $site_url);
            }
            $url = "http://yoshimoshi.com:3000/?url={$site_url}";
            $filename = md5($url);
            $img_path = "/screenshots/{$filename}{$prefix}.png";
            $save_path = dirname(__FILE__) . "/../../public{$img_path}";
            $result = shell_exec("/usr/bin/curl {$url} > $save_path");
        }
        return $img_path;
    }

    public function info($msg = ''){
        if(!empty($_SERVER['SHELL'])){
            echo "$msg\r\n";
            ob_flush();
        }
    }
}