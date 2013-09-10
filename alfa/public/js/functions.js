$(function(){

    $("#aKill").click(function(){
        $.post("/session.php",function(data){
            // if you want you can show some message to user here
        });
    });
})