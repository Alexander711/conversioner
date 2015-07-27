<div id="popup_overlay_purchase_tariff"></div>
<div id="pop_up_purchase_tariff">
    <div class="close_pop_up_purchase_tariff"></div>
    <?= $purchase_tariff_window ?>
</div>

<div class="component">
    <h3>Найти пользователя</h3>
    <form action="" method="post">
        <label for="search_user_by_name">По имени</label>
        <input type="text" name="search_user_by_name" id="search_user_by_name" class="input_search_user_by_name" value="<?php if(isset($search_data['search_user_by_name'])) { echo $search_data['search_user_by_name']; }?>"/>
        <label for="search_user_by_email">По email</label>
        <input type="text" name="search_user_by_email" id="search_user_by_email" class="input_search_user_by_email" value="<?php if(isset($search_data['search_user_by_email'])) { echo $search_data['search_user_by_email']; }?>"/>
        <label for="search_user_by_phone">По телефону</label>
        <input type="text" name="search_user_by_phone" id="search_user_by_phone" class="input_search_user_by_phone" value="<?php if(isset($search_data['search_user_by_phone'])) { echo $search_data['search_user_by_phone']; }?>"/>
        <input type="submit" class="btn" value="Искать">
    </form>
    <div class="table_body">
        <table>
            <thead>
                <tr>
                    <th>Имя пользователя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Количество СМС (остаток)</th>
                    <th>Дата</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($all_users)) { ?>
                    <?php foreach ($all_users as $key => $user): ?>
                        <tr>
                            <td><?= $user['name'] ?>&nbsp<?= $user['last_name'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['phone'] ?></td>
                            <td>
                                <span class="count_sms_user_<?= $user['id_user'] ?>">
                                    <?= $user['count_sms'] ?>
                                </span>
                            </td>
                            <td><?= date('d-m-Y',strtotime($user['date'])) ?></td>
                            <td>
                                <a href="javascript:void(0);" class="purchase_tariff" data-id_user="<?= $user['id_user'] ?>">
                                    Тариф
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php }else{ ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Записей нет
                            </td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>