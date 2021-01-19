<?php

class BaseModel {

    /**
     * Returns model's meta data
     * 
     * @return model's meta data as json object
     */ 
    public static function getMeta() {
        return null;
    }

    /**
     * Returns field's meta data
     * 
     * @param $fieldName The name of the specific field
     * 
     * @return field's meta data
     */ 
    public static function getField($fieldName) {
        foreach (static::getMeta() as $field) {
            if ($field->name == $fieldName) {
                return $field;
            }
        }

        return null;
    }

    /**
     * Creates Model's fields based on model's meta data. 
     * Also, assigns default values to fields (if any) 
     */ 
    function __construct() {
        $this->_state = 0;

        $meta = static::getMeta();

        foreach ($meta as $field) {
            $fieldName = $field->name;

            if (property_exists($field, "default")) {
                $this->$fieldName = $field->default;
            } else {
                $this->$fieldName = null;
            }
        }
    }

}
