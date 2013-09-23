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

    var ret = request.done(function(msg) {
        console.log( msg );
        return msg;
    });

    return ret;

    request.fail(function(jqXHR, textStatus) {
        console.log( "Request failed: " + textStatus );
    });
}

function visitTrack(url, type, data) {
    ajaxRequest('/track.php', 'POST', '{"event":"visit", "link":"'+document.URL+'", "http_referer":"'+document.referrer+'"}');
}

function clickTrack(button) {
    ajaxRequest('/track.php', 'POST', '{"event":"click", "link":"'+button+'", "http_referer":"'+document.URL+'"}');
}

function formTrack(form, attribute, value) {
    var ret = ajaxRequest('/check.php', 'POST', '{"event":"search_first", "entity":"customer", "attribute":"'+attribute+'", "value":"'+value+'"}');

    alert(ret);

    if (ret == 1) {
        return true;
    } else {
        ajaxRequest('/track.php', 'POST', '{"event":"'+form+'", "link":"'+attribute+'|'+value+'", "http_referer":"'+document.URL+'"}');
    }
}
