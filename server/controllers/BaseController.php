<?php

include_once __DIR__ . '/../common/DB.php';
include_once __DIR__ . '/../common/UTILS.php';

class BaseController {

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
        //
    }

    /**
     * Executed after a (new, updated or deleted) record has been saved to db.
     * 
     * @param $modelName      The name of the current model
     * @param $modelClassName The definition class of the current model
     * @param $modelFileName  The php file of the definition class of the current model
     * @param $keyField       The key field of the model
     * @param $dataDBMaster   The old version of the record to be saved
     * @param $dataNEWMaster  The new version of the record to be saved
     */ 
    protected static function onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster) {
        //
    }

    /**
     * Returns the meta data for the Browser
     * 
     * @return model's meta data as json object
     */ 
    protected static function getBrowserMeta() {
        return null;
    }

    /**
     * Returns the meta data for the Loader
     * 
     * @return  meta data as json object
     */ 
    protected static function getLoaderMeta() {
        return null;
    }

    /**
     * Returns the meta data for the Loader including model's fields
     * 
     * @return  meta data as json object
     */ 
    public static function getLoaderFullMeta() {
        $meta = static::getLoaderMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        include_once $modelFileName;

        $metaMaster->fields = $modelClassName::getMeta();

        return $meta;
    }

    /**
     * Returns the meta data for the Browser including model's fields
     * 
     * @return model's meta data as json object
     */ 
    public static function getBrowserFullMeta() {
        $meta = static::getBrowserMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        include_once $modelFileName;

        $metaMaster->fields = $modelClassName::getMeta();

        return $meta;
    }

    /**
     * Returns a list of records based on the specified params
     * 
     * @param $params The parameters (e.g. filters and sort keys) to query the db
     * 
     * @return a list of records as json object
     */ 
    public static function browse($params) {
        $meta = static::getBrowserFullMeta();

        if ($params) {
            if (property_exists($params, 'where')) {
                $where = $params->where;
            } else {
                $where = null;
            }

            if (property_exists($params, 'orderby')) {
                $orderby = $params->orderby;
            } else {
                $orderby = null;
            }
        } else {
            $where = null;
            $orderby = null;
        }

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        $records = DB::query($modelName, $modelClassName, $modelFileName, $where, $orderby);

        $dataMaster = new stdClass();
        $dataMaster->name = $metaMaster->name;
        $dataMaster->records = $records;

        $data = array($dataMaster);

        $rslt = new stdClass();
        $rslt->meta = $meta;
        $rslt->data = $data;

        return $rslt;
    }

    /**
     * Returns one (or more) records based on a given list of ids
     * 
     * @param $params A comma-separated list of ids
     * 
     * @return a list of records as json object
     */ 
    public static function load($params) {
        $meta = static::getLoaderFullMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        if (is_array($params)) {
            $idList = implode(",", $params);
        } else {
            $idList = $params;
        }

        $records = DB::query($modelName, $modelClassName, $modelFileName, UTILS::ToJsonObj(
                                array(
                                    array(
                                        'clause' => $metaMaster->keyField . ' ' . ' in (' . $idList . ')'
                                    )
                                )
                        )
        );

        $dataMaster = new stdClass();
        $dataMaster->name = $metaMaster->name;
        $dataMaster->records = $records;

        $data = array($dataMaster);

        $rslt = new stdClass();
        $rslt->meta = $meta;
        $rslt->data = $data;

        return $rslt;
    }

    /**
     * Creates - and returns - a new record (model)
     * 
     * @return a new record as json object
     */ 
    public static function create() {
        $meta = static::getLoaderFullMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        include_once $modelFileName;

        $records = [];

        $model = new $modelClassName;
        $model->_state = 2;

        $records[] = $model;

        $dataMaster = new stdClass();
        $dataMaster->name = $metaMaster->name;
        $dataMaster->records = $records;

        $data = array($dataMaster);

        $rslt = new stdClass();
        $rslt->meta = $meta;
        $rslt->data = $data;

        return $rslt;
    }

    /**
     * Saves a list of (new, updated or deleted) records to the db
     * 
     * @param $params The list of records to be saved to db
     * 
     * @return the same list of records, after having fetched from db, as json object
     */ 
    public static function save($params) {
        $ids = [];

        $meta = static::getLoaderFullMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        $keyField = $metaMaster->keyField;

        // do deletes first ...

        foreach ($params->data[0]->records as $dataMaster) {
            $state = $dataMaster->_state;

            if ($state == 3) {
                // prepare saving ...

                $id = $dataMaster->$keyField;

                $records = DB::query($modelName, $modelClassName, $modelFileName, UTILS::ToJsonObj(
                                        array(
                                            array(
                                                'field' => $keyField,
                                                'op' => '=',
                                                'value' => $id
                                            )
                                        )
                                )
                );

                $dataDBMaster = $records[0];
                $dataDBMaster->_state = $state;

                static::onBeforeSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataDBMaster);

                // do deletes ...

                DB::delete($modelName, $modelClassName, $modelFileName, $keyField, $id);

                // afrer saving ...

                static::onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataDBMaster);
            }
        }

        // then, do inserts and updates ...

        foreach ($params->data[0]->records as $dataMaster) {
            $state = $dataMaster->_state;

            if ($state == 2 || $state == 1) {
                // prepare saving ...

                $dataNEWMaster = new $modelClassName;
                $dataNEWMaster->_state = $state;

                foreach ($modelClassName::getMeta() as $field) {
                    $fldName = $field->name;

                    if (property_exists($dataMaster, $fldName)) {
                        $dataNEWMaster->$fldName = $dataMaster->$fldName;
                    }
                }

                if ($state == 2) {
                    $dataDBMaster = $dataNEWMaster;
                } else {
                    $id = $dataMaster->$keyField;

                    $records = DB::query($modelName, $modelClassName, $modelFileName, UTILS::ToJsonObj(
                                            array(
                                                array(
                                                    'field' => $keyField,
                                                    'op' => '=',
                                                    'value' => $id
                                                )
                                            )
                                    )
                    );

                    $dataDBMaster = $records[0];
                }

                $dataDBMaster->_state = $state;

                static::onBeforeSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster);

                // save to db ...

                if ($state == 2) { // insert
                    $id = DB::insert($modelName, $modelClassName, $modelFileName, $keyField, $dataNEWMaster);

                    $ids[] = $id;
                } else {           // update 
                    DB::update($modelName, $modelClassName, $modelFileName, $keyField, $id, $dataNEWMaster);

                    $ids[] = $id;
                }

                // after saving ...
                
                static::onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataNEWMaster);
            }
        }

        return static::load($ids);
    }

    /**
     * Deletes - physically - the record of a specified id 
     * 
     * @param $params The id of the record to be deleled
     * 
     * @return true if the record has been deleted normally
     */ 
    public static function delete($params) {
        $meta = static::getLoaderFullMeta();

        $metaMaster = $meta[0];

        $modelName = $metaMaster->model;
        $modelFileName = __DIR__ . '/../models/' . $modelName . "Model.php";
        $modelClassName = $metaMaster->model . "Model";

        $keyField = $metaMaster->keyField;

        // prepare saving ...

        $id = $params;

        $records = DB::query($modelName, $modelClassName, $modelFileName, UTILS::ToJsonObj(
                                array(
                                    array(
                                        'field' => $keyField,
                                        'op' => '=',
                                        'value' => $id
                                    )
                                )
                        )
        );

        $dataDBMaster = $records[0];
        $dataDBMaster->_state = 3;

        static::onBeforeSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataDBMaster);

        // save to db ...

        DB::delete($modelName, $modelClassName, $modelFileName, $keyField, $id);

        // after saving ...

        static::onAfterSave($modelName, $modelClassName, $modelFileName, $keyField, $dataDBMaster, $dataDBMaster);

        return true;
    }

}
