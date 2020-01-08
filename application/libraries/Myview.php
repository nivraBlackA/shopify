<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Myview{

	var $data = "";
	var $theme_dir = "templates";
	var $use_theme = "default";
	var $theme = "";

	function __construct()
	{
		$obj =& get_instance();
		$this->theme_dir = $obj->config->item('theme_dir');
		$this->use_theme = $obj->config->item('theme');
		$this->theme = $this->theme_dir."/".$this->use_theme."/";
	}

	function set_theme($theme){
		$this->theme = $this->theme_dir."/".$theme."/";
	}

	function view($data="",$template="default")
	{
		$obj =& get_instance();

		$data['img_dir'] = $obj->config->item('base_url').$this->theme."images/";
		$data['js_dir'] = $obj->config->item('base_url').$this->theme."js/";
		$data['css_dir'] = $obj->config->item('base_url').$this->theme."css/";
		$obj->load->view($this->theme.$template, $data);
	}

	function get_theme(){
		return $this->theme;
	}
	
	function get_theme_dir()
	{
		$obj =& get_instance();
		return $obj->config->item('base_url').$this->theme;
	}
	
	function get_template_path()
	{
		$apppath = APPPATH;
		return str_replace("system/application/",$this->theme_dir."/",$apppath);
	}
	
	function get_theme_path()
	{
		$apppath = APPPATH;
		return str_replace("system/application/",$this->theme_dir."/".$this->use_theme."/",$apppath);
	}

}
/* End of file myview.php */
/* Location: ./system/application/libraries/Myview.php */