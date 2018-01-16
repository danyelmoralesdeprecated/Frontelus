<?php

namespace Frontelus\Library\Security;

class Encoder
{
    public function encodeRijndael($str, $key)
    {
        $encode = base64_encode(
                mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encode;
    }

}
