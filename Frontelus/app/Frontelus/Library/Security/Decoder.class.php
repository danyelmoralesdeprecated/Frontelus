<?php

namespace Frontelus\Library\Security;

class Decoder
{

    public function decodeRijndael($encoded, $key)
    {
        $decode = rtrim(
                mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encoded), MCRYPT_MODE_CBC, md5(md5($key))
                ), "\0");
        return $decode;
    }

}
