<?php

include_once 'BaseModel.php';

class appointmentModel extends BaseModel {

    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"appointment_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_patient_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_user_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_state",'
                . '         "type":"int",'
                . '         "default": "0"'
                . '        },'
                . '        {'
                . '         "name":"appointment_time",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_date",'
                . '         "type":"date",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_reason",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_doctor_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_cncl",'
                . '         "type":"varchar",'
                . '         "default": "0"'
                . '        },'
                . '        {'
                . '         "name":"appointment_cncl_reason",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }

}
