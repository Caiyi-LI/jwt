<?php
namespace yyc;
use yyc\Expand\Signature;
class RestfulApiAuth{

    private $origin = [];

    private $headers = [];

    private $isAllowHeaders = ['REQUEST_METHOD', 'CONTENT_TYPE', 'REQUEST_TIME', 'SERVER_NAME', 'QUERY_STRING', 'SERVER_ADDR', 'REMOTE_ADDR', 'SERVER_PORT', 'SERVER_SIGNATURE'];

    private $isAllowMethods = 'OPTIONS,GET,POST,DELETE,PUT';

    private $isAllowCustomizeHeaders = [];

    private $certificationMethods = 'OPTIONS';

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
        if(strtoupper($this->headers['request-method']) == $this->certificationMethods){
            $params = [];
            $params['host'] = $this->headers['http-host'];
            $signature = new Signature($params);
            $this->isAllowCustomizeHeaders['_authorization'] = $signature->signature();
            $this->_next();
            return;
        }
    }

    private function _next(){
        $this->_setAllowOrigin();
        $this->_setAllowMethods();
        $this->_setAllowHeaders();
    }

    private function _setAllowOrigin(){
        if(!$this->isAllowOrigin(str_replace('http://', '', $this->headers['http-origin']))){
            $this->_set403Forbidden();
        }
        header('Access-Control-Allow-Origin:'. $this->headers['http-origin'] .'');
    }

    private function _setAllowMethods(){
        if(!$this->isAllowMethods($this->headers['request-method'])){
            $this->_set403Forbidden();
        }
        header('Access-Control-Allow-Methods:'. $this->headers['request-method'] .'');
    }

    public function isAllowMethods($method){
        return in_array($method, explode(',', $this->isAllowMethods)) ? true : false;
    }

    public function isAllowOrigin($origin){
        return in_array($origin, $this->origin) ? true : false;
    }

    private function _set403Forbidden(){
        header('HTTP/1.1 403 Forbidden');
        return;
    }

    private function _setAllowHeaders(){
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