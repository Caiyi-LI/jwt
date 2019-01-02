<?php

namespace yyc\Expand;
use yyc\Exception\RestfulApiException;
use yyc\Expand\Encryption;
class Signature{

    private $key = 'IKReHJ0svHcZBuJy';

    private $time = '';

    private $host = '';

    public function __construct($params){
        if(is_array($params)){
            foreach($params as $key => $param){
                $this->$key = $param;
            }
        }
    }

    public function singnature()
    {
        if(empty($this->time)){
            $this->time = time();
        }
        if(empty($this->host)){
            new RestfulApiException('host is empty');
        }
        $encryption = new Encryption();
        $data = 'PHP加密解密算法';
       $passwod = $encryption->setCharacter($data)->encrypt();
       var_dump($passwod);
    }

    private function _header()
    {
        $header = "{'type':'jwt','alg':'customize'}";
        return $header;
    }

    private function _payload(){
        $payload = "{}";
    }
}