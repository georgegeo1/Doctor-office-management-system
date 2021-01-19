<!DOCTYPE html>
<html>
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
                <div class="panel-body" >
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2 required" for="options_mail_host">Mail Host :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="options_mail_host" name="options_mail_host">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 required" for="options_mail_username">Mail Username :</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="options_mail_username" name="options_mail_username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 required" for="options_mail_password">Mail Password :</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="options_mail_password" name="options_mail_password">
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

            var controllerName = "options";
            var pageTitle = "System Options";

            var onGetEditorData = function (dp) {
                $("#options_mail_host").val(dp.getFieldValueByName("options_mail_host"));
                $("#options_mail_username").val(dp.getFieldValueByName("options_mail_username"));
                $("#options_mail_password").val(dp.getFieldValueByName("options_mail_password"));
            };

            var onValidateEditorData = function () {
                if (!$("#options_mail_host").val()) {
                    throw "Mail Host is required!";
                }

                if (!$("#options_mail_username").val()) {
                    throw "Mail Username is required!";
                }

                if (!$("#options_mail_password").val()) {
                    throw "Mail Password is required!";
                }
            };

            var onPostEditorData = function (dp) {
                dp.setFieldValueByName("options_mail_host", $("#options_mail_host").val());
                dp.setFieldValueByName("options_mail_username", $("#options_mail_username").val());
                dp.setFieldValueByName("options_mail_password", $("#options_mail_password").val());
            };

            var pageNav = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var dataEditorNav = $("#dataMaster").dataEditorNav(
                    $('.panel-footer')[0],
                    controllerName,
                    dpMaster,
                    1, // options table has - always - obly one record
                    getPageParamValue("view"),
                    onGetEditorData,
                    onPostEditorData,
                    onValidateEditorData
                    );

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Load ...
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            dataEditorNav.load();
        </script>
    </body>
</html>
