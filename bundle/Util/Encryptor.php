<?php
namespace Tunacan\Bundle\Util;


class Encryptor
{
    public function makeTrip($data)
    {
        if (is_null($data) || $data === '') {
            throw new \InvalidArgumentException();
        }
        $salt = substr($data . "H.", 1, 2);
        $salt = preg_replace("/[^\.-z]/", ".", $salt);
        $salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef0123456789");
        return substr(crypt($data, $salt), -10);
    }
}