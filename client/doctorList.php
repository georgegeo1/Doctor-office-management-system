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

            var controllerName = "doctor";
            var pageTitle = "Doctors";

            var fields = [
                {"name": "doctor_id", "visible": false},
                {"name": "doctor_firstname", "visible": true, "caption": "First Name", "width": "30%"},
                {"name": "doctor_lastname", "visible": true, "caption": "Last Name", "width": "40%"},
                {"name": "doctor_specialization", "visible": true, "caption": "Specialization", "width": "30%"}
            ]

            var dataTable = $('#grdMaster').dataTable(dpMaster, fields);

            var navPage = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var navMaster = $('#navMaster').dataTableNav(
                    controllerName,
                    dataTable,
                    $('.panel-footer')[0],
                    {
                        'edit': wppApp.getUserRole() >= 1,
                        'append': wppApp.getUserRole() >= 1,
                        'delete': wppApp.getUserRole() >= 1,
                        'view': wppApp.getUserRole() < 1
                    }
            );

            wppApp.prepareBrowser();

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Browse ...

            if (wppApp.getUserRole() >= 1) {
                navMaster.addButton('left', "btn-worktime", "glyphicon-time", "Edit selected doctor's Working Time for a Week",
                        "disabled",
                        function (e) {
                            var iSelectedRowIndex = navMaster.getDataTable().getSelectedRowIndex();

                            var selectedRow = navMaster.getDataPacket().getRecord(iSelectedRowIndex);

                            var id = selectedRow[navMaster.getDataPacket().getDatasetKeyFieldName()];

                            window.location.replace("doctor_worktimeEdit.php" + "?id=" + id + "&btnClose=2");
                        }
                );

                dataTable.OnSelectedRowIndexChange.addListener(navMaster,
                        function (eventData) {
                            if (eventData >= 0) {
                                var btn = navMaster.getButton("btn-worktime");
                                btn.className = btn.className.replace("disabled", "active");
                            } else {
                                var btn = navMaster.getButton("btn-worktime");
                                btn.className = btn.className.replace("active", "disabled");
                            }
                        }
                );
            }

            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            navMaster.browse();
        </script>
    </body>
</html>
