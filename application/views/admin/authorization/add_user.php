<div class="component">
    <h3>Добавление нового пользователя для админки</h3>
    
    <div class="errors_add_user"></div>
    <div class="success_message_add_user">Пользователь удачно создан</div>

    <form action="" method="post" id="add_user_form">
        <label class="label" for="name_add_user_form"><span class="required">*</span>Имя</label>
        <input name="name" id="name_add_user_form" class="input" value=""/>
        <label class="label" for="last_name_add_user_form"><span class="required">*</span>Фамилия</label>
        <input name="last_name" id="last_name_add_user_form" class="input" value=""/>
        <label class="label" for="email_user_add_user_form"><span class="required">*</span>Email</label>
        <input name="email_user" id="email_user_add_user_form" class="input" value=""/>
        <label class="label" for="pass_add_user_form"><span class="required">*</span>Пароль</label>
        <input name="pass" id="pass_add_user_form" class="input" type="password" value=""/>
        <label class="label" for="confirm_pass_add_user_form"><span class="required">*</span>Подтвердить пароль</label>
        <input name="confirm_pass" id="confirm_pass_add_user_form" class="input" type="password" value=""/>
        <br>
        <br>
        <input type="submit" class="btn" value="Отправить">
    </form>
</div>