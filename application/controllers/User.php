<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('authex');
    }

    public function index() {
        if ($this->authex->logged_in()) {
            $this->load->view('user/index');
        } else {
            $this->login();
        }
    }

    public function login() {
        $this->load->view('user/login');
    }
    
    public function check_login() {        
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        if ($this->authex->login($email, $password)) {
            $this->login_success();
        } else {
            $this->login_failed();
        }
    }
    
    public function login_success() {
        $this->load->view('user/login_success');
    }
    
    public function login_failed() {
        $this->load->view('user/login_failed');
    }

    public function logout() {
        $this->authex->logout();
        $this->load->view('user/logout');
    }
}
