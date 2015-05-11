<?php

    namespace Bank\Util;

    class StringUtil 
    {
        
        public static function randomString($length = 32) {
            $characters = '23456789abcdefghkmnpqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    }