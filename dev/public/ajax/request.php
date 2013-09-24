<!DOCTYPE html>
<html>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
    </script>
    <script>
        $(document).ready(function(){
            $("button").click(function(){
                $.getJSON("http://api.cit.localhost/v1/customer/email/pablodv@mac.com",function(result){
                    $.each(result, function(i, field){
                        $("div").append(field + " ");
                    });
                });
            });
        });
    </script>
</head>
<body>

<button>Get JSON data</button>
<div></div>

</body>
</html>
