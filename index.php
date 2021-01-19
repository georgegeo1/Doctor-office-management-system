<?php
session_start();

$userId = null;
$userName = null;
$userRole = -1;

if (!array_key_exists('userName', $_SESSION)) {
    header("location:client/signin.php");
} else {
    $userId = $_SESSION['userId'];
    $userName = $_SESSION['userName'];
    $userRole = (int) $_SESSION['userRole'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Doctor Office System</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="client/js/jquery.min.js"></script>

        <script src="client/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="client/css/bootstrap.min.css">

        <script src="client/js/wpp.app.js"></script>

        <style>
            .framecontent{
                border: none;
                width: 100%;
            }

            /* Remove the navbar's default margin-bottom and rounded borders */ 
            .navbar {
                margin-bottom: 0;
                border-radius: 0;
            }

            /* Set gray background color and 100% height */
            .sidenav {
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
                background-color: #f1f1f1;
                height: 100%;
            }

            /* Set black background color, white text and some padding */
            footer {
                background-color: #555;
                color: #fff;
                padding: 5px;
            }

            .main {
                padding-right: 0px;
                padding-left: 10px;
                height: 100%;
            }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <p class="navbar-brand">Doctor Office System</p>
                </div>
                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span class="glyphicon glyphicon-user"></span><?= " " . $userName ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="client/userEdit.php?id=<?= $userId ?>&btnClose=1" target='main_frame'>User Profile</a>
                            </li>
                            <li>
                                <a href="client/pwdEdit.php?id=<?= $userId ?>&btnClose=1" target='main_frame'>Change Password</a>
                            </li>
                        </ul>
                    </li>                    

                    <li>
                        <a href="server/signout_action.php" onclick="return confirm('Are you sure?')">
                            <span class="glyphicon glyphicon-log-out"></span> Sign Out 
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid">    
            <div class="row content">
                <div class="col-sm-3 sidenav">
                    <table>
                        <?php
                        // create menu content ...
                        
                        echo
                        "<tr>
                            <td>
                                <span class='glyphicon glyphicon-home text-primary'></span>
                                <a href='client/doctor_officeView.php' target='main_frame'>Home Page</a>
                            </td>
                        </tr>
                        ";

                        echo "<tr><td></br></td></tr>";

                        if ($userRole >= 1) { // if current user is "secretary" or "administrator" ...
                            echo
                            "<tr>
                            <td>
                                <span class='glyphicon glyphicon-th-list text-primary'></span>
                                <a href='client/doctor_officeEdit.php' target='main_frame'>Contact Info</a>
                            </td>
                        </tr>";
                        }

                        echo
                        "<tr>
                            <td>
                                <span class='glyphicon glyphicon-th-list text-primary'></span>
                                <a href='client/doctorList.php' target='main_frame'>Doctors</a>
                            </td>
                        </tr>";

                        echo
                        "<tr>
                            <td>
                                <span class='glyphicon glyphicon-th-list text-primary'></span>
                                <a href='client/available_worktimesList.php' target='main_frame'>Search for an appointment</a>
                            </td>
                        </tr>
                        ";

                        if ($userRole === 0) { // if current user is "patient" ...
                            $appointmentsTitle = 'My Appointments';
                        } else {               // else ...
                            $appointmentsTitle = 'Appointments';
                        }

                        echo
                        "<tr>
                            <td>
                                <span class='glyphicon glyphicon-th-list text-primary'></span>
                                <a href='client/appointmentList.php' target='main_frame'>$appointmentsTitle</a>
                            </td>
                        </tr>
                        ";

                        if ($userRole === 2) { // if current user is "administrator" ...
                            echo "<tr><td></br></td></tr>";

                            echo
                            "<tr>
                            <td>
                                <span class='glyphicon glyphicon-cog text-primary'></span>
                                <a href='client/optionsEdit.php' target='main_frame'>System Options</a>
                            </td>
                        </tr>";

                            echo
                            "<tr>
                            <td>
                                <span class='glyphicon glyphicon-th-list text-primary'></span>
                                <a href='client/userList.php' target='main_frame'>User Management</a>
                            </td>
                        </tr>";
                        }

                        echo "<tr><td></br></td></tr>";
                        echo
                        "<tr>
                            <td>
                                <span class='glyphicon glyphicon-info-sign text-primary'></span>
                                <a href='client/help/help$userRole.pdf' target='main_frame'>Help</a>
                            </td>
                        </tr>";
                        ?>                        

                    </table>
                </div>
                <div class="col-sm-9 main"> 
                    <iframe id="main_frame" name="main_frame" class="framecontent" width="100%"></iframe>
                </div>
            </div>
        </div>

        <footer class="container-fluid">
            <em>
                Web Programming Project
            </em>
        </footer>

        <script>
            wppApp.setBaseUrl(location.href.replace("index.php", ""));
            wppApp.setUserId("<?= $userId ?>");
            wppApp.setUserName("<?= $userName ?>");
            wppApp.setUserRole("<?= $userRole ?>");

            var frameParent = $('.row.content')[0];

            frameParent.style.height =
                    $('html')[0].clientHeight
                    - $('footer')[0].offsetHeight
                    - $('.navbar')[0].offsetHeight + "px";

            var mainFrame = $('#main_frame')[0];

            mainFrame.style.height = frameParent.style.height;

            wppApp.go("_home");
        </script>
    </body>
</html>
