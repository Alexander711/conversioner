$(function () {

    $('#edit_user_data').click(function () {
        edit_user_data_view_window();
    });

    $('.close_pop_up_edit_user_data').click(function () {
        $('#edit_user_data_form').trigger('reset');
        edit_user_data_view_window();
    });

    $('#popup_overlay_edit_user_data').click(function () {
        $('#edit_user_data_form').trigger('reset');
        edit_user_data_view_window();
    });

    function edit_user_data_view_window() {
        $('#pop_up_edit_user_data').toggle();
        $('#popup_overlay_edit_user_data').toggle();
        $('.errors_edit_user').hide();
    }

    $("#edit_user_data_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_edit_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_edit_user').hide();

            $.post("/authorization/edit_user_data", $('#edit_user_data_form').serialize(), function (event) {
                if (event.status_ajax == 'ok') {
                    $("input[name='name']", '#edit_user_data_form').val(event.name);
                    $("input[name='last_name']", '#edit_user_data_form').val(event.last_name);
                    $("input[name='email_user']", '#edit_user_data_form').val(event.email);
                    $("input[name='phone']", '#edit_user_data_form').val(event.phone);
                    $('#user_name_aut').html(event.name + ' ' + event.last_name + "!");
                    $('#user_email_aut').html('(' + event.email + ')');
                    edit_user_data_view_window();
                    alert('Данные изменены');
                } else {
                    $('.errors_edit_user').html(event.error_mass);
                    $('.errors_edit_user').show().delay(5000).fadeOut(1000);
                }
            }, 'json');
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_edit_user').html(error_mess);
                $('.errors_edit_user').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_edit_form() {
        var error_mass = new Array();

        var name = $('#edit_user_data_form #name_edit_user_form').val();
        var last_name = $('#edit_user_data_form #last_name_edit_user_form').val();
        var email_user = $('#edit_user_data_form #email_user_edit_user_form').val();
        var pass = $("#edit_user_data_form #pass_edit_user_form").val();
        var confirm_pass = $('#edit_user_data_form #confirm_pass_edit_user_form').val();
        var phone = $('#edit_user_data_form #phone_edit_user_form').val();

        if (name == '') {
            error_mass[error_mass.length] = 'Заполните поле "Имя"';
        }

        if (last_name == '') {
            error_mass[error_mass.length] = 'Заполните поле "Фамилия"';
        }

        if (email_user == '') {
            error_mass[error_mass.length] = 'Заполните поле "Email"';
        }

        if (pass.length < 6 && pass != '') {
            error_mass[error_mass.length] = 'Длина поля "Пароль" должна быть не меньше 6 символов';
        }

        if (pass != confirm_pass) {
            error_mass[error_mass.length] = 'Поля "Пароль" и "Подтвердить пароль" должны совпадать';
        }

        if (phone == '') {
            error_mass[error_mass.length] = 'Заполните поле "Телефон"';
        }

        if (phone.length < 11 && phone != '') {
            error_mass[error_mass.length] = 'Длина поля "Телефон" должна быть не меньше 11 символов';
        }

        return error_mass;
    }

    $('#password_recovery').click(function () {
        password_recovery_view_window();
    });

    $('#popup_overlay_password_recovery').click(function () {
        password_recovery_view_window();
    });

    $('.close_pop_up_password_recovery').click(function () {
        password_recovery_view_window();
    });

    function password_recovery_view_window()
    {
        if (!$('#pop_up_password_recovery').hasClass('first_step_password_recovery')) {
            $('#pop_up_password_recovery').removeClass('second_step_password_recovery');
            $('#pop_up_password_recovery').removeClass('third_step_password_recovery');
            $('#pop_up_password_recovery').addClass('first_step_password_recovery');
        }

        $('#email_form_password_recovery').trigger('reset').show();
        $('#confirm_form_password_recovery').trigger('reset').hide();
        $('#new_password_form').trigger('reset').hide();
        $('#pop_up_password_recovery').toggle();
        $('#popup_overlay_password_recovery').toggle();
    }

    $('#email_form_password_recovery').submit(function (e) {
        e.preventDefault();

        if ($('#email_form_password_recovery #email_password_recovery').val() == '') {
            $('.errors_password_recovery').html('<span>Заполните поле "Введите Email"</span><br/>');
            $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
        } else {
            $('.errors_password_recovery').hide();

            $.post("/authorization/password_recovery", $('#email_form_password_recovery').serialize(), function (event) {
                if (event == 'ok') {
                    $('#pop_up_password_recovery').removeClass('first_step_password_recovery').addClass('second_step_password_recovery');
                    $('#email_form_password_recovery').hide();
                    $('#confirm_form_password_recovery').show();
                } else {
                    $('.errors_password_recovery').html(event);
                    $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
                }
            });
        }

    });

    $('#confirm_form_password_recovery').submit(function (e) {
        e.preventDefault();

        if ($('#confirm_form_password_recovery #confirm_password_recovery').val() == '') {
            $('.errors_password_recovery').html('<span>Заполните поле "Ведите код подтверждения"</span><br/>');
            $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
        } else {
            $('.errors_password_recovery').hide();

            $.post("/authorization/confirm_password_recovery", $('#confirm_form_password_recovery').serialize(), function (event) {
                if (event == 'ok') {
                    $('#pop_up_password_recovery').removeClass('second_step_password_recovery').addClass('third_step_password_recovery');
                    $('#confirm_form_password_recovery').hide();
                    $('#new_password_form').show();
                } else {
                    $('.errors_password_recovery').html(event);
                    $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
                }
            });
        }
    });

    $('#new_password_form').submit(function (e) {
        e.preventDefault();

        var error_mass = validate_new_password_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_password_recovery').hide();

            $.post("/authorization/new_password", $('#new_password_form').serialize(), function (event) {
                if (event == 'ok') {
                    $('#pop_up_password_recovery').removeClass('third_step_password_recovery');
                    password_recovery_view_window();
                    alert('Пароль изменен');
                } else {
                    $('.errors_password_recovery').html(event);
                    $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
                }
            });
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_password_recovery').html(error_mess);
                $('.errors_password_recovery').show().delay(5000).fadeOut(1000);
            }
        }

    });

    function validate_new_password_form() {
        var error_mass = new Array();

        var new_password = $('#new_password_form #new_password').val();
        var confirm_new_password = $('#new_password_form #confirm_new_password').val();

        if (new_password == '') {
            error_mass[error_mass.length] = 'Заполните поле "Введите новый пароль"';
        }

        if (new_password.length < 6 && new_password != '') {
            error_mass[error_mass.length] = 'Длина поля "Введите новый пароль" должна быть не меньше 6 символов';
        }

        if (confirm_new_password == '') {
            error_mass[error_mass.length] = 'Заполните поле "Повторите пароль"';
        }

        if (confirm_new_password.length < 6 && confirm_new_password != '') {
            error_mass[error_mass.length] = 'Длина поня "Повторите пароль" должна быть не меньше 6 символов';
        }

        if ((error_mass.length == 0) && (new_password != confirm_new_password)) {
            error_mass[error_mass.length] = 'Пароли не совпадают';
        }

        return error_mass;
    }

    $("#login_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_login_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_login_form').hide();

            $.post("/authorization/login", $('#login_form').serialize(), function (event) {
                if (event == 'ok') {
                    window.location.replace("/generate_widget/sms_history_list");
                } else {
                    $('.errors_login_form').html(event);
                    $('.errors_login_form').show().delay(5000).fadeOut(1000);
                }
            });
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_login_form').html(error_mess);
                $('.errors_login_form').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_login_form() {
        var error_mass = new Array();

        if ($('#login_form #email_user_login_form').val() == '') {
            error_mass[error_mass.length] = 'Заполните поле "Email"';
        }

        if ($('#login_form #pass_login_form').val() == '') {
            error_mass[error_mass.length] = 'Заполните поле "Пароль"';
        }

        if ($("#login_form #pass_login_form").val().length < 6 && $('#login_form #pass_login_form').val() != '') {
            error_mass[error_mass.length] = 'Длина не меньше 6 символов';
        }

        return error_mass;
    }

    $('#registration').click(function () {
        registration_view_window();
    });

    $('#popup_overlay_registration').click(function () {
        registration_view_window();
    });

    $('.close_pop_up_registration').click(function () {
        registration_view_window();
    });

    function registration_view_window()
    {
        $('.errors_registration').hide();

        if (!$('#pop_up_registration').hasClass('first_step_registration')) {
            $('#pop_up_registration').removeClass('second_step_registration').addClass('first_step_registration');
        }

        $('#reg_form').trigger('reset').show();
        $('#confirm_reg_form').trigger('reset').hide();
        $('#pop_up_registration').toggle();
        $('#popup_overlay_registration').toggle();
    }

    $("#reg_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_reg_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $data_reg_form = $('#reg_form').serialize();
            $('.errors_registration').hide();

            $.post("/authorization/registration", $data_reg_form, function (event) {
                if (event == 'ok') {
                    $('#pop_up_registration').removeClass('first_step_registration').addClass('second_step_registration');
                    $('#reg_form').hide();
                    $('#confirm_reg_form').show();
                } else {
                    $('.errors_registration').html(event);
                    $('.errors_registration').show().delay(5000).fadeOut(1000);
                }
            });
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_registration').html(error_mess);
                $('.errors_registration').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_reg_form() {
        var error_mass = new Array();

        var name = $('#reg_form #name_reg_form').val();
        var last_name = $('#reg_form #last_name_reg_form').val();
        var email_user = $('#reg_form #email_user_reg_form').val();
        var pass = $('#reg_form #pass_reg_form').val();
        var confirm_pass = $('#reg_form #confirm_pass_reg_form').val();
        var phone = $('#reg_form #phone_reg_form').val();

        if (name == '') {
            error_mass[error_mass.length] = 'Заполните поле "Имя"';
        }

        if (last_name == '') {
            error_mass[error_mass.length] = 'Заполните поле "Фамилия"';
        }

        if (email_user == '') {
            error_mass[error_mass.length] = 'Заполните поле "Email"';
        }

        if (pass == '') {
            error_mass[error_mass.length] = 'Заполните поле "Пароль"';
        }

        if (pass.length < 6 && pass != '') {
            error_mass[error_mass.length] = 'Длина поля "Пароль" должна быть не меньше 6 символов';
        }

        if (confirm_pass == '') {
            error_mass[error_mass.length] = 'Заполните поле "Подтвердить пароль"';
        }

        if (pass != confirm_pass) {
            error_mass[error_mass.length] = 'Поля "Пароль" и "Подтвердить пароль" должны совпадать';
        }

        if (phone == '') {
            error_mass[error_mass.length] = 'Заполните поле "Телефон"';
        }

        if (phone.length < 11 && phone != '') {
            error_mass[error_mass.length] = 'Длина поля "Телефон" должна быть не меньше 11 символов';
        }

        return error_mass;
    }

    $("#confirm_reg_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_confirm_reg_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_registration').hide();

            $.post("/authorization/confirm_reg", $data_reg_form + '&code_confirm=' + $('#confirm_reg_form #code_confirm').val(), function (event) {
                if (event == 'ok') {
                    window.location.replace("/generate_widget/sms_history_list");
                } else {
                    $('.errors_registration').html(event);
                    $('.errors_registration').show().delay(5000).fadeOut(1000);
                }
            });
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_registration').html(error_mess);
                $('.errors_registration').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_confirm_reg_form() {
        var error_mass = new Array();

        if ($('#confirm_reg_form #code_confirm').val() == '') {
            error_mass[error_mass.length] = 'Заполните поле "Ведите код подтверждения"';
        }

        return error_mass;
    }
});

