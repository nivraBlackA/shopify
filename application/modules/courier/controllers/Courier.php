<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Courier extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('courier_model','xmod');
        // $this->load->model('Ops_model', 'ops');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function index(){
        $this->section['ci_css'][] = "vendor/selectpicker4/css/selectpicker4.min.css";
        $this->section['ci_js'][] = "vendor/selectpicker4/js/selectpicker4.min.js";

        $this->section['ci_css'][] = "vendor/leaflet/leaflet.css";
        $this->section['ci_js'][] = "vendor/leaflet/leaflet.js";

        // $this->data['ec_opt']  = $this->ops->get_options("couriers","ec_id","ec_name","ec_id != 0",1,"ec_name","ec_id");
        $this->section['content'] = $this->load->view('courier/courier',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function get_courier($ec_id)
    {
        $ajax = $this->input->get('ajax');
        $id = $this->input->get('id');
        $cond = "ec_id = $ec_id AND status = 'active'";

        $fields = "id,ec_id,ec_name,name,full_name,contact_no,status,date_start,company";
        $courierData = $this->ops->get("couriers",$cond,$fields);

        if($ajax){

            echo json_encode($courierData);
            die();
        }
    }

    function get_couriername()
    {
        $id = $this->input->get('id');
        $ec_id = $this->input->get('ec_id');
        $cond = "ec_id = $ec_id AND status = 'active' AND id = $id";
        $fields = "id,ec_id,ec_name,name,full_name,contact_no,status,date_start,company";
        $courierData = $this->ops->get("couriers",$cond,$fields);

        if($id)
        {
            $pass_text = substr(md5(microtime()),rand(0,26),6);
            $obj = new stdClass();
            $obj = $courierData[0];
            $obj->pass_text = $pass_text;
            echo json_encode($obj);
            die();
        }

    }

    function postdata()
    {
        $fullname       =   $this->input->post('fullname');
        $contact        =   $this->input->post('contact');
        $courier_id     =   $this->input->post('courier_id');
        $company        =   $this->input->post('company');
        $pass_text      =   $this->input->post('pass_text');
        $ec_id          =   $this->input->post('ec_id');
        $ec_name        =   $this->input->post('ec_name');


        $courierData = new stdClass();
        $courierData->password_text = $pass_text;
        $courierData->mobile   = $contact;
        $courierData->courier_id = $courier_id;
        $courierData->courier_name  = $fullname;
        $courierData->company   = $company;
        $courierData->ec_id     = $ec_id;
        $courierData->ec_name   = $ec_name;
        $courierData->flg_active   = 1;
        $courierData->flg_init_login   = 1;
        $courierData->date_registered   = date('Y-m-d H:i:s');
        $courierData->password   = $this->xmod->hash_password($pass_text);

        $courierinfo = $this->xmod->get("rider_courier","courier_id = $courier_id");
        if(!$courierinfo){
            if($this->xmod->save("rider_courier",$courierData))
            {
                echo json_encode(array("result" => "ok"));
                die();
            }
        }
        else
            echo json_encode(array("result" => "warning"));
        die();

    }

    function get_table()
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

        $cond = "1 = 1";

        $user_join = array("rider_ecs ec" => array("left" => "ec.id = rc.ec_id"));

        $tCount = $this->xmod->find_with_joins(
        "rider_courier rc", //table
        $user_join, //joins
        $cond, //condi
        "COUNT(rc.id) as total_count", //fields
        "",
        TRUE
        );
        if ($tCount)
            $total_record = $this->xmod->get_records()[0]->total_count;
        if ($search){
            $cond = "(
                rc.courier_id LIKE '%$search%' OR
                rc.courier_name LIKE '%$search%' OR 
                rc.mobile LIKE '%$search%' OR 
                rc.company LIKE '%$search%'
            )";
            $sCount = $this->xmod->find_with_joins(
                "rider_courier rc", //table
                $user_join, //joins
                $cond, //condi
                "COUNT(rc.id) as total_count", //fields
                "",
                TRUE
            );
            if ($sCount)
                $fil_total = $this->xmod->get_records()[0]->total_count;
        }

        $order_arr = array(
            "rc.courier_id",
            "rc.courier_name",
            "rc.ec_name",
            "rc.device_version",
            "rc.date_registered",
            "rc.last_login",
        );

        $result = $this->xmod->find_with_joins(
            "rider_courier rc", //table
            $user_join, //joins
            $cond, //condi
            "rc.*,ec.name,ec.desc,ec.coordinator,ec.email,ec.cellno,ec.address", //fields
            "", //row
            $order_arr[$order_column]. " " .$order_type, //order_by
            "", //group 
            $limit, //limit
            $offset //offset
        );
        // echo $this->xmod->CI->db->last_query();die();
        if ($result){
            $courierData =  $this->xmod->get_records();
            foreach($courierData as $rdata){
                $imageData = checkRemoteFile($rdata->user_image) ? $rdata->user_image : "http://wms.blackarrow.express/sandbox/uploads/avatars/pig.png";

                $rowData = array();
                $rowData[] = $rdata->courier_id;
                $rowData[] = array("name" => $rdata->courier_name, "mobile" => $rdata->mobile,"courier_id" => $rdata->courier_id,"company" => $rdata->company,"img_url" => $imageData);
                $rowData[] = $rdata->name;
                $rowData[] = (($rdata->flg_active == 1) ? "<span class='badge badge-success text-white'>Active</span>" : "");
                $rowData[] = $rdata->device_version;
                $rowData[] = ($rdata->date_registered) ? date('F d, Y H:i A',strtotime($rdata->date_registered)) : "";
                $rowData[] = ($rdata->last_login) ? date('F d, Y H:i A',strtotime($rdata->last_login)) : "";
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

    function id($courier_id)
    {
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";

        $post = $this->input->post('ajax');
        $type = ($this->input->get('type') ? $this->input->get('type') : 'prs');
        if($post)
        {
            //update for approval
            $postData = new stdClass();
            $postData->flg_active = 1;
            $postData->flg_approved = 1;
            $postData->approved_by = $this->user_data->first_name . " " . $this->user_data->last_name;;
            $postData->approved_date = date('Y-m-d H:i:s');

            if($this->xmod->save("rider_courier",$postData,array("courier_id" => $courier_id)))
            {
                echo json_encode(array("result" => "ok"));
                die();
            }
            else{
                echo "error";
                die();
            }
           
        }
        $user_join = array(
            "rider_ecs ec" => array("left" => "ec.id = rc.ec_id"),
        );

        $result = $this->xmod->find_with_joins(
            "rider_courier rc", //table
            $user_join, //joins
            "rc.courier_id = $courier_id", //condi
            "rc.*,ec.*,ec.name as ec_name", //fields
            TRUE //row
        );
       
        $courier = array();
        if ($result)
            $courier = $this->xmod->get_records();

        else
            $error = 1;

        // get locations

        $today = ($this->input->get('date')) ? $this->input->get('date') : date("Y-m-d");
        $accuracy = ($this->input->get('accuracy')) ? $this->input->get('accuracy') : "";
        $ajaxx = $this->input->get('ajaxx');

        $cond = "lat > 0 AND lng > 0 AND courier_id = $courier_id AND DATE_FORMAT(location_date,'%Y-%m-%d') = '$today'";
        if($accuracy){
            if($accuracy == 'greater_than_100')
                $cond .= " AND accuracy > 100";
            elseif($accuracy == 'less_than_100')
                $cond .= " AND accuracy < 100";
            elseif($accuracy == 'less_than_50')
                $cond .= " AND accuracy < 50";
        }

        $q = $this->xmod->CI->db->query("SELECT lat,lng, action, item_id, type from rider_locations WHERE $cond ORDER BY location_date ASC");
       
        $locText = "";
        $xArr = array();
        $markerArr = array();
        if ($q->num_rows() > 0){
            $locArr = $q->result_array();
            foreach ($locArr as $locx){
                $xArr[] = array($locx['lat'], $locx['lng']);

                if ($locx['action'] == 'update')
                    $markerArr[] = $locx;
            }

            $locText = json_encode($xArr);
        }

        if($ajaxx)
        {
            $markerData = new stdClass();
            $markerData->locationArr = $locText;
            $markerData->markerArr = $markerArr;
            echo json_encode(array("result" => "ok","data" => $markerData));
            die();
        }
        // echo $locText;
        // echo "<pre>";
        // print_r($markerArr);
        // echo "</pre>";
        // die();
        $select_opt = array(
            "greater_than_100" => "Greater than 100",
            "less_than_100" => "Less than 100",
            "less_than_50" => "Less than 50"
        );
        
        $this->data['locationArr'] =  $locText;
        $this->data['markerArr'] =  $markerArr;
        $this->data['courier_data'] =  $courier;
        $this->data['date'] =   $today;
        $this->data['accuracy'] =   $accuracy;
        $this->data['select_opt'] =   $select_opt;
        $this->data['type'] = $type;
        $this->section['content'] = $this->load->view('profile',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function download_list()
    {
        $type = $this->input->get('status');
        $headers = array(
            "Courier ID",
            "Name",
            "Mobile",
            "Company",
            "Status",
            "Date Registered",
            "Last Login"
        );
        $cond = "1 = 1 ";
        if($type == 'pending')
            $cond .= " AND rc.flg_active = 0 AND rc.flg_approved = 'O'";
        elseif($type == 'approved')
            $cond .= " AND rc.flg_active = 1 AND rc.flg_approved = 'R'";

        $xquery = "SELECT rc.*,ec.name FROM rider_courier rc LEFT JOIN rider_ecs ec ON ec.id = rc.ec_id WHERE $cond";
        $result = $this->xmod->CI->db->query($xquery);
        $xdata = $result->result();
        $content = array();
        foreach($xdata as $rdata){
            $content[] = array(
                $rdata->courier_id,
                ucwords($rdata->courier_name),
                $rdata->mobile,
                $rdata->company,
                (($rdata->flg_approved == 1) ? "approved" : "pending"),
                date('M d, Y H:i:s',strtotime($rdata->date_registered)),
                date('M d, Y H:i:s',strtotime($rdata->last_login))
            );
        }

        $content[] = array("","","","","","","");
        $filename = "BAExpress_courier_list_".date('mdyHis');
        $this->download_excel($headers, $content,$filename,true);
        die();
    }

    function get_courier_runsheet()
    {
        session_write_close();
        $courier_id         = $this->input->get('courier_id');
        $type         = $this->input->get('type');
        $status         = $this->input->get('status');
        $date         = $this->input->get('date');

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

        $cond = "courier_id = $courier_id AND DATE_FORMAT(entry_date,'%Y-%m-%d') = '$date'";
        $table_name = "";
        $rs_id = "";
        if($type == 'prs')
        {
            $rs_id = "prs_id";
            $table_name = "rider_pickup";

            if($status == 'ongoing')
                $cond .= " AND report_status_code >= 0 AND report_status_code <= 12"; 
            if($status == 'failed')
                $cond .= " AND report_status_code >= 14 AND report_status_code < 30"; 
            if($status == 'success')
                $cond .= " AND report_status_code >= 30"; 
        }
        elseif($type == 'drs')
        {
            $rs_id = "drs_id";
            
            $table_name = "rider_delivery";

            if($status == 'ongoing')
                $cond .= " AND report_status_code < 55"; 
            if($status == 'failed')
                $cond .= " AND report_status_code >= 55 AND report_status_code < 69"; 
            if($status == 'success')
                $cond .= " AND report_status_code = 82"; 
        }
        elseif($type == 'rrs')
        {
            $rs_id = "rrs_id";
            
            $table_name = "rider_return";
            
            if($status == 'ongoing')
                $cond .= " AND (report_status_code = 0 OR report_status_code = '')"; 
            if($status == 'failed')
                $cond .= " AND report_status_code = 75"; 
            if($status == 'success')
                $cond .= " AND report_status_code = 83"; 
        }

        $total_record = $this->xmod->get_count($table_name,$cond);

        if ($search){
            $cond .= " AND (
                tracking_no LIKE '%$search%' OR
                cust_name LIKE '%$search%' OR 
                cod_amount LIKE '%$search%'
            )";
                $fil_total = $this->xmod->get_count($table_name,$cond);
        }

        $order_arr = array(
            $rs_id,
            "tracking_no",
            "cust_name"
        );

        $result = $this->xmod->find_with_joins(
            $table_name, //table
            "", //joins
            $cond, //condi
            "*", //fields
            "", //row
            $order_arr[$order_column]. " " .$order_type, //order_by
            "", //group 
            $limit, //limit
            $offset //offset
        );
        // echo $this->xmod->CI->db->last_query();die();
        if ($result){
            $courierData =  $this->xmod->get_records();
            foreach($courierData as $rdata){
                $rowData = array();
                $rowData[] = (($rs_id == 'prs_id') ? $rdata->prs_id : (($rs_id == 'drs_id') ? $rdata->drs_id : $rdata->rrs_id));
                $rowData[] = array("tracking_no" => $rdata->tracking_no, "item_id" => $rdata->item_id);
                $rowData[] = $rdata->cust_name ."<br/>". $rdata->cust_mobile;
                $rowData[] = number_format($rdata->cod_amount,2);
                $rowData[] = $rdata->report_status;
                $rowData[] = (($rdata->report_status_date) ? date('M d, Y H:i:s',strtotime($rdata->report_status_date)) : "");
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
