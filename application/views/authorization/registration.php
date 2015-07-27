<div class="errors_registration"></div>

<form action="" method="post" id="reg_form">
    <label class="label" for="name_reg_form"><span class="required">*</span>Имя</label>
    <input name="name" id="name_reg_form" class="input" value=""/>
    <label class="label" for="last_name_reg_form"><span class="required">*</span>Фамилия</label>
    <input name="last_name" id="last_name_reg_form" class="input" value=""/>
    <label class="label" for="email_user_reg_form"><span class="required">*</span>Email</label>
    <input name="email_user" id="email_user_reg_form" class="input" value=""/>
    <label class="label" for="pass_reg_form"><span class="required">*</span>Пароль</label>
    <input name="pass" id="pass_reg_form" class="input" type="password" value=""/>
    <label class="label" for="confirm_pass_reg_form"><span class="required">*</span>Подтвердить пароль</label>
    <input name="confirm_pass" id="confirm_pass_reg_form" class="input" type="password" value=""/>
    <label class="label" for="phone_reg_form"><span class="required">*</span>Телефон</label>
    <input name="phone" id="phone_reg_form" class="input" value=""/>
    <br>
    <br>
    <input type="submit" class="btn" value="Зарегистрироваться">
</form>

<form action="confirm_reg" method="post" id="confirm_reg_form" autocomplete="off">
    <label class="label" for="code_confirm"><span class="required">*</span>Ведите код подтверждения:</label>
    <input name="code_confirm" class="input" id="code_confirm"/>
    <br>
    <br>
    <input type="submit" class="btn" value="Отправить">
</form>