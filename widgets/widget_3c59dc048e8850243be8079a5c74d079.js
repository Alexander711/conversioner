$(function () {
    var is_active = "1";
    var work_days = [1,2,3,4,5];
    var time_start = "0";
    var time_end = "1439";
    var detect_exit = "0";
    var date = new Date();
    var day_now = date.getDay();
    var time_now = date.getHours() * 60 + date.getMinutes();
    var max_z_index = 0;

    $('*').each(function () {
        var z_index = Number($(this).css('z-index'));
        if (z_index > max_z_index) {
            max_z_index = z_index;
        }
    })

    if((is_active == 1) && ($.inArray(day_now, work_days) != -1) && (time_now>=time_start && time_now<=time_end)) {
        $('head').append('<link href=\"http://conversioner.ru/css/style_window_operator.css\" type=\"text/css\" rel=\"stylesheet\" />');

        var iframe = '<div id=\"conversioner_contact_window_and_overlay\"> \
                        <div id=\"conversioner_contact_window_body\"> \
                           <div class=\"conversioner_close_contact_window\"></div> \
                           <iframe src=\"http://conversioner.ru/window_operator/index/39875447260327197083517578972075\" scrolling=\"no\" frameborder=\"no\"> \
                               Ваш браузер не поддерживает iframe! \
                           </iframe> \
                        </div> \
                        <div id=\"conversioner_popup_overlay\" class=\"conversioner_popup_overlay\"></div> \
                      </div> \
                      <div id=\"conversioner_attention_window_and_overlay\"></div>';

        $('body').append(iframe);

        $('.conversioner_close_contact_window').css('z-index', max_z_index + 2);
        $('#conversioner_contact_window_body').css('z-index', max_z_index + 2);
        $('.conversioner_popup_overlay').css('z-index', max_z_index + 1);

        setTimeout(conversioner_add_attention_window_and_overlay(max_z_index), 0);

        $(document).on("click", ".conversioner_close_contact_window", function () {
            conversioner_add_attention_window_and_overlay(max_z_index);
            conversioner_hide_contact_window();
        });

        $(document).on("click", "#conversioner_popup_overlay", function () {
            conversioner_add_attention_window_and_overlay(max_z_index);
            conversioner_hide_contact_window();
        });

        $(document).on("mouseover", ".conversioner_attention_overlay", function () {
            $('#conversioner_attention_window_iframe').css('opacity', 1);
        });

        $(document).on("mouseout", ".conversioner_attention_overlay", function () {
            $('#conversioner_attention_window_iframe').css('opacity', 0.5);
        });

        $(document).on("click", ".conversioner_attention_overlay", function () {
            conversioner_view_contact_window();
        });

        if (detect_exit == 1) {
            ouibounce(document.getElementById('conversioner_contact_window_and_overlay'), {
                delay: 10000,
                sitewide: true,
                cookieName: 'oui_conversioner_off',
                cookieExpire: 365,
                timer: 0,
                callback: function () {
                    $('#conversioner_attention_window').toggle();
                }
            });
        }
    }
})

function conversioner_add_attention_window_and_overlay(max_z_index)
{
    $('#conversioner_attention_window_and_overlay').html('<iframe src=\"http://conversioner.ru/window_operator/attention_window\" id=\"conversioner_attention_window_iframe\" scrolling=\"no\" frameborder=\"no\"> \
                                                              Ваш браузер не поддерживает iframe! \
                                                          </iframe> \
                                                          <div class=\"conversioner_attention_overlay\"></div>');

    $('#conversioner_attention_window_iframe').css({'z-index': max_z_index + 2,'display':'block'});

    $('.conversioner_attention_overlay').css({'z-index': max_z_index + 3,'display':'block'});
}

function conversioner_view_contact_window()
{
    $('#conversioner_contact_window_and_overlay').show();
    $('#conversioner_attention_window_and_overlay').html('');
}

function conversioner_hide_contact_window()
{
    $('#conversioner_contact_window_and_overlay').hide();
}