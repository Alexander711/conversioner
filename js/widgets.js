$(function () {

    $(document).on("click", ".delete_widget", function () {
        if (confirm("Вы уверены что хотите удалить?")) {
            var id_widget = $(this).data('id_widget');

            $.post("/generate_widget/delete_widget", {'id_widget': id_widget}, function (event) {
                $('#widget_row_' + id_widget).fadeOut(300);
            });
        }
        ;
    });

    $(document).on("click", ".active_status", function () {
        var id_widget = $(this).data('id_widget');
        var active_status = $(this).data('active_status');

        $.post("/generate_widget/change_active_widget", {id_widget: id_widget, active_status: active_status}, function (event) {
            if (event == 'ok' && active_status == 1) {
                if (!$('#activated_' + id_widget).hasClass('active_status_hide')) {
                    $('#activated_' + id_widget).addClass('active_status_hide');
                }

                $('#not_activated_' + id_widget).removeClass('active_status_hide');

            } else if (event == 'ok' && active_status == 0) {
                if (!$('#not_activated_' + id_widget).hasClass('active_status_hide')) {
                    $('#not_activated_' + id_widget).addClass('active_status_hide');
                }

                $('#activated_' + id_widget).removeClass('active_status_hide');
            }
        });
    });

    $(document).on("click", ".installation_check", function () {
        var id_widget = $(this).data('id_widget');

        $.post("/generate_widget/installation_check", {'id_widget': id_widget}, function (event) {
            if (event == 'installed') {
                $('#installation_check_' + id_widget).html('<img src="/images/icon_ok_green.png" title="Проверить установку виджета(виджет установлен)">');
            } else if (event == 'not installed') {
                $('#installation_check_' + id_widget).html('<img src="/images/gearblue.png" title="Проверить установку виджета(виджет не установлен)">');
            }
        });
    });

    $("#add_widget_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_add_widget_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $('.errors_add_widget').hide();

            $.post("/generate_widget/add_widget", $('#add_widget_form').serialize(), function (event) {

                if (event.status_ajax == 'ok') {
                    add_widget_view_window();
                    $('.empty_list').remove();

                    if (event.is_active == 1) {
                        var active_status = '<a href="javascript:void(0);" id="not_activated_' + event.id_widget + '" class="active_status" data-active_status="0" data-id_widget="' + event.id_widget + '"> \
                                                Отключить \
                                            </a> \
                                            <a href="javascript:void(0);" id="activated_' + event.id_widget + '" class="active_status active_status_hide" data-active_status="1" data-id_widget="' + event.id_widget + '"> \
                                                Включить \
                                            </a>';
                    } else {
                        var active_status = '<a href="javascript:void(0);" id="activated_' + event.id_widget + '" class="active_status" data-active_status="1" data-id_widget="' + event.id_widget + '"> \
                                                Включить \
                                            </a> \
                                            <a href="javascript:void(0);" id="not_activated_' + event.id_widget + '" class="active_status active_status_hide" data-active_status="0" data-id_widget="' + event.id_widget + '"> \
                                                Отключить \
                                            </a>';
                    }

                    if (event.is_installed == 0) {
                        var instal_status = '<img src="/images/gearblue.png" title="Проверить установку виджета(виджет не установлен)">';
                    } else {
                        var instal_status = '<img src="/images/icon_ok_green.png" title="Проверить установку виджета(виджет установлен)">';
                    }

                    var row = '<tr id="widget_row_' + event.id_widget + '"> \
                                    <td>' + event.site_url + '</td> \
                                    <td>' + event.email + '</td> \
                                    <td class="user-mobile">' + event.phone + '</td> \
                                    <td>'
                            + active_status +
                            '</td> \
                                    <td> \
                                        <a class="installation_check" id="installation_check" data-id_widget="' + event.id_widget + '" href="javascript:void(0);">'
                            + instal_status +
                            '</a> \
                                        <a href="javascript:void(0);" class="install_widget" data-id_widget="' + event.id_widget + '"> \
                                            <img src="/images/install_widget.png" title=\'Установить "Конвертик"\'> \
                                        </a> \
                                        <a href="javascript:void(0);" class="edit_widget" data-id_widget="' + event.id_widget + '"> \
                                            <img src="/images/edit.png" title="Редактировать"> \
                                        </a> \
                                        <a class="delete_widget" data-id_widget="' + event.id_widget + '" href="javascript:void(0);"> \
                                            <img src="/images/delete.png" title="Удалить"> \
                                        </a> \
                                    </td> \
                               </tr>';
                    if (event.is_update == 0) {
                        $('#widget_row_' + event.id_widget).remove();
                        $('.widgets_list').append(row);
                        $('.add_widget_success').html('"Конвертик" создан. Перейдите по ссылке "Установить \'Конвертик\'", чтобы установить его на Ваш сайт').show().delay(5000).fadeOut(300);
                    } else {
                        $('#widget_row_' + event.id_widget).replaceWith(row);
                        $('.add_widget_success').html('"Конвертик" изменен. Перейдите по ссылке "Установить \'Конвертик\'", чтобы установить его на Ваш сайт').show().delay(5000).fadeOut(300);
                    }

                    $('#add_widget_form').trigger('reset');
                    $("input[name='id']", '#add_widget_form').val(0);
                    $("input[name='name_uploaded_image']", '#add_widget_form').val('');
                } else {
                    $('.errors_add_widget').html(event.error_mass);
                    $('.errors_add_widget').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>'
                $('.errors_add_widget').html(error_mess);
                $('.errors_add_widget').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_add_widget_form() {
        var error_mass = new Array();

        var phone = $('#add_widget_form #phone_add_widget_form').val();
        var email = $('#add_widget_form #email_add_widget_form').val();
        var title_window = $("#add_widget_form #title_window_add_widget_form").val();
        var content_window = $("#add_widget_form #content_window_add_widget_form").val();
        var text_button = $("#add_widget_form #text_button_add_widget_form").val();
        var site_url = $('#add_widget_form #site_url_add_widget_form').val();

        if (phone == '') {
            error_mass[error_mass.length] = 'Заполните поле "Номер телефона"';
        }

        if (email == '') {
            error_mass[error_mass.length] = 'Заполните поле "Email отдела продаж"';
        }

        if (title_window.length > 49) {
            error_mass[error_mass.length] = 'Длина заголовка окна не должна превышать 49 символов';
        }

        if (content_window.length > 98) {
            error_mass[error_mass.length] = 'Длина контента окна не должна превышать 98 символов';
        }

        if (text_button.length > 28) {
            error_mass[error_mass.length] = 'Длина текста кнопки не должна превышать 28 символов';
        }

        if (site_url == '') {
            error_mass[error_mass.length] = 'Заполните поле "Ссылка на сайт"';
        }

        return error_mass;
    }

    $('#add_widget').click(function () {
        $('#add_widget_form').trigger('reset');
        $("input[name='id']", '#add_widget_form').val(0);
        add_widget_view_window();
    });

    $('#popup_overlay_add_widget').click(function () {
        add_widget_view_window();
    });

    $('.close_pop_up_add_widget').click(function () {
        add_widget_view_window();
    });

    $(document).on("click", ".edit_widget", function () {
        $.post("/generate_widget/get_options_widget", {'id_widget': $(this).data('id_widget')}, function (event) {
            if (event == 'widget_not_found') {
                alert('Такого виджета не существует!');
            } else {
                add_widget_view_window();
                $("input[name='id']", '#add_widget_form').val(event.id);
                $("input[name='site_url']", '#add_widget_form').val(event.site_url);
                $("input[name='phone']", '#add_widget_form').val(event.phone);
                $("input[name='email']", '#add_widget_form').val(event.email);
                $("textarea[name='title_window']", '#add_widget_form').val(event.title_window);
                $("textarea[name='content_window']", '#add_widget_form').val(event.content_window);
                if (event.img_window == 'wom.jpg') {
                    $("input[name='img_window'][value='wom']", '#add_widget_form').prop('checked', true);
                } else if (event.img_window == 'man.jpg') {
                    $("input[name='img_window'][value='man']", '#add_widget_form').prop('checked', true);
                } else {
                    $("input[name='img_window']", "#add_widget_form").prop('checked', false);
                    $(".change_img_operator_body .img_upload_success").show();
                    $("input[name='name_uploaded_image']", '#add_widget_form').val(event.img_window);
                }

                $("input[name='text_button']", '#add_widget_form').val(event.text_button);
                $("input[name='time_attention']", '#add_widget_form').val(event.time_attention / 1000);
                if (event.detect_exit == 1) {
                    $("input[name='detect_exit']", '#add_widget_form').attr("checked", "checked");
                }
            }
        }, "json");
    });

    function add_widget_view_window()
    {
        $("input[name='name_uploaded_image']", '#add_widget_form').val('');
        $(".change_img_operator_body .img_upload_success").hide();
        $('#pop_up_add_widget').toggle();
        $('#popup_overlay_add_widget').toggle();
    }

    $("#instruction_progr_form").submit(function (e) {
        e.preventDefault();

        if ($('#email_progr').val() != '') {
            $('.errors_instr_progr_form').hide();

            $.post("/generate_widget/instruction_programmer", $('#instruction_progr_form').serialize(), function (event) {
                if (event == 'ok') {
                    $('.success_message_instr_progr').show().delay(5000).fadeOut(1000);
                } else {
                    $('.errors_instr_progr_form').html(event);
                    $('.errors_instr_progr_form').show().delay(5000).fadeOut(1000);
                }
            });
        } else {
            $('.errors_instr_progr_form').html('<span>Введите Email программиста</span><br/>');
            $('.errors_instr_progr_form').show().delay(5000).fadeOut(1000);
        }
    });

    $(document).on("click", ".install_widget", function () {
        var id_widget = $(this).data('id_widget');

        $.post("/generate_widget/install_widget", {'id_widget': id_widget}, function (event) {
            if (event == 'error_install_widget') {
                alert('Такого конвертика не существует!');
            } else {
                $('.site_url_install_widget').html(event.site_url);
                $('.link_widget_install_widget').html(event.id_widget);
                $('.conv_code_install_widget').html(event.conversioner_code);

                if (event.detect_exit == 1) {
                    $('.detect_exit_is_active').show();
                } else {
                    $('.detect_exit_is_active').hide();
                }

                $("input[name='id_widget']", '#instruction_progr_form').val(id_widget);

                get_time_work_widget(id_widget);

                install_widget_view_window();
            }
        }, "json");
    });

    $('#popup_overlay_install_widget').click(function () {
        install_widget_view_window();
    });

    $('.close_pop_up_install_widget').click(function () {
        install_widget_view_window();
    });

    function install_widget_view_window() {
        $('#instruction_progr_form').trigger('reset');
        $("select[name='time_start']", '#time_work_form').prop('selectedIndex', 0);
        $("select[name='time_end']", '#time_work_form').prop('selectedIndex', 0);
        $('#pop_up_install_widget').toggle();
        $('#popup_overlay_install_widget').toggle();
    }

    function get_time_work_widget(id_widget) {
        $('#time_work_form').trigger('reset');

        $.post("/generate_widget/get_time_work_widget", {'id_widget': id_widget}, function (event) {
            if (event == 'error_get_time_work') {
                alert('Такого конвертика не существует!');
            } else {
                $("input[name='id_widget']", '#time_work_form').val(id_widget);

                $('#time_work_form input:checkbox').each(function (n, element)
                {
                    if ($.inArray($(element).val(), event.work_days) != -1) {
                        $(element).prop('checked', true);
                    }
                });

                $("select[name='time_start'] option", '#time_work_form').each(function (n, element) {
                    if ($(element).val() == event.time_start) {
                        $(element).prop('selected', true);
                    }
                });

                $("select[name='time_end'] option", '#time_work_form').each(function (n, element) {
                    if ($(element).val() == event.time_end) {
                        $(element).prop('selected', true);
                    }
                });
            }
        }, "json");
    }

    $("#time_work_form").submit(function (e) {
        e.preventDefault();

        var error_mass = validate_time_work_form();
        var error_mess = '';

        if (error_mass.length == 0) {
            $.post("/generate_widget/time_work", $('#time_work_form').serialize(), function (event) {
                if (event.status_ajax == 'error') {
                    $('.errors_time_work_form').html(event.error_mass);
                    $('.errors_time_work_form').show().delay(5000).fadeOut(1000);
                } else {
                    $('.success_message_time_work').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            for (i = 0; i < error_mass.length; i++) {
                error_mess = error_mess + '<span>' + error_mass[i] + '</span><br/>'
                $('.errors_time_work_form').html(error_mess);
                $('.errors_time_work_form').show().delay(5000).fadeOut(1000);
            }
        }
    });

    function validate_time_work_form() {
        var error_mass = new Array();
        var time_start = $("select[name='time_start']", '#time_work_form').val();
        var time_end = $("select[name='time_end']", '#time_work_form').val();

        if (typeof $("input:checkbox:checked", '#time_work_form').val() == 'undefined') {
            error_mass[error_mass.length] = 'Выберите хоть один рабочий день';
        }

        if (time_start > time_end) {
            error_mass[error_mass.length] = 'Время окончания работы не должно быть больше времени начала работы';
        }

        return error_mass;
    }

    $('#order_tariff').click(function () {
        order_tariff_view_window();
    });

    $('#popup_overlay_order_tariff').click(function () {
        order_tariff_view_window();
    });

    $('.close_pop_up_order_tariff').click(function () {
        order_tariff_view_window();
    });

    function order_tariff_view_window()
    {
        $('#pop_up_order_tariff').toggle();
        $('#popup_overlay_order_tariff').toggle();
    }

    $("#order_tariff_form").submit(function (e) {
        e.preventDefault();

        if ($("select[name='tariff_id']", '#order_tariff_form').val() != '') {
            $('.errors_order_tariff_form').hide();

            $.post("/generate_widget/order_tariff", $('#order_tariff_form').serialize(), function (event) {
                if (event.status_ajax == 'ok') {
                    $('.success_message_order_tariff_form').show().delay(5000).fadeOut(1000);
                } else {
                    $('.errors_order_tariff_form').html(event.error_mass);
                    $('.errors_order_tariff_form').show().delay(5000).fadeOut(1000);
                }
            }, "json");
        } else {
            $('.errors_order_tariff_form').html('<span>Выберите тариф</span><br/>');
            $('.errors_order_tariff_form').show().delay(5000).fadeOut(1000);
        }
    });

    $("input[name='img_window']", "#add_widget_form").change(function () {
        var name_uploaded_image = $("input[name='name_uploaded_image']", '#add_widget_form').val();
        var id_widget = $("input[name='id']", '#add_widget_form').val();
        $(".change_img_operator_body .img_upload_success").hide();

        if (name_uploaded_image != '' && id_widget == 0) {
            del_temporary_upload_img(name_uploaded_image);
        }

        if (name_uploaded_image != '' && id_widget != 0) {
            $("input[name='name_uploaded_image']", '#add_widget_form').val('');
        }
    });
});

function del_temporary_upload_img(name_uploaded_image) {
    $.post('/generate_widget/del_temporary_upload_img', {'name_uploaded_image': name_uploaded_image}, function (event) {
        if (event == 'ok') {
            $("input[name='name_uploaded_image']", '#add_widget_form').val('');
        }
    }, "json");
}

