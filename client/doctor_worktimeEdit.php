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

        <style>
            .form-control {
                padding: 6px 6px;
            }

            .row.head {
                background-color:  #d3e0e9;
                font-weight:  bold;
            }

            .row.odd {
                background-color:  linen;
            }

            .col-sm-1.date {
                padding-left: 5px;
                padding-right: 5px;
            }
        </style>

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
                <div class="panel-heading">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Select the fist date (Monday) of the week :</label>
                            <div class="col-sm-3">          
                                <div class="input-group input-append date" id="worktime_first_weekdate">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-addon add-on">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>                            
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="container-fluid" id="worktime_table">
                        <div class="row head">
                            <div class="col-sm-2 time">
                                <p class="form-control-static">Time</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Monday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Tuesday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Wednesday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Thrusday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Friday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Saturday</p>
                            </div>
                            <div class="col-sm-1 date">
                                <p class="form-control-static">Sunday</p>
                            </div>
                        </div>
                        <?php for ($i = 8; $i <= 23; $i++) { ?>
                            <div class="row <?= ($i % 2 === 0 ? 'even' : 'odd') ?>">
                                <div class="col-sm-2 time">
                                    <p class="form-control-static"><?= ($i <= 9 ? '0' : '') ?><?= $i ?>:00</p>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>0"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>1"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>2"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>3"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>4"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>5"><option>NO</option><option>YES</option></select>
                                </div>
                                <div class="col-sm-1 date">
                                    <select class="form-control selection" id="w<?= ($i <= 9 ? '0' : '') ?><?= $i ?>6"><option>NO</option><option>YES</option></select>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </div>

            <script>
                // ******************* Editor's definition *************************

                var controllerName = "doctor_worktime";
                var pageTitle = "Doctor's Working Time for a Week";

                var onGetEditorData = function (dp) {
                    // initialize selectors

                    var selectors = $('.selection');

                    selectors.css('background-color', '#fff');

                    for (var i = 0; i < selectors.length; i++) {
                        var selector = selectors[i];

                        selector.value = 'NO';
                    }

                    // set selectors fot the given week 

                    var firstDateAsStr = $("#worktime_first_weekdate").find("input").val();
                    var firstDate = wppGetDateFromStr(firstDateAsStr);

                    for (var iRow = 0; iRow < dp.getRecordCount(); iRow++) {
                        var workDateAsStr = dp.getFieldValueByName('doctor_worktime_date', iRow);
                        var workDate = wppGetDateFromStr(workDateAsStr);

                        var workTime = dp.getFieldValueByName('doctor_worktime_time', iRow);

                        var selectId = 'w' + workTime.substr(0, 2) +
                                wppDateDiffInDays(firstDate, workDate);

                        $('#' + selectId).val('YES');
                        $('#' + selectId).css('background-color', 'lightgreen');
                    }
                };

                var onPostEditorData = function (dp) {
                    // delete all current "worktime" records

                    for (var iRow = 0; iRow < dp.getRecordCount(); iRow++) {
                        dp.setFieldValueByName("_state", 3, iRow);
                    }

                    // create new "worktime" records where selection is "YES"

                    var firstDateAsStr = $("#worktime_first_weekdate").find("input").val();
                    var firstDate = wppGetDateFromStr(firstDateAsStr);

                    var selectors = $('.selection');

                    for (var i = 0; i < selectors.length; i++) {
                        var selector = selectors[i];

                        if (selector.value === 'YES') {
                            var workTime = selector.id.substr(1, 2) + ':00';

                            var days = selector.id.substr(3);

                            var workDate = wppAddDaysToDate(firstDate, days);
                            var workDateAsStr = wppGetStrFromDate(workDate);

                            var record = dp.addRecord();
                            record['doctor_worktime_date'] = workDateAsStr;
                            record['doctor_worktime_time'] = workTime;
                            record['doctor_worktime_doctor_id'] = getPageParamValue("id");
                        }
                    }
                };

                var pageNav = $('#navPage').pageNav('doctor', pageTitle,
                        getPageParamValue("btnClose"));

                var dataEditorNav = $("#dataMaster").dataEditorNav(
                        $('.panel-footer')[0],
                        controllerName,
                        dpMaster,
                        getPageParamValue("id"),
                        getPageParamValue("view"),
                        onGetEditorData,
                        onPostEditorData
                        );

                //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                // Placeholder for special things before Load ...
                $('#worktime_table').hide();
                //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            </script>

            <script>
                $('#worktime_first_weekdate')
                        .datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            format: 'dd/mm/yyyy'
                        }).on(
                        'changeDate',
                        function (e) {
                            if (e.date.getDay() !== 1) {
                                alert("Error : the date you have chosen is not Monday!");
                                throw "Error. First date is not Monday";
                            }

                            $('#worktime_table').show();

                            dataEditorNav.load(
                                    'loadForDoctor',
                                    {
                                        'doctorId': getPageParamValue("id"),
                                        'weekFirstDate': $("#worktime_first_weekdate").find("input").val()
                                    }
                            );
                        }
                );

                $('.selection').change(function (e) {
                    var current = e.target.value;
                    if (current === 'YES') {
                        $(e.target).css('background-color', 'lightgreen');
                    } else {
                        $(e.target).css('background-color', '#fff');
                    }
                });
            </script>

    </body>
</html>
