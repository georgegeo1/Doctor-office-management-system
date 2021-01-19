<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="js/jquery.min.js"></script>

        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <link rel="stylesheet" href="css/wpp.sign.css">
    </head>

    <body>
        <div class="container">
            <form class="form-signin" action="../server/signup_action.php" method="POST">
                <h2 class="form-signin-heading">Please, sign up</h2>

                <?php
                if (isset($_GET["errorMessage"])) {
                    echo "<p style='color: red'> " . $_GET["errorMessage"] . "</p>";
                }
                else if (isset($_GET["message"])) {
                    echo "<p style='color: green'> " . $_GET["message"] . "</p>";
                }
                ?>

                <div class="form-group">
                    <label for="userName">E-mail (username) :</label>
                    <input type="email" name="userName" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password1">Password :</label>
                    <input type="password" name="password1" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password2">Password (type again) :</label>
                    <input type="password" name="password2" class="form-control" required>
                </div>

                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
            </form>
        </div>

    </body>
</html>