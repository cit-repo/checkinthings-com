$(function(){
    //original field values
    var field_values = {
        //id        :  value
        'email'  : 'email address',
        'password'  : 'password',
        'cpassword' : 'password',
        'firstname'  : 'first name',
        'lastname'  : 'last name'

    };

    //inputfocus
    $('input#email').inputfocus({ value: field_values['email'] });
    $('input#password').inputfocus({ value: field_values['password'] });
    $('input#cpassword').inputfocus({ value: field_values['cpassword'] });
    $('input#lastname').inputfocus({ value: field_values['lastname'] });
    $('input#firstname').inputfocus({ value: field_values['firstname'] });

    //reset progress bar
    $('#progress').css('width','0');
    $('#progress_text').html('0% Complete');

    //first_step
    $('#submit_first').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        var fields = $('#first_step input[type=text]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( (value.length<4 || value==field_values[$(this).attr('id')]) || ( $(this).attr('id')=='email' && !emailPattern.test(value) )) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);

                error++;
            } else {
                $(this).addClass('valid');
            }
        });

        if(!error) {
            if (formTrack('multistepForm', 'email', $('#email').val())) {
                console.log('Sorry, '+$('#email').val()+' is already in use.');
                alert('Sorry, '+$('#email').val()+' is already in use.');
                return false;
            }

            //update progress bar
            $('#progress_text').html('33% Complete');
            $('#progress').css('width','113px');

            //slide steps
            $('#first_step').slideUp();
            $('#second_step').slideDown();
        } else return false;
    });


    $('#submit_second').click(function(){
        //remove classes
        $('#second_step input').removeClass('error').removeClass('valid');

        var fields = $('#second_step input[type=password], #second_step input[type=text]');
        var error = 0;
        fields.each(function(){
            var value = $(this).val();
            if( value.length<1 || value==field_values[$(this).attr('id')]) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);

                error++;
            } else {
                $(this).addClass('valid');
            }
        });

        if(!error) {
            if( $('#password').val() != $('#cpassword').val()) {
                $('#second_step input[type=password]').each(function(){
                    $(this).removeClass('valid').addClass('error');
                    $(this).effect("shake", { times:3 }, 50);
                });

                return false;
            } else {
                //update progress bar
                $('#progress_text').html('66% Complete');
                $('#progress').css('width','226px');

                //slide steps
                $('#second_step').slideUp();
                $('#third_step').slideDown();
            }
        } else return false;

    });


    $('#submit_third').click(function(){
        //update progress bar
        $('#progress_text').html('100% Complete');
        $('#progress').css('width','339px');

        //prepare the fourth step
        var fields = new Array(
            $('#email').val(),
            $('#password').val(),
            $('#firstname').val() + ' ' + $('#lastname').val(),
            $('#age').val(),
            $('#gender').val(),
            $('#country').val()
        );
        var tr = $('#fourth_step tr');
        tr.each(function(){
            //alert( fields[$(this).index()] )
            $(this).children('td:nth-child(2)').html(fields[$(this).index()]);
        });

        //slide steps
        $('#third_step').slideUp();
        $('#fourth_step').slideDown();
    });


    $('#submit_fourth').click(function(){
        //send information to server
        $('multistepForm').submit();
    });

});