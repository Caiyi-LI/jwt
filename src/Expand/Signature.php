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

    public function signature()
    {
        if(empty($this->time)){
            $this->time = time();
        }
        if(empty($this->host)){
            new RestfulApiException('host is empty');
        }
        $encryption = new Encryption();
        $header = $encryption->setCharacter($this->_header())->encrypt();
        $payload = $encryption->setCharacter($this->_payload())->encrypt();
        $sing = $encryption->setCharacter($this->host . $this->key)->encrypt();
        return $header . '.' . $payload . '.' . $sing;
    }

    private function _header()
    {
        $header = [
            'type' => 'jwt',
            'alg' => 'customize'
        ];
        return json_encode($header);
    }

    private function _payload(){
        $payload = [
            'hots' => $this->host
        ];
        return json_encode($payload);
    }
}