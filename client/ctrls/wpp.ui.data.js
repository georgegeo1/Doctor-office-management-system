(function ($) {

    var DataTableNav = function (controllerName, dataTable, panelFooter, visibleButtons,
            extraFiltersFunction = null) {
        var _that = this;

        this._fixedFilters = null;
        this._extraFiltersFunction = extraFiltersFunction;
        this._fixedOrderBy = null;
        this._ulLeft = null;
        this._ulRight = null;
        this._panelFooter = panelFooter;
        this._dataTable = dataTable;
        this._dataTable.OnSelectedRowIndexChange.addListener(this,
                function (eventData) {
                    if (eventData >= 0) {
                        var btn = _that.getButton("btn-view");
                        btn.className = btn.className.replace("disabled", "active");

                        var btn = _that.getButton("btn-edit");
                        btn.className = btn.className.replace("disabled", "active");

                        var btn = _that.getButton("btn-delete");
                        btn.className = btn.className.replace("disabled", "active");
                    } else {
                        var btn = _that.getButton("btn-view");
                        btn.className = btn.className.replace("active", "disabled");

                        var btn = _that.getButton("btn-edit");
                        btn.className = btn.className.replace("active", "disabled");

                        var btn = _that.getButton("btn-delete");
                        btn.className = btn.className.replace("active", "disabled");
                    }
                }
        );
        this._dataPacket = dataTable.getDataPacket();
        this._controllerName = controllerName;
        this.buttons = [];
        this._visibleButtons = visibleButtons;

        this.addButton = function (part, btnName, glyphIcon, btnTooltip, btnState, onClickFunc) {
            var btn = document.createElement('BUTTON');
            btn.className = "btn btn-info btn-me " + btnName + " " + btnState;
            btn.title = btnTooltip;

            btn.addEventListener(
                    "click",
                    onClickFunc
                    );

            var span = document.createElement('SPAN');
            span.className = "glyphicon " + glyphIcon;
            btn.appendChild(span);

            var li = document.createElement('li');

            li.appendChild(btn);

            if (part === 'left') {
                _that._ulLeft.appendChild(li);
            } else {
                _that._ulRight.appendChild(li);
            }

            _that.buttons.push(btn);
        };

        this.getButton = function (btnName) {
            return $("." + btnName)[0];
        };

        this._createDefaultButtons = function () {
            // create browse button

            this.addButton('left', "btn-browse", "glyphicon-refresh", "Refresh",
                    "active",
                    function (e) {
                        _that.browse();
                    }
            );

            // create view button

            this.addButton('left', "btn-view", "glyphicon-share", "View",
                    "disabled",
                    function (e) {
                        var iSelectedRowIndex = _that._dataTable.getSelectedRowIndex();

                        var selectedRow = _that._dataPacket.getRecord(iSelectedRowIndex);

                        var id = selectedRow[_that._dataPacket.getDatasetKeyFieldName()];

                        window.location.replace(_that._controllerName + "Edit" + ".php" + "?id=" + id + "&view=1&btnClose=2");
                    }
            );

            // create edit button

            this.addButton('left', "btn-edit", "glyphicon-edit", "Edit",
                    "disabled",
                    function (e) {
                        var iSelectedRowIndex = _that._dataTable.getSelectedRowIndex();

                        var selectedRow = _that._dataPacket.getRecord(iSelectedRowIndex);

                        var id = selectedRow[_that._dataPacket.getDatasetKeyFieldName()];

                        window.location.replace(_that._controllerName + "Edit" + ".php" + "?id=" + id + "&btnClose=2");
                    }
            );

            // create insert button

            this.addButton('left', "btn-append", "glyphicon-plus", "Insert",
                    "active",
                    function (e) {
                        window.location.replace(_that._controllerName + "Edit" + ".php" + "?btnClose=2");
                    }
            );

            // create delete button

            this.addButton('right', "btn-delete", "glyphicon-trash", "Delete",
                    "disabled",
                    function (e) {
                        if (confirm('Are you sure?')) {
                            var iSelectedRowIndex = _that._dataTable.getSelectedRowIndex();

                            var selectedRow = _that._dataPacket.getRecord(iSelectedRowIndex);

                            var id = selectedRow[_that._dataPacket.getDatasetKeyFieldName()];

                            wppApp.serverCall('post', this,
                                    {
                                        'controller': _that._controllerName,
                                        'method': "delete",
                                        'params': id
                                    },
                                    function (callerObj, responseJson) {
                                        alert("Done");

                                        _that.browse();
                                    },
                                    function (responseText) {
                                        alert("Delete error : " + responseText);
                                    }
                            );
                        }
                    }
            );
        };

        this.getFilters = function () {
            var filters = this._dataTable.getFilters();

            if (this._fixedFilters) {
                if (!filters) {
                    filters = [];
                }

                for (var i = 0; i < this._fixedFilters.length; i++) {
                    filters.push(this._fixedFilters[i]);
                }
            }

            if (this._extraFiltersFunction) {
                var extraFilters = this._extraFiltersFunction();

                if (extraFilters) {
                    if (!filters) {
                        filters = [];
                    }

                    for (var i = 0; i < extraFilters.length; i++) {
                        filters.push(extraFilters[i]);
                    }
                }
            }

            return filters;
        };

        this.getOrderBy = function () {
            ////
            return this._fixedOrderBy;
        }

        this.addFixedFilter = function (filter) {
            if (!this._fixedFilters) {
                this._fixedFilters = [];
            }

            this._fixedFilters.push(filter);
        };

        this.addFixedOrderBy = function (orderby) {
            this._fixedOrderBy = orderby;
        };

        this.browse = function () {
            this._dataTable.setSelectedRowIndex(-1);

            this._dataTable.updateFilterStorage();

            wppApp.serverCall('get', this,
                    {
                        'controller': this._controllerName,
                        'method': 'browse',
                        'params': {
                            where: this.getFilters(),
                            orderby: this.getOrderBy()
                        }
                    },
                    function (callerObj, responseJson) {
                        _that._panelFooter.innerHTML = "";
                        _that._panelFooter.style.color = "green";

                        callerObj._dataPacket.setData(responseJson);
                        callerObj._dataTable.sync();

                        callerObj._dataTable.reloadFilterStorage();
                    },
                    function (responseText) {
                        _that._panelFooter.innerHTML = responseText;
                        _that._panelFooter.style.color = "red";
                    });
        };

        this.getDataTable = function () {
            return this._dataTable;
        }

        this.getDataPacket = function () {
            return this._dataPacket;
        }

        this.$ = function (selector) {
            return $(selector, this);
        };

        this._init = function () {
            // left part buttons ..............................................

            this._ulLeft = document.createElement('ul');
            this._ulLeft.className = "nav navbar-nav";

            this.append(this._ulLeft);

            // right part buttons ..............................................

            this._ulRight = document.createElement('ul');
            this._ulRight.className = "nav navbar-nav navbar-right";

            this.append(this._ulRight);

            this._createDefaultButtons();

            if (!this._visibleButtons.edit)
                this.getButton("btn-edit").style.display = "none";

            if (!this._visibleButtons.append)
                this.getButton("btn-append").style.display = "none";

            if (!this._visibleButtons.delete)
                this.getButton("btn-delete").style.display = "none";

            if (!this._visibleButtons.view)
                this.getButton("btn-view").style.display = "none";
        };

        this._init();

        return this;
    };

    $.fn.dataTableNav = DataTableNav;
})(jQuery);

