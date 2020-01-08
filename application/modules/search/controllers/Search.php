<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Search extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        //$this->sybase = $this->load->driver('sybase_driver');
        $this->data['ci_head'] = '';
        $this->data['js_dir'] = $this->ci_js;
        $this->data['css_dir'] = $this->ci_css;
        $this->data['added_js'] = "";
        $this->load->model('search_model','xmod');        
    }

    function index(){
       $keyword = $this->input->get("keyword");

       $keyword = clean_text($keyword);
       if (substr( $keyword, 0, 4 ) === "BAPP"){
        // if string begins in BAPP
        $search_result = $this->xmod->get("app_carts","tracking_no = '$keyword'");
        if ($search_result){
            redirect(base_url("orders/id/".$search_result[0]->id));
        }
        else{
            $error = "No results found";
        }
        
       }
       else{
        // search in app
        echo "xcsada";
       }

        $this->section['content'] = $this->load->view('search',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */