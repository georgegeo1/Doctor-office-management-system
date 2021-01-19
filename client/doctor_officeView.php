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

        <link href="css/font-awesome.min.css" rel="stylesheet">

        <script>
            var dpMaster = new DataPacket();
        </script>
    </head>

    <body>
        <div class="container wpp-editor-container">
            <nav class="navbar wpp-page-header" style="display: none">
                <div class="container-fluid" id="navPage">
                </div>
            </nav>

            <h2>Welcome to <label id="doctor_office_name"></label> home page</h2>
            <div class="panel panel-default">
                <div class="panel-heading" style="display: none">
                    <nav class="navbar wpp-toolbar">
                        <div class="container-fluid" id="dataMaster">
                        </div>
                    </nav>
                </div>
                <div class="panel-body" >
                    <form class="form-horizontal">
                        <div class="col-sm-6">
                            <img class="img-responsive" src="images/medical_center.jpg" 
                                 style="height: 300px" alt="Medical center"> 
                        </div>
                        <div class="col-sm-6">
                            <h2>Contact info : </h2>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_office_contact_address">Address :</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="doctor_office_contact_address" name="doctor_office_contact_address"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_office_contact_zip">Zip :</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="doctor_office_contact_zip" name="doctor_office_contact_zip"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_office_contact_phone">Phone :</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="doctor_office_contact_phone" name="doctor_office_contact_phone"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_office_contact_email">e-mail :</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="doctor_office_contact_email" name="doctor_office_contact_email"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer" style="height: 60px" >
                    <div class="text-center center-block">
                        <a href="https://www.facebook.com" target="none">
                            <i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                        </a>
                        <a href="https://twitter.com" target="none">
                            <i id="social-tw" class="fa fa-twitter-square fa-3x social"></i>
                        </a>
                        <a href="https://plus.google.com" target="none">
                            <i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i>
                        </a>
                        <a id="a_mailto" target="none">
                            <i id="social-em" class="fa fa-envelope-square fa-3x social"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // ******************* Editor's definition *************************

            var controllerName = "doctor_office";
            var pageTitle = "Welcone to Doctor Office System";

            var onGetEditorData = function (dp) {
                $("#doctor_office_name")[0].innerHTML = dp.getFieldValueByName("doctor_office_name");
                $("#doctor_office_contact_address")[0].innerHTML = dp.getFieldValueByName("doctor_office_contact_address");
                $("#doctor_office_contact_phone")[0].innerHTML = dp.getFieldValueByName("doctor_office_contact_phone");
                $("#doctor_office_contact_zip")[0].innerHTML = dp.getFieldValueByName("doctor_office_contact_zip");
                $("#doctor_office_contact_email")[0].innerHTML = dp.getFieldValueByName("doctor_office_contact_email");
                
                $("#a_mailto").attr("href", "mailto:" + dp.getFieldValueByName("doctor_office_contact_email"));
            };

            var onPostEditorData = function (dp) {
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
                    onPostEditorData
                    );

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Load ...
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            dataEditorNav.load();
        </script>
    </body>
</html>
