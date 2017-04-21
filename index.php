<?php

    isAccess();

    /**
     * 检测是否在白名单内，否则拒绝访问
     * 
     * @return void
     */
    function isAccess(){
        $ip = getRemoteIp();

        if(!empty($ip)) {
            $inst = new Redis();
            $inst->connect('127.0.0.1', 6379);

            $ip_white_list = $inst->sMembers('ip_blacklist');
            if(!in_array($ip, $ip_white_list)) {
                echo '<h1 align=center>HTTP/1.1 403 Forbidden</h1>';
                header('HTTP/1.1 403 Forbidden');
            }
        }
        
    }

    /**
     * 获取用户的IP
     * 
     * @return void
     */
    function getRemoteIp(){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(getenv('HTTP_X_FORWARDED_FOR')){
            $ip = getenv('HTTP_X_FORWARED_FOR');
        }elseif(getenv('HTTP_CLIENT_IP')){
            $ip = getenv('HTTP_CLIENT_IP');
        }elseif($_SERVER['REMOTE_ADDR']){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        else{
            $ip = null;
        }
        return $ip;
    }

?>