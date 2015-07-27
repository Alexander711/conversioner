<div class="errors_add_widget"></div>

<form action="" method="post" id="add_widget_form">
    <input type="hidden" name="name_uploaded_image" />
    <div style="width: 630px;">
        <div style="float: left; padding: 5px;">
            <input name="id" type="hidden" value=""/>
            <label class="label" for="site_url"><span class="required">*</span>Ссылка на сайт</label>
            <input class="input" name="site_url" id="site_url_add_widget_form" value=""/>
            <label class="label" for="phone"><span class="required">*</span>Номер телефона</label>
            <input class="input" name="phone" id="phone_add_widget_form" value=""/>
            <label class="label" for="email"><span class="required">*</span>Email отдела продаж</label>
            <input class="input" name="email" id="email_add_widget_form" value=""/>
            <label class="label" for="title_window">Заголовок окна</label>
            <textarea class="textarea" name="title_window" rows="2" cols="30" maxlength="49" id="title_window_add_widget_form"></textarea>
            <input type="checkbox" class="checkbox" name="detect_exit" id="detect_exit_add_widget_form" value="1"> <label for="detect_exit_add_widget_form">Ловить клиентов на входе</label><br><br>
        </div>
        <div style="float: left; padding: 5px;">
            <label class="label" for="content_window">Текст окна</label>
            <textarea class="textarea" name="content_window" rows="3" cols="30" maxlength="98" id="content_window_add_widget_form"></textarea>
            <div class="change_img_operator">
                <label class="label">Выберите изображение виджета</label>
                <div class="change_img_operator_body">
                    <div style="float: left;">
                        <input type="radio" class="radio" name="img_window" value="wom" checked="checked"><img src="<?= base_url("uploads/img_contact_window/wom.jpg"); ?>">
                        <input type="radio" class="radio" name="img_window" value="man"><img src="<?= base_url("uploads/img_contact_window/man.jpg"); ?>">
                    </div>
                    <div style="float: left; padding-left: 10px;height: 52px">
                        <input type="file" name="file_upload" id="file_upload" />
                    </div>
                    <div style="clear: both;"></div>
                    <div id="queue_uploadify"></div>
                    <div class="img_upload_success">Используется картинка пользователя!</div>
                </div>
            </div>
            <label class="label" for="text_button">Текст кнопки</label>
            <input class="input" name="text_button" id="text_button_add_widget_form" value=""/>
            <label class="label" for="time_attention">Время появления attention-окошка (в секундах)</label>
            <input class="input" name="time_attention" id="time_attention_add_widget_form" value=""/>
        </div>
        <div style="clear: both;"></div>
        <span>Вставьте &lt;br&gt; там, где хотите сделать перенос на новую строку (использовать можно только для "Заголовок окна" и "Текст окна")</span>
    </div>
    <br>
    <input type="submit" class="btn" value="Отправить">
</form>