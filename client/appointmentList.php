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

                <div class="panel-body">
                    <table id="grdMaster" class="table table-striped table-bordered table-list datagrid">
                    </table>
                </div>

                <div class="panel-footer"></div>
            </div>
        </div>

        <script>
            // ******************* Browser's definition ************************

            var controllerName = "appointment";
            var pageTitle = "Appointments";

            var fields = [
                {"name": "user_firstname", "visible": true, "caption": "Patient Firstname", "width": "10%"},
                {"name": "user_lastname", "visible": true, "caption": "Patient Lastname", "width": "20%"},
                {"name": "doctor_firstname", "visible": true, "caption": "Doctor Firstname", "width": "10%"},
                {"name": "doctor_lastname", "visible": true, "caption": "Doctor Lastname", "width": "20%"},
                {"name": "appointment_date", "visible": true, "caption": "Date", "width": "13%"},
                {"name": "appointment_time", "visible": true, "caption": "Time", "width": "7%"},
                {"name": "appointment_cncl_descr", "visible": true, "caption": "Cancelled", "width": "10%"},
                {"name": "appointment_state_descr", "visible": true, "caption": "State", "width": "10%"},
            ];

            var pageNav = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var dataTable = $('#grdMaster').dataTable(dpMaster, fields);

            var navMaster = $('#navMaster').dataTableNav(
                    controllerName,
                    dataTable,
                    $('.panel-footer')[0],
                    {'edit' : true, 'append': true, 'delete': true, 'view': false});

            wppApp.prepareBrowser();

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Browse ...

            if (wppApp.getUserRole() < 1) {
                navMaster.addFixedFilter(
                        {
                            "field": "appointment_patient_id",
                            "op": "=",
                            "value": wppApp.getUserId()
                        }
                );
            }

            // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            navMaster.browse();
        </script>
    </body>
</html>
