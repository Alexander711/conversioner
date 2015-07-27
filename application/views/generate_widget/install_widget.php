<strong>Установка "Конвертика" для сайта: <span class="site_url_install_widget"></span></strong>
<p>Для установки скрипта вставьте данные строчки между тегами HEAD на Вашем сайте.</p>
<strong class="all_links_install_widget">
    <?php echo htmlspecialchars('<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>'); ?>
    <br>
    <?php echo htmlspecialchars('<script type="text/javascript" src="' . base_url() . 'widgets/widget_'); ?><span class="link_widget_install_widget"></span><?php echo htmlspecialchars('.js"></script>'); ?>
    <br>
    <?php echo htmlspecialchars('<script type="text/javascript">var conversioner_code="'); ?><span class="conv_code_install_widget"></span><?php echo htmlspecialchars('";</script>'); ?>
    <span class="detect_exit_is_active">
        <br>
        <?php echo htmlspecialchars('<script type="text/javascript" src="' . base_url('js/ouibounce.js') . '"></script>'); ?>
    </span>
</strong>
<br><br>
<form action="" method="post" id="instruction_progr_form">
    <input type="hidden" name="id_widget" id="id_widget" value=""/>
    <label class="label" for="email_progr"><span class="required">*</span>Или отправьте инструкцию своему программисту</label>
    <div class="errors_instr_progr_form"></div>
    <div class="success_message_instr_progr">Инструкция отправлена Вашему программисту</div>
    <input class="input" name="email_progr" id="email_progr" type="text" value="" placeholder="Введите email"/>
    <br><br>
    <input type="submit" class="btn" value="Отправить">
</form>
<br><br>
<div class="errors_time_work_form"></div>
<div class="success_message_time_work">График изменен</div>
<form action="" method="post" id="time_work_form">
    <input type="hidden" name="id_widget" id="id_widget" value=""/>
    <div class="time_work_body">
        <span>Дни работы виджета</span>
        <br>
        <input type="checkbox" class="checkbox" name="work_days[1]" value="1">Понедельник
        <input type="checkbox" class="checkbox" name="work_days[2]" value="2">Вторник
        <input type="checkbox" class="checkbox" name="work_days[3]" value="3">Среда
        <input type="checkbox" class="checkbox" name="work_days[4]" value="4">Четверг
        <input type="checkbox" class="checkbox" name="work_days[5]" value="5">Пятница
        <input type="checkbox" class="checkbox" name="work_days[6]" value="6">Суббота
        <input type="checkbox" class="checkbox" name="work_days[0]" value="0">Воскресенье
        <br><br>
        <span>Время работы виджета</span>
        <br>
        <select name="time_start">
            <?php foreach ($hours as $hour): ?>
                <option value="<?= $hour ?>"><?= $hour ?>:00</option>
            <?php endforeach ?>
        </select>
        <select name="time_end">
            <?php foreach ($hours as $hour): ?>
                <option value="<?= $hour ?>"><?= $hour ?>:59</option>
            <?php endforeach ?>
        </select>
    </div>
    <div style="clear: both;"></div>
    <br>
    <input type="submit" class="btn" value="Изменить">
</form>    
