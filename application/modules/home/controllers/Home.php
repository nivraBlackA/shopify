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
    
        $this->section['content'] = $this->load->view('home',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }
}
