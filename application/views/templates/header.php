<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title><?php echo $title ?></title>
        <link href="<?= base_url("favicon1.ico"); ?>" rel="shortcut icon">
        <link rel="stylesheet" type="text/css" href="<?= base_url("css/style.css"); ?>">
        <link href='http://fonts.googleapis.com/css?family=Tinos&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
        <?php if (isset($other_css)): ?>
            <?php foreach ($other_css as $filename): ?>
                <link rel="stylesheet" type="text/css" href="<?= base_url($filename); ?>">
            <?php endforeach; ?>
        <?php endif; ?>
        <script type="text/javascript" src="<?= base_url('js/jquery-1.11.1.js'); ?>"></script>
        <?php if (isset($other_js)): ?>
            <?php foreach ($other_js as $filename): ?>
                <script type="text/javascript" src="<?= base_url($filename); ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    </head>
    <body <?php if ($this->uri->segment(2) == 'login') { ?>class="login_page_body"<?php } ?>>
        <div class="container">
            <?php if ($this->uri->segment(2) != 'login') { ?>
                <header>
                    <?php if ($this->session->userdata('id_user') and $this->session->userdata('type_user') == 'user') { ?>
                        <div class="account_info_body">
                            <div class="account_balance">
                                <a href="javascript:void(0);" id="order_tariff">Баланс</a>: осталось <?= $balance_user ?> смс
                            </div>
                            <div class="account_info">
                                Здравствуйте, 
                                <a href="javascript:void(0);" id='edit_user_data'>
                                    <span id="user_name_aut"><?php echo $this->session->userdata('name') . ' ' . $this->session->userdata('last_name') . '!'; ?></span>
                                </a>
                                <span id="user_email_aut"><?php echo '(' . $this->session->userdata('email') . ')' ?></span>
                                <a href="<?= base_url("authorization/logout"); ?>">Выйти</a>
                            </div>
                        </div>
                        <div id="popup_overlay_order_tariff"></div>
                        <div id="pop_up_order_tariff">
                            <div class="close_pop_up_order_tariff"></div>
                            <?= $order_tariff_window ?>
                        </div>
                    <?php } ?>
                    <div class="title_and_menu">
                        <h1>«Конверсионер» &mdash; инструмент удержания клиентов.
                            <span>Установите «конвертик» на ваш веб-сайт и удерживайте до 75% больше клиентов.</span>
                        </h1>	
                        <nav class="main_menu">
                            <?php if($this->uri->segment(2) == 'sms_history_list') { ?>
                                <span class="selected_menu">Собранные контакты</span>
                            <?php }else{ ?>
                                <a href="<?= base_url("generate_widget/sms_history_list"); ?>">Собранные контакты</a>
                            <?php } ?>
                            <?php if($this->uri->segment(2) == 'widgets') { ?>
                                <span class="selected_menu">Мои «конвертики»</span>
                            <?php }else{ ?>
                                <a href="<?= base_url("generate_widget/widgets"); ?>">Мои «конвертики»</a>
                            <?php } ?>
                        </nav>
                    </div>
                </header>
                <div id="popup_overlay_edit_user_data"></div>
                <div id="pop_up_edit_user_data">
                    <div class="close_pop_up_edit_user_data"></div>
                    <?= $edit_user_data_form ?>
                </div>
            <?php } ?>