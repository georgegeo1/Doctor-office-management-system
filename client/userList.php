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

            var controllerName = "user";
            var pageTitle = "System Users";

            var fields = [
                {"name": "user_name", "visible": true, "caption": "Username", "width": "10%"},
                {"name": "user_firstname", "visible": true, "caption": "First Name", "width": "30%"},
                {"name": "user_lastname", "visible": true, "caption": "Last Name", "width": "30%"},
                {"name": "user_type_descr", "visible": true, "caption": "Role", "width": "20%"},
                {"name": "user_active_descr", "visible": true, "caption": "Active", "width": "10%"}
            ]

            var pageNav = $('#navPage').pageNav(controllerName, pageTitle,
                    getPageParamValue("btnClose"));

            var dataTable = $('#grdMaster').dataTable(dpMaster, fields);

            var navMaster = $('#navMaster').dataTableNav(
                    controllerName,
                    dataTable,
                    $('.panel-footer')[0],
                    {
                        'edit': wppApp.getUserRole() > 1,
                        'append': wppApp.getUserRole() > 1,
                        'delete': wppApp.getUserRole() > 1,
                        'view': wppApp.getUserRole() <= 1
                    }
            );

            wppApp.prepareBrowser();

            //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            // Placeholder for special things before Browse ...

            $(".btn-delete").hide();

            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            navMaster.browse();
        </script>
    </body>
</html>
