<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_tariff_model extends CI_Model {
    
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
     * Функция выбирает всех пользователей, которые зарегистрированы в сервисе "конвертик"
     * @param array $search_data
     * @return array
     */
    public function get_all_users($search_data = '') {
        $sql = "SELECT users.id AS id_user,
                    users.name,
                    users.last_name,
                    users.email,
                    users.phone,
                    users.date,
                    balance_user.count_sms
               FROM users
               LEFT JOIN balance_user ON users.id = balance_user.id_user";
        
        if($search_data != ''){
            $sql_part = '';

            if ($search_data['search_user_by_name'] != '') {
                $data = explode(" ", mysql_real_escape_string(strip_tags($search_data['search_user_by_name'])));

                $sql_part .= " WHERE users.name LIKE '%" . $data[0] . "%' ";

                if(isset($data[1])){
                    $sql_part .= "AND users.last_name LIKE '%" . $data[1] . "%' ";
                }
            }

            if ($search_data['search_user_by_email'] != '') {
                if($sql_part != ''){
                    $sql_part .= ' AND ';
                }else{
                    $sql_part .= ' WHERE ';
                }

                $sql_part .= " users.email LIKE '%" . mysql_real_escape_string(strip_tags($search_data['search_user_by_email'])) . "%' ";
            }

            if ($search_data['search_user_by_phone'] != '') {
                if($sql_part != ''){
                    $sql_part .= ' AND ';
                }else{
                    $sql_part .= ' WHERE ';
                }

                $sql_part .= " users.phone LIKE '%" . mysql_real_escape_string(strip_tags($search_data['search_user_by_phone'])) . "%'";
            }

            $sql .= $sql_part;
        }

        $query = $this->db->query($sql);

        if (!$query) {
            return false;
        }

        return $query->result_array();
    }

    /**
     * Функция выбирает баланс пользователя
     * @param int $id_user
     * @return int
     */
    public function get_balance_user($id_user) {
        $query = $this->db->query("SELECT count_sms
                                   FROM balance_user
                                   WHERE id_user = ".(int)$id_user);

        if (!$query) {
            return false;
        }

        $result = $query->row_array();

        return $result['count_sms'];
    }

    /**
     * Функция выбирает данные выбранного тарифа
     * @param int $id_tariff
     * @return array
     */
    public function get_data_tariff($id_tariff){
        $query = $this->db->query("SELECT *
                                   FROM all_tariffs
                                   WHERE id = ".(int)$id_tariff);

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция выбирает данные выбранного пользователя
     * @param int $id_user
     * @return array
     */
    public function get_data_user_by_id($id_user) {
        $query = $this->db->query("SELECT *
                FROM users
                WHERE id = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция изменяет тариф для указанного пользователя
     * @param array $data_post
     * @return int
     */
    public function change_tariff_for_user($data_post) {

        $id_user = $data_post['id_user'];

        $data_tariff = $this->get_data_tariff($data_post['tariff_id']);

        if(empty($data_tariff)){
            return 'error_change_tariff';
        }

        $count_sms_user = $this->get_balance_user($id_user);

        $new_count_sms_user = $count_sms_user + $data_tariff['count_sms'];

        $data_user = $this->get_data_user_by_id($id_user);

        $this->load->model('generate_widget_model');

        if(!$this->generate_widget_model->update_balance_user($id_user, $new_count_sms_user)){
            return 'error_change_tariff';
        }else{
            $data = array(
                'name_user' => $data_user['name'],
                'last_name_user' => $data_user['last_name'],
                'name_tariff' => $data_tariff['name_tariff'],
                'sum' => $data_tariff['sum'],
                'count_sms' => $data_tariff['count_sms'],
                'name_admin' => $data_post['name_admin'],
                'last_name_admin' => $data_post['last_name_admin'],
            );

            $this->save_purchase_tariff_history($data);

            return $new_count_sms_user;
        }
    }

    /**
     * Функция сохраняет историю покупок тарифов
     * @param array $data
     * @return boolean
     */
    public function save_purchase_tariff_history($data) {
        $this->db->insert('purchase_tariff_history', $data);

        if($this->db->insert_id()){
            return TRUE; 
        }else{
            return FALSE;
        }
    }
    
}