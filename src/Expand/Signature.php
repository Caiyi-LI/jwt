<?php

namespace yyc\Expand;
use yyc\Exception\RestfulApiException;
class Signature{

    public $key = 'IKReHJ0svHcZBuJy';

    protected $time = '';

    protected $host = '';

    protected $authorization = null;

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
            throw new RestfulApiException('host is empty');
        }
        $encryption = new Encryption();
        $header = $encryption->setCharacter($this->_header())->encrypt();
        $payload = $encryption->setCharacter($this->_payload())->encrypt();
        $sing = $encryption->setCharacter($this->host . $this->key)->encrypt();
        return $header . '.' . $payload . '.' . $sing;
    }

    public function solve(){
        if(empty($this->authorization)){
            throw new RestfulApiException('authorization is empty');
        }
        $this->authorization = explode('.', $this->authorization);
        $encryption = new Encryption();
        $sing = $encryption->setCharacter($this->authorization[2])->decrypt();
        return $sing;
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