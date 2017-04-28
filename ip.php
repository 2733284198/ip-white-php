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
            if(preg_match('/^\d{2,3}.\d{1,3}.\d{1,3}.\d{1,3}$/',$ip)) {
                $rs = $inst->sAdd('ip_blacklist',$ip);
                echo $rs;
            } else {
                echo 2;
            }
        }
        if($type == 'import'){
            $ips = explode('|',$ip);
            $i = 0;
            foreach($ips as $_ip){
                if(preg_match('/^\d{2,3}.\d{1,3}.\d{1,3}.\d{1,3}$/',$_ip)) {
                    if($inst->sAdd('ip_blacklist',$_ip) == 1){
                        $i++;
                    }
                }
            }
            if($i == 0){
                echo 'error';
                return;
            }
            echo '成功导入'.$i.'条记录';
        }
    }
} else {

    if($type == 'deleteall') {
        $rs = $inst->delete('ip_blacklist');
        echo $rs;
    }

    if ($type == 'excel') {
        header("Content-type:application/vnd.ms-excel");  //设置内容类型
        header("Content-Disposition:attachment;filename=data.xls");  //文件下载

        $ips = $inst->sMembers('ip_blacklist');
        foreach($ips as $ip) {
            echo $ip ;
            echo "\n";
        }
    }

} 



?>