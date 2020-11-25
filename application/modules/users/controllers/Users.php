<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Users extends MX_Controller
{

    //var $template = 'default';
    var $template = 'default';
    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model', 'xmod');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function index()
    {
        $this->data['page_title'] = "Users";
        $this->data['user_list'] = $this->xmod->get("users");
        $this->data['modal_msg'] = "";
        $this->section['content'] = $this->load->view('users/landing', $this->data, true);
        $this->myview->view($this->section, $this->template);
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
        $cond = "";

        $total_record = $this->xmod->get_count("users", "", "COUNT(user_id)");
        if ($search) {
            $cond = "(
                username LIKE '%$search%'
               
            )";
            $fil_total = $this->xmod->get_count("users", "", "COUNT(user_id)");
        }

        $order_arr = array(
            "first_name",
            "username",
            "contact",
            "address",
            "last_login"

        );
        $userData = $this->xmod->get("users", $cond, "*", "", $order_arr[$order_column] . " " . $order_type, $limit, $offset);

        if ($userData) {
            foreach ($userData as $rdata) {
                $rowData = array();
                $rowData[] = $rdata->fullname;
                $rowData[] = $rdata->email;
                $rowData[] = date('F d, Y H:i A', strtotime($rdata->last_login));
                $rowData[] = date('F d, Y H:i A', strtotime($rdata->created_at));
                $rowData[] = date('F d, Y H:i A', strtotime($rdata->modified_at));
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
    function new()
    {
        $error = $this->input->get("error");
        $success = $this->input->get("success");
        if ($error) {
            $this->data['modal_msg'] = "Your username is already exist try another username or email address!";
        }
        if ($success) {
            $this->data['modal_msg'] = "";
        }
        $this->data['page_title'] = "Users";
        $this->section['content'] = $this->load->view('Users/landing', $this->data, true);
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

        $cond = $this->xmod->get("users", "email = '$username'", "email");
        if ($cond) {
            redirect(base_url("new?error=1"));
        } else {
            if ($ajax) {
                if ($upass1 == $upass2) {
                    $userData->username = $username;
                    $userData->email = $username;
                    $userData->passwd = $this->xmod->hash_password($upass1);
                    $userData->password_text = $upass1;
                    $userData->contact = $contact;
                    $userData->fullname = $fname . " " . $lname;
                    $userData->auth_level = 9;
                    if ($this->xmod->save("users", $userData));
                    echo json_encode(array("result" => "ok"));
                    die();
                }
            } else {
                echo "error";
                die();
            }
        }
    }
}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */