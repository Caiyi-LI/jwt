<?php

namespace yyc;
use yyc\Exception\RestfulApiException;
class Encryption{

    protected $key = 'R0BfrHuMFj';

    protected $encrypt = '';

    protected $character = '';

    protected function decryptMd5(){
        if(empty($this->character)){
            throw new RestfulApiException('character is not set');
        }
        return md5(md5($this->character));
    }

    protected function decryptBase64(){
        if(empty($this->character)){
            throw new RestfulApiException('character is not set');
        }
        return base64_decode(base64_decode($this->character));
    }

    public function setCharacter($character){
        $this->character = $character;
    }

    /**
     * 加密
     */
    public function decrypt($data){
        $key = $this->decryptMd5();
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++){
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++){
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))){
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 解密
     */
    function encrypt($data){
        $key = md5($key);
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++){
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++){
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

}