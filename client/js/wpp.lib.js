function ifNull(value1, value2)
{
    if (value1 == null)
        return value2;
    else
        return value1;
}

function wppGetDateFromStr(str) {
    if (str) {
        var parts = str.split('/');

        return new Date(Date.UTC(parts[2], parts[1] - 1, parts[0]));
    } else {
        return null;
    }
}

function wppGetStrFromDate(dt) {
    if (dt) {
        var dd = dt.getDate();
        var mm = dt.getMonth() + 1; //January is 0!
        var yyyy = dt.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        return dd + '/' + mm + '/' + yyyy;
    } else {
        return null;
    }
}

function wppDateDiffInDays(dt1, dt2) {
    var _MS_PER_DAY = 1000 * 60 * 60 * 24;

    // Discard the time and time-zone information.
    var utcDt1 = Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate());
    var utcDt2 = Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate());

    return Math.floor((utcDt2 - utcDt1) / _MS_PER_DAY);
}

function wppAddDaysToDate(dt, days) {
    var _MS_PER_DAY = 1000 * 60 * 60 * 24;

    var utcDt = Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate());

    utcDt = utcDt + _MS_PER_DAY * days;

    return new Date(utcDt);
}

function getCurrentDate() {
    var today = new Date();

    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    return dd + '/' + mm + '/' + yyyy;
}

function getMetaProperty(propertyName) {
    var metas = document.getElementsByTagName(propertyName);

    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute(propertyName)) {
            return metas[i].getAttribute(propertyName);
        }
    }

    return null;
}

function getPageParamValue(paramName) {
    var params = window.location.search.substring(1);

    var paramArray = params.split("&");

    for (var i = 0; i < paramArray.length; i++) {
        var param = paramArray[i].split("=");

        if (param[0] === paramName) {
            return param[1];
        }
    }

    return null;
}

function WppEvent() {
    this._listeners = [];

    this.addListener = function (obj, eventHandler) {
        this._listeners.push(
                {
                    'obj': obj,
                    'handler': eventHandler
                }
        );
    };

    this.fire = function (eventData) {
        for (var i = 0; i < this._listeners.length; i++) {
            this._listeners[i].handler(eventData);
        }
    };
}