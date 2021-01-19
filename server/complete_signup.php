<?php

session_start();

if (array_key_exists("userId", $_GET)) {
    $userId = $_GET["userId"];

    if ($userId) {
        include_once 'controllers/userController.php';

        try {
            // make new user "active" ...
            
            $usrPacket = userController::load($userId);

            $usr = $usrPacket->data[0]->records[0];
            $usr->user_active = "1";
            $usr->_state = 1;

            $usrPacket->data[0]->records[0] = $usr;

            userController::save($usrPacket);

            echo "Done! You are ready to sign in!";
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }
}
