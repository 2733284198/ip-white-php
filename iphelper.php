<?php
$inst = new Redis();
$inst->connect('127.0.0.1', 6379);

$ip = $inst->sMembers('ip_blacklist');

$li = '';
foreach($ip as $val) {
    $li .= '<li><span>'.$val.'</span>&nbsp;<a data="'.$val.'" class="js_del" href="javascript:void(0)">delete</a></li>';
}

?>

<html>
    <head>
        <style>
            ul,li {
                list-style: none;
            }
            ul {
                margin: 0;
                padding: 0;
            }
            li {
                padding: 5px;
                padding-top:0;
            }
        </style>
    </head>
    <body>
        <ul id="ipList">
            <?php echo $li;?>   
        </ul>  

        <input type="text" name="ip_text" />
        <button id="submit">add</button>        
    </body>
    <script src="https://cdn.bootcss.com/jquery/3.2.0/jquery.js"></script>
    <script>
        $(function(){
            // 删除
            $('#ipList').on('click','.js_del',function(){
                var _this = this,
                    ip_text = $.trim($(this).attr('data')); 
                $.ajax({
                    url: 'http://localhost:6699/ip-white-php/ip.php',
                    method: 'POST',
                    data: 'type=delete&ip='+ip_text,
                    success: function(res){
                       if(res == 1){
                           alert('success');
                           $(_this).parent().remove();
                       } else {
                           alert('failed');
                       }     
                    }
                })
            })
            // 添加
            $('#submit').click(function(){
                var ip_text = $.trim($("input[type=text]").val()),
                    re = /^\d{2,3}.\d{1,3}.\d{1,3}.\d{1,3}$/;
                if (!re.test(ip_text)) {
                    alert('您输入的IP格式不对');
                    return;
                }   
                $.ajax({
                    url: 'http://localhost:6699/ip-white-php/ip.php',
                    method: 'POST',
                    data: 'type=add&ip='+ip_text,
                    success: function(res){
                       if(res == 1){
                           alert('success');
                           $('#ipList').append('<li><span>'+ip_text+'</span>&nbsp;<a data="'+ip_text+'" class="js_del" href="javascript:void(0)">delete</a></li>')
                       } else {
                           alert('failed');
                       }
                    }
                })
            })
        })
    </script>
</html>
