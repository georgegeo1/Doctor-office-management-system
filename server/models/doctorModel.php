<?php

include_once 'BaseModel.php';

class doctorModel extends BaseModel{
    
    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"doctor_id",'
                . '         "type":"int",'
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
                . '         "name":"doctor_specialization",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_cv",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"doctor_photo",'
                . '         "type":"blob",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }
    
}
