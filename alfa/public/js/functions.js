function foo()
{

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
            // alert('Success.');
        },
        error: function(data) {
            // alert('Error.');
        }
    });

}

function visit()
{
    ajaxRequest('/track.php', 'POST', '{"link":"'+document.URL+'", "http_referer":"'+document.referrer+'"}');
}