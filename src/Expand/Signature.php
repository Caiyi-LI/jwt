<?php

namespace yyc\Expand;
use yyc\Exception\RestfulApiException;
use yyc\Expand\Encryption;
class Signature{

    public function singnature(){            
        $encryption = new Encryption();
        $data = 'PHP加密解密算法';
       $passwod = $encryption->setCharacter($data)->encrypt();
       var_dump($passwod);
    }
}