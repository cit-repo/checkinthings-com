function foo(wait) {
    var i = 0;
    for (i = 0; i <= wait*1000000; i++) {
        // do nothing
    }
}

function ajaxRequest(url, type, data) {
    // console.log(url);
    // console.log(type);
    // console.log(data);

    var request = $.ajax({
        url: url,
        type: type,
        data: data,
        cache: false,
        success: function(response) {
            // alert(response);
            // console.log('response: '+response);
        },
        error: function(response) {
            // alert(response);
            // console.log('response: '+response);
        }
    });

    request.done(function(msg) {
        // console.log( msg );
    });

    request.fail(function(jqXHR, textStatus) {
        // console.log( "Request failed: " + textStatus );
    });
}

function sendRequest(url) {
    $.getJSON( url, function( data ) {
        var items = [];
        $.each( data, function( key, val ) {
            console.log( key + ': ' + val );
        });
    });
}

function visitTrack(url, type, data) {
    ajaxRequest('/track/', 'POST', '{"event":"visit", "link":"'+document.URL+'", "http_referer":"'+document.referrer+'"}');
}

function clickTrack(button) {
    ajaxRequest('/track/', 'POST', '{"event":"click", "link":"'+button+'", "http_referer":"'+document.URL+'"}');
}

function facebookSession(form, response) {
    // alert(response);
    ajaxRequest('/track/', 'POST', '{"event":"facebook", "link":"'+form+'", "response":'+response+'}');
}

function str_replace(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}

function loadRequest(url, entity, attribute, value) {
    $.ajaxSetup({
        async:false,
        dataType:"jsonp"
    });

    $('#json_response').load(url+'?entity='+entity+'&attribute='+attribute+'&value='+value);

    return jQuery.parseJSON($('#json_response').html());
}

function formTrack(form, attribute, value) {
    var ret = loadRequest('/check/', 'customer', attribute, value);

    if (strstr(ret.check, 'true')) {
        return true;
    } else {
        ajaxRequest('/track/', 'POST', '{"event":"'+form+'", "link":"'+attribute+'|'+value+'", "http_referer":"'+document.URL+'"}');
    }
}

function strstr(haystack, needle, bool) {
    // Finds first occurrence of a string within another
    //
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/strstr    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: strstr(‘Kevin van Zonneveld’, ‘van’);
    // *     returns 1: ‘van Zonneveld’    // *     example 2: strstr(‘Kevin van Zonneveld’, ‘van’, true);
    // *     returns 2: ‘Kevin ‘
    // *     example 3: strstr(‘name@example.com’, ‘@’);
    // *     returns 3: ‘@example.com’
    // *     example 4: strstr(‘name@example.com’, ‘@’, true);    // *     returns 4: ‘name’
    var pos = 0;

    haystack += "";
    pos = haystack.indexOf(needle); if (pos == -1) {
        return false;
    } else {
        if (bool) {
            return haystack.substr(0, pos);
        } else {
            return haystack.slice(pos);
        }
    }
}
