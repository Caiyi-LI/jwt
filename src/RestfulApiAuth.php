<?php
namespace yyc;
use yyc\Expand\Signature;
class RestfulApiAuth{

    protected $origin = '';

    protected $headers = [];

    protected $isAllowHeaders = ['REQUEST_METHOD', 'CONTENT_TYPE', 'REQUEST_TIME', 'SERVER_NAME', 'QUERY_STRING', 'SERVER_ADDR', 'REMOTE_ADDR', 'SERVER_PORT', 'SERVER_SIGNATURE'];

    private $signature = '';

    public function __construct($config = []){
        if (is_array($config)) {
            foreach($config as $key => $item){
                $this->$key = $item;
            }
        }
    }

    public function handle(){
        $this->converHeader();
    }

    private function converHeader(){
        foreach($_SERVER as $serverKey => $item){
            if(in_array($serverKey, $this->isAllowHeaders)){
                $this->headers[strtolower(str_replace('_', '-', $this->trimSpace($serverKey)))] = $_SERVER[$serverKey];
            }
            if(substr($serverKey, 0, 5) == 'HTTP_'){
                $this->headers[strtolower(str_replace('_', '-', $this->trimSpace($serverKey)))] = $item;
            }
        }
        if(strtoupper($this->headers['request-method']) == 'OPTIONS'){
            $params = [];
            $params['host'] = $this->headers['http-host'];
            $signature = new Signature($params);
            $this->signature = $signature->signature();
            $this->next();
        }
        echo '后续';
    }

    private function next(){
        // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:OPTIONS');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        header('Access-Control-Allow-Headers:authorization');
        header('authorization:authorization');
    }

    private function trimSpace($key){
        return preg_replace('/[ |\n\s|\n]/', '', $key);
    }
}