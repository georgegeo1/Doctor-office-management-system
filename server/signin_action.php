<?php

include_once 'common/UTILS.php';

session_start();

$userName = $_POST["userName"];
$password = $_POST["password"];

$found = false;
$error_message = '';

try {
    include_once 'controllers/userController.php';

    $usrPacket = userController::browse(
                    UTILS::ToJsonObj(
                            array(
                                'where' => array(
                                    array(
                                        'field' => 'user_name',
                                        'op' => '=',
                                        'value' => $userName
                                    )
                                )
                            )
                    )
    );

    if (count($usrPacket->data[0]->records)) {
        $usr = $usrPacket->data[0]->records[0];

        if ($usr->user_active === '1') {
            if ($usr->user_password === $password) {
                $found = true;

                $_SESSION['userId'] = $usr->user_id;
                $_SESSION['userRole'] = $usr->user_type;
                $_SESSION['userName'] = $userName;
            } else {
                $error_message = 'Wrong Password!';
            }
        } else {
            $error_message = 'Inactive User!';
        }
    } else {
        $error_message = 'Unknown Username! Please, go to "Sign up"';
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

if ($found) {
    header("Location: ../index.php");
} else {
    header("location: ../client/signin.php?errorMessage=Wrong Login Attempt : " . $error_message);
}

exit;
