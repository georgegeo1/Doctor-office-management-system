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
            <form class="form-signin" action="../server/signin_action.php" method="POST">
                <h2 class="form-signin-heading">Please, sign in</h2>

                <?php
                if (isset($_GET["errorMessage"])) {
                    echo "<p style='color: red'> " . $_GET["errorMessage"] . "</p>";
                }
                ?>
                
                <div class="form-group">
                    <label for="userName">Username :</label>
                    <input type="text" name="userName" class="form-control" 
                           required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password :</label>
                    <input type="password" name="password" class="form-control" 
                           required>
                </div>

                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                
                <div class="form-group signup">
                    <span style="float: right">Don't have an account? <a href="signup.php">Sign up here</a></span>
                </div>
            </form>
        </div>

    </body>
</html>