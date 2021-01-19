<?php

include_once 'BaseModel.php';

class userModel extends BaseModel {

    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return json_decode(
                '['
                . '        {'
                . '         "name":"user_id",'
                . '         "type":"int",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_name",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_password",'
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
                . '         "name":"user_type",'
                . '         "type":"varchar",'
                . '         "default":"0"'
                . '        },'
                . '        {'
                . '         "name":"user_active",'
                . '         "type":"varchar",'
                . '         "default":"1"'
                . '        },'
                . '        {'
                . '         "name":"user_address",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_phone",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_zip",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        },'
                . '        {'
                . '         "name":"user_email",'
                . '         "type":"varchar",'
                . '         "default": null'
                . '        }'
                . ']'
        );
    }

}
