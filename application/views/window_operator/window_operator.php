<!doctype html>
<html lang="en-us">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link href='http://fonts.googleapis.com/css?family=Tinos&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?= base_url("css/style_window_operator.css"); ?>">
        <script type="text/javascript" src="<?= base_url('js/jquery-1.11.1.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('js/window_script.js'); ?>"></script>
    </head>
    <body class="conversioner_contact_window_body">
        <div id="conversioner_contact_window">
            <div class="conversioner_title_window">
                <h2>
                    <?= $title_window; ?>
                </h2>
            </div>
            <div>
                <form id="conversioner_contact_form" name="conversioner_contact_form" method="post" autocomplete="off">
                    <div class="conversioner_img_window">
                        <img src="<?= base_url('uploads/img_contact_window/' . $img_window); ?>">
                    </div>
                    <div class="conversioner_content_window">
                        <?= $content_window; ?>
                    </div>
                    <input type="text" id="conversioner_number_phone" class="placeholder" value="Введите номер своего телефона" name="conversioner_number_phone">
                    <input type="hidden" name="conversioner_code" value="<?= $conversioner_code ?>">
                    <input type="hidden" name="conversioner_email" value="">
                    <input type="hidden" name="conversioner_phone" value="">
                    <button class="conversioner_send_phone">
                        <?= $text_button; ?>
                    </button>
                </form>
            </div>
            <div class="conversioner_copyright">
                Установите на ваш сайт «<a href="http://conversioner.ru" target="_blank">конвертик</a>»!
            </div>
        </div>
    </body>
</html>