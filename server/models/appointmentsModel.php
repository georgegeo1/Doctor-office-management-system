<?php

include_once 'BaseModel.php';

class appointmentsModel extends BaseModel {

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
                . '         "name":"appointment_time",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_date",'
                . '         "type":"varchar",'
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
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_cncl_reason",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_firstname",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_lastname",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_firstname",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_lastname",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_state_descr",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"appointment_cncl_descr",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }

}
