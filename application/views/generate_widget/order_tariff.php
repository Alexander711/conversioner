<h3>Выберите тариф</h3>

<div class="errors_order_tariff_form"></div>
<div class="success_message_order_tariff_form">Заявка отправлена</div>

<form action="" method="post" id="order_tariff_form">
    <select name="tariff_id" class="input">
        <option value="">Выберите тариф</option>
        <?php foreach ($tariffs_list as $tariff): ?>
            <option value="<?= $tariff['id'] ?>"><?php echo $tariff['name_tariff'].'('.$tariff['sum'].')' ?></option>
        <?php endforeach ?>
    </select>
    <br><br>
    <input type="submit" class="btn" value="Выбрать">
</form>