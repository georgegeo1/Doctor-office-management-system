var wppApp = {
    homePage: "client/doctor_officeView.php"
    ,
    setBaseUrl: function (baseUrl) {
        sessionStorage.setItem("baseUrl", baseUrl);
    },
    getBaseUrl: function () {
        return sessionStorage.getItem("baseUrl");
    },
    setUserId: function (userId) {
        sessionStorage.setItem("userId", userId);
    },
    getUserId: function () {
        return sessionStorage.getItem("userId");
    },
    setUserName: function (userName) {
        sessionStorage.setItem("userName", userName);
    },
    getUserName: function () {
        return sessionStorage.getItem("userName");
    },
    setUserRole: function (userRole) {
        sessionStorage.setItem("userRole", userRole);
    },
    getUserRole: function () {
        return parseInt(sessionStorage.getItem("userRole"));
    },
    // handles the click event for link 1, sends the query
    serverCall: function (callType, callerObj, params, successHandler, errorHandler) {
        /*var path = location.pathname.split('/');
        var home = "/" + path[1];*/
        
        this._doServerCall(callType, callerObj, this.getBaseUrl() + 'server/clientProxy.php', // URL for the PHP file
                params,
                successHandler, // handle successful request
                errorHandler // handle error
                );

        return false;
    },
    // helper function for cross-browser request object
    _doServerCall: function (callType, callerObj, url, params, successHandler, errorHandler) {
        /*var req = false;
         
         try {
         // most browsers
         req = new XMLHttpRequest();
         } catch (e) {
         // IE
         try {
         req = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
         // try an older version
         try {
         req = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {
         return false;
         }
         }
         }
         
         if (!req)
         return false;*/

        if (typeof successHandler != 'function')
            successHandler = function () {
            };

        // handles error message
        _errorHandler = function (response) {
            alert("Error: " + response.status + " " + response.statusText);
        }
        
        /*req.onreadystatechange = function() {
         if (req.readyState == 4) {
         return req.status === 200 ? success(callerObj, JSON
         .parse(req.responseText)) : error(req.status);
         }
         }
         
         req.open("GET", url, true);
         
         req.send(null);
         
         return req;*/

        $.ajax({
            type: callType,
            url: url,
            data: params,
            success: function (response) {
                var responseJson = JSON.parse(response);

                if (responseJson.error) {
                    errorHandler(responseJson.error);
                }
                else {
                    successHandler(callerObj, responseJson);
                }
            },
            error: function (response) {
                _errorHandler(response);
            }
        })
    },
    prepareBrowser: function () {
        $('.wpp-browser-container')[0].style.height = $('html')[0].clientHeight + "px";
        $('.panel')[0].style.height = ($('.wpp-browser-container')[0].clientHeight -
                $('.wpp-page-header')[0].clientHeight - 20) + "px";
        $('.panel-body')[0].style.height = ($('.panel')[0].clientHeight -
                $('.panel-heading')[0].clientHeight -
                $('.panel-footer')[0].clientHeight) + "px";
        $('.datagrid')[0].style.height = $('.panel-body')[0].clientHeight + "px";
    },
    go: function (pageUrl) {
        if (pageUrl === "_home") {
            pageUrl = this.homePage;
        }

        if ($('#main_frame', window.document).length) {
            var mainFrame = $('#main_frame', window.document)[0];
        }
        else {
            var mainFrame = $('#main_frame', window.parent.document)[0];
        }

        mainFrame.src = this.getBaseUrl() + pageUrl;
    }
};