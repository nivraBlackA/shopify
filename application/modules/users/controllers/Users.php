<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Users extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('users_model','xmod');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function index(){
        $this->data['user_list'] = $this->xmod->get("users");  
        $this->section['content'] = $this->load->view('users/landing',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function get_users()
    {
        session_write_close();
        $dtable         = $this->input->get();
        $draw           = $dtable['draw'];
        $offset         = $dtable['start'];
        $limit          = $dtable['length'];
        $order_column   = $dtable['order'][0]['column'];
        $order_type     = $dtable['order'][0]['dir'];
        $search         = $dtable['search']['value'];
        $fil_total = 0;
        $total_record = 0;
        $item_data = array();
       
        $total_record = $this->xmod->get_count("users","","COUNT(user_id)");;
        if ($search){
            $cond = "(
                username LIKE '%$search%' OR
                email LIKE '%$search%' OR 
                first_name LIKE '%$search%' OR 
                last_name LIKE '%$search%' OR 
                address LIKE '%$search%' OR 
                last_login LIKE '%$search%'
            )";
            $fil_total = $this->xmod->get_count("users","","COUNT(user_id)");
        }

        $order_arr = array(
                "first_name",
                "username",
                "contact",
                "address",
                "last_login"
           
        );
        $userData = $this->xmod->get("users","","*","",$order_arr[$order_column]. " " .$order_type,$limit,$offset);
   
        if ($userData){
            foreach($userData as $rdata){
                $rowData = array();
                $rowData[] = $rdata->first_name ." ". $rdata->last_name;
                $rowData[] = $rdata->username;
                $rowData[] = $rdata->contact;
                $rowData[] = $rdata->address;
                $rowData[] = date('F d, Y H:i A',strtotime($rdata->last_login));
                $item_data[] = $rowData;
             }
        }

        $json_data = array(
            "draw"            => $draw,
            "recordsTotal"    => $total_record,
            "recordsFiltered" => ($fil_total) ? $fil_total : $total_record,
            "data"            => $item_data
        );
        echo json_encode($json_data);
        die();
    }

    function create()
    {
        $userData   =   new stdClass();
        $fname      =   $this->input->post('fname');
        $lname      =   $this->input->post('lname');
        $contact    =   $this->input->post('contact');
        $username   =   $this->input->post('username');
        $upass1     =   $this->input->post('upass1');
        $upass2     =   $this->input->post('upass2');
        $ajax       =   $this->input->post('ajax');

        if($ajax)
        {
            if($upass1 == $upass2){
                $userData->user_id = rand();
                $userData->username = $username;
                $userData->passwd = $this->xmod->hash_password($upass1);
                $userData->contact = $contact;
                $userData->first_name = $fname;
                $userData->last_name = $lname;
                $userData->fullname = $fname ." ". $lname;
                $userData->auth_level = 100;
                if($this->xmod->save("users",$userData));
                echo json_encode(array("result" => "ok"));
                die();
            }
        }
        else{
            echo "error";
            die();
        }

    }
}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */