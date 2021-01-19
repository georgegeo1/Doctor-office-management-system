function DataPacket() {
    this._data = null;

    this.setControllerName = function (ControllerName) {
        this._ControllerName = ControllerName;
    };

    // Dataset level methods ...

    this.getDatasetIndex = function (datasetName = 'master') {
        if (this._data) {
            for (var i = 0; i < this._data.meta.length; i++) {
                if (this._data.meta[i].name === datasetName) {
                    return i;
                }
            }
        }

        return -1;
    };

    this.getDatasetFields = function (datasetName = 'master') {
        var iDatasetIndex = this.getDatasetIndex(datasetName);

        if (iDatasetIndex >= 0) {
            if (this._data && this._data.meta) {
                return this._data.meta[iDatasetIndex].fields;
            } else {
                return null;
            }
        } else {
            return null;
    }
    };

    this.getDatasetKeyFieldName = function (datasetName = 'master') {
        return this._data.meta[this.getDatasetIndex(datasetName)]["keyField"];
    };

    // Record level methods

    this.getRecords = function (datasetName = 'master') {
        var iDatasetIndex = this.getDatasetIndex(datasetName);

        if (iDatasetIndex >= 0) {
            if (this._data && this._data.data) {
                return this._data.data[iDatasetIndex].records;
            } else {
                return null;
            }
        } else {
            return null;
    }
    };

    this.getRecordCount = function (datasetName = 'master') {
        if (this.getRecords(datasetName)) {
            return this.getRecords(datasetName).length;
        } else {
            return 0;
    }
    };

    this.getRecord = function (iRowIndex = 0, datasetName = 'master') {
        if (iRowIndex >= 0 && iRowIndex <= this.getRecordCount(datasetName)) {
            return this.getRecords(datasetName)[iRowIndex]
        } else {
            return null;
    }
    };

    this.getRecordState = function (rowIndex = 0, datasetName = 'master') {
        return this.getFieldValueByName('_state', rowIndex, datasetName);
    };

    this.addRecord = function (datasetName = 'master') {
        var newRecord = {'_state': 2};

        this.getRecords(datasetName).push(newRecord);
        
        return newRecord;
    }

    // Field level methods ...

    this.getFieldTypeByName = function (fldName, datasetName = 'master') {
        var fields = this.getDatasetFields(datasetName);

        for (var i = 0; i < fields.length; i++) {
            if (fields[i].name === fldName) {
                return fields[i].type;
            }
    }
    }

    this.getFieldValueByName = function (fldName, rowIndex = 0, datasetName = 'master') {
        if (rowIndex >= 0 && rowIndex < this.getRecordCount(datasetName)) {
            return this.getRecords(datasetName)[rowIndex][fldName];
        } else {
            return null;
    }
    };

    this.setFieldValueByName = function (fldName, fldValue, rowIndex = 0, datasetName = 'master') {
        if (rowIndex >= 0 && rowIndex < this.getRecordCount(datasetName)) {
            this.getRecords(datasetName)[rowIndex][fldName] = fldValue;
    }
    };

    this.getKeyFieldValue = function (rowIndex = 0, datasetName = 'master') {
        return this.getFieldValueByName(this.getDatasetKeyFieldName(datasetName), rowIndex, datasetName);
    };

    // Data methods ...

    this.setData = function (dataJson) {
        this._data = dataJson;
    };

    this.getData = function () {
        return this._data;
    };

}
