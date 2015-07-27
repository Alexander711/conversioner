<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Window_operator extends CI_Controller {

    /**
     * Функция генерирует всплывающее окно для виджета
     */
    public function index($code) {
        $this->load->model('generate_widget_model');

        $data = $this->generate_widget_model->get_options_widget_by_code($code);

        $this->load->view('window_operator/window_operator', $data);
    }

    /**
     * Функция отправляет СМС с указанным во всплывающем окне телефоном,
     * также ведет статистику отправки СМС и отправляет сообщение на почту владельцу "конвертика"
     * о малом балансе
     */
    public function send_message() {
        $this->load->library('transport');
        $this->load->model('generate_widget_model');
        $this->load->model('authorization_model');
        $this->load->library('email');
        $this->load->helper('cookie');

        if (get_cookie('send_message_completed')) {
            echo 'error_time';
            exit;
        }

        $phone = $this->input->post('conversioner_number_phone');

        if ($phone == '') {
            echo 'empty_data_input';
            exit;
        }

        preg_match('/^((\d|\+\d)[\- ]?)(\(?\d{3}\)?[\- ]?)[\d]{3}[\- ]?[\d]{2}[\- ]?[\d]{2}$/i', $phone, $result);

        if (empty($result)) {
            echo 'incorrect_phone';
            exit;
        }

        if ($this->input->post('conversioner_code') == '') {
            echo 'empty_data_input';
            exit;
        }

        $code = $this->input->post('conversioner_code');

        $data_script = $this->generate_widget_model->get_options_widget_by_code($code);

        $count_sms_user = $this->generate_widget_model->get_balance_user($data_script['id_user']);

        if ($count_sms_user > 0) {
            $params = array(
                "text" => "Клиент с веб-сайта " . $data_script['site_url'] . " ждёт звонка. Перезвоните по номеру " . $phone,
                "source" => "Conversion",
            );

            $phones = array($data_script['phone']);
            $send = $this->transport->send($params, $phones);

            $sms_send_status = $send['code'];

            if ($sms_send_status == 1) {
                $new_count_sms_user = $count_sms_user - 1;

                $this->generate_widget_model->update_balance_user($data_script['id_user'], $new_count_sms_user);

                if ($new_count_sms_user == 5) {
                    $data_user = $this->authorization_model->get_data_user_by_id($data_script['id_user']);

                    $message = 'На Вашем счету осталось ' . $new_count_sms_user . ' СМС. В скором времени работа услуги "Конвертик" будет недоступна.';

                    $config = Array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'ssl://smtp.yandex.ru',
                        'smtp_port' => '465',
                        'smtp_user' => 'widget@conversioner.ru',
                        'smtp_pass' => '7O3j5A7o',
                        'smtp_timeout' => '5',
                        'mailtype' => 'text',
                        'starttls' => true,
                        'newline' => "\r\n",
                        'priority' => '1'
                    );

                    $this->email->initialize($config);

                    $this->email->from('widget@conversioner.ru', 'Conversioner');
                    $this->email->to($data_user['email']);

                    $this->email->subject('Остаток на счету');
                    $this->email->message($message);

                    $this->email->send();
                }

                $this->input->set_cookie("send_message_completed", "1", 120);
            }
        } else {
            $sms_send_status = 'empty_balance';
        }

        $data_sms_history = array(
            'phone_contact' => mysql_real_escape_string(strip_tags($phone)),
            'status' => $sms_send_status,
            'site_url' => $data_script['site_url'],
            'id_user' => $data_script['id_user'],
        );

        $this->generate_widget_model->save_sms_history($data_sms_history);

        if ($sms_send_status == 1) {
            $this->input->set_cookie("send_message_completed", "1", 120);
        }

        echo $sms_send_status;
        exit;
    }

}
