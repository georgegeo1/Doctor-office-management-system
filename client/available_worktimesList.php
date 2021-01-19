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

        <script src="js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="css/datepicker.min.css" />

        <link rel="stylesheet" href="css/wpp.ui.css">

        <script>
            var dpMaster = new DataPacket();
        </script>
    </head>

    <body>

        <div class="container wpp-browser-container">
            <nav class="navbar wpp-page-header">
                <div class="container-fluid" id="navPage">
                </div>
            </nav>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <nav class="navbar wpp-toolbar">
                        <div class="container-fluid" id="navMaster">
                        </div>
                    </nav>
                </div>

                <div class="panel-heading">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Date from :</label>
                            <div class="col-sm-3">          
                                <div class="input-group input-append date" id="fromDate">
                                    <input type="text" class="form-control" placeholder="Date from ..."/>
                                    <span class="input-group-addon add-on">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>                            
                            </div>
                            <label class="control-label col-sm-2">Date to :</label>
                            <div class="col-sm-3">          
                                <div class="input-group input-append date" id="toDate">
                                    <input type="text" class="form-control" placeholder="Date to ..."/>
                                    <span class="input-group-addon add-on">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>                            
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Specialization :</label>
                            <div class="col-sm-3">          
                                <input type="text" class="form-control" id="preferableSpecialization"
                                       placeholder="Specialization ..." />
                            </div>
                        </div>
                    </form>
                </div>

                <div class="panel-body">
                    <table id="grdMaster" class="table table-striped table-bordered table-list datagrid">
                    </table>
                </div>

                <div class="panel-footer"></div>
            </div>
        </div>

        <script>
            // ******************* Browser's definition ************************

            var controllerName = "available_worktimes";
            var pageTitle = "Search for available hours (the search results are sorted by favorite Doctor)";

            var fields = [
                {"name": "doctor_worktime_id", "visible": false},
                {"name": "doctor_worktime_doctor_id", "visible": false},
                {"name": "doctor_worktime_date", "visible": true, "caption": "Date", "width": "10%"},
                {"name": "doctor_specialization", "visible": true, "caption": "Specialization", "width": "30%"},
                {"name": "doctor_lastname", "visible": true, "caption": "Lsst Name", "width": "30%"},
                {"name": "doctor_firstname", "visible": true, "caption": "First Name", "width": "20%"},
                {"name": "doctor_worktime_time", "visible": true, "caption": "Time", "width": "10%"}
            ]

            var dataTable = $('#grdMaster').dataTable(dpMaster, fields);

            var navPage = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var navMaster = $('#navMaster').dataTableNav(
                    controllerName,
                    dataTable,
                    $('.panel-footer')[0],
                    {'edit': false, 'append': false, 'delete': false, 'view': false},
                    getExtraFilters);

            wppApp.prepareBrowser();

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Browse ...

            function getExtraFilters() {
                var extraFilters = [];

                if ($("#fromDate").find("input").val()) {
                    extraFilters.push(
                            {
                                "field": "doctor_worktime_date",
                                "op": ">=",
                                "value": $("#fromDate").find("input").val()
                            }
                    );
                }

                if ($("#toDate").find("input").val()) {
                    extraFilters.push(
                            {
                                "field": "doctor_worktime_date",
                                "op": "<=",
                                "value": $("#toDate").find("input").val()
                            }
                    );
                }

                if ($("#preferableSpecialization").val()) {
                    extraFilters.push(
                            {
                                "field": "doctor_specialization",
                                "op": "like",
                                "value": $("#preferableSpecialization").val() + '%'
                            }
                    );
                }

                return extraFilters;
            }

            navMaster.addFixedOrderBy(
                    'doctor_worktime_date, ' +
                    'getDoctorAppCntOfPatient(doctor_worktime_doctor_id, 65) desc, ' +
                    'doctor_worktime_time');

            if (wppApp.getUserRole() < 1) {
                navMaster.addButton('left', "btn-insert-appointment", "glyphicon glyphicon-new-window", "Insert an Appointment",
                        "disabled",
                        function (e) {
                            var iSelectedRowIndex = navMaster.getDataTable().getSelectedRowIndex();

                            var selectedRow = navMaster.getDataPacket().getRecord(iSelectedRowIndex);

                            var doctor_id = selectedRow['doctor_worktime_doctor_id'];
                            var appointment_time = selectedRow['doctor_worktime_time'];

                            window.location.replace("appointmentEdit.php" +
                                    "?appointment_doctor_id=" + doctor_id +
                                    "&appointment_time=" + appointment_time +
                                    "&btnClose=2");
                        }
                );

                dataTable.OnSelectedRowIndexChange.addListener(navMaster,
                        function (eventData) {
                            if (eventData >= 0) {
                                var btn = navMaster.getButton("btn-insert-appointment");
                                btn.className = btn.className.replace("disabled", "active");
                            } else {
                                var btn = navMaster.getButton("btn-insert-appointment");
                                btn.className = btn.className.replace("active", "disabled");
                            }
                        }
                );
            }

            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            navMaster.browse();
        </script>

        <script>
            $('#fromDate')
                    .datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy'
                    });

            $('#toDate')
                    .datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy'
                    });
        </script>
    </body>
</html>
