<div class="component">
    <h3>Редактирование своих данных в админке</h3>
    
    <div class="errors_edit_user"></div>
    <div class="success_message_edit_user">Данные изменены</div>

    <form action="" method="post" id="edit_user_form">
        <label class="label" for="name_edit_user_form"><span class="required">*</span>Имя</label>
        <input name="name" id="name_edit_user_form" class="input" value="<?php if(isset($data_user['name'])){ echo $data_user['name']; }?>"/>
        <label class="label" for="last_name_edit_user_form"><span class="required">*</span>Фамилия</label>
        <input name="last_name" id="last_name_edit_user_form" class="input" value="<?php if(isset($data_user['last_name'])){ echo $data_user['last_name']; }?>"/>
        <label class="label" for="email_user_edit_user_form"><span class="required">*</span>Email</label>
        <input name="email_user" id="email_user_edit_user_form" class="input" value="<?php if(isset($data_user['email'])){ echo $data_user['email']; }?>"/>
        <label class="label" for="pass_edit_user_form">Пароль</label>
        <input name="pass" id="pass_edit_user_form" class="input" type="password" value=""/>
        <label class="label" for="confirm_pass_edit_user_form">Подтвердить пароль</label>
        <input name="confirm_pass" id="confirm_pass_edit_user_form" class="input" type="password" value=""/>
        <br>
        <br>
        <input type="submit" class="btn" value="Отправить">
    </form>
</div>