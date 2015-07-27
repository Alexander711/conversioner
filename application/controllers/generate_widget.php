<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_widget extends CI_Controller {

    const PATH_IMG = 'uploads/img_contact_window';
    const PATH_TEMPORARY_UPLOADS = 'temporary_uploads';

    public function __construct() {
        parent::__construct();
        $this->load->model('generate_widget_model');
        $this->load->model('authorization_model');
        $this->load->library('session');
        $this->load->helper('work_helper');
    }

    /**
     * функция выводит список всех "конвертиков" пользователя
     */
    public function widgets() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/authorization/login', 'refresh');
        }

        $data['title'] = 'Мои «конвертики»';

        $id_user = $this->session->userdata('id_user');

        $data['id_user'] = $id_user;

        $data['all_widgets'] = $this->generate_widget_model->get_all_widgets($id_user);

        $data['other_css'] = array('css/uploadify.css');

        $data['other_js'] = array('js/jquery.ba-throttle-debounce.min.js',
            'js/jquery.stickyheader.js',
            'js/script.js',
            'js/widgets.js',
            'js/uploadify/uploadify.js',
            'js/uploadify/connect_uploadify.js');

        $data['add_widget_form'] = $this->load->view('generate_widget/add_widget', '', TRUE);

        $data_user = $this->authorization_model->get_data_user_by_id($id_user);

        for ($i = 0; $i <= 23; $i++) {
            $key = $i;

            if (mb_strlen($i) == 1) {
                $i = '0' . $i;
            }

            $hours_list['hours'][] = $i;
        }

        $data['balance_user'] = $this->generate_widget_model->get_balance_user($id_user);

        $data['edit_user_data_form'] = $this->load->view('authorization/edit_user_data', $data_user, TRUE);
        $data['install_widget_window'] = $this->load->view('generate_widget/install_widget', $hours_list, TRUE);

        $all_tariffs['tariffs_list'] = $this->generate_widget_model->get_all_tariffs();

        $data['order_tariff_window'] = $this->load->view('generate_widget/order_tariff', $all_tariffs, TRUE);

        $this->load->view('templates/header', $data);
        $this->load->view('generate_widget/widgets', $data);
        $this->load->view('templates/footer');
    }

    /**
     * функция удаления выбранного "конвертика"
     */
    public function delete_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $this->load->helper('file');

        $id_widget = $this->input->post('id_widget');

        $id_user = $this->session->userdata('id_user');

        $img_window = $this->generate_widget_model->get_img_window_for_widget($id_widget, $id_user);

        $del_status = $this->generate_widget_model->delete_widget($id_widget, $id_user);

        if ($del_status == 'del_ok') {
            if ($img_window != 'man.jpg' && $img_window != 'wom.jpg') {
                @unlink("uploads/img_contact_window/" . $img_window);
            }

            @unlink("widgets/widget_" . md5($id_widget) . ".js");
        }
    }

    /**
     * функция добавления и редактирования "конвертика"
     * @param int $id
     */
    public function add_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $upload_dir = FCPATH . self::PATH_TEMPORARY_UPLOADS;
        $final_folder = FCPATH . self::PATH_IMG;
        $id_user = $this->session->userdata('id_user');

        $error_mass = '';

        if ($this->input->post('phone') == '') {
            $error_mass .= '<span>Заполните поле "Номер телефона"</span><br/>';
        }

        if ($this->input->post('email') == '') {
            $error_mass .= '<span>Заполните поле "Email отдела продаж"</span><br/>';
        }

        if (mb_strlen($this->input->post('title_window')) > 49) {
            $error_mass .= '<span>Длина заголовка окна не должна превышать 49 символов</span><br/>';
        }

        if (mb_strlen($this->input->post('content_window')) > 98) {
            $error_mass .= '<span>Длина контента окна не должна превышать 98 символов</span><br/>';
        }

        if (mb_strlen($this->input->post('text_button')) > 28) {
            $error_mass .= '<span>Длина текста кнопки не должна превышать 28 символов</span><br/>';
        }

        if ($this->input->post('site_url') == '') {
            $error_mass .= '<span>Заполните поле "Ссылка на сайт"</span><br/>';
        }

        if ($this->input->post('title_window') != '') {
            $title_window = nl2br(strip_tags($this->input->post('title_window'), '<br>,<br/>'), FALSE);

            $count_br = substr_count($title_window, '<br/>') + substr_count($title_window, '<br>');

            if ($count_br > 1) {
                $error_mass .= '<span>В заголовке окна не должно быть больше одного переноса на новую строку</span><br/>';
            }
        } else {
            $title_window = 'Получите медиаплан уже сегодня!';
        }

        if ($this->input->post('content_window') != '') {
            $content_window = nl2br(strip_tags($this->input->post('content_window'), '<br>,<br/>'), FALSE);

            $count_br = substr_count($content_window, '<br/>') + substr_count($content_window, '<br>');

            if ($count_br > 2) {
                $error_mass .= '<span>В тексте окна не должно быть больше одного переноса на новую строку</span><br/>';
            }
        } else {
            $content_window = 'Я перезвоню Вам для уточнения деталей <br>и помогу с заполнением брифа.';
        }

        if ($this->input->post('name_uploaded_image') == '' && !$this->input->post('img_window')) {
            $error_mass .= '<span>Выберите изображение для "Конвертика"</span><br/>';
        }

        if ($error_mass == '') {
            $data_array['options_widget'] = array(
                'phone' => mysql_real_escape_string(strip_tags($this->input->post('phone'))),
                'email' => mysql_real_escape_string(strip_tags($this->input->post('email'))),
                'title_window' => $title_window,
                'content_window' => $content_window,
                'text_button' => $this->input->post('text_button') != '' ? mysql_real_escape_string(strip_tags(nl2br($this->input->post('text_button')))) : 'Позвоните в течение 15 минут',
                'time_attention' => (int) $this->input->post('time_attention') * 1000,
                'detect_exit' => $this->input->post('detect_exit') ? 1 : 0,
            );

            if ($this->input->post('name_uploaded_image') != '') {
                $name_uploaded_image = $this->input->post('name_uploaded_image');

                if ($this->input->post('id') != 0) {
                    $check_img_exist = $this->generate_widget_model->check_img_exist($name_uploaded_image, $id_user, $this->input->post('id'));

                    if (empty($check_img_exist)) {
                        $result_save_img = $this->generate_widget_model->save_img($name_uploaded_image);

                        if ($result_save_img == 'big_img' || $result_save_img == 'small_img') {
                            $data_ajax['status_ajax'] = 'error';
                            $data_ajax['error_mass'] = '<span>Изображение должно быть размером 487 на 274 px</span><br/>
                                                        <span>Допускается изображение на 10% большее этого размера</span>';

                            print_r(json_encode($data_ajax));
                            exit;
                        } elseif ($result_save_img == 'error_save_img') {
                            $data_ajax['status_ajax'] = 'error';
                            $data_ajax['error_mass'] = '<span>Произошла ошибка при загрузке изображения</span><br/>';

                            print_r(json_encode($data_ajax));
                            exit;
                        } else {
                            $img_window = $result_save_img;
                        }
                    } else {
                        $img_window = mysql_real_escape_string(strip_tags($name_uploaded_image));
                    }
                } else {
                    $result_save_img = $this->generate_widget_model->save_img($name_uploaded_image);

                    if ($result_save_img == 'big_img' || $result_save_img == 'small_img') {
                        $data_ajax['status_ajax'] = 'error';
                        $data_ajax['error_mass'] = '<span>Изображение должно быть размером 487 на 274 px</span><br/>
                                                    <span>Допускается изображение на 10% большее этого размера</span>';

                        print_r(json_encode($data_ajax));
                        exit;
                    } elseif ($result_save_img == 'error_save_img') {
                        $data_ajax['status_ajax'] = 'error';
                        $data_ajax['error_mass'] = '<span>Произошла ошибка при загрузке изображения</span><br/>';

                        print_r(json_encode($data_ajax));
                        exit;
                    } else {
                        $img_window = $result_save_img;
                    }
                }
            } else {
                $img_window = mysql_real_escape_string(strip_tags($this->input->post('img_window'))) . '.jpg';
            }

            $data_array['options_widget']['img_window'] = $img_window;

            $data_array['site'] = array(
                'site_url' => mysql_real_escape_string(strip_tags($this->input->post('site_url'))),
                'id_user' => (int) $id_user,
            );

            $data_ajax = array(
                'site_url' => $data_array['site']['site_url'],
                'email' => $data_array['options_widget']['email'],
                'phone' => $data_array['options_widget']['phone'],
            );

            if ($this->input->post('id') == 0) {
                $data_array['options_widget']['id_user'] = $id_user;

                do {
                    $conversioner_code = create_random_code(32);

                    $conv_code_exist = $this->generate_widget_model->check_conv_code_exist($conversioner_code);
                } while (!empty($conv_code_exist));

                $data_array['options_widget']['conversioner_code'] = $conversioner_code;

                $data = $this->generate_widget_model->save_options_widget($data_array);

                if ($data != 'error_insert') {
                    if (!$this->generate_widget($data)) {
                        $data_ajax['status_ajax'] = 'error';
                        $data_ajax['error_mass'] = '<span>Произошла ошибка, попробуйте позже</span><br/>';
                        print_r(json_encode($data_ajax));
                        exit;
                    }

                    if ($this->input->post('name_uploaded_image') != '') {
                        @unlink("$upload_dir/$name_uploaded_image");
                    }

                    $data_ajax['id_widget'] = $data;
                    $data_ajax['is_installed'] = 0;
                    $data_ajax['is_active'] = 1;
                    $data_ajax['is_update'] = 0;
                    $data_ajax['status_ajax'] = 'ok';

                    print_r(json_encode($data_ajax));
                    exit;
                } else {
                    $new_img_window = $data_array['options_widget']['img_window'];

                    if ($new_img_window != 'man.jpg' && $new_img_window != 'wom.jpg') {
                        @unlink("$final_folder/$new_img_window");
                    }

                    $data_ajax['status_ajax'] = 'error';
                    $data_ajax['error_mass'] = '<span>Произошла ошибка, попробуйте позже</span><br/>';
                    print_r(json_encode($data_ajax));
                    exit;
                }
            } else {
                $old_img_window = $this->generate_widget_model->get_img_window_for_widget($this->input->post('id'), $id_user);
                $data = $this->generate_widget_model->update_options_widget($this->input->post('id'), $id_user, $data_array);

                if ($data == 'update_ok') {
                    if (!$this->generate_widget($this->input->post('id'))) {
                        $data_ajax['status_ajax'] = 'error';
                        $data_ajax['error_mass'] = '<span>Произошла ошибка, попробуйте позже</span><br/>';
                        print_r(json_encode($data_ajax));
                        exit;
                    }

                    if ($old_img_window != 'man.jpg' && $old_img_window != 'wom.jpg') {
                        @unlink("$final_folder/$old_img_window");
                    }
                    
                    if ($this->input->post('name_uploaded_image') != '' && empty($check_img_exist)) {
                        @unlink("$upload_dir/$name_uploaded_image");
                    }

                    $data_status_widget = $this->generate_widget_model->get_active_instal_by_id($this->input->post('id'), $id_user);

                    $data_ajax['is_installed'] = $data_status_widget['is_installed'];
                    $data_ajax['is_active'] = $data_status_widget['is_active'];
                    $data_ajax['id_widget'] = $this->input->post('id');
                    $data_ajax['is_update'] = 1;
                    $data_ajax['status_ajax'] = 'ok';

                    print_r(json_encode($data_ajax));
                    exit;
                } else {
                    $new_img_window = $data_array['options_widget']['img_window'];

                    if ($new_img_window != 'man.jpg' && $new_img_window != 'wom.jpg') {
                        @unlink("$final_folder/$new_img_window");
                    }

                    $data_ajax['status_ajax'] = 'error';
                    $data_ajax['error_mass'] = '<span>Произошла ошибка, попробуйте позже</span><br/>';
                    print_r(json_encode($data_ajax));
                    exit;
                }
            }
        } else {
            $data_ajax['status_ajax'] = 'error';
            $data_ajax['error_mass'] = $error_mass;

            print_r(json_encode($data_ajax));
            exit;
        }
    }

    /**
     * функция генерации кода "конвертика"
     * @param int $id_widget
     */
    private function generate_widget($id_widget = 0) {
        $this->load->helper('file');
        $this->load->library('zip');

        $id_user = $this->session->userdata('id_user');

        $data = $this->generate_widget_model->get_data_widget($id_widget, $id_user);

        if (write_file('widgets/widget_' . md5($id_widget) . '.js', $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * функция генерации кода "конвертика"
     */
    public function get_options_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $id_widget = $this->input->post('id_widget');
        $id_user = $this->session->userdata('id_user');

        $data = $this->generate_widget_model->get_options_widget_by_id($id_widget, $id_user);

        if (empty($data)) {
            echo json_encode('widget_not_found');
            exit;
        } else {
            print_r(json_encode($data));
            exit;
        }
    }

    /**
     * функция включения и выключения "конвертика" на сайте
     */
    public function change_active_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $id_widget = $this->input->post('id_widget');
        $id_user = $this->session->userdata('id_user');
        $active_status = $this->input->post('active_status');

        $this->generate_widget_model->update_field_is_active($id_widget, $id_user, $active_status);

        if ($this->generate_widget($id_widget)) {
            echo "ok";
            exit;
        }
    }

    /**
     * функция проверки установленности "конвертика" на сайте
     */
    public function installation_check() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $id_widget = $this->input->post('id_widget');
        $id_user = $this->session->userdata('id_user');

        $data = $this->generate_widget_model->get_site_url_conv_code_detect_exit_by_id($id_widget, $id_user);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['site_url']);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $curl_data = curl_exec($ch);

        preg_match('/var conversioner_code="' . $data['conversioner_code'] . '"/si', $curl_data, $result);

        if (!empty($result)) {
            $this->generate_widget_model->update_field_is_installed($id_widget, $id_user, 1);

            echo 'installed';
            exit;
        } else {
            $this->generate_widget_model->update_field_is_installed($id_widget, $id_user, 0);

            echo 'not installed';
            exit;
        }
    }

    /**
     * функция выводит список отправок смс для пользователя
     */
    public function sms_history_list() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/authorization/login', 'refresh');
        }

        $id_user = $this->session->userdata('id_user');
        $data['title'] = 'Собранные контакты';
        $data['other_js'] = array('js/jquery.ba-throttle-debounce.min.js', 'js/jquery.stickyheader.js', 'js/jquery-ui.js', 'js/script.js', 'js/widgets.js', 'js/sms_history.js');
        $data['other_css'] = array('css/jquery-ui.css');

        if ($_POST) {
            $search_data = $this->input->post();

            $data['search_data'] = $search_data;

            $data['sms_history_list'] = $this->generate_widget_model->get_sms_history($id_user, $search_data);
        } else {
            $data['sms_history_list'] = $this->generate_widget_model->get_sms_history($id_user);
        }

        $data_user = $this->authorization_model->get_data_user_by_id($id_user);

        $data['all_sites'] = $this->generate_widget_model->get_all_sites_from_history($id_user);

        $data['balance_user'] = $this->generate_widget_model->get_balance_user($id_user);

        $data['edit_user_data_form'] = $this->load->view('authorization/edit_user_data', $data_user, TRUE);

        $all_tariffs['tariffs_list'] = $this->generate_widget_model->get_all_tariffs();

        $data['order_tariff_window'] = $this->load->view('generate_widget/order_tariff', $all_tariffs, TRUE);

        $this->load->view('templates/header', $data);
        $this->load->view('generate_widget/sms_history_list', $data);
        $this->load->view('templates/footer');
    }

    /**
     * функция выводит данные для установки "конвертика", а также форму для отправки иснтрукции
     * программисту и форму настройки времени работы "конвертика"
     */
    public function install_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $data['id_user'] = $this->session->userdata('id_user');

        $data['id_widget'] = $this->input->post('id_widget');

        $site_url_conv_code_detect_exit_by_id = $this->generate_widget_model->get_site_url_conv_code_detect_exit_by_id($data['id_widget'], $data['id_user']);

        if (empty($site_url_conv_code_detect_exit_by_id)) {
            echo json_encode('error_install_widget');
            exit;
        }

        $site_url_conv_code_detect_exit_by_id['id_widget'] = md5($data['id_widget']);

        print_r(json_encode($site_url_conv_code_detect_exit_by_id));
        exit;
    }

    /**
     * функция отправки инструкции по установке "конвертика" для программиста
     */
    public function instruction_programmer() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        if ($this->input->post('email_progr') == '') {
            echo '<span>Введите Email программиста</span><br/>';
            exit;
        } else {

            $id_user = $this->session->userdata('id_user');
            $id_widget = $this->input->post('id_widget');

            $message = $this->generate_widget_model->generate_message_for_progr($id_user, $id_widget);

            $this->load->library('email');

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
            $this->email->to($this->input->post('email_progr'));

            $this->email->subject('Инструкция по установку "Конвертика".');
            $this->email->message($message);

            if (!$this->email->send()) {
                echo '<span>Произошла ошибка, попробуйте позже</span><br/>';
                exit;
            } else {
                echo "ok";
                exit;
            }
        }
    }

    /**
     * функция получения времени работы для выбранного "конвертика"
     */
    public function get_time_work_widget() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $id_widget = $this->input->post('id_widget');
        $id_user = $this->session->userdata('id_user');

        $time_work = $this->generate_widget_model->get_time_work_widget($id_widget, $id_user);

        if (empty($time_work)) {
            echo json_encode('error_get_time_work');
            exit;
        }

        $time_work['time_start'] = $time_work['time_start'] / 60;

        $time_work['time_end'] = ($time_work['time_end'] - 59) / 60;

        print_r(json_encode($time_work));
        exit;
    }

    /**
     * функция установки времени работы "конвертика"
     */
    public function time_work() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        for ($i = 0; $i <= 23; $i++) {
            $key = $i;

            if (mb_strlen($i) == 1) {
                $i = '0' . $i;
            }

            $hours[] = $i;
        }

        for ($i = 0; $i <= 7; $i++) {
            $days[] = $i;
        }

        $error_mass = '';

        $data_post = $this->input->post();

        if (!isset($data_post['work_days'])) {
            $error_mass .= '<span>Выберите хоть один рабочий день</span><br/>';
        } else {
            $diff = array_diff($data_post['work_days'], $days);

            if (!empty($diff)) {
                $error_mass .= '<span>Выберите правильные дни работы виджета</span><br/>';
            }
        }

        if ($data_post['time_start'] > $data_post['time_end']) {
            $error_mass .= '<span>Время окончания работы не должно быть больше времени начала работы</span><br/>';
        }

        if (!in_array($data_post['time_start'], $hours)) {
            $error_mass .= '<span>Выберите правильное время работы виджета</span><br/>';
        }

        if (!in_array($data_post['time_end'], $hours)) {
            $error_mass .= '<span>Выберите правильное время работы виджета</span><br/>';
        }

        if ($error_mass == '') {

            $id_widget = (int) $data_post['id_widget'];
            $id_user = $this->session->userdata('id_user');

            $this->generate_widget_model->change_time_work_widget($data_post, $id_widget, $id_user);
            if (!$this->generate_widget($id_widget)) {
                $data_ajax['status_ajax'] = 'error';
                $data_ajax['error_mass'] = '<span>Произошла ошибка, попробуйте позже</span><br/>';
                print_r(json_encode($data_ajax));
                exit;
            }

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

    /**
     * функция производит заказ тарифа для пользоваетля с отправкой письма на
     * почту всем администраторам "конвертика"
     */
    public function order_tariff() {
        if (!$this->authorization_model->check_auth()) {
            redirect('/p404', 'refresh');
        }

        $data_post = $this->input->post();

        $error_mass = '';

        if ($data_post['tariff_id'] == '') {
            $error_mass .= '<span>Выберите тариф</span><br/>';
        }

        $data_tariff = $this->generate_widget_model->get_data_tariff($data_post['tariff_id']);

        if (empty($data_tariff)) {
            $error_mass .= '<span>Выберите тариф</span><br/>';
        }

        if ($error_mass == '') {
            $email_all_users_admin = $this->generate_widget_model->get_email_all_users_admin();

            $this->load->library('email');

            $message = 'Пришла заявка на покупку тарифа "' . $data_tariff['name_tariff'] . '". Покупатель ' . $this->session->userdata('name') . ' ' . $this->session->userdata('last_name') . ' (' . $this->session->userdata('email') . ')';

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
            $this->email->to($email_all_users_admin);

            $this->email->subject('Заявка на заказ тарифа.');
            $this->email->message($message);

            if ($this->email->send()) {
                $data_ajax['status_ajax'] = 'ok';
            } else {
                $data_ajax['status_ajax'] = 'error';
                $data_ajax['error_mass'] = '<span>Произошла ошибка</span><br/>';
            }

            print_r(json_encode($data_ajax));
            exit;
        } else {
            $data_ajax['status_ajax'] = 'error';
            $data_ajax['error_mass'] = $error_mass;
        }

        print_r(json_encode($data_ajax));
        exit;
    }

    public function temporary_uploading_img_contact_window() {
        $upload_dir = FCPATH . self::PATH_TEMPORARY_UPLOADS;
        $max_file_size = 5 * 1024 * 1024;

        if (isset($_FILES)) {
            $ext = pathinfo($_FILES['Filedata']['name']);

            if ($ext['extension'] != 'jpg') {
                echo 'extension';
                exit;
            }

            if ($max_file_size < $_FILES['Filedata']['size']) {
                echo 'size';
                exit;
            }

            if (is_uploaded_file($_FILES['Filedata']['tmp_name'])) {

                do {
                    $file_name = 'i' . md5(microtime()) . '.jpg';
                } while (file_exists("$upload_dir/$file_name"));

                move_uploaded_file($_FILES['Filedata']['tmp_name'], "$upload_dir/$file_name");

                echo $file_name;
                exit;
            }
        }
    }

    public function del_temporary_upload_img() {
        $upload_dir = FCPATH . self::PATH_TEMPORARY_UPLOADS;

        $name_uploaded_image = $this->input->post('name_uploaded_image');

        if (@unlink("$upload_dir/$name_uploaded_image")) {
            echo json_encode('ok');
            exit;
        } else {
            echo json_encode('error');
            exit;
        }
    }

}

?>