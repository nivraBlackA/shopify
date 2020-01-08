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
        $this->section['ci_css'][] = "vendor/selectpicker4/css/selectpicker4.min.css";
        $this->section['ci_js'][] = "vendor/selectpicker4/js/selectpicker4.min.js";

        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";

        $today = date('Y-m-d');
        $cond = "DATE_FORMAT(entry_date,'%Y-%m-%d') = '$today'";
        //PRS
        $this->data['prs_ongoing']   =   $this->xmod->get_count("rider_pickup","report_status_code >= 0 AND report_status_code <= 12 AND $cond");
        $this->data['prs_failed']   =   $this->xmod->get_count("rider_pickup","report_status_code >= 14 AND report_status_code < 30 AND $cond");
        $this->data['prs_success']   =   $this->xmod->get_count("rider_pickup","report_status_code >= 30 AND $cond");
        $this->data['prs_all']   =   $this->xmod->get_count("rider_pickup",$cond);
        //DRS
        $this->data['drs_ongoing']   =   $this->xmod->get_count("rider_delivery","report_status_code < 55 AND $cond");
        $this->data['drs_failed']   =   $this->xmod->get_count("rider_delivery","report_status_code >= 55 AND report_status_code < 69 AND $cond");
        $this->data['drs_success']   =   $this->xmod->get_count("rider_delivery","report_status_code = 82 AND $cond");
        $this->data['drs_all']   =   $this->xmod->get_count("rider_delivery",$cond);
        // RRS
        $this->data['rrs_ongoing']   =   $this->xmod->get_count("rider_return","$cond AND (report_status_code = 0 OR report_status_code = '')");
        $this->data['rrs_failed']   =   $this->xmod->get_count("rider_return","report_status_code = 75 AND $cond");
        $this->data['rrs_success']   =   $this->xmod->get_count("rider_return","report_status_code = 83 AND $cond");
        $this->data['rrs_all']   =   $this->xmod->get_count("rider_return",$cond);

        $this->data['courier']   =   $this->xmod->get_count("rider_courier");
        $this->data['users']   =   $this->xmod->get_count("users","","COUNT(user_id)");

        $entry_date = ($this->input->get('date')) ? $this->input->get('date') : $today;
        $ec_id = ($this->input->get('ec_id')) ? $this->input->get('ec_id') : 1;

        // $this->data['select_opt']  = $this->ops->get_options("couriers","ec_id","ec_name","ec_id != 0",$ec_id,"ec_name","ec_id");

        $this->data['date'] = $entry_date;
        $this->data['ec_id'] = $ec_id;

        $courier_info = $this->xmod->get("rider_courier","ec_id = $ec_id","courier_id,courier_name");

        $courier_table = array();
        if($courier_info)
            $courier_table = $this->get_courierTable($courier_info,$ec_id,$entry_date);
        $this->data['courier_info'] = ($courier_info) ? $courier_info : array();
        $this->data['courier_table'] = ($courier_table) ? $courier_table : array();
        $this->section['content'] = $this->load->view('home',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function get_courierTable($courier_info,$ec_id="",$entry_date="")
    {
        $prs_data = $this->get_count_prs($courier_info,$ec_id,$entry_date);
        $drs_data = $this->get_count_drs($courier_info,$ec_id,$entry_date);
        $rrs_data = $this->get_count_rrs($courier_info,$ec_id,$entry_date);

        $prs_drs = array();
        foreach($prs_data as $courier_id => $prs)
        {
            foreach($drs_data as $drs_id => $drs)
            {
                if($courier_id == $drs_id)
                {
                    $prs_drs[$courier_id] = array_merge($prs,$drs);
                }
            }
        }

        $prs_drs_rrs = array();
        foreach($prs_drs as $courier_id => $xarray)
        {
            foreach($rrs_data as $rrs_id => $rrs)
            {
                if($courier_id == $rrs_id)
                {
                    $prs_drs_rrs[$courier_id] = array_merge($xarray,$rrs);
                }
            }
        }
      
        return $prs_drs_rrs;
    }

    function get_count_prs($courier_info = "",$ec_id="",$entry_date="")
    {
        $cond_ongoing = "ec_id = $ec_id AND report_status_code >= 0 AND report_status_code <= 12 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $ongoing = $this->xmod->get("rider_pickup",$cond_ongoing,"COUNT(prs_id) as count,courier_id","courier_id");

        $cond_failed = "ec_id = $ec_id AND report_status_code >= 14 AND report_status_code < 30 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $failed = $this->xmod->get("rider_pickup",$cond_failed,"COUNT(prs_id) as count,courier_id","courier_id");

        $cond_success = "ec_id = $ec_id AND report_status_code >= 30 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $success = $this->xmod->get("rider_pickup",$cond_success,"COUNT(prs_id) as count,courier_id","courier_id");

        $xongoing =  ($ongoing) ? $this->count_xdata($ongoing) : array();
        $xfailed =  ($failed) ? $this->count_xdata($failed) : array();
        $xsuccess =  ($success) ? $this->count_xdata($success) : array();
       
        $prs_data = array();
        if(isset($courier_info))
        {
            foreach($courier_info as $courier)
            {
                $ongoing_count = 0;
                $failed_count = 0;
                $success_count = 0;

                if(isset($xongoing[$courier->courier_id]))
                    $ongoing_count  = $xongoing[$courier->courier_id];
                if(isset($xfailed[$courier->courier_id]))
                    $failed_count  = $xfailed[$courier->courier_id];
                if(isset($xsuccess[$courier->courier_id]))
                    $success_count  = $xsuccess[$courier->courier_id];

                $xcount = new stdClass();
                $xcount->ongoing = $ongoing_count;
                $xcount->failed = $failed_count;
                $xcount->success =  $success_count;

                $prs_data[$courier->courier_id]['prs'] = $xcount;
            }
        }
        return $prs_data;
    }

    function get_count_drs($courier_info = "",$ec_id="",$entry_date="")
    {
        $cond_ongoing = "ec_id = $ec_id AND report_status_code < 55 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $ongoing = $this->xmod->get("rider_delivery",$cond_ongoing,"COUNT(drs_id) as count,courier_id","courier_id");

        $cond_failed = "ec_id = $ec_id AND report_status_code >= 55 AND report_status_code < 69 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $failed = $this->xmod->get("rider_delivery",$cond_failed,"COUNT(drs_id) as count,courier_id","courier_id");

        $cond_success = "ec_id = $ec_id AND report_status_code = 82 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $success = $this->xmod->get("rider_delivery",$cond_success,"COUNT(drs_id) as count,courier_id","courier_id");

        $xongoing =  ($ongoing) ? $this->count_xdata($ongoing) : array();
        $xfailed =  ($failed) ? $this->count_xdata($failed) : array();
        $xsuccess =  ($success) ? $this->count_xdata($success) : array();
       
        $drs_data = array();
        if(isset($courier_info))
        {
            foreach($courier_info as $courier)
            {
                $ongoing_count = 0;
                $failed_count = 0;
                $success_count = 0;

                if(isset($xongoing[$courier->courier_id]))
                    $ongoing_count  = $xongoing[$courier->courier_id];
                if(isset($xfailed[$courier->courier_id]))
                    $failed_count  = $xfailed[$courier->courier_id];
                if(isset($xsuccess[$courier->courier_id]))
                    $success_count  = $xsuccess[$courier->courier_id];

                $xcount = new stdClass();
                $xcount->ongoing = $ongoing_count;
                $xcount->failed = $failed_count;
                $xcount->success =  $success_count;

                $drs_data[$courier->courier_id]['drs'] = $xcount;
            }
        }
        return $drs_data;
    }
    
    function get_count_rrs($courier_info = "",$ec_id="",$entry_date="")
    {
        $cond_ongoing = "ec_id = $ec_id AND (report_status_code = 0 OR report_status_code = '') AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $ongoing = $this->xmod->get("rider_return",$cond_ongoing,"COUNT(rrs_id) as count,courier_id","courier_id");

        $cond_failed = "ec_id = $ec_id AND report_status_code = 75 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $failed = $this->xmod->get("rider_return",$cond_failed,"COUNT(rrs_id) as count,courier_id","courier_id");

        $cond_success = "ec_id = $ec_id AND report_status_code = 83 AND DATE_FORMAT(entry_date, '%Y-%m-%d') = '$entry_date'";
        $success = $this->xmod->get("rider_return",$cond_success,"COUNT(rrs_id) as count,courier_id","courier_id");

        $xongoing =  ($ongoing) ? $this->count_xdata($ongoing) : array();
        $xfailed =  ($failed) ? $this->count_xdata($failed) : array();
        $xsuccess =  ($success) ? $this->count_xdata($success) : array();
       
        $rrs_data = array();
        if(isset($courier_info))
        {
            foreach($courier_info as $courier)
            {
                $ongoing_count = 0;
                $failed_count = 0;
                $success_count = 0;

                if(isset($xongoing[$courier->courier_id]))
                    $ongoing_count  = $xongoing[$courier->courier_id];
                if(isset($xfailed[$courier->courier_id]))
                    $failed_count  = $xfailed[$courier->courier_id];
                if(isset($xsuccess[$courier->courier_id]))
                    $success_count  = $xsuccess[$courier->courier_id];

                $xcount = new stdClass();
                $xcount->ongoing = $ongoing_count;
                $xcount->failed = $failed_count;
                $xcount->success =  $success_count;

                $rrs_data[$courier->courier_id]['rrs'] = $xcount;
            }
        }
        return $rrs_data;
    }

    function count_xdata($xdata)
    {
        $xxdata = array();
        foreach ($xdata as $rdata) {
            $xxdata[$rdata->courier_id] = $rdata->count;
        }
        return $xxdata;

    }

}
