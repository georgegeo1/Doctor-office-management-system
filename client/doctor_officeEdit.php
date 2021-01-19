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
                            <label class="control-label col-sm-2 required" for="doctor_office_name">Name :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doctor_office_name" name="doctor_office_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="doctor_office_contact_address">Address :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doctor_office_contact_address" name="doctor_office_contact_address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="doctor_office_contact_zip">Zip :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doctor_office_contact_zip" name="doctor_office_contact_zip">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="doctor_office_contact_phone">Phone :</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doctor_office_contact_phone" name="doctor_office_contact_phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2 required" for="doctor_office_contact_email">e-mail :</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="doctor_office_contact_email" name="doctor_office_contact_email">
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

            var controllerName = "doctor_office";
            var pageTitle = "Contact Info Management";

            var onGetEditorData = function (dp) {
                $("#doctor_office_name").val(dp.getFieldValueByName("doctor_office_name"));
                $("#doctor_office_contact_address").val(dp.getFieldValueByName("doctor_office_contact_address"));
                $("#doctor_office_contact_phone").val(dp.getFieldValueByName("doctor_office_contact_phone"));
                $("#doctor_office_contact_zip").val(dp.getFieldValueByName("doctor_office_contact_zip"));
                $("#doctor_office_contact_email").val(dp.getFieldValueByName("doctor_office_contact_email"));
            };

            var onValidateEditorData = function () {
                if (!$("#doctor_office_name").val()) {
                    throw "Name is required!";
                }

                if (!$("#doctor_office_contact_email").val()) {
                    throw "e-mail is required!";
                }
            };

            var onPostEditorData = function (dp) {
                dp.setFieldValueByName("doctor_office_name", $("#doctor_office_name").val());
                dp.setFieldValueByName("doctor_office_contact_address", $("#doctor_office_contact_address").val());
                dp.setFieldValueByName("doctor_office_contact_phone", $("#doctor_office_contact_phone").val());
                dp.setFieldValueByName("doctor_office_contact_zip", $("#doctor_office_contact_zip").val());
                dp.setFieldValueByName("doctor_office_contact_email", $("#doctor_office_contact_email").val());
            };

            var pageNav = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var dataEditorNav = $("#dataMaster").dataEditorNav(
                    $('.panel-footer')[0],
                    controllerName,
                    dpMaster,
                    1,
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
