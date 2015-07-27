<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class P404 extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * функция генерирует страницу 404
     */
    public function index() {
        $this->output->set_status_header('404');
        
        $data['title'] = '404 Страница не найдена';
        
        $this->load->view('p404/p404.php',$data);
    }
}