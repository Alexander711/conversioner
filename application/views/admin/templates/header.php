<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title><?php echo $title ?></title>
        <link href="<?= base_url("favicon.ico"); ?>" rel="shortcut icon">
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
    <body <?php if ($this->uri->segment(3) == 'login') { ?>class="login_page_body"<?php } ?>>
        <div class="container">
            <?php if ($this->uri->segment(3) != 'login') { ?>
                <header>
                    <?php if ($this->session->userdata('id_user') and $this->session->userdata('type_user') == 'admin') { ?>
                        <div class="account_info_body">
                            <div class="account_info">
                                Здравствуйте, 
                                <span id="user_name_adm"><?php echo $this->session->userdata('name') . ' ' . $this->session->userdata('last_name') . '!'; ?></span>
                                <span id="user_email_adm"><?php echo '(' . $this->session->userdata('email') . ')' ?></span>
                                <a href="<?= base_url("admin/authorization/logout"); ?>">Выйти</a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="title_and_menu">
                        <nav class="main_menu">
                            <?php if ($this->uri->segment(2) == 'purchase_tariff') { ?>
                                <span class="selected_menu">Список пользователей</span>
                            <?php } else { ?>
                                <a href="<?= base_url("/admin/purchase_tariff"); ?>">Список пользователей</a>
                            <?php } ?>
                            <?php if ($this->uri->segment(3) == 'add_user') { ?>
                                <span class="selected_menu">Добавить пользователя</span>
                            <?php } else { ?>
                                <a href="<?= base_url("/admin/authorization/add_user"); ?>">Добавить пользователя</a>
                            <?php } ?>
                            <?php if ($this->uri->segment(3) == 'edit_user') { ?>
                                <span class="selected_menu">Изменить свои данные</span>
                            <?php } else { ?>
                                <a href="<?= base_url("/admin/authorization/edit_user"); ?>">Изменить свои данные</a>
                            <?php } ?>
                        </nav>
                    </div>
                </header>
            <?php } ?>