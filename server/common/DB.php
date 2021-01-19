<?php

include_once 'CONFIG.php';

class DB {

    public static function query($modelName, $modelClassName, $modelFileName, $filters = null, $orderby = null) {
        include_once $modelFileName;

        $records = [];

        // Create connection

        $servername = CONFIG::$db_servername;
        $dbname = CONFIG::$db_name;

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", CONFIG::$db_username, CONFIG::$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare select statement

        $sql = 'SELECT ';

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;
            $fieldType = $field->type;

            if ($fieldType === "date") {
                $sql = $sql . "DATE_FORMAT(" . $fieldName . ", '%d/%m/%Y') " . $fieldName . ", ";
            } else {
                $sql = $sql . $fieldName . ", ";
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2);

        $sql = $sql . " FROM " . $modelName;

        if (is_array($filters)) {
            if (count($filters)) {
                $sql = $sql . ' where ';

                $filterIndex = 0;
                foreach ($filters as $filter) {
                    $filterIndex++;
                    
                    if (property_exists($filter, 'clause')) {
                        $sql = $sql . $filter->clause . ' and ';
                    } else {
                        $field = $modelClassName::getField($filter->field);
                        $fieldType = $field->type;
                        
                        if ($fieldType === "date") {
                            $sql = $sql . $filter->field . " " . $filter->op . " str_to_date(:filter" . $filterIndex . ", '%d/%m/%Y') and ";
                        } else {
                            $sql = $sql . $filter->field . " " . $filter->op . " :filter" . $filterIndex . " and ";
                        }
                    }
                }

                $sql = substr($sql, 0, strlen($sql) - 5);
            }
        }

        if ($orderby) {
            $sql = $sql . " order by " . $orderby;
        }

        // Execute select statement

        $stmt = $conn->prepare($sql);

        if (is_array($filters)) {
            if (count($filters)) {
                $filterIndex = 0;
                foreach ($filters as $filter) {
                    $filterIndex++;
                    
                    if (!property_exists($filter, 'clause')) {
                        $stmt->bindParam('filter' . $filterIndex, $filter->value, PDO::PARAM_STR);
                    }
                }
            }
        }

        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Collect selected rows to "records" array

        foreach ($stmt->fetchAll() as $row) {
            $model = new $modelClassName;

            foreach ($modelClassName::getMeta() as $field) {
                $fieldName = $field->name;
                $fieldType = $field->type;

                if ($fieldType === "blob") {
                    $model->$fieldName = base64_encode($row[$fieldName]);
                } else {
                    $model->$fieldName = $row[$fieldName];
                }
            }

            $records[] = $model;
        }

        return $records;
    }

    public static function update($modelName, $modelClassName, $modelFileName, $keyFldName, $keyFldValue, $record) {
        include_once $modelFileName;

        // Create connection

        $servername = CONFIG::$db_servername;
        $dbname = CONFIG::$db_name;

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", CONFIG::$db_username, CONFIG::$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare update statement

        $sql = "update " . $modelName . " set ";

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;
            $fieldType = $field->type;

            if ($fieldType === "date") {
                $sql = $sql . $fieldName . " = str_to_date(:" . $fieldName . ", '%d/%m/%Y'), ";
            } else {
                $sql = $sql . $fieldName . " = :" . $fieldName . ", ";
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2);

        $sql = $sql . " where " . $keyFldName . " = " . $keyFldValue;

        // Execute update statement

        $stmt = $conn->prepare($sql);

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;
            $fieldType = $field->type;

            if ($fieldType === "blob") {
                $lob = base64_decode($record->$fieldName);

                $stmt->bindParam($fieldName, $lob, PDO::PARAM_LOB);
            } else {
                $stmt->bindParam($fieldName, $record->$fieldName, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
    }

    public static function insert($modelName, $modelClassName, $modelFileName, $keyFldName, $record) {
        include_once $modelFileName;

        // Create connection

        $servername = CONFIG::$db_servername;
        $dbname = CONFIG::$db_name;

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", CONFIG::$db_username, CONFIG::$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare insert statement

        $sql = "insert into " . $modelName . " ( ";

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;

            if ($fieldName !== $keyFldName) {
                $sql = $sql . $fieldName . ", ";
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . " ) values (";

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;
            $fieldType = $field->type;

            if ($fieldName !== $keyFldName) {
                if ($fieldType === "date") {
                    $sql = $sql . " str_to_date(:" . $fieldName . ", '%d/%m/%Y'), ";
                } else {
                    $sql = $sql . ":" . $fieldName . ", ";
                }
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . " )";

        // Execute insert statement

        $stmt = $conn->prepare($sql);

        foreach ($modelClassName::getMeta() as $field) {
            $fieldName = $field->name;
            $fieldType = $field->type;

            if ($fieldName !== $keyFldName) {
                if ($fieldType === "blob") {
                    $lob = base64_decode($record->$fieldName);

                    $stmt->bindParam($fieldName, $lob, PDO::PARAM_LOB);
                } else {
                    $stmt->bindParam($fieldName, $record->$fieldName, PDO::PARAM_STR);
                }
            }
        }

        $stmt->execute();

        return $conn->lastInsertId();
    }

    public static function delete($modelName, $modelClassName, $modelFileName, $keyFldName, $keyFldValue) {
        include_once $modelFileName;

        // Create connection

        $servername = CONFIG::$db_servername;
        $dbname = CONFIG::$db_name;

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", CONFIG::$db_username, CONFIG::$db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare update statement

        $sql = "delete from " . $modelName . " where " . $keyFldName . " = " . $keyFldValue;

        // Execute delete statement

        $stmt = $conn->prepare($sql);

        $stmt->execute();
    }

}
