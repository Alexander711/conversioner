<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('authorization_model');
        $this->load->library('session');
    }

    /**
     * функция авторизации пользователя
     */
    public function login() {
        if ($this->authorization_model->check_auth()) {
            redirect('/generate_window/sms_history_list', 'refresh');
        }

        $data['title'] = 'Авторизация';

        $data['other_js'] = array('js/script.js');

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
                $user_data = $this->authorization_model->check_user($this->input->post());

                if (!empty($user_data)) {
                    $data_session = array(
                        'id_user' => (int) $user_data['id'],
                        'name' => $user_data['name'],
                        'last_name' => $user_data['last_name'],
                        'email' => $user_data['email'],
                        'pass' => $user_data['pass'],
                        'phone' => $user_data['phone'],
                        'type_user' => 'user',
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
            $data['password_recovery_form'] = $this->load->view('authorization/password_recovery','',TRUE);
            $data['registration_form'] = $this->load->view('authorization/registration','',TRUE);

            $this->load->view('templates/header', $data);
            $this->load->view('authorization/login', $data);
            $this->load->view('templates/footer');
        }
    }

    /**
     * функция регистрации пользователя
     */
    public function registration() {
        if ($this->authorization_model->check_auth()) {
            redirect('/generate_window/sms_history_list', 'refresh');
        }

        $data['title'] = 'Регистрация';

        $data['other_js'] = array('js/script.js');

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

            if ($this->input->post('confirm_pass') == '') {
                $error_mass .= '<span>Заполните поле "Подтвердить пароль"</span><br/>';
            }

            if ($this->input->post('pass') != $this->input->post('confirm_pass')) {
                $error_mass .= '<span>Поля "Пароль" и "Подтвердить пароль" должны совпадать</span><br/>';
            }

            if ($this->input->post('phone') == '') {
                $error_mass .= '<span>Заполните поле "Телефон"</span><br/>';
            }

            if (mb_strlen($this->input->post('phone')) < 11 && $this->input->post('phone') != '') {
                $error_mass .= '<span>Длина поля "Телефон" должна быть не меньше 11 символов</span><br/>';
            }

            $email_exist = $this->authorization_model->check_email_exist($this->input->post('email_user'));

            if (!empty($email_exist)) {
                $error_mass .= '<span>Такой email уже зарегистрирован</span><br/>';
            }

            if ($error_mass == '') {
                $this->load->helper('work_helper');
                $this->load->library('transport');

                $random_code = create_random_code(5);

                $params = array(
                    "text" => "Код: " . $random_code,
                    "source" => "Conversion",
                );

                $phones = array($this->input->post('phone'));

                $send_status = $this->transport->send($params, $phones);

                if ($send_status['code'] == 1) {
                    $this->session->set_userdata(array('code_confirm_session' => $random_code));
                    echo 'ok';
                    exit;
                } else {
                    $error_mass .= '<span>Произошла ошибка, попробуйте позже</span><br/>';
                    echo $error_mass;
                    exit;
                }
            } else {
                echo $error_mass;
                exit;
            }
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('authorization/registration');
            $this->load->view('templates/footer');
        }
    }

    /**
     * функция подтверждения регистрации по коду, полученному на телефон
     */
    public function confirm_reg() {
        $error_mass = '';

        if ($this->input->post('code_confirm') == '') {
            $error_mass .= '<span>Заполните поле "Ведите код подтверждения"</span><br/>';
        }

        if ($this->session->userdata('code_confirm_session') != $this->input->post('code_confirm')) {
            $error_mass .= '<span>Введен неправильный код</span><br/>';
        }

        if ($error_mass == '') {
            $data = array(
                'name' => mysql_real_escape_string(strip_tags($this->input->post('name'))),
                'last_name' => mysql_real_escape_string(strip_tags($this->input->post('last_name'))),
                'email' => mysql_real_escape_string(strip_tags($this->input->post('email_user'))),
                'phone' => mysql_real_escape_string(strip_tags($this->input->post('phone'))),
                'pass' => md5(mysql_real_escape_string(strip_tags($this->input->post('pass')))),
            );

            $return_data = $this->authorization_model->save_data_user($data);

            if ($return_data != 'insert_error') {
                $data['id_user'] = $return_data;
                $data['type_user'] = 'user';

                $this->session->set_userdata($data);
                $this->session->unset_userdata('pass');
                $this->session->unset_userdata('code_confirm_session');

                echo 'ok';
                exit;
            }
        } else {
            echo $error_mass;
            exit;
        }
    }

    /**
     * функция выхода из аккаунта
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('/authorization/login', 'refresh');
    }

    /**
     * функция востановления пароля (1-я часть - проверяется на существование email и отправляется код подтверждения на телефон пользователя)
     */
    public function password_recovery() {
        $data['title'] = 'Восстановление пароля';

        $data['other_js'] = array('js/script.js');

        if ($this->input->post('email_password_recovery') == '') {
            echo '<span>Заполните поле "Введите Email"</span><br/>';
            exit;
        } else {
            $this->load->library('transport');
            $this->load->helper('work_helper');

            $data_user = $this->authorization_model->check_email_exist($this->input->post('email_password_recovery'));

            if (empty($data_user)) {
                echo '<span>Данный email не найден</span><br/>';
                exit;
            }

            $random_code = create_random_code(5);

            $params = array(
                "text" => "Код: " . $random_code,
                "source" => "Conversion",
            );

            $phones = array($data_user['phone']);

            $send_status = $this->transport->send($params, $phones);

            if ($send_status['code'] == 1) {
                $data_session = array(
                    'id_user_password_recovery' => $data_user['id'],
                    'email_password_recovery' => $data_user['email'],
                    'code_confirm_password_recovery' => $random_code,
                );

                $this->session->set_userdata($data_session);

                echo 'ok';
                exit;
            } else {
                echo '<span>Произошла ошибка, повторите попытку позже</span><br/>';
                exit;
            }
        }
    }

    /**
     * функция востановления пароля (2-я часть - ввод проверочного кода)
     */
    public function confirm_password_recovery() {
        if (!$this->session->userdata('id_user_password_recovery')) {
            redirect('/authorization/login', 'refresh');
        }

        if ($this->input->post('confirm_password_recovery') == '') {
            echo '<span>Заполните поле "Ведите код подтверждения"</span><br/>';
            exit;
        } else {
            if ($this->input->post('confirm_password_recovery') == $this->session->userdata('code_confirm_password_recovery')) {
                echo 'ok';
                exit;
            } else {
                echo '<span>Заполните поле "Ведите код подтверждения"</span><br/>';
                exit;
            }
        }
    }

    /**
     * функция востановления пароля (3-я часть - ввод нового пароля)
     */
    public function new_password() {
        $data['title'] = 'Введите новый пароль';

        $data['other_js'] = array('js/script.js');

        if ($_POST) {
            $error_mass = '';

            $new_password = $this->input->post('new_password');
            $confirm_new_password = $this->input->post('confirm_new_password');

            if ($new_password == '') {
                $error_mass .= '<span>Заполните поле "Введите новый пароль"</span><br/>';
            }

            if (mb_strlen($new_password) < 6 && $new_password != '') {
                $error_mass .= '<span>Длина поля "Введите новый пароль" должна быть не меньше 6 символов</span><br/>';
            }

            if ($confirm_new_password == '') {
                $error_mass .= '<span>Заполните поле "Повторите пароль"</span><br/>';
            }

            if (strlen($confirm_new_password) < 6 && $confirm_new_password != '') {
                $error_mass .= '<span>Длина поля "Повторите пароль" должна быть не меньше 6 символов</span><br/>';
            }

            if (($error_mass == '') && $new_password != $confirm_new_password) {
                $error_mass .= '<span>Пароли не совпадают</span><br/>';
            }

            if ($error_mass == '') {
                $id_user = $this->session->userdata('id_user_password_recovery');

                $this->authorization_model->update_user_pass($id_user, $new_password);

                $this->session->unset_userdata('id_user_password_recovery');
                $this->session->unset_userdata('email_password_recovery');
                $this->session->unset_userdata('code_confirm_password_recovery');

                echo 'ok';
                exit;
            } else {
                echo $error_mass;
                exit;
            }
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('authorization/new_password');
            $this->load->view('templates/footer');
        }
    }

    /**
     * функция вывод страницы с сообщением об успешной смене пароля
     */
    public function new_password_success() {
        $data['title'] = 'Пароль успешно изменен!';

        $this->load->view('templates/header', $data);
        $this->load->view('authorization/new_password_success');
        $this->load->view('templates/footer');
    }

    /**
     * функция изменения данных пользователя
     */
    public function edit_user_data() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/authorization/login', 'refresh');
        }

        $data['title'] = 'Редактирование профиля';

        $data['other_js'] = array('js/script.js');

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

        if ($this->input->post('phone') == '') {
            $error_mass .= '<span>Заполните поле "Телефон"</span><br/>';
        }

        if (mb_strlen($this->input->post('phone')) < 11 && $this->input->post('phone') != '') {
            $error_mass .= '<span>Длина поля "Телефон" должна быть не меньше 11 символов</span><br/>';
        }

        $email_exist = $this->authorization_model->check_email_exist($this->input->post('email_user'));

        if (!empty($email_exist) && $this->session->userdata('email') != $this->input->post('email_user')) {
            $error_mass .= '<span>Такой email уже зарегистрирован</span><br/>';
        }

        if ($error_mass == '') {
            $id_user = $this->session->userdata('id_user');

            $post_data = $this->input->post();

            $this->authorization_model->update_user_data($id_user, $post_data);

            $data_session = array(
                'name' => strip_tags($post_data['name']),
                'last_name' => strip_tags($post_data['last_name']),
                'email' => strip_tags($post_data['email_user']),
                'phone' => strip_tags($post_data['phone']),
            );

            $data_ajax = $data_session;

            if ($post_data['pass'] != '') {
                $data_session['pass'] = md5($post_data['pass']);
            } else {
                $data_session['pass'] = $this->session->userdata('pass');
            }

            $this->session->set_userdata($data_session);

            $data_ajax['status_ajax'] = 'ok';

            print_r(json_encode($data_ajax));
            exit;
        } else {
            $data_ajax['status_ajax'] = 'error';
            $data_ajax['error_mass'] = $error_mass;

            print_r(json_encode($data_ajax));
            exit;
        }
    }

}
