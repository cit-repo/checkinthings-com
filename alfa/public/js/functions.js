function foo(wait)
{
    var i = 0;
    for (i = 0; i <= wait*1000000; i++) {
        // do nothing
    }
}

function ajaxRequest(url, type, data)
{
    console.log(url);
    console.log(type);
    console.log(data);

    $.ajax({
        url: url,
        type: type,
        data: data,
        cache: false,
        success: function(data) {
            // alert(data);
            console.log('Response: '+data);
        },
        error: function(data) {
            // alert(data);
            console.log('Response: '+data);
        }
    });


}

function visitTrack(url, type, data)
{
    ajaxRequest('/track.php', 'POST', '{"event":"visit", "link":"'+document.URL+'", "http_referer":"'+document.referrer+'"}');
}

function clickTrack(button)
{
    ajaxRequest('/track.php', 'POST', '{"event":"click", "link":"'+button+'", "http_referer":"'+document.URL+'"}');
}

function formTrack(form, key, value)
{
    ajaxRequest('/track.php', 'POST', '{"event":"'+form+'", "link":"'+key+'|'+value+'", "http_referer":"'+document.URL+'"}');
}