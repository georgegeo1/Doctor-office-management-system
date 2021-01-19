<?php

include_once 'BaseModel.php';

class optionsModel extends BaseModel {

    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"options_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"options_mail_host",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"options_mail_username",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"options_mail_password",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }

}
