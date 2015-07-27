<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization_model extends CI_Model {

    /**
     * Функция находит пользователя в БД по email и паролю
     * @param array $post_data
     * @return array
     */

    public function check_user($post_data) {
        $query = $this->db->query("SELECT *
                FROM users
                WHERE email = '" . mysql_real_escape_string($post_data['email_user'])
                . "' AND pass = '" . md5(mysql_real_escape_string($post_data['pass'])) . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция сохраняет данные пользователя
     * @param array $data
     * @return int
     */

    public function save_data_user($data) {
        $this->load->model('generate_widget_model');

        $this->db->trans_start();

        $this->db->insert('users', $data);
        
        $id_user = $this->db->insert_id();
        
        $this->generate_widget_model->create_new_balance_user($id_user,10);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return 'insert_error';
        } else {
            $this->db->trans_commit();

            return $id_user;
        }

    }

    /**
     * Функция проверяет email на существование
     * @param string $email_user
     * @return array
     */  

    public function check_email_exist($email_user) {
        $query = $this->db->query("SELECT *
                FROM users
                WHERE email = '" . mysql_real_escape_string($email_user) . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция проверяет авторизован ли пользователь
     * @return boolean
     */ 

    public function check_auth() {
        if ($this->session->userdata('id_user') and $this->session->userdata('type_user') == 'user') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Функция обновляет пароль пользователя в базе
     * @param int $id_user
     * @param string $new_password
     */

    public function update_user_pass($id_user, $new_password) {
        $this->db->where('id', (int) $id_user);
        $query = $this->db->update('users', array('pass' => md5(mysql_real_escape_string(strip_tags($new_password)))));

        if (!$query){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Функция выбирает данные пользователя по id
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
     * Функция обновляет данные пользователя в БД
     * @param int $id_user
     * @param array $post_data
     * @return array
     */

    public function update_user_data($id_user, $post_data) {
        $data = array(
            'name' => mysql_real_escape_string(strip_tags($post_data['name'])),
            'last_name' => mysql_real_escape_string(strip_tags($post_data['last_name'])),
            'email' => mysql_real_escape_string(strip_tags($post_data['email_user'])),
            'phone' => mysql_real_escape_string(strip_tags($post_data['phone'])),
        );

        if($post_data['pass'] != ''){
            $data['pass'] = md5(mysql_real_escape_string(strip_tags($post_data['pass'])));
        }

        $this->db->where('id', (int) $id_user);
        $query = $this->db->update('users', $data);

        if (!$query){
            return FALSE;
        }else{
            return TRUE;
        }
    }

}
