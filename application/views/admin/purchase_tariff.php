<h3>Выберите тариф для пользователя</h3>

<div class="errors_purchase_tariff_form"></div>
<div class="success_message_purchase_tariff_form">Тариф преобретен</div>

<form action="" method="post" id="purchase_tariff_form">
    <input name="id_user" id="id_user" type="hidden" value=""/>
    <select name="tariff_id" class="input">
        <option value="">Выберите тариф</option>
        <?php foreach ($tariffs_list as $tariff): ?>
            <option value="<?= $tariff['id'] ?>"><?php echo $tariff['name_tariff'].'('.$tariff['sum'].')' ?></option>
        <?php endforeach ?>
    </select>
    <br><br>
    <input type="submit" class="btn" value="Выбрать">
</form>