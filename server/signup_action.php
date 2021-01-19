<?php

session_start();

$userName = $_POST["userName"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

if ($password1 !== $password2) {
    header("location: ../client/signup.php?errorMessage=Passwords don't match each other!");

    exit;
} else {
    include_once 'controllers/userController.php';

    //check if "user name" has already been taken ...
    
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
        header('Location: ../client/signup.php?errorMessage=Such a "User Name" already exists!');
        exit;
    } else {
        try {
            // create new user as "not active" ...
            
            include_once 'common/MAIL.php';
            include_once 'controllers/optionsController.php';

            $usrPacket = userController::create();

            $usr = $usrPacket->data[0]->records[0];
            $usr->user_name = $userName;
            $usr->user_password = $password1;
            $usr->user_type = "0";
            $usr->user_active = "0";
            $usr->user_email = $userName;
            $usrPacket->data[0]->records[0] = $usr;

            $usrPacket = userController::save($usrPacket);

            // send e-mail to new user in order to complete sign up ...
            
            $usr = $usrPacket->data[0]->records[0];

            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url = str_replace("signup_action", "complete_signup", $url);
            $url = $url . '?userId=' . $usr->user_id;

            $options = optionsController::getOptionsRecord();

            if ($options) {
                $mail_host = $options->options_mail_host;
                $mail_username = $options->options_mail_username;
                $mail_password = $options->options_mail_password;

                MAIL::SendMail($mail_host, $mail_username, $mail_password, $userName, "Sign Up", "You have successfully signed up! " .
                        "Please follow this link to activate your account : " . $url);
            } else {
                header('Location: ../client/signup.php?errorMessage=System Error!');
                exit;
            }

            header('Location: ../client/signup.php?message=Congratulations! ' .
                    'An email has been sent to your e-mail address. ' .
                    'Follow its link to activate your account.');

            exit;
        } catch (Exception $e) {
            header('Location: ../client/signup.php?errorMessage=Error: ' . $e->getMessage());
            exit;
        }
    }
}
