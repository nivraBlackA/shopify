<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Pagenotfound extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        //$this->sybase = $this->load->driver('sybase_driver');
        $this->data['ci_head'] = '';
        $this->data['js_dir'] = $this->ci_js;
        $this->data['css_dir'] = $this->ci_css;
        $this->data['added_js'] = "";
    }

    function index(){
       
        $this->section['content'] = $this->load->view('pagenotfound',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */