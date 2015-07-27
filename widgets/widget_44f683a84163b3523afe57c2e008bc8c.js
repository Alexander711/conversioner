$(function () {
    var is_active = "0";
    var work_days = [1,2,3,4,5,6,0];
    var time_start = "0";
    var time_end = "1439";
    var detect_exit = "1";
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
        $('head').append('<link href=\"http://www.conversioner.ru/css/style_window_operator.css\" type=\"text/css\" rel=\"stylesheet\" />');

        var iframe = '<div id=\"conversioner_contact_window_and_overlay\"> \
                        <div id=\"conversioner_contact_window_body\"> \
                           <div class=\"conversioner_close_contact_window\" onclick=\"conversioner_view_contact_window();\"></div> \
                           <iframe src=\"http://www.conversioner.ru/window_operator/index/46348219990692267192010172782566\" scrolling=\"no\" frameborder=\"no\"> \
                               Ваш браузер не поддерживает iframe! \
                           </iframe> \
                        </div> \
                        <div id=\"conversioner_popup_overlay\" class=\"conversioner_popup_overlay\" onclick=\"conversioner_view_contact_window();\"></div> \
                      </div> \
                      <div id=\"conversioner_attention_window\" class=\"conversioner-cbh-phone conversioner-cbh-green\" onclick=\"conversioner_view_contact_window();\"> \
                        <div class=\"conversioner-cbh-ph-circle\"></div> \
                        <div class=\"conversioner-cbh-ph-circle-fill\"></div> \
                        <div class=\"conversioner-cbh-ph-img-circle\"></div> \
                      </div>';

        $('body').append(iframe);

        $('.conversioner_close_contact_window').css('z-index', max_z_index + 2);
        $('#conversioner_contact_window_body').css('z-index', max_z_index + 2);
        $('.conversioner_popup_overlay').css('z-index', max_z_index + 1);
        $('.conversioner-cbh-phone').css('z-index', max_z_index + 2);

        setTimeout(conversioner_show_attention_window, 0);

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

function conversioner_show_attention_window()
{
    $('#conversioner_attention_window').css('display', 'block');
}

function conversioner_view_contact_window()
{
    $('#conversioner_contact_window_and_overlay').toggle();
    $('#conversioner_attention_window').toggle();
}