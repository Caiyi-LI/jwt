<?php

namespace yyc\Expand;
use yyc\Exception\RestfulApiException;
class Encryption{

    protected $key = 'R0BfrHuMFj';

    protected $encrypt = '';

    protected $character = '';

    protected function decryptMd5(){
        if(empty($this->character)){
            new RestfulApiException('character is not set');
        }
        return md5(md5($this->character));
    }

    protected function decryptBase64(){
        if(empty($this->character)){
            new RestfulApiException('character is not set');
        }
        return base64_decode(base64_decode($this->character));
    }

    public function setCharacter($character){
        $this->character = $character;
        return $this;
    }

    protected function getLength($key){
        return strlen($this->key);
    }

    /**
     * 加密
     * @return string
     */
    public function decrypt(){
        $key = $this->decryptMd5();
        $x = 0;
        $data = $this->decryptBase64();
        $len = $this->getLength($this->character);
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
    function encrypt(){
        $key = $this->decryptMd5();
        $x = 0;
        $len = strlen($this->character);
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
            $str .= chr(ord($this->character{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

}