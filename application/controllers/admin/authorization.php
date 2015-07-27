<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/authorization_model', 'admin_authorization_model');
        $this->load->library('session');
    }

    /**
     * функция авторизации пользователя в админке
     */
    public function login() {
        if ($this->admin_authorization_model->check_auth()) {
            redirect('/admin/purchase_tariff', 'refresh');
        }

        $data['title'] = 'Авторизация в админку';

        $data['other_js'] = array('js/admin/script.js');

        if ($_POST) {
            $error_mass = '';

            if ($this->input->post('email_user') == '') {
                $error_mass .= 'Заполните поле "Email"</span><br/>';
            }

            if ($this->input->post('pass') == '') {
                $error_mass .= '<span>Заполните поле "Пароль"</span><br/>';
            }

            if (mb_strlen($this->input->post('pass')) < 6 && $this->input->post('pass') != '') {
                $error_mass .= '<span>Длина поля "Пароль" должна быть не меньше 6 символов</span><br/>';
            }

            if ($error_mass == '') {
                $user_data = $this->admin_authorization_model->check_user($this->input->post());

                if (!empty($user_data)) {
                    $data_session = array(
                        'id_user' => (int) $user_data['id'],
                        'name' => $user_data['name'],
                        'last_name' => $user_data['last_name'],
                        'email' => $user_data['email'],
                        'pass' => $user_data['pass'],
                        'type_user' => 'admin',
                    );

                    $this->session->set_userdata($data_session);

                    echo 'ok';
                    exit;
                } else {
                    echo '<span>Введен неправильный логин или пароль!<span>';
                    exit;
                }
            } else {
                echo $error_mass;
                exit;
            }
        } else {
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/authorization/login', $data);
            $this->load->view('admin/templates/footer');
        }
    }

    /**
     * функция выхода из аккаунта для админки
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('admin/authorization/login', 'refresh');
    }

    /**
     * функция создания нового админа
     */
    public function add_user() {
        if (!$this->admin_authorization_model->check_auth()) {
            redirect('/admin/authorization/login', 'refresh');
        }

        $data['title'] = 'Добавление нового пользователя в админке';

        $data['other_js'] = array('js/admin/script.js');

        if ($_POST) {
            $error_mass = '';

            if ($this->input->post('name') == '') {
                $error_mass .= '<span>Заполните поле "Имя"</span><br/>';
            }

            if ($this->input->post('last_name') == '') {
                $error_mass .= '<span>Заполните поле "Фамилия"</span><br/>';
            }

            if ($this->input->post('email_user') == '') {
                $error_mass .= '<span>Заполните поле "Email"</span><br/>';
            }

            if ($this->input->post('pass') == '') {
                $error_mass .= '<span>Заполните поле "Пароль"</span><br/>';
            }

            if (mb_strlen($this->input->post('pass')) < 6 && $this->input->post('pass') != '') {
                $error_mass .= '<span>Длина поля "Пароль" должна быть не меньше 6 символов</span><br/>';
            }

            if ($this->input->post('pass') != $this->input->post('confirm_pass')) {
                $error_mass .= '<span>Поля "Пароль" и "Подтвердить пароль" должны совпадать</span><br/>';
            }

            $email_exist = $this->admin_authorization_model->check_email_exist($this->input->post('email_user'));

            if (!empty($email_exist)) {
                $error_mass .= '<span>Такой email уже зарегистрирован</span><br/>';
            }

            if ($error_mass == '') {
                $data = array(
                    'name' => mysql_real_escape_string(strip_tags($this->input->post('name'))),
                    'last_name' => mysql_real_escape_string(strip_tags($this->input->post('last_name'))),
                    'email' => mysql_real_escape_string(strip_tags($this->input->post('email_user'))),
                    'pass' => md5(mysql_real_escape_string(strip_tags($this->input->post('pass')))),
                );

                $return_data = $this->admin_authorization_model->save_data_user($data);

                if ($return_data != 'insert_error') {
                    $data_ajax = $data;
                    $data_ajax['status_ajax'] = 'ok';

                    print_r(json_encode($data_ajax));
                    exit;
                } else {
                    $data_ajax['status_ajax'] = 'error';
                    $data_ajax['error_mass'] = '<span>Произошла ошибка</span><br/>';
                }
            } else {
                $data_ajax['status_ajax'] = 'error';
                $data_ajax['error_mass'] = $error_mass;

                print_r(json_encode($data_ajax));
                exit;
                exit;
            }
        } else {
            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/authorization/add_user', $data);
            $this->load->view('admin/templates/footer');
        }
    }

    /**
     * функция изменения данных пользователя админки
     */
    public function edit_user() {
        if (!$this->admin_authorization_model->check_auth()) {
            redirect('/admin/authorization/login', 'refresh');
        }

        $data['title'] = 'Редактирование своих данных в админке';

        $data['other_js'] = array('js/admin/script.js');

        $id_user = $this->session->userdata('id_user');

        if ($_POST) {
            $error_mass = '';

            if ($this->input->post('name') == '') {
                $error_mass .= '<span>Заполните поле "Имя"</span><br/>';
            }

            if ($this->input->post('last_name') == '') {
                $error_mass .= '<span>Заполните поле "Фамилия"</span><br/>';
            }

            if ($this->input->post('email_user') == '') {
                $error_mass .= '<span>Заполните поле "Email"</span><br/>';
            }

            if (mb_strlen($this->input->post('pass')) < 6 && $this->input->post('pass') != '') {
                $error_mass .= '<span>Длина поля "Пароль" должна быть не меньше 6 символов</span><br/>';
            }

            if ($this->input->post('pass') != $this->input->post('confirm_pass')) {
                $error_mass .= '<span>Поля "Пароль" и "Подтвердить пароль" должны совпадать</span><br/>';
            }

            $email_exist = $this->admin_authorization_model->check_email_exist($this->input->post('email_user'));

            if (!empty($email_exist) && $this->session->userdata('email') != $this->input->post('email_user')) {
                $error_mass .= '<span>Такой email уже зарегистрирован</span><br/>';
            }

            if ($error_mass == '') {
                $data = array(
                    'name' => mysql_real_escape_string(strip_tags($this->input->post('name'))),
                    'last_name' => mysql_real_escape_string(strip_tags($this->input->post('last_name'))),
                    'email' => mysql_real_escape_string(strip_tags($this->input->post('email_user'))),
                );

                if ($this->input->post('pass') != '') {
                    $data['pass'] = md5(mysql_real_escape_string(strip_tags($this->input->post('pass'))));
                }

                if ($this->admin_authorization_model->update_user_data($id_user, $data)) {
                    $this->session->set_userdata($data);

                    $data_ajax = $data;
                    $data_ajax['status_ajax'] = 'ok';

                    print_r(json_encode($data_ajax));
                    exit;
                } else {
                    $data_ajax['status_ajax'] = 'error';
                    $data_ajax['error_mass'] = '<span>Произошла ошибка</span><br/>';
                }
            } else {
                $data_ajax['status_ajax'] = 'error';
                $data_ajax['error_mass'] = $error_mass;

                print_r(json_encode($data_ajax));
                exit;
                exit;
            }
        } else {
            $data['data_user'] = $this->admin_authorization_model->get_data_admin_user_by_id($id_user);

            $this->load->view('admin/templates/header', $data);
            $this->load->view('admin/authorization/edit_user', $data);
            $this->load->view('admin/templates/footer');
        }
    }

}
