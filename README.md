# jwt
restful api授权控制

# 使用方式
----
### 1.composer 安装
```
composer require caiyi-li/jwt
```

### 2.代码引入
```
<?php

require('../jwt/autoLoad.php');
$parms = [
    'origin' => ['www.xxx.com']
];
use yyc\RestfulApiAuth;
$restful = new RestfulApiAuth($parms);
$restful->handle();
echo '后续';
```
### 3.前端请求
```
        $.ajax({
            type: "OPTIONS",
            url: "http://www.test.com/index.php",
            data: {username:$("#user").val(), password:$("#password").val()},
            dataType: "json",
            success: function(data){
                console.log(data);
            }
        });

```
```
        $.ajax({
            type: "GET",
            url: "http://www.test.com/index.php",
            data: {username:$("#user").val(), password:$("#password").val()},
            dataType: "json",
            beforeSend:function(xhr,options){
                xhr.setRequestHeader("authorization", "加密字符串") ;
            },
            success: function(data){
                console.log(data);
            }
        });
```

