<?php

include_once 'BaseController.php';

class optionsController extends BaseController {

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
                . '         "keyField":"options_id",'
                . '         "model":"options"'
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
                . '         "keyField":"options_id",'
                . '         "model":"options"'
                . '        }'
                . ']'
        );
    }

    /**
     * Returns options data as a record
     * 
     * @return the optionn record 
     */ 
    public static function getOptionsRecord() {
        $optionsPacket = static::load(1);

        if (count($optionsPacket->data[0]->records)) {
            return $optionsPacket->data[0]->records[0];
        } else {
            return null;
        }
    }

}
