<?php

$inst = new Redis();
$inst->connect('127.0.0.1', 6379);

$type = isset($_POST['type']) ? $_POST['type'] : ''; 
$ip = isset($_POST['ip']) ? $_POST['ip'] : '';

if(!empty($ip)){
    $isExist = $inst->sIsMember('ip_blacklist',$ip);
    
    if($isExist) {
        if($type == 'delete'){
            // delete
            $rs = $inst->sRemove('ip_blacklist',$ip);
            echo $rs;
        }
    } else {
        if($type == 'add'){
            if(preg_match('/^\d{2,3}.\d{2,3}.\d{2,3}.\d{1,3}$/',$ip)) {
                $rs = $inst->sAdd('ip_blacklist',$ip);
                echo $rs;
            } else {
                echo 2;
            }
        }
    }
} 



?>