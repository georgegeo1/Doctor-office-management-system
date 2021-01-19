<?php

include_once 'BaseModel.php';

class doctor_worktimeModel extends BaseModel {

    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"doctor_worktime_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_worktime_doctor_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_worktime_time",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_worktime_date",'
                . '         "type":"date",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }

}
