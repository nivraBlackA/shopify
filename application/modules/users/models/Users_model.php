<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Users_model extends MX_Model {

    public function __construct() {
        parent::__construct();
        $CI =& get_instance();
    }

}
?>