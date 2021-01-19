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

        <script src="js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="css/datepicker.min.css" />

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
                                <label class="control-label col-sm-3 required" for="appointment_patient_id">Patient :</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="appointment_patient_id" name="appointment_patient_id">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="appointment_doctor_id">Doctor :</label>
                                <div class="col-sm-9">          
                                    <select class="form-control" id="appointment_doctor_id" name="appointment_doctor_id">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="appointment_date">Date :</label>
                                <div class="col-sm-9">          
                                    <div class="input-group input-append date" id="appointment_date">
                                        <input type="text" class="form-control" name="date" />
                                        <span class="input-group-addon add-on" >
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>                            
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3 required" for="appointment_time">Tine :</label>
                                <div class="col-sm-9">          
                                    <select class="form-control" id="appointment_time"  name="appointment_time">
                                        <option>08:00</option>
                                        <option>09:00</option>
                                        <option>10:00</option>
                                        <option>11:00</option>
                                        <option>12:00</option>
                                        <option>13:00</option>
                                        <option>14:00</option>
                                        <option>15:00</option>
                                        <option>16:00</option>
                                        <option>17:00</option>
                                        <option>18:00</option>
                                        <option>19:00</option>
                                        <option>20:00</option>
                                        <option>21:00</option>
                                        <option>22:00</option>
                                        <option>23:00</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="appointment_reason">Reason :</label>
                                <div class="col-sm-9">          
                                    <textarea class="form-control" rows="3" id="appointment_reason" name="appointment_reason"></textarea>
                                </div>
                            </div>                            
                        </div>  
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="appointment_state">State :</label>
                                <div class="col-sm-9">          
                                    <select class="form-control" id="appointment_state"  name="appointment_state">
                                        <option value="0">PENDING</option>
                                        <option value="1">ACCEPTED</option>
                                        <option value="2">REJECTED</option>
                                        <option value="3">COMPLETED</option>
                                    </select>
                                </div>
                            </div>      
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="appointment_cncl">Cancelled :</label>
                                <div class="col-sm-9">          
                                    <select class="form-control" id="appointment_cncl"  name="appointment_cncl">
                                        <option value="0">NO</option>
                                        <option value="1">YES</option>
                                    </select>
                                </div>
                            </div>      
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="appointment_cncl_reason">Cancel reason :</label>
                                <div class="col-sm-9">          
                                    <textarea class="form-control" rows="3" id="appointment_cncl_reason" name="appointment_cncl_reason"></textarea>
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

            var controllerName = "appointment";
            var pageTitle = "Appointment";

            var onGetEditorData = function (dp) {
                var appointment_patient_id = null;

                if (dp.getRecordState() === 2) {
                    if (wppApp.getUserRole() < 1) {
                        appointment_patient_id = wppApp.getUserId();
                    }
                } else {
                    appointment_patient_id = dp.getFieldValueByName("appointment_patient_id");
                }

                var appointment_doctor_id = null;

                if (dp.getRecordState() === 2) {
                    if (getPageParamValue("appointment_doctor_id")) {
                        appointment_doctor_id = getPageParamValue("appointment_doctor_id");
                    }
                } else {
                    appointment_doctor_id = dp.getFieldValueByName("appointment_doctor_id");
                }

                if (dp.getRecordState() === 2) {
                    $("#appointment_date").find("input").val(getCurrentDate());
                } else {
                    $("#appointment_date").find("input").val(dp.getFieldValueByName("appointment_date"));
                }

                if (dp.getRecordState() === 2) {
                    if (getPageParamValue("appointment_time")) {
                        $("#appointment_time").val(getPageParamValue("appointment_time"));
                    } else {
                        $("#appointment_time").val("00:00");
                    }
                } else {
                    $("#appointment_time").val(dp.getFieldValueByName("appointment_time"));
                }

                if (dp.getRecordState() === 2) {
                    $("#appointment_state").val("0");
                } else {
                    $("#appointment_state").val(dp.getFieldValueByName("appointment_state"));
                }

                $("#appointment_reason").val(dp.getFieldValueByName("appointment_reason"));

                $("#appointment_cncl").val(dp.getFieldValueByName("appointment_cncl"));
                $("#appointment_cncl_reason").val(dp.getFieldValueByName("appointment_cncl_reason"));

                loadPatientOptions(appointment_patient_id);
                loadDoctorOptions(appointment_doctor_id);

                initControls();
            };

            var onValidateEditorData = function () {
                if (!$("#appointment_patient_id").val()) {
                    throw "Patient is required!";
                }

                if (!$("#appointment_doctor_id").val()) {
                    throw "Doctor is required!";
                }
                
                if (!$("#appointment_date").find("input").val()) {
                    throw "Date is required!";
                }
                
                if (!$("#appointment_time").val()) {
                    throw "Time is required!";
                }
            };

            var onPostEditorData = function (dp) {
                dp.setFieldValueByName("appointment_patient_id", $("#appointment_patient_id").val());
                dp.setFieldValueByName("appointment_doctor_id", $("#appointment_doctor_id").val());

                dp.setFieldValueByName("appointment_date", $("#appointment_date").find("input").val());
                dp.setFieldValueByName("appointment_time", $("#appointment_time").val());

                dp.setFieldValueByName("appointment_state", $("#appointment_state").val());

                dp.setFieldValueByName("appointment_reason", $("#appointment_reason").val());

                dp.setFieldValueByName("appointment_cncl", $("#appointment_cncl").val());
                dp.setFieldValueByName("appointment_cncl_reason", $("#appointment_cncl_reason").val());
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
                if (wppApp.getUserRole() < 1) {
                    $("#appointment_patient_id").attr("disabled", true);
                }

                if (wppApp.getUserRole() < 1 ||
                        $("#appointment_state").val() === "2" ||
                        $("#appointment_state").val() === "3") {
                    $("#appointment_state").attr("disabled", true);
                }

                // (FR41) A patient can modify an appointment only if it is pending

                if (wppApp.getUserRole() < 1 &&
                        $("#appointment_state").val() !== "0") {
                    $("#appointment_doctor_id").attr("disabled", true);
                    $('#appointment_date').find('input').attr("disabled", true);
                    $('#appointment_date').find('span').css('display', 'none');
                    $("#appointment_time").attr("disabled", true);
                    $("#appointment_reason").attr("disabled", true);
                }

                // (FR30) An appointment can be canceling only if it is "pending" or "accepted"

                if ($("#appointment_state").val() === "2" ||
                        $("#appointment_state").val() === "3") {
                    $("#appointment_cncl").attr("disabled", true);
                }

                if ($("#appointment_cncl").val() === "1") {
                    $("#appointment_cncl_reason").attr("disabled", false);
                } else {
                    $("#appointment_cncl_reason").attr("disabled", true);
                }
            }

            function updateControls() {
                if ($("#appointment_cncl").val() === "1") {
                    $("#appointment_cncl_reason").attr("disabled", false);
                } else {
                    $("#appointment_cncl_reason").attr("disabled", true);
                }
            }

            function loadPatientOptions(app_patient_id) {
                wppApp.serverCall('get', this,
                        {
                            'controller': 'user',
                            'method': 'browse',
                            'params': {
                                where:
                                        [
                                            {
                                                'field': 'user_type',
                                                'op': '=',
                                                'value': '0'
                                            }
                                        ]
                            },
                            orderby: 'user_lastname, user_firstname'
                        },
                        function (callerObj, responseJson) {
                            var patientPacket = new DataPacket();

                            patientPacket.setData(responseJson);

                            var patientSelect = document.getElementById('appointment_patient_id');

                            for (var iRow = 0; iRow < patientPacket.getRecordCount(); iRow++) {
                                var option = document.createElement('option');

                                option.value = patientPacket.getFieldValueByName('user_id', iRow);
                                option.innerHTML = patientPacket.getFieldValueByName('user_lastname', iRow) + ' ' +
                                        patientPacket.getFieldValueByName('user_firstname', iRow);

                                patientSelect.appendChild(option);
                            }

                            $("#appointment_patient_id").val(app_patient_id);
                        },
                        function (responseText) {
                        }
                );
            }

            function loadDoctorOptions(app_doctor_id) {
                wppApp.serverCall('get', this,
                        {
                            'controller': 'doctor',
                            'method': 'browse',
                            'params': {
                                where: null,
                                orderby: 'doctor_specialization, doctor_lastname, doctor_firstname'
                            }
                        },
                        function (callerObj, responseJson) {
                            var doctorPacket = new DataPacket();

                            doctorPacket.setData(responseJson);

                            var doctorSelect = document.getElementById('appointment_doctor_id');

                            for (var iRow = 0; iRow < doctorPacket.getRecordCount(); iRow++) {
                                var option = document.createElement('option');

                                option.value = doctorPacket.getFieldValueByName('doctor_id', iRow);
                                option.innerHTML = doctorPacket.getFieldValueByName('doctor_specialization', iRow) + ' - ' +
                                        doctorPacket.getFieldValueByName('doctor_lastname', iRow) + ' ' +
                                        doctorPacket.getFieldValueByName('doctor_firstname', iRow);

                                doctorSelect.appendChild(option);
                            }

                            $("#appointment_doctor_id").val(app_doctor_id);
                        },
                        function (responseText) {
                        }
                );
            }

            $('#appointment_date')
                    .datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy'
                    });

            $('#appointment_cncl').change(updateControls);
        </script>

    </body>
</html>