(function ($) {

    var DataEditorNav = function (panelFooter, controllerName, dataPacket, id, readOnly,
            onGetEditorData, onPostEditorData, onValidateEditorData = null) {
        var _that = this;

        this._panelFooter = panelFooter;
        this._dataPacket = dataPacket;
        this._controllerName = controllerName;
        this.buttons = [];
        this._onGetEditorData = onGetEditorData;
        this._onPostEditorData = onPostEditorData;
        this._onValidateEditorData = onValidateEditorData;
        this._id = id;
        this._readOnly = readOnly;

        this.addButton = function (parent, btnName, glyphIcon, btnTooltip, onClickFunc) {
            var btn = document.createElement('BUTTON');
            btn.className = "btn btn-info btn-me " + btnName;
            btn.title = btnTooltip;

            btn.addEventListener(
                    "click",
                    onClickFunc
                    );

            var span = document.createElement('SPAN');
            span.className = "glyphicon " + glyphIcon;
            btn.appendChild(span);

            var li = document.createElement('li');

            li.appendChild(btn);

            parent.appendChild(li);

            _that.buttons.push(btn);
        };

        this.getButton = function (btnName) {
            return $("." + btnName)[0];
        };

        this._createDefaultButtons = function () {
            // left part buttons ...............................................

            var ulLeft = document.createElement('ul');
            ulLeft.className = "nav navbar-nav";
            this.append(ulLeft);

            this.addButton(ulLeft, "btn-save", "glyphicon-check", "Save",
                    function (e) {
                        _that.save();
                    }
            );
        };

        this.load = function (forcedMethod = null, forcedParams = null) {
            var loadMethod = "load";

            if (forcedMethod) {
                loadMethod = forcedMethod;
            } else {
                if (!this._id) {
                    loadMethod = "create";
                }
            }

            var params = this._id;

            if (forcedParams) {
                params = forcedParams;
            }

            wppApp.serverCall('get', this,
                    {
                        'controller': this._controllerName,
                        'method': loadMethod,
                        'params': params
                    },
                    function (callerObj, responseJson) {
                        _that._dataPacket.setData(responseJson);

                        _that._onGetEditorData(_that._dataPacket);
                    },
                    function (responseText) {
                        _that._panelFooter.innerHTML = responseText;
                        _that._panelFooter.style.color = "red";
                    }
            );
        };

        this.save = function () {
            _that._panelFooter.innerHTML = "";
            _that._panelFooter.style.color = "green";

            if (!this._id) {
                _that._dataPacket.setFieldValueByName("_state", 2);
            } else {
                _that._dataPacket.setFieldValueByName("_state", 1);
            }

            try {
                if (_that._onValidateEditorData) {
                    _that._onValidateEditorData();
                }
            } catch (errorMessage) {
                _that._panelFooter.innerHTML = errorMessage;
                _that._panelFooter.style.color = "red";

                return;
            }

            _that._onPostEditorData(_that._dataPacket);

            wppApp.serverCall('post', this,
                    {
                        'controller': this._controllerName,
                        'method': "save",
                        'params': _that._dataPacket.getData()
                    },
                    function (callerObj, responseJson) {
                        _that._dataPacket.setData(responseJson);

                        _that._id = _that._dataPacket.getKeyFieldValue(); // for edit right after insert

                        _that._onGetEditorData(_that._dataPacket);

                        _that._panelFooter.innerHTML = "Done";
                        _that._panelFooter.style.color = "green";
                    },
                    function (responseText) {
                        _that._panelFooter.innerHTML = responseText;
                        _that._panelFooter.style.color = "red";
                    }
            );
        };

        this.$ = function (selector) {
            return $(selector, this);
        };

        this._init = function () {
            this._createDefaultButtons();

            if (this._readOnly) {
                this.getButton("btn-save").style.display = "none";
            }
        };

        this._init();

        return this;
    }

    $.fn.dataEditorNav = DataEditorNav;
})(jQuery);

