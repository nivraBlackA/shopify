<?php
if (!defined('BASEPATH')) exit('No direct script access allowed...');

class Login extends MX_Controller
{

	var $theme = 'default';
	var $template = 'default';

	function __construct()
	{
		parent::__construct();
		$this->myview->set_theme($this->theme);
		$this->load->model('login_model', 'Login');
	}

	function index()
	{
		$this->_login();
	}

	function _login($error = "")
	{
		$this->template = "login";
		$this->data['msg_class'] = $this->session->flashdata("system_message_class");
		$this->data['msg'] = empty($error) ? $this->session->flashdata("system_message") : $error;
		$this->section['content'] = $this->load->view('login', $this->data, true);
		$this->myview->view($this->section, $this->template);
	}

	function verify()
	{
		$rules =
			array(
				array(
					'field' => 'username',
					'label' => 'Email Address',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required'
				)
			);
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run())
			$this->_verify();
		else
			$this->_login(validation_errors());
	}

	function _verify()
	{
		$email_address 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		$redirect 	= $this->input->post('redirect');

		$conditions = array('email' => $email_address);

		$this->Login->find_by_field("users", $conditions, "", TRUE);

		if ($this->Login->get_num_rows() > 0) {
			$user = $this->Login->get_records();
			$hash_password = $user->passwd;
			unset($user->passwd);
			if (password_verify($password, $hash_password)) {

				$this->session->set_userdata('user_data', $user);
				$this->session->set_userdata('user_logged', TRUE);

				//log the last login
				$this->Login->save("users", array('last_login' => date("Y-m-d H:i:s")), array('user_id' => $user->user_id));

				redirect(base_url("home"));
			} else {
				$this->_login("Username and password does not match or do not exist.");
			}
		} else {
			$this->_login("Username and password does not match or do not exist.");
		}
	}

	function logout()
	{
		$this->session->unset_userdata('user_db');
		$this->session->unset_userdata('user_data');
		$this->session->unset_userdata('user_logged', FALSE);
		$this->session->unset_userdata('user_mod_access', FALSE);
		$this->session->unset_userdata('user_mod_header', FALSE);
		$this->session->set_userdata('user_data', "");
		$this->session->set_userdata('user_logged', FALSE);
		$this->session->set_userdata('user_mod_access', FALSE);
		$this->session->set_userdata('user_mod_header', FALSE);
		//$this->session->destroy();
		redirect('login');
	}
}

/* End of file login.php */
/* Location: ./system/application/modules/login/controllers/login.php */