<?php
$inst = new Redis();
$inst->connect('127.0.0.1', 6379);

$ip = $inst->sMembers('ip_blacklist');

$isIp = empty($ip) ? false : true;
$li = '';
foreach($ip as $val) {
    $li .= '<li><span>'.$val.'</span>&nbsp;<a data="'.$val.'" class="js_del" href="javascript:void(0)">delete</a></li>';
}

?>

<html>
    <head>
        <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
	    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
	    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            ul,li {
                list-style: none;
            }
            ul {
                margin: 0;
                padding: 0;
            }
            li {
                padding: 5px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>IP白名单管理助手</h1>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">添加</h3>
                </div>
                <div class="panel-body">
                    <ul id="ipList" data="<?php echo $isIp;?>">
                        <?php echo $li;?>   
                    </ul>  
                    <input type="text" name="ip_text" />
                    <button id="submit">添加</button>
                </div>
            </div>    
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">批量操作</h3>
                </div>
                <div class="panel-body">
                    <button id="export">批量导出</button>
                    <button id="delete">批量删除</button>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">批量导入</h3>
                </div>
                <div class="panel-body">
                    <textarea id="js_ip_textarea" class="form-control" rows="3"></textarea><br>
                    <button id="import">批量导入</button>
                </div>
            </div>
        </div>        
    </body>
    <script>
        const DOMAIN = '//localhost:6699/';
        $(function(){
            // 删除
            $('#ipList').on('click','.js_del',function(){
                var _this = this,
                    ip_text = $.trim($(this).attr('data')); 
                $.ajax({
                    url: DOMAIN + 'ip-white-php/ip.php',
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
                    re = /^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/;
                if (!re.test(ip_text)) {
                    alert('您输入的IP格式不对');
                    return;
                }   
                $.ajax({
                    url: DOMAIN + 'ip-white-php/ip.php',
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
            });
            // 批量删除
            $('#delete').click(function(){
                var ip = $('#ipList').attr('data');
                if (!ip) {
                    alert('没有IP可供批量删除');
                    return;
                }
                if (confirm('你确定要批量删除？')){
                    $.ajax({
                        url: DOMAIN + 'ip-white-php/ip.php',
                        method: 'POST',
                        data: 'type=deleteall',
                        success: function(res) {
                            if(res == 1) {
                                alert('success');
                                location.reload();
                            } else {
                                alert('failed');
                            }
                        }
                    })
                }
            });
            // 导出excel
            $('#export').click(function(){
               var ip = $('#ipList').attr('data');
               if (!ip) {
                    alert('没有IP可供批量导出');
                    return;
               }
               var f = document.createElement("form");
               document.body.appendChild(f);
               $(f).attr({
                    action: DOMAIN + "ip-white-php/ip.php",
                    method: "POST"
               });

               var inp = document.createElement("input");
               inp.name = "type";
               inp.value = "excel";
               inp.style.display = "none";
               f.appendChild(inp);
               f.submit();
            });
            // 批量导入
            $('#import').click(function(){
                window.vals = $('#js_ip_textarea').val().split("\n");
                var ip_text = vals.join('|');
                $.ajax({
                    url: DOMAIN + 'ip-white-php/ip.php',
                    method: 'POST',
                    data: 'type=import&ip=' + ip_text,
                    success: function(res) {
                        if(res != 'error'){
                            alert(res);
                            location.reload();
                        } else {
                            alert('failed');
                        }
                    }
                })
            });
        })
    </script>
</html>
