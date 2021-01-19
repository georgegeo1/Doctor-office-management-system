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
                            <label class="control-label col-sm-2 required" for="user_name">Username :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="user_name" 
                                       name="user_name" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 required" for="user_type">Role:</label>
                            <div class="col-sm-10">          
                                <select class="form-control" id="user_type" name="user_type">
                                    <option value="0">PATIENT</option>
                                    <option value="1">SECRETARY</option>
                                    <option value="2">ADMINISTRATOR</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="user_active_div">
                            <label class="control-label col-sm-2 required" for="user_active">Active:</label>
                            <div class="col-sm-10">          
                                <select class="form-control" id="user_active" name="user_active">
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_firstname">First name :</label>
                            <div class="col-sm-10">          
                                <input type="text" class="form-control" id="user_firstname" 
                                       name="user_firstname" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_lastname">Last name :</label>
                            <div class="col-sm-10">          
                                <input type="text" class="form-control" id="user_lastname" 
                                       name="user_lastname">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_address">Address :</label>
                            <div class="col-sm-10">          
                                <input type="text" class="form-control" id="user_address" name="user_address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_zip">Zip :</label>
                            <div class="col-sm-10">          
                                <input type="text" class="form-control" id="user_zip" name="user_zip">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="user_phone">Phone :</label>
                            <div class="col-sm-10">          
                                <input type="text" class="form-control" id="user_phone" name="user_phone">
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
            var pageTitle = "System User";

            var onGetEditorData = function (dp) {
                $("#user_name").val(dp.getFieldValueByName("user_name"));
                $("#user_type").val(dp.getFieldValueByName("user_type"));
                $("#user_active").val(dp.getFieldValueByName("user_active"));
                $("#user_firstname").val(dp.getFieldValueByName("user_firstname"));
                $("#user_lastname").val(dp.getFieldValueByName("user_lastname"));
                $("#user_address").val(dp.getFieldValueByName("user_address"));
                $("#user_phone").val(dp.getFieldValueByName("user_phone"));
                $("#user_zip").val(dp.getFieldValueByName("user_zip"));
                
                initControls();
            };

            var onValidateEditorData = function () {
                if (!$("#user_name").val()) {
                    throw "Username is required!";
                }

                if (!$("#user_active").val()) {
                    throw "Active is required!";
                }
            };

            var onPostEditorData = function (dp) {
                dp.setFieldValueByName("user_name", $("#user_name").val());
                dp.setFieldValueByName("user_active", $("#user_active").val());
                dp.setFieldValueByName("user_type", $("#user_type").val());
                dp.setFieldValueByName("user_firstname", $("#user_firstname").val());
                dp.setFieldValueByName("user_lastname", $("#user_lastname").val());
                dp.setFieldValueByName("user_address", $("#user_address").val());
                dp.setFieldValueByName("user_phone", $("#user_phone").val());
                dp.setFieldValueByName("user_zip", $("#user_zip").val());
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
                    onPostEditorData,
                    onValidateEditorData
                    );

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Load ...
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            dataEditorNav.load();
        </script>
        
        <script>
            function initControls() {
                if (wppApp.getUserRole() < 2) { // only an administrator
                                                // can change those fields ...
                    $("#user_name").attr("disabled", true);
                    $("#user_active").attr("disabled", true);
                }
            }
        </script>
            
    </body>
</html>