(function ($) {

    var PageNav = function (_controllerName, pageTitle, btnClose) {
        var _that = this;

        this._pageTitle = pageTitle;
        this._btnClose = btnClose;
        this._controllerName = _controllerName;
        this.buttons = [];

        this.addButton = function (parent, btnName, glyphIcon, btnTooltip, onClickFunc) {
            var btn = document.createElement('BUTTON');
            btn.className = "btn btn-info btn-me " + btnName;
            btn.title = btnTooltip;

            btn.addEventListener(
                    "click",
                    onClickFunc
                    );

            var span = document.createElement('SPAN');
            span.className = "glyphicon " + glyphIcon;
            btn.appendChild(span);

            var li = document.createElement('li');

            li.appendChild(btn);

            parent.appendChild(li);

            _that.buttons.push(btn);
        };

        this.getButton = function (btnName) {
            return $("." + btnName)[0];
        };

        this._createDefaultButtons = function () {
            // left part buttons ...............................................

            var divLeft = document.createElement('div');
            divLeft.className = "navbar-header";

            var aTitle = document.createElement('a');
            aTitle.className = "navbar-brand";
            aTitle.innerHTML = this._pageTitle;
            divLeft.appendChild(aTitle);

            this.append(divLeft);

            // right part buttons ..............................................

            var ulRight = document.createElement('ul');
            ulRight.className = "nav navbar-nav navbar-right";
            this.append(ulRight);

            // create back button

            if (this._btnClose === "1") {
                this.addButton(ulRight, "btn-back", "glyphicon-remove", "Close",
                        function (e) {
                            wppApp.go("_home");
                        }
                );
            } else if (this._btnClose === "2") {
                this.addButton(ulRight, "btn-back", "glyphicon-remove", "Back",
                        function (e) {
                            window.location.replace(_that._controllerName + "List" + ".php");
                        }
                );
            }
        };

        this._init = function () {
            this._createDefaultButtons();
        };

        this._init();

        return this;
    }

    $.fn.pageNav = PageNav;
})(jQuery);

