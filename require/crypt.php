<?php

function cryptSave($data)
{
    try
    {
        $method = "aes-256-cbc";
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, $method, CRYPTkey, 0, $iv);
        $output = bin2hex($iv . $encrypted);    
        return $output;
    }
    catch(Exception $e) { return false; }
}

function cryptDel($input)
{        
    try
    {
        $mix = hex2bin($input);
        $method = "aes-256-cbc";    
        $iv_length = openssl_cipher_iv_length($method);
        $iv = substr($mix, 0, $iv_length);
        $encrypted = substr($mix, $iv_length);
        $data = openssl_decrypt($encrypted, $method, CRYPTkey, 0, $iv);
        return $data;
    }
    catch(Exception $e) { return false; }
}

?>