<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Reports extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        //$this->sybase = $this->load->driver('sybase_driver');
        $this->data['ci_head'] = '';
        $this->data['js_dir'] = $this->ci_js;
        $this->data['css_dir'] = $this->ci_css;
        $this->data['added_js'] = "";
        $this->load->model('reports_model','xmod');        
    }

    function index(){
        

        $this->section['content'] = $this->load->view('search',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function payouts($type = "ongoing",$disp = ""){

        
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $date_from = $this->input->get("date_from");
        $date_to = $this->input->get("date_to");
        
        $date_from = ($date_from) ? $date_from : date("Y-m-d",strtotime("last week monday"));
        $date_to = ($date_to) ? $date_to : date("Y-m-d",strtotime("last week sunday"));
        $listData = $this->payout_type($type,$disp,$date_from,$date_to);
       

        $to = date("Y-m-d",strtotime("this week sunday"));
        $xquery = "SELECT COUNT(ac.cod_amount) as total FROM app_baewallet_transaction ab 
            LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
            LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
            LEFT JOIN app_users au ON au.user_id = ab.user_id 
            WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%') <= '$to' AND acc.id IS NULL";  
        $zquery = "SELECT SUM(ac.cod_amount) as total FROM app_baewallet_transaction ab 
            LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
            LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
            LEFT JOIN app_users au ON au.user_id = ab.user_id 
            WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%') <= '$to' AND acc.id IS NULL";  
        $ongoing_count = $this->xmod->CI->db->query($xquery)->row()->total;
        $ongoing_sum = $this->xmod->CI->db->query($zquery)->row()->total;
       
        $this->data['payout_ongoing_count']     = $ongoing_count;
        $this->data['payout_pending_count']     = $this->xmod->get_count("app_cashout","status != 'S' AND status != 'V'");
        $this->data['payout_success_count']     = $this->xmod->get_count("app_cashout","status = 'S'");
        $this->data['payout_all_count']         = $this->xmod->get_count("app_cashout");

        $this->data['payout_ongoing_sum']   = $ongoing_sum;
        $this->data['payout_pending_sum']   = $this->xmod->get("app_cashout","status != 'S' AND status != 'V'","SUM(total_amount) as total")[0]->total;
        $this->data['payout_success_sum']   = $this->xmod->get("app_cashout","status = 'S'","SUM(total_amount) as total")[0]->total;
        $this->data['payout_all_sum']       = $this->xmod->get("app_cashout","","SUM(total_amount) as total")[0]->total;
        $this->data['list'] = $listData;
        $this->data['type'] = $type;
        $this->data['disp'] = $disp;
        $this->data['date_from'] = $date_from;
        $this->data['date_to'] = $date_to;
        $this->section['content'] = $this->load->view('payouts/landing',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function payout_type($type,$disp,$date_from = "", $date_to = ""){

        $cond = "";
        if ($type == 'ongoing'){
            $download = $this->input->get("download");
            $from = date("Y-m-d",strtotime("this week monday"));
            $to = date("Y-m-d",strtotime("this week sunday"));
            if ($disp == 'by_user'){
                $xquery = "SELECT COUNT(ab.trans_no) as trans_no, SUM(ac.cod_amount) as cod_amount, SUM(ab.amount) as amount, ac.cod_percent, SUM(ac.cod_rate) as cod_rate, ac.delivered_date, au.display_name 
                FROM app_baewallet_transaction ab 
                LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
                LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
                LEFT JOIN app_users au ON au.user_id = ab.user_id 
                WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%d') <= '$to' AND acc.id IS NULL GROUP BY ab.user_id ORDER BY SUM(ac.cod_amount) ASC";  
            }
            else{
                $xquery = "SELECT ab.*, ac.cod_amount, ac.cod_percent, ac.cod_rate, ac.delivered_date, au.display_name FROM app_baewallet_transaction ab 
                LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
                LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
                LEFT JOIN app_users au ON au.user_id = ab.user_id 
                WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%d') <= '$to' AND acc.id IS NULL ORDER BY delivered_date ASC";  
            }
                
            
            if ($download){
                $headers = array(
                    "Tracking No",
                    "COD Amount",
                    "COD %",
                    "COD Fee",
                    "Net Amount",
                    "Delivered Date"
                );
                $content = array();
                $mainData = $result->result_array();
                foreach ($mainData as $row) {
                    $content[] = array(
                        $row['trans_no'],
                        $row['cod_amount'],
                        $row['cod_percent'],
                        $row['cod_rate'],
                        $row['amount'],
                        $row['delivered_date']
                    );
                }

                $content[] = array(
                    "TOTAL",
                    array_sum(array_column($mainData,'cod_amount')),
                    "",
                    array_sum(array_column($mainData,'cod_rate')),
                    array_sum(array_column($mainData,'amount')),
                );

                // print_r($content);
                // die();
                $filename = "BAExpress_cod_weekly_report_".$from."_".$to;
                $this->download_excel($headers, $content,$filename,true);
            }
            else{
                $result = $this->xmod->CI->db->query($xquery);
                return $result->result_array();
            }
        }
        else if ($type == 'pending'){
            $cond .= " AND status != 'S' AND status != 'V'";
        }
        else if ($type == 'success'){
            $cond .= " AND status = 'S'";
        }
        else if ($type == 'report' AND !empty($disp)){
            
            if ($disp == 'per_order'){
                $query = "SELECT au.display_name, tr.trans_no, c.cod_amount, c.cod_percent, c.cod_rate, tr.amount, c.delivered_date FROM `app_baewallet_transaction` tr LEFT JOIN app_carts c ON c.id = tr.ref_id LEFT JOIN app_users au ON au.user_id = tr.user_id WHERE trans_type = 'cod' AND tr.trans_date BETWEEN '$date_from' AND '$date_to'";
                
            }
            else if ($disp == 'per_user'){
                $query = "SELECT au.display_name, COUNT(tr.id) as order_count, SUM(c.cod_amount) as cod_amount, c.cod_percent, SUM(c.cod_rate) as cod_rate, SUM(tr.amount)  as net_amount FROM `app_baewallet_transaction` tr LEFT JOIN app_carts c ON c.id = tr.ref_id LEFT JOIN app_users au ON au.user_id = tr.user_id WHERE trans_type = 'cod' AND tr.trans_date BETWEEN '$date_from' AND '$date_to' GROUP BY tr.user_id, c.cod_percent";
            }
            else if ($disp == 'per_cashout'){
                $query = "SELECT au.display_name, ac.transaction_no, COUNT(acc.id) as order_count, SUM(c.cod_amount) as cod_amount, c.cod_percent, SUM(c.cod_rate) as cod_rate, ac.total_amount  as net_amount, ac.merchantFee, ac.status FROM `app_cashout` ac LEFT JOIN app_cashout_cart acc ON ac.id = acc.cashout_id LEFT JOIN app_carts c ON c.id = acc.cart_id LEFT JOIN app_users au ON au.user_id = ac.user_id WHERE ac.cutoff_date BETWEEN '$date_from' AND '$date_to' GROUP BY ac.id";
            }

                $q = $this->xmod->CI->db->query($query);            
                $listData = array();
                if ($q->num_rows() > 0){
                    $listData = $q->result_array();
                    return $listData;
                }
                return array();
        }

        if ($type == 'all'){
            $cond .= " AND ac.date_created BETWEEN '$date_from' AND '$date_to'";
        }
        $subquery = "SELECT acc.cashout_id, SUM(cod_amount) as xcod_amount, SUM(cod_rate) as xcod_fee, (SUM(cod_amount) - SUM(cod_rate)) as xnet_cod FROM app_cashout_cart acc LEFT JOIN app_carts act ON acc.cart_id = act.id GROUP BY cashout_id";
        $query = "SELECT acx.xcod_amount, acx.xcod_fee, acx.xnet_cod, au.display_name, ac.*, copt.bank_name, copt.account_no FROM app_cashout ac LEFT JOIN app_users au USING(user_id) LEFT JOIN app_users_cashout_options copt USING(user_id) LEFT JOIN ($subquery) acx ON acx.cashout_id = ac.id WHERE copt.flg_primary = 1 $cond";
        $q = $this->xmod->CI->db->query($query);
        $listData = array();
        if ($q->num_rows() > 0){
            $listData = $q->result_array();
            return $listData;
        }
        return array();
    }

    function cod_weekly_report($type = ""){

        $download = $this->input->get("download");
        $from = date("Y-m-d",strtotime("last week monday"));
        $to = date("Y-m-d",strtotime("last week sunday"));
        if ($type == 'by_user'){
            $xquery = "SELECT ab.trans_no, SUM(ac.cod_amount) as cod_amount, SUM(ab.amount) as amount, ac.cod_percent, SUM(ac.cod_rate) as cod_rate, ac.delivered_date, au.display_name FROM app_baewallet_transaction ab 
            LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
            LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
            LEFT JOIN app_users au ON au.user_id = ab.user_id 
            WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%d') <= '$to' AND acc.id IS NULL GROUP BY ab.user_id ORDER BY SUM(ac.cod_amount) ASC";  
        }
        else{
            $xquery = "SELECT ab.*, ac.cod_amount, ac.cod_percent, ac.cod_rate, ac.delivered_date, au.display_name FROM app_baewallet_transaction ab 
            LEFT JOIN app_cashout_cart acc ON acc.cart_id = ref_id 
            LEFT JOIN app_carts ac ON ac.id = ab.ref_id 
            LEFT JOIN app_users au ON au.user_id = ab.user_id 
            WHERE trans_type = 'cod' AND DATE_FORMAT(trans_date, '%Y-%m-%d') <= '$to' AND acc.id IS NULL GROUP BY ac.id ORDER BY delivered_date ASC";  
            
        }
              
        
        $result = $this->xmod->CI->db->query($xquery);
        if ($download){
            $headers = array(
                "Name",
                "Tracking No",
                "COD Amount",
                "COD %",
                "COD Fee",
                "Net Amount",
                "Delivered Date"
            );
            $content = array();
            $mainData = $result->result_array();
            foreach ($mainData as $row) {
                $content[] = array(
                    $row['display_name'],
                    $row['trans_no'],
                    $row['cod_amount'],
                    $row['cod_percent'],
                    $row['cod_rate'],
                    $row['amount'],
                    $row['delivered_date']
                );
            }

            $content[] = array(
                "",
                "TOTAL",
                array_sum(array_column($mainData,'cod_amount')),
                "",
                array_sum(array_column($mainData,'cod_rate')),
                array_sum(array_column($mainData,'amount')),
            );

            $filename = "BAExpress_cod_weekly_report_".$from."_".$to;
            $this->download_excel($headers, $content,$filename,true);
        }
        $this->data['payout_list'] = $result->result_array();
        $this->section['content'] = $this->load->view('cod_weekly_report',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function cod_pending_payout_report(){
        $query = "SELECT au.display_name, acc.transaction_no, acc.total_amount, acc.merchantFee, acc.status, acc.cashout_status, acc.status_date, copt.cashout_type, copt.account_no, copt.bank_name FROM app_cashout acc 
        LEFT JOIN app_users au ON au.user_id = acc.user_id 
        LEFT JOIN app_users_cashout_options copt ON acc.cashout_options_id = copt.id 
        WHERE acc.status != 'S'";
        $q = $this->xmod->CI->db->query($query);

        $this->data['item_list'] = $q->result_array();
        $this->section['content'] = $this->load->view('cod_pending_report',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function users_balance(){

        $q = "SELECT amount AS baewallet_balance,type FROM app_baewallet_transaction WHERE user_id = '100354005679237446873'";

        $query = $this->db->query($q);
        $total_bal = 0;
        $deducted_bal = 0;
        if($query->num_rows() > 0){
            $results = $query->result();
            foreach ($results as $lineData){
                if($lineData->type == 'credit'){
                    $total_bal += $lineData->baewallet_balance;
                }else if($lineData->type == 'debit'){
                    $deducted_bal += $lineData->baewallet_balance;
                }
            }
           echo $baewallet_bal = ($total_bal - $deducted_bal);
           
     }

        $subq = "SELECT COUNT(t.ref_id) FROM app_baewallet_transaction t LEFT JOIN app_cashout_cart cc ON t.ref_id = cc.cart_id WHERE t.type = 'credit' AND trans_type = 'cod' AND cc.id IS NULL AND t.user_id = bal.user_id";
        $query = "SELECT bal.current_balance as balance, au.display_name, au.user_id, au.email_address, ($subq) as no_orders FROM `v_baewallet_users_balance` bal LEFT JOIN app_users au ON bal.user_id = au.user_id WHERE bal.current_balance != 0 ORDER BY bal.current_balance DESC";
        $balance_list = $this->xmod->CI->db->query($query);
        $this->data['balance_list'] = $balance_list->result_array();
        $this->section['content'] = $this->load->view('cod_users_balance',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function sales($type="detailed",$cat=""){
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $from = $this->input->get("from");
        $to = $this->input->get("to");
        $from = ($from) ? $from : date("Y-m-d",strtotime("-1 month"));
        $to = ($to) ? $to : date("Y-m-d");
        $view_page = "sales/landing";

        $cond = "flg_paid = 1";
        switch ($type){
            case 'detailed' :
                $this->section['ci_js'][] = "js/pages/sales.js";
                break;
            case 'payment' :
                if ($cat)
                $cond .= " AND ac.payment_type = '$cat'";
                $query = "SELECT au.display_name, au.user_id, ac.id, ac.tracking_no, ac.payment_type, ac.payment_datetime, ac.date_created FROM app_carts ac LEFT JOIN app_users au ON ac.user_id = au.user_id WHERE $cond ORDER BY ac.date_created DESC";
                echo $query;
                die();
                break;
            case 'platform' :
                break;
            case 'social' :
                break;
        }

        $platform = $this->input->get('registration_platform');
        $registration = $this->input->get('registration');
        $payment_type = $this->input->get('payment_type');
        
        $this->data['payment_type'] = $payment_type;
        $this->data['registration'] = $registration;
        $this->data['platform'] = $platform;
        
        $this->data['from'] = $from;
        $this->data['to'] = $to;
        $this->section['content'] = $this->load->view($view_page,$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    function get_table()
    {
        session_write_close();
        $type = $this->input->post("type");
        $from = $this->input->post("from");
        $to = $this->input->post("to");

        $payment_type = $this->input->post("payment_type");
        $registration = $this->input->post("registration");
        $platform = $this->input->post("platform");

        $today = date("Y-m-d");
        $dtable         = $this->input->post();
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
        $cond = "ac.flg_paid = 1 AND ac.ops_status_code >= 30 AND DATE_FORMAT(ac.payment_datetime,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        if($payment_type)
            $cond .= " AND ac.payment_type = '$payment_type'";
        if($registration)
            $cond .= " AND au.registration_type = '$registration'";
        if($platform)
            $cond .= " AND au.registration_platform = '$platform'";

        $user_join = array("app_users au" => array("left" => "au.user_id = ac.user_id"),
                            "app_promos ap" => array("left" => "ap.id = ac.promo_id"));

        $tCount = $this->xmod->find_with_joins(
            "app_carts ac", //table
            $user_join, //joins
            $cond, //condi
            "COUNT(ac.id) as total_count", //fields
            "",
            TRUE
        );
        if ($tCount)
            $total_record = $this->xmod->get_records()[0]->total_count;
       
        if ($search){
            $cond .= " AND (
                ac.tracking_no LIKE '%$search%' OR
                ac.product_name LIKE '%$search%' OR
                ac.product_name LIKE '%$search%' OR
                ac.shipping_fee LIKE '%$search%' OR
                ac.payment_type LIKE '%$search%' OR
                au.display_name LIKE '%$search%' OR
                au.registration_type LIKE '%$search%' OR
                au.registration_platform LIKE '%$search%'
            )";
            $sCount = $this->xmod->find_with_joins(
                "app_carts ac", //table
                $user_join, //joins
                $cond, //condi
                "COUNT(ac.id) as total_count", //fields
                "",
                TRUE
            );
            if ($sCount)
                $fil_total = $this->xmod->get_records()[0]->total_count;
        }

        $order_arr = array(
            "ac.tracking_no",
            "ac.display_name",
            "ac.cod_amount",
            "ac.payment_datetime",
        );
        $order_arr[18] = "ac.payment_datetime";
        $result = $this->xmod->find_with_joins(
            "app_carts ac", //table
            $user_join, //joins
            $cond, //condi
            "DATE_FORMAT(ac.payment_datetime,'%b %d, %Y %h:%i %p') as payment_datetime,au.user_id, au.display_name,au.registration_type,au.device_platform,au.registration_platform,ac.*,ap.promo_code,ap.discount_rate as promo_discount_rate,ap.discount_type,ap.discount_max", //fields
            "", //row
            $order_arr[$order_column]. " " .$order_type, //order_by
            "", //group
            $limit, //limit
            $offset //offset
        );
        // echo $this->xmod->CI->db->last_query();
        // die();
        if ($result){
            $cartData = array();
            $cartData = $this->xmod->get_records();

            if($type == "summary"){
                $userIds = array_column($cartData,'user_id');
                $unique_userids = array_unique($userIds);
               
                $count = 0;
                foreach ($unique_userids as $userId)
                {
                    $cartInfo = new stdClass();
                    $no_items = 0;
                    $total_amount_due = 0;
                    $total_cod_amount = 0;
                    $cod_percent = 0;
                    $total_cod_fee = 0;
                    $total_cod_net_amount = 0;
                    foreach ($cartData as $k => $rdata) {
                        if($userId == $rdata->user_id)
                        {
                            $discount_type = $rdata->discount_type;
                            $shipping_fee = $rdata->shipping_fee;
                            $valuation_charge = $rdata->valuation_charge;
                            $promo_discount = $rdata->promo_discount_rate;
                    
                            $discount_amount = 0;
                            if ($discount_type == 'php'){
                                $discount_amount = $promo_discount;
                            }
                            else if ($discount_type == 'percent'){
                                $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                            }
                    
                            $amount_due = $shipping_fee + $valuation_charge - $discount_amount;

                            $total_amount_due += $amount_due;
                            $total_cod_amount +=  $rdata->cod_amount;
                            $display_name = $rdata->display_name;
                            $cod_percent = $rdata->cod_percent;
                            if($rdata->cod_amount > 0)
                            {
                                $total_cod_fee += $rdata->cod_rate;
                                $total_cod_net_amount += $rdata->total_cod_with_rate;
                            }
                            $no_items++;
                        }
                    }
                    $rowData = array();
                    $rowData[] = array("user_id" => $userId, "display_name" => $display_name);
                    $rowData[] = $no_items;
                    $rowData[] = number_format($total_amount_due,2);
                    $rowData[] = number_format($total_cod_amount,2);
                    $rowData[] = number_format($total_cod_fee,2);
                    $rowData[] = number_format($total_cod_net_amount,2);
                    $item_data[] = $rowData;
                    $count++;
                }
                $total_record =  $count;
               
            }
            else{
                foreach ($cartData as $k => $rdata) {

                    $discount_type = $rdata->discount_type;
                    $shipping_fee = $rdata->shipping_fee;
                    $valuation_charge = $rdata->valuation_charge;
                    $promo_discount = $rdata->promo_discount_rate;
            
                    $discount_amount = 0;
                    if ($discount_type == 'php'){
                        $discount_amount = $promo_discount;
                    }
                    else if ($discount_type == 'percent'){
                        $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                    }
            
                    $amount_due = $shipping_fee + $valuation_charge - $discount_amount;

                    $rowData = array();
                    $rowData[] = $rdata;
                    $rowData[] = array("id" => $rdata->id, "tracking_no" => $rdata->tracking_no);
                    $rowData[] = array("user_id" => $rdata->user_id, "display_name" => $rdata->display_name);
                    $rowData[] = $rdata->product_name;
                    $rowData[] = number_format($rdata->shipping_fee,2);
                    $rowData[] = $rdata->promo_code;
                    $rowData[] = (($rdata->discount_type == 'percent') ? number_format($rdata->promo_discount_rate,2) ." %" : "&#x20B1; ".number_format($rdata->promo_discount_rate,2));
                    $rowData[] = number_format($discount_amount,2);
                    $rowData[] = number_format($rdata->declared_value,2);
                    $rowData[] = number_format($rdata->valuation_charge,2);
                    $rowData[] = number_format($amount_due,2);
                    $rowData[] = number_format($rdata->cod_amount,2);
                    $rowData[] = floor($rdata->cod_percent) ."%";
                    $rowData[] = (($rdata->cod_amount > 0) ? number_format($rdata->cod_rate,2) : 0);
                    $rowData[] = (($rdata->cod_amount > 0) ? number_format($rdata->total_cod_with_rate,2) : 0);
                    $rowData[] = ($rdata->payment_type == 'paypal') ? "<span class='badge badge-primary'>PAYPAL</span>" : (($rdata->payment_type == 'dragonpay') ? "<span class='badge badge-danger'>DRAGONPAY</span>" : "<span class='badge badge-warning'>FOC</span>");
                    $rowData[] = ($rdata->registration_platform == 'android') ? "<i class='fab fa-2x fa-android text-success'></i>" : (($rdata->registration_platform == 'ios') ? "<i class='fab fa-2x fa-apple text-muted'></i>" : "<i class='fa fa-2x fa-globe text-primary'></i>");
                    $rowData[] = ($rdata->registration_type == 'facebook') ? "<i class='fab fa-2x fa-facebook-square text-primary'></i>" : (($rdata->registration_type == 'googleplus') ? "<i class='fab fa-2x fa-google text-danger'></i>" : "<i class='fa fa-2x fa-keyboard'></i>");
                    $rowData[] = ($rdata->payment_datetime) ? date("F d, Y h:i A", strtotime($rdata->payment_datetime)) : "";
                
                    $item_data[] = $rowData;
                }
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

    function sales_report()
    {
        $download = $this->input->get("download");
        $type = $this->input->get("type");
        $from = $this->input->get("from");
        $to = $this->input->get("to");

        $payment_type = $this->input->get("payment_type");
        $registration = $this->input->get("registration");
        $platform = $this->input->get("platform");

        $cond = "flg_paid = 1 AND ops_status_code >= 30 AND DATE_FORMAT(payment_datetime,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        if($payment_type)
            $cond .= " AND ac.payment_type = '$payment_type'";
        if($registration)
            $cond .= " AND au.registration_type = '$registration'";
        if($platform)
            $cond .= " AND au.registration_platform = '$platform'";
        $xquery = "SELECT DATE_FORMAT(ac.payment_datetime,'%b %d, %Y %h:%i %p') as payment_datetime, au.user_id,au.display_name,au.registration_type,au.device_platform,au.registration_platform,ac.product_name,ac.tracking_no,ac.shipping_fee,ac.declared_value,ac.valuation_charge,ac.discount_rate,ac.cod_amount,ac.cod_percent,ac.cod_rate,ac.total_cod_with_rate,ac.payment_type,ap.promo_code,ap.discount_rate as promo_discount_rate,ap.discount_type,ap.discount_max FROM app_carts ac LEFT JOIN app_users au ON ac.user_id = au.user_id LEFT JOIN app_promos ap ON ap.id = ac.promo_id WHERE $cond";
        $result = $this->xmod->CI->db->query($xquery);

        if ($download){
            $mainData = $result->result_array();
            $userIds = array_column($mainData,'user_id');
            $unique_userids = array_unique($userIds);
           
            $content = array();

            if($type == 'detailed'){
                $headers = array(
                    "Name",
                    "Tracking No",
                    "Packaging",
                    "Shipping Fee",
                    "Promo Code",
                    "Promo Discount",
                    "Discount",
                    "Declared Value",
                    "Valuation Charge",
                    "Amount Due",
                    "COD Amount",
                    "COD %",
                    "COD Fee",
                    "Net Amount",
                    "Payment Type",
                    "Platform",
                    "Social",
                    "Payment Date",
                   
                );
                $total_amount_due = 0;
                $total_discount_amount = 0;
                $total_cod_rate = 0;
                $total_cod_with_rate = 0;
                foreach ($mainData as $row) {
                    $discount_type = $row['discount_type'];
                    $shipping_fee = $row['shipping_fee'];
                    $valuation_charge = $row['valuation_charge'];
                    $promo_discount = $row['promo_discount_rate'];
            
                    $discount_amount = 0;
                    if ($discount_type == 'php'){
                        $discount_amount = $promo_discount;
                    }
                    else if ($discount_type == 'percent'){
                        $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                    }

                    if($row['cod_amount'] > 0)
                    {
                        $total_cod_rate += $row['cod_rate'];
                        $total_cod_with_rate += $row['total_cod_with_rate'];
                    }

                    $total_discount_amount += $discount_amount;
                    $amount_due = $shipping_fee + $valuation_charge - $discount_amount;
                    $total_amount_due += $amount_due;
                    $content[] = array(
                        $row['display_name'],
                        $row['tracking_no'],
                        $row['product_name'],
                        $row['shipping_fee'],
                        $row['promo_code'],
                        (($discount_type == 'percent') ? number_format($promo_discount,2)." %" : "Php ".number_format($promo_discount,2)),
                        $discount_amount,
                        $row['declared_value'],
                        $row['valuation_charge'],
                        $amount_due,
                        $row['cod_amount'],
                        floor($row['cod_percent']),
                        (($row['cod_amount'] > 0) ? $row['cod_rate'] : 0 ),
                        (($row['cod_amount'] > 0) ? $row['total_cod_with_rate'] : 0),
                        $row['payment_type'],
                        $row['registration_platform'],
                        $row['registration_type'],
                        $row['payment_datetime'],

                    );
                }

                $content[] = array(
                    "",
                    "",
                    "TOTAL",
                    array_sum(array_column($mainData,'shipping_fee')),
                    "",
                    "",
                    $total_discount_amount,
                    array_sum(array_column($mainData,'declared_value')),
                    array_sum(array_column($mainData,'valuation_charge')),
                    $total_amount_due,
                    array_sum(array_column($mainData,'cod_amount')),
                    "",
                    $total_cod_rate,
                    $total_cod_with_rate,
                    
                );
            }
            else{
                $headers = array(
                    "Name",
                    "No of Items",
                    "Amount Due",
                    "COD Amount",
                    "COD Fee",
                    "Net Amount",
                );

                foreach ($unique_userids as $user_id) {
                    $no_items = 0;
                    $total_amount_due = 0;
                    $total_cod_amount = 0;
                    $cod_percent = 0;
                    $total_cod_fee = 0;
                    $total_cod_net_amount = 0;
                    foreach ($mainData as $rdata) {
                        if($user_id == $rdata['user_id'])
                        {
                            $discount_type = $rdata['discount_type'];
                            $shipping_fee = $rdata['shipping_fee'];
                            $valuation_charge = $rdata['valuation_charge'];
                            $promo_discount = $rdata['promo_discount_rate'];
                    
                            $discount_amount = 0;
                            if ($discount_type == 'php'){
                                $discount_amount = $promo_discount;
                            }
                            else if ($discount_type == 'percent'){
                                $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                            }
                            $amount_due = $shipping_fee + $valuation_charge - $discount_amount;
                            $total_amount_due += $amount_due;
                            $total_cod_amount +=  $rdata['cod_amount'];
                            $display_name = $rdata['display_name'];
                            $cod_percent = $rdata['cod_percent'];
                            if($rdata['cod_amount'] > 0){
                                $total_cod_fee += $rdata['cod_rate'];
                                $total_cod_net_amount += $rdata['total_cod_with_rate'];
                            }
                            $display_name = $rdata['display_name'];
                            $no_items++;
                        }
                    }

                    $content[] = array(
                        $display_name,
                        $no_items,
                        $total_amount_due,
                        $total_cod_amount,
                        $total_cod_fee,
                        $total_cod_net_amount
                    );
                }
               
                $arraycontent = $content;
                $content[] = array(
                    "TOTAL",
                    array_sum(array_column($content,1)),
                    array_sum(array_column($content,2)),
                    array_sum(array_column($content,3)),
                    array_sum(array_column($content,4)),
                    array_sum(array_column($content,5)),
                );
            }
            $filename = "BAExpress_sales_report_".$from."_".$to;
            $this->download_excel($headers, $content,$filename,true);
            die();
        }
        
    }

    public function refunds($type="pending")
    {
        $this->section['ci_js'][] = "js/pages/refunds.js";
        $this->section['ci_css'][] = "css/toogle-switch.css";
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $this->data['flg']              = "active";
        $this->data['count_success']    = $this->xmod->get_count("app_refund","status_code = 'S'");
        $this->data['count_pending']    = $this->xmod->get_count("app_refund","status_code = 'O'");
        $this->data['count_rejected']    = $this->xmod->get_count("app_refund","status_code = 'R'");
        $this->data['count_all']        = $this->xmod->get_count("app_refund");
        $this->data['type']             = $type;
        $this->section['content']       = $this->load->view("refunds/refunds",$this->data,true);
        $this->myview->view($this->section,$this->template);
    }   

    public function get_refund()
    {
        $type         = $this->input->get('type');
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
        if($type == 'pending')
            $cond = "ac.flg_paid = 1 AND ar.status_code = 'O'";
        elseif($type == 'success')
            $cond = "ac.flg_paid = 1 AND ar.status_code = 'S'";
        elseif($type == 'rejected')
            $cond = "ac.flg_paid = 1 AND ar.status_code = 'R'";

        $user_join = array("app_carts ac" => array("left" => "ac.id = ar.cart_id"),
                           "app_users au" => array("left" => "au.user_id = ar.user_id"),
                           "app_promos ap" => array("left" => "ap.id = ac.promo_id"));

        $tCount = $this->xmod->find_with_joins(
            "app_refund ar", //table
            $user_join, //joins
            $cond, //condi
            "COUNT(ar.id) as total_count", //fields
            "",
            TRUE
        );
        if ($tCount)
            $total_record = $this->xmod->get_records()[0]->total_count;
       
        if ($search){
            $cond .= " AND (
                ac.tracking_no LIKE '%$search%' OR
                au.display_name LIKE '%$search%' OR 
                au.email_address LIKE '%$search%'
            )";
            $sCount = $this->xmod->find_with_joins(
                "app_refund ar", //table
                $user_join, //joins
                $cond, //condi
                "COUNT(ar.id) as total_count", //fields
                "",
                TRUE
            );
            if ($sCount)
                $fil_total = $this->xmod->get_records()[0]->total_count;
        }

        $order_arr = array(
            "ac.tracking_no",
            "au.display_name",
            "ar.refund_reference",
            "ar.date_refunded",
        );
        $result = $this->xmod->find_with_joins(
            "app_refund ar", //table
            $user_join, //joins
            $cond, //condi
            "DATE_FORMAT(ar.date_refunded,'%b %d, %Y %h:%i %p') as date_refunded,DATE_FORMAT(ar.date_created,'%b %d, %Y %h:%i %p') as request_date,ar.internal_notes as refund_notes,au.user_id, au.display_name,au.email_address,au.mobile_number,ar.id as ref_id,ar.*,ac.*,ap.promo_code,ap.discount_rate as promo_discount_rate,ap.discount_type,ap.discount_max", //fields
            "", //row
            $order_arr[$order_column]. " " .$order_type, //order_by
            "", //group 
            $limit, //limit
            $offset //offset
        );
    //    echo $this->xmod->CI->db->last_query();
    //    die();
        if ($result){
            $refundData = array();
            $refundData = $this->xmod->get_records();

            foreach($refundData as $rdata){
                $discount_type = $rdata->discount_type;
                $shipping_fee = $rdata->shipping_fee;
                $valuation_charge = $rdata->valuation_charge;
                $promo_discount = $rdata->promo_discount_rate;
        
                $discount_amount = 0;
                if ($discount_type == 'php'){
                    $discount_amount = $promo_discount;
                }
                else if ($discount_type == 'percent'){
                    $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                }
        
                $amount_due = $shipping_fee + $valuation_charge - $discount_amount;

                $rowData = array();
                if($type == 'success'){ 
                    $rowData[] = $rdata->refund_reference;
                }
                $rowData[] = array("id" => $rdata->id, "tracking_no" => $rdata->tracking_no);
                $rowData[] = array("user_id" => $rdata->user_id, "display_name" => ucwords($rdata->display_name),"email" => $rdata->email);
                if($type == 'success'){ 
                    $rowData[] = number_format($rdata->refund_amount,2);
                    $rowData[] = "<span class='badge ".(($rdata->mode_of_refund == 'paypal') ? "bg-primary" : "bg-danger") ."'>".ucfirst($rdata->mode_of_refund)."</span>";
                }
                else{
                    $rowData[] = number_format($amount_due,2);
                    $rowData[] = "<span class='badge ".(($rdata->payment_type == 'paypal') ? "bg-primary" : "bg-danger") ."'>".ucfirst($rdata->payment_type)."</span>";
                }
                $rowData[] = $rdata->refund_reason ."<br/>".long_text($rdata->refund_remarks);
                $rowData[] = $rdata->status_message;
                if($type == 'success'){ 
                    $rowData[] = ($rdata->date_refunded) ? date("F d, Y h:i A", strtotime($rdata->date_refunded)) : "";
                }
                $rowData[] = long_text($rdata->refund_notes);
                $rowData[] = long_text($rdata->public_remarks);
                $rowData[] = ($rdata->request_date) ? date("F d, Y h:i A", strtotime($rdata->request_date)) : "";
                $rowData[] = array(
                            "id" => $rdata->ref_id,
                            "user_id" => $rdata->user_id,
                            "tracking_no" => $rdata->tracking_no ,
                            "amount" => number_format($amount_due,2),
                            "cart_id" => $rdata->cart_id,
                            "internal_notes" => $rdata->refund_notes,
                            "public_remarks" => $rdata->public_remarks,

                        );
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

    public function update_refund()
    {
        $user_id            =   $this->input->post("user_id");
        $tracking_no        =   $this->input->post("tracking_no");
        $refund_amount      =   $this->input->post('refund_amount');
        $refund_reference   =   $this->input->post('refund_reference');
        $mode_of_refund     =   $this->input->post('mode_of_refund');
        $date_refunded      =   $this->input->post('date_refunded');
        $status_code        =   $this->input->post('status_code');
        $internal_notes     =   $this->input->post('internal_notes');
        $public_remarks     =   $this->input->post('public_remarks');
        $id                 =   $this->input->post('id');
        $cart_id            =   $this->input->post('cart_id');
        $user_name = $this->user_data->first_name . " " . $this->user_data->last_name;
        if($id)
        {
            $data = array(
                "internal_notes" => $internal_notes,
                "public_remarks" => $public_remarks,
                "status_code" => $status_code,
                "refund_reference" => null,
                "mode_of_refund" => null,
                "date_refunded" => null,
            );

            $cartData = array(
                "flg_refund" => 0,
                "refund_date" => null,
                "refund_id" => null,
                "ops_status" => null,
                "ops_status_code" => 0,
                "ops_status_date" => null
            );

            if($status_code == 'O')
                $data['status_message'] = "On Process";
            elseif($status_code == 'S')
            {
                $data['status_message'] = "Successfully Refunded";
                $cartData['refund_date'] = $date_refunded;
                $cartData['refund_id'] = $refund_reference;
                $cartData['flg_refund'] = 1;
                $cartData['ops_status'] = "Refunded";
                $cartData['ops_status_code'] = 100;
                $cartData['ops_status_date'] = date("Y-m-d H:i:s");

                $data["date_refunded"] = $date_refunded;
                $data["refund_reference"] = $refund_reference;
                $data["refund_amount"] = $refund_amount;
                $data["mode_of_refund"] = $mode_of_refund;
            }
            elseif($status_code == 'R')
                $data['status_message'] = "Request Rejected";
            
            if($this->xmod->save("app_refund",$data,array("id" => $id)))
            {
                if($this->xmod->save("app_carts",$cartData,array("id" => $cart_id))){
                    if($status_code == 'S')
                    {
                        $title          = "BAExpress Refund";
                        $message        =  "Your refund request for tracking no of ".$tracking_no." has been approved. Click here to view details.";
                        $send_to        = "user";

                        $notif_data = new stdClass();
                        $notif_data->title = $title;
                        $notif_data->message = $message;
                        $notif_data->notif_date = date('Y-m-d H:i:s');
                        $notif_data->type = "shipment";
                        $notif_data->item_id = $cart_id; 
                        $notif_data->user_id = $user_id;
                        
                        if($this->xmod->save("app_users_notifications",$notif_data))
                        {
                            if ($title AND $message){
                                if (util_send_notif($title,$message,$send_to, $user_id,"order","",$cart_id)){
                                    redirect(base_url("reports/refunds"));
                                }
                                else
                                    echo "failed";
                            }
                            else
                                echo "failed";
                        }
                    }
                    redirect(base_url("reports/refunds"));
                }
            }
            else{
                return false;
            }
        }

    }

    public function refund_export()
    {   
        $download = $this->input->get('download');
        $type = $this->input->get('type');
        if($download)
        {
            $headers = array(
                "Refund ID",
                "Tracking No",
                "Sender",
                "Email Address",
                "Refund Amount",
                "Mode of Refund",
                "Reason",
                "Remarks",
                "Status Message",
                "Internal Notes",
                "Display Remarks",
                "Refund Date"
            );
            $cond = "1 = 1 ";
            if($type == 'pending')
                $cond .= " AND ac.flg_paid = 1 AND ar.status_code = 'O'";
            elseif($type == 'success')
                $cond .= " AND ac.flg_paid = 1 AND ar.status_code = 'S'";
            elseif($type == 'rejected')
                $cond .= " AND ac.flg_paid = 1 AND ar.status_code = 'R'";

            $xquery = "SELECT au.user_id, au.display_name, au.email_address,ar.internal_notes as refund_notes, ar.*,ac.*,ap.promo_code,ap.discount_rate as promo_discount_rate,ap.discount_type,ap.discount_max FROM app_refund ar LEFT JOIN app_carts ac ON ar.cart_id = ac.id LEFT JOIN app_users au ON au.user_id = ar.user_id LEFT JOIN app_promos ap ON ap.id = ac.promo_id WHERE $cond";
            $result = $this->xmod->CI->db->query($xquery);
            $xdata = $result->result();
            $content = array();
            foreach($xdata as $rdata){
                $discount_type = $rdata->discount_type;
                $shipping_fee = $rdata->shipping_fee;
                $valuation_charge = $rdata->valuation_charge;
                $promo_discount = $rdata->promo_discount_rate;
        
                $discount_amount = 0;
                if ($discount_type == 'php'){
                    $discount_amount = $promo_discount;
                }
                else if ($discount_type == 'percent'){
                    $discount_amount = ceil($shipping_fee * ($promo_discount / 100));
                }
        
                $amount_due = $shipping_fee + $valuation_charge - $discount_amount;
                $content[] = array(
                    $rdata->refund_reference,
                    $rdata->tracking_no,
                    ucfirst($rdata->display_name),
                    $rdata->email_address, 
                    number_format($amount_due,2),
                    ucfirst($rdata->mode_of_refund), 
                    $rdata->refund_reason, 
                    $rdata->refund_remarks, 
                    $rdata->status_message, 
                    $rdata->refund_notes, 
                    $rdata->public_remarks, 
                    date('M d, Y H:i:s',strtotime($rdata->refund_date)),
                );
            }

            $content[] = array("","","","","","","","","","");
            $filename = "BAExpress_refund_report_".date('mdyHis');
            $this->download_excel($headers, $content,$filename,true);
            die();
        }
    }

    function postpaid_balance()
    {
        $this->data['page_title'] = "Postpaid Balance";
        $query = "SELECT bal.available_credit as balance, au.display_name,bal.credit_total,bal.debit_total,bal.credit_limit, au.user_id, au.email_address FROM `v_postpaid_available_credit` bal LEFT JOIN app_users au ON bal.user_id = au.user_id LEFT JOIN app_postpaid_transaction apt ON bal.user_id = apt.user_id WHERE bal.available_credit != 0 GROUP BY apt.user_id ORDER BY bal.available_credit DESC";
        $balance_list = $this->xmod->CI->db->query($query);
        $this->data['balance_list'] = $balance_list->result_array();
        $this->section['content'] = $this->load->view('postpaid_users_balance',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }


    public function postpaid($type="all")
    {
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $from = $this->input->get("from");
        $to = $this->input->get("to");

        $from = ($from) ? $from : date("Y-m-d",strtotime("-1 month"));
        $to = ($to) ? $to : date("Y-m-d");

        $postpaid_list = array();
        $wData  = array();
        $creditArr  = array();
        $debitArr   = array();

        $week_monday =  date('Y-m-d',strtotime("this week monday"));
        $week_sunday =  date('Y-m-d',strtotime("this week sunday"));


        if($type == 'due'){
            $from = date('Y-m-d',strtotime("last week last week last week monday"));
            $to = date('Y-m-d',strtotime("last week last week last week sunday"));
            $cond = "DATE_FORMAT(trans_date,'%Y-%m-%d') <= '$to'";
        }    
        else{
            $cond = "DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '$from' AND '$to'";
        }

        $balance = $this->xmod->get_count("app_postpaid_transaction","type = 'debit' AND DATE_FORMAT(trans_date,'%Y-%m-%d') < '$from'","SUM(amount)");
        
        $postpaid_trans = array();
        $postpaid_trans = $this->xmod->get("app_postpaid_transaction",$cond," YEARWEEK(trans_date,1) AS trans_date, type, SUM(amount) AS amount","YEARWEEK(trans_date, 1),type");
   
        $pDataArray = array();
        if($postpaid_trans)
        foreach ($postpaid_trans as $xtrans) {
            $pDataArray[$xtrans->trans_date][] = $xtrans;
        }
        $postpaid_trans = $pDataArray;

        $xData_trans = array();
        
        $count = 0;
        $current_bal = 0;
        foreach ($postpaid_trans as $trans_date => $transac) {
            $count++;
            $credit = 0;
            $debit = 0;
            $amount_due = 0;
            foreach ($transac as $trans) {
                
                if($trans_date == $trans->trans_date)
                {
                    if($trans->type == 'debit'){
                        $debit += $trans->amount;
                    }
                    else{
                        $credit += $trans->amount;
                    }
                }
                
            }
            $trans_Obj =  new stdClass();
            $xcredit = 0;
            $pending = 0;
            if($count == 1){
                $current_bal = $balance; //beginning bal
                $amount_due = $debit;
                $xcredit = $credit;
                $pending = ($current_bal + $amount_due) - $credit;
            }
            else{
                $amount_due = $current_bal + $debit;
                $xcredit = $credit;
                $pending = $amount_due - $xcredit;
            }
            
            $trans_Obj->amount_due = $amount_due;
            $trans_Obj->amount_paid = $xcredit;
            $trans_Obj->amount_pending =  $pending;
            $current_bal = $pending;

            $y = substr($trans_date, 0, 4);
            $w = substr($trans_date, 4, 2);
            $start_week = new Datetime();
            $year = date('Y',strtotime($y));
            $start_week->setISODate($y,$w);
            $date_start = $start_week->format('Y-m-d');
            $date_end = date('Y-m-d',strtotime($date_start. "+6 days"));
            $week_range = date('M d',strtotime($date_start))." - ".date('M d, Y',strtotime($date_end));
            $trans_Obj->date_start = $date_start;
            $trans_Obj->date_end = $date_end;
            $trans_Obj->week_range = $week_range;
            $xData_trans[$trans_date] =  $trans_Obj;
        }
        $running_bal = end($xData_trans);

        if($running_bal)
            $remaining_bal = $running_bal->amount_pending;
        else 
            $remaining_bal = 0;

        $postpaid_list = $xData_trans;

        $this->data['balance'] = $balance; 
        $this->data['remaining'] = $remaining_bal; 
        $this->data['from'] = $from;
        $this->data['to'] = $to;
       
        $this->data['start'] = $week_monday;
        $this->data['end'] = $week_sunday;
        $this->data['type'] = $type;
        $this->data['postpaid_list'] = $postpaid_list;
        $this->section['content'] = $this->load->view('postpaid/landing',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }

    public function get_user_postpaid()
    {
        $ajax = $this->input->get("ajax");
        $week_monday = $this->input->get("start");
        $week_sunday = $this->input->get("end");
      
        $xcond = "DATE_FORMAT(apt.trans_date,'%Y-%m-%d') BETWEEN  '$week_monday' AND '$week_sunday'";
        $xquery = "SELECT apt.user_id,au.display_name,au.email_address,
                  (SELECT SUM(debt.amount) FROM app_postpaid_transaction debt WHERE debt.type = 'debit' AND debt.user_id = apt.user_id AND DATE_FORMAT(debt.trans_date,'%Y-%m-%d') BETWEEN '$week_monday' AND '$week_sunday') as amount_pending,
                  (SELECT SUM(cred.amount) FROM app_postpaid_transaction cred WHERE cred.type = 'credit' AND cred.user_id = apt.user_id AND DATE_FORMAT(cred.trans_date,'%Y-%m-%d') BETWEEN '$week_monday' AND '$week_sunday') as amount_paid 
                   FROM app_postpaid_transaction apt LEFT JOIN app_users au ON au.user_id = apt.user_id WHERE $xcond GROUP BY user_id";
        $q = $this->xmod->CI->db->query($xquery);
       
        $xData = array();
        if ($q->num_rows() > 0){
            $listData = $q->result();
            $nData = array();
            foreach($listData as $list)
            {
                $obj = $list;
                $amount_pending = 0;
                $obj->amount_due =  (($list->amount_pending) ? $list->amount_pending : 0);
                $obj->amount_paid = (($list->amount_paid) ? $list->amount_paid : 0);
                $amount_pending = ($list->amount_pending - $list->amount_paid);
                $obj->amount_pending =  number_format($amount_pending,2);
                $nData[] =  $obj;
            }
        }
        $xData = $nData;
        if($ajax)
        {
            echo json_encode($xData);
            die();
        }
       return $listData;
    }
     // PAYMENT TYPE DRAGONPAY GRAPH

     public function dragonpay($type= "summary", $disp_type = "daily")
     {
        $this->section['ci_css'][] = "vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css";
        $this->section['ci_js'][] = "vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";
        $this->section['ci_js'][] = "js/chartjs/dragonpay.js";
        $this->section['ci_js'][] = "vendor/chart.js/dist/Chart.extension.js";
        $this->section['ci_js'][] = "js/argon.js";
        $rangestart = (($this->input->get('range_start')) ? $this->input->get('range_start') : date('Y-m-d',strtotime("-1 month")));
        $rangeend = (($this->input->get('range_end')) ? $this->input->get('range_end') : date('Y-m-d'));

        $disp_type = ($this->input->get('disp_type') ? $this->input->get('disp_type') : $disp_type);
        $this->data['type'] = $type;
        $this->data['disp_type'] =  $disp_type;
        $this->data['rangestart'] = $rangestart;
        $this->data['rangeend'] = $rangeend;
        $this->section['content'] = $this->load->view('graph/dragonpay',$this->data,true);
        $this->myview->view($this->section,$this->template);
     }

     public function dp_graphData()
     {
        $from   =   $this->input->get('from');
        $to     =   $this->input->get('to');
        $type   =   $this->input->get('disp_type');
        $xtype  =   $this->input->get('xtype');

        $today = date('Y-m-d');
        if ($type == 'daily'){
            $format = "%Y-%m-%d";
            $from = "'".date("Y-m-d",strtotime($from))."'";
            $to = "'".date("Y-m-d",strtotime($to))."'";
            $xform = "DATE_FORMAT(last_update,'$format')";
        }
        else if ($type == 'monthly'){
            $format = "%Y-%m";
            $from = "'".date("Y-m-d",strtotime($from))."'";
            $to = "'".date("Y-m-d",strtotime($to))."'";
            $xform = "DATE_FORMAT(last_update,'$format')";
        }
        else if ($type == 'weekly'){
            $format = "%Y-%m-%d";
            $from = "YEARWEEK('".date("Y-m-d",strtotime($from))."',3)";
            $to = "YEARWEEK('".date("Y-m-d",strtotime($to))."',3)";
            $xform = "YEARWEEK(last_update,3)";
        }

        $revx = $this->xmod->CI->db->query("SELECT SUM(amount) as amount, $xform as xdate FROM app_payment_dragonpay WHERE status = 'S' AND $xform BETWEEN $from AND $to GROUP BY $xform");
        $res = $revx->result_array();
        $labels = array_column($res,"xdate");
        $amount = array_column($res,"amount");
      
        $xlabel = array();
        if ($type == 'monthly')
        {
            foreach ($labels as $lab) {
                $xlabel[] = date("Y M",strtotime($lab));
            }
        }else if ($type == 'daily'){
            foreach ($labels as $lab) {
                $xlabel[] = date("M d",strtotime($lab));
            }
        }
        else if ($type == 'weekly'){
            foreach ($labels as $lab) {
                $y = substr($lab, 0, 4);
                $w = substr($lab, 4, 2);
                $start_week = new Datetime();
                $year = date('Y',strtotime($y));
                $start_week->setISODate($y,$w);
                $dayFormat = $start_week->format("F d");
                $weekEnd = date('d',strtotime($dayFormat. "+6 days"));
                $xlabel[] = $dayFormat . " - " .$weekEnd;
            }
        }

        $chartRev = new stdClass();
        $chartRev->labels = $xlabel;
        $chartRev->datasets = $amount;
        echo json_encode($chartRev);
        die();
     }
}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */

// Your refund request for tracking no of BAPP123124213112 has been approved. Click here to view details. 