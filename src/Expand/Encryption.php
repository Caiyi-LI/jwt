<?php

namespace yyc\Expand;
use yyc\Exception\RestfulApiException;
class Encryption{

    protected $key = 'R0BfrHuMFj';

    protected $encrypt = '';

    protected $character = '';

    public function setCharacter($character){
        $this->character = $character;
        return $this;
    }

    /**
     * 解密
     */
    function decrypt()
    {
        $key = md5($this->key);
        $x = 0;
        $data = base64_decode($this->character);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 加密
     */
    function encrypt()
    {
        $key    =    md5($this->key);
        $x        =    0;
        $len    =    strlen($this->character);
        $l        =    strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            $str .= chr(ord($this->character{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

}