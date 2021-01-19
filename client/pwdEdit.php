<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="js/jquery.min.js"></script>

        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <script src="js/wpp.lib.js"></script>
        <script src="js/wpp.app.js"></script>
        <script src="ctrls/wpp.ui.data.js"></script>
        <script src="ctrls/wpp.data.js"></script>

        <link rel="stylesheet" href="css/wpp.ui.css">

        <script>
            var dpMaster = new DataPacket();
        </script>
    </head>

    <body>
        <div class="container wpp-editor-container">
            <nav class="navbar wpp-page-header">
                <div class="container-fluid" id="navPage">
                </div>
            </nav>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <nav class="navbar wpp-toolbar">
                        <div class="container-fluid" id="dataMaster">
                        </div>
                    </nav>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_password1">New password :</label>
                            <div class="col-sm-10">          
                                <input type="password" class="form-control" id="user_password1" name="user_password1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_password2">New password (type again) :</label>
                            <div class="col-sm-10">          
                                <input type="password" class="form-control" id="user_password2" name="user_password2">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>

        <script>
            // ******************* Editor's definition *************************

            var controllerName = "user";
            var pageTitle = "Change Password";

            var onGetEditorData = function (dp) {
            };

            var onPostEditorData = function (dp) {
                if ($("#user_password1").val() !== $("#user_password2").val()) {
                    alert("Passwords don't match!");
                    throw new Error("Passwords don't match!");
                }

                dp.setFieldValueByName("_state", 1);
                dp.setFieldValueByName("user_password", $("#user_password1").val())
            };

            var pageNav = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var dataEditorNav = $("#dataMaster").dataEditorNav(
                    $('.panel-footer')[0],
                    controllerName,
                    dpMaster,
                    getPageParamValue("id"),
                    getPageParamValue("view"),
                    onGetEditorData,
                    onPostEditorData
                    );

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Load ...
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            dataEditorNav.load();
        </script>
    </body>
</html>
