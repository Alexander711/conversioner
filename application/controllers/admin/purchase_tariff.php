<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_tariff extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/purchase_tariff_model');
        $this->load->model('admin/authorization_model', 'admin_authorization_model');
        $this->load->library('session');
    }

    /**
     * функция выводит список всех пользователей,которые приобрели "конвертик"
     */
    public function index() {
        if (!$this->admin_authorization_model->check_auth()) {
            redirect('/admin/authorization/login', 'refresh');
        }

        $data['title'] = 'Список зарегистрированных пользователей';

        $data['other_js'] = array('js/jquery.ba-throttle-debounce.min.js', 'js/jquery.stickyheader.js', 'js/admin/script.js');

        if ($_POST) {
            $search_data = $this->input->post();

            $data['search_data'] = $search_data;

            $data['all_users'] = $this->purchase_tariff_model->get_all_users($search_data);
        } else {
            $data['all_users'] = $this->purchase_tariff_model->get_all_users();
        }

        $all_tariffs['tariffs_list'] = $this->purchase_tariff_model->get_all_tariffs();

        $data['purchase_tariff_window'] = $this->load->view('admin/purchase_tariff', $all_tariffs, TRUE);

        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/user_list', $data);
        $this->load->view('admin/templates/footer');
    }

    /**
     * функция покупки тарифа для выбранного пользователя
     */
    public function purchase_tariff_for_user() {
        if (!$this->admin_authorization_model->check_auth()) {
            redirect('/admin/authorization/login', 'refresh');
        }

        $data_post = $this->input->post();
        
        $data_post['name_admin'] = $this->session->userdata('name');
        $data_post['last_name_admin'] = $this->session->userdata('last_name');

        $error_mass = '';

        if ($data_post['tariff_id'] == '') {
            $error_mass .= '<span>Выберите тариф</span><br/>';
        }

        if ($error_mass == '') {
            $result_change_tariff = $this->purchase_tariff_model->change_tariff_for_user($data_post);

            if($result_change_tariff != 'error_change_tariff'){
                $data_ajax['status_ajax'] = 'ok';
                $data_ajax['new_count_sms_user'] = $result_change_tariff;
            }else{
                $data_ajax['status_ajax'] = 'error';
                $data_ajax['error_mass'] = '<span>Произошла ошибка</span><br/>';
            }
        } else {
            $data_ajax['status_ajax'] = 'error';
            $data_ajax['error_mass'] = $error_mass;
        }

        print_r(json_encode($data_ajax));
        exit;
    }

}
?>