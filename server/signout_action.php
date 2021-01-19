<?php

session_start();

unset($_SESSION['userId']);
unset($_SESSION['userRole']);
unset($_SESSION['userName']);

header("Location: ../index.php");

exit;
?>