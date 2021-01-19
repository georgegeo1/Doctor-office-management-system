<?php

include_once 'BaseController.php';

class doctorController extends BaseController {

    /**
     * Returns the meta data for the Browser
     * 
     * @return model's meta data as json object
     */ 
    protected static function getBrowserMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"master",'
                . '         "keyField":"doctor_id",'
                . '         "model":"doctors"'
                . '        }'
                . ']'
        );
    }

    /**
     * Returns the meta data for the Loader
     * 
     * @return model's meta data as json object
     */ 
    protected static function getLoaderMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"master",'
                . '         "keyField":"doctor_id",'
                . '         "model":"doctor"'
                . '        }'
                . ']'
        );
    }

}
