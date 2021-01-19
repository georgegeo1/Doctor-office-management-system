<?php

include_once 'common/UTILS.php';

$controller = null;
$method = null;
$params = null;

// clarify call' s data (i) controller ii) method and iii) arguments) ...

if (count($_GET)) {
    $controller = $_GET['controller'] . 'Controller';
    $method = $_GET['method'];
    $params = $_GET['params'];
} elseif (count($_POST)) {
    $controller = $_POST['controller'] . 'Controller';
    $method = $_POST['method'];
    $params = $_POST['params'];
}

if (!is_null($controller)) {
    $params = UTILS::ToJsonObj($params);

    // include controller' s file ...
    
    include_once __DIR__ . '/controllers/' . $controller . '.php';

    // call controller' s method...
    
    try {
        $reponse = $controller::$method($params);
    } catch (Exception $e) {
        $reponse = UTILS::ToJsonObj(array("error" => $e->getMessage()));
    }
} else {
    $reponse = null;
}

// return response to client ...

echo json_encode($reponse);
?>
