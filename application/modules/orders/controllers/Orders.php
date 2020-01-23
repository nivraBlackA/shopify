<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Orders extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('Orders_model','ord');
        // $this->load->model('Ops_model', 'ops');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
     
    }

    function index(){
        $this->data['page_title'] = "Shopify Orders Ongoing";
        $this->data['orders'] = $this->ord->get("spf_orders");
        $this->section['content'] = $this->load->view('orders/orders_view',$this->data,true);
        $this->myview->view($this->section,$this->template);
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

        $join = array("services sc" => array("left" => "sc.id = spf.service_id"));
        $total_record = $this->ord->get_count("spf_orders");
        if ($search){
            $cond = "(
                id LIKE '%$search%'
            )";
            $fil_total = $this->ord->get_count("spf_orders",$cond);
        }

        $order_arr = array(
                "spf.id",
                "spf.store_domain",
                "spf.sender_name",
                "spf.sender_addr",
                "spf.sender_tel",
                "spf.sender_email",
                "spf.sender_zip",
                "spf.recipient_name",
                "spf.recipient_addr",
                "spf.recipient_tel",
                "spf.recipient_email",
                "spf.recipient_zip",
                "spf.cust_actual_kg",
                "spf.cust_applied_kg",
                "spf.remarks",
                "spf.delivery_fee",
                "spf.payment_method",
                "spf.cod_amount",
                "spf.declared_value",
                "spf.box_type",
                "spf.oder_date",
                "spf.last_modified",
                "spf.cancel_date",
                "spf.entry_date",
        );
        $userData = $this->ord->find_with_joins("spf_orders spf",$join,$cond,"*","",$order_arr[$order_column]. " " .$order_type,$limit,$offset);
        
        if ($userData){
            $get_data = $this->ord->get_records();
            foreach($get_data as $rdata){
                $rowData = array();
                $rowData[] = $rdata->id;
                $rowData[] = "Store Domain: ". $rdata->store_domain ."<br>Sender Name: ". $rdata->sender_name ."<br>Address: ". $rdata->sender_addr ."<br>Zip code: ". $rdata->sender_zip. "<br>Email: ".$rdata->sender_email ."<br>Tel: ".  $rdata->sender_tel;;
                $rowData[] = "Name: ".$rdata->recipient_name ."<br>Email: ". $rdata->recipient_email ."<br>Address: ".$rdata->recipient_addr ."<br>Zip code: ".  $rdata->recipient_zip  . "<br>Tel: ". $rdata->recipient_tel;
                $rowData[] = $rdata->remarks;
                $rowData[] = $rdata->cust_actual_kg;
                $rowData[] = $rdata->cust_applied_kg;
                $rowData[] = $rdata->box_type;
                $rowData[] = "Payment Method: ".$rdata->payment_method ."<br>Delivery fee: ". $rdata->delivery_fee ."<br>(COD) Amount: ".$rdata->cod_amount;
                $rowData[] = $rdata->declared_value;
                $rowData[] = date('M d, Y H:i:s A',strtotime($rdata->order_date));
                $rowData[] = date('M d, Y H:i:s A',strtotime($rdata->last_modified));
                $rowData[] = date('M d, Y H:i:s A',strtotime($rdata->cancel_date));
                $rowData[] = date('M d, Y H:i:s A',strtotime($rdata->entry_date));
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
