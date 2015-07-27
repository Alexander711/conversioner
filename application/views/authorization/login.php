<div class="login_page">
    <div id="popup_overlay_password_recovery"></div>
    <div id="pop_up_password_recovery">
        <div class="close_pop_up_password_recovery"></div>
        <?= $password_recovery_form ?>
    </div>

    <div id="popup_overlay_registration"></div>
    <div id="pop_up_registration">
        <div class="close_pop_up_registration"></div>
        <?= $registration_form ?>
    </div>

    <div class="cbh-phone cbh-green">
        <div class="cbh-ph-circle"></div>
        <div class="cbh-ph-circle-fill"></div>
        <div class="cbh-ph-img-circle"></div>
    </div>
    <h1>
        «Конверсионер» — инструмент захвата клиентских контактов.
    </h1>
    <br>
    <h3>	
        Установите «конвертик» на веб-сайт и мгновенно получайте контакты клиентов на свой мобильный.
    </h3>
    <br/>
    <div class="errors_login_form"></div>
    <br/>
    <div class="main">
        <form action="" method="post" id="login_form" class="login_form">
            <p class="field">
                <input type="text" name="email_user" id="email_user_login_form" placeholder="E-mail" value="">
                <i class="icon-user icon-large"></i>
            </p>
            <p class="field">
                <input type="password" name="pass" id="pass_login_form" placeholder="Password" value="">
                <i class="icon-lock icon-large"></i>
            </p>
            <p class="submit">
                <button type="submit" name="submit"><i class="icon-arrow-right icon-large"></i></button>
            </p>
        </form>
        <div class="reg_and_rass_rec_links">
            <a id="password_recovery" href="javascript:void(0);">Забыли пароль?</a>
        </div>
    </div>
    <br/>
    <font size="-1"><a href="#">Регистрация временно приостановлена</a> :-(</font>
</div>
