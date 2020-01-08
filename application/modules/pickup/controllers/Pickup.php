<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Pickup extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('pickup_model','xmod');
        $this->load->model('Ops_model', 'ops');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function index(){
        $this->section['ci_css'][] = "vendor/selectpicker4/css/selectpicker4.min.css";
        $this->section['ci_js'][] = "vendor/selectpicker4/js/selectpicker4.min.js";
        
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $this->data['page_name'] = "Pickup Run Sheets";
        $this->data['date'] = date('Y-m-d');
        $this->data['status'] = $this->input->get('status');
        $this->data['select_opt']  = $this->ops->get_options("couriers","ec_id","ec_name","ec_id != 0",1,"ec_name","ec_id");
        $this->section['content'] = $this->load->view('pickup/pickup',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function get_table()
    {
        session_write_close();
        $date           = $this->input->get('date'); 
        $status         = $this->input->get('status'); 
        $ec_id           = $this->input->get('ec_id'); 

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

        $cond = "DATE_FORMAT(prs.entry_date,'%Y-%m-%d') = '$date'";

        if($status)
        {
            if($status == 'ongoing')
                $cond .= " AND prs.report_status_code >= 0 AND prs.report_status_code <= 12";
            elseif($status == 'failed')
                $cond .= " AND prs.report_status_code >= 14 AND prs.report_status_code < 30";
            elseif($status == 'success')
                $cond .= " AND prs.report_status_code >= 30";
        }

        if($ec_id)
            $cond .= " AND prs.ec_id = $ec_id";
        $user_join = array("rider_courier rc" => array("left" => "rc.courier_id = prs.courier_id"));

        $tCount = $this->xmod->find_with_joins(
        "rider_pickup prs", //table
        $user_join, //joins
        $cond, //condi
        "COUNT(prs.id) as total_count", //fields
        "",
        TRUE
        );
        if ($tCount)
            $total_record = $this->xmod->get_records()[0]->total_count;
        if ($search){
            $cond = "(
                prs.courier_id LIKE '%$search%' OR
                rc.courier_name LIKE '%$search%' OR 
                prs.tracking_no LIKE '%$search%' OR 
                prs.prs_id LIKE '%$search%'
            )";
            $sCount = $this->xmod->find_with_joins(
                "rider_users ru", //table
                $user_join, //joins
                $cond, //condi
                "COUNT(prs.id) as total_count", //fields
                "",
                TRUE
            );
            if ($sCount)
                $fil_total = $this->xmod->get_records()[0]->total_count;
        }

        $order_arr = array(
            "prs.courier_id",
            "prs.ec_name",
            "prs.tracking_no",
            "prs.prs_id",
            "prs.report_status",
            "prs.report_status_date",
           
        );

        $result = $this->xmod->find_with_joins(
            "rider_pickup prs", //table
            $user_join, //joins
            $cond, //condi
            "prs.*,rc.courier_name,rc.mobile", //fields
            "", //row
            $order_arr[$order_column]. " " .$order_type, //order_by
            "", //group 
            $limit, //limit
            $offset //offset
        );
   
        if ($result){
            $prsData =  $this->xmod->get_records();
            foreach($prsData as $rdata){
                $rowData = array();
                $rowData[] = array("courier_id" => $rdata->courier_id, "courier_name" => $rdata->courier_name,"mobile" => $rdata->mobile);
                $rowData[] =  $rdata->ec_name;
                $rowData[] = array("tracking_no" => $rdata->tracking_no, "item_id" => $rdata->item_id);
                $rowData[] =  $rdata->prs_id;
                $rowData[] = array("cust_name" => $rdata->cust_name, "cust_mobile" => $rdata->cust_mobile,"address" =>$rdata->cust_address);
                $rowData[] = number_format($rdata->cod_amount,2);
                $rowData[] = (($rdata->report_signature) ? "<img src='".$rdata->report_signature."' class='img img-fluid img-thumbnail'/>" : "");
                $rowData[] = ($rdata->report_image) ? "<img src='".$rdata->report_image."' class='img img-fluid img-thumbnail'/>" : "";
                $rowData[] = $rdata->report_status;
                $rowData[] = $rdata->report_lat;
                $rowData[] = $rdata->report_lng;
                $rowData[] = date('F d, Y H:i A',strtotime($rdata->report_status_date));
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

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */