<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Site_model extends MX_Model {

    function __construct(){
        parent::__construct();
    }

    function get_sidebar_count(){
        // $today = date("Y-m-d");
        // $feedback_count = $this->get_count("app_feedbacks","DATE_FORMAT(date_created,'%Y-%m-%d') = '$today'");
        // $users_count = $this->get_count("app_users","DATE_FORMAT(registration_datetime,'%Y-%m-%d') = '$today'");
        // $order_today = $this->get_count("app_carts","DATE_FORMAT(payment_datetime,'%Y-%m-%d') = '$today'");

        $countArr = new stdClass();
        $countArr->feedback_count = 0;
        $countArr->order_today = 0;
        $countArr->users_count = 0;
        return $countArr;
    }
}