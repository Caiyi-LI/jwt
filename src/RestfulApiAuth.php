<?php
namespace yyc;
use yyc\Exception\RestfulApiException;
class RestfulApiAuth{

    protected $origin = '';

    protected $headers = [];

    protected $isAllowHeaders = ['REQUEST_METHOD', 'CONTENT_TYPE', 'REQUEST_TIME', 'SERVER_NAME', 'QUERY_STRING', 'SERVER_ADDR', 'REMOTE_ADDR', 'SERVER_PORT', 'SERVER_SIGNATURE'];

    public function __construct($config = []){
        if (is_array($config)) {
            foreach($config as $key => $item){
                $this->$key = $item;
            }
        }
    }

    public function handle(){
        $this->converHeader();
        var_dump($this->headers);
        die();
    }

    private function converHeader(){
                      // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:http://dev.blog.com');
        // 响应类型
        header('Access-Control-Allow-Methods:POST,GET');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');

        throw new RestfulApiException('配置不能为空', 'ERROR_CONFIG_EMPTY');
        foreach($_SERVER as $serverKey => $item){
            if(in_array($serverKey, $this->isAllowHeaders)){
                $this->headers[strtolower(str_replace('_', '-', $this->trimSpace($serverKey)))] = $_SERVER[$serverKey];
            }
            if(substr($serverKey, 0, 5) == 'HTTP_'){
                $this->headers[strtolower(str_replace('_', '-', $this->trimSpace($serverKey)))] = $item;
            }
        }
    }

    private function trimSpace($key){
        return preg_replace('/[ |\n\s|\n]/', '', $key);
    }
}