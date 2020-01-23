<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Home extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('home_model','xmod');
        // $this->load->model('Ops_model', 'ops');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
     
    }

    function index(){
       
        $this->data['count_orders']     = $this->xmod->get_count("spf_orders_done");
        $this->data['count_install']    = $this->xmod->get_count("installs","","COUNT(DISTINCT store)");
        $this->data['cod_amount']       = $this->xmod->get_count("spf_orders_done","","SUM(cod_amount)");
        $this->data['count_user']       = $this->xmod->get_count("users","","COUNT(user_id)");
        $this->section['content']       = $this->load->view('home',$this->data,true);

        $this->myview->view($this->section,$this->template);
    }


}
