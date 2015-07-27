<div class="errors_edit_user"></div>

<form action="" method="post" id="edit_user_data_form">
    <label class="label" for="name_edit_user_form"><span class="required">*</span>Имя</label>
    <input class="input" name="name" id="name_edit_user_form" value="<?php if(isset($name)){echo $name;} ?>"/>
    <label class="label" for="last_name_edit_user_form"><span class="required">*</span>Фамилия</label>
    <input class="input" name="last_name" id="last_name_edit_user_form" value="<?php if(isset($last_name)){echo $last_name;} ?>"/>
    <label class="label" for="email_user_edit_user_form"><span class="required">*</span>Email</label>
    <input class="input" name="email_user" id="email_user_edit_user_form" value="<?php if(isset($email)){echo $email;} ?>"/>
    <label class="label" for="pass_edit_user_form">Пароль</label>
    <input class="input" name="pass" id="pass_edit_user_form" type="password" value=""/>
    <label class="label" for="confirm_pass_edit_user_form">Подтвердить пароль</label>
    <input class="input" name="confirm_pass" id="confirm_pass_edit_user_form" type="password" value=""/>
    <label class="label" for="phone_edit_user_form"><span class="required">*</span>Телефон</label>
    <input class="input" name="phone" id="phone_edit_user_form" value="<?php if(isset($phone)){echo $phone;} ?>"/>
    <br/><br/>
    <input type="submit" class="btn" value="Отправить">
</form>