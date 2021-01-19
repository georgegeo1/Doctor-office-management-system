<?php

include_once 'BaseController.php';

class doctor_officeController extends BaseController {
    
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
                . '         "keyField":"doctor_office_id",'
                . '         "model":"doctor_office"'
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
                . '         "keyField":"doctor_office_id",'
                . '         "model":"doctor_office"'
                . '        }'
                . ']'
        );
    }
    
}
