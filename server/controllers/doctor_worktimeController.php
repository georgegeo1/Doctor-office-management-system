<?php

include_once 'BaseController.php';

class doctor_worktimeController extends BaseController {

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
                . '         "keyField":"doctor_worktime_id",'
                . '         "model":"doctor_worktime"'
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
                . '         "keyField":"doctor_worktime_id",'
                . '         "model":"doctor_worktime"'
                . '        }'
                . ']'
        );
    }

    /**
     * Returns the working hours of a specific doctor for a specific week
     * 
     * @param $params  A json that includes the doctor's id and the specified
     *                 week (e.g. first and last date of week)
     * 
     * @return a list of records as json object
     */ 
    public static function loadForDoctor($params) {
        $meta = static::getBrowserFullMeta();

        $where = UTILS::ToJsonObj(
                        array(
                            array(
                                'field' => 'doctor_worktime_doctor_id',
                                'op' => '=',
                                'value' => $params->doctorId
                            ),
                            array(
                                'field' => 'doctor_worktime_date',
                                'op' => '>=',
                                'value' => $params->weekFirstDate
                            ),
                            array(
                                'clause' => "doctor_worktime_date <= date_add(STR_TO_DATE('" . $params->weekFirstDate . "', '%d/%m/%Y'), INTERVAL 6 DAY)"
                            )
                        )
        );

        $orderby = 'doctor_worktime_date';

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

}
