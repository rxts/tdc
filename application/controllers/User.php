<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('authex');
        if (!$this->authex->logged_in()) {
            redirect('user/login');
        }
    }

    public function index() {
        $this->load->view('user/index');
    }

    public function login() {
        $this->load->view('user/login');
    }

}
