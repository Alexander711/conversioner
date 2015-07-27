<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_widget_model extends CI_Model {

    const PATH_IMG = 'uploads/img_contact_window';
    const PATH_TEMPORARY_UPLOADS = 'temporary_uploads';

    /**
     * Функция выбирает все "конвертики" для указанного пользователя
     * @param int $id_user
     * @return array
     */
    public function get_all_widgets($id_user) {
        $query = $this->db->query("SELECT change_options_widget.id,
                                          sites.site_url,
                                          change_options_widget.email,
                                          change_options_widget.phone,
                                          change_options_widget.is_installed,
                                          change_options_widget.is_active
                                   FROM change_options_widget
                                   JOIN sites ON change_options_widget.id_site = sites.id
                                   WHERE change_options_widget.id_user = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        return $query->result_array();
    }

    /**
     * Функция удаляет выбранный "конвертик"
     * @param int $id_widget
     * @param int $id_user
     * @return string
     */
    public function delete_widget($id_widget, $id_user) {
        $query = $this->db->query("SELECT id_site 
                                   FROM change_options_widget 
                                   WHERE id = '" . (int) $id_widget . "'
                                       AND id_user = '" . (int) $id_user . "'");

        $row = $query->row_array();

        if (empty($row)) {
            return 'del_error';
        }

        $this->db->trans_start();
        $this->db->delete('sites', array('id' => (int) $row['id_site'], 'id_user' => (int) $id_user));

        $this->db->delete('change_options_widget', array('id' => (int) $id_widget, 'id_user' => (int) $id_user));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 'del_error';
        } else {
            $this->db->trans_commit();

            return 'del_ok';
        }
    }

    /**
     * Функция сохраняет настройки "конвертика" в БД
     * @param array $data
     */
    public function save_options_widget($data) {
        $this->db->trans_start();
        $this->db->insert('sites', $data['site']);

        $data['options_widget']['id_site'] = $this->db->insert_id();

        $this->db->insert('change_options_widget', $data['options_widget']);

        $id_widget = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 'error_insert';
        } else {
            $this->db->trans_commit();

            return $id_widget;
        }
    }

    /**
     * Функция обновляет настройки "конвертика"
     * @param int $id
     * @param int $id_user
     * @param array $data
     * @return string
     */
    public function update_options_widget($id, $id_user, $data) {
        $query = $this->db->query("SELECT id_site 
                                   FROM change_options_widget 
                                   WHERE id = '" . (int) $id . "'
                                       AND id_user = '" . (int) $id_user . "'");

        $row = $query->row_array();

        if (empty($row)) {
            return 'update_error';
        }

        $this->db->trans_start();
        $this->db->update('change_options_widget', $data['options_widget'], array('id' => (int) $id, 'id_user' => (int) $id_user));

        $this->db->update('sites', $data['site'], array('id' => (int) $row['id_site'], 'id_user' => (int) $id_user));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 'update_error';
        } else {
            $this->db->trans_commit();

            return 'update_ok';
        }
    }

    /**
     * Функция генерирует код "конвертика" и возвращает его в виде строки для последующего формирования js файла
     * @param int $id_widget
     * @param int $id_user
     * @return string
     */
    public function get_data_widget($id_widget, $id_user) {
        $this->load->helper('file');

        $query = $this->db->query("SELECT change_options_widget.*,
                                          sites.site_url
                                   FROM change_options_widget
                                   JOIN sites ON change_options_widget.id_site = sites.id
                                   WHERE change_options_widget.id_user = '" . (int) $id_user
                . "' AND change_options_widget.id = '" . (int) $id_widget . "'");

        if (!$query) {
            return false;
        }

        $data = $query->row_array();

        $string = read_file('./js/widget_body.txt');

        $string = str_replace('{IS_ACTIVE}', $data['is_active'], $string);
        $string = str_replace('{WORK_DAYS}', $data['work_days'], $string);
        $string = str_replace('{TIME_START}', $data['time_start'], $string);
        $string = str_replace('{TIME_END}', $data['time_end'], $string);
        $string = str_replace('{DETECT_EXIT}', $data['detect_exit'], $string);
        $string = str_replace('{CSS_CONTACT_WINDOW}', base_url('css/style_window_operator.css'), $string);
        $string = str_replace('{URL_CONTACT_WINDOW}', base_url('window_operator/index/' . $data['conversioner_code']), $string);
        $string = str_replace('{TIME_ATTENTION}', $data['time_attention'], $string);

        return $string;
    }

    /**
     * Функция выбирает все настройки для выбранного "конвертика"
     * @param int $id_widget
     * @param int $id_user
     * @return array
     */
    public function get_options_widget_by_id($id_widget, $id_user) {
        $query = $this->db->query("SELECT change_options_widget.*,
                                          sites.site_url
                                   FROM change_options_widget
                                   JOIN sites ON change_options_widget.id_site = sites.id
                                   WHERE change_options_widget.id_user = '" . (int) $id_user
                . "' AND change_options_widget.id = '" . (int) $id_widget . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция азменяет поле is_active в БД
     * @param int $id_widget
     * @param int $id_user
     * @param int $active_status
     * @return boolean
     */
    public function update_field_is_active($id_widget, $id_user, $active_status) {
        $query = $this->db->update('change_options_widget', array('is_active' => (int) $active_status), array('id' => (int) $id_widget, 'id_user' => (int) $id_user));

        if (!$query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Функция выбирает ссылку на сайт, conv код и флаг на открытие окна при попытке закрыть сайт
     * @param int $id_widget
     * @param int $id_user
     * @return array
     */
    public function get_site_url_conv_code_detect_exit_by_id($id_widget, $id_user) {
        $query = $this->db->query("SELECT change_options_widget.conversioner_code,
                                          change_options_widget.detect_exit,
                                          sites.site_url
                                   FROM change_options_widget
                                   JOIN sites ON change_options_widget.id_site = sites.id
                                   WHERE change_options_widget.id_user = '" . (int) $id_user
                . "' AND change_options_widget.id = '" . (int) $id_widget . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция выбирает флаги активности и установленности для выбранного "когвертика"
     * @param int $id_widget
     * @param int $id_user
     * @return array
     */
    public function get_active_instal_by_id($id_widget, $id_user) {
        $query = $this->db->query("SELECT change_options_widget.is_installed,
                                          change_options_widget.is_active
                                   FROM change_options_widget
                                   WHERE change_options_widget.id_user = '" . (int) $id_user
                . "' AND change_options_widget.id = '" . (int) $id_widget . "'");

        if (!$query) {
            return false;
        }

        return $row = $query->row_array();
    }

    /**
     * Функция обновляет поле is_installed в БД
     * @param int $id_widget
     * @param int $id_user
     * @param int $flag
     * @return boolean
     */
    public function update_field_is_installed($id_widget, $id_user, $flag) {
        $query = $this->db->update('change_options_widget', array('is_installed' => (int) $flag), array('id' => (int) $id_widget, 'id_user' => (int) $id_user));

        if (!$query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Функция выбирает историю отправки СМС сервисом "конвертик"
     * @param int $id_user
     * @param array $search_data
     * @return array
     */
    public function get_sms_history($id_user, $search_data = '') {
        $sql = "SELECT phone_contact,
                       status,
                       date,
                       site_url
                FROM sms_history
                WHERE id_user = '" . (int) $id_user . "'";

        if ($search_data != '') {
            if ($search_data['site_url'] != '') {
                $sql .= " AND site_url = '" . $search_data['site_url'] . "'";
            }

            if ($search_data['date_beginning'] != '') {
                $sql .= " AND DATE_FORMAT(date,'%d-%m-%Y') >= '" . $search_data['date_beginning'] . "'";
            }

            if ($search_data['date_end'] != '') {
                $sql .= " AND DATE_FORMAT(date,'%d-%m-%Y') <= '" . $search_data['date_end'] . "'";
            }
        }

        $sql .= " ORDER BY date DESC";

        $query = $this->db->query($sql);

        if (!$query) {
            return false;
        }

        return $query->result_array();
    }

    /**
     * Функция выбирает время работы для данного "конвертика"
     * @param int $id_widget
     * @param int $id_user
     * @return array
     */
    public function get_time_work_widget($id_widget, $id_user) {
        $query = $this->db->query("SELECT work_days,
                                          time_start,
                                          time_end
                                       FROM change_options_widget
                                       WHERE id = '" . (int) $id_widget . "'
                                           AND id_user = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        $data = $query->row_array();

        return $data;
    }

    /**
     * Функция изменяет время работы для данного "конвертика"
     * @param array $data_post
     * @param int $id_widget
     * @param int $id_user
     * @return boolean
     */
    public function change_time_work_widget($data_post, $id_widget, $id_user) {
        $data = array(
            'work_days' => mysql_real_escape_string(strip_tags(implode(",", $data_post['work_days']))),
            'time_start' => (int) $data_post['time_start'] * 60,
            'time_end' => ((int) $data_post['time_end'] * 60) + 59,
        );

        $query = $this->db->update('change_options_widget', $data, array('id' => $id_widget, 'id_user' => (int) $id_user));

        if (!$query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Функция выбирает настройки "конвертика" по conv коду
     * @param string $code
     * @return array
     */
    public function get_options_widget_by_code($code) {

        $query = $this->db->query("SELECT change_options_widget.*,
                                          sites.site_url
                                   FROM change_options_widget
                                   JOIN sites ON change_options_widget.id_site = sites.id
                                   WHERE change_options_widget.conversioner_code = '" . $code . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция сохраняет историю отправки СМС сервисом "конвертик"
     * @param array $data_sms_history
     */
    public function save_sms_history($data_sms_history) {
        $this->db->insert('sms_history', $data_sms_history);
    }

    /**
     * Функция генерирует сообщение по установке "конвертика" для программиста
     * @param int $id_widget
     * @param int $id_user
     * @return string
     */
    public function generate_message_for_progr($id_user, $id_widget) {
        $options_widget = $this->get_options_widget_by_id($id_widget, $id_user);

        $conversioner_code = $options_widget['conversioner_code'];

        $string = 'УСТАНОВКА "КОНВЕРТИКА":
                   Для установки скрипта вставьте данные строчки между тегами HEAD на Вашем сайте.
                   
                   <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
                   <script type="text/javascript" src="' . base_url("widgets/script_" . md5($id_widget) . ".js") . '"></script>
                   <script type="text/javascript">
                       var conversioner_code="' . $conversioner_code . '";
                   </script>';

        if ($options_widget['detect_exit'] == 1) {
            $string .= '<script type="text/javascript" src="' . base_url("js/ouibounce.js") . '"></script> ';
        }

        return $string;
    }

    /**
     * Функция выбирает все сайты из истории, на которые проводилась отправка СМС сервисом "конвертик"
     * @param int $id_user
     * @return array
     */
    public function get_all_sites_from_history($id_user) {
        $query = $this->db->query("SELECT DISTINCT site_url
                                   FROM sms_history
                                   WHERE id_user = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        return $query->result_array();
    }

    /**
     * Функция создает баланс для нового пользователя
     * @param int $id_user
     * @param int $count_sms
     * @return int
     */
    public function create_new_balance_user($id_user, $count_sms) {
        $data = array(
            'id_user' => (int) $id_user,
            'count_sms' => (int) $count_sms,
        );

        $this->db->insert('balance_user', $data);

        return $this->db->insert_id();
    }

    /**
     * Функция обновляет баланс для выбранного пользователя
     * @param int $id_user
     * @param int $count_sms
     * @return boolean
     */
    public function update_balance_user($id_user, $count_sms) {
        $query = $this->db->update('balance_user', array('count_sms' => (int) $count_sms), array('id_user' => (int) $id_user));

        if (!$query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Функция проверяет есть ли в БД данный conv_code
     * @param stirng $conv_code
     * @return array
     */
    public function check_conv_code_exist($conv_code) {
        $query = $this->db->query("SELECT id
                FROM change_options_widget
                WHERE conversioner_code = '" . $conv_code . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция выбирает баланс пользователя
     * @param int $id_user
     * @return int
     */
    public function get_balance_user($id_user) {
        $query = $this->db->query("SELECT count_sms
                                   FROM balance_user
                                   WHERE id_user = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        $result = $query->row_array();

        return $result['count_sms'];
    }

    /**
     * Функция выбирает все тарифы
     * @return array
     */
    public function get_all_tariffs() {
        $query = $this->db->query("SELECT *
                                   FROM all_tariffs ");

        if (!$query) {
            return false;
        }

        return $query->result_array();
    }

    /**
     * Функция выбирает все данные о тарифе
     * @param int $id_tariff
     * @return array
     */
    public function get_data_tariff($id_tariff) {
        $query = $this->db->query("SELECT *
                                   FROM all_tariffs
                                   WHERE id = " . (int) $id_tariff);

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция выбирает email всех администраторов сервиса "конвертик"
     * @return array
     */
    public function get_email_all_users_admin() {
        $query = $this->db->query("SELECT email
                                   FROM users_admin ");

        if (!$query) {
            return false;
        }

        $result = array();

        foreach ($query->result_array() as $data) {
            $result[] = $data['email'];
        }

        return $result;
    }

    public function save_img($name_img) {
        $upload_dir = FCPATH . self::PATH_TEMPORARY_UPLOADS;
        $final_folder = FCPATH . self::PATH_IMG;

        $this->load->library('image_lib');

        $img_data = getimagesize("$upload_dir/$name_img");

        if (!$img_data) {echo 2;exit;
            return 'error_save_img';
        }

        $img_width = $img_data[0];
        $img_height = $img_data[1];

        $need_width = 487;
        $need_height = 274;

        if ($img_width < $need_width || $img_height < $need_height) {
            return 'small_img';
        }

        $dif_width = 100 - (487 / $img_width * 100);

        $dif_height = 100 - (274 / $img_height * 100);

        if ($dif_width > 10 || $dif_height > 10) {
            return 'big_img';
        }

        do {
            $new_name_img = 'i' . md5(microtime()) . '.jpg';
        } while (file_exists("$final_folder/$new_name_img"));

        if (($dif_width > 0 && $dif_width <= 10) || ($dif_height > 0 && $dif_height <= 10)) {
            $config['image_library'] = 'gd2';
            $config['source_image'] = "$upload_dir/$name_img";
            $config['new_image'] = "$final_folder/$new_name_img";
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $need_width;
            $config['height'] = $need_height;
            $config['x_axis'] = ($img_width - $config['width']) / 2;
            $config['y_axis'] = ($img_height - $config['height']) / 2;

            $this->image_lib->initialize($config);

            if (!$this->image_lib->crop()) {
                return 'error_save_img';
            }

            $this->image_lib->clear();
        }

        if ($dif_width == 0 || $dif_height == 0) {
            copy("$upload_dir/$name_img", "$final_folder/$new_name_img");
        }

        return $new_name_img;
    }

    public function check_img_exist($img_name, $id_user, $id_widget) {
        $query = $this->db->query("SELECT *
                FROM change_options_widget
                WHERE img_window = '" . mysql_real_escape_string($img_name) . "'
                    AND id_user = '" . (int) $id_user . "'
                    AND id = '" . (int) $id_widget . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    public function get_img_window_for_widget($id_widget, $id_user) {
        $query = $this->db->query("SELECT img_window
                                   FROM change_options_widget
                                   WHERE id = '" . (int) $id_widget . "'
                                       AND id_user = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        $result = $query->row_array();

        return $result['img_window'];
    }

}