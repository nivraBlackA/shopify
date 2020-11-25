<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Login_model extends MX_Model
{
	// public $db;
	public function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
		// $this->CI->db = $this->CI->load->database('login', TRUE);
	}

	function save($table = '', $data = '', $conditions = '')
	{
		if (!empty($data) || !empty($table)) {
			if (!empty($conditions)) {
				foreach ($conditions as $field_name => $field_value) {
					if (!empty($field_value))
						$this->CI->db->where($field_name, $field_value);
				}

				$q = $this->CI->db->update($table, $data);
			} else {
				if ($this->CI->db->insert($table, $data))
					$q = $this->insert_id = $this->CI->db->insert_id();
			}
			return $q;
		}
	}

	function find_by_field($table = '', $conditions = '', $fields = '', $row = FALSE, $order_by = '', $limit = 0, $group_by = '')
	{
		if (!empty($table)) {
			if (!empty($fields)) {
				$this->CI->db->select($fields);
				$this->CI->db->from($table);
				$table = '';
			}
			if (!empty($conditions)) {
				foreach ($conditions as $field_name => $field_value) {
					$this->CI->db->where($field_name, $field_value);
				}
			}
			if (!empty($order_by)) {
				if (is_array($order_by)) {
					foreach ($order_by as $order_field => $order_behavior) {
						$this->CI->db->order_by($order_field, $order_behavior);
					}
				} else
					$this->CI->db->order_by($order_by);
			}
			if (!empty($group_by))
				$this->CI->db->group_by($group_by);
			if (!empty($limit))
				$this->CI->db->limit($limit);
			$q = $this->CI->db->get($table);

			if ($q->num_rows() > 0) {
				if ($row)
					$this->records = $q->row();
				else {
					$this->records = $q->result();
					$this->records_array = $q->result_array();
				}

				$this->fields = $q->list_fields();
				$this->num_rows = $q->num_rows();
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
}