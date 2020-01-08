<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Order extends MX_Controller {

    //var $template = 'default';
    var $template = 'default';
    function __construct(){
        parent::__construct();
        $this->load->model('order_model','xmod');
        $this->data['ci_head'] = '';
        $this->section['ci_js'] = array();
        $this->section['ci_css'] = array();
    }

    function item($type,$item_id)
    {
        $table_name = "";
        $info = "";
        if($type == 'prs'){
            $table_name = "rider_pickup rs";
            $info = "Pickup";
        }
        if($type == 'drs'){
            $table_name = "rider_delivery rs";
            $info = "Delivery";
        }
        if($type == 'rrs'){
            $table_name = "rider_return rs";
            $info = "Return ";

        }
    
        $user_join = array(
            "rider_courier rc" => array("left" => "rc.courier_id = rs.courier_id"),
        );

        $result = $this->xmod->find_with_joins(
            $table_name, //table
            $user_join, //joins
            "rs.item_id = $item_id", //condi
            "rs.*,rc.courier_name,rc.mobile,rc.company", //fields
            TRUE //row
        );

        if ($result)
            $order = $this->xmod->get_records();
        else
            $error = 1;
      
        $order->info = $info;
        $this->data['item'] = $order;
        $this->data['type'] = $type;
        $this->section['content'] = $this->load->view('item',$this->data,true);
        $this->myview->view($this->section,$this->template);
    }
}

/* End of file home.php */
/* Location: ./system/application/modules/login/controllers/home.php */