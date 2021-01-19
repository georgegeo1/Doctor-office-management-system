<?php

class UTILS {

    public static function ToJsonObj($o) {
        return json_decode(json_encode($o));
    }
    
    public static function wppIsNull($v) {
        return is_null($v) || is_empty($v) || (v === "");
    }

}
