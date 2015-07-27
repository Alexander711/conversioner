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
                FROM users_admin
                WHERE email = '" . mysql_real_escape_string($post_data['email_user'])
                . "' AND pass = '" . md5(mysql_real_escape_string($post_data['pass'])) . "'");

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
        if ($this->session->userdata('id_user') and $this->session->userdata('type_user') == 'admin') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Функция сохраняет данные пользователя
     * @param array $data
     * @return int
     */
    public function save_data_user($data) {
        $this->db->insert('users_admin', $data);

        if (!$this->db->insert_id()) {
            return 'insert_error';
        } else {
            return $this->db->insert_id();
        }
    }

    /**
     * Функция проверяет email на существование
     * @param string $email_user
     * @return array
     */ 
    public function check_email_exist($email_user) {
        $query = $this->db->query("SELECT *
                FROM users_admin
                WHERE email = '" . mysql_real_escape_string($email_user) . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

    /**
     * Функция обновляет данные пользователя в БД
     * @param int $id_user
     * @param array $data
     * @return boolean
     */
    public function update_user_data($id_user, $data) {
        $this->db->where('id', (int) $id_user);
        $query = $this->db->update('users_admin', $data);

        if (!$query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Функция выбирает данные пользователя по id
     * @param int $id_user
     * @return array
     */
    public function get_data_admin_user_by_id($id_user) {
        $query = $this->db->query("SELECT *
                FROM users_admin
                WHERE id = '" . (int) $id_user . "'");

        if (!$query) {
            return false;
        }

        return $query->row_array();
    }

}
