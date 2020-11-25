<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Orders extends MX_Controller
{

    //var $template = 'default';
    var $template = 'default';
    function __construct()
    {
        parent::__construct();
        $this->load->model('Orders_model', 'ord');
        // $this->load->model('Ops_model', 'ops');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function index()
    {
        $this->data['page_title'] = "Shopify Orders Ongoing";
        $this->data['orders'] = $this->ord->get("spf_orders");
        $this->section['content'] = $this->load->view('orders/orders_view', $this->data, true);
        $this->myview->view($this->section, $this->template);
    }


    function get_orders()
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

        // $join = array("services sc" => array("left" => "sc.id = spf.service_id"));
        $total_record = $this->ord->get_count("spf_orders");
        if ($search) {
            $cond = "(
              id LIKE '%$search%' OR
              store_domain LIKE '%$search%' OR
              recipient_name LIKE '%$search%'
            )";
            $fil_total = $this->ord->get_count("spf_orders", "", "COUNT(id)");
        }

        $order_arr = array(
            "id",
            "store_domain",
            "sender_name",
            "sender_addr",
            "sender_tel",
            "sender_email",
            "sender_zip",
            "recipient_name",
            "recipient_addr",
            "recipient_tel",
            "recipient_email",
            "recipient_zip",
            "cust_actual_kg",
            "cust_applied_kg",
            "remarks",
            "delivery_fee",
            "payment_method",
            "cod_amount",
            "declared_value",
            "box_type",
            "order_date",
            "last_modified",
            "cancel_date",
            "entry_date"
        );
        // $userData = $this->ord->get("spf_orders as spf", $join, $cond, "*", $order_arr[$order_column] . " " . $order_type, $limit, $offset);
        $userData = $this->ord->get("spf_orders", $cond, "*", "", $order_arr[$order_column] . " " . $order_type, $limit, $offset);

        // echo $this->ord->CI->db->last_query();
        // die();

        if ($userData) {
            foreach ($userData as $rdata) {
                $rowData = array();
                $rowData[] = $rdata->cas_client_id;
                $rowData[] = "<span class='badge badge-pill badge-success text-white'>Store Domain:</span>   " . $rdata->store_domain . "<br>Sender Name: " . $rdata->sender_name . "<br>Address: " . $rdata->sender_addr . "<br>Zip code: " . $rdata->sender_zip . "<br>Email: " . $rdata->sender_email . "<br>Tel: " .  $rdata->sender_tel;
                $rowData[] = "<span class='badge badge-pill badge-success text-white'>Name:</span>  " . $rdata->recipient_name . "<br>Email: " . $rdata->recipient_email . "<br>Address: " . $rdata->recipient_addr . "<br>Zip code: " .  $rdata->recipient_zip  . "<br>Tel: " . $rdata->recipient_tel;
                $rowData[] = $rdata->remarks . "<br>Cust. actual KG: " . $rdata->cust_actual_kg . "<br>Cust. applied KG: " . $rdata->cust_applied_kg . "<br>Box type: " . $rdata->box_type . "<br><span class='badge badge-pill badge-success text-white'>Declared Value: " . $rdata->declared_value . "</span>";
                $rowData[] = "Payment Method: " . $rdata->payment_method . "<br>Delivery fee: " . $rdata->delivery_fee . "<br>(COD) Amount: " . $rdata->cod_amount;
                $rowData[] = date('M d, Y H:i:s A', strtotime($rdata->order_date));
                $rowData[] = date('M d, Y H:i:s A', strtotime($rdata->last_modified));
                $rowData[] = date('M d, Y H:i:s A', strtotime($rdata->cancel_date));
                $rowData[] = date('M d, Y H:i:s A', strtotime($rdata->entry_date));
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
}