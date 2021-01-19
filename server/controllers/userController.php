<?php

include_once 'BaseController.php';
include_once __DIR__ . '/../common/MAIL.php';

class userController extends BaseController {

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
                . '         "keyField":"user_id",'
                . '         "model":"users"'
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
                . '         "keyField":"user_id",'
                . '         "model":"user"'
                . '        }'
                . ']'
        );
    }

    /**
     * Executed before a (new, updated or deleted) record is saved to db.
     * 
     * @param $modelName      The name of the current model
     * @param $modelClassName The definition class of the current model
     * @param $modelFileName  The php file of the definition class of the current model
     * @param $keyField       The key field of the model
     * @param $dataDBMaster   The old version of the record to be saved
     * @param $dataNEWMaster  The new version of the record to be saved
     */
    protected static function onBeforeSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster) {
        // (FR8) Set "user_password" field equal to "user_name" (if it is null), 
        // for a newly inserted user

        if ($dataNEWMaster->_state == '2') {
            if (!$dataNEWMaster->user_password)
                $dataNEWMaster->user_password = $dataNEWMaster->user_name;
        }
    }

}
