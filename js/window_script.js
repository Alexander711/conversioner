$(function () {
    $("#conversioner_number_phone").focus(function () {
        if ($(this).hasClass('placeholder') || $(this).hasClass('placeholder_error')) {
            $(this).val('');
            $(this).removeClass('placeholder');
            $(this).removeClass('placeholder_error');
        }
    });

    $("#conversioner_number_phone").blur(function () {
        if ($(this).val() == '') {
            $(this).val('Введите номер своего телефона');
            $(this).addClass('placeholder');
        }
    });

    $("#conversioner_contact_form").submit(function (e) {
        e.preventDefault();

        var validate = validate_data_contact_form();

        if (validate == 'ok') {
            $.post("/window_operator/send_message", $('#conversioner_contact_form').serialize(), function (event) {
                $("#conversioner_contact_form").trigger("reset");
                if (event == 1 || event == 'error_time') {
                    $('#conversioner_number_phone').attr("disabled", "disabled");
                    $('.conversioner_send_phone').attr("disabled", "disabled");
                    $('#conversioner_number_phone').val('Ждите звонка!');
                } else if (event == 'empty_data_input') {
                    $('#conversioner_number_phone').removeClass('placeholder');
                    $('#conversioner_number_phone').val('Заполните поле!').addClass('placeholder_error');
                } else if (event == 'incorrect_phone') {
                    $('#conversioner_number_phone').removeClass('placeholder');
                    $('#conversioner_number_phone').val('Неправильный номер!').addClass('placeholder_error');
                } else {
                    $('#conversioner_number_phone').removeClass('placeholder');
                    $('#conversioner_number_phone').val('Произошла ошибка! Повторите попытку позже').addClass('placeholder_error');
                }
            });
        } else if(validate == 'empty_phone'){
            $('#conversioner_number_phone').removeClass('placeholder');
            $('#conversioner_number_phone').val('Заполните поле!').addClass('placeholder_error');
        } else {
            $('#conversioner_number_phone').removeClass('placeholder');
            $('#conversioner_number_phone').val('Неправильный номер!').addClass('placeholder_error');
        }
    });

    function validate_data_contact_form() {
        if ($('#conversioner_number_phone').val() == '') {
            return 'empty_phone';
        }

        if ($('#conversioner_number_phone').val() == 'Введите номер своего телефона') {
            return 'empty_phone';
        }

        if ($('#conversioner_number_phone').val() == 'Заполните поле!') {
            return 'empty_phone';
        }

        if ($('#conversioner_number_phone').val() == 'Произошла ошибка!') {
            return 'empty_phone';
        }

        if ($('#conversioner_number_phone').val() == 'Ждите звонка!') {
            return 'empty_phone';
        }

        var regex = /^((\d|\+\d)[\- ]?)(\(?\d{3}\)?[\- ]?)[\d]{3}[\- ]?[\d]{2}[\- ]?[\d]{2}$/i;
        var is_phone = regex.exec($('#conversioner_number_phone').val());

        if (is_phone == null) {
            return 'not_phone';
        }

        return 'ok';
    }

});