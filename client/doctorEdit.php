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
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="doctor_firstname">Firstname:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="doctor_firstname" name="doctor_firstname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="doctor_lastname">Lastname:</label>
                                <div class="col-sm-9">          
                                    <input type="text" class="form-control" id="doctor_lastname" name="doctor_lastname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="doctor_specialization">Specialization:</label>
                                <div class="col-sm-9">          
                                    <input type="text" class="form-control" id="doctor_specialization" name="doctor_specialization">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_cv">CV:</label>
                                <div class="col-sm-9">          
                                    <textarea class="form-control" rows="5" id="doctor_cv" name="doctor_cv"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="doctor_photo">Photo (jpeg):</label>
                                <div class="col-sm-9">          
                                    <img class="img-responsive" id="doctor_photo" name="doctor_photo" src="noimage"></img>
                                </div>
                                <br/>
                                <div class="col-sm-9">          
                                    <input type="file" name="file" id="file" accept='image/jpeg' >
                                </div>
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

            var controllerName = "doctor";
            var pageTitle = "Doctor";

            var onGetEditorData = function (dp) {
                $("#doctor_firstname").val(dp.getFieldValueByName("doctor_firstname"));
                $("#doctor_lastname").val(dp.getFieldValueByName("doctor_lastname"));
                $("#doctor_specialization").val(dp.getFieldValueByName("doctor_specialization"));
                $("#doctor_cv").val(dp.getFieldValueByName("doctor_cv"));

                $("#doctor_photo").attr("src", "data:image/jpeg;base64, " + dp.getFieldValueByName("doctor_photo"));

                initControls();
            };

            var onValidateEditorData = function () {
                if (!$("#doctor_firstname").val()) {
                    throw "First Name is required!";
                }

                if (!$("#doctor_lastname").val()) {
                    throw "Last Name is required!";
                }

                if (!$("#doctor_specialization").val()) {
                    throw "Specialization is required!";
                }
            };

            var onPostEditorData = function (dp) {
                dp.setFieldValueByName("doctor_firstname", $("#doctor_firstname").val())
                dp.setFieldValueByName("doctor_lastname", $("#doctor_lastname").val());
                dp.setFieldValueByName("doctor_specialization", $("#doctor_specialization").val());
                dp.setFieldValueByName("doctor_cv", $("#doctor_cv").val());

                var photo = $("#doctor_photo").attr("src");
                photo = photo.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
                dp.setFieldValueByName("doctor_photo", photo);
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
                if (getPageParamValue("view")) {
                    $('.form-control').attr('disabled', true);

                    $('#file').hide();
                }
            }

            function selectImage(e) {
                $('#doctor_photo').attr('src', e.target.result);
            }

            $('#file').change(function () {
                var reader = new FileReader();
                reader.onload = selectImage;
                reader.readAsDataURL(this.files[0]);
            });
        </script>

    </body>
</html>
