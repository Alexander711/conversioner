$(function () {
    $("#login_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_login_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_login_form').hide();

            $.post("/admin/authorization/login", $('#login_form').serialize(), function (event) {
                if (event == 'ok') {
                    window.location.replace("/admin/purchase_tariff");
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

    $("#add_user_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_add_user_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $data_add_user_form = $('#add_user_form').serialize();
            $('.errors_add_user').hide();

            $.post("/admin/authorization/add_user", $data_add_user_form, function (event) {
                if (event.status_ajax == 'ok') {
                    $('.success_message_add_user').show().delay(5000).fadeOut(1000);
                } else {
                    $('.errors_add_user').html(event.error_mass);
                    $('.errors_add_user').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_add_user').html(error_mess);
                $('.errors_add_user').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_add_user_form() {
        var error_mass = new Array();

        var name = $('#add_user_form #name_add_user_form').val();
        var last_name = $('#add_user_form #last_name_add_user_form').val();
        var email_user = $('#add_user_form #email_user_add_user_form').val();
        var pass = $('#add_user_form #pass_add_user_form').val();
        var confirm_pass = $('#add_user_form #confirm_pass_add_user_form').val();

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

        if (pass != confirm_pass) {
            error_mass[error_mass.length] = 'Поля "Пароль" и "Подтвердить пароль" должны совпадать';
        }

        return error_mass;
    }

    $("#edit_user_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_edit_user_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $data_edit_user_form = $('#edit_user_form').serialize();
            $('.errors_edit_user').hide();

            $.post("/admin/authorization/edit_user", $data_edit_user_form, function (event) {
                if (event.status_ajax == 'ok') {
                    $('.success_message_edit_user').show().delay(5000).fadeOut(1000);
                    $('#user_name_adm').html(event.name + ' ' + event.last_name + "!");
                    $('#user_email_adm').html('(' + event.email + ')');
                } else {
                    $('.errors_edit_user').html(event);
                    $('.errors_edit_user').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>';
                $('.errors_edit_user').html(error_mess);
                $('.errors_edit_user').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_edit_user_form() {
        var error_mass = new Array();

        var name = $('#edit_user_form #name_edit_user_form').val();
        var last_name = $('#edit_user_form #last_name_edit_user_form').val();
        var email_user = $('#edit_user_form #email_user_edit_user_form').val();
        var pass = $('#edit_user_form #pass_edit_user_form').val();
        var confirm_pass = $('#edit_user_form #confirm_pass_edit_user_form').val();

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

        return error_mass;
    }

    $('.purchase_tariff').click(function () {
        $('#id_user','#purchase_tariff_form').val($(this).data('id_user'));
        purchase_tariff_view_window();
    });

    $('#popup_overlay_purchase_tariff').click(function () {
        purchase_tariff_view_window();
    });

    $('.close_pop_up_purchase_tariff').click(function () {
        purchase_tariff_view_window();
    });
    
    function purchase_tariff_view_window()
    {
        $('#pop_up_purchase_tariff').toggle();
        $('#popup_overlay_purchase_tariff').toggle();
    }

    $("#purchase_tariff_form").submit(function (e) {
        e.preventDefault();

        var id_user = $("input[name='id_user']", '#purchase_tariff_form').val();

        if ($("select[name='tariff_id']", '#purchase_tariff_form').val() != '') {
            $('.errors_purchase_tariff_form').hide();

            $.post("/admin/purchase_tariff/purchase_tariff_for_user", $('#purchase_tariff_form').serialize(), function (event) {
                if (event.status_ajax == 'ok') {
                    $('.success_message_purchase_tariff_form').show().delay(5000).fadeOut(1000);
                    $('.count_sms_user_'+id_user).html(event.new_count_sms_user);
                } else {
                    $('.errors_purchase_tariff_form').html(event.error_mass);
                    $('.errors_purchase_tariff_form').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            $('.errors_purchase_tariff_form').html('<span>Выберите тариф</span><br/>');
            $('.errors_purchase_tariff_form').show().delay(5000).fadeOut(1000);
        }
    });
});