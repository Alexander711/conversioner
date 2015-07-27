<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('create_random_code')) {

    function create_random_code($length) {
        $chars = '0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }

        return $string;
    }

}