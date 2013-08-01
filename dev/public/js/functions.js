function ajaxCall(method, url, json, id)
{
    switch (method) {
        case 'post':
            $.post(url, json)
                .done(function(data) {
                    $('div.'+id).html(function() {
                        alert(data);
                        return '<p>' + jsonToHtml(data) + '</p>';
                    });
                });
            break;
        case 'get':
            $.get(url, json)
                .done(function(data) {
                    $('div.'+id).html(function() {
                        alert(data);
                        return '<p>' + jsonToHtml(data) + '</p>';
                    });
                });
            break;
    }
}

function htmlLoad(id)
{
    // curl -X POST 'http://api.cit.localhost/v1/search' -d '{"must":{"_id":"0083905cc81ba112799ef39d876815f0","_rev":"2-1d230be0e4b54e9ba4cf9cf07e2ad9f1"}}'


    // var json = { "name": "John", "time": "2pm" };

    // var url = "http://api.cit.localhost/v1/search";
    var url = "http://dev.cit.localhost/test.php";
    var json = {"must": {"_id": "0083905cc81ba112799ef39d876815f0", "_rev": "2-1d230be0e4b54e9ba4cf9cf07e2ad9f1"}};

    ajaxCall('get', url, json, id);
}

function jsonToHtml(json)
{
    arr = $.parseJSON(json);

    $.each(arr, function(index, value) {
        alert(index + ': ' + value);

        if ($.value.isArray([])) {
            $.each(arr, function(index2, value2) {
                alert(index2 + ': ' + value2);
            });
        }
    });

    var transform = {'tag':'li','html':'${must} (${_id}, ${_rev}, )'};
    return json2html.transform(json, transform);
}