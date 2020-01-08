<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ops_model extends MX_Model {
	public $ops_db;
	public function __construct() {
		parent::__construct();
		$this->CI->ops_db = $this->CI->load->database('ops_db',TRUE);
	}

    function get_options($table,$code,$name,$condition="",$value="", $order_by = "",$group_by  = "")
	{
        $fields = "$code, $name";
        if($fields)
            $this->CI->ops_db->select($fields);
        if($condition)
            $this->CI->ops_db->where($condition);
        
        if($order_by)
            $this->CI->ops_db->order_by($order_by);

        if($group_by)
            $this->CI->ops_db->group_by($group_by);
        
        $q = $this->CI->ops_db->get($table);
        $dataOpt = "";
       
        if ($q->num_rows() > 0){
            $dataOpt .= "<option value=''>-- Select All-- </option>";
            foreach ($q->result() as $dataVal){
                $selected   = ($dataVal->$code == $value) ? "selected" : "";
				$dataOpt    .= "<option value='".$dataVal->$code."' $selected>".$dataVal->$name."</option>";
            }
            return $dataOpt;
        }
        return false;
    }
    
    public function get($table, $condition = "",$field = "",$group_by = "",$order_by = "",$limit = "",$offset=""){
        $this->CI->ops_db->select($field);
        if ($condition)
            $this->CI->ops_db->where($condition);
        if ($group_by)
            $this->CI->ops_db->group_by($group_by);
        if ($order_by)
            $this->CI->ops_db->order_by($order_by);
        if ($limit)
			$this->CI->ops_db->limit($limit);
		if($offset)
			$this->CI->ops_db->limit($limit,$offset);

        $this->CI->ops_db->from($table);
        $query = $this->CI->ops_db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }
}