<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authex {

    function __construct() {
        $CI = & get_instance();
        $CI->load->database();
    }

    function get_userdata() {
        $CI = & get_instance();
        if (!$this->logged_in()) {
            return false;
        } else {
            $query = $CI->db->get_where('users', array('ID' => $CI->session->userdata('user_id')));
            return $query->row();
        }
    }

    function logged_in() {
        $CI = & get_instance();
        return ($CI->session->userdata('user_id')) ? true : false;
    }

    function login($email, $password) {
        $CI = & get_instance();
        $CI->load->database();        
        $data = array(
            'email' => $email,
            'password' =>  $password
        );
        $query = $CI->db->get_where('users', $data);
        if ($query->num_rows() !== 1) {
            return false;
        } else {
            $last_login = date('Y-m-d H-i-s');
            $data = array(
                'last_login' => $last_login
            );
            $CI->db->update('users', $data);
            $CI->session->set_userdata('user_id', $query->row()->ID);
            return true;
        }
    }

    function logout() {
        $CI = & get_instance();
        $CI->session->unset_userdata('user_id');
    }

    function register($email, $password) {
        $CI = & get_instance();
        if ($this->can_register($email)) {
            $data = array(
                'email' => $email,
                'password' =>  ($password)
            );
            $CI->db->insert('users', $data);
            return true;
        }
        return false;
    }

    function can_register($email) {
        $CI = & get_instance();
        $query = $CI->db->get_where('users', array('email' => $email));
        return ($query->num_rows() < 1) ? true : false;
    }

}
