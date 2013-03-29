<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonCrypto
 */
Class HabboEncoding{
                public static function DecodeBit8($v){
                        $v = str_split($v);
                        if ((ord($v[0]) | ord($v[1])) < 0)
                                return -1;
                        return ((ord($v[0]) << 8) + (ord($v[1]) << 0));
                }
                public static function DecodeBit24($v) {
                        $v = str_split($v);
                        if ((ord($v[0]) | ord($v[1]) | ord($v[2]) | ord($v[3])) < 0)
                                return -1;
                        return ((ord($v[0]) << 24) + (ord($v[1]) << 16) + (ord($v[2]) << 8) + (ord($v[3]) << 0));
                }
                public static function EncodeBit8($value){
                        $result = chr(($value >> 8) & 0xFF);
                        $result.= chr($value & 0xFF);
                        return $result;
                }
                public static function EncodeBit24($value){
                        $result = chr(($value >> 24) & 0xFF);
                        $result.= chr(($value >> 16) & 0xFF);
                        $result.= chr(($value >> 8) & 0xFF);
                        $result.= chr($value & 0xFF);
                        return $result;
                }
}
?>