(function ($) {

    var DataTable = function (dataPacket, fields) {
        var _that = this;
        this._dataPacket = dataPacket;
        this._fields = fields;
        this._selectedRowIndex = -1;
        this.OnSelectedRowIndexChange = new WppEvent();

        this.setSelectedRowIndex = function (iRowIndex) {
            if (iRowIndex === this._selectedRowIndex) {
                this._selectedRowIndex = -1;
            } else {
                this._selectedRowIndex = iRowIndex;
            }

            this.OnSelectedRowIndexChange.fire(this._selectedRowIndex);
        };

        this.getSelectedRowIndex = function () {
            return this._selectedRowIndex;
        };

        this.getFilters = function () {
            var filters = null;
            var filterInputs = $('.table-criterio');

            for (var i = 0; i < filterInputs.length; i++) {
                var filterInput = filterInputs[i];
                var filterValue = $(filterInput).val();

                if (filterValue) {
                    var fieldName = filterInput.id.replace("table-criterio_", "");
                    var fieldType = this._dataPacket.getFieldTypeByName(fieldName);

                    if (!filters) {
                        filters = [];
                    }

                    if (fieldType === "varchar") {
                        filters.push(
                                {
                                    "field": fieldName,
                                    "op": "like",
                                    "value": filterValue + "%"
                                }
                        );
                    } else {
                        filters.push(
                                {
                                    "field": fieldName,
                                    "op": "=",
                                    "value": filterValue
                                }
                        );
                    }
                }
            }

            return filters;
        };

        this.sync = function () {
            var iCnt = this.children().length;

            for (i = iCnt - 1; i >= 0; i--) {
                this.children()[i].remove();
            }

            // create thead ****************************************************

            var header = document.createElement('thead');

            // thead captions

            var row = document.createElement('tr');

            var cell = document.createElement('th');
            cell.style = "width: 24px";

            row.appendChild(cell);

            for (var i = 0; i < this._fields.length; i++) {
                if (this._fields[i].visible) {
                    var cell = document.createElement('th');

                    cell.innerHTML = ifNull(fields[i].caption, fields[i].name);
                    cell.style.width = fields[i].width;

                    row.appendChild(cell);
                }
            }

            header.appendChild(row);

            // thead filters

            var row = document.createElement('tr');
            var cell = document.createElement('td');

            row.appendChild(cell);

            for (var i = 0; i < this._fields.length; i++) {
                if (this._fields[i].visible) {
                    var cell = document.createElement('td');

                    var fieldName = this._fields[i].name;
                    var fieldType = this._dataPacket.getFieldTypeByName(fieldName);
                    var criterioPlaceholder = 'Search value';

                    if (fieldType === "varchar") {
                        criterioPlaceholder = 'Search value (or prefix)';
                    }

                    cell.innerHTML = "<input class='table-criterio' " +
                            "id='table-criterio_" + this._fields[i].name + "' " +
                            "placeholder='" + criterioPlaceholder + "' style='width : 100%'></input>";

                    row.appendChild(cell);
                }
            }

            header.appendChild(row);

            this.append(header);

            // create body *****************************************************

            var body = document.createElement('tbody');

            for (var iRow = 0; iRow < this._dataPacket.getRecordCount(); iRow++) {
                var row = document.createElement('tr');

                var cell = document.createElement('td');

                var selBtn = document.createElement('BUTTON');
                selBtn.className = "btn btn-default btn-sm";
                selBtn.value = iRow;
                selBtn.addEventListener(
                        "click",
                        function (e) {
                            _that._onClickSelBtn(e);
                        }
                );

                var span = document.createElement('SPAN');
                span.className = "glyphicon glyphicon-unchecked table-selector";

                selBtn.appendChild(span);

                cell.appendChild(selBtn);

                row.appendChild(cell);

                for (var i = 0; i < this._fields.length; i++) {
                    if (this._fields[i].visible) {
                        var cell = document.createElement('td');
                        cell.innerHTML = this._dataPacket.getFieldValueByName(this._fields[i].name, iRow);
                        row.appendChild(cell);
                    }
                }

                body.appendChild(row);
            }

            this.append(body);
        };

        this.clearFilterStorage = function () {
            for (var i = 0; i < this._fields.length; i++) {
                if (this._fields[i].visible) {
                    sessionStorage.removeItem(this._fields[i].name);
                }
            }
        };

        this.updateFilterStorage = function () {
            for (var i = 0; i < this._fields.length; i++) {
                if (this._fields[i].visible) {
                    if ($("#table-criterio_" + this._fields[i].name).length > 0) {
                        var criterion = $("#table-criterio_" + this._fields[i].name).val();

                        sessionStorage.setItem(this._fields[i].name, criterion);
                    }
                }
            }
        };

        this.reloadFilterStorage = function () {
            for (var i = 0; i < this._fields.length; i++) {
                if (this._fields[i].visible) {
                    if ($("#table-criterio_" + this._fields[i].name).length > 0) {
                        var criterion = sessionStorage.getItem(this._fields[i].name);

                        $("#table-criterio_" + this._fields[i].name).val(criterion);
                    }
                }
            }
        };

        this._onClickSelBtn = function (e) {
            var gl = e.currentTarget.childNodes[0];

            $(".table-selector").removeClass("glyphicon-check");
            $(".table-selector").addClass("glyphicon-unchecked");

            _that.setSelectedRowIndex(parseInt(e.currentTarget.value));

            if (_that._selectedRowIndex >= 0) {
                gl.className = "glyphicon glyphicon-check table-selector";
            }
        };

        this.getDataPacket = function () {
            return this._dataPacket;
        };

        this.$ = function (selector) {
            return $(selector, this);
        };

        this._init = function () {
            this.clearFilterStorage();
        };

        this._init();

        return this;
    }

    $.fn.dataTable = DataTable;
})(jQuery);
