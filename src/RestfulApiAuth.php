<?php
namespace yyc;
use yyc\Expand\Signature;
class RestfulApiAuth{

    private $origin = [];

    private $headers = [];

    private $isAllowHeaders = ['REQUEST_METHOD', 'CONTENT_TYPE', 'REQUEST_TIME', 'SERVER_NAME', 'QUERY_STRING', 'SERVER_ADDR', 'REMOTE_ADDR', 'SERVER_PORT', 'SERVER_SIGNATURE'];

    private $isAllowMethods = 'OPTIONS,GET,POST,DELETE,PUT';

    private $isAllowCustomizeHeaders = [];

    public function __construct($config = []){
        if (is_array($config)) {
            foreach($config as $key => $item){
                $this->$key = $item;
            }
        }
    }

    public function handle(){
        $this->_converHeader();
    }

    private function _converHeader(){
        foreach($_SERVER as $serverKey => $item){
            if(in_array($serverKey, $this->isAllowHeaders)){
                $this->headers[strtolower(str_replace('_', '-', $this->_trimSpace($serverKey)))] = $_SERVER[$serverKey];
            }
            if(substr($serverKey, 0, 5) == 'HTTP_'){
                $this->headers[strtolower(str_replace('_', '-', $this->_trimSpace($serverKey)))] = $item;
            }
        }
        if(strtoupper($this->headers['request-method']) == 'OPTIONS'){
            $params = [];
            $params['host'] = $this->headers['http-host'];
            $signature = new Signature($params);
            $this->isAllowCustomizeHeaders['_authorization'] = $signature->signature();
            $this->_next();
            return;
        }
        echo '后续';
    }

    private function _next(){
        $this->_allowOrigin();
        $this->_allowMethods();
        $this->_allowHeaders();
    }

    private function _allowOrigin(){
        if(!in_array(str_replace('http://', '', $this->headers['http-origin']), $this->origin)){
            header('HTTP/1.1 403 Forbidden');
            return;
        }
        header('Access-Control-Allow-Origin:'. $this->headers['http-origin'] .'');
    }

    private function _allowMethods(){
        header('Access-Control-Allow-Methods:'. $this->isAllowMethods .'');
    }

    private function _allowHeaders(){
        if(is_array($this->isAllowCustomizeHeaders)){
            foreach($this->isAllowCustomizeHeaders as $headerKey => $customizeHeader){
                header('Access-Control-Allow-Headers:'. $headerKey .'');
                header(''. $headerKey .':'. $customizeHeader .'');
            }
        }
    }

    private function _trimSpace($key){
        return preg_replace('/[ |\n\s|\n]/', '', $key);
    }
}