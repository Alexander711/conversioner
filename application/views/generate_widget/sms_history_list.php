<div class="component">
    <form action="" method="post">
        <label for="datepicker_beginning">Звонки с</label>
        <input type="text" name="date_beginning" class="input_sms_history_search" id="datepicker_beginning" value="<?php if(isset($search_data['date_beginning'])) { echo $search_data['date_beginning']; }?>"/>
        <label for="datepicker_end">по</label>
        <input type="text" name="date_end" class="input_sms_history_search" id="datepicker_end" value="<?php if(isset($search_data['date_end'])) { echo $search_data['date_end']; }?>"/>
        <select name="site_url" class="select_sms_history_search">
            <option value="">Все сайты</option>
            <?php foreach ($all_sites as $site): ?>
                <option value="<?= $site['site_url'] ?>" <?php if(isset($search_data['site_url']) and ($search_data['site_url'] == $site['site_url'])) {?> selected="selected" <?php } ?>><?= $site['site_url'] ?></option>
            <?php endforeach ?>
        </select>
        <input type="submit" class="btn" value="Искать">
    </form>
    <div class="table_body">
        <table>
            <thead>
                <tr>
                    <th>Веб-сайт</th>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>Контакт</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($sms_history_list)) { ?>
                    <?php foreach ($sms_history_list as $key => $row): ?>
                        <tr>
                            <td><?= $row['site_url'] ?></td>
                            <td><?= date('j-m-Y', strtotime($row['date'])); ?></td>
                            <td><?= date('H:i', strtotime($row['date'])); ?></td>
                            <td class="user-mobile"><a href="tel:<?= $row['phone_contact'] ?>"><?= $row['phone_contact'] ?></a></td>
                            <td>
                                <?php if($row['status'] == 1) { ?>
                                    Отправлено
                                <?php }elseif($row['status'] == 'empty_balance'){ ?>
                                    Не отправлено (нулевой баланс)
                                <?php }else{ ?>
                                    Не отправлено (тех. причина)
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php }else{ ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">
                                Записей нет
                            </td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>