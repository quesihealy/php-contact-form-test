$(document).ready( function() {
    $('form[name="contact"] #submit_button').click( function(e) {
        
        e.preventDefault();
        $('form[name="contact"] #submit_button').attr("disabled", true);
        var valid = validate_input();

        if(valid) {
            var data = {
                first_name  : $("#first_name").val(),
                last_name   : $("#last_name").val(),
                email       : $("#email").val(),
                phone       : $("#phone").val(),
                message     : $("#message").val(),
            };

            $.ajax({
                type:"POST",
                url:"contact/post.php",
                data:{data},
                success: function(data) {
                    if(data.success == true) {
                        $('#contact-form-response').html(data['msg']);
                    } else if (data.success == false) {
                        $('#contact-form-response').html(data['msg']);
                        $('form[name="contact"] #submit_button').attr("disabled", false);
                    }
                },
                dataType: 'json',
            });
        } else {
            $('form[name="contact"] #submit_button').attr("disabled", false);
        }

        function validate_input() {

            var valid = true;
            var required_warning = 'This field is required';

            var first_name  = $("#first_name").val();
            var last_name   = $("#last_name").val();
            var email       = $("#email").val();
            var phone       = $("#phone").val();
            var message     = $("#message").val();

            clear_validation_errors();

            if(first_name == '') {
                $("#first_name_error").html(required_warning);
                valid = false;
            }
            if(last_name == '') {
                $("#last_name_error").html(required_warning);   
                valid = false;
            }
            if(email == '') {
                $("#email_error").html(required_warning);
                valid = false;
            } else if( ! valid_email(email)) {
                $("#email_error").html('Please enter a valid email.');
                valid = false;
            }
            if( phone != '' && ! valid_phone_number(phone)) {
                $("#phone_error").html('Please enter a 10 digit phone number (ex: 555-555-5555)');
                valid = false;
            }
            if(message == '') {
                $("#message_error").html(required_warning);
                valid = false;
            }

            return valid;

        }

        function clear_validation_errors() {
            $(".error-message").html('');
        }

        function valid_email(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        function valid_phone_number(phone_number) {
            var re = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
            return re.test(phone_number);
        }

    });
});